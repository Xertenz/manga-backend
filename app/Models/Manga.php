<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manga extends Model
{
    //
    protected $fillable = ['user_id', 'title', 'description', 'status'];

    // ليفهم لارافيل أن هذه الحقول تخزن كـ JSON وتدعم تعدد اللغات
    protected $casts = [
        'title' => 'array',
        'description' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class, 'manga_id')->orderBy('chapter_number', 'asc');
    }
}
