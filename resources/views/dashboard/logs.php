<?php include __DIR__ . '/layouts/app.php'; ?>

<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Live Logs</h1>
        <div class="flex space-x-4">
            <button id="clear_logs" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-trash mr-2"></i>
                Clear Logs
            </button>
            <button id="download_logs" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-download mr-2"></i>
                Download Logs
            </button>
        </div>
    </div>

    <!-- Log Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Log Level -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Log Level</label>
                <div class="space-y-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="levels[]" value="emergency" checked class="form-checkbox h-4 w-4 text-red-600">
                        <span class="ml-2 text-sm text-gray-700">Emergency</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="levels[]" value="alert" checked class="form-checkbox h-4 w-4 text-red-600">
                        <span class="ml-2 text-sm text-gray-700">Alert</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="levels[]" value="critical" checked class="form-checkbox h-4 w-4 text-red-600">
                        <span class="ml-2 text-sm text-gray-700">Critical</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="levels[]" value="error" checked class="form-checkbox h-4 w-4 text-red-600">
                        <span class="ml-2 text-sm text-gray-700">Error</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="levels[]" value="warning" checked class="form-checkbox h-4 w-4 text-yellow-600">
                        <span class="ml-2 text-sm text-gray-700">Warning</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="levels[]" value="notice" checked class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-700">Notice</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="levels[]" value="info" checked class="form-checkbox h-4 w-4 text-green-600">
                        <span class="ml-2 text-sm text-gray-700">Info</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="levels[]" value="debug" checked class="form-checkbox h-4 w-4 text-gray-600">
                        <span class="ml-2 text-sm text-gray-700">Debug</span>
                    </label>
                </div>
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                <div class="space-y-2">
                    <input type="datetime-local" id="start_date" class="block w-full pl-3 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <input type="datetime-local" id="end_date" class="block w-full pl-3 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>

            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" id="search" placeholder="Search logs..." class="block w-full pl-3 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <!-- Auto-refresh -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Auto-refresh</label>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="auto_refresh" class="form-checkbox h-4 w-4 text-blue-600">
                    <select id="refresh_interval" class="block w-32 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="5">5 seconds</option>
                        <option value="10">10 seconds</option>
                        <option value="30">30 seconds</option>
                        <option value="60">1 minute</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Viewer -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-500">Showing <span id="log_count">0</span> logs</span>
                <span class="text-sm text-gray-500">Last updated: <span id="last_updated">-</span></span>
            </div>
            <div class="flex items-center space-x-2">
                <button id="pause_logs" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-pause"></i>
                </button>
                <button id="resume_logs" class="text-gray-500 hover:text-gray-700 hidden">
                    <i class="fas fa-play"></i>
                </button>
            </div>
        </div>

        <div id="log_viewer" class="bg-gray-900 text-gray-100 p-4 rounded-lg font-mono text-sm overflow-auto max-h-[600px]">
            <!-- Log entries will be inserted here -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const logViewer = document.getElementById('log_viewer');
    const logCount = document.getElementById('log_count');
    const lastUpdated = document.getElementById('last_updated');
    const pauseLogs = document.getElementById('pause_logs');
    const resumeLogs = document.getElementById('resume_logs');
    const autoRefresh = document.getElementById('auto_refresh');
    const refreshInterval = document.getElementById('refresh_interval');
    const clearLogs = document.getElementById('clear_logs');
    const downloadLogs = document.getElementById('download_logs');
    const search = document.getElementById('search');
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    let isPaused = false;
    let refreshTimer = null;

    // Set default date range
    const now = new Date();
    const oneHourAgo = new Date(now - 3600000);
    startDate.value = oneHourAgo.toISOString().slice(0, 16);
    endDate.value = now.toISOString().slice(0, 16);

    // Format log entry
    function formatLogEntry(entry) {
        const timestamp = new Date(entry.timestamp).toLocaleString();
        const levelClass = {
            emergency: 'text-red-500',
            alert: 'text-red-500',
            critical: 'text-red-500',
            error: 'text-red-500',
            warning: 'text-yellow-500',
            notice: 'text-blue-500',
            info: 'text-green-500',
            debug: 'text-gray-500'
        }[entry.level] || 'text-gray-500';

        return `
            <div class="log-entry ${levelClass}">
                <span class="text-gray-400">[${timestamp}]</span>
                <span class="font-bold">[${entry.level.toUpperCase()}]</span>
                <span class="text-gray-300">${entry.message}</span>
                ${entry.context ? `<pre class="mt-1 text-xs text-gray-400">${JSON.stringify(entry.context, null, 2)}</pre>` : ''}
            </div>
        `;
    }

    // Fetch logs
    async function fetchLogs() {
        if (isPaused) return;

        try {
            const response = await fetch('/dashboard/logs/fetch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    start_date: startDate.value,
                    end_date: endDate.value,
                    search: search.value,
                    levels: Array.from(document.querySelectorAll('input[name="levels[]"]:checked'))
                        .map(checkbox => checkbox.value)
                })
            });

            const data = await response.json();
            
            if (data.success) {
                logViewer.innerHTML = data.logs.map(formatLogEntry).join('');
                logCount.textContent = data.logs.length;
                lastUpdated.textContent = new Date().toLocaleString();
            }
        } catch (error) {
            console.error('Error fetching logs:', error);
        }
    }

    // Initialize auto-refresh
    function updateAutoRefresh() {
        if (refreshTimer) {
            clearInterval(refreshTimer);
        }

        if (autoRefresh.checked) {
            refreshTimer = setInterval(fetchLogs, parseInt(refreshInterval.value) * 1000);
        }
    }

    // Event listeners
    autoRefresh.addEventListener('change', updateAutoRefresh);
    refreshInterval.addEventListener('change', updateAutoRefresh);
    search.addEventListener('input', fetchLogs);
    startDate.addEventListener('change', fetchLogs);
    endDate.addEventListener('change', fetchLogs);

    pauseLogs.addEventListener('click', function() {
        isPaused = true;
        pauseLogs.classList.add('hidden');
        resumeLogs.classList.remove('hidden');
    });

    resumeLogs.addEventListener('click', function() {
        isPaused = false;
        resumeLogs.classList.add('hidden');
        pauseLogs.classList.remove('hidden');
        fetchLogs();
    });

    clearLogs.addEventListener('click', async function() {
        if (!confirm('Are you sure you want to clear all logs?')) return;

        try {
            const response = await fetch('/dashboard/logs/clear', {
                method: 'POST'
            });

            const data = await response.json();
            if (data.success) {
                logViewer.innerHTML = '';
                logCount.textContent = '0';
            }
        } catch (error) {
            console.error('Error clearing logs:', error);
        }
    });

    downloadLogs.addEventListener('click', async function() {
        try {
            const response = await fetch('/dashboard/logs/download', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    start_date: startDate.value,
                    end_date: endDate.value,
                    search: search.value,
                    levels: Array.from(document.querySelectorAll('input[name="levels[]"]:checked'))
                        .map(checkbox => checkbox.value)
                })
            });

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `logs-${new Date().toISOString().slice(0, 10)}.txt`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        } catch (error) {
            console.error('Error downloading logs:', error);
        }
    });

    // Initial fetch
    fetchLogs();
    updateAutoRefresh();
});
</script> 