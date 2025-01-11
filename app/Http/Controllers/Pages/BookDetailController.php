<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookDetailController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Request $request,
        Book $book
    ) {
        $book->load('authUsers');
        return Inertia::render('BookDetail/Page', [
            'book' => fn() => BookResource::make($book->load('creator')),
            'can' => fn() => [
                'addToFavorites' => $request->user()?->can('addToFavorites', $book),
                'removeFromFavorites' => $request->user()?->can('removeFromFavorites', $book)
            ]
        ]);
    }
}
