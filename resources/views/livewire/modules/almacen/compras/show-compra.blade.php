<div class="flex flex-col gap-8">
    <x-simple-card class="flex flex-col gap-1 rounded-md cursor-default p-3">
        <div class="w-full sm:flex sm:gap-3">
            <div class="w-full text-colortitleform">
                <h1 class="font-semibold text-sm leading-4">
                    <span class="text-3xl">{{ $compra->referencia }}</span>
                    {{ $compra->proveedor->name }}
                </h1>

                <h1 class="text-colorsubtitleform font-medium text-xs">
                    {{ $compra->sucursal->name }}
                    @if ($compra->sucursal->trashed())
                        <x-span-text text="NO DISPONIBLE" class="leading-3 !tracking-normal inline-block" />
                    @endif
                </h1>

                <h1 class="font-medium text-xs">
                    {{ formatDate($compra->date) }}
                </h1>

                <h1 class="font-medium text-xs">
                    GUÍA : {{ $compra->guia }}
                </h1>

                <h1 class="font-medium text-xs">
                    MONEDA : {{ $compra->moneda->currency }}
                    @if ($compra->moneda->code == 'USD')
                        / {{ number_format($compra->tipocambio, 3, '.', '') }}
                    @endif
                </h1>

                @if ($compra->isClose())
                    <x-span-text text="CERRADO" type="red" class="leading-3 !tracking-normal" />
                @endif
            </div>

            <div class="w-full text-colortitleform">
                <h3 class="font-semibold text-xs text-end leading-3">
                    <small class="font-medium">SUBTOTAL {{ $compra->moneda->simbolo }}</small>
                    {{ number_format($compra->exonerado + $compra->gravado + $compra->igv + $compra->otros + $compra->descuento, 3, '.', ', ') }}
                </h3>

                {{-- <h3 class="font-semibold text-xs text-end leading-3">
                    <small class="font-medium">EXONERADO </small>
                    {{ number_format($compra->exonerado, 3, '.', ', ') }}
                </h3>

                <h3 class="font-semibold text-xs text-end leading-3">
                    <small class="font-medium">GRAVADO</small>
                    {{ number_format($compra->gravado, 3, '.', ', ') }}
                </h3>

                <h3 class="font-semibold text-xs text-end leading-3">
                    <small class="font-medium">IGV</small>
                    {{ number_format($compra->igv, 3, '.', ', ') }}
                </h3>

                <h3 class="font-semibold text-xs text-end leading-3">
                    <small class="font-medium">OTROS</small>
                    {{ number_format($compra->otros, 3, '.', ', ') }}
                </h3> --}}

                @if ($compra->descuento > 0)
                    <h3 class="font-semibold text-xs text-end leading-3 text-green-500">
                        <small class="font-medium">DESCUENTOS {{ $compra->moneda->simbolo }}</small>
                        {{ number_format($compra->descuento, 3, '.', ', ') }}
                    </h3>
                @endif

                <h3 class="font-semibold text-3xl leading-normal text-end">
                    <small class="text-[10px] font-medium">{{ $compra->moneda->simbolo }}</small>
                    {{ number_format($compra->total, 3, '.', ', ') }}
                </h3>
            </div>
        </div>

        <div class="w-full flex gap-2 items-end justify-end">
            @can('admin.almacen.compras.close')
                @if ($compra->isOpen())
                    <x-button onclick="confirmCloseCompra()">CERRAR COMPRA</x-button>
                @endif
            @endcan

            @can('admin.almacen.compras.delete')
                <x-button-secondary onclick="comfirmDelete()" wire:loading.attr="disabled" wire:target="delete">
                    {{ __('ELIMINAR') }}
                </x-button-secondary>
            @endcan
        </div>
    </x-simple-card>

    @if ($compra->typepayment->isContado())
        <x-form-card titulo="PAGOS" subtitulo="Control de pagos de su compra.">

            @if (count($compra->cajamovimientos) > 0)
                <div class="w-full flex flex-wrap gap-2">
                    @foreach ($compra->cajamovimientos as $item)
                        <x-card-cuota class="w-full xs:w-48" :titulo="null" :detallepago="$item">
                            <p class="text-colorminicard text-xl font-semibold text-center">
                                <small class="text-[10px] font-medium">{{ $item->moneda->simbolo }}</small>
                                {{ number_format($item->totalamount, 2, '.', ', ') }}
                                <small class="text-[10px] font-medium">{{ $item->moneda->currency }}</small>
                            </p>

                            @if ($item->tipocambio > 0)
                                <p class="text-xs text-colorsubtitleform text-center leading-3">
                                    {{ number_format($item->amount, 2, '.', ', ') }}
                                    <small class="text-[8px]">
                                        @if ($item->moneda->code == 'USD')
                                            SOLES
                                        @else
                                            DÓLARES
                                        @endif
                                    </small>
                                </p>
                                <p class="text-xs text-colorsubtitleform text-center leading-3">
                                    <small class="text-[8px]">TIPO CAMBIO :</small>
                                    {{ $item->tipocambio }}
                                </p>
                            @endif

                            <x-slot name="footer">
                                <div class="w-full flex gap-2 items-end justify-between">
                                    <a href="{{ route('admin.payments.print', $item) }}" target="_blank"
                                        class="p-1.5 bg-neutral-900 text-white block rounded-lg transition-colors duration-150">
                                        <svg class="w-4 h-4 block scale-110" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path
                                                d="M7.35396 18C5.23084 18 4.16928 18 3.41349 17.5468C2.91953 17.2506 2.52158 16.8271 2.26475 16.3242C1.87179 15.5547 1.97742 14.5373 2.18868 12.5025C2.36503 10.8039 2.45321 9.95455 2.88684 9.33081C3.17153 8.92129 3.55659 8.58564 4.00797 8.35353C4.69548 8 5.58164 8 7.35396 8H16.646C18.4184 8 19.3045 8 19.992 8.35353C20.4434 8.58564 20.8285 8.92129 21.1132 9.33081C21.5468 9.95455 21.635 10.8039 21.8113 12.5025C22.0226 14.5373 22.1282 15.5547 21.7352 16.3242C21.4784 16.8271 21.0805 17.2506 20.5865 17.5468C19.8307 18 18.7692 18 16.646 18" />
                                            <path
                                                d="M17 8V6C17 4.11438 17 3.17157 16.4142 2.58579C15.8284 2 14.8856 2 13 2H11C9.11438 2 8.17157 2 7.58579 2.58579C7 3.17157 7 4.11438 7 6V8" />
                                            <path
                                                d="M13.9887 16L10.0113 16C9.32602 16 8.98337 16 8.69183 16.1089C8.30311 16.254 7.97026 16.536 7.7462 16.9099C7.57815 17.1904 7.49505 17.5511 7.32884 18.2724C7.06913 19.3995 6.93928 19.963 7.02759 20.4149C7.14535 21.0174 7.51237 21.5274 8.02252 21.7974C8.40513 22 8.94052 22 10.0113 22L13.9887 22C15.0595 22 15.5949 22 15.9775 21.7974C16.4876 21.5274 16.8547 21.0174 16.9724 20.4149C17.0607 19.963 16.9309 19.3995 16.6712 18.2724C16.505 17.5511 16.4218 17.1904 16.2538 16.9099C16.0297 16.536 15.6969 16.254 15.3082 16.1089C15.0166 16 14.674 16 13.9887 16Z" />
                                        </svg>
                                    </a>

                                    @can('admin.almacen.compras.pagos')
                                        <x-button-delete onclick="confirmDeletepay({{ $item }})"
                                            wire:loading.attr="disabled" />
                                    @endcan
                                </div>
                            </x-slot>
                        </x-card-cuota>
                    @endforeach
                </div>
            @else
                <x-span-text text="NO EXISTEN REGISTROS DE PAGOS..." class="mt-3 bg-transparent" />
            @endif

            @can('admin.almacen.compras.pagos')
                @if ($compra->sucursal_id == auth()->user()->sucursal_id)
                    @if ($compra->cajamovimientos()->sum('amount') < $compra->total)
                        <div class="w-full flex gap-2 pt-4 justify-end">
                            <x-button type="button" wire:loading.attr="disabled" wire:click="openmodal">
                                {{ __('REALIZAR PAGO') }}
                            </x-button>
                        </div>
                    @endif
                @endif
            @endcan

            <div wire:loading.flex class="loading-overlay rounded hidden">
                <x-loading-next />
            </div>
        </x-form-card>
    @endif

    <x-jet-dialog-modal wire:model="open" maxWidth="xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Realizar pago compra') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savepayment" class="w-full flex flex-col gap-1" x-data="paycompra">
                @if ($monthbox)
                    <p class="text-colorlabel text-md md:text-3xl font-semibold text-end mt-2 mb-5">
                        <small class="text-[10px] font-medium w-full block leading-3">CAJA MENSUAL</small>
                        {{ formatDate($monthbox->month, 'MMMM Y') }}
                        @if ($openbox)
                            <small class="w-full block font-medium text-xs">{{ $openbox->box->name }}</small>
                        @else
                            <small class="text-colorerror w-full block font-medium text-[10px] leading-3">
                                APERTURA DE CAJA DIARIA NO DISPONIBLE...
                            </small>
                        @endif
                    </p>
                @else
                    <p class="text-colorerror text-[10px] text-end">APERTURA DE CAJA MENSUAL NO DISPONIBLE...</p>
                @endif

                <div class="w-full">
                    <p class="text-colorlabel text-3xl font-semibold">
                        <small class="text-[10px] font-medium">{{ $compra->moneda->simbolo }}</small>
                        {{ number_format($compra->total, 3, '.', ', ') }}
                        <small class="text-[10px] font-medium">{{ $compra->moneda->currency }}</small>
                    </p>

                    @if ($pendiente < $compra->total)
                        <p class="text-colorerror text-2xl font-semibold">
                            <small class="text-[10px] font-medium">SALDO </small>
                            {{ number_format($pendiente, 3, '.', ', ') }}
                            <small class="text-[10px] font-medium">{{ $compra->moneda->currency }}</small>
                        </p>
                    @endif
                </div>

                <div>
                    <x-label value="Seleccionar moneda pago :" />
                    @if (count($diferencias) > 0)
                        <div class="w-full flex flex-wrap gap-2 justify-start items-center">
                            @foreach ($diferencias as $item)
                                <div class="inline-flex">
                                    <input class="sr-only peer" x-model="moneda_id" type="radio" name="monedas"
                                        id="moneda_{{ $item->moneda_id }}" value="{{ $item->moneda_id }}"
                                        @change="getCodeMoneda('{{ $item->moneda }}')" />
                                    <label for="moneda_{{ $item->moneda_id }}"
                                        class="peer-checked:border peer-checked:border-next-500 w-32 h-32 text-[10px] cursor-pointer inline-flex flex-col items-center justify-center relative bg-fondominicard text-colorlinknav shadow shadow-shadowminicard p-1 rounded-xl hover:shadow-md hover:shadow-shadowminicard transition-shadow ease-out duration-150">

                                        <div class="text-xs font-medium text-center">
                                            <small>SALDO CAJA</small>
                                            <h3 class="font-semibold text-xl">
                                                {{ number_format($item->diferencia, 2, '.', ', ') }}
                                            </h3>
                                            <p class="text-md font-semibold">{{ $item->moneda->currency }}</p>
                                        </div>

                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <x-jet-input-error for="moneda_id" />
                </div>
                {{-- <span x-text="moneda_id"></span> --}}

                <div class="w-full">
                    <x-label value="Monto pagar :" />
                    <x-input class="block w-full numeric" x-model="amount" @input="calcular" placeholder="0.00"
                        type="number" min="0" step="0.001"
                        onkeypress="return validarDecimal(event, 12)" />
                    <x-jet-input-error for="paymentactual" />
                </div>

                <div class="w-full" x-show="showtipocambio">
                    <div class="w-full">
                        <x-label value="Tipo cambio :" />
                        <x-input class="block w-full" x-model="tipocambio" @input="calcular" type="number"
                            placeholder="0.00" onkeypress="return validarDecimal(event, 7)" step="0.001"
                            min="0.001" />
                        <x-jet-input-error for="tipocambio" />
                    </div>

                    {{-- <span x-text="tipocambio"></span> --}}
                    <div class="w-full text-xs text-end text-neutral-500 font-semibold" x-show="totalamount > 0">
                        <small class="inline-block" x-text="simbolo"></small>
                        <template x-if="totalamount > 0">
                            <h1 x-text="totalamount" class="text-2xl inline-block"></h1>
                        </template>
                        <template x-if="totalamount == null">
                            <small class="inline-block text-colorerror">SELECCIONAR TIPO DE MONEDA...</small>
                        </template>
                        <small class="inline-block" x-text="currency"></small>
                    </div>
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
                    <x-jet-input-error for="openbox.id" />
                    <x-jet-input-error for="monthbox.id" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('paycompra', () => ({
                showtipocambio: @entangle('showtipocambio').defer,
                amount: @entangle('paymentactual').defer,
                tipocambio: @entangle('tipocambio').defer,
                totalamount: @entangle('totalamount').defer,
                moneda_id: @entangle('moneda_id').defer,
                code_moneda_compra: @json($compra->moneda->code),
                simbolo: null,
                code: null,
                currency: null,

                init() {
                    this.$watch("showtipocambio", (value) => {
                        this.tipocambio = null;
                        this.totalamount = '0.000';
                    });

                    this.$watch("moneda_id", (value) => {
                        // console.log(value);
                    });
                },
                getCodeMoneda(moneda) {
                    const monedaJSON = JSON.parse(moneda);
                    this.code = monedaJSON.code;
                    if (this.code == this.code_moneda_compra) {
                        this.showtipocambio = false;
                    } else {
                        if (this.code == 'PEN') {
                            this.simbolo = '$.';
                            this.currency = 'DÓLARES';
                        } else if (this.code == 'USD') {
                            this.simbolo = 'S/.';
                            this.currency = 'SOLES';
                        }
                        this.calcular();
                        this.currency = monedaJSON.currency;
                        this.simbolo = monedaJSON.simbolo;
                        this.showtipocambio = true;
                    }
                },
                calcular() {
                    if (this.code == 'PEN') {
                        if (toDecimal(this.amount) > 0 && toDecimal(this.tipocambio) > 0) {
                            this.totalamount = toDecimal(this.amount * this.tipocambio, 3);
                        } else {
                            this.totalamount = '0.000'
                        }
                    } else if (this.code == 'USD') {
                        if (toDecimal(this.amount) > 0 && toDecimal(this.tipocambio) > 0) {
                            this.totalamount = toDecimal(this.amount / this.tipocambio, 3);
                        } else {
                            this.totalamount = '0.000'
                        }
                    } else {
                        this.totalamount = null
                    }
                }
            }))
        })

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

        function comfirmDelete() {
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
        }

        function confirmDeletepay(payment) {
            swal.fire({
                title: 'ELIMINAR PAGO DE COMPRA ?',
                text: "Se eliminará un registro de pago de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deletepay(payment.id);
                }
            })
        }

        function confirmCloseCompra() {
            swal.fire({
                title: 'Desea cerrar el registro de la compra ?',
                text: "Se quitará la opción de agregar productos a la compra.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.closecompra();
                }
            })
        }
    </script>
</div>
