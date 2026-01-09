@extends('layouts.admin')

@section('title', 'Détails Campagne')
@section('page-title', 'Détails Campagne')

@section('content')
<div class="admin-page">
    <div class="admin-page__header d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="admin-page__title">{{ $campaign->title }}</h1>
            <p class="admin-page__subtitle">Détails et statistiques de la campagne</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.campaigns.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
            <a href="{{ route('admin.campaigns.analytics.show', $campaign) }}" class="btn btn-info">
                <i class="bi bi-graph-up"></i> Analytiques
            </a>
            <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <form action="{{ route('admin.campaigns.duplicate', $campaign) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-files"></i> Dupliquer
                </button>
            </form>
            @if($campaign->status == 'draft')
            <form action="{{ route('admin.campaigns.submit-approval', $campaign) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Soumettre
                </button>
            </form>
            @elseif($campaign->status == 'published')
            <form action="{{ route('admin.campaigns.pause', $campaign) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-pause-circle"></i> Pause
                </button>
            </form>
            @elseif($campaign->status == 'paused')
            <form action="{{ route('admin.campaigns.resume', $campaign) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-play-circle"></i> Relancer
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Status Badge -->
    <div class="mb-4">
        @if($campaign->status == 'draft')
            <span class="badge bg-secondary fs-6">Brouillon</span>
        @elseif($campaign->status == 'pending_approval')
            <span class="badge bg-warning text-dark fs-6">En attente d'approbation</span>
        @elseif($campaign->status == 'published')
            <span class="badge bg-success fs-6">Publié</span>
        @elseif($campaign->status == 'paused')
            <span class="badge bg-info text-dark fs-6">Pausé</span>
        @else
            <span class="badge bg-dark fs-6">Terminé</span>
        @endif
    </div>

    <div class="row">
        <!-- Left Column: Campaign Details -->
        <div class="col-lg-8">
            <!-- Campaign Image and Info -->
            <div class="card mb-4" style="border: 2px solid #0d6efd;">
                <div class="card-header text-white" style="background-color: #0d6efd;">
                    <h5 class="mb-0"><i class="bi bi-image"></i> Détails de la Campagne</h5>
                </div>
                <div class="card-body">
                    @if($campaign->image)
                    <img src="{{ asset('storage/' . $campaign->image) }}" 
                         alt="{{ $campaign->title }}" 
                         class="img-fluid rounded mb-3"
                         style="max-height: 400px; width: 100%; object-fit: cover; border: 2px solid #dee2e6;">
                    @endif

                    <h4 style="font-weight: 600;">Description</h4>
                    <p style="white-space: pre-wrap;">{{ $campaign->description }}</p>

                    <hr>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong style="font-weight: 600;">Lien CPA:</strong><br>
                            <a href="{{ $campaign->cpa_link }}" target="_blank" class="text-primary">
                                {{ Str::limit($campaign->cpa_link, 50) }} 
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <strong style="font-weight: 600;">Récompense:</strong><br>
                            <span class="badge" style="background-color: #ffc107; color: #000; font-size: 16px;">
                                <i class="bi bi-coin"></i> {{ number_format($campaign->pieces_reward) }} pièces
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong style="font-weight: 600;">Date de Début:</strong><br>
                            {{ \Carbon\Carbon::parse($campaign->start_date)->format('d/m/Y') }}
                        </div>
                        <div class="col-md-6">
                            <strong style="font-weight: 600;">Date de Fin:</strong><br>
                            {{ \Carbon\Carbon::parse($campaign->end_date)->format('d/m/Y') }}
                        </div>
                        <div class="col-md-12">
                            <strong style="font-weight: 600;">Créée par:</strong><br>
                            {{ $campaign->creator->name ?? 'N/A' }} le {{ $campaign->created_at->format('d/m/Y à H:i') }}
                        </div>
                    </div>

                    @if($campaign->validation_rules)
                    <hr>
                    <h5 style="font-weight: 600;"><i class="bi bi-check-square"></i> Conditions de Validation</h5>
                    <p style="white-space: pre-wrap;">{{ $campaign->validation_rules }}</p>
                    @endif

                    @if($campaign->geographic_restrictions)
                    <hr>
                    <h5 style="font-weight: 600;"><i class="bi bi-geo-alt"></i> Restrictions Géographiques</h5>
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

            <!-- Participations List -->
            <div class="card" style="border: 2px solid #198754;">
                <div class="card-header text-white" style="background-color: #198754;">
                    <h5 class="mb-0"><i class="bi bi-people"></i> Participations Récentes</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th style="font-weight: 600;">Utilisateur</th>
                                    <th style="font-weight: 600;">Statut</th>
                                    <th style="font-weight: 600;">Pièces</th>
                                    <th style="font-weight: 600;">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($campaign->participations()->latest()->take(10)->get() as $participation)
                                <tr>
                                    <td>{{ $participation->user->name }}</td>
                                    <td>
                                        @if($participation->status == 'pending')
                                            <span class="badge bg-warning text-dark">En attente</span>
                                        @elseif($participation->status == 'completed')
                                            <span class="badge bg-success">Complété</span>
                                        @elseif($participation->status == 'rejected')
                                            <span class="badge bg-danger">Rejeté</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: #ffc107; color: #000;">
                                            {{ number_format($participation->pieces_earned) }}
                                        </span>
                                    </td>
                                    <td>{{ $participation->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Aucune participation pour le moment
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Statistics -->
        <div class="col-lg-4">
            <!-- Statistics Cards -->
            <div class="card mb-4" style="border: 2px solid #0d6efd;">
                <div class="card-header text-white" style="background-color: #0d6efd;">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Statistiques</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-weight: 600;">Participants Total</span>
                            <span class="badge bg-primary fs-6">{{ number_format($stats['total_participants']) }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-weight: 600;">Complétés</span>
                            <span class="badge bg-success fs-6">{{ number_format($stats['completed_participations']) }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" 
                                 style="width: {{ $stats['total_participants'] > 0 ? ($stats['completed_participations'] / $stats['total_participants']) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-weight: 600;">Taux de Conversion</span>
                            <span class="badge bg-info fs-6">{{ $stats['conversion_rate'] }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: {{ $stats['conversion_rate'] }}%"></div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-weight: 600;">Pièces Distribuées</span>
                            <span class="badge" style="background-color: #ffc107; color: #000; font-size: 14px;">
                                {{ number_format($stats['total_pieces_distributed']) }}
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="background-color: #ffc107; width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campaign Timeline -->
            <div class="card" style="border: 2px solid #6f42c1;">
                <div class="card-header text-white" style="background-color: #6f42c1;">
                    <h5 class="mb-0"><i class="bi bi-calendar"></i> Période de la Campagne</h5>
                </div>
                <div class="card-body">
                    @php
                        $now = now();
                        $start = \Carbon\Carbon::parse($campaign->start_date);
                        $end = \Carbon\Carbon::parse($campaign->end_date);
                        $totalDays = $start->diffInDays($end);
                        $daysElapsed = $start->isPast() ? $start->diffInDays($now) : 0;
                        $daysRemaining = $end->isFuture() ? $now->diffInDays($end) : 0;
                        $progress = $totalDays > 0 ? min(100, ($daysElapsed / $totalDays) * 100) : 0;
                    @endphp

                    <div class="mb-3">
                        <small class="text-muted">Début</small>
                        <p class="mb-0" style="font-weight: 600;">{{ $start->format('d M Y') }}</p>
                    </div>

                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar bg-success" style="width: {{ $progress }}%">
                            {{ round($progress) }}%
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Fin</small>
                        <p class="mb-0" style="font-weight: 600;">{{ $end->format('d M Y') }}</p>
                    </div>

                    <div class="alert alert-info mb-0">
                        @if($start->isFuture())
                            <i class="bi bi-hourglass-split"></i> Débute dans <strong>{{ $now->diffInDays($start) }} jours</strong>
                        @elseif($end->isFuture())
                            <i class="bi bi-check-circle"></i> <strong>{{ $daysRemaining }} jours</strong> restants
                        @else
                            <i class="bi bi-flag-fill"></i> Campagne terminée
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
