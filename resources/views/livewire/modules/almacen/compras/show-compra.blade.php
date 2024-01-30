<div class="flex flex-col gap-8" x-data="data">
    <x-form-card titulo="DATOS COMPRA">
        <form wire:submit.prevent="update" class="w-full flex flex-col gap-2 bg-body p-3 rounded">
            <div class="w-full flex flex-col xs:grid xs:grid-cols-2 xl:grid-cols-3 gap-2">
                <div class="w-full xs:col-span-2">
                    <x-label value="Proveedor :" />
                    <x-disabled-text :text="$compra->proveedor->name" />
                    <x-jet-input-error for="compra.proveedor_id" />
                </div>

                <div class="w-full">
                    <x-label value="Moneda :" />
                    <x-disabled-text :text="$compra->moneda->currency" />
                </div>

                <div class="w-full xs:col-span-2">
                    <x-label value="Sucursal :" />
                    <x-disabled-text :text="$compra->sucursal->name" />
                    <x-jet-input-error for="compra.sucursal_id" />
                </div>

                <div class="w-full">
                    <x-label value="Fecha compra :" />
                    <x-input type="date" class="block w-full" wire:model.defer="compra.date" />
                    <x-jet-input-error for="compra.date" />
                </div>

                <div class="w-full">
                    <x-label value="Boleta/ Factura compra :" />
                    <x-input class="block w-full" wire:model.defer="compra.referencia"
                        placeholder="Boleta o factura de compra..." />
                    <x-jet-input-error for="compra.referencia" />
                </div>

                <div class="w-full">
                    <x-label value="Guía de compra :" />
                    <x-input class="block w-full" wire:model.defer="compra.guia" placeholder="Guia de compra..." />
                    <x-jet-input-error for="compra.guia" />
                </div>

                @if ($compra->moneda->code == 'USD')
                    <div class="w-full">
                        <x-label value="Tipo Cambio :" />
                        <x-input class="block w-full numeric" wire:model.defer="compra.tipocambio" placeholder="0.00"
                            type="number" min="0" step="0.0001" />
                        <x-jet-input-error for="compra.tipocambio" />
                    </div>
                @endif

                <div class="w-full">
                    <x-label value="Total exonerado :" />
                    <x-input class="block w-full numeric" wire:model.defer="compra.exonerado" x-model="exonerado"
                        @change="sumar" type="number" min="0" step="0.0001" />
                    <x-jet-input-error for="compra.exonerado" />
                </div>

                <div class="w-full">
                    <x-label value="Total gravado :" />
                    <x-input class="block w-full numeric" wire:model.defer="compra.gravado" x-model="gravado"
                        @change="sumar" type="number" min="0" step="0.0001" />
                    <x-jet-input-error for="compra.gravado" />
                </div>

                <div class="w-full">
                    <x-label value="Total IGV :" />
                    <x-input class="block w-full numeric" wire:model.defer="compra.igv" x-model="igv" @change="sumar"
                        type="number" min="0" step="0.0001" />
                    <x-jet-input-error for="compra.igv" />
                </div>

                <div class="w-full">
                    <x-label value="Total descuento :" />
                    <x-input class="block w-full numeric" wire:model.defer="compra.descuento" x-model="descuento"
                        @change="sumar" placeholder="0.00" type="number" min="0" step="0.0001" />
                    <x-jet-input-error for="compra.descuento" />
                </div>

                <div class="w-full">
                    <x-label value="Total otros :" />
                    <x-input class="block w-full numeric" wire:model.defer="compra.otros" x-model="otros"
                        placeholder="0.00" type="number" min="0" step="0.0001" />
                    <x-jet-input-error for="compra.otros" />
                </div>

                <div class="w-full">
                    <x-label value="Total compra :" />
                    <x-disabled-text :text="number_format($compra->total, 4, '.', ', ')" x-text="total" />
                    <x-jet-input-error for="compra.total" />
                </div>

                <div class="w-full">
                    <x-label value="Tipo pago :" />
                    <x-disabled-text :text="$compra->typepayment->name" />
                </div>

                {{-- @if ($compra->typepayment->paycuotas == 0)
                    <div class="w-full">
                        <x-label value="Forma pago :" />
                        <x-disabled-text :text="$compra->cajamovimiento->methodpayment->name" />
                    </div>
                @endif --}}

                {{-- @if (count($cuentas) > 1)
                    <div class="w-full">
                        <x-label value="Cuenta pago :" />
                        <div id="parentcuentacompra_id">
                            <x-select class="block w-full" wire:model.defer="cuenta_id" id="cuentacompra_id">
                                <x-slot name="options">
                                    @foreach ($cuentas as $item)
                                        <option value="{{ $item->id }}">{{ $item->account }} -
                                            {{ $item->descripcion }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <x-jet-input-error for="cuenta_id" />
                    </div>
                @endif --}}

                <div class="w-full">
                    <x-label value="Descripción compra, detalle :" />
                    <x-input class="block w-full" wire:model.defer="compra.detalle"
                        placeholder="Descripción de compra..." />
                    <x-jet-input-error for="compra.detalle" />
                </div>

                {{-- <div class="w-full">
                    <x-label value="Conteo productos :" />
                    <x-disabled-text :text="formatDecimalOrInteger($compra->counter)" />
                    <x-jet-input-error for="compra.counter" />
                </div> --}}
            </div>

            <div class="w-full flex gap-2 pt-4 justify-end">
                <x-button-secondary wire:click="$emit('compra.comfirmDelete')" wire:loading.attr="disabled"
                    wire:target="delete">
                    {{ __('ELIMINAR') }}
                </x-button-secondary>

                <x-button type="submit" wire:loading.attr="disabled" wire:target="update">
                    {{ __('ACTUALIZAR') }}
                </x-button>
            </div>
        </form>
        <div wire:loading.flex class="loading-overlay rounded hidden">
            <x-loading-next />
        </div>
    </x-form-card>

    @if ($compra->typepayment->paycuotas == 0)
        <x-form-card titulo="PAGOS" subtitulo="Control de pagos de su compra.">

            @if (count($compra->cajamovimientos) > 0)
                <div class="w-full flex flex-wrap gap-2">
                    @foreach ($compra->cajamovimientos as $item)
                        <x-card-cuota class="w-full xs:w-48" :titulo="null" :detallepago="$item">
                            <p class="text-colorminicard text-xl font-semibold text-center">
                                <small class="text-[10px] font-medium">{{ $compra->moneda->simbolo }}</small>
                                {{ number_format($item->amount, 2, '.', ', ') }}
                                <small class="text-[10px] font-medium">{{ $compra->moneda->currency }}</small>
                            </p>

                            <x-slot name="footer">
                                <div class="w-full flex gap-2 items-end justify-between">
                                    <x-mini-button>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M17.571 18H20.4a.6.6 0 00.6-.6V11a4 4 0 00-4-4H7a4 4 0 00-4 4v6.4a.6.6 0 00.6.6h2.829M8 7V3.6a.6.6 0 01.6-.6h6.8a.6.6 0 01.6.6V7" />
                                            <path
                                                d="M6.098 20.315L6.428 18l.498-3.485A.6.6 0 017.52 14h8.96a.6.6 0 01.594.515L17.57 18l.331 2.315a.6.6 0 01-.594.685H6.692a.6.6 0 01-.594-.685z" />
                                            <path d="M17 10.01l.01-.011" />
                                        </svg>
                                    </x-mini-button>

                                    <x-button-delete wire:click="$emit('compra.confirmDeletepay', {{ $item }})"
                                        wire:loading.attr="disabled" />
                                </div>
                            </x-slot>
                        </x-card-cuota>
                    @endforeach
                </div>
            @else
                <x-span-text text="NO EXISTEN REGISTROS DE PAGOS..." class="mt-3 bg-transparent" />
            @endif

            @if ($compra->cajamovimientos()->sum('amount') < $compra->total)
                <div class="w-full flex gap-2 pt-4 justify-end">
                    <x-button type="button" wire:loading.attr="disabled" wire:click="openmodal">
                        {{ __('REALIZAR PAGO') }}
                    </x-button>
                </div>
            @endif


            <div wire:loading.flex class="loading-overlay rounded hidden">
                <x-loading-next />
            </div>
        </x-form-card>
    @endif


    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Realizar pago compra') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savepayment" class="w-full flex flex-col gap-1">
                <div class="w-full">
                    <p class="text-colorminicard text-xl font-semibold">
                        <small class="text-[10px] font-medium">{{ $compra->moneda->simbolo }}</small>
                        {{ number_format($compra->total, 2, '.', ', ') }}
                        <small class="text-[10px] font-medium">{{ $compra->moneda->currency }}</small>
                    </p>
                </div>

                <div class="w-full">
                    <x-label value="Monto pendiente pagar :" />
                    <x-disabled-text :text="$compra->moneda->simbolo .
                        ' ' .
                        number_format($pendiente, 2, '.', ', ') .
                        ' ' .
                        $compra->moneda->currency" />
                </div>

                <div class="w-full">
                    <x-label value="Monto pagar :" />
                    <x-input class="block w-full numeric" wire:model.defer="paymentactual" placeholder="0.00"
                        type="number" min="0" step="0.0001" />
                    <x-jet-input-error for="paymentactual" />
                </div>

                <div class="w-full">
                    <x-label value="Método pago :" />
                    <div class="relative" x-data="{ methodpayment_id: @entangle('methodpayment_id').defer }" x-init="select2Methodpayment" id="parentqwerty"
                        wire:ignore>
                        <x-select class="block w-full" x-ref="selectmp" id="qwerty" data-dropdown-parent="null">
                            <x-slot name="options">
                                @if (count($methodpayments))
                                    @foreach ($methodpayments as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="methodpayment_id" />
                </div>

                <div class="w-full">
                    <x-label value="Otros (N° operación , Banco, etc) :" />
                    <x-input class="block w-full" wire:model.defer="detalle" />
                    <x-jet-input-error for="detalle" />
                    <x-jet-input-error for="concept_id" />
                </div>

                @if ($errors->any())
                    <div class="mt-2">
                        @foreach ($errors->keys() as $key)
                            <x-jet-input-error :for="$key" />
                        @endforeach
                    </div>
                @endif

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        function select2Methodpayment() {
            this.selectF = $(this.$refs.selectmp).select2();
            this.selectF.val(this.methodpayment_id).trigger("change");
            this.selectF.on("select2:select", (event) => {
                this.methodpayment_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("methodpayment_id", (value) => {
                this.selectF.val(value).trigger("change");
            });
        }

        function select2Cuentas() {
            this.selectC = $(this.$refs.select).select2();
            this.selectC.val(this.cuenta_id).trigger("change");
            this.selectC.on("select2:select", (event) => {
                this.cuenta_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
        }

        document.addEventListener("livewire:load", () => {
            Livewire.on('compra.comfirmDelete', () => {
                swal.fire({
                    title: 'Desea eliminar el registro de la compra ?',
                    text: "Se eliminará un registro de pago de la base de datos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.delete();
                    }
                })
            });


            Livewire.on('compra.confirmDeletepay', data => {
                swal.fire({
                    title: 'Eliminar pago de ' + toDecimal(data.amount, 2) + ' de la compra ?',
                    text: "Se eliminará un registro de pago de la base de datos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.deletepay(data.id);
                    }
                })
            });
        });


        function toDecimal(valor, decimals = 4) {
            let numero = parseFloat(valor);

            if (isNaN(numero)) {
                return 0;
            } else {
                return parseFloat(numero).toFixed(decimals);
            }
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('data', () => ({
                exonerado: @entangle('compra.exonerado').defer,
                gravado: @entangle('compra.gravado').defer,
                igv: @entangle('compra.igv').defer,
                descuento: @entangle('compra.descuento').defer,
                otros: @entangle('compra.otros').defer,
                total: @entangle('compra.total').defer,
                init() {
                    // console.log(@this.get('compra'));
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
                    this.total = toDecimal(total - parseFloat(this.descuento));
                }
            }))
        })
    </script>
</div>
