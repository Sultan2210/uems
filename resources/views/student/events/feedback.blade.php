@extends('layouts.student')

@section('title','Feedback | iEvent')

@section('content')
<div class="container py-5">
    <h3 class="fw-bold mb-4">Feedback</h3>

    @forelse($registrations as $reg)
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="fw-bold">{{ $reg->event->event_name }}</h6>
                <a href="{{ route('student.feedback.form', $reg->event->id) }}"
                   class="btn btn-sm btn-dark mt-2">
                    Give Feedback
                </a>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">
            No attended events yet.
        </div>
    @endforelse

    {{ $registrations->links() }}
</div>
@endsection
