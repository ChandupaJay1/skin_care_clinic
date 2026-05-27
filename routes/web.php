<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\PatientTreatmentPhotoController;
use App\Http\Controllers\DoctorController;

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportController;

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Protected routes ──────────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', fn() => view('welcome'))->name('dashboard');

    // ── Reports (admin only) ──────────────────────────────────────────────────
    Route::prefix('reports')->name('reports.')->middleware('role:admin')->group(function () {
        Route::get('/daily',             [ReportController::class, 'daily'])->name('daily');
        Route::get('/daily/print',       [ReportController::class, 'printDaily'])->name('daily.print');
        Route::get('/monthly',           [ReportController::class, 'monthly'])->name('monthly');
        Route::get('/monthly/print',     [ReportController::class, 'printMonthly'])->name('monthly.print');
        Route::get('/outstanding',       [ReportController::class, 'outstanding'])->name('outstanding');
        Route::get('/outstanding/print', [ReportController::class, 'printOutstanding'])->name('outstanding.print');
    });

    // ── Invoices ──────────────────────────────────────────────────────────────
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/',                        [InvoiceController::class, 'index'])->name('index');
        Route::get('/patient-appointments',    [InvoiceController::class, 'patientAppointments'])->name('patient-appointments');

        Route::middleware('role:admin,receptionist')->group(function () {
            Route::get('/create', [InvoiceController::class, 'create'])->name('create');
            Route::post('/',      [InvoiceController::class, 'store'])->name('store');
        });

        Route::get('/{invoice}',       [InvoiceController::class, 'show'])->name('show');
        Route::get('/{invoice}/print', [InvoiceController::class, 'print'])->name('print');

        Route::middleware('role:admin')->group(function () {
            Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
        });
    });

    // ── Appointments ──────────────────────────────────────────────────────────
    Route::prefix('appointments')->name('appointments.')->group(function () {

        // Static routes FIRST
        Route::get('/',        [AppointmentController::class, 'index'])->name('index');
        Route::get('/history', [AppointmentController::class, 'history'])->name('history');

        Route::middleware('role:admin,receptionist')->group(function () {
            Route::get('/create', [AppointmentController::class, 'create'])->name('create');
            Route::post('/',      [AppointmentController::class, 'store'])->name('store');
        });

        // JSON endpoint — booked slots for a doctor/date
        Route::get('/booked-slots', [AppointmentController::class, 'bookedSlots'])->name('booked-slots');

        // Wildcard routes AFTER
        Route::get('/{appointment}',        [AppointmentController::class, 'show'])->name('show');
        Route::get('/{appointment}/receipt',[AppointmentController::class, 'receipt'])->name('receipt');

        Route::middleware('role:admin,receptionist')->group(function () {
            Route::get('/{appointment}/edit',    [AppointmentController::class, 'edit'])->name('edit');
            Route::put('/{appointment}',         [AppointmentController::class, 'update'])->name('update');
            Route::patch('/{appointment}/status',[AppointmentController::class, 'updateStatus'])->name('status');
        });
    });

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
