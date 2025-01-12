<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Attributes\SearchUsingPrefix;
use Laravel\Scout\Searchable;

/**
 * @property string $title
 * @property string $description
 * @property string $price
 * @property int $created_by
 * @property ?string $image_url
 * @property bool $is_favorite
 * @property ?User $creator
 * @property ?\Illuminate\Support\Collection<int, User> $authUsers
 */
class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;
    use Searchable;

    protected $guarded = [];

    // ------------------------------------------------------------ //
    // --- Attributes --------------------------------------------- //
    // ------------------------------------------------------------ //

    protected function casts()
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    protected function isFavorite(): Attribute
    {
        return Attribute::get(
            function () {
                if ($this->relationLoaded('authUsers')) {
                    return $this->authUsers->isNotEmpty();
                }

                if ($this->relationLoaded('favorite')) {
                    return !!$this->favorite;
                }

                return null;
            }
        );
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(
            function () {
                return asset('storage/' . ($this->image ?? config('filesystems.dummy_image')));
            }
        );
    }

    // ------------------------------------------------------------ //
    // --- Scopes ------------------------------------------------- //
    // ------------------------------------------------------------ //

    protected function scopeCard($query)
    {
        $query->select('id', 'title', 'price', 'image');
    }

    // ------------------------------------------------------------ //
    // --- Relations ---------------------------------------------- //
    // ------------------------------------------------------------ //

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->using(BookUser::class)
            ->as('favorite');
    }

    public function authUsers()
    {
        return $this->users()->where('users.id', auth()->guard()->id());
    }

    // ------------------------------------------------------------ //
    // --- Searchable --------------------------------------------- //
    // ------------------------------------------------------------ //

    #[SearchUsingPrefix(['id'])]
    #[SearchUsingFullText(['title', 'description'])]
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }

    // ------------------------------------------------------------ //
    // --- Setup -------------------------------------------------- //
    // ------------------------------------------------------------ //

    protected static function booted()
    {
        static::creating(function (Book $book) {
            if (auth()->guard()?->check()) {
                $book->created_by = auth()->guard()->id();
            }
        });

        static::updated(function (Book $book) {
            if ($book->wasChanged('image')) {
                $oldImage = $book->getOriginal('image');
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        });

        static::deleted(function (Book $book) {
            if ($book->image) {
                Storage::disk('public')->delete($book->image);
            }
        });
    }
}
