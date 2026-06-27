<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MangaResource;
use App\Models\Manga;
use Illuminate\Http\Request;

class MangaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mangas = Manga::with('user')->latest()->paginate(12);
        return MangaResource::collection($mangas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|array',
            'title.en' => 'required|array',
            'title.ar' => 'required|array',
            'description' => 'required|array',
            'description.en' => 'required|array',
            'description.ar' => 'required|array',
            'status' => 'required|string|in:ongoing|completed'
        ]);
        $manga = Manga::create([
            'user_id' => 1,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status']
        ]);

        return response()->json([
            'message' => 'manga created successfully',
            'data' => new MangaResource($manga),
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
