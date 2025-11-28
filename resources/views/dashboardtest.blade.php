<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Dashboard-Testing</title>

    <!-- Custom fonts -->
    <link href="{{ asset('assets/admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,400,600,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- SB Admin CSS (FIXED PATH) -->
    <link href="{{ asset('assets/admin/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        /* Fixed Sidebar Styles */
        #accordionSidebar.sidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            height: 100vh !important;
            max-height: 100vh !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            z-index: 1000 !important;
        }

        #wrapper {
            display: flex !important;
        }

        #content-wrapper {
            margin-left: 224px !important;
            width: calc(100% - 224px) !important;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Ensure body and html don't cause scroll issues */
        body {
            overflow-x: hidden;
        }

        html {
            overflow-x: hidden;
        }

        /* Sidebar scrollbar styling */
        #accordionSidebar::-webkit-scrollbar {
            width: 6px;
        }

        #accordionSidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        #accordionSidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        #accordionSidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            #accordionSidebar.sidebar {
                position: fixed !important;
                transform: translateX(-100%);
            }
            
            #accordionSidebar.sidebar.mobile-open {
                transform: translateX(0) !important;
            }
            
            #content-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }

        /* Collapsed sidebar adjustments */
        #accordionSidebar.collapsed ~ #content-wrapper {
            margin-left: 64px !important;
            width: calc(100% - 64px) !important;
        }

        @media (max-width: 768px) {
            #accordionSidebar.collapsed ~ #content-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }

         .navbar-logo {
        width: 45px;   
        height: 45px;  
        object-fit: contain; }

        /* Status Pills */
                .status-pill {
                    display: inline-block;
                    padding: 4px 10px;
                    font-size: 0.8rem;
                    font-weight: 600;
                    border-radius: 20px;
                    color: #fff;
                }

                .pill-on {
                    background-color: #28a745; /* Green for ON */
                }

                .pill-off {
                    background-color: #dc3545; /* Red for OFF */
                }

                /* Device Status Badges */
                .badge-success {
                    background-color: #28a745;
                    color: white;
                    padding: 0.375rem 0.75rem;
                    border-radius: 0.375rem;
                    font-size: 0.75rem;
                    font-weight: 500;
                }

                .badge-danger {
                    background-color: #dc3545;
                    color: white;
                    padding: 0.375rem 0.75rem;
                    border-radius: 0.375rem;
                    font-size: 0.75rem;
                    font-weight: 500;
                }

                /* Loading animation */
                .fa-spinner {
                    animation: spin 1s linear infinite;
                }

                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }

                /* Modern Dashboard Styles */
                @keyframes pulse {
                    0%, 100% { opacity: 1; }
                    50% { opacity: 0.5; }
                }

                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .card {
                    animation: fadeInUp 0.6s ease-out;
                }

                .card:hover {
                    transform: translateY(-4px);
                    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12) !important;
                }

                .chart-container {
                    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                    border-radius: 16px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                    padding: 2rem;
                    border: none;
                    transition: all 0.3s ease;
                }

                .chart-container.device-section-card {
                    padding: 16px 18px;
                    min-height: auto;
                    gap: 10px;
                }

                .chart-container:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
                }

                .chart-title {
                    font-size: 1.25rem;
                    font-weight: 700;
                    color: #1e293b;
                    margin-bottom: 1.5rem;
                    font-family: 'Inter', sans-serif;
                }

                .modern-section {
                    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                    border-radius: 16px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                    padding: 2rem;
                    margin-bottom: 2rem;
                    border: none;
                }

                .section-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-bottom: 0.75rem;
                    padding-bottom: 0.4rem;
                    border-bottom: 1px solid rgba(226, 232, 240, 0.35);
                }

                .section-title {
                    font-size: 1.25rem;
                    font-weight: 700;
                    color: #1e293b;
                    margin: 0;
                    font-family: 'Inter', sans-serif;
                }

                .section-subtitle {
                    font-size: 0.875rem;
                    color: #64748b;
                    margin: 0;
                }

                .modern-btn {
                    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
                    color: white;
                    border: none;
                    padding: 8px 16px;
                    border-radius: 8px;
                    font-weight: 600;
                    font-size: 0.875rem;
                    transition: all 0.3s ease;
                    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
                }

                .modern-btn:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
                    color: white;
                }

                .modern-btn-secondary {
                    background: linear-gradient(135deg, #64748b, #475569);
                    color: white;
                    border: none;
                    padding: 8px 16px;
                    border-radius: 8px;
                    font-weight: 600;
                    font-size: 0.875rem;
                    transition: all 0.3s ease;
                    box-shadow: 0 2px 8px rgba(100, 116, 139, 0.3);
                }

                .modern-btn-secondary:hover {
                    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.4);
                    color: white;
                }

                /* Device Cards Styles */
                .device-cards-container {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
                    gap: 12px;
                    padding-right: 0;
                    align-items: stretch;
                    max-width: 1100px;
                    margin: 0 auto;
                }

                .device-cards-container.condensed {
                    gap: 12px;
                }

                .device-card {
                    background: rgba(31, 35, 44, 0.7);
                    border: 1px solid rgba(100, 116, 139, 0.35);
                    border-radius: 14px;
                    padding: 12px 18px;
                    margin-bottom: 0;
                    transition: all 0.3s ease;
                    position: relative;
                    overflow: hidden;
                    min-height: 92px;
                    display: flex;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 14px;
                    box-shadow: 0 10px 24px rgba(2, 6, 23, 0.35);
                }

                .device-card::after {
                    content: '';
                    position: absolute;
                    inset: 0;
                    background: radial-gradient(circle at top left, rgba(59, 130, 246, 0.12), transparent 60%);
                    pointer-events: none;
                }

                .device-card.compact {
                    padding: 12px 18px;
                    min-height: 92px;
                }

                .device-card:hover {
                    box-shadow: 0 18px 32px rgba(2, 6, 23, 0.55);
                    border-color: rgba(59, 130, 246, 0.45);
                    transform: translateY(-2px);
                }

                .device-card:last-child { margin-bottom: 0; }

                .device-field {
                    flex: 1 1 140px;
                    min-width: 140px;
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    color: rgba(229, 231, 235, 0.78);
                    font-size: 0.8rem;
                }

                .device-field.device-name {
                    font-weight: 600;
                    font-size: 0.9rem;
                    color: #e2e8f0;
                }

                .device-field.device-id {
                    font-size: 0.78rem;
                    color: rgba(148, 163, 184, 0.9);
                }

                .device-field.device-location {
                    flex: 1 1 220px;
                    gap: 8px;
                }

                .device-field.status {
                    flex: 0 0 auto;
                    justify-content: flex-end;
                    margin-left: auto;
                }

                .device-field.status .status-chip {
                    margin-left: auto;
                }

                .device-meta-inline {
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    color: rgba(148, 163, 184, 0.85);
                    font-size: 0.72rem;
                }

                .device-meta-inline .meta-divider {
                    color: rgba(148, 163, 184, 0.45);
                }

                .device-meta-inline .meta-divider {
                    color: rgba(148, 163, 184, 0.45);
                }

                .status-indicator {
                    width: 10px;
                    height: 10px;
                    border-radius: 50%;
                    position: relative;
                    animation: pulse 2s infinite;
                }

                .status-indicator.online {
                    background-color: #28a745;
                    box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
                }

                .status-indicator.offline {
                    background-color: #dc3545;
                    box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
                }

                @keyframes pulse {
                    0% {
                        box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
                    }
                    70% {
                        box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
                    }
                    100% {
                        box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
                    }
                }

                .status-badge {
                    padding: 2px 7px;
                    border-radius: 16px;
                    font-size: 0.6rem;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 0.4px;
                }

                .status-badge.online {
                    background-color: #d4edda;
                    color: #155724;
                    border: 1px solid #c3e6cb;
                }

                .status-badge.offline {
                    background-color: #f8d7da;
                    color: #721c24;
                    border: 1px solid #f5c6cb;
                }

                .device-last-seen {
                    font-size: 0.72rem;
                    color: rgba(148, 163, 184, 0.85);
                    display: inline-flex;
                    align-items: center;
                    gap: 4px;
                }

                /* Custom scrollbar for device cards */
                .device-cards-container::-webkit-scrollbar {
                    width: 6px;
                }

                .device-cards-container::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 3px;
                }

                .device-cards-container::-webkit-scrollbar-thumb {
                    background: #c1c1c1;
                    border-radius: 3px;
                }

                .device-cards-container::-webkit-scrollbar-thumb:hover {
                    background: #a8a8a8;
                }

                /* Enhanced Metric Cards Styling */
                .metric-card {
                    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
                    border: none;
                    border-radius: 16px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                    transition: all 0.3s ease;
                    position: relative;
                    overflow: hidden;
                }

                .metric-card:hover {
                    transform: translateY(-4px);
                    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
                }

                .metric-card::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 4px;
                    height: 100%;
                    background: linear-gradient(180deg, var(--card-color) 0%, var(--card-color-light) 100%);
                }

                .metric-card.primary::before {
                    --card-color: #3B82F6;
                    --card-color-light: #60A5FA;
                }

                .metric-card.success::before {
                    --card-color: #10B981;
                    --card-color-light: #34D399;
                }

                .metric-card.danger::before {
                    --card-color: #EF4444;
                    --card-color-light: #F87171;
                }

                .metric-card.warning::before {
                    --card-color: #F59E0B;
                    --card-color-light: #FBBF24;
                }

                .metric-card-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    margin-bottom: 16px;
                }

                .metric-title {
                    font-size: 0.75rem;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    margin: 0;
                    color: var(--card-color);
                }

                .metric-icon {
                    width: 48px;
                    height: 48px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: linear-gradient(135deg, var(--card-color-light) 0%, var(--card-color) 100%);
                    color: white;
                    font-size: 1.25rem;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                }

                .metric-value {
                    font-size: 2.5rem;
                    font-weight: 800;
                    color: #1F2937;
                    margin: 8px 0;
                    line-height: 1;
                }

                .metric-description {
                    font-size: 0.875rem;
                    color: #6B7280;
                    font-weight: 500;
                    margin-top: 8px;
                }

                .metric-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 4px 12px;
                    background: rgba(0, 0, 0, 0.05);
                    border-radius: 20px;
                    font-size: 0.75rem;
                    font-weight: 600;
                    color: #6B7280;
                    margin-top: 12px;
                }

                .metric-badge i {
                    font-size: 0.625rem;
                }

                /* Status indicators */
                .status-indicator {
                    width: 12px;
                    height: 12px;
                    border-radius: 50%;
                    display: inline-block;
                    margin-right: 8px;
                    animation: pulse 2s infinite;
                }

                .status-indicator.online {
                    background: #10B981;
                    box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
                }

                .status-indicator.offline {
                    background: #EF4444;
                    box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
                }

                @keyframes pulse {
                    0% {
                        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
                    }
                    70% {
                        box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
                    }
                    100% {
                        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
                    }
                }

                /* Footer Copyright Centering */
                .copyright {
                    text-align: center !important;
                    width: 100% !important;
                }

                .copyright span {
                    display: block;
                    text-align: center;
                    width: 100%;
                }

        /* Enhanced Orange Sidebar Hover Effects */
        .sidebar .nav-link:hover {
            background: rgba(251, 146, 60, 0.1) !important;
            border: none !important;
            transform: translateX(3px);
            box-shadow: none !important;
        }

        .sidebar .nav-link:hover i {
            color: #f97316 !important;
            transform: scale(1.05);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3) !important;
        }

        .sidebar .nav-link:hover span {
            color: #f97316 !important;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3) !important;
        }

        /* Active state enhancement */
        .sidebar .nav-item.active .nav-link {
            background: rgba(251, 146, 60, 0.08) !important;
            border: none !important;
            box-shadow: none !important;
        }

        .sidebar .nav-item.active .nav-link i {
            color: #f97316 !important;
        }

        /* Sidebar brand hover effect - removed */

                /* Smooth transitions for all sidebar elements */
                .sidebar * {
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                }

                /* Enhanced dark backdrop blur effect */
                .sidebar {
                    backdrop-filter: blur(20px);
                    -webkit-backdrop-filter: blur(20px);
                    border-right: 1px solid rgba(255, 255, 255, 0.05);
                }

                /* Subtle animation for sidebar brand icon */
                .sidebar-brand-icon div {
                    animation: subtleFloat 3s ease-in-out infinite;
                }

                @keyframes subtleFloat {
                    0%, 100% { transform: translateY(0px); }
                    50% { transform: translateY(-2px); }
                }

                /* Enhanced dark divider styling */
                .sidebar-divider {
                    border-top: 1px solid rgba(255, 255, 255, 0.08);
                    box-shadow: 0 1px 0 rgba(0, 0, 0, 0.2);
                }

                /* Improved dark theme typography */
                .sidebar .nav-link {
                    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                    letter-spacing: 0.3px;
                    font-weight: 500;
                }

                /* Enhanced dark shadow for depth */
                .sidebar {
                    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), 0 2px 8px rgba(0, 0, 0, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.1);
                }

                /* Dark theme scrollbar for sidebar */
                .sidebar::-webkit-scrollbar {
                    width: 6px;
                }

                .sidebar::-webkit-scrollbar-track {
                    background: rgba(255, 255, 255, 0.05);
                    border-radius: 3px;
                }

                .sidebar::-webkit-scrollbar-thumb {
                    background: rgba(255, 255, 255, 0.2);
                    border-radius: 3px;
                }

                .sidebar::-webkit-scrollbar-thumb:hover {
                    background: rgba(255, 255, 255, 0.3);
                }

    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-between" href="/dashboardtest">
                <div class="sidebar-brand-icon">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #f97316, #ea580c); 
                    border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-desktop" style="color: white; font-size: 22px;"></i>
                    </div>
                </div>
                <div class="sidebar-brand-text mx-3">EGMS</div>
            </a>

            <!-- Enhanced Dark Divider -->
             <hr class="sidebar-divider my-0">

            <li class="nav-item active">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-tachometer-alt" style="color:#f97316;"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Enhanced Dark Divider -->
          <li class="nav-item">
                <a class="nav-link" href="{{ route('analytics.index') }}">
                    <i class="fas fa-chart-area"></i>
                    <span>Analytics</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('settings.alerts') }}">
                    <i class="fas fa-cog"></i>
                    <span>Alert Settings</span>
                </a>
            </li>

            <hr class="sidebar-divider d-none d-md-block">

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
       <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow">

                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" style="color:white;">
                                <span class="mr-2 d-none d-lg-inline text-white small">Administrator</span>
                                <img class="img-profile rounded-circle"
                                     src="{{ url('assets/admin/img/admin.png') }}">
                            </a>

                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Modern Dashboard Content -->
                <div class="container-fluid" style="background: transparent; padding: 1.5rem 1.25rem;">

                    

                    <!-- Modern Stats Grid -->
                    <div class="row mb-3">
                        <!-- Monthly Outages Card -->
                        <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
                            <div class="card h-100" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 14px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.1); transition: all 0.3s ease; position: relative; overflow: hidden; backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); min-height: 96px;">
                                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #3b82f6, #1d4ed8);"></div>
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-chart-line" style="color: white; font-size: 13px;"></i>
                                        </div>
                                        <div class="text-right">
                                            <div style="font-size: 1.15rem; font-weight: 700; color: white; line-height: 1; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);" id="monthlyOutages">
                                                <i class="fas fa-spinner fa-spin" style="display: none;"></i>
                                                <span class="count-value">--</span>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 style="color: rgba(255, 255, 255, 0.9); font-weight: 600; font-size: 0.875rem; margin-bottom: 8px; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);">Monthly Outages</h6>
                                    <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.75rem; margin: 0; display: flex; align-items: center;">
                                        <i class="fas fa-chart-line me-2"></i>
                                        Monthly outage statistics
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Online Devices Card -->
                        <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
                            <div class="card h-100" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 14px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.1); transition: all 0.3s ease; position: relative; overflow: hidden; backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); min-height: 96px;">
                                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #10b981, #059669);"></div>
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-wifi" style="color: white; font-size: 13px;"></i>
                                        </div>
                                        <div class="text-right">
                                            <div style="font-size: 1.15rem; font-weight: 700; color: white; line-height: 1; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);" id="onlineDevices">
                                                <i class="fas fa-spinner fa-spin" style="display: none;"></i>
                                                <span class="count-value">--</span>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 style="color: #64748b; font-weight: 600; font-size: 0.875rem; margin-bottom: 8px;">Online Devices</h6>
                                    <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.75rem; margin: 0; display: flex; align-items: center;">
                                        <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; margin-right: 8px; animation: pulse 2s infinite;"></div>
                                        Currently reporting
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Offline Devices Card -->
                        <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
                            <div class="card h-100" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 14px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.1); transition: all 0.3s ease; position: relative; overflow: hidden; backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); min-height: 96px;">
                                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #ef4444, #dc2626);"></div>
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-exclamation-triangle" style="color: white; font-size: 13px;"></i>
                                        </div>
                                        <div class="text-right">
                                            <div style="font-size: 1.15rem; font-weight: 700; color: white; line-height: 1; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);" id="offlineDevices">
                                                <i class="fas fa-spinner fa-spin" style="display: none;"></i>
                                                <span class="count-value">--</span>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 style="color: #64748b; font-weight: 600; font-size: 0.875rem; margin-bottom: 8px;">Offline Devices</h6>
                                    <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.75rem; margin: 0; display: flex; align-items: center;">
                                        <div style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%; margin-right: 8px; animation: pulse 2s infinite;"></div>
                                        Needs attention
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Today's Outages Card -->
                        <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
                            <div class="card h-100" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 14px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.1); transition: all 0.3s ease; position: relative; overflow: hidden; backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); min-height: 96px;">
                                <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #f59e0b, #d97706);"></div>
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-clock" style="color: white; font-size: 13px;"></i>
                                        </div>
                                        <div class="text-right">
                                            <div style="font-size: 1.15rem; font-weight: 700; color: white; line-height: 1; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);" id="todayOutages">
                                                <i class="fas fa-spinner fa-spin" style="display: none;"></i>
                                                <span class="count-value">--</span>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 style="color: #64748b; font-weight: 600; font-size: 0.875rem; margin-bottom: 8px;">Today's Outages</h6>
                                    <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.75rem; margin: 0; display: flex; align-items: center;">
                                        <i class="fas fa-clock me-2"></i>
                                        Off events (24h)
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Device Status - Full Width -->
                    <div class="row mb-4">
                        <!-- Device Status Cards -->
                        <div class="col-12 mb-3">
                            <div class="chart-container device-section-card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); padding: 16px 18px; display: flex; flex-direction: column; gap: 10px;">
                                <div class="section-header">
                                    <div>
                                        <h3 class="section-title" style="color: #e5e7eb; font-weight: 700; font-size: 1.15rem; line-height: 1.2; margin-bottom: 2px;">Device Status</h3>
                                     <!--   <p class="section-subtitle" style="color: rgba(229, 231, 235, 0.6); font-size: 0.8rem; margin: 0;">Real-time device monitoring</p>-->
                                    </div>
                                    <div class="d-flex align-items-center gap-2" style="margin-left: auto;">
                                        <span style="background: rgba(108, 120, 138, 0.2); color: #60a5fa; padding: 4px 12px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; border: 1px solid rgba(59, 130, 246, 0.3);" id="deviceCount">0 devices</span>
                                        <button class="modern-btn-secondary" onclick="loadDevices()" title="Refresh">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Loading State -->
                                <div id="deviceLoadingState" class="text-center py-5">
                                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                                        <i class="fas fa-spinner fa-spin" style="color: white; font-size: 20px;"></i>
                                    </div>
                                    <p style="color: #64748b; font-weight: 600; margin: 0;">Loading device status...</p>
                                </div>

                                <!-- Error State -->
                                <div id="deviceErrorState" class="text-center py-5" style="display: none;">
                                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                                        <i class="fas fa-exclamation-triangle" style="color: white; font-size: 20px;"></i>
                                    </div>
                                    <p style="color: #ef4444; font-weight: 600; margin-bottom: 1rem;">Unable to load device data</p>
                                    <button class="modern-btn" onclick="loadDevices()">
                                        <i class="fas fa-redo me-1"></i>Retry
                                    </button>
                                </div>

                                <!-- Device Cards Container -->
                                <div id="deviceCardsContainer" class="device-cards-container condensed" style="display: none;">
                                    <!-- Device cards will be dynamically inserted here -->
                                </div>

                                <!-- Empty State -->
                                <div id="deviceEmptyState" class="text-center py-5" style="display: none;">
                                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #94a3b8, #64748b); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                                        <i class="fas fa-network-wired" style="color: white; font-size: 20px;"></i>
                                    </div>
                                    <p style="color: #64748b; font-weight: 600; margin-bottom: 1rem;">No devices found</p>
                                    <a href="{{ route('devices.index') }}" class="modern-btn">
                                        <i class="fas fa-plus me-1"></i>Add Device
                                    </a>
                                </div>

                                        <!-- Footer -->
                                        <div class="mt-auto pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.1);">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small style="color: rgba(255, 255, 255, 0.7);">
                                                    <i class="fas fa-clock me-1"></i>
                                            Last updated: <span id="lastUpdate" style="color: rgba(255, 255, 255, 0.9);">Never</span>
                                                </small>
                                                <small style="color: rgba(255, 255, 255, 0.7);">
                                                    <i class="fas fa-sync-alt me-1"></i>
                                                    Auto-refresh: <span style="color: #10b981;">ON</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                    <!-- Removed System Status and Recent Events / Logs cards -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer removed -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">Select "Logout" below to end your session.</div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>

                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets/admin/vendor/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- jQuery Easing -->
    <script src="{{ asset('assets/admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- SB Admin JS -->
    <script src="{{ asset('assets/admin/js/sb-admin-2.min.js') }}"></script>


    <!-- Page level plugins removed: Chart.js not used on this page -->

    <script>
        // Removed System Status and Recent Events auto-refresh scripts
    </script>

                    <script>
                        // Device Status Functionality - Updated for devices table
                        async function fetchJSON(url) {
                            const r = await fetch(url, { 
                                headers: { 
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/json'
                                }
                            });
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.json();
                    }

                        function getStatusBadge(device) {
                            const status = device.display_status || device.status;
                            const isOnline = status === 'Active' || status === 'ON' || status === 'Online';
                            const statusClass = isOnline ? 'online' : 'offline';
                            const statusText = isOnline ? 'ONLINE' : 'OFFLINE';
                            
                            return `<div class="status-badge ${statusClass}">
                                <span class="status-indicator ${statusClass}"></span>
                                ${statusText}
                            </div>`;
                        }

                        function formatDeviceName(device) {
                            return device.household_name || 'Unknown Device';
                            }

                        function formatDeviceId(device) {
                            return device.device_id ? `ID: ${device.device_id}` : 'No ID';
                            }

                        function formatDeviceLocation(device) {
                            return device.barangay || 'Unknown Location';
                        }

                        function formatLastSeen(device) {
                            if (device.last_seen_human) {
                                return device.last_seen_human;
                            }
                            if (device.last_seen) {
                                const date = new Date(device.last_seen);
                                return date.toLocaleString();
                            }
                            return 'Never';
                        }

                        async function loadDevices() {
                            const loadingState = document.getElementById('deviceLoadingState');
                            const errorState = document.getElementById('deviceErrorState');
                            const cardsContainer = document.getElementById('deviceCardsContainer');
                            const emptyState = document.getElementById('deviceEmptyState');
                            const deviceCount = document.getElementById('deviceCount');
                            const lastUpdate = document.getElementById('lastUpdate');
                            
                            try {
                                // Show loading state
                                loadingState.style.display = 'block';
                                errorState.style.display = 'none';
                                cardsContainer.style.display = 'none';
                                emptyState.style.display = 'none';

                                const data = await fetchJSON('{{ route("api.devices") }}');
                                
                                if (!data.success) {
                                    throw new Error(data.message || 'Failed to fetch devices');
                                }

                                const devices = data.devices || [];
                                
                                // Update device count
                                deviceCount.textContent = `${devices.length} device${devices.length !== 1 ? 's' : ''}`;
                                
                                if (devices.length === 0) {
                                    loadingState.style.display = 'none';
                                    emptyState.style.display = 'block';
                                } else {
                                    // Create device cards
                                    const deviceCards = devices.map(device => `
                                        <div class="device-card compact">
                                            <div class="device-field device-name">
                                                ${formatDeviceName(device)}
                                            </div>
                                            <div class="device-field device-id">
                                                <span>ID: ${device.device_id || 'N/A'}</span>
                                            </div>
                                            <div class="device-field device-location">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <span>${formatDeviceLocation(device)}</span>
                                                <span class="device-meta-inline">
                                                    <span class="meta-divider">•</span>
                                                    <span class="device-last-seen">
                                                        <i class="fas fa-clock"></i>
                                                        ${formatLastSeen(device)}
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="device-field status">
                                                ${getStatusBadge(device)}
                                            </div>
                                        </div>
                                    `).join('');

                                    cardsContainer.innerHTML = deviceCards;
                                    
                                    // Show cards container
                                    loadingState.style.display = 'none';
                                    cardsContainer.style.display = 'block';
                                }

                                // Update last update time
                                lastUpdate.textContent = new Date().toLocaleTimeString();
                                
                            } catch (error) {
                                console.error('Device loading error:', error);
                                
                                // Show error state
                                loadingState.style.display = 'none';
                                errorState.style.display = 'block';
                                cardsContainer.style.display = 'none';
                                emptyState.style.display = 'none';
                            }
                        }

                        // Auto-refresh functionality
                        let refreshInterval;

                        function startAutoRefresh() {
                            // Clear existing interval
                            if (refreshInterval) {
                                clearInterval(refreshInterval);
                            }
                            
                            // Load devices immediately
                            loadDevices();
                            
                            // Set up auto-refresh every 60 seconds
                            refreshInterval = setInterval(loadDevices, 60000);
                        }

                        function stopAutoRefresh() {
                            if (refreshInterval) {
                                clearInterval(refreshInterval);
                                refreshInterval = null;
                            }
                        }

                        // Initialize when page loads
                        document.addEventListener('DOMContentLoaded', function() {
                            startAutoRefresh();
                        });

                        // Clean up on page unload
                        window.addEventListener('beforeunload', function() {
                            stopAutoRefresh();
                        });

                        /*
                         * Power Outages Chart Implementation
                         * NOTE: The corresponding chart HTML and Chart.js library
                         * are not included on this page anymore. To avoid runtime
                         * errors (which can break other dashboard scripts,
                         * especially in production on Railway), the chart
                         * functionality has been disabled. Re‑enable it only
                         * after restoring the markup and Chart.js include.
                         */

                        // Show export success message
                        function showExportSuccess() {
                            const toast = document.createElement('div');
                            toast.style.cssText = `
                                position: fixed;
                                top: 20px;
                                right: 20px;
                                background: linear-gradient(135deg, #10b981, #059669);
                                color: white;
                                padding: 12px 20px;
                                border-radius: 8px;
                                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
                                z-index: 10000;
                                font-weight: 600;
                                font-size: 14px;
                                display: flex;
                                align-items: center;
                                gap: 8px;
                            `;
                            toast.innerHTML = '<i class="fas fa-check-circle"></i> Chart exported successfully!';
                            document.body.appendChild(toast);
                            
                            setTimeout(() => {
                                toast.remove();
                            }, 3000);
                        }

                        // Show export error message
                        function showExportError() {
                            const toast = document.createElement('div');
                            toast.style.cssText = `
                                position: fixed;
                                top: 20px;
                                right: 20px;
                                background: linear-gradient(135deg, #ef4444, #dc2626);
                                color: white;
                                padding: 12px 20px;
                                border-radius: 8px;
                                box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
                                z-index: 10000;
                                font-weight: 600;
                                font-size: 14px;
                                display: flex;
                                align-items: center;
                                gap: 8px;
                            `;
                            toast.innerHTML = '<i class="fas fa-exclamation-circle"></i> Failed to export chart';
                            document.body.appendChild(toast);
                            
                            setTimeout(() => {
                                toast.remove();
                            }, 3000);
                        }

                        // Real-time Metric Cards Implementation
                        let metricCardsInterval = null;

                        // Fetch dashboard statistics
                        async function fetchDashboardStats() {
                            try {
                                const response = await fetch('{{ url('/dashboard/stats') }}', {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Content-Type': 'application/json'
                                    }
                                });

                                if (!response.ok) {
                                    throw new Error(`HTTP ${response.status}`);
                                }

                                const data = await response.json();

                                if (data.success) {
                                    return data.stats;
                                } else {
                                    throw new Error(data.message || 'Failed to fetch stats');
                                }
                            } catch (error) {
                                console.error('Error fetching dashboard stats:', error);
                                throw error;
                            }
                        }

                        // Show loading state for a specific card
                        function showCardLoading(cardId) {
                            const card = document.getElementById(cardId);
                            if (card) {
                                const spinner = card.querySelector('.fa-spinner');
                                const countValue = card.querySelector('.count-value');
                                
                                if (spinner) spinner.style.display = 'inline';
                                if (countValue) countValue.style.display = 'none';
                            }
                        }

                        // Hide loading state and show value
                        function hideCardLoading(cardId, value) {
                            const card = document.getElementById(cardId);
                            if (card) {
                                const spinner = card.querySelector('.fa-spinner');
                                const countValue = card.querySelector('.count-value');
                                
                                if (spinner) spinner.style.display = 'none';
                                if (countValue) {
                                    countValue.style.display = 'inline';
                                    countValue.textContent = value;
                                }
                            }
                        }

                        // Animate number change with smooth transition
                        function animateNumberChange(cardId, newValue) {
                            const card = document.getElementById(cardId);
                            if (card) {
                                const countValue = card.querySelector('.count-value');
                                if (countValue) {
                                    countValue.style.transition = 'all 0.3s ease-in-out';
                                    countValue.style.transform = 'scale(1.1)';
                                    
                                    setTimeout(() => {
                                        countValue.textContent = newValue;
                                        countValue.style.transform = 'scale(1)';
                                    }, 150);
                                }
                            }
                        }

                        // Update all metric cards
                        function updateMetricCards(stats) {
                            // Update Monthly Outages
                            animateNumberChange('monthlyOutages', stats.monthlyOutages);
                            
                            // Update Online Devices
                            animateNumberChange('onlineDevices', stats.onlineDevices);
                            
                            // Update Offline Devices
                            animateNumberChange('offlineDevices', stats.offlineDevices);
                            
                            // Update Today's Outages
                            animateNumberChange('todayOutages', stats.todayOutages);
                        }

                        // Load dashboard statistics
                        async function loadDashboardStats() {
                            // Show loading for all cards
                            showCardLoading('monthlyOutages');
                            showCardLoading('onlineDevices');
                            showCardLoading('offlineDevices');
                            showCardLoading('todayOutages');

                            try {
                                const stats = await fetchDashboardStats();
                                
                                // Update all cards with new data
                                updateMetricCards(stats);
                                
                                // Hide loading states
                                hideCardLoading('monthlyOutages', stats.monthlyOutages);
                                hideCardLoading('onlineDevices', stats.onlineDevices);
                                hideCardLoading('offlineDevices', stats.offlineDevices);
                                hideCardLoading('todayOutages', stats.todayOutages);
                                
                                console.log('Dashboard stats updated:', stats);
                                
                            } catch (error) {
                                console.error('Error loading dashboard stats:', error);
                                
                                // Show error state (keep loading spinners)
                                console.warn('Failed to update dashboard metrics. Retrying in 10 seconds...');
                            }
                        }

                        // Start auto-refresh for metric cards
                        function startMetricCardsAutoRefresh() {
                            // Load initial data
                            loadDashboardStats();
                            
                            // Set up auto-refresh every 10 seconds
                            metricCardsInterval = setInterval(loadDashboardStats, 10000);
                        }

                        // Stop auto-refresh for metric cards
                        function stopMetricCardsAutoRefresh() {
                            if (metricCardsInterval) {
                                clearInterval(metricCardsInterval);
                                metricCardsInterval = null;
                            }
                        }

                        // Initialize metric cards when page loads
                        document.addEventListener('DOMContentLoaded', function() {
                            startMetricCardsAutoRefresh();
                        });

                        // Clean up on page unload
                        window.addEventListener('beforeunload', function() {
                            stopMetricCardsAutoRefresh();
                        });

                    </script>


</body>

</html>