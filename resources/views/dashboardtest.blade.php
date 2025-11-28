<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard-Testing</title>

    <!-- Fonts -->
    <link href="{{asset('assets/admin/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- SB Admin CSS -->
    <link href="{{asset('assets/admin/css/sb-admin-2.min.css')}}" rel="stylesheet">

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

        <!-- Enhanced Modern Dark Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f172a 100%); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">

            <!-- Enhanced Orange Dark Sidebar Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-between" href="/dashboardtest" style="padding: 24px 24px; background: rgba(255, 255, 255, 0.05); border-bottom: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border-radius: 0 0 16px 16px; margin: 0 12px 16px 12px;">
                <div class="sidebar-brand-icon d-flex align-items-center">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-right: 12px; box-shadow: 0 8px 25px rgba(249, 115, 22, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.1);">
                        <i class="fas fa-desktop" style="color: white; font-size: 22px; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);"></i>
                    </div>
                    <div style="color: white; font-weight: 800; font-size: 20px; font-family: 'Inter', sans-serif; letter-spacing: 0.5px; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);">EGMS</div>
                </div>
            </a>

            <!-- Enhanced Dark Divider -->
            <hr class="sidebar-divider my-0" style="border-color: rgba(255, 255, 255, 0.08); margin: 0 20px; border-width: 1px;">

            <!-- Enhanced Orange Nav Item - Dashboard -->
            <li class="nav-item active" style="margin: 8px 16px;">
                <a class="nav-link" href="#" style="padding: 16px 20px; border-radius: 8px; background: rgba(255, 255, 255, 0.05); border: none; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: none;">
                    <i class="fas fa-fw fa-tachometer-alt" style="font-size: 18px; margin-right: 14px; color: #f97316; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);"></i>
                    <span style="font-weight: 600; font-size: 14px; color: white; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);">Dashboard</span>
                </a>
            </li>

            <!-- Enhanced Dark Divider -->
            <hr class="sidebar-divider" style="border-color: rgba(255, 255, 255, 0.08); margin: 20px 20px; border-width: 1px;">

            <!-- Enhanced Orange Navigation Items -->
            <li class="nav-item" style="margin: 6px 16px;">
                <a class="nav-link" href="{{ route('devices.index') }}" style="padding: 16px 20px; border-radius: 8px; background: transparent; border: none; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); color: rgba(255, 255, 255, 0.9); font-weight: 500;">
                    <i class="fas fa-network-wired" style="font-size: 18px; margin-right: 14px; color: rgba(255, 255, 255, 0.7); text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);"></i>
                    <span style="font-size: 14px; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);">Manage Devices</span>
                </a>
            </li>

            <li class="nav-item" style="margin: 6px 16px;">
                <a class="nav-link" href="{{ route('analytics.index') }}" style="padding: 16px 20px; border-radius: 8px; background: transparent; border: none; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); color: rgba(255, 255, 255, 0.9); font-weight: 500;">
                    <i class="fas fa-fw fa-chart-area" style="font-size: 18px; margin-right: 14px; color: rgba(255, 255, 255, 0.7); text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);"></i>
                    <span style="font-size: 14px; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);">Analytics</span>
                </a>
            </li>

            <li class="nav-item" style="margin: 6px 16px;">
                <a class="nav-link" href="{{ route('settings.alerts') }}" style="padding: 16px 20px; border-radius: 8px; background: transparent; border: none; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); color: rgba(255, 255, 255, 0.9); font-weight: 500;">
                    <i class="fas fa-fw fa-cog" style="font-size: 18px; margin-right: 14px; color: rgba(255, 255, 255, 0.7); text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);"></i>
                    <span style="font-size: 14px; color: #ffffff !important; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3); font-weight: 500;">Alert Settings</span>
                </a>
            </li>

           

            <!-- Enhanced Dark Divider -->
            <hr class="sidebar-divider d-none d-md-block" style="border-color: rgba(255, 255, 255, 0.08); margin: 24px 20px; border-width: 1px;">

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%); min-height: 100vh;">

            <!-- Main Content -->
            <div id="content" style="background: transparent;">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border-bottom: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">


                  
                    <ul class="navbar-nav ml-auto">


                        <!-- Nav Item - Alerts -->
                  

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: white;">
                                <span class="mr-2 d-none d-lg-inline small" style="color: rgba(255, 255, 255, 0.9);">Administrator</span>
                                <img class="img-profile rounded-circle"
                                    src="{{asset('assets/admin/img/admin.png')}}" style="border: 2px solid rgba(255, 255, 255, 0.2);">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
                                
                               
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal" style="color: rgba(255, 255, 255, 0.9); background: transparent; transition: all 0.3s ease;">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2" style="color: rgba(255, 255, 255, 0.7);"></i>
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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
  <script src="{{asset('assets/admin/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/admin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/sb-admin-2.min.js')}}"></script>

    <!-- Page level plugins removed: Chart.js not used on this page -->

    <script>
        // Removed System Status and Recent Events auto-refresh scripts
    </script>
 <script>

