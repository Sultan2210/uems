@extends('layouts.student')

@section('title', 'My Registered Events | iEvent')

@section('content')
{{-- Success Toast --}}
@if(session('success_attended'))
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;">
    <div id="attendanceToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong class="me-auto">Success</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            You have successfully attended this event.
        </div>
    </div>
</div>
@endif

<div class="container py-5">

    <h3 class="fw-bold mb-4" style="color:#1A1A3D">
        My Registered Events
    </h3>

    {{-- JIKA TIADA REGISTRATION --}}
    @if($registrations->count() === 0)
        <div class="alert alert-info text-center">
            You have not registered for any events yet.
        </div>
    @endif

    <div class="row g-4">
        @foreach ($registrations as $reg)
    @php
        $event = $reg->event;
        $poster = $event->poster
            ? Storage::url($event->poster)
            : asset('assets/img/placeholder-event.jpg');
    @endphp

    <div class="col-md-6 col-lg-4">
        <a href="{{ route('student.events.show', $event->id) }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100" style="border-radius:16px; cursor:pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">

                {{-- POSTER --}}
                <img src="{{ $poster }}"
                     class="card-img-top"
                     style="height:180px; object-fit:cover; border-radius:16px 16px 0 0;">

                <div class="card-body d-flex flex-column">

                    <h6 class="fw-bold mb-1">
                        {{ $event->event_name }}
                    </h6>

                    <div class="text-muted small mb-2">
                        <i class="bi bi-calendar-event"></i>
                        {{ optional($event->start_at)->format('d M Y') }}
                    </div>

                    {{-- STATUS --}}
                    <div class="mb-2">
                        @if($reg->status === 'attended')
                            <span class="badge bg-success">Attended</span>
                        @elseif($reg->status === 'registered')
                            <span class="badge bg-warning text-dark">Registered</span>
                        @else
                            <span class="badge bg-secondary">
                                {{ ucfirst($reg->status) }}
                            </span>
                        @endif
                    </div>

                    {{-- ACTION --}}
                    <div class="mt-auto d-grid">
                        <button class="btn btn-outline-dark btn-sm fw-semibold">
                            View Event
                        </button>
                    </div>

                </div>
            </div>
        </a>
    </div>
@endforeach


    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $registrations->links() }}
    </div>

</div>

@if(session('success_attended'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toastElement = document.getElementById('attendanceToast');
    if (toastElement) {
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: 5000
        });
    }
});
</script>
@endif
@endsection
