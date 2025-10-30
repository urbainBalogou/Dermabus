@props([
    'patient',
    'team' => [],
    'types' => [],
    'statuses' => [],
])

<form method="POST" action="{{ route('follow-ups.store') }}" class="space-y-4">
    @csrf
    <input type="hidden" name="patient_id" value="{{ $patient->id }}">

    <div>
        <label for="screening_id" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Dépistage associé</label>
        <select id="screening_id" name="screening_id" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">Plan de suivi général</option>
            @foreach ($patient->screenings as $screening)
                <option value="{{ $screening->id }}">{{ $screening->screened_on?->format('d/m/Y') }} · {{ $screening->suspected_condition ?? 'Observation terrain' }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="scheduled_for" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Date &amp; heure</label>
            <input type="datetime-local" id="scheduled_for" name="scheduled_for" value="{{ now()->format('Y-m-d\\TH:i') }}" required class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
        </div>
        <div>
            <label for="type" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Type de suivi</label>
            <select id="type" name="type" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @foreach ($types as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="assigned_user_id" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Membre assigné</label>
            <select id="assigned_user_id" name="assigned_user_id" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">À confirmer</option>
                @foreach ($team as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="status" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Statut</label>
            <select id="status" name="status" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected($value === 'planned')>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label for="contact_mode" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Mode de contact</label>
        <input type="text" id="contact_mode" name="contact_mode" placeholder="Appel, visite terrain, rendez-vous centre..." class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
    </div>

    <div>
        <label for="notes" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Objectif du suivi</label>
        <textarea id="notes" name="notes" rows="3" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Préparer le transfert vers l’hôpital, vérifier l’observance, organiser un soutien social..."></textarea>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-500">Planifier le suivi</button>
    </div>
</form>
