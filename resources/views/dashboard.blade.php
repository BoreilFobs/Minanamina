@extends('layouts.modern')

@section('title', 'Tableau de bord')

@section('content')
<!-- Welcome Header -->
<div class="welcome-header mb-4">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h4 class="mb-1 fw-bold">Bonjour, {{ Str::words($user->name, 1, '') }} 👋</h4>
            <p class="text-muted mb-0 small">Bienvenue sur Minanamina</p>
        </div>
        <div class="notification-bell">
            <a href="#" class="btn btn-light btn-icon position-relative">
                <i class="bi bi-bell"></i>
                <span class="notification-badge">3</span>
            </a>
        </div>
    </div>
</div>

<!-- Balance Card -->
<div class="balance-card mb-4">
    <div class="balance-content">
        <div class="balance-label">Solde de Pièces</div>
        <div class="balance-amount">
            <i class="bi bi-gem"></i>
            <span>{{ number_format($stats['pieces_balance'], 2) }}</span>
        </div>
        <div class="balance-actions mt-3">
            <a href="{{ route('rewards.convert.form') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-repeat"></i> Convertir
            </a>
            <a href="{{ route('rewards.index') }}" class="btn btn-outline-light btn-sm">
                <i class="bi bi-list"></i> Historique
            </a>
        </div>
    </div>
    <div class="balance-decoration">
        <i class="bi bi-gem"></i>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid mb-4">
    <div class="stat-card-mini" onclick="window.location='{{ route('campaigns.index') }}'">
        <div class="stat-icon bg-primary-subtle">
            <i class="bi bi-megaphone text-primary"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['total_campaigns'] }}</div>
            <div class="stat-label">Campagnes</div>
        </div>
    </div>
    
    <div class="stat-card-mini" onclick="window.location='{{ route('referrals.index') }}'">
        <div class="stat-icon bg-info-subtle">
            <i class="bi bi-people text-info"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['total_referrals'] }}</div>
            <div class="stat-label">Parrainages</div>
        </div>
    </div>
    
    <div class="stat-card-mini">
        <div class="stat-icon bg-success-subtle">
            <i class="bi bi-check-circle text-success"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ $stats['completed_campaigns'] }}</div>
            <div class="stat-label">Terminées</div>
        </div>
    </div>
    
    <div class="stat-card-mini">
        <div class="stat-icon bg-warning-subtle">
            <i class="bi bi-cash-stack text-warning"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value">{{ number_format($stats['referral_earnings'], 0) }}</div>
            <div class="stat-label">Gains</div>
        </div>
    </div>
</div>

<!-- Referral Code Card -->
<div class="referral-card mb-4">
    <div class="referral-header">
        <i class="bi bi-gift-fill text-warning"></i>
        <span>Parrainez et Gagnez!</span>
    </div>
    <div class="referral-body">
        <p class="text-muted small mb-2">Partagez votre code et gagnez des pièces</p>
        <div class="referral-code-box">
            <span class="referral-code" id="referralCode">{{ $user->referral_code }}</span>
            <button type="button" class="copy-btn" onclick="copyReferralCode()">
                <i class="bi bi-clipboard" id="copyIcon"></i>
            </button>
        </div>
        <button type="button" class="btn btn-primary btn-sm w-100 mt-2" onclick="shareReferral()">
            <i class="bi bi-share me-1"></i> Partager le lien
        </button>
    </div>
</div>

<!-- Available Campaigns Section -->
<div class="section-header mb-3">
    <h6 class="mb-0 fw-bold"><i class="bi bi-fire text-danger"></i> Campagnes Actives</h6>
    <a href="{{ route('campaigns.index') }}" class="see-all-link">Voir tout <i class="bi bi-chevron-right"></i></a>
</div>

@if($availableCampaigns->count() > 0)
<div class="campaigns-scroll mb-4">
    @foreach($availableCampaigns as $campaign)
    <div class="campaign-card-mini">
        @if($campaign->image)
            <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="campaign-img">
        @else
            <div class="campaign-img-placeholder">
                <i class="bi bi-megaphone"></i>
            </div>
        @endif
        <div class="campaign-content">
            <h6 class="campaign-title">{{ Str::limit($campaign->title, 25) }}</h6>
            <div class="campaign-meta">
                <span class="campaign-reward">
                    <i class="bi bi-gem"></i> {{ $campaign->pieces_reward }}
                </span>
                <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-sm btn-primary">
                    Démarrer
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="empty-state mb-4">
    <i class="bi bi-inbox"></i>
    <p>Aucune campagne disponible</p>
