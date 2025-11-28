<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard</title>

    <!-- Proper Railway-safe asset paths -->
    <link href="{{ asset('assets/admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- CUSTOM MODERN DASHBOARD CSS (PART 5 will contain the full CSS) -->
    <style>
        body {
            background: linear-gradient(135deg,#0f172a,#1e293b,#334155);
            min-height: 100vh;
            font-family: 'Nunito', sans-serif;
            overflow-x: hidden;
        }

        /* SIDEBAR */
        #accordionSidebar {
            width: 240px;
            background: linear-gradient(135deg,#1a1a2e,#16213e,#0f172a);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            padding-top: 20px;
            z-index: 999;
            border-right: 1px solid rgba(255,255,255,0.05);
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }

        #content-wrapper {
            margin-left: 240px;
            padding: 20px;
        }

        .sidebar-brand-icon {
            display:flex;
            align-items:center;
        }

        .sidebar-brand-icon div {
            width:48px;
            height:48px;
            background:linear-gradient(135deg,#f97316,#ea580c);
            border-radius:14px;
            display:flex;
            align-items:center;
            justify-content:center;
            margin-right:12px;
        }

        .sidebar-brand-text {
            color:white;
            font-size:20px;
            font-weight:800;
        }

        .nav-link {
            color:#cbd5e1 !important;
            font-weight:600;
            padding: 12px 20px !important;
        }

        .nav-link:hover {
            background: rgba(251,146,60,0.15) !important;
            color:#f97316 !important;
        }

        .nav-item.active > .nav-link {
            background: rgba(251,146,60,0.10) !important;
            color:#f97316 !important;
        }
    </style>
</head>

<body id="page-top">

<div id="wrapper">

    <!-- SIDEBAR -->
    <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Logo + Branding -->
        <a class="sidebar-brand d-flex align-items-center" href="/dashboard" style="padding: 0 20px 20px 20px;">
            <div class="sidebar-brand-icon">
                <div>
                    <i class="fas fa-desktop" style="color:white;font-size:22px;"></i>
                </div>
            </div>
            <div class="sidebar-brand-text">EGMS</div>
        </a>

        <hr class="sidebar-divider">

        <!-- Dashboard -->
        <li class="nav-item active">
            <a class="nav-link" href="/dashboard">
                <i class="fas fa-fw fa-tachometer-alt" style="color:#f97316;"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <!-- Manage Devices -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('devices.index') }}">
                <i class="fas fa-network-wired"></i>
                <span>Manage Devices</span>
            </a>
        </li>

        <!-- Analytics -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('analytics.index') }}">
                <i class="fas fa-chart-area"></i>
                <span>Analytics</span>
            </a>
        </li>

        <!-- Alert Settings -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('settings.alerts') }}">
                <i class="fas fa-cog"></i>
                <span>Alert Settings</span>
            </a>
        </li>

        <!-- Backup & Recovery -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('backup_recovery.index') }}">
                <i class="fas fa-wrench"></i>
                <span>Backup & Recovery</span>
            </a>
        </li>

    </ul>
    <!-- END SIDEBAR -->

    <!-- CONTENT WRAPPER -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- TOPBAR -->
              <!-- TOPBAR -->
        <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow"
             style="background:linear-gradient(135deg,#1e293b,#334155); border-radius:12px;">

            <ul class="navbar-nav ml-auto">

                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" style="color:white;">
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
        <!-- END TOPBAR -->


        <!-- MAIN CONTENT -->
        <div class="container-fluid">

            <!-- METRIC CARDS -->
            <div class="row mb-4">

                <!-- Monthly Outages -->
                <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
                    <div class="card shadow-sm h-100"
                         style="background:linear-gradient(135deg,#1e293b,#334155); border-radius:14px; padding:12px;">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div style="width:32px;height:32px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);
                                            border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-chart-line" style="color:white;font-size:13px;"></i>
                                </div>
                                <div id="monthlyOutages" style="color:white;font-size:1.3rem;font-weight:700;">
                                    <span class="count-value">--</span>
                                </div>
                            </div>
                            <h6 style="color:#94a3b8;font-weight:600;">Monthly Outages</h6>
                        </div>
                    </div>
                </div>

                <!-- Online Devices -->
                <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
                    <div class="card shadow-sm h-100"
                         style="background:linear-gradient(135deg,#1e293b,#334155); border-radius:14px; padding:12px;">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div style="width:32px;height:32px;background:linear-gradient(135deg,#10b981,#059669);
                                            border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-wifi" style="color:white;font-size:13px;"></i>
                                </div>
                                <div id="onlineDevices" style="color:white;font-size:1.3rem;font-weight:700;">
                                    <span class="count-value">--</span>
                                </div>
                            </div>
                            <h6 style="color:#94a3b8;font-weight:600;">Online Devices</h6>
                        </div>
                    </div>
                </div>

                <!-- Offline Devices -->
                <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
                    <div class="card shadow-sm h-100"
                         style="background:linear-gradient(135deg,#1e293b,#334155); border-radius:14px; padding:12px;">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div style="width:32px;height:32px;background:linear-gradient(135deg,#ef4444,#dc2626);
                                            border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-exclamation-triangle" style="color:white;font-size:13px;"></i>
                                </div>
                                <div id="offlineDevices" style="color:white;font-size:1.3rem;font-weight:700;">
                                    <span class="count-value">--</span>
                                </div>
                            </div>
                            <h6 style="color:#94a3b8;font-weight:600;">Offline Devices</h6>
                        </div>
                    </div>
                </div>

                <!-- Today's Outages -->
                <div class="col-xl-3 col-lg-3 col-md-6 mb-3">
                    <div class="card shadow-sm h-100"
                         style="background:linear-gradient(135deg,#1e293b,#334155); border-radius:14px; padding:12px;">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div style="width:32px;height:32px;background:linear-gradient(135deg,#f59e0b,#d97706);
                                            border-radius:10px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-clock" style="color:white;font-size:13px;"></i>
                                </div>
                                <div id="todayOutages" style="color:white;font-size:1.3rem;font-weight:700;">
                                    <span class="count-value">--</span>
                                </div>
                            </div>
                            <h6 style="color:#94a3b8;font-weight:600;">Today's Outages</h6>
                        </div>
                    </div>
                </div>

            </div>
                            <!-- DEVICE STATUS SECTION -->
            <div class="card p-4 shadow-sm"
                 style="background:linear-gradient(135deg,#1e293b,#334155);
                        border-radius:16px; color:white;">

                <!-- Header -->
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 style="font-weight:700;">Device Status</h4>

                    <button onclick="loadDevices()"
                            class="btn btn-sm"
                            style="background:rgba(59,130,246,0.15);
                                   color:#3b82f6;
                                   border:1px solid rgba(59,130,246,0.3);
                                   padding:6px 12px;
                                   border-radius:8px;">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>

                <!-- LOADING -->
                <div id="deviceLoadingState" class="text-center py-5">
                    <div style="width:50px;height:50px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);
                                border-radius:12px;display:flex;align-items:center;justify-content:center;
                                margin:0 auto 1rem;">
                        <i class="fas fa-spinner fa-spin" style="color:white;font-size:22px;"></i>
                    </div>
                    <p style="color:#94a3b8;font-weight:600;">Loading devices...</p>
                </div>

                <!-- ERROR -->
                <div id="deviceErrorState" class="text-center py-5" style="display:none;">
                    <div style="width:50px;height:50px;background:linear-gradient(135deg,#ef4444,#dc2626);
                                border-radius:12px;display:flex;align-items:center;justify-content:center;
                                margin:0 auto 1rem;">
                        <i class="fas fa-exclamation-triangle" style="color:white;font-size:22px;"></i>
                    </div>
                    <p style="color:#ef4444;font-weight:600;">Unable to load device data.</p>
                </div>

                <!-- EMPTY -->
                <div id="deviceEmptyState" class="text-center py-5" style="display:none;">
                    <div style="width:50px;height:50px;background:linear-gradient(135deg,#94a3b8,#64748b);
                                border-radius:12px;display:flex;align-items:center;justify-content:center;
                                margin:0 auto 1rem;">
                        <i class="fas fa-network-wired" style="color:white;font-size:22px;"></i>
                    </div>
                    <p style="color:#94a3b8;font-weight:600;">No devices found.</p>

                    <a href="{{ route('devices.index') }}"
                       class="btn"
                       style="background:rgba(16,185,129,0.2);
                              color:#10b981;
                              border:1px solid rgba(16,185,129,0.3);
                              padding:8px 14px;
                              border-radius:8px;
                              font-weight:600;">
                        <i class="fas fa-plus"></i> Add Device
                    </a>
                </div>

                <!-- DEVICE CARDS -->
                <div id="deviceCardsContainer"
                     class="mt-3"
                     style="display:none;
                            display:grid;
                            grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));
                            gap:16px;">
                </div>

                <!-- FOOTER STATUS -->
                <div class="mt-4 pt-3"
                     style="border-top:1px solid rgba(255,255,255,0.1);">

                    <div class="d-flex justify-content-between">
                        <small style="color:#94a3b8;">
                            <i class="fas fa-clock me-1"></i>
                            Last update:
                            <span id="lastUpdate" style="color:white;">Never</span>
                        </small>

                        <small style="color:#10b981;">
                            <i class="fas fa-sync-alt me-1"></i> Auto-refresh enabled
                        </small>
                    </div>

                </div>

            </div>
            <!-- END DEVICE STATUS SECTION -->

        </div> <!-- END container-fluid -->

    </div> <!-- END content-wrapper -->

