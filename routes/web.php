<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EducationResourceController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientPortalAuthController;
use App\Http\Controllers\CaseNoteController;
use App\Http\Controllers\PatientPortalDashboardController;
use App\Http\Controllers\PublicPatientRegistrationController;
use App\Http\Controllers\PublicSiteController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ScreeningController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicSiteController::class, 'home'])->name('home');
Route::get('/inscription', [PublicPatientRegistrationController::class, 'create'])->name('registration.create');
Route::post('/inscription', [PublicPatientRegistrationController::class, 'store'])->name('registration.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register-admin', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register-admin', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/mon-espace', [PatientPortalAuthController::class, 'create'])->name('patient-portal.login');
Route::post('/mon-espace', [PatientPortalAuthController::class, 'store'])->name('patient-portal.authenticate');
Route::post('/mon-espace/deconnexion', [PatientPortalAuthController::class, 'destroy'])
    ->middleware('patient.auth')
    ->name('patient-portal.logout');
Route::get('/mon-espace/tableau-de-bord', [PatientPortalDashboardController::class, 'index'])
    ->middleware('patient.auth')
    ->name('patient-portal.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('patients', PatientController::class);
    Route::resource('screenings', ScreeningController::class);
    Route::resource('screenings.prescriptions', PrescriptionController::class)
        ->except(['index', 'show'])
        ->shallow();
    Route::resource('follow-ups', FollowUpController::class)->except(['show']);
    Route::resource('patients.case-notes', CaseNoteController::class)
        ->except(['index', 'show', 'create'])
        ->shallow();
    Route::resource('resources', EducationResourceController::class);

    Route::resource('users', UserManagementController::class)->except(['show']);
});
