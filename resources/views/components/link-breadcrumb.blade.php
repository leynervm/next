@props(['text', 'active' => false, 'route' => null])

@php
    $colorText = $active ? 'text-next-400' : 'text-colorsubtitleform';
@endphp

{{-- <div class="inline-flex items-center gap-1"> --}}
<span class="text-colorsubtitleform">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd"
            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
            clip-rule="evenodd" />
    </svg>
</span>

@if ($active)
    <span class="flex items-center gap-1 bg-fondominicard {{ $colorText }} rounded py-1 px-2">
        {{ $icon }}
        <span class="text-[10px] font-medium">{{ $text }}</span>

    </span>
@else
    <a href="{{ route($route) }}" aria-label="{{ $text }}"
        class="flex items-center gap-1 bg-fondominicard {{ $colorText }} hover:text-next-400 rounded py-1 px-2">
        {{ $icon }}
        @if ($text)
            <span class="text-[10px] font-medium">{{ $text }}</span>
        @endif
    </a>
@endif
{{-- </div> --}}
