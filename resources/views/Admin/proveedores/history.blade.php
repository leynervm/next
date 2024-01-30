<x-app-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="PROVEEDORES" route="admin.proveedores">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="1"
                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 17h4V5H2v12h3" />
                    <path d="M20 17h2v-3.34a4 4 0 0 0-1.17-2.83L19 9h-5" />
                    <path d="M14 17h1" />
                    <circle cx="7.5" cy="17.5" r="2.5" />
                    <circle cx="17.5" cy="17.5" r="2.5" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
        <x-link-breadcrumb text="HISTORIAL COMPRAS" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="1"
                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C7.52232 2 3.77426 4.94289 2.5 9H5" />
                    <path d="M12 8V12L14 14" />
                    <path
                        d="M2 12C2 12.3373 2.0152 12.6709 2.04494 13M9 22C8.6584 21.8876 8.32471 21.7564 8 21.6078M3.20939 17C3.01655 16.6284 2.84453 16.2433 2.69497 15.8462M4.83122 19.3065C5.1369 19.6358 5.46306 19.9441 5.80755 20.2292">
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    @if (count($sumatorias) > 0)
        <div class="w-full flex flex-wrap gap-5">
            @foreach ($sumatorias as $item)
                <x-minicard :title="null" size="lg" class="cursor-pointer">
                    <div class="text-xs font-medium text-center">
                        <small>TOTAL COMPRAS</small>
                        <h3 class="font-semibold text-lg">
                            {{ number_format($item->total, 2, '.', ', ') }}</h3>
                        <small>{{ $item->moneda->currency }}</small>
                    </div>
                </x-minicard>
            @endforeach
        </div>
    @endif

    @if (count($compras) > 0)
        <div class="w-full flex flex-col gap-5 mt-5">
            @foreach ($compras as $item)
                <div
                    class="w-full sm:flex sm:gap-3 rounded-md cursor-default bg-fondominicard shadow shadow-shadowminicard p-3 hover:shadow-md hover:shadow-shadowminicard">
                    <div class="w-full text-colortitleform">
                        <h1 class="font-semibold text-sm leading-5">
                            {{ $item->sucursal->name }}
                            @if ($item->sucursal->trashed())
                                <x-span-text text="NO DISPONIBLE" class="leading-3 !tracking-normal inline-block" />
                            @endif
                        </h1>
                        <h1 class="font-medium text-[10px]">FECHA COMPRA : {{ formatDate($item->date, 'DD MMMM Y') }}
                        </h1>
                        <div class="w-full text-[10px]">
                            <h1 class="inline-block">REFERENCIA : {{ $item->referencia }}</h1>
                            -
                            <h1 class="inline-block">GUÍA : {{ $item->guia }}</h1>
                        </div>
                        <h1 class="text-colorsubtitleform font-medium text-xs">{{ $proveedor->name }}</h1>
                        @if ($item->typepayment->paycuotas)
                            @if ($item->cuotas()->has('cajamovimiento')->sum('amount') == $item->total)
                                <x-span-text text="PAGADO" class="leading-3 !tracking-normal" type="green" />
                            @else
                                <x-span-text text="PENDIENTE PAGO" class="leading-3 !tracking-normal" type="red" />
                            @endif
                        @else
                            @if (count($item->cajamovimientos) > 0)
                                @if ($item->cajamovimientos()->sum('amount') == $item->total)
                                    <x-span-text text="PAGADO" class="leading-3 !tracking-normal" type="green" />
                                @else
                                    <x-span-text text="PENDIENTE PAGO" class="leading-3 !tracking-normal"
                                        type="red" />
                                @endif
                            @else
                                <x-span-text text="PENDIENTE PAGO" class="leading-3 !tracking-normal" type="red" />
                            @endif
                        @endif
                    </div>

                    <div class="w-full text-colortitleform">
                        <p class="font-medium text-xs text-end">
                            TOTAL {{ $item->moneda->simbolo }}</p>

                        <h3
                            class="font-semibold text-[10px] text-end leading-3 @if ($item->descuento > 0) text-green-500 @endif">
                            <small class="font-medium">DSCT</small>
                            {{ number_format($item->descuento, 2, '.', ', ') }}
                            <small class="font-medium">{{ $item->moneda->currency }}</small>
                        </h3>

                        <h3 class="font-semibold text-[10px] text-end leading-3">
                            <small class="font-medium">IGV</small>
                            {{ number_format($item->igv, 2, '.', ', ') }}
                            <small class="font-medium">{{ $item->moneda->currency }}</small>
                        </h3>

                        <h3 class="font-semibold text-2xl text-end leading-normal">
                            {{ number_format($item->total, 2, '.', ', ') }}
                            <small class="text-[10px] font-medium">{{ $item->moneda->currency }}</small>
                        </h3>

                        @if ($item->moneda->code == 'USD')
                            <h3
                                class="font-semibold text-[10px] text-end leading-3 @if ($item->descuento > 0) text-green-500 @endif">
                                <small class="font-medium">TIPO CAMBIO</small>
                                {{ number_format($item->tipocambio, 2, '.', ', ') }}
                            </h3>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</x-app-layout>
