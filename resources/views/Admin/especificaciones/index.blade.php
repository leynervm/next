<x-app-layout>

    <x-slot name="breadcrumb">
        @if (Module::isEnabled('Almacen'))
            @can('admin.almacen')
                <x-link-breadcrumb text="ALMACÉN" route="admin.almacen">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M11 22C10.1818 22 9.40019 21.6698 7.83693 21.0095C3.94564 19.3657 2 18.5438 2 17.1613C2 16.7742 2 10.0645 2 7M11 22L11 11.3548M11 22C11.3404 22 11.6463 21.9428 12 21.8285M20 7V11.5" />
                            <path stroke="none" fill="currentColor"
                                d="M21.4697 22.5303C21.7626 22.8232 22.2374 22.8232 22.5303 22.5303C22.8232 22.2374 22.8232 21.7626 22.5303 21.4697L21.4697 22.5303ZM19.8697 20.9303L21.4697 22.5303L22.5303 21.4697L20.9303 19.8697L19.8697 20.9303ZM21.95 17.6C21.95 15.1976 20.0024 13.25 17.6 13.25V14.75C19.174 14.75 20.45 16.026 20.45 17.6H21.95ZM17.6 13.25C15.1976 13.25 13.25 15.1976 13.25 17.6H14.75C14.75 16.026 16.026 14.75 17.6 14.75V13.25ZM13.25 17.6C13.25 20.0024 15.1976 21.95 17.6 21.95V20.45C16.026 20.45 14.75 19.174 14.75 17.6H13.25ZM17.6 21.95C20.0024 21.95 21.95 20.0024 21.95 17.6H20.45C20.45 19.174 19.174 20.45 17.6 20.45V21.95Z" />
                            <path
                                d="M7.32592 9.69138L4.40472 8.27785C2.80157 7.5021 2 7.11423 2 6.5C2 5.88577 2.80157 5.4979 4.40472 4.72215L7.32592 3.30862C9.12883 2.43621 10.0303 2 11 2C11.9697 2 12.8712 2.4362 14.6741 3.30862L17.5953 4.72215C19.1984 5.4979 20 5.88577 20 6.5C20 7.11423 19.1984 7.5021 17.5953 8.27785L14.6741 9.69138C12.8712 10.5638 11.9697 11 11 11C10.0303 11 9.12883 10.5638 7.32592 9.69138Z" />
                            <path d="M5 12L7 13" />
                            <path d="M16 4L6 9" />
                        </svg>
                    </x-slot>
                </x-link-breadcrumb>
            @endcan
        @endif

        @can('admin.almacen.productos')
            <x-link-breadcrumb text="PRODUCTOS" route="admin.almacen.productos">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="1"
                        stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4.5 17V6H19.5V17H4.5Z" />
                        <path d="M4.5 6L6.5 2.00001L17.5 2L19.5 6" />
                        <path d="M10 9H14" />
                        <path
                            d="M11.9994 19.5V22M11.9994 19.5L6.99939 19.5M11.9994 19.5H16.9994M6.99939 19.5H1.99939V22M6.99939 19.5V22M16.9994 19.5H22L21.9994 22M16.9994 19.5V22" />
                    </svg>
                </x-slot>
            </x-link-breadcrumb>
        @endcan

        <x-link-breadcrumb text="CARACT. Y ESPECIFICACIONES PRODUCTO" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-list-todo">
                    <rect x="3" y="5" width="6" height="6" rx="1" />
                    <path d="m3 17 2 2 4-4" />
                    <path d="M13 6h8" />
                    <path d="M13 12h8" />
                    <path d="M13 18h8" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    @can('admin.almacen.caracteristicas.create')
        <div class="mt-3">
            <livewire:admin.especificaciones.create-especificacion />
        </div>
    @endcan

    @can('admin.almacen.caracteristicas')
        <div class="mt-3">
            <livewire:admin.especificaciones.show-especificaciones />
        </div>
    @endcan
</x-app-layout>
