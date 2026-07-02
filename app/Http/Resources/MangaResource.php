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
        $slugs = [];

        foreach ($this->translations as $translation) {
            $titles[$translation->locale] = $translation->title;
            $descriptions[$translation->locale] = $translation->description ?? null;
            $slugs[$translation->locale] = $translation->slug;
        }

        return [
            'id' => $this->id,
            'title' => $titles,
            'description' => $descriptions,
            'status' => $this->status,
            'slug' => $slugs,

            'cover_url' => $this->hasMedia('cover') ? $this->getFirstMediaUrl('cover', 'thumb') : null,

            'tags' => $this->tags->map(function ($tag) {
                $tagNames = [];
                foreach ($tag->translations as $translation) {
                    $tagNames[$translation->locale] = $translation->name;
                }
                return [
                    'id' => $tag->id,
                    'type' => $tag->type,
                    'name' => $tagNames
                ];
            }),

            'artist' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name
            ] : null,
            'chapters' => ChapterResource::collection($this->whenLoaded('chapters')),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
