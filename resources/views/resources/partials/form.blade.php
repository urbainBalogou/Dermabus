@php $resourceModel = $resource ?? null; @endphp

@csrf
@if(isset($resourceModel))
    @method('PUT')
@endif

<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="text-sm font-medium text-slate-600" for="title">Titre *</label>
        <input type="text" name="title" id="title" value="{{ old('title', $resourceModel->title ?? '') }}" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="category">Catégorie</label>
        <input type="text" name="category" id="category" value="{{ old('category', $resourceModel->category ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="language">Langue *</label>
        <input type="text" name="language" id="language" value="{{ old('language', $resourceModel->language ?? 'fr') }}" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('language') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="md:col-span-2">
        <label class="text-sm font-medium text-slate-600" for="summary">Résumé</label>
        <textarea name="summary" id="summary" rows="3" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">{{ old('summary', $resourceModel->summary ?? '') }}</textarea>
        @error('summary') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="md:col-span-2">
        <label class="text-sm font-medium text-slate-600" for="content">Contenu *</label>
        <textarea name="content" id="content" rows="6" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">{{ old('content', $resourceModel->content ?? '') }}</textarea>
        @error('content') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="media_url">Lien média</label>
        <input type="url" name="media_url" id="media_url" value="{{ old('media_url', $resourceModel->media_url ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('media_url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="slug">Slug (optionnel)</label>
        <input type="text" name="slug" id="slug" value="{{ old('slug', $resourceModel->slug ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="is_published">Statut</label>
        <select name="is_published" id="is_published" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <option value="0" @selected(old('is_published', (int) ($resourceModel->is_published ?? 0)) === 0)>Brouillon</option>
            <option value="1" @selected(old('is_published', (int) ($resourceModel->is_published ?? 0)) === 1)>Publié</option>
        </select>
        @error('is_published') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="published_at">Date de publication</label>
        <input type="date" name="published_at" id="published_at" value="{{ old('published_at', $resourceModel?->published_at?->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('published_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-8 flex items-center justify-end gap-3">
    <a href="{{ route('resources.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Annuler</a>
    <button type="submit" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-500">Enregistrer</button>
</div>
