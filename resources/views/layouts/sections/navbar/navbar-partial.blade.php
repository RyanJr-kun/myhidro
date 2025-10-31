@php
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\Route;
@endphp

<!--  Brand demo (display only for navbar-full and hide on below xl) -->
@if (isset($navbarFull))
  <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
    <a href="{{ url('/') }}" class="app-brand-link gap-2">
      <span class="app-brand-logo demo">@include('_partials.macros')</span>
      <span class="app-brand-text demo menu-text fw-bold text-heading">{{ config('variables.templateName') }}</span>
    </a>
  </div>
@endif

<!-- ! Not required for layout-without-menu -->
@if (!isset($navbarHideToggle))
  <div
    class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
    <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
      <i class="icon-base bx bx-menu icon-md"></i>
    </a>
  </div>
@endif
<div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
  <!-- Search -->
  <div class="navbar-nav align-items-center">
    <div class="nav-item d-flex align-items-center">
      <i class="icon-base bx bx-search icon-md"></i>
      <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Search..."
        aria-label="Search...">
    </div>
  </div>
  <ul class="navbar-nav flex-row align-items-center ms-md-auto">
    <!-- Quick links -->

    <!-- Notification -->
    <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
      <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
        data-bs-auto-close="outside" aria-expanded="false">
        <span class="position-relative">
          <i class="icon-base bx bx-bell icon-md"></i>
          <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
        </span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end p-0">
        <li class="dropdown-menu-header border-bottom">
          <div class="dropdown-header d-flex align-items-center py-3">
            <h6 class="mb-0 me-auto">Notification</h6>
            <div class="d-flex align-items-center h6 mb-0">
              <span class="badge bg-label-primary me-2">8 New</span>
              <a href="javascript:void(0)" class="dropdown-notifications-all p-2" data-bs-toggle="tooltip"
                data-bs-placement="top" aria-label="Mark all as read" data-bs-original-title="Mark all as read"><i
                  class="icon-base bx bx-envelope-open text-heading"></i></a>
            </div>
          </div>
        </li>
        <li class="dropdown-notifications-list scrollable-container ps">
          <ul class="list-group list-group-flush">
            <li class="list-group-item list-group-item-action dropdown-notifications-item">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar">
                    <img
                      src="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo/assets/img/avatars/1.png"
                      alt="" class="rounded-circle">
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="small mb-0">Congratulation Lettie 🎉</h6>
                  <small class="mb-1 d-block text-body">Won the monthly best seller gold badge</small>
                  <small class="text-body-secondary">1h ago</small>
                </div>
                <div class="flex-shrink-0 dropdown-notifications-actions">
                  <a href="javascript:void(0)" class="dropdown-notifications-read"><span
                      class="badge badge-dot"></span></a>
                  <a href="javascript:void(0)" class="dropdown-notifications-archive"><span
                      class="icon-base bx bx-x"></span></a>
                </div>
              </div>
            </li>
            <li class="list-group-item list-group-item-action dropdown-notifications-item">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar">
                    <span class="avatar-initial rounded-circle bg-label-danger">CF</span>
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="small mb-0">Charles Franklin</h6>
                  <small class="mb-1 d-block text-body">Accepted your connection</small>
                  <small class="text-body-secondary">12hr ago</small>
                </div>
                <div class="flex-shrink-0 dropdown-notifications-actions">
                  <a href="javascript:void(0)" class="dropdown-notifications-read"><span
                      class="badge badge-dot"></span></a>
                  <a href="javascript:void(0)" class="dropdown-notifications-archive"><span
                      class="icon-base bx bx-x"></span></a>
                </div>
              </div>
            </li>
            <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar">
                    <img
                      src="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo/assets/img/avatars/2.png"
                      alt="" class="rounded-circle">
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="small mb-0">New Message ✉️</h6>
                  <small class="mb-1 d-block text-body">You have new message from Natalie</small>
                  <small class="text-body-secondary">1h ago</small>
                </div>
                <div class="flex-shrink-0 dropdown-notifications-actions">
                  <a href="javascript:void(0)" class="dropdown-notifications-read">
                    <span class="badge badge-dot"></span>
                  </a>
                  <a href="javascript:void(0)" class="dropdown-notifications-archive">
                    <span class="icon-base bx bx-x"></span>
                  </a>
                </div>
              </div>
            </li>
          </ul>
          <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
          </div>
          <div class="ps__rail-y" style="top: 0px; right: 0px;">
            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
          </div>
        </li>
        <li class="border-top">
          <div class="d-grid p-4">
            <a class="btn btn-primary btn-sm d-flex" href="{{ route('account-settings-notifications') }}">
              <small class="align-middle">Lihat Semua Notifikasi</small>
            </a>
          </div>
        </li>
      </ul>
    </li>
    <!--/ Notification -->

    <!-- User -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
      <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
          <img
            src="{{ $user->img_user ? asset('assets/img/users/' . $user->img_user) : asset('assets/img/user.svg') }}"
            alt="" class="rounded-circle">
        </div>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        @if (Auth::check())
          <li>
            <a class="dropdown-item" href="{{ route('account-settings') }}">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                    <img
                      src="{{ $user->img_user ? asset('assets/img/users/' . $user->img_user) : asset('assets/img/user.svg') }}"
                      alt="Profile" class="w-px-40 h-auto rounded-circle">
                  </div>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-0">{{ $user->name }}</h6>
                  <small class="text-body-secondary">{{ $user->role->nama }}</small>
                </div>
              </div>
            </a>
          </li>
          <li>
            <div class="dropdown-divider my-1"></div>
          </li>
          <li>
            <a class="dropdown-item" href="{{ route('account-settings') }}">
              <i class="icon-base bx bx-user icon-md me-3"></i><span>My Profile</span>
            </a>
          </li>

          <li>
            <form action="{{ route('logout') }}" method="post">
              @csrf
              <button type="submit" class="dropdown-item border-radius-md w-100 text-danger"
                style="text-align: left;">
                <i class="bi bi-box-arrow-right me-2"></i>Log Out
              </button>
            </form>
          </li>
        @else
          <li>
            <a class="dropdown-item" href="{{ route('login') }}">
              <i class="icon-base bx bx-log-in icon-md me-3"></i><span>Login</span>
            </a>
          </li>
        @endif
      </ul>
    </li>
    <!--/ User -->
  </ul>
</div>
