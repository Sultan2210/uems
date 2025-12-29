<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventRequest;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Feedback;

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
    // Validate the form fields
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'venue' => 'required|string|max:255',
        'start_time' => 'required|date',
        'end_time' => 'required|date',
        'poster_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'payment_qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'organizer_name' => 'required|string|max:255',
    ]);

    // Check if poster file is being uploaded
    if ($request->hasFile('poster_path')) {
        \Log::info('Poster file uploaded: ' . $request->file('poster_path')->getClientOriginalName());
    } else {
        \Log::info('No poster file uploaded.');
    }

    // Check if payment QR code file is being uploaded
    if ($request->hasFile('payment_qr_code')) {
        \Log::info('QR Code file uploaded: ' . $request->file('payment_qr_code')->getClientOriginalName());
    } else {
        \Log::info('No QR Code file uploaded.');
    }

    // Handle file upload for poster
    $posterPath = null;
    if ($request->hasFile('poster_path')) {
        $posterPath = $request->file('poster_path')->store('event_posters', 'public'); // Store in 'event_posters' directory
    }

    // Handle file upload for payment QR code
    $qrCodePath = null;
    if ($request->hasFile('payment_qr_code')) {
        $qrCodePath = $request->file('payment_qr_code')->store('payment_qr_codes', 'public'); // Store in 'payment_qr_codes' directory
    }

    // Store event request with the organizer's ID
    EventRequest::create([
        'title' => $request->input('title'),
        'description' => $request->input('description'),
        'venue' => $request->input('venue'),
        'poster_path' => $posterPath,  // Save the file path for the poster
        'organizer_name' => $request->input('organizer_name'),
        'organizer_id' => auth()->user()->id,  // Store the ID of the logged-in user (organizer)
        'status' => 'pending', // Set the initial status to 'pending'
        'start_time' => $request->input('start_time'),
        'end_time' => $request->input('end_time'),
        'capacity' => $request->input('capacity') ?? null,
        'payment_qr_code' => $qrCodePath, // Save QR code file path
    ]);

    return redirect()->route('organizer.events.index')->with('success', 'Event request submitted successfully.');
}


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

    public function attendees(Request $request)
{
    // Get organizer's approved events
    $events = Event::where('status', 'approved')
        ->where('created_by', auth()->id())
        ->get();

    $selectedEvent = null;
    $registrations = collect();

    if ($request->filled('event_id')) {
        $selectedEvent = Event::where('id', $request->event_id)
            ->where('created_by', auth()->id())
            ->firstOrFail();

        $registrations = Registration::where('event_id', $selectedEvent->id)
            ->with('user') // for email
            ->get();
    }

    return view('organizer.events.attendee', compact(
    'events',
    'registrations',
    'selectedEvent'
));

}
public function feedbackSummary(Request $request)
{
    // dropdown events (only organizer’s approved events)
    $events = Event::where('created_by', auth()->id())
        ->where('status', 'approved')
        ->orderByDesc('start_at')
        ->get();

    $selectedEvent = null;
    $feedbacks = collect();

    if ($request->filled('event_id')) {
        $selectedEvent = Event::where('id', $request->event_id)
            ->where('created_by', auth()->id())
            ->firstOrFail();

        $feedbacks = Feedback::where('event_id', $selectedEvent->id)
            ->orderByDesc('created_at')
            ->get();
    }

    return view('organizer.events.feedback-summary', compact('events', 'selectedEvent', 'feedbacks'));
}

}
