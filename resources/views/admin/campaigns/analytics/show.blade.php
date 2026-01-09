@extends('layouts.admin')

@section('title', 'Analytiques - ' . $campaign->title)
@section('page-title', 'Analytiques')

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
               class="btn btn-success">
                <i class="bi bi-file-earmark-spreadsheet"></i> Exporter
            </a>
            <a href="{{ route('admin.campaigns.show', $campaign) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card" style="border: 2px solid #0d6efd; background-color: #0d6efd;">
                <div class="card-body text-white text-center">
                    <h2 class="mb-0">{{ number_format($stats['total_participants']) }}</h2>
                    <small>Participants Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="border: 2px solid #198754; background-color: #198754;">
                <div class="card-body text-white text-center">
                    <h2 class="mb-0">{{ number_format($stats['completed_participations']) }}</h2>
                    <small>Complétées</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="border: 2px solid #ffc107; background-color: #ffc107;">
                <div class="card-body text-dark text-center">
                    <h2 class="mb-0">{{ $stats['conversion_rate'] }}%</h2>
                    <small>Taux de Conversion</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="border: 2px solid #6f42c1; background-color: #6f42c1;">
                <div class="card-body text-white text-center">
                    <h2 class="mb-0"><i class="bi bi-coin"></i> {{ number_format($stats['total_pieces_distributed']) }}</h2>
                    <small>Pièces Distribuées</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card" style="border: 2px solid #ffc107;">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ number_format($stats['pending_participations']) }}</h4>
                    <small class="text-muted">En Attente</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="border: 2px solid #dc3545;">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ number_format($stats['rejected_participations']) }}</h4>
                    <small class="text-muted">Rejetées</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="border: 2px solid #0dcaf0;">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $stats['average_completion_time'] }} min</h4>
                    <small class="text-muted">Temps Moyen de Complétion</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Charts -->
        <div class="col-lg-8">
            <!-- Daily Participations Chart -->
            <div class="card mb-4" style="border: 2px solid #0d6efd;">
                <div class="card-header text-white" style="background-color: #0d6efd;">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Participations des 30 Derniers Jours</h5>
                </div>
                <div class="card-body">
                    <canvas id="dailyChart" style="max-height: 300px;"></canvas>
                </div>
            </div>

            <!-- Hourly Distribution -->
            <div class="card mb-4" style="border: 2px solid #198754;">
                <div class="card-header text-white" style="background-color: #198754;">
                    <h5 class="mb-0"><i class="bi bi-clock"></i> Distribution par Heure de la Journée</h5>
                </div>
                <div class="card-body">
                    <canvas id="hourlyChart" style="max-height: 250px;"></canvas>
                </div>
            </div>

            <!-- Geographic Distribution -->
            @if($geographicData->count() > 0)
            <div class="card mb-4" style="border: 2px solid #6f42c1;">
                <div class="card-header text-white" style="background-color: #6f42c1;">
                    <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Distribution Géographique</h5>
                </div>
                <div class="card-body">
                    <canvas id="geoChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Lists -->
        <div class="col-lg-4">
            <!-- Top Performers -->
            <div class="card mb-4" style="border: 2px solid #ffc107;">
                <div class="card-header text-dark" style="background-color: #ffc107;">
                    <h6 class="mb-0"><i class="bi bi-trophy"></i> Dernières Validations</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($topPerformers as $participation)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $participation->user->name }}</strong><br>
                                    <small class="text-muted">
                                        {{ $participation->completed_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                <span class="badge" style="background-color: #ffc107; color: #000;">
                                    <i class="bi bi-coin"></i> {{ number_format($participation->pieces_earned) }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="list-group-item text-center text-muted">
                            Aucune validation encore
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Status Breakdown -->
            <div class="card" style="border: 2px solid #0d6efd;">
                <div class="card-header text-white" style="background-color: #0d6efd;">
                    <h6 class="mb-0"><i class="bi bi-pie-chart"></i> Répartition des Statuts</h6>
                </div>
                <div class="card-body">
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
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Complétées',
                data: {!! json_encode($dailyData->pluck('completed')) !!},
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
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
            backgroundColor: '#198754',
            borderColor: '#198754',
            borderWidth: 2
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
                '#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1',
                '#0dcaf0', '#fd7e14', '#20c997', '#d63384', '#6610f2'
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
            backgroundColor: ['#198754', '#ffc107', '#dc3545']
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