</div>
@endif

<!-- Recent Participations -->
<div class="section-header mb-3">
    <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history"></i> Participations Récentes</h6>
    <a href="{{ route('campaigns.my-participations') }}" class="see-all-link">Voir tout <i class="bi bi-chevron-right"></i></a>
</div>

<div class="card mb-4">
    <div class="card-body p-0">
        @if($recentParticipations->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($recentParticipations->take(5) as $participation)
                <div class="list-group-item participation-item">
                    <div class="participation-info">
                        <div class="participation-title">{{ Str::limit($participation->campaign->title ?? 'Campagne', 30) }}</div>
                        <div class="participation-date text-muted small">{{ $participation->started_at->diffForHumans() }}</div>
                    </div>
                    <div class="participation-meta">
                        <span class="badge {{ $participation->status === 'completed' ? 'bg-success' : ($participation->status === 'active' ? 'bg-primary' : 'bg-secondary') }}">
                            {{ $participation->status === 'completed' ? 'Terminée' : ($participation->status === 'active' ? 'Active' : ucfirst($participation->status)) }}
                        </span>
                        @if($participation->pieces_earned > 0)
                        <span class="pieces-earned text-success">
                            +{{ $participation->pieces_earned }} <i class="bi bi-gem"></i>
                        </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state py-4">
                <i class="bi bi-inbox"></i>
                <p class="mb-0">Aucune participation</p>
            </div>
        @endif
    </div>
</div>

<!-- Recent Activities -->
<div class="section-header mb-3">
    <h6 class="mb-0 fw-bold"><i class="bi bi-activity"></i> Activités Récentes</h6>
</div>

<div class="card mb-4">
    <div class="card-body p-0">
        @if($recentActivities->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($recentActivities->take(5) as $activity)
                <div class="list-group-item activity-item-modern">
                    <div class="activity-icon {{ $activity->type === 'earned' ? 'earned' : ($activity->type === 'converted' ? 'converted' : 'bonus') }}">
                        @if($activity->type === 'earned')
                            <i class="bi bi-arrow-up"></i>
                        @elseif($activity->type === 'converted')
                            <i class="bi bi-arrow-down"></i>
                        @else
                            <i class="bi bi-gift"></i>
                        @endif
                    </div>
                    <div class="activity-info">
                        <div class="activity-desc">{{ $activity->description }}</div>
                        <div class="activity-time text-muted small">{{ $activity->created_at->format('d M Y') }}</div>
                    </div>
                    <div class="activity-amount {{ $activity->amount > 0 ? 'positive' : 'negative' }}">
                        {{ $activity->amount > 0 ? '+' : '' }}{{ $activity->amount }}
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state py-4">
                <i class="bi bi-inbox"></i>
                <p class="mb-0">Aucune activité</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.welcome-header {
    padding-top: env(safe-area-inset-top, 0);
}

.btn-icon {
    width: 44px;
    height: 44px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
}

.notification-badge {
    position: absolute;
    top: -2px;
    right: -2px;
    width: 18px;
    height: 18px;
    background: var(--danger);
    color: white;
    border-radius: 50%;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Balance Card */
.balance-card {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    border-radius: 20px;
    padding: 1.5rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.balance-label {
    font-size: 0.9rem;
    color: white;
    opacity: 1;
}

.balance-amount {
    font-size: 2.5rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: white;
}

.balance-actions {
    color: white;
}

.balance-actions .btn-light {
    color: var(--primary);
    background: white;
    border-color: white;
}

.balance-actions .btn-light:hover {
    background: rgba(255, 255, 255, 0.9);
    color: var(--primary);
}

.balance-actions .btn-outline-light {
    color: white;
    border-color: rgba(255, 255, 255, 0.8);
}

.balance-actions .btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.balance-decoration {
    position: absolute;
    right: -20px;
    bottom: -20px;
    font-size: 8rem;
    opacity: 0.1;
    color: white;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.stat-card-mini {
    background: white;
    border-radius: 16px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.stat-card-mini:active {
    transform: scale(0.98);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.stat-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--dark);
}

.stat-label {
    font-size: 0.75rem;
    color: var(--muted);
}

/* Referral Card */
.referral-card {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    border-radius: 16px;
    overflow: hidden;
}

.referral-header {
    background: rgba(0,0,0,0.05);
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    font-size: 0.9rem;
}

.referral-body {
    padding: 1rem;
}

.referral-code-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: white;
    border-radius: 10px;
    padding: 0.75rem 1rem;
}

.referral-code {
    font-family: monospace;
    font-size: 1.25rem;
    font-weight: 700;
    letter-spacing: 2px;
}

.copy-btn {
    width: 36px;
    height: 36px;
    border: none;
    background: var(--primary);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.copy-btn.copied {
    background: var(--success);
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

/* Campaigns Scroll */
.campaigns-scroll {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    padding-bottom: 8px;
    margin: 0 -1rem;
    padding: 0 1rem 8px;
}

.campaigns-scroll::-webkit-scrollbar {
    display: none;
}

.campaign-card-mini {
    flex: 0 0 200px;
    scroll-snap-align: start;
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.campaign-img {
    width: 100%;
    height: 100px;
    object-fit: cover;
}

.campaign-img-placeholder {
    width: 100%;
    height: 100px;
    background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
}

.campaign-content {
    padding: 0.75rem;
}

.campaign-title {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.campaign-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.campaign-reward {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--warning);
}

/* Participation Item */
.participation-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
}

.participation-title {
    font-weight: 500;
}

.participation-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 4px;
}

.pieces-earned {
    font-size: 0.85rem;
    font-weight: 600;
}

/* Activity Item */
.activity-item-modern {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 1rem;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.activity-icon.earned {
    background: rgba(25, 135, 84, 0.1);
    color: var(--success);
}

.activity-icon.converted {
    background: rgba(220, 53, 69, 0.1);
    color: var(--danger);
}

.activity-icon.bonus {
    background: rgba(255, 193, 7, 0.1);
    color: var(--warning);
}

.activity-info {
    flex: 1;
    min-width: 0;
}

.activity-desc {
    font-size: 0.9rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.activity-amount {
    font-weight: 600;
    font-size: 0.9rem;
}

.activity-amount.positive {
    color: var(--success);
}

.activity-amount.negative {
    color: var(--danger);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 2rem;
    color: var(--muted);
}

.empty-state i {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

@media (min-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
    }
    
    .campaigns-scroll {
        margin: 0;
        padding: 0 0 8px;
    }
}
</style>
@endpush

@push('scripts')
<script>
function copyReferralCode() {
    const code = document.getElementById('referralCode').textContent;
    const copyBtn = document.querySelector('.copy-btn');
    const copyIcon = document.getElementById('copyIcon');
    
    navigator.clipboard.writeText(code).then(() => {
        copyBtn.classList.add('copied');
        copyIcon.className = 'bi bi-check-lg';
        
        setTimeout(() => {
            copyBtn.classList.remove('copied');
            copyIcon.className = 'bi bi-clipboard';
        }, 2000);
    }).catch(err => {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = code;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        copyBtn.classList.add('copied');
        copyIcon.className = 'bi bi-check-lg';
        setTimeout(() => {
            copyBtn.classList.remove('copied');
            copyIcon.className = 'bi bi-clipboard';
        }, 2000);
    });
}

function shareReferral() {
    const code = document.getElementById('referralCode').textContent;
    const shareUrl = `{{ url('/register') }}?ref=${code}`;
    const shareText = `Rejoins Minanamina et gagne des récompenses! Utilise mon code de parrainage: ${code}`;
    
    if (navigator.share) {
        navigator.share({
            title: 'Rejoins Minanamina',
            text: shareText,
            url: shareUrl
        }).catch(console.error);
    } else {
        navigator.clipboard.writeText(shareUrl).then(() => {
            alert('Lien copié dans le presse-papiers!');
        });
    }
}
</script>
@endpush
