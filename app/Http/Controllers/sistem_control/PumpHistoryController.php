<?php

namespace App\Http\Controllers\sistem_control;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PumpHistoryExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\sistem_control\PumpHistory;

class PumpHistoryController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data filter dari request
        $filterPump = $request->input('pump_name');
        $filterStartDate = $request->input('start_date');
        $filterEndDate = $request->input('end_date');

        // Query dasar
        $query = PumpHistory::query()->latest('start_time');

        // Terapkan filter jika ada
        if ($filterPump) {
            $query->where('pump_name', $filterPump);
        }
        if ($filterStartDate && $filterEndDate) {
            $query->whereBetween('start_time', [$filterStartDate . ' 00:00:00', $filterEndDate . ' 23:59:59']);
        }

        $wattNodeMCU = 1.5;
        $wattRelayModuleStandby = 3;

        $wattPompa = 5;
        $wattRelayCoilActive = 0.5;

        $wattKonstan = $wattNodeMCU + $wattRelayModuleStandby;

        $totalConstantEnergyWh = 0;
        $days = 0;
        if ($filterStartDate && $filterEndDate) {
             $start = new Carbon($filterStartDate);
             $end = new Carbon($filterEndDate);
             $days = $start->diffInDays($end) + 1;
             $totalConstantEnergyWh = ($wattKonstan * 24) * $days;
        }

        $wattVariabel = $wattPompa + $wattRelayCoilActive;

        $queryForCalc = clone $query;
        $totalDurationDetik = (clone $queryForCalc)->sum('duration_in_seconds');
        $totalDurationJam = $totalDurationDetik / 3600;
        $totalPumpEnergyWh = $totalDurationJam * $wattVariabel;
        $totalEnergyWh = $totalPumpEnergyWh + $totalConstantEnergyWh;

        $totalEnergyKWh = $totalEnergyWh / 1000;
        $costPerKWh = 1444;
        $totalCost = $totalEnergyKWh * $costPerKWh;

        $histories = $query->paginate(5)->withQueryString();
        $pumpOptions = ['pompa hidroponik', 'pompa kolam', 'pompa pembuangan'];

        return view('content.kontrol.pump-history',compact(
            'histories',
            'pumpOptions',
            'totalEnergyKWh',
            'totalCost',
            'days'
        ));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new PumpHistoryExport($request->all()), 'riwayat-pompa.xlsx');
    }

    public function exportPdf(Request $request)
    {
        // Ambil data filter dari request
        $filterPump = $request->input('pump_name');
        $filterStartDate = $request->input('start_date');
        $filterEndDate = $request->input('end_date');

        // Query dasar
        $query = PumpHistory::query()->latest('start_time');

        // Terapkan filter jika ada
        if ($filterPump) {
            $query->where('pump_name', $filterPump);
        }
        if ($filterStartDate && $filterEndDate) {
            $query->whereBetween('start_time', [$filterStartDate . ' 00:00:00', $filterEndDate . ' 23:59:59']);
        }

        // --- Logika Kalkulasi Biaya (disalin dari method index) ---
        $wattNodeMCU = 1.5;
        $wattRelayModuleStandby = 3;
        $wattPompa = 5;
        $wattRelayCoilActive = 0.5;
        $wattKonstan = $wattNodeMCU + $wattRelayModuleStandby;
        $wattVariabel = $wattPompa + $wattRelayCoilActive;

        $totalConstantEnergyWh = 0;
        $days = 0;
        if ($filterStartDate && $filterEndDate) {
             $start = new Carbon($filterStartDate);
             $end = new Carbon($filterEndDate);
             $days = $start->diffInDays($end) + 1;
             $totalConstantEnergyWh = ($wattKonstan * 24) * $days;
        }

        $totalDurationDetik = (clone $query)->sum('duration_in_seconds');
        $totalDurationJam = $totalDurationDetik / 3600;
        $totalPumpEnergyWh = $totalDurationJam * $wattVariabel;
        $totalEnergyWh = $totalPumpEnergyWh + $totalConstantEnergyWh;

        $totalEnergyKWh = $totalEnergyWh / 1000;
        $totalCost = $totalEnergyKWh * 1444; // Tarif per kWh

        $histories = $query->get();

        $pdf = Pdf::loadView('content.kontrol.pump-history-pdf', compact('histories', 'totalEnergyKWh', 'totalCost', 'days', 'filterStartDate', 'filterEndDate'));
        return $pdf->download('riwayat-pompa.pdf');
    }



}
