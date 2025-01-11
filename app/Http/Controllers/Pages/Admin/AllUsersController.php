<?php

namespace App\Http\Controllers\Pages\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminUserResource;
use App\Http\Resources\LightBookResource;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AllUsersController extends Controller
{
    public function __invoke(Request $request)
    {
        return Inertia::render('Admin/Users/Page', [
            'users' => fn() => AdminUserResource::collection(
                User::search($request->query('search', ''))
                    ->orderBy(...$this->resolveOrdering(
                        $request->query('sortColumn', 'id'),
                        $request->query('sortDirection', 'desc')
                    ))
                    ->simplePaginate(12)
            ),
            'books' => Inertia::lazy(fn() => LightBookResource::collection(
                $request->query('focusId', '0') === '0'
                    ? collect()
                    : Book::query()
                    ->whereHas('users', fn($query) => $query->where('user_id', $request->query('focusId', '0')))
                    ->get()
            ))
        ]);
    }

    protected function resolveOrdering(string $sortedBy, string $sortDirection): array
    {
        $direction = $sortDirection === 'asc' ? 'asc' : 'desc';

        return match ($sortedBy) {
            'name' => ['name', $direction],
            'email' => ['email', $direction],
            default => ['id', $direction],
        };
    }
}
