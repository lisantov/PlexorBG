<?php

use App\Http\Controllers\News\CommentaryController as NewsCommentaryController;
use App\Http\Controllers\News\LikeController as NewsLikeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\Trainers\CommentaryController as TrainerCommentaryController;
use App\Http\Controllers\Trainers\LikeController as TrainerLikeController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home')->name('home');

Route::get('news', [NewsController::class, 'index'])->name('news.index');
Route::get('news/{news}', [NewsController::class, 'show'])->name('news.show');
Route::middleware(['auth'])->post('news/{news}/comments', [NewsCommentaryController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('news.comments.store');
Route::middleware(['auth'])->post('news/{news}/like', [NewsLikeController::class, 'toggle'])
    ->middleware('throttle:30,1')
    ->name('news.like.toggle');
Route::get('trainers', [TrainerController::class, 'index'])->name('trainers.index');
Route::get('trainers/{task}', [TrainerController::class, 'show'])->name('trainers.show');
Route::middleware(['auth'])->post('trainers/{task}/comments', [TrainerCommentaryController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('trainers.comments.store');
Route::middleware(['auth'])->post('trainers/{task}/like', [TrainerLikeController::class, 'toggle'])
    ->middleware('throttle:30,1')
    ->name('trainers.like.toggle');

Route::middleware(['auth'])->group(function () {
    Route::inertia('profile', 'Profile')->name('profile.index');

    Route::redirect('dashboard', '/')->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
