{{-- Create: resources/views/payments/mock-payment.blade.php --}}
@extends('layouts.app')

@section('title', 'Mock Payment Gateway')

@section('content')
<div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-6">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Mock Payment Gateway</h2>

            <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                <p class="text-sm text-blue-700">
                    <strong>Note:</strong> This is a mock payment page for testing purposes.
                </p>
            </div>

            <div class="mb-6">
                <p class="text-gray-600 mb-2">Transaction ID:</p>
                <p class="font-mono text-sm bg-gray-100 p-2 rounded">{{ $transactionId }}</p>
            </div>

            <div class="space-y-3">
                <form action="{{ route('mock.payment.complete', $transactionId) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700">
                        Complete Payment (Success)
                    </button>
                </form>

                <a href="{{ route('payment.cancel') }}" class="w-full bg-red-600 text-white py-3 px-4 rounded-md hover:bg-red-700 inline-block text-center">
                    Cancel Payment
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
