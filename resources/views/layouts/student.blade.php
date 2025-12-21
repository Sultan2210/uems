<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','iEvent')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
        }

        /* ================= NAVBAR ================= */
        .navbar-custom {
            background: #2B293D;
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 1050;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 24px;
            color: #fff !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link {
            color: #d1d1d1 !important;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 18px !important;
            border-radius: 6px;
            transition: .25s;
        }

        .nav-link:hover {
            color: #fff !important;
        }

        .nav-link.active {
            background: #F7C948;
            color: #2B293D !important;
            font-weight: 600;
        }

        /* ================= BUTTON ================= */
        .btn-logout {
            border: 1px solid #F7C948;
            color: #F7C948;
            background: transparent;
            padding: 6px 22px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-logout:hover {
            background: #F7C948;
            color: #2B293D;
        }
    </style>
</head>
<body>

<!-- ================= HEADER ================= -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">

        <!-- LOGO -->
        <a class="navbar-brand" href="{{ route('student.events.index') }}">
            <img src="{{ asset('assets/img/favicon.png') }}" style="height:28px;">
            <span>iEvent</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon" style="filter:invert(1)"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">

                <!-- HOME -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('student.dashboard') ? 'active' : '' }}"
                       href="{{ route('student.dashboard') }}">
                        Home
                    </a>
                </li>

                <!-- EVENT -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('student.events.index') || Route::is('student.events.show') ? 'active' : '' }}"
                       href="{{ route('student.events.index') }}">
                        Event
                    </a>
                </li>

                <!-- MY REGISTERED EVENTS -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('student.events.my') ? 'active' : '' }}"
                       href="{{ route('student.events.my') }}">
                        My Registered Event
                    </a>
                </li>

                <!-- FEEDBACK (LIST ATTENDED SAHAJA) -->
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('student.feedback.*') ? 'active' : '' }}"
                       href="{{ route('student.feedback.index') }}">
                        Feedback
                    </a>
                </li>

                <!-- USER -->
                <li class="nav-item ms-3 d-flex align-items-center gap-3">
                    <span class="text-white small d-none d-lg-block">
                        Hi, {{ Auth::user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn-logout">Log Out</button>
                    </form>
                </li>

            </ul>
        </div>
    </div>
</nav>


<!-- ================= CONTENT ================= -->
<main style="min-height: calc(100vh - 140px);">
    @yield('content')
</main>

<!-- ================= FOOTER ================= -->
<footer class="bg-white border-top py-4">
    <div class="container text-center text-muted small">
        &copy; {{ date('Y') }} iEvent. All rights reserved.
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
