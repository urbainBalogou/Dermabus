<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EducationResourceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PublicPatientRegistrationController;
use App\Http\Controllers\PublicSiteController;
use App\Http\Controllers\ScreeningController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicSiteController::class, 'home'])->name('home');
Route::get('/inscription', [PublicPatientRegistrationController::class, 'create'])->name('registration.create');
Route::post('/inscription', [PublicPatientRegistrationController::class, 'store'])->name('registration.store');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('patients', PatientController::class);
Route::resource('screenings', ScreeningController::class);
Route::resource('resources', EducationResourceController::class);
