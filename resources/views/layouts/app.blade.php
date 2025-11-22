<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Minanamina - Plateforme d\'Affiliation CPA')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        /* Mobile Bottom Navigation */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            background: #ffffff;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.15);
            z-index: 1000;
            display: none;
            border-top: 2px solid #0d6efd;
        }
        
        .mobile-bottom-nav {
            display: flex;
            justify-content: space-around;
            align-items: stretch;
        }
        
        .mobile-bottom-nav .nav-link {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 0.25rem;
            color: #6c757d;
            text-decoration: none;
            font-size: 0.7rem;
            font-weight: 500;
            border: none;
            background: #ffffff;
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
        }
        
        .mobile-bottom-nav .nav-link i {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }
        
        .mobile-bottom-nav .nav-link.active {
            color: #0d6efd;
            background: #e7f1ff;
            border-bottom-color: #0d6efd;
        }
        
        .mobile-bottom-nav .nav-link:hover {
            background: #f8f9fa;
        }
        
        @media (max-width: 991.98px) {
            .mobile-bottom-nav {
                display: flex;
            }
            .navbar {
                display: none;
            }
            main {
                padding-bottom: 80px;
            }
        }
        
        @media (min-width: 992px) {
            .mobile-bottom-nav {
                display: none;
            }
        }
        
        /* Solid color styling - Enhanced visibility */
        body {
            background-color: #e9ecef;
        }
        
        .card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background-color: #0d6efd;
            color: #ffffff;
            border-bottom: 2px solid #0a58ca;
        }
        
        .form-control, .form-select {
            background-color: #ffffff;
            border: 2px solid #ced4da;
            color: #212529;
            font-weight: 500;
        }
        
        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .form-control.bg-light {
            background-color: #e9ecef !important;
            color: #6c757d;
        }
        
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
        }
        
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.4);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #ffffff;
            font-weight: 600;
        }
        
        .btn-secondary:hover {
            background-color: #5c636a;
            border-color: #565e64;
        }
        
        .alert {
            border: 2px solid;
            font-weight: 500;
        }
        
        .alert-success {
            background-color: #d1e7dd;
            border-color: #0f5132;
            color: #0f5132;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border-color: #842029;
            color: #842029;
        }
        
        .alert-info {
            background-color: #cfe2ff;
            border-color: #084298;
            color: #084298;
        }
        
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .badge {
            font-weight: 600;
            padding: 0.5em 0.75em;
        }
        
        .stat-card {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .form-label {
            color: #212529;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .text-muted {
            color: #6c757d !important;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-gem"></i> Minanamina
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('campaigns.index') }}">
                                <i class="bi bi-megaphone"></i> Campagnes
                            </a>
                        </li>
                        @if(Auth::user()->canManageCampaigns())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                @if(Auth::user()->isSuperAdmin())
                                <i class="bi bi-shield-fill-check"></i> Super Admin
                                @else
                                <i class="bi bi-shield-check"></i> Créateur
                                @endif
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.campaigns.index') }}"><i class="bi bi-megaphone"></i> Mes Campagnes</a></li>
                                @if(Auth::user()->isSuperAdmin())
                                <li><hr class="dropdown-divider"></li>
                                <li><h6 class="dropdown-header">Super Admin</h6></li>
                                <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="bi bi-people"></i> Utilisateurs</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.users.campaign-creators') }}"><i class="bi bi-person-badge"></i> Créateurs</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.campaigns.approvals.index') }}"><i class="bi bi-check-circle"></i> Approbations</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.validations.index') }}"><i class="bi bi-clipboard-check"></i> Validations</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.conversions.index') }}"><i class="bi bi-cash-coin"></i> Conversions</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.referrals.index') }}"><i class="bi bi-gift"></i> Parrainages</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}"><i class="bi bi-gear-fill"></i> Paramètres</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('referrals.index') }}">
                                <i class="bi bi-gift"></i> Parrainages
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="rounded-circle" style="width: 25px; height: 25px;">
                                @else
                                    <i class="bi bi-person-circle"></i>
                                @endif
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="bi bi-person"></i> Profil</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-wallet"></i> Transactions</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-light text-primary ms-2" href="{{ route('register') }}">Inscription</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation (WhatsApp-style tabs) -->
    @auth
    <nav class="mobile-bottom-nav">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door-fill"></i>
            <span>Accueil</span>
        </a>
        <a href="{{ route('campaigns.index') }}" class="nav-link {{ request()->routeIs('campaigns.*') && !request()->routeIs('campaigns.my-participations') ? 'active' : '' }}">
            <i class="bi bi-megaphone-fill"></i>
            <span>Campagnes</span>
        </a>
        <a href="{{ route('referrals.index') }}" class="nav-link {{ request()->routeIs('referrals.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Parrainages</span>
        </a>
        <a href="{{ route('rewards.index') }}" class="nav-link {{ request()->routeIs('rewards.*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i>
            <span>Récompenses</span>
        </a>
        <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i>
            <span>Profil</span>
        </a>
    </nav>
    @endauth

    <!-- Footer (Only for guests) -->
    @guest
    <footer class="bg-dark text-white mt-5">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5><i class="bi bi-gem"></i> Minanamina</h5>
                    <p class="small">Gagnez des récompenses en complétant des campagnes CPA et en parrainant des amis.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h6>Liens Rapides</h6>
                    <ul class="list-unstyled small">
                        <li><a href="#" class="text-white-50">À Propos</a></li>
                        <li><a href="#" class="text-white-50">FAQ</a></li>
                        <li><a href="#" class="text-white-50">Support</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h6>Légal</h6>
                    <ul class="list-unstyled small">
                        <li><a href="#" class="text-white-50">Politique de Confidentialité</a></li>
                        <li><a href="#" class="text-white-50">Conditions d'Utilisation</a></li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="text-center small">
                &copy; {{ date('Y') }} Minanamina. Tous droits réservés.
            </div>
        </div>
    </footer>
    @endguest

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
