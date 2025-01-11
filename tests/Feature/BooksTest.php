<?php

declare(strict_types=1);

use App\Models\Book;
use App\Models\User;

describe('test books functionalities', function () {

    beforeEach(function () {
        $user = User::factory()->create();

        test()->actingAs($user);

        Book::factory(10)->create([
            'created_by' => $user->id,
        ]);
    });

    it('book can be searched by title', function () {
        $targetBook = Book::factory()->create([
            'title' => 'My focus SpecialBook',
        ]);

        $books = Book::search('focus')->get();

        expect($books->count())->toBe(1)
            ->and($books->first()->is($targetBook))->toBeTrue()
            ->and($books->first()->title)->toBe('My focus SpecialBook');
    })->skip(message: "MySQL cannot save index wihtout commiting the transaction");
});
