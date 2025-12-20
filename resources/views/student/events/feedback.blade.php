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
                    <form action="{{ route('student.events.feedback.store', $event->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="feedback" class="form-label">Your Feedback</label>
                        <textarea name="feedback" class="form-control" rows="5" placeholder="Write your feedback here..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                </form>


                </div>
            </div>

        </div>
    </div>

</div>
@endsection
