<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SOLECO | Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- TAILWIND CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- HEROICONS -->
    <script src="https://unpkg.com/heroicons@2.0.18/dist/heroicons.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

<!-- NAVBAR -->
<header class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        
        <!-- Logo + Name -->
        <a href="{{ route('admin.dashboardtest') }}" class="flex items-center space-x-3">
            <img src="{{ asset('photos/final.png') }}" class="h-12 w-12 object-contain">
            <h1 class="text-xl font-bold text-blue-600 tracking-tight">MONITORING DASHBOARD</h1>
        </a>

        <!-- Right Buttons -->
        <div class="flex items-center space-x-3">
            <a href="{{ route('devices.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-200">
                ⚙ Manage Devices
            </a>

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                    Logout
                </button>
            </form>
        </div>

    </div>
</header>


<!-- MAIN -->
<main class="max-w-7xl mx-auto px-6 py-6">

    <!-- METRICS GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Total Devices -->
        <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500">Total Devices</p>
                    <p id="m-total" class="text-3xl font-bold">—</p>
                </div>
                <span class="text-blue-600 text-4xl">🖥️</span>
            </div>
        </div>

        <!-- Online -->
        <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500">Online (ON)</p>
                    <p id="m-on" class="text-3xl font-bold text-green-600">—</p>
                </div>
                <span class="text-green-600 text-4xl">✔️</span>
            </div>
        </div>

        <!-- Offline -->
        <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500">Offline (OFF)</p>
                    <p id="m-off" class="text-3xl font-bold text-red-600">—</p>
                </div>
                <span class="text-red-600 text-4xl">⚠️</span>
            </div>
        </div>

        <!-- Outages Today -->
        <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500">Outages Today</p>
                    <p id="m-today" class="text-3xl font-bold text-gray-800">—</p>
                </div>
                <span class="text-gray-400 text-4xl">📅</span>
            </div>
        </div>

    </div>

    <!-- CHARTS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

        <!-- Line Chart -->
        <div class="bg-white p-6 rounded-xl shadow col-span-2">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-semibold">Outages (Last 12 Hours)</h2>
                <span class="text-sm text-gray-500">Auto-update</span>
            </div>
            <canvas id="lineChart" height="150"></canvas>
        </div>

        <!-- Donut -->
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-2">Current Status</h2>
            <canvas id="donutChart" height="150"></canvas>
        </div>

    </div>


    <!-- Latest Logs -->
    <div class="bg-white p-6 rounded-xl shadow mt-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Latest Activity</h2>
            <span class="text-sm text-gray-500">Last 20 records</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm">
                        <th class="px-4 py-2">Device ID</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Timestamp</th>
                    </tr>
                </thead>
                <tbody id="logsBody" class="text-gray-700 text-sm">
                    <tr><td colspan="3" class="text-center py-3">Loading…</td></tr>
                </tbody>
            </table>
        </div>
    </div>

</main>


<!-- FOOTER -->
<footer class="text-center text-gray-500 text-sm py-6">
    &copy; {{ date('Y') }} SOLECO. All Rights Reserved.
</footer>


<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let lineChart, donutChart;

async function fetchJSON(url){
    const r = await fetch(url);
    return r.json();
}

function fmtDate(dt){ return new Date(dt).toLocaleString(); }

function ensureCharts(){
    if(!lineChart){
        lineChart = new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: { labels: [], datasets: [{ label:'OFF Events', data:[], borderColor:'#dc2626', tension:.3 }] }
        });
    }
    if(!donutChart){
        donutChart = new Chart(document.getElementById('donutChart'), {
            type:'doughnut',
            data:{ labels:['ON','OFF'], datasets:[{ data:[0,0], backgroundColor:['#16a34a','#dc2626'] }] }
        });
    }
}

async function loadStats(){
    const data = await fetchJSON('{{ route("admin.api.stats") }}');

    document.getElementById('m-total').textContent = data.totals.devices;
    document.getElementById('m-on').textContent = data.totals.on;
    document.getElementById('m-off').textContent = data.totals.off;
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
    const tbody = document.getElementById("logsBody");

    if(!logs.length){
        tbody.innerHTML = `<tr><td colspan="3" class="text-center py-3 text-gray-400">No data</td></tr>`;
        return;
    }

    tbody.innerHTML = logs.map(l => `
        <tr class="hover:bg-gray-50">
            <td class="px-4 py-2">${l.device_id}</td>
            <td class="px-4 py-2">
                ${l.status === "ON"
                    ? `<span class="px-3 py-1 bg-green-200 text-green-700 rounded-full">ON</span>`
                    : `<span class="px-3 py-1 bg-red-200 text-red-700 rounded-full">OFF</span>`}
            </td>
            <td class="px-4 py-2">${fmtDate(l.created_at)}</td>
        </tr>
    `).join("");
}

async function refreshAll(){
    await Promise.all([loadStats(), loadLogs()]);
}

refreshAll();
setInterval(refreshAll, 5000);
</script>

</body>
</html>
