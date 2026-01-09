@extends('layouts.modern')

@section('title', 'Campagnes')

@section('content')
<!-- Header -->
<div class="page-header mb-4">
    <h4 class="mb-1 fw-bold">Campagnes</h4>
    <p class="text-muted mb-0 small">Participez et gagnez des pièces!</p>
</div>

<!-- Search Bar -->
<form method="GET" action="{{ route('campaigns.index') }}" class="search-form mb-4">
    <div class="search-input-wrapper">
        <i class="bi bi-search search-icon"></i>
        <input type="text" 
               class="form-control search-input" 
               name="search" 
               value="{{ request('search') }}" 
               placeholder="Rechercher une campagne...">
        @if(request('search'))
        <a href="{{ route('campaigns.index') }}" class="search-clear">
            <i class="bi bi-x-circle-fill"></i>
        </a>
        @endif
    </div>
</form>

<!-- Filter Chips -->
<div class="filter-chips mb-4">
    <a href="{{ route('campaigns.index', ['sort' => 'latest'] + request()->except('sort')) }}" 
       class="filter-chip {{ request('sort', 'latest') == 'latest' ? 'active' : '' }}">
        <i class="bi bi-clock"></i> Récentes
    </a>
    <a href="{{ route('campaigns.index', ['sort' => 'reward_high'] + request()->except('sort')) }}" 
       class="filter-chip {{ request('sort') == 'reward_high' ? 'active' : '' }}">
        <i class="bi bi-arrow-up"></i> Récompense +
    </a>
    <a href="{{ route('campaigns.index', ['sort' => 'reward_low'] + request()->except('sort')) }}" 
       class="filter-chip {{ request('sort') == 'reward_low' ? 'active' : '' }}">
        <i class="bi bi-arrow-down"></i> Récompense -
    </a>
    <a href="{{ route('campaigns.index', ['sort' => 'ending_soon'] + request()->except('sort')) }}" 
       class="filter-chip {{ request('sort') == 'ending_soon' ? 'active' : '' }}">
        <i class="bi bi-hourglass-split"></i> Fin proche
    </a>
</div>

<!-- Campaigns List -->
<div class="campaigns-list">
    @forelse($campaigns as $campaign)
    <a href="{{ route('campaigns.show', $campaign) }}" class="campaign-card-modern">
        <div class="campaign-image">
            @if($campaign->image)
                <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}">
            @else
                <div class="campaign-placeholder">
                    <i class="bi bi-megaphone"></i>
                </div>
            @endif
            @php
                $daysLeft = \Carbon\Carbon::parse($campaign->end_date)->diffInDays(now());
            @endphp
            @if($daysLeft <= 3)
            <span class="campaign-badge urgent">
                <i class="bi bi-clock"></i> {{ $daysLeft }}j restants
            </span>
            @endif
        </div>
        <div class="campaign-info">
            <h6 class="campaign-name">{{ $campaign->title }}</h6>
            <p class="campaign-desc">{{ Str::limit($campaign->description, 60) }}</p>
            <div class="campaign-footer">
                <span class="campaign-reward-badge">
                    <i class="bi bi-gem"></i> {{ number_format($campaign->pieces_reward) }} pièces
                </span>
                <span class="campaign-arrow">
                    <i class="bi bi-chevron-right"></i>
                </span>
            </div>
        </div>
    </a>
    @empty
    <div class="empty-state">
        <div class="empty-icon">
            <i class="bi bi-inbox"></i>
        </div>
        <h5>Aucune campagne disponible</h5>
        <p class="text-muted">Revenez bientôt pour de nouvelles opportunités!</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($campaigns->hasPages())
<div class="pagination-wrapper mt-4">
    {{ $campaigns->appends(request()->query())->links() }}
</div>
@endif
@endsection

@push('styles')
<style>
/* Search */
.search-form {
    position: sticky;
    top: 0;
    z-index: 10;
    background: var(--light);
    padding: 0.5rem 0;
}

.search-input-wrapper {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
}

.search-input {
    padding-left: 2.75rem;
    padding-right: 2.5rem;
    border-radius: 50px;
    border: none;
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    height: 48px;
}

.search-input:focus {
    box-shadow: 0 2px 12px rgba(102, 126, 234, 0.2);
}

.search-clear {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
}

/* Filter Chips */
.filter-chips {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding-bottom: 8px;
    -webkit-overflow-scrolling: touch;
}

.filter-chips::-webkit-scrollbar {
    display: none;
}

.filter-chip {
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
    display: flex;
    align-items: center;
    gap: 6px;
}

.filter-chip.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.filter-chip:not(.active):hover {
    background: #f8f9fa;
}

/* Campaign Card */
.campaigns-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.campaign-card-modern {
    display: flex;
    background: white;
    border-radius: 16px;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    transition: transform 0.2s, box-shadow 0.2s;
}

.campaign-card-modern:active {
    transform: scale(0.98);
}

.campaign-image {
    width: 100px;
    min-width: 100px;
    height: 100px;
    position: relative;
}

.campaign-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.campaign-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
}

.campaign-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.campaign-badge.urgent {
    background: var(--danger);
    color: white;
}

.campaign-info {
    flex: 1;
    padding: 0.75rem 1rem;
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.campaign-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.campaign-desc {
    font-size: 0.8rem;
    color: var(--muted);
    margin-bottom: auto;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.campaign-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 0.5rem;
}

.campaign-reward-badge {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    color: #c44e1c;
}

.campaign-arrow {
    color: var(--muted);
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

/* Desktop Grid */
@media (min-width: 768px) {
    .campaigns-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    
    .campaign-card-modern {
        flex-direction: column;
    }
    
    .campaign-image {
        width: 100%;
        height: 150px;
    }
    
    .campaign-info {
        padding: 1rem;
    }
}

@media (min-width: 992px) {
    .campaigns-list {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>
@endpush
