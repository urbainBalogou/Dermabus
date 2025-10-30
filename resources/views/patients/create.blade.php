@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-800">Nouvelle fiche patient</h1>
        <p class="mt-1 text-sm text-slate-500">Enregistrez un nouveau cas pour le suivi clinique et communautaire.</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow">
        <form method="POST" action="{{ route('patients.store') }}" class="space-y-6">
            @include('patients.partials.form', ['agents' => $agents])
        </form>
    </div>
@endsection
