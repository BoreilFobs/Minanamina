@extends('layouts.app')

@section('title', 'Détails de la Conversion - Minanamina')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Détails de la Conversion</h1>
                <p class="text-muted mb-0">Référence: #{{ $conversion->id }}</p>
            </div>
            <a href="{{ route('rewards.conversions') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Conversion Info Card -->
        <div class="col-md-8">
            <div class="card mb-4" style="border: 2px solid #0d6efd;">
                <div class="card-header text-white" style="background-color: #0d6efd;">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informations de la Conversion</h5>
                </div>
                <div class="card-body">
                    <!-- Amount Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded text-center">
                                <small class="text-muted d-block mb-2">Pièces Converties</small>
                                <h2 class="mb-0 text-primary">
                                    <i class="bi bi-coin"></i> {{ number_format($conversion->pieces_amount) }}
                                </h2>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded text-center">
                                <small class="text-muted d-block mb-2">Montant à Recevoir</small>
                                <h2 class="mb-0 text-success">
                                    <i class="bi bi-cash"></i> {{ number_format($conversion->cash_amount, 0) }} CFA
                                </h2>
                            </div>
                        </div>
                    </div>

                    <!-- Conversion Details -->
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 40%;">Taux de Conversion:</th>
                            <td>1 pièce = {{ $conversion->conversion_rate }} CFA</td>
                        </tr>
                        <tr>
                            <th>Méthode de Paiement:</th>
                            <td>
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
                                    {{ $methodLabels[$conversion->payment_method] ?? $conversion->payment_method }}
                                </span>
                            </td>
                        </tr>
                        @if($conversion->payment_phone)
                        <tr>
                            <th>Numéro de Téléphone:</th>
                            <td><strong>{{ $conversion->payment_phone }}</strong></td>
                        </tr>
                        @endif
                        @if($conversion->payment_email)
                        <tr>
                            <th>Email PayPal:</th>
                            <td><strong>{{ $conversion->payment_email }}</strong></td>
                        </tr>
                        @endif
                        @if($conversion->payment_account)
                        <tr>
                            <th>Compte Bancaire:</th>
                            <td><strong>{{ $conversion->payment_account }}</strong></td>
                        </tr>
                        @endif
                        <tr>
                            <th>Date de Demande:</th>
                            <td>{{ $conversion->created_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        @if($conversion->processed_at)
                        <tr>
                            <th>Date de Traitement:</th>
                            <td>{{ $conversion->processed_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        @endif
                        @if($conversion->completed_at)
                        <tr>
                            <th>Date de Complétion:</th>
                            <td class="text-success">
                                <i class="bi bi-check-circle"></i> {{ $conversion->completed_at->format('d/m/Y à H:i') }}
                            </td>
                        </tr>
                        @endif
                        @if($conversion->rejected_at)
                        <tr>
                            <th>Date de Rejet:</th>
                            <td class="text-danger">
                                <i class="bi bi-x-circle"></i> {{ $conversion->rejected_at->format('d/m/Y à H:i') }}
                            </td>
                        </tr>
                        @endif
                    </table>

                    <!-- Rejection Reason -->
                    @if($conversion->status === 'rejected' && $conversion->rejection_reason)
                    <div class="alert alert-danger">
                        <h6 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Raison du Rejet</h6>
                        <p class="mb-0">{{ $conversion->rejection_reason }}</p>
                    </div>
                    @endif

                    <!-- Admin Notes -->
                    @if($conversion->admin_notes)
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="bi bi-chat-left-text"></i> Notes de l'Administrateur</h6>
                        <p class="mb-0">{{ $conversion->admin_notes }}</p>
                    </div>
                    @endif

                    <!-- Transaction Reference -->
                    @if($conversion->transaction_reference)
                    <div class="alert alert-success">
                        <h6 class="alert-heading"><i class="bi bi-receipt"></i> Référence de Transaction</h6>
                        <p class="mb-0"><strong>{{ $conversion->transaction_reference }}</strong></p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="col-md-4">
            <div class="card" style="border: 2px solid #0d6efd;">
                <div class="card-header text-white" style="background-color: #0d6efd;">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Statut</h5>
                </div>
                <div class="card-body">
                    <!-- Current Status -->
                    <div class="text-center mb-4">
                        @php
                            $statusConfig = [
                                'pending' => ['icon' => 'clock', 'color' => 'warning', 'label' => 'En Attente', 'desc' => 'Votre demande est en attente de traitement'],
                                'processing' => ['icon' => 'arrow-repeat', 'color' => 'info', 'label' => 'En Traitement', 'desc' => 'Votre demande est en cours de traitement'],
                                'completed' => ['icon' => 'check-circle', 'color' => 'success', 'label' => 'Complété', 'desc' => 'Le paiement a été effectué'],
                                'rejected' => ['icon' => 'x-circle', 'color' => 'danger', 'label' => 'Rejeté', 'desc' => 'Votre demande a été rejetée'],
                            ];
                            $config = $statusConfig[$conversion->status] ?? ['icon' => 'question', 'color' => 'secondary', 'label' => $conversion->status, 'desc' => ''];
                        @endphp
                        
                        <div class="text-{{ $config['color'] }} mb-3">
                            <i class="bi bi-{{ $config['icon'] }}" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="text-{{ $config['color'] }}">{{ $config['label'] }}</h4>
                        <p class="text-muted small">{{ $config['desc'] }}</p>
                    </div>

                    <!-- Timeline -->
                    <div class="timeline">
                        <!-- Created -->
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="bi bi-check text-white"></i>
                                </div>
                            </div>
                            <div>
                                <strong>Demande Créée</strong>
                                <br><small class="text-muted">{{ $conversion->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>

                        <!-- Processing -->
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <div class="bg-{{ $conversion->processed_at ? 'success' : 'secondary' }} rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="bi bi-{{ $conversion->processed_at ? 'check' : 'clock' }} text-white"></i>
                                </div>
                            </div>
                            <div>
                                <strong>En Traitement</strong>
                                <br><small class="text-muted">
                                    {{ $conversion->processed_at ? $conversion->processed_at->format('d/m/Y H:i') : 'En attente...' }}
                                </small>
                            </div>
                        </div>

                        <!-- Completed or Rejected -->
                        @if($conversion->status === 'completed')
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="bi bi-check text-white"></i>
                                </div>
                            </div>
                            <div>
                                <strong>Paiement Effectué</strong>
                                <br><small class="text-muted">{{ $conversion->completed_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @elseif($conversion->status === 'rejected')
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="bi bi-x text-white"></i>
                                </div>
                            </div>
                            <div>
                                <strong>Demande Rejetée</strong>
                                <br><small class="text-muted">{{ $conversion->rejected_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @else
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="bi bi-clock text-white"></i>
                                </div>
                            </div>
                            <div>
                                <strong>Finalisation</strong>
                                <br><small class="text-muted">En attente...</small>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Help Text -->
                    <div class="alert alert-info mt-4">
                        <small>
                            <i class="bi bi-info-circle"></i> 
                            Le délai de traitement habituel est de 1 à 3 jours ouvrables.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
