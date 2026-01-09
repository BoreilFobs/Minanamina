@extends('layouts.modern')

@section('title', 'Détail Conversion')

@section('content')
<!-- Back Header -->
<div class="back-header mb-3">
    <a href="{{ route('rewards.conversions') }}" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="mb-0 fw-bold">Détail</h5>
    <div style="width: 40px;"></div>
</div>

<!-- Status Card -->
<div class="status-card-detail mb-4 {{ $conversion->status }}">
    <div class="status-icon-lg">
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
    <h5 class="fw-bold mb-1">
        @if($conversion->status == 'pending')
            En attente
        @elseif($conversion->status == 'processing')
            En traitement
        @elseif($conversion->status == 'completed')
            Complétée
        @else
            Rejetée
        @endif
    </h5>
    <p class="mb-0 small opacity-75">{{ $conversion->created_at->format('d/m/Y à H:i') }}</p>
</div>

<!-- Amount Info -->
<div class="amount-card mb-4">
    <div class="amount-row">
        <span class="label">Pièces converties</span>
        <span class="value">{{ number_format($conversion->pieces_amount) }}</span>
    </div>
    <div class="amount-divider">
        <i class="bi bi-arrow-down"></i>
    </div>
    <div class="amount-row highlight">
        <span class="label">Montant à recevoir</span>
        <span class="value">{{ number_format($conversion->cash_amount, 0) }} FCFA</span>
    </div>
</div>

<!-- Payment Info -->
<div class="info-card mb-4">
    <h6 class="fw-bold mb-3">Informations de paiement</h6>
    <div class="info-row">
        <span>Méthode</span>
        @php
            $methodLabels = [
                'orange_money' => 'Orange Money',
                'mtn_mobile_money' => 'MTN Mobile Money',
                'wave' => 'Wave',
            ];
        @endphp
        <strong>{{ $methodLabels[$conversion->payment_method] ?? $conversion->payment_method }}</strong>
    </div>
    @if($conversion->payment_phone)
    <div class="info-row">
        <span>Numéro</span>
        <strong>{{ $conversion->payment_phone }}</strong>
    </div>
    @endif
</div>

<!-- Timeline -->
<div class="timeline-card">
    <h6 class="fw-bold mb-3">Historique</h6>
    <div class="timeline">
        <div class="timeline-item completed">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
                <div class="timeline-title">Demande créée</div>
                <div class="timeline-date">{{ $conversion->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
        @if($conversion->processed_at)
        <div class="timeline-item completed">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
                <div class="timeline-title">Traitement démarré</div>
                <div class="timeline-date">{{ $conversion->processed_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
        @endif
        @if($conversion->completed_at)
        <div class="timeline-item completed">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
                <div class="timeline-title">Paiement effectué</div>
                <div class="timeline-date">{{ $conversion->completed_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
        @endif
        @if($conversion->rejected_at)
        <div class="timeline-item rejected">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
                <div class="timeline-title">Demande rejetée</div>
                <div class="timeline-date">{{ $conversion->rejected_at->format('d/m/Y H:i') }}</div>
                @if($conversion->rejection_reason)
                <div class="timeline-reason">{{ $conversion->rejection_reason }}</div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.back-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.back-btn {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--dark);
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

/* Status Card */
.status-card-detail {
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
}

.status-card-detail.pending { background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%); }
.status-card-detail.processing { background: linear-gradient(135deg, #cff4fc 0%, #9eeaf9 100%); }
.status-card-detail.completed { background: linear-gradient(135deg, #d1e7dd 0%, #a3cfbb 100%); }
.status-card-detail.rejected { background: linear-gradient(135deg, #f8d7da 0%, #f1aeb5 100%); }

.status-icon-lg {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.75rem;
}

.status-card-detail.pending .status-icon-lg { color: #856404; }
.status-card-detail.processing .status-icon-lg { color: #055160; }
.status-card-detail.completed .status-icon-lg { color: #0f5132; }
.status-card-detail.rejected .status-icon-lg { color: #842029; }

/* Amount Card */
.amount-card {
    background: white;
    border-radius: 16px;
    padding: 1.25rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.amount-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
}

.amount-row .label { color: var(--muted); }
.amount-row .value { font-weight: 700; font-size: 1.1rem; }
.amount-row.highlight .value { color: var(--success); font-size: 1.25rem; }

.amount-divider {
    text-align: center;
    color: var(--muted);
    padding: 0.5rem 0;
}

/* Info Card */
.info-card {
    background: white;
    border-radius: 16px;
    padding: 1.25rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.info-row:last-child { border-bottom: none; }
.info-row span { color: var(--muted); }

/* Timeline */
.timeline-card {
    background: white;
    border-radius: 16px;
    padding: 1.25rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.timeline {
    position: relative;
    padding-left: 24px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 6px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
}

.timeline-item:last-child { padding-bottom: 0; }

.timeline-dot {
    position: absolute;
    left: -24px;
    top: 4px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #e9ecef;
    border: 2px solid white;
}

.timeline-item.completed .timeline-dot { background: var(--success); }
.timeline-item.rejected .timeline-dot { background: var(--danger); }

.timeline-title { font-weight: 600; font-size: 0.9rem; }
.timeline-date { font-size: 0.75rem; color: var(--muted); }
.timeline-reason { font-size: 0.8rem; color: var(--danger); margin-top: 4px; }
</style>
@endpush
