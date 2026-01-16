@extends('layouts.admin')

@section('title', 'Détails Campagne')
@section('page-title', 'Détails Campagne')

@push('styles')
<style>
    .detail-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .detail-card__header {
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        color: white;
    }
    
    .detail-card__header.primary {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    }
    
    .detail-card__header.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .detail-card__header.purple {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }
    
    .detail-card__body {
        padding: 1.5rem;
    }
    
    .campaign-image {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }
    
    .status-badge-lg {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .status-badge-lg.draft { background: rgba(107, 114, 128, 0.1); color: #6b7280; }
    .status-badge-lg.pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .status-badge-lg.published { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .status-badge-lg.paused { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .status-badge-lg.completed { background: rgba(107, 114, 128, 0.15); color: #374151; }
    
    .reward-badge-lg {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: #78350f;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .stat-item {
        background: #f9fafb;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .stat-item:last-child {
        margin-bottom: 0;
    }
    
    .stat-item__label {
        font-size: 0.8rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }
    
    .stat-item__value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
    }
    
    .progress-modern {
        height: 8px;
        border-radius: 4px;
        background: #e5e7eb;
        overflow: hidden;
    }
    
    .progress-modern__bar {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s;
    }
    
    .progress-modern__bar.primary { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); }
    .progress-modern__bar.success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .progress-modern__bar.info { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
    .progress-modern__bar.warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    
    .table-modern {
        margin: 0;
    }
    
    .table-modern th {
        background: #f8fafc;
        border-bottom: 2px solid #e5e7eb;
        font-weight: 600;
        color: #374151;
        font-size: 0.8rem;
        text-transform: uppercase;
        padding: 1rem;
    }
    
    .table-modern td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .table-modern tbody tr:hover {
        background: #f9fafb;
    }
    
    .participation-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .participation-badge.pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .participation-badge.completed { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .participation-badge.rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    
    .btn--primary {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    
    .btn--primary:hover { color: white; }
    
    .btn--success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    
    .btn--success:hover { color: white; }
    
    .btn--danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn--danger:hover { color: white; }
    
    .btn--warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    
    .btn--warning:hover { color: white; }
    
    .btn--info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    
    .btn--info:hover { color: white; }
    
    .btn--ghost {
        background: transparent;
        color: #6b7280;
        border: 2px solid #e5e7eb;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    
    .btn--ghost:hover {
        background: #f9fafb;
        color: #374151;
    }
    
    .alert-modern {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .alert-modern.success {
        background: rgba(16, 185, 129, 0.1);
        color: #065f46;
    }
    
    .alert-modern.error {
        background: rgba(239, 68, 68, 0.1);
        color: #991b1b;
    }
    
    .alert-modern.info {
        background: rgba(59, 130, 246, 0.1);
        color: #1e40af;
    }
    
    .timeline-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
    }
    
    .timeline-card__header {
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        color: white;
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }
    
    .timeline-card__body {
        padding: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="admin-page">
    <div class="admin-page__header d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="admin-page__title">{{ $campaign->title }}</h1>
            <p class="admin-page__subtitle">Détails et statistiques de la campagne</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.campaigns.index') }}" class="btn--ghost">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
            <a href="{{ route('admin.campaigns.analytics.show', $campaign) }}" class="btn--info">
                <i class="bi bi-graph-up"></i> Analytiques
            </a>
            <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="btn--primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <form action="{{ route('admin.campaigns.duplicate', $campaign) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn--warning">
                    <i class="bi bi-files"></i> Dupliquer
                </button>
            </form>
            @if($campaign->status == 'draft')
            <form action="{{ route('admin.campaigns.submit-approval', $campaign) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn--success">
                    <i class="bi bi-check-circle"></i> Soumettre
                </button>
            </form>
            @elseif($campaign->status == 'published')
            <form action="{{ route('admin.campaigns.pause', $campaign) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn--danger">
                    <i class="bi bi-pause-circle"></i> Pause
                </button>
            </form>
            @elseif($campaign->status == 'paused')
            <form action="{{ route('admin.campaigns.resume', $campaign) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn--success">
                    <i class="bi bi-play-circle"></i> Relancer
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert-modern success mb-4">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-modern error mb-4">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Status Badge -->
    <div class="mb-4">
        @if($campaign->status == 'draft')
            <span class="status-badge-lg draft"><i class="bi bi-file-earmark"></i> Brouillon</span>
        @elseif($campaign->status == 'pending_approval')
            <span class="status-badge-lg pending"><i class="bi bi-clock"></i> En attente d'approbation</span>
        @elseif($campaign->status == 'published')
            <span class="status-badge-lg published"><i class="bi bi-check-circle"></i> Publié</span>
        @elseif($campaign->status == 'paused')
            <span class="status-badge-lg paused"><i class="bi bi-pause-circle"></i> Pausé</span>
        @else
            <span class="status-badge-lg completed"><i class="bi bi-flag"></i> Terminé</span>
        @endif
    </div>

    <div class="row">
        <!-- Left Column: Campaign Details -->
        <div class="col-lg-8">
            <!-- Campaign Image and Info -->
            <div class="detail-card">
                <div class="detail-card__header primary">
                    <i class="bi bi-image"></i>
                    <span>Détails de la Campagne</span>
                </div>
                <div class="detail-card__body">
                    @if($campaign->image)
                    <img src="{{ asset('storage/' . $campaign->image) }}" 
                         alt="{{ $campaign->title }}" 
                         class="campaign-image">
                    @endif

                    <h5 class="fw-bold mb-3">Description</h5>
                    <p style="white-space: pre-wrap; color: #4b5563;">{{ $campaign->description }}</p>

                    <hr class="my-4">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-1">Lien CPA:</strong>
                            <a href="{{ $campaign->cpa_link }}" target="_blank" class="text-primary">
                                {{ Str::limit($campaign->cpa_link, 50) }} 
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-1">Récompense:</strong>
                            <span class="reward-badge-lg">
                                <i class="bi bi-coin"></i> {{ number_format($campaign->pieces_reward) }} pièces
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-1">Date de Début:</strong>
                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($campaign->start_date)->format('d/m/Y') }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong class="text-muted d-block mb-1">Date de Fin:</strong>
                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($campaign->end_date)->format('d/m/Y') }}</span>
                        </div>
                        <div class="col-md-12">
                            <strong class="text-muted d-block mb-1">Créée par:</strong>
                            <span class="fw-semibold">{{ $campaign->creator->name ?? 'N/A' }}</span>
                            <span class="text-muted">le {{ $campaign->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                    </div>

                    @if($campaign->validation_rules)
                    <hr class="my-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-check-square text-success"></i> Conditions de Validation</h5>
                    <p style="white-space: pre-wrap; color: #4b5563;">{{ $campaign->validation_rules }}</p>
                    @endif

                    @if($campaign->geographic_restrictions)
                    <hr class="my-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt text-primary"></i> Restrictions Géographiques</h5>
                    <p>
                        @php
                            $restrictions = is_array($campaign->geographic_restrictions) 
                                ? $campaign->geographic_restrictions
                                : json_decode($campaign->geographic_restrictions, true) ?? [];
                        @endphp
                        @if(empty($restrictions))
                            <span class="text-muted">Tous les pays</span>
                        @else
                            {{ implode(', ', $restrictions) }}
                        @endif
                    </p>
                    @endif
                </div>
            </div>

            <!-- Participations List -->
            <div class="detail-card">
                <div class="detail-card__header success">
                    <i class="bi bi-people"></i>
                    <span>Participations Récentes</span>
                </div>
                <div class="table-responsive">
                    <table class="table-modern table">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Statut</th>
                                <th>Pièces</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($campaign->participations()->latest()->take(10)->get() as $participation)
                            <tr>
                                <td class="fw-semibold">{{ $participation->user->name }}</td>
                                <td>
                                    @if($participation->status == 'pending')
                                        <span class="participation-badge pending">En attente</span>
                                    @elseif($participation->status == 'completed')
                                        <span class="participation-badge completed">Complété</span>
                                    @elseif($participation->status == 'rejected')
                                        <span class="participation-badge rejected">Rejeté</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="reward-badge-lg" style="font-size: 0.8rem; padding: 0.25rem 0.5rem;">
                                        {{ number_format($participation->pieces_earned) }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $participation->created_at->format('d/m/Y H:i') }}</td>
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

        <!-- Right Column: Statistics -->
        <div class="col-lg-4">
            <!-- Statistics Cards -->
            <div class="detail-card">
                <div class="detail-card__header primary">
                    <i class="bi bi-graph-up"></i>
                    <span>Statistiques</span>
                </div>
                <div class="detail-card__body">
                    <div class="stat-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="stat-item__label">Participants Total</span>
                            <span class="fw-bold text-primary">{{ number_format($stats['total_participants']) }}</span>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-modern__bar primary" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="stat-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="stat-item__label">Complétés</span>
                            <span class="fw-bold text-success">{{ number_format($stats['completed_participations']) }}</span>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-modern__bar success" 
                                 style="width: {{ $stats['total_participants'] > 0 ? ($stats['completed_participations'] / $stats['total_participants']) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <div class="stat-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="stat-item__label">Taux de Conversion</span>
                            <span class="fw-bold" style="color: #3b82f6;">{{ $stats['conversion_rate'] }}%</span>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-modern__bar info" style="width: {{ $stats['conversion_rate'] }}%"></div>
                        </div>
                    </div>

                    <div class="stat-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="stat-item__label">Pièces Distribuées</span>
                            <span class="fw-bold" style="color: #f59e0b;">
                                {{ number_format($stats['total_pieces_distributed']) }}
                            </span>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-modern__bar warning" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campaign Timeline -->
            <div class="timeline-card">
                <div class="timeline-card__header">
                    <i class="bi bi-calendar"></i>
                    <span>Période de la Campagne</span>
                </div>
                <div class="timeline-card__body">
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
                        <p class="mb-0 fw-bold">{{ $start->format('d M Y') }}</p>
                    </div>

                    <div class="progress-modern mb-3" style="height: 20px; border-radius: 10px;">
                        <div class="progress-modern__bar success" 
                             style="width: {{ $progress }}%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600; color: white;">
                            {{ round($progress) }}%
                        </div>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Fin</small>
                        <p class="mb-0 fw-bold">{{ $end->format('d M Y') }}</p>
                    </div>

                    <div class="alert-modern info">
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
