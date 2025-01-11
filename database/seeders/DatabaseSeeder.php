<?php

namespace Database\Seeders;

use App\Enum\UserRole;
use App\Models\Book;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->createOrFirst([
            'email' => 'matteo-admin@email.com',
        ], [
            'role' => UserRole::ADMIN,
            'name' => 'Matteo',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $users = User::factory(10)->create();

        $users->each(function (User $user) {
            $user->books()->saveMany(
                \App\Models\Book::factory()->count(5)->make()
            );
        });

        $users->each(function (User $user) {
            $favoriteBooks = \App\Models\Book::inRandomOrder()->limit(3)->get();

            $favoriteBooks->each(fn(Book $book) => $user->favoriteBooks()->attach($book));
        });
    }
}
