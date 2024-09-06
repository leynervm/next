<x-admin-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="CLIENTES" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 19a6 6 0 0 0-12 0" />
                    <circle cx="8" cy="9" r="4" />
                    <path d="M22 19a6 6 0 0 0-6-6 4 4 0 1 0 0-8" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    @can('admin.clientes.create')
        <div class="">
            <livewire:admin.clients.create-client />
        </div>
    @endcan

    @can('admin.clientes')
        <div class="mt-1">
            <livewire:admin.clients.show-clients />
        </div>
    @endcan
</x-admin-layout>
