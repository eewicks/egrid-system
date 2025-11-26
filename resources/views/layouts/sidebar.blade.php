<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EGMS Admin Panel</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
            --logout-bg: #DC2626;
            --logout-hover: #B91C1C;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --transition: all 0.3s ease-in-out;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            line-height: 1.6;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--primary-gradient);
            z-index: 1000;
            transition: var(--transition);
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        /* Logo Section */
        .logo-section {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: var(--transition);
        }
        
        .logo-section img {
            width: 35px;
            height: 35px;
            object-fit: contain;
            border-radius: 8px;
        }
        
        .logo-section span {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        /* Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }
        
        .nav-item {
            margin: 0.25rem 1rem;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: var(--transition);
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            opacity: 0;
            transition: var(--transition);
        }
        
        .nav-link:hover {
            color: white;
            text-decoration: none;
            transform: translateX(4px);
        }
        
        .nav-link:hover::before {
            opacity: 1;
        }
        
        .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-weight: 600;
        }
        
        .nav-link.active::before {
            opacity: 0;
        }
        
        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
        }
        
        .nav-link span {
            position: relative;
            z-index: 1;
        }
        
        /* Logout Button */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logout-btn {
            width: 100%;
            background: var(--logout-bg);
            border: none;
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
        }
        
        .logout-btn:hover {
            background: var(--logout-hover);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
        }
        
        .logout-btn i {
            font-size: 1.1rem;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: var(--transition);
        }
        
        .main-content.sidebar-collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        /* Top Navbar */
        .top-navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #64748b;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: var(--transition);
        }
        
        .sidebar-toggle:hover {
            background: #f1f5f9;
            color: #1e293b;
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .profile-section {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            background: #f8fafc;
            transition: var(--transition);
        }
        
        .profile-section:hover {
            background: #f1f5f9;
        }
        
        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .profile-info {
            display: flex;
            flex-direction: column;
        }
        
        .profile-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.9rem;
        }
        
        .profile-role {
            font-size: 0.8rem;
            color: #64748b;
        }
        
        /* Content Area */
        .content-area {
            padding: 2rem;
        }
        
        /* Responsive Design */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .content-area {
                padding: 1rem;
            }
        }
        
        /* Mobile Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
        
        .sidebar-overlay.show {
            display: block;
        }
        
        /* Collapsed Sidebar Styles */
        .sidebar.collapsed .logo-section span,
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .logout-btn span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        
        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.75rem;
        }
        
        .sidebar.collapsed .logout-btn {
            justify-content: center;
            padding: 0.75rem;
        }
        
        .sidebar.collapsed .logo-section {
            justify-content: center;
        }
        
        /* Tooltips for collapsed sidebar */
        .nav-link[data-bs-toggle="tooltip"] {
            position: relative;
        }
        
        /* Animation for smooth transitions */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <!-- Logo Section -->
        <div class="logo-section d-flex align-items-center">
            <img src="{{ asset('photos/final.png') }}" alt="EGMS Logo">
            <span class="fw-bold text-white ms-2">EGMS</span>
        </div>
        
        <!-- Navigation -->
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="#" class="nav-link active" data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('devices.index') }}" class="nav-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Manage Devices">
                    <i class="bi bi-hdd-network"></i>
                    <span>Manage Devices</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('analytics.index') }}" class="nav-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Analytics">
                    <i class="bi bi-graph-up"></i>
                    <span>Analytics</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Utilities">
                    <i class="bi bi-tools"></i>
                    <span>Utilities</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Settings">
                    <i class="bi bi-gear"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
        
        <!-- Logout Button -->
        <div class="sidebar-footer">
            <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="logout-btn" data-bs-toggle="tooltip" data-bs-placement="right" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="navbar-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">Energy Grid Monitoring System</h1>
            </div>
            
            <div class="navbar-right">
                <div class="profile-section">
                    <div class="profile-avatar">A</div>
                    <div class="profile-info">
                        <div class="profile-name">Admin</div>
                        <div class="profile-role">System Administrator</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar toggle functionality
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        // Desktop sidebar toggle (collapse/expand)
        sidebarToggle.addEventListener('click', function() {
            if (window.innerWidth >= 992) {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('sidebar-collapsed');
            } else {
                // Mobile sidebar toggle
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            }
        });
        
        // Close mobile sidebar when clicking overlay
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
        
        // Close mobile sidebar when clicking outside
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 992) {
                if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            }
        });
        
        // Set active navigation item based on current route
        function setActiveNavItem() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        }
        
        // Initialize active navigation
        setActiveNavItem();
        
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Add smooth scrolling for better UX
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Add fade-in animation to content
        document.addEventListener('DOMContentLoaded', function() {
            const contentArea = document.querySelector('.content-area');
            if (contentArea) {
                contentArea.classList.add('fade-in');
            }
        });
    </script>
</body>
</html>