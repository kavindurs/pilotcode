@extends('layouts.business')

@section('active-integrations', 'bg-blue-700')

@section('title', isset($integration) ? 'Edit Integration' : 'Add Integration')
@section('page-title', isset($integration) ? 'Edit HTML Integration' : 'Add New HTML Integration')
@section('page-subtitle', isset($integration) ? 'Update your HTML integration' : 'Create a new HTML integration for your website')

@push('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/codemirror@5.65.0/lib/codemirror.css">
    <script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.0/lib/codemirror.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.0/mode/htmlmixed/htmlmixed.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.0/mode/xml/xml.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.0/mode/javascript/javascript.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.0/mode/css/css.js"></script>
    <style>
        .CodeMirror {
            height: 200px;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
        }

        .dark .CodeMirror {
            background-color: #374151;
            color: #f3f4f6;
            border-color: #4b5563;
        }

        .dark .CodeMirror-gutters {
            background-color: #374151;
            border-right-color: #4b5563;
        }

        .dark .CodeMirror-linenumber {
            color: #9ca3af;
        }

        .dark .CodeMirror-selected {
            background-color: #4b5563;
        }

        .dark .CodeMirror-cursor {
            border-left-color: #f3f4f6;
        }
    </style>
@endpush

@section('content')
    <!-- Plan Information Banner -->
    <div class="bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 text-blue-700 dark:text-blue-200 p-4 mb-6" role="alert">
        <div class="flex items-center">
            <div class="py-1">
                <i class="fas fa-info-circle text-2xl mr-4"></i>
            </div>
            <div>
                <p class="font-bold">Current Plan: {{ $activePlan->name }}</p>
                <p>HTML Character Limit: <span class="font-semibold">{{ $characterLimit }}</span> characters</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form action="{{ isset($integration) ? route('property.integrations.update', $integration->id) : route('property.integrations.store') }}"
              method="POST">
            @csrf
            @if(isset($integration))
                @method('PUT')
            @endif

            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title *</label>
                    <input type="text" name="title" id="title"
                           value="{{ old('title', isset($integration) ? $integration->title : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- HTML Content -->
                <div>
                    <label for="html_content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        HTML Content * <span class="text-sm text-gray-500 dark:text-gray-400">(Max {{ $characterLimit }} characters for {{ $activePlan->name }} plan)</span>
                    </label>
                    <textarea name="html_content" id="html_content" rows="6"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              required>{{ old('html_content', isset($integration) ? $integration->html_content : '') }}</textarea>
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        <span id="char-count">0</span>/{{ $characterLimit }} characters
                    </div>
                    @error('html_content')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Placement and Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="placement" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Placement *</label>
                        <select name="placement" id="placement"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                required>
                            @foreach($placementOptions as $value => $label)
                                <option value="{{ $value }}" {{ old('placement', isset($integration) ? $integration->placement : '') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('placement')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="integration_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Integration Type</label>
                        <input type="text" name="integration_type" id="integration_type"
                               value="{{ old('integration_type', isset($integration) ? $integration->integration_type : 'custom') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('integration_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded"
                            {{ old('is_active', isset($integration) && $integration->is_active ? 'checked' : '') }}>
                    <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Active (Integration will be displayed on your website)
                    </label>
                </div>
                <p class="text-sm text-amber-600 dark:text-amber-400 mt-1">
                    <i class="fas fa-exclamation-circle"></i>
                    If you make this integration active, any other active integration will be automatically deactivated.
                </p>

                <!-- Submit -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('property.integrations') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        {{ isset($integration) ? 'Update Integration' : 'Add Integration' }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Preview Section -->
    <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">HTML Preview</h3>
        <div id="preview" class="border border-gray-200 dark:border-gray-600 rounded-md p-4 bg-gray-50 dark:bg-gray-700 min-h-[100px]">
            <div class="text-center text-gray-500 dark:text-gray-400">Enter HTML code to see preview</div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize CodeMirror
            var editor = CodeMirror.fromTextArea(document.getElementById('html_content'), {
                mode: 'htmlmixed',
                lineNumbers: true,
                lineWrapping: true,
                viewportMargin: Infinity,
                theme: 'default'
            });

            const characterLimit = {{ $characterLimit }};

            // Character counter
            function updateCharCount() {
                var count = editor.getValue().length;
                document.getElementById('char-count').textContent = count;

                // Show warning if over limit
                if (count > characterLimit) {
                    document.getElementById('char-count').classList.add('text-red-500', 'font-bold');
                } else {
                    document.getElementById('char-count').classList.remove('text-red-500', 'font-bold');
                }
            }

            editor.on('change', function() {
                updateCharCount();
                updatePreview();

                // Update the original textarea for form submission
                editor.save();
            });

            // Preview functionality
            function updatePreview() {
                var content = editor.getValue();
                var previewDiv = document.getElementById('preview');

                if (content.trim() === '') {
                    previewDiv.innerHTML = '<div class="text-center text-gray-500 dark:text-gray-400">Enter HTML code to see preview</div>';
                } else {
                    try {
                        previewDiv.innerHTML = content;
                    } catch (e) {
                        previewDiv.innerHTML = '<div class="text-red-500">Error in HTML: ' + e.message + '</div>';
                    }
                }
            }

            // Initial update
            updateCharCount();
            updatePreview();
        });
    </script>
@endpush
