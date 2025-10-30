@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800">{{ $resource->title }}</h1>
            <p class="text-sm text-slate-500">{{ $resource->category ?? 'Sensibilisation' }} · {{ $resource->language }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('resources.edit', $resource) }}" class="inline-flex items-center rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Modifier</a>
            <form action="{{ route('resources.destroy', $resource) }}" method="POST" onsubmit="return confirm('Supprimer cette ressource ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">Supprimer</button>
            </form>
        </div>
    </div>

    <article class="rounded-xl border border-slate-200 bg-white p-6 shadow">
        <div class="flex flex-wrap items-center justify-between text-xs uppercase tracking-wide text-slate-500">
            <span>{{ $resource->is_published ? 'Publié' : 'Brouillon' }}</span>
            <span>{{ optional($resource->published_at)->format('d/m/Y') }}</span>
            @if ($resource->media_url)
                <a href="{{ $resource->media_url }}" target="_blank" class="text-emerald-600 hover:text-emerald-500">Ouvrir le média</a>
            @endif
        </div>
        <div class="prose mt-6 max-w-none prose-headings:text-slate-800 prose-p:text-slate-700">
            {!! nl2br(e($resource->content)) !!}
        </div>
    </article>
@endsection
