@extends('layouts/contentNavbarLayout')

@section('title', 'Account Management')

@section('content')
  <h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Page /</span> Account Management
  </h4>

  <!-- Basic Bootstrap Table -->
  <div class="card">
    <h5 class="card-header">User List</h5>
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
                    <span class="badge bg-primary" title="Role user">{{ $user->role->nama }}</span>
                  @else
                    <span class="badge bg-info" title="Role user">{{ $user->role->nama }}</span>
                  @endif
              </td>
              <td><span class="badge bg-label-primary me-1">Active</span></td>

              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i
                      class="bx bx-dots-vertical-rounded"></i></button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                    <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <!--/ Basic Bootstrap Table -->
@endsection
