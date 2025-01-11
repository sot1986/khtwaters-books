<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\Pages\BookDetailController;
use App\Http\Controllers\Pages\WelcomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', WelcomeController::class)->name('welcome');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/{book}', BookDetailController::class)->name('books.show');
    Route::post('/{book}/addFavorite', [BookController::class, 'addToFavorites'])->name('books.addToFavorites');
    Route::delete('/{book}/removeFavorite', [BookController::class, 'removeFromFavorites'])->name('books.removeFromFavorites');
    Route::get('/libri-preferiti', \App\Http\Controllers\Pages\FavoriteBooksController::class)->name('books.favorites');
});

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('/admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');

        Route::get('/users', App\Http\Controllers\Pages\Admin\AllUsersController::class)->name('all-users');
        Route::get('/books', App\Http\Controllers\Pages\Admin\AllBooksController::class)->name('all-books');

        Route::apiResource('/users', App\Http\Controllers\Admin\UserController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('/books', App\Http\Controllers\Admin\BookController::class)->only(['store', 'update', 'destroy']);
    });

require __DIR__ . '/auth.php';
