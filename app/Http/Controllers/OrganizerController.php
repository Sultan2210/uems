<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventRequest;
use App\Models\Event;

class OrganizerController extends Controller
{
    // Display all events submitted by the organizer (Pending, Approved, Rejected)
    public function index()
{
    $events = EventRequest::where('organizer_id', auth()->id())
        ->latest()
        ->get();

    return view('organizer.events.index', compact('events'));
}

public function create()
{
    return view('organizer.events.create');  // Return the event creation view
}

    // Handle event editing
    public function edit($id)
    {
        $event = EventRequest::findOrFail($id);

        if ($event->organizer_id !== auth()->id()) {
            return redirect()->route('organizer.events.index')->with('error', 'Unauthorized action.');
        }

        return view('organizer.events.edit', compact('event'));
    }
public function store(Request $request)
{
    $data = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'venue' => 'required|string|max:255',
        'start_time' => 'required|date',
        'end_time' => 'required|date|after_or_equal:start_time',
        'has_certificate' => 'nullable|boolean',
        'poster' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('poster')) {
        $data['poster_path'] = $request->file('poster')->store('event_posters', 'public');
    }

    $data['organizer_id'] = auth()->id();
    $data['organizer_name'] = auth()->user()->name; // optional
    $data['status'] = 'pending';

    EventRequest::create($data);

    return redirect()->route('organizer.events.index')->with('success', 'Event request submitted.');
}
    // Handle event update (status reset for rejected events)
    public function update(Request $request, $id)
    {
        $event = EventRequest::findOrFail($id);

        if ($event->organizer_id !== auth()->id()) {
            return redirect()->route('organizer.events.index')->with('error', 'Unauthorized action.');
        }

        // Validate inputs
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'venue' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'description' => 'nullable|string',
            'poster_path' => 'nullable|image|max:4096',
            'admin_comment' => 'nullable|string',  // For rejected events
        ]);

        // Handle poster upload
        if ($request->hasFile('poster_path')) {
            $validated['poster_path'] = $request->file('poster_path')->store('event_posters', 'public');
        }

        // Update event details
        $event->update($validated);

        // If event was rejected, reset the status to 'pending' and clear the admin comment
        if ($event->status == 'rejected') {
            $event->status = 'pending';
            $event->admin_comment = null;  // Reset comment
            $event->save();
        }

        return redirect()->route('organizer.events.index')->with('success', 'Event updated successfully!');
    }

    // Handle event deletion
    public function destroy($id)
    {
        $event = EventRequest::findOrFail($id);

        if ($event->organizer_id !== auth()->id()) {
            return redirect()->route('organizer.events.index')->with('error', 'Unauthorized action.');
        }

        // Delete the event
        $event->delete();
        return redirect()->route('organizer.events.index')->with('success', 'Event deleted successfully!');
    }
}
