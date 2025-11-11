<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/favicon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
  <title>@yield('title', 'Organizer Dashboard | UEMS')</title>

  <!-- Fonts and icons -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('assets/css/material-dashboard.css?v=3.2.0') }}" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">

  {{-- Sidebar --}}
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="#">
        <img src="{{ asset('assets/img/favicon.png') }}" class="navbar-brand-img" width="26" height="26" alt="main_logo">
        <span class="ms-1 text-sm text-dark">UEMS Organizer</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('organizer.dashboard') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('organizer.dashboard') }}">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">My Events</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('organizer.events.create') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('organizer.events.create') }}">
            <i class="material-symbols-rounded opacity-5">add_circle</i>
            <span class="nav-link-text ms-1">Add Event</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-dark" href="#">
            <i class="material-symbols-rounded opacity-5">group</i>
            <span class="nav-link-text ms-1">Attendee List</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-dark" href="#">
            <i class="material-symbols-rounded opacity-5">feedback</i>
            <span class="nav-link-text ms-1">Feedback Summary</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-dark" href="#">
            <i class="material-symbols-rounded opacity-5">notifications</i>
            <span class="nav-link-text ms-1">Notifications</span>
          </a>
        </li>

        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account pages</h6>
        </li>

        <li class="nav-item">
          <a class="nav-link text-dark" href="{{ route('profile.edit') }}">
            <i class="material-symbols-rounded opacity-5">person</i>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>

        <li class="nav-item">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link text-dark bg-transparent border-0">
              <i class="material-symbols-rounded opacity-5">logout</i>
              <span class="nav-link-text ms-1">Logout</span>
            </button>
          </form>
        </li>
      </ul>
    </div>
  </aside>

  {{-- Main Content --}}
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

    {{-- Navbar --}}
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3 d-flex justify-content-between">
        <h4 class="font-weight-bolder mb-0">@yield('page-title', 'Organizer Dashboard')</h4>
        <div class="d-flex align-items-center">
          <span class="me-3">Welcome, {{ Auth::user()->name }}</span>
        </div>
      </div>
    </nav>
    {{-- End Navbar --}}

    <div class="container-fluid py-4">
      @yield('content')
    </div>
  </main>

  <!-- Core JS Files -->
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
  <script src="{{ asset('assets/js/material-dashboard.min.js?v=3.2.0') }}"></script>
</body>
</html>
