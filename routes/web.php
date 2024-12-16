<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('/images', ImageController::class);
    Route::get('/my-images', [ImageController::class, 'myImages'])->name('images.my-images');
    Route::get('/images', [ImageController::class, 'index'])->name('images.index');
    Route::post('/images/{image}/bookmark', [ImageController::class, 'bookmark'])->name('images.bookmark');
    Route::get('/my-bookmarks', [ImageController::class, 'myBookmarks'])->name('images.myBookmarks');
    Route::get('/images/{id}', [ImageController::class, 'show'])->name('images.show');
    Route::post('/images/{id}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{id}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');


});

require __DIR__.'/auth.php';
