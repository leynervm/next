<div x-data="{
    istransferencia: false,
    detalle: @entangle('detalle').defer,
    reset() {
        this.istransferencia = false;
        this.detalle = '';
    }
}">
    <div wire:loading.flex class="loading-overlay fixed hidden">
        <x-loading-next />
    </div>

    <x-form-card titulo="CUOTAS PAGO" subtitulo="Información de cuotas de pago de la compra.">
        @if (count($compra->cuotas) > 0)
            <div class="w-full flex flex-col gap-2">
                <div
                    class="w-full grid grid-cols-[repeat(auto-fill,minmax(12rem,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(15rem,1fr))] gap-2">
                    @foreach ($compra->cuotas as $item)
                        <x-simple-card class="w-full flex flex-col justify-between p-1">
                            <div class="w-full">
                                <p class="text-colorminicard text-xl font-semibold text-center">
                                    <small class="text-[10px] font-medium">{{ $compra->moneda->simbolo }}</small>
                                    {{ decimalOrInteger($item->amount, 2, ', ') }}
                                    <small class="text-[10px] font-medium">{{ $compra->moneda->currency }}</small>
                                </p>

                                <div class="w-full flex flex-col justify-center items-center">
                                    <x-span-text :text="'Cuota' . substr('000' . $item->cuota, -3)" class="leading-3 !tracking-normal" />
                                    <p class="text-colorsubtitleform text-[10px]">
                                        VENC. {{ formatDate($item->expiredate, 'DD MMMM Y') }}</p>
                                </div>

                                @if (count($item->cajamovimientos))
                                    <div class="w-full flex flex-col gap-1">
                                        @foreach ($item->cajamovimientos as $payment)
                                            <x-card-payment-box :cajamovimiento="$payment" :moneda="$compra->moneda">
                                                <x-slot name="footer">
                                                    <x-button-print class="mr-auto"
                                                        href="{{ route('admin.payments.print', $item) }}" />

                                                    <x-button-delete
                                                        onclick="confirmDeletePay({{ $payment->id }}, {{ $item }})"
                                                        wire.loading.attr="disabled" />
                                                </x-slot>
                                            </x-card-payment-box>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="w-full mt-1 flex gap-2 items-end justify-between">
                                @can('admin.almacen.compras.pagos')
                                    @if (count($item->cajamovimientos) == 0)
                                        <x-button-delete onclick="confirmDeleteCuota({{ $item }})"
                                            wire:loading.attr="disabled" />
                                    @endif
                                @endcan

                                @can('admin.almacen.compras.create')
                                    @if ($compra->sucursal_id == auth()->user()->sucursal_id)
                                        @if ($item->amount - $item->cajamovimientos->sum('amount') > 0)
                                            <x-button wire:click="paycuota({{ $item->id }})"
                                                wire:key="pagarcuota_{{ $item->id }}" wire:loading.attr="disabled"
                                                @click="reset">PAGAR</x-button>
                                        @endif
                                    @endif
                                @endcan
                            </div>
                        </x-simple-card>
                    @endforeach
                </div>

                @can('admin.almacen.compras.create')
                    @if ($compra->sucursal_id == auth()->user()->sucursal_id)
                        @if ($compra->cuotas()->sum('amount') < $compra->total)
                            <div class="w-full flex justify-end">
                                <x-button wire:click="editcuotas" wire:loading.attr="disabled" wire:key="editcuotas">
                                    EDITAR CUOTAS</x-button>
                            </div>
                        @endif
                    @endif
                @endcan
            </div>
        @else
            <div class="w-full grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                <form wire:submit.prevent="calcularcuotas" class="w-full relative flex flex-col">
                    <div class="w-full">
                        <x-label value="Cuotas :" />
                        <x-input class="block w-full" type="number" min="1" step="1" max="10"
                            wire:model.defer="countcuotas" />
                        <x-jet-input-error for="countcuotas" />
                    </div>

                    @can('admin.almacen.compras.create')
                        <div class="w-full flex justify-end mt-2">
                            <x-button type="submit" wire:loading.attr="disabled" wire:key="{{ rand() }}">
                                CALCULAR</x-button>
                        </div>
                    @endcan
                </form>

                <div class="w-full sm:col-span-2 lg:col-span-3 xl:col-span-4">
                    @if (count($cuotas) > 0)
                        <div
                            class="w-full grid grid-cols-[repeat(auto-fill,minmax(10rem,1fr))] xl:grid-cols-[repeat(auto-fill,minmax(12rem,1fr))] gap-2">
                            @foreach ($cuotas as $item)
                                <x-simple-card class="w-full p-1">
                                    <div class="w-full flex flex-col justify-center items-center gap-1">
                                        <x-span-text :text="'Cuota' . substr('000' . $item['cuota'], -3)" />

                                        <div class="block w-full">
                                            <x-label value="Fecha pago :" />
                                            <x-input class="block w-full" type="date"
                                                wire:model.defer="cuotas.{{ $loop->iteration - 1 }}.date" />
                                        </div>
                                        <div class="block w-full">
                                            <x-label value="Monto Cuota :" />
                                            <x-input class="block w-full" type="number" min="1" step="0.001"
                                                wire:model.defer="cuotas.{{ $loop->iteration - 1 }}.amount" />
                                        </div>
                                    </div>

                                    <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.cuota" />
                                    <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.date" />
                                    <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.amount" />
                                </x-simple-card>
                            @endforeach
                        </div>
                        <x-jet-input-error for="cuotas" />
                        <x-jet-input-error for="amountcuotas" />

                        @can('admin.almacen.compras.create')
                            <div class="w-full flex pt-2 justify-end">
                                <x-button wire:click="savecuotas" wire:loading.attr="disabled">
                                    {{ __('Save') }}</x-button>
                            </div>
                        @endcan
                    @endif
                </div>
            </div>
        @endif
    </x-form-card>

    <x-jet-dialog-modal wire:model="openpaycuota" maxWidth="xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Realizar pago cuota compra') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savepayment" class="w-full flex flex-col gap-1" x-data="cuotascompra">
                @if ($monthbox)
                    <x-card-box :openbox="$openbox" :monthbox="$monthbox" />
                @else
                    <p class="text-colorerror text-[10px] text-end">APERTURA DE CAJA MENSUAL NO DISPONIBLE...</p>
                @endif

                <div class="w-full">
                    <p class="text-colorlabel text-3xl font-semibold leading-3">
                        <small class="text-[10px] font-medium">{{ $compra->moneda->simbolo }}</small>
                        {{ number_format($cuota->amount, 2, '.', ', ') }}
                        <small class="text-[10px] font-medium">{{ $compra->moneda->currency }}</small>
                    </p>

                    @if ($pendiente > 0)
                        <p class="text-colorerror text-sm font-semibold">
                            <small class="text-[10px] font-medium">PENDIENTE {{ $compra->moneda->simbolo }}</small>
                            {{ number_format($pendiente, 2, '.', ', ') }}
                        </p>
                    @endif
                </div>

                <div>
                    <x-label value="Seleccionar moneda :" />
                    @if (count($diferencias) > 0)
                        <div class="w-full flex flex-wrap gap-2 justify-start items-center">
                            @foreach ($diferencias as $item)
                                <div class="inline-flex">
                                    <input class="sr-only peer" x-model="moneda_id" type="radio" name="monedascuota"
                                        id="monedacuota_{{ $item->moneda_id }}" value="{{ $item->moneda_id }}"
                                        @change="getCodeMoneda('{{ $item->moneda }}')" />
                                    <x-label-check-moneda for="monedacuota_{{ $item->moneda_id }}" :simbolo="$item->moneda->simbolo"
                                        :saldo="$item->diferencia" :diferenciasbytype="$diferenciasbytype->where('moneda_id', $item->moneda_id)" />
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <x-jet-input-error for="moneda_id" />
                </div>

                <div class="w-full">
                    <x-label value="Monto :" />
                    <x-input class="block w-full input-number-none" x-model="amount" @input="calcular"
                        type="number" onkeypress="return validarDecimal(event, 7)" step="0.001" min="0.001" />
                    <x-jet-input-error for="amount" />
                </div>

                <div class="w-full" x-show="showtipocambio">
                    <div class="w-full">
                        <x-label value="Tipo cambio :" />
                        <x-input class="block w-full input-number-none" x-model="tipocambio" @input="calcular"
                            type="number" step="0.001" onkeypress="return validarDecimal(event, 7)" />
                        <x-jet-input-error for="tipocambio" />
                    </div>

                    <div class="w-full text-xs text-end text-colorsubtitleform font-semibold"
                        x-show="totalamount > 0">
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
                    <div class="relative" x-init="select2Methodpayment" id="parentqwerty" wire:ignore>
                        <x-select class="block w-full" x-ref="select" id="qwerty" data-dropdown-parent="null">
                            <x-slot name="options">
                                @if (count($methodpayments) > 0)
                                    @foreach ($methodpayments as $item)
                                        <option value="{{ $item->id }}"
                                            data-transferencia="{{ $item->isTransferencia() }}">{{ $item->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="methodpayment_id" />
                </div>

                <div class="w-full" x-show="istransferencia" x-cloak style="display: none;" x-transition>
                    <x-label value="Otros (N° operación , Banco, etc) :" />
                    <x-text-area class="block w-full" x-model="detalle"></x-text-area>
                    <x-jet-input-error for="detalle" />
                </div>

                <div class="w-full">
                    <x-jet-input-error for="concept_id" />
                    <x-jet-input-error for="openbox.id" />
                    <x-jet-input-error for="monthbox.id" />
                </div>

                {{-- {{ print_r($errors->all()) }} --}}

                @can('admin.almacen.compras.create')
                    <div class="w-full flex pt-4 justify-end">
                        <x-button type="submit" wire:loading.attr="disabled">
                            {{ __('Save') }}</x-button>
                    </div>
                @endcan
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="opencuotas" maxWidth="2xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Editar cuotas compra') }}
        </x-slot>

        <x-slot name="content">
            <div class="w-full flex flex-col gap-3 relative">
                <form wire:submit.prevent="updatecuotas" class="w-full flex flex-wrap justify-around gap-2">
                    @if (count($cuotas) > 0)
                        <div class="w-full grid grid-cols-[repeat(auto-fill,minmax(12rem,1fr))] gap-2">
                            @foreach ($cuotas as $item)
                                <x-simple-card class="w-full p-2 flex flex-col justify-center items-center gap-2">

                                    <x-span-text :text="'Cuota' . substr('000' . $item['cuota'], -3)" />

                                    @if (count($item['cajamovimientos']) > 0)
                                        <x-icon-default class="absolute top-2 right-2" />
                                        <p class="text-colorminicard text-xl font-semibold text-center">
                                            <small
                                                class="text-[10px] font-medium">{{ $compra->moneda->simbolo }}</small>
                                            {{ (float) $item['amount'] }}
                                            <small
                                                class="text-[10px] font-medium">{{ $compra->moneda->currency }}</small>
                                        </p>
                                    @endif

                                    <div class="w-full">
                                        <x-label value="Fecha pago :" class="mt-5" />
                                        @if (count($item['cajamovimientos']) == 0)
                                            <x-input class="block w-full" type="date"
                                                wire:model.defer="cuotas.{{ $loop->iteration - 1 }}.date" />
                                        @else
                                            <x-disabled-text :text="formatDate($item['date'], 'DD/MM/Y')" />
                                        @endif
                                    </div>
                                    <div class="w-full">
                                        @if (count($item['cajamovimientos']) == 0)
                                            <x-label value="Monto Cuota :" />
                                            <x-input class="block w-full" type="number" min="1"
                                                step="0.0001"
                                                wire:model.defer="cuotas.{{ $loop->iteration - 1 }}.amount"
                                                onkeypress="return validarDecimal(event, 9)" />
                                        @endif
                                    </div>
                                    <div class="w-full">
                                        <x-jet-input-error for="cuotas.{{ $loop->iteration - 1 }}.cuota" />
                                        <x-jet-input-error for="cuotas.{{ $loop->iteration - 1 }}.date" />
                                        <x-jet-input-error for="cuotas.{{ $loop->iteration - 1 }}.amount" />
                                    </div>
                                </x-simple-card>
                            @endforeach
                        </div>
                        <x-jet-input-error for="cuotas" />
                        <x-jet-input-error for="amountcuotas" />

                        <div class="w-full mt-3 gap-2 flex items-center justify-center">
                            <x-button wire:click="addnewcuota" wire:loading.attr="disabled">
                                AGREGAR NUEVA CUOTA</x-button>
                            <x-button type="submit" wire:loading.attr="disable">
                                CONFIRMAR CUOTAS</x-button>
                        </div>
                    @endif
                </form>

                <div wire:loading.flex class="loading-overlay fixed hidden">
                    <x-loading-next />
                </div>
            </div>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cuotascompra', () => ({
                showtipocambio: @entangle('showtipocambio').defer,
                amount: @entangle('amount').defer,
                tipocambio: @entangle('tipocambio').defer,
                totalamount: @entangle('totalamount').defer,
                moneda_id: @entangle('moneda_id').defer,
                methodpayment_id: @entangle('methodpayment_id').defer,
                code_moneda_compra: @json($compra->moneda->code),
                simbolo: null,
                code: null,
                currency: null,

                init() {
                    this.$watch("showtipocambio", (value) => {
                        this.tipocambio = null;
                        this.totalamount = '0.00';
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
                            this.totalamount = toDecimal(this.amount * this.tipocambio, 2);
                        } else {
                            this.totalamount = '0.00'
                        }
                    } else if (this.code == 'USD') {
                        if (toDecimal(this.amount) > 0 && toDecimal(this.tipocambio) > 0) {
                            this.totalamount = toDecimal(this.amount / this.tipocambio, 2);
                        } else {
                            this.totalamount = '0.00'
                        }
                    } else {
                        this.totalamount = null
                    }
                }
            }))
        })

        function select2Methodpayment() {
            this.selectS = $(this.$refs.select).select2();
            this.selectS.val(this.methodpayment_id).trigger("change");
            this.selectS.on("select2:select", (event) => {
                this.methodpayment_id = event.target.value;
                var selectedOption = event.params.data.element;
                this.istransferencia = Boolean($(selectedOption).data('transferencia'));
                if (!this.istransferencia) {
                    this.detalle = '';
                }
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("methodpayment_id", (value) => {
                this.selectS.val(value).trigger("change");
            });
        }

        function confirmDeletePay(cajamovimiento_id, cuota) {
            const cuotastr = '000' + cuota.cuota;
            swal.fire({
                title: 'Desea anular el pago de la Cuota' + cuotastr.substr(-3) + '?',
                text: "Se eliminará un registro de pago de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deletepaycuota(cajamovimiento_id);
                }
            })
        }

        function confirmDeleteCuota(cuota) {
            const cuotastr = '000' + cuota.cuota;
            swal.fire({
                title: 'Desea eliminar la Cuota' + cuotastr.substr(-3) + '?',
                text: "Se eliminará un registro de pago de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deletecuota(cuota.id);
                }
            })
        }
    </script>
</div>
