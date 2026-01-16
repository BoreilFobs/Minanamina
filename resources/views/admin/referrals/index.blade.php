@extends('layouts.admin')

@section('title', 'Système de Parrainage')
@section('page-title', 'Parrainages')

@push('styles')
<style>
    .referral-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    @media (min-width: 768px) {
        .referral-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (min-width: 1024px) {
        .referral-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    
    .config-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    @media (min-width: 768px) {
        .config-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    .config-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }
    
    .config-card__header {
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        color: white;
    }
    
    .config-card__header.purple {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }
    
    .config-card__header.green {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .config-card__header.amber {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    
    .config-card__body {
        padding: 1.25rem;
    }
    
    .config-card .form-control {
        font-size: 1.25rem;
        font-weight: 600;
        text-align: center;
        padding: 0.875rem;
    }
    
    .config-card .form-text {
        text-align: center;
        margin-top: 0.5rem;
    }
    
    .config-card .btn {
        width: 100%;
        margin-top: 1rem;
    }
    
    .toggle-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 12px;
        margin-bottom: 1rem;
    }
    
    .toggle-info h6 {
        margin: 0 0 0.25rem;
        font-weight: 600;
    }
    
    .toggle-info p {
        margin: 0;
        font-size: 0.85rem;
        color: #6b7280;
    }
    
    .info-card {
        background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid #bae6fd;
    }
    
    .info-card h5 {
        color: #0369a1;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1rem;
    }
    
    @media (min-width: 768px) {
        .info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    .info-item h6 {
        color: #0c4a6e;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-item p {
        color: #0369a1;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .referrer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        object-fit: cover;
    }
    
    .referrer-avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
    
    .rank-badge {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
    }
    
    .rank-1 { background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: #78350f; }
    .rank-2 { background: linear-gradient(135deg, #d1d5db 0%, #9ca3af 100%); color: #374151; }
    .rank-3 { background: linear-gradient(135deg, #fcd34d 0%, #f59e0b 100%); color: #78350f; }
    .rank-default { background: #f3f4f6; color: #6b7280; }
    
    .code-badge {
        font-family: 'SF Mono', 'Monaco', 'Inconsolata', monospace;
        background: #f3f4f6;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.85rem;
        color: #374151;
        font-weight: 500;
    }
    
    .pieces-badge {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }
</style>
@endpush

@section('content')
<div class="admin-page">
    <!-- Header -->
    <div class="admin-page__header d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="admin-page__title">Gestion du Système de Parrainage</h1>
            <p class="admin-page__subtitle">Configurer les bonus et suivre les statistiques</p>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="alert-modern success mb-4">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-modern error mb-4">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="referral-grid">
        <div class="stats-card gradient-primary">
            <div class="stats-card__icon" style="background: rgba(255,255,255,0.2);">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stats-card__value">{{ number_format($stats['total_referrals']) }}</div>
            <div class="stats-card__label">Parrainages Totaux</div>
        </div>
        
        <div class="stats-card gradient-warning">
            <div class="stats-card__icon" style="background: rgba(255,255,255,0.2);">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stats-card__value">{{ number_format($stats['pending_referrals']) }}</div>
            <div class="stats-card__label">En Attente</div>
        </div>
        
        <div class="stats-card gradient-success">
            <div class="stats-card__icon" style="background: rgba(255,255,255,0.2);">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stats-card__value">{{ number_format($stats['credited_referrals']) }}</div>
            <div class="stats-card__label">Crédités</div>
        </div>
        
        <div class="stats-card gradient-info">
            <div class="stats-card__icon" style="background: rgba(255,255,255,0.2);">
                <i class="bi bi-coin"></i>
            </div>
            <div class="stats-card__value">{{ number_format($stats['total_bonus_paid']) }}</div>
            <div class="stats-card__label">Pièces Distribuées</div>
        </div>
    </div>

    <!-- Configuration Cards -->
    <div class="config-grid">
        <!-- Referrer Bonus -->
        <div class="config-card">
            <div class="config-card__header purple">
                <i class="bi bi-gift-fill"></i>
                <span>Bonus Parrain</span>
            </div>
            <div class="config-card__body">
                <form action="{{ route('admin.referrals.update-bonus') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <input type="number" 
                               class="form-control @error('bonus_amount') is-invalid @enderror" 
                               id="bonus_amount" 
                               name="bonus_amount" 
                               value="{{ $settings['referral_bonus_amount'] }}"
                               min="0"
                               max="10000"
                               required>
                        @error('bonus_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <p class="form-text text-muted">Pièces pour le parrain</p>
                    <button type="submit" class="btn btn--primary">
                        <i class="bi bi-save"></i> Mettre à jour
                    </button>
                </form>
            </div>
        </div>

        <!-- New User Bonus -->
        <div class="config-card">
            <div class="config-card__header green">
                <i class="bi bi-person-plus-fill"></i>
                <span>Bonus Nouveau</span>
            </div>
            <div class="config-card__body">
                <form action="{{ route('admin.referrals.update-new-user-bonus') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <input type="number" 
                               class="form-control @error('new_user_bonus_amount') is-invalid @enderror" 
                               id="new_user_bonus_amount" 
                               name="new_user_bonus_amount" 
                               value="{{ $settings['new_user_bonus_amount'] }}"
                               min="0"
                               max="10000"
                               required>
                        @error('new_user_bonus_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <p class="form-text text-muted">Pièces pour le filleul</p>
                    <button type="submit" class="btn btn--success">
                        <i class="bi bi-save"></i> Mettre à jour
                    </button>
                </form>
            </div>
        </div>

        <!-- System Toggle -->
        <div class="config-card">
            <div class="config-card__header amber">
                <i class="bi bi-toggle-on"></i>
                <span>Activation</span>
            </div>
            <div class="config-card__body">
                <form action="{{ route('admin.referrals.toggle-system') }}" method="POST">
                    @csrf
                    <div class="toggle-card">
                        <div class="toggle-info">
                            <h6>Système de Parrainage</h6>
                            <p>{{ $settings['referral_enabled'] ? 'Codes actifs' : 'Codes désactivés' }}</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   role="switch" 
                                   id="enabled" 
                                   name="enabled" 
                                   value="1"
                                   {{ $settings['referral_enabled'] ? 'checked' : '' }}
                                   onchange="this.form.submit()"
                                   style="width: 50px; height: 26px;">
                        </div>
                    </div>
                </form>
                
                <div class="alert {{ $settings['referral_enabled'] ? 'alert-success' : 'alert-warning' }} mb-0 text-center" style="border-radius: 10px;">
                    <i class="bi {{ $settings['referral_enabled'] ? 'bi-check-circle' : 'bi-exclamation-triangle' }}"></i>
                    {{ $settings['referral_enabled'] ? 'Système Activé' : 'Système Désactivé' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="info-card">
        <h5><i class="bi bi-info-circle-fill"></i> Comment ça marche</h5>
        <div class="info-grid">
            <div class="info-item">
                <h6><i class="bi bi-gift text-primary"></i> Bonus Parrain</h6>
                <p>Pièces attribuées à l'utilisateur qui possède le code de parrainage lorsqu'un nouveau membre s'inscrit avec son code.</p>
                <span class="badge bg-primary">{{ number_format($settings['referral_bonus_amount']) }} pièces</span>
            </div>
            <div class="info-item">
                <h6><i class="bi bi-person-plus text-success"></i> Bonus Nouveau Membre</h6>
                <p>Pièces de bienvenue offertes au nouveau membre qui s'inscrit en utilisant un code de parrainage.</p>
                <span class="badge bg-success">{{ number_format($settings['new_user_bonus_amount']) }} pièces</span>
            </div>
        </div>
    </div>

    <!-- Top Referrers -->
    <div class="data-card mb-4">
        <div class="data-card__header">
            <h5 class="data-card__title">
                <i class="bi bi-trophy-fill text-warning"></i>
                Top 10 Parrains
            </h5>
            <a href="{{ route('admin.referrals.top-referrers') }}" class="btn btn--ghost btn--sm">
                Voir Tout
            </a>
        </div>
        <div class="data-card__body">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>Utilisateur</th>
                            <th>Filleuls</th>
                            <th>Gains Totaux</th>
                            <th>Code</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['top_referrers'] as $index => $user)
                        <tr>
                            <td>
                                <span class="rank-badge {{ $index < 3 ? 'rank-' . ($index + 1) : 'rank-default' }}">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                                         alt="{{ $user->name }}" 
                                         class="referrer-avatar">
                                    @else
                                    <div class="referrer-avatar-placeholder">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    @endif
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $user->total_referrals }} filleuls</span>
                            </td>
                            <td>
                                <span class="pieces-badge">
                                    <i class="bi bi-coin"></i> {{ number_format($user->referral_earnings) }}
                                </span>
                            </td>
                            <td>
                                <span class="code-badge">{{ $user->referral_code }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                                Aucun parrainage enregistré
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Referrals -->
    <div class="data-card">
        <div class="data-card__header">
            <h5 class="data-card__title">
                <i class="bi bi-clock-history text-success"></i>
                Parrainages Récents
            </h5>
            <a href="{{ route('admin.referrals.all') }}" class="btn btn--ghost btn--sm">
                Voir Tout
            </a>
        </div>
        <div class="data-card__body">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Parrain</th>
                            <th>Filleul</th>
                            <th>Code</th>
                            <th>Bonus</th>
                            <th>Statut</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentReferrals as $referral)
                        <tr>
                            <td>
                                <strong>{{ $referral->referrer ? $referral->referrer->name : 'Supprimé' }}</strong>
                            </td>
                            <td>
                                {{ $referral->referred ? $referral->referred->name : 'Supprimé' }}
                            </td>
                            <td>
                                <span class="code-badge">{{ $referral->referral_code }}</span>
                            </td>
                            <td>
                                <span class="pieces-badge">
                                    <i class="bi bi-coin"></i> {{ number_format($referral->bonus_amount) }}
                                </span>
                            </td>
                            <td>
                                @if($referral->status === 'credited')
                                    <span class="badge bg-success">Crédité</span>
                                @elseif($referral->status === 'pending')
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @else
                                    <span class="badge bg-danger">Annulé</span>
                                @endif
                            </td>
                            <td class="text-muted">
                                {{ $referral->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                                Aucun parrainage récent
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
