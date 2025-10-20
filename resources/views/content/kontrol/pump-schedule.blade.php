@extends('layouts/contentNavbarLayout')

@section('title', 'Penjadwalan Pompa Otomatis')

@section('content')

  <div class="row g-5">
    {{-- Card Jadwal Pompa Tandon --}}
    <div class="col-md-4 ">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <h5 class="card-title mb-1">Jadwal Pompa Tandon</h5>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="scheduleTandonSwitch" checked>
              <label class="form-check-label" for="scheduleTandonSwitch">Aktif</label>
            </div>
          </div>
          <p class="text-secondary card-text">Air dari tandon ke tanaman hidroponik.</p>

          <form id="formScheduleTandon" class="mt-3">
            <div class="mb-3">
              <label for="tandonTime" class="form-label">Waktu Mulai</label>
              <input class="form-control" type="time" value="08:00" id="tandonTime" />
            </div>
            <div class="mb-3">
              <label for="tandonDays" class="form-label">Ulangi Pada Hari</label>
              <select id="tandonDays" class="select2 form-select" multiple>
                <option value="everyday"><strong>Setiap Hari</strong></option>
                <option value="1">Senin</option>
                <option value="2">Selasa</option>
                <option value="3">Rabu</option>
                <option value="4">Kamis</option>
                <option value="5">Jumat</option>
                <option value="6">Sabtu</option>
                <option value="0">Minggu</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="tandonDuration" class="form-label">Durasi (menit)</label>
              <input class="form-control" type="number" value="15" id="tandonDuration" placeholder="Contoh: 15" />
            </div>
            <button type="submit" class="btn btn-primary w-100">Simpan Jadwal</button>
          </form>
        </div>
      </div>
    </div>

    {{-- Card Jadwal Pompa Kolam --}}
    <div class="col-md-4 ">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <h5 class="card-title mb-1">Jadwal Pompa Kolam</h5>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="scheduleKolamSwitch" checked>
              <label class="form-check-label" for="scheduleKolamSwitch">Aktif</label>
            </div>
          </div>
          <p class="text-secondary card-text">Mengisi air ke dalam kolam ikan.</p>

          <form id="formScheduleKolam" class="mt-3">
            <div class="mb-3">
              <label for="kolamTime" class="form-label">Waktu Mulai</label>
              <input class="form-control" type="time" value="12:00" id="kolamTime" />
            </div>
            <div class="mb-3">
              <label for="kolamDays" class="form-label">Ulangi Pada Hari</label>
              <select id="kolamDays" class="select2 form-select" multiple>
                <option value="everyday"><strong>Setiap Hari</strong></option>
                <option value="1">Senin</option>
                <option value="2">Selasa</option>
                <option value="3">Rabu</option>
                <option value="4">Kamis</option>
                <option value="5">Jumat</option>
                <option value="6">Sabtu</option>
                <option value="0">Minggu</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="kolamDuration" class="form-label">Durasi (menit)</label>
              <input class="form-control" type="number" value="10" id="kolamDuration" placeholder="Contoh: 10" />
            </div>
            <button type="submit" class="btn btn-primary w-100">Simpan Jadwal</button>
          </form>
        </div>
      </div>
    </div>

    {{-- Card Jadwal Pompa Pembuangan --}}
    <div class="col-md-4 ">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <h5 class="card-title mb-1">Jadwal Pompa Buang</h5>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="scheduleBuangSwitch" checked>
              <label class="form-check-label" for="scheduleBuangSwitch">Aktif</label>
            </div>
          </div>
          <p class="text-secondary card-text">Air dari kolam ke tanaman di bawah.</p>

          <form id="formScheduleBuang" class="mt-3">
            <div class="mb-3">
              <label for="buangTime" class="form-label">Waktu Mulai</label>
              <input class="form-control" type="time" value="16:00" id="buangTime" />
            </div>
            <div class="mb-3">
              <label for="buangDays" class="form-label">Ulangi Pada Hari</label>
              <select id="buangDays" class="select2 form-select" multiple>
                <option value="everyday"><strong>Setiap Hari</strong></option>
                <option value="1">Senin</option>
                <option value="2">Selasa</option>
                <option value="3">Rabu</option>
                <option value="4">Kamis</option>
                <option value="5">Jumat</option>
                <option value="6">Sabtu</option>
                <option value="0">Minggu</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="buangDuration" class="form-label">Durasi (menit)</label>
              <input class="form-control" type="number" value="20" id="buangDuration"
                placeholder="Contoh: 20" />
            </div>
            <button type="submit" class="btn btn-primary w-100">Simpan Jadwal</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-12 ">
      <div class="card">
          <h5 class="card-header"></h5>
          <div class="table-responsive text-nowrap">
              <table class="table">
                  <thead>
                      <tr class="text-nowrap">
                          <th>#</th>
                          <th>Table heading</th>
                          <th>Table heading</th>
                          <th>Table heading</th>
                          <th>Table heading</th>
                          <th>Table heading</th>
                          <th>Table heading</th>
                          <th>Table heading</th>
                          <th>Table heading</th>
                          <th>Table heading</th>
                      </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                      <tr>
                          <th scope="row">1</th>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                      </tr>
                      <tr>
                          <th scope="row">2</th>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                      </tr>
                      <tr>
                          <th scope="row">3</th>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                      </tr>
                  </tbody>
              </table>
          </div>
      </div>
    </div>
  </div>

@endsection

@section('page-script')
  @vite([
      'resources/assets/vendor/libs/jquery/jquery.js',
      'resources/assets/vendor/libs/select2/select2.js',
      'resources/assets/vendor/libs/select2/select2.css',
  ])
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Inisialisasi Select2 untuk semua elemen dengan class .select2
      const select2 = $('.select2');
      if (select2.length) {
        select2.each(function() {
          var $this = $(this);
          $this.wrap('<div class="position-relative"></div>');
          $this.select2({
            placeholder: 'Pilih hari',
            dropdownParent: $this.parent()
          });
        });
      }

      // Fungsi untuk menangani logika "Setiap Hari"
      function handleEverydayOption(selectElement) {
        $(selectElement).on('change', function(e) {
          const $this = $(this);
          const selectedOptions = $this.val();

          // Jika "Setiap Hari" dipilih
          if (selectedOptions && selectedOptions.includes('everyday')) {
            // Pilih semua opsi hari kecuali "everyday" itu sendiri
            const allDays = $this.find('option[value!="everyday"]').map(function() {
              return this.value;
            }).get();
            $this.val(['everyday', ...allDays]).trigger('change.select2');
          }
        });
      }

      handleEverydayOption('#tandonDays');
      handleEverydayOption('#kolamDays');
      handleEverydayOption('#buangDays');
    });
  </script>
@endsection
