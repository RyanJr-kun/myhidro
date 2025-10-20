<?php

namespace App\Http\Controllers\sistem_control;

use Illuminate\Http\Request;
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
        $query = PumpHistory::query()->latest();

        // Terapkan filter jika ada
        if ($filterPump) {
            $query->where('pump_name', $filterPump);
        }

        if ($filterStartDate && $filterEndDate) {
            $query->whereBetween('created_at', [$filterStartDate . ' 00:00:00', $filterEndDate . ' 23:59:59']);
        }

        $histories = $query->paginate(10)->withQueryString();

        $pumpOptions = ['Pompa Tandon', 'Pompa Kolam', 'Pompa Pembuangan'];

        return view('content.kontrol.pump-history', compact('histories', 'pumpOptions'));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new PumpHistoryExport($request->all()), 'riwayat-pompa.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = PumpHistory::query()->latest();

        if ($request->filled('pump_name')) {
            $query->where('pump_name', $request->pump_name);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $histories = $query->get();

        $pdf = Pdf::loadView('content.kontrol.pump-history-pdf', compact('histories'));
        return $pdf->download('riwayat-pompa.pdf');
    }
    


}
