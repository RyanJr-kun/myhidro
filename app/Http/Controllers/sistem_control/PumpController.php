<?php

namespace App\Http\Controllers\sistem_control;

use Illuminate\Http\Request;
use App\Models\sistem_control\Pump;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\sistem_control\PumpHistory;

class PumpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pumps = Pump::all();
        return view('content.kontrol.pump-control', compact('pumps'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request, Pump $pump)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Data tidak valid.'], 400);
        }

        try {
            $pump->status = $request->status;
            $pump->save();

            if ($pump->status == true) {
                PumpHistory::create([
                    'pump_name' => $pump->name,
                    'triggered_by' => 'Manual',
                    'end_time' => null,
                    'duration_in_seconds' => null
                ]);
            } else {
                $history = PumpHistory::where('pump_name', $pump->name)
                                    ->whereNull('end_time')
                                    ->latest('start_time')
                                    ->first();
                if ($history) {
                    $history->end_time = now();
                    $history->duration_in_seconds = $history->start_time->diffInSeconds($history->end_time);
                    $history->save();
                }
            }

            $statusText = $pump->status ? 'dinyalakan' : 'dimatikan';
            return response()->json([
                'success' => true,
                'message' => "Status {$pump->name} berhasil diubah menjadi {$statusText}."
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui status pompa di database: ' . $e->getMessage()], 500);
        }
    }
}
