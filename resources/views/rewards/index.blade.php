@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 mb-0">Mes Récompenses</h1>
        <p class="text-muted mb-0">Gérez vos pièces et badges</p>
    </div>

    <!-- Balance Cards -->
    <div class="row g-3 mb-4">
        <!-- Current Balance -->
        <div class="col-md-3">
            <div class="card border-primary" style="border-width: 2px;">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="bi bi-coin" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="mb-0" style="font-weight: 700;">{{ number_format($stats['current_balance']) }}</h3>
                    <small class="text-muted">Solde Actuel</small>
                    <div class="text-success small mt-1">
                        ≈ {{ number_format($cashEquivalent, 0) }} CFA
                    </div>
                </div>
            </div>
        </div>

        <!-- Lifetime Earnings -->
        <div class="col-md-3">
            <div class="card border-success" style="border-width: 2px;">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="bi bi-graph-up-arrow" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="mb-0" style="font-weight: 700;">{{ number_format($stats['lifetime_earnings']) }}</h3>
                    <small class="text-muted">Gains Totaux</small>
                </div>
            </div>
        </div>

        <!-- Campaigns Completed -->
        <div class="col-md-3">
            <div class="card border-info" style="border-width: 2px;">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="bi bi-trophy" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="mb-0" style="font-weight: 700;">{{ $stats['total_campaigns_completed'] }}</h3>
                    <small class="text-muted">Campagnes Complétées</small>
                </div>
            </div>
        </div>

        <!-- Badges Earned -->
        <div class="col-md-3">
            <div class="card border-warning" style="border-width: 2px;">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="bi bi-award" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="mb-0" style="font-weight: 700;">{{ $badgeStats['earned_badges'] }}/{{ $badgeStats['total_badges'] }}</h3>
                    <small class="text-muted">Badges Obtenus</small>
                    <div class="progress mt-2" style="height: 5px;">
                        <div class="progress-bar bg-warning" style="width: {{ $badgeStats['completion_percentage'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <a href="{{ route('rewards.convert') }}" class="btn btn-primary w-100 py-3">
                <i class="bi bi-cash-coin"></i> Convertir en Cash
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('rewards.conversions') }}" class="btn btn-outline-primary w-100 py-3">
                <i class="bi bi-list-ul"></i> Mes Conversions
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('campaigns.my-participations') }}" class="btn btn-outline-primary w-100 py-3">
                <i class="bi bi-clipboard-check"></i> Mes Participations
            </a>
        </div>
    </div>

    <!-- Recent Badges -->
    @if($recentBadges->count() > 0)
    <div class="card mb-4" style="border: 2px solid #ffc107;">
        <div class="card-header text-white" style="background-color: #ffc107;">
            <h5 class="mb-0"><i class="bi bi-star-fill"></i> Derniers Badges Obtenus</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($recentBadges as $badge)
                <div class="col-md-4">
                    <div class="d-flex align-items-center p-3 border rounded">
                        <span style="font-size: 2.5rem;" class="me-3">{{ $badge->icon }}</span>
                        <div>
                            <h6 class="mb-0">{{ $badge->name }}</h6>
                            <small class="text-muted">{{ $badge->pivot->awarded_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Badges Section -->
    <div class="card mb-4" style="border: 2px solid #0d6efd;">
        <div class="card-header text-white" style="background-color: #0d6efd;">
            <h5 class="mb-0"><i class="bi bi-award"></i> Tous les Badges</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($badgesWithProgress as $badgeData)
                <div class="col-md-3">
                    @include('components.badge-card', [
                        'badge' => $badgeData['badge'],
                        'isEarned' => $badgeData['is_earned'],
                        'awardedAt' => $badgeData['awarded_at'],
                        'progress' => $badgeData['progress']
                    ])
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card" style="border: 2px solid #0d6efd;">
        <div class="card-header text-white" style="background-color: #0d6efd;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Historique des Transactions</h5>
                <a href="{{ route('rewards.export-transactions') }}" class="btn btn-sm btn-light">
                    <i class="bi bi-download"></i> Exporter CSV
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Montant</th>
                            <th>Solde</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @php
                                    $typeLabels = [
                                        'campaign_completion' => 'Campagne',
                                        'referral_bonus' => 'Parrainage',
                                        'manual_adjustment' => 'Ajustement',
                                        'converted' => 'Conversion',
                                        'refund' => 'Remboursement',
                                    ];
                                    $typeColors = [
                                        'campaign_completion' => 'success',
                                        'referral_bonus' => 'info',
                                        'manual_adjustment' => 'warning',
                                        'converted' => 'danger',
                                        'refund' => 'primary',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $typeColors[$transaction->type] ?? 'secondary' }}">
                                    {{ $typeLabels[$transaction->type] ?? $transaction->type }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $transaction->description }}</small>
                                @if($transaction->campaign)
                                <br><small class="text-muted">{{ $transaction->campaign->title }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold {{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount) }}
                                </span>
                            </td>
                            <td>{{ number_format($transaction->balance_after) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Aucune transaction pour le moment
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
            <div class="mt-3">
                {{ $transactions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
