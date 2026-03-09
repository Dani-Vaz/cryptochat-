<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return redirect('/chat');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/messages/{contactId}', [ChatController::class, 'getMessages'])->name('chat.messages');

    Route::get('/chatbot', [ChatBotController::class, 'index'])->name('chatbot.index');
    Route::post('/chatbot/send', [ChatBotController::class, 'sendMessage'])->name('chatbot.send');
    Route::post('/chatbot/clear', [ChatBotController::class, 'clear'])->name('chatbot.clear');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';