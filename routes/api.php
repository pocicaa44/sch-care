<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ReportController;

use Illuminate\Http\Request;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/v1/register', [AuthController::class, 'register']);
Route::post('/v1/login', [AuthController::class, 'login']);

// protected routes
Route::middleware('auth:sanctum')->group(function() {
    Route::post('/v1/logout', [ReportController::class, 'logout']);
    Route::get('/v1/reports', [ReportController::class, 'index']);
    Route::post('/v1/reports', [ReportController::class, 'store']);
    Route::get('/v1/reports/{id}', [ReportController::class, 'show']);
    Route::delete('/v1/reports/{id}', [ReportController::class, 'destroy']);
    Route::put('/v1/reports/{id}', [ReportController::class, 'update']);
});