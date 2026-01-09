@extends('layouts.admin')

@section('title', 'Assigner un Rôle')
@section('page-title', 'Rôles')

@push('styles')
<style>
    .user-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .user-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .user-avatar-placeholder {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color) 0%, #5a4fcf 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        font-weight: 600;
    }
    
    .user-info h4 {
        margin: 0 0 0.25rem;
        font-weight: 600;
    }
    
    .user-info p {
        margin: 0;
        color: #6b7280;
        font-size: 0.9rem;
    }
    
    .role-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
    }
    
    .role-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #5a4fcf 100%);
        color: white;
        padding: 1rem 1.5rem;
    }
    
    .role-header h5 {
        margin: 0;
        font-weight: 600;
    }
    
    .role-body {
        padding: 1.5rem;
    }
    
    .role-option {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .role-option:hover {
        border-color: var(--primary-color);
        background: #faf9ff;
    }
    
    .role-option.selected {
        border-color: var(--primary-color);
        background: rgba(107, 79, 187, 0.05);
    }
    
    .role-option input[type="radio"] {
        display: none;
    }
    
    .role-option-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }
    
    .role-name {
        font-weight: 600;
        font-size: 1rem;
        color: #1f2937;
    }
    
    .role-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .role-badge.basic { background: #f3f4f6; color: #6b7280; }
    .role-badge.creator { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .role-badge.admin { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    
    .role-description {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 0.75rem;
    }
    
    .role-features {
        list-style: none;
        padding: 0;
        margin: 0;
        font-size: 0.85rem;
    }
    
    .role-features li {
        padding: 0.25rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #4b5563;
    }
    
    .role-features li i {
        color: #10b981;
        font-size: 0.75rem;
    }
    
    .warning-alert {
        background: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 0.75rem;
    }
    
    .warning-alert i {
        color: #f59e0b;
        font-size: 1.25rem;
    }
    
    .warning-alert p {
        margin: 0;
        color: #92400e;
        font-size: 0.9rem;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, var(--primary-color) 0%, #5a4fcf 100%);
        color: white;
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-back {
        background: #f3f4f6;
        color: #4b5563;
        border: none;
        padding: 0.875rem 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    
    .btn-back:hover {
        background: #e5e7eb;
        color: #374151;
    }
</style>
@endpush

@section('content')
<div class="admin-page">
    <div class="admin-page__header">
        <a href="{{ route('admin.users.index') }}" class="btn-back mb-3">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
        <h1 class="admin-page__title">Assigner un Rôle</h1>
        <p class="admin-page__subtitle">Gérer les permissions de {{ $user->name }}</p>
    </div>

    <!-- User Info -->
    <div class="user-card">
        @if($user->avatar)
        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="user-avatar">
        @else
        <div class="user-avatar-placeholder">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        @endif
        <div class="user-info">
            <h4>{{ $user->name }}</h4>
            <p>{{ $user->phone }}</p>
            <p><small>Inscrit le {{ $user->created_at->format('d/m/Y') }}</small></p>
        </div>
    </div>

    @if($user->id === auth()->id())
    <div class="warning-alert">
        <i class="bi bi-exclamation-triangle"></i>
        <p>Attention: Vous modifiez votre propre rôle. Assurez-vous de ne pas perdre vos privilèges de super admin!</p>
    </div>
    @endif

    <!-- Role Selection -->
    <div class="role-card">
        <div class="role-header">
            <h5><i class="bi bi-shield-check me-2"></i>Choisir un Rôle</h5>
        </div>
        <div class="role-body">
            <form action="{{ route('admin.users.assign-role', $user) }}" method="POST">
                @csrf

                <!-- User Role -->
                <label class="role-option {{ $user->role === 'user' ? 'selected' : '' }}">
                    <input type="radio" name="role" value="user" {{ $user->role === 'user' ? 'checked' : '' }}>
                    <div class="role-option-header">
                        <span class="role-name">Utilisateur</span>
                        <span class="role-badge basic">Basique</span>
                    </div>
                    <p class="role-description">Accès standard à l'application</p>
                    <ul class="role-features">
                        <li><i class="bi bi-check-circle-fill"></i> Participer aux campagnes</li>
                        <li><i class="bi bi-check-circle-fill"></i> Gagner des pièces</li>
                        <li><i class="bi bi-check-circle-fill"></i> Convertir en cash</li>
                        <li><i class="bi bi-check-circle-fill"></i> Parrainer des amis</li>
                    </ul>
                </label>

                <!-- Campaign Creator Role -->
                <label class="role-option {{ $user->role === 'campaign_creator' ? 'selected' : '' }}">
                    <input type="radio" name="role" value="campaign_creator" {{ $user->role === 'campaign_creator' ? 'checked' : '' }}>
                    <div class="role-option-header">
                        <span class="role-name">Créateur de Campagnes</span>
                        <span class="role-badge creator">Créateur</span>
                    </div>
                    <p class="role-description">Peut créer et gérer des campagnes</p>
                    <ul class="role-features">
                        <li><i class="bi bi-check-circle-fill"></i> Créer/modifier/supprimer des campagnes</li>
                        <li><i class="bi bi-check-circle-fill"></i> Voir les statistiques détaillées</li>
                        <li><i class="bi bi-check-circle-fill"></i> Dupliquer des campagnes</li>
                        <li><i class="bi bi-check-circle-fill"></i> Soumettre pour approbation</li>
                    </ul>
                </label>

                <!-- SuperAdmin Role -->
                <label class="role-option {{ $user->role === 'superadmin' ? 'selected' : '' }}">
                    <input type="radio" name="role" value="superadmin" {{ $user->role === 'superadmin' ? 'checked' : '' }}>
                    <div class="role-option-header">
                        <span class="role-name">Super Administrateur</span>
                        <span class="role-badge admin">Admin</span>
                    </div>
                    <p class="role-description">Accès complet à toutes les fonctionnalités</p>
                    <ul class="role-features">
                        <li><i class="bi bi-check-circle-fill"></i> Toutes les fonctionnalités créateur</li>
                        <li><i class="bi bi-check-circle-fill"></i> Approuver/rejeter les campagnes</li>
                        <li><i class="bi bi-check-circle-fill"></i> Gérer les utilisateurs et rôles</li>
                        <li><i class="bi bi-check-circle-fill"></i> Gérer les conversions et paiements</li>
                        <li><i class="bi bi-check-circle-fill"></i> Configurer les paramètres système</li>
                    </ul>
                </label>

                <div class="d-flex gap-3 mt-4">
                    <a href="{{ route('admin.users.index') }}" class="btn-back">
                        <i class="bi bi-x"></i> Annuler
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-lg"></i> Assigner le Rôle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.role-option').forEach(option => {
    option.addEventListener('click', function() {
        document.querySelectorAll('.role-option').forEach(o => o.classList.remove('selected'));
        this.classList.add('selected');
    });
});
</script>
@endpush
@endsection
