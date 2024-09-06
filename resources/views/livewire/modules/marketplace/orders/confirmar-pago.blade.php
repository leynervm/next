<x-simple-card class="w-full flex-1 p-5">
    <div wire:loading.flex class="fixed loading-overlay hidden z-[99999]">
        <x-loading-next />
    </div>

    <h1 class="text-xl font-semibold text-colorsubtitleform">
        RESUMEN PAGO</h1>

    @if ($order->isPagoconfirmado())
        <div class="w-full flex flex-col items-start gap-2">
            @if ($order->image)
                <button @click="openModal(); src = '{{ Storage::url('payments/depositos/' . $order->image->url) }}'"
                    class="w-full h-[150px] md:max-w-[100px] border border-borderminicard rounded-md overflow-hidden">
                    <img src="{{ Storage::url('payments/depositos/' . $order->image->url) }}"
                        class="w-full h-full object-cover">
                </button>
            @endif

            @if ($order->methodpay)
                <p class="text-next-500 text-xs font-semibold">
                    {{ str_replace('_', ' ', $order->methodpay->name) }}</p>

                @foreach ($order->transaccions as $item)
                    <p class="text-green-600 text-xs">
                        <small class="text-colorsubtitleform">ESTADO :</small>
                        <b>{{ $item->action_description }}</b>
                    </p>

                    <p class="text-green-600 text-xs">
                        <small class="text-colorsubtitleform">DESCRIPCION :</small>
                        <b>{{ $item->eci_description }}</b>
                    </p>

                    <p class="text-green-600 text-xs">
                        <small class="text-colorsubtitleform">ID TRANSACCIÓN :</small>
                        <b>{{ $item->transaction_id }}</b>
                    </p>
                    <p class="text-green-600 text-xs">
                        <small class="text-colorsubtitleform">FECHA Y HORA PAGO :</small>
                        <b>{{ formatDate($item->date) }}</b>
                    </p>
                    <p class="text-green-600 text-xl">
                        <small class="text-colorsubtitleform text-xs">IMPORTE :</small>
                        <b>{{ number_format($item->amount, 2, '.', ', ') }} {{ $item->currency }}</b>
                    </p>
                @endforeach
            @else
            @endif
        </div>
    @endif

    @if (count($order->cajamovimientos) > 0)
        <div
            class="w-full grid grid-cols-[repeat(auto-fill,minmax(12rem,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(15rem,1fr))] gap-2">
            @foreach ($order->cajamovimientos as $item)
                <x-card-payment-box class="w-full" :cajamovimiento="$item" :moneda="$order->moneda">
                    <x-slot name="footer">
                        <x-button-print-payment class="mr-auto" href="{{ route('admin.payments.print', $item) }}" />

                        @can('admin.marketplace.orders.confirmpay')
                            <x-button-delete onclick="confirmDeletepay({{ $item->id }})"
                                wire:loading.attr="disabled" />
                        @endcan
                    </x-slot>
                </x-card-payment-box>
            @endforeach
        </div>
    @endif

    @if (!$order->isPagoconfirmado())
        @if ($order->cajamovimientos()->sum('amount') < $order->total)
            @can('admin.marketplace.orders.confirmpay')
                <div class="w-full mt-4">
                    <x-button @click="$wire.set('open', true)" wire:loading.attr="disabled" wire:key="confirmarpago">
                        REALIZAR PAGO</x-button>
                </div>
            @endcan
        @endif
    @endif

    <x-jet-dialog-modal wire:model="open" maxWidth="xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Confirmar pago') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savepayment" class="w-full flex flex-col gap-1" x-data="payorder">
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
                    <p class="text-colorerror text-[10px] text-end">
                        APERTURA DE CAJA MENSUAL NO DISPONIBLE...</p>
                @endif

                <div class="w-full">
                    <p class="text-colorlabel text-3xl font-semibold">
                        <small class="text-[10px] font-medium">{{ $order->moneda->simbolo }}</small>
                        {{ number_format($order->total, 2, '.', ', ') }}
                        <small class="text-[10px] font-medium">{{ $order->moneda->currency }}</small>
                    </p>

                    @if ($pendiente < $order->total)
                        <p class="text-colorerror text-2xl font-semibold">
                            <small class="text-[10px] font-medium">SALDO </small>
                            {{ number_format($pendiente, 2, '.', ', ') }}
                            <small class="text-[10px] font-medium">{{ $order->moneda->currency }}</small>
                        </p>
                    @endif
                </div>

                {{-- <div class="w-full">
                    <x-label value="Moneda :" />
                    <div class="relative" x-init="MonedaPago">
                        <x-select class="block w-full" id="monedampman_id" data-dropdown-parent="null"
                            x-ref="selectmoneda">
                            <x-slot name="options">
                                @if (count($monedas) > 0)
                                    @foreach ($monedas as $item)
                                        <option value="{{ $item->id }}">{{ $item->currency }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="moneda_id" />
                </div> --}}

                <div class="w-full">
                    <x-label value="Monto pagar :" />
                    <x-input class="block w-full numeric" x-model="amount" placeholder="0.00" type="number"
                        min="0" step="0.001" onkeypress="return validarDecimal(event, 12)" />
                    <x-jet-input-error for="paymentactual" />
                </div>

                <div class="w-full">
                    <x-label value="Método pago :" />
                    <div class="relative" x-init="select2Methodpayment" id="parentqwerty">
                        <x-select class="block w-full" x-ref="selectmp" id="qwerty" data-dropdown-parent="null">
                            <x-slot name="options">
                                @if (count($methodpayments) > 0)
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
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('payorder', () => ({
                amount: @entangle('paymentactual').defer,
                moneda_id: @entangle('moneda_id').defer,
                methodpayment_id: @entangle('methodpayment_id').defer,
                code_moneda_order: @json($order->moneda->code),

                init() {

                }
            }))
        })


        function MonedaPago() {
            this.selectMP = $(this.$refs.selectmoneda).select2();
            this.selectMP.val(this.moneda_id).trigger("change");
            this.selectMP.on("select2:select", (event) => {
                this.moneda_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("moneda_id", (value) => {
                this.selectMP.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectMP.select2().val(this.moneda_id).trigger('change');
            });
        }

        function select2Methodpayment() {
            this.selectMTP = $(this.$refs.selectmp).select2();
            this.selectMTP.val(this.methodpayment_id).trigger("change");
            this.selectMTP.on("select2:select", (event) => {
                this.methodpayment_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("methodpayment_id", (value) => {
                this.selectMTP.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectMTP.select2().val(this.methodpayment_id).trigger('change');
            });
        }

        function confirmDeletepay(payment_id) {
            swal.fire({
                title: 'ELIMINAR PAGO DE ORDEN ?',
                text: "Se eliminará un registro de pago de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deletepay(payment_id);
                }
            })
        }
    </script>
</x-simple-card>
