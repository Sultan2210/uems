@extends('layouts.organizer')

@section('title', 'Feedback Summary | UEMS')
@section('page-title', 'Feedback Summary')

@section('content')
<div class="container-fluid py-3">

    {{-- Page Header --}}
    <div class="mb-4">
        <h4 class="font-weight-bolder mb-2">Feedback Summary</h4>
        <p class="text-muted mb-0">View and analyze feedback from attendees for your events</p>
    </div>

    {{-- SELECT EVENT CARD --}}
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('organizer.feedback.summary') }}" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label fw-semibold mb-2">
                        <i class="bi bi-calendar-event me-2"></i>
                        Select Approved Event
                    </label>
                    <select name="event_id" class="form-select form-select-lg" required>
                        <option value="">-- Choose Event --</option>
                        @foreach($events as $ev)
                            <option value="{{ $ev->id }}" {{ request('event_id') == $ev->id ? 'selected' : '' }}>
                                {{ $ev->event_name }}
                                @if($ev->start_at)
                                    - {{ optional($ev->start_at)->format('d M Y') }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary btn-lg w-100 view-feedback-btn" type="submit">
                        <i class="bi bi-chat-square-text-fill me-2"></i>
                        View Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- FEEDBACK TABLE CARD --}}
    @if($selectedEvent)
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="font-weight-bolder mb-1">
                            <i class="bi bi-chat-square-text-fill me-2 text-primary"></i>
                            {{ $selectedEvent->event_name }}
                        </h5>
                        @if($selectedEvent->start_at)
                        <p class="text-muted small mb-0">
                            <i class="bi bi-calendar-event me-1"></i>
                            {{ optional($selectedEvent->start_at)->format('l, d F Y') }}
                            @if($selectedEvent->start_at && $selectedEvent->end_at)
                                • {{ optional($selectedEvent->start_at)->format('h:i A') }} - {{ optional($selectedEvent->end_at)->format('h:i A') }}
                            @endif
                        </p>
                        @endif
                    </div>
                    <div>
                        <span class="badge bg-primary px-3 py-2">
                            <i class="bi bi-chat-left-text me-1"></i>
                            {{ $feedbacks->count() }} Feedback{{ $feedbacks->count() !== 1 ? 's' : '' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($feedbacks->count() === 0)
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-inbox" style="font-size: 4rem; color: #dee2e6;"></i>
                        </div>
                        <h6 class="font-weight-bolder mb-2">No Feedback Yet</h6>
                        <p class="text-muted mb-0">No feedback has been submitted for this event yet.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 60px;">#</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Student Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Matric / Staff No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Comments</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 170px;">Submitted At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($feedbacks as $i => $fb)
                                    <tr>
                                        <td>
                                            <span class="text-secondary text-xs font-weight-bold">{{ $i + 1 }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2 bg-primary border-radius-lg">
                                                    <span class="text-white text-xs font-weight-bold">{{ strtoupper(substr($fb->student_name, 0, 1)) }}</span>
                                                </div>
                                                <span class="text-xs font-weight-bold mb-0">{{ $fb->student_name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-secondary text-xs">{{ $fb->matric_number ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary text-xs">{{ $fb->email ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <div class="text-secondary text-xs" style="max-width: 400px; white-space: normal; word-wrap: break-word;">
                                                {{ $fb->comments }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-secondary text-xs">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ optional($fb->created_at)->format('d M Y') }}<br>
                                                <small class="text-muted">{{ optional($fb->created_at)->format('h:i A') }}</small>
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
    @else
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-calendar-event" style="font-size: 4rem; color: #dee2e6;"></i>
                </div>
                <h6 class="font-weight-bolder mb-2">Select an Event</h6>
                <p class="text-muted mb-0">Please select an event from the dropdown above to view its feedback summary.</p>
            </div>
        </div>
    @endif

</div>

<style>
    .avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
    }

    .table th {
        border-bottom: 1px solid #e9ecef;
        padding: 12px;
    }

    .table td {
        padding: 12px;
        border-bottom: 1px solid #f0f0f0;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .thead-light {
        background-color: #f8f9fa;
    }

    .view-feedback-btn:hover {
        background-color: #ffa726 !important;
        border-color: #ffa726 !important;
        color: #fff !important;
    }

    .view-feedback-btn:focus {
        background-color: #ffa726 !important;
        border-color: #ffa726 !important;
        color: #fff !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 167, 38, 0.25) !important;
    }
</style>

@endsection
