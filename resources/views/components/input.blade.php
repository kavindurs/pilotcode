@props([
    'label' => '',
    'icon' => '',
    'type' => 'text'
])

<div class="relative">
    @if($label)
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
    @endif

    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-{{ $icon }} text-gray-400"></i>
            </div>
        @endif

        <input type="{{ $type }}"
               {{ $attributes->merge([
                   'class' => 'block w-full ' . ($icon ? 'pl-10' : 'pl-4') . ' pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200'
               ]) }}>
    </div>
</div>
