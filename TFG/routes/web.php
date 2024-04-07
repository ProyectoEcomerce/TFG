<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TournController;
use App\Models\Tourn;
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

Route::get('/tourns/{id}', [TournController::class, 'index'])->name('show.area');

Route::get('/avai', [AvailabilityController::class, 'index'])->name('availability');

Route::get('/getTourns/{id}', [TournController::class, 'getTourns']);

Route::delete('/deleteTourns/{id}', [TournController::class, 'deleteTourn']);

Route::put('/updateTourns/{id}', [TournController::class, 'updateTourn']);

Route::post('/fill-tourns/{id}', [TournController::class, 'fillTourns'])->name('/fill-tourns/{id}');

Route::post('/create-availability', [AvailabilityController::class, 'createAvailability'])->name('/create-availability');

Route::post('/create-tourn', [TournController::class, 'createTourn'])->name('/create-tourn');

Route::get('/getAvailability', [AvailabilityController::class, 'getAvailability']);

Route::delete('/deleteAvailability/{id}', [AvailabilityController::class, 'deleteAvailability']);

Route::put('/updateAvailability/{id}', [AvailabilityController::class, 'updateAvailability']);

Route::get('/inicio' , [AreaController::class, 'getAreas']);