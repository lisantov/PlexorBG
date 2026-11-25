<?php

use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\TrainerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/news/create')->name('index');

    Route::get('news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('news', [NewsController::class, 'store'])->name('news.store');
    Route::get('news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('news/{news}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');

    Route::get('trainers/create', [TrainerController::class, 'create'])->name('trainers.create');
    Route::post('trainers', [TrainerController::class, 'store'])->name('trainers.store');
    Route::get('trainers/{task}/edit', [TrainerController::class, 'edit'])->name('trainers.edit');
    Route::put('trainers/{task}', [TrainerController::class, 'update'])->name('trainers.update');
    Route::delete('trainers/{task}', [TrainerController::class, 'destroy'])->name('trainers.destroy');
});
