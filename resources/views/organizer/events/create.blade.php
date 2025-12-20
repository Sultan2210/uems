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

<div class="container mt-4">
  <div class="card shadow-sm border-0 rounded-3">
    <div class="card-header">
      <h4 class="mb-0">Add New Event</h4>
    </div>
    <div class="card-body">
      <form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Event Title -->
        <div class="mb-3">
          <label class="form-label">Event Title</label>
          <input type="text" name="title" class="form-control" required placeholder="Enter event title">
        </div>

        <!-- Venue -->
        <div class="mb-3">
          <label class="form-label">Venue</label>
          <input type="text" name="venue" class="form-control" required placeholder="Enter venue name">
        </div>

        <!-- Organizer Name -->
        <div class="mb-3">
          <label class="form-label">Organizer Name</label>
          <input type="text" name="organizer_name" class="form-control" required placeholder="Enter organizer name">
        </div>

        <!-- Start and End Time -->
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

        <!-- Event Description -->
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="4" placeholder="Enter event description"></textarea>
        </div>

        <!-- Poster Upload -->
        <div class="mb-3">
          <label class="form-label">Poster (in jpg/jpeg)</label>
          <input type="file" name="poster_path" class="form-control">
        </div>

        <!-- Radio Button for Certificate (Stacked Vertically) -->
        <div class="mb-3">
          <label class="form-label">Provide Certificate for Attendees</label>
          <div class="form-check">
            <input type="radio" name="has_certificate" value="1" class="form-check-input"
                   {{ old('has_certificate') == '1' ? 'checked' : '' }}>
            <label class="form-check-label">Certificate</label>
          </div>
          <div class="form-check">
            <input type="radio" name="has_certificate" value="0" class="form-check-input"
                   {{ old('has_certificate', '0') == '0' ? 'checked' : '' }}>
            <label class="form-check-label">No Certificate</label>
          </div>
        </div>

        <!-- Radio Button for Payment -->

          <label>Does this event require payment?</label>
          <input type="radio" name="requires_payment" value="1"> Payment Required
          <input type="radio" name="requires_payment" value="0"> No Payment


        <!-- QR Code Upload (Appears when Payment is Yes) -->
    <div id="payment_qr" style="display:none;">
        <label>Upload Payment QR Code</label>
        <input type="file" name="payment_qr_code">
    </div>

        <!-- Submit Button at the Bottom -->
        <div class="text-left mt-4">
          <button type="submit" class="btn btn-primary">Add Event</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
  document.querySelectorAll('input[name="requires_payment"]').forEach((input) => {
    input.addEventListener('change', function () {
      if (this.value == '1') {
        document.getElementById('payment_qr').style.display = 'block';
      } else {
        document.getElementById('payment_qr').style.display = 'none';
      }
    });
  });
</script>
@endsection
