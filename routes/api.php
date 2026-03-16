<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatApiController;
use App\Http\Controllers\Api\BotApiController;
use App\Http\Controllers\Api\ProfileApiController;
use Illuminate\Support\Facades\Route;

// ─── Rutas Públicas (sin auth) ───
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

// ─── Rutas Protegidas (requieren token) ───
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Chat
    Route::get('/contacts', [ChatApiController::class, 'contacts']);
    Route::get('/messages/{contactId}', [ChatApiController::class, 'messages']);
    Route::post('/messages/send', [ChatApiController::class, 'send']);

    // Bot
    Route::post('/bot/send', [BotApiController::class, 'send']);

    // Profile
    Route::get('/profile', [ProfileApiController::class, 'show']);
    Route::post('/profile/update', [ProfileApiController::class, 'update']);
});
