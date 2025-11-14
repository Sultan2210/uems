<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventRequest;
use App\Models\Event;
use App\Models\User;

class AdminController extends Controller
{
   public function index()
{
    // Get the count of pending event requests
    $pendingCount = EventRequest::where('status', 'pending')->count();

    // Get the count of approved events
    $approvedCount = Event::where('status', 'approved')->count();

    // Get the count of rejected events (ensure correct status is checked)
    $rejectedCount = Event::where('status', 'rejected')->count();

    // Get the total number of events (approved, pending, and rejected)
    $totalEvents = Event::count();

    // Pass the data to the view
    return view('admin.dashboard', compact('pendingCount', 'approvedCount', 'rejectedCount', 'totalEvents'));
}




    public function pendingRequests()
{
    $requests = \App\Models\EventRequest::where('status', 'pending')->latest()->get();
    return view('admin.events.pending', compact('requests'));
}

public function approveRequest($id)
{
    // Find the pending event request
    $eventRequest = EventRequest::findOrFail($id);

    // Create a new event based on the request
    $event = new Event();
    $event->organizer_id   = $eventRequest->organizer_id;
    $event->organizer_name = $eventRequest->organizer_name;
    $event->title          = $eventRequest->title;
    $event->description    = $eventRequest->description;
    $event->venue          = $eventRequest->venue;
    $event->start_time     = $eventRequest->start_time;
    $event->end_time       = $eventRequest->end_time;
    $event->poster_path    = $eventRequest->poster_path;
    $event->has_certificate = $eventRequest->has_certificate;
    $event->status         = 'approved';
    $event->save();

    // Update the EventRequest to 'approved' and mark it as processed
    $eventRequest->status = 'approved';
    $eventRequest->save();

    // Redirect back with a success message
    return redirect()->route('admin.events.approved')->with('success', 'Event approved successfully.');
}

   public function approvedEvents()
    {
        $events = Event::where('status','approved')
            ->orderByDesc('start_time')   // or 'start_at' if you use the new schema
            ->paginate(10);

        return view('admin.events.approved', compact('events'));
    }

    // (optional) list pending
    public function pendingEvents()
    {
        $requests = EventRequest::where('status','pending')
            ->latest()
            ->paginate(10);

        return view('admin.events.pending', compact('requests'));
    }

    // (optional) list rejected
    public function rejectedEvents()
{
    $requests = EventRequest::where('status', 'rejected')
        ->latest()
        ->paginate(10);

    return view('admin.events.rejected', compact('requests'));
}



    public function rejectRequest(Request $request, $id)
{
    $request->validate(['admin_comment' => 'nullable|string|max:1000']);

    // Find the EventRequest
    $eventRequest = EventRequest::findOrFail($id);

    // Update status and add comment
    $eventRequest->status = 'rejected';
    $eventRequest->admin_comment = $request->admin_comment;
    $eventRequest->save();

    // Optionally, update the event in the Event table (if you have one)
    $event = Event::where('event_request_id', $eventRequest->id)->first();
    if ($event) {
        $event->status = 'rejected';
        $event->save();
    }

    // Redirect back with a message
    return redirect()->route('admin.events.rejected')->with('error', 'Event request rejected.');
}


    public function userList()
{
    $organizers = User::where('role', 'organizer')->get();
    return view('admin.user-list', compact('organizers'));
}

public function activateUser($id)
{
    $user = User::findOrFail($id);
    $user->update(['is_active' => true]);
    return redirect()->back()->with('success', 'User activated successfully!');
}

public function deactivateUser($id)
{
    $user = User::findOrFail($id);
    $user->update(['is_active' => false]);
    return redirect()->back()->with('error', 'User deactivated successfully!');
}
}
