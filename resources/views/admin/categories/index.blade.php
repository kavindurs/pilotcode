@extends('layouts.admin')

@section('active-categories', 'menu-item-active')
@section('page-title', 'Categories Management')
@section('page-subtitle', 'Manage all categories and their subcategories, approve, reject or edit them.')

@section('content')
<div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-white">Categories</h2>
                <p class="text-gray-400 text-sm">Total: {{ $categories->total() }} categories</p>
            </div>

            <!-- Search Form and Actions -->
            <div class="flex items-center gap-3">
                <!-- Add New Category Button -->
                <a href="{{ route('admin.categories.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Category
                </a>

                <form method="GET" action="{{ route('admin.categories.index') }}" class="flex items-center gap-2">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search categories..."
                            class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg pl-10 pr-4 py-2 w-64 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                    @if($search)
                        <a href="{{ route('admin.categories.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-900">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Subcategories</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-4 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-750 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                    <i class="fas fa-tag text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-white">{{ $category->name }}</div>
                                <div class="text-sm text-gray-400">{{ $category->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-300">
                            {{ Str::limit($category->description, 100) ?: 'No description' }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="bg-blue-900 text-blue-300 px-2 py-1 rounded-full text-xs font-medium">
                                {{ $category->subcategories->count() }} subcategories
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
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
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-400">
                        {{ $category->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <!-- View Button -->
                            <a
                                href="{{ route('admin.categories.show', $category->id) }}"
                                class="bg-gray-600 hover:bg-gray-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                title="View Category Details"
                            >
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- Edit Button -->
                            <button
                                onclick="openEditModal({{ $category->id }})"
                                class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                title="Edit Category"
                            >
                                <i class="fas fa-edit"></i>
                            </button>

                            @if($category->is_active)
                                <!-- Reject Button -->
                                <form method="POST" action="{{ route('admin.categories.reject', $category->id) }}" class="inline">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="bg-yellow-600 hover:bg-yellow-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                        title="Deactivate Category"
                                        onclick="return confirm('Are you sure you want to deactivate this category?')"
                                    >
                                        <i class="fas fa-pause"></i>
                                    </button>
                                </form>
                            @else
                                <!-- Approve Button -->
                                <form method="POST" action="{{ route('admin.categories.approve', $category->id) }}" class="inline">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                        title="Activate Category"
                                        onclick="return confirm('Are you sure you want to activate this category?')"
                                    >
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif

                            <!-- Delete Button -->
                            <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg transition-colors duration-200 text-sm"
                                    title="Delete Category"
                                    onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone.')"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-tag text-4xl text-gray-600 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-400 mb-2">No categories found</h3>
                            <p class="text-gray-500">
                                @if($search)
                                    No categories match your search criteria.
                                @else
                                    Get started by adding your first category.
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
    <div class="bg-gray-900 px-6 py-4 border-t border-gray-700">
        {{ $categories->links('custom.pagination') }}
    </div>
    @endif
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-700">
            <h3 class="text-xl font-semibold text-white">Edit Category</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="editCategoryForm" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_name" class="block text-sm font-medium text-gray-300 mb-2">Category Name</label>
                    <input
                        type="text"
                        id="edit_name"
                        name="name"
                        required
                        class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <div>
                    <label for="edit_slug" class="block text-sm font-medium text-gray-300 mb-2">Slug</label>
                    <input
                        type="text"
                        id="edit_slug"
                        name="slug"
                        required
                        class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
            </div>

            <div>
                <label for="edit_description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                <textarea
                    id="edit_description"
                    name="description"
                    rows="3"
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                ></textarea>
            </div>

            <div class="flex items-center">
                <input
                    type="checkbox"
                    id="edit_is_active"
                    name="is_active"
                    value="1"
                    class="h-4 w-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500"
                >
                <label for="edit_is_active" class="ml-2 text-sm text-gray-300">Active</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-700">
                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="px-4 py-2 text-gray-400 hover:text-white transition-colors"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors font-medium"
                >
                    Update Category
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let categories = @json($categories->items());

function openEditModal(categoryId) {
    const category = categories.find(c => c.id === categoryId);
    if (!category) return;

    document.getElementById('edit_name').value = category.name;
    document.getElementById('edit_slug').value = category.slug;
    document.getElementById('edit_description').value = category.description || '';
    document.getElementById('edit_is_active').checked = category.is_active == 1;

    document.getElementById('editCategoryForm').action = `/admin/categories/${categoryId}`;
    document.getElementById('editCategoryModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editCategoryModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('editCategoryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script>
@endsection
