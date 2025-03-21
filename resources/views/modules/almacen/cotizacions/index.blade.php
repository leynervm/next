<x-admin-layout>
    <x-slot name="breadcrumb">
        @if (Module::isEnabled('Almacen') || Module::isEnabled('Ventas'))
            @can('admin.cotizaciones')
                <x-link-breadcrumb text="COTIZACIONES" active>
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
        @endif
    </x-slot>

    <div class="flex flex-wrap gap-2 mt-3">
        @can('admin.cotizaciones.create')
            <x-link-next href="{{ route('admin.cotizacions.create') }}" titulo="Registrar">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" x2="12" y1="5" y2="19" />
                    <line x1="5" x2="19" y1="12" y2="12" />
                </svg>
            </x-link-next>
        @endcan
    </div>

    @can('admin.cotizaciones')
        <div class="w-full">
            <livewire:modules.almacen.cotizacions.show-cotizacions />
        </div>
    @endcan

</x-admin-layout>
