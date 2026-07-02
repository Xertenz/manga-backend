<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChapterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $titles = [];
        foreach ($this->translations as $translation) {
            $titles[$translation->locale] = $translation->title ? $translation->title : null;
        }
        return [
            'id' => $this->id,
            'chapter_number' => $this->chapter_number,
            'title' => $titles,

            'uploader' => $this->manga && $this->manga->user ? [
                'id' => $this->manga->user->id,
                'name' => $this->manga->user->name,
            ] : null,

            'pages' => $this->getMedia('pages')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'name' => $media->name,
                    'file_name' => $media->file_name,
                    'order' => $media->order_column,
                    'url' => $media->getUrl('optimized'),
                    'fallback_url' => $media->getUrl(),
                ];
            }),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
