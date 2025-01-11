<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LightBookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->whenNotNull($this->image_url),
            'price' => (float) $this->price,
            'is_favorite' => $this->whenNotNull($this->is_favorite),
            'users_count' => $this->whenNotNull($this->users_count),
        ];
    }
}
