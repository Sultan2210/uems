@extends('layouts.organizer')
@section('title', 'Edit Event | UEMS')
@section('page-title', 'Edit Event')

@section('content')
<div class="container mt-4">
    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 10px;">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 10px;">
            <strong>Error!</strong> Please check the form for errors.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header">
            <h4 class="mb-0">Editing Event: {{ $event->title }}</h4>
        </div>
        <div class="card-body">

            <!-- Event Status Indicator -->
            <div class="mb-4 p-3 rounded
                @if($event->status === 'approved') alert-success
                @elseif($event->status === 'rejected') alert-danger
                @else alert-warning @endif">
                Current Status: <strong>{{ ucfirst($event->status) }}</strong>
                @if($event->status === 'approved')
                    <p class="mb-0 text-sm">Note: Saving changes will reset the status to **PENDING** for Admin re-approval.</p>
                @endif
            </div>

            <form action="{{ route('organizer.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Using POST as defined in web.php, but adding method override for REST compliance -->
                @method('POST')

                <!-- Event Title -->
                <div class="mb-3">
                    <label class="form-label">Event Title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $event->title) }}" required placeholder="Enter event title">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <!-- Venue -->
                <div class="mb-3">
                    <label class="form-label">Venue</label>
                    <input type="text" name="venue" class="form-control @error('venue') is-invalid @enderror" value="{{ old('venue', $event->venue) }}" required placeholder="Enter venue name">
                    @error('venue')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <!-- Start and End Time -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Time</label>
                        @php
                            $start_time = \Carbon\Carbon::parse($event->start_time)->format('Y-m-d\TH:i');
                        @endphp
                        <input type="datetime-local" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', $start_time) }}" required>
                        @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Time</label>
                        @php
                            $end_time = \Carbon\Carbon::parse($event->end_time)->format('Y-m-d\TH:i');
                        @endphp
                        <input type="datetime-local" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', $end_time) }}" required>
                        @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Event Description -->
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required placeholder="Enter event description">{{ old('description', $event->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <!-- Poster Upload -->
                <div class="mb-3">
                    <label class="form-label d-block">Current Poster</label>
                    @if ($event->poster_path)
                        <!-- Note: The asset() helper function assumes your storage link is set up -->
                        <img src="{{ asset('storage/' . $event->poster_path) }}" alt="Current Event Poster" style="max-width: 200px; height: auto;" class="img-thumbnail mb-2">
                        <p class="text-muted small">Upload a new image to replace the current one.</p>
                    @else
                        <p class="text-muted small">No poster uploaded yet.</p>
                    @endif

                    <label class="form-label">Upload New Poster (in jpg/jpeg/png)</label>
                    <input type="file" name="poster_path" class="form-control @error('poster_path') is-invalid @enderror">
                    @error('poster_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <!-- Provide Certificate Checkbox -->
                <div class="mb-3 form-check">
                    <input type="checkbox" name="has_certificate" class="form-check-input" id="has_certificate" value="1" {{ old('has_certificate', $event->has_certificate) ? 'checked' : '' }}>
                    <label class="form-check-label" for="has_certificate">Provide Certificate for Attendees</label>
                </div>

                <!-- Resubmission comment field (only relevant if event is rejected) -->
                @if($event->status === 'rejected')
                <div class="mb-3">
                    <label class="form-label">Resubmission Note (Optional)</label>
                    <textarea name="admin_comment" class="form-control" rows="3" placeholder="Explain the changes you made based on the Admin's rejection comment. This will resubmit the event.">{{ old('admin_comment') }}</textarea>
                </div>
                @endif


                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary mt-3">Save Changes and Resubmit</button>
                <a href="{{ route('organizer.events.index') }}" class="btn btn-secondary mt-3">Cancel</a>
            </form>
        </div>
    </div>
</div>

@endsection
