<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Admin Dashboard')</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Add Google Font for improved typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @stack('head')
    <style>
      body {
        font-family: 'Inter', sans-serif;
      }
      .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
      }
      .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.1);
      }
      .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
      }
      .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.2);
      }
      .menu-item-active {
        background: linear-gradient(90deg, #DC2626 0%, #B91C1C 100%);
        border-left: 3px solid #FACC15;
      }
      .menu-item:hover {
        background: linear-gradient(90deg, rgba(220, 38, 38, 0.1) 0%, rgba(185, 28, 28, 0.1) 100%);
      }
      .glass-effect {
        background: rgba(17, 24, 39, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
      }
    </style>
  </head>
  <body class="bg-gray-900 font-sans">
    <div x-data="{ showSidebar: localStorage.getItem('adminSidebarOpen') === 'false' ? false : window.innerWidth >= 768 }" class="flex flex-col md:flex-row min-h-screen">
      <!-- Universal navigation toggle button (both mobile and desktop) -->
      <div class="fixed top-0 left-0 z-30 m-4">
        <button @click="showSidebar = !showSidebar; localStorage.setItem('adminSidebarOpen', showSidebar)"
                class="p-2 rounded-md bg-gray-800 text-white focus:outline-none border border-gray-700 hover:bg-gray-700
                       transition-all duration-300 shadow-lg group">
          <svg x-show="!showSidebar" class="h-6 w-6 text-red-400 group-hover:text-yellow-400 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg x-show="showSidebar" class="h-6 w-6 text-red-400 group-hover:text-yellow-400 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Sidebar overlay for mobile only -->
      <div x-show="showSidebar && window.innerWidth < 768" @click="showSidebar = false; localStorage.setItem('adminSidebarOpen', 'false')"
           class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden transition-opacity duration-200"
           x-transition:enter="ease-out duration-300"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:leave="ease-in duration-200"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0"></div>

      <!-- Sidebar with premium styling -->
      <aside :class="{'translate-x-0': showSidebar, '-translate-x-full': !showSidebar}"
             class="w-64 fixed inset-y-0 left-0 z-20 glass-effect border-r border-gray-800 shadow-xl
                    transform transition-transform duration-300 ease-in-out overflow-y-auto custom-scrollbar">
        <!-- Logo area -->
        <div class="px-6 py-6 border-b border-gray-800">
          <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white flex items-center">
              <span class="bg-gradient-to-r from-red-500 to-red-700 p-1.5 rounded mr-2 shadow-md">
                <i class="fas fa-shield-alt text-white"></i>
              </span>
              <span class="bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-300">
                Admin Panel
              </span>
            </h2>
          </div>
        </div>

        <!-- Admin profile section -->
        <div class="px-6 py-4 border-b border-gray-800">
          <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-600 to-red-800 flex items-center justify-center shadow-md">
              <i class="fas fa-user-shield text-white"></i>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-white">{{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</p>
              <p class="text-xs text-gray-400">{{ Auth::guard('admin')->user()->email ?? 'admin@example.com' }}</p>
            </div>
          </div>
        </div>

        <!-- Navigation menu -->
        <nav class="mt-4 px-3">
          <div class="text-xs uppercase text-gray-500 font-semibold px-3 mb-2">Main Menu</div>
          <ul class="space-y-1">
            <!-- Dashboard Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-dashboard', '')">
              <a href="{{ route('admin.dashboard') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-red-900 group-hover:bg-red-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-tachometer-alt text-red-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Dashboard</span>
              </a>
            </li>

            <!-- Staff Management Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-staff', '')">
              <a href="{{ route('admin.staff.index') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-red-900 group-hover:bg-red-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-user-tie text-red-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Staff Management</span>
              </a>
            </li>

            <!-- Users Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-users', '')">
              <a href="{{ route('admin.users.index') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-red-900 group-hover:bg-red-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-users text-red-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Users</span>
              </a>
            </li>

            <!-- Properties Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-properties', '')">
              <a href="{{ route('admin.properties.index') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-red-900 group-hover:bg-red-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-building text-red-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Properties</span>
              </a>
            </li>

            <div class="text-xs uppercase text-gray-500 font-semibold px-3 mb-2 mt-6">Content Management</div>

            <!-- Reviews Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-reviews', '')">
              <a href="{{ route('admin.reviews.index') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-red-900 group-hover:bg-red-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-star text-red-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Reviews</span>
              </a>
            </li>

            <!-- Email Templates Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-email-templates', '')">
              <a href="{{ route('admin.email_templates.index') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-red-900 group-hover:bg-red-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-envelope text-red-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Email Templates</span>
              </a>
            </li>

            <!-- Categories Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-categories', '')">
              <a href="{{ route('admin.categories.index') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-red-900 group-hover:bg-red-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-tags text-red-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Categories</span>
              </a>
            </li>

            <!-- Subcategories Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-subcategories', '')">
              <a href="{{ route('admin.subcategories.index') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-red-900 group-hover:bg-red-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-tag text-red-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Subcategories</span>
              </a>
            </li>

            <!-- Plans Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-plans', '')">
              <a href="{{ route('admin.plans.index') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-red-900 group-hover:bg-red-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-medal text-red-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Plans</span>
              </a>
            </li>

            <!-- Referrals Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-referrals', '')">
              <a href="{{ route('admin.referrals.index') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-red-900 group-hover:bg-red-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-handshake text-red-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Referrals</span>
              </a>
            </li>

            <div class="text-xs uppercase text-gray-500 font-semibold px-3 mb-2 mt-6">Financial</div>

            <!-- Payments Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-payments', '')">
              <a href="{{ route('admin.payments.index') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-red-900 group-hover:bg-red-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-credit-card text-red-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Payments</span>
              </a>
            </li>

            <!-- Wallets Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-wallets', '')">
              <a href="{{ route('admin.wallets.index') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-red-900 group-hover:bg-red-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-wallet text-red-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Wallets</span>
              </a>
            </li>

            <div class="text-xs uppercase text-gray-500 font-semibold px-3 mb-2 mt-6">System</div>

            <!-- Claim Business Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-claim-business', '')">
              <a href="#" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-red-900 group-hover:bg-red-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-hand-holding-heart text-red-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Claim Business</span>
              </a>
            </li>

            <!-- Ads Manager Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-ads-manager', '')">
              <a href="#" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-red-900 group-hover:bg-red-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-bullhorn text-red-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Ads Manager</span>
              </a>
            </li>

            <!-- Analytics Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-analytics', '')">
              <a href="#" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-red-900 group-hover:bg-red-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-chart-line text-red-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Analytics</span>
              </a>
            </li>


            <!-- Settings Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-settings', '')">
              <a href="{{ route('admin.profile.edit') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-red-900 group-hover:bg-red-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-cog text-red-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Settings</span>
              </a>
            </li>

            <!-- Logout Option -->
            <li class="menu-item rounded-md overflow-hidden mt-6">
              <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                  <span class="w-8 h-8 flex items-center justify-center bg-red-900 group-hover:bg-red-700 rounded-md transition-colors mr-3">
                    <i class="fas fa-sign-out-alt text-red-300 group-hover:text-white transition-colors"></i>
                  </span>
                  <span>Logout</span>
                </button>
              </form>
            </li>
          </ul>
        </nav>

        <!-- Bottom admin info box -->
        <div class="mt-6 mx-3 mb-6">
          <div class="p-4 rounded-lg bg-gradient-to-br from-red-900 to-red-800 shadow-lg border border-red-700">
            <div class="flex items-center justify-between mb-2">
              <div class="text-sm font-medium text-white">System Status</div>
              <div class="px-2 py-1 rounded text-xs font-medium bg-green-700 text-white">
                Online
              </div>
            </div>
            <div class="text-xs text-gray-300">
              <div class="flex justify-between mb-1">
                <span>Server:</span>
                <span class="text-green-400">Active</span>
              </div>
              <div class="flex justify-between">
                <span>Database:</span>
                <span class="text-green-400">Connected</span>
              </div>
            </div>
          </div>
        </div>
      </aside>

      <!-- Main Content - adjusts width based on sidebar state -->
      <main :class="{'md:ml-64': showSidebar, 'ml-0': !showSidebar}"
            class="flex-1 md:ml-0 md:pt-6 px-4 md:px-6 bg-gray-900 transition-all duration-300">
        <!-- Enhanced Professional Header -->
        <header class="relative mb-8">
          <!-- Modern Gradient Background -->
          <div class="rounded-lg overflow-hidden shadow-2xl">
            <div class="bg-gradient-to-r from-gray-900 via-red-900 to-gray-900 h-64 relative">
              <!-- Abstract Pattern -->
              <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'100\' height=\'100\' viewBox=\'0 0 100 100\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z\' fill=\'%23DC2626\' fill-opacity=\'0.1\' fill-rule=\'evenodd\'/%3E%3C/svg%3E'); background-position: center center;">
              </div>

              <!-- Content Container with Better Spacing -->
              <div class="h-full flex items-center relative z-10">
                <div class="px-8 md:px-12 w-full">
                  <!-- Title Section with Gradient Text -->
                  <div class="max-w-4xl">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-3 tracking-tight">
                      <span class="bg-clip-text text-transparent bg-gradient-to-r from-white via-red-100 to-gray-300">
                        @yield('page-title', 'Admin Dashboard')
                      </span>
                    </h1>

                    <div class="w-20 h-1 bg-gradient-to-r from-yellow-500 to-yellow-400 rounded mb-4"></div>

                    <p class="text-base md:text-lg text-gray-300 mb-6 max-w-2xl">
                      @yield('page-subtitle', 'Manage your platform with powerful administrative tools.')
                    </p>
                  </div>
                </div>

                <!-- 3D floating objects design element -->
                <div class="absolute right-8 bottom-8 opacity-20 hidden lg:block">
                  <div class="relative">
                    <!-- Animated particle effects -->
                    <div class="absolute -top-10 -left-10 w-20 h-20 rounded-full bg-red-500 opacity-10 animate-pulse"></div>
                    <div class="absolute top-5 -right-5 w-16 h-16 rounded-full bg-yellow-500 opacity-10 animate-pulse" style="animation-delay: 1s"></div>
                    <i class="fas fa-shield-alt text-9xl text-white opacity-80"></i>
                  </div>
                </div>
              </div>
            </div>

            <!-- Statistics Bar -->
            <div class="bg-gray-800 bg-opacity-95 py-4 px-8 flex flex-wrap items-center justify-between border-t border-gray-700 shadow-inner">
              <div class="flex items-center text-sm text-gray-300 mr-6 my-1">
                <div class="w-8 h-8 rounded-md bg-gray-700 flex items-center justify-center mr-2 shadow-inner">
                  <i class="fas fa-calendar-alt text-yellow-500"></i>
                </div>
                <span>{{ now()->format('F j, Y') }}</span>
              </div>

              <div class="flex items-center text-sm text-gray-300 mr-6 my-1">
                <div class="w-8 h-8 rounded-md bg-gray-700 flex items-center justify-center mr-2 shadow-inner">
                  <i class="fas fa-user-shield text-yellow-500"></i>
                </div>
                <span>{{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</span>
              </div>

              <div class="flex items-center text-sm text-gray-300 my-1">
                <div class="w-8 h-8 rounded-md bg-gray-700 flex items-center justify-center mr-2 shadow-inner">
                  <i class="fas fa-server text-yellow-500"></i>
                </div>
                <span>System Online</span>
              </div>
            </div>
          </div>
        </header>

        <div class="content mb-20">
          @yield('content')
        </div>
      </main>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Apply active class to current menu item - check each menu item individually
        const menuItems = document.querySelectorAll('li.menu-item');

        menuItems.forEach(item => {
          // Check if this specific menu item has the bg-gray-900 class (active state)
          if (item.classList.contains('bg-gray-900')) {
            item.classList.add('menu-item-active');
            item.classList.remove('menu-item');
          }
        });

        // Handle resize events
        window.addEventListener('resize', () => {
          const sidebarOpen = localStorage.getItem('adminSidebarOpen');

          // If localStorage doesn't have a preference yet, set default based on window width
          if (sidebarOpen === null) {
            localStorage.setItem('adminSidebarOpen', window.innerWidth >= 768 ? 'true' : 'false');
          }
        });
      });
    </script>

    @stack('scripts')
  </body>
</html>

