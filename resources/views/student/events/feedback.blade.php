@extends('layouts.student')

@section('title','Feedback | iEvent')

@section('content')
@if(session('success'))
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;">
    <div id="feedbackToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong class="me-auto">Success</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif

<div class="container py-5">
    {{-- Page Header --}}
    <div class="mb-4">
        <h3 class="fw-bold mb-2" style="color:#1A1A3D">
            <i class="bi bi-chat-square-text-fill me-2"></i>
            Give Feedback
        </h3>
        <p class="text-muted">Share your thoughts and experiences about the events you've attended.</p>
    </div>

    {{-- Events Grid --}}
    @forelse($registrations as $reg)
        @php
            $event = $reg->event;
            $poster = $event->poster
                ? \Illuminate\Support\Facades\Storage::url($event->poster)
                : asset('assets/img/placeholder-event.jpg');
        @endphp

        <div class="card border-0 shadow-sm mb-4" style="border-radius:16px; overflow:hidden;">
            <div class="row g-0">
                {{-- Event Poster --}}
                <div class="col-md-4">
                    <img src="{{ $poster }}"
                         class="w-100 h-100"
                         style="object-fit:cover; min-height:200px;"
                         alt="{{ $event->event_name }}">
                </div>

                {{-- Event Details --}}
                <div class="col-md-8">
                    <div class="card-body p-4">
                        {{-- Event Name --}}
                        <h5 class="fw-bold mb-3" style="color:#1A1A3D">
                            {{ $event->event_name }}
                        </h5>

                        {{-- Event Info --}}
                        <div class="mb-3">
                            @if($event->start_at)
                            <div class="d-flex align-items-center mb-2 text-muted">
                                <i class="bi bi-calendar-event me-2"></i>
                                <span>{{ optional($event->start_at)->format('d M Y, h:i A') }}</span>
                            </div>
                            @endif

                            @if($event->location)
                            <div class="d-flex align-items-center mb-2 text-muted">
                                <i class="bi bi-geo-alt-fill me-2"></i>
                                <span>{{ $event->location }}</span>
                            </div>
                            @endif

                            <div class="d-flex align-items-center text-muted">
                                <i class="bi bi-check-circle-fill me-2 text-success"></i>
                                <span>You attended this event</span>
                            </div>
                        </div>

                        {{-- Description Preview --}}
                        @if($event->description)
                        <p class="text-muted small mb-3" style="line-height:1.6;">
                            {{ Str::limit(strip_tags($event->description), 150) }}
                        </p>
                        @endif

                        {{-- Action Button --}}
                        <div class="mt-auto">
                            <button type="button"
                                    class="btn btn-dark px-4 py-2 fw-semibold"
                                    data-bs-toggle="modal"
                                    data-bs-target="#feedbackModal"
                                    data-event-id="{{ $event->id }}"
                                    data-event-name="{{ $event->event_name }}">
                                <i class="bi bi-pencil-square me-2"></i>
                                Give Feedback
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #dee2e6;"></i>
            </div>
            <h5 class="fw-bold mb-2" style="color:#1A1A3D">No Attended Events Yet</h5>
            <p class="text-muted mb-4">You haven't attended any events yet. Attend an event to provide feedback.</p>
            <a href="{{ route('student.events.index') }}" class="btn btn-dark px-4 py-2">
                <i class="bi bi-calendar-event me-2"></i>
                Browse Events
            </a>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($registrations->hasPages())
    <div class="mt-4">
        {{ $registrations->links() }}
    </div>
    @endif
</div>

{{-- Feedback Modal --}}
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <form id="feedbackForm" method="POST" action="">
                @csrf

                {{-- HEADER --}}
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title fw-bold mb-1" id="feedbackModalLabel" style="color:#1A1A3D">
                            <i class="bi bi-chat-square-text-fill me-2"></i>
                            Share Your Feedback
                        </h5>
                        <p class="text-muted small mb-0" id="modalEventName"></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- BODY --}}
                <div class="modal-body pt-3">
                    {{-- MATRIC NUMBER --}}
                    <div class="mb-4">
                        <label for="matric_number" class="fw-bold small mb-2">
                            Matric Number <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="matric_number"
                               id="matric_number"
                               class="form-control form-control-lg"
                               placeholder="Enter your matric number"
                               value="{{ Auth::user()->matric_no ?? '' }}"
                               required
                               maxlength="255">
                    </div>

                    {{-- FEEDBACK TEXTAREA --}}
                    <div class="mb-3">
                        <label for="feedback" class="fw-bold small mb-2">
                            Your Feedback <span class="text-danger">*</span>
                        </label>
                        <textarea name="feedback"
                                  id="feedback"
                                  class="form-control"
                                  rows="6"
                                  placeholder="Please share your thoughts, experiences, and suggestions about this event. Your feedback helps us improve future events..."
                                  required
                                  maxlength="1000"
                                  style="resize: vertical;"></textarea>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Maximum 1000 characters
                            </small>
                            <small class="text-muted" id="charCount">0 / 1000</small>
                        </div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-dark px-4 fw-semibold">
                        <i class="bi bi-send-fill me-2"></i>
                        Submit Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toastElement = document.getElementById('feedbackToast');
    if (toastElement) {
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: 5000
        });
        toast.show();
    }
});
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const feedbackModal = document.getElementById('feedbackModal');
    const feedbackForm = document.getElementById('feedbackForm');
    const modalEventName = document.getElementById('modalEventName');
    const feedbackTextarea = document.getElementById('feedback');
    const charCount = document.getElementById('charCount');
    const matricNumberField = document.getElementById('matric_number');

    // Character counter for feedback textarea
    if (feedbackTextarea && charCount) {
        feedbackTextarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length + ' / 1000';
            if (length > 1000) {
                charCount.classList.add('text-danger');
            } else {
                charCount.classList.remove('text-danger');
            }
        });
    }

    feedbackModal.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        const button = event.relatedTarget;

        // Extract info from data-bs-* attributes
        const eventId = button.getAttribute('data-event-id');
        const eventName = button.getAttribute('data-event-name');

        // Update the modal's content
        modalEventName.textContent = eventName;

        // Update form action
        feedbackForm.action = '{{ route("student.feedback.store", ":id") }}'.replace(':id', eventId);

        // Clear the textarea and reset character count
        feedbackTextarea.value = '';
        if (charCount) {
            charCount.textContent = '0 / 1000';
            charCount.classList.remove('text-danger');
        }

        // Pre-fill matric number if available, otherwise clear it
        const userMatricNo = '{{ Auth::user()->matric_no ?? "" }}';
        if (matricNumberField) {
            matricNumberField.value = userMatricNo || '';
        }
    });
});
</script>
@endsection
