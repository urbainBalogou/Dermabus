@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800">Patients suivis</h1>
            <p class="text-sm text-slate-500">Gestion des dossiers patients et suivi de l’accompagnement communautaire.</p>
        </div>
        <a href="{{ route('patients.create') }}" class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-500">Nouvelle fiche patient</a>
    </div>

    <form method="GET" class="mb-6">
        <label class="text-sm font-medium text-slate-600" for="search">Recherche</label>
        <div class="mt-1 flex gap-3">
            <input type="search" name="search" id="search" value="{{ request('search') }}" placeholder="Nom, ID ou village" class="w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
            <button type="submit" class="rounded-md bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Filtrer</button>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Identifiant</th>
                    <th class="px-4 py-3">Nom complet</th>
                    <th class="px-4 py-3">Localisation</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-sm">
                @forelse ($patients as $patient)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-mono text-xs text-slate-500">{{ $patient->external_id }}</td>
                        <td class="px-4 py-3 font-medium text-slate-700">{{ $patient->full_name }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $patient->village ?? '—' }}, {{ $patient->district ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-600">{{ Str::headline($patient->status) }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('patients.show', $patient) }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-500">Voir</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">Aucun patient enregistré pour le moment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $patients->links() }}</div>
@endsection
