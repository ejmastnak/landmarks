<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\Landmark;

use App\Http\Controllers\LandmarkController;

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

Route::post('landmarks/export', [LandmarkController::class, 'exportLandmarks'])->name('landmarks.export');
Route::get('landmarks', [LandmarkController::class, 'index'])->name('landmarks.index');
// Redirect to home page to Landmarks/Index
Route::get('/', function () {
    return redirect()->route('landmarks.index');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('landmarks/create', [LandmarkController::class, 'create'])->name('landmarks.create')->can('create', Landmark::class);
    Route::post('landmarks', [LandmarkController::class, 'store'])->name('landmarks.store')->can('create', Landmark::class);
    Route::get('landmarks/{landmark}/edit', [LandmarkController::class, 'edit'])->name('landmarks.edit')->can('update', 'landmark');
    Route::put('landmarks/{landmark}', [LandmarkController::class, 'update'])->name('landmarks.update')->can('update', 'landmark');
    Route::delete('landmarks/{landmark}', [LandmarkController::class, 'destroy'])->name('landmarks.destroy')->can('delete', 'landmark');
});

// The route should be defined last among the landmarks routes, since the
// catch-all parameter `landmarks/{landmarks}` would otherwise "capture" e.g.
// landmarks/create and landmarks/export
Route::get('landmarks/{landmark}', [LandmarkController::class, 'show'])->name('landmarks.show');


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__.'/auth.php';
