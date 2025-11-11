<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrganizerController extends Controller
{
    public function index()
    {
 $events = \App\Models\Event::where('created_by', auth()->id())
        ->withCount('registrations')
        ->orderByDesc('created_at')
        ->get();
        }
    public function create()
    {
        return view('organizer.events.create');
    }


    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
         'organizer_name' => 'required|string|max:255',
        'venue' => 'required|string|max:255',
        'start_time' => 'required|date',
        'end_time' => 'required|date|after_or_equal:start_time',
        'description' => 'nullable|string',
        'poster_path' => 'nullable|image|max:4096',
    ]);

    $event = new \App\Models\EventRequest();
    $event->organizer_id = auth()->id();
    $event->organizer_name = $request->organizer_name;
    $event->title = $request->title;
    $event->venue = $request->venue;
    $event->description = $request->description;
    $event->start_time = $request->start_time;
    $event->end_time = $request->end_time;
    $event->has_certificate = $request->has('has_certificate');
    $event->status = 'pending';

    if ($request->hasFile('poster_path')) {
        $path = $request->file('poster_path')->store('event_posters', 'public');
        $event->poster_path = $path;
    }

    $event->save();

    return redirect()->route('organizer.events.create')->with('success', 'Event request submitted for admin approval!');
}

}
