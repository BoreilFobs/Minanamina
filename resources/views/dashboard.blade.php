@extends('layouts.app')

@section('title', 'Tableau de bord - Minanamina')

@push('styles')
<style>
    .stat-card {
        background-color: #0d6efd;
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        border: 2px solid #0a58ca;
    }
    
    .stat-card.success {
        background-color: #dc3545;
        border-color: #bb2d3b;
    }
    
    .stat-card.info {
        background-color: #0dcaf0;
        border-color: #0aa2c0;
    }
    
    .stat-card.warning {
        background-color: #198754;
        border-color: #146c43;
    }
    
    .campaign-card {
        transition: all 0.3s ease;
        border: 2px solid #dee2e6;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .campaign-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        border-color: #0d6efd;
    }
    
    .activity-item {
        border-left: 4px solid #0d6efd;
        padding-left: 1rem;
        margin-bottom: 1rem;
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 0 8px 8px 0;
    }
    
    .badge-lg {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        font-weight: 600;
    }
    
    .card-header {
        background-color: #0d6efd !important;
        color: white !important;
        border-bottom: 2px solid #0a58ca;
        font-weight: 600;
    }
    
    .table {
        color: #212529;
    }
    
    .table thead {
        background-color: #e9ecef;
        color: #212529;
        font-weight: 600;
    }
    
    .welcome-card {
        background-color: #6f42c1;
        border: 2px solid #59359a;
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        color: white;
    }
    
    .referral-code-box {
        background-color: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 8px;
        padding: 1rem;
    }
    
    .copy-btn {
        background-color: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.4);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .copy-btn:hover {
        background-color: rgba(255,255,255,0.3);
        border-color: rgba(255,255,255,0.6);
    }
    
    .copy-btn.copied {
        background-color: #198754;
        border-color: #146c43;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow welcome-card">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">
                                <i class="bi bi-emoji-smile"></i> Bon retour, {{ $user->name }}!
                            </h2>
                            <p class="mb-0 opacity-75">Voici votre aperçu pour aujourd'hui</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <div class="referral-code-box">
                                <div class="small opacity-75 mb-1">Code de parrainage</div>
                                <div class="d-flex align-items-center justify-content-md-end gap-2">
                                    <div class="h4 mb-0 font-monospace" id="referralCode">{{ $user->referral_code }}</div>
                                    <button type="button" class="copy-btn" onclick="copyReferralCode()" title="Copier le code">
                                        <i class="bi bi-clipboard" id="copyIcon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <!-- Pieces Balance -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small opacity-75">Solde de Pièces</div>
                        <h3 class="mb-0">{{ number_format($stats['pieces_balance'], 2) }}</h3>
                    </div>
                    <i class="bi bi-gem" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>

        <!-- Total Campaigns -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small opacity-75">Campagnes Totales</div>
                        <h3 class="mb-0">{{ $stats['total_campaigns'] }}</h3>
                        <small class="opacity-75">{{ $stats['completed_campaigns'] }} terminées</small>
                    </div>
                    <i class="bi bi-megaphone" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>

        <!-- Referrals -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small opacity-75">Parrainages Totaux</div>
                        <h3 class="mb-0">{{ $stats['total_referrals'] }}</h3>
                    </div>
                    <i class="bi bi-people" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>

        <!-- Referral Earnings -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small opacity-75">Gains de Parrainage</div>
                        <h3 class="mb-0">{{ number_format($stats['referral_earnings'], 2) }}</h3>
                    </div>
                    <i class="bi bi-cash-coin" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content Area -->
        <div class="col-lg-8 mb-4">
            <!-- Available Campaigns -->
            <div class="card border-0 shadow-sm">
                <div class="card-header py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-megaphone-fill"></i> Campagnes Disponibles
                    </h5>
                </div>
                <div class="card-body">
                    @if($availableCampaigns->count() > 0)
                        <div class="row g-3">
                            @foreach($availableCampaigns as $campaign)
                                <div class="col-12 col-md-6">
                                    <div class="card campaign-card h-100 shadow-sm">
                                        @if($campaign->image)
                                            <img src="{{ asset('storage/' . $campaign->image) }}" class="card-img-top" alt="{{ $campaign->title }}" style="height: 150px; object-fit: cover; border-radius: 12px 12px 0 0;">
                                        @endif
                                        <div class="card-body">
                                            <h6 class="card-title">{{ Str::limit($campaign->title, 40) }}</h6>
                                            <p class="card-text small text-muted">
                                                {{ Str::limit($campaign->description, 80) }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-success badge-lg">
                                                    <i class="bi bi-gem"></i> {{ $campaign->pieces_reward }} Pièces
                                                </span>
                                                <a href="#" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-play-circle"></i> Démarrer
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-primary">
                                <i class="bi bi-grid"></i> Voir Toutes les Campagnes
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                            <p class="mt-3">Aucune campagne disponible pour le moment</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Participations -->
            <div class="card border-0 shadow-sm">
                <div class="card-header py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Participations Récentes
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentParticipations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Campagne</th>
                                        <th>Statut</th>
                                        <th>Gagné</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentParticipations as $participation)
                                        <tr>
                                            <td>{{ Str::limit($participation->campaign->title, 30) }}</td>
                                            <td>
                                                @if($participation->status === 'completed')
                                                    <span class="badge bg-success">Terminée</span>
                                                @elseif($participation->status === 'active')
                                                    <span class="badge bg-primary">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($participation->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <i class="bi bi-gem text-warning"></i> {{ $participation->pieces_earned }}
                                            </td>
                                            <td class="small text-muted">{{ $participation->started_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">Aucune participation pour le moment</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Recent Activities -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-activity"></i> Activités Récentes
                    </h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @if($recentActivities->count() > 0)
                        @foreach($recentActivities as $activity)
                            <div class="activity-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>
                                            @if($activity->type === 'earned')
                                                <i class="bi bi-arrow-up-circle text-success"></i> Gagné
                                            @elseif($activity->type === 'converted')
                                                <i class="bi bi-arrow-down-circle text-danger"></i> Converti
                                            @elseif($activity->type === 'referral_bonus')
                                                <i class="bi bi-gift text-warning"></i> Bonus de Parrainage
                                            @else
                                                <i class="bi bi-circle text-secondary"></i> {{ ucfirst($activity->type) }}
                                            @endif
                                        </strong>
                                        <p class="small text-muted mb-1">{{ $activity->description }}</p>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-light text-dark">{{ $activity->amount > 0 ? '+' : '' }}{{ $activity->amount }}</span>
                                        <div class="small text-muted">{{ $activity->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">Aucune activité pour le moment</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge"></i> Actions Rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary">
                            <i class="bi bi-megaphone"></i> Parcourir les Campagnes
                        </a>
                        <a href="#" class="btn btn-outline-success">
                            <i class="bi bi-share"></i> Partager le Lien de Parrainage
                        </a>
                        <a href="#" class="btn btn-outline-warning">
                            <i class="bi bi-wallet2"></i> Demander un Paiement
                        </a>
                        <a href="#" class="btn btn-outline-info">
                            <i class="bi bi-question-circle"></i> Obtenir de l'Aide
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyReferralCode() {
    const code = document.getElementById('referralCode').textContent;
    const copyBtn = document.querySelector('.copy-btn');
    const copyIcon = document.getElementById('copyIcon');
    
    // Copy to clipboard
    navigator.clipboard.writeText(code).then(() => {
        // Change button appearance
        copyBtn.classList.add('copied');
        copyIcon.className = 'bi bi-check-lg';
        
        // Reset after 2 seconds
        setTimeout(() => {
            copyBtn.classList.remove('copied');
            copyIcon.className = 'bi bi-clipboard';
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy:', err);
        alert('Erreur lors de la copie du code');
    });
}
</script>
@endpush
