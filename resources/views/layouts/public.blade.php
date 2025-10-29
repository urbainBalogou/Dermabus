<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DermaBus+ – Santé communautaire</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
</head>
<body class="bg-slate-50 font-sans text-slate-900">
    <div class="min-h-screen">
        <header class="border-b border-emerald-100 bg-white/90 backdrop-blur">
            <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-4 py-5 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-emerald-500 text-lg font-bold text-white shadow">D+</span>
                    <div>
                        <p class="text-base font-semibold text-emerald-700">DermaBus+</p>
                        <p class="text-xs text-slate-500">La santé cutanée qui va vers les communautés</p>
                    </div>
                </a>
                <nav class="flex flex-wrap items-center gap-4 text-sm font-semibold text-slate-600">
                    <a href="{{ route('home') }}" class="hover:text-emerald-600 {{ request()->routeIs('home') ? 'text-emerald-600' : '' }}">Accueil</a>
                    <a href="{{ route('home') }}#mission" class="hover:text-emerald-600">Notre mission</a>
                    <a href="{{ route('home') }}#services" class="hover:text-emerald-600">Services</a>
                    <a href="{{ route('registration.create') }}" class="rounded-full bg-emerald-600 px-4 py-2 text-white shadow hover:bg-emerald-500 {{ request()->routeIs('registration.*') ? 'ring-2 ring-emerald-300 ring-offset-2 ring-offset-emerald-600' : '' }}">S’inscrire</a>
                    <a href="{{ route('dashboard') }}" class="text-xs font-semibold uppercase tracking-wide text-slate-400 hover:text-slate-600">Espace équipe</a>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="border-t border-slate-200 bg-white">
            <div class="mx-auto max-w-6xl px-4 py-6 text-sm text-slate-500 sm:px-6 lg:px-8">
                <p>© {{ date('Y') }} DermaBus+. Ensemble pour vaincre les maladies tropicales négligées cutanées.</p>
                <p class="mt-1 text-xs">Projet porté par Agbessimé Prisca et le réseau DermaBus+. Données protégées &amp; hébergement sécurisé.</p>
            </div>
        </footer>
    </div>
</body>
</html>
