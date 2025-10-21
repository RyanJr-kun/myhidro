@extends('layouts/contentNavbarLayout')

@section('title', 'Riwayat Pompa')

@section('vendor-style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('vendor-script')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection

@section('content')
<!-- Card Filter -->
<div class="card mb-4">
  <h5 class="card-header">Filter Riwayat</h5>
  <div class="card-body">
    <form action="{{ route('sistem-pump-history') }}" method="GET" id="filterForm">
      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="pump_name" class="form-label">Jenis Pompa</label>
          <select name="pump_name" id="pump_name" class="form-select">
            <option value="">Semua Pompa</option>
            @foreach($pumpOptions as $pump)
            <option value="{{ $pump }}" {{ request('pump_name') == $pump ? 'selected' : '' }}>{{ $pump }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4 mb-3">
          <label for="date_range" class="form-label">Rentang Waktu</label>
          <input type="text" class="form-control" id="date_range" placeholder="YYYY-MM-DD to YYYY-MM-DD" />
          <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
          <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-4 d-flex align-items-end mb-3">
          <button type="submit" class="btn btn-primary me-2">Filter</button>
          <a href="{{ route('sistem-pump-history') }}" class="btn btn-secondary">Reset</a>
        </div>
      </div>
    </form>
  </div>
</div>

@if($days > 0)
<div class="card mb-4">
  <h5 class="card-header">Estimasi Pemakaian Listrik</h5>
  <div class="card-body">
    <p>Estimasi untuk rentang waktu **{{ $days }} hari** (sesuai filter tanggal Anda):</p>
    <div class="row">
      <div class="col-md-6">
        <h6 class="text-primary">Total Pemakaian Energi</h6>
        <h3>{{ number_format($totalEnergyKWh, 2, ',', '.') }} <small>kWh</small></h3>
      </div>
      <div class="col-md-6">
        <h6 class="text-success">Estimasi Total Biaya</h6>
        <h3>Rp {{ number_format($totalCost, 0, ',', '.') }}</h3>
        <small>(biaya Rp 1.444 per kWh)</small>
      </div>
    </div>
  </div>
</div>
@else
<div class="alert alert-info mb-4" role="alert">
  <h6 class="alert-heading mb-1">Info Laporan Listrik</h6>
  <span>Silakan pilih **rentang waktu** pada filter untuk melihat estimasi pemakaian listrik.</span>
</div>
@endif

<!-- Card Tabel Riwayat -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Tabel Riwayat</h5>
    <div>
      <button id="exportExcelBtn" class="btn btn-success me-2"><i class='bx bxs-file-export me-1'></i>Excel</button>
      <button id="exportPdfBtn" class="btn btn-danger"><i class='bx bxs-file-pdf me-1'></i>PDF</button>
    </div>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Pompa</th>
          <th>Pemicu</th>
          <th>Waktu Mulai</th>
          <th>Waktu Selesai</th>
          <th>Durasi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse ($histories as $history)
        <tr>
          <td>{{ $loop->iteration + $histories->firstItem() - 1 }}</td>
          <td>{{ $history->pump_name }}</td>
          <td>{{ ucfirst($history->triggered_by) }}</td>

          {{-- Ini adalah perbaikan untuk error Anda --}}
          <td>
            {{ $history->start_time ? $history->start_time->format('d M Y, H:i:s') : 'N/A' }}
          </td>

          <td>
            {{-- Tampilkan end_time HANYA jika sudah ada --}}
            @if($history->end_time)
              {{ $history->end_time->format('d M Y, H:i:s') }}
            @else
              <span class="badge bg-label-success">Sedang Berjalan</span>
            @endif
          </td>

          <td>
            {{-- Tampilkan durasi HANYA jika sudah selesai --}}
            @if($history->duration_in_seconds)
              {{ gmdate('H:i:s', $history->duration_in_seconds) }}
              <small class="text-muted">({{ $history->duration_in_seconds }} dtk)</small>
            @else
              -
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center">Tidak ada data riwayat.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">
    {{ $histories->links() }}
  </div>
</div>
@endsection

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi Flatpickr untuk rentang tanggal
    flatpickr("#date_range", {
      mode: 'range',
      dateFormat: 'Y-m-d',
      defaultDate: [document.getElementById('start_date').value, document.getElementById('end_date').value],
      onChange: function(selectedDates, dateStr, instance) {
        if (selectedDates.length === 2) {
          document.getElementById('start_date').value = instance.formatDate(selectedDates[0], "Y-m-d");
          document.getElementById('end_date').value = instance.formatDate(selectedDates[1], "Y-m-d");
        }
      }
    });

    // Fungsi untuk export
    function exportData(format) {
      const form = document.getElementById('filterForm');
      const currentAction = form.action;
      const exportUrl = format === 'excel' ? "{{ route('kontrol-riwayat-pompa-excel') }}" : "{{ route('kontrol-riwayat-pompa-pdf') }}";

      form.action = exportUrl;
      form.submit();
      form.action = currentAction; // Kembalikan action form seperti semula
    }

    document.getElementById('exportExcelBtn').addEventListener('click', () => exportData('excel'));
    document.getElementById('exportPdfBtn').addEventListener('click', () => exportData('pdf'));
  });
</script>
@endsection
