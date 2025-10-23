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
          <h5 class="mb-0">Ubah Ikan: {{ $ikan->nama_ikan }}</h5>
          <small class="text-muted float-end">Perbarui data</small>
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

          {{-- Form mengarah ke route update, gunakan PUT/PATCH method --}}
          <form method="POST" action="{{ route('ikan.update', $ikan->nama_ikan) }}">
            @csrf
            @method('PUT') {{-- Atau PATCH --}}

            <div class="mb-3">
              <label class="form-label" for="nama_ikan">Nama Ikan</label>
              {{-- Isi value dengan data yang ada --}}
              <input type="text" class="form-control @error('nama_ikan') is-invalid @enderror" id="nama_ikan"
                name="nama_ikan" placeholder="Contoh: Selada Hijau" value="{{ old('nama_ikan', $ikan->nama_ikan) }}"
                required />
              @error('nama_ikan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label" for="jumlah_bibit">Jumlah Bibit Ditanam</label>
                <input type="number" class="form-control @error('jumlah_bibit') is-invalid @enderror" id="jumlah_bibit"
                  name="jumlah_bibit" placeholder="Contoh: 20" value="{{ old('jumlah_bibit', $ikan->jumlah_bibit) }}"
                  min="1" required />
                @error('jumlah_bibit')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label" for="tanggal_tebar">Tanggal Tebar</label>
                {{-- Format tanggal untuk input type="date" --}}
                <input type="date" class="form-control @error('tanggal_tebar') is-invalid @enderror" id="tanggal_tebar"
                  name="tanggal_tebar"
                  value="{{ old('tanggal_tebar', $ikan->tanggal_tebar ? $ikan->tanggal_tebar->format('Y-m-d') : '') }}"
                  required />
                @error('tanggal_tebar')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label" for="estimasi_panen_hari">Estimasi Panen (Hari)</label>
              <input type="number" class="form-control @error('estimasi_panen_hari') is-invalid @enderror"
                id="estimasi_panen_hari" name="estimasi_panen_hari" placeholder="Contoh: 60"
                value="{{ old('estimasi_panen_hari', $ikan->estimasi_panen_hari) }}" min="1" required />
              @error('estimasi_panen_hari')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">Perkiraan jumlah hari dari tanggal tebar hingga siap panen.</div>
            </div>

            {{-- Tambahkan input untuk tanggal panen aktual dan status jika ingin diedit --}}
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label" for="tanggal_panen_aktual">Tanggal Panen Aktual (Opsional)</label>
                <input type="date" class="form-control @error('tanggal_panen_aktual') is-invalid @enderror"
                  id="tanggal_panen_aktual" name="tanggal_panen_aktual"
                  value="{{ old('tanggal_panen_aktual', $ikan->tanggal_panen_aktual ? $ikan->tanggal_panen_aktual->format('Y-m-d') : '') }}" />
                @error('tanggal_panen_aktual')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label" for="status">Status</label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                  <option value="ditebar" {{ old('status', $ikan->status) == 'ditebar' ? 'selected' : '' }}>Ditebar
                  </option>
                  <option value="dipanen" {{ old('status', $ikan->status) == 'dipanen' ? 'selected' : '' }}>Dipanen
                  </option>
                  <option value="gagal" {{ old('status', $ikan->status) == 'gagal' ? 'selected' : '' }}>Gagal</option>
                </select>
                @error('status')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label" for="catatan">Catatan (Opsional)</label>
              <textarea id="catatan" name="catatan" class="form-control @error('catatan') is-invalid @enderror"
                placeholder="Catatan tambahan mengenai ikan ini...">{{ old('catatan', $ikan->catatan) }}</textarea>
              @error('catatan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update Data</button>
            <a href="{{ route('dashboard-analytics-ikan') }}" class="btn btn-secondary">Batal</a>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
