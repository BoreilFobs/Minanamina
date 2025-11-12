@extends('layouts.app')

@section('title', 'Connexion - Minanamina')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h3 class="card-title text-center mb-4">
                        <i class="bi bi-box-arrow-in-right text-primary"></i> Connexion
                    </h3>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Numéro de téléphone</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" 
                                   placeholder="+237 6 XX XX XX XX" required autofocus>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Se souvenir de moi</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-box-arrow-in-right"></i> Se connecter
                        </button>

                        <div class="text-center">
                            <a href="{{ route('password.request') }}" class="text-muted small">Mot de passe oublié?</a>
                        </div>

                        <hr>

                        <p class="text-center text-muted small mb-0">
                            Pas encore de compte? 
                            <a href="{{ route('register') }}" class="text-primary">Inscrivez-vous ici</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
