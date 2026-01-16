@extends('layouts.admin')

@section('title', 'Nouvelle Campagne')
@section('page-title', 'Nouvelle Campagne')

@push('styles')
<style>
    .form-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .form-card__header {
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        color: white;
    }
    
    .form-card__header.primary {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    }
    
    .form-card__header.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .form-card__header.purple {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }
    
    .form-card__body {
        padding: 1.5rem;
    }
    
    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e5e7eb;
        padding: 0.75rem 1rem;
        transition: all 0.2s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    
    .input-group-text {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        border: none;
        color: #78350f;
        font-weight: 600;
    }
    
    .btn--primary {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }
    
    .btn--primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        color: white;
    }
    
    .btn--secondary {
        background: #f3f4f6;
        color: #4b5563;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    
    .btn--secondary:hover {
        background: #e5e7eb;
        color: #374151;
    }
    
    .btn--ghost {
        background: transparent;
        color: #6b7280;
        border: 2px solid #e5e7eb;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    
    .btn--ghost:hover {
        background: #f9fafb;
        color: #374151;
    }
    
    .alert-modern {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
    }
    
    .alert-modern.error {
        background: rgba(239, 68, 68, 0.1);
        color: #991b1b;
    }
    
    .image-preview {
        margin-top: 1rem;
    }
    
    .image-preview img {
        max-height: 200px;
        border-radius: 12px;
        border: 3px solid #6366f1;
    }
</style>
@endpush

@section('content')
<div class="admin-page">
    <div class="admin-page__header d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="admin-page__title">Créer une Nouvelle Campagne</h1>
            <p class="admin-page__subtitle">Configurer une nouvelle campagne CPA</p>
        </div>
        <a href="{{ route('admin.campaigns.index') }}" class="btn--ghost">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <!-- Errors -->
    @if($errors->any())
    <div class="alert-modern error mb-4">
        <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-circle"></i> Erreurs de validation</h6>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.campaigns.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Basic Information -->
        <div class="form-card">
            <div class="form-card__header primary">
                <i class="bi bi-info-circle"></i>
                <span>Informations de Base</span>
            </div>
            <div class="form-card__body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="title" class="form-label">
                            Titre de la Campagne <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="{{ old('title') }}" required
                               placeholder="Ex: Inscription à l'application XYZ">
                    </div>

                    <div class="col-md-12">
                        <label for="description" class="form-label">
                            Description Détaillée <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="5" required
                                  placeholder="Décrivez les détails de la campagne, ce que l'utilisateur doit faire...">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <label for="image" class="form-label">
                            Image de la Campagne
                        </label>
                        <input type="file" class="form-control" id="image" name="image" 
                               accept="image/jpeg,image/png,image/jpg,image/gif">
                        <small class="text-muted">Formats acceptés: JPG, PNG, GIF (Max: 2MB)</small>
                        <div id="imagePreview" class="image-preview"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campaign Details -->
        <div class="form-card">
            <div class="form-card__header success">
                <i class="bi bi-link-45deg"></i>
                <span>Détails de la Campagne</span>
            </div>
            <div class="form-card__body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="cpa_link" class="form-label">
                            Lien CPA/Affilié <span class="text-danger">*</span>
                        </label>
                        <input type="url" class="form-control" id="cpa_link" name="cpa_link" 
                               value="{{ old('cpa_link') }}" required
                               placeholder="https://example.com/affiliate-link">
                        <small class="text-muted">Le lien vers lequel les utilisateurs seront redirigés</small>
                    </div>

                    <div class="col-md-4">
                        <label for="pieces_reward" class="form-label">
                            Récompense (Pièces) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-coin"></i>
                            </span>
                            <input type="number" class="form-control" id="pieces_reward" 
                                   name="pieces_reward" value="{{ old('pieces_reward') }}" 
                                   min="1" required placeholder="100">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="start_date" class="form-label">
                            Date de Début <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ old('start_date', date('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="end_date" class="form-label">
                            Date de Fin <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ old('end_date') }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Settings -->
        <div class="form-card">
            <div class="form-card__header purple">
                <i class="bi bi-gear"></i>
                <span>Paramètres Avancés</span>
            </div>
            <div class="form-card__body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="validation_rules" class="form-label">
                            Conditions de Validation
                        </label>
                        <textarea class="form-control" id="validation_rules" name="validation_rules" 
                                  rows="3"
                                  placeholder="Ex: L'utilisateur doit s'inscrire et vérifier son email">{{ old('validation_rules') }}</textarea>
                        <small class="text-muted">Décrivez les conditions que l'utilisateur doit remplir pour être validé</small>
                    </div>

                    <div class="col-md-12">
                        <label for="geographic_restrictions" class="form-label">
                            Restrictions Géographiques
                        </label>
                        <input type="text" class="form-control" id="geographic_restrictions" 
                               name="geographic_restrictions" value="{{ old('geographic_restrictions') }}"
                               placeholder="CI,SN,BF,ML,TG,BJ (codes pays séparés par des virgules)">
                        <small class="text-muted">
                            Codes pays ISO (ex: CI=Côte d'Ivoire, SN=Sénégal, BF=Burkina Faso).
                            Laissez vide pour tous les pays.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-end gap-3 mb-4">
            <a href="{{ route('admin.campaigns.index') }}" class="btn--secondary">
                <i class="bi bi-x-circle"></i> Annuler
            </a>
            <button type="submit" class="btn--primary">
                <i class="bi bi-save"></i> Créer la Campagne
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
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
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

// Set minimum dates
const today = new Date().toISOString().split('T')[0];
document.getElementById('start_date').setAttribute('min', today);
document.getElementById('end_date').setAttribute('min', today);
</script>
@endsection
