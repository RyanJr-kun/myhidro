<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\sistem_control\Pump;
use App\Models\sistem_control\PumpSchedule;
use App\Models\sistem_control\PumpHistory;
use Carbon\Carbon;

class PumpApiController extends Controller
{
    /**
     *
     * Mengirim status pompa yang seharusnya ke NodeMCU.
     */
    public function getDesiredStates(Request $request)
    {
        $now = Carbon::now();
        $currentDay = (string)$now->dayOfWeek;
        $currentTime = $now->format('H:i:s');
        $manualStates = Pump::all()->pluck('status', 'name')->toArray();

        $automaticStates = [];
        $activeSchedules = PumpSchedule::where('status', true)
                            ->where(function ($query) use ($currentDay) {
                                $query->whereJsonContains('days', $currentDay)
                                      ->orWhereJsonContains('days', 'everyday');
                            })->get();

        foreach ($activeSchedules as $schedule) {
            $startTime = $schedule->start_time;
            $endTime = Carbon::parse($schedule->start_time)
                             ->addMinutes($schedule->duration_minutes)
                             ->format('H:i:s');

            // Jika waktu sekarang berada di dalam rentang jadwal
            if ($currentTime >= $startTime && $currentTime <= $endTime) {
                $automaticStates[$schedule->pump_name] = true; // Harusnya ON
            }
        }

        $allPumpNames = ['pompa hidroponik', 'pompa kolam', 'pompa pembuangan'];
        $finalStates = [];

        foreach ($allPumpNames as $pumpName) {
            $isManualOn = $manualStates[$pumpName] ?? false;
            $isAutomaticOn = $automaticStates[$pumpName] ?? false;

            $finalStates[$pumpName] = $isManualOn || $isAutomaticOn;
        }

        return response()->json([
            'success' => true,
            'desired_states' => $finalStates,
            'timestamp' => $now
        ]);
    }

