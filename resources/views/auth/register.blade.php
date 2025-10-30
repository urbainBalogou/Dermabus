@extends('layouts.public')

@section('content')
<div class="mx-auto max-w-xl rounded-3xl bg-white p-8 shadow-xl ring-1 ring-emerald-100">
    <h1 class="text-2xl font-bold text-emerald-700">Créer le compte administrateur</h1>
    <p class="mt-2 text-sm text-slate-600">Ce formulaire apparaît uniquement lors de la première installation de DermaBus+. Conservez précieusement les identifiants créés.</p>

    <form action="{{ route('register') }}" method="POST" class="mt-6 space-y-5">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700">Nom complet</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            @error('name')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700">E-mail professionnel</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            @error('email')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-slate-700">Téléphone</label>
            <input id="phone" name="phone" type="text" value="{{ old('phone') }}" autocomplete="tel" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            @error('phone')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700">Mot de passe</label>
            <input id="password" name="password" type="password" required autocomplete="new-password" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            @error('password')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirmation</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
        </div>
        <button type="submit" class="w-full rounded-lg bg-emerald-600 px-4 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Créer l’accès</button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Déjà configuré ? <a href="{{ route('login') }}" class="font-semibold text-emerald-600 hover:text-emerald-500">Retour à la connexion</a>.
    </p>
</div>
@endsection
