@extends('layouts.app')<x-guest-layout>

    <form method="POST" action="{{ route('password.store') }}">

@section('title', 'Nouveau Mot de Passe - Minanamina')        @csrf



@section('content')        <!-- Password Reset Token -->

<div class="container py-5">        <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div class="row justify-content-center">

        <div class="col-md-5">        <!-- Email Address -->

            <div class="card shadow">        <div>

                <div class="card-body p-4">            <x-input-label for="email" :value="__('Email')" />

                    <h3 class="card-title text-center mb-4">            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />

                        <i class="bi bi-shield-lock text-primary"></i> Nouveau Mot de Passe            <x-input-error :messages="$errors->get('email')" class="mt-2" />

                    </h3>        </div>



                    <form method="POST" action="{{ route('password.update') }}">        <!-- Password -->

                        @csrf        <div class="mt-4">

                        <input type="hidden" name="token" value="{{ $token }}">            <x-input-label for="password" :value="__('Password')" />

                        <input type="hidden" name="phone" value="{{ $phone }}">            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />

                        <!-- Verification Code -->        </div>

                        <div class="mb-3">

                            <label for="code" class="form-label">Code de Vérification SMS</label>        <!-- Confirm Password -->

                            <input type="text" class="form-control @error('code') is-invalid @enderror"         <div class="mt-4">

                                   id="code" name="code" maxlength="6" required autofocus>            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                            @error('code')

                                <div class="invalid-feedback">{{ $message }}</div>            <x-text-input id="password_confirmation" class="block mt-1 w-full"

                            @enderror                                type="password"

                        </div>                                name="password_confirmation" required autocomplete="new-password" />



                        <!-- New Password -->            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

                        <div class="mb-3">        </div>

                            <label for="password" class="form-label">Nouveau Mot de Passe</label>

                            <input type="password" class="form-control @error('password') is-invalid @enderror"         <div class="flex items-center justify-end mt-4">

                                   id="password" name="password" required>            <x-primary-button>

                            <small class="text-muted">Minimum 8 caractères</small>                {{ __('Reset Password') }}

                            @error('password')            </x-primary-button>

                                <div class="invalid-feedback">{{ $message }}</div>        </div>

                            @enderror    </form>

                        </div></x-guest-layout>


                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le Mot de Passe</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-check-circle"></i> Réinitialiser le Mot de Passe
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
