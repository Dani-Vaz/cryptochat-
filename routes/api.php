<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatApiController;
use App\Http\Controllers\Api\BotApiController;
use App\Http\Controllers\Api\ProfileApiController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/contacts', [ChatApiController::class, 'contacts']);
    Route::get('/messages/{contactId}', [ChatApiController::class, 'messages']);
    Route::post('/messages/send', [ChatApiController::class, 'send']);
    Route::post('/messages/send-media', [ChatApiController::class, 'sendMedia']);
    Route::delete('/messages/{messageId}', [ChatApiController::class, 'destroy']);
    Route::post('/bot/send', [BotApiController::class, 'send']);
    Route::get('/profile', [ProfileApiController::class, 'show']);
    Route::post('/profile/update', [ProfileApiController::class, 'update']);

    // Ruta de prueba (opcional)
    Route::get('/ping', function () {
        return response()->json(['message' => 'pong']);
    });
}); // <-- Este cierre es importante