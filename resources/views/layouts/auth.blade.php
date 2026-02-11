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
        html, body {
            height: 100%;
            overflow: hidden;
        }
        
        body {
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            padding-bottom: 0;
        }
        
        .auth-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            height: 100dvh;
            overflow: hidden;
        }
        
        .auth-header {
            padding: var(--space-sm) var(--space-md);
            text-align: center;
            padding-top: calc(var(--space-sm) + var(--safe-area-top));
            flex-shrink: 0;
        }
        
        @media (max-width: 991px) and (max-height: 700px) {
            .auth-header {
                padding: var(--space-xs) var(--space-sm);
            }
        }
        
        .auth-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-xs);
            margin-bottom: 0;
        }
        
        .auth-logo i {
            font-size: 1.75rem;
        }
        
        @media (max-width: 991px) and (max-height: 700px) {
            .auth-logo {
                font-size: 1.25rem;
            }
            .auth-logo i {
                font-size: 1.5rem;
            }
        }
        
        .auth-tagline {
            color: var(--gray-500);
            font-size: 0.75rem;
            margin-top: 2px;
        }
        
        @media (max-width: 991px) and (max-height: 700px) {
            .auth-tagline {
                display: none;
            }
        }
        
        .auth-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .auth-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 0 var(--space-md);
            padding-bottom: calc(var(--space-sm) + var(--safe-area-bottom));
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        @media (max-width: 991px) and (max-height: 700px) {
            .auth-content {
                padding: 0 var(--space-sm);
                padding-bottom: var(--space-xs);
            }
        }
        
        .auth-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            padding: var(--space-md);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }
        
        @media (max-width: 991px) and (max-height: 700px) {
            .auth-card {
                padding: var(--space-sm);
                border-radius: var(--radius-lg);
            }
        }
        
        .auth-title {
            font-size: 1.25rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: var(--space-xs);
            color: var(--gray-900);
        }
        
        @media (max-width: 991px) and (max-height: 700px) {
            .auth-title {
                font-size: 1.1rem;
                margin-bottom: 2px;
            }
        }
        
        .auth-subtitle {
            text-align: center;
            color: var(--gray-500);
            margin-bottom: var(--space-md);
            font-size: 0.8rem;
        }
        
        @media (max-width: 991px) and (max-height: 700px) {
            .auth-subtitle {
                font-size: 0.75rem;
                margin-bottom: var(--space-sm);
            }
        }
        
        .auth-divider {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            margin: var(--space-md) 0;
            color: var(--gray-400);
            font-size: 0.7rem;
        }
        
        @media (max-width: 991px) and (max-height: 700px) {
            .auth-divider {
                margin: var(--space-sm) 0;
            }
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
            padding: var(--space-xs) var(--space-sm);
            color: var(--gray-500);
            font-size: 0.7rem;
            flex-shrink: 0;
        }
        
        @media (max-width: 991px) and (max-height: 700px) {
            .auth-footer {
                display: none;
            }
        }
        
        .auth-footer a {
            color: var(--primary);
            font-weight: 600;
        }
        
        .auth-illustration {
            display: none;
        }
        
        /* Mobile form adjustments */
        @media (max-width: 991px) {
            .form-group {
                margin-bottom: var(--space-sm);
            }
            
            .form-label {
                font-size: 0.8rem;
                margin-bottom: 4px;
            }
            
            .form-input {
                padding: 0.5rem 0.75rem;
                font-size: 0.9rem;
            }
            
            .form-hint, .form-error {
                font-size: 0.7rem;
                margin-top: 2px;
            }
            
            .btn--lg {
                padding: 0.65rem 1rem;
                font-size: 0.9rem;
            }
            
            .form-check {
                margin-bottom: var(--space-sm);
            }
            
            .mb-lg {
                margin-bottom: var(--space-sm) !important;
            }
            
            .mb-md {
                margin-bottom: var(--space-xs) !important;
            }
            
            .mt-lg {
                margin-top: var(--space-sm) !important;
            }
            
            .alert {
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
            }
            
            .text-muted.mb-md {
                margin-bottom: var(--space-xs) !important;
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 991px) and (max-height: 700px) {
            .form-group {
                margin-bottom: 0.4rem;
            }
            
            .form-label {
                font-size: 0.75rem;
                margin-bottom: 2px;
            }
            
            .form-input {
                padding: 0.4rem 0.6rem;
                font-size: 0.85rem;
            }
            
            .btn--lg {
                padding: 0.5rem 0.875rem;
                font-size: 0.85rem;
            }
            
            .btn--outline {
                padding: 0.4rem 0.75rem;
                font-size: 0.8rem;
            }
            
            .text-muted.mb-md {
                display: none;
            }
        }
        
        @media (min-width: 992px) {
            html, body {
                overflow: auto;
            }
            
            .auth-wrapper {
                flex-direction: row;
                height: auto;
                min-height: 100vh;
                overflow: visible;
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
                overflow: visible;
            }
            
            .auth-content {
                padding: 0;
                overflow: visible;
            }
            
            .auth-card {
                box-shadow: none;
                padding: var(--space-xl) 0;
            }
            
            .auth-title {
                font-size: 1.5rem;
            }
            
            .auth-subtitle {
                font-size: 0.875rem;
                margin-bottom: var(--space-xl);
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
