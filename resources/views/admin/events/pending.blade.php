@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 mt-4">

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

        <div class="modal-body">
          <p><strong>Title:</strong> {{ $req->title }}</p>
          <p><strong>Organizer:</strong> {{ $req->organizer_name ?? $req->organizer_id }}</p>
          <p><strong>Venue:</strong> {{ $req->venue }}</p>

          <p><strong>Start:</strong>
            {{ $req->start_time ? \Carbon\Carbon::parse($req->start_time)->format('d M Y, h:i A') : '-' }}
          </p>

          <p><strong>End:</strong>
            {{ $req->end_time ? \Carbon\Carbon::parse($req->end_time)->format('d M Y, h:i A') : '-' }}
          </p>

          <hr>
          <p><strong>Description:</strong></p>
          <p class="mb-0">{{ $req->description ?? '-' }}</p>

          @if($req->poster_path)
            <hr>
            <p><strong>Poster:</strong></p>
            <img src="{{ asset('storage/'.$req->poster_path) }}" class="img-fluid rounded">
          @endif

          <hr>
          <p><strong>Certificate:</strong> {{ $req->has_certificate ? 'Yes' : 'No' }}</p>

          @if($req->admin_comment)
            <p><strong>Admin Comment:</strong> {{ $req->admin_comment }}</p>
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
