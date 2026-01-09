@extends('layouts.auth')

@section('title', 'Vérification du compte')

@section('content')
<div class="text-center mb-4">
    <div class="verification-icon mb-3">
        <i class="bi bi-shield-check"></i>
    </div>
    <h4 class="fw-bold">Vérifiez votre compte</h4>
    <p class="text-muted">
        Nous avons envoyé un code de vérification à 6 chiffres au<br>
        <strong class="text-primary">{{ $phone ?? 'votre numéro' }}</strong>
    </p>
</div>

@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form method="POST" action="{{ route('verification.verify') }}">
    @csrf

    <div class="mb-4">
        <label for="code" class="form-label">Code de vérification</label>
        <div class="verification-code-input">
            <input type="text" 
                   class="form-control form-control-lg text-center @error('code') is-invalid @enderror" 
                   id="code" 
                   name="code" 
                   maxlength="6" 
                   pattern="\d{6}" 
                   inputmode="numeric"
                   autocomplete="one-time-code"
                   placeholder="000000"
                   required 
                   autofocus>
        </div>
        @error('code')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <small class="text-muted">Entrez le code à 6 chiffres reçu par SMS</small>
    </div>

    <button type="submit" class="btn btn-primary w-100 mb-3">
        <i class="bi bi-check-circle me-2"></i>Vérifier mon compte
    </button>
</form>

<div class="text-center">
    <p class="text-muted small mb-2">Vous n'avez pas reçu de code ?</p>
    <form method="POST" action="{{ route('verification.resend') }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-link text-decoration-none p-0" id="resendBtn">
            <i class="bi bi-arrow-clockwise me-1"></i>Renvoyer le code
        </button>
    </form>
</div>

<hr class="my-4">

<div class="text-center">
    <a href="{{ route('login') }}" class="text-muted text-decoration-none small">
        <i class="bi bi-arrow-left me-1"></i>Retour à la connexion
    </a>
</div>

<style>
.verification-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
.verification-icon i {
    font-size: 2.5rem;
    color: white;
}
.verification-code-input input {
    font-size: 2rem;
    letter-spacing: 1rem;
    padding: 1rem;
    font-weight: 600;
    font-family: 'Courier New', monospace;
}
.verification-code-input input::placeholder {
    color: #dee2e6;
    letter-spacing: 1rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on first input and format code
    const codeInput = document.getElementById('code');
    
    codeInput.addEventListener('input', function(e) {
        // Only allow digits
        this.value = this.value.replace(/\D/g, '');
        
        // Auto-submit when 6 digits entered
        if (this.value.length === 6) {
            this.form.submit();
        }
    });
    
    // Countdown for resend button
    let resendBtn = document.getElementById('resendBtn');
    let countdown = 60;
    
    function startCountdown() {
        resendBtn.disabled = true;
        let timer = setInterval(function() {
            countdown--;
            resendBtn.innerHTML = `<i class="bi bi-clock me-1"></i>Renvoyer dans ${countdown}s`;
            
            if (countdown <= 0) {
                clearInterval(timer);
                resendBtn.disabled = false;
                resendBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Renvoyer le code';
                countdown = 60;
            }
        }, 1000);
    }
    
    // Start countdown if page was loaded after resend
    @if(session('resent'))
    startCountdown();
    @endif
});
</script>
@endsection
