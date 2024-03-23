<div x-data="data">
    <x-form-card titulo="REGISTRAR COMPRA" subtitulo="Complete todos los campos para registrar una nueva compra.">
        <form wire:submit.prevent="save" class="w-full flex flex-col gap-2 bg-body p-3 rounded">

            <div class="w-full flex flex-col xs:grid xs:grid-cols-2 xl:grid-cols-3 gap-2">
                <div class="w-full xs:col-span-2">
                    <x-label value="Proveedor :" />
                    <div id="parentproveedorcompra_id" class="relative" x-init="selec2Proveedor">
                        <x-select class="block w-full" x-ref="selectproveedor" id="proveedorcompra_id"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @if (count($proveedores))
                                    @foreach ($proveedores as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->document }} - {{ $item->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="proveedor_id" />
                </div>

                <div class="w-full">
                    <x-label value="Moneda :" />
                    <div id="parentmonedacompra_id" class="relative" x-init="selec2Moneda" wire:ignore>
                        <x-select class="block w-full" x-ref="selectmoneda" id="monedacompra_id">
                            <x-slot name="options">
                                @if (count($monedas))
                                    @foreach ($monedas as $item)
                                        <option value="{{ $item->id }}" data-code="{{ $item->code }}"
                                            data-simbolo="{{ $item->simbolo }}">
                                            {{ $item->currency }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="moneda_id" />
                </div>

                <div class="w-full xs:col-span-2">
                    <x-label value="Sucursal :" />
                    <div id="parentsucursalcompra_id" class="relative" x-init="selec2Sucursal" wire:ignore>
                        <x-select class="block w-full" x-ref="selectsucursal" id="sucursalcompra_id"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @if (count($sucursals))
                                    @foreach ($sucursals as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="sucursal_id" />
                </div>

                <div class="w-full">
                    <x-label value="Fecha compra :" />
                    <x-input type="date" class="block w-full" wire:model.defer="date" />
                    <x-jet-input-error for="date" />
                </div>

                <div class="w-full">
                    <x-label value="Boleta/ Factura compra :" />
                    <x-input class="block w-full" wire:model.defer="referencia"
                        placeholder="Boleta o factura de compra..." />
                    <x-jet-input-error for="referencia" />
                </div>

                <div class="w-full">
                    <x-label value="Guía de compra :" />
                    <x-input class="block w-full" wire:model.defer="guia" placeholder="Guia de compra..." />
                    <x-jet-input-error for="guia" />
                </div>

                <div class="w-full animate__animated animate__fadeInDown" x-show="open">
                    <x-label value="Tipo Cambio :" />
                    <x-input class="block w-full numeric" wire:model.defer="tipocambio" placeholder="0.00"
                        type="number" min="0" step="0.0001" onkeypress="return validarDecimal(event, 11)" />
                    <x-jet-input-error for="tipocambio" />
                </div>

                <div class="w-full">
                    <x-label value="Total exonerado :" />
                    <x-input class="block w-full" wire:model.defer="exonerado" x-model="exonerado" @change="sumar"
                        placeholder="0.00" type="number" min="0" step="0.0001"
                        onkeypress="return validarDecimal(event, 11)" />
                    <x-jet-input-error for="exonerado" />
                </div>

                <div class="w-full">
                    <x-label value="Total gravado :" />
                    <x-input class="block w-full numeric" wire:model.defer="gravado" x-model="gravado" @change="sumar"
                        placeholder="0.00" type="number" min="0" step="0.0001"
                        onkeypress="return validarDecimal(event, 11)" />
                    <x-jet-input-error for="gravado" />
                </div>

                <div class="w-full">
                    <x-label value="Total IGV :" />
                    <x-input class="block w-full numeric" wire:model.defer="igv" x-model="igv" @change="sumar"
                        placeholder="0.00" type="number" min="0" step="0.0001"
                        onkeypress="return validarDecimal(event, 11)" />
                    <x-jet-input-error for="igv" />
                </div>

                <div class="w-full">
                    <x-label value="Total descuento :" />
                    <x-input class="block w-full numeric" wire:model.defer="descuento" x-model="descuento"
                        @change="sumar" placeholder="0.00" type="number" min="0" step="0.0001"
                        onkeypress="return validarDecimal(event, 11)" />
                    <x-jet-input-error for="descuento" />
                </div>

                <div class="w-full">
                    <x-label value="Total otros :" />
                    <x-input class="block w-full numeric" wire:model.defer="otros" x-model="otros" @change="sumar"
                        placeholder="0.00" type="number" min="0" step="0.0001"
                        onkeypress="return validarDecimal(event, 11)" />
                    <x-jet-input-error for="otros" />
                </div>

                <div class="w-full">
                    <x-label value="Tipo pago :" />
                    <div id="parenttypepaymentcompra_id" class="relative" x-init="select2Typepayment" wire:ignore>
                        <x-select class="block w-full" x-ref="select" id="typepaymentcompra_id">
                            <x-slot name="options">
                                @if (count($typepayments))
                                    @foreach ($typepayments as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="typepayment_id" />
                </div>

                <div class="w-full">
                    <x-label value="Descripción compra, detalle :" />
                    <x-input class="block w-full" wire:model.defer="detalle"
                        placeholder="Descripción de compra..." />
                    <x-jet-input-error for="detalle" />
                </div>
            </div>

            <x-jet-input-error for="total" />

            <div class="w-full">
                <p class="text-colorminicard text-sm font-semibold text-end !leading-4">
                    <small class="text-[10px] font-medium">SUBTOTAL : </small>
                    <span x-text="subtotal"></span>
                </p>

                <p class="text-colorminicard text-sm font-semibold text-end !leading-4">
                    <small class="text-[10px] font-medium">DSCT. : </small>
                    <span x-text="descuento"></span>
                </p>

                <p class="text-colorminicard lg:text-3xl font-semibold text-end">
                    <small class="text-[10px] font-medium" x-text="simbolo"></small>
                    <span x-text="total"></span>
                </p>
            </div>

            <div class="w-full flex pt-4 justify-end">
                <x-button type="submit" wire:loading.attr="disabled">
                    {{ __('REGISTRAR') }}
                </x-button>
            </div>
        </form>
        <div wire:loading.flex class="loading-overlay rounded hidden">
            <x-loading-next />
        </div>
    </x-form-card>

    <script>
        function toDecimal(valor, decimals = 3) {
            let numero = parseFloat(valor);

            if (isNaN(numero)) {
                return 0;
            } else {
                return parseFloat(numero).toFixed(decimals);
            }
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('data', () => ({
                open: false,
                simbolo: '',
                proveedor_id: @entangle('proveedor_id').defer,
                sucursal_id: @entangle('sucursal_id').defer,
                typepayment_id: @entangle('typepayment_id').defer,
                exonerado: @entangle('exonerado').defer,
                gravado: @entangle('gravado').defer,
                igv: @entangle('igv').defer,
                descuento: @entangle('descuento').defer,
                otros: @entangle('otros').defer,
                subtotal: @entangle('subtotal').defer,
                total: @entangle('total').defer,
                moneda_id: @entangle('moneda_id').defer,

                init() {
                    this.exonerado = toDecimal(this.exonerado);
                    this.gravado = toDecimal(this.gravado);
                    this.igv = toDecimal(this.igv);
                    this.otros = toDecimal(this.otros);
                    this.descuento = toDecimal(this.descuento);
                    this.subtotal = toDecimal(this.subtotal);
                    this.total = toDecimal(this.total);
                },
                sumar() {
                    let total = 0;
                    this.exonerado = toDecimal(this.exonerado);
                    this.gravado = toDecimal(this.gravado);
                    this.igv = toDecimal(this.igv);
                    this.otros = toDecimal(this.otros);
                    this.descuento = toDecimal(this.descuento);

                    total = parseFloat(this.exonerado) + parseFloat(this.gravado) + parseFloat(this
                        .igv) + parseFloat(this.otros);
                    this.subtotal = toDecimal(parseFloat(total) + parseFloat(this.descuento));
                    this.total = toDecimal(total);
                },
            }))
        })

        function selec2Proveedor() {
            this.selectP = $(this.$refs.selectproveedor).select2();
            this.selectP.val(this.proveedor_id).trigger("change");
            this.selectP.on("select2:select", (event) => {
                this.proveedor_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("proveedor_id", (value) => {
                this.selectP.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectP.select2().val(this.proveedor_id).trigger('change');
            });
        }

        function selec2Sucursal() {
            this.selectS = $(this.$refs.selectsucursal).select2();
            this.selectS.val(this.sucursal_id).trigger("change");
            this.selectS.on("select2:select", (event) => {
                this.sucursal_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("sucursal_id", (value) => {
                this.selectS.val(value).trigger("change");
            });
        }

        function selec2Moneda() {
            this.selectM = $(this.$refs.selectmoneda).select2();
            this.selectM.val(this.moneda_id).trigger("change");
            this.selectM.on("select2:select", (event) => {
                this.moneda_id = event.target.value;
                let datacode = event.target.options[event.target.selectedIndex].getAttribute('data-code');
                let datasimbolo = event.target.options[event.target.selectedIndex].getAttribute('data-simbolo');
                this.open = datacode == 'USD' ? true : false;
                this.simbolo = datasimbolo;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("moneda_id", (value) => {
                this.selectM.val(value).trigger("change");
            });
        }

        function select2Typepayment() {
            this.selectT = $(this.$refs.select).select2();
            this.selectT.val(this.typepayment_id).trigger("change");
            this.selectT.on("select2:select", (event) => {
                this.typepayment_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
        }
    </script>
</div>
