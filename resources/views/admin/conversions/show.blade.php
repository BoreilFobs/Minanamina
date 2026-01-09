@extends('layouts.admin')

@section('title', 'Détails de la Conversion #' . $conversion->id)
@section('page-title', 'Conversion #' . $conversion->id)

@section('content')
<div class="admin-page">
    <div class="admin-page__header d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="admin-page__title">
                <i class="bi bi-cash-stack me-2"></i>Conversion #{{ $conversion->id }}
            </h1>
            <p class="admin-page__subtitle">Gérer la demande de conversion</p>
        </div>
        <a href="{{ route('admin.conversions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <!-- Main Info Column -->
        <div class="col-md-8">
            <!-- User & Conversion Info Card -->
            <div class="card mb-4" style="border: 2px solid #0d6efd;">
                <div class="card-header text-white" style="background-color: #0d6efd;">
                    <h5 class="mb-0"><i class="bi bi-person-circle"></i> Informations de l'Utilisateur</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                @if($conversion->user->avatar)
                                <img src="{{ asset('storage/' . $conversion->user->avatar) }}" 
                                     alt="{{ $conversion->user->name }}" 
                                     class="rounded-circle me-3" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" 
                                     style="width: 60px; height: 60px; font-size: 1.5rem;">
                                    {{ strtoupper(substr($conversion->user->name, 0, 1)) }}
                                </div>
                                @endif
                                <div>
                                    <h5 class="mb-0">{{ $conversion->user->name }}</h5>
                                    <small class="text-muted">{{ $conversion->user->phone }}</small>
                                </div>
                            </div>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th style="width: 40%;">Solde Actuel:</th>
                                    <td><strong>{{ number_format($conversion->user->pieces_balance) }}</strong> pièces</td>
                                </tr>
                                <tr>
                                    <th>Total Conversions:</th>
                                    <td>{{ $conversion->user->conversionRequests->count() }}</td>
                                </tr>
                                <tr>
                                    <th>Membre Depuis:</th>
                                    <td>{{ $conversion->user->created_at->format('d/m/Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded text-center mb-3">
                                <small class="text-muted d-block">Montant de la Conversion</small>
                                <h2 class="text-success mb-0">{{ number_format($conversion->cash_amount, 0) }} CFA</h2>
                                <small class="text-muted">({{ number_format($conversion->pieces_amount) }} pièces)</small>
                            </div>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th style="width: 50%;">Taux de Conversion:</th>
                                    <td>{{ $conversion->conversion_rate }} CFA/pièce</td>
                                </tr>
                                <tr>
                                    <th>Date de Demande:</th>
                                    <td>{{ $conversion->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Details Card -->
            <div class="card mb-4" style="border: 2px solid #28a745;">
                <div class="card-header text-white" style="background-color: #28a745;">
                    <h5 class="mb-0"><i class="bi bi-wallet2"></i> Détails de Paiement</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 30%;">Méthode de Paiement:</th>
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
                                <strong>{{ $methodLabels[$conversion->payment_method] ?? $conversion->payment_method }}</strong>
                            </td>
                        </tr>
                        @if($conversion->payment_phone)
                        <tr>
                            <th>Numéro de Téléphone:</th>
                            <td><strong class="text-primary">{{ $conversion->payment_phone }}</strong></td>
                        </tr>
                        @endif
                        @if($conversion->payment_email)
                        <tr>
                            <th>Email PayPal:</th>
                            <td><strong class="text-primary">{{ $conversion->payment_email }}</strong></td>
                        </tr>
                        @endif
                        @if($conversion->payment_account)
                        <tr>
                            <th>Compte Bancaire:</th>
                            <td><strong class="text-primary">{{ $conversion->payment_account }}</strong></td>
                        </tr>
                        @endif
                        @if($conversion->transaction_reference)
                        <tr>
                            <th>Référence de Transaction:</th>
                            <td><span class="badge bg-success">{{ $conversion->transaction_reference }}</span></td>
                        </tr>
                        @endif
                        @if($conversion->payment_proof)
                        <tr>
                            <th>Preuve de Paiement:</th>
                            <td>
                                <a href="{{ asset('storage/' . $conversion->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-earmark-pdf"></i> Voir le fichier
                                </a>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Admin Notes Card -->
            <div class="card" style="border: 2px solid #17a2b8;">
                <div class="card-header text-white" style="background-color: #17a2b8;">
                    <h5 class="mb-0"><i class="bi bi-chat-left-text"></i> Notes de l'Administrateur</h5>
                </div>
                <div class="card-body">
                    @if($conversion->admin_notes)
                    <div class="alert alert-info mb-3">
                        {{ $conversion->admin_notes }}
                    </div>
                    @endif

                    <form action="{{ route('admin.conversions.notes', $conversion) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Ajouter/Modifier des Notes</label>
                            <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3">{{ old('admin_notes', $conversion->admin_notes) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Enregistrer les Notes
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Actions Column -->
        <div class="col-md-4">
            <!-- Status Card -->
            <div class="card mb-4" style="border: 2px solid #0d6efd;">
                <div class="card-header text-white" style="background-color: #0d6efd;">
                    <h5 class="mb-0"><i class="bi bi-flag"></i> Statut</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @php
                            $statusConfig = [
                                'pending' => ['clock', 'warning', 'En Attente'],
                                'approved' => ['check-circle', 'info', 'Approuvé'],
                                'processing' => ['arrow-repeat', 'primary', 'En Traitement'],
                                'completed' => ['check-circle-fill', 'success', 'Complété'],
                                'rejected' => ['x-circle', 'danger', 'Rejeté'],
                            ];
                            $config = $statusConfig[$conversion->status] ?? ['question', 'secondary', $conversion->status];
                        @endphp
                        
                        <div class="text-{{ $config[1] }} mb-3">
                            <i class="bi bi-{{ $config[0] }}" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="text-{{ $config[1] }}">{{ $config[2] }}</h4>
                    </div>

                    <!-- Timeline -->
                    <div class="timeline">
                        <div class="mb-3">
                            <small class="text-muted d-block">Créée</small>
                            <strong>{{ $conversion->created_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        @if($conversion->approved_at)
                        <div class="mb-3">
                            <small class="text-muted d-block">Approuvée</small>
                            <strong>{{ $conversion->approved_at->format('d/m/Y H:i') }}</strong>
                            @if($conversion->approver)
                            <br><small>Par: {{ $conversion->approver->name }}</small>
                            @endif
                        </div>
                        @endif
                        @if($conversion->processed_at)
                        <div class="mb-3">
                            <small class="text-muted d-block">En Traitement</small>
                            <strong>{{ $conversion->processed_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        @endif
                        @if($conversion->completed_at)
                        <div class="mb-3">
                            <small class="text-muted d-block">Complétée</small>
                            <strong>{{ $conversion->completed_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card" style="border: 2px solid #ffc107;">
                <div class="card-header text-dark" style="background-color: #ffc107;">
                    <h5 class="mb-0"><i class="bi bi-gear"></i> Actions</h5>
                </div>
                <div class="card-body">
                    @if($conversion->status === 'pending')
                    <!-- Approve Button -->
                    <form action="{{ route('admin.conversions.approve', $conversion) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Confirmer l\'approbation de cette conversion?')">
                            <i class="bi bi-check-circle"></i> Approuver
                        </button>
                    </form>

                    <!-- Reject Button -->
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="bi bi-x-circle"></i> Rejeter
                    </button>
                    @endif

                    @if($conversion->status === 'approved')
                    <!-- Mark as Processing -->
                    <form action="{{ route('admin.conversions.processing', $conversion) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-arrow-repeat"></i> Marquer En Traitement
                        </button>
                    </form>

                    <!-- Mark as Completed -->
                    <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#completeModal">
                        <i class="bi bi-check-circle-fill"></i> Marquer Complété
                    </button>
                    @endif

                    @if($conversion->status === 'processing')
                    <!-- Mark as Completed -->
                    <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#completeModal">
                        <i class="bi bi-check-circle-fill"></i> Marquer Complété
                    </button>
                    @endif

                    @if($conversion->status === 'rejected' && $conversion->rejection_reason)
                    <div class="alert alert-danger">
                        <strong>Raison du rejet:</strong><br>
                        {{ $conversion->rejection_reason }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.conversions.reject', $conversion) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-x-circle"></i> Rejeter la Conversion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> Les pièces seront automatiquement remboursées à l'utilisateur.
                    </div>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Raison du Rejet <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                        <small class="text-muted">Minimum 10 caractères</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle"></i> Rejeter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Complete Modal -->
<div class="modal fade" id="completeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.conversions.completed', $conversion) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-check-circle"></i> Marquer comme Complété</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="transaction_reference" class="form-label">Référence de Transaction <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="transaction_reference" name="transaction_reference" required>
                        <small class="text-muted">Numéro de référence du paiement effectué</small>
                    </div>
                    <div class="mb-3">
                        <label for="payment_proof" class="form-label">Preuve de Paiement (Optionnel)</label>
                        <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">PDF, JPG, PNG (Max 2MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
