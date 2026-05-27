<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\PatientTreatmentPhotoController;
use App\Http\Controllers\DoctorController;

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Protected routes ──────────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', fn() => view('welcome'))->name('dashboard');

    // ── Patients ──────────────────────────────────────────────────────────────
    Route::prefix('patients')->name('patients.')->group(function () {

        // Static routes FIRST (before any {patient} wildcard)
        Route::get('/',       [PatientController::class, 'index'])->name('index');

        Route::middleware('role:admin,receptionist')->group(function () {
            Route::get('/create',  [PatientController::class, 'create'])->name('create');
            Route::post('/',       [PatientController::class, 'store'])->name('store');
        });

        // Wildcard routes AFTER static ones
        Route::get('/{patient}',      [PatientController::class, 'show'])->name('show');

        Route::middleware('role:admin,receptionist')->group(function () {
            Route::get('/{patient}/edit',  [PatientController::class, 'edit'])->name('edit');
            Route::put('/{patient}',       [PatientController::class, 'update'])->name('update');
            Route::delete('/{patient}',    [PatientController::class, 'destroy'])->name('destroy');
            Route::post('/{patient}/barcode', [PatientController::class, 'regenerateBarcode'])->name('barcode.regenerate');
        });

        // Treatment progress photos
        Route::get('/{patient}/treatment-photos/add',        [PatientTreatmentPhotoController::class, 'create'])->name('treatment-photos.create');
        Route::get('/{patient}/treatment-photos/compare',    [PatientTreatmentPhotoController::class, 'compare'])->name('treatment-photos.compare');
        Route::post('/{patient}/treatment-photos',           [PatientTreatmentPhotoController::class, 'store'])->name('treatment-photos.store');
        Route::delete('/{patient}/treatment-photos/{photo}', [PatientTreatmentPhotoController::class, 'destroy'])->name('treatment-photos.destroy');
    });

    // ── Doctors ───────────────────────────────────────────────────────────────
    Route::prefix('doctors')->name('doctors.')->group(function () {

        // Static routes FIRST
        Route::get('/', [DoctorController::class, 'index'])->name('index');

        Route::middleware('role:admin')->group(function () {
            Route::get('/create', [DoctorController::class, 'create'])->name('create');
            Route::post('/',      [DoctorController::class, 'store'])->name('store');
        });

        // Wildcard routes AFTER
        Route::get('/{doctor}', [DoctorController::class, 'show'])->name('show');

        Route::middleware('role:admin')->group(function () {
            Route::get('/{doctor}/edit', [DoctorController::class, 'edit'])->name('edit');
            Route::put('/{doctor}',      [DoctorController::class, 'update'])->name('update');
            Route::delete('/{doctor}',   [DoctorController::class, 'destroy'])->name('destroy');
        });
    });

    // ── Treatments ────────────────────────────────────────────────────────────
    Route::prefix('treatments')->name('treatments.')->group(function () {

        // Static routes FIRST
        Route::get('/', [TreatmentController::class, 'index'])->name('index');

        Route::middleware('role:admin,doctor')->group(function () {
            Route::get('/create', [TreatmentController::class, 'create'])->name('create');
            Route::post('/',      [TreatmentController::class, 'store'])->name('store');
        });

        // Wildcard routes AFTER
        Route::get('/{treatment}', [TreatmentController::class, 'show'])->name('show');

        Route::middleware('role:admin,doctor')->group(function () {
            Route::get('/{treatment}/edit', [TreatmentController::class, 'edit'])->name('edit');
            Route::put('/{treatment}',      [TreatmentController::class, 'update'])->name('update');
            Route::delete('/{treatment}',   [TreatmentController::class, 'destroy'])->name('destroy');
        });
    });

});
