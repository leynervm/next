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
        <x-link-breadcrumb text="PRIORIDADES ATENCIÓN" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m3 8 4-4 4 4"></path>
                    <path d="M7 4v16"></path>
                    <path d="M11 12h4"></path>
                    <path d="M11 16h7"></path>
                    <path d="M11 20h10"></path>
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <div class="">
        @livewire('modules.soporte.priorities.create-priority')
    </div>

    <div class="mt-3">
        @livewire('modules.soporte.priorities.show-priorities')
    </div>

</x-admin-layout>
