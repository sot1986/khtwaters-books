<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Dashboard/Page', [
            'stats' => $request->user()->isAdmin() ? $this->getAdminStats() : $this->getUserStats($request->user()),
        ]);
    }

    protected function getAdminStats()
    {
        return [
            ['label' => 'Registered users', 'value' => User::count(), 'link' => route('admin.all-users')],
            ['label' => 'Books in the library', 'value' => Book::count(), 'link' => route('admin.all-books')],
        ];
    }

    protected function getUserStats(User $user)
    {
        return [
            ['label' => 'Favorite books', 'value' => $user->favoriteBooks()->count(), 'link' => route('books.favorites')],
        ];
    }
}
