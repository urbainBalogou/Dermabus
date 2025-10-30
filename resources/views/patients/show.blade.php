@extends('layouts.app')

@php
    use App\Models\CaseNote;
    use App\Models\FollowUp;
    use App\Models\User;
    use Illuminate\Support\Str;

    $channelLabels = [
        'field_agent' => 'Agent communautaire',
        'mobile_clinic' => 'Clinique mobile DermaBus+',
        'self_registration' => 'Auto-inscription en ligne',
        'referral_partner' => 'Partenaire de référence',
    ];

    $followUpTypeLabels = $followUpTypes ?? [];
    $followUpStatusLabels = $followUpStatuses ?? [];
    $caseNoteCategoryLabels = $caseNoteCategories ?? [];
    $caseNoteVisibilityLabels = $caseNoteVisibilities ?? [];
    $authUser = auth()->user();
@endphp

@section('content')
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800">{{ $patient->full_name }}</h1>
            <p class="text-sm text-slate-500">Référence : <span class="font-semibold">{{ $patient->reference_code }}</span> · ID terrain : {{ $patient->external_id }}</p>
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
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Référent DermaBus+</dt>
                    <dd class="mt-1 text-sm text-slate-700">{{ $patient->primaryAgent->name ?? 'Non attribué' }}</dd>
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
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Plan de prise en charge</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm text-slate-700">{{ $patient->care_plan ?? '—' }}</dd>
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
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Portail patient</dt>
                    <dd class="mt-1">{{ $patient->portal_enabled ? 'Accès actif' : 'Accès suspendu' }} · Dernière connexion : {{ optional($patient->portal_last_access_at)->diffForHumans() ?? 'Jamais' }}</dd>
                    <p class="mt-1 font-mono text-xs text-slate-400">Code portail : {{ $patient->portal_code }}</p>
                </div>
            </dl>
        </section>
    </div>

    <div class="mt-10 grid gap-6 lg:grid-cols-3">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow lg:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-800">Suivis planifiés &amp; visites terrain</h2>
                <a href="{{ route('follow-ups.index', ['timeline' => 'upcoming']) }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-500">Voir tous les suivis</a>
            </div>
            <p class="mt-1 text-sm text-slate-500">Coordination des visites médicales, sociales et appels de contrôle pour {{ $patient->full_name }}.</p>

            @php
                $upcoming = $patient->followUps->where('status', FollowUp::STATUS_PLANNED)->sortBy('scheduled_for');
                $pastFollowUps = $patient->followUps->where('status', '!=', FollowUp::STATUS_PLANNED)->sortByDesc('scheduled_for');
            @endphp

            <div class="mt-4 space-y-4">
                @forelse ($upcoming as $followUp)
                    <article class="rounded-lg border border-emerald-100 bg-emerald-50 p-4 text-sm text-emerald-800">
                        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="font-semibold">{{ $followUp->scheduled_for?->format('d/m/Y H:i') ?? 'À planifier' }}</p>
                                <p class="text-xs uppercase tracking-wide text-emerald-600/80">{{ $followUpTypeLabels[$followUp->type] ?? Str::headline($followUp->type) }}</p>
                            </div>
                            <div class="text-right text-xs">
                                <p class="font-semibold">Référent·e : {{ $followUp->assignee?->name ?? 'Non assigné' }}</p>
                                @if($followUp->location)
                                    <p class="text-emerald-700/80">{{ $followUp->location }}</p>
                                @endif
                            </div>
                        </div>
                        @if($followUp->notes)
                            <p class="mt-3 text-xs text-emerald-700/80">{{ Str::limit($followUp->notes, 180) }}</p>
                        @endif
                        <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-emerald-700/80">
                            <span>Contact : {{ $followUp->contact_mode ?? 'À définir' }}</span>
                            @if($followUp->screening)
                                <span>Dépistage lié : {{ $followUp->screening->screened_on?->format('d/m/Y') }}</span>
                            @endif
                        </div>
                        <div class="mt-4 flex items-center gap-3 text-xs">
                            <a href="{{ route('follow-ups.edit', $followUp) }}" class="inline-flex rounded-md border border-emerald-400 px-3 py-1 font-semibold text-emerald-600 hover:bg-emerald-100">Mettre à jour</a>
                            <form method="POST" action="{{ route('follow-ups.destroy', $followUp) }}" onsubmit="return confirm('Supprimer ce suivi planifié ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex rounded-md border border-transparent bg-emerald-600 px-3 py-1 font-semibold text-white hover:bg-emerald-500">Supprimer</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p class="text-sm text-slate-500">Aucun suivi planifié pour le moment. Utilisez le formulaire pour en créer un.</p>
                @endforelse
            </div>

            @if($pastFollowUps->isNotEmpty())
                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-slate-700">Historique des suivis réalisés</h3>
                    <div class="mt-3 overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-3 py-2 text-left">Date</th>
                                    <th class="px-3 py-2 text-left">Type</th>
                                    <th class="px-3 py-2 text-left">Statut</th>
                                    <th class="px-3 py-2 text-left">Résultats</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($pastFollowUps->take(5) as $followUp)
                                    <tr>
                                        <td class="px-3 py-2 text-slate-600">{{ $followUp->scheduled_for?->format('d/m/Y H:i') ?? '—' }}</td>
                                        <td class="px-3 py-2 text-slate-600">{{ $followUpTypeLabels[$followUp->type] ?? Str::headline($followUp->type) }}</td>
                                        <td class="px-3 py-2 text-slate-600">{{ $followUpStatusLabels[$followUp->status] ?? Str::headline($followUp->status) }}</td>
                                        <td class="px-3 py-2 text-slate-600">{{ Str::limit($followUp->outcome ?? $followUp->notes, 80) ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </section>

        @if($authUser && $authUser->hasRole([User::ROLE_ADMIN, User::ROLE_CLINICIAN, User::ROLE_SOCIAL]))
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow">
                <h2 class="text-lg font-semibold text-slate-800">Planifier un nouveau suivi</h2>
                <p class="mt-1 text-sm text-slate-500">Programmez rapidement une visite DermaBus+, un appel de contrôle ou une action sociale.</p>
                <div class="mt-4">
                    @include('patients.partials.follow-up-quick-form', ['patient' => $patient, 'team' => $team, 'types' => $followUpTypes, 'statuses' => $followUpStatuses])
                </div>
            </section>
        @endif
    </div>

    <div class="mt-10 grid gap-6 lg:grid-cols-3">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow lg:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-800">Notes de suivi clinique &amp; social</h2>
                <span class="text-xs text-slate-500">Visible selon votre rôle dans l’équipe</span>
            </div>
            <p class="mt-1 text-sm text-slate-500">Synthèse des accompagnements personnalisés, observances et besoins psychosociaux.</p>

            @php
                $visibleNotes = $patient->caseNotes->filter(function ($note) use ($authUser) {
                    if (! $authUser) {
                        return false;
                    }

                    if ($authUser->isAdmin() || $note->user_id === $authUser->id) {
                        return true;
                    }

                    return match ($note->visibility) {
                        CaseNote::VISIBILITY_TEAM => true,
                        CaseNote::VISIBILITY_HEALTH => $authUser->hasRole([User::ROLE_ADMIN, User::ROLE_CLINICIAN]),
                        CaseNote::VISIBILITY_SOCIAL => $authUser->hasRole([User::ROLE_ADMIN, User::ROLE_SOCIAL]),
                        default => false,
                    };
                });
            @endphp

            <div class="mt-4 space-y-4">
                @forelse ($visibleNotes as $note)
                    <article class="rounded-lg border border-slate-200 p-4">
                        <div class="flex flex-wrap items-center justify-between gap-3 text-xs text-slate-500">
                            <span class="font-semibold text-slate-700">{{ $note->noted_on?->format('d/m/Y') ?? $note->created_at->format('d/m/Y') }}</span>
                            <span class="rounded-full bg-slate-100 px-2 py-1">{{ $caseNoteCategoryLabels[$note->category] ?? Str::headline($note->category) }}</span>
                            <span class="rounded-full bg-emerald-50 px-2 py-1 text-emerald-700">{{ $caseNoteVisibilityLabels[$note->visibility] ?? Str::headline($note->visibility) }}</span>
                        </div>
                        <h3 class="mt-2 text-base font-semibold text-slate-800">{{ $note->title }}</h3>
                        <p class="mt-1 text-sm text-slate-600">{{ $note->summary }}</p>
                        @if($note->next_actions)
                            <p class="mt-2 text-xs text-slate-500">Actions à suivre : {{ $note->next_actions }}</p>
                        @endif
                        <p class="mt-3 text-xs text-slate-500">Rédigé par {{ $note->author?->name ?? 'Équipe DermaBus+' }}</p>

                        @php
                            $canManageNote = $authUser && ($authUser->isAdmin() || $note->user_id === $authUser->id);
                        @endphp
                        @if($canManageNote)
                            <div class="mt-3 flex items-center gap-3 text-xs">
                                <a href="{{ route('case-notes.edit', $note) }}" class="inline-flex rounded-md border border-slate-300 px-3 py-1 font-semibold text-slate-600 hover:bg-slate-100">Modifier</a>
                                <form method="POST" action="{{ route('case-notes.destroy', $note) }}" onsubmit="return confirm('Supprimer cette note ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex rounded-md bg-red-600 px-3 py-1 font-semibold text-white hover:bg-red-500">Supprimer</button>
                                </form>
                            </div>
                        @endif
                    </article>
                @empty
                    <p class="text-sm text-slate-500">Aucune note enregistrée ou accessible pour votre rôle.</p>
                @endforelse
            </div>
        </section>

        @if($authUser && $authUser->hasRole([User::ROLE_ADMIN, User::ROLE_CLINICIAN, User::ROLE_SOCIAL]))
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow">
                <h2 class="text-lg font-semibold text-slate-800">Ajouter une note de suivi</h2>
                <p class="mt-1 text-sm text-slate-500">Documentez les rendez-vous, besoins psychosociaux ou actions à engager.</p>
                <div class="mt-4">
                    @include('patients.partials.case-note-form', ['patient' => $patient, 'categories' => $caseNoteCategories, 'visibilities' => $caseNoteVisibilities])
                </div>
            </section>
        @endif
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
                        <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-600">{{ Str::headline($screening->severity) }}</span>
                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600">Traitement : {{ Str::headline($screening->treatment_status) }}</span>
                        <span class="text-xs text-slate-500">Référencement : {{ Str::headline($screening->referral_status) }}</span>
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
