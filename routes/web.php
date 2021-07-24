<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('api/courses', function () {
    return response()->json([\App\Models\Course::all()], 200);
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

\Illuminate\Support\Facades\Auth::routes();

Route::middleware(['verified', 'auth'])->group(function () {
    Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('courses', \App\Http\Controllers\CourseController::class);

    Route::get('/mark-completed',
        [\App\Http\Controllers\CourseController::class, 'completedForm'])->name('completedForm');
    Route::post('/mark-completed',
        [\App\Http\Controllers\CourseController::class, 'markAsCompleted'])->name('markAsCompleted');

    Route::get('/recommendations',
        [\App\Http\Controllers\CourseController::class, 'recommendations'])->name('recommendations');

    Route::post('/recommendations',
        [\App\Http\Controllers\CourseController::class, 'recommendationResults'])->name('recommendationResults');
});

