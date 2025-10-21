@extends('layouts/blankLayout')

@section('title', 'Forgot Password Basic - Pages')

@section('page-style')
  @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Forgot Password -->
        <div class="card px-sm-6 px-0">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-6">
              <a href="{{ url('/') }}">
                  <img src="{{ asset('assets/img/favicon/favicon.ico') }}" alt="" height="">
              </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-1">Lupa Password? 🔒</h4>
            <p class="mb-6">Masukan email dan kami akan mengirim instruksi untuk mengatur ulang kata sandi Anda.</p>

            <form id="formAuthentication" class="mb-6" action="{{ route('password.email') }}" method="POST">
              @csrf
              <div class="mb-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                  name="email" placeholder="Masukan emailmu" value="{{ old('email') }}" required autofocus />
              </div>
              <button class="btn btn-primary d-grid w-100">Kirim Ulang Link</button>
            </form>
            <div class="text-center">
              <a href="{{route('login') }}" class="d-flex justify-content-center">
                <i class="icon-base bx bx-chevron-left me-1"></i>
                Kembali ke login
              </a>
            </div>
          </div>
        </div>
        <!-- /Forgot Password -->
      </div>
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

      @if (session('status'))
        Toast.fire({
          icon: 'success',
          title: '{{ session('status') }}'
        });
      @endif

      @error('email')
        Toast.fire({
          icon: 'error',
          title: '{{ $message }}'
        });
      @enderror
    });
  </script>
@endsection
