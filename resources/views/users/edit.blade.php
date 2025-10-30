@extends('layouts.app')

@section('content')
<div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-emerald-700">Modifier le compte</h1>
            <p class="mt-1 text-sm text-slate-600">Mettez à jour les accès et informations du membre de l’équipe.</p>
        </div>
        <a href="{{ route('users.index') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Retour</a>
    </div>

    <form action="{{ route('users.update', $user) }}" method="POST" class="mt-8 space-y-6">
        @method('PUT')
        @include('users.partials.form')

        <div class="flex justify-end">
            <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Enregistrer</button>
        </div>
    </form>

    <form action="{{ route('users.destroy', $user) }}" method="POST" class="mt-6" onsubmit="return confirm('Supprimer définitivement ce compte ?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="rounded-lg border border-rose-200 px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50">Supprimer ce compte</button>
    </form>
</div>
@endsection
