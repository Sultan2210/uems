@extends('layouts.organizer')

@section('title', 'Attendee List | UEMS')
@section('page-title', 'Attendee List')

@section('content')
<div class="container-fluid py-3">

    {{-- Page Header --}}
    <div class="mb-4">
        <h4 class="font-weight-bolder mb-2">Attendee List</h4>
        <p class="text-muted mb-0">View and manage attendees for your approved events</p>
    </div>

    {{-- SELECT EVENT CARD --}}
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('organizer.attendees') }}" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label fw-semibold mb-2">
                        <i class="bi bi-calendar-event me-2"></i>
                        Select Approved Event
                    </label>
                    <select name="event_id" class="form-select form-select-lg" required>
                        <option value="">-- Choose Event --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}"
                                {{ optional($selectedEvent)->id == $event->id ? 'selected' : '' }}>
                                {{ $event->event_name }}
                                @if($event->start_at)
                                    - {{ optional($event->start_at)->format('d M Y') }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary btn-lg w-100 view-attendees-btn" type="submit">
                        <i class="bi bi-people-fill me-2"></i>
                        View Attendees
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ATTENDEE TABLE CARD --}}
    @if($selectedEvent)
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="font-weight-bolder mb-1">
                            <i class="bi bi-people-fill me-2 text-primary"></i>
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
                            <i class="bi bi-person-check me-1"></i>
                            {{ $registrations->count() }} Attendee{{ $registrations->count() !== 1 ? 's' : '' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($registrations->isEmpty())
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-inbox" style="font-size: 4rem; color: #dee2e6;"></i>
                        </div>
                        <h6 class="font-weight-bolder mb-2">No Attendees Yet</h6>
                        <p class="text-muted mb-0">No students have registered for this event yet.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 60px;">#</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Matric / Staff No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Payment Receipt</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registrations as $index => $reg)
                                    <tr>
                                        <td>
                                            <span class="text-secondary text-xs font-weight-bold">{{ $index + 1 }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2 bg-primary border-radius-lg">
                                                    <span class="text-white text-xs font-weight-bold">{{ strtoupper(substr($reg->full_name, 0, 1)) }}</span>
                                                </div>
                                                <span class="text-xs font-weight-bold mb-0">{{ $reg->full_name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-secondary text-xs">{{ $reg->matric_or_staff_no ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary text-xs">{{ optional($reg->user)->email ?? '-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($reg->payment_receipt)
                                                <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#receiptModal{{ $reg->id }}">
                                                    <i class="bi bi-receipt me-1"></i>
                                                    View Receipt
                                                </button>
                                            @else
                                                <span class="text-secondary text-xs">No Receipt</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($reg->status === 'attended')
                                                <span class="badge bg-success text-white">
                                                    <i class="bi bi-check-circle-fill me-1"></i>
                                                    Attended
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-clock-fill me-1"></i>
                                                    Registered
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($reg->payment_receipt && optional($reg->user)->email)
                                                <form action="{{ route('organizer.send-ticket', $reg->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Send ticket email to {{ $reg->full_name }}?')">
                                                        <i class="bi bi-envelope-fill me-1"></i>
                                                        Send Ticket
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted text-xs">-</span>
                                            @endif
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
                <p class="text-muted mb-0">Please select an event from the dropdown above to view its attendees.</p>
            </div>
        </div>
    @endif

</div>

{{-- Receipt Modals --}}
@foreach($registrations as $reg)
    @if($reg->payment_receipt)
    <div class="modal fade" id="receiptModal{{ $reg->id }}" tabindex="-1" aria-labelledby="receiptModalLabel{{ $reg->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiptModalLabel{{ $reg->id }}">
                        <i class="bi bi-receipt me-2"></i>
                        Payment Receipt - {{ $reg->full_name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('storage/' . $reg->payment_receipt) }}" alt="Payment Receipt" class="img-fluid" style="max-height: 70vh;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

{{-- Success/Error Messages --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9999;">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9999;">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


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

    .view-attendees-btn:hover {
        background-color: #ffa726 !important;
        border-color: #ffa726 !important;
        color: #fff !important;
    }

    .view-attendees-btn:focus {
        background-color: #ffa726 !important;
        border-color: #ffa726 !important;
        color: #fff !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 167, 38, 0.25) !important;
    }
</style>

@endsection
