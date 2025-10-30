@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-800">Planifier un suivi</h1>
        <p class="mt-1 text-sm text-slate-500">Renseignez les détails du prochain rendez-vous avec {{ $patients[$selectedPatient] ?? 'un·e patient·e DermaBus+' }}.</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow">
        <form method="POST" action="{{ route('follow-ups.store') }}" class="space-y-6">
            @include('follow-ups.partials.form', ['followUp' => new \App\Models\FollowUp()])
        </form>
    </div>
@endsection
