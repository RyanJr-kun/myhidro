@extends('layouts/blankLayout')

@section('title', 'Register Basic - Pages')

@section('page-style')
  @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Register Card -->
        <div class="card px-sm-6 px-0">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-n6">
              <a href="{{ url('/') }}">
                  <img src="{{ asset('assets/img/favicon/favicon.ico') }}" alt="" height="">
              </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-1">Mulai Sekarang!🚀</h4>
            <p class="mb-6">Buat aplikasi managementmu lebih mudah.</p>

            <form id="formAuthentication" class="mb-6" action="{{ route('auth-register-store') }}" method="POST">
              @csrf
              <div class="mb-6">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                  name="username" placeholder="Masukkan username Anda" value="{{ old('username') }}" autofocus />
                @error('username')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-6">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email"
                  name="email" placeholder="Masukkan email Anda" value="{{ old('email') }}" />
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-password-toggle mb-6">
                <label class="form-label" for="password">Password</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password" autocomplete="new-password" />
                  <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                </div>
                @error('password')
                  <div class="d-block invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <button class="btn btn-primary d-grid w-100">Mendaftar</button>
            </form>
            <p class="text-center">
              <span>Sudah punya akun?</span>
              <a href="{{ url('auth/login-basic') }}">
                <span>Login.</span>
              </a>
            </p>
          </div>
        </div>
        <!-- Register Card -->
      </div>
    </div>
  </div>
@endsection
