@extends('layouts.creator')

@section('title', 'Tableau de Bord - Créateur')
@section('header', 'Tableau de Bord')

@push('styles')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
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
        position: relative;
    }
    
    .welcome-banner p {
        opacity: 0.9;
        margin-bottom: 1.5rem;
        font-size: 1rem;
        position: relative;
    }
    
    .welcome-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        position: relative;
    }
    
    .btn-welcome {
        background: white;
        color: #4f46e5;
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
        color: #4f46e5;
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
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }
    
    .stat-icon.primary { background: rgba(79, 70, 229, 0.1); color: #4f46e5; }
    .stat-icon.success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .stat-icon.info { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    
    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111827;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 0.85rem;
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
        padding: 1.5rem;
        text-align: center;
        text-decoration: none;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
    }
    
    .quick-action:hover {
        border-color: #4f46e5;
        background: #f5f3ff;
        transform: translateY(-2px);
    }
    
    .quick-action i {
        font-size: 2rem;
        color: #4f46e5;
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
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }
    
    .campaign-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.2s;
    }
    
    .campaign-item:last-child {
        border-bottom: none;
    }
    
    .campaign-item:hover {
        background: #f9fafb;
    }
    
    .campaign-image {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
    }
    
    .campaign-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .campaign-image i {
        font-size: 1.5rem;
        color: #4f46e5;
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
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.8rem;
        color: #6b7280;
    }
    
    .campaign-meta span {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .campaign-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .badge {
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.7rem;
    }
    
    .badge-draft { background: #f3f4f6; color: #6b7280; }
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-published { background: #d1fae5; color: #065f46; }
    .badge-rejected { background: #fee2e2; color: #991b1b; }
    .badge-ended { background: #e5e7eb; color: #374151; }
    
    .btn-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: white;
        color: #6b7280;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        text-decoration: none;
    }
    
    .btn-icon:hover {
        background: #f3f4f6;
        color: #374151;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }
    
    .empty-state h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        color: #6b7280;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<!-- Welcome Banner -->
<div class="welcome-banner">
    <h1>Bienvenue, {{ Auth::user()->name }}! 👋</h1>
    <p>Gérez vos campagnes et suivez leurs performances depuis votre espace créateur.</p>
    <div class="welcome-actions">
        <a href="{{ route('creator.campaigns.create') }}" class="btn-welcome">
            <i class="bi bi-plus-lg"></i>
            Créer une Campagne
        </a>
        <a href="{{ route('creator.analytics') }}" class="btn-welcome-outline">
            <i class="bi bi-graph-up"></i>
            Voir les Statistiques
        </a>
    </div>
</div>

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
        <div class="stat-icon info">
            <i class="bi bi-people"></i>
        </div>
        <div class="stat-value">{{ number_format($stats['total_participants']) }}</div>
        <div class="stat-label">Participants</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="bi bi-coin"></i>
        </div>
        <div class="stat-value">{{ number_format($stats['total_pieces_distributed']) }}</div>
        <div class="stat-label">Pièces Distribuées</div>
    </div>
</div>

<!-- Quick Actions -->
<h2 class="section-title mb-3">
    <i class="bi bi-lightning-charge"></i>
    Actions Rapides
</h2>
<div class="quick-actions">
    <a href="{{ route('creator.campaigns.create') }}" class="quick-action">
        <i class="bi bi-plus-circle"></i>
        <span>Nouvelle Campagne</span>
    </a>
    <a href="{{ route('creator.campaigns.index') }}" class="quick-action">
        <i class="bi bi-collection"></i>
        <span>Mes Campagnes</span>
    </a>
    <a href="{{ route('creator.participations') }}" class="quick-action">
        <i class="bi bi-people"></i>
        <span>Participations</span>
        @if($stats['pending_participations'] > 0)
        <span class="badge bg-danger ms-1">{{ $stats['pending_participations'] }}</span>
        @endif
    </a>
    <a href="{{ route('creator.analytics') }}" class="quick-action">
        <i class="bi bi-bar-chart"></i>
        <span>Statistiques</span>
    </a>
</div>

<!-- Recent Campaigns -->
<div class="section-header">
    <h2 class="section-title">
        <i class="bi bi-clock-history"></i>
        Campagnes Récentes
    </h2>
    <a href="{{ route('creator.campaigns.index') }}" class="btn btn-outline-secondary btn-sm">
        Voir tout <i class="bi bi-arrow-right"></i>
    </a>
</div>

<div class="campaigns-list">
    @forelse($campaigns as $campaign)
    <div class="campaign-item">
        <div class="campaign-image">
            @if($campaign->image)
            <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}">
            @else
            <i class="bi bi-megaphone"></i>
            @endif
        </div>
        <div class="campaign-info">
            <div class="campaign-title">{{ $campaign->title }}</div>
            <div class="campaign-meta">
                <span><i class="bi bi-coin"></i> {{ $campaign->pieces_reward }} pièces</span>
                <span><i class="bi bi-calendar"></i> {{ $campaign->created_at->format('d/m/Y') }}</span>
                <span><i class="bi bi-people"></i> {{ $campaign->participations_count ?? $campaign->participations()->count() }} participants</span>
            </div>
        </div>
        <div class="campaign-actions">
            <span class="badge badge-{{ $campaign->status }}">
                @switch($campaign->status)
                    @case('draft')
                        Brouillon
                        @break
                    @case('pending_review')
                    @case('pending_approval')
                        En attente
                        @break
                    @case('published')
                        Publié
                        @break
                    @case('rejected')
                        Rejeté
                        @break
                    @default
                        {{ ucfirst($campaign->status) }}
                @endswitch
            </span>
            <a href="{{ route('creator.campaigns.show', $campaign) }}" class="btn-icon" title="Voir">
                <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('creator.campaigns.edit', $campaign) }}" class="btn-icon" title="Modifier">
                <i class="bi bi-pencil"></i>
            </a>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="bi bi-megaphone"></i>
        <h3>Aucune campagne</h3>
        <p>Vous n'avez pas encore créé de campagne. Commencez maintenant!</p>
        <a href="{{ route('creator.campaigns.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            Créer ma première campagne
        </a>
    </div>
    @endforelse
</div>

@if($stats['pending_approval'] > 0)
<!-- Pending Approval Alert -->
<div class="alert alert-warning mt-4">
    <i class="bi bi-hourglass-split"></i>
    <div>
        <strong>{{ $stats['pending_approval'] }} campagne(s) en attente d'approbation.</strong>
        Un administrateur examinera bientôt vos campagnes.
    </div>
</div>
@endif
@endsection
