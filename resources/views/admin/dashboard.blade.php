@extends('layouts.admin')

@section('title', 'Tableau de Bord Admin')
@section('header', 'Tableau de Bord')

@push('styles')
<style>
    /* Dashboard Stats Grid */
    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    
    @media (min-width: 768px) {
        .dashboard-stats {
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        position: relative;
        overflow: hidden;
    }
    
    @media (min-width: 768px) {
        .stat-card {
            border-radius: 16px;
            padding: 1.25rem;
        }
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
    }
    
    @media (min-width: 768px) {
        .stat-card::before {
            height: 4px;
        }
    }
    
    .stat-card.users::before { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
    .stat-card.campaigns::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
    .stat-card.validations::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .stat-card.conversions::before { background: linear-gradient(90deg, #10b981, #34d399); }
    
    .stat-card__icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
    }
    
    @media (min-width: 768px) {
        .stat-card__icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
    }
    
    .stat-card.users .stat-card__icon { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .stat-card.campaigns .stat-card__icon { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
    .stat-card.validations .stat-card__icon { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .stat-card.conversions .stat-card__icon { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    
    .stat-card__value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        line-height: 1;
    }
    
    @media (min-width: 768px) {
        .stat-card__value {
            font-size: 2rem;
        }
    }
    
    .stat-card__label {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.35rem;
    }
    
    @media (min-width: 768px) {
        .stat-card__label {
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
    }
    
    .stat-card__sub {
        font-size: 0.7rem;
        color: #9ca3af;
        margin-top: 0.2rem;
    }
    
    @media (min-width: 768px) {
        .stat-card__sub {
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
    }
    
    .stat-card__sub.positive { color: #10b981; }
    .stat-card__sub.negative { color: #ef4444; }
    
    /* Quick Actions */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    
    @media (min-width: 768px) {
        .quick-actions {
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
    }
    
    .quick-action {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        text-decoration: none;
        color: inherit;
        transition: all 0.2s;
        border: 2px solid transparent;
    }
    
    @media (min-width: 768px) {
        .quick-action {
            border-radius: 12px;
            padding: 1.25rem;
        }
    }
    
    .quick-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        border-color: var(--primary);
    }
    
    .quick-action__icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin: 0 auto 0.75rem;
    }
    
    @media (min-width: 768px) {
        .quick-action__icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            font-size: 1.5rem;
            margin: 0 auto 1rem;
        }
    }
    
    .quick-action__icon.danger { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .quick-action__icon.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .quick-action__icon.success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .quick-action__icon.primary { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
    
    .quick-action__title {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
        font-size: 0.8rem;
    }
    
    @media (min-width: 768px) {
        .quick-action__title {
            font-size: 0.9rem;
        }
    }
    
    .quick-action__badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 20px;
        height: 20px;
        background: #ef4444;
        color: white;
        font-size: 0.65rem;
        font-weight: 600;
        border-radius: 10px;
        padding: 0 6px;
    }
    
    @media (min-width: 768px) {
        .quick-action__badge {
            min-width: 24px;
            height: 24px;
            font-size: 0.75rem;
            border-radius: 12px;
            padding: 0 8px;
        }
    }
    
    /* Dashboard Cards */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    @media (min-width: 992px) {
        .dashboard-grid {
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
        }
    }
    
    .dashboard-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
    }
    
    @media (min-width: 768px) {
        .dashboard-card {
            border-radius: 16px;
        }
    }
    
    .dashboard-card__header {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    @media (min-width: 768px) {
        .dashboard-card__header {
            padding: 1rem 1.25rem;
        }
    }
    
    .dashboard-card__title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    @media (min-width: 768px) {
        .dashboard-card__title {
            font-size: 1rem;
        }
    }
    
    .dashboard-card__body {
        padding: 0;
    }
    
    /* Activity List */
    .activity-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.875rem 1rem;
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.2s;
    }
    
    @media (min-width: 768px) {
        .activity-item {
            gap: 1rem;
            padding: 1rem 1.25rem;
        }
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-item:hover {
        background: #f9fafb;
    }
    
    .activity-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
    }
    
    @media (min-width: 768px) {
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            font-size: 1rem;
        }
    }
    
    .activity-icon.user { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .activity-icon.campaign { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
    .activity-icon.conversion { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .activity-icon.validation { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    
    .activity-content {
        flex: 1;
        min-width: 0;
    }
    
    .activity-title {
        font-weight: 500;
        color: #1f2937;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 0.85rem;
    }
    
    @media (min-width: 768px) {
        .activity-title {
            font-size: 0.9rem;
        }
    }
    
    .activity-meta {
        font-size: 0.7rem;
        color: #9ca3af;
    }
    
    @media (min-width: 768px) {
        .activity-meta {
            font-size: 0.75rem;
        }
    }
    
    .activity-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
        border-radius: 6px;
        font-weight: 500;
    }
    
    .activity-badge.pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .activity-badge.completed { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .activity-badge.rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    
    /* Alerts Section */
    .alerts-section {
        margin-bottom: 2rem;
    }
    
    .alert-banner {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        border-left: 4px solid #f59e0b;
    }
    
    .alert-banner.danger {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-left-color: #ef4444;
    }
    
    .alert-banner__icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    .alert-banner.danger .alert-banner__icon {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }
    
    .alert-banner__content {
        flex: 1;
    }
    
    .alert-banner__title {
        font-weight: 600;
        color: #92400e;
    }
    
    .alert-banner.danger .alert-banner__title {
        color: #991b1b;
    }
    
    .alert-banner__text {
        font-size: 0.875rem;
        color: #a16207;
    }
    
    .alert-banner.danger .alert-banner__text {
        color: #b91c1c;
    }
    
    .alert-banner__action {
        flex-shrink: 0;
    }
    
    /* Money Stats */
    .money-stat {
        padding: 1.25rem;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .money-stat:last-child {
        border-bottom: none;
    }
    
    .money-stat__label {
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    
    .money-stat__value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
    }
    
    .money-stat__value.success { color: #10b981; }
    .money-stat__value.warning { color: #f59e0b; }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #9ca3af;
    }
    
    .empty-state i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
</style>
@endpush

@section('content')
<!-- Alerts Section -->
@if($pendingApprovals > 0 || $pendingValidations > 0 || $pendingConversions > 0)
<div class="alerts-section">
    @if($pendingApprovals > 0)
    <div class="alert-banner">
        <div class="alert-banner__icon">
            <i class="bi bi-megaphone"></i>
        </div>
        <div class="alert-banner__content">
            <div class="alert-banner__title">{{ $pendingApprovals }} campagne(s) en attente d'approbation</div>
            <div class="alert-banner__text">Des campagnes nécessitent votre validation avant publication</div>
        </div>
        <div class="alert-banner__action">
            <a href="{{ route('admin.campaigns.approvals.index') }}" class="btn btn--primary btn--sm">Voir</a>
        </div>
    </div>
    @endif
    
    @if($pendingConversions > 0)
    <div class="alert-banner danger">
        <div class="alert-banner__icon">
            <i class="bi bi-cash-coin"></i>
        </div>
        <div class="alert-banner__content">
            <div class="alert-banner__title">{{ $pendingConversions }} demande(s) de conversion en attente</div>
            <div class="alert-banner__text">{{ number_format($pendingPayout, 0) }} FCFA en attente de traitement</div>
        </div>
        <div class="alert-banner__action">
            <a href="{{ route('admin.conversions.index') }}" class="btn btn--danger btn--sm">Traiter</a>
        </div>
    </div>
    @endif
</div>
@endif

<!-- Stats Cards -->
<div class="dashboard-stats">
    <div class="stat-card users">
        <div class="stat-card__icon">
            <i class="bi bi-people"></i>
        </div>
        <div class="stat-card__value">{{ number_format($totalUsers) }}</div>
        <div class="stat-card__label">Utilisateurs</div>
        <div class="stat-card__sub positive">+{{ $newUsersToday }} aujourd'hui</div>
    </div>
    
    <div class="stat-card campaigns">
        <div class="stat-card__icon">
            <i class="bi bi-megaphone"></i>
        </div>
        <div class="stat-card__value">{{ $activeCampaigns }}</div>
        <div class="stat-card__label">Campagnes Actives</div>
        <div class="stat-card__sub">{{ $totalCampaigns }} au total</div>
    </div>
    
    <div class="stat-card validations">
        <div class="stat-card__icon">
            <i class="bi bi-clipboard-check"></i>
        </div>
        <div class="stat-card__value">{{ $pendingValidations }}</div>
        <div class="stat-card__label">En Attente</div>
        <div class="stat-card__sub">{{ $completedToday }} validées aujourd'hui</div>
    </div>
    
    <div class="stat-card conversions">
        <div class="stat-card__icon">
            <i class="bi bi-cash-stack"></i>
        </div>
        <div class="stat-card__value">{{ number_format($totalPaidOut, 0) }}</div>
        <div class="stat-card__label">FCFA Distribués</div>
        <div class="stat-card__sub">{{ number_format($totalPiecesDistributed) }} pièces totales</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <a href="{{ route('admin.validations.index') }}" class="quick-action">
        <div class="quick-action__icon warning">
            <i class="bi bi-clipboard-check"></i>
        </div>
        <div class="quick-action__title">Validations</div>
        @if($pendingValidations > 0)
        <span class="quick-action__badge">{{ $pendingValidations }}</span>
        @endif
    </a>
    
    <a href="{{ route('admin.conversions.index') }}" class="quick-action">
        <div class="quick-action__icon danger">
            <i class="bi bi-cash-coin"></i>
        </div>
        <div class="quick-action__title">Conversions</div>
        @if($pendingConversions > 0)
        <span class="quick-action__badge">{{ $pendingConversions }}</span>
        @endif
    </a>
    
    <a href="{{ route('admin.campaigns.approvals.index') }}" class="quick-action">
        <div class="quick-action__icon success">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="quick-action__title">Approbations</div>
        @if($pendingApprovals > 0)
        <span class="quick-action__badge">{{ $pendingApprovals }}</span>
        @endif
    </a>
    
    <a href="{{ route('admin.campaigns.create') }}" class="quick-action">
        <div class="quick-action__icon primary">
            <i class="bi bi-plus-lg"></i>
        </div>
        <div class="quick-action__title">Nouvelle Campagne</div>
    </a>
</div>

<!-- Dashboard Grid -->
<div class="dashboard-grid">
    <!-- Left Column -->
    <div>
        <!-- Recent Participations -->
        <div class="dashboard-card" style="margin-bottom: 1.5rem;">
            <div class="dashboard-card__header">
                <h3 class="dashboard-card__title">
                    <i class="bi bi-activity"></i> Participations Récentes
                </h3>
                <a href="{{ route('admin.validations.index') }}" class="btn btn--ghost btn--sm">Voir tout</a>
            </div>
            <div class="dashboard-card__body">
                @if($recentParticipations->count() > 0)
                <ul class="activity-list">
                    @foreach($recentParticipations as $participation)
                    <li class="activity-item">
                        <div class="activity-icon validation">
                            <i class="bi bi-person"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">{{ $participation->user->name ?? 'Utilisateur' }}</div>
                            <div class="activity-meta">{{ $participation->campaign->title ?? 'Campagne' }} • {{ $participation->created_at->diffForHumans() }}</div>
                        </div>
                        <span class="activity-badge {{ $participation->status }}">
                            {{ $participation->status === 'pending' ? 'En attente' : ($participation->status === 'completed' ? 'Validée' : 'Rejetée') }}
                        </span>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <p>Aucune participation récente</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Campaigns Needing Approval -->
        @if($campaignsNeedingApproval->count() > 0)
        <div class="dashboard-card">
            <div class="dashboard-card__header">
                <h3 class="dashboard-card__title">
                    <i class="bi bi-clock-history text-warning"></i> Campagnes à Approuver
                </h3>
                <a href="{{ route('admin.campaigns.approvals.index') }}" class="btn btn--ghost btn--sm">Voir tout</a>
            </div>
            <div class="dashboard-card__body">
                <ul class="activity-list">
                    @foreach($campaignsNeedingApproval as $campaign)
                    <li class="activity-item">
                        <div class="activity-icon campaign">
                            <i class="bi bi-megaphone"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">{{ $campaign->title }}</div>
                            <div class="activity-meta">par {{ $campaign->creator->name ?? 'Inconnu' }} • {{ $campaign->created_at->diffForHumans() }}</div>
                        </div>
                        <span class="activity-badge pending">En attente</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Right Column -->
    <div>
        <!-- Financial Summary -->
        <div class="dashboard-card" style="margin-bottom: 1.5rem;">
            <div class="dashboard-card__header">
                <h3 class="dashboard-card__title">
                    <i class="bi bi-wallet2"></i> Résumé Financier
                </h3>
            </div>
            <div class="dashboard-card__body">
                <div class="money-stat">
                    <div class="money-stat__label">Total Payé</div>
                    <div class="money-stat__value success">{{ number_format($totalPaidOut, 0) }} FCFA</div>
                </div>
                <div class="money-stat">
                    <div class="money-stat__label">En Attente de Paiement</div>
                    <div class="money-stat__value warning">{{ number_format($pendingPayout, 0) }} FCFA</div>
                </div>
                <div class="money-stat">
                    <div class="money-stat__label">Pièces Aujourd'hui</div>
                    <div class="money-stat__value">{{ number_format($piecesDistributedToday) }}</div>
                </div>
            </div>
        </div>
        
        <!-- Recent Users -->
        <div class="dashboard-card" style="margin-bottom: 1.5rem;">
            <div class="dashboard-card__header">
                <h3 class="dashboard-card__title">
                    <i class="bi bi-person-plus"></i> Nouveaux Utilisateurs
                </h3>
                <a href="{{ route('admin.users.index') }}" class="btn btn--ghost btn--sm">Voir tout</a>
            </div>
            <div class="dashboard-card__body">
                @if($recentUsers->count() > 0)
                <ul class="activity-list">
                    @foreach($recentUsers as $user)
                    <li class="activity-item">
                        <div class="activity-icon user">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" style="width: 100%; height: 100%; border-radius: 10px; object-fit: cover;">
                            @else
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">{{ $user->name }}</div>
                            <div class="activity-meta">{{ $user->created_at->diffForHumans() }}</div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="empty-state">
                    <i class="bi bi-people"></i>
                    <p>Aucun nouvel utilisateur</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Recent Conversions -->
        <div class="dashboard-card">
            <div class="dashboard-card__header">
                <h3 class="dashboard-card__title">
                    <i class="bi bi-arrow-left-right"></i> Conversions Récentes
                </h3>
                <a href="{{ route('admin.conversions.index') }}" class="btn btn--ghost btn--sm">Voir tout</a>
            </div>
            <div class="dashboard-card__body">
                @if($recentConversions->count() > 0)
                <ul class="activity-list">
                    @foreach($recentConversions as $conversion)
                    <li class="activity-item">
                        <div class="activity-icon conversion">
                            <i class="bi bi-cash"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">{{ number_format($conversion->cash_amount, 0) }} FCFA</div>
                            <div class="activity-meta">{{ $conversion->user->name ?? 'Utilisateur' }} • {{ $conversion->created_at->diffForHumans() }}</div>
                        </div>
                        <span class="activity-badge {{ $conversion->status }}">
                            @php
                                $statusLabels = [
                                    'pending' => 'En attente',
                                    'approved' => 'Approuvé',
                                    'processing' => 'En cours',
                                    'completed' => 'Payé',
                                    'rejected' => 'Rejeté'
                                ];
                            @endphp
                            {{ $statusLabels[$conversion->status] ?? $conversion->status }}
                        </span>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="empty-state">
                    <i class="bi bi-cash-stack"></i>
                    <p>Aucune conversion récente</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
