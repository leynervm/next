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
            <x-simple-card class="w-full p-5">
                <h1 class="text-xl font-semibold text-colorsubtitleform  uppercase">
                    N° ORDEN : {{ $order->seriecompleta }}</h1>
                <p class="text-colorsubtitleform text-2xl font-bold">
                    <small class="text-[10px] font-medium">TOTAL : {{ $order->moneda->simbolo }}</small>
                    {{ number_format($order->total, 2, '.', ', ') }}
                </p>
            </x-simple-card>

            {{-- PAYMENT --}}
            <livewire:modules.marketplace.orders.confirmar-pago :order="$order" />


            <x-simple-card class="w-full p-5">
                <h1 class="text-xl font-semibold text-colorsubtitleform">
                    DATOS DEL USUARIO CLIENTE</h1>

                <p class="text-neutral-500 text-[10px]">
                    {{ $order->user->document }} - {{ $order->user->name }}</p>
                <p class="text-neutral-500 text-[10px]">
                    EMAIL: {{ $order->user->email }}</p>
            </x-simple-card>

            {{-- TIPO ENVIO --}}
            <div class="w-full grid grid-cols-1 xl:grid-cols-2 gap-8">
                <x-simple-card class="w-full p-5">
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
                </x-simple-card>

                {{-- CONTACTO RECEIVER --}}
                <x-simple-card class="w-full p-5">
                    <h1 class="text-lg font-semibold text-colorsubtitleform">
                        DATOS DEL CONTACTO</h1>

                    <p class="text-neutral-500 text-[10px] uppercase">
                        {{ $order->receiverinfo['document'] }} -
                        {{ $order->receiverinfo['name'] }}
                    </p>
                    <p class="text-neutral-500 text-[10px]">
                        TELÉFONO: {{ $order->receiverinfo['telefono'] }}</p>
                </x-simple-card>
            </div>

            <x-simple-card class="w-full p-5">
                <livewire:modules.marketplace.orders.show-trackings :order="$order" />
            </x-simple-card>

            <x-simple-card class="w-full flex-1">
                <livewire:modules.marketplace.orders.show-resumen-order :order="$order" />
            </x-simple-card>
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
