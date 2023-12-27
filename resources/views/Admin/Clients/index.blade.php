<x-app-layout>
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


    <div class="">
        @livewire('admin.clients.create-client')
        {{-- <x-link-next href="{{ route('admin.channelsales') }}" titulo="Canales Venta">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M8.3 10a.7.7 0 0 1-.626-1.079L11.4 3a.7.7 0 0 1 1.198-.043L16.3 8.9a.7.7 0 0 1-.572 1.1Z" />
                <rect x="3" y="14" width="7" height="7" rx="1" />
                <circle cx="17.5" cy="17.5" r="3.5" />
            </svg>
        </x-link-next> --}}
    </div>

    {{-- <x-title-next titulo="Listado clientes" class="mt-5" /> --}}
    <div class="mt-1">
        @livewire('admin.clients.show-clients')
    </div>

</x-app-layout>
