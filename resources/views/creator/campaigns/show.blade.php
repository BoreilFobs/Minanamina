@extends('layouts.creator')

@section('title', $campaign->title . ' - Créateur')
@section('header', 'Détails de la Campagne')

@push('styles')
<style>
    .campaign-header {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        border: 1px solid #e5e7eb;
    }
    
    .campaign-banner {
        height: 200px;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .campaign-banner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .campaign-banner i {
        font-size: 4rem;
        color: rgba(255,255,255,0.3);
    }
    
    .campaign-status-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .status-draft { background: white; color: #6b7280; }
    .status-pending_review, .status-pending_approval { background: #fef3c7; color: #92400e; }
    .status-published { background: #d1fae5; color: #065f46; }
    .status-rejected { background: #fee2e2; color: #991b1b; }
    .status-paused { background: #fef3c7; color: #92400e; }
    .status-ended { background: #e5e7eb; color: #374151; }
    
    .campaign-info {
        padding: 1.5rem;
    }
    
    .campaign-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.5rem;
    }
    
    .campaign-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        color: #6b7280;
        font-size: 0.9rem;
    }
    
    .campaign-meta span {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
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
        text-align: center;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin: 0 auto 0.75rem;
    }
    
    .stat-icon.primary { background: rgba(79, 70, 229, 0.1); color: #4f46e5; }
    .stat-icon.success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .stat-icon.info { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
    }
    
    .stat-label {
        font-size: 0.8rem;
        color: #6b7280;
    }
    
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    
    .content-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }
    
    .content-card__header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        font-weight: 600;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .content-card__body {
        padding: 1.25rem;
    }
    
    .detail-row {
        display: flex;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .detail-row:last-child {
        border-bottom: none;
    }
    
    .detail-label {
        width: 180px;
        font-weight: 600;
        color: #6b7280;
        flex-shrink: 0;
    }
    
    .detail-value {
        flex: 1;
        color: #111827;
    }
    
    .participations-table {
        width: 100%;
    }
    
    .participations-table th {
        background: #f9fafb;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #6b7280;
        padding: 1rem;
    }
    
    .participations-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    
    .user-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-completed { background: #d1fae5; color: #065f46; }
    .badge-rejected { background: #fee2e2; color: #991b1b; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('creator.campaigns.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Retour aux campagnes
    </a>
</div>

<!-- Campaign Header -->
<div class="campaign-header">
    <div class="campaign-banner">
        @if($campaign->image)
        <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}">
        @else
        <i class="bi bi-megaphone"></i>
        @endif
        <span class="campaign-status-badge status-{{ $campaign->status }}">
            @switch($campaign->status)
                @case('draft') Brouillon @break
                @case('pending_review')
                @case('pending_approval') En attente d'approbation @break
                @case('published') Publié @break
                @case('rejected') Rejeté @break
                @case('paused') En pause @break
                @case('ended') Terminé @break
                @default {{ ucfirst($campaign->status) }}
            @endswitch
        </span>
    </div>
    <div class="campaign-info">
        <h1 class="campaign-title">{{ $campaign->title }}</h1>
        <div class="campaign-meta">
            <span><i class="bi bi-coin"></i> {{ $campaign->pieces_reward }} pièces</span>
            <span><i class="bi bi-calendar"></i> {{ $campaign->start_date->format('d/m/Y') }} - {{ $campaign->end_date->format('d/m/Y') }}</span>
            <span><i class="bi bi-clock"></i> Créée le {{ $campaign->created_at->format('d/m/Y à H:i') }}</span>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="bi bi-people"></i>
        </div>
        <div class="stat-value">{{ $stats['total_participants'] }}</div>
        <div class="stat-label">Participants</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="stat-value">{{ $stats['completed_participations'] }}</div>
        <div class="stat-label">Complétés</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="stat-value">{{ $stats['pending_participations'] }}</div>
        <div class="stat-label">En attente</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon info">
            <i class="bi bi-percent"></i>
        </div>
        <div class="stat-value">{{ $stats['conversion_rate'] }}%</div>
        <div class="stat-label">Taux de conversion</div>
    </div>
</div>

<!-- Action Buttons -->
<div class="action-buttons">
    <a href="{{ route('creator.campaigns.edit', $campaign) }}" class="btn btn-primary">
        <i class="bi bi-pencil"></i> Modifier
    </a>
    @if($campaign->status === 'draft')
    <form action="{{ route('creator.campaigns.submit-approval', $campaign) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-success">
            <i class="bi bi-send"></i> Soumettre pour approbation
        </button>
    </form>
    @endif
    <form action="{{ route('creator.campaigns.duplicate', $campaign) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-outline-secondary">
            <i class="bi bi-copy"></i> Dupliquer
        </button>
    </form>
</div>

<!-- Campaign Details -->
<div class="content-card">
    <div class="content-card__header">
        <i class="bi bi-info-circle"></i>
        Détails de la Campagne
    </div>
    <div class="content-card__body">
        <div class="detail-row">
            <div class="detail-label">Description</div>
            <div class="detail-value">{{ $campaign->description }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Lien CPA</div>
            <div class="detail-value">
                <a href="{{ $campaign->cpa_link }}" target="_blank" class="text-primary">
                    {{ $campaign->cpa_link }} <i class="bi bi-box-arrow-up-right"></i>
                </a>
            </div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Règles de validation</div>
            <div class="detail-value">{{ $campaign->validation_rules ?? 'Non spécifiées' }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Restrictions géographiques</div>
            <div class="detail-value">
                @if($campaign->geographic_restrictions)
                    @php
                        $restrictions = is_string($campaign->geographic_restrictions) 
                            ? json_decode($campaign->geographic_restrictions, true) 
                            : $campaign->geographic_restrictions;
                    @endphp
                    @if(is_array($restrictions))
                        {{ implode(', ', $restrictions) }}
                    @else
                        Toutes les régions
                    @endif
                @else
                    Toutes les régions
                @endif
            </div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Pièces distribuées</div>
            <div class="detail-value">
                <strong>{{ number_format($stats['total_pieces_distributed']) }}</strong> pièces au total
            </div>
        </div>
    </div>
</div>

<!-- Recent Participations -->
<div class="content-card">
    <div class="content-card__header">
        <i class="bi bi-people"></i>
        Participations Récentes
    </div>
    <div class="content-card__body p-0">
        @if($campaign->participations->count() > 0)
        <table class="participations-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Pièces</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campaign->participations->take(10) as $participation)
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar">
                                {{ strtoupper(substr($participation->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $participation->user->name ?? 'Utilisateur supprimé' }}</div>
                                <div class="text-muted small">{{ $participation->user->phone ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $participation->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="badge badge-{{ $participation->status }}">
                            @switch($participation->status)
                                @case('pending') En attente @break
                                @case('completed') Complété @break
                                @case('rejected') Rejeté @break
                                @default {{ ucfirst($participation->status) }}
                            @endswitch
                        </span>
                    </td>
                    <td>
                        @if($participation->status === 'completed')
                        <span class="text-success fw-semibold">+{{ $participation->pieces_earned }}</span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="text-center py-5">
            <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-2 mb-0">Aucune participation pour le moment</p>
        </div>
        @endif
    </div>
</div>
@endsection
