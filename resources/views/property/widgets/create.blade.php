@extends('layouts.business')

@section('active-widgets', 'bg-blue-600')

@section('title', 'Create Widget')
@section('page-title', 'Create New Widget')
@section('page-subtitle', 'Add a new widget to your website')

@section('content')
    <div class="bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-6 mb-8">
        <!-- Success Messages -->
        @if(session('success'))
            <div class="bg-green-900 border-l-4 border-green-400 text-green-100 p-4 mb-6 rounded-r-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if(session('error'))
            <div class="bg-red-900 border-l-4 border-red-400 text-red-100 p-4 mb-6 rounded-r-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-gray-900 border-l-4 border-blue-500 text-blue-200 p-4 mb-6 rounded-r-lg">
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-3 text-lg text-blue-400"></i>
                <div>
                    <p class="font-medium text-white">Your Plan: <span class="text-blue-400">{{ $planInfo['planType'] }}</span></p>
                    <p class="text-sm text-gray-300">
                        You can create <span class="font-medium text-blue-400">{{ $planInfo['remainingWidgets'] }}</span> more
                        widget(s) with your current plan.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-gray-400">
                Need more widgets? <a href="{{ route('property.widgets.upgrade') }}" class="text-blue-400 hover:text-blue-300 hover:underline">Upgrade your plan</a>
            </p>
        </div>

        <!-- Widget Type Selection -->
        <div class="mb-8">
            <h2 class="text-lg font-medium text-white mb-4">Select Widget Type</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <button type="button" class="widget-type-btn p-4 border-2 border-gray-600 bg-gray-700 rounded-lg text-center hover:border-blue-400 hover:bg-gray-600 transition-colors" data-type="badge">
                    <i class="fas fa-certificate text-2xl text-blue-400 mb-2"></i>
                    <h3 class="font-medium text-white">Verification Badge</h3>
                    <p class="text-sm text-gray-400">Show verification status</p>
                </button>

                <button type="button" class="widget-type-btn p-4 border-2 border-gray-600 bg-gray-700 rounded-lg text-center hover:border-blue-400 hover:bg-gray-600 transition-colors" data-type="instagram">
                    <i class="fab fa-instagram text-2xl text-pink-400 mb-2"></i>
                    <h3 class="font-medium text-white">Instagram</h3>
                    <p class="text-sm text-gray-400">Link to your Instagram</p>
                </button>

                <button type="button" class="widget-type-btn p-4 border-2 border-gray-600 bg-gray-700 rounded-lg text-center hover:border-blue-400 hover:bg-gray-600 transition-colors" data-type="linkedin">
                    <i class="fab fa-linkedin text-2xl text-blue-400 mb-2"></i>
                    <h3 class="font-medium text-white">LinkedIn</h3>
                    <p class="text-sm text-gray-400">Link to your LinkedIn</p>
                </button>

                <button type="button" class="widget-type-btn p-4 border-2 border-gray-600 bg-gray-700 rounded-lg text-center hover:border-blue-400 hover:bg-gray-600 transition-colors" data-type="youtube">
                    <i class="fab fa-youtube text-2xl text-red-400 mb-2"></i>
                    <h3 class="font-medium text-white">YouTube</h3>
                    <p class="text-sm text-gray-400">Link to your channel</p>
                </button>

                <button type="button" class="widget-type-btn p-4 border-2 border-gray-600 bg-gray-700 rounded-lg text-center hover:border-blue-400 hover:bg-gray-600 transition-colors" data-type="facebook">
                    <i class="fab fa-facebook text-2xl text-blue-400 mb-2"></i>
                    <h3 class="font-medium text-white">Facebook</h3>
                    <p class="text-sm text-gray-400">Link to your Facebook</p>
                </button>

                <button type="button" class="widget-type-btn p-4 border-2 border-gray-600 bg-gray-700 rounded-lg text-center hover:border-blue-400 hover:bg-gray-600 transition-colors" data-type="cover_image">
                    <i class="fas fa-image text-2xl text-green-400 mb-2"></i>
                    <h3 class="font-medium text-white">Cover Image</h3>
                    <p class="text-sm text-gray-400">Add a hero image</p>
                </button>

                <button type="button" class="widget-type-btn p-4 border-2 border-gray-600 bg-gray-700 rounded-lg text-center hover:border-blue-400 hover:bg-gray-600 transition-colors" data-type="text">
                    <i class="fas fa-align-left text-2xl text-gray-400 mb-2"></i>
                    <h3 class="font-medium text-white">Text</h3>
                    <p class="text-sm text-gray-400">Add descriptive text</p>
                </button>

                <button type="button" class="widget-type-btn p-4 border-2 border-gray-600 bg-gray-700 rounded-lg text-center hover:border-blue-400 hover:bg-gray-600 transition-colors" data-type="faq">
                    <i class="fas fa-question-circle text-2xl text-purple-400 mb-2"></i>
                    <h3 class="font-medium text-white">FAQ</h3>
                    <p class="text-sm text-gray-400">Frequently asked questions</p>
                </button>
            </div>
        </div>

        <!-- Individual Widget Forms - Hidden Initially -->
        <div class="widget-forms">
            <!-- Badge Widget Form -->
            <div id="badge_form" class="widget-form hidden">
                <h2 class="text-lg font-medium text-white mb-4">Create Verification Badge Widget</h2>
                <form action="{{ route('property.widgets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="widget_type" value="badge">

                    <div class="form-group">
                        <label for="badge_title" class="block text-sm font-medium text-gray-300 mb-1">Badge Title</label>
                        <input type="text" name="title" id="badge_title" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ old('title') }}">
                    </div>

                    <div class="form-group">
                        <label for="badge_image" class="block text-sm font-medium text-gray-300 mb-1">Badge Image <span class="text-red-400">*</span></label>
                        <input type="file" name="image" id="badge_image" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700" accept="image/*">
                        <p class="mt-1 text-xs text-gray-400">Upload a badge image (JPG, PNG, GIF) - Required</p>
                    </div>

                    <div class="form-group">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="badge_is_active" class="h-4 w-4 text-blue-600 focus:ring-blue-500 bg-gray-700 border-gray-600 rounded" checked>
                            <label for="badge_is_active" class="ml-2 block text-sm text-gray-300">Active (Widget will be visible)</label>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" class="cancel-btn mr-2 px-4 py-2 bg-gray-600 text-gray-300 rounded-md hover:bg-gray-500 transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Create Badge Widget</button>
                    </div>
                </form>
            </div>

            <!-- Social Media Widget Form (Instagram, LinkedIn, YouTube, Facebook) -->
            <div id="social_form" class="widget-form hidden">
                <h2 class="text-lg font-medium text-white mb-4">Create <span id="social_type_title">Social Media</span> Widget</h2>
                <form action="{{ route('property.widgets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="widget_type" id="social_widget_type" value="">

                    <div class="form-group">
                        <label for="social_title" class="block text-sm font-medium text-gray-300 mb-1">Title</label>
                        <input type="text" name="title" id="social_title" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ old('title') }}">
                    </div>

                    <div class="form-group">
                        <label for="social_link_url" class="block text-sm font-medium text-gray-300 mb-1">Profile Link <span class="text-red-400">*</span></label>
                        <input type="url" name="link_url" id="social_link_url" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ old('link_url') }}" placeholder="https://...">
                        <p class="mt-1 text-xs text-gray-400">Enter the full URL to your profile - Required</p>
                    </div>

                    <div class="form-group">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="social_is_active" class="h-4 w-4 text-blue-600 focus:ring-blue-500 bg-gray-700 border-gray-600 rounded" checked>
                            <label for="social_is_active" class="ml-2 block text-sm text-gray-300">Active (Widget will be visible)</label>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" class="cancel-btn mr-2 px-4 py-2 bg-gray-600 text-gray-300 rounded-md hover:bg-gray-500 transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Create <span id="social_submit_text">Social Media</span> Widget</button>
                    </div>
                </form>
            </div>

            <!-- Cover Image Widget Form -->
            <div id="cover_image_form" class="widget-form hidden">
                <h2 class="text-lg font-medium text-white mb-4">Create Cover Image Widget</h2>
                <form action="{{ route('property.widgets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="widget_type" value="cover_image">

                    <div class="form-group">
                        <label for="cover_image_title" class="block text-sm font-medium text-gray-300 mb-1">Image Title</label>
                        <input type="text" name="title" id="cover_image_title" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ old('title') }}">
                    </div>

                    <div class="form-group">
                        <label for="cover_image_file" class="block text-sm font-medium text-gray-300 mb-1">Cover Image <span class="text-red-400">*</span></label>
                        <input type="file" name="image" id="cover_image_file" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700" accept="image/*">
                        <p class="mt-1 text-xs text-gray-400">Upload a cover image (JPG, PNG). Recommended size: 1200×400px - Required</p>
                    </div>

                    <div class="form-group">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="cover_image_is_active" class="h-4 w-4 text-blue-600 focus:ring-blue-500 bg-gray-700 border-gray-600 rounded" checked>
                            <label for="cover_image_is_active" class="ml-2 block text-sm text-gray-300">Active (Widget will be visible)</label>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" class="cancel-btn mr-2 px-4 py-2 bg-gray-600 text-gray-300 rounded-md hover:bg-gray-500 transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Create Cover Image Widget</button>
                    </div>
                </form>
            </div>

            <!-- Text Widget Form -->
            <div id="text_form" class="widget-form hidden">
                <h2 class="text-lg font-medium text-white mb-4">Create Text Widget</h2>
                <form action="{{ route('property.widgets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="widget_type" value="text">

                    <div class="form-group">
                        <label for="text_title" class="block text-sm font-medium text-gray-300 mb-1">Title</label>
                        <input type="text" name="title" id="text_title" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ old('title') }}">
                    </div>

                    <div class="form-group">
                        <label for="text_content" class="block text-sm font-medium text-gray-300 mb-1">Text Content <span class="text-red-400">*</span></label>
                        <textarea name="content" id="text_content" rows="5" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('content') }}</textarea>
                        <p class="mt-1 text-xs text-gray-400">Enter the text content for this widget - Required</p>
                    </div>

                    <div class="form-group">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="text_is_active" class="h-4 w-4 text-blue-600 focus:ring-blue-500 bg-gray-700 border-gray-600 rounded" checked>
                            <label for="text_is_active" class="ml-2 block text-sm text-gray-300">Active (Widget will be visible)</label>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" class="cancel-btn mr-2 px-4 py-2 bg-gray-600 text-gray-300 rounded-md hover:bg-gray-500 transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Create Text Widget</button>
                    </div>
                </form>
            </div>

            <!-- FAQ Widget Form -->
            <div id="faq_form" class="widget-form hidden">
                <h2 class="text-lg font-medium text-white mb-4">Create FAQ Widget</h2>
                <form action="{{ route('property.widgets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="widget_type" value="faq">
                    <input type="hidden" name="faq_content" id="faq_content" value="">

                    <div class="form-group">
                        <label for="faq_title" class="block text-sm font-medium text-gray-300 mb-1">FAQ Section Title</label>
                        <input type="text" name="title" id="faq_title" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ old('title') ? old('title') : 'Frequently Asked Questions' }}">
                    </div>

                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-300 mb-3">Questions & Answers <span class="text-red-400">*</span></label>

                        <div id="faq_items" class="space-y-4">
                            <div class="faq-item border border-gray-600 bg-gray-700 rounded-md p-4">
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-300 mb-1">Question 1</label>
                                    <input type="text" class="faq-question w-full px-3 py-2 bg-gray-600 border border-gray-500 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter question">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1">Answer 1</label>
                                    <textarea class="faq-answer w-full px-3 py-2 bg-gray-600 border border-gray-500 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Enter answer"></textarea>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="add_faq_item" class="mt-2 inline-flex items-center px-3 py-1.5 border border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-300 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <i class="fas fa-plus mr-2"></i> Add Another Question
                        </button>
                    </div>

                    <div class="form-group">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="faq_is_active" class="h-4 w-4 text-blue-600 focus:ring-blue-500 bg-gray-700 border-gray-600 rounded" checked>
                            <label for="faq_is_active" class="ml-2 block text-sm text-gray-300">Active (Widget will be visible)</label>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" class="cancel-btn mr-2 px-4 py-2 bg-gray-600 text-gray-300 rounded-md hover:bg-gray-500 transition-colors">Cancel</button>
                        <button type="submit" id="faq_submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Create FAQ Widget</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const widgetTypeBtns = document.querySelectorAll('.widget-type-btn');
        const widgetForms = document.querySelectorAll('.widget-form');
        const cancelBtns = document.querySelectorAll('.cancel-btn');
        const socialForm = document.getElementById('social_form');
        const socialTypeTitle = document.getElementById('social_type_title');
        const socialWidgetType = document.getElementById('social_widget_type');
        const socialSubmitText = document.getElementById('social_submit_text');
        const faqItemsContainer = document.getElementById('faq_items');
        const addFaqItemBtn = document.getElementById('add_faq_item');
        const faqSubmitBtn = document.getElementById('faq_submit');

        // Initialize counters
        let faqCounter = 1;

        // Show the selected form, hide others
        function showForm(type) {
            widgetForms.forEach(form => {
                form.classList.add('hidden');
            });

            if (type === 'instagram' || type === 'linkedin' || type === 'youtube' || type === 'facebook') {
                socialForm.classList.remove('hidden');
                socialTypeTitle.textContent = type.charAt(0).toUpperCase() + type.slice(1);
                socialWidgetType.value = type;
                socialSubmitText.textContent = type.charAt(0).toUpperCase() + type.slice(1);
            } else {
                document.getElementById(type + '_form').classList.remove('hidden');
            }

            // Scroll to form
            document.querySelector('.widget-forms').scrollIntoView({ behavior: 'smooth' });
        }

        // Widget type selection
        widgetTypeBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const type = this.dataset.type;
                showForm(type);
            });
        });

        // Cancel buttons
        cancelBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                widgetForms.forEach(form => {
                    form.classList.add('hidden');
                });

                // Scroll back to widget type selection
                document.querySelector('.mb-8').scrollIntoView({ behavior: 'smooth' });
            });
        });

        // Add FAQ item
        addFaqItemBtn.addEventListener('click', function() {
            faqCounter++;

            const newItem = document.createElement('div');
            newItem.className = 'faq-item border border-gray-600 bg-gray-700 rounded-md p-4';
            newItem.innerHTML = `
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-medium text-gray-300">Question ${faqCounter}</label>
                    <button type="button" class="remove-faq text-red-400 hover:text-red-300 transition-colors">
                        <i class="fas fa-times"></i> Remove
                    </button>
                </div>
                <div class="mb-3">
                    <input type="text" class="faq-question w-full px-3 py-2 bg-gray-600 border border-gray-500 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter question">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Answer ${faqCounter}</label>
                    <textarea class="faq-answer w-full px-3 py-2 bg-gray-600 border border-gray-500 text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" rows="3" placeholder="Enter answer"></textarea>
                </div>
            `;

            faqItemsContainer.appendChild(newItem);

            // Add remove event listener
            newItem.querySelector('.remove-faq').addEventListener('click', function() {
                faqItemsContainer.removeChild(newItem);
            });
        });

        // FAQ form submission
        faqSubmitBtn.addEventListener('click', function(e) {
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
            document.getElementById('faq_form').querySelector('form').submit();
        });
    });
</script>
@endpush
