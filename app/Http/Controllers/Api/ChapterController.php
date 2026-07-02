<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChapterResource;
use App\Models\Chapter;
use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'chapter_number' => 'required|numeric|min:0',

            'lang'  => 'required|in:en,ar',
            'title' => 'nullable|string',

            'pages' => 'required|array|min:1',
            'pages.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096'
        ]);

        // التحقق من أن الفنان الحالي هو صاحب المانغا (حماية أمنية 🔒)
        $manga = Manga::findOrFail($validated['manga_id']);
        if ($manga->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized. You do not own this manga.'], 403);
        }

        $slug = 'ch-' . $validated['chapter_number'];

        $chapter = DB::transaction(function () use ($validated, $slug) {
            $chapter = Chapter::create([
                'manga_id' => $validated['manga_id'],
                'chapter_number' => $validated['chapter_number'],
            ]);

            $chapter->translations()->create([
                'locale' => $validated['lang'],
                'title' => $validated['title'] ?? null,
                'slug' => $slug,
            ]);

            return $chapter;
        });

        if ($request->hasFile('pages')) {
            foreach ($request->file('pages') as $index => $pageFile) {
                // تسمية الصور بناءً على ترتيبها (page-001, page-002...) لضمان الترتيب الأبجدي عند الجلب
                $pageName = 'page-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);

                $chapter->addMedia($pageFile)
                    ->usingFileName($pageName . '.' . $pageFile->getClientOriginalExtension())
                    ->toMediaCollection('pages');
            }
        }

        return response()->json([
            'message' => 'Chapter published successfully with ' . count($validated['pages']) . ' pages!',
            'data' => new ChapterResource($chapter->load(['translations', 'manga.user']))
        ], 201);


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
