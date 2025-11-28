<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SOLECO | Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- LOAD CSS + JS FROM VITE (REQUIRED FOR RAILWAY) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
            font-family: 'Roboto', sans-serif;
        }

        .card-metric {
            border: none;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 6px 15px rgba(0,0,0,0.08);
            transition: 0.2s ease;
        }
        .card-metric:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.12);
        }

        .status-pill {
            padding: .35rem .7rem;
            border-radius: 999px;
            font-weight: 600;
            font-size: .85rem;
        }
        .pill-on {
            background:#d6f5d8;
            color:#1e7d32;
        }
        .pill-off {
            background:#ffd7dd;
            color:#b71c1c;
        }

        .table thead th {
            background:#eef2f7;
            text-transform: uppercase;
            font-size: .8rem;
            letter-spacing: .4px;
        }
    </style>
</head>

<body>

{{-- ===================== NAVBAR ===================== --}}
<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm sticky-top">
    <div class="container">

        <a class="navbar-brand fw-bold text-primary d-flex align-items-center"
           href="{{ route('admin.dashboardtest') }}">
            <img src="{{ asset('photos/final.png') }}" style="width:45px;" class="me-2">
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


{{-- ===================== MAIN ===================== --}}
<main class="container py-4">

    {{-- ===== METRICS CARDS ===== --}}
    <div class="row g-3">

        <div class="col-md-3">
            <div class="card card-metric">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted">Total Devices</p>
                            <p id="m-total" class="h3 fw-bold">—</p>
                        </div>
                        <i class="bi bi-hdd-network text-primary fs-2"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-metric">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted">Online (ON)</p>
                            <p id="m-on" class="h3 fw-bold text-success">—</p>
                        </div>
                        <i class="bi bi-check-circle text-success fs-2"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-metric">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted">Offline (OFF)</p>
                            <p id="m-off" class="h3 fw-bold text-danger">—</p>
                        </div>
                        <i class="bi bi-x-octagon text-danger fs-2"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-metric">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-muted">Outages Today</p>
                            <p id="m-today" class="h3 fw-bold text-dark">—</p>
                        </div>
                        <i class="bi bi-calendar-event text-secondary fs-2"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- ===== CHARTS ===== --}}
    <div class="row g-3 mt-2">
        
        {{-- Line Chart --}}
        <div class="col-lg-7">
            <div class="card card-metric h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0">Outages (last 12 hours)</h5>
                        <span class="text-muted small">Auto-updating</span>
                    </div>

                    <canvas id="lineChart" height="120"></canvas>
                </div>
            </div>
        </div>

        {{-- Donut Chart --}}
        <div class="col-lg-5">
            <div class="card card-metric h-100">
                <div class="card-body">
                    <h5 class="mb-2">Current Status</h5>
                    <canvas id="donutChart" height="120"></canvas>
                </div>
            </div>
        </div>

    </div>


    {{-- ===== LATEST LOGS ===== --}}
    <div class="card card-metric mt-3">
        <div class="card-body">

            <div class="d-flex justify-content-between mb-3">
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
                        <tr>
                            <td colspan="3" class="text-center text-muted">Loading…</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</main>


{{-- ===================== FOOTER ===================== --}}
<footer class="text-center text-muted py-4">
    &copy; {{ date('Y') }} Southern Leyte Electric Cooperative (SOLECO). All Rights Reserved.
</footer>


{{-- ===================== SCRIPTS ===================== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let lineChart, donutChart;

async function fetchJSON(url){
    const res = await fetch(url);
    return res.json();
}

function fmtDate(dt){ return new Date(dt).toLocaleString(); }

function ensureCharts(){
    if(!lineChart){
        lineChart = new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: { labels: [], datasets: [{ label: 'OFF Events', data: [], borderColor:'#dc2626', tension:.4 }] }
        });
    }

    if(!donutChart){
        donutChart = new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: { labels:['ON','OFF'], datasets:[{ data:[0,0], backgroundColor:['#16a34a','#dc2626'] }] }
        });
    }
}

async function loadStats(){
    const data = await fetchJSON('{{ route("admin.api.stats") }}');

    document.getElementById('m-total').textContent = data.totals.devices;
    document.getElementById('m-on').textContent    = data.totals.on;
    document.getElementById('m-off').textContent   = data.totals.off;
    document.getElementById('m-today').textContent = data.totals.todayOutages;

    ensureCharts();

    lineChart.data.labels = data.series.map(x => x.h);
    lineChart.data.datasets[0].data = data.series.map(x => x.c);
    lineChart.update();

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

    tbody.innerHTML = logs.map(l => `
        <tr>
            <td>${l.device_id}</td>

            <td>
                ${l.status === "ON"
                    ? `<span class="status-pill pill-on">ON</span>`
                    : `<span class="status-pill pill-off">OFF</span>`}
            </td>

            <td>${fmtDate(l.created_at)}</td>
        </tr>
    `).join('');
}

async function refreshAll(){
    await Promise.all([loadStats(), loadLogs()]);
}

refreshAll();
setInterval(refreshAll, 5000);
</script>

</body>
</html>
