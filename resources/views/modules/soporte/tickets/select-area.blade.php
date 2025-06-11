<x-admin-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="SOPORTE TÉCNICO" route="admin.soporte">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 10 2 2 4-4" />
                    <rect width="20" height="14" x="2" y="3" rx="2" />
                    <path d="M12 17v4" />
                    <path d="M8 21h8" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
        <x-link-breadcrumb text="TICKETS" route="admin.soporte.tickets">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                    <path d="M13 15.5v-6.5a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v4" />
                    <path d="M18 8v-3a1 1 0 0 0 -1 -1h-13a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h7" />
                    <path d="M16 9h2" />
                    <path d="M15 19l2 2l4 -4" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
        <x-link-breadcrumb text="SELECCIONAR AREA" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 18l-2 -4l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5l-2.901 8.034" />
                    <path
                        d="M21.121 20.121a3 3 0 1 0 -4.242 0c.418 .419 1.125 1.045 2.121 1.879c1.051 -.89 1.759 -1.516 2.121 -1.879z" />
                    <path d="M19 18v.01" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <h1 class="text-lg sm:text-xl md:text-3xl text-center text-primary font-medium mb-3 !leading-none">
        SELECCIONAR AREA RESPONSABLE DEL TICKET</h1>

    <div class="w-full flex flex-wrap justify-center gap-2 sm:gap-3">
        @foreach ($areas as $item)
            <a class="p-3 py-5 sm:p-5 md:p-8 md:py-10 rounded-lg font-medium sm:rounded-xl text-colorlabel text-center max-w-24 sm:max-w-32 md:max-w-48 !leading-none text-xs sm:text-sm border border-borderminicard hover:shadow-md hover:shadow-shadowminicard hover:text-primary transition-colors ease-in-out duration-300 flex flex-col justify-center items-center gap-2"
                href="{{ route('admin.soporte.tickets.create', $item) }}">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="size-8 sm:size-11 md:size-20 block">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M13.13 14.833a5.002 5.002 0 0 1 -1.13 -.833a5 5 0 0 0 -7 0v-9a5 5 0 0 1 7 0a5 5 0 0 0 7 0v8" />
                    <path d="M5 21v-7" />
                    <path d="M16 22l5 -5" />
                    <path d="M21 21.5v-4.5h-4.5" />
                </svg>
                {{ $item->name }}
            </a>
        @endforeach
    </div>
</x-admin-layout>
