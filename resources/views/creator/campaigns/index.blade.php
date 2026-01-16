@extends('layouts.creator')

@section('title', 'Mes Campagnes - Créateur')
@section('header', 'Mes Campagnes')

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    
    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e5e7eb;
    }
    
    .campaigns-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    @media (min-width: 768px) {
        .campaigns-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (min-width: 1200px) {
        .campaigns-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    .campaign-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        transition: all 0.2s;
    }
    
    .campaign-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.1);
    }
    
    .campaign-image {
        height: 160px;
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    
    .campaign-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .campaign-image i {
        font-size: 3rem;
        color: #4f46e5;
    }
    
    .campaign-status {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.7rem;
    }
    
    .status-draft { background: white; color: #6b7280; }
    .status-pending_review, .status-pending_approval { background: #fef3c7; color: #92400e; }
    .status-published { background: #d1fae5; color: #065f46; }
    .status-rejected { background: #fee2e2; color: #991b1b; }
    .status-paused { background: #fef3c7; color: #92400e; }
    .status-ended { background: #e5e7eb; color: #374151; }
    
    .campaign-body {
        padding: 1.25rem;
    }
    
    .campaign-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.5rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .campaign-desc {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .campaign-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 12px;
        margin-bottom: 1rem;
    }
    
    .campaign-stat {
        text-align: center;
    }
    
    .campaign-stat-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
    }
    
    .campaign-stat-label {
        font-size: 0.7rem;
        color: #6b7280;
        text-transform: uppercase;
    }
    
    .campaign-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .campaign-actions .btn {
        flex: 1;
        padding: 0.625rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1.5rem;
    }
    
    .empty-state h3 {
        font-size: 1.25rem;
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
<div class="page-header">
    <h1 class="page-title">Mes Campagnes</h1>
    <a href="{{ route('creator.campaigns.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        Nouvelle Campagne
    </a>
</div>

<!-- Filters -->
<div class="filter-card">
    <form method="GET" action="{{ route('creator.campaigns.index') }}" class="row g-3">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select">
                <option value="">Tous les statuts</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                <option value="pending_review" {{ request('status') == 'pending_review' ? 'selected' : '' }}>En attente d'approbation</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publié</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>En pause</option>
                <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Terminé</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-funnel"></i> Filtrer
            </button>
        </div>
    </form>
</div>

<!-- Campaigns Grid -->
@if($campaigns->count() > 0)
<div class="campaigns-grid">
    @foreach($campaigns as $campaign)
    <div class="campaign-card">
        <div class="campaign-image">
            @if($campaign->image)
            <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}">
            @else
            <i class="bi bi-megaphone"></i>
            @endif
            <span class="campaign-status status-{{ $campaign->status }}">
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
                    @case('paused')
                        En pause
                        @break
                    @case('ended')
                        Terminé
                        @break
                    @default
                        {{ ucfirst($campaign->status) }}
                @endswitch
            </span>
        </div>
        <div class="campaign-body">
            <h3 class="campaign-title">{{ $campaign->title }}</h3>
            <p class="campaign-desc">{{ Str::limit($campaign->description, 100) }}</p>
            
            <div class="campaign-stats">
                <div class="campaign-stat">
                    <div class="campaign-stat-value">{{ $campaign->pieces_reward }}</div>
                    <div class="campaign-stat-label">Pièces</div>
                </div>
                <div class="campaign-stat">
                    <div class="campaign-stat-value">{{ $campaign->participations()->count() }}</div>
                    <div class="campaign-stat-label">Participants</div>
                </div>
                <div class="campaign-stat">
                    <div class="campaign-stat-value">{{ $campaign->participations()->where('status', 'completed')->count() }}</div>
                    <div class="campaign-stat-label">Complétés</div>
                </div>
            </div>
            
            <div class="campaign-actions">
                <a href="{{ route('creator.campaigns.show', $campaign) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-eye"></i> Voir
                </a>
                <a href="{{ route('creator.campaigns.edit', $campaign) }}" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4">
    {{ $campaigns->withQueryString()->links() }}
</div>
@else
<div class="empty-state">
    <i class="bi bi-megaphone"></i>
    <h3>Aucune campagne trouvée</h3>
    <p>Vous n'avez pas encore créé de campagne ou aucune campagne ne correspond à vos critères.</p>
    <a href="{{ route('creator.campaigns.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        Créer ma première campagne
    </a>
</div>
@endif
@endsection
