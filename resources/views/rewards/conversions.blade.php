@extends('layouts.modern')

@section('title', 'Mes Conversions')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold">Mes Conversions</h4>
        <p class="text-muted mb-0 small">Historique de vos demandes</p>
    </div>
    <a href="{{ route('rewards.convert.form') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus"></i> Nouvelle
    </a>
</div>

<!-- Stats -->
<div class="conversion-stats mb-4">
    <div class="c-stat">
        <div class="c-stat-value">{{ $stats['total_requests'] ?? 0 }}</div>
        <div class="c-stat-label">Total</div>
    </div>
    <div class="c-stat warning">
        <div class="c-stat-value">{{ $stats['pending_requests'] ?? 0 }}</div>
        <div class="c-stat-label">En attente</div>
    </div>
    <div class="c-stat success">
        <div class="c-stat-value">{{ $stats['completed_requests'] ?? 0 }}</div>
        <div class="c-stat-label">Complétées</div>
    </div>
    <div class="c-stat info">
        <div class="c-stat-value">{{ number_format($stats['total_converted_cash'] ?? 0, 0) }}</div>
        <div class="c-stat-label">FCFA</div>
    </div>
</div>

<!-- Filter -->
<div class="filter-chips mb-4">
    <a href="{{ route('rewards.conversions') }}" class="filter-chip {{ !request('status') ? 'active' : '' }}">
        Toutes
    </a>
    <a href="{{ route('rewards.conversions', ['status' => 'pending']) }}" class="filter-chip {{ request('status') == 'pending' ? 'active' : '' }}">
        En attente
    </a>
    <a href="{{ route('rewards.conversions', ['status' => 'completed']) }}" class="filter-chip {{ request('status') == 'completed' ? 'active' : '' }}">
        Complétées
    </a>
    <a href="{{ route('rewards.conversions', ['status' => 'rejected']) }}" class="filter-chip {{ request('status') == 'rejected' ? 'active' : '' }}">
        Rejetées
    </a>
</div>

<!-- Conversions List -->
<div class="conversions-list">
    @forelse($conversions as $conversion)
    <a href="{{ route('rewards.conversions.show', $conversion) }}" class="conversion-card {{ $conversion->status }}">
        <div class="conversion-status-icon">
            @if($conversion->status == 'pending')
                <i class="bi bi-clock"></i>
            @elseif($conversion->status == 'processing')
                <i class="bi bi-arrow-repeat"></i>
            @elseif($conversion->status == 'completed')
                <i class="bi bi-check-circle"></i>
            @else
                <i class="bi bi-x-circle"></i>
            @endif
        </div>
        <div class="conversion-info">
            <div class="conversion-amounts">
                <span class="pieces">{{ number_format($conversion->pieces_amount) }} pièces</span>
                <i class="bi bi-arrow-right"></i>
                <span class="cash">{{ number_format($conversion->cash_amount, 0) }} FCFA</span>
            </div>
            <div class="conversion-meta">
                @php
                    $methodLabels = [
                        'orange_money' => 'Orange Money',
                        'mtn_mobile_money' => 'MTN MoMo',
                        'wave' => 'Wave',
                    ];
                @endphp
                <span class="method">{{ $methodLabels[$conversion->payment_method] ?? $conversion->payment_method }}</span>
                <span class="date">{{ $conversion->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
        <div class="conversion-status">
            @if($conversion->status == 'pending')
                <span class="badge-status pending">En attente</span>
            @elseif($conversion->status == 'processing')
                <span class="badge-status processing">Traitement</span>
            @elseif($conversion->status == 'completed')
                <span class="badge-status completed">Complétée</span>
            @else
                <span class="badge-status rejected">Rejetée</span>
            @endif
        </div>
    </a>
    @empty
    <div class="empty-state">
        <div class="empty-icon">
            <i class="bi bi-inbox"></i>
        </div>
        <h5>Aucune conversion</h5>
        <p class="text-muted">Convertissez vos pièces en cash!</p>
        <a href="{{ route('rewards.convert.form') }}" class="btn btn-primary">
            <i class="bi bi-arrow-repeat me-1"></i> Convertir
        </a>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($conversions->hasPages())
<div class="pagination-wrapper mt-4">
    {{ $conversions->links() }}
</div>
@endif
@endsection

@push('styles')
<style>
/* Stats */
.conversion-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

.c-stat {
    background: white;
    border-radius: 12px;
    padding: 0.75rem;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.c-stat.warning { background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%); }
.c-stat.success { background: linear-gradient(135deg, #d1e7dd 0%, #a3cfbb 100%); }
.c-stat.info { background: linear-gradient(135deg, #cff4fc 0%, #9eeaf9 100%); }

.c-stat-value {
    font-size: 1.25rem;
    font-weight: 700;
}

.c-stat-label {
    font-size: 0.65rem;
    color: var(--muted);
}

/* Filter Chips */
.filter-chips {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.filter-chips::-webkit-scrollbar { display: none; }

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
}

.filter-chip.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* Conversion Card */
.conversions-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.conversion-card {
    display: flex;
    align-items: center;
    background: white;
    border-radius: 16px;
    padding: 1rem;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border-left: 4px solid;
}

.conversion-card.pending { border-left-color: var(--warning); }
.conversion-card.processing { border-left-color: var(--info); }
.conversion-card.completed { border-left-color: var(--success); }
.conversion-card.rejected { border-left-color: var(--danger); }

.conversion-status-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 1.2rem;
}

.conversion-card.pending .conversion-status-icon { background: #fff3cd; color: #856404; }
.conversion-card.processing .conversion-status-icon { background: #cff4fc; color: #055160; }
.conversion-card.completed .conversion-status-icon { background: #d1e7dd; color: #0f5132; }
.conversion-card.rejected .conversion-status-icon { background: #f8d7da; color: #842029; }

.conversion-info {
    flex: 1;
}

.conversion-amounts {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    font-size: 0.9rem;
}

.conversion-amounts .pieces { color: var(--primary); }
.conversion-amounts .cash { color: var(--success); }
.conversion-amounts i { color: var(--muted); font-size: 0.8rem; }

.conversion-meta {
    display: flex;
    gap: 12px;
    font-size: 0.75rem;
    color: var(--muted);
    margin-top: 4px;
}

.badge-status {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.badge-status.pending { background: #fff3cd; color: #856404; }
.badge-status.processing { background: #cff4fc; color: #055160; }
.badge-status.completed { background: #d1e7dd; color: #0f5132; }
.badge-status.rejected { background: #f8d7da; color: #842029; }

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
</style>
@endpush
