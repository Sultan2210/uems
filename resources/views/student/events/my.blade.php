@extends('layouts.student')
@section('title','My Registrations | UEMS')
@section('page-title','My Registrations')

@section('content')
<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table align-items-center mb-0">
        <thead>
          <tr>
            <th>Event bgugugugygyugy</th>
            <th>Date</th>
            <th>Location</th>
            <th>Attendance</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($regs as $reg)
            <tr>
              <td>{{ $reg->event->event_name }}</td>
              <td>{{ optional($reg->event->start_at)->format('d M Y, h:ia') }}</td>
              <td>{{ $reg->event->location }}</td>
              <td>
                @if($reg->attended)
                  <span class="badge bg-success">Attended</span>
                @else
                  <span class="badge bg-secondary">Not yet</span>
                @endif
              </td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-dark" href="{{ route('student.events.show', $reg->event) }}">Details</a>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center p-4">No registrations yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-footer">
    {{ $regs->links() }}
  </div>
</div>
@endsection
