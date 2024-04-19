<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'telegram:strachovanie1_bot'])->name('strachovanie1_bot.')->group(function () {
    Route::get('/page', function(){
        return view('strachovanie1_bot::page');
    })->name('page');
});