@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 mt-4">
  <h4 class="mb-4">Admin Dashboard</h4>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


  <div class="row">
    <!-- Pending Events Card (Clickable) -->
    <div class="col-md-3">
      <a href="{{ route('admin.events.pending') }}" class="text-decoration-none">
        <div class="card shadow-sm border-0 rounded-3">
          <div class="card-body bg-warning text-white p-4">
            <div class="d-flex justify-content-between">
              <div>
                <h5 class="card-title">Pending Events</h5>
                <p class="card-text">You have {{ $pendingCount }} events waiting for approval.</p>
              </div>
              <div class="pt-2">
                <i class="bi bi-exclamation-circle-fill" style="font-size: 2rem;"></i>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>

    <!-- Approved Events Card -->
    <div class="col-md-3">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body bg-success text-white p-4">
          <h5 class="card-title">Approved Events</h5>
          <p class="card-text">You have {{ $approvedCount }} events approved.</p>
        </div>
      </div>
    </div>

    <!-- Rejected Events Card -->
    <div class="col-md-3">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body bg-danger text-white p-4">
          <h5 class="card-title">Rejected Events</h5>
          <p class="card-text">You have {{ $rejectedCount }} events rejected.</p>
        </div>
      </div>
    </div>

    <!-- Total Events Card -->
    <div class="col-md-3">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body bg-primary text-white p-4">
          <h5 class="card-title">Total Events</h5>
          <p class="card-text">You have {{ $totalEvents }} events in total.</p>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
