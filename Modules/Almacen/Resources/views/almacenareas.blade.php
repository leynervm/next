<x-app-layout>
    <x-slot name="breadcrumb">
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

        <x-link-breadcrumb text="AREA Y ESTANTES" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="1"
                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m4 6 3-3 3 3" />
                    <path d="M7 17V3" />
                    <path d="m14 6 3-3 3 3" />
                    <path d="M17 17V3" />
                    <path d="M4 21h16" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    @can('admin.almacen.almacenareas')
        <x-title-next titulo="ÁREAS DE ALMACÉN" />
        @can('admin.almacen.almacenareas.create')
            <div class="my-1">
                <livewire:almacen::almacenareas.create-almacenarea />
            </div>
        @endcan

        <div class="mt-3">
            @livewire('almacen::almacenareas.show-almacenareas')
        </div>
    @endcan

    @can('admin.almacen.estantes')
        <x-title-next titulo="ESTANTES DE ALMACÉN" class="mt-5" />

        @can('admin.almacen.estantes.create')
            <div>
                @livewire('almacen::estantes.create-estante')
            </div>
        @endcan

        <div class="mt-3">
            @livewire('almacen::estantes.show-estantes')
        </div>
    @endcan
</x-app-layout>
