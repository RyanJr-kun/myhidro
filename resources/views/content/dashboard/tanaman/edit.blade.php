@extends('layouts/contentNavbarLayout')

@section('title', 'Ubah Tanaman')

@section('content')

  <div class="nav-align-top">
    <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-md-0 gap-2">
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/') }}"><i class="icon-base bx bx-dashboard icon-sm me-1_5"></i>
          Dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="javascript:void(0);"><i class="icon-base bx bx-leaf icon-sm me-1_5"></i> Tanaman</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard-analytics-ikan') }}"><i
            class="icon-base bx bx-fish-alt icon-sm me-1_5"></i> Ikan</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-xl-12">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Form Edit Tanaman: {{ $tanaman->nama_tanaman }}</h5>
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
          <form method="POST" action="{{ route('tanaman.update', $tanaman->nama_tanaman) }}">
            @csrf
            @method('PUT') {{-- Atau PATCH --}}

            <div class="mb-3">
              <label class="form-label" for="nama_tanaman">Nama Tanaman</label>
              {{-- Isi value dengan data yang ada --}}
              <input type="text" class="form-control @error('nama_tanaman') is-invalid @enderror" id="nama_tanaman"
                name="nama_tanaman" placeholder="Contoh: Selada Hijau"
                value="{{ old('nama_tanaman', $tanaman->nama_tanaman) }}" required />
              @error('nama_tanaman')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label" for="jumlah_benih">Jumlah Benih Ditanam</label>
                <input type="number" class="form-control @error('jumlah_benih') is-invalid @enderror" id="jumlah_benih"
                  name="jumlah_benih" placeholder="Contoh: 20" value="{{ old('jumlah_benih', $tanaman->jumlah_benih) }}"
                  min="1" required />
                @error('jumlah_benih')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label" for="tanggal_tanam">Tanggal Tanam</label>
                {{-- Format tanggal untuk input type="date" --}}
                <input type="date" class="form-control @error('tanggal_tanam') is-invalid @enderror" id="tanggal_tanam"
                  name="tanggal_tanam"
                  value="{{ old('tanggal_tanam', $tanaman->tanggal_tanam ? $tanaman->tanggal_tanam->format('Y-m-d') : '') }}"
                  required />
                @error('tanggal_tanam')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label" for="estimasi_panen_hari">Estimasi Panen (Hari)</label>
              <input type="number" class="form-control @error('estimasi_panen_hari') is-invalid @enderror"
                id="estimasi_panen_hari" name="estimasi_panen_hari" placeholder="Contoh: 60"
                value="{{ old('estimasi_panen_hari', $tanaman->estimasi_panen_hari) }}" min="1" required />
              @error('estimasi_panen_hari')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">Perkiraan jumlah hari dari tanggal tanam hingga siap panen.</div>
            </div>

            {{-- Tambahkan input untuk tanggal panen aktual dan status jika ingin diedit --}}
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label" for="tanggal_panen_aktual">Tanggal Panen Aktual (Opsional)</label>
                <input type="date" class="form-control @error('tanggal_panen_aktual') is-invalid @enderror"
                  id="tanggal_panen_aktual" name="tanggal_panen_aktual"
                  value="{{ old('tanggal_panen_aktual', $tanaman->tanggal_panen_aktual ? $tanaman->tanggal_panen_aktual->format('Y-m-d') : '') }}" />
                @error('tanggal_panen_aktual')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label" for="status">Status</label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                  <option value="ditanam" {{ old('status', $tanaman->status) == 'ditanam' ? 'selected' : '' }}>Ditanam
                  </option>
                  <option value="dipanen" {{ old('status', $tanaman->status) == 'dipanen' ? 'selected' : '' }}>Dipanen
                  </option>
                  <option value="gagal" {{ old('status', $tanaman->status) == 'gagal' ? 'selected' : '' }}>Gagal
                  </option>
                </select>
                @error('status')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label" for="pupuk_interval_hari">Interval Pemupukan (Hari, Opsional)</label>
                <input type="number" class="form-control @error('pupuk_interval_hari') is-invalid @enderror"
                  id="pupuk_interval_hari" name="pupuk_interval_hari" placeholder="Contoh: 7"
                  value="{{ old('pupuk_interval_hari', $tanaman->pupuk_interval_hari) }}" min="1" />
                @error('pupuk_interval_hari')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Berapa hari sekali perlu dipupuk? Kosongkan jika tidak perlu pengingat.</div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label" for="air_interval_hari">Interval Cek Air/Nutrisi (Hari, Opsional)</label>
                <input type="number" class="form-control @error('air_interval_hari') is-invalid @enderror"
                  id="air_interval_hari" name="air_interval_hari" placeholder="Contoh: 3"
                  value="{{ old('air_interval_hari', $tanaman->air_interval_hari) }}" min="1" />
                @error('air_interval_hari')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Berapa hari sekali perlu cek air/nutrisi? Kosongkan jika tidak perlu.</div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label" for="catatan">Catatan (Opsional)</label>
              <textarea id="catatan" name="catatan" class="form-control @error('catatan') is-invalid @enderror"
                placeholder="Catatan tambahan mengenai tanaman ini...">{{ old('catatan', $tanaman->catatan) }}</textarea>
              @error('catatan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update Data</button>
            <a href="{{ route('dashboard-analytics-tanaman') }}" class="btn btn-secondary">Batal</a>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('page-script')
  {{-- Tambahkan script inisialisasi datepicker jika perlu --}}
@endsection
