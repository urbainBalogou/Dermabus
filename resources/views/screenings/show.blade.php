@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800">Dépistage du {{ $screening->screened_on?->format('d/m/Y') }}</h1>
            <p class="text-sm text-slate-500">Patient : {{ $screening->patient->full_name }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('screenings.edit', $screening) }}" class="inline-flex items-center rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Modifier</a>
            <form action="{{ route('screenings.destroy', $screening) }}" method="POST" onsubmit="return confirm('Supprimer ce dépistage ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">Supprimer</button>
            </form>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow lg:col-span-2">
            <h2 class="text-lg font-semibold text-slate-800">Résumé clinique</h2>
            <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Gravité</dt>
                    <dd class="mt-1 inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-600">{{ Str::headline($screening->severity) }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Suspicion clinique</dt>
                    <dd class="mt-1 text-sm text-slate-700">{{ $screening->suspected_condition ?? 'À confirmer' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Score de risque</dt>
                    <dd class="mt-1 text-sm text-slate-700">{{ $screening->risk_score ?? 'Non calculé' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Agent en charge</dt>
                    <dd class="mt-1 text-sm text-slate-700">{{ $screening->agent?->name ?? 'Non attribué' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Symptômes observés</dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        @if($screening->symptoms)
                            <ul class="list-disc space-y-1 pl-5">
                                @foreach ($screening->symptoms as $symptom)
                                    <li>{{ $symptom }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span>Non renseigné</span>
                        @endif
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Notes cliniques</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm text-slate-700">{{ $screening->clinical_notes ?? '—' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Notes communautaires</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm text-slate-700">{{ $screening->community_notes ?? '—' }}</dd>
                </div>
            </dl>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow">
            <h2 class="text-lg font-semibold text-slate-800">Référencement &amp; suivi</h2>
            <dl class="mt-4 space-y-4 text-sm text-slate-700">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Structure de référence</dt>
                    <dd class="mt-1">{{ $screening->referral_facility ?? 'À déterminer' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Statut du référencement</dt>
                    <dd class="mt-1">{{ Str::headline($screening->referral_status) }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Suivi requis</dt>
                    <dd class="mt-1">{{ $screening->requires_follow_up ? 'Oui' : 'Non' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date de suivi</dt>
                    <dd class="mt-1">{{ optional($screening->follow_up_on)->format('d/m/Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Localisation</dt>
                    <dd class="mt-1">{{ $screening->screening_location ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Coordonnées GPS</dt>
                    <dd class="mt-1">{{ $screening->gps_latitude ?? '—' }}, {{ $screening->gps_longitude ?? '—' }}</dd>
                </div>
            </dl>
        </section>
    </div>
@endsection
