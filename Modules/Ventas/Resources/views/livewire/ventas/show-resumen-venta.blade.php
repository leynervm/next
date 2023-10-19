<div class="soft-scrollbar h-full xl:overflow-y-auto">
    <div class="w-full flex flex-col gap-8" x-data="{ searchingclient: false }">
        <x-form-card titulo="GENERAR NUEVA VENTA" widthBefore="before:w-36"
            subtitulo="Complete todos los campos para registrar una nueva venta.">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2 bg-body p-3 rounded-md"
                id="form_create_venta">
                <div class="w-full flex flex-col gap-1">

                    <div class="w-full flex flex-wrap lg:flex-nowrap xl:flex-wrap gap-1">
                        <div class="w-full">
                            <x-label value="Vincular cotización :" />
                            <div id="parentventacotizacion_id">
                                <x-select class="block w-full" id="ventacotizacion_id" wire:model.defer="cotizacion_id"
                                    data-minimum-results-for-search="3">
                                    <x-slot name="options">
                                        {{-- @if (count($categories))
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif --}}
                                    </x-slot>
                                </x-select>
                            </div>
                            <x-jet-input-error for="cotizacion_id" />
                        </div>
                        <div class="w-full">
                            <x-label value="Moneda :" />
                            <div id="parentventamoneda_id">
                                <x-select class="block w-full" id="ventamoneda_id" wire:model.defer="moneda_id">
                                    <x-slot name="options">
                                        @if (count($monedas))
                                            @foreach ($monedas as $item)
                                                <option value="{{ $item->id }}">{{ $item->currency }}</option>
                                            @endforeach
                                        @endif
                                    </x-slot>
                                </x-select>
                            </div>
                            <x-jet-input-error for="moneda_id" />
                        </div>
                    </div>

                    <div class="w-full flex flex-wrap lg:flex-nowrap xl:flex-wrap gap-1">
                        <div class="w-full lg:w-1/3 xl:w-full">
                            <x-label value="DNI / RUC :" />
                            <div class="w-full inline-flex">
                                <x-input class="block w-full prevent" wire:model.defer="document"
                                    wire:keydown.enter="getClient" minlength="0" maxlength="11" />
                                <x-button-add class="px-2" wire:click="getClient" wire:target="getClient"
                                    wire:loading.attr="disable">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8" />
                                        <path d="m21 21-4.3-4.3" />
                                    </svg>
                                </x-button-add>
                            </div>
                            <x-jet-input-error for="document" />
                        </div>
                        <div class="w-full lg:w-2/3 xl:w-full">
                            <x-label value="Cliente / Razón Social :" />
                            <x-input class="block w-full" wire:model.defer="name"
                                placeholder="Nombres / razón social del cliente" />
                            <x-jet-input-error for="name" />
                        </div>
                    </div>

                    <div class="w-full flex flex-wrap lg:flex-nowrap xl:flex-wrap gap-1">
                        <div class="w-full lg:w-2/3 xl:w-full">
                            <x-label value="Dirección :" />
                            <x-input class="block w-full" wire:model.defer="direccion"
                                placeholder="Dirección del cliente" />
                            <x-jet-input-error for="direccion" />
                        </div>

                        @if (isset($empresa->id))
                            @if ($empresa->uselistprice)
                                @if ($pricetypeasigned)
                                    <div class="w-full lg:w-1/3 xl:w-full">
                                        <x-label value="Lista precio asignado :" />
                                        <x-disabled-text :text="$pricetypeasigned ?? ' - '" />
                                    </div>
                                @endif
                            @endif
                        @endif
                    </div>

                    @if ($mensaje)
                        {{-- <div class="w-full mt-2">
                            <x-label value="Mensaje :" />
                            <x-input class="block w-full" wire:model.defer="mensaje" disabled readonly />
                            <x-jet-input-error for="mensaje" />
                        </div> --}}
                    @endif

                    @if (count($typecomprobantes))
                        <div class="w-full flex flex-wrap lg:flex-nowrap xl:flex-wrap gap-1">
                            <div class="w-full">
                                <x-label value="Tipo comprobante :" />
                                <div id="parentventatypecomprobante_id">
                                    <x-select class="block w-full" id="ventatypecomprobante_id"
                                        wire:model="typecomprobante_id">
                                        <x-slot name="options">
                                            @if (count($typecomprobantes))
                                                @foreach ($typecomprobantes as $item)
                                                    <option value="{{ $item->id }}">{{ $item->descripcion }} -
                                                        {{ $item->serie }}</option>
                                                @endforeach
                                            @endif
                                        </x-slot>
                                    </x-select>
                                </div>
                                <x-jet-input-error for="typecomprobante_id" />
                            </div>

                            <div class="w-full">
                                <x-label value="Tipo pago :" />
                                <div id="parentventatypepayment_id">
                                    <x-select class="block w-full" id="ventatypepayment_id" wire:model="typepayment_id">
                                        <x-slot name="options">
                                            @if (count($typepayments))
                                                @foreach ($typepayments as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            @endif
                                        </x-slot>
                                    </x-select>
                                </div>
                                <x-jet-input-error for="typepayment_id" />
                            </div>
                        </div>

                        <div class="w-full grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-1 gap-1">

                            @if ($typepayment)
                                @if ($typepayment->paycuotas)
                                    <div class="w-full">
                                        <div class="w-full lg:w-full">
                                            <x-label value="Incrementar venta (%):" />
                                            <x-input class="block w-full prevent" type="number" min="0"
                                                step="0.10" wire:model.lazy="increment" />
                                            <x-jet-input-error for="increment" />
                                        </div>
                                        <div class="w-full lg:w-full">
                                            <x-label value="Cuotas :" />
                                            <div class="w-full inline-flex">
                                                <x-input class="block w-full" type="number" min="1"
                                                    step="1" max="12" wire:model.defer="countcuotas" />
                                            </div>
                                            <x-jet-input-error for="countcuotas" />
                                        </div>
                                    </div>
                                @else
                                    <div class="w-full">
                                        <x-label value="Método pago :" />
                                        <div id="parentventamethodpayment_id">
                                            <x-select class="block w-full" id="ventamethodpayment_id"
                                                wire:model.defer="methodpayment_id">
                                                <x-slot name="options">
                                                    @if (count($methodpayments))
                                                        @foreach ($methodpayments as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </x-slot>
                                            </x-select>
                                        </div>
                                        <x-jet-input-error for="methodpayment_id" />
                                    </div>

                                    @if (count($accounts))
                                        <div class="w-full">
                                            <x-label value="Cuenta pago :" />
                                            <div id="parentventacuenta_id">
                                                <x-select class="block w-full" id="ventacuenta_id"
                                                    wire:model.defer="cuenta_id">
                                                    <x-slot name="options">
                                                        @if (count($accounts))
                                                            @foreach ($accounts as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->account }}
                                                                    ({{ $item->descripcion }})
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </x-slot>
                                                </x-select>
                                            </div>
                                            <x-jet-input-error for="cuenta_id" />
                                        </div>
                                    @endif

                                    <div class="w-full">
                                        <x-label value="Detalle pago :" />
                                        <x-input class="block w-full" wire:model.defer="detallepago" />
                                        <x-jet-input-error for="detallepago" />
                                    </div>

                                @endif
                            @else
                                <p class="text-colorerror text-xs font-semibold bg-red-100 p-0.5 rounded">
                                    Seleccione tipo de pago.</p>
                            @endif

                        </div>

                        <div class="block">
                            <x-label-check for="incluyeigv">
                                <x-input wire:model.lazy="incluyeigv" name="incluyeigv" value="1"
                                    type="checkbox" id="incluyeigv" />
                                INCLUIR IGV
                            </x-label-check>
                            <x-jet-input-error for="incluyeigv" />
                        </div>
                    @else
                        <p class="text-colorerror text-xs font-semibold bg-red-100 p-0.5 rounded">
                            No existen tipos de comprobantes a generar.</p>
                    @endif

                    @if ($errors->any())
                        <div class="w-full flex flex-col gap-1">
                            @foreach ($errors->keys() as $key)
                                <x-jet-input-error :for="$key" />
                            @endforeach
                        </div>
                    @endif

                    <div class="w-full flex mt-2 justify-end">
                        <x-button type="submit" wire:loading.attr="disabled" wire:target="save">
                            {{ __('REGISTRAR') }}
                        </x-button>
                    </div>
                </div>
            </form>

            <div x-show="searchingclient" wire:loading wire:loading.flex wire:target="save"
                class="loading-overlay rounded">
                <x-loading-next />
            </div>
        </x-form-card>

        <x-form-card titulo="RESUMEN DE VENTA" widthBefore="before:w-28"
            subtitulo="Resumen de los costos generales de la venta.">

            <div class="w-full text-colortitleform bg-body p-3 rounded-md">
                <p class="text-[10px]">
                    TOTAL EXONERADO : {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($exonerado, 3, '.', ', ') }}</span>
                </p>

                <p class="text-[10px]">TOTAL GRAVADO : {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($gravado, 3, '.', ', ') }}</span>
                </p>

                <p class="text-[10px]">
                    TOTAL IGV : {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($igv, 3, '.', ', ') }}</span>
                </p>

                <p class="text-[10px]">TOTAL DESCUENTOS : {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($descuentos, 3, '.', ', ') }}</span>
                </p>

                <p class="text-[10px]">
                    @if ($increment)
                        IMPORTE TOTAL :
                    @else
                        TOTAL PAGAR :
                    @endif
                    {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($total, 3, '.', ', ') }}</span>
                </p>

                @if ($increment)
                    <p class="text-[10px]">TOTAL PAGAR
                        (+ {{ \App\Helpers\FormatoPersonalizado::getValue($increment) }}% INCREMENTO) :
                        {{ $moneda->simbolo }}
                        <span class="font-bold text-xs">{{ number_format($totalincrement, 3, '.', ', ') }}</span>
                    </p>
                @endif
            </div>

            <div x-show="searchingclient" wire:loading wire:loading.flex
                wire:target="save, setTotal, totalincrement, increment, incluyeigv" class="loading-overlay rounded">
                <x-loading-next />
            </div>
        </x-form-card>

        @if (count($carrito))
            <x-form-card titulo="CARRITO DE COMPRAS" widthBefore="before:w-32"
                subtitulo="Resumen de productos agregados al carrito para la venta.">

                <span
                    class="text-white font-semibold absolute -top-2 left-0 flex items-center justify-center w-5 h-5 p-0.5 leading-3 bg-next-500 ring-1 ring-white rounded-full text-[10px] animate-bounce">
                    {{ count($carrito) }}</span>

                <div class="flex gap-2 flex-wrap justify-start">
                    @foreach ($carrito as $item)
                        <div
                            class="w-full bg-body border border-borderminicard flex flex-col justify-between lg:w-60 xl:w-full group rounded shadow shadow-shadowminicard p-1 text-xs relative hover:shadow-md hover:shadow-shadowminicard cursor-pointer">
                            <div class="w-full">

                                @if ($item->status == 0)
                                    <span
                                        class="absolute bottom-1 left-0 text-[9px] leading-3 p-0.5 pr-1 rounded-r-lg bg-orange-500 text-white transition-all ease-in-out duration-150">
                                        Pendiente Pago</span>
                                @endif

                                <div class="w-full inline-flex gap-2 text-colorminicard">
                                    <h1 class="w-full text-[10px] leading-3 text-left mt-1">
                                        {{ $item->producto->name }}</h1>
                                    <h1 class="whitespace-nowrap text-right text-[10px] text-xs leading-3">
                                        {{ $item->moneda->simbolo }}
                                        {{ number_format($item->total, 2) }}
                                        <small class="text-[7px]">{{ $item->moneda->currency }}</small>
                                    </h1>
                                </div>

                                <div class="w-full flex flex-wrap gap-1 mt-2 text-[10px]">
                                    <span
                                        class="inline-flex leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                        P. UNIT:
                                        {{ $item->moneda->simbolo }}
                                        {{ number_format($item->price, 2, '.', ', ') }}
                                    </span>
                                    <span
                                        class="inline-flex leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                        {{ \App\Helpers\FormatoPersonalizado::getValue($item->cantidad) }}
                                        {{ $item->producto->unit->name }}
                                    </span>
                                    <span
                                        class="inline-flex leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                        {{ $item->almacen->name }}
                                    </span>
                                    @if (count($item->carshoopseries) == 1)
                                        <span
                                            class="inline-flex leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase whitespace-nowrap">
                                            SERIE: {{ $item->carshoopseries()->first()->serie->serie }}
                                        </span>
                                    @endif
                                </div>

                                @if (count($item->carshoopseries) > 1)
                                    <div x-data="{ showForm: false }" class="mt-1">
                                        <x-button @click="showForm = !showForm" class="whitespace-nowrap">
                                            {{ __('VER SERIES') }}
                                        </x-button>
                                        <div x-show="showForm"
                                            x-transition:enter="transition ease-out duration-300 transform"
                                            x-transition:enter-start="opacity-0 translate-y-[-10%]"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-300 transform"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 translate-y-[-10%]"
                                            class="block w-full rounded mt-1">
                                            <div class="w-full flex flex-wrap gap-1">
                                                @foreach ($item->carshoopseries as $itemserie)
                                                    <span
                                                        class="inline-flex items-center gap-1 text-[10px] bg-fondospancardproduct text-textspancardproduct p-1 rounded-lg">
                                                        {{ $itemserie->serie->serie }}
                                                        <x-button-delete
                                                            wire:click="$emit('ventas.confirmDeleteSerie',{{ $itemserie }})"
                                                            wire:loading.attr="disabled" />
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="w-full flex items-end gap-1 justify-end mt-2">
                                <x-button-delete
                                    wire:click="$emit('ventas.confirmDeleteItemCart', {{ $item->id }})"
                                    wire:loading.attr="disabled" wire:target="deleteitem" />
                            </div>
                        </div>
                    @endforeach
                </div>

                <div x-show="searchingclient" wire:loading wire:loading.flex
                    wire:target="save, setTotal, deleteitem, deleteserie" class="loading-overlay rounded">
                    <x-loading-next />
                </div>
            </x-form-card>
        @endif
    </div>

    @section('scripts')
        <script>
            // document.addEventListener("DOMContentLoaded", () => {

            renderselect2();

            $("#ventamoneda_id").on("change", (e) => {
                deshabilitarSelects();
                @this.moneda_id = e.target.value;
            });
            $("#ventatypepayment_id").on("change", (e) => {
                deshabilitarSelects();
                @this.typepayment_id = e.target.value;
            });

            $("#ventamethodpayment_id").on("change", (e) => {
                deshabilitarSelects();
                @this.methodpayment_id = e.target.value;
            });

            $("#ventacuenta_id").on("change", (e) => {
                deshabilitarSelects();
                @this.cuenta_id = e.target.value;
            });

            $("#ventatypecomprobante_id").on("change", (e) => {
                deshabilitarSelects();
                @this.typecomprobante_id = e.target.value;
            });

            $("#ventacotizacion_id").on("change", (e) => {
                deshabilitarSelects();
                @this.cotizacion_id = e.target.value;
            });

            // $("#ventamoneda_id").on("change", (e) => {
            //     deshabilitarSelects();
            //     @this.setMoneda(e.target.value);
            //     @this.moneda_id = e.target.value;
            // });


            document.addEventListener('render-show-resumen-venta', () => {
                renderselect2();
                $('#ventamethodpayment_id').on("change", (e) => {
                    deshabilitarSelects();
                    @this.methodpayment_id = e.target.value;
                });
            });

            document.addEventListener('update-carrito', () => {
                @this.setTotal();
            });

            Livewire.on('ventas.confirmDeleteItemCart', data => {
                swal.fire({
                    title: 'Desea eliminar el producto del carrito de compras ?',
                    text: "Se eliminará un registro de pago de la base de datos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.deleteitem(data);
                        // Livewire.emitTo('ventas::ventas.show-resumen-venta', 'deleteitem', data);
                    }
                })
            });

            Livewire.on('ventas.confirmDeleteSerie', data => {
                swal.fire({
                    title: 'Desea quitar la serie ' + data.serie.serie + ' del carrito de compras ?',
                    text: "Se eliminará un registro de pago de la base de datos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.deleteserie(data.id);
                        // Livewire.emitTo('ventas::ventas.show-resumen-venta', 'deleteserie', data.id);
                    }
                })
            });

            function renderselect2() {
                $('#ventamoneda_id, #ventatypepayment_id, #ventacotizacion_id, #ventamethodpayment_id, #ventacuenta_id, #ventatypecomprobante_id')
                    .select2().on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });
            }

            function deshabilitarSelects() {
                $('#ventamoneda_id, #ventatypepayment_id, #ventacotizacion_id, #ventamethodpayment_id, #ventacuenta_id, #ventatypecomprobante_id')
                    .attr('disabled', true);
            }
            // })
        </script>
    @endsection
</div>
