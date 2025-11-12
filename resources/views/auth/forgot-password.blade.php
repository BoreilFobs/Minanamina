@extends('layouts.app')<x-guest-layout>

    <div class="mb-4 text-sm text-gray-600">

@section('title', 'Mot de passe oublié - Minanamina')        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}

    </div>

@section('content')

<div class="container py-5">    <!-- Session Status -->

    <div class="row justify-content-center">    <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="col-md-5">

            <div class="card shadow">    <form method="POST" action="{{ route('password.email') }}">

                <div class="card-body p-4">        @csrf

                    <h3 class="card-title text-center mb-4">

                        <i class="bi bi-key text-primary"></i> Réinitialiser le Mot de Passe        <!-- Email Address -->

                    </h3>        <div>

            <x-input-label for="email" :value="__('Email')" />

                    <p class="text-muted text-center mb-4">            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />

                        Entrez votre numéro de téléphone et nous vous enverrons un code SMS pour réinitialiser votre mot de passe.            <x-input-error :messages="$errors->get('email')" class="mt-2" />

                    </p>        </div>



                    @if(session('status'))        <div class="flex items-center justify-end mt-4">

                        <div class="alert alert-success">            <x-primary-button>

                            <i class="bi bi-check-circle"></i> {{ session('status') }}                {{ __('Email Password Reset Link') }}

                        </div>            </x-primary-button>

                    @endif        </div>

    </form>

                    <form method="POST" action="{{ route('password.phone') }}"></x-guest-layout>

                        @csrf

                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Numéro de téléphone</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" 
                                   placeholder="+221771234567" required autofocus>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-send"></i> Envoyer le Code SMS
                        </button>

                        <p class="text-center text-muted small mb-0">
                            <a href="{{ route('login') }}" class="text-primary">Retour à la connexion</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
