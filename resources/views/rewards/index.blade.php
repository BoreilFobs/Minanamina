@extends('layouts.modern')

@section('title', 'Récompenses')

@section('content')
<!-- Header -->
<div class="page-header mb-4">
    <h4 class="mb-1 fw-bold">Récompenses</h4>
    <p class="text-muted mb-0 small">Gérez vos pièces et badges</p>
</div>

<!-- Balance Card -->
<div class="balance-card-main mb-4">
    <div class="balance-header">
        <span class="balance-label">Solde Actuel</span>
        <a href="{{ route('rewards.conversions') }}" class="history-link">
            <i class="bi bi-clock-history"></i>
        </a>
    </div>
    <div class="balance-value">
        <i class="bi bi-gem"></i>
        <span>{{ number_format($stats['current_balance']) }}</span>
    </div>
    <div class="balance-cash">
        ≈ {{ number_format($cashEquivalent ?? 0, 0) }} FCFA
    </div>
    <a href="{{ route('rewards.convert.form') }}" class="btn btn-light btn-lg w-100 mt-3">
        <i class="bi bi-arrow-repeat me-2"></i>Convertir en Cash
    </a>
</div>

<!-- Quick Stats -->
<div class="quick-stats-row mb-4">
    <div class="quick-stat">
        <div class="stat-icon-mini bg-success-subtle">
            <i class="bi bi-graph-up text-success"></i>
        </div>
        <div class="stat-detail">
            <div class="stat-number">{{ number_format($stats['lifetime_earnings']) }}</div>
            <div class="stat-text">Gains totaux</div>
        </div>
    </div>
    <div class="quick-stat">
        <div class="stat-icon-mini bg-info-subtle">
            <i class="bi bi-trophy text-info"></i>
        </div>
        <div class="stat-detail">
            <div class="stat-number">{{ $stats['total_campaigns_completed'] }}</div>
            <div class="stat-text">Campagnes</div>
        </div>
    </div>
    <div class="quick-stat">
        <div class="stat-icon-mini bg-warning-subtle">
            <i class="bi bi-award text-warning"></i>
        </div>
        <div class="stat-detail">
            <div class="stat-number">{{ $badgeStats['earned_badges'] ?? 0 }}/{{ $badgeStats['total_badges'] ?? 0 }}</div>
            <div class="stat-text">Badges</div>
        </div>
    </div>
</div>

<!-- Recent Badges -->
@if(isset($recentBadges) && $recentBadges->count() > 0)
<div class="section-header mb-3">
    <h6 class="mb-0 fw-bold"><i class="bi bi-star-fill text-warning"></i> Badges Récents</h6>
</div>
<div class="badges-scroll mb-4">
    @foreach($recentBadges as $badge)
    <div class="badge-card">
        <div class="badge-emoji">{{ $badge->icon }}</div>
        <div class="badge-name">{{ $badge->name }}</div>
        <div class="badge-date">{{ \Carbon\Carbon::parse($badge->pivot->awarded_at)->diffForHumans() }}</div>
    </div>
    @endforeach
</div>
@endif

<!-- All Badges -->
@if(isset($badgesWithProgress) && count($badgesWithProgress) > 0)
<div class="section-header mb-3">
    <h6 class="mb-0 fw-bold"><i class="bi bi-award"></i> Tous les Badges</h6>
</div>
<div class="badges-grid mb-4">
    @foreach($badgesWithProgress as $badgeData)
    <div class="badge-item {{ $badgeData['is_earned'] ? 'earned' : 'locked' }}" 
         title="{{ $badgeData['badge']->description ?? '' }}">
        <div class="badge-emoji">{{ $badgeData['badge']->icon }}</div>
        <div class="badge-name">{{ $badgeData['badge']->name }}</div>
        @if($badgeData['is_earned'])
            <span class="badge-status earned"><i class="bi bi-check"></i></span>
        @else
            <div class="badge-progress" title="{{ $badgeData['progress']['label'] ?? '' }}: {{ $badgeData['progress']['current'] ?? 0 }}/{{ $badgeData['progress']['required'] ?? 0 }}">
                <div class="progress-bar-mini" style="width: {{ $badgeData['progress']['percentage'] ?? 0 }}%"></div>
            </div>
            <div class="badge-progress-text">{{ $badgeData['progress']['current'] ?? 0 }}/{{ $badgeData['progress']['required'] ?? 0 }}</div>
        @endif
    </div>
    @endforeach
</div>
@endif

<!-- Recent Transactions -->
<div class="section-header mb-3">
    <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history"></i> Historique</h6>
    <a href="{{ route('rewards.transactions.export') }}" class="see-all-link">
        <i class="bi bi-download"></i> CSV
    </a>
</div>

