<x-admin-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="VENTAS" route="admin.ventas">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M11.5 21h-2.926a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304h11.339a2 2 0 0 1 1.977 2.304l-.117 .761" />
                    <path d="M9 11v-5a3 3 0 0 1 6 0v5" />
                    <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                    <path d="M20.2 20.2l1.8 1.8" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>

        @can('admin.ventas.create')
            <x-link-breadcrumb text="REGISTRAR VENTA" active>
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M12.5 21h-3.926a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304h11.339a2 2 0 0 1 1.977 2.304l-.263 1.708" />
                        <path d="M16 19h6" />
                        <path d="M19 16v6" />
                        <path d="M9 11v-5a3 3 0 0 1 6 0v5" />
                    </svg>
                </x-slot>
            </x-link-breadcrumb>
        @endcan
    </x-slot>

    <div class="w-full flex flex-wrap xl:flex-nowrap gap-8 xl:h-[calc(100vh_-_4rem)]">
        <div class="w-full xl:flex-shrink-0 xl:w-96 xl:overflow-y-auto soft-scrollbar h-full">
            <livewire:modules.ventas.ventas.show-resumen-venta :seriecomprobante="$seriecomprobante" :moneda="$moneda" :concept="$concept" />
        </div>
        <div class="w-full xl:flex-1 xl:overflow-y-auto soft-scrollbar h-full">
            <livewire:modules.ventas.ventas.create-venta :pricetype="$pricetype" :moneda="$moneda" />
        </div>
    </div>
</x-admin-layout>
