@extends('layouts.admin')

@section('title', 'Gestion des Conversions')
@section('page-title', 'Conversions')

@push('styles')
<style>
    .stats-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    @media (min-width: 768px) {
        .stats-row {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        border-left: 4px solid;
    }
    
    .stat-card.pending { border-color: #f59e0b; }
    .stat-card.processing { border-color: #3b82f6; }
    .stat-card.completed { border-color: #10b981; }
    .stat-card.total { border-color: var(--primary-color); }
    
    .stat-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .stat-card.pending .stat-icon { color: #f59e0b; }
    .stat-card.processing .stat-icon { color: #3b82f6; }
    .stat-card.completed .stat-icon { color: #10b981; }
    .stat-card.total .stat-icon { color: var(--primary-color); }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
    }
    
    .stat-label {
        font-size: 0.8rem;
        color: #6b7280;
    }
    
    .stat-amount {
        font-size: 0.75rem;
        color: #10b981;
        margin-top: 0.25rem;
    }
    
    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    
    .data-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .table-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #5a4fcf 100%);
        color: white;
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .table-header h5 {
        margin: 0;
        font-weight: 600;
    }
    
    .table {
        margin: 0;
    }
    
    .table th {
        background: #f8fafc;
        border-bottom: 2px solid #e5e7eb;
        font-weight: 600;
        color: #374151;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
    }
    
    .table td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .table tbody tr:hover {
        background: #f9fafb;
    }
    
    .user-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color) 0%, #5a4fcf 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .status-badge.pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .status-badge.approved { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .status-badge.processing { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .status-badge.completed { background: rgba(16, 185, 129, 0.2); color: #059669; }
    .status-badge.rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    
    .amount-cell {
        font-weight: 600;
    }
    
    .amount-pieces {
        font-size: 0.8rem;
        color: #6b7280;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        text-decoration: none;
    }
    
    .action-btn.view { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .action-btn.view:hover { background: #3b82f6; color: white; }
    .action-btn.approve { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .action-btn.approve:hover { background: #10b981; color: white; }
    .action-btn.reject { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .action-btn.reject:hover { background: #ef4444; color: white; }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6b7280;
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>
@endpush

@section('content')
<div class="admin-page">
    <div class="admin-page__header d-flex justify-content-between align-items-start">
        <div>
            <h1 class="admin-page__title">Gestion des Conversions</h1>
            <p class="admin-page__subtitle">Gérez les demandes de conversion de pièces en cash</p>
        </div>
        <a href="{{ route('admin.conversions.export') }}" class="btn btn-success">
            <i class="bi bi-download me-1"></i> Exporter
        </a>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card pending">
            <div class="stat-icon"><i class="bi bi-clock"></i></div>
            <div class="stat-value">{{ $stats['pending_count'] }}</div>
            <div class="stat-label">En Attente</div>
            <div class="stat-amount">{{ number_format($stats['pending_amount'], 0) }} FCFA</div>
        </div>
        <div class="stat-card processing">
            <div class="stat-icon"><i class="bi bi-arrow-repeat"></i></div>
            <div class="stat-value">{{ $stats['processing_count'] }}</div>
            <div class="stat-label">En Traitement</div>
        </div>
        <div class="stat-card completed">
            <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="stat-value">{{ $stats['completed_today'] }}</div>
            <div class="stat-label">Complétées Aujourd'hui</div>
        </div>
        <div class="stat-card total">
            <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
            <div class="stat-value">{{ number_format($stats['total_paid_out'], 0) }}</div>
            <div class="stat-label">FCFA Total Payé</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.conversions.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Statut</label>
                <select name="status" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En Attente</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En Traitement</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Complété</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel"></i> Filtrer
                </button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a href="{{ route('admin.conversions.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-x"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Conversions Table -->
    <div class="data-table">
        <div class="table-header">
            <h5><i class="bi bi-cash-stack me-2"></i>Liste des Conversions</h5>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Montant</th>
                        <th>Méthode</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($conversions as $conversion)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($conversion->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $conversion->user->name ?? 'N/A' }}</strong>
                                    <div class="text-muted small">{{ $conversion->user->phone ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="amount-cell">
                            {{ number_format($conversion->fcfa_amount, 0) }} FCFA
                            <div class="amount-pieces">{{ number_format($conversion->pieces_amount, 0) }} pièces</div>
                        </td>
                        <td>
                            <span class="text-capitalize">{{ $conversion->payment_method }}</span>
                            <div class="text-muted small">{{ $conversion->payment_number }}</div>
                        </td>
                        <td>
                            @php
                                $statusLabels = [
                                    'pending' => 'En Attente',
                                    'approved' => 'Approuvé',
                                    'processing' => 'En Traitement',
                                    'completed' => 'Complété',
                                    'rejected' => 'Rejeté',
                                ];
                            @endphp
                            <span class="status-badge {{ $conversion->status }}">
                                {{ $statusLabels[$conversion->status] ?? $conversion->status }}
                            </span>
                        </td>
                        <td>{{ $conversion->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.conversions.show', $conversion) }}" class="action-btn view" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($conversion->status === 'pending')
                                <form action="{{ route('admin.conversions.approve', $conversion) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="action-btn approve" title="Approuver">
                                        <i class="bi bi-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.conversions.reject', $conversion) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Rejeter cette conversion?')">
                                    @csrf
                                    <button type="submit" class="action-btn reject" title="Rejeter">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-cash-stack"></i>
                                <p>Aucune conversion trouvée</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($conversions->hasPages())
        <div class="p-3 border-top">
            {{ $conversions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
