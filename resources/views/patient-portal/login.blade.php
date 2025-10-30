@extends('layouts.public')

@section('content')
<div class="mx-auto max-w-xl rounded-3xl bg-white p-8 shadow-xl ring-1 ring-slate-100">
    <h1 class="text-2xl font-bold text-emerald-700">Mon espace patient DermaBus+</h1>
    <p class="mt-2 text-sm text-slate-600">Retrouvez vos informations, rendez-vous de suivi et documents de sensibilisation.</p>

    <form action="{{ route('patient-portal.authenticate') }}" method="POST" class="mt-6 space-y-5">
        @csrf
        <div>
            <label for="reference_code" class="block text-sm font-medium text-slate-700">Référence DermaBus+</label>
            <input id="reference_code" name="reference_code" type="text" value="{{ old('reference_code', session('reference')) }}" required autofocus class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            @error('reference_code')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-slate-700">Numéro de téléphone</label>
            <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Ex. +22890xxxxxx">
            @error('phone')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="w-full rounded-lg bg-emerald-600 px-4 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Se connecter</button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Besoin d’aide ? Contactez l’équipe DermaBus+ au <strong>+228 92 00 00 00</strong>.
    </p>
</div>
@endsection
