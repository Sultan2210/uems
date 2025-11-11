@extends('layouts.admin')
@section('title', 'User List | UEMS')
@section('page-title', 'Organizer User List')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>Registered Organizers</h4>
  </div>
  <div class="card-body">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-striped">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($organizers as $organizer)
          <tr>
            <td>{{ $organizer->name }}</td>
            <td>{{ $organizer->email }}</td>
            <td>
              @if ($organizer->is_active)
                <span class="badge bg-success">Active</span>
              @else
                <span class="badge bg-danger">Inactive</span>
              @endif
            </td>
            <td>
              @if ($organizer->is_active)
                <form action="{{ route('admin.user.deactivate', $organizer->id) }}" method="POST" style="display:inline;">
                  @csrf
                  <button class="btn btn-warning btn-sm">Deactivate</button>
                </form>
              @else
                <form action="{{ route('admin.user.activate', $organizer->id) }}" method="POST" style="display:inline;">
                  @csrf
                  <button class="btn btn-success btn-sm">Activate</button>
                </form>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
