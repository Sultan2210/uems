@extends('layouts.organizer')

@section('title', 'My Events | UEMS')

@section('page-title', 'Organizer Dashboard')

@section('content')
  <div class="container mt-4">
    <h4 class="mb-4">My Submitted Events</h4>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Check if there are events -->
    @if($events->isEmpty())
      <div class="alert alert-info">
        You have not submitted any events yet.
      </div>
    @else
      @foreach ($events as $event)
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">{{ $event->title }}</h5>
            <p class="card-text"><strong>Venue:</strong> {{ $event->venue }}</p>
    <p class="card-text"><strong>Status:</strong>
    <span class="badge
        @if($event->status == 'pending')
            bg-warning text-dark
        @elseif($event->status == 'approved')
            bg-success text-white
        @elseif($event->status == 'rejected')
            bg-danger text-white
        @endif">
        {{ ucfirst($event->status) }}
    </span>
    </p>
    <p class="card-text"><strong>Start Time:</strong> {{ \Carbon\Carbon::parse($event->start_time)->format('d M Y, h:i A') }}</p>
            <p class="card-text"><strong>End Time:</strong> {{ \Carbon\Carbon::parse($event->end_time)->format('d M Y, h:i A') }}</p>

            @if($event->status === 'rejected')
              <p><strong>Admin Comment:</strong> {{ $event->admin_comment }}</p>
              <form action="{{ route('organizer.events.update', $event->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                  <textarea name="admin_comment" class="form-control" rows="3" placeholder="Edit Admin Comment">{{ $event->admin_comment }}</textarea>
                </div>
                <button type="submit" class="btn btn-warning">Resubmit for Approval</button>
              </form>
            @endif

            <div class="mt-3">
              <a href="{{ route('organizer.events.edit', $event->id) }}" class="btn btn-primary">Edit</a>
              <form action="{{ route('organizer.events.delete', $event->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-danger">Delete</button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    @endif
  </div>
@endsection
