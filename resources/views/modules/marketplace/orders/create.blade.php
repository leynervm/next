<x-app-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="TIENDA WEB" route="productos">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M3.06164 15.1933L3.42688 13.1219C3.85856 10.6736 4.0744 9.44952 4.92914 8.72476C5.78389 8 7.01171 8 9.46734 8H14.5327C16.9883 8 18.2161 8 19.0709 8.72476C19.9256 9.44952 20.1414 10.6736 20.5731 13.1219L20.9384 15.1933C21.5357 18.5811 21.8344 20.275 20.9147 21.3875C19.995 22.5 18.2959 22.5 14.8979 22.5H9.1021C5.70406 22.5 4.00504 22.5 3.08533 21.3875C2.16562 20.275 2.4643 18.5811 3.06164 15.1933Z" />
                    <path
                        d="M7.5 8L7.66782 5.98618C7.85558 3.73306 9.73907 2 12 2C14.2609 2 16.1444 3.73306 16.3322 5.98618L16.5 8" />
                    <path d="M15 11C14.87 12.4131 13.5657 13.5 12 13.5C10.4343 13.5 9.13002 12.4131 9 11" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
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
        <x-link-breadcrumb text="REGISTRAR ORDEN" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path
                        d="M11.5 21.9999C8.29487 21.9939 5.09603 21.5203 3.78549 20.6104C3.06418 20.1097 2.51361 19.4143 2.20352 18.6124C1.69716 17.3029 2.18147 15.6144 3.1501 12.2373L3.87941 9.67787C4.24669 8.38895 5.42434 7.5 6.76456 7.5H16.2371C17.5765 7.5 18.7537 8.38793 19.1217 9.67584L19.5 11" />
                    <path d="M7 7.5V6.36364C7 3.95367 9.01472 2 11.5 2C13.9853 2 16 3.95367 16 6.36364V7.5" />
                    <path d="M10 11H13">
                    </path>
                    <path d="M14 18H22M18 22L18 14" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <div class="contenedor flex flex-col lg:flex-row gap-3 lg:gap-6 my-6">
        <div class="w-full flex-1">
            {{-- <div class="w-full mb-6">
                <h1 class="text-lg font-semibold text-primary">
                    RESUMEN DEL PEDIDO</h1>
            </div> --}}

            @if (count($shoppings) > 0)
                <div class="w-full flex flex-col gap-3">
                    @foreach ($shoppings as $item)
                        @php
                            $combo = getAmountCombo($item->options->promocion, $pricetype);
                            $image =
                                !is_null($item->model) && count($item->model->images) > 0
                                    ? pathURLProductImage($item->model->images->first()->url)
                                    : null;
                        @endphp

                        <div class="relative w-full flex flex-col gap-2 text-xs p-1 sm:p-2 border border-borderminicard shadow-md shadow-shadowminicard rounded-lg md:rounded-2xl"
                            wire:key="{{ $item->rowId }}">
                            <div
                                class="{{ $item->options->is_disponible ? '' : 'opacity-50' }} w-full flex flex-col xs:flex-row gap-2 text-xs">
                                <div class="w-full h-48 xs:size-32 sm:size-40 rounded overflow-hidden">
                                    @if ($image)
                                        <img src="{{ $image }}" alt=""
                                            class="block w-full h-full object-scale-down object-center overflow-hidden">
                                    @else
                                        <x-icon-file-upload class="w-full h-full" type="unknown" />
                                    @endif
                                </div>
                                <div class="w-ful flex-1">
                                    <p class="leading-tight text-xs text-colorlabel text-justify">
                                        {{ !is_null($item->model) ? $item->model->name : $item->name }}</p>
                                    @if ($combo)
                                        <div class="w-full flex items-center flex-wrap gap-1 sm:gap-3">
                                            @foreach ($combo->products as $itemcombo)
                                                <span class="block w-5 h-5 text-colorsubtitleform">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        color="currentColor" fill="none" stroke="currentColor"
                                                        stroke-width="2.5" stroke-linecap="round"
                                                        stroke-linejoin="round" class="w-full h-full block">
                                                        <path d="M12 4V20M20 12H4" />
                                                    </svg>
                                                </span>

                                                <a class="block size-16 sm:size-20 rounded-lg relative"
                                                    href="{{ route('productos.show', $itemcombo->producto_slug) }}">
                                                    @if ($itemcombo->imagen)
                                                        <img src="{{ $itemcombo->imagen }}"
                                                            alt="{{ $itemcombo->producto_slug }}"
                                                            class="block w-full h-full object-scale-down overflow-hidden rounded-lg">
                                                    @else
                                                        <x-icon-image-unknown
                                                            class="w-full h-full text-colorsubtitleform" />
                                                    @endif

                                                    @if ($itemcombo->price <= 0)
                                                        <x-span-text text="GRATIS" type="green"
                                                            class="text-nowrap absolute bottom-0 left-[50%] -translate-x-[50%] !text-[9px] py-0.5" />
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                        <p class="leading-tight text-xs text-colorlabel">
                                            {{ $item->options->promocion->titulo }}</p>
                                    @endif

                                    @if ($item->options->is_disponible)
                                        <h1
                                            class="text-colorlabel text-sm sm:text-lg md:text-xl font-semibold text-end !leading-none">
                                            @if ($item->options->promocion)
                                                <span
                                                    class="text-[10px] md:text-xs p-0.5 rounded text-colorerror font-medium line-through">
                                                    @if ($combo)
                                                        {{ number_format(getPrecioventa($item->model, $pricetype) + $combo->total_normal, 2, '.', ', ') }}
                                                    @else
                                                        {{ number_format(getPrecioventa($item->model, $pricetype), 2, '.', ', ') }}
                                                    @endif
                                                </span>
                                                <br>
                                            @endif

                                            <small
                                                class="text-[10px] font-medium">{{ $item->options->simbolo }}</small>
                                            {{ number_format($item->price, 2, '.', ', ') }}
                                        </h1>

                                        <h1
                                            class="text-colorlabel text-sm sm:text-lg md:text-xl font-semibold text-end !leading-tight">
                                            <small class="text-[10px] font-medium">TOTAL :
                                                {{ $item->options->simbolo }}</small>
                                            {{ number_format($item->price * $item->qty, 2, '.', ', ') }}
                                        </h1>
                                    @endif
                                </div>
                            </div>

                            @if (!$item->options->is_disponible)
                                <div
                                    class="absolute top-0 left-0 bg-body w-full h-full opacity-50 rounded-lg md:rounded-2xl">
                                </div>
                                <div
                                    class="absolute w-full h-full flex flex-col xs:flex-row flex-wrap justify-center gap-2 items-center top-[50%] left-[50%] -translate-x-[50%] -translate-y-[50%]">
                                    <span class="btn-outline-secondary bg-inherit inline-block text-center">
                                        PROMOCIÓN NO DISPONIBLE</span>
                                    <x-button-secondary wire:click="deleteitem('{{ $item->rowId }}')"
                                        wire:loading.attr="disabled" class="inline-block text-center">
                                        ELIMINAR</x-button-secondary>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <h1 class="text-xs p-3 font-medium text-colorerror">
                    NO EXISTEN PRODUCTOS AGREGADOS EN EL CARRITO...</h1>
            @endif
        </div>

        @if (auth()->user())
            <div class="w-full lg:w-96 lg:flex-shrink-0 relative">
                <div class="w-full lg:sticky lg:top-28">
                    <div class="w-full flex justify-between gap-2 items-end mb-2">
                        <small class="inline-block text-sm font-medium text-colorsubtitleform">
                            RESUMEN DEL CARRITO</small>
                        <p class="text-3xl text-right font-semibold text-colorlabel !leading-none">
                            <small class="text-[10px] font-medium">{{ $moneda->simbolo }}</small>
                            {{ number_format(getAmountCart($shoppings)->total, 2, '.', ', ') }}
                        </p>
                    </div>

                    <livewire:modules.marketplace.carrito.show-shippments :pricetype="$pricetype" />

                </div>
            </div>
        @endif
    </div>


    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha_v3.key_web') }}"></script>
    <script type="text/javascript" src="{{ config('services.niubiz.url_js') }}"></script>
</x-app-layout>
