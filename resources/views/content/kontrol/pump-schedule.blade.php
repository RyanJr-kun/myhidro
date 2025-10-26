@extends('layouts/contentNavbarLayout')

@section('title', 'Penjadwalan Pompa Otomatis')

@section('content')

  <div class="row g-5">
    {{-- Card Jadwal pompa hidroponik --}}
    <div class="col-md-4 ">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-0">Jadwal pompa hidroponik</h5>
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
            {{-- Hidden input to identify the pump --}}
            <input type="hidden" name="pump_name" value="pompa hidroponik">
            <button type="submit" class="btn btn-primary w-100">Simpan Jadwal</button>
          </form>
        </div>
      </div>
    </div>

    {{-- Card Jadwal Pompa Kolam --}}
    <div class="col-md-4 ">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-0">Jadwal Pompa Kolam</h5>
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
            {{-- Hidden input to identify the pump --}}
            <input type="hidden" name="pump_name" value="pompa kolam">
            <button type="submit" class="btn btn-primary w-100">Simpan Jadwal</button>
          </form>
        </div>
      </div>
    </div>

    {{-- Card Jadwal Pompa Pembuangan --}}
    <div class="col-md-4 ">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-0">Jadwal Pompa Buang</h5>
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
            {{-- Hidden input to identify the pump --}}
            <input type="hidden" name="pump_name" value="pompa pembuangan">
            <button type="submit" class="btn btn-primary w-100">Simpan Jadwal</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-12 ">
      <div class="card">
        <h5 class="card-header">Daftar Jadwal Pompa</h5>
        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead>
              <tr class="text-nowrap">
                <th>#</th>
                <th>Pompa</th>
                <th>Waktu Mulai</th>
                <th>Durasi</th>
                <th>Hari</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              {{-- Asumsi variabel $schedules dikirim dari controller --}}
              @forelse ($schedules ?? [] as $schedule)
                <tr>
                  <th scope="row">{{ $loop->iteration }}</th>
                  <td>{{ $schedule->pump_name }}</td>
                  <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</td>
                  <td>{{ $schedule->duration_minutes }} menit</td>
                  <td>
                    {{ implode(', ',array_map(function ($day) {return ['0' => 'Minggu', '1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu', 'everyday' => 'Setiap Hari'][$day] ?? $day;}, $schedule->days)) }}
                  </td>
                  <td>
                    <button type="button"
                      class="btn btn-sm badge toggle-status-btn {{ $schedule->status ? 'bg-label-success' : 'bg-label-secondary' }}"
                      data-id="{{ $schedule->id }}">
                      {{ $schedule->status ? 'Aktif' : 'Nonaktif' }}
                    </button>
                  </td>
                  <td>
                    <button type="button" class="btn btn-icon delete-schedule-btn" data-id="{{ $schedule->id }}"
                      data-name="{{ $schedule->pump_name }} jam {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}">
                      <i class='bx bx-trash'></i>
                    </button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center">Belum ada jadwal yang disimpan.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('page-script')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

      // Fungsi untuk menangani submit form
      function handleFormSubmit(event) {
        event.preventDefault(); // Mencegah form dari submit biasa

        const form = event.target;
        const pumpType = form.id.replace('formSchedule', ''); // 'Tandon', 'Kolam', atau 'Buang'
        const pumpName = form.querySelector('input[name="pump_name"]').value;
        const startTime = document.getElementById(pumpType.toLowerCase() + 'Time').value;
        const duration = document.getElementById(pumpType.toLowerCase() + 'Duration').value;
        const days = $(`#${pumpType.toLowerCase()}Days`).val();


        const formData = {
          pump_name: pumpName,
          start_time: startTime,
          duration_minutes: duration,
          days: days,
          status: true,
          _token: '{{ csrf_token() }}' // Menambahkan CSRF token
        };

        // Kirim data ke server
        fetch('{{ route('sistem-pump-schedule.store') }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
          })
          .then(response => {
            if (response.ok) {
              // Jika sukses, reload halaman untuk melihat data baru di tabel
              window.location.reload();
            } else {
              // Jika ada error, tampilkan di console
              console.error('Gagal menyimpan jadwal.');
              alert('Gagal menyimpan jadwal. Silakan cek console untuk detail.');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Terjadi error. Silakan cek console untuk detail.');
          });
      }

      // Tambahkan event listener untuk submit form
      document.getElementById('formScheduleTandon').addEventListener('submit', handleFormSubmit);
      document.getElementById('formScheduleKolam').addEventListener('submit', handleFormSubmit);
      document.getElementById('formScheduleBuang').addEventListener('submit', handleFormSubmit);
      const deleteButtons = document.querySelectorAll('.delete-schedule-btn');

      // 2. Beri event listener untuk setiap tombol
      deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
          const scheduleId = this.dataset.id;
          const scheduleName = this.dataset.name;

          // 3. Tampilkan konfirmasi
          Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Anda akan menghapus jadwal: ${scheduleName}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
          }).then((result) => {
            if (result.isConfirmed) {
              // 4. Jika dikonfirmasi, kirim request DELETE
              // Buat URL yang benar
              let deleteUrl =
                '{{ route('sistem-pump-schedule.destroy', ['pumpSchedule' => '__ID__']) }}';
              deleteUrl = deleteUrl.replace('__ID__', scheduleId);

              fetch(deleteUrl, {
                  method: 'DELETE',
                  headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                  }
                })
                .then(response => {
                  if (response.ok) {
                    // 5. Jika sukses, reload halaman
                    // (Sama seperti logika form 'create' Anda)
                    window.location.reload();
                  } else {
                    Swal.fire('Gagal!', 'Gagal menghapus jadwal.', 'error');
                  }
                })
                .catch(error => {
                  console.error('Error:', error);
                  Swal.fire('Error!', 'Terjadi kesalahan.', 'error');
                });
            }
          });
        });
      });

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

      // 1. Temukan semua tombol toggle status
      const toggleButtons = document.querySelectorAll('.toggle-status-btn');

      // 2. Beri event listener untuk setiap tombol
      toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
          const scheduleId = this.dataset.id;
          const currentButton = this; // Simpan referensi ke tombol

          // Buat URL
          let toggleUrl = '{{ route('sistem-pump-schedule.toggleStatus', ['pumpSchedule' => '__ID__']) }}';
          toggleUrl = toggleUrl.replace('__ID__', scheduleId);

          // Kirim request PATCH
          fetch(toggleUrl, {
              method: 'PATCH',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              }
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                // 3. Update UI (tombol) tanpa reload halaman
                const newStatus = data.new_status; // true atau false dari controller

                if (newStatus) {
                  currentButton.textContent = 'Aktif';
                  currentButton.classList.remove('bg-label-secondary');
                  currentButton.classList.add('bg-label-success');
                } else {
                  currentButton.textContent = 'Nonaktif';
                  currentButton.classList.remove('bg-label-success');
                  currentButton.classList.add('bg-label-secondary');
                }

                // Tampilkan notifikasi sukses
                Toast.fire({
                  icon: 'success',
                  title: data.success
                });

              } else {
                Swal.fire('Gagal!', data.error || 'Gagal mengubah status.', 'error');
              }
            })
            .catch(error => {
              console.error('Error:', error);
              Swal.fire('Error!', 'Terjadi kesalahan.', 'error');
            });
        });
      });
    });
  </script>
@endsection
