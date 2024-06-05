<div>
    <x-form-card titulo="CUOTAS PAGO" subtitulo="Información de cuotas de pago de la compra.">
        @if (count($compra->cuotas) > 0)
            <div class="w-full flex flex-col gap-2">
                <div class="w-full flex gap-2 flex-wrap justify-start">
                    @foreach ($compra->cuotas as $item)
                        <x-card-cuota class="w-full xs:w-60" :titulo="null" :detallepago="$item->cajamovimiento"
                            :wire:key="'cardcuota-'.$item->id">
                            <p class="text-colorminicard text-xl font-semibold text-center">
                                <small class="text-[10px] font-medium">{{ $compra->moneda->simbolo }}</small>
                                {{ number_format($item->amount, 3, '.', ', ') }}
                                <small class="text-[10px] font-medium">{{ $compra->moneda->currency }}</small>
                            </p>

                            <div class="w-full flex flex-wrap gap-2 justify-center">
                                <x-span-text :text="'Cuota' . substr('000' . $item->cuota, -3)" class="leading-3 !tracking-normal" />
                                <x-span-text :text="formatDate($item->expiredate, 'DD MMMM Y')" class="leading-3 !tracking-normal" />
                            </div>

                            <x-slot name="footer">
                                @if (auth()->user()->sucursal_id == $compra->sucursal_id)
                                    @if ($item->cajamovimiento)
                                        <div class="w-full flex gap-2 flex-wrap items-end justify-between">
                                            <a href="{{ route('admin.payments.print', $item->cajamovimiento) }}"
                                                target="_blank"
                                                class="p-1.5 bg-neutral-900 text-white block rounded-lg transition-colors duration-150">
                                                <svg class="w-4 h-4 block scale-110" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path
                                                        d="M7.35396 18C5.23084 18 4.16928 18 3.41349 17.5468C2.91953 17.2506 2.52158 16.8271 2.26475 16.3242C1.87179 15.5547 1.97742 14.5373 2.18868 12.5025C2.36503 10.8039 2.45321 9.95455 2.88684 9.33081C3.17153 8.92129 3.55659 8.58564 4.00797 8.35353C4.69548 8 5.58164 8 7.35396 8H16.646C18.4184 8 19.3045 8 19.992 8.35353C20.4434 8.58564 20.8285 8.92129 21.1132 9.33081C21.5468 9.95455 21.635 10.8039 21.8113 12.5025C22.0226 14.5373 22.1282 15.5547 21.7352 16.3242C21.4784 16.8271 21.0805 17.2506 20.5865 17.5468C19.8307 18 18.7692 18 16.646 18" />
                                                    <path
                                                        d="M17 8V6C17 4.11438 17 3.17157 16.4142 2.58579C15.8284 2 14.8856 2 13 2H11C9.11438 2 8.17157 2 7.58579 2.58579C7 3.17157 7 4.11438 7 6V8" />
                                                    <path
                                                        d="M13.9887 16L10.0113 16C9.32602 16 8.98337 16 8.69183 16.1089C8.30311 16.254 7.97026 16.536 7.7462 16.9099C7.57815 17.1904 7.49505 17.5511 7.32884 18.2724C7.06913 19.3995 6.93928 19.963 7.02759 20.4149C7.14535 21.0174 7.51237 21.5274 8.02252 21.7974C8.40513 22 8.94052 22 10.0113 22L13.9887 22C15.0595 22 15.5949 22 15.9775 21.7974C16.4876 21.5274 16.8547 21.0174 16.9724 20.4149C17.0607 19.963 16.9309 19.3995 16.6712 18.2724C16.505 17.5511 16.4218 17.1904 16.2538 16.9099C16.0297 16.536 15.6969 16.254 15.3082 16.1089C15.0166 16 14.674 16 13.9887 16Z" />
                                                </svg>
                                            </a>

                                            <x-button-delete onclick="confirmDeletePay( {{ $item }})"
                                                wire:loading.attr="disabled" wire:key="deletepay_{{ $item->id }}" />
                                        </div>
                                    @else
                                        @can('admin.almacen.compras.pagos')
                                            <div class="w-full flex gap-2 flex-wrap items-end justify-between">

                                                <x-button wire:key="paycuota_{{ $item->id }}"
                                                    wire:click="paycuota({{ $item->id }})"
                                                    wire:loading.attr="disabled">PAGAR</x-button>

                                                <x-button-delete onclick="confirmDeleteCuota({{ $item }})"
                                                    wire:loading.attr="disabled"
                                                    wire:key="deletecuota_{{ $item->id }}" />
                                            </div>
                                        @endcan
                                    @endif
                                @endif
                            </x-slot>

                        </x-card-cuota>
                    @endforeach
                </div>

                @can('admin.almacen.compras.create')
                    @if ($compra->cuotas()->sum('amount') < $compra->total)
                        <div class="w-full flex justify-end">
                            <x-button wire:click="editcuotas" wire:loading.attr="disabled" wire:key="editcuotas">
                                EDITAR CUOTAS</x-button>
                        </div>
                    @endif
                @endcan
            </div>
        @else
            <div class="w-full flex flex-wrap xl:flex-nowrap gap-2">
                <form wire:submit.prevent="calcularcuotas"
                    class="w-full xl:w-1/3 relative flex flex-col gap-2 bg-body p-3 rounded">
                    <div class="w-full">
                        <x-label value="Cuotas :" />
                        <x-input class="block w-full" type="number" min="1" step="1" max="10"
                            wire:model.defer="countcuotas" />
                    </div>
                    <x-jet-input-error for="countcuotas" />

                    @can('admin.almacen.compras.create')
                        <div class="w-full flex justify-end mt-3">
                            <x-button type="submit" wire:loading.attr="disabled" wire:key="{{ rand() }}">
                                CALCULAR
                            </x-button>
                        </div>
                    @endcan
                </form>

                <div class="w-full xl:w-2/3">
                    @if (count($cuotas))
                        <div class="w-full flex flex-wrap gap-1">
                            @foreach ($cuotas as $item)
                                <x-card-cuota :titulo="substr('000' . $item['cuota'], -3)" class="w-full sm:w-48">

                                    <x-label value="Fecha pago :" />
                                    <x-input class="block w-full" type="date"
                                        wire:model.defer="cuotas.{{ $loop->iteration - 1 }}.date" />

                                    <x-label value="Monto Cuota :" />
                                    <x-input class="block w-full" type="number" min="1" step="0.001"
                                        wire:model.defer="cuotas.{{ $loop->iteration - 1 }}.amount" />

                                    <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.cuota" />
                                    <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.date" />
                                    <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.amount" />
                                </x-card-cuota>
                            @endforeach
                        </div>
                        <x-jet-input-error for="cuotas" />
                        <x-jet-input-error for="amountcuotas" />

                        @can('admin.almacen.compras.create')
                            <div class="w-full flex pt-4 justify-end">
                                <x-button wire:click="savecuotas" wire:loading.attr="disabled">
                                    {{ __('REGISTRAR') }}
                                </x-button>
                            </div>
                        @endcan
                    @endif
                </div>
            </div>
        @endif

        <div wire:loading.flex class="loading-overlay rounded hidden">
            <x-loading-next />
        </div>
    </x-form-card>

    <x-jet-dialog-modal wire:model="openpaycuota" maxWidth="xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Realizar pago cuota compra') }}
            <x-button-close-modal wire:click="$toggle('openpaycuota')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savepayment" class="w-full flex flex-col gap-1" x-data="cuotascompra">
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
                        {{ number_format($cuota->amount, 3, '.', ', ') }}
                        <small class="text-[10px] font-medium">{{ $compra->moneda->currency }}</small>
                    </p>
                </div>

                <div>
                    <x-label value="Seleccionar moneda pago :" />
                    @if (count($diferencias) > 0)
                        <div class="w-full flex flex-wrap gap-2 justify-start items-center">
                            @foreach ($diferencias as $item)
                                <div class="inline-flex">
                                    <input class="sr-only peer" x-model="moneda_id" type="radio" name="monedascuota"
                                        id="monedacuota_{{ $item->moneda_id }}" value="{{ $item->moneda_id }}"
                                        @change="getCodeMoneda('{{ $item->moneda }}')" />
                                    <label for="monedacuota_{{ $item->moneda_id }}"
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

                <div class="w-full" x-show="showtipocambio">
                    <div class="w-full">
                        <x-label value="Tipo cambio :" />
                        <x-input class="block w-full" x-model="tipocambio" @input="calcular" type="number"
                            placeholder="0.00" onkeypress="return validarDecimal(event, 7)" step="0.001"
                            min="0.001" />
                        <x-jet-input-error for="tipocambio" />
                    </div>

                    {{-- x-show="totalamount > 0" --}}
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
                        <x-select class="block w-full" x-ref="select" id="qwerty" data-dropdown-parent="null">
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
                    <x-jet-input-error for="opencaja.id" />
                </div>

                {{-- {{ print_r($errors->all()) }} --}}

                @can('admin.almacen.compras.create')
                    <div class="w-full flex pt-4 justify-end">
                        <x-button type="submit" wire:loading.attr="disabled" wire:target="savepayment">
                            {{ __('REGISTRAR') }}
                        </x-button>
                    </div>
                @endcan
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="opencuotas" maxWidth="2xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Editar cuotas compra') }}
            <x-button-close-modal wire:click="$toggle('opencuotas')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <div class="w-full flex flex-col gap-3 relative">
                <form wire:submit.prevent="updatecuotas" class="w-full flex flex-wrap justify-around gap-2">
                    @if (count($cuotas) > 0)
                        <div class="w-full flex flex-wrap gap-1">
                            @foreach ($cuotas as $item)
                                <x-card-cuota :titulo="substr('000' . $item['cuota'], -3)" class="w-full sm:w-60">
                                    @if (!is_null($item['cajamovimiento_id']))
                                        <x-icon-default class="absolute top-2 right-2" />
                                        <p class="text-colorminicard text-xl font-semibold text-center">
                                            <small
                                                class="text-[10px] font-medium">{{ $compra->moneda->simbolo }}</small>
                                            {{ number_format($item['amount'], 3, '.', ', ') }}
                                            <small
                                                class="text-[10px] font-medium">{{ $compra->moneda->currency }}</small>
                                        </p>
                                    @endif

                                    <x-label value="Fecha pago :" class="mt-5" />
                                    @if (is_null($item['cajamovimiento_id']))
                                        <x-input class="block w-full" type="date"
                                            wire:model.defer="cuotas.{{ $loop->iteration - 1 }}.date" />
                                    @else
                                        <x-disabled-text :text="\Carbon\Carbon::parse($item['date'])->format('d/m/Y')" />
                                    @endif


                                    @if (is_null($item['cajamovimiento_id']))
                                        <x-label value="Monto Cuota :" />
                                        <x-input class="block w-full" type="number" min="1" step="0.0001"
                                            wire:model.defer="cuotas.{{ $loop->iteration - 1 }}.amount"
                                            onkeypress="return validarDecimal(event, 9)" />
                                    @endif

                                    <x-jet-input-error for="cuotas.{{ $loop->iteration - 1 }}.cuota" />
                                    <x-jet-input-error for="cuotas.{{ $loop->iteration - 1 }}.date" />
                                    <x-jet-input-error for="cuotas.{{ $loop->iteration - 1 }}.amount" />

                                </x-card-cuota>
                            @endforeach
                        </div>
                        <x-jet-input-error for="cuotas" />
                        <x-jet-input-error for="amountcuotas" />

                        <div class="w-full mt-3 gap-2 flex items-center justify-center">
                            <x-button wire:click="addnewcuota" wire:loading.attr="disabled"
                                wire:target="addnewcuota">
                                AGREGAR NUEVA CUOTA
                            </x-button>
                            <x-button type="submit" wire:loading.attr="disable" wire:target="updatecuotas">
                                CONFIRMAR CUOTAS</x-button>
                        </div>
                    @endif
                </form>

                <div wire:loading.flex class="loading-overlay rounded hidden">
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
            this.selectS = $(this.$refs.select).select2();
            this.selectS.val(this.methodpayment_id).trigger("change");
            this.selectS.on("select2:select", (event) => {
                this.methodpayment_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("methodpayment_id", (value) => {
                this.selectS.val(value).trigger("change");
            });
        }

        function confirmDeletePay(cuota) {
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
                    @this.deletepaycuota(cuota.id);
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
