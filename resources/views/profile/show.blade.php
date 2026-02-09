@extends('layouts.modern')

@section('title', 'Mon Profil')

@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #5a4fcf 100%);
        padding: 2rem 1.5rem;
        padding-top: 3.5rem;
        margin: -1.5rem -1rem 0;
        color: white;
        text-align: center;
        position: relative;
    }
    
    .avatar-container {
        position: relative;
        display: inline-block;
        margin-bottom: 1rem;
    }
    
    .avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,0.3);
        object-fit: cover;
        background: rgba(255,255,255,0.2);
    }
    
    .avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,0.3);
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 600;
        color: white;
    }
    
    .verified-badge {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 24px;
        height: 24px;
        background: #10b981;
        border-radius: 50%;
        border: 2px solid white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: white;
    }
    
    .profile-name {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    
    .profile-phone {
        opacity: 0.9;
        font-size: 0.95rem;
    }
    
    .member-since {
        font-size: 0.85rem;
        opacity: 0.8;
        margin-top: 0.5rem;
    }
    
    .edit-profile-btn {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255,255,255,0.2);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .edit-profile-btn:hover {
        background: rgba(255,255,255,0.3);
        color: white;
    }
    
    .stats-bar {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        background: white;
        border-radius: 16px;
        margin: -1.5rem 1rem 1.5rem;
        padding: 1.25rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        position: relative;
        z-index: 10;
        overflow-x: hidden;
    }
    
    .stat-item {
        text-align: center;
        position: relative;
    }
    
    .stat-item:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        height: 60%;
        width: 1px;
        background: #e5e7eb;
    }
    
    .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-color);
        display: block;
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    
    @media (max-width: 576px) {
        .stats-bar {
            padding: 1rem 0.75rem;
            margin: -1.5rem 0.5rem 1.5rem;
        }
        
        .stat-value {
            font-size: 1.1rem;
        }
        
        .stat-label {
            font-size: 0.7rem;
        }
    }
    
    .section-card {
        background: white;
        border-radius: 16px;
        margin-bottom: 1rem;
        overflow: hidden;
    }
    
    .section-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .section-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    
    .section-icon.blue {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
    
    .section-icon.purple {
        background: rgba(139, 92, 246, 0.1);
        color: #8b5cf6;
    }
    
    .section-icon.green {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    
    .section-icon.red {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }
    
    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .info-item {
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f9fafb;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-size: 0.9rem;
        color: #6b7280;
    }
    
    .info-value {
        font-size: 0.9rem;
        font-weight: 500;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        word-wrap: break-word;
        overflow-wrap: break-word;
        max-width: 60%;
        text-align: right;
    }
    
    .verified-tag {
        font-size: 0.7rem;
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        padding: 0.2rem 0.5rem;
        border-radius: 20px;
    }
    
    .referral-code-box {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        padding: 1rem 1.25rem;
        margin: 0.5rem 1rem 1rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        overflow-x: hidden;
    }
    
    @media (max-width: 576px) {
        .referral-code-box {
            flex-direction: column;
            gap: 0.75rem;
            padding: 1rem;
        }
    }
    
    .referral-code {
        font-family: monospace;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-color);
        letter-spacing: 1px;
    }
    
    .copy-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .danger-section {
        padding: 1.25rem;
    }
    
    .danger-text {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }
    
    .btn-danger-outline {
        background: transparent;
        border: 1px solid #ef4444;
        color: #ef4444;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        width: 100%;
    }
    
    .logout-btn {
        background: #f3f4f6;
        color: #6b7280;
        border: none;
        padding: 1rem;
        border-radius: 12px;
        font-weight: 500;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 6rem;
    }
    
    /* Modal */
    .custom-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    
    .custom-modal-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }
    
    .custom-modal-dialog {
        position: relative;
        width: 100%;
        max-width: 500px;
        z-index: 1;
    }
    
    .custom-modal-content {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        overflow: hidden;
    }
    
    .custom-modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .custom-modal-title {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .custom-modal-close {
        background: none;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #6b7280;
        transition: all 0.2s;
    }
    
    .custom-modal-close:hover {
        background: #f3f4f6;
        color: #1f2937;
    }
    
    .custom-modal-close i {
        font-size: 1.5rem;
    }
    
    .custom-modal-body {
        padding: 1.5rem;
    }
    
    .custom-modal-footer {
        padding: 1rem 1.5rem;
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }
    
    .custom-modal-footer .btn {
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .custom-modal-footer .btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }
    
    .custom-modal-footer .btn-secondary:hover {
        background: #e5e7eb;
    }
    
    .custom-modal-footer .btn-danger {
        background: #ef4444;
        color: white;
    }
    
    .custom-modal-footer .btn-danger:hover {
        background: #dc2626;
    }
</style>
@endpush

@section('content')
<!-- Profile Header -->
<div class="profile-header">
    <a href="{{ route('profile.edit') }}" class="edit-profile-btn">
        <i class="bi bi-pencil"></i>
    </a>
    
    <div class="avatar-container">
        @if($user->avatar)
            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="avatar">
        @else
            <div class="avatar-placeholder">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif
        @if($user->phone_verified_at)
            <div class="verified-badge">
                <i class="bi bi-check"></i>
            </div>
        @endif
    </div>
    
    <h1 class="profile-name">{{ $user->name }}</h1>
    <p class="profile-phone">{{ $user->phone }}</p>
    <p class="member-since">
        <i class="bi bi-calendar3"></i> Membre depuis {{ $user->created_at->format('M Y') }}
    </p>
</div>

<!-- Stats Bar -->
<div class="stats-bar">
    <div class="stat-item">
        <span class="stat-value">{{ number_format($user->pieces_balance, 0) }}</span>
        <span class="stat-label">Pièces</span>
    </div>
    <div class="stat-item">
        <span class="stat-value">{{ $user->referredUsers()->count() }}</span>
        <span class="stat-label">Filleuls</span>
    </div>
    <div class="stat-item">
        <span class="stat-value">{{ $user->participations()->count() ?? 0 }}</span>
        <span class="stat-label">Campagnes</span>
    </div>
</div>

<div class="px-3">
    <!-- Account Information -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon blue">
                <i class="bi bi-person"></i>
            </div>
            <h2 class="section-title">Informations du Compte</h2>
        </div>
        <ul class="info-list">
            <li class="info-item">
                <span class="info-label">Nom complet</span>
                <span class="info-value">{{ $user->name }}</span>
            </li>
            <li class="info-item">
                <span class="info-label">Téléphone</span>
                <span class="info-value">
                    {{ $user->phone }}
                </span>
            </li>
            <li class="info-item">
                <span class="info-label">Pays</span>
                <span class="info-value">
                    @switch($user->country)
                        @case('SN') 🇸🇳 Sénégal @break
                        @case('CI') 🇨🇮 Côte d'Ivoire @break
                        @case('BF') ��🇫 Burkina Faso @break
                        @case('ML') 🇲🇱 Mali @break
                        @case('CM') 🇨🇲 Cameroun @break
                        @case('GN') 🇬🇳 Guinée @break
                        @case('BJ') 🇧🇯 Bénin @break
                        @case('TG') 🇹🇬 Togo @break
                        @default Non spécifié
                    @endswitch
                </span>
            </li>
        </ul>
    </div>

    <!-- Referral Code -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon purple">
                <i class="bi bi-share"></i>
            </div>
            <h2 class="section-title">Code de Parrainage</h2>
        </div>
        <div class="referral-code-box">
            <span class="referral-code" id="referralCode">{{ $user->referral_code }}</span>
            <button class="copy-btn" onclick="copyCode()">
                <i class="bi bi-copy"></i> Copier
            </button>
        </div>
    </div>

    <!-- Security Section -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon red">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h2 class="section-title">Sécurité & Confidentialité</h2>
        </div>
        <ul class="info-list">
            <li class="info-item">
                <span class="info-label">Mot de passe</span>
                <a href="{{ route('profile.edit') }}#password" class="info-value text-primary text-decoration-none">
                    Modifier <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        </ul>
        <div class="danger-section">
            <p class="danger-text">
                La suppression de votre compte est irréversible. Toutes vos données seront définitivement effacées.
            </p>
            <button type="button" class="btn-danger-outline" onclick="openDeleteModal()">
                <i class="bi bi-trash"></i> Supprimer mon compte
            </button>
        </div>
    </div>

    <!-- Logout -->
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="logout-btn">
            <i class="bi bi-box-arrow-right"></i> Déconnexion
        </button>
    </form>
</div>

<!-- Delete Account Modal -->
<div class="custom-modal" id="deleteAccountModal" style="display: none;">
    <div class="custom-modal-overlay" onclick="closeDeleteModal()"></div>
    <div class="custom-modal-dialog">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5 class="custom-modal-title text-danger">
                    <i class="bi bi-exclamation-triangle"></i> Supprimer le compte
                </h5>
                <button type="button" class="custom-modal-close" onclick="closeDeleteModal()">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <form action="{{ route('profile.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="custom-modal-body">
                    <p class="mb-3">Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.</p>
                    <p class="text-muted small mb-3">Pour confirmer, entrez votre mot de passe :</p>
                    <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                </div>
                <div class="custom-modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Annuler</button>
                    <button type="submit" class="btn btn-danger">Confirmer la suppression</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openDeleteModal() {
    document.getElementById('deleteAccountModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteAccountModal').style.display = 'none';
    document.body.style.overflow = '';
}

function copyCode() {
    const code = document.getElementById('referralCode').textContent;
    navigator.clipboard.writeText(code).then(() => {
        // Show toast
        const toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 start-50 translate-middle-x mb-5 px-4 py-2 bg-dark text-white rounded-pill';
        toast.style.zIndex = '9999';
        toast.innerHTML = '<i class="bi bi-check-circle me-2"></i>Code copié!';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    });
}
</script>
@endpush