</div>
<!-- END WRAPPER -->

<!-- LOGOUT MODAL -->
<!-- LOGOUT MODAL -->
<div class="modal fade" id="logoutModal">
    <div class="modal-dialog">
        <div class="modal-content" style="background:#1e293b;color:white;border-radius:12px;">
            <div class="modal-header">
                <h5 class="modal-title">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" style="color:white;">×</button>
            </div>
            <div class="modal-body">
                Select "Logout" below if you want to end your current session.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-primary">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- JS ASSETS (Railway-Compatible) -->
<script src="{{ asset('assets/admin/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/sb-admin-2.min.js') }}"></script>


<!-- DEVICE STATUS + REFRESH SCRIPTS -->
<script>
    async function fetchJSON(url) {
        const r = await fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    }

    function getStatusBadge(device) {
        const status = device.status === 'Online' || device.status === 'ON' || device.status === 'Active';
        return `
            <span style="
                padding:4px 10px;
                border-radius:20px;
                font-weight:600;
                font-size:0.75rem;
                color:white;
                background:${status ? '#10b981' : '#ef4444'};
            ">
                ${status ? 'ONLINE' : 'OFFLINE'}
            </span>
        `;
    }

    function deviceCard(device) {
        return `
            <div class="p-3"
                 style="
                    background:linear-gradient(135deg,#0f172a,#1e293b);
                    border-radius:14px;
                    border:1px solid rgba(255,255,255,0.08);
                 ">
                <h5 style="color:white;font-weight:700;font-size:1rem;">
                    ${device.household_name || 'Unknown Device'}
                </h5>

                <p style="color:#94a3b8;margin:0;font-size:0.85rem;">
                    <i class="fas fa-map-marker-alt"></i>
                    ${device.barangay || 'Unknown Location'}
                </p>

                <p style="color:#64748b;margin:4px 0 10px;font-size:0.75rem;">
                    <i class="fas fa-clock"></i>
                    ${device.last_seen_human || 'Never'}
                </p>

                ${getStatusBadge(device)}
            </div>
        `;
    }

    async function loadDevices() {
        const load = document.getElementById("deviceLoadingState");
        const error = document.getElementById("deviceErrorState");
        const empty = document.getElementById("deviceEmptyState");
        const cards = document.getElementById("deviceCardsContainer");

        load.style.display = "block";
        error.style.display = "none";
        empty.style.display = "none";
        cards.style.display = "none";

        try {
            const data = await fetchJSON(`{{ route('api.devices') }}`);

            load.style.display = "none";

            if (!data.devices || data.devices.length === 0) {
                empty.style.display = "block";
                return;
            }

            cards.innerHTML = data.devices.map(deviceCard).join("");
            cards.style.display = "grid";

            document.getElementById("lastUpdate").textContent = new Date().toLocaleTimeString();

        } catch (err) {
            console.error(err);
            load.style.display = "none";
            error.style.display = "block";
        }
    }

    let autoRefresh;
    function startAutoRefresh() {
        loadDevices();
        autoRefresh = setInterval(loadDevices, 60000);
    }

    document.addEventListener("DOMContentLoaded", startAutoRefresh);
</script>


</body>
</html>