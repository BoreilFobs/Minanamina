@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-gift-fill text-primary"></i> Mon Parrainage</h1>
        <p class="text-muted mb-0">Partagez votre code et gagnez des pièces</p>
    </div>

    <!-- Referral Code Card -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white text-center p-4">
                    <i class="bi bi-qr-code" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Votre Code de Parrainage</h5>
                    <div class="bg-white text-dark p-3 rounded my-3" style="font-size: 2rem; font-weight: 700; letter-spacing: 3px;">
                        {{ $user->referral_code }}
                    </div>
                    <button class="btn btn-light" onclick="copyReferralCode()">
                        <i class="bi bi-clipboard"></i> Copier le Code
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white text-center p-4">
                    <i class="bi bi-link-45deg" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Lien de Parrainage</h5>
                    <div class="bg-white text-dark p-2 rounded my-3" style="font-size: 0.85rem; word-break: break-all;">
                        {{ $referralLink }}
                    </div>
                    <button class="btn btn-light" onclick="copyReferralLink()">
                        <i class="bi bi-link"></i> Copier le Lien
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center border-primary" style="border-width: 2px;">
                <div class="card-body">
                    <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                    <h3 class="mb-0 mt-2" style="font-weight: 700;">{{ $stats['total_referrals'] }}</h3>
                    <small class="text-muted">Filleuls</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-success" style="border-width: 2px;">
                <div class="card-body">
                    <i class="bi bi-coin text-warning" style="font-size: 2rem;"></i>
                    <h3 class="mb-0 mt-2" style="font-weight: 700;">{{ number_format($stats['referral_earnings']) }}</h3>
                    <small class="text-muted">Pièces Gagnées</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-warning" style="border-width: 2px;">
                <div class="card-body">
                    <i class="bi bi-hourglass-split text-warning" style="font-size: 2rem;"></i>
                    <h3 class="mb-0 mt-2" style="font-weight: 700;">{{ $stats['pending_referrals'] }}</h3>
                    <small class="text-muted">En Attente</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-info" style="border-width: 2px;">
                <div class="card-body">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                    <h3 class="mb-0 mt-2" style="font-weight: 700;">{{ $stats['credited_referrals'] }}</h3>
                    <small class="text-muted">Crédités</small>
                </div>
            </div>
        </div>
    </div>

    <!-- How it Works -->
    <div class="card mb-4" style="border: 2px solid #0d6efd;">
        <div class="card-header text-white" style="background-color: #0d6efd;">
            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Comment ça marche?</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: 700;">
                        1
                    </div>
                    <h6>Partagez votre code</h6>
                    <p class="text-muted small">Envoyez votre code ou lien à vos amis</p>
                </div>
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: 700;">
                        2
                    </div>
                    <h6>Ils s'inscrivent</h6>
                    <p class="text-muted small">Vos amis créent un compte avec votre code</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: 700;">
                        3
                    </div>
                    <h6>Vous gagnez {{ number_format($bonusAmount) }} pièces</h6>
                    <p class="text-muted small">Bonus crédité automatiquement</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Referred Users List -->
    <div class="card" style="border: 2px solid #28a745;">
        <div class="card-header text-white" style="background-color: #28a745;">
            <h5 class="mb-0"><i class="bi bi-people"></i> Mes Filleuls ({{ $stats['referred_users']->count() }})</h5>
        </div>
        <div class="card-body p-0">
            @if($stats['referred_users']->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Utilisateur</th>
                            <th>Date d'inscription</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats['referred_users'] as $referred)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($referred->avatar)
                                    <img src="{{ asset('storage/' . $referred->avatar) }}" 
                                         alt="{{ $referred->name }}" 
                                         class="rounded-circle me-2" 
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                         style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($referred->name, 0, 1)) }}
                                    </div>
                                    @endif
                                    <strong>{{ $referred->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $referred->created_at->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Actif
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <p class="mt-2">Vous n'avez pas encore de filleuls</p>
                <p class="small">Partagez votre code pour commencer à gagner!</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function copyReferralCode() {
    const code = "{{ $user->referral_code }}";
    navigator.clipboard.writeText(code).then(function() {
        alert('Code copié: ' + code);
    });
}

function copyReferralLink() {
    const link = "{{ $referralLink }}";
    navigator.clipboard.writeText(link).then(function() {
        alert('Lien copié!');
    });
}
</script>
@endsection
