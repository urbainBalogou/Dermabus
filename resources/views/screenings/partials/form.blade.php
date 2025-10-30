@php $screeningModel = $screening ?? null; @endphp

@csrf
@if(isset($screeningModel))
    @method('PUT')
@endif

<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label class="text-sm font-medium text-slate-600" for="patient_id">Patient *</label>
        <select name="patient_id" id="patient_id" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">Sélectionner un patient</option>
            @foreach ($patients as $id => $name)
                <option value="{{ $id }}" @selected((int) old('patient_id', $screeningModel->patient_id ?? $selectedPatient ?? '') === (int) $id)>{{ $name }}</option>
            @endforeach
        </select>
        @error('patient_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="screened_on">Date du dépistage *</label>
        <input type="date" name="screened_on" id="screened_on" value="{{ old('screened_on', $screeningModel?->screened_on?->format('Y-m-d')) ?? now()->format('Y-m-d') }}" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('screened_on') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="screening_location">Lieu du dépistage</label>
        <input type="text" name="screening_location" id="screening_location" value="{{ old('screening_location', $screeningModel->screening_location ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('screening_location') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="grid gap-6 md:grid-cols-2 md:col-span-1">
        <div>
            <label class="text-sm font-medium text-slate-600" for="gps_latitude">Latitude</label>
            <input type="number" step="0.0000001" name="gps_latitude" id="gps_latitude" value="{{ old('gps_latitude', $screeningModel->gps_latitude ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
            @error('gps_latitude') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-sm font-medium text-slate-600" for="gps_longitude">Longitude</label>
            <input type="number" step="0.0000001" name="gps_longitude" id="gps_longitude" value="{{ old('gps_longitude', $screeningModel->gps_longitude ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
            @error('gps_longitude') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="severity">Gravité *</label>
        <select name="severity" id="severity" required class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <option value="low" @selected(old('severity', $screeningModel->severity ?? 'low') === 'low')>Faible</option>
            <option value="medium" @selected(old('severity', $screeningModel->severity ?? 'low') === 'medium')>Moyenne</option>
            <option value="high" @selected(old('severity', $screeningModel->severity ?? 'low') === 'high')>Élevée</option>
        </select>
        @error('severity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="suspected_condition">Suspicion clinique</label>
        <input type="text" name="suspected_condition" id="suspected_condition" value="{{ old('suspected_condition', $screeningModel->suspected_condition ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('suspected_condition') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="risk_score">Score de risque</label>
        <input type="text" name="risk_score" id="risk_score" value="{{ old('risk_score', $screeningModel->risk_score ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('risk_score') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="requires_follow_up">Suivi requis</label>
        <select name="requires_follow_up" id="requires_follow_up" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <option value="0" @selected(old('requires_follow_up', (int) ($screeningModel->requires_follow_up ?? 0)) === 0)>Non</option>
            <option value="1" @selected(old('requires_follow_up', (int) ($screeningModel->requires_follow_up ?? 0)) === 1)>Oui</option>
        </select>
        @error('requires_follow_up') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="follow_up_on">Date de suivi</label>
        <input type="date" name="follow_up_on" id="follow_up_on" value="{{ old('follow_up_on', $screeningModel?->follow_up_on?->format('Y-m-d')) }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('follow_up_on') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="referral_facility">Structure de santé de référence</label>
        <input type="text" name="referral_facility" id="referral_facility" value="{{ old('referral_facility', $screeningModel->referral_facility ?? '') }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('referral_facility') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="referral_status">Statut du référencement</label>
        <select name="referral_status" id="referral_status" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <option value="pending" @selected(old('referral_status', $screeningModel->referral_status ?? 'pending') === 'pending')>En attente</option>
            <option value="in_progress" @selected(old('referral_status', $screeningModel->referral_status ?? 'pending') === 'in_progress')>En cours</option>
            <option value="completed" @selected(old('referral_status', $screeningModel->referral_status ?? 'pending') === 'completed')>Réalisé</option>
            <option value="declined" @selected(old('referral_status', $screeningModel->referral_status ?? 'pending') === 'declined')>Refusé</option>
        </select>
        @error('referral_status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="treatment_status">Statut du traitement</label>
        <select name="treatment_status" id="treatment_status" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
            <option value="pending" @selected(old('treatment_status', $screeningModel->treatment_status ?? 'pending') === 'pending')>À initier</option>
            <option value="in_progress" @selected(old('treatment_status', $screeningModel->treatment_status ?? 'pending') === 'in_progress')>En cours</option>
            <option value="completed" @selected(old('treatment_status', $screeningModel->treatment_status ?? 'pending') === 'completed')>Terminé</option>
            <option value="not_required" @selected(old('treatment_status', $screeningModel->treatment_status ?? 'pending') === 'not_required')>Non requis</option>
        </select>
        @error('treatment_status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="treatment_started_at">Début du traitement</label>
        <input type="datetime-local" name="treatment_started_at" id="treatment_started_at" value="{{ old('treatment_started_at', optional($screeningModel->treatment_started_at)->format('Y-m-d\TH:i')) }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('treatment_started_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-medium text-slate-600" for="treatment_completed_at">Fin du traitement</label>
        <input type="datetime-local" name="treatment_completed_at" id="treatment_completed_at" value="{{ old('treatment_completed_at', optional($screeningModel->treatment_completed_at)->format('Y-m-d\TH:i')) }}" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
        @error('treatment_completed_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="md:col-span-2">
        <label class="text-sm font-medium text-slate-600" for="symptoms">Symptômes observés</label>
        <textarea name="symptoms" id="symptoms" rows="3" placeholder="Séparer les symptômes par ligne (ex: lésions, œdèmes...)" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">{{ old('symptoms', is_array($screeningModel?->symptoms) ? implode("\n", $screeningModel->symptoms) : '') }}</textarea>
        @error('symptoms') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="md:col-span-2">
        <label class="text-sm font-medium text-slate-600" for="clinical_notes">Notes cliniques</label>
        <textarea name="clinical_notes" id="clinical_notes" rows="3" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">{{ old('clinical_notes', $screeningModel->clinical_notes ?? '') }}</textarea>
        @error('clinical_notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="md:col-span-2">
        <label class="text-sm font-medium text-slate-600" for="treatment_plan">Plan de traitement</label>
        <textarea name="treatment_plan" id="treatment_plan" rows="3" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">{{ old('treatment_plan', $screeningModel->treatment_plan ?? '') }}</textarea>
        @error('treatment_plan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="md:col-span-2">
        <label class="text-sm font-medium text-slate-600" for="community_notes">Notes communautaires</label>
        <textarea name="community_notes" id="community_notes" rows="3" class="mt-1 w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">{{ old('community_notes', $screeningModel->community_notes ?? '') }}</textarea>
        @error('community_notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-8 flex items-center justify-end gap-3">
    <a href="{{ route('screenings.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Annuler</a>
    <button type="submit" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-emerald-500">Enregistrer</button>
</div>
