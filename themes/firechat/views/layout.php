<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'FireChat' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#1E293B',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="/themes/firechat/assets/style.css">
</head>
<body class="h-full bg-gray-100 dark:bg-gray-900">
    <div class="min-h-full">
        <nav class="bg-white dark:bg-gray-800 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="/" class="text-xl font-bold text-gray-800 dark:text-white">FireChat</a>
                        </div>
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="/chat" class="text-gray-900 dark:text-white px-3 py-2 rounded-md text-sm font-medium">Chat</a>
                            <a href="/rooms" class="text-gray-900 dark:text-white px-3 py-2 rounded-md text-sm font-medium">Rooms</a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button id="theme-toggle" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                </svg>
                            </button>
                            <div class="ml-3 relative">
                                <div class="flex items-center">
                                    <span class="text-gray-700 dark:text-gray-300 mr-2"><?= htmlspecialchars($_SESSION['username']) ?></span>
                                    <a href="/logout" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Logout</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="/login" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium">Login</a>
                            <a href="/register" class="ml-3 bg-primary text-white px-3 py-2 rounded-md text-sm font-medium">Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <?= $content ?? '' ?>
        </main>
    </div>

    <script src="/themes/firechat/assets/script.js"></script>
    <script>
        // Theme toggle functionality
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                document.documentElement.classList.toggle('dark');
                localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            });
        }

        // Check for saved theme preference
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</body>
</html> 