<?php include __DIR__ . '/layouts/app.php'; ?>

<div class="max-w-7xl mx-auto">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Welcome to FireUp</h1>
        <p class="text-gray-600">Your modern PHP framework with visual development tools</p>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <a href="/dashboard/database" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-database text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Database Setup</h3>
                    <p class="text-sm text-gray-500">Configure your database connection</p>
                </div>
            </div>
        </a>

        <a href="/dashboard/table-builder" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-table text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Table Builder</h3>
                    <p class="text-sm text-gray-500">Create and manage database tables</p>
                </div>
            </div>
        </a>

        <a href="/dashboard/model-generator" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-cube text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Model Generator</h3>
                    <p class="text-sm text-gray-500">Generate models from tables</p>
                </div>
            </div>
        </a>

        <a href="/dashboard/api-generator" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-code text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">API Generator</h3>
                    <p class="text-sm text-gray-500">Create RESTful APIs</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Project Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Tables -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Tables</h2>
            <div class="space-y-4">
                <?php if (empty($recentTables)): ?>
                    <p class="text-gray-500 text-sm">No tables created yet. Use the Table Builder to create your first table.</p>
                <?php else: ?>
                    <?php foreach ($recentTables as $table): ?>
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900"><?= htmlspecialchars($table['name']) ?></h3>
                            <p class="text-sm text-gray-500">Last modified: <?= $table['modified_at'] ?></p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="/dashboard/table-builder?table=<?= urlencode($table['name']) ?>" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteTable('<?= htmlspecialchars($table['name']) ?>')" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent APIs -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Recent APIs</h2>
            <div class="space-y-4">
                <?php if (empty($recentApis)): ?>
                    <p class="text-gray-500 text-sm">No APIs created yet. Use the API Generator to create your first API.</p>
                <?php else: ?>
                    <?php foreach ($recentApis as $api): ?>
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900"><?= htmlspecialchars($api['endpoint']) ?></h3>
                            <p class="text-sm text-gray-500"><?= implode(', ', $api['methods']) ?></p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="/dashboard/api-generator?endpoint=<?= urlencode($api['endpoint']) ?>" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/dashboard/api-tester?endpoint=<?= urlencode($api['endpoint']) ?>" class="text-green-600 hover:text-green-800">
                                <i class="fas fa-vial"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function deleteTable(tableName) {
    if (confirm('Are you sure you want to delete this table?')) {
        fetch(`/dashboard/table-builder/delete?table=${encodeURIComponent(tableName)}`, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            }
        });
    }
}
</script>