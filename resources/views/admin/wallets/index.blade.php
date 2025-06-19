@extends('layouts.admin')

@section('active-wallets', 'menu-item-active')
@section('page-title', 'Wallet Management')
@section('page-subtitle', 'Manage user wallets, balances, and financial transactions')

@section('content')
<div class="bg-gray-800 border border-gray-700 rounded-xl shadow-xl overflow-hidden">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 border-b border-gray-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-white mb-1">Wallet Management</h2>
                <p class="text-gray-300 text-sm">View and manage user wallet balances and transactions</p>
            </div>
            <div class="mt-3 sm:mt-0">
                <div class="text-sm text-gray-300">
                    <span class="font-medium">Total Wallets: {{ $wallets->total() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-gray-750 px-6 py-4 border-b border-gray-700">
        <form method="GET" action="{{ route('admin.wallets.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-300 mb-1">Search Users</label>
                <input type="text"
                       id="search"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Search by user name or email..."
                       class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="w-full sm:w-48">
                <label for="currency" class="block text-sm font-medium text-gray-300 mb-1">Currency</label>
                <select id="currency"
                        name="currency"
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Currencies</option>
                    @foreach($currencies as $curr)
                        <option value="{{ $curr }}" {{ $currency == $curr ? 'selected' : '' }}>
                            {{ $curr }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-48">
                <label for="sort" class="block text-sm font-medium text-gray-300 mb-1">Sort By</label>
                <select id="sort"
                        name="sort"
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="created_at" {{ $sort == 'created_at' ? 'selected' : '' }}>Date Created</option>
                    <option value="balance" {{ $sort == 'balance' ? 'selected' : '' }}>Balance</option>
                    <option value="total_earned" {{ $sort == 'total_earned' ? 'selected' : '' }}>Total Earned</option>
                    <option value="total_withdrawn" {{ $sort == 'total_withdrawn' ? 'selected' : '' }}>Total Withdrawn</option>
                </select>
                <input type="hidden" name="direction" value="{{ $direction }}">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                @if($search || $currency)
                    <a href="{{ route('admin.wallets.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-times mr-1"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-600 text-white px-6 py-3 border-b border-gray-700">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-600 text-white px-6 py-3 border-b border-gray-700">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($wallets->count() > 0)
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-750 border-b border-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <a href="{{ route('admin.wallets.index', array_merge(request()->query(), ['sort' => 'balance', 'direction' => $sort == 'balance' && $direction == 'asc' ? 'desc' : 'asc'])) }}"
                               class="flex items-center hover:text-white transition-colors">
                                Balance
                                @if($sort == 'balance')
                                    <i class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1 opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Pending Balance</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <a href="{{ route('admin.wallets.index', array_merge(request()->query(), ['sort' => 'total_earned', 'direction' => $sort == 'total_earned' && $direction == 'asc' ? 'desc' : 'asc'])) }}"
                               class="flex items-center hover:text-white transition-colors">
                                Total Earned
                                @if($sort == 'total_earned')
                                    <i class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1 opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            <a href="{{ route('admin.wallets.index', array_merge(request()->query(), ['sort' => 'total_withdrawn', 'direction' => $sort == 'total_withdrawn' && $direction == 'asc' ? 'desc' : 'asc'])) }}"
                               class="flex items-center hover:text-white transition-colors">
                                Total Withdrawn
                                @if($sort == 'total_withdrawn')
                                    <i class="fas fa-sort-{{ $direction == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1 opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Currency</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($wallets as $wallet)
                        <tr class="hover:bg-gray-750 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-medium mr-3">
                                        {{ strtoupper(substr($wallet->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-white font-medium">{{ $wallet->user->name ?? 'Unknown User' }}</div>
                                        <div class="text-gray-400 text-sm">{{ $wallet->user->email ?? 'No email' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-white font-medium">
                                    {{ $wallet->currency }} {{ number_format($wallet->balance, 2) }}
                                </div>
                                @if($wallet->balance > 0)
                                    <div class="text-xs text-green-400">Available</div>
                                @else
                                    <div class="text-xs text-gray-500">Empty</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-orange-400 font-medium">
                                    {{ $wallet->currency }} {{ number_format($wallet->pending_balance, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-green-400 font-medium">
                                    {{ $wallet->currency }} {{ number_format($wallet->total_earned, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-red-400 font-medium">
                                    {{ $wallet->currency }} {{ number_format($wallet->total_withdrawn, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-600 text-white">
                                    {{ $wallet->currency }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-300">
                                {{ $wallet->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.wallets.show', $wallet->id) }}"
                                       class="text-blue-400 hover:text-blue-300 transition-colors p-1"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.wallets.edit', $wallet->id) }}"
                                       class="text-yellow-400 hover:text-yellow-300 transition-colors p-1"
                                       title="Edit Wallet">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form method="POST" action="{{ route('admin.wallets.destroy', $wallet->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-400 hover:text-red-300 transition-colors p-1"
                                                title="Delete Wallet"
                                                onclick="return confirm('Are you sure you want to delete this wallet? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-750 px-6 py-4 border-t border-gray-600">
            {{ $wallets->withQueryString()->links('admin.partials.pagination') }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="w-24 h-24 mx-auto mb-4 bg-gray-700 rounded-full flex items-center justify-center">
                <i class="fas fa-wallet text-3xl text-gray-500"></i>
            </div>
            <h3 class="text-lg font-medium text-white mb-2">No Wallets Found</h3>
            <p class="text-gray-400 mb-4">
                @if($search || $currency)
                    No wallets match your current filters.
                @else
                    No wallets have been created yet.
                @endif
            </p>
            @if($search || $currency)
                <a href="{{ route('admin.wallets.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Clear Filters
                </a>
            @endif
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when sort changes
    document.getElementById('sort').addEventListener('change', function() {
        this.form.submit();
    });
});
</script>
@endsection
