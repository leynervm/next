<div class="w-full flex gap-1 flex-wrap items-center justify-center">
    <label {{ $attributes->merge(['class' => $classes]) }}>
        <span class="w-3 h-3 block">
            <svg class="w-full h-full" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path
                    d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
            </svg>
        </span>

        {{ $slot }}

        @if (isset($titulo))
            <span class="text-center">{{ $titulo }}</span>
        @endif
    </label>

    @if (isset($clear))
        {{ $clear }}
    @endif

</div>
