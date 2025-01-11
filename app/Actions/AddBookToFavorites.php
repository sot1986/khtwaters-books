<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Book;
use App\Models\User;

class AddBookToFavorites
{
    public function handle(User $user, Book $book): void
    {
        $user->favoriteBooks()->attach($book);
    }
}
