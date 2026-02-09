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
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
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
            padding: var(--space-md);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .admin-sidebar__logo {
            font-size: 1.1rem;
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
            padding: var(--space-sm) 0;
        }
        
        .admin-sidebar__section {
            padding: var(--space-sm) var(--space-md) var(--space-xs);
            font-size: 0.65rem;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        
        .admin-sidebar__link {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            padding: var(--space-xs) var(--space-md);
            color: var(--gray-400);
            font-size: 0.8rem;
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
            width: 18px;
            text-align: center;
            font-size: 0.9rem;
        }
        
        .admin-sidebar__badge {
            margin-left: auto;
            background: var(--danger);
            color: var(--white);
            font-size: 0.6rem;
            padding: 2px 5px;
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
            padding: var(--space-sm) var(--space-md);
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: var(--space-sm);
        }
        
        .admin-header__left {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }
        
        .admin-header__toggle {
            display: flex;
            width: 36px;
            height: 36px;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-md);
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
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }
        
        @media (max-width: 480px) {
            .admin-header__title {
                font-size: 0.9rem;
            }
        }
        
        .admin-header__right {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }
        
        .admin-page {
            padding: var(--space-md);
        }
        
        @media (min-width: 768px) {
            .admin-page {
                padding: var(--space-lg);
            }
        }
        
        @media (min-width: 992px) {
            .admin-page {
                padding: var(--space-xl);
            }
        }
        
        .admin-page__header {
            margin-bottom: var(--space-lg);
        }
        
        @media (min-width: 768px) {
            .admin-page__header {
                margin-bottom: var(--space-xl);
            }
        }
        
        .admin-page__title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0 0 var(--space-xs);
        }
        
        @media (min-width: 768px) {
            .admin-page__title {
                font-size: 1.5rem;
            }
        }
        
        .admin-page__subtitle {
            color: var(--gray-500);
            margin: 0;
            font-size: 0.85rem;
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
            gap: var(--space-sm);
            margin-bottom: var(--space-lg);
        }
        
        @media (min-width: 768px) {
            .admin-stats {
                grid-template-columns: repeat(4, 1fr);
                gap: var(--space-md);
                margin-bottom: var(--space-xl);
            }
        }
        
        /* Admin Card */
        .admin-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }
        
        .admin-card__header {
            padding: var(--space-sm) var(--space-md);
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        @media (min-width: 768px) {
            .admin-card__header {
                padding: var(--space-md) var(--space-lg);
            }
        }
        
        .admin-card__title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }
        
        @media (min-width: 768px) {
            .admin-card__title {
                font-size: 1rem;
            }
        }
        
        .admin-card__body {
            padding: var(--space-md);
        }
        
        @media (min-width: 768px) {
            .admin-card__body {
                padding: var(--space-lg);
            }
        }
        
        .admin-card__footer {
            padding: var(--space-md) var(--space-lg);
            border-top: 1px solid var(--gray-100);
            background: var(--gray-50);
        }
        
        /* Filter Card Styles */
        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #e5e7eb;
        }
        
        @media (min-width: 768px) {
            .filter-card {
                border-radius: 16px;
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }
        }
        
        .filter-card .form-label {
            font-weight: 600;
            color: #374151;
            font-size: 0.8rem;
            margin-bottom: 0.4rem;
        }
        
        @media (min-width: 768px) {
            .filter-card .form-label {
                font-size: 0.875rem;
                margin-bottom: 0.5rem;
            }
        }
        
        .filter-card .form-control,
        .filter-card .form-select {
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        
        @media (min-width: 768px) {
            .filter-card .form-control,
            .filter-card .form-select {
                border-radius: 10px;
                padding: 0.625rem 1rem;
                font-size: 0.9rem;
            }
        }
        
        .filter-card .form-control:focus,
        .filter-card .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }
        
        .filter-card .input-group-text {
            background: #f3f4f6;
            border: 2px solid #e5e7eb;
            border-right: none;
            border-radius: 8px 0 0 8px;
            color: #6b7280;
            padding: 0.5rem 0.75rem;
        }
        
        @media (min-width: 768px) {
            .filter-card .input-group-text {
                border-radius: 10px 0 0 10px;
            }
        }
        
        .filter-card .input-group .form-control {
            border-radius: 0 8px 8px 0;
        }
        
        @media (min-width: 768px) {
            .filter-card .input-group .form-control {
                border-radius: 0 10px 10px 0;
            }
        }
        
        /* Modern Form Styles */
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            padding: 0.625rem 0.875rem;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        
        @media (min-width: 768px) {
            .form-control, .form-select {
                border-radius: 10px;
                padding: 0.75rem 1rem;
                font-size: 0.95rem;
            }
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }
        
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.4rem;
            font-size: 0.85rem;
        }
        
        @media (min-width: 768px) {
            .form-label {
                margin-bottom: 0.5rem;
                font-size: 0.9rem;
            }
        }
        
        /* Modern Card Styles */
        .card {
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        
        @media (min-width: 768px) {
            .card {
                border-radius: 16px;
            }
        }
        
        .card-header {
            padding: 0.875rem 1rem;
            font-weight: 600;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.9rem;
        }
        
        @media (min-width: 768px) {
            .card-header {
                padding: 1rem 1.25rem;
                font-size: 1rem;
            }
        }
        
        .card-body {
            padding: 1rem;
        }
        
        @media (min-width: 768px) {
            .card-body {
                padding: 1.25rem;
            }
        }
        
        /* Modern Buttons */
        .btn--primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s;
            text-decoration: none;
            font-size: 0.8rem;
        }
        
        @media (min-width: 768px) {
            .btn--primary {
                padding: 0.625rem 1.25rem;
                border-radius: 10px;
                gap: 0.5rem;
                font-size: 0.9rem;
            }
        }
        
        .btn--primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
            color: white;
        }
        
        .btn--success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s;
            text-decoration: none;
            font-size: 0.8rem;
        }
        
        @media (min-width: 768px) {
            .btn--success {
                padding: 0.625rem 1.25rem;
                border-radius: 10px;
                gap: 0.5rem;
                font-size: 0.9rem;
            }
        }
        
        .btn--success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
            color: white;
        }
        
        .btn--warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s;
            text-decoration: none;
            font-size: 0.8rem;
        }
        
        @media (min-width: 768px) {
            .btn--warning {
                padding: 0.625rem 1.25rem;
                border-radius: 10px;
                gap: 0.5rem;
                font-size: 0.9rem;
            }
        }
        
        .btn--warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
            color: white;
        }
        
        .btn--danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s;
            text-decoration: none;
            font-size: 0.8rem;
        }
        
        @media (min-width: 768px) {
            .btn--danger {
                padding: 0.625rem 1.25rem;
                border-radius: 10px;
                gap: 0.5rem;
                font-size: 0.9rem;
            }
        }
        
        .btn--danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
            color: white;
        }
        
        .btn--ghost {
            background: transparent;
            color: #6b7280;
            border: 2px solid #e5e7eb;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s;
            text-decoration: none;
            font-size: 0.8rem;
        }
        
        @media (min-width: 768px) {
            .btn--ghost {
                padding: 0.5rem 1rem;
                border-radius: 10px;
                gap: 0.5rem;
                font-size: 0.9rem;
            }
        }
        
        .btn--ghost:hover {
            background: #f3f4f6;
            color: #374151;
            border-color: #d1d5db;
        }
        
        .btn--sm {
            padding: 0.3rem 0.65rem;
            font-size: 0.75rem;
        }
        
        @media (min-width: 768px) {
            .btn--sm {
                padding: 0.375rem 0.875rem;
                font-size: 0.8rem;
            }
        }
        
        /* Modern Table Styles */
        .table {
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.85rem;
        }
        
        @media (min-width: 768px) {
            .table {
                font-size: 0.9rem;
            }
        }
        
        .table thead th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.75rem 0.5rem;
            border-bottom: 2px solid #e5e7eb;
            white-space: nowrap;
        }
        
        @media (min-width: 768px) {
            .table thead th {
                font-size: 0.8rem;
                padding: 1rem;
            }
        }
        
        .table tbody td {
            padding: 0.75rem 0.5rem;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
        }
        
        @media (min-width: 768px) {
            .table tbody td {
                padding: 1rem;
            }
        }
        
        .table tbody tr:hover {
            background: #f9fafb;
        }
        
        /* Responsive table wrapper */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* Modern Badge Styles */
        .badge {
            padding: 0.3rem 0.6rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.65rem;
        }
        
        @media (min-width: 768px) {
            .badge {
                padding: 0.375rem 0.75rem;
                border-radius: 8px;
                font-size: 0.75rem;
            }
        }
        
        /* Alert Styles */
        .alert-modern {
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border: none;
        }
        
        .alert-modern.success {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
        }
        
        .alert-modern.error {
            background: rgba(239, 68, 68, 0.1);
            color: #991b1b;
        }
        
        .alert-modern.warning {
            background: rgba(245, 158, 11, 0.1);
            color: #92400e;
        }
        
        .alert-modern.info {
            background: rgba(59, 130, 246, 0.1);
            color: #1e40af;
        }
        
        /* Stats Card */
        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
            transition: all 0.2s;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .stats-card.gradient-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border: none;
        }
        
        .stats-card.gradient-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
        }
        
        .stats-card.gradient-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border: none;
        }
        
        .stats-card.gradient-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
        }
        
        .stats-card.gradient-info {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            border: none;
        }
        
        .stats-card__icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stats-card__value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 0.25rem;
        }
        
        .stats-card__label {
            font-size: 0.875rem;
            opacity: 0.8;
        }
        
        /* Form Card */
        .form-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 1.5rem;
            border: 1px solid #e5e7eb;
        }
        
        .form-card__header {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            color: white;
        }
        
        .form-card__header.primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }
        
        .form-card__header.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .form-card__header.warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        
        .form-card__header.danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        .form-card__header.info {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        
        .form-card__body {
            padding: 1.5rem;
        }
        
        /* Input Group Modern */
        .input-group-modern {
            display: flex;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.2s;
        }
        
        .input-group-modern:focus-within {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }
        
        .input-group-modern .input-prefix,
        .input-group-modern .input-suffix {
            background: #f9fafb;
            padding: 0.75rem 1rem;
            color: #6b7280;
            font-size: 0.9rem;
            white-space: nowrap;
            display: flex;
            align-items: center;
        }
        
        .input-group-modern input {
            flex: 1;
            border: none;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            min-width: 0;
        }
        
        .input-group-modern input:focus {
            outline: none;
        }
        
        /* Data Table Card */
        .data-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }
        
        .data-card__header {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }
        
        .data-card__title {
            font-weight: 700;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .data-card__body {
            padding: 0;
        }
        
        /* Pagination */
        .pagination {
            gap: 0.25rem;
        }
        
        .pagination .page-link {
            border-radius: 8px;
            border: none;
            padding: 0.5rem 0.875rem;
            color: #6b7280;
            font-weight: 500;
        }
        
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
        }
        
        .pagination .page-link:hover {
            background: #f3f4f6;
            color: #374151;
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
                <a href="{{ route('admin.dashboard') }}" class="admin-sidebar__logo">
                    <i class="bi bi-shield-check"></i>
                    Super Admin
                </a>
                <button class="admin-sidebar__close" onclick="closeSidebar()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <nav class="admin-sidebar__nav">
                <div class="admin-sidebar__section">Principal</div>
                <a href="{{ route('admin.dashboard') }}" class="admin-sidebar__link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i>
                    Tableau de Bord
                </a>
                
                <div class="admin-sidebar__section">Gestion</div>
                <a href="{{ route('admin.users.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    Utilisateurs
                </a>
                <a href="{{ route('admin.campaigns.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.campaigns.index') || request()->routeIs('admin.campaigns.show') || request()->routeIs('admin.campaigns.edit') ? 'active' : '' }}">
                    <i class="bi bi-megaphone"></i>
                    Campagnes
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
                
                <div class="admin-sidebar__section">Finances</div>
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
                <a href="{{ route('admin.pieces.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.pieces.*') ? 'active' : '' }}">
                    <i class="bi bi-coin"></i>
                    Pièces
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
                        <div class="text-xs text-gray-400">Super Administrateur</div>
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
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
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
