@csrf

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label for="patient_id" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Patient</label>
        <select id="patient_id" name="patient_id" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
            @foreach ($patients as $id => $name)
                <option value="{{ $id }}" @selected(old('patient_id', $followUp->patient_id ?? $selectedPatient) == $id)>{{ $name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="screening_id" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Dépistage lié</label>
        <select id="screening_id" name="screening_id" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">Plan global</option>
            @foreach ($relatedScreenings as $screening)
                <option value="{{ $screening->id }}" @selected(old('screening_id', $followUp->screening_id ?? null) == $screening->id)>
                    {{ $screening->screened_on?->format('d/m/Y') }} · {{ $screening->suspected_condition ?? 'Observation terrain' }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label for="scheduled_for" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Date &amp; heure</label>
        <input type="datetime-local" id="scheduled_for" name="scheduled_for" value="{{ old('scheduled_for', optional($followUp->scheduled_for ?? null)->format('Y-m-d\\TH:i') ?? now()->format('Y-m-d\\TH:i')) }}" required class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
    </div>
    <div>
        <label for="assigned_user_id" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Assigné à</label>
        <select id="assigned_user_id" name="assigned_user_id" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">À confirmer</option>
            @foreach ($team as $id => $name)
                <option value="{{ $id }}" @selected(old('assigned_user_id', $followUp->assigned_user_id ?? null) == $id)>{{ $name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid gap-4 md:grid-cols-3">
    <div>
        <label for="type" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Type</label>
        <select id="type" name="type" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            @foreach ($typeOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('type', $followUp->type ?? null) == $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="status" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Statut</label>
        <select id="status" name="status" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $followUp->status ?? 'planned') == $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="completed_at" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Terminé le</label>
        <input type="datetime-local" id="completed_at" name="completed_at" value="{{ old('completed_at', optional($followUp->completed_at ?? null)->format('Y-m-d\\TH:i')) }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
    </div>
</div>

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label for="location" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Lieu / zone</label>
        <input type="text" id="location" name="location" value="{{ old('location', $followUp->location ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Village, centre de santé, domicile...">
    </div>
    <div>
        <label for="contact_mode" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Mode de contact</label>
        <input type="text" id="contact_mode" name="contact_mode" value="{{ old('contact_mode', $followUp->contact_mode ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Visite terrain, appel, SMS...">
    </div>
</div>

<div>
    <label for="notes" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Objectif / préparatifs</label>
    <textarea id="notes" name="notes" rows="4" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('notes', $followUp->notes ?? '') }}</textarea>
</div>

<div>
    <label for="outcome" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Résultats / conclusion</label>
    <textarea id="outcome" name="outcome" rows="4" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('outcome', $followUp->outcome ?? '') }}</textarea>
</div>

<div class="flex justify-end">
    <button type="submit" class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-500">Enregistrer</button>
</div>
