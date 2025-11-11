<?php
namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Models\EventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:organizer']); // we'll create role middleware if not exist
    }

    // show form
    public function create()
    {
        return view('organizer.events.create');
    }

    // store request
    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();
        $data['organizer_id'] = $request->user()->id;
        $data['has_certificate'] = $request->has('has_certificate') ? 1 : 0;

        // handle poster upload
        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $filename = Str::slug($data['title']).'-'.time().'.'.$file->getClientOriginalExtension();
            // store in storage/app/public/posters
            $path = $file->storeAs('posters', $filename, 'public');
            $data['poster_path'] = $path; // will be /storage/posters/filename via storage:link
        }

        $eventRequest = EventRequest::create($data);

        // optional: notify admin by email/notification
        // AdminNotification::dispatch($eventRequest);

        return redirect()->route('organizer.events.create')->with('success','Event request submitted. Awaiting admin approval.');
    }

    // (optional) show list of requests, edit, etc.
    public function index()
    {
        $requests = EventRequest::where('organizer_id', auth()->id())->latest()->paginate(10);
        return view('organizer.events.index', compact('requests'));
    }
}
