@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Mes Participations</h1>
            <p class="text-muted mb-0">Suivez l'état de vos participations</p>
        </div>
        <a href="{{ route('campaigns.index') }}" class="btn btn-primary">
            <i class="bi bi-megaphone"></i> Nouvelles Campagnes
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card" style="border: 2px solid #0d6efd; background-color: #0d6efd;">
                <div class="card-body text-white text-center">
                    <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
                    <small>Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="border: 2px solid #ffc107; background-color: #ffc107;">
                <div class="card-body text-dark text-center">
                    <h3 class="mb-0">{{ number_format($stats['pending']) }}</h3>
                    <small>En Attente</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="border: 2px solid #198754; background-color: #198754;">
                <div class="card-body text-white text-center">
                    <h3 class="mb-0">{{ number_format($stats['completed']) }}</h3>
                    <small>Complétées</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="border: 2px solid #6f42c1; background-color: #6f42c1;">
                <div class="card-body text-white text-center">
                    <h3 class="mb-0"><i class="bi bi-coin"></i> {{ number_format($stats['total_earned']) }}</h3>
                    <small>Pièces Gagnées</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Participations List -->
    <div class="card" style="border: 2px solid #0d6efd;">
        <div class="card-header text-white" style="background-color: #0d6efd;">
            <h5 class="mb-0"><i class="bi bi-list-check"></i> Historique des Participations</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th style="font-weight: 600;">Campagne</th>
                            <th style="font-weight: 600;">Récompense</th>
                            <th style="font-weight: 600;">Statut</th>
                            <th style="font-weight: 600;">Date de Participation</th>
                            <th style="font-weight: 600;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($participations as $participation)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($participation->campaign->image)
                                    <img src="{{ asset('storage/' . $participation->campaign->image) }}" 
                                         alt="{{ $participation->campaign->title }}"
                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; margin-right: 10px;">
                                    @endif
                                    <div>
                                        <strong>{{ Str::limit($participation->campaign->title, 40) }}</strong><br>
                                        <small class="text-muted">{{ Str::limit($participation->campaign->description, 60) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background-color: #ffc107; color: #000; font-size: 14px;">
                                    <i class="bi bi-coin"></i> {{ number_format($participation->campaign->pieces_reward) }}
                                </span>
                            </td>
                            <td>
                                @if($participation->status == 'pending')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-clock-history"></i> En Attente
                                    </span>
                                @elseif($participation->status == 'completed')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Complétée
                                    </span>
                                    <br><small class="text-muted">
                                        +{{ number_format($participation->pieces_earned) }} pièces
                                    </small>
                                @elseif($participation->status == 'rejected')
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle"></i> Rejetée
                                    </span>
                                @endif
                            </td>
                            <td>
                                {{ $participation->started_at->format('d/m/Y') }}<br>
                                <small class="text-muted">{{ $participation->started_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('campaigns.show', $participation->campaign) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> Voir
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #dee2e6;"></i>
                                <p class="text-muted mt-2">Aucune participation pour le moment</p>
                                <a href="{{ route('campaigns.index') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-megaphone"></i> Découvrir les Campagnes
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($participations->hasPages())
        <div class="card-footer">
            {{ $participations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
