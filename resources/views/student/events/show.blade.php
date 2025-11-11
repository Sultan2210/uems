@extends('layouts.student')
@section('title', $event->event_name . ' | UEMS')
@section('page-title', $event->event_name)

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card mb-4">
      @if($event->poster)
        <img src="{{ Storage::url($event->poster) }}" class="card-img-top" style="object-fit:cover;max-height:260px;">
      @endif
      <div class="card-body">
        <p><strong>When:</strong> {{ optional($event->start_at)->format('d M Y, h:ia') }} @if($event->end_at) – {{ $event->end_at->format('h:ia') }} @endif</p>
        <p><strong>Where:</strong> {{ $event->location }}</p>
        <p class="mb-0">{{ $event->description }}</p>
      </div>
      <div class="card-footer">
        <strong>Registered:</strong> {{ $currentCount }}@if($event->capacity)/{{ $event->capacity }}@endif
      </div>
    </div>

    @if($alreadyRegistered)
      <div class="card mb-3">
        <div class="card-body d-flex gap-2">
          <form method="POST" action="{{ route('student.events.attend', $event) }}">@csrf
            <button class="btn btn-success">Mark Attended</button>
          </form>
          <form method="POST" action="{{ route('student.events.cancel', $event) }}">@csrf
            <button class="btn btn-outline-danger">Cancel Registration</button>
          </form>
        </div>
      </div>
    @elseif($isFull)
      <div class="alert alert-danger">This event is full.</div>
    @else
      <div class="card">
        <div class="card-header"><strong>Register</strong></div>
        <div class="card-body">
          <form method="POST" action="{{ route('student.events.register', $event) }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">Full name (optional)</label>
              <input type="text" name="full_name" class="form-control" value="{{ old('full_name', Auth::user()->name) }}">
            </div>
            <div class="mb-3">
              <label class="form-label">Matric/Staff No. (optional)</label>
              <input type="text" name="matric_or_staff_no" class="form-control" value="{{ old('matric_or_staff_no') }}">
            </div>
            <div class="mb-3">
              <label class="form-label">Department (optional)</label>
              <input type="text" name="department" class="form-control" value="{{ old('department') }}">
            </div>
            <button class="btn btn-dark">Register</button>
          </form>
        </div>
      </div>
    @endif
  </div>
</div>
@endsection
