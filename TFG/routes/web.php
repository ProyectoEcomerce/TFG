<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TournController;
use App\Http\Controllers\UserController;
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


Route::middleware('auth', 'verified')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/tourns/{id}', [TournController::class, 'index'])->name('show.area');

    Route::get('/avai', [AvailabilityController::class, 'index'])->name('availability');

    Route::get('/getTourns/{id}', [TournController::class, 'getTourns']);

    Route::delete('/deleteTourns/{id}', [TournController::class, 'deleteTourn']);

    Route::put('/updateTourns/{id}', [TournController::class, 'updateTourn']);

    Route::post('/fill-tourns/{id}', [TournController::class, 'fillTourns'])->name('/fill-tourns/{id}');

    Route::post('/deleteIntervaTourns/{id}', [TournController::class, 'deleteIntervalTourns'])->name('/deleteIntervaTourns/{id}');

    Route::post('/create-availability', [AvailabilityController::class, 'createAvailability'])->name('/create-availability');

    Route::post('/create-tourn', [TournController::class, 'createTourn'])->name('/create-tourn');

    Route::get('/getAvailability', [AvailabilityController::class, 'getAvailability']);

    Route::delete('/deleteAvailability/{id}', [AvailabilityController::class, 'deleteAvailability']);

    Route::put('/updateAvailability/{id}', [AvailabilityController::class, 'updateAvailability']);

    Route::get('/', [AreaController::class, 'getAreas']);

    Route::get('/home', [UserController::class, 'show'])->name('home');

    Route::put('/updateProfile/{id}', [UserController::class, 'updateUser'])->name('updateUser');

    Route::post('/user/upload-profile-image', [UserController::class, 'uploadProfileImage'])
    ->name('user.uploadProfileImage');
});

Route::middleware('admin')->group(function () {
    Route::get('/admin', [AdminController::class, 'getAdmin'])->name('viewAdmin');

    Route::get('/adminAreas', [AdminController::class, 'getAreas'])->name('adminAreas');
    Route::put('/updateArea/{id}', [AdminController::class, 'updateArea'])->name('updateArea');
    Route::post('/createArea', [AdminController::class, 'createArea'])->name('createArea');

    Route::get('/adminUsers', [AdminController::class, 'getUsers'])->name('adminUsers');
    Route::put('/updateUserAdmin/{id}', [AdminController::class, 'updateUser'])->name('updateUserAdmin');
    Route::get('/usersFilter', [AdminController::class, 'filterUsers'])->name('usersFilter');
    Route::get('/areaFilter', [AdminController::class, 'areaFilter'])->name('areaFilter');
});