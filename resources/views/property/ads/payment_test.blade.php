@extends('layouts.business')

@section('title', 'Payment Test')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <div class="bg-gray-900/50 backdrop-blur-sm border border-gray-800 rounded-xl p-6">
        <h2 class="text-2xl font-bold text-white mb-6">Payment Integration Test</h2>

        <div class="space-y-4">
            <div class="bg-green-900/20 border border-green-700/50 rounded-lg p-4">
                <h3 class="text-green-300 font-medium mb-2">✅ Payment Integration Features Implemented</h3>
                <ul class="text-gray-300 text-sm space-y-1">
                    <li>• Mandatory payment before ad submission</li>
                    <li>• $1.00 USD per day pricing calculation</li>
                    <li>• Genie Business Payment Gateway integration</li>
                    <li>• Payment status tracking and verification</li>
                    <li>• Automatic refunds for rejected ads</li>
                    <li>• Admin payment verification before approval</li>
                </ul>
            </div>

            <div class="bg-blue-900/20 border border-blue-700/50 rounded-lg p-4">
                <h3 class="text-blue-300 font-medium mb-2">🔧 How It Works</h3>
                <ol class="text-gray-300 text-sm space-y-1 list-decimal list-inside">
                    <li>Business owner selects promotion dates</li>
                    <li>System calculates cost ($1 per day)</li>
                    <li>Payment is processed through Genie Business API</li>
                    <li>Ad request is submitted only after successful payment</li>
                    <li>Admin can only approve ads with completed payments</li>
                    <li>Rejected ads trigger automatic refunds</li>
                </ol>
            </div>

            <div class="bg-yellow-900/20 border border-yellow-700/50 rounded-lg p-4">
                <h3 class="text-yellow-300 font-medium mb-2">📋 Database Changes</h3>
                <ul class="text-gray-300 text-sm space-y-1">
                    <li>• Added payment tracking fields to ads_simple table</li>
                    <li>• Added payment_status field (pending/completed/failed/refunded)</li>
                    <li>• Added total_amount and total_days fields</li>
                    <li>• Added payment_id and transaction_id for tracking</li>
                </ul>
            </div>

            <div class="flex space-x-4 mt-6">
                <a href="{{ route('property.ads.create') }}"
                   class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Test Ad Creation
                </a>
                <a href="{{ route('property.ads.index') }}"
                   class="px-6 py-3 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors">
                    View Ads Manager
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
