<?php

use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Spatie\FlareClient\View;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function(){
    return view('auth.dashboard');
})->middleware(['auth', 'verified']);

Route::get('/inicio', [HomeController::class, 'index'])->name('home');

Route::get('/events', [AvailabilityController::class, 'getAvailability']);

Route::delete('/event/{id}', [AvailabilityController::class, 'deleteAvailability']);

Route::put('/event/{id}', [AvailabilityController::class, 'updateAvailability']);