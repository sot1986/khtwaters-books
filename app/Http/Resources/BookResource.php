<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'description' => $this->description,
            'price' => (float) $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'image' => $this->whenNotNull($this->image_url),
            'is_favorite' => $this->whenNotNull($this->is_favorite),
            'users_count' => $this->whenNotNull($this->users_count),
            'creator' => LightUserResource::make($this->whenLoaded('creator')),
            'users' => LightUserResource::collection($this->whenLoaded('users')),
        ];
    }
}
