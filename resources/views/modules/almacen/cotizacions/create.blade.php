<x-admin-layout>
    <x-slot name="breadcrumb">
        @if (Module::isEnabled('Almacen') || Module::isEnabled('Ventas'))
            @can('admin.cotizaciones')
                <x-link-breadcrumb text="COTIZACIONES" route="admin.cotizacions">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
                            <path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-.5" />
                            <path d="M16 4h2a2 2 0 0 1 1.73 1" />
                            <path d="M18.42 9.61a2.1 2.1 0 1 1 2.97 2.97L16.95 17 13 18l.99-3.95 4.43-4.44Z" />
                            <path d="M8 18h1" />
                        </svg>
                    </x-slot>
                </x-link-breadcrumb>
            @endcan

            <x-link-breadcrumb text="REGISTRAR COTIZACIÓN" active>
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="size-5"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3.5 9v11a2 2 0 0 0 2 2h13a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H12m1.5 15h4m-4-10h4m-4 5h4" />
                        <path d="M6.5 16.5 8 18l3-4m-1-9H3.5M10 5 7.083 2M10 5 7.083 8" />
                    </svg>
                </x-slot>
            </x-link-breadcrumb>
        @endif
    </x-slot>

    <div class="mx-auto xl:max-w-7xl h-full pb-14">
        <livewire:modules.almacen.cotizacions.create-cotizacion />
    </div>
</x-admin-layout>
