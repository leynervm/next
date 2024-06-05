<div>
    <div wire:loading.flex class="loading-overlay rounded fixed hidden">
        <x-loading-next />
    </div>

    <div class="flex flex-wrap gap-2 mt-3">
        <div class="w-full xs:max-w-sm">
            <x-label value="Buscar cliente / comprobante :" />
            <div class="relative flex items-center">
                <span class="absolute">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 mx-3 text-next-300">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </span>
                <x-input placeholder="Buscar N° orden, nombres, email..." class="block w-full pl-9"
                    wire:model.lazy="search">
                </x-input>
            </div>
        </div>

        <div class="w-full xs:max-w-xs">
            <x-label value="Estado pago :" />
            <div class="relative" x-data="{ pago: @entangle('pago') }" x-init="select2Pago" id="parentpago">
                <x-select class="block w-full" x-model="pago" x-ref="selectpago" id="pago" data-placeholder="null">
                    @if (count(getStatusPayWeb()) > 0)
                        <x-slot name="options">
                            @foreach (getStatusPayWeb() as $item)
                                <option value="{{ $item->value }}">{{ str_replace('_', ' ', $item->name) }}</option>
                            @endforeach
                        </x-slot>
                    @endif
                </x-select>
                <x-icon-select />
            </div>
        </div>

        <div class="w-full xs:max-w-[150px]">
            <x-label value="Fecha :" />
            <x-input type="date" wire:model.lazy="date" class="w-full" />
        </div>

        <div class="w-full xs:max-w-[150px]">
            <x-label value="Hasta :" />
            <x-input type="date" wire:model.lazy="dateto" class="w-full" />
        </div>
    </div>
    {{-- @can('admin.ventas.deletes')
        <div class="w-full mt-1">
            <x-label-check for="eliminados">
                <x-input wire:model.lazy="deletes" name="deletes" value="true" type="checkbox" id="eliminados" />
                MOSTRAR VENTAS EN LÍNEA ELIMINADAS
            </x-label-check>
        </div>
    @endcan --}}

    @if ($orders->hasPages())
        <div class="pt-3 pb-1">
            {{ $orders->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif
    @if (count($orders) > 0)
        <x-table class="w-full mt-1">
            <x-slot name="header">
                <tr>
                    <th scope="col" class="p-2 font-medium">
                        <button class="flex items-center gap-2 focus:outline-none">
                            <span>PEDIDO</span>
                            <svg class="h-3 w-3" viewBox="0 0 10 11" fill="currentColor" stroke="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z"
                                    stroke-width="0.1" />
                                <path
                                    d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z"
                                    stroke-width="0.1" />
                                <path
                                    d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z"
                                    stroke-width="0.3" />
                            </svg>
                        </button>
                    </th>
                    <th scope="col" class="p-2 font-medium">USUARIO / CLIENTE</th>
                    <th scope="col" class="p-2 font-medium">RECIBE</th>
                    <th scope="col" class="p-2 font-medium">EXONERADO</th>
                    <th scope="col" class="p-2 font-medium">GRAVADO</th>
                    <th scope="col" class="p-2 font-medium">IGV</th>
                    <th scope="col" class="p-2 font-medium">TOTAL</th>
                    <th scope="col" class="p-2 font-medium text-center">TIPO ENVÍO</th>
                    <th scope="col" class="p-2 font-medium">FORMA PAGO</th>
                    <th scope="col" class="p-2 font-medium">PAGO</th>
                    <th scope="col" class="p-2 font-medium text-center">ESTADO</th>
                </tr>
            </x-slot>

            <x-slot name="body">
                @foreach ($orders as $item)
                    <tr>
                        <td class="p-2 text-[10px] uppercase">
                            @if ($item->trashed())
                                <p class="block w-full leading-3 text-colorsubtitleform mb-1">
                                    {{ $item->seriecompleta }}
                                    <br>
                                    {{ formatDate($item->date()) }}
                                </p>
                                <x-span-text :text="'ELIMINADO ' . formatDate($item->deleted_at, 'DD MMMM YYYY')" type="red" class="leading-3 !tracking-normal" />
                            @else
                                {{-- @can('admin.ventas.edit') --}}
                                <a href="{{ route('admin.marketplace.orders.show', $item) }}"
                                    class="text-linktable hover:text-hoverlinktable whitespace-nowrap inline-block transition-colors ease-out duration-150">
                                    {{ $item->seriecompleta }}
                                    <br>
                                    {{ formatDate($item->date) }}
                                </a>
                                {{-- @endcan --}}

                                @cannot('admin.ventas.edit')
                                    <p class="text-linktable">
                                        {{ $item->seriecompleta }}
                                        <br>
                                        {{ formatDate($item->date) }}
                                    </p>
                                @endcannot
                            @endif
                        </td>
                        <td class="p-2">
                            <p class=""> {{ $item->user->name }}</p>
                            <p class="text-neutral-500">{{ $item->user->document }}</p>
                            <p class="text-neutral-500">{{ $item->user->email }}</p>
                        </td>
                        <td class="p-2 ">
                            <p class="text-neutral-500">{{ $item->receiverinfo['document'] }}</p>
                            <p class="uppercase">{{ $item->receiverinfo['name'] }}</p>
                            <p class="">TELÉFONO: {{ formatTelefono($item->receiverinfo['telefono']) }}</p>
                        </td>
                        <td class="p-2 text-center">
                            {{ $item->moneda->simbolo }}
                            {{ number_format($item->exonerado, 3, '.', ', ') }}
                        </td>
                        <td class="p-2 text-center">
                            {{ $item->moneda->simbolo }}
                            {{ number_format($item->gravado, 3, '.', ', ') }}
                        </td>
                        <td class="p-2 text-center">
                            {{ $item->moneda->simbolo }}
                            {{ number_format($item->igv, 3, '.', ', ') }}
                        </td>
                        <td class="p-2 text-center">
                            {{ $item->moneda->simbolo }}
                            {{ number_format($item->total, 3, '.', ', ') }}
                        </td>
                        <td class="p-2 text-left">
                            {{ $item->shipmenttype->name }}
                            @if ($item->shipmenttype->isEnviodomicilio())
                                <p class="text-neutral-500 text-[10px]">
                                    LUGAR : {{ $item->direccion->ubigeo->distrito }},
                                    {{ $item->direccion->ubigeo->provincia }},
                                    {{ $item->direccion->ubigeo->region }}</p>

                                <p class="text-neutral-500 text-[10px]">
                                    DIRECCIÓN: {{ $item->direccion->name }}</p>

                                <p class="text-neutral-500 text-[10px]">
                                    REFERENCIA: {{ $item->direccion->referencia }}</p>
                            @else
                                @if ($item->entrega)
                                    <p class="text-neutral-500 text-[10px]">
                                        TIENDA : {{ $item->entrega->sucursal->name }}</p>
                                    <p class="text-neutral-500 text-[10px] leading-3">
                                        DIRECCIÓN :
                                        {{ $item->entrega->sucursal->direccion }} <br>
                                        {{ $item->entrega->sucursal->ubigeo->distrito }},
                                        {{ $item->entrega->sucursal->ubigeo->provincia }},
                                        {{ $item->entrega->sucursal->ubigeo->region }}</p>
                                    <p class="text-neutral-500 text-[10px]">
                                        FECHA RECOJO : {{ formatDate($item->entrega->date, 'DD MMMM Y') }}</p>
                                @endif
                            @endif
                        </td>
                        <td class="p-2 text-center leading-3 text-[10px]">
                            <span class="block mx-auto w-4 h-4 text-neutral-500">
                                @if ($item->isDeposito())
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                        fill="none" class="w-full h-full block">
                                        <path
                                            d="M19 9H6.65856C5.65277 9 5.14987 9 5.02472 8.69134C4.89957 8.38268 5.25517 8.01942 5.96637 7.29289L8.21091 5" />
                                        <path
                                            d="M5 15H17.3414C18.3472 15 18.8501 15 18.9753 15.3087C19.1004 15.6173 18.7448 15.9806 18.0336 16.7071L15.7891 19" />
                                    </svg>
                                @endif
                            </span>

                            @if ($item->methodpay)
                                {{ str_ireplace('_', ' ', $item->methodpay->name) }}
                            @endif
                        </td>
                        <td class="p-2 text-center text-[10px]">
                            @if ($item->isPagoconfirmado())
                                <x-span-text class="!tracking-normal !p-0.5" text="PAGO CONFIRMADO" type="green" />
                            @elseif ($item->isPagado())
                                <p class="text-green-600">PAGADO</p>
                                {{-- <p class="text-orange-600 leading-3">EN ESPERA DE CONFIRMACIÓN</p> --}}
                                <x-span-text class="!tracking-normal !p-0.5" text="CONFIRMAR PAGO" type="orange" />
                            @else
                                <x-span-text class="!tracking-normal !p-0.5" text="PENDIENTE" type="red" />
                            @endif
                        </td>
                        <td class="p-2 text-center text-[10px]">
                            @if (count($item->trackings) > 0)
                                <span style="background: {{ $item->trackings->first()->trackingstate->background }}"
                                    class="p-0.5 rounded-md text-white inline-block">
                                    {{ $item->trackings->first()->trackingstate->name }}
                                </span>
                            @endif
                        </td>

                    </tr>
                @endforeach
            </x-slot>
        </x-table>
    @else
        <x-span-text text="NO EXISTEN REGISTROS DE VENTAS EN LÍNEA..." class="mt-3 bg-transparent" />
    @endif

    <script>
        function select2Pago() {
            this.selectSP = $(this.$refs.selectpago).select2();
            this.selectSP.val(this.pago).trigger("change");
            this.selectSP.on("select2:select", (event) => {
                this.pago = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("pago", (value) => {
                this.selectSP.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectSP.select2().val(this.pago).trigger('change');
            });
        }


        function select2Tipoenvio() {
            this.selectS = $(this.$refs.selects).select2();
            this.selectS.val(this.searchsucursal).trigger("change");
            this.selectS.on("select2:select", (event) => {
                    this.searchsucursal = event.target.value;
                })
                .on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
            this.$watch("searchsucursal", (value) => {
                this.selectS.val(value).trigger("change");
            });
        }

        function select2Estado() {
            this.selectS = $(this.$refs.selects).select2();
            this.selectS.val(this.searchsucursal).trigger("change");
            this.selectS.on("select2:select", (event) => {
                    this.searchsucursal = event.target.value;
                })
                .on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
            this.$watch("searchsucursal", (value) => {
                this.selectS.val(value).trigger("change");
            });
        }
    </script>
</div>
