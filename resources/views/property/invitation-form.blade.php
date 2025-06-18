@extends('layouts.business')

@section('active-invitations', 'bg-blue-600')

@section('title', 'Send Review Invitation')
@section('page-title', 'Send Review Invitation')
@section('page-subtitle', 'Invite a customer to leave a review for your business')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form Section -->
        <div class="lg:col-span-2">
            <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6">
                <form action="{{ route('property.invitations.store') }}" method="POST">
                    @csrf

                <div class="space-y-6">
                    <!-- Customer Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-300 mb-1">Customer Name *</label>
                            <input type="text" name="customer_name" id="customer_name"
                                   value="{{ old('customer_name') }}"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            @error('customer_name')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_email" class="block text-sm font-medium text-gray-300 mb-1">Customer Email *</label>
                            <input type="email" name="customer_email" id="customer_email"
                                   value="{{ old('customer_email') }}"
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            @error('customer_email')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email Subject -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-300 mb-1">Email Subject</label>
                        <input type="text" name="subject" id="subject"
                               value="{{ old('subject', 'Please share your experience with ' . $property->business_name) }}"
                               class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('subject')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-300 mb-1">
                            Message to Customer *
                        </label>
                        <textarea name="message" id="message" rows="6"
                                  class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                  required>{{ old('message', "Hi there,\n\nThank you for choosing " . $property->business_name . ". We hope you had a great experience with us!\n\nWe'd really appreciate it if you could take a moment to share your feedback. Your review helps us improve and helps other customers make informed decisions.\n\nThank you for your support!") }}</textarea>
                        @error('message')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expiration -->
                    <div>
                        <label for="expires_days" class="block text-sm font-medium text-gray-300 mb-1">
                            Invitation Expires After
                        </label>
                        <select name="expires_days" id="expires_days"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="7" {{ old('expires_days') == 7 ? 'selected' : '' }}>1 week</option>
                            <option value="14" {{ old('expires_days') == 14 ? 'selected' : '' }}>2 weeks</option>
                            <option value="30" {{ old('expires_days', 30) == 30 ? 'selected' : '' }}>1 month</option>
                            <option value="60" {{ old('expires_days') == 60 ? 'selected' : '' }}>2 months</option>
                            <option value="90" {{ old('expires_days') == 90 ? 'selected' : '' }}>3 months</option>
                        </select>
                    </div>

                    <!-- Email Preview -->
                    <div class="border border-gray-600 rounded-lg p-4 bg-gray-700">
                        <h3 class="text-sm font-medium text-gray-300 mb-2">Email Preview</h3>
                        <div class="bg-gray-800 border border-gray-600 rounded-lg p-4 max-h-96 overflow-y-auto">
                            <div class="mb-4 pb-4 border-b border-gray-600">
                                <div class="text-sm text-gray-400 mb-1">From: {{ $property->business_name }} &lt;{{ config('mail.from.address') }}&gt;</div>
                                <div class="text-sm text-gray-400 mb-1">To: <span id="preview-email">your-customer@example.com</span></div>
                                <div class="text-sm text-gray-400">Subject: <span id="preview-subject">Please share your experience with {{ $property->business_name }}</span></div>
                            </div>

                            <div id="preview-message" class="text-sm text-gray-300 whitespace-pre-line">
                                Hi there,

                                Thank you for choosing {{ $property->business_name }}. We hope you had a great experience with us!

                                We'd really appreciate it if you could take a moment to share your feedback. Your review helps us improve and helps other customers make informed decisions.

                                Thank you for your support!
                            </div>

                            <div class="mt-4 text-center">
                                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors shadow-lg" disabled>
                                    Leave a Review
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('property.invitations') }}" class="px-4 py-2 border border-gray-600 rounded-md text-gray-300 hover:bg-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors shadow-lg">
                            <i class="fas fa-paper-plane mr-1"></i> Send Invitation
                        </button>
                    </div>
                </div>
            </form>
            </div>
        </div>

        <!-- Tips Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-lightbulb mr-2 text-yellow-400"></i>
                    Tips for Effective Review Invitations
                </h3>
                <ul class="space-y-4 text-gray-300">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-400 mr-3 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <strong class="text-white">Be personal</strong>
                            <p class="text-sm text-gray-400 mt-1">Address your customer by name and reference their specific experience if possible.</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-400 mr-3 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <strong class="text-white">Keep it simple</strong>
                            <p class="text-sm text-gray-400 mt-1">Make it easy for customers to understand what you're asking.</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-400 mr-3 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <strong class="text-white">Be timely</strong>
                            <p class="text-sm text-gray-400 mt-1">Send invitations shortly after service when the experience is still fresh.</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-400 mr-3 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <strong class="text-white">Express gratitude</strong>
                            <p class="text-sm text-gray-400 mt-1">Thank customers for their business and for taking time to leave a review.</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-400 mr-3 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <strong class="text-white">Explain the value</strong>
                            <p class="text-sm text-gray-400 mt-1">Let customers know how their feedback helps your business and other customers.</p>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Best Practices Card -->
            <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-star mr-2 text-yellow-400"></i>
                    Best Practices
                </h3>
                <div class="space-y-3">
                    <div class="bg-gray-700 rounded-lg p-3">
                        <div class="flex items-center text-yellow-400 mb-1">
                            <i class="fas fa-clock mr-2"></i>
                            <span class="font-medium text-sm">Timing Matters</span>
                        </div>
                        <p class="text-xs text-gray-400">Send within 24-48 hours after service completion for best response rates.</p>
                    </div>
                    <div class="bg-gray-700 rounded-lg p-3">
                        <div class="flex items-center text-blue-400 mb-1">
                            <i class="fas fa-mobile-alt mr-2"></i>
                            <span class="font-medium text-sm">Mobile-Friendly</span>
                        </div>
                        <p class="text-xs text-gray-400">Keep messages concise as many customers read emails on mobile devices.</p>
                    </div>
                    <div class="bg-gray-700 rounded-lg p-3">
                        <div class="flex items-center text-green-400 mb-1">
                            <i class="fas fa-heart mr-2"></i>
                            <span class="font-medium text-sm">Show Appreciation</span>
                        </div>
                        <p class="text-xs text-gray-400">Always thank customers first before asking for a favor.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Live preview functionality
        const customerEmail = document.getElementById('customer_email');
        const subject = document.getElementById('subject');
        const message = document.getElementById('message');

        const previewEmail = document.getElementById('preview-email');
        const previewSubject = document.getElementById('preview-subject');
        const previewMessage = document.getElementById('preview-message');

        customerEmail.addEventListener('input', function() {
            previewEmail.textContent = this.value || 'your-customer@example.com';
        });

        subject.addEventListener('input', function() {
            previewSubject.textContent = this.value;
        });

        message.addEventListener('input', function() {
            previewMessage.textContent = this.value;
        });
    });
</script>
@endpush
