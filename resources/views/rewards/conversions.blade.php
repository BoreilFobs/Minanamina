@extends('layouts.app')

@section('title', 'Mes Conversions - Minanamina')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Mes Conversions</h1>
                <p class="text-muted mb-0">Suivez l'état de vos demandes de conversion</p>
            </div>
            <div>
                <a href="{{ route('rewards.convert.form') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouvelle Conversion
                </a>
                <a href="{{ route('rewards.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-primary" style="border-width: 2px;">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="bi bi-list-check" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="mb-0" style="font-weight: 700;">{{ $stats['total_requests'] }}</h3>
                    <small class="text-muted">Total Demandes</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-warning" style="border-width: 2px;">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="bi bi-clock" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="mb-0" style="font-weight: 700;">{{ $stats['pending_requests'] }}</h3>
                    <small class="text-muted">En Attente</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-success" style="border-width: 2px;">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="mb-0" style="font-weight: 700;">{{ $stats['completed_requests'] }}</h3>
                    <small class="text-muted">Complétées</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-info" style="border-width: 2px;">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="bi bi-cash-stack" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="mb-0" style="font-weight: 700;">{{ number_format($stats['total_converted_cash'], 0) }}</h3>
                    <small class="text-muted">CFA Convertis</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Conversions List -->
    <div class="card" style="border: 2px solid #0d6efd;">
        <div class="card-header text-white" style="background-color: #0d6efd;">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> Historique des Conversions</h5>
        </div>
        <div class="card-body">
            @forelse($conversions as $conversion)
            <div class="card mb-3 {{ $conversion->status === 'rejected' ? 'border-danger' : ($conversion->status === 'completed' ? 'border-success' : 'border-warning') }}" style="border-width: 2px;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Status Badge -->
                        <div class="col-md-2 text-center">
                            @php
                                $statusConfig = [
                                    'pending' => ['icon' => 'clock', 'color' => 'warning', 'label' => 'En Attente'],
                                    'processing' => ['icon' => 'arrow-repeat', 'color' => 'info', 'label' => 'En Traitement'],
                                    'completed' => ['icon' => 'check-circle', 'color' => 'success', 'label' => 'Complété'],
                                    'rejected' => ['icon' => 'x-circle', 'color' => 'danger', 'label' => 'Rejeté'],
                                ];
                                $config = $statusConfig[$conversion->status] ?? ['icon' => 'question', 'color' => 'secondary', 'label' => $conversion->status];
                            @endphp
                            <div class="text-{{ $config['color'] }} mb-2">
                                <i class="bi bi-{{ $config['icon'] }}" style="font-size: 3rem;"></i>
                            </div>
                            <span class="badge bg-{{ $config['color'] }}">{{ $config['label'] }}</span>
                        </div>

                        <!-- Conversion Details -->
                        <div class="col-md-6">
                            <h5 class="mb-1">
                                <i class="bi bi-coin text-primary"></i> {{ number_format($conversion->pieces_amount) }} pièces
                                <i class="bi bi-arrow-right mx-2"></i>
                                <i class="bi bi-cash text-success"></i> {{ number_format($conversion->cash_amount, 0) }} CFA
                            </h5>
                            <p class="text-muted mb-1">
                                <i class="bi bi-calendar"></i> {{ $conversion->created_at->format('d/m/Y à H:i') }}
                            </p>
                            <p class="mb-0">
                                @php
                                    $methodLabels = [
                                        'orange_money' => 'Orange Money',
                                        'mtn_mobile_money' => 'MTN Mobile Money',
                                        'wave' => 'Wave',
                                        'bank_transfer' => 'Virement Bancaire',
                                        'paypal' => 'PayPal',
                                    ];
                                @endphp
                                <span class="badge bg-secondary">
                                    <i class="bi bi-wallet2"></i> {{ $methodLabels[$conversion->payment_method] ?? $conversion->payment_method }}
                                </span>
                                @if($conversion->payment_phone)
                                    <small class="text-muted ms-2">{{ $conversion->payment_phone }}</small>
                                @endif
                                @if($conversion->payment_email)
                                    <small class="text-muted ms-2">{{ $conversion->payment_email }}</small>
                                @endif
                            </p>
                        </div>

                        <!-- Timeline -->
                        <div class="col-md-3">
                            <small class="text-muted d-block">
                                <i class="bi bi-clock-history"></i> Créée: {{ $conversion->created_at->diffForHumans() }}
                            </small>
                            @if($conversion->processed_at)
                            <small class="text-muted d-block">
                                <i class="bi bi-gear"></i> Traitée: {{ $conversion->processed_at->diffForHumans() }}
                            </small>
                            @endif
                            @if($conversion->completed_at)
                            <small class="text-success d-block">
                                <i class="bi bi-check2"></i> Complétée: {{ $conversion->completed_at->diffForHumans() }}
                            </small>
                            @endif
                            @if($conversion->rejected_at)
                            <small class="text-danger d-block">
                                <i class="bi bi-x"></i> Rejetée: {{ $conversion->rejected_at->diffForHumans() }}
                            </small>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="col-md-1 text-center">
                            <a href="{{ route('rewards.conversions.show', $conversion) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Rejection Reason -->
                    @if($conversion->status === 'rejected' && $conversion->rejection_reason)
                    <div class="alert alert-danger mb-0 mt-3">
                        <strong><i class="bi bi-exclamation-triangle"></i> Raison du rejet:</strong>
                        {{ $conversion->rejection_reason }}
                    </div>
                    @endif

                    <!-- Admin Notes -->
                    @if($conversion->admin_notes)
                    <div class="alert alert-info mb-0 mt-3">
                        <strong><i class="bi bi-chat-left-text"></i> Notes:</strong>
                        {{ $conversion->admin_notes }}
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                <h5 class="text-muted mt-3">Aucune conversion pour le moment</h5>
                <p class="text-muted">Commencez par convertir vos pièces en cash</p>
                <a href="{{ route('rewards.convert.form') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-plus-circle"></i> Faire une Conversion
                </a>
            </div>
            @endforelse

            <!-- Pagination -->
            @if($conversions->hasPages())
            <div class="mt-4">
                {{ $conversions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
