@extends('layouts.admin')

@section('title', 'Assigner un Rôle - ' . $user->name)

@section('content')
<style>
    .role-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    
    .user-profile-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 2rem;
    }
    
    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: 700;
        flex-shrink: 0;
    }
    
    .user-info h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 0.5rem 0;
    }
    
    .user-info-detail {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }
    
    .current-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-top: 0.5rem;
    }
    
    .current-role-badge.user {
        background: #f3f4f6;
        color: #6b7280;
    }
    
    .current-role-badge.campaign_creator {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }
    
    .current-role-badge.superadmin {
        background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%);
        color: #9d174d;
    }
    
    .role-selection-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1.5rem;
    }
    
    .role-options {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .role-option {
        position: relative;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }
    
    .role-option:hover {
        border-color: #667eea;
        background: #f8fafc;
    }
    
    .role-option.selected {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    }
    
    .role-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    
    .role-option-content {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .role-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.25rem;
    }
    
    .role-icon.user-role {
        background: #f3f4f6;
        color: #6b7280;
    }
    
    .role-icon.creator-role {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }
    
    .role-icon.admin-role {
        background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%);
        color: #9d174d;
    }
    
    .role-details {
        flex: 1;
    }
    
    .role-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .current-indicator {
        font-size: 0.75rem;
        background: #10b981;
        color: white;
        padding: 0.15rem 0.5rem;
        border-radius: 20px;
        font-weight: 500;
    }
    
    .role-description {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 0.75rem;
    }
    
    .role-permissions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .permission-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.8rem;
        color: #374151;
        background: #f3f4f6;
        padding: 0.25rem 0.6rem;
        border-radius: 4px;
    }
    
    .permission-tag i {
        color: #10b981;
        font-size: 0.7rem;
    }
    
    .role-check {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 2px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .role-option.selected .role-check {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
    }
    
    .role-check i {
        color: white;
        font-size: 0.75rem;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    
    .role-option.selected .role-check i {
        opacity: 1;
    }
    
    .warning-box {
        background: linear-gradient(135deg, #fef3c7 0%, #fef9c3 100%);
        border-left: 4px solid #f59e0b;
        padding: 1rem 1.25rem;
        border-radius: 0 8px 8px 0;
        margin-top: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .warning-box i {
        color: #f59e0b;
        font-size: 1.25rem;
        margin-top: 0.1rem;
    }
    
    .warning-box p {
        color: #92400e;
        font-size: 0.9rem;
        margin: 0;
        line-height: 1.5;
    }
    
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }
    
    .btn-cancel {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        border: 1px solid #e5e7eb;
        background: white;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-cancel:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        color: #374151;
    }
    
    .btn-save {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    
    .btn-save:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    
    .breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }
    
    .breadcrumb-nav a {
        color: #6b7280;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .breadcrumb-nav a:hover {
        color: #667eea;
    }
    
    .breadcrumb-nav span {
        color: #9ca3af;
    }
    
    .breadcrumb-nav .current {
        color: #1f2937;
        font-weight: 500;
    }
    
    @media (max-width: 768px) {
        .user-profile-header {
            flex-direction: column;
            text-align: center;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .form-actions .btn-cancel,
        .form-actions .btn-save {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="container py-4">
    <!-- Breadcrumb -->
    <div class="breadcrumb-nav">
        <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i> Tableau de bord</a>
        <span>/</span>
        <a href="{{ route('admin.users.index') }}">Utilisateurs</a>
        <span>/</span>
        <span class="current">Assigner un rôle</span>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="role-card">
                <!-- User Profile Header -->
                <div class="user-profile-header">
                    <div class="user-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <h2>{{ $user->name }}</h2>
                        <div class="user-info-detail">
                            <i class="bi bi-phone"></i>
                            {{ $user->phone }}
                        </div>
                        @if($user->email)
                        <div class="user-info-detail">
                            <i class="bi bi-envelope"></i>
                            {{ $user->email }}
                        </div>
                        @endif
                        <div class="user-info-detail">
                            <i class="bi bi-calendar"></i>
                            Membre depuis {{ $user->created_at->format('d M Y') }}
                        </div>
                        <span class="current-role-badge {{ $user->role }}">
                            @if($user->role === 'superadmin')
                                <i class="bi bi-shield-check"></i> Super Administrateur
                            @elseif($user->role === 'campaign_creator')
                                <i class="bi bi-person-badge"></i> Créateur de Campagnes
                            @else
                                <i class="bi bi-person"></i> Utilisateur
                            @endif
                        </span>
                    </div>
                </div>
                
                <!-- Role Selection Form -->
                <form action="{{ route('admin.users.assign-role', $user) }}" method="POST" id="roleForm">
                    @csrf
                    
                    <h3 class="role-selection-title">
                        <i class="bi bi-shield me-2"></i>
                        Sélectionnez le nouveau rôle
                    </h3>
                    
                    <div class="role-options">
                        <!-- User Role -->
                        <label class="role-option {{ $user->role === 'user' ? 'selected' : '' }}" data-role="user">
                            <input type="radio" name="role" value="user" {{ $user->role === 'user' ? 'checked' : '' }}>
                            <div class="role-option-content">
                                <div class="role-icon user-role">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div class="role-details">
                                    <div class="role-name">
                                        Utilisateur
                                        @if($user->role === 'user')
                                            <span class="current-indicator">Rôle actuel</span>
                                        @endif
                                    </div>
                                    <p class="role-description">
                                        Accès de base à l'application. Peut participer aux campagnes et gagner des récompenses.
                                    </p>
                                    <div class="role-permissions">
                                        <span class="permission-tag"><i class="bi bi-check"></i> Participer aux campagnes</span>
                                        <span class="permission-tag"><i class="bi bi-check"></i> Voir son profil</span>
                                        <span class="permission-tag"><i class="bi bi-check"></i> Parrainer des amis</span>
                                    </div>
                                </div>
                            </div>
                            <div class="role-check"><i class="bi bi-check"></i></div>
                        </label>
                        
                        <!-- Campaign Creator Role -->
                        <label class="role-option {{ $user->role === 'campaign_creator' ? 'selected' : '' }}" data-role="campaign_creator">
                            <input type="radio" name="role" value="campaign_creator" {{ $user->role === 'campaign_creator' ? 'checked' : '' }}>
                            <div class="role-option-content">
                                <div class="role-icon creator-role">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <div class="role-details">
                                    <div class="role-name">
                                        Créateur de Campagnes
                                        @if($user->role === 'campaign_creator')
                                            <span class="current-indicator">Rôle actuel</span>
                                        @endif
                                    </div>
                                    <p class="role-description">
                                        Peut créer et gérer ses propres campagnes. Accès au tableau de bord créateur.
                                    </p>
                                    <div class="role-permissions">
                                        <span class="permission-tag"><i class="bi bi-check"></i> Créer des campagnes</span>
                                        <span class="permission-tag"><i class="bi bi-check"></i> Gérer ses campagnes</span>
                                        <span class="permission-tag"><i class="bi bi-check"></i> Voir les statistiques</span>
                                        <span class="permission-tag"><i class="bi bi-check"></i> Valider les participations</span>
                                    </div>
                                </div>
                            </div>
                            <div class="role-check"><i class="bi bi-check"></i></div>
                        </label>
                        
                        <!-- Superadmin Role -->
                        <label class="role-option {{ $user->role === 'superadmin' ? 'selected' : '' }}" data-role="superadmin">
                            <input type="radio" name="role" value="superadmin" {{ $user->role === 'superadmin' ? 'checked' : '' }}>
                            <div class="role-option-content">
                                <div class="role-icon admin-role">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div class="role-details">
                                    <div class="role-name">
                                        Super Administrateur
                                        @if($user->role === 'superadmin')
                                            <span class="current-indicator">Rôle actuel</span>
                                        @endif
                                    </div>
                                    <p class="role-description">
                                        Accès complet au système. Peut gérer tous les utilisateurs, campagnes et paramètres.
                                    </p>
                                    <div class="role-permissions">
                                        <span class="permission-tag"><i class="bi bi-check"></i> Toutes les permissions</span>
                                        <span class="permission-tag"><i class="bi bi-check"></i> Gérer les utilisateurs</span>
                                        <span class="permission-tag"><i class="bi bi-check"></i> Paramètres système</span>
                                        <span class="permission-tag"><i class="bi bi-check"></i> Rapports avancés</span>
                                    </div>
                                </div>
                            </div>
                            <div class="role-check"><i class="bi bi-check"></i></div>
                        </label>
                    </div>
                    
                    @if($user->id === auth()->id())
                        <div class="warning-box">
                            <i class="bi bi-exclamation-triangle"></i>
                            <p>
                                <strong>Attention!</strong> Vous modifiez votre propre compte. 
                                Si vous changez votre rôle, vous pourriez perdre l'accès à certaines fonctionnalités.
                            </p>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="warning-box" style="background: #fee2e2; border-color: #ef4444;">
                            <i class="bi bi-x-circle" style="color: #ef4444;"></i>
                            <p style="color: #991b1b;">{{ session('error') }}</p>
                        </div>
                    @endif
                    
                    <div class="form-actions">
                        <a href="{{ route('admin.users.index') }}" class="btn-cancel">
                            <i class="bi bi-arrow-left"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn-save" id="submitBtn">
                            <i class="bi bi-check-lg"></i>
                            Enregistrer le rôle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleOptions = document.querySelectorAll('.role-option');
        const form = document.getElementById('roleForm');
        const submitBtn = document.getElementById('submitBtn');
        const originalRole = '{{ $user->role }}';
        
        // Handle role selection
        roleOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remove selected from all
                roleOptions.forEach(opt => opt.classList.remove('selected'));
                // Add selected to clicked
                this.classList.add('selected');
                // Check the radio
                this.querySelector('input[type="radio"]').checked = true;
                
                // Update button state
                const selectedRole = this.dataset.role;
                if (selectedRole === originalRole) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Aucun changement';
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Enregistrer le rôle';
                }
            });
        });
        
        // Initial state - disable if no change
        const checkedRadio = document.querySelector('input[name="role"]:checked');
        if (checkedRadio && checkedRadio.value === originalRole) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Aucun changement';
        }
        
        // Form submission
        form.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Enregistrement...';
        });
    });
</script>
@endsection
