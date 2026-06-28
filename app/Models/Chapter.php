<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Chapter extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['manga_id', 'chapter_number', 'title'];

    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class, 'manga_id');
    }

    // هنا نخبر المكتبة بتحويل الصور تلقائياً إلى صيغة WebP الخفيفة وسريعة التحميل
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('optimized')
            ->format('webp')
            ->quality(80)
            ->performOnCollections('pages');
    }
}
