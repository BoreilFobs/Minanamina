@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Header -->
            <div class="mb-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
                <h1 class="h3 mb-0">Assigner un Rôle</h1>
                <p class="text-muted mb-0">Gérer les permissions de {{ $user->name }}</p>
            </div>

            <!-- User Info -->
            <div class="card mb-4" style="border: 2px solid #0d6efd;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" 
                             alt="{{ $user->name }}" 
                             class="rounded-circle me-3" 
                             style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" 
                             style="width: 60px; height: 60px; font-size: 1.5rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        @endif
                        <div>
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-0">{{ $user->phone }}</p>
                            <p class="text-muted mb-0 small">Inscrit le {{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Assignment Form -->
            <div class="card" style="border: 2px solid #0d6efd;">
                <div class="card-header text-white" style="background-color: #0d6efd;">
                    <h5 class="mb-0"><i class="bi bi-shield-check"></i> Choisir un Rôle</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.assign-role', $user) }}" method="POST">
                        @csrf

                        @if($user->id === auth()->id())
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            Attention: Vous modifiez votre propre rôle. Assurez-vous de ne pas perdre vos privilèges de super admin!
                        </div>
                        @endif

                        <!-- Role Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Sélectionner un rôle</label>
                            
                            <!-- User Role -->
                            <div class="form-check mb-3 p-3 border rounded {{ $user->role === 'user' ? 'bg-light' : '' }}">
                                <input class="form-check-input" type="radio" name="role" id="role_user" 
                                       value="user" {{ $user->role === 'user' ? 'checked' : '' }}>
                                <label class="form-check-label w-100" for="role_user">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>Utilisateur</strong>
                                            <p class="text-muted small mb-0">
                                                Accès standard: participer aux campagnes, gagner des pièces, convertir en cash
                                            </p>
                                        </div>
                                        <span class="badge bg-secondary">Basique</span>
                                    </div>
                                </label>
                            </div>

                            <!-- Campaign Creator Role -->
                            <div class="form-check mb-3 p-3 border rounded {{ $user->role === 'campaign_creator' ? 'bg-light' : '' }}">
                                <input class="form-check-input" type="radio" name="role" id="role_creator" 
                                       value="campaign_creator" {{ $user->role === 'campaign_creator' ? 'checked' : '' }}>
                                <label class="form-check-label w-100" for="role_creator">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>Créateur de Campagnes</strong>
                                            <p class="text-muted small mb-0">
                                                Peut créer et gérer des campagnes, voir les analytics, soumettre pour approbation
                                            </p>
                                            <ul class="small mb-0 mt-1">
                                                <li>Créer/modifier/supprimer des campagnes</li>
                                                <li>Voir les statistiques détaillées</li>
                                                <li>Dupliquer des campagnes</li>
                                                <li>Soumettre pour approbation (superadmin)</li>
                                            </ul>
                                        </div>
                                        <span class="badge bg-primary">Créateur</span>
                                    </div>
                                </label>
                            </div>

                            <!-- SuperAdmin Role -->
                            <div class="form-check mb-3 p-3 border rounded {{ $user->role === 'superadmin' ? 'bg-light' : '' }}">
                                <input class="form-check-input" type="radio" name="role" id="role_superadmin" 
                                       value="superadmin" {{ $user->role === 'superadmin' ? 'checked' : '' }}>
                                <label class="form-check-label w-100" for="role_superadmin">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>Super Administrateur</strong>
                                            <p class="text-muted small mb-0">
                                                Accès complet: gestion des utilisateurs, approbation campagnes, validation participations, gestion pièces
                                            </p>
                                            <ul class="small mb-0 mt-1">
                                                <li>Toutes les permissions de créateur</li>
                                                <li>Approuver/rejeter des campagnes</li>
                                                <li>Valider les participations et attribuer des pièces</li>
                                                <li>Gérer les utilisateurs et leurs rôles</li>
                                                <li>Gérer les conversions et paiements</li>
                                            </ul>
                                        </div>
                                        <span class="badge bg-danger">Admin</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        @error('role')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Mettre à jour le rôle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