<div class="transactions-list">
    @forelse($transactions as $transaction)
    <div class="transaction-item">
        <div class="transaction-icon {{ $transaction->amount > 0 ? 'positive' : 'negative' }}">
            @if($transaction->type === 'earned')
                <i class="bi bi-megaphone"></i>
            @elseif($transaction->type === 'referral_bonus')
                <i class="bi bi-people"></i>
            @elseif($transaction->type === 'welcome_bonus')
                <i class="bi bi-gift"></i>
            @elseif($transaction->type === 'converted')
                <i class="bi bi-arrow-repeat"></i>
            @else
                <i class="bi bi-coin"></i>
            @endif
        </div>
        <div class="transaction-info">
            <div class="transaction-type">
                @php
                    $typeLabels = [
                        'earned' => 'Campagne',
                        'referral_bonus' => 'Parrainage',
                        'welcome_bonus' => 'Bienvenue',
                        'manual_adjustment' => 'Ajustement',
                        'converted' => 'Conversion',
                        'reversal' => 'Annulation',
                    ];
                @endphp
                {{ $typeLabels[$transaction->type] ?? ucfirst($transaction->type) }}
            </div>
            <div class="transaction-date">{{ $transaction->created_at->format('d M Y \\\u00e0 H:i') }}</div>
        </div>
        <div class="transaction-amount {{ $transaction->amount > 0 ? 'positive' : 'negative' }}">
            {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount) }}
        </div>
    </div>
    @empty
    <div class="empty-state py-4">
        <i class="bi bi-inbox"></i>
        <p class="mb-0">Aucune transaction</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($transactions->hasPages())
<div class="pagination-wrapper mt-4">
    {{ $transactions->links() }}
</div>
@endif
@endsection

@push('styles')
<style>
/* Balance Card Main */
.balance-card-main {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    border-radius: 20px;
    padding: 1.5rem;
    color: white;
}

.balance-card-main * {
    color: white;
}

.balance-card-main .btn-light {
    color: var(--primary) !important;
    background: white;
}

.balance-card-main .btn-light i {
    color: var(--primary) !important;
}

.balance-card-main .btn-light:hover {
    background: rgba(255, 255, 255, 0.9);
    color: var(--primary) !important;
}

.balance-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.balance-label {
    color: white;
    opacity: 1;
    font-size: 0.9rem;
}

.history-link {
    color: white;
    opacity: 0.8;
    font-size: 1.2rem;
}

.balance-value {
    font-size: 2.5rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: white;
}

.balance-cash {
    color: white;
    opacity: 1;
    font-size: 1rem;
    margin-top: 0.25rem;
}

/* Quick Stats */
.quick-stats-row {
    display: flex;
    gap: 12px;
    overflow-x: auto;
}

.quick-stat {
    flex: 1;
    min-width: 100px;
    background: white;
    border-radius: 14px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.stat-icon-mini {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.stat-number {
    font-weight: 700;
    font-size: 1.1rem;
}

.stat-text {
    font-size: 0.7rem;
    color: var(--muted);
}

/* Badges Scroll */
.badges-scroll {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    padding-bottom: 8px;
    -webkit-overflow-scrolling: touch;
}

.badges-scroll::-webkit-scrollbar {
    display: none;
}

.badge-card {
    flex: 0 0 100px;
    background: white;
    border-radius: 14px;
    padding: 1rem;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.badge-emoji {
    font-size: 2rem;
    margin-bottom: 0.25rem;
}

.badge-name {
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.badge-date {
    font-size: 0.65rem;
    color: var(--muted);
}

/* Badges Grid */
.badges-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

.badge-item {
    background: white;
    border-radius: 12px;
    padding: 0.75rem 0.5rem;
    text-align: center;
    position: relative;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.badge-item.locked {
    opacity: 0.5;
}

.badge-item .badge-emoji {
    font-size: 1.5rem;
}

.badge-item .badge-name {
    font-size: 0.65rem;
}

.badge-status {
    position: absolute;
    top: -4px;
    right: -4px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
}

.badge-status.earned {
    background: var(--success);
    color: white;
}

.badge-progress {
    height: 3px;
    background: #e9ecef;
    border-radius: 2px;
    margin-top: 4px;
}

.progress-bar-mini {
    height: 100%;
    background: var(--primary);
    border-radius: 2px;
    transition: width 0.3s ease;
}

.badge-progress-text {
    font-size: 0.6rem;
    color: var(--muted);
    margin-top: 2px;
    font-weight: 500;
}

/* Transactions List */
.transactions-list {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.transaction-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #f0f0f0;
}

.transaction-item:last-child {
    border-bottom: none;
}

.transaction-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    margin-right: 12px;
}

.transaction-icon.positive {
    background: rgba(25, 135, 84, 0.1);
    color: var(--success);
}

.transaction-icon.negative {
    background: rgba(220, 53, 69, 0.1);
    color: var(--danger);
}

.transaction-info {
    flex: 1;
}

.transaction-type {
    font-weight: 500;
    font-size: 0.9rem;
}

.transaction-date {
    font-size: 0.75rem;
    color: var(--muted);
}

.transaction-amount {
    font-weight: 700;
    font-size: 0.95rem;
}

.transaction-amount.positive {
    color: var(--success);
}

.transaction-amount.negative {
    color: var(--danger);
}

/* Section Header */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.see-all-link {
    font-size: 0.85rem;
    color: var(--primary);
    text-decoration: none;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 2rem;
    color: var(--muted);
}

.empty-state i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

@media (min-width: 768px) {
    .badges-grid {
        grid-template-columns: repeat(6, 1fr);
    }
    
    .quick-stats-row {
        gap: 16px;
    }
}
</style>
@endpush
