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
                                <small class="text-[10px] font-medium">{{ $compra->moneda->simbolo }}</small>
                                {{ number_format($item->amount, 2, '.', ', ') }}
                                <small class="text-[10px] font-medium">{{ $compra->moneda->currency }}</small>
                            </p>

                            <x-slot name="footer">
                                <div class="w-full flex gap-2 items-end justify-between">
                                    <x-mini-button>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path
                                                d="M17.571 18H20.4a.6.6 0 00.6-.6V11a4 4 0 00-4-4H7a4 4 0 00-4 4v6.4a.6.6 0 00.6.6h2.829M8 7V3.6a.6.6 0 01.6-.6h6.8a.6.6 0 01.6.6V7" />
                                            <path
                                                d="M6.098 20.315L6.428 18l.498-3.485A.6.6 0 017.52 14h8.96a.6.6 0 01.594.515L17.57 18l.331 2.315a.6.6 0 01-.594.685H6.692a.6.6 0 01-.594-.685z" />
                                            <path d="M17 10.01l.01-.011" />
                                        </svg>
                                    </x-mini-button>

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

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Realizar pago compra') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savepayment" class="w-full flex flex-col gap-1">
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
                </div>

                <div class="w-full">
                    <x-label value="Monto pendiente pagar :" />
                    <x-disabled-text :text="number_format($pendiente, 3, '.', ', ')" />
                </div>

                <div class="w-full">
                    <x-label value="Monto pagar :" />
                    <x-input class="block w-full numeric" wire:model.defer="paymentactual" placeholder="0.00"
                        type="number" min="0" step="0.0001" />
                    <x-jet-input-error for="paymentactual" />
                </div>

                <div class="w-full">
                    <x-label value="Método pago :" />
                    <div class="relative" x-data="{ methodpayment_id: @entangle('methodpayment_id').defer }" x-init="select2Methodpayment" id="parentqwerty" wire:ignore>
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
        function toDecimal(valor, decimals = 3) {
            let numero = parseFloat(valor);

            if (isNaN(numero)) {
                return 0;
            } else {
                return parseFloat(numero).toFixed(decimals);
            }
        }

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
                title: 'Eliminar pago de ' + toDecimal(payment.amount, 2) + ' de la compra ?',
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
