<?php

namespace App\Http\Controllers\sistem_control;

use App\Models\sistem_control\PumpSchedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PumpScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = PumpSchedule::all();
        return view('content.kontrol.pump-schedule', compact('schedules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'pump_name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'duration_minutes' => 'required|integer|min:1',
            'days' => 'required|array',
            'days.*' => 'string',
            'status' => 'nullable|boolean',
        ]);

        PumpSchedule::create($validatedData);
        return response()->json(['success' => 'Jadwal pompa berhasil disimpan!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PumpSchedule $pumpSchedule)
    {
        try {
            $pumpSchedule->delete();
            return response()->json(['success' => 'Jadwal berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus jadwal.'], 500);
        }
    }

    public function toggleStatus(PumpSchedule $pumpSchedule)
    {
        try {
            $pumpSchedule->status = !$pumpSchedule->status;
            $pumpSchedule->save();

            $statusText = $pumpSchedule->status ? 'diaktifkan' : 'dinonaktifkan';
            return response()->json([
                'success' => "Jadwal berhasil {$statusText}.",
                'new_status' => $pumpSchedule->status
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengubah status jadwal.'], 500);
        }
    }
  }
