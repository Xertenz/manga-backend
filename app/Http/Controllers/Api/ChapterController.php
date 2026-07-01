<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChapterResource;
use App\Models\Chapter;
use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'manga_id' => 'required|exists:mangas,id',
            'chapter_number' => 'required|numeric',
            'title' => 'nullable|string',
            'pages' => 'required|array',
            'pages.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096'
        ]);
        // مؤقتاً: التأكد من أن المانغا تخص الرسام الحالي (سنقوم بتحسينها عند إضافة نظام الحماية)
        $manga = Manga::findOrFail($validated['manga_id']);

        $chapter = Chapter::create([
            'manga_id' => $validated['manga_id'],
            'chapter_number' => $validated['chapter_number'],
            'title' => $validated['title'] ?? null,
            'user_id' => $request->user()->id,
        ]);

        /*
        if ($request->hasFile('pages')) {
            foreach ($request->file('pages') as $file) {
                $chapter->addMedia($file)->toMediaCollection('pages');
            }
        }
        */

        if ($request->hasFile('pages')) {
            $pageNumber = 1;
            foreach ($request->file('pages') as $file) {
                $extension = $file->getClientOriginalExtension();
                $randomHash = Str::random(8);
                $cleanFileName = 'page-' . $pageNumber . '-' . $randomHash . '.' . $extension;
                $chapter->addMedia($file)
                    ->usingName('Page ' . $pageNumber) // الاسم المعروض في قاعدة البيانات (Human-readable)
                    ->usingFileName($cleanFileName)     // اسم الملف الحقيقي المخزن على القرص الصلب (Sanitized)
                    ->toMediaCollection('pages');
                $pageNumber++;
            }
        }

        return response()->json([
            'message' => 'Chapter and pages uploaded successfully.',
            'data' => new ChapterResource($chapter),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Chapter $chapter)
    {
        //$chapter = Chapter::findOrFail($id);
        //return new ChapterResource($chapter);

        $chapter->load('media');

        $allChapters = Chapter::where('manga_id', $chapter->manga_id)
            ->orderBy('chapter_number', 'asc')
            ->get(['id', 'chapter_number']);

        return (new ChapterResource($chapter))->additional(['all_chapters' => $allChapters]);

        /*
        return response()->json([
            'chapter' => $chapter,
            'all_chapters' => $allChapters
        ]);
        */
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
