@extends('layouts.admin')

@section('title', 'Reviews')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-semibold mb-4">Reviews List</h2>

    <!-- Tabs for Approved and Not Approved Reviews -->
    <div class="mb-4 border-b">
        <nav class="-mb-px flex">
            <a href="{{ route('admin.reviews.index', ['tab' => 'pending']) }}"
               class="mr-8 pb-3 pr-4 border-b-2 {{ $tab === 'pending' ? 'border-blue-500 text-blue-500' : 'border-transparent text-gray-600 hover:text-gray-800' }}">
               Pending Approval
            </a>
            <a href="{{ route('admin.reviews.index', ['tab' => 'approved']) }}"
               class="mr-8 pb-3 pr-4 border-b-2 {{ $tab === 'approved' ? 'border-blue-500 text-blue-500' : 'border-transparent text-gray-600 hover:text-gray-800' }}">
               Approved
            </a>
            <a href="{{ route('admin.reviews.index', ['tab' => 'rejected']) }}"
               class="pb-3 pr-4 border-b-2 {{ $tab === 'rejected' ? 'border-blue-500 text-blue-500' : 'border-transparent text-gray-600 hover:text-gray-800' }}">
               Rejected
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
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Review</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($reviews as $review)
                <tr class="{{ $review->status === 'Pending Approval' ? 'bg-yellow-100' : '' }}">
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $review->id }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $review->review }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $review->rate }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $review->status }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">
                        @if($review->status === 'Pending Approval')
                            <div class="flex space-x-2">
                                <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 transition-colors text-white px-3 py-1 rounded-md">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 transition-colors text-white px-3 py-1 rounded-md">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-gray-600">No Action</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
       {{ $reviews->links() }}
    </div>
</div>
@endsection
