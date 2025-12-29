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
    <h3 class="fw-bold mb-4">FEEDBACK-ONLY ATTENDED EVENTS</h3>

    @forelse($registrations as $reg)
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="fw-bold">{{ $reg->event->event_name }}</h6>
                <button type="button"
                        class="btn btn-sm btn-dark mt-2"
                        data-bs-toggle="modal"
                        data-bs-target="#feedbackModal"
                        data-event-id="{{ $reg->event->id }}"
                        data-event-name="{{ $reg->event->event_name }}">
                    Give Feedback
                </button>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">
            No attended events yet.
        </div>
    @endforelse

    {{ $registrations->links() }}
</div>

{{-- Feedback Modal --}}
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <form id="feedbackForm" method="POST" action="">
                @csrf

                {{-- HEADER --}}
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="feedbackModalLabel">Give Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- BODY --}}
                <div class="modal-body">
                    {{-- EVENT NAME --}}
                    <div class="p-3 bg-light rounded mb-4">
                        <label class="fw-bold small text-muted mb-1">Event Name</label>
                        <div class="fw-bold" id="modalEventName"></div>
                    </div>

                    {{-- MATRIC NUMBER --}}
                    <div class="mb-3">
                        <label for="matric_number" class="fw-bold small">Matric Number</label>
                        <input type="text"
                               name="matric_number"
                               id="matric_number"
                               class="form-control"
                               placeholder="Enter your matric number"
                               required
                               maxlength="255">
                    </div>

                    {{-- FEEDBACK TEXTAREA --}}
                    <div class="mb-3">
                        <label for="feedback" class="fw-bold small">Your Feedback</label>
                        <textarea name="feedback"
                                  id="feedback"
                                  class="form-control"
                                  rows="5"
                                  placeholder="Please share your feedback about this event..."
                                  required
                                  maxlength="1000"></textarea>
                        <small class="text-muted">Maximum 1000 characters</small>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">Submit Feedback</button>
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

        // Clear the textarea and matric number field
        document.getElementById('feedback').value = '';
        document.getElementById('matric_number').value = '';
    });
});
</script>
@endsection
