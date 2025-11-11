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
        return view('layouts.admin');
    }


    public function pendingRequests()
{
    $requests = \App\Models\EventRequest::where('status', 'pending')->latest()->get();
    return view('admin.events.pending', compact('requests'));
}

public function approveRequest($id)
{
    $eventRequest = EventRequest::findOrFail($id);

    $event = new \App\Models\Event();
    $event->organizer_id   = $eventRequest->organizer_id;
    $event->organizer_name = $eventRequest->organizer_name;
    $event->title          = $eventRequest->title;
    $event->description    = $eventRequest->description;
    $event->venue          = $eventRequest->venue;
    $event->start_time     = $eventRequest->start_time;
    $event->end_time       = $eventRequest->end_time;
    $event->poster_path    = $eventRequest->poster_path;
    $event->has_certificate= $eventRequest->has_certificate;
    $event->status         = 'approved';
    $event->save();

    return back()->with('success', 'Event approved successfully.');
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

        $eventRequest = EventRequest::findOrFail($id);
        $eventRequest->status = 'rejected';
        $eventRequest->admin_comment = $request->admin_comment;
        $eventRequest->save();

        return redirect()->back()->with('error', 'Event request rejected.');
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
