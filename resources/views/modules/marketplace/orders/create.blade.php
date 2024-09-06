<x-app-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="CARRITO DE COMPRAS" route="carshoop">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
        <x-link-breadcrumb text="REGISTRAR PEDIDO" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 7l.867 12.143a2 2 0 0 0 2 1.857h10.276a2 2 0 0 0 2 -1.857l.867 -12.143h-16z" />
                    <path d="M8.5 7c0 -1.653 1.5 -4 3.5 -4s3.5 2.347 3.5 4" />
                    <path
                        d="M9.5 17c.413 .462 1 1 2.5 1s2.5 -.897 2.5 -2s-1 -1.5 -2.5 -2s-2 -1.47 -2 -2c0 -1.104 1 -2 2 -2s1.5 0 2.5 1" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <div class="contenedor file:w-full grid grid-cols-1 lg:grid-cols-2 items-start gap-5 py-10">
        @if (auth()->user())
            <div class="w-full">
                <livewire:modules.marketplace.carrito.show-shippments :empresa="$empresa" :moneda="$moneda" />
            </div>
        @endif

        <div class="w-full">
            @if (Cart::instance('shopping')->count() > 0)
                <div class="w-full flex flex-col gap-1">
                    @foreach (Cart::instance('shopping')->content() as $item)
                        <x-simple-card class="w-full flex flex-col xs:flex-row gap-2 text-xs p-1">
                            <div class="w-full xs:w-20 flex-shrink-0 h-20 xs:h-full rounded">
                                @if ($item->model->getImageURL())
                                    <img src="{{ $item->model->getImageURL() }}" alt=""
                                        class="block w-full h-full object-scale-down aspect-square">
                                @else
                                    <x-icon-file-upload class="!w-full !h-full !m-0 text-colorsubtitleform" type="unknown" />
                                @endif
                            </div>
                            <div class="w-full flex flex-1 flex-col gap-2 h-full justify-between p-2">
                                <a class="leading-3 text-center xs:text-left text-xs text-colorlabel">
                                    {{ $item->model->name }}</a>

                                <div class="w-full flex flex-wrap items-start justify-between gap-2">
                                    <h1 class="text-sm text-green-500">
                                        <small class="text-[10px] text-colorsubtitleform">IMPORTE :
                                            {{ $item->options->simbolo }}</small>
                                        {{ number_format($item->price * $item->qty, 2, '.', ',') }}
                                        <small
                                            class="text-[10px] text-colorsubtitleform">{{ $item->options->currency }}</small>
                                    </h1>
                                    <div class="text-[10px] text-colorsubtitleform text-end">
                                        <h1 class=" leading-3">
                                            P. UNIT : {{ $item->options->simbolo }}
                                            {{ number_format($item->price, 2, '.', ', ') }}
                                        </h1>
                                        <h1 class="font-medium leading-3 text-right">
                                            CANT: {{ $item->qty }} {{ $item->model->unit->name }}</h1>
                                    </div>
                                </div>
                            </div>
                        </x-simple-card>
                    @endforeach
                </div>

                <x-simple-card class="w-full p-3 mt-4 text-colorlabel">
                    <p class="text-[10px] text-right font-semibold0">TOTAL</p>
                    <p class="text-xl text-right font-semibold">
                        <small class="text-[10px] font-medium">{{ $moneda->simbolo }}</small>
                        {{ number_format(Cart::instance('shopping')->subtotal(), 2, '.', ', ') }}
                    </p>
                </x-simple-card>
            @else
                <h1 class="text-xs p-3 font-medium text-colorerror">
                    NO EXISTEN PRODUCTOS AGREGADOS EN EL CARRITO...</h1>
            @endif
        </div>
    </div>
</x-app-layout>
