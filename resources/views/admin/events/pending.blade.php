@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 mt-4">

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Success!</strong> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Error!</strong> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <h4 class="mb-4">Pending Event Requests</h4>

  <div class="card shadow-sm border-0 rounded-3">
    <div class="card-body bg-white p-4">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Poster</th>
              <th>Title</th>
              <th>Organizer</th>
              <th>Venue</th>
              <th>Start Time</th>
              <th>End Time</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
@forelse($requests as $req)
  <tr>
    <td style="width:110px;">
      @if($req->poster_path)
        <img src="{{ asset('storage/'.$req->poster_path) }}"
             alt="poster" width="90" class="rounded shadow-sm">
      @else
        <span class="text-muted">No Image</span>
      @endif
    </td>

    <td>{{ $req->title }}</td>
    <td>{{ $req->organizer_name ?? $req->organizer_id }}</td>
    <td>{{ $req->venue }}</td>
    <td>{{ $req->start_time ? \Carbon\Carbon::parse($req->start_time)->format('d M Y, h:i A') : '-' }}</td>
    <td>{{ $req->end_time ? \Carbon\Carbon::parse($req->end_time)->format('d M Y, h:i A') : '-' }}</td>

    <td>
      <span class="badge bg-warning px-3 py-2 text-dark">PENDING</span>
    </td>

    <td class="d-flex gap-2 flex-wrap">
      <!-- View Modal Button -->
      <button type="button"
              class="btn btn-primary btn-sm"
              data-bs-toggle="modal"
              data-bs-target="#viewReq{{ $req->id }}">
        View
      </button>

      <!-- Approve -->
      <form action="{{ route('admin.events.approve', $req->id) }}" method="POST" style="display:inline;">
        @csrf
        <button class="btn btn-success btn-sm">Approve</button>
      </form>

      <!-- Reject (Collapse) -->
      <button class="btn btn-danger btn-sm"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#reject-{{ $req->id }}">
        Reject
      </button>

      <div id="reject-{{ $req->id }}" class="collapse mt-2 w-100">
        <form action="{{ route('admin.events.reject', $req->id) }}" method="POST">
          @csrf
          <textarea name="admin_comment" class="form-control mb-2"
                    placeholder="Reason for rejection (optional)"></textarea>
          <button type="submit" class="btn btn-outline-danger btn-sm">Confirm Reject</button>
        </form>
      </div>
    </td>
  </tr>

  <!-- ✅ Modal MUST be here (inside loop, but OUTSIDE <tr>) -->
  <div class="modal fade" id="viewReq{{ $req->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Event Request Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
          <div class="mb-3">
            <strong>Title:</strong>
            <p class="mb-0">{{ $req->title }}</p>
          </div>

          <div class="mb-3">
            <strong>Organizer:</strong>
            <p class="mb-0">{{ $req->organizer_name ?? $req->organizer_id }}</p>
          </div>

          <div class="mb-3">
            <strong>Venue:</strong>
            <p class="mb-0">{{ $req->venue ?? '-' }}</p>
          </div>

          <div class="mb-3">
            <strong>Start Time:</strong>
            <p class="mb-0">{{ $req->start_time ? \Carbon\Carbon::parse($req->start_time)->format('d M Y, h:i A') : '-' }}</p>
          </div>

          <div class="mb-3">
            <strong>End Time:</strong>
            <p class="mb-0">{{ $req->end_time ? \Carbon\Carbon::parse($req->end_time)->format('d M Y, h:i A') : '-' }}</p>
          </div>

          @if($req->capacity)
          <div class="mb-3">
            <strong>Capacity:</strong>
            <p class="mb-0">{{ $req->capacity }}</p>
          </div>
          @endif

          <hr>

          <div class="mb-3">
            <strong>Description:</strong>
            <p class="mb-0" style="white-space: pre-wrap; word-wrap: break-word;">{{ $req->description ?? '-' }}</p>
          </div>

          @if($req->poster_path)
            <hr>
            <div class="mb-3">
              <strong>Poster:</strong>
              <div class="mt-2">
                <img src="{{ asset('storage/'.$req->poster_path) }}" class="img-fluid rounded" alt="Event Poster">
              </div>
            </div>
          @endif

          <hr>
          <div class="mb-3">
            <strong>Certificate:</strong>
            <p class="mb-0">{{ $req->has_certificate ? 'Yes' : 'No' }}</p>
          </div>

          @if($req->payment_qr_code)
          <div class="mb-3">
            <strong>Payment QR Code:</strong>
            <div class="mt-2">
              <img src="{{ asset('storage/'.$req->payment_qr_code) }}" class="img-fluid rounded" style="max-width: 200px;" alt="Payment QR Code">
            </div>
          </div>
          @endif

          @if($req->admin_comment)
            <hr>
            <div class="mb-3">
              <strong>Admin Comment:</strong>
              <p class="mb-0" style="white-space: pre-wrap; word-wrap: break-word;">{{ $req->admin_comment }}</p>
            </div>
          @endif
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

@empty
  <tr>
    <td colspan="8" class="text-center text-muted py-4">
      No pending event requests.
    </td>
  </tr>
@endforelse
</tbody>

        </table>
      </div>
    </div>
  </div>

</div>
@endsection
