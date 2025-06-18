@extends('layouts.business')

@section('active-invitations', 'bg-blue-600')

@section('title', 'Bulk Send Review Invitations')
@section('page-title', 'Bulk Send Review Invitations')
@section('page-subtitle', 'Send multiple review invitations at once')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('property.invitations.bulk.send') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                    <div class="flex items-center">
                        <div class="py-1">
                            <i class="fas fa-info-circle mr-2"></i>
                        </div>
                        <div>
                            <p class="font-medium">Monthly Email Limit: {{ $emailLimit }} | Used: {{ $emailsUsed }} | Remaining: {{ max(0, $emailLimit - $emailsUsed) }}</p>
                        </div>
                    </div>
                </div>

                <!-- CSV File Upload -->
                <div>
                    <label for="csv_file" class="block text-sm font-medium text-gray-700 mb-1">Upload CSV File *</label>
                    <input type="file" name="csv_file" id="csv_file" accept=".csv"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           required>
                    <p class="mt-1 text-sm text-gray-500">
                        CSV file should contain columns: name, email, message (optional)
                    </p>
                    @error('csv_file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Default Message -->
                <div>
                    <label for="default_message" class="block text-sm font-medium text-gray-700 mb-1">
                        Default Message (used if no message in CSV)
                    </label>
                    <textarea name="default_message" id="default_message" rows="6"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('default_message', "Hi there,\n\nThank you for choosing " . $property->business_name . ". We hope you had a great experience with us!\n\nWe'd really appreciate it if you could take a moment to share your feedback. Your review helps us improve and helps other customers make informed decisions.\n\nThank you for your support!") }}</textarea>
                    @error('default_message')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Expiration -->
                <div>
                    <label for="expires_days" class="block text-sm font-medium text-gray-700 mb-1">
                        Invitation Expires After
                    </label>
                    <select name="expires_days" id="expires_days"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="7">1 week</option>
                        <option value="14">2 weeks</option>
                        <option value="30" selected>1 month</option>
                        <option value="60">2 months</option>
                        <option value="90">3 months</option>
                    </select>
                </div>

                <!-- Submit -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('property.invitations') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-paper-plane mr-1"></i> Send Invitations
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Sample CSV Template Section -->
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-2">CSV Template</h3>
        <p class="text-gray-600 mb-4">For bulk sending, use this CSV format.</p>

        <div class="bg-gray-50 p-4 rounded-lg overflow-x-auto">
            <table class="min-w-full border border-gray-300">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b border-r border-gray-300 bg-gray-100">name</th>
                        <th class="px-4 py-2 border-b border-r border-gray-300 bg-gray-100">email</th>
                        <th class="px-4 py-2 border-b border-gray-300 bg-gray-100">message</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 py-2 border-b border-r border-gray-300">John Smith</td>
                        <td class="px-4 py-2 border-b border-r border-gray-300">john@example.com</td>
                        <td class="px-4 py-2 border-b border-gray-300">Thanks for visiting our store! We'd love to hear about your experience.</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 border-b border-r border-gray-300">Jane Doe</td>
                        <td class="px-4 py-2 border-b border-r border-gray-300">jane@example.com</td>
                        <td class="px-4 py-2 border-b border-gray-300"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
