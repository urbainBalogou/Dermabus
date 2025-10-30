@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800">Ressources de sensibilisation</h1>
            <p class="text-sm text-slate-500">Supports pédagogiques pour les agents communautaires et les bénéficiaires.</p>
        </div>
        <a href="{{ route('resources.create') }}" class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-500">Nouvelle ressource</a>
    </div>

    <form method="GET" class="mb-6 max-w-xs">
        <label class="text-sm font-medium text-slate-600" for="category">Catégorie</label>
        <input type="text" name="category" id="category" value="{{ request('category') }}" placeholder="ex: Prévention" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
    </form>

    <div class="grid gap-6 md:grid-cols-2">
        @forelse ($resources as $resource)
            <article class="rounded-xl border border-slate-200 bg-white p-6 shadow">
                <div class="flex items-center justify-between text-xs uppercase tracking-wide text-slate-500">
                    <span>{{ $resource->category ?? 'Sensibilisation' }}</span>
                    <span>{{ optional($resource->published_at)->format('d/m/Y') }}</span>
                </div>
                <h2 class="mt-3 text-xl font-semibold text-slate-800">{{ $resource->title }}</h2>
                <p class="mt-3 h-20 overflow-hidden text-ellipsis text-sm text-slate-600">{{ $resource->summary ?? Str::limit(strip_tags($resource->content), 160) }}</p>
                <div class="mt-4 flex items-center justify-between">
                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-600">{{ $resource->is_published ? 'Publié' : 'Brouillon' }}</span>
                    <a href="{{ route('resources.show', $resource) }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-500">Consulter</a>
                </div>
            </article>
        @empty
            <p class="text-sm text-slate-500">Aucune ressource enregistrée pour le moment.</p>
        @endforelse
    </div>

    <div class="mt-6">{{ $resources->links() }}</div>
@endsection
