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
        return view('content.kontrol.pump-schedule');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PumpSchedule $pumpSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PumpSchedule $pumpSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PumpSchedule $pumpSchedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PumpSchedule $pumpSchedule)
    {
        //
    }
}
