<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Post routes
    Route::resource('/posts', PostController::class);
    Route::get('/my-posts', [PostController::class, 'myPosts'])->name('posts.my-posts');
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::post('/posts/{post}/bookmark', [PostController::class, 'bookmark'])->name('posts.bookmark');
    Route::get('/my-bookmarks', [PostController::class, 'myBookmarks'])->name('posts.myBookmarks');
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

    // Image handling within posts
    Route::post('/posts/{post}/images', [PostController::class, 'addImage'])->name('posts.addImage');
    Route::delete('/posts/{post}/images/{image}', [PostController::class, 'deleteImage'])->name('posts.deleteImage');

    // Comment routes (linked to posts)
    Route::post('/posts/{id}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/comments/{id}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

require __DIR__.'/auth.php';
