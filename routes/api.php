<?php

use App\Http\Controllers\Api\EducationResourceApiController;
use App\Http\Controllers\Api\PatientApiController;
use App\Http\Controllers\Api\ScreeningApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('patients', PatientApiController::class);
    Route::apiResource('screenings', ScreeningApiController::class);
    Route::apiResource('resources', EducationResourceApiController::class);
});
