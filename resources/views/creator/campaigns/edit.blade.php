@extends('layouts.creator')

@section('title', 'Modifier ' . $campaign->title . ' - Créateur')
@section('header', 'Modifier la Campagne')

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
    
    .form-card__body {
        padding: 1.5rem;
    }
    
    .input-group-text {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        border: none;
        color: #78350f;
        font-weight: 600;
    }
    
    .current-image {
        margin-bottom: 1rem;
    }
    
    .current-image img {
        max-height: 150px;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
    }
    
    .image-preview {
        margin-top: 1rem;
    }
    
    .image-preview img {
        max-height: 200px;
        border-radius: 12px;
        border: 3px solid #4f46e5;
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
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .status-draft { background: #f3f4f6; color: #6b7280; }
    .status-pending_review, .status-pending_approval { background: #fef3c7; color: #92400e; }
    .status-published { background: #d1fae5; color: #065f46; }
    .status-rejected { background: #fee2e2; color: #991b1b; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1">Modifier la Campagne</h1>
        <p class="text-muted mb-0">
            <span class="status-badge status-{{ $campaign->status }}">
                @switch($campaign->status)
                    @case('draft') Brouillon @break
                    @case('pending_review')
                    @case('pending_approval') En attente d'approbation @break
                    @case('published') Publié @break
                    @case('rejected') Rejeté @break
                    @default {{ ucfirst($campaign->status) }}
                @endswitch
            </span>
        </p>
    </div>
    <a href="{{ route('creator.campaigns.show', $campaign) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<form action="{{ route('creator.campaigns.update', $campaign) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

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
                           value="{{ old('title', $campaign->title) }}" required>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="description" class="form-label">
                        Description Détaillée <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" 
                              rows="5" required>{{ old('description', $campaign->description) }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="image" class="form-label">Image de la Campagne</label>
                    @if($campaign->image)
                    <div class="current-image">
                        <p class="text-muted small mb-2">Image actuelle:</p>
                        <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}">
                    </div>
                    @endif
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                    @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="help-text">Laissez vide pour conserver l'image actuelle. Format recommandé: 800x400px.</div>
                    <div id="imagePreview" class="image-preview" style="display: none;">
                        <p class="text-muted small mb-2">Nouvelle image:</p>
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
                           value="{{ old('cpa_link', $campaign->cpa_link) }}" required>
                    @error('cpa_link')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="pieces_reward" class="form-label">
                        Récompense en Pièces <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-coin"></i></span>
                        <input type="number" class="form-control @error('pieces_reward') is-invalid @enderror" id="pieces_reward" name="pieces_reward" 
                               value="{{ old('pieces_reward', $campaign->pieces_reward) }}" required min="1">
                    </div>
                    @error('pieces_reward')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="validation_rules" class="form-label">Règles de Validation</label>
                    <input type="text" class="form-control @error('validation_rules') is-invalid @enderror" id="validation_rules" name="validation_rules" 
                           value="{{ old('validation_rules', $campaign->validation_rules) }}">
                    @error('validation_rules')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="start_date" class="form-label">
                        Date de Début <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" 
                           value="{{ old('start_date', $campaign->start_date->format('Y-m-d')) }}" required>
                    @error('start_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="end_date" class="form-label">
                        Date de Fin <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" 
                           value="{{ old('end_date', $campaign->end_date->format('Y-m-d')) }}" required>
                    @error('end_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="geographic_restrictions" class="form-label">Restrictions Géographiques</label>
                    @php
                        $restrictions = $campaign->geographic_restrictions;
                        if (is_string($restrictions)) {
                            $restrictions = json_decode($restrictions, true);
                        }
                        $restrictionString = is_array($restrictions) ? implode(',', $restrictions) : '';
                    @endphp
                    <input type="text" class="form-control @error('geographic_restrictions') is-invalid @enderror" id="geographic_restrictions" name="geographic_restrictions" 
                           value="{{ old('geographic_restrictions', $restrictionString) }}"
                           placeholder="Ex: FR, BE, CH (séparés par des virgules)">
                    @error('geographic_restrictions')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="help-text">Laissez vide pour toutes les régions.</div>
                </div>
            </div>
        </div>
    </div>

    @if($campaign->status === 'rejected' && $campaign->rejection_reason)
    <div class="alert alert-danger d-flex align-items-start gap-3 mb-4">
        <i class="bi bi-x-circle-fill fs-4"></i>
        <div>
            <strong>Raison du rejet</strong>
            <p class="mb-0 mt-1">{{ $campaign->rejection_reason }}</p>
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="form-actions">
        <div>
            <a href="{{ route('creator.campaigns.show', $campaign) }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-lg"></i> Annuler
            </a>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Enregistrer les modifications
            </button>
        </div>
    </div>
</form>

@if($campaign->status === 'draft')
<div class="mt-4">
    <hr>
    <h5 class="fw-bold text-danger">Zone de danger</h5>
    <p class="text-muted">Les actions ci-dessous sont irréversibles.</p>
    <form action="{{ route('creator.campaigns.destroy', $campaign) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette campagne? Cette action est irréversible.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger">
            <i class="bi bi-trash"></i> Supprimer cette campagne
        </button>
    </form>
</div>
@endif
@endsection

@push('scripts')
<script>
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
