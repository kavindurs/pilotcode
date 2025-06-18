@extends('layouts.business')

@section('active-integrations', 'bg-blue-700')

@section('title', 'HTML Integration')
@section('page-title', 'HTML Integrations')
@section('page-subtitle', 'Manage HTML snippets for your website')

@section('content')
    <!-- Plan Information Section -->
    <div class="bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 text-blue-700 dark:text-blue-200 p-4 mb-6" role="alert">
        <div class="flex items-center">
            <div class="py-1">
                <i class="fas fa-info-circle text-2xl mr-4"></i>
            </div>
            <div>
                @if(isset($activePlan))
                    <p class="font-bold">Current Plan: {{ $activePlan->name }}</p>
                    <p>
                        HTML Character Limit: <span class="font-semibold">{{ $characterLimit }}</span> characters
                    </p>
                    <p class="mt-1">
                        <strong>Note:</strong> Only one HTML integration can be active at a time.
                    </p>
                    @if($activePlan->name !== 'Premium')
                        <p class="mt-2">Need more characters? <a href="{{ route('plans.index') }}" class="underline text-blue-800 dark:text-blue-300">Upgrade your plan</a> for increased limits!</p>
                    @endif
                @else
                    <p>Unable to determine your current plan. Please contact support.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Active Integration Preview -->
    @if(isset($activeIntegration))
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 px-6 py-4 rounded-t-lg border-b border-green-200 dark:border-green-700">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-300 text-lg"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-green-800 dark:text-green-200">
                                {{ $activeIntegration->title }}
                            </h3>
                            <div class="flex items-center space-x-4 text-sm text-green-700 dark:text-green-300">
                                <span class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    {{ ucfirst($activeIntegration->placement) }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-tag mr-1"></i>
                                    {{ ucfirst($activeIntegration->integration_type) }}
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $activeIntegration->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 border border-green-200 dark:border-green-700">
                            <i class="fas fa-circle text-green-500 mr-1 text-xs"></i>
                            Active
                        </span>
                        <a href="{{ route('property.integrations.edit', $activeIntegration->id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Edit
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-6">
                <!-- HTML Content -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">HTML Code</h4>
                        <button onclick="copyToClipboard('html-content')" class="inline-flex items-center px-2 py-1 text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                            <i class="fas fa-copy mr-1"></i>
                            Copy
                        </button>
                    </div>
                    <div class="relative">
                        <pre id="html-content" class="bg-gray-900 dark:bg-gray-950 text-green-400 p-4 rounded-lg overflow-x-auto text-sm font-mono border border-gray-700"><code>{{ $activeIntegration->html_content }}</code></pre>
                    </div>
                </div>

                <!-- Preview Section -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide mb-3">Live Preview</h4>
                    <div class="bg-white dark:bg-gray-700 p-6 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 min-h-[120px] relative" id="active-preview">
                        <div class="absolute top-2 right-2">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                <i class="fas fa-eye mr-1"></i>
                                Preview
                            </span>
                        </div>
                        <div class="pt-6">
                            {!! $activeIntegration->html_content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const text = element.textContent;

            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(function() {
                    // Show success feedback
                    const button = event.target.closest('button');
                    const originalContent = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-check mr-1"></i>Copied!';
                    button.classList.add('text-green-600');

                    setTimeout(() => {
                        button.innerHTML = originalContent;
                        button.classList.remove('text-green-600');
                    }, 2000);
                });
            }
        }
        </script>
    @endif

    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Your HTML Integrations</h2>

        <a href="{{ route('property.integrations.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Add New Integration
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-200 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-200 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2">
            @if(isset($integrations) && count($integrations) > 0)
                <!-- Integrations Table -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Placement</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($integrations as $integration)
                                <tr class="{{ $integration->is_active ? 'bg-green-50 dark:bg-green-900/20' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ $integration->title }}
                                            @if($integration->is_active)
                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                                    Active
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $integration->integration_type }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ ucfirst($integration->placement) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $integration->is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                            {{ $integration->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $integration->created_at->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('property.integrations.edit', $integration->id) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('property.integrations.destroy', $integration->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                                                    onclick="return confirm('Are you sure you want to delete this integration?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                    <div class="text-gray-400 dark:text-gray-500 mb-4">
                        <i class="fas fa-code text-6xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No HTML Integrations Yet</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">You haven't added any HTML integrations yet.</p>
                    <a href="{{ route('property.integrations.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Add Your First Integration
                    </a>
                </div>
            @endif
        </div>

        <!-- Right Column - Instructions -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 sticky top-6">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">How to Use HTML Integrations</h3>
                <div class="prose max-w-none">
                    <p class="text-gray-700 dark:text-gray-300 text-sm">HTML integrations allow you to add custom code snippets to your website. Follow these steps:</p>
                    <ol class="list-decimal pl-5 space-y-2 mt-3 text-gray-700 dark:text-gray-300 text-sm">
                        <li>Create a new integration by clicking the "Add New Integration" button.</li>
                        <li>Give your integration a descriptive title.</li>
                        <li>Enter your HTML code (limited to {{ $characterLimit }} characters for your {{ $activePlan->name }} plan).</li>
                        <li>Select where you want the code to appear (header, footer, sidebar, etc.).</li>
                        <li>Set the integration as active or inactive. <strong>Remember, only one integration can be active at a time.</strong></li>
                        <li>Click "Save" to create your integration.</li>
                    </ol>
                    <p class="mt-4 text-gray-700 dark:text-gray-300 text-sm">Your HTML code will be automatically included in your website based on the placement you selected.</p>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4 mt-4">
                        <p class="text-yellow-700 dark:text-yellow-200 text-sm">
                            <strong>Note:</strong> Be careful when adding custom HTML. Invalid code could potentially cause display issues on your website.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
