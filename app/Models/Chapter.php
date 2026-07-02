<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Chapter extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['manga_id', 'chapter_number'];

    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class, 'manga_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ChapterTranslation::class, 'chapter_id');
    }

    public function translation(?string $locale = null)
    {
        $locale = $locale ?? App::getLocale();
        return $this->hasOne(ChapterTranslation::class, 'chapter_id')->where('locale', $locale);
    }

    // هنا نخبر المكتبة بتحويل الصور تلقائياً إلى صيغة WebP الخفيفة وسريعة التحميل
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('optimized')
            ->format('webp')
            ->quality(80)
            ->nonQueued()
            ->keepOriginalImageFormat()
            ->performOnCollections('pages');
    }
}
