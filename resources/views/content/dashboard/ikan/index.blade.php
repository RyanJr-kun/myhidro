@extends('layouts/contentNavbarLayout')

@section('title', 'Manajemen Ikan')

@section('content')

<div class="nav-align-top">
  <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-md-0 gap-2">
    <li class="nav-item">
      <a class="nav-link" href="{{ url('/') }}"><i class="icon-base bx bx-dashboard icon-sm me-1_5"></i>
        Dashboard</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('dashboard-analytics-tanaman') }}"><i
          class="icon-base bx bx-leaf icon-sm me-1_5"></i> Tanaman</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" href="javascript:void(0);"><i
          class="icon-base bx bx-fish-alt icon-sm me-1_5"></i> Ikan</a>
    </li>
  </ul>
</div>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Daftar Ikan</h5>
    {{-- Tombol Tambah - Mengarah ke route 'create' --}}
    {{-- Pastikan Anda membuat route 'create' di web.php atau ganti dengan trigger modal --}}
    <a href="{{ route('ikan.create') }}" class="btn btn-primary">
      <i class='bx bx-plus me-1'></i> Tambah Ikan
    </a>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Ikan</th>
          <th>Jml Bibit</th>
          <th>Tgl Tebar</th>
          <th>Estimasi Panen</th>
          <th>Progress Panen</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse ($ikans as $index => $ikan)
          <tr>
            <td>{{ $ikans->firstItem() + $index }}</td>
            <td><strong>{{ $ikan->nama_ikan }}</strong></td>
            <td>{{ $ikan->jumlah_benih }}</td>
            <td>{{ $ikan->tanggal_tebar ? $ikan->tanggal_tebar->format('d M Y') : '-' }}</td>
            <td>
              @if ($ikan->estimasi_panen_hari)
                {{ $ikan->estimasi_panen_hari }} hari
                @if ($ikan->sisa_hari_panen !== null && !$ikan->tanggal_panen_aktual)
                  <small class="text-muted d-block">({{ $ikan->sisa_hari_panen }} hari lagi)</small>
                @endif
              @else
                -
              @endif
            </td>
            <td>
              @if ($ikan->tanggal_tebar && $ikan->estimasi_panen_hari)
                <div class="progress" style="height: 6px;">
                  <div class="progress-bar {{ $ikan->progress_panen >= 100 ? 'bg-success' : 'bg-primary' }}"
                    role="progressbar" style="width: {{ $ikan->progress_panen }}%;"
                    aria-valuenow="{{ $ikan->progress_panen }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small>{{ $ikan->progress_panen }}%</small>
              @else
                -
              @endif
            </td>
            <td>
              {{-- Tampilkan status --}}
              <span
                class="badge rounded-pill bg-label-{{ $ikan->status == 'dipanen' ? 'success' : ($ikan->status == 'gagal' ? 'danger' : 'info') }} me-1">
                {{ ucfirst($ikan->status ?? 'ditebar') }}
              </span>
            </td>
            <td>
              {{-- Tombol Edit & Hapus --}}
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i
                    class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="{{ route('ikan.edit', $ikan->nama_ikan) }}"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                  <form action="{{ route('ikan.destroy', $ikan->nama_ikan) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item text-danger">
                      <i class="bx bx-trash me-1"></i> Hapus
                    </button>
                  </form>
                </div>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center">Belum ada data ikan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{-- Tampilkan Link Pagination --}}
  <div class="card-footer">
    {{ $ikans->links() }}
  </div>
</div>
@endsection

@section('page-script')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const Toast = Swal.mixin({
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      });

      function showToast(icon, title) {
        Toast.fire({
          icon: icon,
          title: title
        });
      }

      @if (session('success'))
        showToast('success', '{{ session('success') }}');
      @endif

      @if (session('error'))
        showToast('error', '{{ session('error') }}');
      @endif

      // Konfirmasi hapus dengan SweetAlert
      const deleteForms = document.querySelectorAll('.delete-form');
      deleteForms.forEach(form => {
        form.addEventListener('submit', function(event) {
          event.preventDefault(); // Mencegah form dari submit langsung

          Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan dapat mengembalikan data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
          }).then((result) => {
            if (result.isConfirmed) {
              form.submit(); // Jika dikonfirmasi, submit form
            }
          });
        });
      });
    });
  </script>
@endsection
