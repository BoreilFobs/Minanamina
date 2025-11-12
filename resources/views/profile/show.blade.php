@extends('layouts.app')

@section('title', 'Profil - Minanamina')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-4 mb-4">
            <!-- Profile Card -->
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px; font-size: 3rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->phone }}</p>
                    <span class="badge bg-success mb-3">Actif</span>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Modifier le Profil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h6 class="card-title mb-3">Statistiques</h6>
                    <div class="mb-2">
                        <i class="bi bi-gem text-warning"></i>
                        <strong>Solde de Pièces:</strong> {{ number_format($user->pieces_balance, 2) }}
                    </div>
                    <div class="mb-2">
                        <i class="bi bi-people text-primary"></i>
                        <strong>Parrainages:</strong> {{ $user->referrals()->count() }}
                    </div>
                    <div class="mb-2">
                        <i class="bi bi-code text-info"></i>
                        <strong>Code de Parrainage:</strong> <code>{{ $user->referral_code }}</code>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Account Information -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-person-circle"></i> Informations du Compte</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Nom Complet:</strong></div>
                        <div class="col-sm-8">{{ $user->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Téléphone:</strong></div>
                        <div class="col-sm-8">
                            {{ $user->phone }}
                            @if($user->phone_verified_at)
                                <span class="badge bg-success ms-2"><i class="bi bi-check-circle"></i> Vérifié</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Pays:</strong></div>
                        <div class="col-sm-8">{{ $user->country ?? 'Non spécifié' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Membre Depuis:</strong></div>
                        <div class="col-sm-8">{{ $user->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- Notification Preferences -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-bell"></i> Préférences de Notification</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.notifications') }}" method="POST">
                        @csrf
                        @php
                            $preferences = $user->notification_preferences ?? [];
                        @endphp
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="sms_notifications" 
                                   id="sms_notifications" {{ ($preferences['sms_notifications'] ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="sms_notifications">
                                Notifications SMS
                            </label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="campaign_updates" 
                                   id="campaign_updates" {{ ($preferences['campaign_updates'] ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="campaign_updates">
                                Mises à Jour des Campagnes
                            </label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="referral_updates" 
                                   id="referral_updates" {{ ($preferences['referral_updates'] ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="referral_updates">
                                Mises à Jour des Parrainages
                            </label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="payment_updates" 
                                   id="payment_updates" {{ ($preferences['payment_updates'] ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="payment_updates">
                                Mises à Jour des Paiements
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Enregistrer les Préférences
                        </button>
                    </form>
                </div>
            </div>

            <!-- Privacy Settings -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-shield-lock"></i> Paramètres de Confidentialité</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.privacy') }}" method="POST">
                        @csrf
                        @php
                            $privacy = $user->privacy_settings ?? [];
                        @endphp
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="show_profile" 
                                   id="show_profile" {{ ($privacy['show_profile'] ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_profile">
                                Afficher mon profil publiquement
                            </label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="show_earnings" 
                                   id="show_earnings" {{ ($privacy['show_earnings'] ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_earnings">
                                Afficher mes gains sur le classement
                            </label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="show_referrals" 
                                   id="show_referrals" {{ ($privacy['show_referrals'] ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_referrals">
                                Afficher le nombre de mes parrainages
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Enregistrer les Paramètres
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
