<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/privacy', function () {
    return view('privacy');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

        Route::get('user', [UserController::class, 'index'])->name('user.index');
        Route::get('service', [ServiceController::class, 'index'])->name('service.index');

        Route::group(['middleware' => [\Illuminate\Auth\Middleware\Authorize::using('manage-appointment')]], function () {
            Route::resource('appointment', AppointmentController::class);
        });

    });


    Route::get('/appointments', [AppointmentController::class, 'index'])
        ->name('appointment.index')
    ->middleware('permission:see-appointment');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
