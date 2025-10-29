@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-800">Modifier {{ $resource->title }}</h1>
        <p class="mt-1 text-sm text-slate-500">Actualisez les contenus pédagogiques disponibles pour le DermaBus+.</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow">
        <form method="POST" action="{{ route('resources.update', $resource) }}" class="space-y-6">
            @include('resources.partials.form', ['resource' => $resource])
        </form>
    </div>
@endsection
