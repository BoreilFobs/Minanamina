@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Alerts -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 mb-0">Gestion du Système de Parrainage</h1>
        <p class="text-muted mb-0">Configurer le bonus et suivre les statistiques</p>
    </div>

    <!-- Settings Card -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-primary" style="border-width: 2px;">
                <div class="card-header text-white" style="background-color: #0d6efd;">
                    <h5 class="mb-0"><i class="bi bi-gift-fill"></i> Bonus Parrain</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.referrals.update-bonus') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="bonus_amount" class="form-label">Montant (pièces)</label>
                            <input type="number" 
                                   class="form-control form-control-lg @error('bonus_amount') is-invalid @enderror" 
                                   id="bonus_amount" 
                                   name="bonus_amount" 
                                   value="{{ $settings['referral_bonus_amount'] }}"
                                   min="0"
                                   max="10000"
                                   required>
                            <small class="text-muted">Pièces pour le parrain</small>
                            @error('bonus_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save"></i> Mettre à Jour
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-success" style="border-width: 2px;">
                <div class="card-header text-white" style="background-color: #28a745;">
                    <h5 class="mb-0"><i class="bi bi-person-plus-fill"></i> Bonus Nouveau</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.referrals.update-new-user-bonus') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="new_user_bonus_amount" class="form-label">Montant (pièces)</label>
                            <input type="number" 
                                   class="form-control form-control-lg @error('new_user_bonus_amount') is-invalid @enderror" 
                                   id="new_user_bonus_amount" 
                                   name="new_user_bonus_amount" 
                                   value="{{ $settings['new_user_bonus_amount'] }}"
                                   min="0"
                                   max="10000"
                                   required>
                            <small class="text-muted">Pièces pour le nouveau utilisateur</small>
                            @error('new_user_bonus_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-save"></i> Mettre à Jour
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-primary" style="border-width: 2px;">
                <div class="card-header text-white" style="background-color: #0d6efd;">
                    <h5 class="mb-0"><i class="bi bi-gear-fill"></i> Paramètres du Bonus</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.referrals.update-bonus') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="bonus_amount" class="form-label">Montant du Bonus (pièces)</label>
                            <input type="number" 
                                   class="form-control form-control-lg @error('bonus_amount') is-invalid @enderror" 
                                   id="bonus_amount" 
                                   name="bonus_amount" 
                                   value="{{ $settings['referral_bonus_amount'] }}"
                                   min="0"
                                   max="10000"
                                   required>
                            <small class="text-muted">Pièces attribuées au parrain quand quelqu'un utilise son code</small>
                            @error('bonus_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save"></i> Mettre à Jour le Bonus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-warning" style="border-width: 2px;">
                <div class="card-header text-white" style="background-color: #ffc107;">
                    <h5 class="mb-0"><i class="bi bi-toggle-on"></i> Activation</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.referrals.toggle-system') }}" method="POST">
                        @csrf
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   role="switch" 
                                   id="enabled" 
                                   name="enabled" 
                                   value="1"
                                   {{ $settings['referral_enabled'] ? 'checked' : '' }}
                                   onchange="this.form.submit()">
                            <label class="form-check-label" for="enabled">
                                <strong>Système {{ $settings['referral_enabled'] ? 'Activé' : 'Désactivé' }}</strong>
                            </label>
                        </div>
                    </form>
                    
                    <div class="alert {{ $settings['referral_enabled'] ? 'alert-success' : 'alert-warning' }} mb-0">
                        <i class="bi {{ $settings['referral_enabled'] ? 'bi-check-circle' : 'bi-exclamation-triangle' }}"></i>
                        {{ $settings['referral_enabled'] 
                            ? 'Codes actifs' 
                            : 'Codes désactivés' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="card mb-4" style="border: 2px solid #17a2b8;">
        <div class="card-header text-white" style="background-color: #17a2b8;">
            <h5 class="mb-0"><i class="bi bi-info-circle-fill"></i> Comment ça marche</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="bi bi-gift text-primary"></i> Bonus Parrain</h6>
                    <p class="text-muted small mb-3">
                        Pièces attribuées à l'utilisateur qui possède le code de parrainage 
                        lorsqu'un nouveau membre s'inscrit avec son code.
                    </p>
                    <p class="mb-0">
                        <strong>Montant actuel:</strong> 
                        <span class="badge bg-primary">{{ number_format($settings['referral_bonus_amount']) }} pièces</span>
                    </p>
                </div>
                <div class="col-md-6">
                    <h6><i class="bi bi-person-plus text-success"></i> Bonus Nouveau Membre</h6>
                    <p class="text-muted small mb-3">
                        Pièces de bienvenue offertes au nouveau membre qui s'inscrit 
                        en utilisant un code de parrainage.
                    </p>
                    <p class="mb-0">
                        <strong>Montant actuel:</strong> 
                        <span class="badge bg-success">{{ number_format($settings['new_user_bonus_amount']) }} pièces</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <i class="bi bi-people-fill" style="font-size: 2.5rem;"></i>
                    <h3 class="mb-0 mt-2" style="font-weight: 700;">{{ number_format($stats['total_referrals']) }}</h3>
                    <small>Parrainages Totaux</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <i class="bi bi-hourglass-split" style="font-size: 2.5rem;"></i>
                    <h3 class="mb-0 mt-2" style="font-weight: 700;">{{ number_format($stats['pending_referrals']) }}</h3>
                    <small>En Attente</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white">
                    <i class="bi bi-check-circle-fill" style="font-size: 2.5rem;"></i>
                    <h3 class="mb-0 mt-2" style="font-weight: 700;">{{ number_format($stats['credited_referrals']) }}</h3>
                    <small>Crédités</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-0" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="card-body text-white">
                    <i class="bi bi-coin" style="font-size: 2.5rem;"></i>
                    <h3 class="mb-0 mt-2" style="font-weight: 700;">{{ number_format($stats['total_bonus_paid']) }}</h3>
                    <small>Pièces Distribuées</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Referrers -->
    <div class="card mb-4" style="border: 2px solid #0d6efd;">
        <div class="card-header text-white" style="background-color: #0d6efd;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-trophy-fill"></i> Top 10 Parrains</h5>
                <a href="{{ route('admin.referrals.top-referrers') }}" class="btn btn-sm btn-light">
                    Voir Tout
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
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
                                @if($index < 3)
                                    <span class="badge" style="background: {{ $index == 0 ? '#FFD700' : ($index == 1 ? '#C0C0C0' : '#CD7F32') }}">
                                        {{ $index + 1 }}
                                    </span>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                                         alt="{{ $user->name }}" 
                                         class="rounded-circle me-2" 
                                         style="width: 35px; height: 35px; object-fit: cover;">
                                    @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                         style="width: 35px; height: 35px; font-size: 0.9rem;">
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
                                <span class="badge" style="background-color: #ffc107; color: #000;">
                                    <i class="bi bi-coin"></i> {{ number_format($user->referral_earnings) }}
                                </span>
                            </td>
                            <td>
                                <code>{{ $user->referral_code }}</code>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
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
    <div class="card" style="border: 2px solid #28a745;">
        <div class="card-header text-white" style="background-color: #28a745;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Parrainages Récents</h5>
                <a href="{{ route('admin.referrals.all') }}" class="btn btn-sm btn-light">
                    Voir Tout
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
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
                                <strong>
                                    {{ $referral->referrer ? $referral->referrer->name : 'Utilisateur supprimé' }}
                                </strong>
                            </td>
                            <td>
                                {{ $referral->referred ? $referral->referred->name : 'Utilisateur supprimé' }}
                            </td>
                            <td><code>{{ $referral->referral_code }}</code></td>
                            <td>
                                <span class="badge" style="background-color: #ffc107; color: #000;">
                                    {{ number_format($referral->bonus_amount) }} pièces
                                </span>
                            </td>
                            <td>
                                @if($referral->status === 'credited')
                                    <span class="badge bg-success">Crédité</span>
                                @elseif($referral->status === 'pending')
                                    <span class="badge bg-warning">En attente</span>
                                @else
                                    <span class="badge bg-danger">Annulé</span>
                                @endif
                            </td>
                            <td>{{ $referral->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
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
