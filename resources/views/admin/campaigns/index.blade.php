@extends('layouts.admin')

@section('title', 'Gestion des Campagnes')
@section('page-title', 'Campagnes')

@section('content')
<div class="admin-page">
    <div class="admin-page__header d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="admin-page__title">Gestion des Campagnes</h1>
            <p class="admin-page__subtitle">Gérez toutes les campagnes CPA</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.campaigns.approvals.index') }}" class="btn btn-warning">
                <i class="bi bi-clock-history"></i> Approbations
            </a>
            <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nouvelle Campagne
            </a>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4" style="border: 2px solid #0d6efd;">
        <div class="card-header text-white" style="background-color: #0d6efd;">
            <h5 class="mb-0"><i class="bi bi-funnel"></i> Filtrer et Rechercher</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.campaigns.index') }}" class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label" style="font-weight: 600;">Rechercher</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Titre ou description...">
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label" style="font-weight: 600;">Statut</label>
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
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Campaigns Table -->
    <div class="card" style="border: 2px solid #0d6efd;">
        <div class="card-header text-white" style="background-color: #0d6efd;">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> Liste des Campagnes ({{ $campaigns->total() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="font-weight: 600;">Image</th>
                            <th style="font-weight: 600;">Titre</th>
                            <th style="font-weight: 600;">Statut</th>
                            <th style="font-weight: 600;">Récompense</th>
                            <th style="font-weight: 600;">Dates</th>
                            <th style="font-weight: 600;">Créateur</th>
                            <th style="font-weight: 600;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaigns as $campaign)
                        <tr>
                            <td>
                                @if($campaign->image)
                                <img src="{{ asset('storage/' . $campaign->image) }}" 
                                     alt="{{ $campaign->title }}" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                @else
                                <div style="width: 50px; height: 50px; background-color: #e9ecef; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ Str::limit($campaign->title, 40) }}</div>
                                <small class="text-muted">{{ Str::limit($campaign->description, 60) }}</small>
                            </td>
                            <td>
                                @if($campaign->status == 'draft')
                                    <span class="badge bg-secondary">Brouillon</span>
                                @elseif($campaign->status == 'pending_approval')
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @elseif($campaign->status == 'published')
                                    <span class="badge bg-success">Publié</span>
                                @elseif($campaign->status == 'paused')
                                    <span class="badge bg-info text-dark">Pausé</span>
                                @else
                                    <span class="badge bg-dark">Terminé</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge" style="background-color: #ffc107; color: #000; font-size: 14px;">
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
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.campaigns.show', $campaign) }}" 
                                       class="btn btn-sm btn-info" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.campaigns.edit', $campaign) }}" 
                                       class="btn btn-sm btn-primary" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($campaign->status == 'draft')
                                    <form action="{{ route('admin.campaigns.submit-approval', $campaign) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Soumettre">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('admin.campaigns.destroy', $campaign) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette campagne?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                <p class="text-muted mt-2">Aucune campagne trouvée</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($campaigns->hasPages())
        <div class="card-footer">
            {{ $campaigns->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
