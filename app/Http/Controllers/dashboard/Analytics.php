<?php

namespace App\Http\Controllers\dashboard;

use Carbon\Carbon;
use App\Models\Ikan;
use App\Models\Tanaman;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\sistem_control\PumpHistory;

class Analytics extends Controller
{
  public function index()
  {
    // 1. Ambil data mentah (panggil fungsi dari jawaban_sebelumnya)
    $reportData = $this->getMonthlyPumpUsage();

    // 2. Definisikan konstanta perhitungan Anda
    $tarifPerKwh = 1445; // (Rp) GANTI SESUAI TARIF LISTRIK ANDA
    $kwhStandbyBulanan = 4.32; // (6W * 24jam * 30hari) / 1000
    $biayaStandbyBulanan = round($kwhStandbyBulanan * $tarifPerKwh, 0);

    // 3. Siapkan data untuk Chart
    $chartLabels = [];
    $chartBiayaStandby = [];
    $chartBiayaPompa = [];
    // $tableData = []; // Anda bisa tetap membuat tableData jika perlu

    // Proses data yang didapat dari database
    foreach ($reportData as $data) {
      $biayaPompa = round($data['kwh_pompa'] * $tarifPerKwh, 0);

      // Data untuk Chart (Grafik)
      $chartLabels[] = $data['bulan_tahun'];         // Label (e.g., "Okt 2025")
      $chartBiayaStandby[] = $biayaStandbyBulanan; // Data series 1
      $chartBiayaPompa[] = $biayaPompa;             // Data series 2
    }

    // --- AWAL LOGIKA GROWTH CHART ---

    $today = Carbon::now();

    // 1. Hitung Total & Rata-rata Pertumbuhan Tanaman
    // Menggunakan 'jumlah_benih' sesuai tabel Anda
    $totalTanaman = Tanaman::sum('jumlah_benih');

    // Ambil tanaman yang SEDANG TUMBUH (belum dipanen)
    $tanamanData = Tanaman::whereNotNull('tanggal_tanam')
                            ->whereNotNull('estimasi_panen_hari')
                            ->where('estimasi_panen_hari', '>', 0) // Pastikan ada estimasi hari
                            ->whereNull('tanggal_panen_aktual')  // <-- PERBAIKAN PENTING
                            ->get();

    $growthPercentagesTanaman = [];
    foreach ($tanamanData as $tanaman) {
        $tanam = Carbon::parse($tanaman->tanggal_tanam);
        $totalDuration = (int)$tanaman->estimasi_panen_hari; // <-- PERBAIKAN PENTING
        $elapsedDuration = $tanam->diffInDays($today);

        if ($totalDuration > 0) {
            $percentage = ($elapsedDuration / $totalDuration) * 100;
            $growthPercentagesTanaman[] = min($percentage, 100); // Batasi di 100%
        }
    }
    // Hitung rata-rata, hindari pembagian dengan nol
    $avgGrowthTanaman = count($growthPercentagesTanaman) > 0
                        ? round(array_sum($growthPercentagesTanaman) / count($growthPercentagesTanaman))
                        : 0; // Default 0% jika tidak ada data

    // 2. Hitung Total & Rata-rata Pertumbuhan Ikan
    // Menggunakan 'jumlah_bibit' sesuai tabel Anda
    $totalIkan = Ikan::sum('jumlah_bibit');

    // Ambil ikan yang SEDANG TUMBUH (belum dipanen)
    $ikanData = Ikan::whereNotNull('tanggal_tebar') // <-- PERBAIKAN (kolom 'tanggal_tebar')
                    ->whereNotNull('estimasi_panen_hari')
                    ->where('estimasi_panen_hari', '>', 0)
                    ->whereNull('tanggal_panen_aktual') // <-- PERBAIKAN PENTING
                    ->get();

    $growthPercentagesIkan = [];
    foreach ($ikanData as $ikan) {
        $tebar = Carbon::parse($ikan->tanggal_tebar); // <-- PERBAIKAN (kolom 'tanggal_tebar')
        $totalDuration = (int)$ikan->estimasi_panen_hari; // <-- PERBAIKAN PENTING
        $elapsedDuration = $tebar->diffInDays($today);

        if ($totalDuration > 0) {
            $percentage = ($elapsedDuration / $totalDuration) * 100;
            $growthPercentagesIkan[] = min($percentage, 100);
        }
    }
    // Hitung rata-rata, hindari pembagian dengan nol
    $avgGrowthIkan = count($growthPercentagesIkan) > 0
                     ? round(array_sum($growthPercentagesIkan) / count($growthPercentagesIkan))
                     : 0;


    // 4. Kirim semua data yang sudah diproses ke view
    return view('content.dashboard.dashboards-analytics', [
      // 'tableData' => $tableData, // Opsional jika Anda masih butuh tabel
      'chartLabels' => $chartLabels,
      'chartBiayaStandby' => $chartBiayaStandby,
      'chartBiayaPompa' => $chartBiayaPompa,
      'totalTanaman' => $totalTanaman,
      'totalIkan' => $totalIkan,
      'avgGrowthTanaman' => $avgGrowthTanaman,
      'avgGrowthIkan' => $avgGrowthIkan
    ]);
  }

  /**
   * Mengambil data penggunaan pompa bulanan untuk 6 bulan terakhir.
   * (Fungsi dari jawaban sebelumnya)
   */
  public function getMonthlyPumpUsage()
  {
    $endDate = Carbon::now();
    // Ambil 6 bulan, termasuk bulan ini.
    $startDate = Carbon::now()->subMonths(5)->startOfMonth();

    $pumpUsage = PumpHistory::select(
      DB::raw('YEAR(start_time) as tahun'),
      DB::raw('MONTH(start_time) as bulan'),
      DB::raw('SUM(duration_in_seconds) as total_durasi_detik')
    )
      ->whereBetween('start_time', [$startDate, $endDate])
      ->groupBy('tahun', 'bulan')
      ->orderBy('tahun', 'asc')
      ->orderBy('bulan', 'asc')
      ->get();

    // Buat koleksi 6 bulan terakhir sebagai template
    $monthlyReport = collect([]);
    for ($i = 5; $i >= 0; $i--) {
        $date = Carbon::now()->subMonths($i);
        $monthlyReport->put($date->format('Y-m'), [
            'tahun' => $date->year,
            'bulan' => $date->month,
            'total_durasi_detik' => 0 // Default 0
        ]);
    }

    // Isi data dari database
    foreach ($pumpUsage as $usage) {
        $key = $usage->tahun . '-' . str_pad($usage->bulan, 2, '0', STR_PAD_LEFT);
        if ($monthlyReport->has($key)) {
            $monthlyReport[$key] = [
                'tahun' => $usage->tahun,
                'bulan' => $usage->bulan,
                'total_durasi_detik' => $usage->total_durasi_detik
            ];
        }
    }

    // Proses data untuk laporan
    $report = $monthlyReport->map(function ($item) {
      $totalJam = $item['total_durasi_detik'] / 3600;
      $kwhPompa = (15 / 1000) * $totalJam; // 15 Watt daya pompa

      return [
        'bulan_tahun' => Carbon::createFromDate($item['tahun'], $item['bulan'], 1)->format('M Y'),
        'total_jam_nyala_pompa' => round($totalJam, 2),
        'kwh_pompa' => round($kwhPompa, 4),
        'total_durasi_detik' => $item['total_durasi_detik']
      ];
    })->values();

    return $report;
  }
}
