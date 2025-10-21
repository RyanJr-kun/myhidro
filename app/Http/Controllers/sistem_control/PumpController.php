<?php

namespace App\Http\Controllers\sistem_control;

use Illuminate\Http\Request;
use App\Models\sistem_control\Pump;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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

            $statusText = $pump->status ? 'dinyalakan' : 'dimatikan';
            return response()->json([
                'success' => true,
                'message' => "{$pump->name} berhasil {$statusText}."
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui status pompa di database.'], 500);
        }
    }
}
