@extends('layouts.public')

@section('content')
    <section class="grid gap-10 lg:grid-cols-2 lg:items-center">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-emerald-600">Prévention des maladies tropicales négligées cutanées</p>
            <h1 class="mt-3 text-4xl font-bold text-slate-900 sm:text-5xl">DermaBus+ rapproche les soins dermatologiques des communautés rurales du Togo.</h1>
            <p class="mt-4 text-lg text-slate-600">Clinique mobile, application connectée et accompagnement humain : nous dépistons précocement les maladies cutanées, orientons vers les structures de santé et suivons chaque personne jusqu’à sa réinsertion sociale.</p>
            <div class="mt-6 flex flex-wrap gap-4">
                <a href="{{ route('registration.create') }}" class="inline-flex items-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg hover:bg-emerald-500">Je m’inscris au parcours DermaBus+</a>
                <a href="#services" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 5v14m7-7H5" />
                    </svg>
                    Découvrir nos services
                </a>
            </div>
        </div>
        <div class="relative">
            <div class="absolute inset-0 -z-10 translate-x-6 translate-y-6 rounded-3xl bg-emerald-100"></div>
            <div class="rounded-3xl border border-emerald-200 bg-white p-6 shadow-xl">
                <h2 class="text-lg font-semibold text-slate-800">DermaBus+ en chiffres</h2>
                <p class="mt-2 text-sm text-slate-500">Impact des équipes mobiles et de l’application connectée.</p>
                <dl class="mt-4 grid gap-4 sm:grid-cols-3">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Communautés suivies</dt>
                        <dd class="mt-1 text-3xl font-bold text-slate-900">{{ $stats['communities'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Personnes dépistées</dt>
                        <dd class="mt-1 text-3xl font-bold text-slate-900">{{ $stats['screened'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Auto-inscriptions</dt>
                        <dd class="mt-1 text-3xl font-bold text-slate-900">{{ $stats['self_registered'] }}</dd>
                    </div>
                </dl>
                <p class="mt-4 text-xs text-slate-400">Données synchronisées en temps réel depuis l’application DermaBus+.</p>
            </div>
        </div>
    </section>

    <section id="mission" class="mt-16 grid gap-10 rounded-3xl bg-white p-8 shadow-xl lg:grid-cols-2">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Notre mission : détecter, soigner et réinsérer</h2>
            <p class="mt-3 text-sm text-slate-600">Les maladies tropicales négligées cutanées touchent principalement les populations rurales. DermaBus+ combine clinique mobile, outils numériques et médiation communautaire pour offrir un parcours complet : dépistage, référencement, suivi médical et accompagnement psychosocial.</p>
            <ul class="mt-6 space-y-4 text-sm text-slate-700">
                <li class="flex items-start gap-3">
                    <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">1</span>
                    <span><strong>Dépister précocement</strong> grâce au guide OMS Skin-NTDs intégré à l’application.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">2</span>
                    <span><strong>Référencer rapidement</strong> les cas vers les centres de santé partenaires pour confirmation et traitement.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">3</span>
                    <span><strong>Accompagner humainement</strong> les patients et leurs familles vers la guérison et la réinsertion socio-économique.</span>
                </li>
            </ul>
        </div>
        <div class="space-y-6">
            <article class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                <h3 class="text-lg font-semibold text-slate-800">Santé communautaire augmentée</h3>
                <p class="mt-2 text-sm text-slate-600">Nos agents utilisent des tablettes connectées pour documenter les cas, suivre les traitements et alimenter la surveillance nationale.</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                <h3 class="text-lg font-semibold text-slate-800">Respect &amp; confidentialité</h3>
                <p class="mt-2 text-sm text-slate-600">Les données sont chiffrées et chaque patient signe un consentement éclairé. Seule l’équipe médicale peut accéder aux informations nominatives.</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                <h3 class="text-lg font-semibold text-slate-800">Alliance locale</h3>
                <p class="mt-2 text-sm text-slate-600">Nous collaborons avec le Ministère de la Santé, les districts sanitaires et les incubateurs locaux pour assurer la durabilité du projet.</p>
            </article>
        </div>
    </section>

    <section id="services" class="mt-16">
        <h2 class="text-center text-2xl font-semibold text-slate-900">Un accompagnement complet pour les communautés rurales</h2>
        <div class="mt-10 grid gap-6 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-800">Clinique mobile</h3>
                <p class="mt-2 text-sm text-slate-600">Voiture médicalisée équipée pour le dépistage dermatologique, la photographie clinique et les premiers soins.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-800">Application DermaBus+</h3>
                <p class="mt-2 text-sm text-slate-600">Application Flutter connectée au backend Laravel/MySQL, intégrant le module OMS Skin-NTDs et un mode hors ligne.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-800">Réinsertion sociale</h3>
                <p class="mt-2 text-sm text-slate-600">Suivi psychosocial, lutte contre la stigmatisation et soutien à la création de micro-entreprises pour les personnes guéries.</p>
            </div>
        </div>
    </section>

    <section class="mt-16 rounded-3xl bg-emerald-600 px-6 py-10 text-white lg:px-16">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-2xl font-semibold">Prêt à rencontrer l’équipe DermaBus+ ?</h2>
                <p class="mt-2 max-w-xl text-sm text-emerald-100">Inscrivez-vous en ligne et nous vous recontactons pour organiser votre passage sur la clinique mobile ou auprès du centre de santé partenaire le plus proche.</p>
            </div>
            <a href="{{ route('registration.create') }}" class="inline-flex items-center rounded-full bg-white px-6 py-3 text-sm font-semibold text-emerald-700 shadow-lg hover:bg-emerald-100">Remplir le formulaire sécurisé</a>
        </div>
    </section>

    @if ($resources->isNotEmpty())
        <section class="mt-16">
            <h2 class="text-center text-2xl font-semibold text-slate-900">Ressources pour comprendre les MTN cutanées</h2>
            <p class="mt-2 text-center text-sm text-slate-600">Guides visuels, fiches pratiques et conseils de prévention adaptés aux réalités togolaises.</p>
            <div class="mt-8 grid gap-6 lg:grid-cols-3">
                @foreach ($resources as $resource)
                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-slate-800">{{ $resource->title }}</h3>
                        <p class="mt-2 h-24 overflow-hidden text-sm text-slate-600">{{ $resource->summary ?? \Illuminate\Support\Str::limit(strip_tags($resource->content), 140) }}</p>
                        <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
                            <span class="uppercase tracking-wide">{{ $resource->category ?? 'Sensibilisation' }}</span>
                            <span>{{ optional($resource->published_at)->format('d/m/Y') }}</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
@endsection
