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
        $activeSchedules = PumpSchedule::where('is_active', true)
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

        $allPumpNames = ['Pompa Tandon', 'Pompa Kolam', 'Pompa Buang'];
        $finalStates = [];

        foreach ($allPumpNames as $pumpName) {
            $isManualOn = $manualStates[$pumpName] ?? false;
            $isAutomaticOn = $automaticStates[$pumpName] ?? false;

            // Status final adalah true (ON) jika salah satunya true
            $finalStates[$pumpName] = $isManualOn || $isAutomaticOn;
        }

        return response()->json([
            'success' => true,
            'desired_states' => $finalStates, // Misal: {'Pompa Tandon': true, 'Pompa Kolam': false}
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

            if ($validated['status'] == 'ON') {
                PumpHistory::create([
                    'pump_name'    => $pumpName,
                    'triggered_by' => 'Otomatis',
                    'end_time' => null,
                    'duration_in_seconds' => null
                ]);
            } else {
                // Pompa baru saja Dimatikan
                $history = PumpHistory::where('pump_name', $pumpName)
                                    ->where('triggered_by', 'Otomatis')
                                    ->whereNull('end_time') // Cari log 'ON' yang belum 'OFF'
                                    ->latest('start_time')
                                    ->first();

                if ($history) {
                    $history->end_time = now();
                    $history->duration_in_seconds = $history->start_time->diffInSeconds($history->end_time);
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
        $allPumpNames = ['Pompa Tandon', 'Pompa Kolam', 'Pompa Buang']; // Sesuaikan

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
            // Asumsi frontend mengirim 'pump_name'
            'pump_name' => 'required|string|in:Pompa Tandon,Pompa Kolam,Pompa Buang',
            // Anda mungkin juga ingin mengirim 'status' (true/false) dari frontend
            // agar cocok dengan 'PumpController@updateStatus'
            // 'status' => 'required|boolean'
        ]);

        $pumpName = $validated['pump_name'];
        $userId = Auth::id(); // Ambil ID user yang login

        $pump = Pump::where('name', $pumpName)->first();
        if (!$pump) {
            return response()->json(['success' => false, 'message' => 'Pompa tidak ditemukan.'], 404);
        }

        // --- Logika Toggle ---
        // Jika frontend mengirim status spesifik:
        // $newStatus = $validated['status'];

        // Jika frontend hanya mengirim "toggle", maka kita balik statusnya:
        $newStatus = !$pump->status;
        // --- Selesai Logika Toggle ---

        $pump->status = $newStatus;
        $pump->save();

        // --- LOGIKA PENCATATAN RIWAYAT (MASALAH 2A) ---
        if ($newStatus == true) { // Baru dinyalakan
            PumpHistory::create([
                'pump_name'    => $pumpName,
                'triggered_by' => 'Manual',
                'end_time' => null,
                'duration_in_seconds' => null
            ]);
        } else { // Baru dimatikan
            $history = PumpHistory::where('pump_name', $pumpName)
                                ->where('triggered_by', 'Manual')
                                ->whereNull('end_time')
                                ->latest('start_time')
                                ->first();

            if ($history) {
                $history->end_time = now();
                $history->duration_in_seconds = $history->start_time->diffInSeconds($history->end_time);
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
}
