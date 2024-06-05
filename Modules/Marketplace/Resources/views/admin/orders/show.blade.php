<x-admin-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="VENTAS EN LÍNEA" route="admin.marketplace">
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
        <div class="w-full flex flex-col gap-8">
            <div class="w-full bg-fondominicard shadow-xl rounded-xl border border-borderminicard">
                <h1 class="text-xl font-semibold text-colorsubtitleform p-5 uppercase">
                    N° ORDEN : {{ $order->seriecompleta }}</h1>
            </div>

            <div class="w-full bg-fondominicard shadow-xl rounded-xl border border-borderminicard p-5">
                <h1 class="text-xl font-semibold text-colorsubtitleform">
                    DATOS DEL USUARIO CLIENTE</h1>

                <p class="text-neutral-500 text-[10px]">
                    {{ $order->user->document }} - {{ $order->user->name }}</p>
                <p class="text-neutral-500 text-[10px]">
                    EMAIL: {{ $order->user->email }}</p>
            </div>

            {{-- TIPO ENVIO --}}
            <div class="w-full grid grid-cols-1 xl:grid-cols-2 gap-8">
                <div class="w-full bg-fondominicard shadow-xl rounded-xl border border-borderminicard p-5">
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
                <div class="w-full bg-fondominicard shadow-xl rounded-xl border border-borderminicard p-5">
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
            <div class="w-full bg-fondominicard shadow-xl rounded-xl border border-borderminicard flex-1 p-5">
                <h1 class="text-xl font-semibold text-colorsubtitleform">
                    RESUMEN PAGO</h1>
                @if (is_null($order->methodpay))
                    <p class="text-colorerror text-[10px]">NO SE ENCONTRARON DATOS DEL PAGO</p>
                @else
                    @if ($order->isDeposito())
                        @if ($order->image)
                            <button
                                @click="openModal(); src = '{{ Storage::url('payments/depositos/' . $order->image->url) }}'"
                                class="w-full h-[150px] md:max-w-[100px] border border-borderminicard rounded-md overflow-hidden">
                                <img src="{{ Storage::url('payments/depositos/' . $order->image->url) }}"
                                    class="w-full h-full object-cover">
                            </button>
                        @endif

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

                        {{-- <div class="w-full"> --}}
                        <livewire:modules.marketplace.orders.confirmar-pago :order="$order" />
                        {{-- </div> --}}

                        <p class="text-xl font-semibold text-neutral-500">
                            <small class="text-[10px]">TOTAL {{ $order->moneda->simbolo }}</small>
                            {{ number_format($order->total, 2, '.', ', ') }}
                            <small class="text-[10px]">{{ $order->moneda->code }}</small>
                        </p>
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
                @endif
            </div>

            <div class="w-full bg-fondominicard shadow-xl rounded-xl border border-borderminicard p-5">
                <livewire:modules.marketplace.orders.show-trackings :order="$order" />
            </div>

            <div class="w-full bg-fondominicard shadow-xl rounded-xl border border-borderminicard flex-1">
                <livewire:modules.marketplace.orders.show-resumen-order :order="$order" />
            </div>
        </div>

        <div x-show="modelOpen" class="fixed inset-0 z-40 overflow-hidden" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="w-full h-full relative">

                <div x-cloak @click="closeModal()" x-show="modelOpen"
                    x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200 transform"
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
    {{-- @can('admin.ventas') --}}
    {{-- @endcan --}}

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

        document.addEventListener('livewire:load', function() {
            window.addEventListener('reload', () => {
                location.reload();
            });
        });
    </script>
</x-admin-layout>
