@extends('layouts.admin')

@section('title', 'Tableau de Bord Créateur')
@section('page-title', 'Mon Espace')

@push('styles')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 50%;
        height: 200%;
        background: rgba(255,255,255,0.1);
        transform: rotate(15deg);
    }
    
    .welcome-banner h1 {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .welcome-banner p {
        opacity: 0.9;
        margin-bottom: 1.5rem;
        font-size: 1rem;
    }
    
    .welcome-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .btn-welcome {
        background: white;
        color: #6366f1;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-welcome:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        color: #6366f1;
    }
    
    .btn-welcome-outline {
        background: rgba(255,255,255,0.15);
        color: white;
        border: 2px solid rgba(255,255,255,0.3);
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-welcome-outline:hover {
        background: rgba(255,255,255,0.25);
        color: white;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    @media (min-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    
    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
    }
    
    .stat-icon.primary { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
    .stat-icon.success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .stat-icon.info { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 0.8rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    @media (min-width: 768px) {
        .quick-actions {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    
    .quick-action {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        text-align: center;
        text-decoration: none;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
    }
    
    .quick-action:hover {
        border-color: #6366f1;
        background: #faf9ff;
        transform: translateY(-2px);
    }
    
    .quick-action i {
        font-size: 1.75rem;
        color: #6366f1;
        margin-bottom: 0.75rem;
        display: block;
    }
    
    .quick-action span {
        font-weight: 600;
        color: #374151;
        font-size: 0.9rem;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .section-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .campaigns-list {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }
    
    .campaign-item {
        display: flex;
        align-items: center;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f3f4f6;
        gap: 1rem;
    }
    
    .campaign-item:last-child {
        border-bottom: none;
    }
    
    .campaign-item:hover {
        background: #f9fafb;
    }
    
    .campaign-image {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        object-fit: cover;
        flex-shrink: 0;
    }
    
    .campaign-image-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        flex-shrink: 0;
    }
    
    .campaign-info {
        flex: 1;
        min-width: 0;
    }
    
    .campaign-title {
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .campaign-meta {
        font-size: 0.8rem;
        color: #6b7280;
    }
    
    .campaign-status {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        flex-shrink: 0;
    }
    
    .campaign-status.draft { background: #f3f4f6; color: #6b7280; }
    .campaign-status.pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .campaign-status.published { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .campaign-status.paused { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .campaign-status.rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    
    .empty-campaigns {
        text-align: center;
        padding: 3rem;
        color: #6b7280;
    }
    
    .empty-campaigns i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .empty-campaigns h4 {
        margin-bottom: 0.5rem;
        color: #374151;
    }
    
    .workflow-card {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border: 1px solid #86efac;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .workflow-title {
        font-weight: 700;
        color: #166534;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .workflow-steps {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .workflow-step {
        background: white;
        border-radius: 25px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        color: #166534;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .workflow-step .step-num {
        width: 22px;
        height: 22px;
        background: #166534;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    .workflow-arrow {
        color: #86efac;
        font-size: 1.25rem;
    }
</style>
@endpush

@section('content')
<div class="admin-page">
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <h1>Bienvenue, {{ Auth::user()->name }}! 👋</h1>
        <p>Créez des campagnes engageantes et atteignez votre audience cible.</p>
        <div class="welcome-actions">
            <a href="{{ route('admin.campaigns.create') }}" class="btn-welcome">
                <i class="bi bi-plus-circle"></i>
                Créer une Campagne
            </a>
            <a href="{{ route('admin.campaigns.index') }}" class="btn-welcome-outline">
                <i class="bi bi-list-ul"></i>
                Voir mes Campagnes
            </a>
        </div>
    </div>

    <!-- Workflow Guide (for new users) -->
    @if($stats['total_campaigns'] == 0)
    <div class="workflow-card">
        <div class="workflow-title">
            <i class="bi bi-lightbulb"></i>
            Comment créer une campagne réussie
        </div>
        <div class="workflow-steps">
            <div class="workflow-step">
                <span class="step-num">1</span>
                Créer
            </div>
            <span class="workflow-arrow"><i class="bi bi-arrow-right"></i></span>
            <div class="workflow-step">
                <span class="step-num">2</span>
                Configurer
            </div>
            <span class="workflow-arrow"><i class="bi bi-arrow-right"></i></span>
            <div class="workflow-step">
                <span class="step-num">3</span>
                Soumettre
            </div>
            <span class="workflow-arrow"><i class="bi bi-arrow-right"></i></span>
            <div class="workflow-step">
                <span class="step-num">4</span>
                Approbation
            </div>
            <span class="workflow-arrow"><i class="bi bi-arrow-right"></i></span>
            <div class="workflow-step">
                <span class="step-num">5</span>
                Publication
            </div>
        </div>
    </div>
    @endif

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="bi bi-megaphone"></i>
            </div>
            <div class="stat-value">{{ $stats['total_campaigns'] }}</div>
            <div class="stat-label">Campagnes Totales</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="bi bi-broadcast"></i>
            </div>
            <div class="stat-value">{{ $stats['active_campaigns'] }}</div>
            <div class="stat-label">Campagnes Actives</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-value">{{ $stats['pending_approval'] }}</div>
            <div class="stat-label">En Attente</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['total_participants']) }}</div>
            <div class="stat-label">Participants</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="section-header">
        <h3 class="section-title">
            <i class="bi bi-lightning-charge"></i>
            Actions Rapides
        </h3>
    </div>
    <div class="quick-actions">
        <a href="{{ route('admin.campaigns.create') }}" class="quick-action">
            <i class="bi bi-plus-circle"></i>
            <span>Nouvelle Campagne</span>
        </a>
        <a href="{{ route('admin.campaigns.index') }}?status=draft" class="quick-action">
            <i class="bi bi-file-earmark-text"></i>
            <span>Brouillons ({{ $stats['draft_campaigns'] }})</span>
        </a>
        <a href="{{ route('admin.campaigns.index') }}?status=published" class="quick-action">
            <i class="bi bi-broadcast"></i>
            <span>Actives ({{ $stats['active_campaigns'] }})</span>
        </a>
        <a href="{{ route('admin.campaigns.index') }}" class="quick-action">
            <i class="bi bi-bar-chart"></i>
            <span>Toutes les Stats</span>
        </a>
    </div>

    <!-- Performance Summary -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="stat-card" style="height: 100%;">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-label">Participations Complétées</div>
                        <div class="stat-value">{{ number_format($stats['completed_participations']) }}</div>
                    </div>
                </div>
                <div class="progress" style="height: 8px; border-radius: 4px;">
                    @php
                        $completionRate = $stats['total_participants'] > 0 
                            ? round(($stats['completed_participations'] / $stats['total_participants']) * 100) 
                            : 0;
                    @endphp
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $completionRate }}%"></div>
                </div>
                <div class="text-muted mt-2" style="font-size: 0.8rem;">
                    Taux de complétion: {{ $completionRate }}%
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card" style="height: 100%;">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon warning">
                        <i class="bi bi-coin"></i>
                    </div>
                    <div>
                        <div class="stat-label">Pièces Distribuées</div>
                        <div class="stat-value">{{ number_format($stats['total_pieces_distributed']) }}</div>
                    </div>
                </div>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">
                    Total des pièces gagnées par les participants de vos campagnes.
                </p>
            </div>
        </div>
    </div>

    <!-- Recent Campaigns -->
    <div class="section-header">
        <h3 class="section-title">
            <i class="bi bi-clock-history"></i>
            Campagnes Récentes
        </h3>
        <a href="{{ route('admin.campaigns.index') }}" class="btn btn--ghost btn--sm">
            Voir tout
        </a>
    </div>
    <div class="campaigns-list">
        @forelse($campaigns as $campaign)
        <a href="{{ route('admin.campaigns.show', $campaign) }}" class="campaign-item text-decoration-none">
            @if($campaign->image)
            <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="campaign-image">
            @else
            <div class="campaign-image-placeholder">
                <i class="bi bi-image"></i>
            </div>
            @endif
            <div class="campaign-info">
                <div class="campaign-title">{{ $campaign->title }}</div>
                <div class="campaign-meta">
                    <i class="bi bi-coin"></i> {{ number_format($campaign->pieces_reward) }} pièces
                    · {{ $campaign->created_at->diffForHumans() }}
                </div>
            </div>
            <span class="campaign-status {{ $campaign->status }}">
                @switch($campaign->status)
                    @case('draft')
                        <i class="bi bi-file-earmark"></i> Brouillon
                        @break
                    @case('pending_approval')
                    @case('pending_review')
                        <i class="bi bi-hourglass"></i> En attente
                        @break
                    @case('published')
                        <i class="bi bi-broadcast"></i> Publiée
                        @break
                    @case('paused')
                        <i class="bi bi-pause-circle"></i> Pausée
                        @break
                    @case('rejected')
                        <i class="bi bi-x-circle"></i> Rejetée
                        @break
                    @default
                        {{ ucfirst($campaign->status) }}
                @endswitch
            </span>
        </a>
        @empty
        <div class="empty-campaigns">
            <i class="bi bi-megaphone"></i>
            <h4>Aucune campagne créée</h4>
            <p>Commencez par créer votre première campagne!</p>
            <a href="{{ route('admin.campaigns.create') }}" class="btn btn--primary mt-3">
                <i class="bi bi-plus-circle"></i>
                Créer ma première campagne
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
