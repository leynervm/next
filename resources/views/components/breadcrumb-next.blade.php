@props(['home' => '/'])
<div class="w-full flex flex-wrap items-center gap-1 lg:gap-3">
    <a href="{{ $home }}" aria-label="{{ $home }}"
        class="text-colorsubtitleform bg-fondominicard hover:text-next-400 rounded py-1 px-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
            <path
                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
        </svg>
    </a>
    {{ $slot }}
</div>
