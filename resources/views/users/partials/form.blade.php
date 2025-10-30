@csrf
<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label for="name" class="block text-sm font-medium text-slate-700">Nom complet</label>
        <input id="name" name="name" type="text" value="{{ old('name', $user->name ?? '') }}" required class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
        @error('name')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="email" class="block text-sm font-medium text-slate-700">E-mail</label>
        <input id="email" name="email" type="email" value="{{ old('email', $user->email ?? '') }}" required class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
        @error('email')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="phone" class="block text-sm font-medium text-slate-700">Téléphone</label>
        <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
        @error('phone')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="role" class="block text-sm font-medium text-slate-700">Rôle</label>
        <select id="role" name="role" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            @foreach($roles as $key => $label)
                <option value="{{ $key }}" @selected(old('role', $user->role ?? '') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        @error('role')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="title" class="block text-sm font-medium text-slate-700">Fonction</label>
        <input id="title" name="title" type="text" value="{{ old('title', $user->title ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
        @error('title')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="assigned_zone" class="block text-sm font-medium text-slate-700">Zone d’intervention</label>
        <input id="assigned_zone" name="assigned_zone" type="text" value="{{ old('assigned_zone', $user->assigned_zone ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
        @error('assigned_zone')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>
<div class="mt-5 grid gap-5 sm:grid-cols-2">
    <div>
        <label for="password" class="block text-sm font-medium text-slate-700">Mot de passe</label>
        <input id="password" name="password" type="password" {{ isset($user) ? '' : 'required' }} class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
        @error('password')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirmation</label>
        <input id="password_confirmation" name="password_confirmation" type="password" {{ isset($user) ? '' : 'required' }} class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
    </div>
</div>
