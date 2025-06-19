@extends('layouts.admin')

@section('active-categories', 'menu-item-active')
@section('page-title', 'Category Details')
@section('page-subtitle', 'View category information and manage its subcategories.')

@section('content')
<div class="max-w-6xl mx-auto">
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
                    <a href="{{ route('admin.categories.index') }}" class="text-gray-400 hover:text-white transition-colors">
                        Categories
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <span class="text-gray-300">{{ $category->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl mb-6">
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-4">
                        <i class="fas fa-tag text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $category->name }}</h2>
                        <p class="text-gray-400">Category Details</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Category
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Categories
                    </a>
                </div>
            </div>
        </div>

        <!-- Category Basic Information -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Basic Info -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Basic Information
                        </h3>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">Category Name</label>
                                    <p class="text-white bg-gray-700 px-3 py-2 rounded-md">{{ $category->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-1">Slug</label>
                                    <p class="text-white bg-gray-700 px-3 py-2 rounded-md">{{ $category->slug }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-1">Description</label>
                                <div class="text-white bg-gray-700 px-3 py-2 rounded-md min-h-[80px]">
                                    {{ $category->description ?: 'No description provided.' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Stats & Status -->
                <div class="space-y-6">
                    <!-- Status Card -->
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Status & Stats
                        </h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Status</span>
                                @if($category->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-300">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Inactive
                                    </span>
                                @endif
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Subcategories</span>
                                <span class="text-white font-semibold">{{ $category->subcategories->count() }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Active Subcategories</span>
                                <span class="text-green-400 font-semibold">{{ $category->subcategories->where('is_active', 1)->count() }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Created</span>
                                <span class="text-white">{{ $category->created_at->format('M d, Y') }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Last Updated</span>
                                <span class="text-white">{{ $category->updated_at->format('M d, Y') }}</span>
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
                            @if($category->is_active)
                                <form method="POST" action="{{ route('admin.categories.reject', $category->id) }}">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors text-sm"
                                        onclick="return confirm('Are you sure you want to deactivate this category?')"
                                    >
                                        <i class="fas fa-pause mr-2"></i>
                                        Deactivate Category
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.categories.approve', $category->id) }}">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors text-sm"
                                        onclick="return confirm('Are you sure you want to activate this category?')"
                                    >
                                        <i class="fas fa-check mr-2"></i>
                                        Activate Category
                                    </button>
                                </form>
                            @endif

                            @if($category->subcategories->count() == 0)
                                <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors text-sm"
                                        onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone.')"
                                    >
                                        <i class="fas fa-trash mr-2"></i>
                                        Delete Category
                                    </button>
                                </form>
                            @else
                                <div class="w-full bg-gray-600 text-gray-400 px-4 py-2 rounded-lg text-sm text-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Cannot delete (has subcategories)
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subcategories Section -->
    @if($category->subcategories->count() > 0)
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-tags mr-2"></i>
                    Subcategories ({{ $category->subcategories->count() }})
                </h2>
                <a href="{{ route('admin.subcategories.index', ['category' => $category->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors text-sm">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Manage All Subcategories
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-900">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Subcategory</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @foreach($category->subcategories as $subcategory)
                    <tr class="hover:bg-gray-750 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-md bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                                        <i class="fas fa-tag text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-white">{{ $subcategory->name }}</div>
                                    <div class="text-sm text-gray-400">{{ $subcategory->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($subcategory->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-300">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-400">
                            {{ $subcategory->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a
                                    href="{{ route('admin.subcategories.show', $subcategory->id) }}"
                                    class="bg-gray-600 hover:bg-gray-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                    title="View Subcategory"
                                >
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a
                                    href="{{ route('admin.subcategories.edit', $subcategory->id) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                    title="Edit Subcategory"
                                >
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl p-12 text-center">
        <i class="fas fa-tags text-4xl text-gray-600 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-400 mb-2">No Subcategories</h3>
        <p class="text-gray-500 mb-6">This category doesn't have any subcategories yet.</p>
        <a href="{{ route('admin.subcategories.index', ['category' => $category->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Manage Subcategories
        </a>
    </div>
    @endif
</div>
@endsection
