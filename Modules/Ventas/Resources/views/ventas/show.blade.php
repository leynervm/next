<x-app-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="VENTAS" route="admin.ventas">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M11.5 21h-2.926a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304h11.339a2 2 0 0 1 1.977 2.304l-.117 .761" />
                    <path d="M9 11v-5a3 3 0 0 1 6 0v5" />
                    <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                    <path d="M20.2 20.2l1.8 1.8" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>

        <x-link-breadcrumb text="RESUMEN VENTA" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="1"
                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M11 22H9.62182C7.27396 22 6.10003 22 5.28565 21.2945C4.47127 20.5889 4.27181 19.3991 3.87289 17.0194L2.66933 9.83981C2.48735 8.75428 2.39637 8.21152 2.68773 7.85576C2.9791 7.5 3.51461 7.5 4.58564 7.5H19.4144C20.4854 7.5 21.0209 7.5 21.3123 7.85576C21.6036 8.21152 21.5126 8.75428 21.3307 9.83981L20.8009 13" />
                    <path d="M13.5 20C13.5 20 14.5 20 15.5 22C15.5 22 18.6765 17 21.5 16" />
                    <path d="M17.5 7.5C17.5 4.46243 15.0376 2 12 2C8.96243 2 6.5 4.46243 6.5 7.5" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <div class="mx-auto animate__animated animate__fadeIn animate__faster">
        <livewire:modules.ventas.ventas.show-venta :venta="$venta" :concept="$concept" />
    </div>
</x-app-layout>
