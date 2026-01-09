@extends('layouts.auth')

@section('title', 'Mot de passe oublié')

@section('content')
<div class="text-center mb-4">
    <div class="forgot-icon mb-3">
        <i class="bi bi-key"></i>
    </div>
    <h4 class="fw-bold">Mot de passe oublié ?</h4>
    <p class="text-muted">
        Entrez votre numéro de téléphone et nous vous enverrons un code SMS pour réinitialiser votre mot de passe.
    </p>
</div>

@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form method="POST" action="{{ route('password.phone') }}">
    @csrf

    <div class="mb-4">
        <label for="phone" class="form-label">Numéro de téléphone</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-phone"></i></span>
            <input type="tel" 
                   class="form-control @error('phone') is-invalid @enderror" 
                   id="phone" 
                   name="phone" 
                   value="{{ old('phone') }}"
                   placeholder="+221 77 123 45 67"
                   required 
                   autofocus>
        </div>
        @error('phone')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary w-100 mb-3">
        <i class="bi bi-send me-2"></i>Envoyer le code SMS
    </button>
</form>

<hr class="my-4">

<div class="text-center">
    <p class="text-muted mb-2">Vous vous souvenez de votre mot de passe ?</p>
    <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100">
        <i class="bi bi-arrow-left me-2"></i>Retour à la connexion
    </a>
</div>

<style>
.forgot-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--warning) 0%, #f39c12 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
.forgot-icon i {
    font-size: 2.5rem;
    color: white;
}
</style>
@endsection
