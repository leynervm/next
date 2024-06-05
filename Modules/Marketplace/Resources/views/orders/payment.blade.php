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
        <x-link-breadcrumb text="PEDIDOS" route="orders">
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
        <x-link-breadcrumb text="RESUMEN PEDIDO" active>
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

    <div class="w-full" x-data="modal()">
        <div class="w-full flex flex-col xl:flex-row gap-8 py-10 xl:items-start">
            <div class="w-full flex-1 flex flex-col gap-8">
                <div class="w-full bg-fondominicard shadow-xl rounded-xl border border-borderminicard p-2 sm:p-5">
                    <h1 class="text-xl font-semibold text-colorsubtitleform ">
                        SEGUIMIENTO DEL PEDIDO</h1>
                    @if (count($order->trackings) > 0)
                        <div
                            class="w-full sm:overflow-x-auto pt-6 pb-16 flex flex-col sm:flex-row divide-y sm:divide-y-0">
                            @foreach ($order->trackings()->orderBy('id', 'asc')->get() as $item)
                                <div
                                    class="relative sm:min-w-[130px] w-full sm:max-w-[200px] flex flex-col sm:flex-row items-center">
                                    @if (!$loop->first)
                                        <div
                                            class="absolute h-full w-[1px] left-1.5 bottom-1/2  sm:w-full sm:-left-1/2 sm:right-1/2 sm:h-0.5 bg-next-500">
                                        </div>
                                    @endif
                                    <div class="w-full flex gap-2 justify-center items-center relative py-3 sm:p-3">
                                        <div class="flex-shrink rounded-full h-3 w-3 bg-next-500"></div>
                                        <div
                                            class="flex-1 sm:absolute w-full flex flex-col justify-center sm:items-center text-[10px] sm:top-[90%] sm:left-1/2 sm:-translate-x-1/2">
                                            <p class="leading-3 sm:text-center">{{ $item->trackingstate->name }}</p>

                                            <small
                                                class="text-neutral-500 sm:text-center sm:text-[10px]">{{ formatDate($item->date, 'DD MMM Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="w-full bg-fondominicard shadow-xl rounded-xl border border-borderminicard flex-1">
                    <h1 class="text-xl font-semibold text-colorsubtitleform p-5 uppercase">
                        N° ORDEN : {{ $order->seriecompleta }}</h1>
                </div>

                {{-- TIPO ENVIO --}}
                <div class="w-full grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <div class="w-full bg-fondominicard shadow-xl rounded-xl border border-borderminicard flex-1 p-2 sm:p-5">
                        <h1 class="text-lg font-semibold text-colorsubtitleform">
                            {{ $order->shipmenttype->name }}</h1>

                        @if ($order->shipmenttype->isEnviodomicilio())
                            <p class="text-neutral-500 text-[10px]">
                                LUGAR : {{ $order->direccion->ubigeo->distrito }},
                                {{ $order->direccion->ubigeo->provincia }},
                                {{ $order->direccion->ubigeo->region }}</p>

                            <p class="text-neutral-500 text-[10px]">
                                DIRECCIÓN: {{ $order->direccion->name }}</p>

                            <p class="text-neutral-500 text-[10px]">
                                REFERENCIA: {{ $order->direccion->referencia }}</p>
                        @else
                            <p class="text-neutral-500 text-[10px]">
                                TIENDA : {{ $order->entrega->sucursal->name }}</p>
                            <p class="text-neutral-500 text-[10px] leading-3">
                                DIRECCIÓN :
                                {{ $order->entrega->sucursal->direccion }} <br>
                                {{ $order->entrega->sucursal->ubigeo->distrito }},
                                {{ $order->entrega->sucursal->ubigeo->provincia }},
                                {{ $order->entrega->sucursal->ubigeo->region }}</p>
                            <p class="text-neutral-500 text-[10px]">
                                FECHA RECOJO : {{ formatDate($order->entrega->date, 'DD MMMM Y') }}</p>
                        @endif
                    </div>

                    {{-- CONTACTO RECEIVER --}}
                    <div class="w-full bg-fondominicard shadow-xl rounded-xl border border-borderminicard flex-1 p-2 sm:p-5">
                        <h1 class="text-lg font-semibold text-colorsubtitleform">
                            DATOS DEL CONTACTO</h1>

                        <p class="text-neutral-500 text-[10px] uppercase">
                            {{ $order->receiverinfo['document'] }} -
                            {{ $order->receiverinfo['name'] }}
                        </p>
                        <p class="text-neutral-500 text-[10px]">
                            TELÉFONO: {{ $order->receiverinfo['telefono'] }}</p>
                    </div>
                </div>

                {{-- PAYMENT --}}
                @if (!is_null($order->methodpay))
                    <div class="w-full bg-fondominicard shadow-xl rounded-xl border border-borderminicard flex-1 p-2 sm:p-5">
                        <h1 class="text-xl font-semibold text-colorsubtitleform">
                            RESUMEN PAGO</h1>

                        @if ($order->isDeposito())
                            @if ($order->image)
                                <button
                                    @click="openModal(); src = '{{ Storage::url('payments/depositos/' . $order->image->url) }}'"
                                    class="w-full h-[150px] md:max-w-[100px] border border-borderminicard rounded-md overflow-hidden">
                                    <img src="{{ Storage::url('payments/depositos/' . $order->image->url) }}"
                                        class="w-full h-full object-cover">
                                </button>

                                <p class="text-xs text-neutral-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"
                                        class="w-4 h-4 inline-block">
                                        <path
                                            d="M19 9H6.65856C5.65277 9 5.14987 9 5.02472 8.69134C4.89957 8.38268 5.25517 8.01942 5.96637 7.29289L8.21091 5" />
                                        <path
                                            d="M5 15H17.3414C18.3472 15 18.8501 15 18.9753 15.3087C19.1004 15.6173 18.7448 15.9806 18.0336 16.7071L15.7891 19" />
                                    </svg>
                                    {{ str_replace('_', ' ', $order->methodpay->name) }}
                                </p>

                                @if ($order->isPagoconfirmado())
                                    <span class="text-green-600 inline-block text-[10px]">
                                        PAGO CONFIRMADO CON ÉXITO</span>
                                @elseif ($order->isPagado())
                                    <span class="text-green-600 inline-block text-[10px]">
                                        PAGADO</span>
                                    <p class="text-[10px] text-orange-600">EN ESPERA DE CONFIRMACIÓN</p>
                                @endif

                                <p class="text-xl font-semibold text-neutral-500">
                                    <small class="text-[10px]">TOTAL {{ $order->moneda->simbolo }}</small>
                                    {{ number_format($order->total, 2, '.', ', ') }}
                                    <small class="text-[10px]">{{ $order->moneda->code }}</small>
                                </p>
                            @endif
                        @else
                            <p class="text-neutral-500 text-[10px]">
                                APROBADO Y COMPLETADO CON EXITO </p>
                            <p class="text-neutral-500 text-[10px]">
                                ID TRANSACCIÓN </p>
                            <p class="text-neutral-500 text-[10px]">
                                FECHA Y HORA PAGO: </p>
                            <p class="text-neutral-500 text-[10px]">
                                TARJETA: </p>
                            <p class="text-neutral-500 text-[10px]">
                                IMPORTE: </p>

                        @endif
                    </div>
                @endif


                <div class="w-full bg-fondominicard shadow-xl rounded-xl border border-borderminicard flex-1">
                    <h1 class="text-md font-semibold text-colorsubtitleform p-2 sm:p-5">
                        RESUMEN</h1>

                    <div class="w-full overflow-x-auto md:rounded-lg">
                        @if (count($order->tvitems) > 0)
                            <table class="w-full min-w-full text-[10px]">
                                <thead>
                                    <tr class="text-[10px] text-colorsubtitleform">
                                        <th></th>
                                        <th>PRECIO</th>
                                        <th>CANT.</th>
                                        <th>TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @foreach ($order->tvitems as $item)
                                        @php
                                            $image = $item->producto->getImageURL();
                                        @endphp

                                        <tr class="text-colorsubtitleform">
                                            <td class="flex gap-2 text-left p-2">
                                                <div class="flex-shrink w-14 h-14 rounded overflow-hidden">
                                                    @if ($image)
                                                        <img src="{{ $image }}" alt=""
                                                            class="w-full h-full object-scale-down rounded aspect-square overflow-hidden">
                                                    @else
                                                        <x-icon-file-upload
                                                            class="!w-full !h-full !m-0 text-neutral-500 !border-0"
                                                            type="unknown" />
                                                    @endif
                                                </div>
                                                <p class="w-full flex-1 leading-3 text-[10px]">
                                                    {{ $item->producto->name }}</p>
                                            </td>
                                            <td class="text-center p-2">
                                                {{ $order->moneda->simbolo }}
                                                {{ number_format($item->price, 2, '.', ', ') }}
                                                </h1>
                                            </td>
                                            <td class="text-center p-2">
                                                {{ formatDecimalOrInteger($item->cantidad) }}
                                                {{ $item->producto->unit->name }}
                                            </td>
                                            <td class="text-center p-2">
                                                {{ $order->moneda->simbolo }}
                                                {{ number_format($item->total, 2, '.', ', ') }}
                                                {{-- {{ $order->moneda->currency }} --}}
                                                </h1>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <h1 class="text-xs p-3 font-medium text-neutral-500">
                                NO EXISTEN PRODUCTOS AGREGADOS EN LA ORDEN...</h1>
                        @endif
                    </div>
                </div>
            </div>


            @if (is_null($order->methodpay))
                <div class="w-full flex flex-col gap-8 xl:max-w-md flex-shrink-0 shadow-xl rounded-xl border border-borderminicard p-2 sm:p-5">
                    <div class="w-full flex flex-wrap xs:flex-nowrap gap-2 justify-between bg-white">
                        <img class="h-8" src="https://codersfree.com/img/payments/credit-cards.png"
                            alt="">

                        <p class="text-xl text-right font-semibold text-neutral-500">
                            <small class="text-[10px]">TOTAL {{ $order->moneda->simbolo }}</small>
                            {{ number_format($order->total, 2, '.', ', ') }}
                            <small class="text-[10px]">{{ $order->moneda->code }}</small>
                        </p>
                    </div>

                    <div class="w-full" x-data="{ pago: {{ old('pago') ?? 1 }} }">
                        <ul class="text-xs flex flex-col gap-5">
                            <li class="rounded-xl border" :class="pago == 1 ? 'shadow-xl border-green-500' : ''">
                                <label for="pago1"
                                    class="p-3 font-semibold text-gray-500 flex flex-wrap xs:flex-nowrap cursor-pointer items-center ">
                                    <input class="sr-only" type="radio" x-model="pago" name="pago"
                                        id="pago1" value="1">
                                    <span class="">TARJETA DE DEBITO / CRÉDITO</span>
                                    <img class="h-6 xs:ml-auto"
                                        src="https://codersfree.com/img/payments/credit-cards.png" alt="">
                                </label>

                                <div class="p-3 bg-fondominicard rounded-b-xl flex flex-col sm:flex-row xl:flex-col gap-2" x-show="pago ==1">
                                    <button
                                        class="w-full sm:max-w-[200px] xl:max-w-full block p-3 rounded-md text-xs bg-blue-500 text-white">PAYPAL</button>
                                    <button
                                        class="w-full sm:max-w-[200px] xl:max-w-full block p-3 rounded-md text-xs bg-neutral-700 text-white">
                                        TARJETA DE CREDITO</button>
                                </div>
                            </li>
                            <li class="rounded-xl border" :class="pago == 2 ? 'shadow-xl  border-green-500' : ''">
                                <label for="pago2"
                                    class="p-3 font-semibold text-gray-500 cursor-pointer block w-full">
                                    <input class="sr-only" type="radio" x-model="pago" name="pago"
                                        id="pago2" value="2">
                                    <span class="">DEPÓSITO BANCARIO O YAPE</span>
                                </label>
                                <div class="p-3 text-xs bg-fondominicard rounded-b-xl text-neutral-500"
                                    x-show="pago ==2" x-cloak>

                                    <p>1. PAGO POR DEPÓSITO O TRANSFERENCIA BANCARIA</p>
                                    <p>-BCP SOLES: XXX-XXXXXXXX-XX</p>
                                    <p>-CCI: XXX-XXX-XXXXXXX</p>
                                    <p>-RAZON SOCIAL: NEXT TECHNOLOGIES EIRL</p>
                                    <p>-RUC: 205389XX054</p>
                                    <p>2. PAGO POR YAPE</p>
                                    <p>-YAPE AL NUMERO +51 XXXXXXXX (NEXT TECHNOLOGIES EIRL)</p>
                                    <p>-ENVIAR COMPROBANTE DE PAGO AL NUMERO +51 XXXXXXXXX</p>

                                    <form class="relative w-full text-center" x-data="{ boucher: null }"
                                        action="{{ route('orders.pay.deposito', $order->id) }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('post')
                                        <template x-if="boucher !=null">
                                            <x-simple-card
                                                class="w-full h-40 md:max-w-md mx-auto mb-1 border border-borderminicard rounded-xl">
                                                <img x-bind:src="URL.createObjectURL(boucher)"
                                                    class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster">
                                            </x-simple-card>
                                        </template>

                                        <input type="hidden" name="pago" value="2">
                                        {{-- <span x-text="boucher"></span> --}}

                                        <label for="boucher"
                                            class="w-full block p-3 mt-3 rounded-md text-xs bg-next-500 focus:ring-2 focus:ring-next-300 text-white cursor-pointer hover:bg-next-700 focus:bg-next-700 transition ease-in-out duration-150">
                                            ADJUNTAR COMPROBANTE PAGO
                                            <input type="file" class="hidden" id="boucher" accept="image/*"
                                                @change="boucher = $event.target.files[0]" name="file" />
                                        </label>
                                        <x-jet-input-error for="file" class="text-center" />

                                        <template x-if="boucher !=null">
                                            <button type="submit"
                                                class="w-full block p-3 mt-3 rounded-md text-xs bg-blue-500 text-white focus:ring-2 focus:ring-blue-300 hover:bg-blue-700 focus:bg-blue-700 transition ease-in-out duration-150">
                                                ENVIAR</button>
                                        </template>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>


        <div x-show="modelOpen" class="fixed inset-0 z-[1000] overflow-hidden" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div class="w-full h-full relative">

                <div x-cloak @click="closeModal()" x-show="modelOpen"
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200 transform"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-neutral-900 bg-opacity-80" aria-hidden="true">
                </div>

                <div x-cloak x-show="modelOpen" x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200 transform"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="w-full max-w-full h-full p-2 lg:p-5 relative overflow-y-auto transition-all flex justify-center items-center">

                    <button @click="closeModal()"
                        class="absolute top-2 right-2 text-red-500 focus:outline-none hover:text-red-700 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path d="M19.0005 4.99988L5.00045 18.9999M5.00045 4.99988L19.0005 18.9999" />
                        </svg>
                    </button>

                    {{-- <div class="p-5 w-full h-[calc(100vh-2.50rem)] rounded-xl overflow-hidden"> --}}
                    <img x-bind:src="src" alt=""
                        class="block h-full w-auto max-w-xl object-scale-down rounded-xl">
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>

    <script>
        function modal() {
            return {
                modelOpen: false,
                src: null,
                openModal() {
                    this.modelOpen = true;
                    document.body.style.overflow = 'hidden';
                },
                closeModal() {
                    this.modelOpen = false;
                    document.body.style.overflow = 'auto';
                }
            }
        }
    </script>
</x-app-layout>
