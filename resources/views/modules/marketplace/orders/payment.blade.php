<x-app-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="MIS COMPRAS" route="orders">
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
        <x-link-breadcrumb text="RESUMEN DE ORDEN" active>
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

    <div class="contenedor py-5">
        @if (session('message'))
            <div class="w-full flex gap-2 items-center justify-center mb-5">
                <svg class="inline-block w-6 h-6 bg-primary text-white rounded-full" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 11.917 9.724 16.5 19 7.5"></path>
                </svg>
                <h1 class="text-2xl font-semibold text-primary">
                    ¡ Gracias por su compra !</h1>
            </div>
        @endif

        <div
            class="w-full text-xs grid grid-cols-1 xs:grid-cols-3 lg:grid-cols-7 xl:grid-cols-7 font-medium gap-3 xl:gap-5">
            <div class="w-full border border-borderminicard p-3 rounded-lg">
                <svg class="w-10 h-10 inline-block text-colorsubtitleform" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M10 3v4a1 1 0 0 1-1 1H5m4 6 2 2 4-4m4-8v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z">
                    </path>
                </svg>
                <p class="text-sm text-colorsubtitleform font-semibold">
                    #{{ $order->purchase_number }}</p>

                <p class="text-xs font-medium text-colorsubtitleform">
                    {{ formatDate($order->transaccion->date, 'DD MMM Y') }}</p>
            </div>
            <div class="w-full xs:col-span-2 xl:col-span-2 border border-borderminicard p-3 rounded-lg">
                <span class="inline-block w-10 h-10 text-colorsubtitleform">
                    @if ($order->shipmenttype->isEnviodomicilio())
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 33" fill-rule="evenodd"
                            clip-rule="evenodd" fill="currentColor" class="w-full h-full">
                            <path
                                d="M3 7.5C3 6.39543 3.89543 5.5 5 5.5H17C18.1046 5.5 19 6.39543 19 7.5V10.5H24.4338C25.1363 10.5 25.7873 10.8686 26.1488 11.471L28.715 15.748C28.9015 16.0588 29 16.4145 29 16.777V22.5C29 23.6046 28.1046 24.5 27 24.5H25.874C25.4299 26.2252 23.8638 27.5 22 27.5C20.0283 27.5 18.3898 26.0734 18.0604 24.1961C17.753 24.3887 17.3895 24.5 17 24.5H12.874C12.4299 26.2252 10.8638 27.5 9 27.5C7.12577 27.5 5.55261 26.211 5.1187 24.4711C3.91896 24.2875 3 23.2511 3 22V21.5C3 20.9477 3.44772 20.5 4 20.5C4.55228 20.5 5 20.9477 5 21.5V22C5 22.1459 5.06252 22.2773 5.16224 22.3687C5.65028 20.7105 7.18378 19.5 9 19.5C10.8638 19.5 12.4299 20.7748 12.874 22.5H17V16.5V7.5H5V8.5C5 9.05228 4.55228 9.5 4 9.5C3.44772 9.5 3 9.05228 3 8.5V7.5ZM19 15.5V12.5H24.4338L26.2338 15.5H19ZM19 17.5H27V22.5H25.874C25.4299 20.7748 23.8638 19.5 22 19.5C20.8053 19.5 19.7329 20.0238 19 20.8542V17.5ZM22 21.5C23.1046 21.5 24 22.3954 24 23.5C24 24.6046 23.1046 25.5 22 25.5C20.8954 25.5 20 24.6046 20 23.5C20 22.3954 20.8954 21.5 22 21.5ZM7 23.5C7 24.6046 7.89543 25.5 9 25.5C10.1046 25.5 11 24.6046 11 23.5C11 22.3954 10.1046 21.5 9 21.5C7.89543 21.5 7 22.3954 7 23.5ZM2 10.5C1.44772 10.5 1 10.9477 1 11.5C1 12.0523 1.44772 12.5 2 12.5H7C7.55228 12.5 8 12.0523 8 11.5C8 10.9477 7.55228 10.5 7 10.5H2ZM3 13.5C2.44772 13.5 2 13.9477 2 14.5C2 15.0523 2.44772 15.5 3 15.5H7C7.55228 15.5 8 15.0523 8 14.5C8 13.9477 7.55228 13.5 7 13.5H3ZM3 17.5C3 16.9477 3.44772 16.5 4 16.5H7C7.55229 16.5 8 16.9477 8 17.5C8 18.0523 7.55229 18.5 7 18.5H4C3.44772 18.5 3 18.0523 3 17.5Z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 33" fill-rule="evenodd"
                            clip-rule="evenodd" fill="currentColor" class="w-full h-full">
                            <path
                                d="M18.4449 14.2024C19.4296 12.8623 20 11.5761 20 10.5C20 8.29086 18.2091 6.5 16 6.5C13.7909 6.5 12 8.29086 12 10.5C12 11.5761 12.5704 12.8623 13.5551 14.2024C14.3393 15.2698 15.2651 16.2081 16 16.8815C16.7349 16.2081 17.6607 15.2698 18.4449 14.2024ZM16.8669 18.7881C18.5289 17.3455 22 13.9227 22 10.5C22 7.18629 19.3137 4.5 16 4.5C12.6863 4.5 10 7.18629 10 10.5C10 13.9227 13.4712 17.3455 15.1331 18.7881C15.6365 19.2251 16.3635 19.2251 16.8669 18.7881ZM5 11.5H8.27078C8.45724 12.202 8.72804 12.8724 9.04509 13.5H5V26.5H10.5V22C10.5 21.4477 10.9477 21 11.5 21H20.5C21.0523 21 21.5 21.4477 21.5 22V26.5H27V13.5H22.9549C23.272 12.8724 23.5428 12.202 23.7292 11.5H27C28.1046 11.5 29 12.3954 29 13.5V26.5C29.5523 26.5 30 26.9477 30 27.5C30 28.0523 29.5523 28.5 29 28.5H3C2.44772 28.5 2 28.0523 2 27.5C2 26.9477 2.44772 26.5 3 26.5V13.5C3 12.3954 3.89543 11.5 5 11.5ZM19.5 23V26.5H12.5V23H19.5ZM17 10.5C17 11.0523 16.5523 11.5 16 11.5C15.4477 11.5 15 11.0523 15 10.5C15 9.94772 15.4477 9.5 16 9.5C16.5523 9.5 17 9.94772 17 10.5ZM19 10.5C19 12.1569 17.6569 13.5 16 13.5C14.3431 13.5 13 12.1569 13 10.5C13 8.84315 14.3431 7.5 16 7.5C17.6569 7.5 19 8.84315 19 10.5Z" />
                        </svg>
                    @endif
                </span>
                <p class="text-sm text-colorsubtitleform font-semibold leading-none">
                    {{ $order->shipmenttype->name }}</p>

                @if ($order->shipmenttype->isEnvioDomicilio())
                    <p class="text-colorsubtitleform text-[10px] leading-3">
                        DIRECCIÓN: {{ $order->direccion->name }}</p>
                    <p class="text-colorsubtitleform text-[10px] leading-3">
                        REF.: {{ $order->direccion->referencia }}</p>
                    <p class="text-colorsubtitleform text-[10px] leading-3">
                        {{ $order->direccion->ubigeo->distrito }},
                        {{ $order->direccion->ubigeo->provincia }},
                        {{ $order->direccion->ubigeo->region }}</p>
                @else
                    <p class="text-colorsubtitleform text-[10px]">
                        {{ $order->entrega->sucursal->name }}</p>
                    <p class="text-colorsubtitleform text-[10px] leading-3">
                        {{ $order->entrega->sucursal->direccion }} <br>
                        {{ $order->entrega->sucursal->ubigeo->distrito }},
                        {{ $order->entrega->sucursal->ubigeo->provincia }},
                        {{ $order->entrega->sucursal->ubigeo->region }}</p>
                    <p class="text-colorsubtitleform text-[10px]">
                        FECHA RECLAMO : {{ formatDate($order->entrega->date, 'DD MMMM Y') }}</p>
                @endif
            </div>
            <div class="w-full lg:col-span-2 border border-borderminicard p-3 rounded-lg">
                <h1 class="text-sm text-colorsubtitleform font-semibold leading-none">RECIBE PEDIDO</h1>
                <p class="text-colorsubtitleform font-medium text-[10px] uppercase">
                    {{ $order->receiverinfo['name'] }} <br>
                    {{ $order->receiverinfo['document'] }}</p>
                <p class="text-colorsubtitleform font-medium text-[10px]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block text-green-600 w-4 h-4 p-0.5"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                    </svg>
                    {{ formatTelefono($order->receiverinfo['telefono']) }}
                </p>
            </div>
            @if ($order->transaccion)
                <div class="w-full xs:col-span-2 md:col-span-2 border border-borderminicard p-3 rounded-lg">
                    <table class="w-full text-xs">
                        <tbody class="">
                            <tr>
                                {{-- <td class="text-colorsubtitleform">Monto</td> --}}
                                <td class="font-semibold text-colorsubtitleform text-end text-xl !leading-none">
                                    {{ number_format($order->transaccion->amount, 2, '.', ', ') }}
                                    <small class="text-[10px]">{{ $order->transaccion->currency }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-medium text-colorlabel text-end">
                                    @if ($order->transaccion->brand == 'visa')
                                        <x-icon-file-upload type="visa" class="!w-10 !h-6 !p-0 !m-0 !ml-auto" />
                                    @elseif ($order->transaccion->brand == 'mastercard')
                                        <x-icon-file-upload type="visa" class="!w-10 !h-6 !p-0 !m-0 !ml-auto" />
                                    @elseif ($order->transaccion->brand == 'paypal')
                                        <x-icon-file-upload type="paypal" class="!w-10 !h-6 !p-0 !m-0 !ml-auto" />
                                    @elseif ($order->transaccion->brand == 'unionpay')
                                        <x-icon-file-upload type="unionpay" class="!w-10 !h-6 !p-0 !m-0 !ml-auto" />
                                    @elseif ($order->transaccion->brand == 'dinersclub')
                                        <x-icon-file-upload type="dinersclub" class="!w-10 !h-6 !p-0 !m-0 !ml-auto" />
                                    @elseif ($order->transaccion->brand == 'amex')
                                        <x-icon-file-upload type="amex" class="!w-10 !h-6 !p-0 !m-0 !ml-auto" />
                                    @else
                                        <x-icon-file-upload type="paydefault" class="!w-10 !h-6 !p-0 !m-0 !ml-auto" />
                                    @endif

                                    {{ $order->transaccion->card }}
                                    <br>
                                    ID : {{ $order->transaccion->transaction_id }}
                                </td>
                            </tr>
                            <tr>
                                <td class="w-full text-end align-middle">
                                    <div class="w-full flex items-center justify-end">
                                        <x-span-text :text="$order->transaccion->action_description" class="text-xs" type="green" />
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="contenedor w-full grid grid-cols-1 lg:grid-cols-7 gap-3 xl:gap-5">
        <div class="lg:col-span-5 w-full overflow-x-auto {{-- rounded-xl border border-borderminicard --}}">
            <table class="w-full min-w-full text-[10px] md:text-xs">
                <tbody class="divide-y">
                    @foreach ($order->tvitems as $item)
                        @php
                            $image = !empty($item->producto->imagen)
                                ? pathURLProductImage($item->producto->imagen->url)
                                : null;
                        @endphp

                        <tr class="text-colorlabel">
                            <td class="text-left py-2 align-middle">
                                <div class="flex flex-col xs:flex-row items-center gap-1 xs:gap-2">
                                    <div
                                        class="flex-shrink-0 w-full max-w-40 xs:size-36 xl:size-28 rounded overflow-hidden">
                                        @if ($image)
                                            <img src="{{ $image }}" alt=""
                                                class="w-full h-full object-scale-down rounded aspect-square overflow-hidden">
                                        @else
                                            <x-icon-file-upload class="w-full h-full" type="unknown" />
                                        @endif
                                    </div>
                                    <div
                                        class="w-full flex-1 sm:flex justify-between gap-3 items-center text-colorsubtitleform">
                                        <div class="w-full text-xs sm:flex-1">
                                            @if (!empty($item->promocion) && $item->promocion->isCombo())
                                                <p class="w-full text-center sm:text-start">
                                                    {{ $item->promocion->titulo }}</p>
                                                <span
                                                    class="p-1 font-semibold inline-block ring-1 rounded-lg text-[10px] ring-green-600 text-end text-green-600 whitespace-nowrap">
                                                    PROMOCIÓN</span>
                                            @else
                                                <p class="w-full text-center sm:text-start">
                                                    {{ $item->producto->name }}</p>
                                            @endif

                                            @if (count($item->carshoopitems) > 0)
                                                <div class="w-full flex flex-wrap gap-2 mt-2">
                                                    @foreach ($item->carshoopitems as $carshoopitem)
                                                        @php
                                                            $imagen = !empty($carshoopitem->producto->imagen)
                                                                ? pathURLProductImage(
                                                                    $carshoopitem->producto->imagen->url,
                                                                )
                                                                : null;
                                                        @endphp

                                                        <div class="w-20 lg:w-24 rounded-lg block">
                                                            @if ($imagen)
                                                                <img src="{{ $imagen }}"
                                                                    alt="{{ $carshoopitem->producto->slug }}"
                                                                    class="block w-full h-auto max-h-20 object-scale-down overflow-hidden rounded-lg">
                                                            @else
                                                                <x-icon-image-unknown
                                                                    class="w-full h-full max-h-16 text-colorsubtitleform {{ $opacidad }}" />
                                                            @endif
                                                            <p class="text-[9px] !leading-none text-center">
                                                                {{ $carshoopitem->producto->name }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex items-end sm:items-center sm:w-60 sm:flex-shrink-0 ">
                                            <span
                                                class="text-left p-2 text-xs sm:text-end font-semibold whitespace-nowrap">
                                                x{{ decimalOrInteger($item->cantidad) }}
                                                {{ $item->producto->unit->name }}
                                            </span>

                                            <span
                                                class="p-2 font-semibold text-lg flex-1 text-end text-colorlabel whitespace-nowrap">
                                                {{ $order->moneda->simbolo }}
                                                {{ number_format($item->total, 2, '.', ', ') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="lg:col-span-2 w-full pb-5">
            <div class="w-full space-y-3 p-3 rounded-lg border border-borderminicard">
                <h3 class="text-xl font-semibold text-colorsubtitleform">
                    Tracking</h3>

                <ol class="relative ms-3 border-s border-borderminicard">
                    @foreach ($order->trackings as $item)
                        <li class="mb-5 sm:mb-10 ms-6 text-colorlabel">
                            <span
                                class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-next-500 ring-8 ring-body">
                                @if ($item->trackingstate->isFinalizado())
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                        class="h-4 w-4 text-white" fill="none" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M21 7V12M3 7C3 10.0645 3 16.7742 3 17.1613C3 18.5438 4.94564 19.3657 8.83693 21.0095C10.4002 21.6698 11.1818 22 12 22L12 11.3548" />
                                        <path d="M15 19C15 19 15.875 19 16.75 21C16.75 21 19.5294 16 22 15" />
                                        <path
                                            d="M8.32592 9.69138L5.40472 8.27785C3.80157 7.5021 3 7.11423 3 6.5C3 5.88577 3.80157 5.4979 5.40472 4.72215L8.32592 3.30862C10.1288 2.43621 11.0303 2 12 2C12.9697 2 13.8712 2.4362 15.6741 3.30862L18.5953 4.72215C20.1984 5.4979 21 5.88577 21 6.5C21 7.11423 20.1984 7.5021 18.5953 8.27785L15.6741 9.69138C13.8712 10.5638 12.9697 11 12 11C11.0303 11 10.1288 10.5638 8.32592 9.69138Z" />
                                        <path d="M6 12L8 13" />
                                        <path d="M17 4L7 9" />
                                    </svg>
                                @else
                                    <svg class="h-4 w-4 text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5" />
                                    </svg>
                                @endif
                            </span>
                            <h4 class="mb-0.5 font-semibold text-[10px] sm:text-sm text-primary">
                                {{ formatDate($item->date, 'DD MMM Y, hh:mm A') }}</h4>
                            <p class="text-[10px] sm:text-xs text-colorsubtitleform">
                                {{ $item->trackingstate->name }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>

    @if ($order->comprobante)
        <div class="contenedor pb-5">
            <div class="w-full max-w-sm border border-borderminicard rounded-xl p-3 mt-5">
                <h3 class="text-xl font-semibold text-colorsubtitleform">
                    Comprobante Electrónico</h3>

                <div class="w-full text-colorlabel">
                    @if ($order->comprobante->trashed())
                        <h1 class="font-semibold text-sm leading-4 text-colortitleform">
                            <span class="text-3xl">{{ $order->comprobante->seriecompleta }}</span>
                            {{ $order->comprobante->seriecomprobante->typecomprobante->name }}
                        </h1>
                        <x-span-text :text="'ELIMINADO ' . formatDate($order->comprobante->deleted_at, 'DD MMMM YYYY')" type="red" class="!leading-none" />
                    @else
                        <h1 class="font-semibold text-sm leading-4 text-colortitleform">
                            <span class="text-3xl">{{ $order->comprobante->seriecompleta }}</span>
                            {{ $order->comprobante->seriecomprobante->typecomprobante->name }}
                        </h1>
                    @endif

                    <h1 class="font-medium text-xs leading-4">
                        {{ $order->comprobante->client->name }} -
                        {{ $order->comprobante->client->document }}
                        @if (!empty($order->comprobante->direccion))
                            <p>DIRECCIÓN : {{ $order->comprobante->direccion }}</p>
                        @endif
                    </h1>

                    <h1 class="font-medium text-xs">
                        {{ formatDate($order->comprobante->date) }}
                    </h1>

                    <h1 class="font-semibold text-sm leading-none text-end text-colortitleform">
                        {{ $order->moneda->simbolo }}
                        <span class="text-3xl">{{ number_format($order->comprobante->total, 2, '.', ', ') }}</span>
                    </h1>

                    <div class="w-full flex justify-end items-end gap-2">
                        <a target="_blank"
                            href="{{ route('facturacion.download.pdf', ['comprobante' => $order->comprobante->uuid, 'format' => 'ticket']) }}"
                            class="inline-block p-1.5 bg-neutral-800 text-white rounded-lg transition-colors duration-150">
                            <svg class="w-4 h-4 block scale-110" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 370 370" stroke-width="2" stroke="currentColor">
                                <path
                                    d="M302.901,100.986H67.1c-22.301,0-40.38,18.08-40.38,40.376v95.291c0,22.301,18.079,40.376,40.38,40.376h31.094v65.519   c0,15.138,12.319,27.451,27.455,27.451H244.35c15.138,0,27.457-12.313,27.457-27.451V277.03h31.094   c22.301,0,40.379-18.076,40.379-40.376v-95.291C343.28,119.066,325.202,100.986,302.901,100.986z M247.578,342.549   c0,1.778-1.448,3.225-3.228,3.225H125.649c-1.778,0-3.226-1.447-3.226-3.225V213.952h125.154V342.549z M279.253,170.526   c-9.07,0-16.419-7.351-16.419-16.42c0-9.068,7.349-16.419,16.419-16.419c9.068,0,16.42,7.351,16.42,16.419   C295.673,163.175,288.322,170.526,279.253,170.526z" />
                                <path
                                    d="M271.807,27.452C271.807,12.314,259.488,0,244.352,0H125.651c-15.138,0-27.457,12.314-27.457,27.452v61.423h173.613V27.452   z" />
                                <path
                                    d="M150.276,246.344h69.449c3.901,0,7.064-3.163,7.064-7.065c0-3.903-3.162-7.066-7.064-7.066h-69.449   c-3.901,0-7.063,3.164-7.063,7.066C143.212,243.181,146.374,246.344,150.276,246.344z" />
                                <path
                                    d="M150.276,274.202h69.449c3.901,0,7.064-3.161,7.064-7.064c0-3.902-3.162-7.065-7.064-7.065h-69.449   c-3.901,0-7.063,3.163-7.063,7.065C143.212,271.041,146.374,274.202,150.276,274.202z" />
                            </svg>
                        </a>
                        <a target="_blank"
                            href="{{ route('facturacion.download.pdf', ['comprobante' => $order->comprobante->uuid, 'format' => 'a4']) }}"
                            class="inline-block p-1.5 bg-red-800 text-white rounded-lg transition-colors duration-150">
                            <svg class="w-4 h-4 block scale-110 " xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path
                                    d="M7 18V15.5M7 15.5V14C7 13.5286 7 13.2929 7.15377 13.1464C7.30754 13 7.55503 13 8.05 13H8.75C9.47487 13 10.0625 13.5596 10.0625 14.25C10.0625 14.9404 9.47487 15.5 8.75 15.5H7ZM21 13H19.6875C18.8625 13 18.4501 13 18.1938 13.2441C17.9375 13.4882 17.9375 13.881 17.9375 14.6667V15.5M17.9375 18V15.5M17.9375 15.5H20.125M15.75 15.5C15.75 16.8807 14.5747 18 13.125 18C12.7979 18 12.6343 18 12.5125 17.933C12.2208 17.7726 12.25 17.448 12.25 17.1667V13.8333C12.25 13.552 12.2208 13.2274 12.5125 13.067C12.6343 13 12.7979 13 13.125 13C14.5747 13 15.75 14.1193 15.75 15.5Z" />
                                <path
                                    d="M15 22H10.7273C7.46607 22 5.83546 22 4.70307 21.2022C4.37862 20.9736 4.09058 20.7025 3.8477 20.3971C3 19.3313 3 17.7966 3 14.7273V12.1818C3 9.21865 3 7.73706 3.46894 6.55375C4.22281 4.65142 5.81714 3.15088 7.83836 2.44135C9.09563 2 10.6698 2 13.8182 2C15.6173 2 16.5168 2 17.2352 2.2522C18.3902 2.65765 19.3012 3.5151 19.732 4.60214C20 5.27832 20 6.12494 20 7.81818V10" />
                                <path
                                    d="M3 12C3 10.1591 4.49238 8.66667 6.33333 8.66667C6.99912 8.66667 7.78404 8.78333 8.43137 8.60988C9.00652 8.45576 9.45576 8.00652 9.60988 7.43136C9.78333 6.78404 9.66667 5.99912 9.66667 5.33333C9.66667 3.49238 11.1591 2 13 2" />
                            </svg>
                        </a>
                        @if ($order->comprobante->isSendSunat())
                            <a target="_blank"
                                href="{{ route('facturacion.download.xml', ['comprobante' => $order->comprobante->uuid, 'type' => 'xml']) }}"
                                class="inline-block p-1.5 bg-next-600 text-white rounded-lg transition-colors duration-150">
                                <svg class="w-4 h-4 block scale-110" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none">
                                    <path
                                        d="M7 13L8.64706 15.5M8.64706 15.5L10.2941 18M8.64706 15.5L10.2941 13M8.64706 15.5L7 18M21 18H20.1765C19.4 18 19.0118 18 18.7706 17.7559C18.5294 17.5118 18.5294 17.119 18.5294 16.3333V13M12.3529 17.9999L12.6946 13.8346C12.7236 13.4813 12.7381 13.3046 12.845 13.2716C12.9518 13.2386 13.0613 13.3771 13.2801 13.6539L14.1529 14.7579C14.2716 14.9081 14.331 14.9831 14.4102 14.9831C14.4893 14.9831 14.5487 14.9081 14.6674 14.7579L15.5407 13.6533C15.7594 13.3767 15.8687 13.2384 15.9755 13.2713C16.0824 13.3042 16.097 13.4807 16.1262 13.8338L16.4706 17.9999" />
                                    <path
                                        d="M15 22H10.7273C7.46607 22 5.83546 22 4.70307 21.2022C4.37862 20.9736 4.09058 20.7025 3.8477 20.3971C3 19.3313 3 17.7966 3 14.7273V12.1818C3 9.21865 3 7.73706 3.46894 6.55375C4.22281 4.65142 5.81714 3.15088 7.83836 2.44135C9.09563 2 10.6698 2 13.8182 2C15.6173 2 16.5168 2 17.2352 2.2522C18.3902 2.65765 19.3012 3.5151 19.732 4.60214C20 5.27832 20 6.12494 20 7.81818V10" />
                                    <path
                                        d="M3 12C3 10.1591 4.49238 8.66667 6.33333 8.66667C6.99912 8.66667 7.78404 8.78333 8.43137 8.60988C9.00652 8.45576 9.45576 8.00652 9.60988 7.43136C9.78333 6.78404 9.66667 5.99912 9.66667 5.33333C9.66667 3.49238 11.1591 2 13 2" />
                                </svg>
                            </a>
                            <a target="_blank"
                                href="{{ route('facturacion.download.xml', ['comprobante' => $order->comprobante->uuid, 'type' => 'cdr']) }}"
                                class="inline-block p-1.5 bg-fondospancardproduct text-textspancardproduct rounded-lg transition-colors duration-150">
                                <svg class="w-4 h-4 block scale-110" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none">
                                    <path
                                        d="M12.5 2H12.7727C16.0339 2 17.6645 2 18.7969 2.79784C19.1214 3.02643 19.4094 3.29752 19.6523 3.60289C20.5 4.66867 20.5 6.20336 20.5 9.27273V11.8182C20.5 14.7814 20.5 16.2629 20.0311 17.4462C19.2772 19.3486 17.6829 20.8491 15.6616 21.5586C14.4044 22 12.8302 22 9.68182 22C7.88275 22 6.98322 22 6.26478 21.7478C5.10979 21.3424 4.19875 20.4849 3.76796 19.3979C3.5 18.7217 3.5 17.8751 3.5 16.1818V12" />
                                    <path
                                        d="M20.5 12C20.5 13.8409 19.0076 15.3333 17.1667 15.3333C16.5009 15.3333 15.716 15.2167 15.0686 15.3901C14.4935 15.5442 14.0442 15.9935 13.8901 16.5686C13.7167 17.216 13.8333 18.0009 13.8333 18.6667C13.8333 20.5076 12.3409 22 10.5 22" />
                                    <path
                                        d="M4.5 7.5C4.99153 8.0057 6.29977 10 7 10M9.5 7.5C9.00847 8.0057 7.70023 10 7 10M7 10L7 2" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
