@extends('layouts.modern')

@section('title', $campaign->title)

@section('content')
<!-- Back Header -->
<div class="back-header mb-3">
    <a href="{{ route('campaigns.index') }}" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h5 class="mb-0 fw-bold">Détails</h5>
    <div style="width: 40px;"></div>
</div>

<!-- Campaign Hero -->
<div class="campaign-hero mb-4">
    @if($campaign->image)
        <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="hero-image">
    @else
        <div class="hero-placeholder">
            <i class="bi bi-megaphone"></i>
        </div>
    @endif
    <div class="hero-overlay">
        <span class="reward-badge-lg">
            <i class="bi bi-gem"></i> {{ number_format($campaign->pieces_reward) }} pièces
        </span>
    </div>
</div>

<!-- Campaign Info -->
<div class="campaign-detail-card mb-4">
    <h4 class="fw-bold mb-2">{{ $campaign->title }}</h4>
    
    <div class="status-badges mb-3">
        <span class="badge bg-success">
            <i class="bi bi-check-circle"></i> Active
        </span>
        @php
            $daysLeft = \Carbon\Carbon::parse($campaign->end_date)->diffInDays(now());
        @endphp
        @if($daysLeft <= 3)
        <span class="badge bg-danger">
            <i class="bi bi-clock"></i> {{ $daysLeft }}j restants
        </span>
        @else
        <span class="badge bg-secondary">
            <i class="bi bi-clock"></i> {{ $daysLeft }}j restants
        </span>
        @endif
    </div>

    <p class="campaign-description">{{ $campaign->description }}</p>
</div>

<!-- How to Participate -->
@if($campaign->validation_rules)
<div class="info-card mb-4">
    <div class="info-card-header">
        <i class="bi bi-list-check text-primary"></i>
        <h6 class="mb-0 fw-bold">Comment Participer</h6>
    </div>
    <div class="info-card-body">
        <p class="mb-0">{{ $campaign->validation_rules }}</p>
    </div>
</div>
@endif

<!-- Stats Row -->
<div class="stats-row mb-4">
    <div class="stat-item">
        <i class="bi bi-calendar-event text-primary"></i>
        <div class="stat-text">
            <small class="text-muted">Début</small>
            <strong>{{ \Carbon\Carbon::parse($campaign->start_date)->format('d/m/Y') }}</strong>
        </div>
    </div>
    <div class="stat-item">
        <i class="bi bi-calendar-x text-danger"></i>
        <div class="stat-text">
            <small class="text-muted">Fin</small>
            <strong>{{ \Carbon\Carbon::parse($campaign->end_date)->format('d/m/Y') }}</strong>
        </div>
    </div>
    <div class="stat-item">
        <i class="bi bi-people text-info"></i>
        <div class="stat-text">
            <small class="text-muted">Participants</small>
            <strong>{{ number_format($stats['total_participants']) }}</strong>
        </div>
    </div>
</div>

<!-- User Participation Status -->
@if($userParticipation)
<div class="participation-status mb-4">
    @if($userParticipation->status == 'pending')
    <div class="status-card warning">
        <div class="status-icon">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="status-info">
            <h6 class="fw-bold mb-1">En attente de validation</h6>
            <p class="mb-0 small text-muted">Votre participation est en cours de vérification</p>
        </div>
    </div>
    @elseif($userParticipation->status == 'completed')
    <div class="status-card success">
        <div class="status-icon">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="status-info">
            <h6 class="fw-bold mb-1">Participation validée!</h6>
            <p class="mb-0 small">
                <span class="text-success fw-bold">+{{ number_format($userParticipation->pieces_earned) }} pièces</span>
                <br>
                <span class="text-muted">Le {{ $userParticipation->completed_at->format('d/m/Y à H:i') }}</span>
            </p>
        </div>
    </div>
    @elseif($userParticipation->status == 'rejected')
    <div class="status-card danger">
        <div class="status-icon">
            <i class="bi bi-x-circle"></i>
        </div>
        <div class="status-info">
            <h6 class="fw-bold mb-1">Participation rejetée</h6>
            <p class="mb-0 small text-muted">Votre participation n'a pas été validée</p>
        </div>
    </div>
    @endif
</div>
@endif

<!-- Action Button -->
<div class="action-button-wrapper">
    @if(!$userParticipation)
        @auth
        <form action="{{ route('campaigns.participate', $campaign) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary btn-lg w-100 participate-btn">
                <i class="bi bi-rocket-takeoff me-2"></i>Participer Maintenant
            </button>
        </form>
        <p class="text-center text-muted small mt-2">
            <i class="bi bi-info-circle"></i> Vous serez redirigé vers le site partenaire
        </p>
        @else
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">
            <i class="bi bi-box-arrow-in-right me-2"></i>Connectez-vous pour Participer
        </a>
        @endauth
    @endif
</div>
@endsection

@push('styles')
<style>
/* Back Header */
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

/* Campaign Hero */
.campaign-hero {
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    margin: 0 -1rem;
}

.hero-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.hero-placeholder {
    width: 100%;
    height: 200px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: white;
}

.hero-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 1rem;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
}

.reward-badge-lg {
    background: var(--warning);
    color: #000;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1rem;
}

/* Campaign Detail Card */
.campaign-detail-card {
    background: white;
    border-radius: 16px;
    padding: 1.25rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.status-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.campaign-description {
    color: var(--muted);
    line-height: 1.6;
    white-space: pre-wrap;
}

/* Info Card */
.info-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.info-card-header {
    padding: 1rem 1.25rem;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-card-body {
    padding: 1.25rem;
}

/* Stats Row */
.stats-row {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    padding-bottom: 8px;
}

.stat-item {
    flex: 1;
    min-width: 100px;
    background: white;
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.stat-item i {
    font-size: 1.5rem;
}

.stat-text {
    display: flex;
    flex-direction: column;
}

.stat-text small {
    font-size: 0.7rem;
}

.stat-text strong {
    font-size: 0.9rem;
}

/* Participation Status */
.status-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    border-radius: 16px;
}

.status-card.warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
}

.status-card.success {
    background: linear-gradient(135deg, #d1e7dd 0%, #a3cfbb 100%);
}

.status-card.danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f1aeb5 100%);
}

.status-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.status-card.warning .status-icon {
    color: var(--warning);
}

.status-card.success .status-icon {
    color: var(--success);
}

.status-card.danger .status-icon {
    color: var(--danger);
}

/* Action Button */
.action-button-wrapper {
    position: sticky;
    bottom: 80px;
    padding: 1rem 0;
    background: linear-gradient(transparent, var(--light) 30%);
}

.participate-btn {
    padding: 1rem;
    font-size: 1.1rem;
    border-radius: 14px;
}

@media (min-width: 768px) {
    .campaign-hero {
        margin: 0;
    }
    
    .hero-image,
    .hero-placeholder {
        height: 300px;
    }
    
    .action-button-wrapper {
        position: static;
        background: none;
    }
}
</style>
@endpush
