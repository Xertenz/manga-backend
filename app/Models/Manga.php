<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Manga extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['user_id', 'status'];

    // ليفهم لارافيل أن هذه الحقول تخزن كـ JSON وتدعم تعدد اللغات
    protected $casts = [
        'title' => 'array',
        'description' => 'array'
    ];

    public function artist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class, 'manga_id')->orderBy('chapter_number', 'asc');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(MangaTranslation::class, 'manga_id');
    }

    public function translation(?string $locale = null)
    {
        $locale = $locale ?? App::getLocale();
        return $this->hasOne(MangaTranslation::class, 'manga_id')->where('locale', $locale);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->format('webp')
            ->width(300)
            ->height(450)
            ->quality(85)
            ->performOnCollections('cover');
    }
}
