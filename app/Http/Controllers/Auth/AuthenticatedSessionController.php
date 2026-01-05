<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Check if the error is due to multiple roles
            $errors = $e->errors();
            if (isset($errors['email']) && in_array('multiple_roles', $errors['email'])) {
                $email = session('pending_login_email') ?? $request->input('email');
                $users = User::where('email', $email)->get();
                $hasOrganizer = $users->contains('role', 'organizer');
                $hasStudent = $users->contains('role', 'student');

                // Return to login with role selection
                return back()
                    ->withInput($request->only('email'))
                    ->with('multiple_roles', true)
                    ->with('has_organizer', $hasOrganizer)
                    ->with('has_student', $hasStudent)
                    ->with('pending_login_email', $email);
            }

            // Re-throw other validation errors
            throw $e;
        }

        if (!auth()->user()->is_active) {
            Auth::logout();
            return back()->withErrors(['email' => 'Your account has been deactivated by the admin.']);
        }

        // Check if admin is approved
        $user = auth()->user();
        if ($user->role === 'admin' && $user->admin_approval_status !== 'approved') {
            Auth::logout();
            if ($user->admin_approval_status === 'rejected') {
                return back()->withErrors(['email' => 'Your admin account has been rejected. Please contact an administrator.']);
            } else {
                return back()->withErrors(['email' => 'Your admin account is pending approval. Please wait for an administrator to approve your account.']);
            }
        }

        $request->session()->regenerate();

        // Clear any pending login email from session
        $request->session()->forget('pending_login_email');
        $request->session()->forget('multiple_roles');
        $request->session()->forget('has_organizer');
        $request->session()->forget('has_student');

        $user = $request->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'organizer') {
            return redirect()->route('organizer.dashboard');
        } else {
            return redirect()->route('student.dashboard');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
