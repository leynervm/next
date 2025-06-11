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
        <x-link-breadcrumb text="SELECCIONAR AREA" route="admin.soporte.tickets.selectarea">
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
        <x-link-breadcrumb :text="$areawork->name" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="size-5">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12.5 21h-7.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v7.5" />
                    <path d="M3 10h18" />
                    <path d="M10 3v18" />
                    <path d="M16 19h6" />
                    <path d="M19 16v6" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <div class="w-full mx-auto xl:max-w-7xl">
        <h1 class="text-lg sm:text-xl md:text-3xl text-center text-primary font-semibold mb-3 !leading-none">
            {{ $areawork->name }}</h1>

        <livewire:modules.soporte.tickets.create-ticket :areawork="$areawork" />
    </div>

</x-admin-layout>
