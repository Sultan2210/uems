<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentEventController extends Controller
{
    /* =====================================================
       1. EVENT LIST
    ===================================================== */
    public function index(Request $request)
    {
        $query = Event::where('status', 'approved');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('event_name', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $events = $query
            ->withCount('registrations')
            ->orderBy('start_at')
            ->paginate(9);

        return view('student.events.index', compact('events'));
    }

    /* =====================================================
       2. EVENT DETAIL
    ===================================================== */
   public function show($eventId)
{
    // Find the event using the provided event ID
    $event = Event::findOrFail($eventId);

    // Check if the student is registered for the event using matric_or_staff_no
    $registration = $event->registrations()->where('matric_or_staff_no', auth()->user()->matric_no)->first();

    // Check if the event is full (Optional, depending on your requirements)
$isFull = ($event->capacity !== null) &&
          ($event->registrations()->count() >= $event->capacity);

    // Determine the status of the registration
    $alreadyRegistered = false;
    $alreadyAttended = false;

    if ($registration) {
        // Check if the student is registered
        $alreadyRegistered = $registration->status === 'registered' || $registration->status === 'attended';

        // Check if the student has already attended
        $alreadyAttended = $registration->status === 'attended';
    }

    // Return the event details view and pass the necessary data
    return view('student.events.show', compact('event', 'registration', 'alreadyRegistered', 'alreadyAttended', 'isFull'));
}


    /* =====================================================
       3. REGISTER (FREE EVENT)
    ===================================================== */
    public function register(Request $request, Event $event)
{
    $request->validate([
        'matric_or_staff_no' => 'required|string',
        'phone' => 'required|string',
    ]);

    Registration::updateOrCreate(
    [
        'event_id' => $event->id,
        'user_id'  => Auth::id(), // ✅ REQUIRED
    ],
    [
        'matric_or_staff_no' => Auth::user()->matric_no, // keep this too
        'full_name' => $request->full_name ?? Auth::user()->name,
        'status' => 'registered',
    ]
    );

    return redirect()->route('student.events.show', $event->id);
}

    /* =====================================================
       4. PAYMENT PAGE (BERBAYAR SAHAJA)
    ===================================================== */
    public function paymentPage(Event $event)
    {
        abort_unless($event->fee > 0, 404);
        return view('student.events.payment', compact('event'));
    }

    /* =====================================================
       5. SUBMIT PAYMENT
    ===================================================== */
    public function submitPayment(Request $request, Event $event)
    {
        $request->validate([
            'payment_receipt' => 'required|image|max:2048',
        ]);

        $path = $request->file('payment_receipt')
                        ->store('receipts', 'public');

        Registration::updateOrCreate(
            [
                'event_id' => $event->id,
                'user_id'  => Auth::id(),
            ],
            [
                'full_name' => $request->full_name ?? Auth::user()->name,
                'matric_or_staff_no' => $request->matric_or_staff_no,
                'phone' => $request->phone,
                'proof_of_payment' => $path,
                'status' => 'registered',
            ]
        );

        return redirect()
            ->route('student.events.show', $event->id)
            ->with('success_register', true);
    }

    /* =====================================================
       6. MARK ATTENDANCE
    ===================================================== */
    public function markAttendance($eventId)
{
    $event = Event::findOrFail($eventId);
    $student = auth()->user(); // Assuming the student is the logged-in user

    // Check if the student is registered for the event
    $registration = $event->registrations()->where('matric_or_staff_no', $student->matric_no)->first();
    if (!$registration) {
        return back()->with('error', 'You are not registered for this event.');
    }

    // Update the status to 'attended' in the pivot table
    $registration->update(['status' => 'attended']);

    return back()->with('success', 'Your attendance has been marked.');
}



    /* =====================================================
       7. MY REGISTERED EVENTS  ✅ (INI YANG MISSING)
    ===================================================== */
    public function my()
    {
        $registrations = Registration::with('event')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('student.events.my', compact('registrations'));
    }

    /* =====================================================
       8. FEEDBACK PAGE
    ===================================================== */


public function showFeedbackForm($eventId)
{
    $event = Event::findOrFail($eventId);

    // Check if the student is registered for the event
    $registration = $event->registrations()->where('student_id', auth()->user()->id)->first();

    if (!$registration) {
        return redirect()->route('student.events.index')->with('error', 'You are not registered for this event.');
    }

    return view('student.events.feedback', compact('event'));
}

 public function addFeedback(Request $request, $eventId)
{
    $request->validate([
        'feedback' => 'required|string|max:1000',
    ]);

    $event = Event::findOrFail($eventId);
    $student = auth()->user();

    // Check if the student is registered for the event
    $registration = $event->registrations()->where('matric_or_staff_no', $student->matric_no)->first();
    if (!$registration) {
        return back()->with('error', 'You are not registered for this event.');
    }

    // Update the feedback in the pivot table
    $registration->update(['feedback' => $request->input('feedback')]);

    return back()->with('success', 'Your feedback has been submitted.');
}


    /* =====================================================
       9. STORE FEEDBACK
    ===================================================== */
    public function storeFeedback(Request $request, Event $event)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        // (Optional) save feedback to DB later

        return redirect()
            ->route('student.events.show', $event->id)
            ->with('success_feedback', true);
    }
}
