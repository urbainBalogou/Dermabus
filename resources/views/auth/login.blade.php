@extends('layouts.public')

@section('content')
<div class="mx-auto max-w-xl rounded-3xl bg-white p-8 shadow-xl ring-1 ring-slate-100">
    <h1 class="text-2xl font-bold text-emerald-700">Connexion équipe DermaBus+</h1>
    <p class="mt-2 text-sm text-slate-600">Accédez à vos dossiers patients, dépistages et ressources en toute sécurité.</p>

    <form action="{{ route('login') }}" method="POST" class="mt-6 space-y-5">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700">E-mail professionnel</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            @error('email')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700">Mot de passe</label>
            <input id="password" name="password" type="password" required autocomplete="current-password" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            @error('password')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex items-center justify-between text-sm text-slate-600">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                <span>Se souvenir de moi</span>
            </label>
            <a href="{{ route('patient-portal.login') }}" class="font-semibold text-emerald-600 hover:text-emerald-500">Espace patient</a>
        </div>
        <button type="submit" class="w-full rounded-lg bg-emerald-600 px-4 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Se connecter</button>
    </form>

    @if(!\App\Models\User::query()->exists())
        <p class="mt-6 text-center text-sm text-slate-500">
            Première utilisation ? <a href="{{ route('register') }}" class="font-semibold text-emerald-600 hover:text-emerald-500">Créer le compte administrateur</a>.
        </p>
    @endif
</div>
@endsection
