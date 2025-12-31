<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
{
    $validationRules = [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['required', 'in:student,organizer,admin'],
        'matric_no' => ['nullable', 'string', 'max:255'],
        'kulliyyah' => ['nullable', 'string', 'max:255'],
    ];

    // Require picture only for admin role
    if ($request->role === 'admin') {
        $validationRules['picture'] = ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'];
    } else {
        $validationRules['picture'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'];
    }

    $request->validate($validationRules);

    // Handle picture upload
    $picturePath = null;
    if ($request->hasFile('picture')) {
        $picturePath = $request->file('picture')->store('profile_pictures', 'public');
    }

    // Create user and assign to $user (this is the important part)
    $adminApprovalStatus = null;
    if ($request->role === 'admin') {
        $adminApprovalStatus = 'pending'; // New admin registrations need approval
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'matric_no' => $request->matric_no,
        'kulliyyah' => $request->kulliyyah,
        'picture' => $picturePath,
        'admin_approval_status' => $adminApprovalStatus,
    ]);

    // Fire Registered event with the created user
    event(new Registered($user));

    // Log the user in (except for pending admins)
    if ($request->role !== 'admin' || $adminApprovalStatus !== 'pending') {
        Auth::login($user);
    }

    // Redirect based on role (adjust route names as you have them)
    if ($user->role === 'admin' && $adminApprovalStatus === 'pending') {
        Auth::logout();
        return redirect()->route('login')->with('status', 'Your admin registration is pending approval. Please wait for an administrator to approve your account.');
    } elseif ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'organizer') {
        return redirect()->route('organizer.dashboard');
    } else {
        return redirect()->route('student.events.index'); // or 'student.dashboard' if you keep it
    }
}

}
