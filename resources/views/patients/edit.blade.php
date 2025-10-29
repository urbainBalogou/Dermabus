@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-800">Modifier {{ $patient->full_name }}</h1>
        <p class="mt-1 text-sm text-slate-500">Actualisez les informations du patient et l’état d’avancement du suivi.</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow">
        <form method="POST" action="{{ route('patients.update', $patient) }}" class="space-y-6">
            @include('patients.partials.form', ['patient' => $patient])
        </form>
    </div>
@endsection
