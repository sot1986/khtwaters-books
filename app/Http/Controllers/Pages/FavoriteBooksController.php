<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Resources\LightBookResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FavoriteBooksController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Request $request
    ) {
        return Inertia::render('FavoriteBooks/Page', [
            'books' => BookResource::collection(
                $request->user()->favoriteBooks()
                    ->with('creator')
                    ->get()
            ),
        ]);
    }
}
