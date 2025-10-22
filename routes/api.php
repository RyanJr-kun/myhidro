<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PumpApiController;
use App\Http\Middleware\VerifyDeviceToken;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pump/status', [PumpApiController::class, 'getManualStatusForWeb']);
    Route::post('/pump/toggle', [PumpApiController::class, 'togglePumpStatus']);
});

Route::middleware(VerifyDeviceToken::class)->group(function () {
    Route::get('/arduino/get-desired-states', [PumpApiController::class, 'getDesiredStates']);
    Route::post('/arduino/log-action', [PumpApiController::class, 'logArduinoPumpAction']);
    Route::get('/arduino/schedules', [PumpApiController::class, 'getSchedulesForArduino']);
});
