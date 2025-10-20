<?php

namespace App\Exports;


use App\Models\sistem_control\PumpHistory;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PumpHistoryExport implements FromQuery, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = PumpHistory::query()->latest();

        if (!empty($this->filters['pump_name'])) {
            $query->where('pump_name', $this->filters['pump_name']);
        }

        if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
            $query->whereBetween('created_at', [$this->filters['start_date'] . ' 00:00:00', $this->filters['end_date'] . ' 23:59:59']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Pompa',
            'Status',
            'Pemicu',
            'Waktu',
        ];
    }

    public function map($history): array
    {
        return [
            $history->id,
            $history->pump_name,
            $history->status,
            ucfirst($history->triggered_by),
            $history->created_at->format('d-m-Y H:i:s'),
        ];
    }
}
