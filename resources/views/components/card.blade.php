@props([
    'header' => null,
    'footer' => null,
    'padding' => 'p-6',
])

<div {{ $attributes->merge(['class' => 'bg-white shadow-lg rounded-lg overflow-hidden']) }}>
    @if($header)
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            {{ $header }}
        </div>
    @endif

    <div class="{{ $padding }}">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $footer }}
        </div>
    @endif
</div>
