@extends('layouts.student')
@section('title', $event->event_name . ' | iEvent')

@section('content')

@php
    $poster = $event->poster ? \Illuminate\Support\Facades\Storage::url($event->poster) : asset('assets/img/placeholder-event.jpg');
    $fee = $event->fee ?? 0;
    $price_display = $fee > 0 ? 'RM '.number_format($fee,2) : 'Free';
@endphp

<div class="container py-5">

    {{-- BACK --}}
    <a href="{{ route('student.events.index') }}"
       class="fw-bold text-decoration-none mb-4 d-inline-block"
       style="color:#1A1A3D">
        <i class="bi bi-arrow-left"></i> Back to Events
    </a>

    {{-- EVENT CARD --}}
    <div class="card shadow border-0 rounded-4 overflow-hidden">
        <div class="row g-0">
            <div class="col-md-5">
                <img src="{{ $poster }}" class="w-100 h-100" style="object-fit:cover">
            </div>
            <div class="col-md-7 p-4">
                <h3 class="fw-bold">{{ $event->event_name }}</h3>

                <p class="text-muted mb-2">
                    <i class="bi bi-calendar-event"></i>
                    {{ optional($event->start_at)->format('d M Y') }}
                </p>

                <p class="text-muted mb-2">
                    <i class="bi bi-geo-alt"></i>
                    {{ $event->location }}
                </p>

                <p class="mt-3">{{ $event->description }}</p>
            </div>
        </div>
    </div>

    {{-- ACTION AREA --}}
    <div class="text-center mt-5">

        {{-- 1️⃣ SUDAH ATTEND --}}
        @if($alreadyAttended)
            <span class="badge bg-success px-4 py-2 fs-6">
                Attended
            </span>

            <div class="mt-3">
                <a href="{{ route('student.events.feedback', $event->id) }}"
                   class="btn btn-dark px-5 py-2">
                    Give Feedback
                </a>
            </div>

        {{-- 2️⃣ SUDAH REGISTER --}}
        @elseif($alreadyRegistered)
            <p class="fw-bold text-success">
                You are registered for this event.
            </p>

            <form method="POST"
                  action="{{ route('student.events.attend', $event->id) }}">
                @csrf
                <button class="btn btn-warning px-5 py-2 fw-bold">
                    Mark as Attended
                </button>
            </form>

        {{-- 3️⃣ EVENT FULL --}}
        @elseif($isFull)
            <button disabled class="btn btn-secondary px-5 py-2">
                Event Full
            </button>

        {{-- 4️⃣ BELUM REGISTER --}}
        @else
            <button class="btn fw-bold px-5 py-3"
                    style="background:#F7C948;color:#1A1A3D"
                    data-bs-toggle="modal"
                    data-bs-target="#registerModal">
                Register Now
            </button>
        @endif

    </div>
</div>

{{-- ================= MODAL REGISTER ================= --}}
@if(!$alreadyRegistered && !$isFull)
<div class="modal fade" id="registerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">

            <form method="POST"
                  action="{{ $fee > 0 ? route('student.events.payment',$event->id)
                                       : route('student.events.register',$event->id) }}"
                  enctype="multipart/form-data">
                @csrf

                {{-- HEADER --}}
                <div class="modal-header border-0">
                    <h5 class="fw-bold">Attendee Details</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                {{-- BODY --}}
                <div class="modal-body">

                    {{-- EVENT INFO --}}
                    <div class="p-3 bg-light rounded mb-4 d-flex justify-content-between">
                        <div>
                            <div class="fw-bold">{{ $event->event_name }}</div>
                            <small class="text-muted">Standard Ticket</small>
                        </div>
                        <small class="text-muted">
                            {{ optional($event->start_at)->format('d M Y') }}
                        </small>
                    </div>

                    {{-- FORM --}}
                    <div class="mb-3">
                        <label class="fw-bold small">Full Name</label>
                        <input type="text" name="full_name"
                               class="form-control"
                               value="{{ Auth::user()->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold small">Matric Number</label>
                        <input type="text" name="matric_or_staff_no"
                               class="form-control"
                               value="{{ Auth::user()->matric_no ?? '' }}">
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold small">Phone</label>
                        <input type="text" name="phone"
                               class="form-control"
                               value="{{ Auth::user()->phone ?? '' }}">
                    </div>

                    {{-- JIKA BERBAYAR --}}
                    @if($fee > 0)
                        <hr>
                        <div class="text-center mb-3">
                            <img src="{{ asset('assets/img/sample-qr.png') }}"
                                 width="200">
                            <p class="small text-muted mt-2">
                                Scan QR & upload receipt
                            </p>
                        </div>

                        <input type="file"
                               name="payment_receipt"
                               class="form-control" required>
                    @endif

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 d-flex justify-content-between">
                    <div class="fw-bold">
                        Total:
                        <span class="text-success">{{ $price_display }}</span>
                    </div>

                    <button class="btn text-white px-4"
                            style="background:#1A1A3D">
                        {{ $fee > 0 ? 'Proceed Payment' : 'Confirm Register' }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endif

@endsection
