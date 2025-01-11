<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Book;
use App\Models\User;

class RemoveBookFromFavorites
{
    public function handle(Book $book, User $user): void
    {
        $user->favoriteBooks()->detach($book);
    }
}
