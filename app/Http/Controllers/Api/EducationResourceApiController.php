<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EducationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class EducationResourceApiController extends Controller
{
    /**
     * Display a listing of education resources.
     */
    public function index(Request $request): ResourceCollection
    {
        $resources = EducationResource::query()
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')))
            ->when($request->boolean('published', false), fn ($query) => $query->where('is_published', true))
            ->orderByDesc('published_at')
            ->paginate($request->integer('per_page', 20));

        return JsonResource::collection($resources);
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $this->validatedData($request);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if (array_key_exists('is_published', $data)) {
            $data['is_published'] = (bool) $data['is_published'];
        }

        $resource = EducationResource::create($data);

        return response()->json($resource, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(EducationResource $resource): JsonResponse
    {
        return response()->json($resource);
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, EducationResource $resource): JsonResponse
    {
        $data = $this->validatedData($request, $resource->id);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if (array_key_exists('is_published', $data)) {
            $data['is_published'] = (bool) $data['is_published'];
        }

        $resource->update($data);

        return response()->json($resource);
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(EducationResource $resource): JsonResponse
    {
        $resource->delete();

        return response()->json(null, 204);
    }

    /**
     * Validate resource payload.
     */
    private function validatedData(Request $request, ?int $resourceId = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:education_resources,slug,' . $resourceId],
            'category' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'media_url' => ['nullable', 'url', 'max:255'],
            'language' => ['required', 'string', 'max:10'],
            'is_published' => ['sometimes', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);
    }
}
