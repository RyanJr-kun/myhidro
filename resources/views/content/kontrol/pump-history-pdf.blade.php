<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Pompa</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Laporan Riwayat Pompa</h1>
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
                <td>{{ $history->end_time ? $history->end_time->format('d-m-Y H:i:s') : 'Selesai' }}</td>
                <td>{{ $history->duration_in_seconds ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
