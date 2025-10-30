@extends('layouts.public')

@php use App\Models\FollowUp; use Illuminate\Support\Str; @endphp

@section('content')
<div class="mx-auto max-w-5xl space-y-8">
    <div class="rounded-3xl bg-white p-8 shadow-xl ring-1 ring-emerald-100">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-emerald-700">Bonjour {{ $patient->full_name }}</h1>
                <p class="mt-1 text-sm text-slate-600">Référence DermaBus+ : <span class="font-semibold text-emerald-600">{{ $patient->reference_code }}</span></p>
                <p class="text-sm text-slate-500">Dernière connexion : {{ optional($patient->portal_last_access_at)->diffForHumans() ?? 'Première visite' }}</p>
            </div>
            <form method="POST" action="{{ route('patient-portal.logout') }}">
                @csrf
                <button type="submit" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">Se déconnecter</button>
            </form>
        </div>

        <div class="mt-6 grid gap-6 md:grid-cols-3">
            <div class="rounded-2xl bg-emerald-50 p-5 text-sm text-emerald-700">
                <p class="text-xs font-semibold uppercase tracking-wide">Statut dossier</p>
                <p class="mt-2 text-xl font-bold text-emerald-900">{{ Str::headline($patient->status) }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-5 text-sm text-slate-600">
                <p class="text-xs font-semibold uppercase tracking-wide">Téléphone</p>
                <p class="mt-2 text-xl font-semibold text-slate-800">{{ $patient->phone ?? 'Non renseigné' }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-5 text-sm text-slate-600">
                <p class="text-xs font-semibold uppercase tracking-wide">Référent·e DermaBus+</p>
                <p class="mt-2 text-xl font-semibold text-slate-800">{{ $patient->primaryAgent?->name ?? 'En cours d’assignation' }}</p>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <section class="rounded-3xl bg-white p-6 shadow ring-1 ring-slate-100">
            <h2 class="text-lg font-semibold text-slate-800">Mes rendez-vous de suivi</h2>
            <p class="mt-1 text-sm text-slate-500">Prochaines visites prévues avec l’équipe médicale ou sociale.</p>
            <div class="mt-4 space-y-4">
                @php
                    $typeLabels = [
                        FollowUp::TYPE_MEDICAL => 'Visite médicale',
                        FollowUp::TYPE_SOCIAL => 'Visite sociale',
                        FollowUp::TYPE_PHONE => 'Appel de suivi',
                    ];
                    $followUps = $patient->followUps->where('status', FollowUp::STATUS_PLANNED)->sortBy('scheduled_for')->take(5);
                @endphp
                @forelse ($followUps as $followUp)
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4 text-sm text-emerald-700">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold">{{ optional($followUp->scheduled_for)->locale('fr')->translatedFormat('d F Y à H\hi') }}</span>
                            <span class="text-xs uppercase tracking-wide">{{ $typeLabels[$followUp->type] ?? Str::headline($followUp->type) }}</span>
                        </div>
                        <p class="mt-2 text-xs">Avec : {{ $followUp->assignee?->name ?? 'Équipe DermaBus+' }} · {{ $followUp->location ?? 'Lieu à confirmer' }}</p>
                        @if($followUp->notes)
                            <p class="mt-2 text-xs">{{ Str::limit($followUp->notes, 120) }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucun rendez-vous n’est planifié pour le moment. L’équipe vous contactera dès qu’une visite sera programmée.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-3xl bg-white p-6 shadow ring-1 ring-slate-100">
            <h2 class="text-lg font-semibold text-slate-800">Mes traitements</h2>
            <p class="mt-1 text-sm text-slate-500">Synthèse des prescriptions réalisées lors des dépistages.</p>
            <div class="mt-4 space-y-4">
                @php
                    $prescriptions = $patient->prescriptions()->latest()->with(['screening'])->get();
                @endphp
                @forelse ($prescriptions as $prescription)
                    <div class="rounded-2xl border border-slate-200 p-4 text-sm text-slate-700">
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span>{{ optional($prescription->screening->screened_on)->format('d/m/Y') }}</span>
                            <span>{{ Str::headline($prescription->screening->treatment_status) }}</span>
                        </div>
                        <p class="mt-2 text-base font-semibold text-slate-800">{{ $prescription->medication_name }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $prescription->dosage ?? 'Dosage à préciser avec le soignant' }}</p>
                        <p class="mt-1 text-xs text-slate-500">Fréquence : {{ $prescription->frequency ?? '—' }} · Durée : {{ $prescription->duration ?? '—' }}</p>
                        @if ($prescription->instructions)
                            <p class="mt-2 text-xs text-slate-500">{{ $prescription->instructions }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucun traitement n’est enregistré pour le moment.</p>
                @endforelse
            </div>
        </section>
    </div>

    <section class="rounded-3xl bg-white p-6 shadow ring-1 ring-slate-100">
        <h2 class="text-lg font-semibold text-slate-800">Historique des visites</h2>
        <p class="mt-1 text-sm text-slate-500">Consultez les dépistages réalisés et leur suivi.</p>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Lieu</th>
                        <th class="px-4 py-3">Observations</th>
                        <th class="px-4 py-3">Suivi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($patient->screenings as $screening)
                        <tr>
                            <td class="px-4 py-3 text-slate-600">{{ optional($screening->screened_on)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $screening->screening_location ?? 'Village' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ Str::limit($screening->clinical_notes ?? $screening->suspected_condition, 60) ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-600">
                                @php($relatedFollowUp = $patient->followUps->firstWhere('screening_id', $screening->id))
                                @if($relatedFollowUp && $relatedFollowUp->status === FollowUp::STATUS_PLANNED)
                                    Suivi prévu le {{ optional($relatedFollowUp->scheduled_for)->format('d/m') }}
                                @elseif($relatedFollowUp)
                                    {{ Str::headline($relatedFollowUp->status) }} le {{ optional($relatedFollowUp->scheduled_for)->format('d/m') }}
                                @else
                                    Observation clôturée
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500">Aucun dépistage enregistré à ce jour.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
