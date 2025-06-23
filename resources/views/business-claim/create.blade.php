<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Claim Business - {{ $property->business_name }} | Scoreness</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-white text-text-body">

    @include('navigation_bars.business_home_nav')

    <!-- Main Content -->
    <div class="bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 min-h-screen">
        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 py-16">
            <!-- Enhanced Textured Background Pattern -->
            <div class="absolute inset-0 opacity-15">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                    <defs>
                        <!-- Main Grid Pattern -->
                        <pattern id="mainGrid" width="60" height="60" patternUnits="userSpaceOnUse">
                            <path d="M0 30 L60 30" stroke="#fff" stroke-width="0.5" fill="none" opacity="0.6" />
                            <path d="M30 0 L30 60" stroke="#fff" stroke-width="0.5" fill="none" opacity="0.6" />
                        </pattern>

                        <!-- Dotted Texture Pattern -->
                        <pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse">
                            <circle cx="10" cy="10" r="1" fill="#fff" opacity="0.3" />
                            <circle cx="5" cy="5" r="0.5" fill="#fff" opacity="0.2" />
                            <circle cx="15" cy="5" r="0.5" fill="#fff" opacity="0.2" />
                            <circle cx="5" cy="15" r="0.5" fill="#fff" opacity="0.2" />
                            <circle cx="15" cy="15" r="0.5" fill="#fff" opacity="0.2" />
                        </pattern>

                        <!-- Diagonal Lines Texture -->
                        <pattern id="diagonals" width="30" height="30" patternUnits="userSpaceOnUse">
                            <path d="M0 0 L30 30" stroke="#fff" stroke-width="0.3" fill="none" opacity="0.4" />
                            <path d="M0 30 L30 0" stroke="#fff" stroke-width="0.3" fill="none" opacity="0.3" />
                        </pattern>

                        <!-- Combined Pattern -->
                        <pattern id="texturedPattern" width="60" height="60" patternUnits="userSpaceOnUse">
                            <rect width="60" height="60" fill="url(#mainGrid)" />
                            <rect width="60" height="60" fill="url(#dots)" />
                            <rect width="60" height="60" fill="url(#diagonals)" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#texturedPattern)" />
                </svg>
            </div>

            <!-- Additional Texture Overlay -->
            <div class="absolute inset-0 opacity-5">
                <div class="w-full h-full" style="background-image:
                    radial-gradient(circle at 25% 25%, rgba(255,255,255,0.2) 2px, transparent 2px),
                    radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 1px, transparent 1px),
                    linear-gradient(45deg, rgba(255,255,255,0.05) 25%, transparent 25%),
                    linear-gradient(-45deg, rgba(255,255,255,0.05) 25%, transparent 25%);
                    background-size: 40px 40px, 60px 60px, 20px 20px, 20px 20px;
                    background-position: 0 0, 30px 30px, 0 0, 10px 10px;">
                </div>
            </div>

            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full mb-6">
                    <i class="fas fa-hand-holding text-white text-2xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-white mb-4">Claim Your Business</h1>
                <p class="text-xl text-blue-100 max-w-2xl mx-auto">Take control of your online presence and start managing customer reviews</p>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 pb-16">
            <!-- Business Preview Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 mb-8 relative">
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-2 rounded-full text-sm font-semibold shadow-lg">
                        <i class="fas fa-star mr-2"></i>Available for Claim
                    </div>
                </div>

                <div class="flex items-start space-x-6 mt-4">
                    <div class="flex-shrink-0">
                        @if($property->profile_picture)
                            <div class="relative">
                                <img src="{{ Storage::url($property->profile_picture) }}" alt="{{ $property->business_name }}"
                                     class="w-20 h-20 rounded-2xl object-cover shadow-lg border-4 border-white">
                                <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-green-500 rounded-full border-2 border-white"></div>
                            </div>
                        @else
                            <div class="relative">
                                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg border-4 border-white">
                                    <span class="text-white font-bold text-2xl">{{ substr($property->business_name, 0, 2) }}</span>
                                </div>
                                <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-green-500 rounded-full border-2 border-white"></div>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow">
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ $property->business_name }}</h2>
                        <div class="space-y-2">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt text-red-500 mr-3"></i>
                                <span>{{ $property->city }}, {{ $property->country }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-tag text-blue-500 mr-3"></i>
                                <span>{{ $property->category }}@if($property->subcategory) → {{ $property->subcategory }}@endif</span>
                            </div>
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-700 border border-orange-200">
                                <i class="fas fa-clock mr-2"></i>
                                {{ $property->status }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-8 py-6 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-file-alt text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Claim Information</h3>
                            <p class="text-gray-600">Please provide accurate information to verify your business ownership</p>
                        </div>
                    </div>
                </div>

                @if(session('error'))
                    <div class="mx-8 mt-6">
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('business-claim.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="property_id" value="{{ $property->id }}">

                    <!-- Business Information Section -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 pb-3 border-b border-gray-100">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-building text-blue-600 text-sm"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900">Business Information</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <label for="business_name" class="block text-sm font-semibold text-gray-700">Business Name *</label>
                                <input type="text" id="business_name" name="business_name" value="{{ old('business_name', $property->business_name) }}" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                                @error('business_name')
                                    <p class="text-red-500 text-sm mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="property_type" class="block text-sm font-semibold text-gray-700">Property Type *</label>
                                <select id="property_type" name="property_type" required onchange="toggleTypeSpecificFields()"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                                    <option value="web" {{ old('property_type', $property->property_type) == 'web' ? 'selected' : '' }}>🌐 Web Business</option>
                                    <option value="physical" {{ old('property_type', $property->property_type) == 'physical' ? 'selected' : '' }}>🏢 Physical Business</option>
                                </select>
                                @error('property_type')
                                    <p class="text-red-500 text-sm mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Domain field (for web businesses) -->
                            <div id="domain_field" class="{{ old('property_type', $property->property_type) == 'web' ? '' : 'hidden' }} space-y-2">
                                <label for="domain" class="block text-sm font-semibold text-gray-700">Website Domain</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-globe text-gray-400"></i>
                                    </div>
                                    <input type="url" id="domain" name="domain" value="{{ old('domain', $property->domain) }}" placeholder="https://example.com"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                                </div>
                                @error('domain')
                                    <p class="text-red-500 text-sm mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Business Document field (for physical businesses) -->
                            <div id="document_field" class="{{ old('property_type', $property->property_type) == 'physical' ? '' : 'hidden' }} space-y-2">
                                <label for="business_document" class="block text-sm font-semibold text-gray-700">Business Document</label>
                                <div class="relative">
                                    <input type="file" id="business_document" name="business_document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>
                                <p class="text-sm text-gray-500 flex items-center">
                                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                    Upload business license, registration, or other proof documents (PDF, DOC, JPG, PNG - Max 10MB)
                                </p>
                                @error('business_document')
                                    <p class="text-red-500 text-sm mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information Section -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 pb-3 border-b border-gray-100">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900">Your Information</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <label for="first_name" class="block text-sm font-semibold text-gray-700">First Name *</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $property->first_name) }}" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                                @error('first_name')
                                    <p class="text-red-500 text-sm mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="last_name" class="block text-sm font-semibold text-gray-700">Last Name *</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $property->last_name) }}" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                                @error('last_name')
                                    <p class="text-red-500 text-sm mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="country" class="block text-sm font-semibold text-gray-700">Country *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-flag text-gray-400"></i>
                                    </div>
                                    <input type="text" id="country" name="country" value="{{ old('country', $property->country) }}" required
                                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                                </div>
                                @error('country')
                                    <p class="text-red-500 text-sm mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Business Details Section -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 pb-3 border-b border-gray-100">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-chart-line text-blue-600 text-sm"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900">Business Details</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <label for="annual_revenue" class="block text-sm font-semibold text-gray-700">Annual Revenue</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-dollar-sign text-gray-400"></i>
                                    </div>
                                    <select id="annual_revenue" name="annual_revenue"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                                        <option value="">Select Revenue Range</option>
                                        <option value="1-9999" {{ old('annual_revenue', $property->annual_revenue) == '1-9999' ? 'selected' : '' }}>$1-9,999</option>
                                        <option value="10000-99999" {{ old('annual_revenue', $property->annual_revenue) == '10000-99999' ? 'selected' : '' }}>$10,000-99,999</option>
                                        <option value="100000-999999" {{ old('annual_revenue', $property->annual_revenue) == '100000-999999' ? 'selected' : '' }}>$100,000-999,999</option>
                                        <option value="1000000+" {{ old('annual_revenue', $property->annual_revenue) == '1000000+' ? 'selected' : '' }}>More than $1 million</option>
                                    </select>
                                </div>
                                @error('annual_revenue')
                                    <p class="text-red-500 text-sm mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="employee_count" class="block text-sm font-semibold text-gray-700">Employee Count</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-users text-gray-400"></i>
                                    </div>
                                    <select id="employee_count" name="employee_count"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                                        <option value="">Select Employee Range</option>
                                        <option value="1-9" {{ old('employee_count', $property->employee_count) == '1-9' ? 'selected' : '' }}>1-9</option>
                                        <option value="10-49" {{ old('employee_count', $property->employee_count) == '10-49' ? 'selected' : '' }}>10-49</option>
                                        <option value="50-99" {{ old('employee_count', $property->employee_count) == '50-99' ? 'selected' : '' }}>50-99</option>
                                        <option value="100-499" {{ old('employee_count', $property->employee_count) == '100-499' ? 'selected' : '' }}>100-499</option>
                                        <option value="500-999" {{ old('employee_count', $property->employee_count) == '500-999' ? 'selected' : '' }}>500-999</option>
                                        <option value="1000-9999" {{ old('employee_count', $property->employee_count) == '1000-9999' ? 'selected' : '' }}>1,000-9,999</option>
                                        <option value="10000+" {{ old('employee_count', $property->employee_count) == '10000+' ? 'selected' : '' }}>More than 10,000</option>
                                    </select>
                                </div>
                                @error('employee_count')
                                    <p class="text-red-500 text-sm mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="category_id" class="block text-sm font-semibold text-gray-700">Category *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-tags text-gray-400"></i>
                                    </div>
                                    <select id="category_id" name="category_id" required onchange="loadSubcategories()"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('category_id')
                                    <p class="text-red-500 text-sm mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="subcategory_id" class="block text-sm font-semibold text-gray-700">Subcategory</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-tag text-gray-400"></i>
                                    </div>
                                    <select id="subcategory_id" name="subcategory_id"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                                        <option value="">Select Subcategory</option>
                                    </select>
                                </div>
                                @error('subcategory_id')
                                    <p class="text-red-500 text-sm mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="business_email" class="block text-sm font-semibold text-gray-700">Business Email *</label>

                                <!-- Web Business Email (with domain extension) -->
                                <div id="web_email_field" class="{{ old('property_type', $property->property_type) == 'web' ? '' : 'hidden' }}">
                                    <div class="flex">
                                        <div class="relative flex-1">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-envelope text-gray-400"></i>
                                            </div>
                                            <input type="text" id="email_username" name="email_username"
                                                   value="{{ old('email_username', $property->business_email ? explode('@', $property->business_email)[0] ?? '' : '') }}"
                                                   placeholder="username" required
                                                   class="w-full pl-10 pr-2 py-3 border border-gray-200 rounded-l-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                                        </div>
                                        <div class="flex items-center px-3 py-3 bg-gray-100 border border-l-0 border-gray-200 rounded-r-xl">
                                            <span id="domain_extension" class="text-gray-600 text-sm">@example.com</span>
                                        </div>
                                    </div>
                                    <input type="hidden" id="business_email_hidden" name="business_email" value="{{ old('business_email', $property->business_email) }}">
                                </div>

                                <!-- Physical Business Email (regular input) -->
                                <div id="physical_email_field" class="{{ old('property_type', $property->property_type) == 'physical' ? '' : 'hidden' }}">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400"></i>
                                        </div>
                                        <input type="email" id="business_email_physical" name="business_email_physical"
                                               value="{{ old('business_email_physical', $property->business_email) }}" required
                                               class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                                    </div>
                                </div>

                                @error('business_email')
                                    <p class="text-red-500 text-sm mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                                @error('email_username')
                                    <p class="text-red-500 text-sm mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="zip_code" class="block text-sm font-semibold text-gray-700">ZIP Code</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-map-pin text-gray-400"></i>
                                    </div>
                                    <input type="text" id="zip_code" name="zip_code" value="{{ old('zip_code', $property->zip_code) }}"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                                </div>
                                @error('zip_code')
                                    <p class="text-red-500 text-sm mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-4 border-t border-gray-100">
                        <a href="{{ url()->previous() }}"
                           class="px-6 py-2.5 border-2 border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition-all duration-200 text-center flex items-center justify-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Cancel</span>
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                            <i class="fas fa-paper-plane"></i>
                            <span>Submit Claim</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleTypeSpecificFields() {
            const propertyType = document.getElementById('property_type').value;
            const domainField = document.getElementById('domain_field');
            const documentField = document.getElementById('document_field');
            const webEmailField = document.getElementById('web_email_field');
            const physicalEmailField = document.getElementById('physical_email_field');

            if (propertyType === 'web') {
                domainField.classList.remove('hidden');
                documentField.classList.add('hidden');
                webEmailField.classList.remove('hidden');
                physicalEmailField.classList.add('hidden');

                // Update domain extension when switching to web
                updateDomainExtension();
            } else {
                domainField.classList.add('hidden');
                documentField.classList.remove('hidden');
                webEmailField.classList.add('hidden');
                physicalEmailField.classList.remove('hidden');
            }
        }

        function extractDomainFromUrl(url) {
            try {
                // Remove protocol if present
                let domain = url.replace(/^https?:\/\//, '');
                // Remove www if present
                domain = domain.replace(/^www\./, '');
                // Remove trailing slash and path
                domain = domain.split('/')[0];
                // Remove port if present
                domain = domain.split(':')[0];
                return domain;
            } catch (e) {
                return '';
            }
        }

        function updateDomainExtension() {
            const domainInput = document.getElementById('domain');
            const domainExtensionSpan = document.getElementById('domain_extension');
            const emailUsernameInput = document.getElementById('email_username');
            const hiddenEmailInput = document.getElementById('business_email_hidden');

            if (domainInput && domainInput.value) {
                const domain = extractDomainFromUrl(domainInput.value);
                if (domain) {
                    domainExtensionSpan.textContent = '@' + domain;
                    updateFullEmail();
                } else {
                    domainExtensionSpan.textContent = '@example.com';
                }
            } else {
                domainExtensionSpan.textContent = '@example.com';
            }
        }

        function updateFullEmail() {
            const emailUsernameInput = document.getElementById('email_username');
            const domainExtensionSpan = document.getElementById('domain_extension');
            const hiddenEmailInput = document.getElementById('business_email_hidden');

            if (emailUsernameInput && domainExtensionSpan && hiddenEmailInput) {
                const username = emailUsernameInput.value;
                const domain = domainExtensionSpan.textContent;

                if (username && domain !== '@example.com') {
                    hiddenEmailInput.value = username + domain;
                } else {
                    hiddenEmailInput.value = '';
                }
            }
        }

        function handleFormSubmit() {
            const propertyType = document.getElementById('property_type').value;
            const webEmailField = document.getElementById('web_email_field');
            const physicalEmailField = document.getElementById('physical_email_field');

            if (propertyType === 'web') {
                // Copy the constructed email to the main business_email field
                const hiddenEmail = document.getElementById('business_email_hidden').value;
                const mainEmailInput = document.querySelector('input[name="business_email"]') ||
                                      document.createElement('input');
                mainEmailInput.type = 'hidden';
                mainEmailInput.name = 'business_email';
                mainEmailInput.value = hiddenEmail;

                if (!document.querySelector('input[name="business_email"]')) {
                    document.querySelector('form').appendChild(mainEmailInput);
                }

                // Disable physical email field
                const physicalEmailInput = document.getElementById('business_email_physical');
                if (physicalEmailInput) {
                    physicalEmailInput.disabled = true;
                }
            } else {
                // Copy physical email to main field
                const physicalEmail = document.getElementById('business_email_physical').value;
                const mainEmailInput = document.querySelector('input[name="business_email"]') ||
                                      document.createElement('input');
                mainEmailInput.type = 'hidden';
                mainEmailInput.name = 'business_email';
                mainEmailInput.value = physicalEmail;

                if (!document.querySelector('input[name="business_email"]')) {
                    document.querySelector('form').appendChild(mainEmailInput);
                }

                // Disable web email fields
                const usernameInput = document.getElementById('email_username');
                if (usernameInput) {
                    usernameInput.disabled = true;
                }
            }
        }

        function loadSubcategories() {
            const categorySelect = document.getElementById('category_id');
            const subcategorySelect = document.getElementById('subcategory_id');
            const categoryId = categorySelect.value;

            // Clear subcategory options
            subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';

            if (!categoryId) return;

            fetch(`/api/business-claim/subcategories/${categoryId}`)
                .then(response => response.json())
                .then(subcategories => {
                    subcategories.forEach(subcategory => {
                        const option = document.createElement('option');
                        option.value = subcategory.id;
                        option.textContent = subcategory.name;

                        // Check if this was the previously selected subcategory
                        if (subcategory.id == '{{ old('subcategory_id') }}') {
                            option.selected = true;
                        }

                        subcategorySelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error loading subcategories:', error);
                });
        }

        // Load subcategories on page load if category is selected
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category_id');
            if (categorySelect.value) {
                loadSubcategories();
            }

            // Add event listeners for domain and email functionality
            const domainInput = document.getElementById('domain');
            const emailUsernameInput = document.getElementById('email_username');
            const form = document.querySelector('form');

            // Update domain extension when domain changes
            if (domainInput) {
                domainInput.addEventListener('input', updateDomainExtension);
                domainInput.addEventListener('change', updateDomainExtension);
            }

            // Update full email when username changes
            if (emailUsernameInput) {
                emailUsernameInput.addEventListener('input', updateFullEmail);
                emailUsernameInput.addEventListener('change', updateFullEmail);
            }

            // Handle form submission
            if (form) {
                form.addEventListener('submit', handleFormSubmit);
            }

            // Initialize domain extension on page load
            updateDomainExtension();
        });
    </script>

    @include('footer.footer')
</body>
</html>
