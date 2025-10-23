@extends('layouts/contentNavbarLayout')

@section('title', 'Tambahkan Ikan')

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
        <a class="nav-link active" href="javascript:void(0);"><i class="icon-base bx bx-fish-alt icon-sm me-1_5"></i>
          Ikan</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-xl-12">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Form Tambah ikan</h5> <small class="text-muted float-end">Isi data dengan benar</small>
        </div>
        <div class="card-body">
          {{-- Tampilkan error validasi global jika ada --}}
          @if ($errors->any())
            <div class="alert alert-danger pb-0">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('ikan.store') }}">
            @csrf {{-- CSRF Token Protection --}}

            <div class="row">
              <div class="col-md-12 mb-3">
                <label class="form-label" for="nama_ikan">Nama Ikan</label>
                <input type="text" class="form-control @error('nama_ikan') is-invalid @enderror" id="nama_ikan"
                  name="nama_ikan" placeholder="Contoh: Ikan Lele" value="{{ old('nama_ikan') }}" required />
                @error('nama_ikan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label" for="tanggal_tebar">Tanggal Tebar</label>
                <input type="date" class="form-control @error('tanggal_tebar') is-invalid @enderror" id="tanggal_tebar"
                  name="tanggal_tebar" value="{{ old('tanggal_tebar') }}" required />
                @error('tanggal_tebar')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label" for="jumlah_bibit">Jumlah Benih Ditebar</label>
                <input type="number" class="form-control @error('jumlah_bibit') is-invalid @enderror" id="jumlah_bibit"
                  name="jumlah_bibit" placeholder="Contoh: 20" value="{{ old('jumlah_bibit') }}" min="1"
                  required />
                @error('jumlah_bibit')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-12 mb-3">
                <label class="form-label" for="estimasi_panen_hari">Estimasi Panen (Hari)</label>
                <input type="number" class="form-control @error('estimasi_panen_hari') is-invalid @enderror"
                  id="estimasi_panen_hari" name="estimasi_panen_hari" placeholder="Contoh: 60"
                  value="{{ old('estimasi_panen_hari') }}" min="1" required />
                @error('estimasi_panen_hari')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Perkiraan jumlah hari dari tanggal tebar hingga siap panen.</div>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label" for="catatan">Catatan (Opsional)</label>
              <textarea id="catatan" name="catatan" class="form-control @error('catatan') is-invalid @enderror"
                placeholder="Catatan tambahan mengenai ikan ini...">{{ old('catatan') }}</textarea>
              @error('catatan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <button type="submit" class="btn btn-primary">Simpan Data</button>
            <a href="{{ route('dashboard-analytics-ikan') }}" class="btn btn-secondary">Batal</a>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
