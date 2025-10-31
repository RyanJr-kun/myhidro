@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Pages')

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="nav-align-top">
        <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-md-0 gap-2">
          <li class="nav-item">
            <a class="nav-link" href="{{ url('pages/account-settings') }}"><i
                class="icon-base bx bx-user icon-sm me-1_5"></i> Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="javascript:void(0);"><i class="icon-base bx bx-bell icon-sm me-1_5"></i>
              Notifikasi</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('pages/account-settings-security') }}"><i
                class="icon-base bx bx-shield icon-sm me-1_5"></i> Keamanan</a>
          </li>
        </ul>
      </div>
      <div class="card">
        <!-- Notifications -->
        <div class="card-body">
          <h5 class="mb-1">Semua Notifikasi</h5>
          <span class="card-subtitle">Halaman ini menampilkan semua notifikasi dari yang <span
              class="notificationRequest"><span class="text-primary">Terbaru.</span></span></span>
          <div class="error"></div>
        </div>
      </div>
    </div>
  </div>
@endsection
