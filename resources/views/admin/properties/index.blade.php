@extends('layouts.admin')

@section('title', 'Properties')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-semibold mb-4">Properties List</h2>

    <!-- Tabs for Web and Physical -->
    <div class="mb-4 border-b">
        <nav class="-mb-px flex">
            <a href="{{ route('admin.properties.index', ['tab' => 'web']) }}"
                class="mr-8 pb-2 pr-4 border-b-2 {{ $tab === 'web' ? 'border-blue-500 text-blue-500' : 'border-transparent text-gray-600 hover:text-gray-800' }}">
                 Web
             </a>
            <a href="{{ route('admin.properties.index', ['tab' => 'physical']) }}"
               class="pb-2 border-b-2 {{ $tab === 'physical' ? 'border-blue-500 text-blue-500' : 'border-transparent text-gray-600 hover:text-gray-800' }}">
                Physical
            </a>
        </nav>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Business Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Country</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($properties as $property)
                <tr class="{{ $property->status === 'Not Approved' ? 'bg-yellow-100' : '' }}">
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $property->id }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $property->business_name }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $property->city }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $property->country }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $property->business_email }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $property->status }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">
                        @if($property->status === 'Not Approved')
                            <div class="flex space-x-2">
                                <form action="{{ route('admin.properties.approve', $property->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 transition-colors text-white px-3 py-1 rounded-md">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.properties.reject', $property->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 transition-colors text-white px-3 py-1 rounded-md">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-gray-600">No Action</span>
                        @endif

                        <div class="flex space-x-2 mt-2">
                            <!-- Edit Button triggers popup modal -->
                            <button type="button" data-edit-url="{{ route('admin.properties.edit', $property->id) }}" class="btn-edit bg-blue-500 hover:bg-blue-600 transition-colors text-white px-3 py-1 rounded-md">
                                Edit
                            </button>
                            <!-- Delete Button -->
                            <form action="{{ route('admin.properties.destroy', $property->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this property?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-700 hover:bg-red-800 transition-colors text-white px-3 py-1 rounded-md">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
       {{ $properties->links() }}
    </div>
</div>

<!-- Modal for Property Edit -->
<div id="editModal" class="fixed inset-0 z-10 overflow-y-auto hidden">
  <div class="flex items-center justify-center min-h-screen px-4">
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl">
      <div class="flex justify-between items-center px-4 py-2 border-b">
        <h3 class="text-lg font-semibold">Edit Property</h3>
        <button type="button" class="modal-close text-gray-500 text-2xl">&times;</button>
      </div>
      <div class="p-4" id="modalBody">
         <!-- Edit form will be loaded here via AJAX -->
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function(){
    const modal = document.getElementById('editModal');
    const modalBody = document.getElementById('modalBody');
    const closeModalButtons = document.querySelectorAll('.modal-close');

    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function(){
            const editUrl = this.getAttribute('data-edit-url');
            // Load the edit form via AJAX
            fetch(editUrl)
                .then(response => response.text())
                .then(html => {
                    modalBody.innerHTML = html;
                    modal.classList.remove('hidden');
                })
                .catch(error => console.error('Error loading edit form:', error));
        });
    });

    closeModalButtons.forEach(btn => {
        btn.addEventListener('click', function(){
            modal.classList.add('hidden');
        });
    });

    // Optionally, close modal when clicking outside the modal content
    window.addEventListener('click', function(e) {
        if(e.target === modal) {
            modal.classList.add('hidden');
        }
    });
});
</script>
@endsection
