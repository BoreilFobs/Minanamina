@extends('layouts.admin')

@section('title', 'Validation des Participations')
@section('page-title', 'Validations')

@section('content')
<div class="admin-page">
    <!-- Alerts -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle-fill"></i> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="admin-page__header mb-4">
        <h1 class="admin-page__title">Validation des Participations</h1>
        <p class="admin-page__subtitle">Valider et attribuer les pièces aux participants</p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-warning" style="border-width: 2px;">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="bi bi-hourglass-split" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="mb-0" style="font-weight: 700;">{{ $stats['pending_count'] }}</h3>
                    <small class="text-muted">En Attente</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-success" style="border-width: 2px;">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="mb-0" style="font-weight: 700;">{{ $stats['validated_today'] }}</h3>
                    <small class="text-muted">Validées Aujourd'hui</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4" style="border: 2px solid #0d6efd;">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.validations.index') }}" class="row g-3">
                <div class="col-md-5">
                    <label for="search" class="form-label">Rechercher un utilisateur</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Nom ou téléphone...">
                </div>
                <div class="col-md-4">
                    <label for="campaign_id" class="form-label">Campagne</label>
                    <select class="form-select" id="campaign_id" name="campaign_id">
                        <option value="">Toutes les campagnes</option>
                        @foreach(\App\Models\Campaign::where('status', 'published')->get() as $campaign)
                        <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                            {{ $campaign->title }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Participations List -->
    <div class="card" style="border: 2px solid #0d6efd;">
        <div class="card-header text-white" style="background-color: #0d6efd;">
            <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Participations à Valider</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Utilisateur</th>
                            <th>Campagne</th>
                            <th>Récompense</th>
                            <th>Date Participation</th>
                            <th>Durée</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($participations as $participation)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($participation->user->avatar)
                                    <img src="{{ asset('storage/' . $participation->user->avatar) }}" 
                                         alt="{{ $participation->user->name }}" 
                                         class="rounded-circle me-2" 
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                         style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($participation->user->name, 0, 1)) }}
                                    </div>
                                    @endif
                                    <div>
                                        <strong>{{ $participation->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $participation->user->phone }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>{{ Str::limit($participation->campaign->title, 30) }}</strong>
                            </td>
                            <td>
                                <span class="badge" style="background-color: #ffc107; color: #000; font-size: 1rem;">
                                    <i class="bi bi-coin"></i> {{ number_format($participation->campaign->pieces_reward) }}
                                </span>
                            </td>
                            <td>
                                {{ $participation->started_at ? $participation->started_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td>
                                @if($participation->started_at)
                                    @php
                                        $duration = $participation->started_at->diffInMinutes(now());
                                        $hours = floor($duration / 60);
                                        $minutes = $duration % 60;
                                    @endphp
                                    @if($hours > 0)
                                        {{ $hours }}h {{ $minutes }}min
                                    @else
                                        {{ $minutes }}min
                                    @endif
                                    
                                    @if($participation->started_at->diffInSeconds(now()) < 60)
                                        <span class="badge bg-danger ms-1" title="Temps suspect">⚠️</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-warning">En attente</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <form action="{{ route('admin.validations.validate', $participation) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Valider cette participation et attribuer {{ number_format($participation->campaign->pieces_reward) }} pièces à {{ $participation->user->name }}?')">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Valider">
                                            <i class="bi bi-check-circle"></i> Valider
                                        </button>
                                    </form>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectModal{{ $participation->id }}"
                                            title="Rejeter">
                                        <i class="bi bi-x-circle"></i> Rejeter
                                    </button>
                                </div>
                                
                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $participation->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Rejeter la Participation</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.validations.reject', $participation) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <p><strong>Utilisateur:</strong> {{ $participation->user->name }}</p>
                                                    <p><strong>Campagne:</strong> {{ $participation->campaign->title }}</p>
                                                    
                                                    <div class="mb-3">
                                                        <label for="rejection_reason{{ $participation->id }}" class="form-label">
                                                            Raison du Rejet <span class="text-danger">*</span>
                                                        </label>
                                                        <textarea 
                                                            class="form-control" 
                                                            id="rejection_reason{{ $participation->id }}" 
                                                            name="rejection_reason" 
                                                            rows="4" 
                                                            required
                                                            minlength="10"
                                                            placeholder="Expliquez pourquoi cette participation est rejetée..."></textarea>
                                                        <small class="text-muted">Minimum 10 caractères</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="bi bi-x-circle"></i> Confirmer le Rejet
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-2">Aucune participation en attente de validation</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($participations->hasPages())
        <div class="card-footer">
            {{ $participations->links() }}
        </div>
        @endif
    </div>

    <!-- Bulk Actions -->
    @if($participations->count() > 0)
    <div class="card mt-4" style="border: 2px solid #28a745;">
        <div class="card-header text-white" style="background-color: #28a745;">
            <h5 class="mb-0"><i class="bi bi-lightning"></i> Actions Groupées</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.validations.bulk-validate') }}" method="POST" 
                  onsubmit="return confirm('Valider TOUTES les participations affichées ({{ $participations->count() }})?')">
                @csrf
                @foreach($participations as $p)
                    <input type="hidden" name="participation_ids[]" value="{{ $p->id }}">
                @endforeach
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0">Valider toutes les participations visibles à la fois</p>
                        <small class="text-muted">Cette action attribuera les pièces à tous les participants affichés ({{ $participations->count() }} participations)</small>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-check-all"></i> Valider Tout ({{ $participations->count() }})
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection
