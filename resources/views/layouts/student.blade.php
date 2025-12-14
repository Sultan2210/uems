<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
  <title>@yield('title','Student | UEMS')</title>
  <link rel="stylesheet" href="{{ asset('assets/css/nucleo-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/nucleo-svg.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/material-dashboard.css?v=3.2.0') }}">
</head>
<body class="g-sidenav-show bg-gray-100">

  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2">
    <div class="sidenav-header px-4 py-3">
      <a class="navbar-brand m-0" href="#">
        <img src="{{ asset('assets/img/favicon.png') }}" width="26" height="26" class="navbar-brand-img">
        <span class="ms-2 text-sm text-dark">UEMS Student</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="w-auto">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('student.events.index') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('student.events.index') }}">
            <i class="material-symbols-rounded opacity-5">event</i>
            <span class="nav-link-text ms-1">Browse Events</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('student.events.my') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('student.events.my') }}">
            <i class="material-symbols-rounded opacity-5">bookmark_added</i>
            <span class="nav-link-text ms-1">My Registrations</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark" href="{{ route('profile.edit') }}">
            <i class="material-symbols-rounded opacity-5">person</i>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
        <li class="nav-item">
          <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="nav-link text-dark bg-transparent border-0">
              <i class="material-symbols-rounded opacity-5">logout</i>
              <span class="nav-link-text ms-1">Logout</span>
            </button>
          </form>
        </li>
      </ul>
    </div>
  </aside>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl">
      <div class="container-fluid py-1 px-3 d-flex justify-content-between">
        <h4 class="font-weight-bolder mb-0">@yield('page-title')</h4>
        <div>Welcome, {{ Auth::user()->name }}</div>
      </div>
    </nav>

    <div class="container-fluid py-4">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      @yield('content')
    </div>
  </main>

  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/material-dashboard.min.js?v=3.2.0') }}"></script>
</body>
</html>

