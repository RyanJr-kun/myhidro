<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Pompa</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
        .summary-section {
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #eeeeee;
            background-color: #f9f9f9;
        }
        .summary-section h3 {
            margin-top: 0;
            text-align: center;
        }
        .summary-section table {
            width: 50%;
            margin: 0 auto;
        }
        .summary-section td {
            border: none;
            padding: 5px;
        }
    </style>
</head>
<body>
    <h1>Laporan Riwayat Pompa</h1>
    @if($filterStartDate && $filterEndDate)
        <p style="text-align: center; margin-top: -10px; margin-bottom: 20px;">Periode: {{ \Carbon\Carbon::parse($filterStartDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($filterEndDate)->format('d M Y') }}</p>
    @endif
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pompa</th>
                <th>Pemicu</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Durasi (detik)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($histories as $history)
            <tr>
                <td>{{ $history->id }}</td>
                <td>{{ $history->pump_name }}</td>
                <td>{{ ucfirst($history->triggered_by) }}</td>
                <td>{{ $history->start_time ? $history->start_time->format('d-m-Y H:i:s') : 'N/A' }}</td>
                <td>{{ $history->end_time ? $history->end_time->format('d-m-Y H:i:s') : 'Sedang Berjalan' }}</td>
                <td>{{ $history->duration_in_seconds ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if ($days > 0)
    <div class="summary-section">
        <h3>Estimasi Pemakaian Listrik ({{ $days }} hari)</h3>
        <table>
            <tr>
                <td><strong>Total Pemakaian Energi:</strong></td>
                <td>{{ number_format($totalEnergyKWh, 3, ',', '.') }} kWh</td>
            </tr>
            <tr>
                <td><strong>Estimasi Total Biaya:</strong></td>
                <td>Rp {{ number_format($totalCost, 2, ',', '.') }}</td>
            </tr>
        </table>
        <p style="text-align: center; font-size: 10px; color: #888; margin-top: 10px;">(Perhitungan berdasarkan tarif Rp 1.444 per kWh)</p>
    </div>
    @endif

</body>
</html>
