<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Manga;
use App\Models\Chapter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. إنشاء مستخدم تجريبي (رسام / Artist)
        $artist = User::create([
            'name' => 'Eiichiro Oda',
            'email' => 'oda@example.com',
            'password' => Hash::make('password123'),
            'role' => 'artist',
        ]);

        // 2. إنشاء المانغا الأولى (مع دعم اللغات الإنجليزية والعربية في الـ JSON)
        $manga1 = Manga::create([
            'user_id' => $artist->id,
            'status' => 'ongoing',
        ]);
        $manga1->translations()->create([
            'locale' => 'en',
            'title' =>  'One Piece',
            'description' =>  'Monkey D. Luffy refuses to let anyone or anything stand in the way of his quest to become the king of all pirates.',
            'slug' => 'one-piece'
        ]);
        $manga1->translations()->create([
            'locale' => 'ar',
            'title' =>  'ون بيس',
            'description' =>  'مونكي دي لوفي يرفض السماح لأي شخص أو شيء بالوقوف في طريق سعيه ليصبح ملك القراصنة.',
            'slug' => 'ون-بيس'
        ]);

        /*
        // إضافة فصول تجريبية للمانغا الأولى
        Chapter::create([
            'manga_id' => $manga1->id,
            'user_id' => $artist->id,
            'chapter_number' => 1.0,
            'title' => 'Romance Dawn',
        ]);

        Chapter::create([
            'manga_id' => $manga1->id,
            'user_id' => $artist->id,
            'chapter_number' => 2.0,
            'title' => 'They Call Him Luffy',
        ]);

        // 3. إنشاء مانغا ثانية للتنوع في القائمة
        $manga2 = Manga::create([
            'user_id' => $artist->id,
            'title' => [
                'en' => 'Naruto',
                'ar' => 'ناروتو'
            ],
            'description' => [
                'en' => 'Naruto Uzumaki, a mischievous adolescent ninja, struggles as he searches for recognition and dreams of becoming the Hokage.',
                'ar' => 'ناروتو أوزوماكي، نينجا مراهق ومشاغب، يكافح ويبحث عن الاعتراف به ويحلم بأن يصبح الهوكاجي.'
            ],
            'status' => 'completed',
            'slug' => 'naruto'
        ]);

        Chapter::create([
            'manga_id' => $manga2->id,
            'user_id' => $artist->id,
            'chapter_number' => 1.0,
            'title' => 'Uzamaki Naruto!!',
        ]);
        */
    }
}
