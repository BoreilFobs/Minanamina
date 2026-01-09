@extends('layouts.modern')

@section('title', 'Parrainage')

@section('content')
<!-- Header -->
<div class="page-header mb-4">
    <h4 class="mb-1 fw-bold"><i class="bi bi-gift-fill text-warning"></i> Parrainage</h4>
    <p class="text-muted mb-0 small">Partagez votre code et gagnez des pièces</p>
</div>

<!-- Referral Code Card -->
<div class="referral-hero mb-4">
    <div class="referral-hero-content">
        <div class="referral-icon">
            <i class="bi bi-gift"></i>
        </div>
        <h5 class="fw-bold text-white mb-1">Votre Code</h5>
        <div class="referral-code-display" id="referralCode">{{ $user->referral_code }}</div>
        <div class="referral-actions">
            <button class="btn btn-light btn-sm" onclick="copyReferralCode()">
                <i class="bi bi-clipboard" id="copyIcon"></i> Copier
            </button>
            <button class="btn btn-outline-light btn-sm" onclick="shareReferral()">
                <i class="bi bi-share"></i> Partager
            </button>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="referral-stats mb-4">
    <div class="r-stat">
        <div class="r-stat-icon">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="r-stat-value">{{ $stats['total_referrals'] }}</div>
        <div class="r-stat-label">Filleuls</div>
    </div>
    <div class="r-stat success">
        <div class="r-stat-icon">
            <i class="bi bi-gem"></i>
        </div>
        <div class="r-stat-value">{{ number_format($stats['referral_earnings'], 0) }}</div>
        <div class="r-stat-label">Pièces gagnées</div>
    </div>
    <div class="r-stat warning">
        <div class="r-stat-icon">
            <i class="bi bi-hourglass"></i>
        </div>
        <div class="r-stat-value">{{ $stats['pending_referrals'] }}</div>
        <div class="r-stat-label">En attente</div>
    </div>
</div>

<!-- How it works -->
<div class="how-it-works mb-4">
    <h6 class="fw-bold mb-3">Comment ça marche?</h6>
    <div class="steps-list">
        <div class="step-item">
            <div class="step-number">1</div>
            <div class="step-content">
                <div class="step-title">Partagez votre code</div>
                <div class="step-desc">Envoyez votre code à vos amis</div>
            </div>
        </div>
        <div class="step-item">
            <div class="step-number">2</div>
            <div class="step-content">
                <div class="step-title">Ils s'inscrivent</div>
                <div class="step-desc">Avec votre code de parrainage</div>
            </div>
        </div>
        <div class="step-item">
            <div class="step-number">3</div>
            <div class="step-content">
                <div class="step-title">Vous gagnez!</div>
                <div class="step-desc">{{ number_format($bonusAmount ?? 100) }} pièces par filleul</div>
            </div>
        </div>
    </div>
</div>

<!-- Referred Users -->
<div class="section-header mb-3">
    <h6 class="mb-0 fw-bold"><i class="bi bi-people"></i> Mes Filleuls</h6>
    <span class="badge bg-primary">{{ $stats['referred_users']->count() }}</span>
</div>

<div class="referred-list">
    @forelse($stats['referred_users'] as $referred)
    <div class="referred-item">
        <div class="referred-avatar">
            @if($referred->avatar)
                <img src="{{ asset('storage/' . $referred->avatar) }}" alt="{{ $referred->name }}">
            @else
                <div class="avatar-placeholder">{{ strtoupper(substr($referred->name, 0, 1)) }}</div>
            @endif
        </div>
        <div class="referred-info">
            <div class="referred-name">{{ $referred->name }}</div>
            <div class="referred-date">Inscrit {{ $referred->created_at->diffForHumans() }}</div>
        </div>
        <div class="referred-status">
            @if($referred->pivot->is_credited ?? false)
                <span class="badge bg-success-subtle text-success">Crédité</span>
            @else
                <span class="badge bg-warning-subtle text-warning">En attente</span>
            @endif
        </div>
    </div>
    @empty
    <div class="empty-state">
        <div class="empty-icon">
            <i class="bi bi-people"></i>
        </div>
        <h5>Aucun filleul</h5>
        <p class="text-muted">Partagez votre code pour commencer!</p>
    </div>
    @endforelse
</div>
@endsection

@push('styles')
<style>
/* Referral Hero */
.referral-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    text-align: center;
}

.referral-icon {
    width: 60px;
    height: 60px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.75rem;
    color: white;
}

.referral-code-display {
    background: white;
    color: #333;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    font-size: 1.75rem;
    font-weight: 700;
    letter-spacing: 4px;
    margin: 1rem 0;
    font-family: monospace;
}

.referral-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
}

/* Stats */
.referral-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

.r-stat {
    background: white;
    border-radius: 14px;
    padding: 1rem;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.r-stat.success { background: linear-gradient(135deg, #d1e7dd 0%, #a3cfbb 100%); }
.r-stat.warning { background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%); }

.r-stat-icon {
    width: 40px;
    height: 40px;
    background: rgba(0,0,0,0.05);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    font-size: 1.1rem;
    color: var(--primary);
}

.r-stat.success .r-stat-icon { color: var(--success); }
.r-stat.warning .r-stat-icon { color: #856404; }

.r-stat-value {
    font-size: 1.5rem;
    font-weight: 700;
}

.r-stat-label {
    font-size: 0.7rem;
    color: var(--muted);
}

/* How it works */
.how-it-works {
    background: white;
    border-radius: 16px;
    padding: 1.25rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.steps-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.step-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.step-number {
    width: 36px;
    height: 36px;
    background: var(--primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    flex-shrink: 0;
}

.step-title {
    font-weight: 600;
    font-size: 0.9rem;
}

.step-desc {
    font-size: 0.75rem;
    color: var(--muted);
}

/* Section Header */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Referred List */
.referred-list {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.referred-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #f0f0f0;
}

.referred-item:last-child { border-bottom: none; }

.referred-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 12px;
}

.referred-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: var(--primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.referred-info {
    flex: 1;
}

.referred-name {
    font-weight: 600;
    font-size: 0.9rem;
}

.referred-date {
    font-size: 0.75rem;
    color: var(--muted);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 2rem;
}

.empty-icon {
    width: 60px;
    height: 60px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: var(--muted);
}
</style>
@endpush

@push('scripts')
<script>
function copyReferralCode() {
    const code = document.getElementById('referralCode').textContent;
    navigator.clipboard.writeText(code).then(() => {
        const btn = event.target.closest('button');
        const icon = btn.querySelector('i');
        icon.className = 'bi bi-check-lg';
        setTimeout(() => { icon.className = 'bi bi-clipboard'; }, 2000);
    });
}

function shareReferral() {
    const code = document.getElementById('referralCode').textContent;
    const shareUrl = `{{ url('/register') }}?ref=${code}`;
    const shareText = `Rejoins Minanamina et gagne des récompenses! Utilise mon code: ${code}`;
    
    if (navigator.share) {
        navigator.share({ title: 'Rejoins Minanamina', text: shareText, url: shareUrl });
    } else {
        navigator.clipboard.writeText(shareUrl).then(() => {
            alert('Lien copié!');
        });
    }
}
</script>
@endpush
