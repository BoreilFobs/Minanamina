@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Gestion des Utilisateurs</h1>
            <p class="text-muted mb-0">Gérer les rôles et permissions</p>
        </div>
        <a href="{{ route('admin.users.campaign-creators') }}" class="btn btn-primary">
            <i class="bi bi-people-fill"></i> Créateurs de Campagnes
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-4" style="border: 2px solid #0d6efd;">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Rechercher</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Nom ou téléphone...">
                </div>
                <div class="col-md-3">
                    <label for="role" class="form-label">Rôle</label>
                    <select class="form-select" id="role" name="role">
                        <option value="">Tous les rôles</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Utilisateur</option>
                        <option value="campaign_creator" {{ request('role') == 'campaign_creator' ? 'selected' : '' }}>Créateur de Campagnes</option>
                        <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card" style="border: 2px solid #0d6efd;">
        <div class="card-header text-white" style="background-color: #0d6efd;">
            <h5 class="mb-0"><i class="bi bi-people"></i> Liste des Utilisateurs</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Utilisateur</th>
                            <th>Téléphone</th>
                            <th>Rôle</th>
                            <th>Campagnes</th>
                            <th>Inscrit le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                                         alt="{{ $user->name }}" 
                                         class="rounded-circle me-2" 
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                         style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    @endif
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->id === auth()->id())
                                        <span class="badge bg-info ms-1">Vous</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                @php
                                    $roleConfig = [
                                        'user' => ['label' => 'Utilisateur', 'class' => 'secondary'],
                                        'campaign_creator' => ['label' => 'Créateur', 'class' => 'primary'],
                                        'superadmin' => ['label' => 'Super Admin', 'class' => 'danger'],
                                    ];
                                    $config = $roleConfig[$user->role] ?? $roleConfig['user'];
                                @endphp
                                <span class="badge bg-{{ $config['class'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td>
                                @if($user->canManageCampaigns())
                                <span class="badge bg-success">{{ $user->campaigns()->count() }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.users.assign-role.form', $user) }}" 
                                       class="btn btn-outline-primary"
                                       title="Modifier le rôle">
                                        <i class="bi bi-shield-check"></i>
                                    </a>
                                    @if($user->role !== 'user' && $user->id !== auth()->id())
                                    <form action="{{ route('admin.users.remove-role', $user) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Retirer le rôle de {{ $user->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Retirer le rôle">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Aucun utilisateur trouvé
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
        <div class="card-footer">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
