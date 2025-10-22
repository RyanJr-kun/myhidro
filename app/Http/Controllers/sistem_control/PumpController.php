<?php

namespace App\Http\Controllers\sistem_control;

use Illuminate\Http\Request;
use App\Models\sistem_control\Pump;
use App\Models\sistem_control\PumpSchedule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\sistem_control\PumpHistory;

class PumpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pumps = Pump::all();

        $now = Carbon::now();
        $currentDay = (string)$now->dayOfWeek;
        $currentTime = $now->format('H:i:s');

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

            if ($currentTime >= $startTime && $currentTime <= $endTime) {
                $automaticStates[$schedule->pump_name] = true;
            }
        }
        return view('content.kontrol.pump-control', compact('pumps', 'automaticStates'));
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
