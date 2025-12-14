@extends('layouts.student')
@section('title','Browse Events | iEvent')

@section('content')

@php
    function poster_url($event) {
        if (!empty($event->poster)) {
            try { return Storage::url($event->poster); } catch (\Throwable $e) {}
        }
        return asset('assets/img/placeholder-event.jpg');
    }
@endphp

{{-- ================= HERO SECTION ================= --}}
<div class="w-100 position-relative"
     style="
        background:url('{{ asset('assets/img/audience-1853662_1280.jpg') }}') center/cover no-repeat;
        height:420px;
     ">

    <div style="position:absolute;inset:0;background:rgba(0,0,0,.55)"></div>

    <div class="container position-relative text-white h-100 d-flex align-items-center">
        <div style="max-width:780px">

            <h2 class="fw-bold" style="font-size:42px">
                Don’t miss out!
            </h2>

            <p class="fs-5 mb-4">
                Explore the <span style="color:#F7C948">vibrant events</span> happening in IIUM
            </p>

            {{-- SEARCH --}}
            <form method="GET"
                  action="{{ route('student.events.index') }}"
                  class="d-flex gap-2">

                <input type="text"
                       name="search"
                       class="form-control form-control-lg"
                       placeholder="Search events..."
                       value="{{ request('search') }}">

                <button class="btn btn-lg text-white px-4"
                        style="background:#1A1A3D">
                    Search
                </button>
            </form>

        </div>
    </div>
</div>

{{-- ================= EVENT LIST ================= --}}
<div class="container py-5">

    <h3 class="fw-bold mb-4">Events</h3>

    <div class="row g-4">

        @forelse($events as $event)
            <div class="col-md-4">

                {{-- CLICKABLE CARD --}}
                <a href="{{ route('student.events.show',$event->id) }}"
                   class="text-decoration-none text-dark">

                    <div class="card h-100 shadow-sm border-0 rounded-4">

                        {{-- IMAGE --}}
                        <div style="height:180px;overflow:hidden">
                            <img src="{{ poster_url($event) }}"
                                 class="w-100 h-100"
                                 style="object-fit:cover">
                        </div>

                        {{-- BODY --}}
                        <div class="card-body">

                            <p class="small text-muted mb-1">
                                {{ optional($event->start_at)->format('d M Y') }}
                            </p>

                            <h5 class="fw-bold">
                                {{ $event->event_name }}
                            </h5>

                            <p class="text-muted mb-1">
                                <i class="bi bi-geo-alt"></i>
                                {{ $event->location }}
                            </p>

                            <p class="text-muted small">
                                Registered:
                                {{ $event->registrations_count }}
                                @if($event->capacity)
                                    / {{ $event->capacity }}
                                @endif
                            </p>
                        </div>

                        {{-- FOOTER --}}
                        <div class="card-footer bg-white border-0 pb-4">
                            <span class="btn btn-dark w-100 rounded-pill">
                                View & Register
                            </span>
                        </div>

                    </div>
                </a>
            </div>

        @empty
            <div class="col-12 text-center">
                <div class="alert alert-secondary">
                    No events available.
                </div>
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $events->links() }}
    </div>
</div>

@endsection
