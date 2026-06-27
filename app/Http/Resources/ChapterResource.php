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
        return [
            'id' => $this->id,
            'chapter_number' => $this->chapter_number,
            'title' => $this->title,

            'pages' => $this->getMedia('pages')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'file_name' => $media->file_name,
                    'order' => $media->order,
                    'url' => $media->getUrl('optimized')
                ];
            }),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
