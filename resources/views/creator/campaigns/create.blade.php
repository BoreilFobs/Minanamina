@extends('layouts.creator')

@section('title', 'Nouvelle Campagne - Créateur')
@section('header', 'Nouvelle Campagne')

@push('styles')
<style>
    .form-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        border: 1px solid #e5e7eb;
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
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    }
    
    .form-card__header.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .form-card__header.warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    
    .form-card__body {
        padding: 1.5rem;
    }
    
    .input-group-text {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        border: none;
        color: #78350f;
        font-weight: 600;
    }
    
    .image-preview {
        margin-top: 1rem;
    }
    
    .image-preview img {
        max-height: 200px;
        border-radius: 12px;
        border: 3px solid #4f46e5;
    }
    
    .step-indicator {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }
    
    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e5e7eb;
        color: #6b7280;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        transition: all 0.3s;
    }
    
    .step.active .step-number {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
    }
    
    .step.completed .step-number {
        background: #10b981;
        color: white;
    }
    
    .step-label {
        font-size: 0.8rem;
        color: #6b7280;
        font-weight: 500;
    }
    
    .step.active .step-label {
        color: #4f46e5;
        font-weight: 600;
    }
    
    .help-text {
        font-size: 0.8rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    
    .form-actions {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
        margin-top: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1">Créer une Nouvelle Campagne</h1>
        <p class="text-muted mb-0">Configurez votre nouvelle campagne CPA</p>
    </div>
    <a href="{{ route('creator.campaigns.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<!-- Step Indicator -->
<div class="step-indicator">
    <div class="step active">
        <div class="step-number">1</div>
        <span class="step-label">Informations</span>
    </div>
    <div class="step">
        <div class="step-number">2</div>
        <span class="step-label">Configuration</span>
    </div>
    <div class="step">
        <div class="step-number">3</div>
        <span class="step-label">Soumission</span>
    </div>
</div>

<form action="{{ route('creator.campaigns.store') }}" method="POST" enctype="multipart/form-data">
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
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" 
                           value="{{ old('title') }}" required
                           placeholder="Ex: Inscription à l'application XYZ">
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="help-text">Choisissez un titre clair et attractif pour votre campagne.</div>
                </div>

                <div class="col-md-12">
                    <label for="description" class="form-label">
                        Description Détaillée <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" 
                              rows="5" required
                              placeholder="Décrivez les détails de la campagne, ce que l'utilisateur doit faire...">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="help-text">Expliquez clairement les étapes que l'utilisateur doit suivre.</div>
                </div>

                <div class="col-md-12">
                    <label for="image" class="form-label">Image de la Campagne</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                    @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="help-text">Format recommandé: 800x400px, JPG ou PNG.</div>
                    <div id="imagePreview" class="image-preview" style="display: none;">
                        <img src="" alt="Preview" id="previewImg">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Campaign Settings -->
    <div class="form-card">
        <div class="form-card__header success">
            <i class="bi bi-gear"></i>
            <span>Configuration de la Campagne</span>
        </div>
        <div class="form-card__body">
            <div class="row g-3">
                <div class="col-md-12">
                    <label for="cpa_link" class="form-label">
                        Lien CPA <span class="text-danger">*</span>
                    </label>
                    <input type="url" class="form-control @error('cpa_link') is-invalid @enderror" id="cpa_link" name="cpa_link" 
                           value="{{ old('cpa_link') }}" required
                           placeholder="https://exemple.com/offre?ref=xxx">
                    @error('cpa_link')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="help-text">L'URL vers laquelle les utilisateurs seront redirigés.</div>
                </div>

                <div class="col-md-6">
                    <label for="pieces_reward" class="form-label">
                        Récompense en Pièces <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-coin"></i></span>
                        <input type="number" class="form-control @error('pieces_reward') is-invalid @enderror" id="pieces_reward" name="pieces_reward" 
                               value="{{ old('pieces_reward', 10) }}" required min="1">
                    </div>
                    @error('pieces_reward')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="help-text">Nombre de pièces que l'utilisateur recevra après validation.</div>
                </div>

                <div class="col-md-6">
                    <label for="validation_rules" class="form-label">Règles de Validation</label>
                    <input type="text" class="form-control @error('validation_rules') is-invalid @enderror" id="validation_rules" name="validation_rules" 
                           value="{{ old('validation_rules') }}"
                           placeholder="Ex: Inscription complète avec email vérifié">
                    @error('validation_rules')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="help-text">Critères pour valider une participation.</div>
                </div>

                <div class="col-md-6">
                    <label for="start_date" class="form-label">
                        Date de Début <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" 
                           value="{{ old('start_date', date('Y-m-d')) }}" required>
                    @error('start_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="end_date" class="form-label">
                        Date de Fin <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" 
                           value="{{ old('end_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                    @error('end_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="geographic_restrictions" class="form-label">Restrictions Géographiques</label>
                    <input type="text" class="form-control @error('geographic_restrictions') is-invalid @enderror" id="geographic_restrictions" name="geographic_restrictions" 
                           value="{{ old('geographic_restrictions') }}"
                           placeholder="Ex: FR, BE, CH (séparés par des virgules)">
                    @error('geographic_restrictions')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="help-text">Laissez vide pour toutes les régions, ou entrez les codes pays séparés par des virgules.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="alert alert-warning d-flex align-items-start gap-3 mb-4">
        <i class="bi bi-info-circle-fill fs-4"></i>
        <div>
            <strong>Information importante</strong>
            <p class="mb-0 mt-1">Votre campagne sera créée en tant que <strong>brouillon</strong>. Vous pourrez ensuite la soumettre pour approbation par un administrateur avant qu'elle ne soit publiée.</p>
        </div>
    </div>

    <!-- Actions -->
    <div class="form-actions">
        <a href="{{ route('creator.campaigns.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-x-lg"></i> Annuler
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Créer la Campagne
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
