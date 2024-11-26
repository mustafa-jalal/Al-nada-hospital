<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\VisitController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [VisitController::class, 'todaysVisits']);
Route::resource('patients', PatientController::class);
Route::resource('visits', VisitController::class);
Route::get('visits/{id}/sticker', [VisitController::class, 'printSticker'])->name('visits.sticker');
Route::post('visits/{id}/checkout', [VisitController::class, 'checkOut'])->name('visits.checkout');
Route::get('/visits', [VisitController::class, 'index'])->name('visits.index');
Route::post('/visits/{id}/cancel', [VisitController::class, 'cancel'])->name('visits.cancel');