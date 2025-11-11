@extends('layouts.organizer')
@section('title', 'Add Event | UEMS')
@section('page-title', 'Add Event')

@section('content')
@if (session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 10px;">
    <strong>Success!</strong> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="card">
  <div class="card-header">
    <h4>Add New Event</h4>
  </div>
  <div class="card-body">
    <form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label class="form-label">Event Title</label>
        <input type="text" name="title" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Venue</label>
        <input type="text" name="venue" class="form-control" required>
      </div>

      <div class="mb-3">
  <label class="form-label">Organizer Name</label>
  <input type="text" name="organizer_name" class="form-control" required>
</div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Start Time</label>
          <input type="datetime-local" name="start_time" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">End Time</label>
          <input type="datetime-local" name="end_time" class="form-control" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Poster (optional)</label>
        <input type="file" name="poster_path" class="form-control">
      </div>

      <div class="mb-3 form-check">
        <input type="checkbox" name="has_certificate" class="form-check-input" id="has_certificate">
        <label class="form-check-label" for="has_certificate">Provide Certificate for Attendees</label>
      </div>

      <button type="submit" class="btn btn-dark">Add Event</button>
    </form>
  </div>
</div>
@endsection
