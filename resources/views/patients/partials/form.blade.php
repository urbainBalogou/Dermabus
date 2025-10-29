@php $patientModel = $patient ?? null; @endphp

@csrf
@if(isset($patientModel))
    @method('PUT')
@endif

<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label class="text-sm font-medium text-slate-600" for="full_name">Nom complet *</label>
        <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $patientModel->full_name ?? '') }}" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('full_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="external_id">Identifiant terrain</label>
        <input type="text" name="external_id" id="external_id" value="{{ old('external_id', $patientModel->external_id ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('external_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="date_of_birth">Date de naissance</label>
        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $patientModel?->date_of_birth?->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('date_of_birth') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="gender">Genre</label>
        <select name="gender" id="gender" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">Sélectionner</option>
            <option value="female" @selected(old('gender', $patientModel->gender ?? '') === 'female')>Femme</option>
            <option value="male" @selected(old('gender', $patientModel->gender ?? '') === 'male')>Homme</option>
            <option value="other" @selected(old('gender', $patientModel->gender ?? '') === 'other')>Autre</option>
        </select>
        @error('gender') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="phone">Téléphone</label>
        <input type="text" name="phone" id="phone" value="{{ old('phone', $patientModel->phone ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="email">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $patientModel->email ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="address_line">Adresse</label>
        <input type="text" name="address_line" id="address_line" value="{{ old('address_line', $patientModel->address_line ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('address_line') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="grid gap-6 md:grid-cols-2 md:col-span-1">
        <div>
            <label class="text-sm font-medium text-slate-600" for="village">Village</label>
            <input type="text" name="village" id="village" value="{{ old('village', $patientModel->village ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
            @error('village') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600" for="district">District</label>
            <input type="text" name="district" id="district" value="{{ old('district', $patientModel->district ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
            @error('district') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="region">Région</label>
        <input type="text" name="region" id="region" value="{{ old('region', $patientModel->region ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('region') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="emergency_contact_name">Contact d’urgence</label>
        <input type="text" name="emergency_contact_name" id="emergency_contact_name" value="{{ old('emergency_contact_name', $patientModel->emergency_contact_name ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('emergency_contact_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="emergency_contact_phone">Téléphone d’urgence</label>
        <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" value="{{ old('emergency_contact_phone', $patientModel->emergency_contact_phone ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('emergency_contact_phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="md:col-span-2">
        <label class="text-sm font-medium text-slate-600" for="medical_history">Antécédents médicaux</label>
        <textarea name="medical_history" id="medical_history" rows="3" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">{{ old('medical_history', $patientModel->medical_history ?? '') }}</textarea>
        @error('medical_history') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="md:col-span-2">
        <label class="text-sm font-medium text-slate-600" for="psychosocial_notes">Notes psychosociales</label>
        <textarea name="psychosocial_notes" id="psychosocial_notes" rows="3" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">{{ old('psychosocial_notes', $patientModel->psychosocial_notes ?? '') }}</textarea>
        @error('psychosocial_notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="status">Statut du cas</label>
        <input type="text" name="status" id="status" value="{{ old('status', $patientModel->status ?? 'sous surveillance') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="is_reintegrated">Réinsertion socio-économique</label>
        <select name="is_reintegrated" id="is_reintegrated" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <option value="0" @selected(old('is_reintegrated', (int) ($patientModel->is_reintegrated ?? 0)) === 0)>En cours</option>
            <option value="1" @selected(old('is_reintegrated', (int) ($patientModel->is_reintegrated ?? 0)) === 1)>Réinséré(e)</option>
        </select>
        @error('is_reintegrated') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="reintegrated_at">Date de réinsertion</label>
        <input type="date" name="reintegrated_at" id="reintegrated_at" value="{{ old('reintegrated_at', $patientModel?->reintegrated_at?->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('reintegrated_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="gps_latitude">Latitude GPS</label>
        <input type="number" step="0.0000001" name="gps_latitude" id="gps_latitude" value="{{ old('gps_latitude', $patientModel->gps_latitude ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('gps_latitude') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="gps_longitude">Longitude GPS</label>
        <input type="number" step="0.0000001" name="gps_longitude" id="gps_longitude" value="{{ old('gps_longitude', $patientModel->gps_longitude ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('gps_longitude') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-8 flex items-center justify-end gap-3">
    <a href="{{ route('patients.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Annuler</a>
    <button type="submit" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-500">Enregistrer</button>
</div>
