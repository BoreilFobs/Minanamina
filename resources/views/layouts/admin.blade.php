<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f172a">
    
    <title>@yield('title', 'Admin - Minanamina')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Modern CSS -->
    <link href="{{ asset('css/modern.css') }}" rel="stylesheet">
    
    <style>
        body {
            padding-bottom: 0;
        }
        
        /* Admin specific overrides */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        
        .admin-sidebar {
            width: 260px;
            background: var(--gray-900);
            color: var(--white);
            flex-shrink: 0;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
            z-index: 1000;
            transform: translateX(-100%);
            transition: transform var(--transition-base);
        }
        
        .admin-sidebar.open {
            transform: translateX(0);
        }
        
        @media (min-width: 992px) {
            .admin-sidebar {
                position: sticky;
                transform: none;
            }
        }
        
        .admin-sidebar__header {
            padding: var(--space-lg);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .admin-sidebar__logo {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--white);
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            text-decoration: none;
        }
        
        .admin-sidebar__close {
            display: flex;
            width: 32px;
            height: 32px;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-md);
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: var(--white);
            cursor: pointer;
        }
        
        @media (min-width: 992px) {
            .admin-sidebar__close {
                display: none;
            }
        }
        
        .admin-sidebar__nav {
            padding: var(--space-md) 0;
        }
        
        .admin-sidebar__section {
            padding: var(--space-md) var(--space-lg) var(--space-sm);
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        
        .admin-sidebar__link {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            padding: var(--space-sm) var(--space-lg);
            color: var(--gray-400);
            font-size: 0.875rem;
            font-weight: 500;
            transition: all var(--transition-fast);
            text-decoration: none;
        }
        
        .admin-sidebar__link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: var(--white);
        }
        
        .admin-sidebar__link.active {
            background: rgba(99, 102, 241, 0.2);
            color: var(--primary-light);
            border-left: 3px solid var(--primary-light);
        }
        
        .admin-sidebar__link i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }
        
        .admin-sidebar__badge {
            margin-left: auto;
            background: var(--danger);
            color: var(--white);
            font-size: 0.625rem;
            padding: 2px 6px;
            border-radius: var(--radius-full);
            font-weight: 600;
        }
        
        .admin-content {
            flex: 1;
            min-width: 0;
            background: var(--gray-100);
        }
        
        @media (min-width: 992px) {
            .admin-content {
                margin-left: 0;
            }
        }
        
        .admin-header {
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            padding: var(--space-md) var(--space-lg);
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: var(--space-md);
        }
        
        .admin-header__left {
            display: flex;
            align-items: center;
            gap: var(--space-md);
        }
        
        .admin-header__toggle {
            display: flex;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-lg);
            background: transparent;
            border: none;
            cursor: pointer;
            color: var(--gray-600);
        }
        
        .admin-header__toggle:hover {
            background: var(--gray-100);
        }
        
        @media (min-width: 992px) {
            .admin-header__toggle {
                display: none;
            }
        }
        
        .admin-header__title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }
        
        .admin-header__right {
            display: flex;
            align-items: center;
            gap: var(--space-md);
        }
        
        .admin-page {
            padding: var(--space-lg);
        }
        
        @media (min-width: 992px) {
            .admin-page {
                padding: var(--space-xl);
            }
        }
        
        .admin-page__header {
            margin-bottom: var(--space-xl);
        }
        
        .admin-page__title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0 0 var(--space-xs);
        }
        
        .admin-page__subtitle {
            color: var(--gray-500);
            margin: 0;
        }
        
        /* Overlay for mobile sidebar */
        .admin-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-base);
        }
        
        .admin-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        /* Quick stats row */
        .admin-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--space-md);
            margin-bottom: var(--space-xl);
        }
        
        @media (min-width: 768px) {
            .admin-stats {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        /* Admin Card */
        .admin-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }
        
        .admin-card__header {
            padding: var(--space-md) var(--space-lg);
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .admin-card__title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }
        
        .admin-card__body {
            padding: var(--space-lg);
        }
        
        .admin-card__footer {
            padding: var(--space-md) var(--space-lg);
            border-top: 1px solid var(--gray-100);
            background: var(--gray-50);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Overlay -->
    <div class="admin-overlay" id="adminOverlay" onclick="closeSidebar()"></div>
    
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar__header">
                <a href="{{ route('admin.campaigns.index') }}" class="admin-sidebar__logo">
                    <i class="bi bi-coin"></i>
                    Admin
                </a>
                <button class="admin-sidebar__close" onclick="closeSidebar()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <nav class="admin-sidebar__nav">
                <div class="admin-sidebar__section">Principal</div>
                <a href="{{ route('dashboard') }}" class="admin-sidebar__link">
                    <i class="bi bi-house"></i>
                    Retour au site
                </a>
                
                @if(Auth::user()->isCampaignCreator() || Auth::user()->isSuperAdmin())
                <div class="admin-sidebar__section">Campagnes</div>
                <a href="{{ route('admin.campaigns.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.campaigns.index') ? 'active' : '' }}">
                    <i class="bi bi-megaphone"></i>
                    Mes Campagnes
                </a>
                <a href="{{ route('admin.campaigns.create') }}" class="admin-sidebar__link {{ request()->routeIs('admin.campaigns.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>
                    Nouvelle Campagne
                </a>
                @endif
                
                @if(Auth::user()->isSuperAdmin())
                <div class="admin-sidebar__section">Administration</div>
                <a href="{{ route('admin.users.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    Utilisateurs
                </a>
                <a href="{{ route('admin.campaigns.approvals.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.campaigns.approvals.*') ? 'active' : '' }}">
                    <i class="bi bi-check-circle"></i>
                    Approbations
                    @php
                        $pendingCount = \App\Models\Campaign::where('status', 'pending_review')->count();
                    @endphp
                    @if($pendingCount > 0)
                    <span class="admin-sidebar__badge">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.validations.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.validations.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check"></i>
                    Validations
                    @php
                        $pendingValidations = \App\Models\CampaignParticipation::where('status', 'pending')->count();
                    @endphp
                    @if($pendingValidations > 0)
                    <span class="admin-sidebar__badge">{{ $pendingValidations }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.conversions.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.conversions.*') ? 'active' : '' }}">
                    <i class="bi bi-cash-coin"></i>
                    Conversions
                    @php
                        $pendingConversions = \App\Models\ConversionRequest::where('status', 'pending')->count();
                    @endphp
                    @if($pendingConversions > 0)
                    <span class="admin-sidebar__badge">{{ $pendingConversions }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.referrals.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.referrals.*') ? 'active' : '' }}">
                    <i class="bi bi-gift"></i>
                    Parrainages
                </a>
                
                <div class="admin-sidebar__section">Configuration</div>
                <a href="{{ route('admin.settings.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    Paramètres
                </a>
                @endif
            </nav>
            
            <!-- User Info at bottom -->
            <div style="margin-top: auto; padding: var(--space-lg); border-top: 1px solid rgba(255,255,255,0.1);">
                <div class="flex items-center gap-md">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="avatar">
                    @else
                        <div class="avatar avatar-placeholder">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-400">{{ Auth::user()->isSuperAdmin() ? 'Super Admin' : 'Créateur' }}</div>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="admin-content">
            <!-- Header -->
            <header class="admin-header">
                <div class="admin-header__left">
                    <button class="admin-header__toggle" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="admin-header__title">@yield('header', 'Administration')</h1>
                </div>
                <div class="admin-header__right">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn--ghost btn--sm">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="hidden lg:inline">Déconnexion</span>
                        </button>
                    </form>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="admin-page">
                @if(session('success'))
                <div class="alert alert--success mb-lg">
                    <i class="bi bi-check-circle alert__icon"></i>
                    <div class="alert__content">{{ session('success') }}</div>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert--danger mb-lg">
                    <i class="bi bi-x-circle alert__icon"></i>
                    <div class="alert__content">{{ session('error') }}</div>
                </div>
                @endif
                
                @if($errors->any())
                <div class="alert alert--danger mb-lg">
                    <i class="bi bi-exclamation-triangle alert__icon"></i>
                    <div class="alert__content">
                        <ul class="m-0" style="padding-left: var(--space-md);">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <script>
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('open');
            document.getElementById('adminOverlay').classList.toggle('active');
            document.body.style.overflow = document.getElementById('adminSidebar').classList.contains('open') ? 'hidden' : '';
        }
        
        function closeSidebar() {
            document.getElementById('adminSidebar').classList.remove('open');
            document.getElementById('adminOverlay').classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
