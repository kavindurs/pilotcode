@extends('layouts.admin')

@section('active-subcategories', 'menu-item-active')
@section('page-title', 'View Subcategory')
@section('page-subtitle', 'View detailed information about this subcategory.')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <a href="{{ route('admin.subcategories.index') }}" class="text-gray-400 hover:text-white transition-colors">
                        Subcategories
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <span class="text-gray-300">{{ $subcategory->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-tag text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-white">{{ $subcategory->name }}</h2>
                        <p class="text-gray-400 text-sm">Subcategory Details</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.subcategories.edit', $subcategory->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    <a href="{{ route('admin.subcategories.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column - Basic Info -->
                <div class="space-y-6">
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Basic Information
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Subcategory Name</label>
                                <p class="text-white text-lg font-medium">{{ $subcategory->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Slug</label>
                                <p class="text-gray-300 font-mono">{{ $subcategory->slug }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Parent Category</label>
                                <p class="text-white">
                                    <span class="bg-blue-900 text-blue-300 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $subcategory->category->name }}
                                    </span>
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Status</label>
                                @if($subcategory->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-900 text-green-300">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-900 text-red-300">
                                        <i class="fas fa-times-circle mr-2"></i>
                                        Inactive
                                    </span>
                                @endif
                            </div>

                            @if($subcategory->description)
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Description</label>
                                <p class="text-gray-300 leading-relaxed">{{ $subcategory->description }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column - Stats & Actions -->
                <div class="space-y-6">
                    <!-- Stats -->
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Statistics
                        </h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Created</span>
                                <span class="text-white">{{ $subcategory->created_at->format('M d, Y \a\\t g:i A') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Last Updated</span>
                                <span class="text-white">{{ $subcategory->updated_at->format('M d, Y \a\\t g:i A') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">ID</span>
                                <span class="text-white font-mono">#{{ $subcategory->id }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-bolt mr-2"></i>
                            Quick Actions
                        </h3>

                        <div class="space-y-3">
                            @if($subcategory->is_active)
                                <form method="POST" action="{{ route('admin.subcategories.reject', $subcategory->id) }}" class="w-full">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors font-medium"
                                        onclick="return confirm('Are you sure you want to deactivate this subcategory?')"
                                    >
                                        <i class="fas fa-pause mr-2"></i>
                                        Deactivate Subcategory
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.subcategories.approve', $subcategory->id) }}" class="w-full">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors font-medium"
                                        onclick="return confirm('Are you sure you want to activate this subcategory?')"
                                    >
                                        <i class="fas fa-check mr-2"></i>
                                        Activate Subcategory
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.subcategories.destroy', $subcategory->id) }}" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors font-medium"
                                    onclick="return confirm('Are you sure you want to delete this subcategory? This action cannot be undone.')"
                                >
                                    <i class="fas fa-trash mr-2"></i>
                                    Delete Subcategory
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
