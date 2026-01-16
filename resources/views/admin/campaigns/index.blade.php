@extends('layouts.admin')

@section('title', 'Gestion des Campagnes')
@section('page-title', 'Campagnes')

@push('styles')
<style>
    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    
    .data-table {
        background: white;
        border-radius: 16px;
        overflow: hidden;
    }
    
    .table-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
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
    
    .campaign-image {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        object-fit: cover;
    }
    
    .campaign-image-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
    }
    
    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .status-badge.draft { background: rgba(107, 114, 128, 0.1); color: #6b7280; }
    .status-badge.pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .status-badge.published { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .status-badge.paused { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .status-badge.completed { background: rgba(107, 114, 128, 0.15); color: #374151; }
    
    .reward-badge {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: #78350f;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
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
        cursor: pointer;
    }
    
    .action-btn.view { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .action-btn.view:hover { background: #3b82f6; color: white; }
    .action-btn.edit { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
    .action-btn.edit:hover { background: #6366f1; color: white; }
    .action-btn.approve { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .action-btn.approve:hover { background: #10b981; color: white; }
    .action-btn.delete { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .action-btn.delete:hover { background: #ef4444; color: white; }
    
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
    
    .btn--primary {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn--primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        color: white;
    }
    
    .btn--warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    
    .btn--warning:hover {
        color: white;
    }
    
    .alert-modern {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .alert-modern.success {
        background: rgba(16, 185, 129, 0.1);
        color: #065f46;
    }
    
    .alert-modern.error {
        background: rgba(239, 68, 68, 0.1);
        color: #991b1b;
    }
</style>
@endpush

@section('content')
<div class="admin-page">
    <div class="admin-page__header d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="admin-page__title">Gestion des Campagnes</h1>
            <p class="admin-page__subtitle">Gérez toutes les campagnes CPA</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.campaigns.approvals.index') }}" class="btn--warning">
                <i class="bi bi-clock-history"></i> Approbations
            </a>
            <a href="{{ route('admin.campaigns.create') }}" class="btn--primary">
                <i class="bi bi-plus-circle"></i> Nouvelle Campagne
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
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

    <!-- Filter Card -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.campaigns.index') }}" class="row g-3">
            <div class="col-md-6">
                <label for="search" class="form-label fw-semibold">Rechercher</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Titre ou description...">
                </div>
            </div>
            <div class="col-md-4">
                <label for="status" class="form-label fw-semibold">Statut</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tous les statuts</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="pending_approval" {{ request('status') == 'pending_approval' ? 'selected' : '' }}>En attente</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publié</option>
                    <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>Pausé</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn--primary w-100">
                    <i class="bi bi-funnel"></i> Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Campaigns Table -->
    <div class="data-table">
        <div class="table-header">
            <h5><i class="bi bi-megaphone me-2"></i>Liste des Campagnes ({{ $campaigns->total() }})</h5>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Titre</th>
                        <th>Statut</th>
                        <th>Récompense</th>
                        <th>Dates</th>
                        <th>Créateur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                    <tr>
                        <td>
                            @if($campaign->image)
                            <img src="{{ asset('storage/' . $campaign->image) }}" 
                                 alt="{{ $campaign->title }}" 
                                 class="campaign-image">
                            @else
                            <div class="campaign-image-placeholder">
                                <i class="bi bi-image"></i>
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ Str::limit($campaign->title, 40) }}</div>
                            <small class="text-muted">{{ Str::limit($campaign->description, 60) }}</small>
                        </td>
                        <td>
                            @if($campaign->status == 'draft')
                                <span class="status-badge draft"><i class="bi bi-file-earmark"></i> Brouillon</span>
                            @elseif($campaign->status == 'pending_approval')
                                <span class="status-badge pending"><i class="bi bi-clock"></i> En attente</span>
                            @elseif($campaign->status == 'published')
                                <span class="status-badge published"><i class="bi bi-check-circle"></i> Publié</span>
                            @elseif($campaign->status == 'paused')
                                <span class="status-badge paused"><i class="bi bi-pause-circle"></i> Pausé</span>
                            @else
                                <span class="status-badge completed"><i class="bi bi-flag"></i> Terminé</span>
                            @endif
                        </td>
                        <td>
                            <span class="reward-badge">
                                <i class="bi bi-coin"></i> {{ number_format($campaign->pieces_reward) }}
                            </span>
                        </td>
                        <td>
                            <small>
                                <strong>Début:</strong> {{ \Carbon\Carbon::parse($campaign->start_date)->format('d/m/Y') }}<br>
                                <strong>Fin:</strong> {{ \Carbon\Carbon::parse($campaign->end_date)->format('d/m/Y') }}
                            </small>
                        </td>
                        <td>
                            <small>{{ $campaign->creator->name ?? 'N/A' }}</small>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.campaigns.show', $campaign) }}" 
                                   class="action-btn view" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.campaigns.edit', $campaign) }}" 
                                   class="action-btn edit" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($campaign->status == 'draft')
                                <form action="{{ route('admin.campaigns.submit-approval', $campaign) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="action-btn approve" title="Soumettre">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.campaigns.destroy', $campaign) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette campagne?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p>Aucune campagne trouvée</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($campaigns->hasPages())
        <div class="p-3 border-top">
            {{ $campaigns->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
