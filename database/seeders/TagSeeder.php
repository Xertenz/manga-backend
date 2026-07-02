<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        // قائمة الأوسمة العالمية للهيكل الذكي
        $tagsData = [
            // 1. تصنيفات رئيسية (Genres)
            ['type' => 'genre', 'translations' => [
                'en' => ['name' => 'Action', 'slug' => 'action'],
                'ar' => ['name' => 'أكشن', 'slug' => 'أكشن']
            ]],
            ['type' => 'genre', 'translations' => [
                'en' => ['name' => 'Comedy', 'slug' => 'comedy'],
                'ar' => ['name' => 'كوميدي', 'slug' => 'كوميدي']
            ]],
            ['type' => 'genre', 'translations' => [
                'en' => ['name' => 'Drama', 'slug' => 'drama'],
                'ar' => ['name' => 'دراما', 'slug' => 'دراما']
            ]],
            ['type' => 'genre', 'translations' => [
                'en' => ['name' => 'Fantasy', 'slug' => 'fantasy'],
                'ar' => ['name' => 'خيال', 'slug' => 'خيال']
            ]],
            ['type' => 'genre', 'translations' => [
                'en' => ['name' => 'Isekai', 'slug' => 'isekai'],
                'ar' => ['name' => 'إيسيكاي', 'slug' => 'إيسيكاي']
            ]],

            // 2. موضوعات فرعية (Themes)
            ['type' => 'theme', 'translations' => [
                'en' => ['name' => 'Magic', 'slug' => 'magic'],
                'ar' => ['name' => 'سحر', 'slug' => 'سحر']
            ]],
            ['type' => 'theme', 'translations' => [
                'en' => ['name' => 'Monsters', 'slug' => 'monsters'],
                'ar' => ['name' => 'وحوش', 'slug' => 'وحوش']
            ]],
            ['type' => 'theme', 'translations' => [
                'en' => ['name' => 'Time Travel', 'slug' => 'time-travel'],
                'ar' => ['name' => 'سفر عبر الزمن', 'slug' => 'سفر-عبر-الزمن']
            ]],

            // 3. شكل العمل (Format)
            ['type' => 'format', 'translations' => [
                'en' => ['name' => 'Full Color', 'slug' => 'full-color'],
                'ar' => ['name' => 'ملون بالكامل', 'slug' => 'ملون-بالكامل']
            ]],
            ['type' => 'format', 'translations' => [
                'en' => ['name' => 'Long Strip', 'slug' => 'long-strip'],
                'ar' => ['name' => 'شريط طولي / ويبتون', 'slug' => 'شريط-طولي']
            ]],
        ];

        // إدخال البيانات في الجدولين الأساسي والتراجم بذكاء
        foreach ($tagsData as $data) {
            $tag = Tag::create(['type' => $data['type']]);

            foreach ($data['translations'] as $locale => $trans) {
                $tag->translations()->create([
                    'locale' => $locale,
                    'name'   => $trans['name'],
                    'slug'   => $trans['slug'],
                ]);
            }
        }
    }
}
