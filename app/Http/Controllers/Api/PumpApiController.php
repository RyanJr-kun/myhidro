<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\sistem_control\PumpHistory;
use App\Models\sistem_control\PumpSchedule;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\sistem_control\Pump;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PumpApiController extends Controller
{
    // 1. Pengganti Page/GetPumpStatus.php
    public function getManualStatusForWeb(Request $request)
    {
        // Validasi login sudah dihandle oleh middleware 'auth:sanctum'
        return $this->getPumpStatus();
    }

    // 2. Pengganti Hidroponik/ApiKontrolPompaArduino.php
    public function getManualStatusForArduino(Request $request)
    {
        // Validasi token sudah dihandle oleh middleware VerifyDeviceToken
        return $this->getPumpStatus();
    }

    // Fungsi helper (digunakan oleh 2 method di atas)
    private function getPumpStatus()
    {
        $defaultPumps = ['pompa_hidroponik', 'pompa_ikan', 'pompa_tanaman'];

        $pumps = Pump::where('aktif', 1)
                    ->whereIn('nama_pompa', $defaultPumps)
                    ->get()
                    ->keyBy('nama_pompa'); // Jadikan 'nama_pompa' sebagai key

        $pumpData = [];
        foreach ($defaultPumps as $pump) {
            $pumpData[$pump] = [
                'status' => $pumps->has($pump) ? (int)$pumps[$pump]->status : 0
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
        $request->validate([
            'pump' => 'required|string|in:pompa_hidroponik,pompa_ikan,pompa_tanaman',
        ]);

        $pumpName = $request->pump;
        $userId = Auth::id();

        try {
            DB::beginTransaction();

            $pump = Pump::firstOrCreate(
                ['nama_pompa' => $pumpName],
                ['status' => 0, 'aktif' => 1, 'diperbarui_oleh' => $userId]
            );

            $newStatus = !$pump->status;

            $pump->status = $newStatus;
            $pump->diperbarui_oleh = $userId;
            $pump->updated_at = now();
            $pump->aktif = 1;
            $pump->save();

            // 3. Log ke riwayat
            $actionType = $newStatus ? 'manual_nyala' : 'manual_mati';
            $keterangan = 'Pompa ' . ($newStatus ? 'dinyalakan' : 'dimatikan') . ' secara manual dari web';

            PumpHistory::create([
                'nama_pompa' => $pumpName,
                'aksi' => $actionType,
                'id_pengguna' => $userId,
                'keterangan' => $keterangan,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => 'Status pompa berhasil diubah'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSchedulesForArduino(Request $request)
    {
        $schedules = PumpSchedule::where('status', 'aktif')
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu', 'Setiap Hari')")
            ->orderBy('waktu_mulai')
            ->get(['nama_pompa', 'waktu_mulai', 'waktu_selesai', 'hari', 'status']);

        return response()->json([
            'success' => true,
            'data' => $schedules,
            'total' => $schedules->count(),
            'timestamp' => now()
        ]);
    }
}
