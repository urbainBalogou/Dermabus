<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DermaBus+ – Tableau de bord</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
</head>
<body class="bg-slate-100 font-sans text-slate-900">
    <div class="min-h-screen">
        <nav class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500 text-white font-bold">D+</span>
                        <div>
                            <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-emerald-700">DermaBus+</a>
                            <p class="text-sm text-slate-500">Santé communautaire &amp; innovation sociale</p>
                        </div>
                    </div>
                    @if(auth()->check())
                        @php($user = auth()->user())
                        <div class="hidden items-center gap-6 text-sm font-medium text-slate-600 md:flex">
                            <a href="{{ route('home') }}" class="hover:text-emerald-600">Site public</a>
                            <a href="{{ route('dashboard') }}" class="hover:text-emerald-600 {{ request()->routeIs('dashboard') ? 'text-emerald-600' : '' }}">Tableau de bord</a>
                            <a href="{{ route('patients.index') }}" class="hover:text-emerald-600 {{ request()->is('patients*') ? 'text-emerald-600' : '' }}">Patients</a>
                            <a href="{{ route('screenings.index') }}" class="hover:text-emerald-600 {{ request()->is('screenings*') ? 'text-emerald-600' : '' }}">Dépistages</a>
                            @if($user->hasRole([App\Models\User::ROLE_ADMIN, App\Models\User::ROLE_CLINICIAN, App\Models\User::ROLE_SOCIAL]))
                                <a href="{{ route('follow-ups.index') }}" class="hover:text-emerald-600 {{ request()->is('follow-ups*') ? 'text-emerald-600' : '' }}">Suivis</a>
                            @endif
                            <a href="{{ route('resources.index') }}" class="hover:text-emerald-600 {{ request()->is('resources*') ? 'text-emerald-600' : '' }}">Sensibilisation</a>
                            @if($user?->isAdmin())
                                <a href="{{ route('users.index') }}" class="hover:text-emerald-600 {{ request()->is('users*') ? 'text-emerald-600' : '' }}">Équipe</a>
                            @endif
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right text-sm">
                                <p class="font-semibold text-slate-700">{{ $user->name }}</p>
                                <p class="text-xs text-slate-500">{{ App\Models\User::availableRoles()[$user->role] ?? 'Équipe DermaBus+' }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="rounded-full bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Déconnexion</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </nav>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-md border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="border-t border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-4 text-sm text-slate-500 sm:px-6 lg:px-8">
                DermaBus+ &copy; {{ date('Y') }} – L’innovation au service des communautés rurales.
            </div>
        </footer>
    </div>
</body>
</html>
