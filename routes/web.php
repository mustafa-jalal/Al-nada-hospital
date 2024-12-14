<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VisitController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [VisitController::class, 'todayVisits'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('patients', PatientController::class);
    Route::resource('visits', VisitController::class);
    Route::get('visits/{id}/sticker', [VisitController::class, 'printSticker'])->name('visits.sticker');
    Route::post('visits/{id}/checkout', [VisitController::class, 'checkOut'])->name('visits.checkout');
    Route::get('/visits', [VisitController::class, 'index'])->name('visits.index');
    Route::post('/visits/{visit}/cancel', [VisitController::class, 'cancel'])->name('visits.cancel');
});

require __DIR__.'/auth.php';
