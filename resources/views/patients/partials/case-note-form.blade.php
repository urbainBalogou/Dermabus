@props([
    'patient',
    'categories' => [],
    'visibilities' => [],
])

<form method="POST" action="{{ route('patients.case-notes.store', $patient) }}" class="space-y-4">
    @csrf

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="noted_on" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Date</label>
            <input type="date" id="noted_on" name="noted_on" value="{{ now()->format('Y-m-d') }}" required class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
        </div>
        <div>
            <label for="category" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Catégorie</label>
            <select id="category" name="category" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                @foreach ($categories as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label for="visibility" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Visibilité</label>
        <select id="visibility" name="visibility" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            @foreach ($visibilities as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="title" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Titre de la note</label>
        <input type="text" id="title" name="title" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Visite à domicile, séance de soutien, etc." required>
    </div>

    <div>
        <label for="summary" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Résumé</label>
        <textarea id="summary" name="summary" rows="4" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Décrivez l’intervention réalisée et les observations clés." required></textarea>
    </div>

    <div>
        <label for="next_actions" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Actions à suivre</label>
        <textarea id="next_actions" name="next_actions" rows="3" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Planifier un appel, coordonner une aide alimentaire..."></textarea>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-500">Ajouter la note</button>
    </div>
</form>
