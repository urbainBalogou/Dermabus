@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-800">Modifier la note de suivi</h1>
        <p class="mt-1 text-sm text-slate-500">Patient·e : {{ $patient->full_name }} · Référence {{ $patient->reference_code }}</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow">
        <form method="POST" action="{{ route('case-notes.update', $caseNote) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="noted_on" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Date</label>
                    <input type="date" id="noted_on" name="noted_on" value="{{ old('noted_on', $caseNote->noted_on?->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label for="category" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Catégorie</label>
                    <select id="category" name="category" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                        @foreach ($categories as $value => $label)
                            <option value="{{ $value }}" @selected(old('category', $caseNote->category) == $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="visibility" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Visibilité</label>
                <select id="visibility" name="visibility" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @foreach ($visibilities as $value => $label)
                        <option value="{{ $value }}" @selected(old('visibility', $caseNote->visibility) == $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="title" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Titre</label>
                <input type="text" id="title" name="title" value="{{ old('title', $caseNote->title) }}" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
            </div>

            <div>
                <label for="summary" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Résumé</label>
                <textarea id="summary" name="summary" rows="4" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>{{ old('summary', $caseNote->summary) }}</textarea>
            </div>

            <div>
                <label for="next_actions" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Actions à suivre</label>
                <textarea id="next_actions" name="next_actions" rows="3" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('next_actions', $caseNote->next_actions) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('patients.show', $patient) }}" class="inline-flex items-center rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Annuler</a>
                <button type="submit" class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-500">Enregistrer</button>
            </div>
        </form>
    </div>
@endsection
