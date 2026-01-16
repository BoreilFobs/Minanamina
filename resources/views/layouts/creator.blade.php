<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f172a">
    
    <title>@yield('title', 'Créateur - Minanamina')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-light: #818cf8;
            --primary-dark: #4f46e5;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--gray-100);
            color: var(--gray-800);
            line-height: 1.6;
        }
        
        /* Layout */
        .creator-layout {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .creator-sidebar {
            width: 260px;
            background: linear-gradient(180deg, #4f46e5 0%, #6366f1 100%);
            color: white;
            flex-shrink: 0;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
            z-index: 1000;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .creator-sidebar.open {
            transform: translateX(0);
        }
        
        @media (min-width: 992px) {
            .creator-sidebar {
                position: sticky;
                transform: none;
            }
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar-logo {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }
        
        .sidebar-logo i {
            font-size: 1.5rem;
        }
        
        .sidebar-close {
            display: flex;
            width: 32px;
            height: 32px;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            cursor: pointer;
        }
        
        @media (min-width: 992px) {
            .sidebar-close {
                display: none;
            }
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-section {
            padding: 1rem 1.5rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
            text-decoration: none;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: white;
        }
        
        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }
        
        .nav-badge {
            margin-left: auto;
            background: white;
            color: var(--primary);
            font-size: 0.65rem;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 700;
        }
        
        /* User card at bottom */
        .sidebar-user {
            margin-top: auto;
            padding: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .user-card {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
        }
        
        .user-info {
            flex: 1;
            min-width: 0;
        }
        
        .user-name {
            font-size: 0.9rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .user-role {
            font-size: 0.75rem;
            opacity: 0.7;
        }
        
        /* Main content */
        .creator-content {
            flex: 1;
            min-width: 0;
        }
        
        /* Header */
        .creator-header {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .menu-toggle {
            display: flex;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: transparent;
            border: none;
            cursor: pointer;
            color: var(--gray-600);
            font-size: 1.25rem;
        }
        
        .menu-toggle:hover {
            background: var(--gray-100);
        }
        
        @media (min-width: 992px) {
            .menu-toggle {
                display: none;
            }
        }
        
        .page-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            border: 1px solid var(--gray-200);
            background: white;
            color: var(--gray-600);
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.875rem;
        }
        
        .btn-logout:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
        }
        
        /* Page content */
        .creator-page {
            padding: 1.5rem;
        }
        
        @media (min-width: 992px) {
            .creator-page {
                padding: 2rem;
            }
        }
        
        /* Overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        /* Alerts */
        .alert {
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            border: none;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #991b1b;
        }
        
        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            color: #92400e;
        }
        
        .alert i {
            font-size: 1.25rem;
            margin-top: 0.1rem;
        }
        
        /* Form Elements */
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid var(--gray-200);
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }
        
        /* Cards */
        .card {
            border-radius: 16px;
            border: 1px solid var(--gray-200);
            background: white;
            overflow: hidden;
        }
        
        .card-header {
            padding: 1rem 1.25rem;
            font-weight: 600;
            border-bottom: 1px solid var(--gray-200);
            background: var(--gray-50);
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
            color: white;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
            color: white;
        }
        
        .btn-outline-secondary {
            background: transparent;
            color: var(--gray-600);
            border: 2px solid var(--gray-200);
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        
        .btn-outline-secondary:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
            color: var(--gray-700);
        }
        
        /* Tables */
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background: var(--gray-50);
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem;
            border-bottom: 2px solid var(--gray-200);
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--gray-100);
        }
        
        .table tbody tr:hover {
            background: var(--gray-50);
        }
        
        /* Badges */
        .badge {
            padding: 0.375rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
        }
        
        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--gray-200);
            transition: all 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
    
    <div class="creator-layout">
        <!-- Sidebar -->
        <aside class="creator-sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('creator.dashboard') }}" class="sidebar-logo">
                    <i class="bi bi-megaphone-fill"></i>
                    Créateur
                </a>
                <button class="sidebar-close" onclick="closeSidebar()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">Principal</div>
                <a href="{{ route('creator.dashboard') }}" class="nav-link {{ request()->routeIs('creator.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    Tableau de Bord
                </a>
                
                <div class="nav-section">Mes Campagnes</div>
                <a href="{{ route('creator.campaigns.index') }}" class="nav-link {{ request()->routeIs('creator.campaigns.index') ? 'active' : '' }}">
                    <i class="bi bi-collection"></i>
                    Toutes les Campagnes
                </a>
                <a href="{{ route('creator.campaigns.create') }}" class="nav-link {{ request()->routeIs('creator.campaigns.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>
                    Nouvelle Campagne
                </a>
                
                <div class="nav-section">Suivi</div>
                <a href="{{ route('creator.analytics') }}" class="nav-link {{ request()->routeIs('creator.analytics') ? 'active' : '' }}">
                    <i class="bi bi-graph-up"></i>
                    Statistiques
                </a>
                <a href="{{ route('creator.participations') }}" class="nav-link {{ request()->routeIs('creator.participations') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    Participations
                    @php
                        $pendingCount = \App\Models\CampaignParticipation::whereIn('campaign_id', Auth::user()->campaigns->pluck('id'))->where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                    <span class="nav-badge">{{ $pendingCount }}</span>
                    @endif
                </a>
            </nav>
            
            <!-- User Info at bottom -->
            <div class="sidebar-user">
                <div class="user-card">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">Créateur de Campagnes</div>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="creator-content">
            <!-- Header -->
            <header class="creator-header">
                <div class="header-left">
                    <button class="menu-toggle" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1 class="page-title">@yield('header', 'Tableau de Bord')</h1>
                </div>
                <div class="header-right">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="d-none d-md-inline">Déconnexion</span>
                        </button>
                    </form>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="creator-page">
                @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i>
                    <div>{{ session('success') }}</div>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle-fill"></i>
                    <div>{{ session('error') }}</div>
                </div>
                @endif
                
                @if($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <ul class="mb-0 ps-3">
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
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('active');
            document.body.style.overflow = document.getElementById('sidebar').classList.contains('open') ? 'hidden' : '';
        }
        
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('active');
            document.body.style.overflow = '';
        }
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
