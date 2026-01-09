@extends('layouts.auth')

@section('title', 'Inscription - Minanamina')

@section('content')
<div class="auth-card animate-slide-up">
    <h1 class="auth-title">Créer un compte</h1>
    <p class="auth-subtitle">Rejoignez Minanamina et commencez à gagner</p>
    
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label form-label--required">Nom complet</label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   class="form-input {{ $errors->has('name') ? 'form-input--error' : '' }}"
                   value="{{ old('name') }}"
                   placeholder="Jean Dupont"
                   autocomplete="name"
                   required 
                   autofocus>
            @error('name')
            <div class="form-error">
                <i class="bi bi-exclamation-circle"></i>
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone" class="form-label form-label--required">Numéro de téléphone</label>
            <input type="tel" 
                   id="phone" 
                   name="phone" 
                   class="form-input {{ $errors->has('phone') ? 'form-input--error' : '' }}"
                   value="{{ old('phone') }}"
                   placeholder="Ex: 0701234567"
                   autocomplete="tel"
                   required>
            <div class="form-hint">Ce numéro sera utilisé pour vous connecter</div>
            @error('phone')
            <div class="form-error">
                <i class="bi bi-exclamation-circle"></i>
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label form-label--required">Mot de passe</label>
            <div style="position: relative;">
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-input {{ $errors->has('password') ? 'form-input--error' : '' }}"
                       placeholder="Minimum 8 caractères"
                       autocomplete="new-password"
                       required>
                <button type="button" 
                        onclick="togglePassword('password')" 
                        class="btn btn--ghost btn--icon"
                        style="position: absolute; right: 4px; top: 50%; transform: translateY(-50%);">
                    <i class="bi bi-eye" id="password-toggle-icon"></i>
                </button>
            </div>
            @error('password')
            <div class="form-error">
                <i class="bi bi-exclamation-circle"></i>
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label form-label--required">Confirmer le mot de passe</label>
            <input type="password" 
                   id="password_confirmation" 
                   name="password_confirmation" 
                   class="form-input"
                   placeholder="Répétez le mot de passe"
                   autocomplete="new-password"
                   required>
        </div>

        <div class="form-group">
            <label for="referral_code" class="form-label">
                Code de parrainage
                <span class="badge badge--success" style="margin-left: 4px;">Bonus!</span>
            </label>
            <input type="text" 
                   id="referral_code" 
                   name="referral_code" 
                   class="form-input {{ $errors->has('referral_code') ? 'form-input--error' : '' }}"
                   value="{{ old('referral_code', request('ref')) }}"
                   placeholder="Entrez un code si vous en avez un"
                   {{ request('ref') ? 'readonly style=background:var(--gray-100);cursor:not-allowed;' : '' }}>
            @if(request('ref'))
            <div class="form-hint" style="color: var(--success);">
                <i class="bi bi-check-circle"></i> Vous recevrez un bonus de bienvenue !
            </div>
            @else
            <div class="form-hint">
                <i class="bi bi-gift"></i> Bonus de bienvenue avec un code valide
            </div>
            @endif
            @error('referral_code')
            <div class="form-error">
                <i class="bi bi-exclamation-circle"></i>
                {{ $message }}
            </div>
            @enderror
        </div>

        <button type="submit" class="btn btn--primary btn--lg btn--block" onclick="return confirmNoReferral()">
            <i class="bi bi-person-plus"></i>
            Créer mon compte
        </button>
    </form>
    
    <div class="alert alert--info mt-lg">
        <i class="bi bi-shield-check alert__icon"></i>
        <div class="alert__content text-sm">
            Un code de vérification SMS sera envoyé pour confirmer votre numéro
        </div>
    </div>
    
    <div class="auth-divider">ou</div>
    
    <div class="text-center">
        <p class="text-muted mb-md">Déjà inscrit ?</p>
        <a href="{{ route('login') }}" class="btn btn--outline btn--block">
            Se connecter
        </a>
    </div>
</div>

<div class="auth-footer">
    En créant un compte, vous acceptez nos <a href="#">Conditions</a> et notre <a href="#">Politique de confidentialité</a>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '-toggle-icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}

function confirmNoReferral() {
    const referralCode = document.getElementById('referral_code').value.trim();
    
    if (!referralCode) {
        return confirm(
            '⚠️ Vous n\'avez pas saisi de code de parrainage.\n\n' +
            'En utilisant un code de parrainage, vous recevrez un bonus de bienvenue.\n\n' +
            'Voulez-vous continuer sans code de parrainage ?'
        );
    }
    
    return true;
}
</script>
@endpush