async function fetchJSON(url) {
    const r = await fetch(url, {
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Content-Type": "application/json"
        }
    });
    if (!r.ok) throw new Error("HTTP " + r.status);
    return r.json();
}

// Status Badge
function getStatusBadge(device) {
    const status = device.display_status || device.status;
    const isOnline =
        status === "Active" ||
        status === "ON" ||
        status === "Online";

    return `
        <div class="status-badge ${isOnline ? "online" : "offline"}">
            <span class="status-indicator ${isOnline ? "online" : "offline"}"></span>
            ${isOnline ? "ONLINE" : "OFFLINE"}
        </div>
    `;
}

// MAIN FUNCTION – LOAD DEVICES
async function loadDevices() {

    const loadingState   = document.getElementById('deviceLoadingState');
    const errorState     = document.getElementById('deviceErrorState');
    const cardsContainer = document.getElementById('deviceCardsContainer');
    const emptyState     = document.getElementById('deviceEmptyState');
    const deviceCount    = document.getElementById('deviceCount');
    const lastUpdate     = document.getElementById('lastUpdate');

    loadingState.style.display = "block";
    errorState.style.display   = "none";
    cardsContainer.style.display = "none";
    emptyState.style.display     = "none";

    try {

        // 🔥 FIXED — use the REAL route
        const data = await fetchJSON("{{ route('admin.api.devices') }}");

        if (!data || !data.success) throw new Error("Invalid JSON");

        const devices = data.devices || [];
        deviceCount.textContent = `${devices.length} device${devices.length !== 1 ? "s" : ""}`;

        // Empty
        if (devices.length === 0) {
            loadingState.style.display = "none";
            emptyState.style.display   = "block";
            return;
        }

        // Build Cards – NOW INSIDE TRY BLOCK ✔
        cardsContainer.innerHTML = devices.map(device => `
            <div class="device-card compact">
                <div class="device-field device-name">${device.household_name}</div>

                <div class="device-field device-id">
                    <span>ID: ${device.device_id}</span>
                </div>

                <div class="device-field device-location">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>${device.barangay}</span>

                    <span class="device-meta-inline">
                        <span class="meta-divider">•</span>
                        <span class="device-last-seen">
                            <i class="fas fa-clock"></i>
                            ${device.last_seen_human}
                        </span>
                    </span>
                </div>

                <div class="device-field status">
                    ${getStatusBadge(device)}
                </div>
            </div>
        `).join("");

        loadingState.style.display = "none";
        cardsContainer.style.display = "block";
        lastUpdate.textContent = new Date().toLocaleTimeString();

    } catch (err) {
        console.error("Device loading error:", err);

        loadingState.style.display = "none";
        errorState.style.display   = "block";
    }
}


// Auto-refresh
let refreshInterval;
function startAutoRefresh() {
    if (refreshInterval) clearInterval(refreshInterval);
    loadDevices();
    refreshInterval = setInterval(loadDevices, 60000);
}
function stopAutoRefresh() {
    if (refreshInterval) clearInterval(refreshInterval);
}

document.addEventListener('DOMContentLoaded', startAutoRefresh);
window.addEventListener('beforeunload', stopAutoRefresh);

</script>



</body>

</html>