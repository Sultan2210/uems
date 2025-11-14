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
                <td>{{ \Carbon\Carbon::parse($req->start_time)->format('d M Y, h:i A') }}</td>
                <td>{{ \Carbon\Carbon::parse($req->end_time)->format('d M Y, h:i A') }}</td>
                <td>
                  <span class="badge bg-warning px-3 py-2 text-dark">PENDING</span>
                </td>
                <td>
                  {{-- Approve Button --}}
                  <form action="{{ route('admin.events.approve', $req->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button class="btn btn-success btn-sm">Approve</button>
                  </form>

                  {{-- Reject Button with Collapse for Admin Comment --}}
                  <button class="btn btn-danger btn-sm" data-bs-toggle="collapse" data-bs-target="#reject-{{ $req->id }}">Reject</button>
                  <div id="reject-{{ $req->id }}" class="collapse mt-2">
                    <form action="{{ route('admin.events.reject', $req->id) }}" method="POST">
                      @csrf
                      <textarea name="admin_comment" class="form-control mb-2" placeholder="Reason for rejection (optional)"></textarea>
                      <button type="submit" class="btn btn-outline-danger btn-sm">Confirm Reject</button>
                    </form>
                  </div>
                </td>
              </tr>
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
