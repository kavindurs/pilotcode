@extends('layouts.admin')

@section('active-categories', 'menu-item-active')
@section('page-title', 'Create Category')
@section('page-subtitle', 'Add a new category to organize your content and subcategories.')

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
                    <a href="{{ route('admin.categories.index') }}" class="text-gray-400 hover:text-white transition-colors">
                        Categories
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-600 mx-2"></i>
                    <span class="text-gray-300">Create New Category</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">Create New Category</h2>
                    <p class="text-gray-400 text-sm">Add a new category with name, slug, and description</p>
                </div>
                <a href="{{ route('admin.categories.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Categories
                </a>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.categories.store') }}" class="p-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column - Form Fields -->
                <div class="space-y-6">
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Category Information
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                    Category Name *
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                    placeholder="Enter category name"
                                >
                                @error('name')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-300 mb-2">
                                    Slug *
                                </label>
                                <input
                                    type="text"
                                    id="slug"
                                    name="slug"
                                    value="{{ old('slug') }}"
                                    required
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('slug') border-red-500 @enderror"
                                    placeholder="category-slug"
                                >
                                <p class="text-gray-500 text-xs mt-1">URL-friendly version of the name (auto-generated from name)</p>
                                @error('slug')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                                    Description
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    rows="4"
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                    placeholder="Enter category description (optional)"
                                >{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center">
                                <input
                                    type="checkbox"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500"
                                >
                                <label for="is_active" class="ml-2 text-sm text-gray-300">
                                    Active Category
                                </label>
                                <p class="ml-2 text-gray-500 text-xs">(Category will be visible and usable)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Guidelines -->
                <div class="space-y-6">
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-lightbulb mr-2"></i>
                            Guidelines
                        </h3>

                        <div class="space-y-4 text-sm text-gray-300">
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-green-400 mr-2 mt-0.5"></i>
                                <div>
                                    <strong>Category Name:</strong> Choose a clear, descriptive name that represents the content type.
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-green-400 mr-2 mt-0.5"></i>
                                <div>
                                    <strong>Slug:</strong> Will be auto-generated from the name. Used in URLs and must be unique.
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-green-400 mr-2 mt-0.5"></i>
                                <div>
                                    <strong>Description:</strong> Optional but helpful for explaining the category's purpose.
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-green-400 mr-2 mt-0.5"></i>
                                <div>
                                    <strong>Active Status:</strong> Only active categories will be visible to users.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            What's Next?
                        </h3>

                        <div class="space-y-3 text-sm text-gray-300">
                            <p>After creating this category, you can:</p>
                            <ul class="space-y-2">
                                <li class="flex items-center">
                                    <i class="fas fa-plus text-blue-400 mr-2"></i>
                                    Add subcategories to organize content further
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-edit text-yellow-400 mr-2"></i>
                                    Edit category details anytime
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-building text-green-400 mr-2"></i>
                                    Assign properties to this category
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-700">
                <a
                    href="{{ route('admin.categories.index') }}"
                    class="px-6 py-2 text-gray-400 hover:text-white transition-colors"
                >
                    Cancel
                </a>
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-2 rounded-lg transition-colors font-medium"
                >
                    <i class="fas fa-plus mr-2"></i>
                    Create Category
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9 -]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    document.getElementById('slug').value = slug;
});
</script>
@endsection
