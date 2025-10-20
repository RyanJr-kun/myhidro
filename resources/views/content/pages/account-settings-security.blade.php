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
            <a class="nav-link" href="{{ url('pages/account-settings-notifications') }}"><i
                class="icon-base bx bx-bell icon-sm me-1_5"></i> Notifikasi</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="javascript:void(0);"><i class="icon-base bx bx-shield icon-sm me-1_5"></i>
              Keamanan</a>
          </li>
        </ul>
      </div>
      <div class="card">
        <div class="card-header">
          <h5 class="mb-1">Keamanan Akun</h5>
          <p class="my-0 card-subtitle">perbarui kata sandi anda disini.</p>
        </div>
        @if (session('status'))
          <div class="alert alert-success mx-4" role="alert">
            {{ session('status') }}
          </div>
        @elseif (session('error'))
          <div class="alert alert-danger mx-4" role="alert">
            {{ session('error') }}
          </div>
        @endif
        <div class="card-body">
          <form id="formAccountSettings" method="POST" action="{{ route('account-settings-security.update') }}">
            @csrf
            <div class="row">
              <div class="mb-6 col-md-6 form-password-toggle">
                <label class="form-label" for="currentPassword">Password Lama</label>
                <div class="input-group input-group-merge">
                  <input class="form-control @error('current_password') is-invalid @enderror" type="password"
                    name="current_password" id="currentPassword"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                  <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                </div>
                @error('current_password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="row">
              <div class="mb-6 col-md-6 form-password-toggle">
                <label class="form-label" for="newPassword">Password Baru</label>
                <div class="input-group input-group-merge">
                  <input class="form-control @error('new_password') is-invalid @enderror" type="password" id="newPassword"
                    name="new_password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                  <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                </div>
                @error('new_password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-6 col-md-6 form-password-toggle">
                <label class="form-label" for="confirmPassword">Konfirmasi Password Baru</label>
                <div class="input-group input-group-merge">
                  <input class="form-control @error('new_password_confirmation') is-invalid @enderror" type="password"
                    name="new_password_confirmation" id="confirmPassword"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                  <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                </div>
              </div>
              <div class="col-12">
                <p class="fw-medium mt-4">Kebutuhan Password:</p>
                <ul class="ps-4 mb-0" style="list-style-type: disc">
                  <li class="mb-2"> panjang minimal 5 karakter.</li>
                  <li>Sertakan huruf kapital dan simbol.</li>
                </ul>
              </div>
              <div class="col-12 mt-6">
                <button type="submit" class="btn btn-primary me-3">Simpan Perubahan</button>
                <button type="reset" class="btn btn-outline-secondary">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
