@extends('layouts.student')
@section('title','Browse Events | UEMS')
@section('page-title','Browse Events')

@section('content')
@php
    // small helper to resolve a poster path (supports both 'poster' and 'poster_path')
    function poster_url($event) {
        // full http(s) link already?
        if (!empty($event->poster) && str_starts_with($event->poster, 'http')) return $event->poster;
        if (!empty($event->poster_path) && str_starts_with($event->poster_path, 'http')) return $event->poster_path;

        // storage paths
        if (!empty($event->poster)) {
            try { return Storage::url($event->poster); } catch (\Throwable $e) {}
        }
        if (!empty($event->poster_path)) {
            try { return Storage::url($event->poster_path); } catch (\Throwable $e) {}
        }

        // public/assets fallback (put a placeholder in public/assets/img/placeholder-event.jpg)
        return asset('assets/img/placeholder-event.jpg');
    }
@endphp

<div class="row">
  @forelse($events as $event)
    @php
      $name  = $event->event_name ?? $event->title ?? 'Untitled Event';
      $whenS = optional($event->start_at ?? $event->start_time)->format('d M Y, h:ia');
      $whenE = optional($event->end_at   ?? $event->end_time)->format('h:ia');
      $where = $event->location ?? $event->venue ?? '—';
      $cap   = $event->capacity ?? null;
      $poster= poster_url($event);
    @endphp

    <div class="col-md-4 mb-4">
      <div class="card h-100">
        <img src="{{ $poster }}" class="card-img-top" alt="poster" style="object-fit:cover;height:180px;">
        <div class="card-body">
          <h5 class="card-title mb-2">{{ $name }}</h5>
          <p class="text-sm mb-1"><strong>When:</strong> {{ $whenS }} @if($event->end_at || $event->end_time) – {{ $whenE }} @endif</p>
          <p class="text-sm mb-1"><strong>Where:</strong> {{ $where }}</p>
          <p class="text-sm mb-1">
            <strong>Registered:</strong>
            {{ $event->registrations_count ?? $event->registrations_count ?? 0 }}
            @if(!is_null($cap)) / {{ $cap }} @endif
          </p>
        </div>
        <div class="card-footer bg-transparent">
          <a href="{{ route('student.events.show', $event) }}" class="btn btn-dark w-100">View & Register</a>
        </div>
      </div>
    </div>
  @empty
    <div class="col-12">
      <div class="alert alert-secondary">No approved events yet.</div>
    </div>
  @endforelse
</div>

<div class="mt-3">
  {{ $events->links() }}
</div>
@endsection
