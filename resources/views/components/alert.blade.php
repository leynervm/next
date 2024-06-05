@php
    switch ($type) {
        case 'success':
            $bgalert = 'bg-green-500';
            $borderalert = 'border-green-500';
            $colortitlealert = 'text-green-500';
            break;
        case 'light':
            $bgalert = 'bg-gray-500';
            $borderalert = 'border-gray-300';
            $colortitlealert = 'text-gray-300';
            break;
        case 'error':
            $bgalert = 'bg-red-300';
            $borderalert = 'border-red-500';
            $colortitlealert = 'text-red-500';
            break;
        case 'warning':
            $bgalert = 'bg-amber-500';
            $borderalert = 'border-amber-500';
            $colortitlealert = 'text-amber-500';
            break;
        case 'next':
            $bgalert = 'bg-next-500';
            $borderalert = 'border-next-500';
            $colortitlealert = 'text-next-500';
            break;
        default:
            $bgalert = 'bg-blue-500';
            $borderalert = 'border-blue-500';
            $colortitlealert = 'text-blue-500';
            break;
    }
@endphp

<div x-data="timer()" x-init="start()">
    <div class="fixed z-50 border-stroke flex items-center rounded-md border border-l-[8px] {{ $borderalert }} bg-fondominicard p-2 w-full max-w-sm bg-opacity-80"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-show="open">
        <div class="mr-3 flex-shrink-0 flex items-center justify-center rounded-full {{ $bgalert }} text-white p-1">
            @if (isset($icono))
                {{ $icono }}
            @endif
        </div>
        <div class="w-full flex-1 relative">
            <h3 class="mb-1 text-xs font-semibold pr-2 {{ $colortitlealert }}">
                {{ $titulo }}</h3>
            <p class="text-textspancardproduct text-xs leading-3 font-medium">
                {{ $mensaje }}</p>
            <button class="w-4 h-4 hover:text-red-500 text-gray-400 absolute top-0 right-0" @click="open = false">
                <svg class="w-ful h-full" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                    <path
                        d="M18.8839 5.11612C19.372 5.60427 19.372 6.39573 18.8839 6.88388L6.88388 18.8839C6.39573 19.372 5.60427 19.372 5.11612 18.8839C4.62796 18.3957 4.62796 17.6043 5.11612 17.1161L17.1161 5.11612C17.6043 4.62796 18.3957 4.62796 18.8839 5.11612Z" />
                    <path
                        d="M5.11612 5.11612C5.60427 4.62796 6.39573 4.62796 6.88388 5.11612L18.8839 17.1161C19.372 17.6043 19.372 18.3957 18.8839 18.8839C18.3957 19.372 17.6043 19.372 17.1161 18.8839L5.11612 6.88388C4.62796 6.39573 4.62796 5.60427 5.11612 5.11612Z" />
                </svg>
            </button>
        </div>
    </div>

    <script>
        function timer() {
            return {
                open: true,
                start() {
                    window.setInterval(() => {
                        this.open = false;
                    }, 10000)
                }
            }
        }
    </script>
</div>
