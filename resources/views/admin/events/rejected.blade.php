@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 mt-4">

  <h4 class="mb-4">Rejected Event Requests</h4>

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
              <th>Admin Comment</th>
              <th>Status</th>
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
                  <span class="text-danger fw-semibold">
                    {{ $req->admin_comment ?? '—' }}
                  </span>
                </td>
                <td>
                  <span class="badge bg-danger px-3 py-2">REJECTED</span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center text-muted py-4">
                  No rejected event requests.
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
