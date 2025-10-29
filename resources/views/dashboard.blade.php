@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-slate-800">Tableau de bord opérationnel</h1>
        <p class="mt-1 text-sm text-slate-500">Suivi en temps réel du dispositif DermaBus+ et de l’intégration du modèle OMS Skin-NTDs.</p>
    </div>

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-5">
        <x-stat-card title="Patients enregistrés" :value="$stats['patients']" icon="users" />
        <x-stat-card title="Auto-inscriptions" :value="$stats['self_registered']" icon="megaphone" />
        <x-stat-card title="Dépistages effectués" :value="$stats['screenings']" icon="clipboard" />
        <x-stat-card title="Cas pris en charge" :value="$stats['treated']" icon="hand-holding-medical" />
        <x-stat-card title="Réinsertion réussie" :value="$stats['reintegrated']" icon="seedling" />
    </div>

    <div class="mt-10 grid gap-6 lg:grid-cols-2">
        <section class="rounded-xl bg-white p-6 shadow">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-800">Derniers dépistages</h2>
                <a href="{{ route('screenings.index') }}" class="text-sm font-medium text-emerald-600">Voir tout</a>
            </div>
            <p class="mt-1 text-sm text-slate-500">Synthèse des cas récents collectés sur le terrain.</p>
            <div class="mt-4 space-y-4">
                @forelse ($recentScreenings as $screening)
                    <div class="rounded-lg border border-slate-200 p-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-semibold text-slate-700">{{ $screening->patient->full_name }}</span>
                            <span class="text-slate-500">{{ $screening->screened_on?->format('d/m/Y') }}</span>
                        </div>
                        <p class="mt-2 text-sm text-slate-600">Suspicion : <span class="font-medium">{{ $screening->suspected_condition ?? 'À confirmer' }}</span></p>
                        <div class="mt-2 flex items-center gap-3 text-xs text-slate-500">
                            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-1 font-medium text-slate-600">
                                Gravité : <span class="capitalize">{{ Str::headline($screening->severity) }}</span>
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 font-medium text-emerald-700">
                                Référencement : <span class="capitalize">{{ Str::headline($screening->referral_status) }}</span>
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucun dépistage enregistré pour le moment.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-xl bg-white p-6 shadow">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-800">Suivi &amp; réinsertion</h2>
                <a href="{{ route('patients.index') }}" class="text-sm font-medium text-emerald-600">Voir les patients</a>
            </div>
            <p class="mt-1 text-sm text-slate-500">Planification des visites de suivi et accompagnement psychosocial.</p>

            <div class="mt-4 space-y-4">
                @forelse ($upcomingFollowUps as $followUp)
                    <div class="rounded-lg border border-amber-100 bg-amber-50 p-4">
                        <div class="flex items-center justify-between text-sm text-amber-700">
                            <span class="font-semibold">{{ $followUp->patient->full_name }}</span>
                            <span>{{ $followUp->follow_up_on?->format('d/m/Y') }}</span>
                        </div>
                        <p class="mt-2 text-xs text-amber-700/80">Suivi requis : {{ $followUp->clinical_notes ?? 'Sans note clinique' }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucun suivi programmé cette semaine.</p>
                @endforelse
            </div>

            <div class="mt-6 rounded-lg bg-emerald-50 p-4 text-sm text-emerald-700">
                <p class="font-semibold">Objectif DermaBus+ :</p>
                <p class="mt-1">Dépister 30 000 personnes et accompagner 2 000 cas prioritaires dans la préfecture de Kpélé.</p>
            </div>
        </section>
    </div>

    <section class="mt-10 rounded-xl bg-white p-6 shadow">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-800">Ressources de sensibilisation</h2>
            <a href="{{ route('resources.create') }}" class="text-sm font-medium text-emerald-600">Ajouter une ressource</a>
        </div>
        <p class="mt-1 text-sm text-slate-500">Supports de formation et d’éducation issus du modèle OMS “Skin-NTDs”.</p>

        <div class="mt-4 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($resources as $resource)
                <article class="rounded-lg border border-slate-200 p-4">
                    <h3 class="text-base font-semibold text-slate-800">{{ $resource->title }}</h3>
                    <p class="mt-2 h-20 overflow-hidden text-ellipsis text-sm text-slate-600">{{ $resource->summary ?? Str::limit(strip_tags($resource->content), 120) }}</p>
                    <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                        <span class="uppercase tracking-wide">{{ $resource->category ?? 'Sensibilisation' }}</span>
                        <span>{{ optional($resource->published_at)->format('d/m/Y') }}</span>
                    </div>
                </article>
            @empty
                <p class="text-sm text-slate-500">Publiez votre première ressource de sensibilisation pour les agents communautaires.</p>
            @endforelse
        </div>
    </section>
@endsection
