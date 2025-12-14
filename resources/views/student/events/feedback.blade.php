@extends('layouts.student')

@section('title','Event Feedback')

@section('content')
<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- CARD --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    {{-- TITLE --}}
                    <h4 class="fw-bold mb-2">Event Feedback</h4>
                    <p class="text-muted small mb-4">
                        {{ $event->event_name }}
                    </p>

                    {{-- FORM --}}
                    <form method="POST"
                          action="{{ route('student.events.feedback.store', $event->id) }}">
                        @csrf

                        {{-- RATING --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                Rate this event
                            </label>

                            <div class="d-flex gap-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="rating"
                                               id="rate{{ $i }}"
                                               value="{{ $i }}"
                                               required>
                                        <label class="form-check-label"
                                               for="rate{{ $i }}">
                                            {{ $i }}
                                        </label>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        {{-- COMMENT --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                Your Feedback
                            </label>
                            <textarea name="comment"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Share your experience..."
                                      required></textarea>
                        </div>

                        {{-- BUTTON --}}
                        <div class="d-flex justify-content-end">
                            <button type="submit"
                                    class="btn px-4 text-white"
                                    style="background:#1A1A3D">
                                Submit Feedback
                                <i class="bi bi-send ms-1"></i>
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
