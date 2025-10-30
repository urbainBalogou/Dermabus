@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-800">Modifier le suivi</h1>
        <p class="mt-1 text-sm text-slate-500">Ajustez les informations et consignez le résultat de la visite DermaBus+.</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow">
        <form method="POST" action="{{ route('follow-ups.update', $followUp) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('follow-ups.partials.form', ['followUp' => $followUp])
        </form>
    </div>
@endsection
