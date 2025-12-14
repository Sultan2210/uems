@extends('layouts.student')

@section('title', 'My Registered Events | iEvent')

@section('content')
<div class="container py-5">

    <h3 class="fw-bold mb-4" style="color:#1A1A3D">
        My Registered Events
    </h3>

    @if($regs->count() === 0)
        <div class="alert alert-info text-center">
            You have not registered for any events yet.
        </div>
    @endif

    <div class="row g-4">
        @foreach($regs as $reg)
            @php
                $event = $reg->event;
                $poster = $event->poster
                    ? Storage::url($event->poster)
                    : asset('assets/img/placeholder-event.jpg');
            @endphp

            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius:16px;">

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

                        <div class="mb-2">
                            @if($reg->status === 'attended')
                                <span class="badge bg-success">Attended</span>
                            @elseif($reg->status === 'registered')
                                <span class="badge bg-warning text-dark">Registered</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($reg->status) }}</span>
                            @endif
                        </div>

                        <div class="mt-auto d-grid gap-2">
                            <a href="{{ route('student.events.show', $event->id) }}"
                               class="btn btn-outline-dark btn-sm fw-semibold">
                                View Event
                            </a>

                            @if($reg->status === 'attended')
                                <a href="{{ route('student.events.feedback', $event->id) }}"
                                   class="btn btn-success btn-sm fw-semibold">
                                    Give Feedback
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $regs->links() }}
    </div>

</div>
@endsection
