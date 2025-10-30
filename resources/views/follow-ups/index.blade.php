@extends('layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800">Suivis terrain &amp; rendez-vous</h1>
            <p class="text-sm text-slate-500">Planifiez et suivez les visites médicales, sociales ou appels de contrôle DermaBus+.</p>
        </div>
        <a href="{{ route('follow-ups.create') }}" class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-500">Nouveau suivi</a>
    </div>

    <form method="GET" class="mb-6 rounded-xl border border-slate-200 bg-white p-4 shadow">
        <div class="grid gap-4 md:grid-cols-4">
            <div>
                <label for="timeline" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Période</label>
                <select id="timeline" name="timeline" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="upcoming" @selected($timeline === 'upcoming')>À venir</option>
                    <option value="past" @selected($timeline === 'past')>Passés</option>
                    <option value="all" @selected($timeline === 'all')>Tous</option>
                </select>
            </div>
            <div>
                <label for="status" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Statut</label>
                <select id="status" name="status" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Tous</option>
                    @foreach ($statusOptions as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="assigned_user_id" class="block text-xs font-semibold uppercase tracking-wide text-slate-500">Assigné à</label>
                <select id="assigned_user_id" name="assigned_user_id" class="mt-1 w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Toute l’équipe</option>
                    @foreach ($team as $id => $name)
                        <option value="{{ $id }}" @selected(request('assigned_user_id') == $id)>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end justify-end">
                <button type="submit" class="inline-flex items-center rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Filtrer</button>
            </div>
        </div>
    </form>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-left">Date &amp; heure</th>
                    <th class="px-4 py-3 text-left">Patient</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Statut</th>
                    <th class="px-4 py-3 text-left">Assigné à</th>
                    <th class="px-4 py-3 text-left">Notes</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($followUps as $followUp)
                    <tr class="hover:bg-slate-50/60">
                        <td class="px-4 py-3 text-slate-600">{{ $followUp->scheduled_for?->format('d/m/Y H:i') ?? 'À planifier' }}</td>
                        <td class="px-4 py-3 text-slate-600">
                            <div class="font-semibold text-slate-800">{{ $followUp->patient->full_name }}</div>
                            <div class="text-xs text-slate-500">{{ $followUp->patient->reference_code }}</div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $typeOptions[$followUp->type] ?? Str::headline($followUp->type) }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-emerald-600">{{ $statusOptions[$followUp->status] ?? Str::headline($followUp->status) }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $followUp->assignee?->name ?? 'À confirmer' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ Str::limit($followUp->notes ?? $followUp->outcome, 60) ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('follow-ups.edit', $followUp) }}" class="inline-flex rounded-md border border-slate-300 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-100">Modifier</a>
                                <form method="POST" action="{{ route('follow-ups.destroy', $followUp) }}" onsubmit="return confirm('Supprimer ce suivi ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex rounded-md bg-red-600 px-3 py-1 text-xs font-semibold text-white hover:bg-red-500">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-sm text-slate-500">Aucun suivi ne correspond à vos filtres.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $followUps->links() }}</div>
@endsection
