<?php

namespace App\Http\Controllers;

use App\Models\EducationResource;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EducationResourceController extends Controller
{
    /**
     * Display a listing of the resources.
     */
    public function index(Request $request): View
    {
        $resources = EducationResource::query()
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')))
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();

        return view('resources.index', compact('resources'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('resources.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if (array_key_exists('is_published', $data)) {
            $data['is_published'] = (bool) $data['is_published'];
        }

        $resource = EducationResource::create($data);

        return redirect()
            ->route('resources.show', $resource)
            ->with('status', 'Ressource publiée.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EducationResource $resource): View
    {
        return view('resources.show', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EducationResource $resource): View
    {
        return view('resources.edit', compact('resource'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EducationResource $resource): RedirectResponse
    {
        $data = $this->validatedData($request, $resource->id);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if (array_key_exists('is_published', $data)) {
            $data['is_published'] = (bool) $data['is_published'];
        }

        $resource->update($data);

        return redirect()
            ->route('resources.show', $resource)
            ->with('status', 'Ressource mise à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EducationResource $resource): RedirectResponse
    {
        $resource->delete();

        return redirect()
            ->route('resources.index')
            ->with('status', 'Ressource supprimée.');
    }

    /**
     * Validate resource data.
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