    public function logArduinoPumpAction(Request $request)
    {
        $validated = $request->validate([
            'pump_name' => 'required|string',
            'status'    => 'required|in:ON,OFF', // NodeMCU lapor pakai string ON/OFF
        ]);

        try {
            $pumpName = $validated['pump_name'];

            if ($validated['status'] == 'ON') { // Laporan Otomatis ON dari NodeMCU
                // Cek dulu apakah ada sesi Manual yang sedang berjalan
                $isManualRunning = PumpHistory::where('pump_name', $pumpName)
                                              ->where('triggered_by', 'Manual')
                                              ->whereNull('end_time')
                                              ->exists(); // Cukup cek keberadaannya

                if (!$isManualRunning) {
                    // Hanya buat sesi Otomatis jika TIDAK ada sesi Manual yang aktif
                    PumpHistory::create([
                        'pump_name'    => $pumpName,
                        'triggered_by' => 'Otomatis',
                        'end_time' => null,
                        'duration_in_seconds' => null
                    ]);
                } else {

                }
            } else {
                $openHistories = PumpHistory::where('pump_name', $pumpName)
                                            ->whereNull('end_time')
                                            ->get();

                // Loop dan tutup semua entri yang terbuka
                foreach ($openHistories as $history) {
                    $history->end_time = now();
                    if ($history->start_time) {
                        $history->duration_in_seconds = $history->start_time->diffInSeconds($history->end_time);
                    } else {
                        $history->duration_in_seconds = 0; // Atau null
                    }
                    $history->save();
                }
            }

            return response()->json(['success' => true, 'message' => 'Log received']);

        } catch (\Exception $e) {
          return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getManualStatusForWeb(Request $request)
    {
        $manualStates = Pump::all()->pluck('status', 'name')->toArray();
        $allPumpNames = ['pompa hidroponik', 'pompa kolam', 'pompa pembuangan']; // Sesuaikan

        $pumpData = [];
        foreach ($allPumpNames as $pump) {
            $pumpData[$pump] = [
                'status' => (int)($manualStates[$pump] ?? 0)
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $pumpData,
            'timestamp' => now()
        ]);
    }

    public function togglePumpStatus(Request $request)
    {
        $validated = $request->validate([
            'pump_name' => 'required|string|in:pompa hidroponik,pompa kolam,pompa pembuangan',
        ]);

        $pumpName = $validated['pump_name'];
        $userId = Auth::id();
        $pump = Pump::where('name', $pumpName)->first();
        if (!$pump) {
            return response()->json(['success' => false, 'message' => 'Pompa tidak ditemukan.'], 404);
        }

        // Jika frontend hanya mengirim "toggle", maka kita balik statusnya:
        $newStatus = !$pump->status;
        // --- Selesai Logika Toggle ---

        $pump->status = $newStatus;
        $pump->save();

        // --- LOGIKA PENCATATAN RIWAYAT (VERSI PRIORITAS MANUAL) ---
        if ($newStatus == true) { // Baru dinyalakan (Manual ON)
            // 1. Tutup paksa sesi Otomatis yang mungkin sedang berjalan
            $runningAutomatic = PumpHistory::where('pump_name', $pumpName)
                                           ->where('triggered_by', 'Otomatis')
                                           ->whereNull('end_time')
                                           ->latest('start_time')
                                           ->first();
            if ($runningAutomatic) {
                $runningAutomatic->end_time = now();
                if ($runningAutomatic->start_time) {
                    $runningAutomatic->duration_in_seconds = $runningAutomatic->start_time->diffInSeconds($runningAutomatic->end_time);
                }
                $runningAutomatic->save();
                // Opsional: Log bahwa manual override terjadi
                // \Log::info("Manual ON override closed Automatic session for pump: " . $pumpName);
            }

            // 2. Buat sesi Manual baru
            PumpHistory::create([
                'pump_name'    => $pumpName,
                'triggered_by' => 'Manual',
                'end_time' => null,
                'duration_in_seconds' => null
            ]);
        } else { // Baru dimatikan (Manual OFF)
            // (Tetap gunakan logika "tutup semua" dari sebelumnya)
            // Cari SEMUA entri riwayat untuk pompa ini yang belum selesai
            $openHistories = PumpHistory::where('pump_name', $pumpName)
                                        ->whereNull('end_time')
                                        ->get();

            // Loop dan tutup semua entri yang terbuka
            foreach ($openHistories as $history) {
                $history->end_time = now();
                if ($history->start_time) {
                    $history->duration_in_seconds = $history->start_time->diffInSeconds($history->end_time);
                } else {
                    $history->duration_in_seconds = 0; // Atau null
                }
                $history->save();
            }
        }
        // --- LOGIKA RIWAYAT SELESAI ---

        $statusText = $newStatus ? 'dinyalakan' : 'dimatikan';
        return response()->json([
            'success' => true,
            'message' => "Status {$pumpName} berhasil diubah menjadi {$statusText}.",
            'new_status' => $newStatus
        ]);
    }

    public function getSchedulesForArduino(Request $request)
    {
        try {
            // Ambil semua jadwal yang aktif dari database
            $schedules = PumpSchedule::where('is_active', true)
                ->orderBy('pump_name') // Urutkan agar mudah dibaca (opsional)
                ->orderBy('start_time')
                ->get();

            $formattedSchedules = [];
            foreach ($schedules as $schedule) {
                $daysString = implode(',', $schedule->days ?? []);

                $formattedSchedules[] = [
                    'nama_pompa'        => $schedule->pump_name, // Misal: "pompa hidroponik"
                    'waktu_mulai'       => Carbon::parse($schedule->start_time)->format('H:i'), // Format HH:MM
                    'durasi_menit'      => $schedule->duration_minutes,
                    'hari'              => $daysString,
                ];
            }

            return response()->json([
                'success' => true,
                'data'    => $formattedSchedules,
                'total'   => count($formattedSchedules),
                'timestamp' => now()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data jadwal: ' . $e->getMessage()
            ], 500);
        }
    }
}
