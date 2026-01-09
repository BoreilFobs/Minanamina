@extends('layouts.auth')

@section('title', 'Connexion - Minanamina')

@section('content')
<div class="auth-card animate-slide-up">
    <h1 class="auth-title">Bon retour !</h1>
    <p class="auth-subtitle">Connectez-vous pour accéder à votre compte</p>
    
    @if(session('error'))
    <div class="alert alert--danger mb-lg">
        <i class="bi bi-exclamation-circle alert__icon"></i>
        <div class="alert__content">{{ session('error') }}</div>
    </div>
    @endif
    
    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <div class="form-group">
            <label for="phone" class="form-label form-label--required">Numéro de téléphone</label>
            <input type="tel" 
                   id="phone" 
                   name="phone" 
                   class="form-input form-select {{ $errors->has('phone') ? 'form-input--error' : '' }}"
                   value="{{ old('phone') }}"
                   placeholder="Ex: 0701234567"
                   autocomplete="tel"
                   required
                   autofocus>
            @error('phone')
            <div class="form-error">
                <i class="bi bi-exclamation-circle"></i>
                {{ $message }}
            </div>
            @enderror
        </div>
        
        <div class="form-group">
            <div class="flex justify-between items-center mb-sm">
                <label for="password" class="form-label form-label--required m-0">Mot de passe</label>
                <a href="{{ route('password.request') }}" class="text-sm" style="color: var(--primary);">Oublié ?</a>
            </div>
            <div style="position: relative;">
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-input {{ $errors->has('password') ? 'form-input--error' : '' }}"
                       placeholder="••••••••"
                       autocomplete="current-password"
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
        
        <div class="form-check mb-lg">
            <input type="checkbox" 
                   id="remember" 
                   name="remember" 
                   class="form-check-input"
                   {{ old('remember') ? 'checked' : '' }}>
            <label for="remember" class="form-check-label">Se souvenir de moi</label>
        </div>
        
        <button type="submit" class="btn btn--primary btn--lg btn--block">
            <i class="bi bi-box-arrow-in-right"></i>
            Se connecter
        </button>
    </form>
    
    <div class="auth-divider">ou</div>
    
    <div class="text-center">
        <p class="text-muted mb-md">Pas encore de compte ?</p>
        <a href="{{ route('register') }}" class="btn btn--outline btn--block">
            Créer un compte
        </a>
    </div>
</div>

<div class="auth-footer">
    En vous connectant, vous acceptez nos <a href="#">Conditions</a> et notre <a href="#">Politique de confidentialité</a>
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
</script>
@endpush
