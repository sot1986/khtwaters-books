<?php

namespace App\Http\Controllers\Pages\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Resources\LightBookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AllBooksController extends Controller
{
    public function  __invoke(Request $request)
    {
        return Inertia::render('Admin/Books/Page', [
            'books' => fn() => BookResource::collection(
                Book::search($request->query('search', ''))
                    ->query(fn($query) => $query->withCount('users'))
                    ->orderBy(
                        ...$this->resolveOrdering(
                            $request->query('sortColumn', 'id'),
                            $request->query('sortDirection', 'desc')
                        )
                    )
                    ->simplePaginate(12)
            ),
        ]);
    }

    protected function resolveOrdering(string $sortedBy, string $sortDirection): array
    {
        $direction = $sortDirection === 'asc' ? 'asc' : 'desc';

        return match ($sortedBy) {
            'title' => ['title', $direction],
            'price' => ['price', $direction],
            'date' => ['created_at', $direction],
            'likes' => ['users_count', $direction],
            default => ['id', $direction],
        };
    }
}
