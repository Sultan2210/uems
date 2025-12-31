@extends('layouts.admin')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="container-fluid px-4 mt-4">

  <h4 class="mb-4">Approved Events</h4>

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
            </tr>
          </thead>
          <tbody>
            @forelse($events as $event)
              <tr>
                <td style="width:110px;">
                  @if($event->poster)
                    <img src="{{ Storage::url($event->poster) }}"
                         alt="poster" width="90" class="rounded shadow-sm">
                  @elseif($event->poster_path)
                    <img src="{{ Storage::url($event->poster_path) }}"
                         alt="poster" width="90" class="rounded shadow-sm">
                  @else
                    <span class="text-muted">No Image</span>
                  @endif
                </td>
                <td>{{ $event->event_name ?? $event->title ?? 'N/A' }}</td>
                <td>
                  @if($event->organizer)
                    {{ $event->organizer->name }}
                  @elseif($event->creator)
                    {{ $event->creator->name }}
                  @elseif($event->organizer_name)
                    {{ $event->organizer_name }}
                  @elseif($event->created_by)
                    User #{{ $event->created_by }}
                  @else
                    -
                  @endif
                </td>
                <td>{{ $event->location ?? $event->venue ?? '-' }}</td>
                <td>
                  @if($event->start_at)
                    {{ \Carbon\Carbon::parse($event->start_at)->format('d M Y, h:i A') }}
                  @elseif($event->start_time)
                    {{ \Carbon\Carbon::parse($event->start_time)->format('d M Y, h:i A') }}
                  @else
                    -
                  @endif
                </td>
                <td>
                  @if($event->end_at)
                    {{ \Carbon\Carbon::parse($event->end_at)->format('d M Y, h:i A') }}
                  @elseif($event->end_time)
                    {{ \Carbon\Carbon::parse($event->end_time)->format('d M Y, h:i A') }}
                  @else
                    -
                  @endif
                </td>
                <td>
                  <span class="badge bg-success px-3 py-2">APPROVED</span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">
                  No approved events yet.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      @if(method_exists($events, 'links'))
        <div class="mt-3">
          {{ $events->links() }}
        </div>
      @endif
    </div>
  </div>

</div>
@endsection
