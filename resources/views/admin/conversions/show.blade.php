@extends('layouts.admin')

@section('title', 'Détails de la Conversion #' . $conversion->id)
@section('page-title', 'Conversion #' . $conversion->id)

@push('styles')
<style>
    .admin-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .admin-card__header {
        padding: 1rem 1.25rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .admin-card__header.primary {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
    }
    
    .admin-card__header.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .admin-card__header.info {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        color: white;
    }
    
    .admin-card__header.warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
    
    .admin-card__header.danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    .admin-card__body {
        padding: 1.5rem;
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.5rem;
    }
    
    .user-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .info-table {
        width: 100%;
    }
    
    .info-table th {
        width: 40%;
        padding: 0.5rem 0;
        font-weight: 500;
        color: #6b7280;
    }
    
    .info-table td {
        padding: 0.5rem 0;
        font-weight: 600;
        color: #1f2937;
    }
    
    .amount-highlight {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        margin-bottom: 1rem;
    }
    
    .amount-highlight__value {
        font-size: 2rem;
        font-weight: 700;
        color: #059669;
    }
    
    .amount-highlight__label {
        font-size: 0.85rem;
        color: #6b7280;
    }
    
    .status-display {
        text-align: center;
        padding: 1.5rem;
    }
    
    .status-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }
    
    .status-icon.pending { color: #f59e0b; }
    .status-icon.approved { color: #3b82f6; }
    .status-icon.processing { color: #6366f1; }
    .status-icon.completed { color: #10b981; }
    .status-icon.rejected { color: #ef4444; }
    
    .status-label {
        font-size: 1.25rem;
        font-weight: 600;
    }
    
    .status-label.pending { color: #f59e0b; }
    .status-label.approved { color: #3b82f6; }
    .status-label.processing { color: #6366f1; }
    .status-label.completed { color: #10b981; }
    .status-label.rejected { color: #ef4444; }
    
    .timeline-item {
        padding-bottom: 1rem;
        border-left: 2px solid #e5e7eb;
        padding-left: 1rem;
        margin-left: 0.5rem;
    }
    
    .timeline-item:last-child {
        border-left: 2px solid #10b981;
    }
    
    .timeline-item__label {
        font-size: 0.75rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .timeline-item__value {
        font-weight: 600;
        color: #1f2937;
    }
    
    .timeline-item__note {
        font-size: 0.85rem;
        color: #6b7280;
    }
    
    .btn--primary {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .btn--primary:hover { color: white; transform: translateY(-1px); }
    
    .btn--success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .btn--success:hover { color: white; transform: translateY(-1px); }
    
    .btn--danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .btn--danger:hover { color: white; transform: translateY(-1px); }
    
    .btn--ghost {
        background: transparent;
        color: #6b7280;
        border: 1px solid #e5e7eb;
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }
    
    .btn--ghost:hover { background: #f3f4f6; color: #374151; }
    
    .alert-modern {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .alert-modern.warning {
        background: rgba(245, 158, 11, 0.1);
        color: #92400e;
    }
    
    .alert-modern.danger {
        background: rgba(239, 68, 68, 0.1);
        color: #991b1b;
    }
    
    .alert-modern.info {
        background: rgba(59, 130, 246, 0.1);
        color: #1e40af;
    }
    
    .payment-badge {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .modal-content {
        border-radius: 16px;
        overflow: hidden;
        border: none;
    }
    
    .modal-header.danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    .modal-header.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="admin-page">
    <div class="admin-page__header d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="admin-page__title">
                <i class="bi bi-cash-stack me-2"></i>Conversion #{{ $conversion->id }}
            </h1>
            <p class="admin-page__subtitle">Gérer la demande de conversion</p>
        </div>
        <a href="{{ route('admin.conversions.index') }}" class="btn--ghost">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <!-- Main Info Column -->
        <div class="col-md-8">
            <!-- User & Conversion Info Card -->
            <div class="admin-card">
                <div class="admin-card__header primary">
                    <i class="bi bi-person-circle"></i> Informations de l'Utilisateur
                </div>
                <div class="admin-card__body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="user-info">
                                <div class="user-avatar">
                                    @if($conversion->user->avatar)
                                    <img src="{{ asset('storage/' . $conversion->user->avatar) }}" alt="{{ $conversion->user->name }}">
                                    @else
                                    {{ strtoupper(substr($conversion->user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $conversion->user->name }}</h5>
                                    <small class="text-muted">{{ $conversion->user->phone }}</small>
                                </div>
                            </div>
                            <table class="info-table">
                                <tr>
                                    <th>Solde Actuel:</th>
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
                            <div class="amount-highlight">
                                <div class="amount-highlight__label">Montant de la Conversion</div>
                                <div class="amount-highlight__value">{{ number_format($conversion->cash_amount, 0) }} CFA</div>
                                <div class="amount-highlight__label">({{ number_format($conversion->pieces_amount) }} pièces)</div>
                            </div>
                            <table class="info-table">
                                <tr>
                                    <th>Taux de Conversion:</th>
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
            <div class="admin-card">
                <div class="admin-card__header success">
                    <i class="bi bi-wallet2"></i> Détails de Paiement
                </div>
                <div class="admin-card__body">
                    <table class="info-table">
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
                            <td><span class="payment-badge">{{ $conversion->payment_phone }}</span></td>
                        </tr>
                        @endif
                        @if($conversion->payment_email)
                        <tr>
                            <th>Email PayPal:</th>
                            <td><span class="payment-badge">{{ $conversion->payment_email }}</span></td>
                        </tr>
                        @endif
                        @if($conversion->payment_account)
                        <tr>
                            <th>Compte Bancaire:</th>
                            <td><span class="payment-badge">{{ $conversion->payment_account }}</span></td>
                        </tr>
                        @endif
                        @if($conversion->transaction_reference)
                        <tr>
                            <th>Référence de Transaction:</th>
                            <td><span class="payment-badge">{{ $conversion->transaction_reference }}</span></td>
                        </tr>
                        @endif
                        @if($conversion->payment_proof)
                        <tr>
                            <th>Preuve de Paiement:</th>
                            <td>
                                <a href="{{ asset('storage/' . $conversion->payment_proof) }}" target="_blank" class="btn--ghost" style="width: auto; padding: 0.5rem 0.75rem;">
                                    <i class="bi bi-file-earmark-pdf"></i> Voir le fichier
                                </a>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Admin Notes Card -->
            <div class="admin-card">
                <div class="admin-card__header info">
                    <i class="bi bi-chat-left-text"></i> Notes de l'Administrateur
                </div>
                <div class="admin-card__body">
                    @if($conversion->admin_notes)
                    <div class="alert-modern info mb-3">
                        <i class="bi bi-info-circle"></i>
                        <span>{{ $conversion->admin_notes }}</span>
                    </div>
                    @endif

                    <form action="{{ route('admin.conversions.notes', $conversion) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="admin_notes" class="form-label fw-semibold">Ajouter/Modifier des Notes</label>
                            <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" style="border-radius: 10px;">{{ old('admin_notes', $conversion->admin_notes) }}</textarea>
                        </div>
                        <button type="submit" class="btn--primary" style="width: auto;">
                            <i class="bi bi-save"></i> Enregistrer les Notes
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Actions Column -->
        <div class="col-md-4">
            <!-- Status Card -->
            <div class="admin-card">
                <div class="admin-card__header primary">
                    <i class="bi bi-flag"></i> Statut
                </div>
                <div class="admin-card__body">
                    <div class="status-display">
                        @php
                            $statusConfig = [
                                'pending' => ['clock', 'En Attente'],
                                'approved' => ['check-circle', 'Approuvé'],
                                'processing' => ['arrow-repeat', 'En Traitement'],
                                'completed' => ['check-circle-fill', 'Complété'],
                                'rejected' => ['x-circle', 'Rejeté'],
                            ];
                            $config = $statusConfig[$conversion->status] ?? ['question', $conversion->status];
                        @endphp
                        
                        <div class="status-icon {{ $conversion->status }}">
                            <i class="bi bi-{{ $config[0] }}"></i>
                        </div>
                        <div class="status-label {{ $conversion->status }}">{{ $config[1] }}</div>
                    </div>

                    <!-- Timeline -->
                    <div class="mt-4">
                        <div class="timeline-item">
                            <div class="timeline-item__label">Créée</div>
                            <div class="timeline-item__value">{{ $conversion->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        @if($conversion->approved_at)
                        <div class="timeline-item">
                            <div class="timeline-item__label">Approuvée</div>
                            <div class="timeline-item__value">{{ $conversion->approved_at->format('d/m/Y H:i') }}</div>
                            @if($conversion->approver)
                            <div class="timeline-item__note">Par: {{ $conversion->approver->name }}</div>
                            @endif
                        </div>
                        @endif
                        @if($conversion->processed_at)
                        <div class="timeline-item">
                            <div class="timeline-item__label">En Traitement</div>
                            <div class="timeline-item__value">{{ $conversion->processed_at->format('d/m/Y H:i') }}</div>
                        </div>
                        @endif
                        @if($conversion->completed_at)
                        <div class="timeline-item">
                            <div class="timeline-item__label">Complétée</div>
                            <div class="timeline-item__value">{{ $conversion->completed_at->format('d/m/Y H:i') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="admin-card">
                <div class="admin-card__header warning">
                    <i class="bi bi-gear"></i> Actions
                </div>
                <div class="admin-card__body">
                    @if($conversion->status === 'pending')
                    <!-- Approve Button -->
                    <form action="{{ route('admin.conversions.approve', $conversion) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn--success" onclick="return confirm('Confirmer l\'approbation de cette conversion?')">
                            <i class="bi bi-check-circle"></i> Approuver
                        </button>
                    </form>

                    <!-- Reject Button -->
                    <button type="button" class="btn--danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="bi bi-x-circle"></i> Rejeter
                    </button>
                    @endif

                    @if($conversion->status === 'approved')
                    <!-- Mark as Processing -->
                    <form action="{{ route('admin.conversions.processing', $conversion) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn--primary">
                            <i class="bi bi-arrow-repeat"></i> Marquer En Traitement
                        </button>
                    </form>

                    <!-- Mark as Completed -->
                    <button type="button" class="btn--success" data-bs-toggle="modal" data-bs-target="#completeModal">
                        <i class="bi bi-check-circle-fill"></i> Marquer Complété
                    </button>
                    @endif

                    @if($conversion->status === 'processing')
                    <!-- Mark as Completed -->
                    <button type="button" class="btn--success" data-bs-toggle="modal" data-bs-target="#completeModal">
                        <i class="bi bi-check-circle-fill"></i> Marquer Complété
                    </button>
                    @endif

                    @if($conversion->status === 'rejected' && $conversion->rejection_reason)
                    <div class="alert-modern danger">
                        <i class="bi bi-x-circle"></i>
                        <div>
                            <strong>Raison du rejet:</strong><br>
                            {{ $conversion->rejection_reason }}
                        </div>
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
                <div class="modal-header danger">
                    <h5 class="modal-title"><i class="bi bi-x-circle me-2"></i> Rejeter la Conversion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert-modern warning mb-3">
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>Les pièces seront automatiquement remboursées à l'utilisateur.</span>
                    </div>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label fw-semibold">Raison du Rejet <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required style="border-radius: 10px;"></textarea>
                        <small class="text-muted">Minimum 10 caractères</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Annuler</button>
                    <button type="submit" class="btn--danger" style="width: auto;">
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
                <div class="modal-header success">
                    <h5 class="modal-title"><i class="bi bi-check-circle me-2"></i> Marquer comme Complété</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="transaction_reference" class="form-label fw-semibold">Référence de Transaction <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="transaction_reference" name="transaction_reference" required style="border-radius: 10px;">
                        <small class="text-muted">Numéro de référence du paiement effectué</small>
                    </div>
                    <div class="mb-3">
                        <label for="payment_proof" class="form-label fw-semibold">Preuve de Paiement (Optionnel)</label>
                        <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept=".pdf,.jpg,.jpeg,.png" style="border-radius: 10px;">
                        <small class="text-muted">PDF, JPG, PNG (Max 2MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Annuler</button>
                    <button type="submit" class="btn--success" style="width: auto;">
                        <i class="bi bi-check-circle"></i> Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
