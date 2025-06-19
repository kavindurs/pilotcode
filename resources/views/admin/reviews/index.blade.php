@extends('layouts.admin')

@section('active-reviews', 'menu-item-active')
@section('page-title', 'Reviews Management')
@section('page-subtitle', 'Monitor and moderate all reviews across your platform.')

@section('content')
<div class="bg-gray-800 border border-gray-700 shadow-xl rounded-xl p-6">
    <h2 class="text-2xl font-semibold mb-6 text-white flex items-center">
        <i class="fas fa-star text-yellow-400 mr-3"></i>
        Reviews List
    </h2>

    <!-- Tabs for Approved and Not Approved Reviews -->
    <div class="mb-6 border-b border-gray-700">
        <nav class="-mb-px flex">
            <a href="{{ route('admin.reviews.index', ['tab' => 'pending']) }}"
               class="mr-8 pb-3 pr-4 border-b-2 font-medium transition-colors duration-200 {{ $tab === 'pending' ? 'border-yellow-500 text-yellow-400' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
               <i class="fas fa-clock mr-2"></i>Pending Approval
            </a>
            <a href="{{ route('admin.reviews.index', ['tab' => 'approved']) }}"
               class="mr-8 pb-3 pr-4 border-b-2 font-medium transition-colors duration-200 {{ $tab === 'approved' ? 'border-yellow-500 text-yellow-400' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
               <i class="fas fa-check mr-2"></i>Approved
            </a>
            <a href="{{ route('admin.reviews.index', ['tab' => 'rejected']) }}"
               class="pb-3 pr-4 border-b-2 font-medium transition-colors duration-200 {{ $tab === 'rejected' ? 'border-yellow-500 text-yellow-400' : 'border-transparent text-gray-400 hover:text-gray-200' }}">
               <i class="fas fa-times mr-2"></i>Rejected
            </a>
        </nav>
    </div>

    @if(session('success'))
        <div class="bg-green-900 border border-green-700 text-green-300 p-4 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-900">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Property</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Review</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Rating</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700">
                @foreach($reviews as $review)
                <tr class="hover:bg-gray-750 transition-colors duration-200 {{ $review->status === 'Pending Approval' ? 'bg-yellow-900/20' : '' }}">
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-200">#{{ $review->id }}</td>
                    <td class="px-4 py-4 text-sm text-gray-200">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-md bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center mr-3">
                                <i class="fas fa-building text-white text-xs"></i>
                            </div>
                            <div>
                                <div class="font-medium text-white">{{ $review->property->business_name ?? 'Unknown Property' }}</div>
                                <div class="text-gray-400 text-xs">ID: {{ $review->property_id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-200">
                        <div class="max-w-xs">
                            <div class="truncate">{{ $review->review }}</div>
                            @if(strlen($review->review) > 50)
                                <div class="text-gray-400 text-xs mt-1">{{ Str::limit($review->review, 50) }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-xs {{ $i <= $review->rate ? 'text-yellow-400' : 'text-gray-600' }}"></i>
                            @endfor
                            <span class="ml-2 text-gray-200">{{ $review->rate }}/5</span>
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm">
                        @if($review->status === 'Pending Approval')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900 text-yellow-300">
                                <i class="fas fa-clock mr-1"></i>
                                Pending
                            </span>
                        @elseif($review->status === 'Approved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900 text-green-300">
                                <i class="fas fa-check mr-1"></i>
                                Approved
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900 text-red-300">
                                <i class="fas fa-times mr-1"></i>
                                Rejected
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm">
                        <div class="flex space-x-1">
                            @if($review->status === 'Pending Approval')
                                <!-- Approve Button -->
                                <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center bg-green-600 hover:bg-green-500 text-white rounded-md transition-colors shadow-sm"
                                            title="Approve Review">
                                        <i class="fas fa-check text-xs"></i>
                                    </button>
                                </form>

                                <!-- Reject Button -->
                                <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center bg-red-600 hover:bg-red-500 text-white rounded-md transition-colors shadow-sm"
                                            title="Reject Review">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </form>
                            @endif

                            <!-- Delete Button (always visible) -->
                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this review? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center bg-gray-600 hover:bg-gray-500 text-white rounded-md transition-colors shadow-sm"
                                        title="Delete Review">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($reviews->isEmpty())
        <div class="text-center py-12">
            <div class="w-20 h-20 rounded-full bg-gray-700 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-star text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-300 mb-2">No Reviews Found</h3>
            <p class="text-gray-400">
                @if($tab === 'pending')
                    No reviews are pending approval at this time.
                @elseif($tab === 'approved')
                    No reviews have been approved yet.
                @else
                    No reviews have been rejected.
                @endif
            </p>
        </div>
    @endif

    @if($reviews->hasPages())
        <div class="mt-6">
            {{ $reviews->appends(request()->query())->links('custom.pagination') }}
        </div>
    @endif
</div>
@endsection
