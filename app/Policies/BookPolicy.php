<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function addToFavorites(User $user, Book $book)
    {
        if ($user->isAdmin()) {
            return false;
        }

        if (!isset($book->is_favorite)) {
            return false;
        }

        return !$book->is_favorite;
    }

    public function removeFromFavorites(User $user, Book $book)
    {
        if (!isset($book->is_favorite)) {
            return false;
        }

        return $book->is_favorite;
    }
}
