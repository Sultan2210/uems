<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\StudentEventController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH DASHBOARD REDIRECT
|--------------------------------------------------------------------------
*/
Route::get('/student/dashboard', fn () =>
    redirect()->route('student.events.index')
)->middleware(['auth','role:student'])->name('student.dashboard');

Route::get('/organizer/dashboard', fn () =>
    view('layouts.organizer')
)->middleware(['auth','role:organizer'])->name('organizer.dashboard');

Route::get('/admin/dashboard', fn () =>
    view('layouts.admin')
)->middleware(['auth','role:admin'])->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',[ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class,'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class,'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/approved-events',[AdminController::class,'approvedEvents'])
        ->name('events.approved');

    Route::get('/pending-events',[AdminController::class,'pendingEvents'])
        ->name('events.pending');

    Route::get('/rejected-events',[AdminController::class,'rejectedEvents'])
        ->name('events.rejected');

    Route::post('/approve/{id}',[AdminController::class,'approveRequest'])
        ->name('events.approve');

    Route::post('/reject/{id}',[AdminController::class,'rejectRequest'])
        ->name('events.reject');

    Route::get('/user-list',[AdminController::class,'userList'])
        ->name('users.list');

    Route::post('/user/activate/{id}',[AdminController::class,'activateUser'])
        ->name('users.activate');

    Route::post('/user/deactivate/{id}',[AdminController::class,'deactivateUser'])
        ->name('users.deactivate');
});

/*
|--------------------------------------------------------------------------
| ORGANIZER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:organizer'])->prefix('organizer')->name('organizer.')->group(function () {

    Route::get('/events/create',[OrganizerController::class,'create'])
        ->name('events.create');

    Route::post('/events/store',[OrganizerController::class,'store'])
        ->name('events.store');
});

/*
|--------------------------------------------------------------------------
| STUDENT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:student'])->prefix('student')->name('student.')->group(function () {

    // Event browsing
    Route::get('/events',[StudentEventController::class,'index'])
        ->name('events.index');

    Route::get('/events/{event}',[StudentEventController::class,'show'])
        ->name('events.show');

    // Register
    Route::post('/events/{event}/register',[StudentEventController::class,'register'])
        ->name('events.register');

    // Payment (PAID ONLY)
    Route::get('/events/{event}/payment',[StudentEventController::class,'paymentPage'])
        ->name('events.payment');

    Route::post('/events/{event}/payment',[StudentEventController::class,'submitPayment'])
        ->name('events.submit_payment');

    // My Events
    Route::get('/my-events',[StudentEventController::class,'my'])
        ->name('events.my');

    // Attendance
    Route::post('/events/{event}/attend',[StudentEventController::class,'markAttendance'])
        ->name('events.attend');

    // Feedback
    Route::get('/events/{event}/feedback',[StudentEventController::class,'feedback'])
        ->name('events.feedback');

    Route::post('/events/{event}/feedback',[StudentEventController::class,'storeFeedback'])
        ->name('events.feedback.store');
});

require __DIR__.'/auth.php';
