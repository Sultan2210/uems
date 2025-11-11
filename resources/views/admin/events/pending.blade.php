@extends('layouts.admin')

@section('content')
<div class="container mt-4">
  <h4>Pending Event Requests</h4>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Poster</th>
        <th>Title</th>
        <th>Organizer ID</th>
        <th>Organizer Name</th>
        <th>Venue</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Certificate</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($requests as $req)
      <tr>
        <td>
          @if($req->poster_path)
            <img src="{{ asset('storage/'.$req->poster_path) }}" alt="poster" width="100">
          @else
            No Image
          @endif
        </td>
        <td>{{ $req->title }}</td>
        <td>{{ $req->organizer_id }}</td>
        <td>{{ $req->organizer_name }}</td>
        <td>{{ $req->venue }}</td>
        <td>{{ \Carbon\Carbon::parse($req->start_time)->format('d M Y, h:i A') }}</td>
        <td>{{ \Carbon\Carbon::parse($req->end_time)->format('d M Y, h:i A') }}</td>
        <td>{{ $req->has_certificate ? 'Yes' : 'No' }}</td>
        <td>
         {{-- APPROVE --}}
<form action="{{ route('admin.events.approve', $req->id) }}" method="POST" style="display:inline">
  @csrf
  <button class="btn btn-success btn-sm">Approve</button>
</form>


{{-- REJECT (inside the collapse) --}}
<form action="{{ route('admin.events.reject', $req->id) }}" method="POST">
  @csrf
  <textarea name="admin_comment" class="form-control mb-2" placeholder="Reason (optional)"></textarea>
  <button type="submit" class="btn btn-outline-danger btn-sm">Confirm Reject</button>
</form>

          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
