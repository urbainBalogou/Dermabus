@extends('layouts.app')

@section('content')
<div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-emerald-700">Nouvelle prescription</h1>
            <p class="mt-1 text-sm text-slate-600">Ajoutez un traitement suite au dépistage du {{ $screening->screened_on->format('d/m/Y') }}.</p>
        </div>
        <a href="{{ route('screenings.show', $screening) }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Retour au dépistage</a>
    </div>

    <div class="mt-6 rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
        <p><span class="font-semibold text-slate-800">Patient :</span> {{ $screening->patient->full_name }} ({{ $screening->patient->reference_code }})</p>
        <p class="mt-1"><span class="font-semibold text-slate-800">Agent :</span> {{ $screening->agent->name ?? 'Non attribué' }}</p>
        <p class="mt-1"><span class="font-semibold text-slate-800">Diagnostic suspecté :</span> {{ $screening->suspected_condition ?? '—' }}</p>
    </div>

    <form action="{{ route('screenings.prescriptions.store', $screening) }}" method="POST" class="mt-8 space-y-6">
        @include('prescriptions.partials.form')

        <div class="flex justify-end">
            <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Enregistrer la prescription</button>
        </div>
    </form>
</div>
@endsection
