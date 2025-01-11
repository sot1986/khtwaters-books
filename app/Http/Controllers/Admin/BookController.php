<?php

namespace App\Http\Controllers\Admin;

use App\Actions\SavePublicImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Book\StoreBookRequest;
use App\Http\Requests\Book\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;

class BookController extends Controller
{
    public function store(
        StoreBookRequest $request,
        SavePublicImage $savePublicImage
    ) {
        $data = $request->validated();

        $data['image'] = $savePublicImage->handle($request->file('file'));

        $book = Book::create([
            'image' => $data['image'],
            ...$request->only(['title', 'description', 'price']),
        ]);

        return back()->with('flash', [
            'notification' => [
                'type' => 'success',
                'message' => 'Book created successfully'
            ],
            'book' => BookResource::make($book)
        ]);
    }

    public function update(
        UpdateBookRequest $request,
        Book $book,
        SavePublicImage $savePublicImage
    ) {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            $data['image'] = $savePublicImage->handle($request->file('file'));
        }

        $book->update(collect($data)->except('file')->toArray());

        return back()->with('flash', [
            'notification' => [
                'type' => 'success',
                'message' => 'Book updated successfully'
            ],
            'book' => BookResource::make($book)
        ]);
    }

    public function destroy(
        Request $request,
        Book $book
    ) {
        $book->delete();

        return back()->with('flash', [
            'notification' => [
                'type' => 'success',
                'message' => 'Book deleted successfully'
            ]
        ]);
    }
}
