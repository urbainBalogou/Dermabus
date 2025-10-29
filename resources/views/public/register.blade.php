@extends('layouts.public')

@section('content')
    <section class="mx-auto max-w-3xl">
        <h1 class="text-3xl font-bold text-slate-900">Formulaire d’auto-inscription sécurisée</h1>
        <p class="mt-3 text-sm text-slate-600">Vous (ou un proche) présentez des signes dermatologiques suspects ? Renseignez ce formulaire et l’équipe DermaBus+ vous contactera pour organiser un dépistage et un accompagnement personnalisé.</p>

        @if (session('reference'))
            <div class="mt-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                <p class="font-semibold">Votre référence DermaBus+ : {{ session('reference') }}</p>
                <p class="mt-1">Conservez ce code pour faciliter nos échanges et le suivi de votre dossier.</p>
            </div>
        @endif

        <form action="{{ route('registration.store') }}" method="POST" class="mt-8 space-y-6 rounded-3xl border border-slate-200 bg-white p-8 shadow-xl">
            @csrf
            <div class="grid gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-slate-600" for="full_name">Nom et prénoms *</label>
                    <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
                    @error('full_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600" for="phone">Téléphone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
                    @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600" for="email">Adresse email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600" for="village">Village / Quartier</label>
                    <input type="text" name="village" id="village" value="{{ old('village') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
                    @error('village') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600" for="district">Préfecture / District</label>
                    <input type="text" name="district" id="district" value="{{ old('district') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
                    @error('district') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-slate-600" for="region">Région</label>
                    <input type="text" name="region" id="region" value="{{ old('region') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
                    @error('region') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-medium text-slate-600" for="preferred_language">Langue la plus parlée</label>
                    <input type="text" name="preferred_language" id="preferred_language" value="{{ old('preferred_language') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
                    @error('preferred_language') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="text-sm font-medium text-slate-600" for="health_concerns">Décrivez vos symptômes ou vos inquiétudes</label>
                <textarea name="health_concerns" id="health_concerns" rows="5" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Ex : lésions qui s’agrandissent, démangeaisons, douleurs, durée des symptômes...">{{ old('health_concerns') }}</textarea>
                @error('health_concerns') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                <p class="font-semibold text-slate-800">Protection de vos données</p>
                <p class="mt-2">Vos informations sont traitées par l’équipe DermaBus+ pour organiser un dépistage et un suivi. Elles sont stockées sur un serveur sécurisé, chiffrées et jamais partagées sans votre accord.</p>
                <label class="mt-4 flex items-start gap-3">
                    <input type="checkbox" name="consent" value="1" class="mt-1 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" {{ old('consent') ? 'checked' : '' }} required />
                    <span>J’accepte que DermaBus+ me contacte et traite mes données de santé dans le cadre de son programme de dépistage.</span>
                </label>
                @error('consent') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">← Retour à l’accueil</a>
                <button type="submit" class="inline-flex items-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg hover:bg-emerald-500">Envoyer ma demande</button>
            </div>
        </form>
    </section>
@endsection
