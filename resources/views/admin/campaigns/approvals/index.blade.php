@extends('layouts.admin')

@section('title', 'Approbation des Campagnes')
@section('page-title', 'Approbations')

@push('styles')
<style>
    .approval-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        border: 2px solid #f59e0b;
    }
    
    .approval-card__header {
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
    
    .approval-card__header h5 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .approval-card__body {
        padding: 1.5rem;
    }
    
    .campaign-image-preview {
        width: 100%;
        max-height: 300px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }
    
    .action-card {
        background: white;
        border-radius: 12px;
        border: 2px solid #10b981;
        overflow: hidden;
    }
    
    .action-card__header {
        padding: 0.75rem 1rem;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        font-weight: 600;
    }
    
    .action-card__body {
        padding: 1rem;
    }
    
    .info-card {
        background: white;
        border-radius: 12px;
        border: 2px solid #6366f1;
        overflow: hidden;
        margin-top: 1rem;
    }
    
    .info-card__header {
        padding: 0.75rem 1rem;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        font-weight: 600;
    }
    
    .info-card__body {
        padding: 1rem;
    }
    
    .reward-badge {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: #78350f;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .status-badge-dark {
        background: #1f2937;
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .btn--success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn--success:hover { color: white; }
    
    .btn--warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        border: none;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn--warning:hover { color: white; }
    
    .btn--danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn--danger:hover { color: white; }
    
    .btn--info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    
    .btn--info:hover { color: white; }
    
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
    
    .empty-state-card {
        background: white;
        border-radius: 16px;
        padding: 4rem 2rem;
        text-align: center;
    }
    
    .empty-state-card i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
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
    
    .modal-header.danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    .modal-header.warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="admin-page">
    <div class="admin-page__header d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="admin-page__title">Approbation des Campagnes</h1>
            <p class="admin-page__subtitle">Gérer les campagnes en attente d'approbation</p>
        </div>
        <a href="{{ route('admin.campaigns.index') }}" class="btn--ghost">
            <i class="bi bi-arrow-left"></i> Toutes les Campagnes
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert-modern success mb-4">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-modern error mb-4">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Pending Campaigns -->
    @forelse($pendingCampaigns as $campaign)
    <div class="approval-card">
        <div class="approval-card__header">
            <h5><i class="bi bi-clock-history"></i> {{ $campaign->title }}</h5>
            <span class="status-badge-dark">En attente d'approbation</span>
        </div>
        <div class="approval-card__body">
            <div class="row">
                <!-- Campaign Details -->
                <div class="col-lg-8">
                    @if($campaign->image)
                    <img src="{{ asset('storage/' . $campaign->image) }}" 
                         alt="{{ $campaign->title }}" 
                         class="campaign-image-preview">
                    @endif

                    <h6 class="fw-bold mb-2"><i class="bi bi-file-text text-primary"></i> Description</h6>
                    <p style="white-space: pre-wrap; color: #4b5563;">{{ $campaign->description }}</p>

                    <hr class="my-4">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-1">Lien CPA:</strong>
                            <a href="{{ $campaign->cpa_link }}" target="_blank" class="text-primary">
                                {{ Str::limit($campaign->cpa_link, 50) }} <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-1">Récompense:</strong>
                            <span class="reward-badge">
                                <i class="bi bi-coin"></i> {{ number_format($campaign->pieces_reward) }} pièces
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-1">Période:</strong>
                            <span class="fw-semibold">
                                Du {{ \Carbon\Carbon::parse($campaign->start_date)->format('d/m/Y') }}
                                au {{ \Carbon\Carbon::parse($campaign->end_date)->format('d/m/Y') }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-1">Créée par:</strong>
                            <span class="fw-semibold">{{ $campaign->creator->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    @if($campaign->validation_rules)
                    <hr class="my-4">
                    <h6 class="fw-bold mb-2"><i class="bi bi-check-square text-success"></i> Conditions de Validation</h6>
                    <p style="white-space: pre-wrap; color: #4b5563;">{{ $campaign->validation_rules }}</p>
                    @endif

                    @if($campaign->geographic_restrictions)
                    <hr class="my-4">
                    <h6 class="fw-bold mb-2"><i class="bi bi-geo-alt text-primary"></i> Restrictions Géographiques</h6>
                    <p>
                        @php
                            $restrictions = is_array($campaign->geographic_restrictions) 
                                ? $campaign->geographic_restrictions
                                : json_decode($campaign->geographic_restrictions, true) ?? [];
                        @endphp
                        @if(empty($restrictions))
                            <span class="text-muted">Tous les pays</span>
                        @else
                            {{ implode(', ', $restrictions) }}
                        @endif
                    </p>
                    @endif
                </div>

                <!-- Action Panel -->
                <div class="col-lg-4">
                    <div class="action-card">
                        <div class="action-card__header">
                            <i class="bi bi-gear"></i> Actions
                        </div>
                        <div class="action-card__body">
                            <!-- Approve Button -->
                            <form action="{{ route('admin.campaigns.approvals.approve', $campaign) }}" 
                                  method="POST" class="mb-3">
                                @csrf
                                <button type="submit" class="btn--success" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir approuver cette campagne?')">
                                    <i class="bi bi-check-circle"></i> Approuver et Publier
                                </button>
                            </form>

                            <!-- Request Modifications Button -->
                            <button type="button" class="btn--warning mb-3" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modificationsModal{{ $campaign->id }}">
                                <i class="bi bi-pencil-square"></i> Demander des Modifications
                            </button>

                            <!-- Reject Button -->
                            <button type="button" class="btn--danger mb-3" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#rejectModal{{ $campaign->id }}">
                                <i class="bi bi-x-circle"></i> Rejeter
                            </button>

                            <!-- View Details Button -->
                            <a href="{{ route('admin.campaigns.show', $campaign) }}" class="btn--info">
                                <i class="bi bi-eye"></i> Voir Détails Complets
                            </a>
                        </div>
                    </div>

                    <!-- Campaign Info -->
                    <div class="info-card">
                        <div class="info-card__header">
                            <i class="bi bi-info-circle"></i> Informations
                        </div>
                        <div class="info-card__body">
                            <small class="text-muted">Soumise le</small>
                            <p class="mb-3 fw-bold">{{ $campaign->updated_at->format('d/m/Y à H:i') }}</p>
                            
                            <small class="text-muted">Durée de la campagne</small>
                            <p class="mb-0 fw-bold">
                                {{ \Carbon\Carbon::parse($campaign->start_date)->diffInDays($campaign->end_date) }} jours
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal{{ $campaign->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                <form action="{{ route('admin.campaigns.approvals.reject', $campaign) }}" method="POST">
                    @csrf
                    <div class="modal-header danger">
                        <h5 class="modal-title"><i class="bi bi-x-circle"></i> Rejeter la Campagne</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="fw-bold">{{ $campaign->title }}</p>
                        <div class="alert-modern" style="background: rgba(245, 158, 11, 0.1); color: #92400e;">
                            <i class="bi bi-exclamation-triangle"></i> Le créateur sera notifié du rejet avec la raison fournie.
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="rejection_reason{{ $campaign->id }}" class="form-label fw-bold">
                                Raison du rejet <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="rejection_reason{{ $campaign->id }}" 
                                      name="rejection_reason" rows="4" required
                                      style="border-radius: 10px;"
                                      placeholder="Expliquez pourquoi cette campagne est rejetée..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Annuler</button>
                        <button type="submit" class="btn--danger" style="width: auto;">
                            <i class="bi bi-x-circle"></i> Confirmer le Rejet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modifications Modal -->
    <div class="modal fade" id="modificationsModal{{ $campaign->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                <form action="{{ route('admin.campaigns.approvals.request-modifications', $campaign) }}" method="POST">
                    @csrf
                    <div class="modal-header warning">
                        <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Demander des Modifications</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="fw-bold">{{ $campaign->title }}</p>
                        <div class="alert-modern" style="background: rgba(59, 130, 246, 0.1); color: #1e40af;">
                            <i class="bi bi-info-circle"></i> Le créateur pourra modifier la campagne et la soumettre à nouveau.
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="modification_request{{ $campaign->id }}" class="form-label fw-bold">
                                Modifications demandées <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="modification_request{{ $campaign->id }}" 
                                      name="modification_request" rows="4" required
                                      style="border-radius: 10px;"
                                      placeholder="Décrivez les modifications nécessaires..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Annuler</button>
                        <button type="submit" class="btn--warning" style="width: auto;">
                            <i class="bi bi-send"></i> Envoyer la Demande
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state-card">
        <i class="bi bi-inbox"></i>
        <h4 class="text-muted">Aucune campagne en attente</h4>
        <p class="text-muted">Toutes les campagnes ont été traitées</p>
        <a href="{{ route('admin.campaigns.index') }}" class="btn--info mt-3" style="width: auto; display: inline-flex;">
            <i class="bi bi-list"></i> Voir Toutes les Campagnes
        </a>
    </div>
    @endforelse

    <!-- Pagination -->
    @if($pendingCampaigns->hasPages())
    <div class="mt-4">
        {{ $pendingCampaigns->links() }}
    </div>
    @endif
</div>
@endsection
