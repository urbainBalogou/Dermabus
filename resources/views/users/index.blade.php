@extends('layouts.app')

@section('content')
<div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-100">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-emerald-700">Gestion des accès</h1>
            <p class="mt-1 text-sm text-slate-600">Visualisez les rôles actifs et assurez le suivi des connexions de l’équipe DermaBus+.</p>
        </div>
        <a href="{{ route('users.create') }}" class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-500">Nouveau compte</a>
    </div>

    <div class="mt-8 overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Nom</th>
                    <th class="px-4 py-3">Rôle</th>
                    <th class="px-4 py-3">Téléphone</th>
                    <th class="px-4 py-3">Zone</th>
                    <th class="px-4 py-3">Dernière connexion</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                    <tr class="hover:bg-emerald-50/40">
                        <td class="px-4 py-3">
                            <div class="font-semibold text-slate-800">{{ $user->name }}</div>
                            <div class="text-xs text-slate-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ App\Models\User::availableRoles()[$user->role] ?? $user->role }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $user->phone ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $user->assigned_zone ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ optional($user->last_login_at)->diffForHumans() ?? 'Jamais' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('users.edit', $user) }}" class="rounded-full border border-emerald-200 px-3 py-1 text-xs font-semibold text-emerald-600 hover:bg-emerald-50">Modifier</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-sm text-slate-500">Aucun membre enregistré pour le moment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
