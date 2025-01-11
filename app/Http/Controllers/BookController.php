<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }

    public function addToFavorites(
        Request $request,
        Book $book,
        \App\Actions\AddBookToFavorites $addBookToFavorites
    ) {
        Gate::authorize('addToFavorites', $book->load('authUsers'));

        $addBookToFavorites->handle(user: $request->user(), book: $book);

        return back();
    }

    public function removeFromFavorites(
        Request $request,
        Book $book,
        \App\Actions\RemoveBookFromFavorites $removeBookFromFavorites
    ) {
        Gate::authorize('removeFromFavorites', $book->load('authUsers'));

        $removeBookFromFavorites->handle(user: $request->user(), book: $book);

        return back();
    }
}
