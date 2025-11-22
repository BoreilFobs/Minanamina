@extends('layouts.app')

@section('title', 'Paramètres du Système - Admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 mb-0">
            <i class="bi bi-gear-fill"></i> Paramètres du Système
        </h1>
        <p class="text-muted mb-0">Configurer les paramètres globaux de l'application</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i> 
        <strong>Erreur:</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Main Settings Form -->
        <div class="col-lg-8">
            <div class="card mb-4" style="border: 2px solid #0d6efd;">
                <div class="card-header text-white" style="background-color: #0d6efd;">
                    <h5 class="mb-0"><i class="bi bi-cash-coin"></i> Paramètres de Conversion</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update-all') }}" method="POST">
                        @csrf
                        
                        <!-- Conversion Rate -->
                        <div class="mb-4">
                            <label for="conversion_rate" class="form-label">
                                <i class="bi bi-currency-exchange"></i> 
                                <strong>Taux de Conversion</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">1 pièce =</span>
                                <input type="number" 
                                       class="form-control @error('conversion_rate') is-invalid @enderror" 
                                       id="conversion_rate" 
                                       name="conversion_rate" 
                                       step="0.0001" 
                                       min="0.0001"
                                       max="1000"
                                       value="{{ old('conversion_rate', Setting::get('conversion_rate', 0.001)) }}"
                                       required
                                       oninput="updatePreview()">
                                <span class="input-group-text">FCFA</span>
                                @error('conversion_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted d-block mt-1">
                                Valeur actuelle appliquée dans toute l'application
                            </small>
                            
                            <!-- Preview Calculation -->
                            <div class="alert alert-info mt-3">
                                <strong>Aperçu:</strong>
                                <div id="preview-calculations">
                                    <div>10,000 pièces = <strong id="preview-10k">10</strong> FCFA</div>
                                    <div>50,000 pièces = <strong id="preview-50k">50</strong> FCFA</div>
                                    <div>100,000 pièces = <strong id="preview-100k">100</strong> FCFA</div>
                                    <div>1,000,000 pièces = <strong id="preview-1m">1,000</strong> FCFA</div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Minimum Conversion Pieces -->
                        <div class="mb-4">
                            <label for="minimum_conversion_pieces" class="form-label">
                                <i class="bi bi-hash"></i>
                                <strong>Minimum de Pièces pour Conversion</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control @error('minimum_conversion_pieces') is-invalid @enderror" 
                                       id="minimum_conversion_pieces" 
                                       name="minimum_conversion_pieces" 
                                       step="100" 
                                       min="100"
                                       max="1000000"
                                       value="{{ old('minimum_conversion_pieces', Setting::get('minimum_conversion_pieces', 10000)) }}"
                                       required
                                       oninput="updateMinPreview()">
                                <span class="input-group-text">pièces</span>
                                @error('minimum_conversion_pieces')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted d-block mt-1">
                                Nombre minimum de pièces qu'un utilisateur doit avoir pour effectuer une conversion
                            </small>
                            <div class="alert alert-secondary mt-2">
                                <strong>Valeur minimale en FCFA:</strong> <span id="min-fcfa-preview">10</span> FCFA
                            </div>
                        </div>

                        <hr>

                        <!-- Conversion System Toggle -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-toggle-on"></i>
                                <strong>État du Système de Conversion</strong>
                            </label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       role="switch" 
                                       id="conversion_enabled" 
                                       name="conversion_enabled"
                                       value="1"
                                       {{ Setting::get('conversion_enabled', true) ? 'checked' : '' }}
                                       style="width: 3em; height: 1.5em;">
                                <label class="form-check-label" for="conversion_enabled">
                                    <span id="conversion-status-text">
                                        {{ Setting::get('conversion_enabled', true) ? 'Activé' : 'Désactivé' }}
                                    </span>
                                </label>
                            </div>
                            <small class="text-muted d-block mt-1">
                                Si désactivé, les utilisateurs ne pourront pas soumettre de nouvelles demandes de conversion
                            </small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save"></i> Enregistrer Tous les Paramètres
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Information Sidebar -->
        <div class="col-lg-4">
            <!-- Current Stats Card -->
            <div class="card mb-4" style="border: 2px solid #28a745;">
                <div class="card-header text-white" style="background-color: #28a745;">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informations</h5>
                </div>
                <div class="card-body">
                    <h6 class="text-muted mb-3">Taux de Conversion Actuel</h6>
                    <div class="text-center mb-4 p-3 bg-light rounded">
                        <div class="display-6 text-success">{{ number_format(Setting::get('conversion_rate', 0.001), 4) }}</div>
                        <small class="text-muted">FCFA par pièce</small>
                    </div>

                    <h6 class="text-muted mb-3">Minimum Requis</h6>
                    <div class="text-center mb-4 p-3 bg-light rounded">
                        <div class="h4 text-primary">{{ number_format(Setting::get('minimum_conversion_pieces', 10000)) }}</div>
                        <small class="text-muted">pièces</small>
                    </div>

                    <h6 class="text-muted mb-3">Système</h6>
                    <div class="text-center p-3 bg-light rounded">
                        @if(Setting::get('conversion_enabled', true))
                        <span class="badge bg-success" style="font-size: 1.2rem; padding: 0.5rem 1rem;">
                            <i class="bi bi-check-circle"></i> Activé
                        </span>
                        @else
                        <span class="badge bg-danger" style="font-size: 1.2rem; padding: 0.5rem 1rem;">
                            <i class="bi bi-x-circle"></i> Désactivé
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Important Notes Card -->
            <div class="card" style="border: 2px solid #ffc107;">
                <div class="card-header text-dark" style="background-color: #ffc107;">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Notes Importantes</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li class="mb-2">
                            <strong>Impact Immédiat:</strong> Les modifications prendront effet immédiatement pour toutes les nouvelles conversions.
                        </li>
                        <li class="mb-2">
                            <strong>Conversions en Cours:</strong> Les conversions déjà créées conserveront leur taux d'origine.
                        </li>
                        <li class="mb-2">
                            <strong>Cache:</strong> Le système met en cache les paramètres pendant 1 heure pour de meilleures performances.
                        </li>
                        <li>
                            <strong>Recommandation:</strong> Évitez de modifier le taux trop fréquemment pour maintenir la confiance des utilisateurs.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updatePreview() {
    const rate = parseFloat(document.getElementById('conversion_rate').value) || 0.001;
    
    document.getElementById('preview-10k').textContent = (10000 * rate).toLocaleString('fr-FR', {minimumFractionDigits: 0, maximumFractionDigits: 0});
    document.getElementById('preview-50k').textContent = (50000 * rate).toLocaleString('fr-FR', {minimumFractionDigits: 0, maximumFractionDigits: 0});
    document.getElementById('preview-100k').textContent = (100000 * rate).toLocaleString('fr-FR', {minimumFractionDigits: 0, maximumFractionDigits: 0});
    document.getElementById('preview-1m').textContent = (1000000 * rate).toLocaleString('fr-FR', {minimumFractionDigits: 0, maximumFractionDigits: 0});
    
    updateMinPreview();
}

function updateMinPreview() {
    const rate = parseFloat(document.getElementById('conversion_rate').value) || 0.001;
    const minPieces = parseInt(document.getElementById('minimum_conversion_pieces').value) || 10000;
    const minFcfa = minPieces * rate;
    
    document.getElementById('min-fcfa-preview').textContent = minFcfa.toLocaleString('fr-FR', {minimumFractionDigits: 0, maximumFractionDigits: 0});
}

// Update status text when toggling
document.getElementById('conversion_enabled').addEventListener('change', function() {
    const statusText = document.getElementById('conversion-status-text');
    statusText.textContent = this.checked ? 'Activé' : 'Désactivé';
});

// Initialize preview on page load
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
});
</script>
@endsection
