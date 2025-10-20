{{-- resources/views/auth/verify-email.blade.php --}}
@extends('layouts/blankLayout')

@section('title', 'Verify Email')

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <div class="card">
        <div class="card-body">
          <h4 class="mb-2">Verifikasi Alamat Email Anda</h4>
          @if (session('message'))
            <div class="alert alert-success" role="alert">
              {{ session('message') }}
            </div>
          @endif
          <p class="mb-4">
            Terima kasih telah mendaftar! Sebelum memulai, bisakah Anda memverifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan melalui email kepada Anda? Jika Anda tidak menerima email tersebut, kami akan dengan senang hati mengirimkan yang lain.
          </p>

          <div class="mt-4 d-flex align-items-center justify-content-between">
            <form method="POST" action="{{ route('verification.send') }}">
              @csrf
              <button type="submit" class="btn btn-primary">
                Kirim Ulang Email Verifikasi
              </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-secondary">
                    Log Out
                </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
