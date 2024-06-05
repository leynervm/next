<x-app-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="CARRITO DE COMPRAS" route="carshoop">
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

    <div class="w-full grid grid-cols-1 lg:grid-cols-2 items-start gap-5 py-10">
        @if (auth()->user())
            <div class="w-full">
                <livewire:modules.marketplace.carrito.show-shippments :empresa="$empresa" :moneda="$moneda" />
            </div>
        @endif

        <div class="w-full">
            <h1 class="block w-full font-semibold text-[10px] rounded-t-xl p-2 bg-fondospancardproduct text-colorlabel">
                RESUMEN DEL CARRITO</h1>

            @if (Cart::instance('shopping')->count() > 0)
                <div class="w-full flex flex-col divide-y rounded-b-xl">
                    @foreach (Cart::instance('shopping')->content() as $item)
                        <x-simple-card class="w-full flex flex-col xs:flex-row gap-2 text-xs">
                            <div class="w-full xs:w-14 h-14 xs:h-full rounded overflow-hidden">
                                @if ($item->model->getImageURL())
                                    <img src="{{ $item->model->getImageURL() }}" alt=""
                                        class="w-full h-full object-scale-down aspect-square">
                                @else
                                    <x-icon-file-upload class="!w-full !h-full !m-0 text-neutral-500" type="unknown" />
                                @endif
                            </div>
                            <div class="w-full flex flex-col gap-2 flex-1 h-full justify-between p-2">
                                <a class="leading-3 text-center xs:text-left text-xs text-colorlabel"> {{ $item->model->name }}</a>

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
                                        <h1 class="font-medium leading-3 text-left text-center">
                                            CANT: {{ $item->qty }} {{ $item->model->unit->name }}</h1>
                                    </div>
                                </div>
                            </div>
                        </x-simple-card>
                    @endforeach
                </div>

                <x-simple-card
                    class="w-full p-3 shadow-xl rounded-xl mt-4 text-colorlabel">
                    <p class="text-[10px] text-right font-semibold0">TOTAL</p>
                    <p class="text-xl text-right font-semibold0">
                        {{-- <small>{{ $moneda->simbolo }}</small> --}}
                        {{ Cart::instance('shopping')->subtotal() }}
                        {{-- <small class="text-[10px]">{{ $moneda->currency }}</small> --}}
                    </p>
                </x-simple-card>
            @else
                <h1 class="text-xs p-3 font-medium text-colorerror">
                    NO EXISTEN PRODUCTOS AGREGADOS EN EL CARRITO...</h1>
            @endif
        </div>
    </div>
</x-app-layout>
