@extends('layouts.student')

@section('title','Student Dashboard | iEvent')

@section('content')
<div class="container py-5">

    <!-- PAGE TITLE -->
    <div class="mb-4">
        <h3 class="fw-bold">Student Dashboard</h3>
        <p class="text-muted mb-0">
            Welcome back, {{ Auth::user()->name }} 👋
        </p>
    </div>

    <!-- STATS -->
    <div class="row g-4 mb-5">

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center"
                         style="width:50px;height:50px;">
                        <i class="bi bi-calendar-event fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Total Events</h6>
                        <h4 class="mb-0">{{ $totalEvents ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                         style="width:50px;height:50px;">
                        <i class="bi bi-check-circle fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Registered Events</h6>
                        <h4 class="mb-0">{{ $registeredEvents ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                         style="width:50px;height:50px;">
                        <i class="bi bi-star fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-semibold">Attended Events</h6>
                        <h4 class="mb-0">{{ $attendedEvents ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- QUICK ACTIONS -->
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Quick Actions</h5>

            <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('student.events.index') }}" class="btn btn-warning fw-semibold">
                    <i class="bi bi-search me-1"></i> Browse Events
                </a>

                <a href="{{ route('student.events.my') }}" class="btn btn-outline-secondary fw-semibold">
                    <i class="bi bi-ticket-perforated me-1"></i> My Registered Events
                </a>

                <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary fw-semibold">
                    <i class="bi bi-person me-1"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>

    <!-- RECENT EVENTS -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-semibold mb-0">Recent Registered Events</h5>

                <a href="{{ route('student.events.my') }}" class="text-decoration-none small fw-semibold">
                    View All →
                </a>
            </div>

            @if(isset($recentEvents) && $recentEvents->count())
                <div class="list-group list-group-flush">
                    @foreach($recentEvents as $event)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $event->title }}</div>
                                <div class="small text-muted">
                                    {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                                </div>
                            </div>

                            <span class="badge bg-success">Registered</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-muted text-center py-4">
                    You have not registered for any events yet.
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
