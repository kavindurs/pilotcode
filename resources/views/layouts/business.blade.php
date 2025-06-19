<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Business Dashboard')</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
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
        background: linear-gradient(90deg, #3B82F6 0%, #1E40AF 100%);
        border-left: 3px solid #FACC15;
      }
      .menu-item:hover {
        background: linear-gradient(90deg, rgba(59, 130, 246, 0.1) 0%, rgba(30, 64, 175, 0.1) 100%);
      }
      .glass-effect {
        background: rgba(17, 24, 39, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
      }
    </style>
  </head>
  <body class="bg-gray-900 font-sans">
    <div x-data="{ showSidebar: localStorage.getItem('sidebarOpen') === 'false' ? false : window.innerWidth >= 768 }" class="flex flex-col md:flex-row min-h-screen">
      <!-- Universal navigation toggle button (both mobile and desktop) -->
      <div class="fixed top-0 left-0 z-30 m-4">
        <button @click="showSidebar = !showSidebar; localStorage.setItem('sidebarOpen', showSidebar)"
                class="p-2 rounded-md bg-gray-800 text-white focus:outline-none border border-gray-700 hover:bg-gray-700
                       transition-all duration-300 shadow-lg group">
          <svg x-show="!showSidebar" class="h-6 w-6 text-blue-400 group-hover:text-yellow-400 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg x-show="showSidebar" class="h-6 w-6 text-blue-400 group-hover:text-yellow-400 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Sidebar overlay for mobile only -->
      <div x-show="showSidebar && window.innerWidth < 768" @click="showSidebar = false; localStorage.setItem('sidebarOpen', 'false')"
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
              <span class="bg-gradient-to-r from-blue-500 to-blue-700 p-1.5 rounded mr-2 shadow-md">
                <i class="fas fa-tachometer-alt text-white"></i>
              </span>
              <span class="bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-300">
                Dashboard
              </span>
            </h2>
          </div>
        </div>

        <!-- User profile section -->
        <div class="px-6 py-4 border-b border-gray-800">
          <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center shadow-md">
              <i class="fas fa-user text-white"></i>
            </div>
            <div class="ml-3">
              @php
                $currentProperty = null;
                if (session('property_id')) {
                  $currentProperty = \App\Models\Property::find(session('property_id'));
                }
              @endphp
              <p class="text-sm font-medium text-white">{{ $currentProperty->business_name ?? (Auth::user()->name ?? 'User') }}</p>
              <p class="text-xs text-gray-400">{{ $currentProperty->business_email ?? (Auth::user()->email ?? 'user@example.com') }}</p>
            </div>
          </div>
        </div>

        <!-- Navigation menu -->
        <nav class="mt-4 px-3">
          <div class="text-xs uppercase text-gray-500 font-semibold px-3 mb-2">Main Menu</div>
          <ul class="space-y-1">
            <!-- Home Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-home', '')">
              <a href="{{ route('property.dashboard') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-blue-900 group-hover:bg-blue-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-home text-blue-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Home</span>
              </a>
            </li>



            <!-- Products Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-products', '')">
              <a href="{{ route('property.products') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-blue-900 group-hover:bg-blue-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-box text-blue-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Products</span>
              </a>
            </li>

            <div class="text-xs uppercase text-gray-500 font-semibold px-3 mb-2 mt-6">Review Management</div>

            <!-- Reviews Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-reviews', '')">
              <a href="{{ route('property.reviews') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-blue-900 group-hover:bg-blue-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-star text-blue-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Reviews</span>
              </a>
            </li>

            <!-- Review Invitations Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-invitations', '')">
              <a href="{{ route('property.invitations') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-blue-900 group-hover:bg-blue-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-envelope-open-text text-blue-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Invitations</span>
              </a>
            </li>

            <!-- Review Analysis Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-review-analysis', '')">
              <a href="{{ route('property.review.analysis') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-blue-900 group-hover:bg-blue-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-chart-bar text-blue-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Analysis</span>
              </a>
            </li>

            <div class="text-xs uppercase text-gray-500 font-semibold px-3 mb-2 mt-6">Integration</div>

            <!-- HTML Integration Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-integrations', '')">
              <a href="{{ route('property.integrations') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-blue-900 group-hover:bg-blue-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-code text-blue-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>HTML Integration</span>
              </a>
            </li>

            <!-- Widgets Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-widgets', '')">
              <a href="{{ route('property.widgets') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-blue-900 group-hover:bg-blue-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-puzzle-piece text-blue-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Widgets</span>
              </a>
            </li>

            <!-- Ads Manager Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-ads-manager', '')">
              <span class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200 cursor-not-allowed opacity-75">
                <span class="w-8 h-8 flex items-center justify-center bg-blue-900 group-hover:bg-blue-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-bullhorn text-blue-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Ads Manager</span>
                <span class="ml-auto text-xs bg-yellow-600 text-white px-2 py-1 rounded-full">Soon</span>
              </span>
            </li>

            <div class="text-xs uppercase text-gray-500 font-semibold px-3 mb-2 mt-6">Account</div>

            <!-- Settings Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-settings', '')">
              <a href="{{ route('property.settings') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-blue-900 group-hover:bg-blue-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-cog text-blue-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Settings</span>
              </a>
            </li>

            <!-- Pricing Tab -->
            <li class="menu-item rounded-md overflow-hidden @yield('active-pricing', '')">
              <a href="{{ route('plans.index') }}" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200">
                <span class="w-8 h-8 flex items-center justify-center bg-blue-900 group-hover:bg-blue-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-dollar-sign text-blue-300 group-hover:text-yellow-400 transition-colors"></i>
                </span>
                <span>Pricing</span>
              </a>
            </li>

            <!-- Logout Option -->
            <li class="menu-item rounded-md overflow-hidden mt-6">
              <a href="#" class="flex items-center text-gray-200 py-3 px-3 rounded-md group transition-all duration-200"
                 onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="w-8 h-8 flex items-center justify-center bg-red-900 group-hover:bg-red-700 rounded-md transition-colors mr-3">
                  <i class="fas fa-sign-out-alt text-red-300 group-hover:text-white transition-colors"></i>
                </span>
                <span>Logout</span>
              </a>
              <form id="logout-form" action="{{ route('property.logout') }}" method="POST" class="hidden">
                @csrf
              </form>
            </li>
          </ul>
        </nav>

        <!-- Bottom promo box -->
        <div class="mt-6 mx-3 mb-6">
          <div class="p-4 rounded-lg bg-gradient-to-br from-blue-900 to-blue-800 shadow-lg border border-blue-700">
            <div class="flex items-center justify-between mb-2">
              <div class="text-sm font-medium text-white">Current Plan</div>
              <div class="px-2 py-1 rounded text-xs font-medium bg-blue-700 text-white">
                @php
                  $planName = 'Free';
                  if (session('property_id')) {
                    $currentProperty = \App\Models\Property::find(session('property_id'));
                    if ($currentProperty) {
                      // Get the latest completed payment for this property
                      $currentPayment = \App\Models\Payment::where('property_id', $currentProperty->id)
                                        ->whereIn('status', ['completed', 'success'])
                                        ->latest()
                                        ->first();

                      if ($currentPayment && $currentPayment->plan_id) {
                        $planDetails = \App\Models\Plan::find($currentPayment->plan_id);
                        if ($planDetails) {
                          $planName = $planDetails->name;
                        }
                      }
                    }
                  }
                @endphp
                {{ $planName }}
              </div>
            </div>
            <a href="{{ route('plans.index') }}" class="mt-2 flex items-center justify-center px-3 py-2 text-sm text-white bg-yellow-600 hover:bg-yellow-500 transition-colors rounded-md font-medium">
              <i class="fas fa-arrow-up mr-1"></i> Upgrade Plan
            </a>
          </div>
        </div>
      </aside>

      <!-- Main Content - adjusts width based on sidebar state -->
      <main :class="{'md:ml-64': showSidebar, 'ml-0': !showSidebar}"
            class="flex-1 md:ml-0  md:pt-6 px-4 md:px-6 bg-gray-900 transition-all duration-300">
        <!-- Enhanced Professional Header -->
        <header class="relative mb-8">
          <!-- Modern Gradient Background -->
          <div class="rounded-lg overflow-hidden shadow-2xl">
            <div class="bg-gradient-to-r from-gray-900 via-blue-900 to-gray-900 h-64 relative">
              <!-- Abstract Pattern -->
              <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'100\' height=\'100\' viewBox=\'0 0 100 100\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z\' fill=\'%232563EB\' fill-opacity=\'0.1\' fill-rule=\'evenodd\'/%3E%3C/svg%3E'); background-position: center center;">
              </div>

              <!-- Content Container with Better Spacing -->
              <div class="h-full flex items-center relative z-10">
                <div class="px-8 md:px-12 w-full">
                  <!-- Title Section with Gradient Text -->
                  <div class="max-w-4xl">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-3 tracking-tight">
                      <span class="bg-clip-text text-transparent bg-gradient-to-r from-white via-blue-100 to-gray-300">
                        @yield('page-title', 'Dashboard')
                      </span>
                    </h1>

                    <div class="w-20 h-1 bg-gradient-to-r from-yellow-500 to-yellow-400 rounded mb-4"></div>

                    <p class="text-base md:text-lg text-gray-300 mb-6 max-w-2xl">
                      @yield('page-subtitle', 'Welcome to your dashboard.')
                    </p>
                  </div>
                </div>

                <!-- 3D floating objects design element -->
                <div class="absolute right-8 bottom-8 opacity-20 hidden lg:block">
                  <div class="relative">
                    <!-- Animated particle effects -->
                    <div class="absolute -top-10 -left-10 w-20 h-20 rounded-full bg-blue-500 opacity-10 animate-pulse"></div>
                    <div class="absolute top-5 -right-5 w-16 h-16 rounded-full bg-yellow-500 opacity-10 animate-pulse" style="animation-delay: 1s"></div>
                    <i class="fas fa-chart-line text-9xl text-white opacity-80"></i>
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
                  <i class="fas fa-user-check text-yellow-500"></i>
                </div>
                <span>{{ Auth::user()->name ?? 'User' }}</span>
              </div>

              @if(isset($property) && isset($property->business_name))
              <div class="flex items-center text-sm text-gray-300 my-1">
                <div class="w-8 h-8 rounded-md bg-gray-700 flex items-center justify-center mr-2 shadow-inner">
                  <i class="fas fa-building text-yellow-500"></i>
                </div>
                <span>{{ $property->business_name }}</span>
              </div>
              @endif
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
          const sidebarOpen = localStorage.getItem('sidebarOpen');

          // If localStorage doesn't have a preference yet, set default based on window width
          if (sidebarOpen === null) {
            localStorage.setItem('sidebarOpen', window.innerWidth >= 768 ? 'true' : 'false');
          }
        });
      });
    </script>

    @stack('scripts')
  </body>
</html>

