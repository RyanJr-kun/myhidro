@extends('layouts/contentNavbarLayout')

@section('title', 'Account Management')

@section('content')
  <!-- Basic Bootstrap Table -->
  <div class="card">
    <div class="card-header pt-3 pb-1">
      <div class="row">
        <div class="col-6">
          <h5 class="mb-0">User List</h5>
          <p class="text-secondary">Kelola data pengguna yang terdaftar di sistem.</p>
        </div>
        <div class="col-6 text-end">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal" id="addUserBtn">
            <i class="bx bx-plus me-1"></i> Add User
          </button>
        </div>
      </div>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach ($users as $user)
            <tr>
              <td>
                <span class="fw-medium">{{ $user->name }}</span>
              </td>
              <td>{{ $user->email }}</td>
              <td>
                @if ($user->role->id == 1)
                  <span class="text-truncate d-flex align-items-center text-heading"><i
                      class="icon-base bx bx-desktop text-danger me-2"></i>{{ $user->role->nama }}</span>
                @else
                  <span class="text-truncate d-flex align-items-center text-heading"><i
                      class="icon-base bx bx-user text-success me-2"></i>{{ $user->role->nama }}</span>
                @endif
              </td>

              <td>
                @if ($user->status == 1)
                  <span class="badge bg-label-success me-1">Active</span>
                @else
                  <span class="badge bg-label-secondary me-1">Offline</span>
                @endif
              </td>

              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i
                      class="bx bx-dots-vertical-rounded"></i></button>
                  <div class="dropdown-menu">
                    <button class="dropdown-item edit-btn" data-user-id="{{ $user->id }}"
                      data-user-data="{{ json_encode($user) }}"><i class="bx bx-edit-alt me-2"></i>
                      Edit</button>
                    <form action="{{ route('account-management.destroy', $user->id) }}" method="POST"
                      class="delete-form">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="dropdown-item delete-btn"><i class="bx bx-trash me-2"></i>
                        Delete</button>
                    </form>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="userForm" method="POST" action="">
          @csrf
          <div id="method-field"></div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label for="name" class="form-label">Nama</label>
                <input class="form-control" type="text" id="name" name="name" required autofocus />
              </div>
              <div class="col-md-6">
                <label for="email" class="form-label">E-mail</label>
                <input class="form-control" type="email" id="email" name="email" required />
              </div>
              <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input class="form-control" type="password" id="password" name="password" />
                <small id="passwordHelp" class="form-text text-muted">Kosongkan jika tidak ingin mengubah
                  password.</small>
              </div>
              <div class="col-md-6">
                <label for="role_id" class="form-label">Role</label>
                <select id="role_id" name="role_id" class="form-select" required>
                  <option value="">Pilih Role</option>
                  @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->nama }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label for="organisasi" class="form-label">Organisasi</label>
                <input type="text" class="form-control" id="organisasi" name="organisasi" />
              </div>
              <div class="col-md-6">
                <label class="form-label" for="nomer_telepon">Nomer Telepon</label>
                <input type="text" id="nomer_telepon" name="nomer_telepon" class="form-control"
                  placeholder="081234567890" />
              </div>
              <div class="col-12">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" name="alamat" id="alamat"></textarea>
              </div>
              <div class="col-12">
                <div class="form-check form-switch">
                  <input type="hidden" name="status" value="0">
                  <input class="form-check-input" type="checkbox" name="status" id="status" value="1">
                  <label class="form-check-label" for="status">Active</label>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!--/ Basic Bootstrap Table -->
@endsection

@section('page-script')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const userModal = new bootstrap.Modal(document.getElementById('userModal'));
      const userForm = document.getElementById('userForm');
      const modalTitle = document.getElementById('modalTitle');
      const methodField = document.getElementById('method-field');
      const passwordInput = document.getElementById('password');
      const passwordHelp = document.getElementById('passwordHelp');

      // Handle Add User button click
      document.getElementById('addUserBtn').addEventListener('click', function() {
        userForm.reset();
        userForm.action = "{{ route('account-management.store') }}";
        methodField.innerHTML = '';
        modalTitle.textContent = 'Add User';
        passwordInput.required = true;
        passwordHelp.style.display = 'none';
        userModal.show();
      });

      // Handle Edit User button click
      document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
          const userId = this.getAttribute('data-user-id');
          const userData = JSON.parse(this.getAttribute('data-user-data'));

          userForm.reset();
          userForm.action = `/pages/account-management/${userId}`;
          methodField.innerHTML = '@method('PUT')';
          modalTitle.textContent = 'Edit User';
          passwordInput.required = false;
          passwordHelp.style.display = 'block';

          // Populate form
          document.getElementById('name').value = userData.name;
          document.getElementById('email').value = userData.email;
          document.getElementById('role_id').value = userData.role_id;
          document.getElementById('status').checked = userData.status == 1;
          document.getElementById('organisasi').value = userData.organisasi || '';
          document.getElementById('nomer_telepon').value = userData.nomer_telepon || '';
          document.getElementById('alamat').value = userData.alamat || '';

          userModal.show();
        });
      });


      const deleteButtons = document.querySelectorAll('.delete-btn');

      deleteButtons.forEach(button => {
        button.addEventListener('click', function(event) {
          event.preventDefault(); // Mencegah form submit langsung
          const form = this.closest('.delete-form');

          Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
          }).then((result) => {
            if (result.isConfirmed) {
              form.submit(); // Submit form jika dikonfirmasi
            }
          });
        });
      });

      @if (session('success'))
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
        Toast.fire({
          icon: 'success',
          title: '{{ session('success') }}'
        });
      @endif
    });
  </script>
@endsection
