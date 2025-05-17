<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return redirect()->route('posts.index');
})->middleware(middleware: ['auth', 'verified'])->name('dashboard');

Route::resource('authors', AuthorController::class)->middleware(middleware: ['auth', 'verified']);

Route::resource('posts', PostController::class)->middleware(middleware: ['auth', 'verified']);
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
