@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800">Dépistages terrain</h1>
            <p class="text-sm text-slate-500">Suivi du protocole OMS Skin-NTDs et référencement des cas vers les structures de santé.</p>
        </div>
        <a href="{{ route('screenings.create') }}" class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-500">Nouveau dépistage</a>
    </div>

    <form method="GET" class="mb-6 grid gap-4 md:grid-cols-3">
        <div>
            <label class="text-sm font-medium text-slate-600" for="severity">Gravité</label>
            <select name="severity" id="severity" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Toutes</option>
                <option value="low" @selected(request('severity') === 'low')>Faible</option>
                <option value="medium" @selected(request('severity') === 'medium')>Moyenne</option>
                <option value="high" @selected(request('severity') === 'high')>Élevée</option>
            </select>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600" for="status">Statut de référencement</label>
            <select name="status" id="status" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Tous</option>
                <option value="pending" @selected(request('status') === 'pending')>En attente</option>
                <option value="in_progress" @selected(request('status') === 'in_progress')>En cours</option>
                <option value="completed" @selected(request('status') === 'completed')>Réalisé</option>
                <option value="declined" @selected(request('status') === 'declined')>Refusé</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full rounded-md bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Filtrer</button>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Patient</th>
                    <th class="px-4 py-3">Suspicion</th>
                    <th class="px-4 py-3">Gravité</th>
                    <th class="px-4 py-3">Référencement</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-sm">
                @forelse ($screenings as $screening)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-slate-500">{{ $screening->screened_on?->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 font-medium text-slate-700">{{ $screening->patient->full_name }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $screening->suspected_condition ?? 'À confirmer' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-600">{{ Str::headline($screening->severity) }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ Str::headline($screening->referral_status) }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('screenings.show', $screening) }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-500">Voir</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-sm text-slate-500">Aucun dépistage enregistré.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $screenings->links() }}</div>
@endsection
