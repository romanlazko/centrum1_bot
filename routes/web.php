<?php

use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TelegramChatController;
use App\Livewire\Telegram\Advertisement;
use App\Livewire\Telegram\AdvertisementLogs;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});
Route::get('/dashboard', function () {
    return redirect()->route('pulse');
})->name('dashboard');


Route::controller(TelegramChatController::class)->middleware(['auth', 'verified'])->prefix('chat')->group(function () {
    Route::get('/', 'index')->name('chat.index');
    Route::get('/{telegram_chat}', 'show')->name('chat.show');
    Route::post('/send_message/{telegram_chat}', 'send_message')->name('chat.send_message');
});

Route::middleware(['auth', 'verified'])->prefix('advertisement')->group(function () {
    Route::get('/', Advertisement::class)->name('advertisement.index');
    Route::get('/{advertisement}', AdvertisementLogs::class)->name('advertisement.logs');
    // Route::post('/send_message/{advertisement}', 'dispatch')->name('advertisement.dispatch');
}); 

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
