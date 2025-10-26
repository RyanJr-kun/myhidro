<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use App\Models\sistem_control\PumpHistory;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PumpHistoryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents, WithCustomStartCell, WithTitle
{
    protected $filters;
    private $totalDuration;
    private $totalEnergyKWh;
    private $totalCost;
    private $query;
    private $dataCount;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
        $this->query = $this->buildQuery();
    }

    private function buildQuery()
    {
        $query = PumpHistory::query()->latest('start_time');

        if (!empty($this->filters['pump_name'])) {
            $query->where('pump_name', $this->filters['pump_name']);
        }

        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $query->whereBetween('start_time', [$this->filters['start_date'] . ' 00:00:00', $this->filters['end_date'] . ' 23:59:59']);
        }

        return $query;
    }

    public function collection()
    {
        $data = $this->query->get();
        $this->dataCount = $data->count();
        $this->calculateTotals();
        return $data;
    }

    private function calculateTotals()
    {
        // Ambil total durasi dari query
        $this->totalDuration = (clone $this->query)->sum('duration_in_seconds');

        // Logika perhitungan energi dan biaya (dipindahkan dari controller)
        $wattNodeMCU = 1.5;
        $wattRelayModuleStandby = 3;
        $wattPompa = 5;
        $wattRelayCoilActive = 0.5;

        $wattKonstan = $wattNodeMCU + $wattRelayModuleStandby;
        $wattVariabel = $wattPompa + $wattRelayCoilActive;

        $totalConstantEnergyWh = 0;
        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
             $start = new Carbon($this->filters['start_date']);
             $end = new Carbon($this->filters['end_date']);
             $days = $start->diffInDays($end) + 1;
             $totalConstantEnergyWh = ($wattKonstan * 24) * $days;
        }

        $totalDurationJam = $this->totalDuration / 3600;
        $totalPumpEnergyWh = $totalDurationJam * $wattVariabel;
        $totalEnergyWh = $totalPumpEnergyWh + $totalConstantEnergyWh;

        $this->totalEnergyKWh = $totalEnergyWh / 1000;
        $this->totalCost = $this->totalEnergyKWh * 1444; // Tarif per kWh
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Pompa',
            'Pemicu',
            'Waktu Mulai',
            'Waktu Selesai',
            'Durasi (detik)',
        ];
    }

    public function map($history): array
    {
        return [
            $history->id,
            $history->pump_name,
            ucfirst($history->triggered_by),
            $history->start_time ? $history->start_time->format('d-m-Y H:i:s') : 'N/A',
            $history->end_time ? $history->end_time->format('d-m-Y H:i:s') : 'Sedang Berjalan',
            $history->duration_in_seconds,
        ];
    }


    public function title(): string
    {
        return 'Laporan Riwayat Pompa';
    }

    public function startCell(): string
    {
        return 'A3'; // Data tabel dimulai dari A3
    }

    /**
     * Menerapkan style pada sheet Excel.
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk judul utama
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Style untuk sub-judul (rentang tanggal)
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A2')->getFont()->setItalic(true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Style untuk header tabel (sekarang di baris 3)
        return [
            3 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD3D3D3']],
            ],
        ];
    }

    /**
     * Mendaftarkan event untuk memanipulasi sheet.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Menulis Judul Utama
                $sheet->setCellValue('A1', 'Laporan Riwayat Pompa');

                // Menulis Sub-judul (Filter Tanggal)
                $dateRange = 'Semua Waktu';
                if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
                    $start = Carbon::parse($this->filters['start_date'])->format('d M Y');
                    $end = Carbon::parse($this->filters['end_date'])->format('d M Y');
                    $dateRange = "Periode: {$start} - {$end}";
                }
                $sheet->setCellValue('A2', $dateRange);

                // Menambahkan baris ringkasan di bawah tabel
                $lastRow = $this->dataCount + 4; // 3 (start row) + data count + 1 (untuk spasi)

                $sheet->setCellValue("E{$lastRow}", 'Total Durasi:');
                $sheet->setCellValue("F{$lastRow}", $this->totalDuration . ' detik');

                $sheet->setCellValue("E" . ($lastRow + 1), 'Total Energi:');
                $sheet->setCellValue("F" . ($lastRow + 1), number_format($this->totalEnergyKWh, 3, ',', '.') . ' kWh');

                $sheet->setCellValue("E" . ($lastRow + 2), 'Estimasi Biaya:');
                $sheet->setCellValue("F" . ($lastRow + 2), 'Rp ' . number_format($this->totalCost, 2, ',', '.'));

                // Memberi style pada ringkasan
                $summaryRange = "E{$lastRow}:E" . ($lastRow + 2);
                $sheet->getStyle($summaryRange)->getFont()->setBold(true);
                $sheet->getStyle($summaryRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            },
        ];
    }
}
