<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('welcome');

Route::middleware(['auth'])->group(function () {
    Route::inertia('home', 'Home')->name('home');
    Route::inertia('news', 'news/Index')->name('news.index');
    Route::inertia('trainers', 'trainers/Index')->name('trainers.index');
    Route::inertia('trainers/session', 'trainers/Session')->name('trainers.session');
    Route::inertia('profile', 'Profile')->name('profile.index');

    Route::redirect('dashboard', '/home')->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';