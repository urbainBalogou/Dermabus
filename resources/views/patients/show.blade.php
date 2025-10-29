@extends('layouts.app')

@php
    use Illuminate\Support\Str;

    $channelLabels = [
        'field_agent' => 'Agent communautaire',
        'mobile_clinic' => 'Clinique mobile DermaBus+',
        'self_registration' => 'Auto-inscription en ligne',
        'referral_partner' => 'Partenaire de référence',
    ];
@endphp

@section('content')
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800">{{ $patient->full_name }}</h1>
            <p class="text-sm text-slate-500">Identifiant DermaBus+ : {{ $patient->external_id }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('patients.edit', $patient) }}" class="inline-flex items-center rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Modifier</a>
            <form action="{{ route('patients.destroy', $patient) }}" method="POST" onsubmit="return confirm('Supprimer cette fiche patient ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">Supprimer</button>
            </form>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow lg:col-span-2">
            <h2 class="text-lg font-semibold text-slate-800">Informations générales</h2>
            <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Genre</dt>
                    <dd class="mt-1 text-sm text-slate-700">{{ $patient->gender ?? 'Non renseigné' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date de naissance</dt>
                    <dd class="mt-1 text-sm text-slate-700">{{ optional($patient->date_of_birth)->format('d/m/Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Localisation</dt>
                    <dd class="mt-1 text-sm text-slate-700">{{ implode(', ', array_filter([$patient->village, $patient->district, $patient->region])) ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Contact</dt>
                    <dd class="mt-1 text-sm text-slate-700">{{ $patient->phone ?? '—' }} · {{ $patient->email ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Langue préférée</dt>
                    <dd class="mt-1 text-sm text-slate-700">{{ $patient->preferred_language ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Canal d’enregistrement</dt>
                    <dd class="mt-1 text-sm text-slate-700">{{ $channelLabels[$patient->registration_channel] ?? Str::headline($patient->registration_channel ?? 'field_agent') }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Antécédents médicaux</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm text-slate-700">{{ $patient->medical_history ?? 'Non renseigné' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Notes psychosociales</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm text-slate-700">{{ $patient->psychosocial_notes ?? 'Non renseigné' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Déclaration du patient</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm text-slate-700">{{ $patient->self_report_notes ?? '—' }}</dd>
                </div>
            </dl>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow">
            <h2 class="text-lg font-semibold text-slate-800">Réinsertion &amp; statut</h2>
            <dl class="mt-4 space-y-4 text-sm text-slate-700">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Statut</dt>
                    <dd class="mt-1 inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-600">{{ Str::headline($patient->status) }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Réinsertion socio-économique</dt>
                    <dd class="mt-1">{{ $patient->is_reintegrated ? 'Réinséré(e)' : 'En cours' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date de réinsertion</dt>
                    <dd class="mt-1">{{ optional($patient->reintegrated_at)->format('d/m/Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Coordonnées GPS</dt>
                    <dd class="mt-1">{{ $patient->gps_latitude ?? '—' }}, {{ $patient->gps_longitude ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Contact d’urgence</dt>
                    <dd class="mt-1">{{ $patient->emergency_contact_name ?? '—' }} · {{ $patient->emergency_contact_phone ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Auto-inscription</dt>
                    <dd class="mt-1">{{ $patient->is_self_registered ? 'Inscription en ligne confirmée' : 'Dossier créé par un agent' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Consentement</dt>
                    <dd class="mt-1">{{ optional($patient->consent_signed_at)->format('d/m/Y H:i') ?? 'Non recueilli' }}</dd>
                </div>
            </dl>
        </section>
    </div>

    <section class="mt-10 rounded-xl border border-slate-200 bg-white p-6 shadow">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-800">Historique des dépistages</h2>
            <a href="{{ route('screenings.create', ['patient_id' => $patient->id]) }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-500">Nouveau dépistage</a>
        </div>
        <p class="mt-1 text-sm text-slate-500">Suivi des passages du DermaBus+ et du protocole OMS Skin-NTDs.</p>

        <div class="mt-4 space-y-4">
            @forelse ($patient->screenings as $screening)
                <article class="rounded-lg border border-slate-200 p-4">
                    <div class="flex flex-wrap items-center justify-between gap-3 text-sm">
                        <span class="font-semibold text-slate-700">{{ $screening->screened_on?->format('d/m/Y') }}</span>
                        <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-600">{{ $screening->severity }}</span>
                        <span class="text-xs text-slate-500">Référencement : {{ $screening->referral_status }}</span>
                    </div>
                    <p class="mt-2 text-sm text-slate-600">Suspicion : {{ $screening->suspected_condition ?? 'À confirmer' }}</p>
                    <p class="mt-1 text-xs text-slate-500">Agent : {{ $screening->agent?->name ?? 'Non attribué' }}</p>
                    <a href="{{ route('screenings.show', $screening) }}" class="mt-3 inline-flex text-sm font-semibold text-emerald-600 hover:text-emerald-500">Voir les détails</a>
                </article>
            @empty
                <p class="text-sm text-slate-500">Aucun dépistage enregistré pour ce patient.</p>
            @endforelse
        </div>
    </section>
@endsection
