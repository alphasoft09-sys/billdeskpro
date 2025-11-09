<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BillDesk Pro')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #fafafa;
            color: #1a1a1a;
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        .app-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid #e5e5e5;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e5e5;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: #1a1a1a;
        }
        
        .logo-icon {
            width: 32px;
            height: 32px;
            background: #1a1a1a;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }
        
        .user-profile {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e5e5;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: #f5f5f5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 600;
            color: #666;
        }
        
        .user-details h4 {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 0.125rem;
        }
        
        .user-details p {
            font-size: 0.75rem;
            color: #666;
        }
        
        .nav-menu {
            padding: 1rem 0;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            color: #666;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        
        .nav-item:hover {
            background: #f8f8f8;
            color: #1a1a1a;
        }
        
        .nav-item.active {
            background: #f8f8f8;
            color: #1a1a1a;
            border-left-color: #1a1a1a;
        }
        
        .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        /* Header */
        .header {
            background: #ffffff;
            border-bottom: 1px solid #e5e5e5;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-left h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1a1a1a;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .header-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            background: #ffffff;
            color: #666;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .btn:hover {
            background: #f8f8f8;
            color: #1a1a1a;
        }
        
        .btn-primary {
            background: #1a1a1a;
            color: #ffffff;
            border-color: #1a1a1a;
        }
        
        .btn-primary:hover {
            background: #333;
            border-color: #333;
        }
        
        .btn-danger {
            background: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
        }
        
        .btn-danger:hover {
            background: #b91c1c;
            border-color: #b91c1c;
        }
        
        /* Content Area */
        .content {
            flex: 1;
            padding: 2rem;
        }
        
        .page-header {
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            color: #666;
            font-size: 1rem;
        }
        
        /* Cards */
        .card {
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e5e5;
        }
        
        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1a1a1a;
        }
        
        /* Footer */
        .footer {
            background: #ffffff;
            border-top: 1px solid #e5e5e5;
            padding: 1rem 2rem;
            margin-top: auto;
            text-align: center;
            color: #666;
            font-size: 0.875rem;
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            min-width: 0;
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.75rem;
            font-weight: 500;
            line-height: 1.2;
        }
        
        /* Modules Grid */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .module-card {
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 1.5rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
        }
        
        .module-card:hover {
            border-color: #1a1a1a;
            transform: translateY(-2px);
            color: inherit;
        }
        
        .module-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }
        
        .module-desc {
            color: #666;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        
        .module-status {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            background: #f0f0f0;
            color: #666;
        }
        
        .status-active {
            background: #dcfce7;
            color: #166534;
        }
        
        /* Forms */
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #1a1a1a;
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: border-color 0.2s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #1a1a1a;
        }
        
        /* Tables */
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e5e5e5;
        }
        
        th {
            background: #f8f8f8;
            font-weight: 600;
            color: #1a1a1a;
            font-size: 0.875rem;
        }
        
        td {
            font-size: 0.875rem;
            color: #666;
        }
        
        tr:hover {
            background: #f8f8f8;
        }
        
        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        
        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        /* Loading */
        .loading {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .header {
                padding: 1rem;
            }
            
            .content {
                padding: 1rem;
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }
            
            .stat-card {
                padding: 0.75rem;
            }
            
            .stat-number {
                font-size: 1.25rem;
            }
            
            .stat-label {
                font-size: 0.7rem;
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .modules-grid {
                grid-template-columns: 1fr;
            }
            
            .header-right {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
        
        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }
        
        @media (max-width: 1024px) {
            .mobile-menu-toggle {
                display: block;
            }
        }
    </style>
    
    <!-- Selectize CSS -->
    <link rel="stylesheet" href="{{ asset('css/vendor/selectize.bootstrap3.min.css') }}">
    
    <!-- Custom Selectize Fixes -->
    <style>
        /* Basic Selectize fixes */
        .selectize-dropdown {
            z-index: 9999 !important;
        }
        
        .modal .selectize-dropdown {
            z-index: 10001 !important;
        }
        
        .selectize-input {
            border: 1px solid #d1d5db !important;
            border-radius: 6px !important;
            padding: 0.75rem !important;
            font-size: 0.875rem !important;
        }
        
        .selectize-input:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon">🏪</div>
                    <span>BillDesk Pro</span>
                </div>
            </div>
            
            <div class="user-profile">
                <div class="user-info">
                    <div class="user-avatar" id="user-avatar">👤</div>
                    <div class="user-details">
                        <h4 id="user-name">Loading...</h4>
                        <p id="user-role">Loading...</p>
                    </div>
                </div>
            </div>
            
            <nav class="nav-menu">
                <a href="/dashboard" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">📊</span>
                    Dashboard
                </a>
                <a href="/users" class="nav-item {{ request()->is('users*') ? 'active' : '' }}">
                    <span class="nav-icon">👥</span>
                    User Management
                </a>
                <a href="/suppliers" class="nav-item {{ request()->is('suppliers*') ? 'active' : '' }}">
                    <span class="nav-icon">🏢</span>
                    Supplier Management
                </a>
                <a href="/inventory" class="nav-item {{ request()->is('inventory*') ? 'active' : '' }}">
                    <span class="nav-icon">📦</span>
                    Inventory
                </a>
                <a href="/inventory/batches" class="nav-item {{ request()->is('inventory/batches*') ? 'active' : '' }}">
                    <span class="nav-icon">📋</span>
                    Batch Management
                </a>
                <a href="/sales" class="nav-item {{ request()->is('sales*') ? 'active' : '' }}">
                    <span class="nav-icon">🛒</span>
                    Sales
                </a>
                <a href="/purchases" class="nav-item {{ request()->is('purchases*') ? 'active' : '' }}">
                    <span class="nav-icon">📋</span>
                    Purchases
                </a>
                <a href="/invoices" class="nav-item {{ request()->is('invoices*') ? 'active' : '' }}">
                    <span class="nav-icon">📄</span>
                    Invoices
                </a>
                <a href="/billing" class="nav-item {{ request()->is('billing*') ? 'active' : '' }}">
                    <span class="nav-icon">💳</span>
                    Billing
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="mobile-menu-toggle" onclick="toggleSidebar()">☰</button>
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="header-right">
                    <div class="header-actions">
                        <button class="btn" onclick="copyToken()">
                            <span>📋</span>
                            Copy Token
                        </button>
                        <button class="btn btn-danger" onclick="logout()">
                            <span>🚪</span>
                            Logout
                        </button>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <main class="content">
                @yield('content')
            </main>
            
            <!-- Footer -->
            <footer class="footer">
                <p>&copy; 2025 BillDesk Pro - Hardware Shop Management System. All rights reserved.</p>
            </footer>
        </div>
    </div>

    <script>
        // Load user data
        document.addEventListener('DOMContentLoaded', function() {
            loadUserData();
        });

        function loadUserData() {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                window.location.href = '/';
                return;
            }

            fetch('/api/profile', {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Authentication failed');
                }
                return response.json();
            })
            .then(data => {
                const user = data.user || data;
                if (user && user.name) {
                    document.getElementById('user-name').textContent = user.name;
                    document.getElementById('user-role').textContent = user.user_role === 1 ? 'Administrator' : 'User';
                    
                    // Set user avatar initials
                    const initials = user.name.split(' ').map(n => n[0]).join('').toUpperCase();
                    document.getElementById('user-avatar').textContent = initials;
                } else {
                    throw new Error('Invalid user data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                localStorage.removeItem('auth_token');
                window.location.href = '/';
            });
        }

        function copyToken() {
            const token = localStorage.getItem('auth_token');
            if (token) {
                navigator.clipboard.writeText(token).then(() => {
                    alert('Token copied to clipboard!');
                });
            }
        }

        function logout() {
            const token = localStorage.getItem('auth_token');
            if (token) {
                fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(() => {
                    localStorage.removeItem('auth_token');
                    window.location.href = '/';
                });
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const mobileToggle = document.querySelector('.mobile-menu-toggle');
            
            if (window.innerWidth <= 1024 && 
                !sidebar.contains(e.target) && 
                !mobileToggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    </script>
    
    <!-- jQuery -->
    <script src="{{ asset('js/vendor/jquery.min.js') }}"></script>
    
    <!-- Selectize.js -->
    <script src="{{ asset('js/vendor/selectize.min.js') }}"></script>
    
    <!-- Selectize will be initialized per page -->
    
    @stack('scripts')
</body>
</html>
