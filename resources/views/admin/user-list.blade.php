@extends('layouts.admin')
@section('title', 'User List | UEMS')
@section('page-title', 'User List')

@section('content')
@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- Pending Admin Requests --}}
@if($pendingAdmins->count() > 0)
<div class="card mb-4">
  <div class="card-header">
    <h4>Pending Admin Approval Requests</h4>
  </div>
  <div class="card-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Matric Card Picture</th>
          <th>Name</th>
          <th>Email</th>
          <th>Matric/Staff Number</th>
          <th>Kulliyyah</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($pendingAdmins as $admin)
          <tr>
            <td>
              @if($admin->picture)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($admin->picture) }}" alt="{{ $admin->name }}" style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                <br>
                <button class="btn btn-sm btn-info mt-1" data-bs-toggle="modal" data-bs-target="#matricCardModal"
                        onclick="showMatricCard('{{ \Illuminate\Support\Facades\Storage::url($admin->picture) }}', '{{ $admin->name }}')">
                  <i class="bi bi-eye"></i> View Full Image
                </button>
              @else
                <div style="width: 80px; height: 60px; background-color: #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; border: 1px solid #ccc;">
                  <span style="font-size: 10px;">No Image</span>
                </div>
              @endif
            </td>
            <td>{{ $admin->name }}</td>
            <td>{{ $admin->email }}</td>
            <td>{{ $admin->matric_no ?? '-' }}</td>
            <td>{{ $admin->kulliyyah ?? '-' }}</td>
            <td>
              <span class="badge bg-warning">Pending Approval</span>
            </td>
            <td>
              <form action="{{ route('admin.admin.approve', $admin->id) }}" method="POST" style="display:inline;">
                @csrf
                <button class="btn btn-success btn-sm">Approve</button>
              </form>
              <form action="{{ route('admin.admin.reject', $admin->id) }}" method="POST" style="display:inline;">
                @csrf
                <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject this admin?')">Reject</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif

{{-- Registered Organizers --}}
<div class="card mb-4">
  <div class="card-header">
    <h4>Registered Organizers</h4>
  </div>
  <div class="card-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Kulliyyah</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($organizers as $organizer)
          <tr>
            <td>{{ $organizer->name }}</td>
            <td>{{ $organizer->email }}</td>
            <td>{{ $organizer->kulliyyah }}</td>
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

{{-- Approved Admins --}}
<div class="card">
  <div class="card-header">
    <h4>Approved Admins</h4>
  </div>
  <div class="card-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Profile Picture</th>
          <th>Name</th>
          <th>Email</th>
          <th>Matric/Staff Number</th>
          <th>Kulliyyah</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($approvedAdmins as $admin)
          <tr>
            <td>
              @if($admin->picture)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($admin->picture) }}" alt="{{ $admin->name }}" style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                <br>
                <button class="btn btn-sm btn-info mt-1" data-bs-toggle="modal" data-bs-target="#matricCardModal"
                        onclick="showMatricCard('{{ \Illuminate\Support\Facades\Storage::url($admin->picture) }}', '{{ $admin->name }}')">
                  <i class="bi bi-eye"></i> View Full Image
                </button>
              @else
                <div style="width: 80px; height: 60px; background-color: #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; border: 1px solid #ccc;">
                  <span style="font-size: 10px;">No Image</span>
                </div>
              @endif
            </td>
            <td>{{ $admin->name }}</td>
            <td>{{ $admin->email }}</td>
            <td>{{ $admin->matric_no ?? '-' }}</td>
            <td>{{ $admin->kulliyyah ?? '-' }}</td>
            <td>
              @if ($admin->is_active)
                <span class="badge bg-success">Active</span>
              @else
                <span class="badge bg-danger">Inactive</span>
              @endif
            </td>
            <td>
              @if ($admin->is_active)
                <form action="{{ route('admin.user.deactivate', $admin->id) }}" method="POST" style="display:inline;">
                  @csrf
                  <button class="btn btn-warning btn-sm">Deactivate</button>
                </form>
              @else
                <form action="{{ route('admin.user.activate', $admin->id) }}" method="POST" style="display:inline;">
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

{{-- Matric Card Modal --}}
<div class="modal fade" id="matricCardModal" tabindex="-1" aria-labelledby="matricCardModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="matricCardModalLabel">Matric Card Picture</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="matricCardImage" src="" alt="Matric Card" class="img-fluid" style="max-height: 70vh; width: auto;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
function showMatricCard(imageUrl, adminName) {
  document.getElementById('matricCardImage').src = imageUrl;
  document.getElementById('matricCardModalLabel').textContent = 'Matric Card Picture - ' + adminName;
}
</script>
@endsection
