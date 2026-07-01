<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\App;

class Tag extends Model
{

    protected $fillable = ['type'];

    public function mangas(): BelongsToMany
    {
        return $this->belongsToMany(Manga::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(TagTranslation::class, 'tag_id');
    }

    public function translation(?string $locale = null): HasOne
    {
        $locale = $locale ?? App::getLocale();
        return $this->hasOne(TagTranslation::class, 'tag_id')->where('locale', $locale);
    }
}
