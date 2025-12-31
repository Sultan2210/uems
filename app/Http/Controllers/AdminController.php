<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventRequest;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
   public function index()
{
    // Get the count of pending event requests
    $pendingCount = EventRequest::where('status', 'pending')->count();

    // Get the count of approved events (from Event table)
    $approvedCount = Event::where('status', 'approved')->count();

    // Get the count of rejected events (from EventRequest table, as rejections are stored there)
    $rejectedCount = EventRequest::where('status', 'rejected')->count();

    // Get the total number of events (sum of approved, pending, and rejected)
    $totalEvents = $approvedCount ;

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
    // Find the event request by ID
    $req = EventRequest::findOrFail($id);

    // Ensure the organizer_id exists
    if (!$req->organizer_id) {
        return back()->withErrors(['error' => 'Organizer ID is missing for this event request.']);
    }

    // Get the payment QR code path from the EventRequest (already stored)
    $paymentQrPath = $req->payment_qr_code ?? null;

    // Create the event with the necessary fields
    // Note: The Event model uses different field names than EventRequest
    Event::create([
        'title'           => $req->title,  // Required field in events table
        'event_name'      => $req->title,
        'description'     => $req->description,
        'venue'           => $req->venue,
        'location'        => $req->venue,
        'poster_path'     => $req->poster_path,
        'poster'          => $req->poster_path,
        'organizer_id'    => $req->organizer_id,  // Required field in events table
        'created_by'      => $req->organizer_id,
        'status'          => 'approved',
        'start_time'      => $req->start_time,
        'start_at'        => $req->start_time,
        'end_time'        => $req->end_time,
        'end_at'          => $req->end_time,
        'capacity'        => $req->capacity ?? null,
        'payment_qr_code' => $paymentQrPath,
    ]);

    // Update the status of the EventRequest to approved
    $req->update(['status' => 'approved']);

    return back()->with('success', 'Event approved.');
}

    public function pendingEvents()
{
    $requests = EventRequest::where('status', 'pending')
        ->latest()
        ->get();

    return view('admin.events.pending', compact('requests'));
}

    public function approvedEvents()
{
    $events = Event::where('status', 'approved')
        ->with(['creator', 'organizer'])
        ->latest()
        ->paginate(10);

    return view('admin.events.approved', compact('events'));
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

    // Redirect back with a success message
    return redirect()->route('admin.events.pending')->with('success', 'Event request rejected successfully.');
}


    public function userList()
{
    $organizers = User::where('role', 'organizer')->get();
    $approvedAdmins = User::where('role', 'admin')->where('admin_approval_status', 'approved')->get();
    $pendingAdmins = User::where('role', 'admin')->where('admin_approval_status', 'pending')->get();
    return view('admin.user-list', compact('organizers', 'approvedAdmins', 'pendingAdmins'));
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

public function approveAdmin($id)
{
    $user = User::findOrFail($id);
    if ($user->role !== 'admin') {
        return redirect()->back()->with('error', 'User is not an admin.');
    }
    $user->update(['admin_approval_status' => 'approved', 'is_active' => true]);
    return redirect()->back()->with('success', 'Admin approved successfully!');
}

public function rejectAdmin($id)
{
    $user = User::findOrFail($id);
    if ($user->role !== 'admin') {
        return redirect()->back()->with('error', 'User is not an admin.');
    }
    $user->update(['admin_approval_status' => 'rejected', 'is_active' => false]);
    return redirect()->back()->with('error', 'Admin rejected successfully!');
}
}
