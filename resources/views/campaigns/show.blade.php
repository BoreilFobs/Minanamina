@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="mb-4">
        <a href="{{ route('campaigns.index') }}" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Retour aux Campagnes
        </a>
    </div>

    <div class="row">
        <!-- Left Column: Campaign Details -->
        <div class="col-lg-8">
            <!-- Campaign Image and Info -->
            <div class="card mb-4" style="border: 2px solid #0d6efd;">
                @if($campaign->image)
                <img src="{{ asset('storage/' . $campaign->image) }}" 
                     class="card-img-top" 
                     alt="{{ $campaign->title }}"
                     style="max-height: 400px; object-fit: cover;">
                @endif
                
                <div class="card-body">
                    <h1 class="h3" style="font-weight: 600;">{{ $campaign->title }}</h1>
                    
                    <div class="mb-4">
                        <span class="badge bg-success me-2">
                            <i class="bi bi-check-circle"></i> Active
                        </span>
                        <span class="badge" style="background-color: #ffc107; color: #000; font-size: 1.2rem;">
                            <i class="bi bi-coin"></i> {{ number_format($campaign->pieces_reward) }} pièces
                        </span>
                    </div>

                    <h5 style="font-weight: 600;"><i class="bi bi-file-text"></i> Description</h5>
                    <p style="white-space: pre-wrap; font-size: 1.05rem;">{{ $campaign->description }}</p>

                    @if($campaign->validation_rules)
                    <hr>
                    <h5 style="font-weight: 600;"><i class="bi bi-check-square"></i> Comment Participer</h5>
                    <div class="alert alert-info">
                        <p style="white-space: pre-wrap; margin-bottom: 0;">{{ $campaign->validation_rules }}</p>
                    </div>
                    @endif

                    <hr>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong style="font-weight: 600;"><i class="bi bi-calendar-event"></i> Date de Début</strong><br>
                            {{ \Carbon\Carbon::parse($campaign->start_date)->format('d/m/Y') }}
                        </div>
                        <div class="col-md-6">
                            <strong style="font-weight: 600;"><i class="bi bi-calendar-x"></i> Date de Fin</strong><br>
                            {{ \Carbon\Carbon::parse($campaign->end_date)->format('d/m/Y') }}
                            @php
                                $daysLeft = \Carbon\Carbon::parse($campaign->end_date)->diffInDays(now());
                            @endphp
                            <br><small class="text-{{ $daysLeft <= 3 ? 'danger' : 'muted' }}">
                                <i class="bi bi-hourglass-split"></i> Plus que {{ $daysLeft }} jour(s)
                            </small>
                        </div>
                        <div class="col-md-12">
                            <strong style="font-weight: 600;"><i class="bi bi-people"></i> Participants</strong><br>
                            {{ number_format($stats['total_participants']) }} participant(s)
                            ({{ number_format($stats['completed_participations']) }} complété(s))
                        </div>
                    </div>

                    @if($campaign->geographic_restrictions)
                    <hr>
                    <h6 style="font-weight: 600;"><i class="bi bi-geo-alt"></i> Disponible dans</h6>
                    <p>
                        @php
                            $restrictions = is_array($campaign->geographic_restrictions) 
                                ? $campaign->geographic_restrictions
                                : json_decode($campaign->geographic_restrictions, true) ?? [];
                        @endphp
                        @if(empty($restrictions))
                            Tous les pays
                        @else
                            {{ implode(', ', $restrictions) }}
                        @endif
                    </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Participation Panel -->
        <div class="col-lg-4">
            @if($userParticipation)
            <!-- User Already Participated -->
            <div class="card mb-3" style="border: 2px solid #ffc107;">
                <div class="card-header text-dark" style="background-color: #ffc107;">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Votre Participation</h5>
                </div>
                <div class="card-body">
                    <p><strong>Statut:</strong></p>
                    @if($userParticipation->status == 'pending')
                        <span class="badge bg-warning text-dark fs-6">
                            <i class="bi bi-clock-history"></i> En Attente de Validation
                        </span>
                        <div class="alert alert-info mt-3">
                            <small>
                                <i class="bi bi-info-circle"></i> 
                                Votre participation est en cours de vérification. 
                                Vous serez notifié une fois validée.
                            </small>
                        </div>
                    @elseif($userParticipation->status == 'completed')
                        <span class="badge bg-success fs-6">
                            <i class="bi bi-check-circle"></i> Complétée
                        </span>
                        <div class="alert alert-success mt-3">
                            <strong><i class="bi bi-coin"></i> {{ number_format($userParticipation->pieces_earned) }} pièces gagnées!</strong><br>
                            <small>Le {{ $userParticipation->completed_at->format('d/m/Y à H:i') }}</small>
                        </div>
                    @elseif($userParticipation->status == 'rejected')
                        <span class="badge bg-danger fs-6">
                            <i class="bi bi-x-circle"></i> Rejetée
                        </span>
                        <div class="alert alert-danger mt-3">
                            <small>
                                <i class="bi bi-exclamation-triangle"></i> 
                                Votre participation n'a pas été validée.
                            </small>
                        </div>
                    @endif

                    <hr>

                    <p class="mb-0">
                        <small class="text-muted">
                            Participé le {{ $userParticipation->started_at->format('d/m/Y à H:i') }}
                        </small>
                    </p>
                </div>
            </div>
            @else
            <!-- Participation Button -->
            <div class="card mb-3" style="border: 2px solid #198754;">
                <div class="card-header text-white" style="background-color: #198754;">
                    <h5 class="mb-0"><i class="bi bi-rocket"></i> Participer Maintenant</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="display-6" style="color: #ffc107;">
                            <i class="bi bi-coin"></i>
                        </div>
                        <h3 style="font-weight: 600; color: #198754;">
                            {{ number_format($campaign->pieces_reward) }} pièces
                        </h3>
                        <p class="text-muted">à gagner!</p>
                    </div>

                    @auth
                    <form action="{{ route('campaigns.participate', $campaign) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-rocket-takeoff"></i> Participer Maintenant
                        </button>
                    </form>
                    <small class="text-muted d-block mt-2 text-center">
                        <i class="bi bi-info-circle"></i> Vous serez redirigé vers le site partenaire
                    </small>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Connectez-vous pour Participer
                    </a>
                    @endauth
                </div>
            </div>
            @endif

            <!-- Campaign Stats -->
            <div class="card" style="border: 2px solid #0d6efd;">
                <div class="card-header text-white" style="background-color: #0d6efd;">
                    <h6 class="mb-0"><i class="bi bi-graph-up"></i> Statistiques</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span style="font-weight: 600;">Participants Total</span>
                        <span class="badge bg-primary">{{ number_format($stats['total_participants']) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span style="font-weight: 600;">Validations</span>
                        <span class="badge bg-success">{{ number_format($stats['completed_participations']) }}</span>
                    </div>
                    @if($stats['total_participants'] > 0)
                    <div class="d-flex justify-content-between">
                        <span style="font-weight: 600;">Taux de Réussite</span>
                        <span class="badge bg-info">
                            {{ round(($stats['completed_participations'] / $stats['total_participants']) * 100, 1) }}%
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
