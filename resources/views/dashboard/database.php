<?php include __DIR__ . '/layouts/app.php'; ?>

<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Database Configuration</h1>

        <form action="/dashboard/database/save" method="POST" class="space-y-6">
            <!-- Database Type -->
            <div>
                <label for="db_type" class="block text-sm font-medium text-gray-700">Database Type</label>
                <select id="db_type" name="db_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="mysql">MySQL</option>
                    <option value="pgsql">PostgreSQL</option>
                    <option value="sqlite">SQLite</option>
                </select>
            </div>

            <!-- Host -->
            <div>
                <label for="db_host" class="block text-sm font-medium text-gray-700">Host</label>
                <input type="text" id="db_host" name="db_host" value="localhost" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Port -->
            <div>
                <label for="db_port" class="block text-sm font-medium text-gray-700">Port</label>
                <input type="number" id="db_port" name="db_port" value="3306" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Database Name -->
            <div>
                <label for="db_name" class="block text-sm font-medium text-gray-700">Database Name</label>
                <input type="text" id="db_name" name="db_name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Username -->
            <div>
                <label for="db_username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="db_username" name="db_username" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- Password -->
            <div>
                <label for="db_password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="db_password" name="db_password" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- SQLite Database File -->
            <div id="sqlite_path" class="hidden">
                <label for="db_path" class="block text-sm font-medium text-gray-700">Database File Path</label>
                <input type="text" id="db_path" name="db_path" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                <p class="mt-1 text-sm text-gray-500">Relative to your project root</p>
            </div>

            <!-- Test Connection Button -->
            <div>
                <button type="button" id="test_connection" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-vial mr-2"></i>
                    Test Connection
                </button>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-save mr-2"></i>
                    Save Configuration
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dbType = document.getElementById('db_type');
    const sqlitePath = document.getElementById('sqlite_path');
    const testConnection = document.getElementById('test_connection');

    // Show/hide SQLite path field based on database type
    dbType.addEventListener('change', function() {
        if (this.value === 'sqlite') {
            sqlitePath.classList.remove('hidden');
        } else {
            sqlitePath.classList.add('hidden');
        }
    });

    // Test connection functionality
    testConnection.addEventListener('click', function() {
        const formData = new FormData(document.querySelector('form'));
        
        fetch('/dashboard/database/test', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Connection successful!');
            } else {
                alert('Connection failed: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error testing connection: ' + error.message);
        });
    });
});
</script> 