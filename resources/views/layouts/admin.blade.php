<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Tailwind CSS & Font Awesome -->
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg">
        <div class="px-6 py-4 border-b">
            <h2 class="text-2xl font-bold text-gray-800">Admin Panel</h2>
        </div>
        <nav class="px-4 py-6">
            <ul class="space-y-4">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center text-gray-700 hover:bg-gray-200 px-4 py-2 rounded transition">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center text-gray-700 hover:bg-gray-200 px-4 py-2 rounded transition">
                        <i class="fas fa-users mr-3"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.properties.index') }}" class="flex items-center text-gray-700 hover:bg-gray-200 px-4 py-2 rounded transition">
                        <i class="fas fa-building mr-3"></i>
                        <span>Properties</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.reviews.index') }}" class="flex items-center text-gray-700 hover:bg-gray-200 px-4 py-2 rounded transition">
                        <i class="fas fa-star mr-3"></i>
                        <span>Reviews</span>
                    </a>
                </li>
                <li>
                    <!-- New Email Templates section -->
                    <a href="{{ route('admin.email_templates.index') }}" class="flex items-center text-gray-700 hover:bg-gray-200 px-4 py-2 rounded transition">
                        <i class="fas fa-envelope mr-3"></i>
                        <span>Email Templates</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.settings') }}" class="flex items-center text-gray-700 hover:bg-gray-200 px-4 py-2 rounded transition">
                        <i class="fas fa-cog mr-3"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center text-gray-700 hover:bg-gray-200 px-4 py-2 rounded transition">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow px-6 py-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
            <div class="flex items-center space-x-4">
                <a href="#" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-bell fa-lg"></i>
                </a>
                <a href="#" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-user-circle fa-lg"></i>
                </a>
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white shadow px-6 py-4">
            <div class="text-center text-sm text-gray-500">
                © {{ date('Y') }} Admin Panel. All rights reserved.
            </div>
        </footer>
    </div>
</body>
</html>

