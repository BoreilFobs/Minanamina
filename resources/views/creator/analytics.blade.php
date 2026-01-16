@extends('layouts.creator')

@section('title', 'Statistiques - Créateur')
@section('header', 'Statistiques')

@push('styles')
<style>
    .stats-overview {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    @media (min-width: 768px) {
        .stats-overview {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (min-width: 1200px) {
        .stats-overview {
            grid-template-columns: repeat(6, 1fr);
        }
    }
    
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        border: 1px solid #e5e7eb;
        text-align: center;
    }
    
    .stat-card.highlight {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        border: none;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin: 0 auto 0.75rem;
    }
    
    .stat-card:not(.highlight) .stat-icon.primary { background: rgba(79, 70, 229, 0.1); color: #4f46e5; }
    .stat-card:not(.highlight) .stat-icon.success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stat-card:not(.highlight) .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .stat-card:not(.highlight) .stat-icon.info { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    
    .stat-card.highlight .stat-icon {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 0.8rem;
        margin-top: 0.25rem;
        opacity: 0.8;
    }
    
    .content-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }
    
    .content-card__header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        font-weight: 600;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .content-card__body {
        padding: 1.25rem;
    }
    
    .chart-container {
        height: 300px;
        display: flex;
        align-items: flex-end;
        gap: 0.5rem;
        padding: 1rem 0;
    }
    
    .chart-bar-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }
    
    .chart-bar {
        width: 100%;
        max-width: 40px;
        border-radius: 6px 6px 0 0;
        transition: height 0.3s ease;
    }
    
    .chart-bar.primary {
        background: linear-gradient(180deg, #4f46e5 0%, #7c3aed 100%);
    }
    
    .chart-bar.success {
        background: linear-gradient(180deg, #10b981 0%, #059669 100%);
    }
    
    .chart-label {
        font-size: 0.75rem;
        color: #6b7280;
    }
    
    .chart-value {
        font-size: 0.7rem;
        font-weight: 600;
        color: #374151;
    }
    
    .campaign-table {
        width: 100%;
    }
    
    .campaign-table th {
        background: #f9fafb;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #6b7280;
        padding: 1rem;
        text-align: left;
    }
    
    .campaign-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    
    .progress-bar-container {
        background: #e5e7eb;
        border-radius: 10px;
        height: 8px;
        overflow: hidden;
    }
    
    .progress-bar-fill {
        height: 100%;
        border-radius: 10px;
        background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%);
    }
    
    .campaign-name {
        font-weight: 600;
        color: #111827;
    }
    
    .badge-status {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .badge-published { background: #d1fae5; color: #065f46; }
    .badge-draft { background: #f3f4f6; color: #6b7280; }
    .badge-pending { background: #fef3c7; color: #92400e; }
</style>
@endpush

@section('content')
<div class="mb-4">
    <h1 class="h4 fw-bold mb-1">Statistiques</h1>
    <p class="text-muted mb-0">Analysez les performances de vos campagnes</p>
</div>

<!-- Overview Stats -->
<div class="stats-overview">
    <div class="stat-card highlight">
        <div class="stat-icon">
            <i class="bi bi-megaphone"></i>
        </div>
        <div class="stat-value">{{ $stats['total_campaigns'] }}</div>
        <div class="stat-label">Campagnes</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="bi bi-broadcast"></i>
        </div>
        <div class="stat-value">{{ $stats['active_campaigns'] }}</div>
        <div class="stat-label">Actives</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="bi bi-people"></i>
        </div>
        <div class="stat-value">{{ number_format($stats['total_participants']) }}</div>
        <div class="stat-label">Participants</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="stat-value">{{ number_format($stats['completed_participations']) }}</div>
        <div class="stat-label">Complétés</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="bi bi-coin"></i>
        </div>
        <div class="stat-value">{{ number_format($stats['total_pieces_distributed']) }}</div>
        <div class="stat-label">Pièces</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon info">
            <i class="bi bi-percent"></i>
        </div>
        <div class="stat-value">{{ $stats['avg_conversion_rate'] }}%</div>
        <div class="stat-label">Conversion</div>
    </div>
</div>

<!-- Weekly Chart -->
<div class="content-card">
    <div class="content-card__header">
        <i class="bi bi-graph-up"></i>
        Activité des 7 derniers jours
    </div>
    <div class="content-card__body">
        @php
            $maxValue = max(array_column($weeklyData, 'participations'));
            $maxValue = $maxValue > 0 ? $maxValue : 1;
        @endphp
        <div class="chart-container">
            @foreach($weeklyData as $day)
            <div class="chart-bar-wrapper">
                <div class="chart-value">{{ $day['participations'] }}</div>
                <div class="chart-bar primary" style="height: {{ ($day['participations'] / $maxValue) * 200 }}px; min-height: 4px;"></div>
                <div class="chart-label">{{ $day['date'] }}</div>
            </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center gap-4 mt-3">
            <div class="d-flex align-items-center gap-2">
                <div style="width: 12px; height: 12px; border-radius: 3px; background: linear-gradient(180deg, #4f46e5 0%, #7c3aed 100%);"></div>
                <span class="text-muted small">Participations</span>
            </div>
        </div>
    </div>
</div>

<!-- Campaign Performance -->
<div class="content-card">
    <div class="content-card__header">
        <i class="bi bi-trophy"></i>
        Performance par Campagne
    </div>
    <div class="content-card__body p-0">
        @if($campaigns->count() > 0)
        <table class="campaign-table">
            <thead>
                <tr>
                    <th>Campagne</th>
                    <th>Statut</th>
                    <th>Participants</th>
                    <th>Complétés</th>
                    <th>Taux</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campaigns as $campaign)
                @php
                    $rate = $campaign->participations_count > 0 
                        ? round(($campaign->completed_count / $campaign->participations_count) * 100, 1) 
                        : 0;
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('creator.campaigns.show', $campaign) }}" class="campaign-name text-decoration-none">
                            {{ Str::limit($campaign->title, 30) }}
                        </a>
                    </td>
                    <td>
                        <span class="badge-status badge-{{ $campaign->status == 'published' ? 'published' : ($campaign->status == 'draft' ? 'draft' : 'pending') }}">
                            @switch($campaign->status)
                                @case('published') Publié @break
                                @case('draft') Brouillon @break
                                @default En attente
                            @endswitch
                        </span>
                    </td>
                    <td>{{ $campaign->participations_count }}</td>
                    <td>{{ $campaign->completed_count }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress-bar-container" style="width: 80px;">
                                <div class="progress-bar-fill" style="width: {{ $rate }}%;"></div>
                            </div>
                            <span class="small fw-semibold">{{ $rate }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="text-center py-5">
            <i class="bi bi-bar-chart text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-2 mb-0">Aucune campagne à analyser</p>
        </div>
        @endif
    </div>
</div>
@endsection
