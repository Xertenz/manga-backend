<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MangaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $titles = [];
        $descriptions = [];

        foreach ($this->translations as $translation) {
            $titles[$translation->locale] = $translation->title;
            $descriptions[$translation->locale] = $translation->description;
        }

        return [
            'id' => $this->id,
            'title' => $titles,
            'description' => $descriptions,
            'status' => $this->status,
            'slug' => $this->slug,

            'cover_url' => $this->hasMedia('cover') ? $this->getFirstMediaUrl('cover', 'thumb') : null,

            'artist' => $this->artist ? [
                'id' => $this->artist->id,
                'name' => $this->artist->name
            ] : null,
            'chapters' => ChapterResource::collection($this->whenLoaded('chapters')),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
