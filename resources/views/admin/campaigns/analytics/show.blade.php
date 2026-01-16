@extends('layouts.admin')

@section('title', 'Analytiques - ' . $campaign->title)
@section('page-title', 'Analytiques')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    @media (min-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    
    .stat-card-lg {
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        color: white;
    }
    
    .stat-card-lg.primary {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    }
    
    .stat-card-lg.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .stat-card-lg.warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    
    .stat-card-lg.purple {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }
    
    .stat-card-lg h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    
    .stat-card-lg small {
        opacity: 0.9;
    }
    
    .stats-row-secondary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-card-sm {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        border: 2px solid #e5e7eb;
    }
    
    .stat-card-sm.pending { border-color: #f59e0b; }
    .stat-card-sm.rejected { border-color: #ef4444; }
    .stat-card-sm.time { border-color: #3b82f6; }
    
    .stat-card-sm h4 {
        font-weight: 700;
        margin-bottom: 0;
    }
    
    .chart-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .chart-card__header {
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        color: white;
    }
    
    .chart-card__header.primary {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    }
    
    .chart-card__header.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .chart-card__header.purple {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }
    
    .chart-card__header.warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    
    .chart-card__body {
        padding: 1.5rem;
    }
    
    .list-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .list-card__header {
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        color: white;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    
    .list-item {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .list-item:last-child {
        border-bottom: none;
    }
    
    .reward-badge {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: #78350f;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
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
</style>
@endpush

@section('content')
<div class="admin-page">
    <!-- Header -->
    <div class="admin-page__header d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="admin-page__title">Analytiques: {{ $campaign->title }}</h1>
            <p class="admin-page__subtitle">Performance détaillée de la campagne</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.campaigns.analytics.export', ['campaign' => $campaign, 'format' => 'csv']) }}" 
               class="btn--success">
                <i class="bi bi-file-earmark-spreadsheet"></i> Exporter
            </a>
            <a href="{{ route('admin.campaigns.show', $campaign) }}" class="btn--ghost">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="stats-grid">
        <div class="stat-card-lg primary">
            <h2>{{ number_format($stats['total_participants']) }}</h2>
            <small>Participants Total</small>
        </div>
        <div class="stat-card-lg success">
            <h2>{{ number_format($stats['completed_participations']) }}</h2>
            <small>Complétées</small>
        </div>
        <div class="stat-card-lg warning">
            <h2>{{ $stats['conversion_rate'] }}%</h2>
            <small>Taux de Conversion</small>
        </div>
        <div class="stat-card-lg purple">
            <h2><i class="bi bi-coin"></i> {{ number_format($stats['total_pieces_distributed']) }}</h2>
            <small>Pièces Distribuées</small>
        </div>
    </div>

    <!-- Additional Metrics -->
    <div class="stats-row-secondary">
        <div class="stat-card-sm pending">
            <h4>{{ number_format($stats['pending_participations']) }}</h4>
            <small class="text-muted">En Attente</small>
        </div>
        <div class="stat-card-sm rejected">
            <h4>{{ number_format($stats['rejected_participations']) }}</h4>
            <small class="text-muted">Rejetées</small>
        </div>
        <div class="stat-card-sm time">
            <h4>{{ $stats['average_completion_time'] }} min</h4>
            <small class="text-muted">Temps Moyen de Complétion</small>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Charts -->
        <div class="col-lg-8">
            <!-- Daily Participations Chart -->
            <div class="chart-card">
                <div class="chart-card__header primary">
                    <i class="bi bi-graph-up"></i>
                    <span>Participations des 30 Derniers Jours</span>
                </div>
                <div class="chart-card__body">
                    <canvas id="dailyChart" style="max-height: 300px;"></canvas>
                </div>
            </div>

            <!-- Hourly Distribution -->
            <div class="chart-card">
                <div class="chart-card__header success">
                    <i class="bi bi-clock"></i>
                    <span>Distribution par Heure de la Journée</span>
                </div>
                <div class="chart-card__body">
                    <canvas id="hourlyChart" style="max-height: 250px;"></canvas>
                </div>
            </div>

            <!-- Geographic Distribution -->
            @if($geographicData->count() > 0)
            <div class="chart-card">
                <div class="chart-card__header purple">
                    <i class="bi bi-geo-alt"></i>
                    <span>Distribution Géographique</span>
                </div>
                <div class="chart-card__body">
                    <canvas id="geoChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Lists -->
        <div class="col-lg-4">
            <!-- Top Performers -->
            <div class="list-card">
                <div class="list-card__header">
                    <i class="bi bi-trophy"></i>
                    <span>Dernières Validations</span>
                </div>
                @forelse($topPerformers as $participation)
                <div class="list-item">
                    <div>
                        <strong>{{ $participation->user->name }}</strong><br>
                        <small class="text-muted">
                            {{ $participation->completed_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                    <span class="reward-badge">
                        <i class="bi bi-coin"></i> {{ number_format($participation->pieces_earned) }}
                    </span>
                </div>
                @empty
                <div class="list-item justify-content-center text-muted">
                    Aucune validation encore
                </div>
                @endforelse
            </div>

            <!-- Status Breakdown -->
            <div class="chart-card">
                <div class="chart-card__header primary">
                    <i class="bi bi-pie-chart"></i>
                    <span>Répartition des Statuts</span>
                </div>
                <div class="chart-card__body">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Daily Participations Chart
const dailyCtx = document.getElementById('dailyChart').getContext('2d');
const dailyChart = new Chart(dailyCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($dailyData->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!},
        datasets: [
            {
                label: 'Total',
                data: {!! json_encode($dailyData->pluck('count')) !!},
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Complétées',
                data: {!! json_encode($dailyData->pluck('completed')) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Hourly Distribution Chart
const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
const hourlyChart = new Chart(hourlyCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($hourlyData->pluck('hour')->map(fn($h) => $h . 'h')) !!},
        datasets: [{
            label: 'Participations',
            data: {!! json_encode($hourlyData->pluck('count')) !!},
            backgroundColor: 'rgba(16, 185, 129, 0.7)',
            borderColor: '#10b981',
            borderWidth: 2,
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

@if($geographicData->count() > 0)
// Geographic Distribution Chart
const geoCtx = document.getElementById('geoChart').getContext('2d');
const geoChart = new Chart(geoCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($geographicData->pluck('country')) !!},
        datasets: [{
            data: {!! json_encode($geographicData->pluck('count')) !!},
            backgroundColor: [
                '#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
                '#3b82f6', '#f97316', '#14b8a6', '#ec4899', '#7c3aed'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right'
            }
        }
    }
});
@endif

// Status Breakdown Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Complétées', 'En Attente', 'Rejetées'],
        datasets: [{
            data: [
                {{ $stats['completed_participations'] }},
                {{ $stats['pending_participations'] }},
                {{ $stats['rejected_participations'] }}
            ],
            backgroundColor: ['#10b981', '#f59e0b', '#ef4444']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endsection
