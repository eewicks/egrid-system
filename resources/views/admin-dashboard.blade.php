<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SOLECO | Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">


    <style>
        body { background: #f6f8fb; }
        .navbar-brand { font-weight: 800; font-family: 'Roboto', sans-serif;}
        .card-metric { border: none; border-radius: 14px; box-shadow: 0 8px 20px rgba(0,0,0,.06); }
        .metric-badge { font-size: .85rem; }
        .status-pill { padding:.35rem .6rem; border-radius: 999px; font-weight:600; }
        .pill-on  { background:#e8f5e9; color:#2e7d32; }
        .pill-off { background:#ffebee; color:#c62828; }
        .table thead th { background:#f1f4f9; }
        .footer { color:#6c757d; }
        .navbar-logo {
        width: 45px;   
        height: 45px;  
        object-fit: contain; 
}
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand text-primary fw-bold d-flex align-items-center" href="{{ route('admin.dashboard') }}">
        <img src="{{ asset('photos/final.png') }}" alt="Logo" class="navbar-logo me-2">
        MONITORING DASHBOARD
        </a>

        <div class="ms-auto d-flex align-items-center gap-2">
           
            <a href="{{ route('devices.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-gear me-1"></i> Manage Devices
            </a>
            
            <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-danger btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </form>
        </div>
    </div>
</nav>


<main class="container py-4">
  
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card card-metric">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Total Devices</div>
                            <div class="h3 fw-bold" id="m-total">—</div>
                        </div>
                        <i class="bi bi-hdd-network fs-2 text-primary"></i>
                    </div>
                    <span class="metric-badge text-muted">Latest status per device</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-metric">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Online (ON)</div>
                            <div class="h3 fw-bold text-success" id="m-on">—</div>
                        </div>
                        <i class="bi bi-check-circle fs-2 text-success"></i>
                    </div>
                    <span class="metric-badge text-muted">Currently reporting</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-metric">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Offline (OFF)</div>
                            <div class="h3 fw-bold text-danger" id="m-off">—</div>
                        </div>
                        <i class="bi bi-exclamation-octagon fs-2 text-danger"></i>
                    </div>
                    <span class="metric-badge text-muted">Needs attention</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-metric">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Outages Today</div>
                            <div class="h3 fw-bold" id="m-today">—</div>
                        </div>
                        <i class="bi bi-calendar-event fs-2 text-secondary"></i>
                    </div>
                    <span class="metric-badge text-muted">OFF events (24h)</span>
                </div>
            </div>
        </div>
    </div>

   
    <div class="row g-3 mt-1">
        <div class="col-lg-7">
            <div class="card card-metric h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">Outages (last 12 hours)</h5>
                        <span class="text-muted small" id="chart-subtitle">Auto-updating</span>
                    </div>
                    <canvas id="lineChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card card-metric h-100">
                <div class="card-body">
                    <h5 class="mb-2">Current Status</h5>
                    <canvas id="donutChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>

 
    <div class="card card-metric mt-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Latest Activity</h5>
                <span class="text-muted small">Last 20 records</span>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Device ID</th>
                            <th>Status</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody id="logsBody">
                        <tr><td colspan="3" class="text-center text-muted">Loading…</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<footer class="container py-4 footer">
    <div class="text-center">
        &copy; {{ date('Y') }} Southern Leyte Electric Cooperative (SOLECO). All Rights Reserved.
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
let lineChart, donutChart;

async function fetchJSON(url){
    const r = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
    return r.json();
}

function fmtDate(dt){ return new Date(dt).toLocaleString(); }

function ensureCharts(){
    const lc = document.getElementById('lineChart').getContext('2d');
    const dc = document.getElementById('donutChart').getContext('2d');
    if(!lineChart){
        lineChart = new Chart(lc, {
            type: 'line',
            data: { labels: [], datasets: [{ label: 'OFF events', data: [] }] },
            options: { responsive:true, maintainAspectRatio:false }
        });
    }
    if(!donutChart){
        donutChart = new Chart(dc, {
            type: 'doughnut',
            data: { labels: ['ON','OFF'], datasets: [{ data: [0,0] }] },
            options: { responsive:true, maintainAspectRatio:false }
        });
    }
}

async function loadStats(){
    const data = await fetchJSON('{{ route("admin.api.stats") }}');

    
    document.getElementById('m-total').textContent = data.totals.devices ?? 0;
    document.getElementById('m-on').textContent    = data.totals.on ?? 0;
    document.getElementById('m-off').textContent   = data.totals.off ?? 0;
    document.getElementById('m-today').textContent = data.totals.todayOutages ?? 0;

  
    ensureCharts();

    // Line (12h OFF counts)
    const labels = data.series.map(x => x.h);
    const counts = data.series.map(x => x.c);
    lineChart.data.labels = labels;
    lineChart.data.datasets[0].data = counts;
    lineChart.update();

    //  (ON/OFF split)
    donutChart.data.datasets[0].data = [data.totals.on, data.totals.off];
    donutChart.update();
}

async function loadLogs(){
    const logs = await fetchJSON('{{ route("admin.api.logs") }}');
    const tbody = document.getElementById('logsBody');
    if(!logs.length){
        tbody.innerHTML = `<tr><td colspan="3" class="text-center text-muted">No data</td></tr>`;
        return;
    }
    tbody.innerHTML = logs.map(l => {
        const pill = l.status === 'ON'
            ? `<span class="status-pill pill-on">ON</span>`
            : `<span class="status-pill pill-off">OFF</span>`;
        return `<tr>
            <td>${l.device_id}</td>
            <td>${pill}</td>
            <td>${fmtDate(l.created_at)}</td>
        </tr>`;
    }).join('');
}

async function refreshAll(){
    await Promise.all([loadStats(), loadLogs()]);
}

refreshAll();
setInterval(refreshAll, 5000); // auto-refresh every 5s
</script>
</body>
</html>
