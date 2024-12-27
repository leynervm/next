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
                    ! Gracias por su compra !</h1>
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
                                <td class="text-colorsubtitleform">Método de Pago</td>
                                <td class="font-medium text-colorlabel text-end">

                                    @if ($order->transaccion->brand == 'visa')
                                        <svg class="w-10 h-6 block ml-auto">
                                            <use href="#visa" />
                                        </svg>
                                    @elseif ($order->transaccion->brand == 'mastercard')
                                        <svg class="w-10 h-6 block ml-auto">
                                            <use href="#mastercard" />
                                        </svg>
                                    @elseif ($order->transaccion->brand == 'paypal')
                                        <svg class="w-10 h-6 block ml-auto">
                                            <use href="#paypal" />
                                        </svg>
                                    @elseif ($order->transaccion->brand == 'unionpay')
                                        <svg class="w-10 h-6 block ml-auto">
                                            <use href="#unionpay" />
                                        </svg>
                                    @elseif ($order->transaccion->brand == 'dinersclub')
                                        <svg class="w-10 h-6 block ml-auto">
                                            <use href="#dinersclub" />
                                        </svg>
                                    @elseif ($order->transaccion->brand == 'amex')
                                        <svg class="w-10 h-6 block ml-auto">
                                            <use href="#amex" />
                                        </svg>
                                    @else
                                        <svg class="w-10 h-6 block ml-auto">
                                            <use href="#default" />
                                        </svg>
                                    @endif

                                    {{ $order->transaccion->brand }}
                                    <br>
                                    {{ $order->transaccion->card }}
                                </td>
                            </tr>
                            <tr>
                                <td class="w-full text-end align-middle" colspan="2">
                                    <div class="w-full flex items-center justify-end">
                                        <x-span-text :text="$order->transaccion->action_description" class="text-xs" type="green" />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-colorsubtitleform">ID Transacción</td>
                                <td class="font-medium text-colorlabel text-end">
                                    {{ $order->transaccion->transaction_id }}</td>
                            </tr>
                            <tr>
                                <td class="text-colorsubtitleform">Correo</td>
                                <td class="font-medium text-colorlabel text-end">
                                    {{ $order->transaccion->user->email }}</td>
                            </tr>
                            <tr>
                                <td class="text-colorsubtitleform">Nombres</td>
                                <td class="font-medium text-colorlabel text-end">
                                    {{ $order->transaccion->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-colorsubtitleform">Monto</td>
                                <td class="font-semibold text-colorsubtitleform text-end text-xl">
                                    {{ number_format($order->transaccion->amount, 2, '.', ', ') }} </td>
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
                            $image = $item->producto->getImageURL();
                        @endphp

                        <tr class="text-colorlabel">
                            <td class="text-left py-2 align-middle">
                                <div class="flex items-center gap-2">
                                    <div class="flex-shrink-0 w-16 h-16 xl:w-24 xl:h-24 rounded overflow-hidden">
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
                                            <p class="w-full">{{ $item->producto->name }}</p>
                                            @if (!empty($item->promocion_id))
                                                <span
                                                    class="p-1 font-semibold inline-block ring-1 rounded-lg text-[10px] ring-green-600 text-end text-green-600 whitespace-nowrap">
                                                    PROMOCIÓN</span>
                                            @endif
                                        </div>

                                        <div class="flex items-end sm:items-center sm:w-60 sm:flex-shrink-0 ">
                                            <span
                                                class="text-left p-2 text-xs sm:text-end font-semibold whitespace-nowrap">
                                                x{{ decimalOrInteger($item->cantidad) }}
                                                {{ $item->producto->unit->name }}
                                            </span>

                                            @if ($item->isGratuito())
                                                <div class="flex-1 flex justify-end items-center">
                                                    <span
                                                        class="p-2 font-semibold inline-block ring-1 rounded-lg text-xs ring-green-600 text-end text-green-600 whitespace-nowrap">
                                                        GRATUITO</span>
                                                </div>
                                            @else
                                                <span
                                                    class="p-2 font-semibold text-lg flex-1 text-end text-colorlabel whitespace-nowrap">
                                                    {{ $order->moneda->simbolo }}
                                                    {{ number_format($item->total, 2, '.', ', ') }}
                                                </span>
                                            @endif
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
            <div class="w-full space-y-6 {{-- p-3 rounded-lg border border-borderminicard --}}">
                <h3 class="text-xl font-semibold text-colorsubtitleform">Tracking</h3>

                <ol class="relative ms-3 border-s border-borderminicard">
                    @foreach ($order->trackings as $item)
                        <li class="mb-10 ms-6 text-colorlabel">
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
                            <h4 class="mb-0.5 font-semibold text-sm text-primary">
                                {{ formatDate($item->date, 'DD MMM Y, hh:mm A') }}</h4>
                            <p class="text-xs text-colorsubtitleform">{{ $item->trackingstate->name }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>

    @include('partials.icons-cards')
</x-app-layout>
