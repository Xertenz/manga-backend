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
        // قراءة اللغة المرسلة من React عبر الـ Header (مثلاً ar أو en)
        // إذا لم ترسل React لغة، سنعتمد العربية كافتراضية
        $lang = $request->header('Accept-Language', 'en');
        return [
            'id' => $this->id,
            'title' => $this->title[$lang] ?? $this->title['en'],
            'description' => $this->description[$lang] ?? $this->description['en'],
            'status' => $this->status,
            'artist' => [
                'id' => $this->user->id,
                'name' => $this->user->name
            ],
            'chapters' => ChapterResource::collection($this->whenLoaded('chapters')),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
