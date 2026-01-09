<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#6366f1">
    
    <title>@yield('title', 'Minanamina')</title>
    
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-bottom: 0;
        }
        
        .auth-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .auth-header {
            padding: var(--space-lg);
            text-align: center;
            padding-top: calc(var(--space-lg) + var(--safe-area-top));
        }
        
        .auth-logo {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-sm);
            margin-bottom: var(--space-sm);
        }
        
        .auth-logo i {
            font-size: 2.5rem;
        }
        
        .auth-tagline {
            color: var(--gray-500);
            font-size: 0.875rem;
        }
        
        .auth-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 0 var(--space-lg);
            padding-bottom: calc(var(--space-xl) + var(--safe-area-bottom));
        }
        
        .auth-card {
            background: var(--white);
            border-radius: var(--radius-2xl);
            padding: var(--space-xl);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }
        
        .auth-title {
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: var(--space-xs);
            color: var(--gray-900);
        }
        
        .auth-subtitle {
            text-align: center;
            color: var(--gray-500);
            margin-bottom: var(--space-xl);
            font-size: 0.875rem;
        }
        
        .auth-divider {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            margin: var(--space-xl) 0;
            color: var(--gray-400);
            font-size: 0.75rem;
        }
        
        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gray-200);
        }
        
        .auth-footer {
            text-align: center;
            padding: var(--space-lg);
            color: var(--gray-500);
            font-size: 0.875rem;
        }
        
        .auth-footer a {
            color: var(--primary);
            font-weight: 600;
        }
        
        .auth-illustration {
            display: none;
        }
        
        @media (min-width: 992px) {
            .auth-wrapper {
                flex-direction: row;
            }
            
            .auth-illustration {
                display: flex;
                flex: 1;
                background: linear-gradient(135deg, var(--primary), var(--primary-dark));
                align-items: center;
                justify-content: center;
                padding: var(--space-2xl);
                color: var(--white);
            }
            
            .auth-illustration__content {
                max-width: 500px;
                text-align: center;
            }
            
            .auth-illustration__icon {
                font-size: 6rem;
                margin-bottom: var(--space-xl);
                opacity: 0.9;
            }
            
            .auth-illustration__title {
                font-size: 2rem;
                font-weight: 700;
                margin-bottom: var(--space-md);
            }
            
            .auth-illustration__text {
                font-size: 1.125rem;
                opacity: 0.9;
                line-height: 1.6;
            }
            
            .auth-main {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
                padding: var(--space-2xl);
            }
            
            .auth-content {
                padding: 0;
            }
            
            .auth-card {
                box-shadow: none;
                padding: var(--space-xl) 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="auth-wrapper">
        <!-- Desktop Illustration -->
        <div class="auth-illustration">
            <div class="auth-illustration__content">
                <div class="auth-illustration__icon">
                    <i class="bi bi-coin"></i>
                </div>
                <h1 class="auth-illustration__title">Bienvenue sur Minanamina</h1>
                <p class="auth-illustration__text">
                    Participez à des campagnes publicitaires et gagnez des pièces que vous pouvez convertir en argent réel. Simple, rapide et fiable.
                </p>
            </div>
        </div>
        
        <!-- Auth Form -->
        <div class="auth-main">
            <div class="auth-header lg\:hidden">
                <div class="auth-logo">
                    <i class="bi bi-coin"></i>
                    Minanamina
                </div>
                <p class="auth-tagline">Gagnez en participant</p>
            </div>
            
            <div class="auth-content">
                @yield('content')
            </div>
        </div>
    </div>
    
    <script>
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('SW registered'))
                    .catch(err => console.log('SW registration failed:', err));
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>
