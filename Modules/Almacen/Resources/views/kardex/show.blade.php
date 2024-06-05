<x-admin-layout>
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

        @can('admin.almacen.kardex')
            <x-link-breadcrumb text="KARDEX" route="admin.almacen.kardex">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="1"
                        stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d=" m3 16 4 4 4-4" />
                        <path d="M7 20V4" />
                        <path d="m21 8-4-4-4 4" />
                        <path d="M17 4v16" />
                    </svg>
                </x-slot>
            </x-link-breadcrumb>
        @endcan

        @can('admin.almacen.kardex.series')
            <x-link-breadcrumb text="RASTREAR SERIES" route="admin.almacen.kardex.series">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="1"
                        stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.5 17.5L22 22" />
                        <path
                            d="M20 11C20 6.02944 15.9706 2 11 2C6.02944 2 2 6.02944 2 11C2 15.9706 6.02944 20 11 20C15.9706 20 20 15.9706 20 11Z" />
                        <path
                            d="M9.4924 7.5C8.77591 7.54342 8.31993 7.66286 7.99139 7.99139C7.66286 8.31993 7.54342 8.77591 7.5 9.4924M12.5076 7.5C13.2241 7.54342 13.6801 7.66286 14.0086 7.99139C14.3371 8.31993 14.4566 8.77591 14.5 9.4924M14.4923 12.6214C14.4431 13.273 14.3194 13.6978 14.0086 14.0086C13.6801 14.3371 13.2241 14.4566 12.5076 14.5M9.4924 14.5C8.7759 14.4566 8.31993 14.3371 7.99139 14.0086C7.6806 13.6978 7.55693 13.273 7.50772 12.6214" />
                    </svg>
                </x-slot>
            </x-link-breadcrumb>
        @endcan

        <x-link-breadcrumb text="RESULTADOS" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="1"
                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path fill="currentColor" stroke-width="0" stroke="none"
                        d="M21.4697 21.5303C21.7626 21.8232 22.2374 21.8232 22.5303 21.5303C22.8232 21.2374 22.8232 20.7626 22.5303 20.4697L21.4697 21.5303ZM19.0697 19.1303L21.4697 21.5303L22.5303 20.4697L20.1303 18.0697L19.0697 19.1303ZM21.55 14.4C21.55 11.0034 18.7966 8.25 15.4 8.25V9.75C17.9681 9.75 20.05 11.8319 20.05 14.4H21.55ZM15.4 8.25C12.0034 8.25 9.25 11.0034 9.25 14.4H10.75C10.75 11.8319 12.8319 9.75 15.4 9.75V8.25ZM9.25 14.4C9.25 17.7966 12.0034 20.55 15.4 20.55V19.05C12.8319 19.05 10.75 16.9681 10.75 14.4H9.25ZM15.4 20.55C18.7966 20.55 21.55 17.7966 21.55 14.4H20.05C20.05 16.9681 17.9681 19.05 15.4 19.05V20.55Z" />
                    <path d="M2 10L7 10" />
                    <path d="M2 17H7" />
                    <path d="M2 3H19" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <div class="w-full mt-3 flex flex-wrap gap-5">

        <div class="w-full p-3 shadow shadow-shadowminicard rounded max-w-sm bg-fondominicard">
            <x-title-next titulo="DETALLES" />

            <div class="mt-3 flex flex-col gap-2">
                <div>
                    <x-label value="Serie :" />
                    <x-span-text :text="$serie->serie" class="leading-3 !tracking-normal" />
                </div>
                <div>
                    <x-label value="Fecha registro :" />
                    <x-span-text :text="formatDate($serie->created_at)" class="leading-3 !tracking-normal" />
                </div>
                <div>
                    <x-label value="Producto relacionado:" />
                    <x-span-text :text="$serie->producto->name" class="leading-3 !tracking-normal" />
                </div>
                <div>
                    <x-label value="Almacén relacionado :" />
                    <x-span-text :text="$serie->almacen->name" class="leading-3 !tracking-normal" />
                </div>
                <div>
                    <x-label value="Usuario registro :" />
                    <x-span-text :text="$serie->user->name" class="leading-3 !tracking-normal" />
                </div>
                {{-- <div>
                    <x-label value="Sucursal relacinado :" />
                    <x-span-text :text="$serie->almacen->sucursal->name" />
                </div> --}}
            </div>
        </div>

        @if ($serie->compraitem)
            <div class="w-full p-3 shadow shadow-shadowminicard rounded max-w-sm bg-fondominicard">
                <x-title-next titulo="ENTRADA" />

                <div class="mt-3 flex flex-col gap-2">
                    <div>
                        <x-label value="Fecha entrada :" />
                        <x-span-text :text="formatDate($serie->compraitem->created_at)" class="leading-3 !tracking-normal" />
                    </div>
                    <div>
                        <x-label value="Cantidad entrante :" />
                        <x-span-text :text="formatDecimalOrInteger($serie->compraitem->cantidad) .
                            ' ' .
                            $serie->compraitem->producto->unit->name" class="leading-3 !tracking-normal" />
                    </div>
                    <div>
                        <x-label value="Documento referencia entrada :" />
                        <x-span-text :text="$serie->compraitem->compra->referencia" class="block leading-3 !tracking-normal" />
                    </div>
                </div>
                {{-- {{ $serie->compraitem }} --}}
            </div>
        @endif

        @if ($serie->itemserie)
            <div class="w-full p-3 shadow shadow-shadowminicard rounded max-w-sm bg-fondominicard">
                <x-title-next titulo="SALIDA" />

                <div class="mt-3 flex flex-col gap-2">
                    <div>
                        <x-label value="Fecha salida :" />
                        <x-span-text :text="formatDate($serie->itemserie->date)" class="leading-3 !tracking-normal" />
                    </div>
                    <div>
                        <x-label value="Cantidad salida :" />
                        <x-span-text :text="formatDecimalOrInteger($serie->itemserie->tvitem->cantidad) .
                            ' ' .
                            $serie->producto->unit->name" class="leading-3 !tracking-normal" />
                    </div>

                    <div>
                        <x-label value="Documento referencia salida :" />
                        <x-span-text :text="$serie->itemserie->tvitem->tvitemable->seriecompleta" />
                    </div>
                </div>
            </div>
        @endif
    </div>

</x-admin-layout>
