@extends('layouts.modern')

@section('title', 'Mes Participations')

@section('content')
<!-- Header -->
<div class="page-header mb-4">
    <h4 class="mb-1 fw-bold">Mes Participations</h4>
    <p class="text-muted mb-0 small">Suivez l'état de vos participations</p>
</div>

<!-- Stats Cards -->
<div class="participation-stats mb-4">
    <div class="p-stat-card">
        <div class="p-stat-value">{{ $stats['total'] }}</div>
        <div class="p-stat-label">Total</div>
    </div>
    <div class="p-stat-card warning">
        <div class="p-stat-value">{{ $stats['pending'] }}</div>
        <div class="p-stat-label">En attente</div>
    </div>
    <div class="p-stat-card success">
        <div class="p-stat-value">{{ $stats['completed'] }}</div>
        <div class="p-stat-label">Validées</div>
    </div>
    <div class="p-stat-card primary">
        <div class="p-stat-value">{{ number_format($stats['total_earned'], 0) }}</div>
        <div class="p-stat-label">Pièces</div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="filter-tabs mb-4">
    <a href="{{ route('campaigns.my-participations') }}" 
       class="filter-tab {{ !request('status') ? 'active' : '' }}">
        Toutes
    </a>
    <a href="{{ route('campaigns.my-participations', ['status' => 'pending']) }}" 
       class="filter-tab {{ request('status') == 'pending' ? 'active' : '' }}">
        En attente
    </a>
    <a href="{{ route('campaigns.my-participations', ['status' => 'completed']) }}" 
       class="filter-tab {{ request('status') == 'completed' ? 'active' : '' }}">
        Validées
    </a>
    <a href="{{ route('campaigns.my-participations', ['status' => 'rejected']) }}" 
       class="filter-tab {{ request('status') == 'rejected' ? 'active' : '' }}">
        Rejetées
    </a>
</div>

<!-- Participations List -->
<div class="participations-list">
    @forelse($participations as $participation)
    <a href="{{ route('campaigns.show', $participation->campaign) }}" class="participation-card">
        <div class="participation-image">
            @if($participation->campaign->image)
                <img src="{{ asset('storage/' . $participation->campaign->image) }}" alt="{{ $participation->campaign->title }}">
            @else
                <div class="image-placeholder">
                    <i class="bi bi-megaphone"></i>
                </div>
            @endif
        </div>
        <div class="participation-content">
            <div class="participation-top">
                <h6 class="participation-title">{{ Str::limit($participation->campaign->title, 30) }}</h6>
                @if($participation->status == 'pending')
                    <span class="status-badge pending">En attente</span>
                @elseif($participation->status == 'completed')
                    <span class="status-badge completed">Validée</span>
                @elseif($participation->status == 'rejected')
                    <span class="status-badge rejected">Rejetée</span>
                @endif
            </div>
            <div class="participation-bottom">
                <span class="participation-date text-muted">
                    <i class="bi bi-calendar3"></i> {{ $participation->started_at->format('d/m/Y') }}
                </span>
                @if($participation->status == 'completed' && $participation->pieces_earned > 0)
                    <span class="participation-earned text-success">
                        +{{ number_format($participation->pieces_earned) }} <i class="bi bi-gem"></i>
                    </span>
                @else
                    <span class="participation-reward text-muted">
                        {{ number_format($participation->campaign->pieces_reward) }} <i class="bi bi-gem"></i>
                    </span>
                @endif
            </div>
        </div>
        <div class="participation-arrow">
            <i class="bi bi-chevron-right"></i>
        </div>
    </a>
    @empty
    <div class="empty-state">
        <div class="empty-icon">
            <i class="bi bi-inbox"></i>
        </div>
        <h5>Aucune participation</h5>
        <p class="text-muted">Participez à des campagnes pour gagner des pièces!</p>
        <a href="{{ route('campaigns.index') }}" class="btn btn-primary">
            <i class="bi bi-megaphone me-1"></i> Découvrir les Campagnes
        </a>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($participations->hasPages())
<div class="pagination-wrapper mt-4">
    {{ $participations->appends(request()->query())->links() }}
</div>
@endif
@endsection

@push('styles')
<style>
/* Stats Cards */
.participation-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

.p-stat-card {
    background: white;
    border-radius: 12px;
    padding: 1rem 0.75rem;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.p-stat-card.warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
}

.p-stat-card.success {
    background: linear-gradient(135deg, #d1e7dd 0%, #a3cfbb 100%);
}

.p-stat-card.primary {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    color: white;
}

.p-stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
}

.p-stat-label {
    font-size: 0.7rem;
    margin-top: 4px;
    opacity: 0.8;
}

/* Filter Tabs */
.filter-tabs {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 8px;
    -webkit-overflow-scrolling: touch;
}

.filter-tabs::-webkit-scrollbar {
    display: none;
}

.filter-tab {
    flex-shrink: 0;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    background: white;
    color: var(--dark);
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    border: 1px solid #e9ecef;
    transition: all 0.2s;
}

.filter-tab.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* Participation Card */
.participations-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.participation-card {
    display: flex;
    align-items: center;
    background: white;
    border-radius: 16px;
    padding: 0.75rem;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    transition: transform 0.2s;
}

.participation-card:active {
    transform: scale(0.98);
}

.participation-image {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
}

.participation-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.participation-content {
    flex: 1;
    padding: 0 0.75rem;
    min-width: 0;
}

.participation-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 8px;
    margin-bottom: 4px;
}

.participation-title {
    font-weight: 600;
    font-size: 0.9rem;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.status-badge {
    flex-shrink: 0;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.status-badge.pending {
    background: #fff3cd;
    color: #856404;
}

.status-badge.completed {
    background: #d1e7dd;
    color: #0f5132;
}

.status-badge.rejected {
    background: #f8d7da;
    color: #842029;
}

.participation-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.8rem;
}

.participation-earned {
    font-weight: 600;
}

.participation-arrow {
    color: var(--muted);
    padding-left: 0.5rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2rem;
    color: var(--muted);
}

@media (min-width: 768px) {
    .participation-stats {
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    
    .p-stat-card {
        padding: 1.5rem;
    }
    
    .p-stat-value {
        font-size: 2rem;
    }
    
    .p-stat-label {
        font-size: 0.85rem;
    }
}
</style>
@endpush
