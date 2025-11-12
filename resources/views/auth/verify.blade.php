@extends('layouts.app')

@section('title', 'Vérification du compte - Minanamina')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body p-4 text-center">
                    <div class="mb-4">
                        <i class="bi bi-shield-check text-primary" style="font-size: 3rem;"></i>
                    </div>
                    
                    <h3 class="card-title mb-3">Vérifiez votre compte</h3>
                    
                    <p class="text-muted mb-4">
                        Nous avons envoyé un code de vérification à 6 chiffres au<br>
                        <strong>{{ $phone }}</strong>
                    </p>

                    <form method="POST" action="{{ route('verification.verify') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="code" class="form-label">Entrez le code de vérification</label>
                            <input type="text" class="form-control form-control-lg text-center @error('code') is-invalid @enderror" 
                                   id="code" name="code" maxlength="6" pattern="\d{6}" required autofocus
                                   style="letter-spacing: 0.5rem; font-size: 1.5rem;">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-check-circle"></i> Vérifier
                        </button>
                    </form>

                    <form method="POST" action="{{ route('verification.resend') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link text-muted small">
                            <i class="bi bi-arrow-clockwise"></i> Renvoyer le code
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
