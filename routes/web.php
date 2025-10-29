<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EducationResourceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ScreeningController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('patients', PatientController::class);
Route::resource('screenings', ScreeningController::class);
Route::resource('resources', EducationResourceController::class);
