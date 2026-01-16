@extends('layouts.creator')

@section('title', 'Participations - Créateur')
@section('header', 'Participations')

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e5e7eb;
    }
    
    .content-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }
    
    .content-card__header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        font-weight: 600;
        color: #111827;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .participations-table {
        width: 100%;
    }
    
    .participations-table th {
        background: #f9fafb;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #6b7280;
        padding: 1rem;
        text-align: left;
    }
    
    .participations-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    
    .participations-table tr:hover {
        background: #f9fafb;
    }
    
    .user-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        flex-shrink: 0;
    }
    
    .user-name {
        font-weight: 600;
        color: #111827;
    }
    
    .user-phone {
        font-size: 0.8rem;
        color: #6b7280;
    }
    
    .campaign-link {
        color: #4f46e5;
        text-decoration: none;
        font-weight: 500;
    }
    
    .campaign-link:hover {
        text-decoration: underline;
    }
    
    .badge-status {
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
    }
    
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-completed { background: #d1fae5; color: #065f46; }
    .badge-rejected { background: #fee2e2; color: #991b1b; }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-action {
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-validate {
        background: #d1fae5;
        color: #065f46;
    }
    
    .btn-validate:hover {
        background: #a7f3d0;
    }
    
    .btn-reject {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .btn-reject:hover {
        background: #fecaca;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }
    
    .empty-state h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        color: #6b7280;
    }
    
    /* Modal styles */
    .modal-content {
        border-radius: 16px;
        border: none;
    }
    
    .modal-header {
        border-bottom: 1px solid #e5e7eb;
        padding: 1rem 1.5rem;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-footer {
        border-top: 1px solid #e5e7eb;
        padding: 1rem 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="h4 fw-bold mb-1">Participations</h1>
        <p class="text-muted mb-0">Gérez les participations à vos campagnes</p>
    </div>
</div>

<!-- Filters -->
<div class="filter-card">
    <form method="GET" action="{{ route('creator.participations') }}" class="row g-3">
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <select name="campaign_id" class="form-select">
                <option value="">Toutes les campagnes</option>
                @foreach($campaigns as $campaign)
                <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                    {{ Str::limit($campaign->title, 30) }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Tous les statuts</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Complété</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-funnel"></i> Filtrer
            </button>
        </div>
    </form>
</div>

<!-- Participations List -->
<div class="content-card">
    <div class="content-card__header">
        <span><i class="bi bi-people me-2"></i> {{ $participations->total() }} participation(s)</span>
    </div>
    
    @if($participations->count() > 0)
    <table class="participations-table">
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Campagne</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participations as $participation)
            <tr>
                <td>
                    <div class="user-cell">
                        <div class="user-avatar">
                            {{ strtoupper(substr($participation->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="user-name">{{ $participation->user->name ?? 'Utilisateur supprimé' }}</div>
                            <div class="user-phone">{{ $participation->user->phone ?? '' }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <a href="{{ route('creator.campaigns.show', $participation->campaign) }}" class="campaign-link">
                        {{ Str::limit($participation->campaign->title, 25) }}
                    </a>
                </td>
                <td>
                    <div>{{ $participation->created_at->format('d/m/Y') }}</div>
                    <div class="text-muted small">{{ $participation->created_at->format('H:i') }}</div>
                </td>
                <td>
                    <span class="badge-status badge-{{ $participation->status }}">
                        @switch($participation->status)
                            @case('pending') En attente @break
                            @case('completed') Complété @break
                            @case('rejected') Rejeté @break
                            @default {{ ucfirst($participation->status) }}
                        @endswitch
                    </span>
                </td>
                <td>
                    @if($participation->status === 'pending')
                    <div class="action-buttons">
                        <form action="{{ route('creator.participations.validate', $participation) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-action btn-validate" onclick="return confirm('Valider cette participation?')">
                                <i class="bi bi-check"></i> Valider
                            </button>
                        </form>
                        <button type="button" class="btn-action btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $participation->id }}">
                            <i class="bi bi-x"></i> Rejeter
                        </button>
                    </div>
                    
                    <!-- Reject Modal -->
                    <div class="modal fade" id="rejectModal{{ $participation->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Rejeter la participation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('creator.participations.reject', $participation) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <p>Vous allez rejeter la participation de <strong>{{ $participation->user->name ?? 'Utilisateur' }}</strong>.</p>
                                        <div class="mb-3">
                                            <label class="form-label">Raison du rejet (optionnel)</label>
                                            <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Expliquez pourquoi cette participation est rejetée..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-danger">Rejeter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @elseif($participation->status === 'completed')
                    <span class="text-success small">
                        <i class="bi bi-check-circle"></i> +{{ $participation->pieces_earned }} pièces
                    </span>
                    @elseif($participation->status === 'rejected')
                    <span class="text-muted small" title="{{ $participation->rejection_reason ?? 'Pas de raison spécifiée' }}">
                        <i class="bi bi-info-circle"></i> Voir raison
                    </span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="p-3">
        {{ $participations->withQueryString()->links() }}
    </div>
    @else
    <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <h3>Aucune participation</h3>
        <p>Il n'y a pas de participation correspondant à vos critères.</p>
    </div>
    @endif
</div>
@endsection
