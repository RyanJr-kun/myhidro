@extends('layouts/contentNavbarLayout')

@section('title', 'Kontrol Pompa Manual')

@section('content')
  <div class="row">
    @php
      $pumpIcons = [
          'pompa hidroponik' => [
              'icon' => 'bxs-water-drop-alt',
              'color' => 'primary',
              'description' => 'Air dari tandon ke tanaman hidroponik.',
          ],
          'pompa kolam' => [
              'icon' => 'bxs-fish-alt',
              'color' => 'info',
              'description' => 'Mengisi air ke dalam kolam ikan.',
          ],
          'pompa pembuangan' => [
              'icon' => 'bx-recycle',
              'color' => 'success',
              'description' => 'Air dari kolam ke tanaman di bawah.',
          ],
      ];
    @endphp

    @foreach ($pumps as $pump)
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <h5 class="card-title mb-1 text-capitalize">{{ $pump->name }}</h5>
                <p class="text-secondary card-text">
                  {{ $pumpIcons[strtolower($pump->name)]['description'] ?? 'Kontrol pompa.' }}</p>
              </div>
              <div class="form-check form-switch">
                <input class="form-check-input pump-switch" type="checkbox" id="pumpSwitch-{{ $pump->id }}"
                  data-id="{{ $pump->id }}" {{ $pump->status ? 'checked' : '' }} />
                <label class="form-check-label" for="pumpSwitch-{{ $pump->id }}"></label>
              </div>
            </div>
            <div class="d-flex align-items-center mt-2">
              <i
                class='bx {{ $pumpIcons[strtolower($pump->name)]['icon'] ?? 'bxs-toggle-right' }} fs-3 me-2 text-{{ $pumpIcons[strtolower($pump->name)]['color'] ?? 'secondary' }}'></i>
              <span id="pumpStatus-{{ $pump->id }}"
                class="badge {{ $pump->status ? 'bg-label-success' : 'bg-label-secondary' }}">{{ $pump->status ? 'ON' : 'OFF' }}</span>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endsection

@section('page-script')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const pumpSwitches = document.querySelectorAll('.pump-switch');

      pumpSwitches.forEach(pumpSwitch => {
        pumpSwitch.addEventListener('change', function() {
          const pumpId = this.dataset.id;
          const status = this.checked;
          const pumpStatusBadge = document.getElementById(`pumpStatus-${pumpId}`);

          // Update UI secara optimis
          updateUI(pumpStatusBadge, status);

          // Kirim request ke server
          fetch(`{{ route('sistem-pump-status', ['pump' => '__PUMP_ID__']) }}`.replace('__PUMP_ID__',
              pumpId), {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                  status: status ? 1 : 0
                })
              })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                showToast('success', data.message);
                // Di sini Anda bisa mengirim perintah ke nodeMCU/IoT device
                console.log(`Perintah untuk pompa ${pumpId} berhasil: ${status ? 'ON' : 'OFF'}`);
              } else {
                // Kembalikan UI ke state semula jika gagal
                this.checked = !status;
                updateUI(pumpStatusBadge, !status);
                showToast('error', data.message || 'Gagal mengubah status pompa.');
              }
            })
            .catch(error => {
              // Kembalikan UI ke state semula jika terjadi error network
              this.checked = !status;
              updateUI(pumpStatusBadge, !status);
              showToast('error', 'Terjadi kesalahan. Periksa koneksi Anda.');
              console.error('Error:', error);
            });
        });
      });

      function updateUI(badge, status) {
        if (status) {
          badge.textContent = 'ON';
          badge.classList.remove('bg-label-secondary');
          badge.classList.add('bg-label-success');
        } else {
          badge.textContent = 'OFF';
          badge.classList.remove('bg-label-success');
          badge.classList.add('bg-label-secondary');
        }
      }

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

      function showToast(icon, title) {
        Toast.fire({
          icon: icon,
          title: title
        });
      }

      @if (session('success'))
        showToast('success', '{{ session('success') }}');
      @endif

      @if (session('error'))
        showToast('error', '{{ session('error') }}');
      @endif
    });
  </script>
@endsection
