<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\GeoController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\UserAdminController;

Route::get('/', function(){ return view('welcome'); })->name('home');

// Auth
Route::get('register', [RegisteredUserController::class,'create'])->name('register');
Route::post('register', [RegisteredUserController::class,'store'])->name('register.post');

Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.post');
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// 2FA
Route::get('2fa/verify', [TwoFactorController::class,'showVerify'])->name('2fa.verify');
Route::post('2fa/verify', [TwoFactorController::class,'verify'])->name('2fa.verify.post');
Route::post('2fa/resend', [TwoFactorController::class,'resend'])->name('2fa.resend');

// Properties
Route::resource('properties', PropertyController::class);
Route::post('properties/{property}/contact', [ContactController::class,'contactOwner'])->name('properties.contact');

// Dashboard
Route::get('/dashboard', function(){
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Geo
Route::get('/geo/search', [GeoController::class, 'search'])->name('geo.search');
Route::get('/geo/reverse', [GeoController::class, 'reverse'])->name('geo.reverse');


// Reservations
Route::resource('reservations', ReservationController::class)->only(['index','destroy']);
Route::post('properties/{property}/reserve', [ReservationController::class,'store'])->name('properties.reserve');
Route::patch('reservations/{reservation}/status', [ReservationController::class,'updateStatus'])->name('reservations.status');
Route::delete('reservations/{reservation}', [ReservationController::class,'destroy'])->name('reservations.destroy')->middleware('auth');


// Reviews & reports
Route::post('properties/{property}/reviews', [ReviewController::class,'store'])->name('properties.reviews');
Route::post('properties/{property}/report', [ReportController::class,'store'])->name('properties.report');

// Admin group
Route::prefix('admin')->name('admin.')->middleware(['auth','can:admin'])->group(function(){
    Route::resource('users', UserAdminController::class);
    // plus tard : settings, reports moderation...
});