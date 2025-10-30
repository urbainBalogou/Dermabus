@csrf
<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label for="medication_name" class="block text-sm font-medium text-slate-700">Médicament prescrit</label>
        <input id="medication_name" name="medication_name" type="text" value="{{ old('medication_name', $prescription->medication_name ?? '') }}" required class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
        @error('medication_name')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="dosage" class="block text-sm font-medium text-slate-700">Dosage</label>
        <input id="dosage" name="dosage" type="text" value="{{ old('dosage', $prescription->dosage ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
        @error('dosage')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="frequency" class="block text-sm font-medium text-slate-700">Fréquence</label>
        <input id="frequency" name="frequency" type="text" value="{{ old('frequency', $prescription->frequency ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
        @error('frequency')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label for="duration" class="block text-sm font-medium text-slate-700">Durée</label>
        <input id="duration" name="duration" type="text" value="{{ old('duration', $prescription->duration ?? '') }}" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
        @error('duration')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>
    <div class="sm:col-span-2">
        <label for="instructions" class="block text-sm font-medium text-slate-700">Instructions complémentaires</label>
        <textarea id="instructions" name="instructions" rows="4" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('instructions', $prescription->instructions ?? '') }}</textarea>
        @error('instructions')
            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>
