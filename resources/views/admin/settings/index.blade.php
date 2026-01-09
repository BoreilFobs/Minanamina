@extends('layouts.admin')

@section('title', 'Paramètres du Système')
@section('page-title', 'Paramètres')

@push('styles')
<style>
    .settings-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    @media (min-width: 1024px) {
        .settings-grid {
            grid-template-columns: 2fr 1fr;
        }
    }
    
    .settings-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
    }
    
    .settings-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #5a4fcf 100%);
        color: white;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .settings-header h5 {
        margin: 0;
        font-weight: 600;
    }
    
    .settings-body {
        padding: 1.5rem;
    }
    
    .setting-group {
        margin-bottom: 2rem;
    }
    
    .setting-group:last-child {
        margin-bottom: 0;
    }
    
    .setting-label {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .setting-label i {
        color: var(--primary-color);
    }
    
    .setting-description {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }
    
    .input-group-modern {
        display: flex;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.2s;
    }
    
    .input-group-modern:focus-within {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(107, 79, 187, 0.1);
    }
    
    .input-group-modern .input-prefix,
    .input-group-modern .input-suffix {
        background: #f9fafb;
        padding: 0.875rem 1rem;
        color: #6b7280;
        font-size: 0.9rem;
        white-space: nowrap;
    }
    
    .input-group-modern input {
        flex: 1;
        border: none;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        min-width: 0;
    }
    
    .input-group-modern input:focus {
        outline: none;
    }
    
    .preview-box {
        background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
        border-radius: 12px;
        padding: 1rem;
        margin-top: 1rem;
    }
    
    .preview-title {
        font-size: 0.8rem;
        font-weight: 600;
        color: #1e40af;
        margin-bottom: 0.75rem;
    }
    
    .preview-item {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        padding: 0.35rem 0;
        color: #1e3a8a;
    }
    
    .preview-item strong {
        font-weight: 600;
    }
    
    .toggle-section {
        background: #f9fafb;
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .toggle-info {
        flex: 1;
    }
    
    .toggle-label {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }
    
    .toggle-description {
        font-size: 0.8rem;
        color: #6b7280;
    }
    
    .form-switch .form-check-input {
        width: 56px;
        height: 30px;
        cursor: pointer;
    }
    
    .form-switch .form-check-input:checked {
        background-color: #10b981;
        border-color: #10b981;
    }
    
    .btn-save {
        background: linear-gradient(135deg, var(--primary-color) 0%, #5a4fcf 100%);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        font-size: 1rem;
    }
    
    .divider {
        height: 1px;
        background: #e5e7eb;
        margin: 1.5rem 0;
    }
    
    /* Info Sidebar */
    .info-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .info-header {
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .info-header.green {
        background: #d1fae5;
        color: #065f46;
    }
    
    .info-header.yellow {
        background: #fef3c7;
        color: #92400e;
    }
    
    .info-header h6 {
        margin: 0;
        font-weight: 600;
    }
    
    .info-body {
        padding: 1.25rem;
    }
    
    .current-stat {
        text-align: center;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 12px;
        margin-bottom: 1rem;
    }
    
    .current-stat:last-child {
        margin-bottom: 0;
    }
    
    .current-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .current-stat-label {
        font-size: 0.8rem;
        color: #6b7280;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .status-badge.active {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    
    .status-badge.inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    
    .notes-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .notes-list li {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.85rem;
        color: #4b5563;
    }
    
    .notes-list li:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .notes-list strong {
        color: #1f2937;
        display: block;
        margin-bottom: 0.25rem;
    }
    
    .alert-success {
        background: #d1fae5;
        border: none;
        color: #065f46;
        border-radius: 12px;
        padding: 1rem;
    }
    
    .alert-danger {
        background: #fee2e2;
        border: none;
        color: #991b1b;
        border-radius: 12px;
        padding: 1rem;
    }
</style>
@endpush

@section('content')
<div class="admin-page">
    <div class="admin-page__header">
        <h1 class="admin-page__title">Paramètres du Système</h1>
        <p class="admin-page__subtitle">Configurer les paramètres globaux de l'application</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success mb-4">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger mb-4">
        <i class="bi bi-exclamation-triangle me-2"></i>
        @foreach($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
    </div>
    @endif

    <div class="settings-grid">
        <!-- Main Settings -->
        <div>
            <div class="settings-card">
                <div class="settings-header">
                    <i class="bi bi-cash-coin"></i>
                    <h5>Paramètres de Conversion</h5>
                </div>
                <div class="settings-body">
                    <form action="{{ route('admin.settings.update-all') }}" method="POST">
                        @csrf

                        <!-- Conversion Rate -->
                        <div class="setting-group">
                            <label class="setting-label">
                                <i class="bi bi-currency-exchange"></i>
                                Taux de Conversion
                            </label>
                            <p class="setting-description">
                                Valeur d'une pièce en FCFA. Ce taux sera utilisé pour tous les calculs de conversion.
                            </p>
                            <div class="input-group-modern">
                                <span class="input-prefix">1 pièce =</span>
                                <input type="number" 
                                       id="conversion_rate" 
                                       name="conversion_rate" 
                                       step="0.0001" 
                                       min="0.0001"
                                       max="1000"
                                       value="{{ old('conversion_rate', Setting::get('conversion_rate', 0.001)) }}"
                                       required
                                       oninput="updatePreview()">
                                <span class="input-suffix">FCFA</span>
                            </div>
                            
                            <div class="preview-box">
                                <div class="preview-title">📊 Aperçu des conversions</div>
                                <div class="preview-item">
                                    <span>10,000 pièces</span>
                                    <strong id="preview-10k">10</strong> FCFA
                                </div>
                                <div class="preview-item">
                                    <span>50,000 pièces</span>
                                    <strong id="preview-50k">50</strong> FCFA
                                </div>
                                <div class="preview-item">
                                    <span>100,000 pièces</span>
                                    <strong id="preview-100k">100</strong> FCFA
                                </div>
                                <div class="preview-item">
                                    <span>1,000,000 pièces</span>
                                    <strong id="preview-1m">1,000</strong> FCFA
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <!-- Minimum Pieces -->
                        <div class="setting-group">
                            <label class="setting-label">
                                <i class="bi bi-hash"></i>
                                Minimum de Pièces
                            </label>
                            <p class="setting-description">
                                Nombre minimum de pièces nécessaires pour effectuer une conversion.
                            </p>
                            <div class="input-group-modern">
                                <input type="number" 
                                       id="minimum_conversion_pieces" 
                                       name="minimum_conversion_pieces" 
                                       step="100" 
                                       min="100"
                                       max="1000000"
                                       value="{{ old('minimum_conversion_pieces', Setting::get('minimum_conversion_pieces', 10000)) }}"
                                       required
                                       oninput="updateMinPreview()">
                                <span class="input-suffix">pièces</span>
                            </div>
                            <div class="preview-box">
                                <div class="preview-item">
                                    <span>Valeur minimale en FCFA:</span>
                                    <strong id="min-fcfa-preview">10</strong> FCFA
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <!-- System Toggle -->
                        <div class="setting-group">
                            <div class="toggle-section">
                                <div class="toggle-info">
                                    <div class="toggle-label">Système de Conversion</div>
                                    <div class="toggle-description">
                                        Activer ou désactiver les demandes de conversion
                                    </div>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="conversion_enabled" 
                                           name="conversion_enabled"
                                           value="1"
                                           {{ Setting::get('conversion_enabled', true) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-save mt-4">
                            <i class="bi bi-save"></i> Enregistrer les modifications
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Current Values -->
            <div class="info-card">
                <div class="info-header green">
                    <i class="bi bi-info-circle"></i>
                    <h6>Valeurs Actuelles</h6>
                </div>
                <div class="info-body">
                    <div class="current-stat">
                        <div class="current-stat-value">{{ number_format(Setting::get('conversion_rate', 0.001), 4) }}</div>
                        <div class="current-stat-label">FCFA par pièce</div>
                    </div>
                    <div class="current-stat">
                        <div class="current-stat-value">{{ number_format(Setting::get('minimum_conversion_pieces', 10000)) }}</div>
                        <div class="current-stat-label">Pièces minimum</div>
                    </div>
                    <div class="current-stat">
                        @if(Setting::get('conversion_enabled', true))
                            <span class="status-badge active">
                                <i class="bi bi-check-circle"></i> Système Activé
                            </span>
                        @else
                            <span class="status-badge inactive">
                                <i class="bi bi-x-circle"></i> Système Désactivé
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="info-card">
                <div class="info-header yellow">
                    <i class="bi bi-exclamation-triangle"></i>
                    <h6>Notes Importantes</h6>
                </div>
                <div class="info-body">
                    <ul class="notes-list">
                        <li>
                            <strong>Impact Immédiat</strong>
                            Les modifications prennent effet immédiatement pour toutes les nouvelles conversions.
                        </li>
                        <li>
                            <strong>Conversions en Cours</strong>
                            Les conversions existantes conservent leur taux d'origine.
                        </li>
                        <li>
                            <strong>Cache Système</strong>
                            Les paramètres sont mis en cache pendant 1 heure.
                        </li>
                        <li>
                            <strong>Recommandation</strong>
                            Évitez les modifications fréquentes pour maintenir la confiance.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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

document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
});
</script>
@endpush
