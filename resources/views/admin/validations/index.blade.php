@extends('layouts.admin')

@section('title', 'Validation des Participations')
@section('page-title', 'Validations')

@push('styles')
<style>
    .stats-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        border-left: 4px solid;
    }
    
    .stat-card.pending {
        border-color: #f59e0b;
    }
    
    .stat-card.validated {
        border-color: #10b981;
    }
    
    .stat-card__icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
    
    .stat-card.pending .stat-card__icon { color: #f59e0b; }
    .stat-card.validated .stat-card__icon { color: #10b981; }
    
    .stat-card__value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
    }
    
    .stat-card__label {
        font-size: 0.85rem;
        color: #6b7280;
    }
    
    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    
    .data-table {
        background: white;
        border-radius: 16px;
        overflow: hidden;
    }
    
    .table-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .table-header h5 {
        margin: 0;
        font-weight: 600;
    }
    
    .table {
        margin: 0;
    }
    
    .table th {
        background: #f8fafc;
        border-bottom: 2px solid #e5e7eb;
        font-weight: 600;
        color: #374151;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
    }
    
    .table td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .table tbody tr:hover {
        background: #f9fafb;
    }
    
    .user-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .user-avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
    
    .reward-badge {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: #78350f;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .status-badge.pending {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    
    .btn--success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.85rem;
    }
    
    .btn--success:hover { color: white; }
    
    .btn--danger {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: none;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    
    .btn--danger:hover {
        background: #ef4444;
        color: white;
    }
    
    .btn--primary {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn--primary:hover { color: white; }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6b7280;
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .alert-modern {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .alert-modern.success {
        background: rgba(16, 185, 129, 0.1);
        color: #065f46;
    }
    
    .alert-modern.error {
        background: rgba(239, 68, 68, 0.1);
        color: #991b1b;
    }
    
    .alert-modern.warning {
        background: rgba(245, 158, 11, 0.1);
        color: #92400e;
    }
    
    .suspect-badge {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }
    
    .bulk-action-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        margin-top: 1.5rem;
    }
    
    .bulk-action-card__header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 1rem 1.25rem;
        font-weight: 600;
    }
    
    .bulk-action-card__body {
        padding: 1.25rem;
    }
    
    .modal-header.danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="admin-page">
    <!-- Alerts -->
    @if(session('success'))
    <div class="alert-modern success mb-4">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-modern error mb-4">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    @if(session('warning'))
    <div class="alert-modern warning mb-4">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span>{{ session('warning') }}</span>
    </div>
    @endif

    <div class="admin-page__header mb-4">
        <h1 class="admin-page__title">Validation des Participations</h1>
        <p class="admin-page__subtitle">Valider et attribuer les pièces aux participants</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-row">
        <div class="stat-card pending">
            <div class="stat-card__icon"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-card__value">{{ $stats['pending_count'] }}</div>
            <div class="stat-card__label">En Attente</div>
        </div>
        <div class="stat-card validated">
            <div class="stat-card__icon"><i class="bi bi-check-circle"></i></div>
            <div class="stat-card__value">{{ $stats['validated_today'] }}</div>
            <div class="stat-card__label">Validées Aujourd'hui</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.validations.index') }}" class="row g-3">
            <div class="col-md-5">
                <label for="search" class="form-label fw-semibold">Rechercher un utilisateur</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Nom ou téléphone...">
            </div>
            <div class="col-md-4">
                <label for="campaign_id" class="form-label fw-semibold">Campagne</label>
                <select class="form-select" id="campaign_id" name="campaign_id">
                    <option value="">Toutes les campagnes</option>
                    @foreach(\App\Models\Campaign::where('status', 'published')->get() as $campaign)
                    <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                        {{ $campaign->title }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn--primary w-100">
                    <i class="bi bi-funnel"></i> Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Participations List -->
    <div class="data-table">
        <div class="table-header">
            <h5><i class="bi bi-clipboard-check me-2"></i>Participations à Valider</h5>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Campagne</th>
                        <th>Récompense</th>
                        <th>Date Participation</th>
                        <th>Durée</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($participations as $participation)
                    <tr>
                        <td>
                            <div class="user-cell">
                                @if($participation->user->avatar)
                                <img src="{{ asset('storage/' . $participation->user->avatar) }}" 
                                     alt="{{ $participation->user->name }}" 
                                     class="user-avatar">
                                @else
                                <div class="user-avatar-placeholder">
                                    {{ strtoupper(substr($participation->user->name, 0, 1)) }}
                                </div>
                                @endif
                                <div>
                                    <strong>{{ $participation->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $participation->user->phone }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong>{{ Str::limit($participation->campaign->title, 30) }}</strong>
                        </td>
                        <td>
                            <span class="reward-badge">
                                <i class="bi bi-coin"></i> {{ number_format($participation->campaign->pieces_reward) }}
                            </span>
                        </td>
                        <td>
                            {{ $participation->started_at ? $participation->started_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td>
                            @if($participation->started_at)
                                @php
                                    $duration = $participation->started_at->diffInMinutes(now());
                                    $hours = floor($duration / 60);
                                    $minutes = $duration % 60;
                                @endphp
                                @if($hours > 0)
                                    {{ $hours }}h {{ $minutes }}min
                                @else
                                    {{ $minutes }}min
                                @endif
                                
                                @if($participation->started_at->diffInSeconds(now()) < 60)
                                    <span class="suspect-badge" title="Temps suspect">⚠️ Suspect</span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="status-badge pending">En attente</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <form action="{{ route('admin.validations.validate', $participation) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Valider cette participation et attribuer {{ number_format($participation->campaign->pieces_reward) }} pièces à {{ $participation->user->name }}?')">
                                    @csrf
                                    <button type="submit" class="btn--success" title="Valider">
                                        <i class="bi bi-check-circle"></i> Valider
                                    </button>
                                </form>
                                <button type="button" 
                                        class="btn--danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#rejectModal{{ $participation->id }}"
                                        title="Rejeter">
                                    <i class="bi bi-x-circle"></i> Rejeter
                                </button>
                            </div>
                            
                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $participation->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                                        <div class="modal-header danger">
                                            <h5 class="modal-title">Rejeter la Participation</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.validations.reject', $participation) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p><strong>Utilisateur:</strong> {{ $participation->user->name }}</p>
                                                <p><strong>Campagne:</strong> {{ $participation->campaign->title }}</p>
                                                
                                                <div class="mb-3">
                                                    <label for="rejection_reason{{ $participation->id }}" class="form-label fw-bold">
                                                        Raison du Rejet <span class="text-danger">*</span>
                                                    </label>
                                                    <textarea 
                                                        class="form-control" 
                                                        id="rejection_reason{{ $participation->id }}" 
                                                        name="rejection_reason" 
                                                        rows="4" 
                                                        required
                                                        minlength="10"
                                                        style="border-radius: 10px;"
                                                        placeholder="Expliquez pourquoi cette participation est rejetée..."></textarea>
                                                    <small class="text-muted">Minimum 10 caractères</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Annuler</button>
                                                <button type="submit" class="btn--danger" style="background: #ef4444; color: white;">
                                                    <i class="bi bi-x-circle"></i> Confirmer le Rejet
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p class="mt-2">Aucune participation en attente de validation</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($participations->hasPages())
        <div class="p-3 border-top">
            {{ $participations->links() }}
        </div>
        @endif
    </div>

    <!-- Bulk Actions -->
    @if($participations->count() > 0)
    <div class="bulk-action-card">
        <div class="bulk-action-card__header">
            <i class="bi bi-lightning me-2"></i>Actions Groupées
        </div>
        <div class="bulk-action-card__body">
            <form action="{{ route('admin.validations.bulk-validate') }}" method="POST" 
                  onsubmit="return confirm('Valider TOUTES les participations affichées ({{ $participations->count() }})?')">
                @csrf
                @foreach($participations as $p)
                    <input type="hidden" name="participation_ids[]" value="{{ $p->id }}">
                @endforeach
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 fw-semibold">Valider toutes les participations visibles à la fois</p>
                        <small class="text-muted">Cette action attribuera les pièces à tous les participants affichés ({{ $participations->count() }} participations)</small>
                    </div>
                    <button type="submit" class="btn--success" style="padding: 0.75rem 1.5rem; font-size: 1rem;">
                        <i class="bi bi-check-all"></i> Valider Tout ({{ $participations->count() }})
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
