<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Dashboard</title>

    <!-- FIXED ASSET PATHS -->
    <link href="{{ asset('assets/admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
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

        body, html { overflow-x: hidden; }

        /* Sidebar Scrollbar */
        #accordionSidebar::-webkit-scrollbar {
            width: 6px;
        }
        #accordionSidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }
        #accordionSidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }
        #accordionSidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.3);
        }

        @media (max-width:768px) {
            #accordionSidebar.sidebar {
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
        @media (max-width:768px) {
            #accordionSidebar.collapsed ~ #content-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }

        .navbar-logo {
            width: 45px;
            height: 45px;
            object-fit: contain;
        }

        /* Status Pills */
        .status-pill {
            display:inline-block;
            padding:4px 10px;
            font-size:0.8rem;
            font-weight:600;
            border-radius:20px;
            color:#fff;
        }
        .pill-on { background:#28a745; }
        .pill-off { background:#dc3545; }

        /* Badges */
        .badge-success {
            background:#28a745;
            color:white;
            padding:0.375rem 0.75rem;
            border-radius:0.375rem;
            font-size:0.75rem;
        }
        .badge-danger {
            background:#dc3545;
            color:white;
            padding:0.375rem 0.75rem;
            border-radius:0.375rem;
            font-size:0.75rem;
        }

        /* Spinner */
        .fa-spinner { animation: spin 1s linear infinite; }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Animations */
        @keyframes pulse {
            0%,100% { opacity:1; }
            50% { opacity:0.5; }
        }

        @keyframes fadeInUp {
            from { opacity:0; transform:translateY(20px); }
            to { opacity:1; transform:translateY(0); }
        }

        .card { animation: fadeInUp 0.6s ease-out; }
        .card:hover {
            transform:translateY(-4px);
            box-shadow:0 8px 30px rgba(0,0,0,0.12) !important;
        }

        /* Sidebar Hover Fixes */
        .sidebar .nav-link:hover {
            background: rgba(251, 146, 60, 0.1) !important;
            transform: translateX(3px);
        }
        .sidebar .nav-link:hover i,
        .sidebar .nav-link:hover span {
            color:#f97316 !important;
        }

        .sidebar .nav-item.active .nav-link {
            background:rgba(251,146,60,0.08) !important;
        }
        .sidebar .nav-item.active .nav-link i {
            color:#f97316 !important;
        }

        .sidebar {
            backdrop-filter:blur(20px);
            -webkit-backdrop-filter:blur(20px);
            border-right:1px solid rgba(255,255,255,0.05);
            box-shadow:
                0 8px 32px rgba(0,0,0,0.3),
                0 2px 8px rgba(0,0,0,0.2),
                inset 0 1px 0 rgba(255,255,255,0.1);
        }

    </style>

</head>

/a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('backup_recovery.index') }}">
                    <i class="fas fa-wrench"></i>
                    <span>Backup & Recovery</span>
                </a>
            </li>

        </ul>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow">

                <ul class="navbar-nav ml-auto">

                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-toggle="dropdown">
                            <span class="mr-2 d-none d-lg-inline small">Administrator</span>
                            <img class="img-profile rounded-circle" 
                                 src="{{ asset('assets/admin/img/admin.png') }}">
                        </a>

                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i> Logout
                            </a>
                        </div>
                    </li>

                </ul>
            </nav>
            <!-- End Topbar -->

            <!-- MAIN CONTENT -->
            <div class="container-fluid">

                <!-- Modern Stats Grid -->
                <div class="row mb-3">

                    <!-- Monthly Outages Card -->
                    <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
                        <div class="card h-100"
                             style="background: linear-gradient(135deg,#1e293b,#334155); 
                                    border:1px solid rgba(255,255,255,0.1);
                                    border-radius:14px; 
                                    box-shadow:0 8px 32px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.1);
                                    overflow:hidden;">
                            <div style="position:absolute; top:0; left:0; right:0; height:4px; background:linear-gradient(90deg,#3b82f6,#1d4ed8);"></div>

                            <div class="card-body p-2">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div style="width:32px;height:32px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);
                                                border-radius:10px; display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-chart-line" style="color:white;font-size:13px;"></i>
                                    </div>

                                    <div class="text-right">
                                        <div id="monthlyOutages"
                                             style="font-size:1.15rem;font-weight:700;color:white;line-height:1;">
                                            <i class="fas fa-spinner fa-spin" style="display:none;"></i>
                                            <span class="count-value">--</span>
                                        </div>
                                    </div>
                                </div>

                                <h6 style="color:#e2e8f0;font-weight:600;font-size:0.875rem;margin-bottom:8px;">
                                    Monthly Outages
                                </h6>

                                <p style="color:rgba(255,255,255,0.7);font-size:0.75rem;margin:0;">
                                    <i class="fas fa-chart-line me-2"></i>Monthly outage statistics
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Online Devices Card -->
                    <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
                        <div class="card h-100"
                             style="background:linear-gradient(135deg,#1e293b,#334155);
                                    border:1px solid rgba(255,255,255,0.1);
                                    border-radius:14px; 
                                    box-shadow:0 8px 32px rgba(0,0,0,0.3);">
                            <div style="position:absolute; top:0; left:0; right:0; height:4px;
                                        background:linear-gradient(90deg,#10b981,#059669);">
                            </div>

                            <div class="card-body p-2">
                                <div class="d-flex align-items-center justify-content-between mb-1">

                                    <div style="width:32px;height:32px;background:linear-gradient(135deg,#10b981,#059669);
                                                border-radius:10px; display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-wifi" style="color:white;font-size:13px;"></i>
                                    </div>

                                    <div class="text-right">
                                        <div id="onlineDevices"
                                             style="font-size:1.15rem;font-weight:700;color:white;">
                                            <i class="fas fa-spinner fa-spin" style="display:none;"></i>
                                            <span class="count-value">--</span>
                                        </div>
                                    </div>

                                </div>

                                <h6 style="color:#64748b;font-weight:600;font-size:0.875rem;margin-bottom:8px;">
                                    Online Devices
                                </h6>

                                <p style="color:rgba(255,255,255,0.7);font-size:0.75rem;margin:0;">
                                    <div style="width:8px;height:8px;background:#10b981;border-radius:50%;
                                                margin-right:8px; display:inline-block; animation:pulse 2s infinite;">
                                    </div>
                                    Currently reporting
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Offline Devices Card -->
                    <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
                        <div class="card h-100"
                             style="background:linear-gradient(135deg,#1e293b,#334155);
                                    border:1px solid rgba(255,255,255,0.1);
                                    border-radius:14px;">
                            <div style="position:absolute;top:0;left:0;right:0;height:4px;
                                        background:linear-gradient(90deg,#ef4444,#dc2626);"></div>

                            <div class="card-body p-2">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div style="width:32px;height:32px;background:linear-gradient(135deg,#ef4444,#dc2626);
                                                border-radius:10px; display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-exclamation-triangle" style="color:white;font-size:13px;"></i>
                                    </div>

                                    <div class="text-right">
                                        <div id="offlineDevices"
                                             style="font-size:1.15rem;font-weight:700;color:white;">
                                            <i class="fas fa-spinner fa-spin" style="display:none;"></i>
                                            <span class="count-value">--</span>
                                        </div>
                                    </div>
                                </div>

                                <h6 style="color:#64748b;font-weight:600;font-size:0.875rem;margin-bottom:8px;">
                                    Offline Devices
                                </h6>

                                <p style="color:rgba(255,255,255,0.7);font-size:0.75rem;margin:0;">
                                    <div style="width:8px;height:8px;background:#ef4444;border-radius:50%;
                                                margin-right:8px; display:inline-block; animation:pulse 2s infinite;">
                                    </div>
                                    Needs attention
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Outages Card -->
                    <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
                        <div class="card h-100"
                             style="background:linear-gradient(135deg,#1e293b,#334155);
                                    border:1px solid rgba(255,255,255,0.1);
                                    border-radius:14px;">
                            <div style="position:absolute;top:0;left:0;right:0;height:4px;
                                        background:linear-gradient(90deg,#f59e0b,#d97706);"></div>

                            <div class="card-body p-2">
                                <div class="d-flex align-items-center justify-content-between mb-1">

                                    <div style="width:32px;height:32px;background:linear-gradient(135deg,#f59e0b,#d97706);
                                                border-radius:10px; display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-clock" style="color:white;font-size:13px;"></i>
                                    </div>

                                    <div class="text-right">
                                        <div id="todayOutages"
                                             style="font-size:1.15rem;font-weight:700;color:white;">
                                            <i class="fas fa-spinner fa-spin" style="display:none;"></i>
                                            <span class="count-value">--</span>
                                        </div>
                                    </div>

                                </div>

                                <h6 style="color:#64748b;font-weight:600;font-size:0.875rem;margin-bottom:8px;">
                                    Today's Outages
                                </h6>

                                <p style="color:rgba(255,255,255,0.7);font-size:0.75rem;margin:0;">
                                    <i class="fas fa-clock me-2"></i>
                                    Off events (24h)
                                </p>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Device Status Section -->
                <div class="row mb-4">
                    <div class="col-12 mb-3">

                        <div class="chart-container device-section-card"
                             style="background:linear-gradient(135deg,#1e293b,#334155);
                                    border:1px solid rgba(255,255,255,0.1);
                                    border-radius:16px;
                                    padding:16px 18px;">

                            <div class="section-header">

                                <div>
                                    <h3 class="section-title" style="color:#e5e7eb;
                                                                   font-weight:700;font-size:1.15rem;">
                                        Device Status
                                    </h3>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <span id="deviceCount"
                                          style="background:rgba(108,120,138,0.2);
                                                 color:#60a5fa;padding:4px 12px;
                                                 border-radius:12px;
                                                 border:1px solid rgba(59,130,246,0.3);
                                                 font-size:0.75rem;font-weight:600;">
                                        0 devices
                                    </span>

                                    <button class="modern-btn-secondary" onclick="loadDevices()">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>

                            </div>

                            <!-- Loading State -->
                            <div id="deviceLoadingState" class="text-center py-5">
                                <div style="width:48px;height:48px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);
                                            border-radius:50%;display:flex;align-items:center;justify-content:center;
                                            margin:0 auto 1rem;">
                                    <i class="fas fa-spinner fa-spin" style="color:white;font-size:20px;"></i>
                                </div>
                                <p style="color:#64748b;font-weight:600;">Loading device status...</p>
                            </div>

                            <!-- Error State -->
                            <div id="deviceErrorState" class="text-center py-5" style="display:none;">
                                <div style="width:48px;height:48px;background:linear-gradient(135deg,#ef4444,#dc2626);
                                            border-radius:50%;display:flex;align-items:center;justify-content:center;
                                            margin:0 auto 1rem;">
                                    <i class="fas fa-exclamation-triangle" style="color:white;font-size:20px;"></i>
                                </div>
                                <p style="color:#ef4444;font-weight:600;">Unable to load device data</p>
                                <button class="modern-btn" onclick="loadDevices()">
                                    <i class="fas fa-redo me-1"></i> Retry
                                </button>
                            </div>

                            <!-- Device Cards -->
                            <div id="deviceCardsContainer" class="device-cards-container condensed"
                                 style="display:none;"></div>

                            <!-- Empty State -->
                            <div id="deviceEmptyState" class="text-center py-5" style="display:none;">
                                <div style="width:48px;height:48px;background:linear-gradient(135deg,#94a3b8,#64748b);
                                            border-radius:50%;display:flex;align-items:center;justify-content:center;
                                            margin:0 auto 1rem;">
                                    <i class="fas fa-network-wired" style="color:white;font-size:20px;"></i>
                                </div>
                                <p style="color:#64748b;font-weight:600;">No devices found</p>
                                <a href="{{ route('devices.index') }}" class="modern-btn">
                                    <i class="fas fa-plus me-1"></i> Add Device
                                </a>
                            </div>

                            <!-- Footer -->
                            <div class="mt-auto pt-3"
                                 style="border-top:1px solid rgba(255,255,255,0.1);">

                                <div class="d-flex justify-content-between">
                                    <small style="color:rgba(255,255,255,0.7);">
                                        <i class="fas fa-clock me-1"></i>
                                        Last updated:
                                        <span id="lastUpdate" style="color:white;">Never</span>
                                    </small>

                                    <small style="color:rgba(255,255,255,0.7);">
                                        <i class="fas fa-sync-alt me-1"></i>
                                        Auto-refresh:
                                        <span style="color:#10b981;">ON</span>
                                    </small>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>

            </div>
            <!-- End of Main Content -->

            <!-- Footer removed -->

          </div>
        <!-- END CONTENT WRAPPER -->

    </div>
    <!-- END WRAPPER -->

    <!-- Logout Modal -->
       <div class="modal fade" id="logoutModal">
        <div class="modal-dialog">
            <div class="modal-content" style="background:#1e293b;color:white;">
                <div class="modal-header">
                    <h5 class="modal-title">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" style="color:white;">×</button>
                </div>
                <div class="modal-body">
                    Select "Logout" to end your session.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.logout') }}" method="POST">@csrf
                        <button class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FIXED JS PATHS -->
    <script src="{{ asset('assets/admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
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

                        // Power Outages Chart Implementation
                        let powerOutagesChart = null;
                        let chartUpdateInterval = null;

                        // Fetch power outages data
                        async function fetchPowerOutages() {
                            try {
                                const response = await fetch('/api/power-outages', {
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
                                    return data;
                                } else {
                                    throw new Error(data.message || 'Failed to fetch data');
                                }
                            } catch (error) {
                                console.error('Error fetching power outages:', error);
                                throw error;
                            }
                        }

                        // Create power outages chart
                        function createPowerOutagesChart(labels, data) {
                            const ctx = document.getElementById('powerOutagesChart').getContext('2d');
                            
                            if (powerOutagesChart) {
                                powerOutagesChart.destroy();
                            }

                            // Create gradient for the chart
                            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                            gradient.addColorStop(0, 'rgba(0, 123, 255, 0.3)');
                            gradient.addColorStop(0.5, 'rgba(0, 123, 255, 0.1)');
                            gradient.addColorStop(1, 'rgba(0, 123, 255, 0.05)');

                            powerOutagesChart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Power Outages',
                                        data: data,
                                        borderColor: '#007bff',
                                        backgroundColor: gradient,
                                        borderWidth: 4,
                                        fill: true,
                                        tension: 0.4,
                                        pointBackgroundColor: '#ffffff',
                                        pointBorderColor: '#007bff',
                                        pointBorderWidth: 3,
                                        pointRadius: 8,
                                        pointHoverRadius: 12,
                                        pointHoverBackgroundColor: '#0056b3',
                                        pointHoverBorderColor: '#ffffff',
                                        pointHoverBorderWidth: 4,
                                        pointStyle: 'circle',
                                        pointHitRadius: 20,
                                        // Add shadow effect
                                        shadowOffsetX: 0,
                                        shadowOffsetY: 4,
                                        shadowBlur: 8,
                                        shadowColor: 'rgba(0, 123, 255, 0.3)'
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    animation: {
                                        duration: 2000,
                                        easing: 'easeInOutQuart',
                                        delay: (context) => {
                                            return context.dataIndex * 100;
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        tooltip: {
                                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                            titleColor: '#ffffff',
                                            bodyColor: '#ffffff',
                                            borderColor: '#3b82f6',
                                            borderWidth: 2,
                                            cornerRadius: 12,
                                            displayColors: false,
                                            titleFont: {
                                                size: 14,
                                                weight: '600'
                                            },
                                            bodyFont: {
                                                size: 13,
                                                weight: '500'
                                            },
                                            padding: 12,
                                            callbacks: {
                                                title: function(context) {
                                                    return `📊 ${context[0].label}`;
                                                },
                                                label: function(context) {
                                                    return `Outages: ${context.parsed.y}`;
                                                }
                                            }
                                        }
                                    },
                                    scales: {
                                        x: {
                                            grid: {
                                                display: true,
                                                color: 'rgba(226, 232, 240, 0.5)',
                                                drawBorder: false,
                                                drawOnChartArea: true,
                                                drawTicks: false
                                            },
                                            ticks: {
                                                color: '#64748b',
                                                font: {
                                                    size: 12,
                                                    weight: '600',
                                                    family: "'Inter', sans-serif"
                                                },
                                                padding: 8
                                            },
                                            border: {
                                                display: false
                                            }
                                        },
                                        y: {
                                            beginAtZero: true,
                                            grid: {
                                                color: 'rgba(226, 232, 240, 0.5)',
                                                drawBorder: false,
                                                drawOnChartArea: true,
                                                drawTicks: false
                                            },
                                            ticks: {
                                                color: '#64748b',
                                                font: {
                                                    size: 12,
                                                    weight: '600',
                                                    family: "'Inter', sans-serif"
                                                },
                                                padding: 8,
                                                stepSize: 1,
                                                callback: function(value) {
                                                    return value;
                                                }
                                            },
                                            border: {
                                                display: false
                                            }
                                        }
                                    },
                                    interaction: {
                                        intersect: false,
                                        mode: 'index'
                                    },
                                    elements: {
                                        point: {
                                            hoverBackgroundColor: '#1d4ed8',
                                            hoverBorderColor: '#ffffff',
                                            hoverBorderWidth: 4
                                        },
                                        line: {
                                            borderCapStyle: 'round',
                                            borderJoinStyle: 'round'
                                        }
                                    },
                                    layout: {
                                        padding: {
                                            top: 20,
                                            bottom: 20,
                                            left: 20,
                                            right: 20
                                        }
                                    }
                                }
                            });
                        }

                        // Update chart with new data
                        function updatePowerOutagesChart(labels, data) {
                            if (powerOutagesChart) {
                                powerOutagesChart.data.labels = labels;
                                powerOutagesChart.data.datasets[0].data = data;
                                powerOutagesChart.update('active');
                            }
                        }

                        // Show loading state
                        function showChartLoading() {
                            document.getElementById('chartLoading').style.display = 'block';
                            document.getElementById('chartError').style.display = 'none';
                            document.getElementById('chartContainer').style.display = 'none';
                        }

                        // Show error state
                        function showChartError() {
                            document.getElementById('chartLoading').style.display = 'none';
                            document.getElementById('chartError').style.display = 'block';
                            document.getElementById('chartContainer').style.display = 'none';
                        }

                        // Show chart
                        function showChart() {
                            document.getElementById('chartLoading').style.display = 'none';
                            document.getElementById('chartError').style.display = 'none';
                            document.getElementById('chartNoData').style.display = 'none';
                            document.getElementById('chartContainer').style.display = 'block';
                        }

                        // Show no data message
                        function showNoDataMessage() {
                            document.getElementById('chartLoading').style.display = 'none';
                            document.getElementById('chartError').style.display = 'none';
                            document.getElementById('chartContainer').style.display = 'none';
                            document.getElementById('chartNoData').style.display = 'block';
                        }

                        // Load power outages data
                        async function loadPowerOutagesData() {
                            showChartLoading();
                            
                            try {
                                const data = await fetchPowerOutages();
                                
                                // Check if there's any data
                                const hasData = data.data && data.data.some(value => value > 0);
                                
                                if (!hasData) {
                                    showNoDataMessage();
                                    return;
                                }
                                
                                if (powerOutagesChart) {
                                    updatePowerOutagesChart(data.labels, data.data);
                                } else {
                                    createPowerOutagesChart(data.labels, data.data);
                                }
                                
                                showChart();
                            } catch (error) {
                                console.error('Error loading power outages data:', error);
                                showChartError();
                            }
                        }

                        // Start auto-refresh for power outages chart
                        function startPowerOutagesAutoRefresh() {
                            // Load initial data
                            loadPowerOutagesData();
                            
                            // Set up auto-refresh every 10 seconds
                            chartUpdateInterval = setInterval(loadPowerOutagesData, 10000);
                        }

                        // Stop auto-refresh
                        function stopPowerOutagesAutoRefresh() {
                            if (chartUpdateInterval) {
                                clearInterval(chartUpdateInterval);
                                chartUpdateInterval = null;
                            }
                        }

                        // Initialize power outages chart when page loads
                        document.addEventListener('DOMContentLoaded', function() {
                            startPowerOutagesAutoRefresh();
                        });

                        // Clean up on page unload
                        window.addEventListener('beforeunload', function() {
                            stopPowerOutagesAutoRefresh();
                        });

                        // Add keyboard support for fullscreen
                        document.addEventListener('keydown', function(event) {
                            if (event.key === 'Escape' && document.fullscreenElement) {
                                fullscreenChart();
                            }
                        });

                        // Modern button functions
                        function refreshChart() {
                            // Clear existing chart
                            if (powerOutagesChart) {
                                powerOutagesChart.destroy();
                                powerOutagesChart = null;
                            }
                            // Reload data
                            loadPowerOutagesData();
                        }

                        function exportChart() {
                            if (powerOutagesChart) {
                                try {
                                    // Get the canvas element
                                    const canvas = document.getElementById('powerOutagesChart');
                                    
                                    // Create a temporary canvas with higher resolution for better quality
                                    const tempCanvas = document.createElement('canvas');
                                    const tempCtx = tempCanvas.getContext('2d');
                                    const scale = 2; // Higher resolution
                                    
                                    tempCanvas.width = canvas.width * scale;
                                    tempCanvas.height = canvas.height * scale;
                                    
                                    // Scale the context for higher resolution
                                    tempCtx.scale(scale, scale);
                                    
                                    // Draw the original chart on the temporary canvas
                                    tempCtx.drawImage(canvas, 0, 0);
                                    
                                    // Convert to PNG and download
                                    const url = tempCanvas.toDataURL('image/png', 1.0);
                                    const link = document.createElement('a');
                                    link.download = `power-outages-chart-${new Date().toISOString().split('T')[0]}.png`;
                                    link.href = url;
                                    document.body.appendChild(link);
                                    link.click();
                                    document.body.removeChild(link);
                                    
                                    // Show success message
                                    showExportSuccess();
                                } catch (error) {
                                    console.error('Error exporting chart:', error);
                                    showExportError();
                                }
                            } else {
                                showExportError();
                            }
                        }

                        function fullscreenChart() {
                            const chartContainer = document.getElementById('chartContainer');
                            const exitBtn = document.getElementById('fullscreenExitBtn');
                            
                            if (!document.fullscreenElement) {
                                // Enter fullscreen
                                if (chartContainer.requestFullscreen) {
                                    chartContainer.requestFullscreen();
                                } else if (chartContainer.webkitRequestFullscreen) {
                                    chartContainer.webkitRequestFullscreen();
                                } else if (chartContainer.msRequestFullscreen) {
                                    chartContainer.msRequestFullscreen();
                                } else if (chartContainer.mozRequestFullScreen) {
                                    chartContainer.mozRequestFullScreen();
                                }
                                
                                // Add fullscreen styles
                                chartContainer.style.height = '100vh';
                                chartContainer.style.width = '100vw';
                                chartContainer.style.position = 'fixed';
                                chartContainer.style.top = '0';
                                chartContainer.style.left = '0';
                                chartContainer.style.zIndex = '9999';
                                chartContainer.style.background = 'white';
                                chartContainer.style.padding = '2rem';
                                chartContainer.style.borderRadius = '0';
                                
                                // Show exit button
                                exitBtn.style.display = 'block';
                                
                                // Resize chart for fullscreen
                                setTimeout(() => {
                                    if (powerOutagesChart) {
                                        powerOutagesChart.resize();
                                    }
                                }, 100);
                                
                            } else {
                                // Exit fullscreen
                                if (document.exitFullscreen) {
                                    document.exitFullscreen();
                                } else if (document.webkitExitFullscreen) {
                                    document.webkitExitFullscreen();
                                } else if (document.msExitFullscreen) {
                                    document.msExitFullscreen();
                                } else if (document.mozCancelFullScreen) {
                                    document.mozCancelFullScreen();
                                }
                                
                                // Reset styles
                                chartContainer.style.height = '400px';
                                chartContainer.style.width = 'auto';
                                chartContainer.style.position = 'relative';
                                chartContainer.style.top = 'auto';
                                chartContainer.style.left = 'auto';
                                chartContainer.style.zIndex = 'auto';
                                chartContainer.style.background = 'linear-gradient(135deg, #f8fafc 0%, #ffffff 100%)';
                                chartContainer.style.padding = '20px';
                                chartContainer.style.borderRadius = '12px';
                                
                                // Hide exit button
                                exitBtn.style.display = 'none';
                                
                                // Resize chart back to normal
                                setTimeout(() => {
                                    if (powerOutagesChart) {
                                        powerOutagesChart.resize();
                                    }
                                }, 100);
                            }
                        }

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
                                const response = await fetch('/dashboard/stats', {
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