@extends('layouts.business')

@section('active-widgets', 'bg-blue-600')

@section('title', 'Edit Widget')
@section('page-title', 'Edit Widget')
@section('page-subtitle', 'Update your widget settings')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
        @if(session('error'))
            <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-200 p-4 mb-6 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-600">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200">Edit {{ ucfirst($widget->widget_type) }} Widget</h2>
                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm">
                    {{ ucfirst($widget->widget_type) }}
                </span>
            </div>
        </div>

        <!-- Badge Widget Form -->
        @if($widget->widget_type == 'badge')
        <form action="{{ route('property.widgets.update', $widget->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="widget_type" value="badge">

            <div class="form-group">
                <label for="badge_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Badge Title</label>
                <input type="text" name="title" id="badge_title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ old('title', $widget->title) }}">
            </div>

            <div class="form-group">
                @if($widget->image_path)
                <div class="mb-3">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Badge Image:</p>
                    <img src="{{ asset('storage/' . $widget->image_path) }}" alt="{{ $widget->title }}" class="h-16 w-auto">
                </div>
                @endif

                <label for="badge_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Replace Badge Image</label>
                <input type="file" name="image" id="badge_image" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" accept="image/*">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Upload a new badge image (JPG, PNG, GIF)</p>
            </div>

            <div class="form-group">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="badge_is_active" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded" {{ $widget->is_active ? 'checked' : '' }}>
                    <label for="badge_is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Active (Widget will be visible)</label>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('property.widgets') }}" class="mr-2 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Badge Widget</button>
            </div>
        </form>
        @endif

        <!-- Social Media Widget Form -->
        @if(in_array($widget->widget_type, ['instagram', 'linkedin', 'youtube', 'facebook']))
        <form action="{{ route('property.widgets.update', $widget->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="widget_type" value="{{ $widget->widget_type }}">

            <div class="form-group">
                <label for="social_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                <input type="text" name="title" id="social_title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ old('title', $widget->title) }}">
            </div>

            <div class="form-group">
                <label for="social_link_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Profile Link <span class="text-red-600">*</span></label>
                <input type="url" name="link_url" id="social_link_url" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ old('link_url', $widget->link_url) }}" placeholder="https://...">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter the full URL to your {{ $widget->widget_type }} profile</p>
            </div>

            <div class="form-group">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="social_is_active" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded" {{ $widget->is_active ? 'checked' : '' }}>
                    <label for="social_is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Active (Widget will be visible)</label>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('property.widgets') }}" class="mr-2 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update {{ ucfirst($widget->widget_type) }} Widget</button>
            </div>
        </form>
        @endif

        <!-- Cover Image Widget Form -->
        @if($widget->widget_type == 'cover_image')
        <form action="{{ route('property.widgets.update', $widget->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="widget_type" value="cover_image">

            <div class="form-group">
                <label for="cover_image_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Image Title</label>
                <input type="text" name="title" id="cover_image_title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ old('title', $widget->title) }}">
            </div>

            <div class="form-group">
                @if($widget->image_path)
                <div class="mb-3">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Cover Image:</p>
                    <img src="{{ asset('storage/' . $widget->image_path) }}" alt="{{ $widget->title }}" class="w-full h-48 object-cover rounded">
                </div>
                @endif

                <label for="cover_image_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Replace Cover Image</label>
                <input type="file" name="image" id="cover_image_file" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" accept="image/*">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Upload a new cover image (JPG, PNG). Recommended size: 1200×400px</p>
            </div>

            <div class="form-group">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="cover_image_is_active" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded" {{ $widget->is_active ? 'checked' : '' }}>
                    <label for="cover_image_is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Active (Widget will be visible)</label>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('property.widgets') }}" class="mr-2 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Cover Image Widget</button>
            </div>
        </form>
        @endif

        <!-- Text Widget Form -->
        @if($widget->widget_type == 'text')
        <form action="{{ route('property.widgets.update', $widget->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="widget_type" value="text">

            <div class="form-group">
                <label for="text_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                <input type="text" name="title" id="text_title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ old('title', $widget->title) }}">
            </div>

            <div class="form-group">
                <label for="text_content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Text Content <span class="text-red-600">*</span></label>
                <textarea name="content" id="text_content" rows="5" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('content', $widget->content) }}</textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter the text content for this widget - Required</p>
            </div>

            <div class="form-group">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="text_is_active" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded" {{ $widget->is_active ? 'checked' : '' }}>
                    <label for="text_is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Active (Widget will be visible)</label>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('property.widgets') }}" class="mr-2 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Text Widget</button>
            </div>
        </form>
        @endif

        <!-- FAQ Widget Form -->
        @if($widget->widget_type == 'faq')
        <form action="{{ route('property.widgets.update', $widget->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="faq_edit_form">
            @csrf
            @method('PUT')
            <input type="hidden" name="widget_type" value="faq">
            <input type="hidden" name="faq_content" id="faq_content" value="">

            <div class="form-group">
                <label for="faq_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">FAQ Section Title</label>
                <input type="text" name="title" id="faq_title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ old('title', $widget->title) }}">
            </div>

            <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Questions & Answers <span class="text-red-600">*</span></label>

                <div id="faq_items" class="space-y-4">
                    @php
                        $faqItems = json_decode($widget->content, true) ?? [];
                    @endphp

                    @forelse($faqItems as $index => $item)
                        <div class="faq-item border border-gray-200 dark:border-gray-600 rounded-md p-4">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Question {{ $index + 1 }}</label>
                                @if($index > 0)
                                <button type="button" class="remove-faq text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                    <i class="fas fa-times"></i> Remove
                                </button>
                                @endif
                            </div>
                            <div class="mb-3">
                                <input type="text" class="faq-question w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ $item['question'] ?? '' }}" placeholder="Enter question">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Answer {{ $index + 1 }}</label>
                                <textarea class="faq-answer w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Enter answer">{{ $item['answer'] ?? '' }}</textarea>
                            </div>
                        </div>
                    @empty
                        <div class="faq-item border border-gray-200 dark:border-gray-600 rounded-md p-4">
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Question 1</label>
                                <input type="text" class="faq-question w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter question">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Answer 1</label>
                                <textarea class="faq-answer w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Enter answer"></textarea>
                            </div>
                        </div>
                    @endforelse
                </div>

                <button type="button" id="add_faq_item" class="mt-2 inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i> Add Another Question
                </button>
            </div>

            <div class="form-group">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="faq_is_active" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded" {{ $widget->is_active ? 'checked' : '' }}>
                    <label for="faq_is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Active (Widget will be visible)</label>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('property.widgets') }}" class="mr-2 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">Cancel</a>
                <button type="submit" id="faq_submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update FAQ Widget</button>
            </div>
        </form>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // FAQ Form Logic
        if (document.getElementById('faq_edit_form')) {
            const faqItemsContainer = document.getElementById('faq_items');
            const addFaqItemBtn = document.getElementById('add_faq_item');
            const faqSubmitBtn = document.getElementById('faq_submit');
            let faqCounter = document.querySelectorAll('.faq-item').length;

            // Add FAQ item
            if (addFaqItemBtn) {
                addFaqItemBtn.addEventListener('click', function() {
                    faqCounter++;

                    const newItem = document.createElement('div');
                    newItem.className = 'faq-item border border-gray-200 dark:border-gray-600 rounded-md p-4';
                    newItem.innerHTML = `
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Question ${faqCounter}</label>
                            <button type="button" class="remove-faq text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                <i class="fas fa-times"></i> Remove
                            </button>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="faq-question w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter question">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Answer ${faqCounter}</label>
                            <textarea class="faq-answer w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Enter answer"></textarea>
                        </div>
                    `;

                    faqItemsContainer.appendChild(newItem);

                    // Add remove event listener
                    newItem.querySelector('.remove-faq').addEventListener('click', function() {
                        faqItemsContainer.removeChild(newItem);
                    });
                });
            }

            // Initialize remove buttons for existing FAQ items
            document.querySelectorAll('.remove-faq').forEach(function(button) {
                button.addEventListener('click', function() {
                    const faqItem = this.closest('.faq-item');
                    faqItemsContainer.removeChild(faqItem);
                });
            });

            // FAQ form submission
            if (faqSubmitBtn) {
                document.getElementById('faq_edit_form').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const faqItems = document.querySelectorAll('.faq-item');
                    const faqData = [];

                    // Collect FAQ data
                    faqItems.forEach(item => {
                        const questionInput = item.querySelector('.faq-question');
                        const answerInput = item.querySelector('.faq-answer');

                        const question = questionInput ? questionInput.value.trim() : '';
                        const answer = answerInput ? answerInput.value.trim() : '';

                        if (question && answer) {
                            faqData.push({
                                question: question,
                                answer: answer
                            });
                        }
                    });

                    // Validate FAQ items
                    if (faqData.length === 0) {
                        alert('Please add at least one question and answer for your FAQ widget.');
                        return;
                    }

                    // Set hidden field value
                    document.getElementById('faq_content').value = JSON.stringify(faqData);

                    // Submit the form
                    this.submit();
                });
            }
        }
    });
</script>
@endpush
