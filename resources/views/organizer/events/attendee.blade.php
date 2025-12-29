@extends('layouts.organizer')

@section('title','Attendee List')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold mb-4">Attendee List</h4>

    {{-- SELECT EVENT --}}
    <form method="GET" action="{{ route('organizer.attendees') }}" class="mb-4">
        <div class="row align-items-end g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Select Approved Event
                </label>
                <select name="event_id" class="form-select" required>
                    <option value="">-- Choose Event --</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}"
                            {{ optional($selectedEvent)->id == $event->id ? 'selected' : '' }}>
                            {{ $event->event_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <li>
            <div class="col-md-3">
                <button class="btn btn-primary w-100">
                    View Attendees
                </button>
            </div>
            </li>
        </div>
    </form>

    {{-- ATTENDEE TABLE --}}
    @if($selectedEvent)
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <h6 class="fw-bold mb-3">
                    Event: {{ $selectedEvent->event_name }}
                </h6>

                @if($registrations->isEmpty())
                    <div class="alert alert-info">
                        No students have registered for this event yet.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Matric / Staff No</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registrations as $index => $reg)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $reg->full_name }}</td>
                                        <td>{{ $reg->matric_or_staff_no }}</td>
                                        <td>{{ optional($reg->user)->email ?? '-' }}</td>
                                        <td>
                                            <span class="badge
                                                {{ $reg->status === 'attended'
                                                    ? 'bg-success'
                                                    : 'bg-warning text-dark' }}">
                                                {{ ucfirst($reg->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    @endif

</div>
@endsection
