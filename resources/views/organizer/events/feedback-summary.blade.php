@extends('layouts.organizer')

@section('title', 'Feedback Summary | iEvent')
@section('page-title', 'Feedback Summary')

@section('content')
<div class="container-fluid py-3">

  <div class="card shadow-sm border-0 rounded-3">
    <div class="card-body">

      <form method="GET" action="{{ route('organizer.feedback.summary') }}" class="row g-3 align-items-end">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Select Approved Event</label>
          <select name="event_id" class="form-select" required>
            <option value="">-- Choose Event --</option>
            @foreach($events as $ev)
              <option value="{{ $ev->id }}" {{ request('event_id') == $ev->id ? 'selected' : '' }}>
                {{ $ev->event_name }} ({{ optional($ev->start_at)->format('d M Y') }})
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3">
          <button class="btn btn-primary w-100" type="submit">
            View Feedback
          </button>
        </div>
      </form>

      <hr class="my-4">

      @if(!$selectedEvent)
        <div class="alert alert-info mb-0">
          Please select an event to view feedback summary.
        </div>
      @else

        <h6 class="fw-bold mb-3">
          Feedback for: <span class="text-primary">{{ $selectedEvent->event_name }}</span>
        </h6>

        @if($feedbacks->count() === 0)
          <div class="alert alert-warning mb-0">
            No feedback submitted yet for this event.
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-bordered align-middle">
              <thead class="table-light">
                <tr>
                  <th style="width:60px;">#</th>
                  <th>Student Name</th>
                  <th>Matric/Staff No</th>
                  <th>Email</th>
                  <th>Comments</th>
                  <th style="width:170px;">Submitted At</th>
                </tr>
              </thead>
              <tbody>
                @foreach($feedbacks as $i => $fb)
                  <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $fb->student_name }}</td>
                    <td>{{ $fb->matric_number ?? '-' }}</td>
                    <td>{{ $fb->email ?? '-' }}</td>
                    <td style="white-space: normal;">{{ $fb->comments }}</td>
                    <td>{{ optional($fb->created_at)->format('d M Y, h:i A') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif

      @endif

    </div>
  </div>

</div>
@endsection
