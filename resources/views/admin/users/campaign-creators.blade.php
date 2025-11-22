@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Créateurs de Campagnes</h1>
            <p class="text-muted mb-0">Utilisateurs autorisés à gérer les campagnes</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Tous les utilisateurs
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-primary" style="border-width: 2px;">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="mb-0" style="font-weight: 700;">{{ $creators->total() }}</h3>
                    <small class="text-muted">Total Créateurs</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success" style="border-width: 2px;">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="bi bi-megaphone-fill" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="mb-0" style="font-weight: 700;">{{ $creators->sum('campaigns_count') }}</h3>
                    <small class="text-muted">Campagnes Créées</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning" style="border-width: 2px;">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="bi bi-shield-check" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="mb-0" style="font-weight: 700;">{{ $creators->where('role', 'superadmin')->count() }}</h3>
                    <small class="text-muted">Super Admins</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Creators List -->
    <div class="card" style="border: 2px solid #0d6efd;">
        <div class="card-header text-white" style="background-color: #0d6efd;">
            <h5 class="mb-0"><i class="bi bi-star-fill"></i> Liste des Créateurs</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Créateur</th>
                            <th>Rôle</th>
                            <th>Campagnes</th>
                            <th>Membre depuis</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($creators as $creator)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($creator->avatar)
                                    <img src="{{ asset('storage/' . $creator->avatar) }}" 
                                         alt="{{ $creator->name }}" 
                                         class="rounded-circle me-2" 
                                         style="width: 45px; height: 45px; object-fit: cover;">
                                    @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                         style="width: 45px; height: 45px;">
                                        {{ strtoupper(substr($creator->name, 0, 1)) }}
                                    </div>
                                    @endif
                                    <div>
                                        <strong>{{ $creator->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $creator->phone }}</small>
                                        @if($creator->id === auth()->id())
                                        <span class="badge bg-info ms-1">Vous</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($creator->isSuperAdmin())
                                <span class="badge bg-danger">
                                    <i class="bi bi-shield-fill-check"></i> Super Admin
                                </span>
                                @else
                                <span class="badge bg-primary">
                                    <i class="bi bi-person-badge"></i> Créateur
                                </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-success" style="font-size: 1rem;">
                                    {{ $creator->campaigns_count }}
                                </span>
                            </td>
                            <td>{{ $creator->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.users.assign-role.form', $creator) }}" 
                                       class="btn btn-outline-primary"
                                       title="Modifier le rôle">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($creator->id !== auth()->id())
                                    <form action="{{ route('admin.users.remove-role', $creator) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Retirer les privilèges de {{ $creator->name }}? Il redeviendra un utilisateur simple.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Révoquer">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Aucun créateur de campagne pour le moment
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($creators->hasPages())
        <div class="card-footer">
            {{ $creators->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
