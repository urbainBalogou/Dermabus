@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-800">Modifier le dépistage</h1>
        <p class="mt-1 text-sm text-slate-500">Mettez à jour les informations collectées sur le terrain.</p>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow">
        <form method="POST" action="{{ route('screenings.update', $screening) }}" class="space-y-6">
            @include('screenings.partials.form', ['screening' => $screening, 'patients' => $patients, 'selectedPatient' => $screening->patient_id])
        </form>
    </div>
@endsection
