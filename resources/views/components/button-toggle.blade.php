@props(['checked' => true])
@php
    $class =
        ' inline-flex items-center gap-1 text-xs p-0.5 rounded-sm disabled:opacity-25 transition ease-in-out duration-150';
@endphp


<button
    {{ $attributes->merge(['class' => $checked ? 'text-green-500' . $class : 'text-neutral-400 hover:text-gray-200' . $class, 'type' => 'button']) }}>
    {{ $slot }}
    @if ($checked)
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" fill-rule="evenodd" clip-rule="evenodd"
            class="w-5 h-5 scale-125 inline-block">
            <path
                d="M8 5.25C4.27208 5.25 1.25 8.27208 1.25 12C1.25 15.7279 4.27208 18.75 8 18.75H16C19.7279 18.75 22.75 15.7279 22.75 12C22.75 8.27208 19.7279 5.25 16 5.25H8ZM16 8.25C13.9289 8.25 12.25 9.92893 12.25 12C12.25 14.0711 13.9289 15.75 16 15.75C18.0711 15.75 19.75 14.0711 19.75 12C19.75 9.92893 18.0711 8.25 16 8.25Z"
                fill="currentColor" />
        </svg>
    @else
        {{-- <svg class="w-5 h-5 scale-125" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path fill="currentColor"
                d="M11 12C11 13.6569 9.65685 15 8 15C6.34315 15 5 13.6569 5 12C5 10.3431 6.34315 9 8 9C9.65685 9 11 10.3431 11 12Z" />
            <path
                d="M16 6H8C4.68629 6 2 8.68629 2 12C2 15.3137 4.68629 18 8 18H16C19.3137 18 22 15.3137 22 12C22 8.68629 19.3137 6 16 6Z" />
        </svg> --}}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" fill-rule="evenodd" clip-rule="evenodd"
            class="w-5 h-5 scale-125 inline-block">
            <path
                d="M16 5.25C19.7279 5.25 22.75 8.27208 22.75 12C22.75 15.7279 19.7279 18.75 16 18.75H8C4.27208 18.75 1.25 15.7279 1.25 12C1.25 8.27208 4.27208 5.25 8 5.25H16ZM8 8.25C10.0711 8.25 11.75 9.92893 11.75 12C11.75 14.0711 10.0711 15.75 8 15.75C5.92893 15.75 4.25 14.0711 4.25 12C4.25 9.92893 5.92893 8.25 8 8.25Z"
                fill="currentColor" />
        </svg>
    @endif
</button>
