@extends('layouts.admin')

@section('active-email-templates', 'menu-item-active')
@section('page-title', 'Edit Email Template')
@section('page-subtitle', 'Customize the email template content and styling.')

@section('content')
<div class="min-h-screen bg-gray-900 p-6">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white flex items-center">
                    <i class="fas fa-edit text-blue-400 mr-3"></i>
                    Edit Email Template
                </h1>
                <p class="text-gray-400 mt-2">Customize your email template content and styling</p>
            </div>
            <a href="{{ route('admin.email_templates.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Templates
            </a>
        </div>

        <!-- Template Info Card -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 mb-6">
            <div class="flex items-center">
                <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center mr-4">
                    <i class="fas fa-envelope text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-white">
                        @php
                            $displayName = ucwords(str_replace('_', ' ', $template->slug));
                        @endphp
                        {{ $displayName }}
                    </h2>
                    <p class="text-gray-400">Template Slug: {{ $template->slug }}</p>
                    <p class="text-gray-400 text-sm">ID: {{ $template->id }}</p>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="bg-red-900 border border-red-700 text-red-300 p-4 rounded-lg mb-6">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle mr-3 mt-0.5"></i>
                    <div>
                        <h4 class="font-semibold mb-2">Please fix the following errors:</h4>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-900 border border-green-700 text-green-300 p-4 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Edit Form -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
            <form action="{{ route('admin.email_templates.update', $template->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Subject Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-3">
                        <i class="fas fa-heading mr-2 text-blue-400"></i>
                        Email Subject
                    </label>
                    <input type="text"
                           name="subject"
                           value="{{ old('subject') ?: $template->subject }}"
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="Enter email subject line..."
                           required>
                    <p class="text-gray-400 text-xs mt-2">This will be the subject line of the email sent to users.</p>
                </div>

                <!-- Body Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-3">
                        <i class="fas fa-file-alt mr-2 text-green-400"></i>
                        Email Body Content
                    </label>
                    <div class="border border-gray-600 rounded-lg overflow-hidden">
                        <textarea name="body"
                                  id="email-body"
                                  rows="25"
                                  class="w-full px-4 py-3 bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all resize-none font-mono text-sm"
                                  placeholder="Enter email body content..."
                                  required>{{ old('body', $template->body) }}</textarea>
                    </div>

                    <!-- Template Variables -->
                    <div class="mt-4 p-4 bg-gray-700 rounded-lg">
                        <p class="text-gray-300 text-sm mb-2 font-medium">
                            <i class="fas fa-info-circle mr-1"></i>
                            Available template variables:
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-gray-600 rounded-full text-blue-300 text-xs">{{ '{{name}}' }}</span>
                            <span class="px-3 py-1 bg-gray-600 rounded-full text-blue-300 text-xs">{{ '{{email}}' }}</span>
                            <span class="px-3 py-1 bg-gray-600 rounded-full text-blue-300 text-xs">{{ '{{site_name}}' }}</span>
                            <span class="px-3 py-1 bg-gray-600 rounded-full text-blue-300 text-xs">{{ '{{site_url}}' }}</span>
                            <span class="px-3 py-1 bg-gray-600 rounded-full text-blue-300 text-xs">{{ '{{reset_link}}' }}</span>
                            <span class="px-3 py-1 bg-gray-600 rounded-full text-blue-300 text-xs">{{ '{{verification_link}}' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-700">
                    <div class="text-sm text-gray-400">
                        <i class="fas fa-clock mr-1"></i>
                        Last updated:
                        @if($template->updated_at)
                            {{ $template->updated_at->format('M d, Y') }} at {{ $template->updated_at->format('H:i') }}
                        @else
                            Never
                        @endif
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.email_templates.index') }}"
                           class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg transition-all transform hover:scale-105 shadow-lg">
                            <i class="fas fa-save mr-2"></i>
                            Update Template
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('email-body');
    if (textarea) {
        // Auto-resize textarea
        function resizeTextarea() {
            textarea.style.height = 'auto';
            textarea.style.height = Math.max(textarea.scrollHeight, 400) + 'px';
        }

        textarea.addEventListener('input', resizeTextarea);

        // Set initial height
        setTimeout(resizeTextarea, 100);

        // Line numbers or syntax highlighting could be added here
        console.log('Email template editor initialized');
    }
});
</script>
@endpush
@endsection
