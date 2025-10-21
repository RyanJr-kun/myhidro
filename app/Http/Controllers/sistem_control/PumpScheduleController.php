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
            'is_active' => 'nullable|boolean',
        ]);

        $validatedData['is_active'] = $request->has('is_active');
        PumpSchedule::create($validatedData);

        return response()->json(['success' => 'Jadwal pompa berhasil disimpan!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PumpSchedule $pumpSchedule)
    {
        //
    }
  }
