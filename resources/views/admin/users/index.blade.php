@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')
@section('page-title', 'Utilisateurs')

@push('styles')
<style>
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .stat-icon.blue { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .stat-icon.green { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stat-icon.purple { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
    .stat-icon.orange { background: rgba(249, 115, 22, 0.1); color: #f97316; }
    
    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    
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
    
    .user-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .user-avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
    
    .role-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .role-badge.user { background: #f3f4f6; color: #6b7280; }
    .role-badge.creator { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .role-badge.admin { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .action-btn.edit { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .action-btn.edit:hover { background: #3b82f6; color: white; }
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
    }
    
    .btn--primary:hover { color: white; }
    
    .btn--ghost {
        background: transparent;
        color: #6b7280;
        border: 1px solid #e5e7eb;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }
    
    .btn--ghost:hover { background: #f3f4f6; color: #374151; }
    
    .btn--white {
        background: white;
        color: #374151;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }
    
    .btn--white:hover { background: #f3f4f6; color: #1f2937; }
    
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.85rem;
        }
        
        .user-cell .user-name {
            max-width: 100px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }
</style>
@endpush

@section('content')
<div class="admin-page">
    <div class="admin-page__header">
        <h1 class="admin-page__title">Gestion des Utilisateurs</h1>
        <p class="admin-page__subtitle">Gérer les rôles et permissions</p>
    </div>

    <!-- Stats Row -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-people"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['total'] ?? $users->total() }}</div>
                <div class="stat-label">Total Utilisateurs</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-person-check"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['users'] ?? '-' }}</div>
                <div class="stat-label">Utilisateurs Standard</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-person-workspace"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['creators'] ?? '-' }}</div>
                <div class="stat-label">Créateurs</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="bi bi-shield-check"></i>
            </div>
            <div>
                <div class="stat-value">{{ $stats['admins'] ?? '-' }}</div>
                <div class="stat-label">Administrateurs</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Rechercher</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Nom ou téléphone...">
                </div>
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

    <!-- Users Table -->
    <div class="data-table">
        <div class="table-header">
            <h5><i class="bi bi-people me-2"></i>Liste des Utilisateurs</h5>
            <a href="{{ route('admin.users.campaign-creators') }}" class="btn btn-light btn-sm">
                <i class="bi bi-people-fill"></i> Créateurs
            </a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
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
                            <div class="user-cell">
                                @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="user-avatar">
                                @else
                                <div class="user-avatar-placeholder">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                @endif
                                <div>
                                    <strong class="user-name">{{ $user->name }}</strong>
                                    @if($user->id === auth()->id())
                                    <span class="badge bg-info ms-1">Vous</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->phone }}</td>
                        <td>
                            @php
                                $roleClasses = [
                                    'user' => 'user',
                                    'campaign_creator' => 'creator',
                                    'superadmin' => 'admin',
                                ];
                                $roleLabels = [
                                    'user' => 'Utilisateur',
                                    'campaign_creator' => 'Créateur',
                                    'superadmin' => 'Super Admin',
                                ];
                            @endphp
                            <span class="role-badge {{ $roleClasses[$user->role] ?? 'user' }}">
                                {{ $roleLabels[$user->role] ?? 'Utilisateur' }}
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
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.users.assign-role.form', $user) }}" 
                                   class="action-btn edit" title="Modifier le rôle">
                                    <i class="bi bi-shield-check"></i>
                                </a>
                                @if($user->role !== 'user' && $user->id !== auth()->id())
                                <form action="{{ route('admin.users.remove-role', $user) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Retirer le rôle de {{ $user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Retirer le rôle">
                                        <i class="bi bi-x-circle"></i>
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
                                <i class="bi bi-people"></i>
                                <p>Aucun utilisateur trouvé</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="p-3 border-top">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
