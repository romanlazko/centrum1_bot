<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'telegram:Centrum1_bot'])->name('Centrum1_bot.')->group(function () {
    Route::get('/page', function(){
        return view('Centrum1_bot::page');
    })->name('page');
});