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
                <th>Status</th>
                <th>Pemicu</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($histories as $history)
            <tr>
                <td>{{ $history->id }}</td>
                <td>{{ $history->pump_name }}</td>
                <td>{{ $history->status }}</td>
                <td>{{ ucfirst($history->triggered_by) }}</td>
                <td>{{ $history->created_at->format('d-m-Y H:i:s') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
