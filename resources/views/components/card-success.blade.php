@props(['titulo', 'subtitulo' => null])

<div
    class="w-full mx-auto max-w-2xl bg-fondominicard rounded-lg p-10 flex items-center shadow shadow-shadowminicard justify-between">
    <div class="w-full">
        <svg class="mb-4 h-20 w-20 text-next-500 mx-auto" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd" />
        </svg>

        <h2 class="text-3xl mb-4 text-colortitle text-center font-bold">
            {{ __($titulo) }}</h2>

        @if (isset($subtitulo))
            {{ $subtitulo }}
        @endif

        {{ $slot }}
    </div>
</div>
