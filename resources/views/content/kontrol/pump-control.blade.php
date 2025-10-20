@extends('layouts/contentNavbarLayout')

@section('title', 'Kontrol Pompa Manual')

@section('content')
  <div class="row">
    {{-- Pompa Tandon Hidroponik --}}
    <div class="col-md-4 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <h5 class="card-title mb-1">Pompa Tandon</h5>
              <p class="text-secondary card-text">Air dari tandon ke tanaman hidroponik.</p>
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="pumpTandonSwitch" />
              <label class="form-check-label" for="pumpTandonSwitch"></label>
            </div>
          </div>
          <div class="d-flex align-items-center mt-2">
            <i class='bx bxs-droplet-half fs-3 me-2 text-primary'></i>
            <span id="pumpTandonStatus" class="badge bg-label-secondary">OFF</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Pompa Kolam Ikan --}}
    <div class="col-md-4 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <h5 class="card-title mb-1">Pompa Kolam</h5>
              <p class="text-secondary card-text">Mengisi air ke dalam kolam ikan.</p>
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="pumpKolamSwitch" />
              <label class="form-check-label" for="pumpKolamSwitch"></label>
            </div>
          </div>
          <div class="d-flex align-items-center mt-2">
            <i class='bx bxs-fish fs-3 me-2 text-info'></i>
            <span id="pumpKolamStatus" class="badge bg-label-secondary">OFF</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Pompa Pembuangan --}}
    <div class="col-md-4 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <h5 class="card-title mb-1">Pompa Pembuangan</h5>
              <p class="text-secondary card-text">Air dari kolam ke tanaman di bawah.</p>
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="pumpBuangSwitch" />
              <label class="form-check-label" for="pumpBuangSwitch"></label>
            </div>
          </div>
          <div class="d-flex align-items-center mt-2">
            <i class='bx bx-recycle fs-3 me-2 text-success'></i>
            <span id="pumpBuangStatus" class="badge bg-label-secondary">OFF</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Di sini Anda bisa menambahkan script untuk interaksi dengan IoT --}}
@endsection

@section('page-script')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Contoh interaksi untuk satu pompa, bisa diadaptasi untuk yang lain
      const pumpTandonSwitch = document.getElementById('pumpTandonSwitch');
      const pumpTandonStatus = document.getElementById('pumpTandonStatus');

      pumpTandonSwitch.addEventListener('change', function() {
        const isChecked = this.checked;
        if (isChecked) {
          pumpTandonStatus.textContent = 'ON';
          pumpTandonStatus.classList.remove('bg-label-secondary');
          pumpTandonStatus.classList.add('bg-label-success');
          // Di sini Anda akan mengirim perintah ke nodeMCU untuk menyalakan pompa
          console.log('Mengirim perintah ON ke Pompa Tandon...');
        } else {
          pumpTandonStatus.textContent = 'OFF';
          pumpTandonStatus.classList.remove('bg-label-success');
          pumpTandonStatus.classList.add('bg-label-secondary');
          // Di sini Anda akan mengirim perintah ke nodeMCU untuk mematikan pompa
          console.log('Mengirim perintah OFF ke Pompa Tandon...');
        }
      });

      // Anda bisa menambahkan event listener serupa untuk pumpKolamSwitch dan pumpBuangSwitch
    });
  </script>
@endsection
