<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
  <title>@yield('title', 'Admin Dashboard | UEMS')</title>

  <!-- Fonts & Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link id="pagestyle" href="{{ asset('assets/css/material-dashboard.css?v=3.2.0') }}" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom sidebar/theme overrides -->
  <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">

  {{-- Sidebar --}}
  <aside id="sidenav-main"
         class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 my-2 uems-sidebar">

    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
         id="iconSidenav"></i>

      <a class="navbar-brand px-4 py-3 m-0 d-flex align-items-center gap-2" href="#">
        <img src="{{ asset('assets/img/favicon.png') }}" class="navbar-brand-img" width="26" height="26" alt="logo">
        <span class="ms-1 text-sm">iEvent</span>
      </a>
    </div>

    <hr class="horizontal mt-0 mb-2">

    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">

        <li class="nav-item">
          <a class="nav-link uems-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
             href="{{ route('admin.dashboard') }}">
            <span class="icon-tile"><i class="material-symbols-rounded">dashboard</i></span>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link uems-link {{ request()->routeIs('admin.events.pending') ? 'active' : '' }}"
             href="{{ route('admin.events.pending') }}">
            <span class="icon-tile"><i class="material-symbols-rounded">pending_actions</i></span>
            <span class="nav-link-text ms-1">Pending Events</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link uems-link {{ request()->routeIs('admin.events.approved') ? 'active' : '' }}"
             href="{{ route('admin.events.approved') }}">
            <span class="icon-tile"><i class="material-symbols-rounded">check_circle</i></span>
            <span class="nav-link-text ms-1">Approved Events</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link uems-link {{ request()->routeIs('admin.events.rejected') ? 'active' : '' }}"
             href="{{ route('admin.events.rejected') }}">
            <span class="icon-tile"><i class="material-symbols-rounded">cancel</i></span>
            <span class="nav-link-text ms-1">Rejected Events</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link uems-link {{ request()->routeIs('admin.user.list') ? 'active' : '' }}"
             href="{{ route('admin.user.list') }}">
            <span class="icon-tile"><i class="material-symbols-rounded">group</i></span>
            <span class="nav-link-text ms-1">User List</span>
          </a>
        </li>

        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-5">Account</h6>
        </li>



        <li class="nav-item">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="nav-link uems-link bg-transparent border-0 w-100 text-start">
              <span class="icon-tile"><i class="material-symbols-rounded">logout</i></span>
              <span class="nav-link-text ms-1">Logout</span>
            </button>
          </form>
        </li>

      </ul>
    </div>
  </aside>

  {{-- Main content --}}
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3 d-flex justify-content-between">
        <h4 class="font-weight-bolder mb-0">@yield('page-title', 'Admin Dashboard')</h4>
        <div class="d-flex align-items-center">
          <span class="me-3">Welcome, {{ Auth::user()->name }}</span>
        </div>
      </div>
    </nav>

    <div class="container-fluid py-4">
      @yield('content')
    </div>
  </main>

  {{-- Scripts --}}
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/material-dashboard.min.js?v=3.2.0') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
