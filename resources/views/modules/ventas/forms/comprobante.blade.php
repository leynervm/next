<div class="w-full flex flex-col gap-1">
    @if (count($monedas) > 1)
        <div class="w-full">
            <x-label value="Moneda :" />
            <div id="parentmnd" class="relative" x-init="selectMoneda">
                <x-select class="block w-full" x-ref="selectmoneda" id="mnd">
                    <x-slot name="options">
                        @foreach ($monedas as $item)
                            <option value="{{ $item->id }}">{{ $item->currency }}</option>
                        @endforeach
                    </x-slot>
                </x-select>
                <x-icon-select />
            </div>
            <x-jet-input-error for="moneda_id" />
        </div>
    @endif

    <div class="w-full" x-data="{ otherdocs: false }">
        <div class="w-full pb-2">
            <x-label for="otherdocs" class="font-semibold !text-[10px] cursor-pointer">
                <x-input type="checkbox" id="otherdocs" x-model="otherdocs" class="!rounded-none cursor-pointer mr-2" />
                VINCULAR DOCUMENTOS REFERENCIA
            </x-label>
        </div>

        <div x-cloack x-show="otherdocs" x-transition style="display: none;"
            class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-1">
            {{-- <div class="w-full">
            <x-label value="Vincular cotización :" />
            <div id="parentctzc" class="relative" x-init="selectCotizacion" wire:ignore>
                <x-select class="block w-full" id="ctzc" x-ref="selectcot" data-minimum-results-for-search="3">
                    <x-slot name="options">
                    </x-slot>
                </x-select>
                <x-icon-select />
            </div>
            <x-jet-input-error for="cotizacion_id" />
        </div> --}}

            {{-- BUSCAR GRE Y OBTENER SUS ITEMS --}}
            @can('admin.ventas.create.guias')
                @if ($sincronizegre)
                    <div class="w-full">
                        <x-label value="Buscar GRE :" />
                        <div class="w-full inline-flex relative">
                            <x-disabled-text :text="$searchgre" class="w-full flex-1 block" />
                            <x-button-close-modal class="btn-desvincular" wire:click="desvinculargre"
                                wire:loading.attr="disabled" />
                        </div>
                        <x-jet-input-error for="searchgre" />
                    </div>
                @else
                    <div x-show="!incluyeguia" class="w-full">
                        <x-label value="Buscar GRE :" />
                        <div class="w-full inline-flex gap-1">
                            <x-input class="block w-full flex-1" wire:model.defer="searchgre" wire:keydown.enter="getGRE"
                                minlength="0" maxlength="13" onkeydown="disabledEnter(event)" />
                            <x-button-add class="px-2" wire:click="getGRE" wire:loading.attr="disabled">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.3-4.3" />
                                </svg>
                            </x-button-add>
                        </div>
                        <x-jet-input-error for="searchgre" />
                    </div>
                @endif
            @endcan
        </div>
    </div>

    <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-1">
        <div class="w-full">
            <x-label value="DNI / RUC :" />
            @if ($client_id)
                <div class="w-full inline-flex relative">
                    <x-disabled-text :text="$document" class="w-full flex-1 block" />
                    <x-button-close-modal class="btn-desvincular" wire:click="limpiarcliente"
                        wire:loading.attr="disabled" />
                </div>
            @else
                <div class="w-full inline-flex gap-1">
                    <x-input class="block w-full flex-1 input-number-none" x-model="document"
                        wire:model.defer="document" x-on:keydown.enter.prevent="consultaCliente()" minlength="8"
                        maxlength="11" onkeypress="return validarNumero(event, 11)"
                        onpaste="return validarPasteNumero(event, 11)" />
                    <x-button-add class="px-2" x-on:click="consultaCliente()" wire:loading.attr="disabled">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                    </x-button-add>
                </div>
            @endif
            <x-jet-input-error for="document" />
        </div>
        <div class="w-full">
            <x-label value="Cliente / Razón Social :" />
            <x-input class="block w-full" x-model="name" placeholder="" />
            <x-jet-input-error for="name" />
        </div>
        <div class="w-full {{ $empresa->usarLista() ? '' : 'sm:col-span-2' }} lg:col-span-1">
            <x-label value="Dirección :" />
            <x-input class="block w-full" x-model="direccion" placeholder="" />
            <x-jet-input-error for="direccion" />
        </div>
        @if ($empresa->usarLista())
            @if ($pricetypeasigned)
                <div class="w-full xs:col-span-2 sm:col-span-1">
                    <x-label value="Lista precio asignado :" />
                    <x-disabled-text :text="$pricetypeasigned ?? 'SIN LISTA PRECIOS'" />
                </div>
            @endif
        @endif
    </div>

    <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-1 gap-1">
        <div class="w-full">
            <x-label value="Tipo comprobante :" />
            <div id="parenttpcmpbt" class="relative">
                <x-select class="block w-full" x-ref="selectcomprobante" id="tpcmpbt" data-placeholder="null">
                    {{-- <x-slot name="options">
                        @if (count($typecomprobantes) > 0)
                            @foreach ($typecomprobantes as $item)
                                <option value="{{ $item->id }}" data-code="{{ $item->typecomprobante->code }}"
                                    data-sunat="{{ $item->typecomprobante->sendsunat }}">
                                    [{{ $item->serie }}] - {{ $item->typecomprobante->descripcion }}
                                </option>
                            @endforeach
                        @endif
                    </x-slot> --}}
                </x-select>
                <x-icon-select />
            </div>
            <x-jet-input-error for="seriecomprobante_id" />
        </div>

        @if (Module::isEnabled('Facturacion'))
            <div class="w-full">
                <x-label value="Tipo pago :" />
                <div id="parenttpymt" class="relative">
                    <x-select class="block w-full" id="tpymt" x-ref="selectpayment" data-placeholder="null">
                    </x-select>
                    <x-icon-select />
                </div>
                <x-jet-input-error for="typepayment_id" />
            </div>
        @endif

        <div class="w-full" x-cloak x-show="paymentcuotas" x-transition style="display: none;">
            <x-label value="Cuotas :" />
            <div class="w-full inline-flex">
                <x-input class="block w-full" type="number" min="1" step="1" max="100"
                    wire:model.defer="countcuotas" wire:key="countcuotas"
                    onkeypress="return validarNumero(event, 3)" />
            </div>
            <x-jet-input-error for="countcuotas" />
        </div>

        <div class="w-full" x-cloak x-show="paymentcuotas" x-transition style="display: none;">
            <x-label value="Incrementar venta (%):" />
            <x-input class="block w-full input-number-none prevent" type="number" min="0" step="0.10"
                wire:model.lazy="increment" wire:key="increment" onkeypress="return validarDecimal(event, 5)" />
            <x-jet-input-error for="increment" />
        </div>

        <div class="w-full" x-cloak x-show="!paymentcuotas" style="display: none;">
            <x-label value="Modalidad pago :" />
            <div id="parenttypepay" class="relative" x-init="selectTypepay">
                <x-select class="block w-full" x-ref="typepay" id="typepay">
                    <x-slot name="options">
                        <option value="0">PAGO TOTAL</option>
                        <option value="1">PAGO PARCIAL</option>
                    </x-slot>
                </x-select>
                <x-icon-select />
            </div>
            <x-jet-input-error for="typepay" />
        </div>

        <div class="w-full" x-cloak x-show="typepay == '1'" style="display: none;" x-transition>
            <x-label value="Monto parcial pago :" />
            <x-input class="block w-full input-number-none" type="number" min="1" step="0.01"
                min="0.01" wire:model.defer="amountparcial" wire:key="amountparcial"
                onkeypress="return validarDecimal(event, 12)" @keydown.enter.prevent="savepay" placeholder="0.00" />
            <x-jet-input-error for="amountparcial" />
        </div>

        <div class="w-full">
            <x-label value="Método pago :" />
            <div id="parenttmpym" class="relative">
                <x-select class="block w-full" id="tmpym" x-ref="selectmethodpayment" data-placeholder="null">
                </x-select>
                <x-icon-select />
            </div>
            <x-jet-input-error for="methodpayment_id" />
        </div>

        <div class="w-full" x-show="istransferencia" x-cloak style="display: none;">
            <x-label value="Detalle, N° transacción, etc :" />
            <x-input class="block w-full" wire:model.defer="detallepago" />
            <x-jet-input-error for="detallepago" />
        </div>

        <div class="w-full flex justify-end items-end" x-cloak x-show="typepay == '1'" style="display: none;"
            x-transition>
            <x-button class="w-full block" @click.prevent="savepay" type="button" wire:loading.attr="disabled">
                {{ __('AGREGAR PAGO') }}</x-button>
        </div>
    </div>

    <div class="w-full grid grid-cols-1 gap-1">
        <div class="w-full">
            <x-label value="Observaciones :" />
            <x-input class="block w-full" wire:model.defer="observaciones" />
            <x-jet-input-error for="observaciones" />
        </div>
    </div>

    <script>
        function selectCotizacion() {
            this.selectCO = $(this.$refs.selectcot).select2();
            this.selectCO.val(this.cotizacion_id).trigger("change");
            this.selectCO.on("select2:select", (event) => {
                this.cotizacion_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("cotizacion_id", (value) => {
                this.selectCO.val(value).trigger("change");
            });
        }

        function selectMoneda() {
            this.selectMD = $(this.$refs.selectmoneda).select2();
            this.selectMD.val(this.moneda_id).trigger("change");
            this.selectMD.on("select2:select", (event) => {
                this.moneda_id = event.target.value;
                // @this.setMoneda(event.target.value);
                // window.dispatchEvent(new CustomEvent('setMoneda', {
                //     detail: {
                //         message: event.target.value
                //     }
                // }));
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("moneda_id", (value) => {
                this.selectMD.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectMD.select2().val(this.moneda_id).trigger('change');
            });
        }

        function selectTypepay() {
            this.selectTPay = $(this.$refs.typepay).select2();
            this.selectTPay.val(this.typepay).trigger("change");
            this.selectTPay.on("select2:select", (event) => {
                this.typepay = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("typepay", (value) => {
                this.selectTPay.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectTPay.select2().val(this.typepay).trigger('change');
            });
        }
    </script>
</div>
