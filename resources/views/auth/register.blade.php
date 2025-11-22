@extends('layouts.app')

@section('title', 'Inscription - Minanamina')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
            <div class="card shadow border-0">
                <div class="card-body p-4 p-md-5">
                    <h3 class="card-title text-center mb-4">
                        <i class="bi bi-person-plus-fill text-primary"></i> Créer un compte
                    </h3>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom complet</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Numéro de téléphone</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" placeholder="+221771234567" required>
                            <small class="text-muted">Format: +[indicatif pays][numéro]</small>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            <small class="text-muted">Minimum 8 caractères</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <!-- Referral Code (Optional but Recommended) -->
                        <div class="mb-3">
                            <label for="referral_code" class="form-label">
                                Code de parrainage 
                                <span class="text-muted">(Optionnel)</span>
                                <span class="badge bg-success">Bonus!</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('referral_code') is-invalid @enderror" 
                                   id="referral_code" 
                                   name="referral_code" 
                                   value="{{ old('referral_code', request('ref')) }}" 
                                   placeholder="Entrez le code de parrainage"
                                   {{ request('ref') ? 'readonly' : '' }}>
                            @error('referral_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(request('ref'))
                                <small class="text-success">
                                    <i class="bi bi-check-circle-fill"></i> 
                                    Vous utilisez un code de parrainage! Vous recevrez un bonus à l'inscription.
                                </small>
                            @else
                                <small class="text-muted">
                                    <i class="bi bi-gift"></i> 
                                    Entrez un code de parrainage pour recevoir un bonus de bienvenue
                                </small>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3" onclick="return confirmNoReferral()">
                            <i class="bi bi-person-check"></i> S'inscrire
                        </button>

                        <p class="text-center text-muted small mb-0">
                            Vous avez déjà un compte? 
                            <a href="{{ route('login') }}" class="text-primary">Connexion</a>
                        </p>
                    </form>

                    <div class="alert alert-info mt-3 mb-0">
                        <small><i class="bi bi-info-circle"></i> Un code de vérification SMS sera envoyé après l'inscription</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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

// Lock referral code field if it comes from URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const refFromUrl = urlParams.get('ref');
    const referralInput = document.getElementById('referral_code');
    
    if (refFromUrl && referralInput) {
        referralInput.value = refFromUrl;
        referralInput.readOnly = true;
        referralInput.style.backgroundColor = '#e9ecef';
        referralInput.style.cursor = 'not-allowed';
    }
});
</script>
@endsection
