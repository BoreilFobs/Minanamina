@extends('layouts.auth')

@section('title', 'Nouveau mot de passe')

@section('content')
<div class="text-center mb-4">
    <div class="reset-icon mb-3">
        <i class="bi bi-shield-lock"></i>
    </div>
    <h4 class="fw-bold">Nouveau mot de passe</h4>
    <p class="text-muted">
        Entrez le code de vérification reçu par SMS et choisissez un nouveau mot de passe.
    </p>
</div>

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token ?? '' }}">
    <input type="hidden" name="phone" value="{{ $phone ?? '' }}">

    <!-- Verification Code -->
    <div class="mb-3">
        <label for="code" class="form-label">Code de vérification SMS</label>
        <input type="text" 
               class="form-control form-control-lg text-center @error('code') is-invalid @enderror" 
               id="code" 
               name="code" 
               maxlength="6"
               pattern="\d{6}"
               inputmode="numeric"
               placeholder="000000"
               style="letter-spacing: 0.5rem; font-size: 1.25rem;"
               required 
               autofocus>
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- New Password -->
    <div class="mb-3">
        <label for="password" class="form-label">Nouveau mot de passe</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   id="password" 
                   name="password"
                   minlength="8"
                   required>
            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                <i class="bi bi-eye" id="password-icon"></i>
            </button>
        </div>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <small class="text-muted">Minimum 8 caractères</small>
    </div>

    <!-- Confirm Password -->
    <div class="mb-4">
        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" 
                   class="form-control" 
                   id="password_confirmation" 
                   name="password_confirmation"
                   minlength="8"
                   required>
            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                <i class="bi bi-eye" id="password_confirmation-icon"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 mb-3">
        <i class="bi bi-check-circle me-2"></i>Réinitialiser le mot de passe
    </button>
</form>

<hr class="my-4">

<div class="text-center">
    <a href="{{ route('login') }}" class="text-muted text-decoration-none small">
        <i class="bi bi-arrow-left me-1"></i>Retour à la connexion
    </a>
</div>

<style>
.reset-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--success) 0%, #27ae60 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
.reset-icon i {
    font-size: 2.5rem;
    color: white;
}
</style>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

// Auto-format code input
document.getElementById('code').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '');
});
</script>
@endsection
