@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-800">Nouveau dépistage</h1>
        <p class="mt-1 text-sm text-slate-500">Documentez un passage du DermaBus+ en suivant le guide OMS Skin-NTDs.</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow">
        <form method="POST" action="{{ route('screenings.store') }}" class="space-y-6">
            @include('screenings.partials.form', ['patients' => $patients, 'selectedPatient' => $selectedPatient])
        </form>
    </div>
@endsection
