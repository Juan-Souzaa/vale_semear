<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'SEMEAR') - Sistema de Gestão Operacional</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #16a34a; /* SEMEAR green */
            --primary-color-dark: #15803d;
            --secondary-color: #64748b;
            --success-color: #16a34a;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --sidebar-bg: #0f2f23;
            --sidebar-active: #0b231b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background-color: var(--sidebar-bg);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #374151;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .logo-text h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            color: white;
        }

        .logo-text p {
            font-size: 0.875rem;
            color: #9ca3af;
            margin: 0;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin: 0.25rem 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: #d1d5db;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.06);
            color: white;
            transform: translateX(2px);
        }

        .nav-link.active {
            background-color: var(--sidebar-active);
            color: white;
            position: relative;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 8px;
            bottom: 8px;
            width: 3px;
            border-radius: 3px;
            background: var(--primary-color);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 2rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 900;
            backdrop-filter: saturate(150%) blur(4px);
            width: 100%;
            box-sizing: border-box;
            overflow: visible;
        }

        .header-title {
            flex: 1;
            min-width: 0;
            overflow: hidden;
        }

        .header-title h1 {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .header-title p {
            color: #6b7280;
            margin: 0;
            font-size: 0.875rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-shrink: 0;
            flex-wrap: wrap;
            position: relative;
            overflow: visible;
        }

        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--dark-color);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: background-color 0.2s ease;
            flex-shrink: 0;
            margin-right: 0.5rem;
        }

        .sidebar-toggle:hover {
            background-color: #f3f4f6;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }

        .sidebar-overlay.active {
            display: block;
        }

        .notification-btn, .profile-btn {
            background: none;
            border: none;
            color: #6b7280;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s, transform .1s;
            position: relative;
        }

        /* Dropdown fix */
        .header-actions .dropdown {
            position: relative;
        }

        .header-actions .dropdown-menu {
            z-index: 1050;
            position: absolute;
            margin-top: 0.5rem;
        }

        .notification-btn:hover, .profile-btn:hover {
            background-color: #f3f4f6;
            color: var(--dark-color);
        }

        .notification-btn:active, .profile-btn:active { transform: scale(.96); }

        .notification-label {
            position: absolute;
            top: 0;
            right: 0;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            min-width: 18px;
            height: 18px;
            font-size: 0.7rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            line-height: 1;
            transform: translate(25%, -25%);
        }

        /* Content Area */
        .content {
            flex: 1;
            padding: 2rem;
            background-color: #f9fafb;
        }

        /* Cards */
        .metric-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            position: relative;
            transition: transform .2s ease, box-shadow .2s ease;
            animation: fadeUp .4s ease both;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,.08);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .metric-card .icon {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .metric-card .icon.blue { background-color: #dbeafe; color: #2563eb; }
        .metric-card .icon.green { background-color: #dcfce7; color: #16a34a; }
        .metric-card .icon.orange { background-color: #fed7aa; color: #ea580c; }
        .metric-card .icon.purple { background-color: #e9d5ff; color: #9333ea; }

        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0.5rem 0;
        }

        .metric-label {
            color: #6b7280;
            font-size: 0.875rem;
            margin: 0;
        }

        .metric-description {
            color: #9ca3af;
            font-size: 0.75rem;
            margin: 0;
        }

        /* Activity List */
        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #f3f4f6;
            transition: background-color .2s ease;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item:hover { background-color:#fafafa; }

        .activity-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .activity-dot.green { background-color: #10b981; }
        .activity-dot.blue { background-color: #3b82f6; }
        .activity-dot.yellow { background-color: #f59e0b; }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: var(--dark-color);
            margin: 0 0 0.25rem 0;
        }

        .activity-description {
            color: #6b7280;
            font-size: 0.875rem;
            margin: 0;
        }

        .activity-status {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-completed {
            background-color: #f3f4f6;
            color: #6b7280;
        }

        .status-progress {
            background-color: var(--dark-color);
            color: white;
        }

        .status-scheduled {
            background-color: #f3f4f6;
            color: #6b7280;
        }

        /* Obligation List */
        .obligation-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .obligation-item:last-child {
            border-bottom: none;
        }

        .obligation-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .obligation-icon.red { color: #ef4444; }
        .obligation-icon.yellow { color: #f59e0b; }
        .obligation-icon.blue { color: #3b82f6; }

        .obligation-content {
            flex: 1;
        }

        .obligation-title {
            font-weight: 600;
            color: var(--dark-color);
            margin: 0 0 0.25rem 0;
        }

        .obligation-description {
            color: #6b7280;
            font-size: 0.875rem;
            margin: 0;
        }

        .obligation-urgency {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .urgency-urgent {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .urgency-attention {
            background-color: #fffbeb;
            color: #d97706;
        }

        .urgency-scheduled {
            background-color: #f3f4f6;
            color: #6b7280;
        }

        /* Buttons - brand color */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: var(--primary-color-dark);
            border-color: var(--primary-color-dark);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar-toggle {
                display: block;
            }

            .header {
                padding: 1rem;
                flex-direction: column;
                align-items: flex-start;
            }

            .header-title {
                width: 100%;
            }

            .header-title h1 {
                font-size: 1.25rem;
            }

            .header-title p {
                font-size: 0.75rem;
            }

            .header-actions {
                width: 100%;
                justify-content: space-between;
                margin-top: 0.5rem;
            }

            .header-actions .btn {
                font-size: 0.875rem;
                padding: 0.5rem 1rem;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 999;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
                overflow-x: hidden;
            }

            .content {
                padding: 1rem;
                overflow-x: hidden;
            }
        }

        @media (max-width: 992px) and (min-width: 769px) {
            .header {
                flex-wrap: wrap;
            }

            .header-title {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .header-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
        
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                        <div class="logo-icon">SE</div>
                    <div class="logo-text">
                            <h1>SEMEAR</h1>
                        <p>Sistema de Gestão Operacional</p>
                    </div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart"></i>
                        Dashboard
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('planejamento.index') }}" class="nav-link {{ request()->routeIs('planejamento.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar3"></i>
                        Planejamento
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('obrigacoes.index') }}" class="nav-link {{ request()->routeIs('obrigacoes.*') ? 'active' : '' }}">
                        <i class="bi bi-exclamation-triangle"></i>
                        Obrigações
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('reunioes.index') }}" class="nav-link {{ request()->routeIs('reunioes.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        Reuniões
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('relatorios') }}" class="nav-link {{ request()->routeIs('relatorios') ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i>
                        Relatórios
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <button class="sidebar-toggle d-md-none" type="button" onclick="toggleSidebar()" title="Abrir menu">
                    <i class="bi bi-list"></i>
                </button>
                <div class="header-title">
                    <h1>@yield('page-title', 'Dashboard')</h1>
                    <p>@yield('page-subtitle', 'Visão geral das atividades da associação')</p>
                </div>
                
                <div class="header-actions">
                    @yield('header-actions')
                    
                    @auth
                    <button class="notification-btn" title="Notificações" data-bs-toggle="modal" data-bs-target="#notificacoesModal">
                        <i class="bi bi-bell"></i>
                        <span class="notification-label" id="notification-count">0</span>
                    </button>
                    <div class="dropdown">
                        <button class="profile-btn dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Perfil">
                            <i class="bi bi-person"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><h6 class="dropdown-header">{{ Auth::user()?->name ?? 'Usuário' }}</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Meu Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endauth
                </div>
            </header>

            <!-- Content -->
            <div class="content">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @auth
    <!-- Modal de Notificações -->
    <div class="modal fade" id="notificacoesModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Notificações</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="notificacoes-content">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endauth

    <!-- Custom JavaScript -->
    <script>

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                const forms = document.getElementsByClassName('needs-validation');
                Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            if (overlay) {
                overlay.classList.toggle('active');
            }
        }
    </script>

    @auth
    @push('scripts')
    <script>
        // Carregar notificações ao abrir modal
        const notificacoesModal = document.getElementById('notificacoesModal');
        if (notificacoesModal) {
            notificacoesModal.addEventListener('show.bs.modal', function() {
                const content = document.getElementById('notificacoes-content');
                if (content) {
                    content.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Carregando...</span></div></div>';
                }
                
                fetch('{{ route("dashboard.notificacoes") }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro na resposta: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        const count = document.getElementById('notification-count');
                        
                        if (count) {
                            const total = data.total || 0;
                            count.textContent = total > 99 ? '99+' : total;
                            count.style.display = total > 0 ? 'flex' : 'none';
                        }
                        
                        if (content) {
                            if (data.notificacoes && data.notificacoes.length > 0) {
                                let html = '';
                                data.notificacoes.forEach(notif => {
                                    html += `
                                        <div class="alert alert-${notif.tipo} alert-dismissible fade show" role="alert">
                                            <strong>${notif.titulo}</strong>
                                            <p class="mb-0 small">${notif.mensagem}</p>
                                            ${notif.link ? `<a href="${notif.link}" class="btn btn-sm btn-outline-primary mt-2">Ver Detalhes</a>` : ''}
                                        </div>
                                    `;
                                });
                                content.innerHTML = html;
                            } else {
                                content.innerHTML = '<p class="text-muted text-center py-4">Nenhuma notificação no momento.</p>';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao carregar notificações:', error);
                        if (content) {
                            content.innerHTML = '<p class="text-danger text-center py-4">Erro ao carregar notificações. Por favor, recarregue a página.</p>';
                        }
                    });
            });
        }

        // Carregar contador de notificações ao carregar página
        document.addEventListener('DOMContentLoaded', function() {
            const count = document.getElementById('notification-count');
            if (!count) return; // Se não existe, usuário não está autenticado
            
            fetch('{{ route("dashboard.notificacoes") }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na resposta: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (count) {
                        const total = data.total || 0;
                        count.textContent = total > 99 ? '99+' : total;
                        count.style.display = total > 0 ? 'flex' : 'none';
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar contador:', error);
                    if (count) {
                        count.style.display = 'none';
                    }
                });
        });
    </script>
    @endpush
    @endauth

    @stack('scripts')
</body>
</html>
