<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Feedback;
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
                $q->where('event_name', 'like', "%{$request->search}%")
                  ->orWhere('location', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
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
        $event = Event::findOrFail($eventId);

        $registration = Registration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();

        $alreadyRegistered = $registration !== null;
        $alreadyAttended   = $registration && $registration->status === 'attended';

        $isFull = $event->capacity
            ? $event->registrations()->count() >= $event->capacity
            : false;

        // Check if user can mark attendance (between event start and 15 minutes after end)
        $canMarkAttendance = false;
        if ($event->start_at && $event->end_at) {
            // Get current time
            $now = now();

            // start_at and end_at are already Carbon instances due to model cast
            // They are in the app timezone (Asia/Kuala_Lumpur - Malaysia time)
            $startTime = $event->start_at;
            $endTime = $event->end_at->copy()->addMinutes(15);

            // Compare times: current time must be >= start AND <= end + 15 minutes
            $canMarkAttendance = $now->greaterThanOrEqualTo($startTime) && $now->lessThanOrEqualTo($endTime);
        }

        return view('student.events.show', compact(
            'event',
            'registration',
            'alreadyRegistered',
            'alreadyAttended',
            'isFull',
            'canMarkAttendance'
        ));
    }

    /* =====================================================
       3. REGISTER EVENT
    ===================================================== */
    public function register(Request $request, Event $event)
    {
        // Check if payment is required (payment_qr_code exists or requires_payment is true)
        $paymentRequired = ($event->payment_qr_code || $event->requires_payment);

        $validationRules = [
            'matric_or_staff_no' => 'required|string|max:255',
            'department' => 'required|string|max:255',
        ];

        // If payment is required, validate receipt upload
        if ($paymentRequired) {
            $validationRules['payment_receipt'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        $request->validate($validationRules);

        // Handle payment receipt upload
        $paymentReceiptPath = null;
        if ($request->hasFile('payment_receipt')) {
            $paymentReceiptPath = $request->file('payment_receipt')->store('payment_receipts', 'public');
        }

        Registration::updateOrCreate(
            [
                'event_id' => $event->id,
                'user_id'  => Auth::id(),
            ],
            [
                'full_name' => Auth::user()->name,
                'matric_or_staff_no' => $request->matric_or_staff_no,
                'department' => $request->department,
                'payment_receipt' => $paymentReceiptPath,
                'status' => 'registered',
            ]
        );

        return redirect()
            ->route('student.events.show', $event->id)
            ->with('success_register', true);
    }

    /* =====================================================
       4. MARK ATTENDANCE
    ===================================================== */
    public function markAttendance($eventId)
    {
        $event = Event::findOrFail($eventId);

        // Validate that the current time is within the allowed window
        if (!$event->start_at || !$event->end_at) {
            return back()->withErrors(['error' => 'Event timing information is not available.']);
        }

        $now = now();
        $startTime = \Carbon\Carbon::parse($event->start_at);
        $endTime = \Carbon\Carbon::parse($event->end_at)->addMinutes(15);

        if ($now->lessThan($startTime)) {
            return back()->withErrors(['error' => 'Attendance cannot be marked before the event starts.']);
        }

        if ($now->greaterThan($endTime)) {
            return back()->withErrors(['error' => 'Attendance can only be marked up to 15 minutes after the event ends.']);
        }

        $registration = Registration::where('event_id', $eventId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $registration->update([
            'status' => 'attended'
        ]);

        return back()->with('success_attended', true);
    }

    /* =====================================================
       5. MY REGISTERED EVENTS
    ===================================================== */
    public function my()
    {
        $registrations = Registration::with('event')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(9);

        return view('student.events.my', compact('registrations'));
    }

    /* =====================================================
       6. FEEDBACK LIST (ATTENDED ONLY)
    ===================================================== */
    public function feedbackList()
    {
        $registrations = Registration::with('event')
            ->where('user_id', Auth::id())
            ->where('status', 'attended')
            ->paginate(9);

        return view('student.events.feedback', compact('registrations'));
    }

    /* =====================================================
       7. FEEDBACK FORM (PER EVENT)
    ===================================================== */
    public function showFeedbackForm($eventId)
    {
        $event = Event::findOrFail($eventId);

        Registration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->where('status', 'attended')
            ->firstOrFail();

        return view('student.events.feedback', compact('event'));
    }

    /* =====================================================
       8. SUBMIT FEEDBACK
    ===================================================== */
    public function addFeedback(Request $request, $eventId)
    {
        $request->validate([
            'feedback' => 'required|string|max:1000',
        ]);

        // Verify user has attended the event
        $registration = Registration::where('event_id', $eventId)
            ->where('user_id', Auth::id())
            ->where('status', 'attended')
            ->firstOrFail();

        // Create feedback record
        Feedback::create([
            'event_id' => $eventId,
            'user_id' => Auth::id(),
            'student_name' => Auth::user()->name,
            'matric_number' => $request->matric_number,
            'email' => Auth::user()->email,
            'comments' => $request->feedback,
        ]);

        return back()->with('success', 'Feedback submitted successfully.');
    }
}
