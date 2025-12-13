<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    
    <div class="py-6">

    <!-- STAT CARDS ----------------------------- -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mt-4">
                    Recent Activity
                    <br/><br/><hr>
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="card text-white bg-primary h-100">
                                        <div class="card-body d-flex flex-column">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-people-fill me-2" style="font-size: 1.5rem;"></i>
                                                <h5 class="card-title mb-0">Users</h5>
                                            </div>
                                            <p class="card-text display-6 mt-auto" id="stat-users">0</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card text-white bg-success h-100">
                                        <div class="card-body d-flex flex-column">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-link-45deg me-2" style="font-size: 1.5rem;"></i>
                                                <h5 class="card-title mb-0">Active Connections</h5>
                                            </div>
                                            <p class="card-text display-6 mt-auto" id="stat-active-connections">0</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card text-white bg-info h-100">
                                        <div class="card-body d-flex flex-column">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-chat-left-text me-2" style="font-size: 1.5rem;"></i>
                                                <h5 class="card-title mb-0">Messages Sent</h5>
                                            </div>
                                            <p class="card-text display-6 mt-auto" id="stat-messages-sent">0</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card text-white bg-danger h-100">
                                        <div class="card-body d-flex flex-column">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-exclamation-triangle-fill me-2" style="font-size: 1.5rem;"></i>
                                                <h5 class="card-title mb-0">Errors</h5>
                                            </div>
                                            <p class="card-text display-6 mt-auto" id="stat-errors">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- row -->
                </div><!-- container -->
            </div><!-- wrapper -->
        </div>

    <!-- GRAPHS AN TABLES ----------------------- -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg pb-3 mb-4">
                <div class="container mt-4">
                    Monthly Statistics
                    <br/><br/><hr></br>

                    <div class="row">
                        <!-- Sales Chart -->
                        <div class="col-md-8 mb-4">
                            <div class="card">
                                <div class="card-header">Monthly Sales</div>
                                <div class="card-body">
                                    <canvas id="salesChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Users Table -->
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header">Users</div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="usersTableBody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> <!--  ROW  -->

                    <!-- Progress Cards -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header">Connection Utilization</div>
                                <div class="card-body">
                                    <div class="progress mb-2">
                                        <div id="progress-connections" class="progress-bar bg-success" role="progressbar" style="width:0%">0%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header">Message Activity</div>
                                <div class="card-body">
                                    <div class="progress mb-2">
                                        <div id="progress-messages" class="progress-bar bg-info" role="progressbar" style="width:0%">0%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!--  ROW  -->

                </div>
            </div>
        </div>

    <!-- LOGS ----------------------------------- -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg pb-3">
                <div class="container mt-4">
                    Traffic Logs
                    <br/><br/><hr>  
                    <div class="card mt-3 mb-3">
                        <div class="card-body bg-gray-700 text-white">
                            <h5 class="card-title">/var/logs/laravel.log</h5>
                            <div id="logConsole" style="height: 250px; overflow-y: scroll; font-family: monospace; background: #111; padding: 10px; border-radius: 5px;">
                                Loading logs...
                            </div>
                            <button class="btn btn-sm btn-light mt-2" id="refreshLogs">Refresh</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- PIE CHARTS ----------------------------- -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4 mt-4">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mt-4">
                    Monthly Activity
                    <br/><br/><hr></br>
                    <!-- Pie Charts -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Connection Status</h5>
                                    <canvas id="connectionsPie"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Messages Distribution</h5>
                                    <canvas id="messagesPie"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- wrapper -->
        </div>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-100">
                        <!-- {{ __("You're logged in!") }}
                        <br/>
                        <br/><hr></br> -->
                        <ul>                        
                            <li>📌 Python Automation</li>
                            <li>📌 Selenium</li>
                            <li>📌 API testing </li>
                            <li>📌 Postman </li>
                            <li>📌 InfoSec</li>
                            <li>📌 Developer Tools</li>
                            <li>📌 InfoSec</li>
                            <li>📌 Pickle</li>
                            <li>📌 Other ToDo...</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', async () => {

        // -------------------------------
        // 📌 Load JSON Dashboard Data
        // -------------------------------
        let data;
        try {
            const res = await fetch('/fake-dashboard.json');

            if (!res.ok) {
                throw new Error(`Failed to fetch JSON: HTTP ${res.status}`);
            }

            try {
                data = await res.json();
            } catch (parseErr) {
                console.error('❌ JSON PARSE ERROR:', parseErr);

                // Read raw text so you can see what is broken
                const raw = await res.clone().text();
                console.log('Raw JSON response:', raw);

                alert('Dashboard JSON is invalid. Check console for details.');
                return; // stop execution
            }

        } catch (err) {
            console.error(err);
            alert('Could not load dashboard data.');
            return; // stop execution
        }

        // Log loaded data
        console.log("Dashboard JSON loaded:", data);

        // Pie chart and tailwinds data
        // Example data
        const connectionData = {
            labels: ['Active', 'Requested', 'Refused', 'Deactivated'],
            datasets: [{
                data: [12, 5, 2, 1],
                backgroundColor: ['#198754', '#ffc107', '#dc3545', '#6c757d']
            }]
        };

        const messagesData = {
            labels: ['Text', 'Image', 'Video', 'Other'],
            datasets: [{
                data: [50, 20, 15, 15],
                backgroundColor: ['#0d6efd', '#0dcaf0', '#fd7e14', '#adb5bd']
            }]
        };

        // Connection Pie
        new Chart(document.getElementById('connectionsPie').getContext('2d'), {
            type: 'pie',
            data: connectionData,
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // Messages Pie
        new Chart(document.getElementById('messagesPie').getContext('2d'), {
            type: 'pie',
            data: messagesData,
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });        


        // -------------------------------
        // 📌 Stats
        // -------------------------------
        document.getElementById('stat-users').textContent = data.stats.users;
        document.getElementById('stat-active-connections').textContent = data.stats.active_connections;
        document.getElementById('stat-messages-sent').textContent = data.stats.messages_sent;
        document.getElementById('stat-errors').textContent = data.stats.errors;


        // -------------------------------
        // 📌 Users Table
        // -------------------------------
        const tbody = document.getElementById('usersTableBody');
        data.users.forEach(u => {
            const tr = document.createElement('tr');
            tr.innerHTML = `<td>${u.name}</td><td>${u.status}</td>`;
            tbody.appendChild(tr);
        });


        // -------------------------------
        // 📌 Sales Chart
        // -------------------------------
        const ctx = document.getElementById('salesChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.sales.map(s => s.month),
                datasets: [{
                    label: 'Sales',
                    data: data.sales.map(s => s.value),
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });


        // -------------------------------
        // 📌 Progress Bars
        // -------------------------------
        const connPercent = Math.round((data.stats.active_connections / data.stats.users) * 100);
        const msgPercent = Math.round((data.stats.messages_sent / (data.stats.users * 10)) * 100);

        const progConn = document.getElementById('progress-connections');
        progConn.style.width = connPercent + '%';
        progConn.textContent = connPercent + '%';

        const progMsg = document.getElementById('progress-messages');
        progMsg.style.width = msgPercent + '%';
        progMsg.textContent = msgPercent + '%';

    });

    async function loadLogs() {
        const container = document.getElementById('logConsole');
        container.textContent = 'Loading logs...';

        try {
            const res = await fetch('/logs/recent');
            if (!res.ok) throw new Error('Failed to fetch logs');

            const lines = await res.json();
            container.textContent = ''; // clear previous content
            lines.forEach(line => {
                const div = document.createElement('div');
                div.textContent = line;
                container.appendChild(div);
            });

            // Auto-scroll to bottom
            container.scrollTop = container.scrollHeight;

        } catch (err) {
            container.textContent = 'Error loading logs';
            console.error(err);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadLogs();

        document.getElementById('refreshLogs').addEventListener('click', loadLogs);
    });

    </script>


</x-app-layout>
