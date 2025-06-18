@extends('layouts.business')

@section('active-widgets', 'bg-blue-600')

@section('title', 'Manage Widgets')
@section('page-title', 'Manage Widgets')
@section('page-subtitle', 'Customize review display widgets for your website')

@section('content')
    <!-- Success Messages -->
    @if(session('success'))
        <div class="bg-green-900 border-l-4 border-green-400 text-green-100 p-4 mb-6 rounded-r-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Messages -->
    @if(session('error'))
        <div class="bg-red-900 border-l-4 border-red-400 text-red-100 p-4 mb-6 rounded-r-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Plan Info Bar -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-3 md:mb-0">
                <h3 class="text-lg font-semibold text-white">Your Plan: <span class="text-blue-400">{{ $planInfo['planType'] }}</span></h3>
                <p class="text-sm text-gray-300">
                    Widgets: <span class="font-medium text-white">{{ $planInfo['widgetCount'] }} / {{ $planInfo['widgetLimit'] }}</span>
                    <span class="text-sm {{ $planInfo['remainingWidgets'] > 0 ? 'text-green-400' : 'text-red-400' }}">
                        ({{ $planInfo['remainingWidgets'] }} remaining)
                    </span>
                </p>
            </div>

            @if(!$planInfo['canAddWidget'])
                <div class="flex items-center">
                    <span class="text-red-400 mr-3">You've reached your widget limit!</span>
                    <a href="{{ route('property.widgets.upgrade') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Upgrade Plan</a>
                </div>
            @elseif($planInfo['remainingWidgets'] <= 1)
                <div class="flex items-center">
                    <span class="text-yellow-400 mr-3">Almost at your limit!</span>
                    <a href="{{ route('property.widgets.upgrade') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Upgrade Plan</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Instructions Card -->
    <div class="bg-gray-800 border border-gray-700 border-l-4 border-l-blue-500 rounded-r-lg p-6 mb-8 shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0 pt-0.5">
                <i class="fas fa-lightbulb text-2xl text-yellow-400 mr-4"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white mb-1">Widget Management</h3>
                <p class="text-gray-300 mb-2">Widgets help you display your reviews and business information on your website.</p>
                <ul class="text-sm text-gray-300 space-y-1 mt-2">
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-400 mr-2"></i> Add different types of widgets to showcase your reviews</li>
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-400 mr-2"></i> Use social media widgets to connect with customers</li>
                    <li class="flex items-center"><i class="fas fa-check-circle text-green-400 mr-2"></i> Customize the appearance and content of each widget</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-white">Your Widgets</h2>
            @if(!$planInfo['canAddWidget'])
                <button class="px-4 py-2 bg-gray-600 text-gray-400 rounded-md cursor-not-allowed" disabled>
                    <i class="fas fa-plus mr-1"></i> Create New Widget
                </button>
                <span class="ml-2 text-red-400">Upgrade your plan to add more widgets</span>
            @else
                <a href="{{ route('property.widgets.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-1"></i> Create New Widget
                </a>
            @endif
        </div>

        @if($widgets->isEmpty())
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-puzzle-piece text-4xl text-gray-500"></i>
                </div>
                <h3 class="text-xl font-medium text-white mb-2">No Widgets Yet</h3>
                <p class="text-gray-400 mb-6">Start adding widgets to enhance your website with review displays and social media connections.</p>
                <a href="{{ route('property.widgets.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Create Your First Widget
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($widgets as $widget)
                <div class="border border-gray-600 bg-gray-700 rounded-lg p-4 hover:shadow-lg hover:border-gray-500 transition-all">
                    <h3 class="font-medium text-lg mb-2 text-white">{{ $widget->title }}</h3>
                    <p class="text-gray-300 mb-3">{{ Str::limit($widget->content, 100) }}</p>
                    <div class="flex justify-between items-center text-sm">
                        <span class="px-2 py-1 bg-blue-900 text-blue-200 rounded-full">{{ $widget->widget_type }}</span>
                        <div class="flex space-x-2">
                            <a href="{{ route('property.widgets.edit', $widget->id) }}" class="text-blue-400 hover:text-blue-300 hover:underline transition-colors">Edit</a>
                            <form action="{{ route('property.widgets.destroy', $widget->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this widget?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 hover:underline transition-colors">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Widget Type Guide Section -->
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-white mb-6">Widget Types Guide</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="border border-gray-600 bg-gray-700 rounded-lg p-5 hover:shadow-lg hover:border-gray-500 transition-all">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 rounded-full bg-blue-900 flex items-center justify-center mr-3">
                        <i class="fas fa-certificate text-blue-400"></i>
                    </div>
                    <h3 class="font-semibold text-white">Badge Widget</h3>
                </div>
                <p class="text-sm text-gray-300">Display a verified business badge on your website to build trust with customers.</p>
            </div>

            <div class="border border-gray-600 bg-gray-700 rounded-lg p-5 hover:shadow-lg hover:border-gray-500 transition-all">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 rounded-full bg-pink-900 flex items-center justify-center mr-3">
                        <i class="fab fa-instagram text-pink-400"></i>
                    </div>
                    <h3 class="font-semibold text-white">Social Media Widget</h3>
                </div>
                <p class="text-sm text-gray-300">Connect your social media profiles to engage with customers across platforms.</p>
            </div>

            <div class="border border-gray-600 bg-gray-700 rounded-lg p-5 hover:shadow-lg hover:border-gray-500 transition-all">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 rounded-full bg-green-900 flex items-center justify-center mr-3">
                        <i class="fas fa-image text-green-400"></i>
                    </div>
                    <h3 class="font-semibold text-white">Cover Image Widget</h3>
                </div>
                <p class="text-sm text-gray-300">Add attractive cover images to showcase your business and products.</p>
            </div>

            <div class="border border-gray-600 bg-gray-700 rounded-lg p-5 hover:shadow-lg hover:border-gray-500 transition-all">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 rounded-full bg-purple-900 flex items-center justify-center mr-3">
                        <i class="fas fa-question-circle text-purple-400"></i>
                    </div>
                    <h3 class="font-semibold text-white">FAQ Widget</h3>
                </div>
                <p class="text-sm text-gray-300">Create a FAQ section to answer common customer questions about your business.</p>
            </div>
        </div>
    </div>

    <!-- Integration Help Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-8 text-white mb-8">
        <div class="flex flex-col md:flex-row md:items-center">
            <div class="mb-6 md:mb-0 md:mr-8">
                <h2 class="text-2xl font-semibold mb-3">Need Help Adding Widgets To Your Website?</h2>
                <p class="text-blue-100 mb-4">Our widgets can be easily added to any website with a simple code snippet. Check our integration guide for detailed instructions.</p>
                <a href="{{ route('property.integrations') }}" class="inline-flex items-center px-4 py-2 bg-white text-blue-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-code mr-2"></i> View Integration Guide
                </a>
            </div>
            <div class="flex-shrink-0 mx-auto md:mx-0">
                <div class="w-32 h-32 bg-blue-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-puzzle-piece text-6xl text-white opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
@endsection
