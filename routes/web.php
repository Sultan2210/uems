<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Organizer\EventRequestController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentEventController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->name('admin.dashboard')
    ->middleware('auth');

Route::get('/organizer/dashboard', function () {
    return view('layouts.organizer');
})->name('organizer.dashboard')->middleware('auth');

Route::get('/student/dashboard', fn () => redirect()->route('student.events.index'))
    ->middleware(['auth','role:student'])
    ->name('student.dashboard');

Route::get('/students/dashboard', fn () => redirect()->route('student.events.index'))
    ->middleware(['auth','role:student']);
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Other routes...
    Route::get('/admin/user-list', [AdminController::class, 'userList'])->name('admin.user.list');
    Route::post('/admin/user/activate/{id}', [AdminController::class, 'activateUser'])->name('admin.user.activate');
    Route::post('/admin/user/deactivate/{id}', [AdminController::class, 'deactivateUser'])->name('admin.user.deactivate');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// Organizer routes
Route::prefix('organizer')->middleware(['auth', 'role:organizer'])->name('organizer.')->group(function () {
    // Display all submitted events
    Route::get('/events', [OrganizerController::class, 'index'])->name('events.index');

    // Edit event
    Route::get('/events/edit/{id}', [OrganizerController::class, 'edit'])->name('events.edit');

    // Update event (for rejected events)
    Route::post('/events/update/{id}', [OrganizerController::class, 'update'])->name('events.update');

    // Delete event
    Route::post('/events/delete/{id}', [OrganizerController::class, 'destroy'])->name('events.delete');
});

Route::middleware(['auth', 'role:organizer'])->prefix('organizer')->name('organizer.')->group(function () {
    // Display events for the organizer
    Route::get('/dashboard', [OrganizerController::class, 'index'])->name('dashboard');
});

Route::prefix('organizer')->middleware(['auth', 'role:organizer'])->name('organizer.')->group(function () {
    // Display the event creation form
    Route::get('/add-event', [OrganizerController::class, 'create'])->name('events.create');

    // Store the new event
    Route::post('/add-event', [OrganizerController::class, 'store'])->name('events.store');
});



// Admin routes
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/approved-events', [AdminController::class, 'approvedEvents'])
        ->name('admin.events.approved');

    // (optional) pending/rejected listing
    Route::get('/admin/pending-events', [AdminController::class, 'pendingEvents'])
        ->name('admin.events.pending');
    Route::get('/admin/rejected-events', [AdminController::class, 'rejectedEvents'])
        ->name('admin.events.rejected');

    // approve / reject actions you already have
    Route::post('/admin/approve/{id}', [AdminController::class, 'approveRequest'])
        ->name('admin.events.approve');
    Route::post('/admin/reject/{id}', [AdminController::class, 'rejectRequest'])
        ->name('admin.events.reject');
});
require __DIR__.'/auth.php';

Route::prefix('student')->middleware(['auth','role:student'])->group(function () {
    Route::get('/events',[StudentEventController::class, 'index'])->name('student.events.index');   // list approved events
    Route::get('/events/{event}',[StudentEventController::class, 'show'])->name('student.events.show');     // event details
    Route::post('/events/{event}/register', [StudentEventController::class, 'register'])->name('student.events.register');
    Route::post('/events/{event}/cancel',   [StudentEventController::class, 'cancel'])->name('student.events.cancel');
    Route::post('/events/{event}/attend',   [StudentEventController::class, 'markAttendance'])->name('student.events.attend');
    Route::get('/my-registrations',         [StudentEventController::class, 'my'])->name('student.events.my');
});

