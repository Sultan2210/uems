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
    public function show(Event $event)
    {
        abort_unless($event->status === 'approved', 404);

        $registration = Registration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();

        $alreadyRegistered = $registration && $registration->status === 'registered';
        $alreadyAttended   = $registration && $registration->status === 'attended';

        $currentCount = $event->registrations()->count();
        $isFull = $event->capacity
            ? $currentCount >= $event->capacity
            : false;

        return view('student.events.show', compact(
            'event',
            'alreadyRegistered',
            'alreadyAttended',
            'currentCount',
            'isFull'
        ));
    }

    /* =====================================================
       3. REGISTER (FREE EVENT)
    ===================================================== */
    public function register(Request $request, Event $event)
    {
        abort_unless($event->status === 'approved', 404);

        // Jika event BERBAYAR → redirect ke payment page
        if ($event->fee > 0) {
            return redirect()
                ->route('student.events.payment', $event->id)
                ->withInput();
        }

        // Event FREE
        Registration::updateOrCreate(
            [
                'event_id' => $event->id,
                'user_id'  => Auth::id(),
            ],
            [
                'full_name' => $request->full_name ?? Auth::user()->name,
                'matric_or_staff_no' => $request->matric_or_staff_no,
                'phone' => $request->phone,
                'status' => 'registered',
            ]
        );

        return redirect()
            ->route('student.events.show', $event->id)
            ->with('success_register', true);
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
    public function markAttendance(Event $event)
    {
        $registration = Registration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $registration->update([
            'status' => 'attended'
        ]);

        return back()->with('success_attended', true);
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
    public function feedback(Event $event)
    {
        return view('student.events.feedback', compact('event'));
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
