<?php

// app/Http/Controllers/StudentEventController.php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentEventController extends Controller
{
    // List approved, upcoming/current events for students
    public function index()
    {
         $events = \App\Models\Event::query()
        ->where('status', 'approved') // ensure lowercase 'approved' in DB
        ->withCount('registrations')
        ->orderByRaw("
            COALESCE(
                UNIX_TIMESTAMP(start_at),
                UNIX_TIMESTAMP(start_time),
                UNIX_TIMESTAMP(created_at)
            ) ASC
        ")
        ->paginate(9);

        return view('student.events.index', compact('events'));
    }

    // Event details page
    public function show(Event $event)
    {
        abort_unless($event->status === 'approved', 404);

        $alreadyRegistered = Registration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->exists();

        $currentCount = $event->registrations()->count();
        $isFull = $event->capacity ? $currentCount >= $event->capacity : false;

        return view('student.events.show', compact('event','alreadyRegistered','currentCount','isFull'));
    }

    // Register to event
    public function register(Request $request, Event $event)
    {
        abort_unless($event->status === 'approved', 404);

        $request->validate([
            'full_name' => ['nullable','string','max:255'],
            'matric_or_staff_no' => ['nullable','string','max:100'],
            'department' => ['nullable','string','max:255'],
        ]);

        // capacity guard
        if ($event->capacity && $event->registrations()->count() >= $event->capacity) {
            return back()->with('error','This event is already full.');
        }

        Registration::firstOrCreate(
            ['event_id' => $event->id, 'user_id' => Auth::id()],
            [
                'full_name' => $request->full_name ?? Auth::user()->name,
                'matric_or_staff_no' => $request->matric_or_staff_no,
                'department' => $request->department,
            ]
        );

        return redirect()->route('student.events.show', $event)->with('success','Registered successfully!');
    }

    // Cancel registration
    public function cancel(Event $event)
    {
        $reg = Registration::where('event_id',$event->id)->where('user_id',Auth::id())->first();
        if ($reg) $reg->delete();

        return back()->with('success','Registration cancelled.');
    }

    // Mark attendance (student self-check-in)
    public function markAttendance(Event $event)
    {
        $reg = Registration::where('event_id',$event->id)->where('user_id',Auth::id())->firstOrFail();
        $reg->attended = true;
        $reg->save();

        return back()->with('success','Attendance recorded.');
    }

    // List my registered events
    public function my()
    {
        $regs = Registration::with('event')->where('user_id', Auth::id())->latest()->paginate(10);
        return view('student.events.my', compact('regs'));
    }
}
