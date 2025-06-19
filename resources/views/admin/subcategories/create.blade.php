@extends('layouts.admin')

@section('active-subcategories', 'menu-item-active')
@section('page-title', 'Create Subcategory')
@section('page-subtitle', 'Add a new subcategory to organize your categories better.')

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
                    <span class="text-gray-300">Create New</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">Create New Subcategory</h2>
                    <p class="text-gray-400 text-sm">Add a new subcategory with details and settings</p>
                </div>
                <a href="{{ route('admin.subcategories.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Subcategories
                </a>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.subcategories.store') }}" class="p-6">
            @csrf

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
                                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                    Subcategory Name *
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                    placeholder="Enter subcategory name"
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
                                    placeholder="auto-generated from name"
                                >
                                @error('slug')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-500 text-sm mt-1">URL-friendly version of the name</p>
                            </div>

                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-300 mb-2">
                                    Parent Category *
                                </label>
                                <select
                                    id="category_id"
                                    name="category_id"
                                    required
                                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category_id') border-red-500 @enderror"
                                >
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
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
                                    placeholder="Enter subcategory description (optional)"
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
                                    Active Subcategory
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Preview & Guidelines -->
                <div class="space-y-6">
                    <!-- Guidelines -->
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-lightbulb mr-2"></i>
                            Guidelines
                        </h3>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-green-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-white font-medium">Choose a clear, descriptive name</p>
                                    <p class="text-gray-400">Use simple, understandable names that users can easily recognize</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-green-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-white font-medium">Select the appropriate parent category</p>
                                    <p class="text-gray-400">Ensure the subcategory logically belongs under the chosen category</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-green-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-white font-medium">Add a helpful description</p>
                                    <p class="text-gray-400">Provide context about what types of businesses belong in this subcategory</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-white font-medium">Slug auto-generation</p>
                                    <p class="text-gray-400">The slug will be automatically generated from the name, but you can customize it</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="bg-gray-750 border border-gray-600 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-eye mr-2"></i>
                            Preview
                        </h3>

                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-md bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center mr-3">
                                    <i class="fas fa-tag text-white text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-white" id="preview-name">New Subcategory</div>
                                    <div class="text-sm text-gray-400" id="preview-slug">new-subcategory</div>
                                </div>
                            </div>

                            <div class="text-sm text-gray-400" id="preview-category">Select a category above</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-700">
                <a
                    href="{{ route('admin.subcategories.index') }}"
                    class="px-6 py-2 text-gray-400 hover:text-white transition-colors"
                >
                    Cancel
                </a>
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-2 rounded-lg transition-colors font-medium"
                >
                    <i class="fas fa-plus mr-2"></i>
                    Create Subcategory
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

    // Update preview
    document.getElementById('preview-name').textContent = name || 'New Subcategory';
    document.getElementById('preview-slug').textContent = slug || 'new-subcategory';
});

// Update category preview
document.getElementById('category_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const categoryName = selectedOption.textContent;
    document.getElementById('preview-category').textContent =
        this.value ? `Under: ${categoryName}` : 'Select a category above';
});
</script>
@endsection
