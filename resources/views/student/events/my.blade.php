@extends('layouts.student')

@section('title', 'My Registered Events | iEvent')

@section('content')
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
        <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">

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
<div class="mt-auto d-grid gap-2">
    <a href="{{ route('student.events.show', $event->id) }}"
       class="btn btn-outline-dark btn-sm fw-semibold">
        View Event
    </a>

    {{-- ALWAYS show both buttons --}}
    <form method="POST" action="{{ route('student.events.attend', $event->id) }}">
        @csrf
        <button type="submit" class="btn btn-warning btn-sm fw-semibold">
            Mark as Attended
        </button>
    </form>

    <a href="{{ route('student.feedback.form', $event->id) }}"
   class="btn btn-success btn-sm fw-semibold">
    Give Feedback
</a>

</div>


            </div>
        </div>
    </div>
@endforeach


    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">
        {{ $registrations->links() }}
    </div>

</div>
@endsection
