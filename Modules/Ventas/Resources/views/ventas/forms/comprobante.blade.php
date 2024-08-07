<div class="w-full flex flex-col gap-1">
    <div class="w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-1">
        {{-- <div class="w-full">
            <x-label value="Vincular cotización :" />
            <div id="parentctzc" class="relative" x-init="selectCotizacion" wire:ignore>
                <x-select class="block w-full" id="ctzc" x-ref="selectcot"
                    data-minimum-results-for-search="3">
                    <x-slot name="options">
                    </x-slot>
                </x-select>
                <x-icon-select />
            </div>
            <x-jet-input-error for="cotizacion_id" />
        </div> --}}
        @if (count($monedas) > 1)
            <div class="w-full">
                <x-label value="Moneda :" />
                <div id="parentmnd" class="relative" x-init="selectMoneda">
                    <x-select class="block w-full" x-ref="selectmoneda" id="mnd">
                        <x-slot name="options">
                            @if (count($monedas))
                                @foreach ($monedas as $item)
                                    <option value="{{ $item->id }}">{{ $item->currency }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
                <x-jet-input-error for="moneda_id" />
            </div>
        @endif
    </div>

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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                    </x-button-add>
                </div>
                <x-jet-input-error for="searchgre" />
            </div>
        @endif
    @endcan

    <div class="w-full flex flex-wrap lg:flex-nowrap xl:flex-wrap gap-1">
        <div class="w-full lg:w-1/3 xl:w-full">
            <x-label value="DNI / RUC :" />
            @if ($client_id)
                <div class="w-full inline-flex relative">
                    <x-disabled-text :text="$document" class="w-full flex-1 block" />
                    <x-button-close-modal class="btn-desvincular" wire:click="limpiarcliente"
                        wire:loading.attr="disabled" />
                </div>
            @else
                <div class="w-full inline-flex gap-1">
                    <x-input class="block w-full flex-1 numeric prevent" wire:model.defer="document"
                        wire:keydown.enter="getClient" minlength="8" maxlength="11"
                        onkeypress="return validarNumero(event, 11)" onkeydown="disabledEnter(event)" />
                    <x-button-add class="px-2" wire:click="getClient" wire:loading.attr="disabled">
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
        <div class="w-full lg:w-2/3 xl:w-full">
            <x-label value="Cliente / Razón Social :" />
            <x-input class="block w-full" wire:model.defer="name" placeholder="Nombres / razón social del cliente" />
            <x-jet-input-error for="name" />
        </div>
    </div>

    <div class="w-full flex flex-wrap lg:flex-nowrap xl:flex-wrap gap-1">
        <div class="w-full lg:w-full xl:w-full">
            <x-label value="Dirección :" />
            <x-input class="block w-full" wire:model.defer="direccion" placeholder="Dirección del cliente" />
            <x-jet-input-error for="direccion" />
        </div>

        @if (mi_empresa()->uselistprice)
            @if ($pricetypeasigned)
                <div class="w-full lg:w-1/3 xl:w-full">
                    <x-label value="Lista precio asignado :" />
                    <x-disabled-text :text="$pricetypeasigned ?? ' - '" />
                </div>
            @endif
        @endif
    </div>

    <div class="w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-1">
        <div class="w-full">
            <x-label value="Tipo comprobante :" />
            <div id="parenttpcmpbt" class="relative" x-init="selectComprobante">
                <x-select class="block w-full" x-ref="selectcomprobante" id="tpcmpbt"
                    @change="getCodeSend($event.target)" data-placeholder="null">
                    <x-slot name="options">
                        @if (count($typecomprobantes))
                            @foreach ($typecomprobantes as $item)
                                <option value="{{ $item->id }}" data-code="{{ $item->typecomprobante->code }}"
                                    data-sunat="{{ $item->typecomprobante->sendsunat }}">
                                    [{{ $item->serie }}] - {{ $item->typecomprobante->descripcion }}
                                </option>
                            @endforeach
                        @endif
                    </x-slot>
                </x-select>
                <x-icon-select />
            </div>
            <x-jet-input-error for="seriecomprobante_id" />
        </div>

        @if (Module::isEnabled('Facturacion'))
            <div class="w-full">
                <x-label value="Tipo pago :" />
                <div id="parenttpymt" class="relative" x-init="selectPayment">
                    <x-select class="block w-full" id="tpymt" x-ref="selectpayment" data-placeholder="null">
                        <x-slot name="options">
                            @if (count($typepayments))
                                @foreach ($typepayments as $item)
                                    <option value="{{ $item->id }}" data-paycredito="{{ $item->isCredito() }}">
                                        {{ $item->name }} </option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
                <x-jet-input-error for="typepayment_id" />
            </div>
        @endif
    </div>

    <div class="w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-1">
        <div style="display: none;" x-show="!paymentcuotas">
            <x-label value="Seleccionar pago :" />
            <div class="w-full grid grid-cols-1 xs:grid-cols-2 gap-2">
                <x-input-radio class="py-2" for="paytotal" text="PAGO TOTAL">
                    <input x-model="typepay" class="sr-only peer peer-disabled:opacity-25" type="radio"
                        id="paytotal" name="payment" value="0" />
                </x-input-radio>
                <x-input-radio class="py-2" for="payparcial" text="PAGO PARCIAL">
                    <input x-model="typepay" class="sr-only peer peer-disabled:opacity-25" type="radio"
                        id="payparcial" name="payment" value="1" />
                </x-input-radio>
            </div>
            <x-jet-input-error for="typepay" />
        </div>

        <div class="w-full" style="display: none;" x-show="typepay == '1'" x-transition>
            <x-label value="Monto parcial pago :" />
            <x-input class="block w-full" type="number" min="1" step="0.01" min="0.01"
                wire:model.defer="amountparcial" wire:key="amountparcial"
                onkeypress="return validarDecimal(event, 12)" @keydown.enter.prevent="savepay" placeholder="0.00" />
            <x-jet-input-error for="amountparcial" />
        </div>

        <div class="w-full">
            <x-label value="Método pago :" />
            <div id="parenttmpym" class="relative" x-init="selectMethodpayment">
                <x-select class="block w-full" id="tmpym" x-ref="selectmethodpayment" data-placeholder="null">
                    <x-slot name="options">
                        @if (count($methodpayments) > 0)
                            @foreach ($methodpayments as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        @endif
                    </x-slot>
                </x-select>
                <x-icon-select />
            </div>
            <x-jet-input-error for="methodpayment_id" />
        </div>

        <div class="w-full flex mt-2 justify-end items-end" style="display: none;" x-show="typepay == '1'"
            x-transition>
            <x-button @click.prevent="savepay" type="button" wire:loading.attr="disabled">
                {{ __('AGREGAR PAGO') }}</x-button>
        </div>

        {{-- <div class="w-full lg:w-full animate__animated animate__fadeInDown" x-show="paymentcuotas">
            <x-label value="Pago actual:" />
            <x-input class="block w-full prevent" type="number" min="0" step="0.100"
                wire:model.lazy="paymentactual" wire:key="paymentactual"
                wire:keydown.enter="setpaymentactual($event.target.value)"
                onkeypress="return validarDecimal(event, 12)" />
            <x-jet-input-error for="paymentactual" />
        </div> --}}

        <div class="w-full lg:w-full animate__animated animate__fadeInDown" x-show="paymentcuotas"
            style="display: none;">
            <x-label value="Incrementar venta (%):" />
            <x-input class="block w-full prevent" type="number" min="0" step="0.10"
                wire:model.lazy="increment" wire:key="increment" onkeypress="return validarDecimal(event, 5)" />
            <x-jet-input-error for="increment" />
        </div>

        <div class="w-full lg:w-full animate__animated animate__fadeInDown" x-show="paymentcuotas"
            style="display: none;">
            <x-label value="Cuotas :" />
            <div class="w-full inline-flex">
                <x-input class="block w-full" type="number" min="1" step="1" max="100"
                    wire:model.defer="countcuotas" wire:key="countcuotas"
                    onkeypress="return validarNumero(event, 3)" />
            </div>
            <x-jet-input-error for="countcuotas" />
        </div>

        {{-- <div class="w-full" x-show="!paymentcuotas">
            <x-label value="Detalle pago :" />
            <x-input class="block w-full" wire:model.defer="detallepago" />
            <x-jet-input-error for="detallepago" />
        </div> --}}
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

        function selectComprobante() {
            this.selectTC = $(this.$refs.selectcomprobante).select2();
            this.selectTC.val(this.seriecomprobante_id).trigger("change");
            this.selectTC.on("select2:select", (event) => {
                this.seriecomprobante_id = event.target.value;
                this.getCodeSend(event.target);
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("seriecomprobante_id", (value) => {
                this.selectTC.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectTC.select2().val(this.seriecomprobante_id).trigger('change');
            });
        }

        function selectPayment() {
            this.selectTP = $(this.$refs.selectpayment).select2();
            this.selectTP.val(this.typepayment_id).trigger("change");
            this.selectTP.on("select2:select", (event) => {
                this.typepayment_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("typepayment_id", (value) => {
                this.selectTP.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectTP.select2().val(this.typepayment_id).trigger('change');
                let target = this.$refs.selectpayment;
                let selectedOption = target.options[target.selectedIndex];
                let paycredito = Boolean(selectedOption.getAttribute('data-paycredito'));

                switch (paycredito) {
                    case true:
                        this.paymentcuotas = true;
                        this.typepay = '1';
                        break;
                    case false:
                        this.paymentcuotas = false;
                        break;
                    default:
                        this.paymentcuotas = false;
                        this.formapago = '';
                }
            });
        }

        function selectMethodpayment() {
            this.selectMP = $(this.$refs.selectmethodpayment).select2();
            this.selectMP.val(this.methodpayment_id).trigger("change");
            this.selectMP.on("select2:select", (event) => {
                this.methodpayment_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("methodpayment_id", (value) => {
                this.selectMP.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectMP.select2().val(this.methodpayment_id).trigger('change');
            });
        }
    </script>
</div>
