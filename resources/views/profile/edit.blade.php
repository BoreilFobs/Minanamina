@extends('layouts.modern')

@section('title', 'Modifier le Profil')

@push('styles')
<style>
    .edit-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #5a4fcf 100%);
        padding: 1.5rem;
        padding-top: 3rem;
        margin: -1.5rem -1rem 0;
        color: white;
        position: relative;
    }
    
    .back-btn {
        position: absolute;
        top: 1rem;
        left: 1rem;
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
    }
    
    .header-title {
        font-size: 1.25rem;
        font-weight: 600;
        text-align: center;
        margin: 0;
    }
    
    .avatar-edit-section {
        background: white;
        margin: -2rem 1rem 1rem;
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        position: relative;
        z-index: 10;
    }
    
    .avatar-edit-container {
        position: relative;
        display: inline-block;
        margin-bottom: 1rem;
    }
    
    .avatar-preview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--primary-color);
    }
    
    .avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color) 0%, #5a4fcf 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 600;
        color: white;
        border: 4px solid var(--primary-color);
    }
    
    .avatar-edit-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 36px;
        height: 36px;
        background: var(--primary-color);
        border: 3px solid white;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    
    .form-section {
        background: white;
        margin: 0 1rem 1rem;
        border-radius: 16px;
        padding: 1.25rem;
    }
    
    .section-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-group {
        margin-bottom: 1.25rem;
    }
    
    .form-label {
        font-size: 0.85rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(107, 79, 187, 0.1);
    }
    
    .form-control.is-invalid {
        border-color: #ef4444;
    }
    
    .form-control[readonly] {
        background: #f9fafb;
        color: #6b7280;
    }
    
    .form-select {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 0.875rem 1rem;
        font-size: 1rem;
    }
    
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(107, 79, 187, 0.1);
    }
    
    .input-hint {
        font-size: 0.8rem;
        color: #9ca3af;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .invalid-feedback {
        font-size: 0.8rem;
        color: #ef4444;
        margin-top: 0.5rem;
    }
    
    .password-toggle {
        position: relative;
    }
    
    .password-toggle .toggle-btn {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #9ca3af;
        padding: 0;
    }
    
    .btn-save {
        background: linear-gradient(135deg, var(--primary-color) 0%, #5a4fcf 100%);
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-save:disabled {
        opacity: 0.7;
    }
    
    .success-toast {
        position: fixed;
        bottom: 100px;
        left: 50%;
        transform: translateX(-50%);
        background: #10b981;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        z-index: 1000;
        animation: slideUp 0.3s ease;
    }
    
    @keyframes slideUp {
        from { opacity: 0; transform: translateX(-50%) translateY(20px); }
        to { opacity: 1; transform: translateX(-50%) translateY(0); }
    }
    
    .country-option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .bottom-spacer {
        height: 6rem;
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="edit-header">
    <a href="{{ route('profile.show') }}" class="back-btn">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="header-title">Modifier le Profil</h1>
</div>

<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
    @csrf
    @method('PUT')
    
    <!-- Avatar Section -->
    <div class="avatar-edit-section">
        <div class="avatar-edit-container">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="avatar-preview" id="avatarPreview">
            @else
                <div class="avatar-placeholder" id="avatarPlaceholder">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <img src="" alt="Avatar" class="avatar-preview d-none" id="avatarPreview">
            @endif
            <label for="avatarInput" class="avatar-edit-btn">
                <i class="bi bi-camera"></i>
            </label>
            <input type="file" id="avatarInput" name="avatar" accept="image/*" class="d-none">
        </div>
        <p class="text-muted mb-0" style="font-size: 0.85rem;">Appuyez pour changer votre photo</p>
        @error('avatar')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <!-- Personal Information -->
    <div class="form-section">
        <h2 class="section-title">
            <i class="bi bi-person"></i> Informations Personnelles
        </h2>
        
        <div class="form-group">
            <label for="name" class="form-label">Nom Complet</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                   id="name" name="name" value="{{ old('name', $user->name) }}" 
                   placeholder="Votre nom complet" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="phone" class="form-label">Numéro de Téléphone</label>
            <input type="tel" class="form-control" id="phone" value="{{ $user->phone }}" readonly>
            <p class="input-hint">
                <i class="bi bi-lock-fill"></i> Le numéro ne peut pas être modifié
            </p>
        </div>
        
        <div class="form-group mb-0">
            <label for="country" class="form-label">Pays</label>
            <select class="form-select @error('country') is-invalid @enderror" id="country" name="country">
                <option value="">Sélectionner un pays</option>
                <option value="SN" {{ old('country', $user->country) == 'SN' ? 'selected' : '' }}>🇸🇳 Sénégal</option>
                <option value="CI" {{ old('country', $user->country) == 'CI' ? 'selected' : '' }}>🇨🇮 Côte d'Ivoire</option>
                <option value="BF" {{ old('country', $user->country) == 'BF' ? 'selected' : '' }}>🇧🇫 Burkina Faso</option>
                <option value="ML" {{ old('country', $user->country) == 'ML' ? 'selected' : '' }}>🇲🇱 Mali</option>
                <option value="CM" {{ old('country', $user->country) == 'CM' ? 'selected' : '' }}>🇨🇲 Cameroun</option>
                <option value="GN" {{ old('country', $user->country) == 'GN' ? 'selected' : '' }}>🇬🇳 Guinée</option>
                <option value="BJ" {{ old('country', $user->country) == 'BJ' ? 'selected' : '' }}>��🇯 Bénin</option>
                <option value="TG" {{ old('country', $user->country) == 'TG' ? 'selected' : '' }}>🇹🇬 Togo</option>
            </select>
            @error('country')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Password Section -->
    <div class="form-section" id="password">
        <h2 class="section-title">
            <i class="bi bi-shield-lock"></i> Changer le Mot de Passe
        </h2>
        <p class="text-muted mb-3" style="font-size: 0.85rem;">Laissez vide si vous ne souhaitez pas changer</p>
        
        <div class="form-group">
            <label for="current_password" class="form-label">Mot de passe actuel</label>
            <div class="password-toggle">
                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                       id="current_password" name="current_password" placeholder="••••••••">
                <button type="button" class="toggle-btn" onclick="togglePassword('current_password')">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password" class="form-label">Nouveau mot de passe</label>
            <div class="password-toggle">
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password" placeholder="••••••••">
                <button type="button" class="toggle-btn" onclick="togglePassword('password')">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <p class="input-hint">Minimum 8 caractères</p>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group mb-0">
            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <div class="password-toggle">
                <input type="password" class="form-control" 
                       id="password_confirmation" name="password_confirmation" placeholder="••••••••">
                <button type="button" class="toggle-btn" onclick="togglePassword('password_confirmation')">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div class="px-3">
        <button type="submit" class="btn-save" id="saveBtn">
            <i class="bi bi-check-lg"></i> Enregistrer les modifications
        </button>
    </div>
</form>

<div class="bottom-spacer"></div>

@if(session('status') === 'profile-updated')
<div class="success-toast" id="successToast">
    <i class="bi bi-check-circle"></i> Profil mis à jour
</div>
@endif
@endsection

@push('scripts')
<script>
// Avatar preview
document.getElementById('avatarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatarPreview');
            const placeholder = document.getElementById('avatarPlaceholder');
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if (placeholder) {
                placeholder.classList.add('d-none');
            }
        }
        reader.readAsDataURL(file);
    }
});

// Password toggle
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

// Success toast auto-hide
const toast = document.getElementById('successToast');
if (toast) {
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(-50%) translateY(20px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Form submit state
document.getElementById('profileForm').addEventListener('submit', function() {
    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Enregistrement...';
});
</script>
@endpush
