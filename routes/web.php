<?php

use App\Http\Controllers\News\CommentaryController as NewsCommentaryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\TrainerController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home')->name('home');

Route::get('news', [NewsController::class, 'index'])->name('news.index');
Route::get('news/{news}', [NewsController::class, 'show'])->name('news.show');
Route::middleware(['auth'])->post('news/{news}/comments', [NewsCommentaryController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('news.comments.store');
Route::get('trainers', [TrainerController::class, 'index'])->name('trainers.index');
Route::get('trainers/{task}', [TrainerController::class, 'show'])->name('trainers.show');

Route::middleware(['auth'])->group(function () {
    Route::inertia('profile', 'Profile')->name('profile.index');

    Route::redirect('dashboard', '/')->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
