@extends('layouts/contentNavbarLayout')

@section('title', 'Riwayat Pompa')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
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
          <th>Status</th>
          <th>Pemicu</th>
          <th>Waktu</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse ($histories as $history)
        <tr>
          <td>{{ $loop->iteration + $histories->firstItem() - 1 }}</td>
          <td>{{ $history->pump_name }}</td>
          <td>
            @if($history->status == 'ON')
            <span class="badge bg-label-success">ON</span>
            @else
            <span class="badge bg-label-secondary">OFF</span>
            @endif
          </td>
          <td>{{ ucfirst($history->triggered_by) }}</td>
          <td>{{ $history->created_at->format('d M Y, H:i:s') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center">Tidak ada data riwayat.</td>
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
