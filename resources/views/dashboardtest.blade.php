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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- SB Admin CSS -->
   <link rel="stylesheet" href="{{ asset('assets/admin/css/sb-admin-2.min.css') }}">

<style>

/* =============================
   FIXED SIDEBAR LAYOUT
============================= */
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

body, html {
    overflow-x: hidden;
}

/* Sidebar Scrollbar */
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

/* Mobile Sidebar */
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

/* Collapsed Sidebar */
#accordionSidebar.collapsed ~ #content-wrapper {
    margin-left: 64px !important;
    width: calc(100% - 64px) !important;
}

/* Branding */
.navbar-logo {
    width: 45px;
    height: 45px;
    object-fit: contain;
}

/* =============================
   STATUS BADGES + PILLS
============================= */
.status-pill {
    display: inline-block;
    padding: 4px 10px;
    font-size: 0.8rem;
    font-weight: 600;
    border-radius: 20px;
    color: #fff;
}
.pill-on { background-color: #28a745; }
.pill-off { background-color: #dc3545; }

.badge-success {
    background-color: #28a745;
    color: white;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
}
.badge-danger {
    background-color: #dc3545;
    color: white;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
}

/* =============================
   ANIMATIONS
============================= */
.fa-spinner { animation: spin 1s linear infinite; }
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* =============================
   METRIC CARDS (TOP 4 CARDS)
============================= */
.metric-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 18px 20px;
    transition: 0.3s ease;
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
    top: 0; left: 0;
    width: 4px; height: 100%;
}
.metric-card.primary::before { background: #3B82F6; }
.metric-card.success::before { background: #10B981; }
.metric-card.danger::before { background: #EF4444; }
.metric-card.warning::before { background: #F59E0B; }

.metric-value {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1F2937;
}

/* =============================
   DEVICE LIST SECTION
============================= */

/* FIXED — stacked like your perfect design */
.device-cards-container {
    display: flex !important;
    flex-direction: column !important;
    gap: 14px !important;
    width: 100% !important;
    max-width: 100% !important;
}

.device-card {
    background: rgba(31, 35, 44, 0.7);
    border: 1px solid rgba(100, 116, 139, 0.35);
    border-radius: 14px;
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    width: 100% !important;
    transition: 0.3s ease;
}

.device-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(2, 6, 23, 0.45);
}

.device-field {
    flex: 1 1 auto;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    color: #e5e7eb;
}

.device-field.device-name {
    font-size: 1rem;
    font-weight: 600;
}

.device-field.status {
    margin-left: auto !important;
    justify-content: flex-end !important;
}

/* Status badge */
.status-badge {
    padding: 3px 10px;
    border-radius: 14px;
    font-size: 0.7rem;
    text-transform: uppercase;
    font-weight: 700;
}
.status-badge.online {
    background: #d4edda;
    color: #155724;
}
.status-badge.offline {
    background: #f8d7da;
    color: #721c24;
}

/* Status Dot */
.status-indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    animation: pulse 2s infinite;
}
.status-indicator.online { background: #10B981; }
.status-indicator.offline { background: #EF4444; }

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

/* =============================
   SIDEBAR HOVER EFFECTS
============================= */
.sidebar .nav-link:hover {
    background: rgba(251, 146, 60, 0.1) !important;
    transform: translateX(3px);
}
.sidebar .nav-link:hover i,
.sidebar .nav-link:hover span {
    color: #f97316 !important;
}

/* Active menu state */
.sidebar .nav-item.active .nav-link {
    background: rgba(251, 146, 60, 0.08) !important;
}
.sidebar .nav-item.active .nav-link i {
    color: #f97316 !important;
}

/* Sidebar aesthetic */
.sidebar {
    backdrop-filter: blur(20px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
}

/* Center copyright */
.copyright {
    text-align: center !important;
    width: 100% !important;
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

    <!-- Monthly Outages -->
    <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
        <div class="card h-100 gradient-card">
            <div class="card-topline blue"></div>
            <div class="card-body p-2">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div class="icon-box blue"><i class="fas fa-chart-line"></i></div>
                    <div id="monthlyOutages" class="stat-number">
                        <span class="count-value">--</span>
                    </div>
                </div>
                <h6 class="stat-title">Monthly Outages</h6>
                <p class="stat-desc"><i class="fas fa-chart-line me-2"></i>Monthly outage statistics</p>
            </div>
        </div>
    </div>

    <!-- Online Devices -->
    <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
        <div class="card h-100 gradient-card">
            <div class="card-topline green"></div>
            <div class="card-body p-2">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div class="icon-box green"><i class="fas fa-wifi"></i></div>
                    <div id="onlineDevices" class="stat-number">
                        <span class="count-value">--</span>
                    </div>
                </div>
                <h6 class="stat-title">Online Devices</h6>
                <p class="stat-desc">
                    <div class="dot green"></div>Currently reporting
                </p>
            </div>
        </div>
    </div>

    <!-- Offline Devices -->
    <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
        <div class="card h-100 gradient-card">
            <div class="card-topline red"></div>
            <div class="card-body p-2">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div class="icon-box red"><i class="fas fa-exclamation-triangle"></i></div>
                    <div id="offlineDevices" class="stat-number">
                        <span class="count-value">--</span>
                    </div>
                </div>
                <h6 class="stat-title">Offline Devices</h6>
                <p class="stat-desc"><div class="dot red"></div>Needs attention</p>
            </div>
        </div>
    </div>

    <!-- Today's Outages -->
    <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
        <div class="card h-100 gradient-card">
            <div class="card-topline orange"></div>
            <div class="card-body p-2">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div class="icon-box orange"><i class="fas fa-clock"></i></div>
                    <div id="todayOutages" class="stat-number">
                        <span class="count-value">--</span>
                    </div>
                </div>
                <h6 class="stat-title">Today's Outages</h6>
                <p class="stat-desc"><i class="fas fa-clock me-2"></i>Off events (24h)</p>
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
/* -----------------------------------------------------------
   GLOBAL STATE
----------------------------------------------------------- */
let lastState = {};      
let offlineStart = {};
const DELAY = 5 * 60 * 1000; // 5 minutes

/* -----------------------------------------------------------
   FETCH HELPER
----------------------------------------------------------- */
async function fetchJSON(url) {
    try {
        const r = await fetch(url, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        });
        return r.json();
    } catch (e) {
        console.error("Fetch failed:", e);
        return { success: false };
    }
}

/* -----------------------------------------------------------
   STATUS FORMATTERS
----------------------------------------------------------- */
function getStatus(device) {
    let s = (device.status || "").toUpperCase();
    if (s === "ACTIVE") return "ON";
    if (s === "INACTIVE") return "OFF";
    return s;
}

/* -----------------------------------------------------------
   ALERTS
----------------------------------------------------------- */
function alertOffline(device) {
    Swal.fire({
        title: "Device Offline",
        html: `<strong>${device.household_name}</strong> has been offline for 5 minutes.`,
        icon: "warning",
        background: "#111827",
        color: "#fff",
        confirmButtonColor: "#f87171"
    });
}

function alertOnline(device) {
    Swal.fire({
        title: "Device Online",
        html: `<strong>${device.household_name}</strong> is back online.`,
        icon: "success",
        background: "#111827",
        color: "#fff",
        confirmButtonColor: "#4ade80"
    });
}

/* -----------------------------------------------------------
   LOAD DEVICE CARDS
----------------------------------------------------------- */
async function loadDevices() {
    const data = await fetchJSON("{{ route('api.devices') }}");

    if (!data.success) {
        document.getElementById("deviceLoadingState").style.display = "none";
        document.getElementById("deviceErrorState").style.display = "block";
        return;
    }

    const devices = data.devices;

    document.getElementById("deviceLoadingState").style.display = "none";
    document.getElementById("deviceErrorState").style.display = "none";
    document.getElementById("deviceEmptyState").style.display = devices.length === 0 ? "block" : "none";
    document.getElementById("deviceCardsContainer").style.display = devices.length > 0 ? "grid" : "none";

    // Render device cards
    document.getElementById("deviceCardsContainer").innerHTML = devices.map(device => `
        <div class="device-card compact">
            <div class="device-field device-name">${device.household_name}</div>
            <div class="device-field device-id">ID: ${device.device_id}</div>

            <div class="device-field device-location">
                <i class="fas fa-map-marker-alt"></i>
                <span>${device.barangay}</span>
                <span class="device-meta-inline">
                    <span class="device-last-seen">
                        <i class="fas fa-clock"></i> ${device.last_seen_human}
                    </span>
                </span>
            </div>

            <div class="device-field status">
                <span class="status-indicator ${getStatus(device) === 'ON' ? 'online' : 'offline'}"></span>
                <span>${getStatus(device)}</span>
            </div>
        </div>
    `).join("");

    document.getElementById("deviceCount").textContent = `${devices.length} devices`;
    document.getElementById("lastUpdate").textContent = new Date().toLocaleTimeString();

    /* --------------------------------------------
        STATUS CHANGE ALERTS
    -------------------------------------------- */
    devices.forEach(device => {
        const id = device.device_id;
        const newStatus = getStatus(device);

        if (!lastState[id]) {
            lastState[id] = newStatus;
            if (newStatus === "OFF") offlineStart[id] = Date.now();
            return;
        }

        const old = lastState[id];

        // Went OFFLINE
        if (old === "ON" && newStatus === "OFF") {
            offlineStart[id] = Date.now();
        }

        // Stayed OFFLINE for 5 min
        if (newStatus === "OFF" && Date.now() - offlineStart[id] >= DELAY && old !== "ALERTED") {
            alertOffline(device);
            lastState[id] = "ALERTED";
            return;
        }

        // BACK ONLINE
        if (old !== "ON" && newStatus === "ON") {
            alertOnline(device);
        }

        lastState[id] = newStatus;
    });
}

/* -----------------------------------------------------------
   LOAD SUMMARY CARDS (4 METRICS)
----------------------------------------------------------- */
async function loadDashboardStats() {
    const stats = await fetchJSON("{{ url('/admin/api/dashboard-stats') }}");

    if (!stats.success) return;

    document.querySelector("#monthlyOutages .count-value").textContent = stats.monthly;
    document.querySelector("#onlineDevices .count-value").textContent  = stats.online;
    document.querySelector("#offlineDevices .count-value").textContent = stats.offline;
    document.querySelector("#todayOutages .count-value").textContent   = stats.today;
}

/* -----------------------------------------------------------
   AUTO-INITIALIZE
----------------------------------------------------------- */
document.addEventListener("DOMContentLoaded", () => {
    loadDevices();
    loadDashboardStats();

    setInterval(loadDevices, 60000);
    setInterval(loadDashboardStats, 60000);
});
</script>

</body>

</html>