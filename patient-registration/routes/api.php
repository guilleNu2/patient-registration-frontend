<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;


    Route::get('/test', function () {
        return response()->json(['message' => 'API is working!']);
    });

    Route::post('/patients', [PatientController::class, 'store']);
    Route::get('/patients', [PatientController::class, 'index']);
    Route::get('/patients/{id}', [PatientController::class, 'show']);
    Route::put('/patients/{id}', [PatientController::class, 'update']);
    Route::delete('/patients/{id}', [PatientController::class, 'destroy']);

