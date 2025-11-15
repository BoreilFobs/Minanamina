@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Approbation des Campagnes</h1>
            <p class="text-muted mb-0">Gérer les campagnes en attente d'approbation</p>
        </div>
        <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Toutes les Campagnes
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Pending Campaigns -->
    @forelse($pendingCampaigns as $campaign)
    <div class="card mb-4" style="border: 2px solid #ffc107;">
        <div class="card-header text-dark" style="background-color: #ffc107;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> {{ $campaign->title }}</h5>
                <span class="badge bg-dark">En attente d'approbation</span>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Campaign Details -->
                <div class="col-lg-8">
                    @if($campaign->image)
                    <img src="{{ asset('storage/' . $campaign->image) }}" 
                         alt="{{ $campaign->title }}" 
                         class="img-fluid rounded mb-3"
                         style="max-height: 300px; width: 100%; object-fit: cover; border: 2px solid #dee2e6;">
                    @endif

                    <h6 style="font-weight: 600;"><i class="bi bi-file-text"></i> Description</h6>
                    <p style="white-space: pre-wrap;">{{ $campaign->description }}</p>

                    <hr>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong style="font-weight: 600;">Lien CPA:</strong><br>
                            <a href="{{ $campaign->cpa_link }}" target="_blank" class="text-primary">
                                {{ Str::limit($campaign->cpa_link, 50) }} <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <strong style="font-weight: 600;">Récompense:</strong><br>
                            <span class="badge" style="background-color: #ffc107; color: #000; font-size: 16px;">
                                <i class="bi bi-coin"></i> {{ number_format($campaign->pieces_reward) }} pièces
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong style="font-weight: 600;">Période:</strong><br>
                            Du {{ \Carbon\Carbon::parse($campaign->start_date)->format('d/m/Y') }}
                            au {{ \Carbon\Carbon::parse($campaign->end_date)->format('d/m/Y') }}
                        </div>
                        <div class="col-md-6">
                            <strong style="font-weight: 600;">Créée par:</strong><br>
                            {{ $campaign->creator->name ?? 'N/A' }}
                        </div>
                    </div>

                    @if($campaign->validation_rules)
                    <hr>
                    <h6 style="font-weight: 600;"><i class="bi bi-check-square"></i> Conditions de Validation</h6>
                    <p style="white-space: pre-wrap;">{{ $campaign->validation_rules }}</p>
                    @endif

                    @if($campaign->geographic_restrictions)
                    <hr>
                    <h6 style="font-weight: 600;"><i class="bi bi-geo-alt"></i> Restrictions Géographiques</h6>
                    <p>
                        @php
                            $restrictions = is_array($campaign->geographic_restrictions) 
                                ? $campaign->geographic_restrictions
                                : json_decode($campaign->geographic_restrictions, true) ?? [];
                        @endphp
                        @if(empty($restrictions))
                            Tous les pays
                        @else
                            {{ implode(', ', $restrictions) }}
                        @endif
                    </p>
                    @endif
                </div>

                <!-- Action Panel -->
                <div class="col-lg-4">
                    <div class="card" style="border: 2px solid #198754;">
                        <div class="card-header text-white" style="background-color: #198754;">
                            <h6 class="mb-0"><i class="bi bi-gear"></i> Actions</h6>
                        </div>
                        <div class="card-body">
                            <!-- Approve Button -->
                            <form action="{{ route('admin.campaigns.approvals.approve', $campaign) }}" 
                                  method="POST" class="mb-3">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir approuver cette campagne?')">
                                    <i class="bi bi-check-circle"></i> Approuver et Publier
                                </button>
                            </form>

                            <!-- Request Modifications Button -->
                            <button type="button" class="btn btn-warning w-100 mb-3" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modificationsModal{{ $campaign->id }}">
                                <i class="bi bi-pencil-square"></i> Demander des Modifications
                            </button>

                            <!-- Reject Button -->
                            <button type="button" class="btn btn-danger w-100 mb-3" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#rejectModal{{ $campaign->id }}">
                                <i class="bi bi-x-circle"></i> Rejeter
                            </button>

                            <!-- View Details Button -->
                            <a href="{{ route('admin.campaigns.show', $campaign) }}" 
                               class="btn btn-info w-100">
                                <i class="bi bi-eye"></i> Voir Détails Complets
                            </a>
                        </div>
                    </div>

                    <!-- Campaign Info -->
                    <div class="card mt-3" style="border: 2px solid #0d6efd;">
                        <div class="card-header text-white" style="background-color: #0d6efd;">
                            <h6 class="mb-0"><i class="bi bi-info-circle"></i> Informations</h6>
                        </div>
                        <div class="card-body">
                            <small class="text-muted">Soumise le</small>
                            <p class="mb-2" style="font-weight: 600;">{{ $campaign->updated_at->format('d/m/Y à H:i') }}</p>
                            
                            <small class="text-muted">Durée de la campagne</small>
                            <p class="mb-0" style="font-weight: 600;">
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
            <div class="modal-content">
                <form action="{{ route('admin.campaigns.approvals.reject', $campaign) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="bi bi-x-circle"></i> Rejeter la Campagne</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>{{ $campaign->title }}</strong></p>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> Le créateur sera notifié du rejet avec la raison fournie.
                        </div>
                        <div class="mb-3">
                            <label for="rejection_reason{{ $campaign->id }}" class="form-label" style="font-weight: 600;">
                                Raison du rejet <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="rejection_reason{{ $campaign->id }}" 
                                      name="rejection_reason" rows="4" required
                                      placeholder="Expliquez pourquoi cette campagne est rejetée..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">
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
            <div class="modal-content">
                <form action="{{ route('admin.campaigns.approvals.request-modifications', $campaign) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Demander des Modifications</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>{{ $campaign->title }}</strong></p>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Le créateur pourra modifier la campagne et la soumettre à nouveau.
                        </div>
                        <div class="mb-3">
                            <label for="modification_request{{ $campaign->id }}" class="form-label" style="font-weight: 600;">
                                Modifications demandées <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="modification_request{{ $campaign->id }}" 
                                      name="modification_request" rows="4" required
                                      placeholder="Décrivez les modifications nécessaires..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-send"></i> Envoyer la Demande
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="card" style="border: 2px solid #0d6efd;">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox" style="font-size: 4rem; color: #dee2e6;"></i>
            <h4 class="mt-3 text-muted">Aucune campagne en attente</h4>
            <p class="text-muted">Toutes les campagnes ont été traitées</p>
            <a href="{{ route('admin.campaigns.index') }}" class="btn btn-primary mt-3">
                <i class="bi bi-list"></i> Voir Toutes les Campagnes
            </a>
        </div>
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
