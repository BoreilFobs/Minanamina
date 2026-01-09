<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#6366f1">
    <meta name="description" content="Gagnez des pièces en participant aux campagnes publicitaires">
    
    <title>@yield('title', 'Minanamina')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Modern CSS -->
    <link href="{{ asset('css/modern.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="has-header">
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    @auth
        <!-- Mobile Header -->
        <header class="mobile-header">
            <a href="{{ route('dashboard') }}" class="mobile-header__logo">
                <i class="bi bi-coin"></i>
                <span>Minanamina</span>
            </a>
            <div class="mobile-header__actions">
                <button class="mobile-header__btn" onclick="location.href='{{ route('rewards.index') }}'">
                    <i class="bi bi-wallet2"></i>
                </button>
                <a href="{{ route('profile.show') }}" class="mobile-header__btn">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="" class="avatar avatar--sm" style="width: 100%; height: 100%;">
                    @else
                        <i class="bi bi-person-circle"></i>
                    @endif
                </a>
            </div>
        </header>

        <!-- Desktop Navigation -->
        <nav class="desktop-nav">
            <div class="container-app">
                <div class="desktop-nav__container">
                    <a href="{{ route('dashboard') }}" class="desktop-nav__logo">
                        <i class="bi bi-coin"></i>
                        Minanamina
                    </a>
                    
                    <ul class="desktop-nav__menu">
                        <li>
                            <a href="{{ route('dashboard') }}" class="desktop-nav__link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="bi bi-house"></i>
                                Accueil
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('campaigns.index') }}" class="desktop-nav__link {{ request()->routeIs('campaigns.*') ? 'active' : '' }}">
                                <i class="bi bi-megaphone"></i>
                                Campagnes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('rewards.index') }}" class="desktop-nav__link {{ request()->routeIs('rewards.*') ? 'active' : '' }}">
                                <i class="bi bi-wallet2"></i>
                                Mes Pièces
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('referrals.index') }}" class="desktop-nav__link {{ request()->routeIs('referrals.*') ? 'active' : '' }}">
                                <i class="bi bi-gift"></i>
                                Parrainages
                            </a>
                        </li>
                        @if(Auth::user()->isCampaignCreator() || Auth::user()->isSuperAdmin())
                        <li>
                            <a href="{{ route('admin.campaigns.index') }}" class="desktop-nav__link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                                <i class="bi bi-gear"></i>
                                Administration
                            </a>
                        </li>
                        @endif
                    </ul>
                    
                    <div class="desktop-nav__actions">
                        <div class="flex items-center gap-md">
                            <div class="text-right">
                                <div class="text-sm font-semibold">{{ number_format(Auth::user()->pieces_balance) }}</div>
                                <div class="text-xs text-muted">pièces</div>
                            </div>
                            <div class="relative" style="position: relative;">
                                <button class="btn btn--ghost btn--icon" onclick="toggleUserMenu()" id="userMenuBtn">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="" class="avatar">
                                    @else
                                        <div class="avatar avatar-placeholder">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                                    @endif
                                </button>
                                <div class="user-dropdown hidden" id="userDropdown">
                                    <div class="user-dropdown__header">
                                        <div class="font-semibold">{{ Auth::user()->name }}</div>
                                        <div class="text-sm text-muted">{{ Auth::user()->phone }}</div>
                                    </div>
                                    <div class="user-dropdown__divider"></div>
                                    <a href="{{ route('profile.show') }}" class="user-dropdown__item">
                                        <i class="bi bi-person"></i> Mon Profil
                                    </a>
                                    <a href="{{ route('campaigns.my-participations') }}" class="user-dropdown__item">
                                        <i class="bi bi-list-check"></i> Mes Participations
                                    </a>
                                    <a href="{{ route('rewards.conversions') }}" class="user-dropdown__item">
                                        <i class="bi bi-arrow-left-right"></i> Mes Conversions
                                    </a>
                                    <div class="user-dropdown__divider"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="user-dropdown__item user-dropdown__item--danger w-full">
                                            <i class="bi bi-box-arrow-right"></i> Déconnexion
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Bottom Navigation -->
        <nav class="bottom-nav">
            <a href="{{ route('dashboard') }}" class="bottom-nav__item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house{{ request()->routeIs('dashboard') ? '-fill' : '' }}"></i>
                <span>Accueil</span>
            </a>
            <a href="{{ route('campaigns.index') }}" class="bottom-nav__item {{ request()->routeIs('campaigns.*') ? 'active' : '' }}">
                <i class="bi bi-megaphone{{ request()->routeIs('campaigns.*') ? '-fill' : '' }}"></i>
                <span>Campagnes</span>
            </a>
            <a href="{{ route('referrals.index') }}" class="bottom-nav__item {{ request()->routeIs('referrals.*') ? 'active' : '' }}">
                <i class="bi bi-gift{{ request()->routeIs('referrals.*') ? '-fill' : '' }}"></i>
                <span>Parrainer</span>
            </a>
            <a href="{{ route('rewards.index') }}" class="bottom-nav__item {{ request()->routeIs('rewards.*') ? 'active' : '' }}">
                <i class="bi bi-wallet2{{ request()->routeIs('rewards.*') ? '' : '' }}"></i>
                <span>Pièces</span>
            </a>
            <a href="{{ route('profile.show') }}" class="bottom-nav__item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-person{{ request()->routeIs('profile.*') ? '-fill' : '' }}"></i>
                <span>Profil</span>
            </a>
        </nav>
    @endauth

    <!-- Main Content -->
    <main class="page-content">
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showToast('{{ session('success') }}', 'success');
                });
            </script>
        @endif
        
        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showToast('{{ session('error') }}', 'error');
                });
            </script>
        @endif

        @yield('content')
    </main>

    <!-- Scripts -->
    <script>
        // Toast notifications
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast--${type}`;
            toast.innerHTML = `
                <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle'}"></i>
                <div class="flex-1">${message}</div>
                <button onclick="this.parentElement.remove()" class="btn btn--ghost btn--icon" style="width: 24px; height: 24px;">
                    <i class="bi bi-x"></i>
                </button>
            `;
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 5000);
        }

        // User dropdown toggle
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown on outside click
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('userDropdown');
            const btn = document.getElementById('userMenuBtn');
            if (dropdown && btn && !dropdown.contains(e.target) && !btn.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Pull to refresh (mobile)
        let touchStartY = 0;
        let touchEndY = 0;
        
        document.addEventListener('touchstart', e => {
            touchStartY = e.touches[0].clientY;
        }, { passive: true });
        
        document.addEventListener('touchend', e => {
            touchEndY = e.changedTouches[0].clientY;
            if (window.scrollY === 0 && touchEndY - touchStartY > 100) {
                location.reload();
            }
        }, { passive: true });

        // Haptic feedback on button click (if supported)
        document.querySelectorAll('.btn, .bottom-nav__item').forEach(btn => {
            btn.addEventListener('click', () => {
                if (navigator.vibrate) {
                    navigator.vibrate(10);
                }
            });
        });
    </script>

    <style>
        /* User Dropdown */
        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: var(--space-sm);
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            min-width: 220px;
            z-index: 1000;
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }
        
        .user-dropdown.hidden {
            display: none;
        }
        
        .user-dropdown__header {
            padding: var(--space-md);
            background: var(--gray-50);
        }
        
        .user-dropdown__divider {
            height: 1px;
            background: var(--gray-200);
        }
        
        .user-dropdown__item {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            padding: var(--space-sm) var(--space-md);
            color: var(--gray-700);
            font-size: 0.875rem;
            transition: background var(--transition-fast);
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }
        
        .user-dropdown__item:hover {
            background: var(--gray-50);
        }
        
        .user-dropdown__item--danger {
            color: var(--danger);
        }
        
        .user-dropdown__item--danger:hover {
            background: var(--danger-bg);
        }
    </style>

    @stack('scripts')
</body>
</html>
