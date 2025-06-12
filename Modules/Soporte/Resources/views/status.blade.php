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
        <x-link-breadcrumb text="ESTADOS DE TICKETS" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 block" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="5" width="6" height="6" rx="1"></rect>
                    <path d="m3 17 2 2 4-4"></path>
                    <path d="M13 6h8"></path>
                    <path d="M13 12h8"></path>
                    <path d="M13 18h8"></path>
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <div class="">
        @livewire('modules.soporte.estates.create-estate')
    </div>

    <div class="">
        @livewire('modules.soporte.estates.show-estates')
    </div>
</x-admin-layout>
