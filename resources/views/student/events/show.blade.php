@extends('layouts.student')

@section('title', $event->event_name . ' | iEvent')

@section('content')

@php
    $poster = $event->poster ? \Illuminate\Support\Facades\Storage::url($event->poster) : asset('assets/img/placeholder-event.jpg');
    $fee = $event->fee ?? 0;
    $paymentRequired = ($event->payment_qr_code || $event->requires_payment);
    $price_display = $paymentRequired ? 'Payment needed' : 'Free';
@endphp

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

{{-- Error Messages --}}
@if($errors->any())
<div class="container py-3">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Error!</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif

<div class="container py-5">

    {{-- BACK --}}
    <a href="{{ route('student.events.index') }}"
       class="fw-bold text-decoration-none mb-4 d-inline-block"
       style="color:#1A1A3D">
        <i class="bi bi-arrow-left"></i> Back to Events
    </a>

    {{-- EVENT CARD --}}
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="row g-0" style="min-height: 500px;">
            {{-- POSTER SECTION --}}
            <div class="col-md-5 d-flex align-items-center" style="background-color: #f8f9fa;">
                <img src="{{ $poster }}"
                     class="img-fluid w-100"
                     style="object-fit: contain; max-height: 100%; display: block;"
                     alt="{{ $event->event_name }}">
            </div>

            {{-- DETAILS SECTION --}}
            <div class="col-md-7">
                <div class="p-4 p-md-5 h-100 d-flex flex-column">
                    {{-- EVENT TITLE --}}
                    <h2 class="fw-bold mb-4" style="color:#1A1A3D; line-height:1.3;">
                        {{ $event->event_name }}
                    </h2>

                    {{-- EVENT INFORMATION CARDS --}}
                    <div class="mb-4">
                        {{-- DATE & TIME --}}
                        @if($event->start_at)
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-light rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-calendar-event text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-muted mb-1">Date & Time</div>
                                <div class="fw-semibold">
                                    {{ optional($event->start_at)->format('l, d F Y') }}
                                </div>
                                @if($event->start_at && $event->end_at)
                                <div class="text-muted small">
                                    {{ optional($event->start_at)->format('h:i A') }} - {{ optional($event->end_at)->format('h:i A') }}
                                </div>
                                @elseif($event->start_at)
                                <div class="text-muted small">
                                    {{ optional($event->start_at)->format('h:i A') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- LOCATION --}}
                        @if($event->location)
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-light rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-geo-alt-fill text-danger"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-muted mb-1">Venue</div>
                                <div class="fw-semibold">{{ $event->location }}</div>
                            </div>
                        </div>
                        @endif

                        {{-- CAPACITY (if available) --}}
                        @if($event->capacity)
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-light rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-people-fill text-info"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-muted mb-1">Capacity</div>
                                <div class="fw-semibold">{{ $event->capacity }} seats</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- DIVIDER --}}
                    <hr class="my-4">

                    {{-- DESCRIPTION --}}
                    <div class="flex-grow-1">
                        <h5 class="fw-bold mb-3" style="color:#1A1A3D;">About This Event</h5>
                        <div class="text-muted" style="line-height:1.8; white-space: pre-wrap; word-wrap: break-word;">
                            {{ $event->description }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ACTION AREA --}}
    <div class="mt-5">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="text-center">

                    {{-- 1️⃣ SUDAH ATTEND --}}
                    @if($alreadyAttended)
                        <div class="mb-3">
                            <span class="badge bg-success px-4 py-2 fs-6">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                Attended
                            </span>
                        </div>
                        <div>
                            <a href="{{ route('student.feedback.index') }}"
                               class="btn btn-dark px-5 py-2 fw-semibold">
                                <i class="bi bi-chat-square-text-fill me-2"></i>
                                Give Feedback
                            </a>
                        </div>

                    {{-- 2️⃣ SUDAH REGISTER --}}
                    @elseif($alreadyRegistered)
                        <div class="mb-3">
                            <div class="alert alert-success border-0 mb-0" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <strong>You are registered for this event.</strong>
                            </div>
                        </div>
                        @if($canMarkAttendance)
                            <form method="POST" id="attendForm"
                                  action="{{ route('student.events.attend', $event->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-warning px-5 py-2 fw-bold" id="attendButton">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Mark as Attended
                                </button>
                            </form>
                        @else
                            @if($event->start_at && now()->lessThan($event->start_at))
                                <div class="alert alert-info border-0 mb-0" role="alert">
                                    <i class="bi bi-clock me-2"></i>
                                    <strong>Event has not started yet.</strong> You can mark attendance once the event begins.
                                </div>
                            @else
                                <div class="alert alert-warning border-0 mb-0" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Attendance window has closed.</strong> You can only mark attendance during the event or within 15 minutes after it ends.
                                </div>
                            @endif
                        @endif

                    {{-- 3️⃣ EVENT FULL --}}
                    @elseif($isFull)
                        <div class="alert alert-warning border-0 mb-0" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>This event is full.</strong> No more registrations available.
                        </div>

                    {{-- 4️⃣ BELUM REGISTER --}}
                    @else
                        <div class="mb-3">
                            <p class="text-muted mb-0">Ready to join this event?</p>
                        </div>
                        <button type="button"
                                class="btn fw-bold px-5 py-3"
                                style="background:#F7C948;color:#1A1A3D; border:none;"
                                data-bs-toggle="modal"
                                data-bs-target="#registerModal">
                            <i class="bi bi-calendar-check-fill me-2"></i>
                            Register Now
                        </button>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL REGISTER ================= --}}
@if(!$alreadyRegistered && !$isFull)
<div class="modal fade" id="registerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">

            <form method="POST"
                  action="{{ route('student.events.register', $event->id) }}"
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
                               value="{{ Auth::user()->name }}" required readonly>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold small">Matric Number <span class="text-danger">*</span></label>
                        <input type="text" name="matric_or_staff_no"
                               class="form-control"
                               value="{{ Auth::user()->matric_no ?? '' }}"
                               required readonly>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold small">Kulliyyah <span class="text-danger">*</span></label>
                        <input type="text" name="department"
                               class="form-control"
                               value="{{ Auth::user()->kulliyyah ?? '' }}"
                               placeholder="Enter your kulliyyah"
                               required readonly>
                    </div>

                    {{-- PAYMENT SECTION --}}
                    @if($paymentRequired && $event->payment_qr_code)
                        <hr>
                        <div class="mb-4">
                            <label class="fw-bold small mb-2">Payment QR Code</label>
                            <div class="text-center mb-3 p-3 bg-light rounded">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($event->payment_qr_code) }}"
                                     alt="Payment QR Code"
                                     width="200"
                                     class="img-fluid">
                                <p class="small text-muted mt-2 mb-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Scan QR code to make payment
                                </p>
                            </div>

                            <label class="fw-bold small mb-2">
                                Upload Payment Receipt <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                   name="payment_receipt"
                                   class="form-control"
                                   accept="image/jpeg,image/png,image/jpg,image/gif"
                                   required>
                            <small class="text-muted">Please upload your payment receipt (JPEG, PNG, JPG, GIF - Max 2MB)</small>
                        </div>
                    @endif

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 d-flex justify-content-between">
                    <div class="fw-bold">
                        Total:
                        <span class="text-success">{{ $price_display }}</span>
                    </div>

                    <button type="submit" class="btn text-white px-4" style="background:#1A1A3D">
                        Confirm Register
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endif

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
