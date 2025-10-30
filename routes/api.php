<?php

use App\Http\Controllers\Api\CaseNoteApiController;
use App\Http\Controllers\Api\EducationResourceApiController;
use App\Http\Controllers\Api\FollowUpApiController;
use App\Http\Controllers\Api\PatientApiController;
use App\Http\Controllers\Api\ScreeningApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('patients', PatientApiController::class)->names('api.patients');
    Route::apiResource('screenings', ScreeningApiController::class)->names('api.screenings');
    Route::apiResource('follow-ups', FollowUpApiController::class)->names('api.follow-ups');
    Route::apiResource('case-notes', CaseNoteApiController::class)->names('api.case-notes');
    Route::apiResource('resources', EducationResourceApiController::class)->names('api.resources');
});
