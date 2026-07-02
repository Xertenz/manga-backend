<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MangaResource;
use App\Models\Manga;
use App\Models\MangaTranslation;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MangaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mangas = Manga::with(['user', 'translations', 'tags.translations'])->latest()->paginate(12);
        return MangaResource::collection($mangas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            //'user_id'        => 'required|exists:users,id',
            'status'         => 'required|string|in:ongoing,completed,hiatus',

            'lang'           => 'required|in:en,ar',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string|max:1000',

            'cover'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',

            'tags'           => 'required|array',
            'tags.*'         => 'exists:tags,id'
        ]);

        $slug = Str::slug($validated['title']) ?: 'manga-' . Str::random(8);

        $manga = DB::transaction(function () use ($request, $validated, $slug) {
            $manga = Manga::create([
                'user_id' => $request->user()->id,
                'status'  => $validated['status'],
            ]);

            $manga->translations()->create([
                'locale'      => $validated['lang'],
                'title'       => $validated['title'],
                'description' => $validated['description'] ?? null,
                'slug'        => $slug,
            ]);

            if (!empty($validated['tags'])) {
                $manga->tags()->sync($validated['tags']);
            }

            return $manga;
        });

        if ($request->hasFile('cover')) {
            $manga->addMediaFromRequest('cover')
                ->usingFileName('cover-' . Str::random(8) . '.' . $request->file('cover')->getClientOriginalExtension())
                ->toMediaCollection('cover');
        }

        return response()->json([
            'message' => 'Manga created successfully',
            'data'    => new MangaResource($manga->load('translations', 'tags.translations')),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $manga = Manga::with(['user', 'chapters'])->findOrFail($id);
        return new MangaResource($manga);
    }

    public function getAvailableTags()
    {
        $tags = Tag::with('translations')->get();
        return response()->json([
            'data' => $tags->map(function ($tag) {
                $names = [];
                foreach ($tag->translations as $translation) {
                    $names[$translation->locale] = $translation->name;
                }
                return [
                    'id' => $tag->id,
                    'type' => $tag->type,
                    'name' => $names,
                ];
            })
        ]);
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
