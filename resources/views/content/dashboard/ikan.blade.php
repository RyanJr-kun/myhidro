@extends('layouts/contentNavbarLayout')

@section('title', 'Manajemen Tanaman')

@section('content')

  <div class="row">
    <div class="col-12">
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
        <h5 class="card-header">Daftar ikan</h5>
        <div class="card-body">
          <p>Di sini Anda dapat menampilkan tabel atau kartu yang berisi data ikan di kolam Anda.</p>
          {{-- TODO: Tambahkan tabel atau konten lain untuk manajemen ikan --}}
        </div>
      </div>
    </div>
  </div>
@endsection
