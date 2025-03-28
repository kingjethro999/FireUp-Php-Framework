<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FireUp Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.3/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100" x-data="{ sidebarOpen: false }">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 transform transition-transform duration-300 ease-in-out"
         :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
        <div class="flex items-center justify-between h-16 px-4 bg-gray-800">
            <span class="text-xl font-bold text-white">FireUp</span>
            <button @click="sidebarOpen = false" class="text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav class="mt-5 px-2">
            <a href="/dashboard" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-white hover:bg-gray-700">
                <i class="fas fa-home mr-3"></i>
                Dashboard
            </a>
            <a href="/dashboard/database" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-white hover:bg-gray-700">
                <i class="fas fa-database mr-3"></i>
                Database
            </a>
            <a href="/dashboard/table-builder" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-white hover:bg-gray-700">
                <i class="fas fa-table mr-3"></i>
                Table Builder
            </a>
            <a href="/dashboard/model-generator" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-white hover:bg-gray-700">
                <i class="fas fa-cube mr-3"></i>
                Model Generator
            </a>
            <a href="/dashboard/api-generator" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-white hover:bg-gray-700">
                <i class="fas fa-code mr-3"></i>
                API Generator
            </a>
            <a href="/dashboard/marketplace" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-white hover:bg-gray-700">
                <i class="fas fa-store mr-3"></i>
                Marketplace
            </a>
            <a href="/dashboard/logs" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-white hover:bg-gray-700">
                <i class="fas fa-clipboard-list mr-3"></i>
                Logs
            </a>
            <a href="/dashboard/api-tester" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-white hover:bg-gray-700">
                <i class="fas fa-vial mr-3"></i>
                API Tester
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col min-h-screen">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4">
                <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="flex items-center">
                    <span class="text-gray-700 mr-4">Welcome, User</span>
                    <button class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-user-circle"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-6">
            <?php echo $content ?? ''; ?>
        </main>

        <!-- Footer -->
        <footer class="bg-white shadow-sm mt-auto">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-gray-500 text-sm">
                    &copy; <?php echo date('Y'); ?> FireUp Framework. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html> 