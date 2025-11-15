@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Campagnes Disponibles</h1>
            <p class="text-muted mb-0">Participez et gagnez des pièces!</p>
        </div>
        @auth
        <a href="{{ route('campaigns.my-participations') }}" class="btn btn-primary">
            <i class="bi bi-list-check"></i> Mes Participations
        </a>
        @endauth
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4" style="border: 2px solid #0d6efd;">
        <div class="card-header text-white" style="background-color: #0d6efd;">
            <h5 class="mb-0"><i class="bi bi-search"></i> Rechercher et Filtrer</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('campaigns.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label" style="font-weight: 600;">Rechercher</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Titre ou description...">
                </div>
                <div class="col-md-3">
                    <label for="min_reward" class="form-label" style="font-weight: 600;">Récompense Min.</label>
                    <input type="number" class="form-control" id="min_reward" name="min_reward" 
                           value="{{ request('min_reward') }}" 
                           placeholder="Ex: 50">
                </div>
                <div class="col-md-3">
                    <label for="max_reward" class="form-label" style="font-weight: 600;">Récompense Max.</label>
                    <input type="number" class="form-control" id="max_reward" name="max_reward" 
                           value="{{ request('max_reward') }}" 
                           placeholder="Ex: 500">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sorting -->
    <div class="mb-3">
        <div class="btn-group" role="group">
            <a href="{{ route('campaigns.index', ['sort' => 'latest'] + request()->except('sort')) }}" 
               class="btn btn-sm {{ request('sort', 'latest') == 'latest' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-clock"></i> Plus Récentes
            </a>
            <a href="{{ route('campaigns.index', ['sort' => 'reward_high'] + request()->except('sort')) }}" 
               class="btn btn-sm {{ request('sort') == 'reward_high' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-arrow-up"></i> Récompense +
            </a>
            <a href="{{ route('campaigns.index', ['sort' => 'reward_low'] + request()->except('sort')) }}" 
               class="btn btn-sm {{ request('sort') == 'reward_low' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-arrow-down"></i> Récompense -
            </a>
            <a href="{{ route('campaigns.index', ['sort' => 'ending_soon'] + request()->except('sort')) }}" 
               class="btn btn-sm {{ request('sort') == 'ending_soon' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-hourglass-split"></i> Fin Proche
            </a>
        </div>
    </div>

    <!-- Campaigns Grid -->
    <div class="row g-4">
        @forelse($campaigns as $campaign)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100" style="border: 2px solid #0d6efd;">
                @if($campaign->image)
                <img src="{{ asset('storage/' . $campaign->image) }}" 
                     class="card-img-top" 
                     alt="{{ $campaign->title }}"
                     style="height: 200px; object-fit: cover;">
                @else
                <div class="card-img-top d-flex align-items-center justify-content-center" 
                     style="height: 200px; background-color: #e9ecef;">
                    <i class="bi bi-image" style="font-size: 3rem; color: #adb5bd;"></i>
                </div>
                @endif
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title" style="font-weight: 600;">{{ Str::limit($campaign->title, 50) }}</h5>
                    <p class="card-text text-muted flex-grow-1">
                        {{ Str::limit($campaign->description, 100) }}
                    </p>
                    
                    <div class="mb-3">
                        <span class="badge" style="background-color: #ffc107; color: #000; font-size: 1.1rem;">
                            <i class="bi bi-coin"></i> {{ number_format($campaign->pieces_reward) }} pièces
                        </span>
                        @if(\Carbon\Carbon::parse($campaign->start_date)->isFuture())
                        <span class="badge bg-info text-dark ms-2">
                            <i class="bi bi-clock-history"></i> Démarre le {{ \Carbon\Carbon::parse($campaign->start_date)->format('d/m/Y') }}
                        </span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="bi bi-calendar"></i> 
                            Se termine le {{ \Carbon\Carbon::parse($campaign->end_date)->format('d/m/Y') }}
                        </small>
                        @php
                            $daysLeft = \Carbon\Carbon::parse($campaign->end_date)->diffInDays(now());
                        @endphp
                        @if($daysLeft <= 3)
                        <br><small class="text-danger"><i class="bi bi-exclamation-circle"></i> Plus que {{ $daysLeft }} jour(s)</small>
                        @endif
                    </div>

                    <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-primary w-100">
                        <i class="bi bi-eye"></i> Voir Détails
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card" style="border: 2px solid #0d6efd;">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #dee2e6;"></i>
                    <h4 class="mt-3 text-muted">Aucune campagne disponible</h4>
                    <p class="text-muted">Revenez bientôt pour de nouvelles opportunités!</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($campaigns->hasPages())
    <div class="mt-4">
        {{ $campaigns->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
