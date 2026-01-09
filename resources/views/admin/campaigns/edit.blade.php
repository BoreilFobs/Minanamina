@extends('layouts.admin')

@section('title', 'Modifier Campagne')
@section('page-title', 'Modifier Campagne')

@section('content')
<div class="admin-page">
    <div class="admin-page__header d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="admin-page__title">Modifier la Campagne</h1>
            <p class="admin-page__subtitle">Mettre à jour les informations de la campagne</p>
        </div>
        <a href="{{ route('admin.campaigns.show', $campaign) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <!-- Errors -->
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5><i class="bi bi-exclamation-circle"></i> Erreurs de validation</h5>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('admin.campaigns.update', $campaign) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="card mb-4" style="border: 2px solid #0d6efd;">
            <div class="card-header text-white" style="background-color: #0d6efd;">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informations de Base</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="title" class="form-label" style="font-weight: 600;">
                            Titre de la Campagne <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="{{ old('title', $campaign->title) }}" required>
                    </div>

                    <div class="col-md-12">
                        <label for="description" class="form-label" style="font-weight: 600;">
                            Description Détaillée <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="5" required>{{ old('description', $campaign->description) }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <label for="image" class="form-label" style="font-weight: 600;">
                            Image de la Campagne
                        </label>
                        
                        @if($campaign->image)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $campaign->image) }}" 
                                 class="img-thumbnail" 
                                 style="max-height: 200px; border: 2px solid #0d6efd;">
                        </div>
                        @endif
                        
                        <input type="file" class="form-control" id="image" name="image" 
                               accept="image/jpeg,image/png,image/jpg,image/gif">
                        <small class="text-muted">Formats acceptés: JPG, PNG, GIF (Max: 2MB). Laissez vide pour conserver l'image actuelle.</small>
                        <div id="imagePreview" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campaign Details -->
        <div class="card mb-4" style="border: 2px solid #198754;">
            <div class="card-header text-white" style="background-color: #198754;">
                <h5 class="mb-0"><i class="bi bi-link-45deg"></i> Détails de la Campagne</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="cpa_link" class="form-label" style="font-weight: 600;">
                            Lien CPA/Affilié <span class="text-danger">*</span>
                        </label>
                        <input type="url" class="form-control" id="cpa_link" name="cpa_link" 
                               value="{{ old('cpa_link', $campaign->cpa_link) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="pieces_reward" class="form-label" style="font-weight: 600;">
                            Récompense (Pièces) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text" style="background-color: #ffc107;">
                                <i class="bi bi-coin"></i>
                            </span>
                            <input type="number" class="form-control" id="pieces_reward" 
                                   name="pieces_reward" value="{{ old('pieces_reward', $campaign->pieces_reward) }}" 
                                   min="1" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="start_date" class="form-label" style="font-weight: 600;">
                            Date de Début <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ old('start_date', $campaign->start_date?->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="end_date" class="form-label" style="font-weight: 600;">
                            Date de Fin <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ old('end_date', $campaign->end_date?->format('Y-m-d')) }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Settings -->
        <div class="card mb-4" style="border: 2px solid #6f42c1;">
            <div class="card-header text-white" style="background-color: #6f42c1;">
                <h5 class="mb-0"><i class="bi bi-gear"></i> Paramètres Avancés</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="validation_rules" class="form-label" style="font-weight: 600;">
                            Conditions de Validation
                        </label>
                        <textarea class="form-control" id="validation_rules" name="validation_rules" 
                                  rows="3">{{ old('validation_rules', $campaign->validation_rules) }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <label for="geographic_restrictions" class="form-label" style="font-weight: 600;">
                            Restrictions Géographiques
                        </label>
                        @php
                            $restrictions = is_array($campaign->geographic_restrictions) 
                                ? implode(',', $campaign->geographic_restrictions)
                                : (is_string($campaign->geographic_restrictions) 
                                    ? implode(',', json_decode($campaign->geographic_restrictions, true) ?? [])
                                    : '');
                        @endphp
                        <input type="text" class="form-control" id="geographic_restrictions" 
                               name="geographic_restrictions" value="{{ old('geographic_restrictions', $restrictions) }}">
                        <small class="text-muted">Codes pays ISO séparés par des virgules</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="{{ route('admin.campaigns.show', $campaign) }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Mettre à Jour
            </button>
        </div>
    </form>
</div>

<script>
// Image Preview
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div>
                    <strong class="text-muted">Nouvelle image:</strong>
                    <img src="${e.target.result}" 
                         class="img-thumbnail mt-2" 
                         style="max-height: 200px; border: 2px solid #0d6efd;">
                </div>
            `;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});

// Date validation
document.getElementById('end_date').addEventListener('change', function() {
    const startDate = new Date(document.getElementById('start_date').value);
    const endDate = new Date(this.value);
    
    if (endDate <= startDate) {
        alert('La date de fin doit être après la date de début!');
        this.value = '';
    }
});
</script>
@endsection
