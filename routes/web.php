<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;

// Landing page
Route::get('/', function () {
    return view('welcome');
});

// Patient routes
Route::prefix('patients')->name('patients.')->group(function () {
    Route::get('/',                          [PatientController::class, 'index'])->name('index');
    Route::get('/create',                    [PatientController::class, 'create'])->name('create');
    Route::post('/',                         [PatientController::class, 'store'])->name('store');
    Route::get('/{patient}',                 [PatientController::class, 'show'])->name('show');
    Route::get('/{patient}/edit',            [PatientController::class, 'edit'])->name('edit');
    Route::put('/{patient}',                 [PatientController::class, 'update'])->name('update');
    Route::delete('/{patient}',              [PatientController::class, 'destroy'])->name('destroy');
    Route::post('/{patient}/barcode',        [PatientController::class, 'regenerateBarcode'])->name('barcode.regenerate');
});
