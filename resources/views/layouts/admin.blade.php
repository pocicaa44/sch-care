<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SCHCare Admin') — SCHCare</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* ========================================
           CSS Variables — Light Theme
           ======================================== */
        :root {
            --bg-body: #f4f6f9;
            --bg-sidebar: #ffffff;
            --bg-card: #ffffff;
            --bg-input: #f9fafb;
            --bg-navbar: rgba(255, 255, 255, 0.88);
            --accent: #1e40af;
            --accent-hover: #1e3a8a;
            --accent-subtle: rgba(30, 64, 175, 0.08);
            --accent-glow: rgba(30, 64, 175, 0.18);
            --text-primary: #111827;
            --text-secondary: #4b5563;
            --text-muted: #9ca3af;
            --border-color: #e5e7eb;
            --success: #16a34a;
            --success-bg: rgba(22, 163, 74, 0.08);
            --warning: #d97706;
            --warning-bg: rgba(217, 119, 6, 0.08);
            --danger: #dc2626;
            --danger-bg: rgba(220, 38, 38, 0.08);
            --info: #1e40af;
            --info-bg: rgba(30, 64, 175, 0.08);
            --sidebar-width: 260px;
            --navbar-height: 64px;
        }

        /* ========================================
           Base & Reset
           ======================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-primary);
            font-size: 14px;
            line-height: 1.6;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-body); }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        /* ========================================
           Sidebar
           ======================================== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            box-shadow: 1px 0 3px rgba(0, 0, 0, 0.04);
        }

        .sidebar-brand {
            height: var(--navbar-height);
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-bottom: 1px solid var(--border-color);
            flex-shrink: 0;
        }

        .sidebar-brand .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--accent), #3b82f6);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .sidebar-brand .brand-text {
            margin-left: 12px;
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.3px;
        }

        .sidebar-brand .brand-text span {
            color: var(--accent);
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .sidebar-nav .nav-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-muted);
            padding: 8px 12px 6px;
            margin-top: 8px;
        }

        .sidebar-nav .nav-label:first-child {
            margin-top: 0;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            padding: 10px 14px;
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 2px;
            position: relative;
        }

        .nav-item-link i {
            font-size: 18px;
            width: 22px;
            text-align: center;
            margin-right: 12px;
            flex-shrink: 0;
            transition: color 0.2s ease;
        }

        .nav-item-link:hover {
            color: var(--text-primary);
            background: rgba(0, 0, 0, 0.04);
        }

        .nav-item-link.active {
            color: var(--accent);
            background: var(--accent-subtle);
            font-weight: 600;
        }

        .nav-item-link.active i {
            color: var(--accent);
        }

        .nav-item-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 20px;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
        }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid var(--border-color);
            flex-shrink: 0;
        }

        .sidebar-footer .nav-item-link {
            color: var(--danger);
        }

        .sidebar-footer .nav-item-link:hover {
            background: var(--danger-bg);
            color: var(--danger);
        }

        /* ========================================
           Main Content Area
           ======================================== */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ========================================
           Top Navbar
           ======================================== */
        .top-navbar {
            height: var(--navbar-height);
            background: var(--bg-navbar);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 1030;
            flex-shrink: 0;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-sidebar-toggle {
            display: none;
            background: none;
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            width: 36px;
            height: 36px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-sidebar-toggle:hover {
            color: var(--text-primary);
            border-color: var(--text-muted);
            background: rgba(0,0,0,0.03);
        }

        .page-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 8px 4px 4px;
            border-radius: 8px;
            cursor: default;
        }

        .admin-avatar {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--accent), #3b82f6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
        }

        .admin-info {
            line-height: 1.3;
        }

        .admin-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .admin-role {
            font-size: 11px;
            color: var(--text-muted);
        }

        /* ========================================
           Konten Utama
           ======================================== */
        .main-content {
            flex: 1;
            padding: 24px;
        }

        /* ========================================
           Card (Light)
           ======================================== */
        .card-dark {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .card-dark:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .card-dark .card-body {
            padding: 20px;
        }

        /* Card statistik */
        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            opacity: 0.06;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover::after {
            opacity: 0.1;
        }

        .stat-card.stat-total::after { background: var(--accent); }
        .stat-card.stat-pending::after { background: var(--warning); }
        .stat-card.stat-diproses::after { background: var(--info); }
        .stat-card.stat-selesai::after { background: var(--success); }
        .stat-card.stat-ditolak::after { background: var(--danger); }
        .stat-card.stat-dihapus::after { background: var(--text-muted); }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-icon.icon-total { background: var(--info-bg); color: var(--info); }
        .stat-icon.icon-pending { background: var(--warning-bg); color: var(--warning); }
        .stat-icon.icon-diproses { background: var(--info-bg); color: var(--info); }
        .stat-icon.icon-selesai { background: var(--success-bg); color: var(--success); }
        .stat-icon.icon-ditolak { background: var(--danger-bg); color: var(--danger); }
        .stat-icon.icon-dihapus { background: rgba(156,163,175,0.12); color: var(--text-muted); }

        .stat-value {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .stat-label {
            font-size: 12.5px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* ========================================
           Tabel (Light)
           ======================================== */
        .table-dark-custom {
            width: 100%;
            margin-bottom: 0;
        }

        .table-dark-custom thead th {
            background: #f9fafb;
            color: var(--text-secondary);
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }

        .table-dark-custom tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid #f3f4f6;
            color: var(--text-primary);
            font-size: 13.5px;
            vertical-align: middle;
        }

        .table-dark-custom tbody tr {
            transition: background 0.15s ease;
        }

        .table-dark-custom tbody tr:hover {
            background: #f9fafb;
        }

        .table-dark-custom tbody tr:last-child td {
            border-bottom: none;
        }

        /* ========================================
           Badge Status
           ======================================== */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        .badge-status .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .badge-pending { background: var(--warning-bg); color: var(--warning); }
        .badge-pending .dot { background: var(--warning); }

        .badge-diproses { background: var(--info-bg); color: var(--info); }
        .badge-diproses .dot { background: var(--info); }

        .badge-selesai { background: var(--success-bg); color: var(--success); }
        .badge-selesai .dot { background: var(--success); }

        .badge-ditolak { background: var(--danger-bg); color: var(--danger); }
        .badge-ditolak .dot { background: var(--danger); }

        .badge-aktif { background: var(--success-bg); color: var(--success); }
        .badge-aktif .dot { background: var(--success); }

        .badge-dihapus-user { background: rgba(156,163,175,0.1); color: #6b7280; }
        .badge-dihapus-user .dot { background: #6b7280; }

        /* ========================================
           Form Controls (Light)
           ======================================== */
        .form-control-dark,
        .form-select-dark {
            background: var(--bg-input);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 13.5px;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control-dark::placeholder {
            color: var(--text-muted);
        }

        .form-control-dark:focus,
        .form-select-dark:focus {
            background: #fff;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
            color: var(--text-primary);
            outline: none;
        }

        .form-select-dark {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%239ca3af' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
            appearance: none;
        }

        .form-select-dark option {
            background: #fff;
            color: var(--text-primary);
        }

        /* ========================================
           Tombol Aksi
           ======================================== */
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-detail {
            background: var(--info-bg);
            color: var(--info);
        }

        .btn-detail:hover {
            background: var(--info);
            color: #fff;
        }

        .btn-delete {
            background: var(--danger-bg);
            color: var(--danger);
        }

        .btn-delete:hover {
            background: var(--danger);
            color: #fff;
        }

        /* ========================================
           Filter Bar
           ======================================== */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-bar .search-input-wrapper {
            position: relative;
            flex: 1;
            min-width: 200px;
        }

        .filter-bar .search-input-wrapper i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
        }

        .filter-bar .search-input-wrapper .form-control-dark {
            padding-left: 36px;
        }

        /* ========================================
           Pagination
           ======================================== */
        .pagination-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding-top: 16px;
            border-top: 1px solid var(--border-color);
            margin-top: 4px;
        }

        .pagination-info {
            font-size: 13px;
            color: var(--text-muted);
        }

        .pagination-dark {
            display: flex;
            gap: 4px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .pagination-dark li a,
        .pagination-dark li span {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 8px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            background: transparent;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }

        .pagination-dark li a:hover {
            background: #f3f4f6;
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .pagination-dark li.active span {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        .pagination-dark li.disabled span {
            color: var(--text-muted);
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* ========================================
           Flash Messages / Toast
           ======================================== */
        .flash-container {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-width: 380px;
        }

        .flash-alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 14px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            animation: slideInRight 0.3s ease;
            border: 1px solid;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .flash-alert.flash-success {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: var(--success);
        }

        .flash-alert.flash-error {
            background: #fef2f2;
            border-color: #fecaca;
            color: var(--danger);
        }

        .flash-alert.flash-warning {
            background: #fffbeb;
            border-color: #fde68a;
            color: var(--warning);
        }

        .flash-alert .flash-close {
            background: none;
            border: none;
            color: inherit;
            opacity: 0.6;
            cursor: pointer;
            padding: 0;
            font-size: 16px;
            margin-left: auto;
            flex-shrink: 0;
        }

        .flash-alert .flash-close:hover {
            opacity: 1;
        }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* ========================================
           Modal (Light)
           ======================================== */
        .modal-dark .modal-content {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            color: var(--text-primary);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .modal-dark .modal-header {
            border-bottom: 1px solid var(--border-color);
            padding: 20px 24px 16px;
        }

        .modal-dark .modal-title {
            font-size: 16px;
            font-weight: 600;
        }

        .modal-dark .modal-body {
            padding: 20px 24px;
            font-size: 14px;
            color: var(--text-secondary);
        }

        .modal-dark .modal-footer {
            border-top: 1px solid var(--border-color);
            padding: 14px 24px 20px;
        }

        .modal-dark .btn-close {
            /* Default warna sudah cocok untuk light mode */
        }

        .btn-confirm-delete {
            background: var(--danger);
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .btn-confirm-delete:hover {
            background: #b91c1c;
        }

        .btn-cancel-modal {
            background: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-cancel-modal:hover {
            background: #f3f4f6;
            color: var(--text-primary);
        }

        /* ========================================
           Overlay sidebar (mobile)
           ======================================== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 1035;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* ========================================
           Empty State
           ======================================== */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 12px;
            opacity: 0.35;
        }

        .empty-state p {
            font-size: 14px;
        }

        /* ========================================
           Responsive
           ======================================== */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            .btn-sidebar-toggle {
                display: flex;
            }

            .admin-info {
                display: none;
            }

            .main-content {
                padding: 16px;
            }

            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-bar .search-input-wrapper {
                min-width: 100%;
            }

            .pagination-wrapper {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
        }

        @media (max-width: 575.98px) {
            .stat-value {
                font-size: 22px;
            }

            .table-dark-custom thead th,
            .table-dark-custom tbody td {
                padding: 10px 12px;
            }
        }

        /* ========================================
           Prefers Reduced Motion
           ======================================== */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>

    <!-- Flash Messages -->
    <div class="flash-container" id="flashContainer">
        @if (session('success'))
            <div class="flash-alert flash-success">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
                <button class="flash-close" onclick="this.parentElement.remove()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="flash-alert flash-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ session('error') }}</span>
                <button class="flash-close" onclick="this.parentElement.remove()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @if (session('warning'))
            <div class="flash-alert flash-warning">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ session('warning') }}</span>
                <button class="flash-close" onclick="this.parentElement.remove()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">S</div>
            <div class="brand-text">SCH<span>Care</span></div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}"
               class="nav-item-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text-fill"></i>
                Laporan
            </a>
            <a href="{{ route('admin.users') }}"
               class="nav-item-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                User
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('logout') }}"
               class="nav-item-link"
               onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                <i class="bi bi-box-arrow-left"></i>
                Logout
            </a>
            <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="display:none;">
                @csrf
            </form>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <header class="top-navbar">
            <div class="navbar-left">
                <button class="btn-sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">@yield('pageTitle', 'Dashboard')</h1>
            </div>
            <div class="navbar-right">
                <div class="admin-profile">
                    <div class="admin-avatar">A</div>
                    <div class="admin-info">
                        <div class="admin-name">Administrator</div>
                        <div class="admin-role">Super Admin</div>
                    </div>
                </div>
            </div>
        </header>

        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade modal-dark" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle text-danger me-1"></i>
                        Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body" id="deleteModalBody">
                    Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-confirm-delete">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
            document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
        }

        document.querySelectorAll('.sidebar .nav-item-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    toggleSidebar();
                }
            });
        });

        const deleteModalEl = document.getElementById('deleteModal');
        let deleteModalInstance = null;

        if (deleteModalEl) {
            deleteModalInstance = new bootstrap.Modal(deleteModalEl);
        }

        function confirmDelete(actionUrl, message) {
            const form = document.getElementById('deleteForm');
            const body = document.getElementById('deleteModalBody');
            if (form) form.action = actionUrl;
            if (body && message) body.textContent = message;
            if (deleteModalInstance) deleteModalInstance.show();
        }

        document.querySelectorAll('.flash-alert').forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(20px)';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });

        document.querySelectorAll('.auto-submit').forEach(el => {
            el.addEventListener('change', function () {
                this.closest('form').submit();
            });
        });
    </script>

    @stack('scripts')
</body>
</html>