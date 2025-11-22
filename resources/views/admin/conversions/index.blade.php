@extends('layouts.app')

@section('title', 'Gestion des Conversions - Admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0"><i class="bi bi-cash-stack"></i> Gestion des Conversions</h1>
                <p class="text-muted mb-0">Gérez les demandes de conversion de pièces en cash</p>
            </div>
            <div>
                <a href="{{ route('admin.conversions.export') }}" class="btn btn-success">
                    <i class="bi bi-download"></i> Exporter CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-warning" style="border-width: 2px;">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="bi bi-clock" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="mb-0" style="font-weight: 700;">{{ $stats['pending_count'] }}</h3>
                    <small class="text-muted">En Attente</small>
                    <div class="text-success small mt-1">
                        {{ number_format($stats['pending_amount'], 0) }} CFA
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-info" style="border-width: 2px;">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="bi bi-arrow-repeat" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="mb-0" style="font-weight: 700;">{{ $stats['processing_count'] }}</h3>
                    <small class="text-muted">En Traitement</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-success" style="border-width: 2px;">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="bi bi-check-circle" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="mb-0" style="font-weight: 700;">{{ $stats['completed_today'] }}</h3>
                    <small class="text-muted">Complétées Aujourd'hui</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-primary" style="border-width: 2px;">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="bi bi-cash-stack" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="mb-0" style="font-weight: 700;">{{ number_format($stats['total_paid_out'], 0) }}</h3>
                    <small class="text-muted">CFA Total Payé</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.conversions.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select">
                        <option value="">Tous</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En Attente</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En Traitement</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Complété</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Conversions List -->
    <div class="card" style="border: 2px solid #0d6efd;">
        <div class="card-header text-white" style="background-color: #0d6efd;">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> Demandes de Conversion</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Utilisateur</th>
                            <th>Montant</th>
                            <th>Méthode</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conversions as $conversion)
                        <tr class="{{ $conversion->status === 'pending' ? 'table-warning' : '' }}">
                            <td><strong>#{{ $conversion->id }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($conversion->user->avatar)
                                    <img src="{{ asset('storage/' . $conversion->user->avatar) }}" 
                                         alt="{{ $conversion->user->name }}" 
                                         class="rounded-circle me-2" 
                                         style="width: 35px; height: 35px; object-fit: cover;">
                                    @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                         style="width: 35px; height: 35px; font-size: 0.9rem;">
                                        {{ strtoupper(substr($conversion->user->name, 0, 1)) }}
                                    </div>
                                    @endif
                                    <div>
                                        <strong>{{ $conversion->user->name }}</strong>
                                        <br><small class="text-muted">{{ $conversion->user->phone }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><strong class="text-primary">{{ number_format($conversion->pieces_amount) }}</strong> pièces</div>
                                <div><strong class="text-success">{{ number_format($conversion->cash_amount, 0) }} CFA</strong></div>
                                <small class="text-muted">Taux: {{ $conversion->conversion_rate }}</small>
                            </td>
                            <td>
                                @php
                                    $methodLabels = [
                                        'orange_money' => ['Orange Money', 'warning'],
                                        'mtn_mobile_money' => ['MTN MoMo', 'primary'],
                                        'wave' => ['Wave', 'info'],
                                        'bank_transfer' => ['Virement', 'secondary'],
                                        'paypal' => ['PayPal', 'primary'],
                                    ];
                                    $method = $methodLabels[$conversion->payment_method] ?? [$conversion->payment_method, 'secondary'];
                                @endphp
                                <span class="badge bg-{{ $method[1] }}">{{ $method[0] }}</span>
                                <br>
                                @if($conversion->payment_phone)
                                    <small class="text-muted">{{ $conversion->payment_phone }}</small>
                                @elseif($conversion->payment_email)
                                    <small class="text-muted">{{ $conversion->payment_email }}</small>
                                @elseif($conversion->payment_account)
                                    <small class="text-muted">{{ $conversion->payment_account }}</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusConfig = [
                                        'pending' => ['En Attente', 'warning'],
                                        'approved' => ['Approuvé', 'info'],
                                        'processing' => ['En Traitement', 'primary'],
                                        'completed' => ['Complété', 'success'],
                                        'rejected' => ['Rejeté', 'danger'],
                                    ];
                                    $status = $statusConfig[$conversion->status] ?? [$conversion->status, 'secondary'];
                                @endphp
                                <span class="badge bg-{{ $status[1] }}">{{ $status[0] }}</span>
                            </td>
                            <td>
                                <small>{{ $conversion->created_at->format('d/m/Y H:i') }}</small>
                                <br><small class="text-muted">{{ $conversion->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.conversions.show', $conversion) }}" 
                                       class="btn btn-outline-primary"
                                       title="Voir détails">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    @if($conversion->status === 'pending')
                                    <form action="{{ route('admin.conversions.approve', $conversion) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success" title="Approuver">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    
                                    @if($conversion->status === 'approved')
                                    <form action="{{ route('admin.conversions.processing', $conversion) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-info" title="Marquer en traitement">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-2">Aucune demande de conversion</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($conversions->hasPages())
    <div class="mt-4">
        {{ $conversions->links() }}
    </div>
    @endif
</div>
@endsection
