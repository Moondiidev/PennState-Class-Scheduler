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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('courses', \App\Http\Controllers\CourseController::class);

    Route::get('/mark-completed',
        [\App\Http\Controllers\CourseController::class, 'completedForm'])->name('completedForm');
    Route::post('/mark-completed',
        [\App\Http\Controllers\CourseController::class, 'markAsCompleted'])->name('markAsCompleted');
});

