<div>
    <h1 class="text-xl font-semibold text-colorsubtitleform pb-3">
        MIS COMPRAS</h1>

    <div class="flex flex-wrap gap-2 pb-3">
        <div class="w-full xs:max-w-[170px]">
            <x-label value="Fecha :" />
            <x-input type="date" wire:model.lazy="date" class="w-full" />
        </div>

        <div class="w-full xs:max-w-[170px]">
            <x-label value="Hasta :" />
            <x-input type="date" wire:model.lazy="dateto" class="w-full" />
        </div>
    </div>

    @if (count($orders) > 0)
        <div class="border border-borderminicard rounded-xl">
            <table class="w-full min-w-full text-[10px]">
                <tbody class="divide-y text-textbodytable">
                    @foreach ($orders as $item)
                        <tr class="border-b border-dividetable">
                            <td class="align-middle text-left p-3 xl:py-5">
                                <a href="{{ route('orders.payment', $item) }}"
                                    class="inline-block leading-3 text-[10px] uppercase text-linktable">
                                    #{{ $item->purchase_number }}
                                    <br>
                                    {{ formatDate($item->date, 'DD MMM Y hh:mm A') }}
                                </a>
                            </td>
                            <td class="text-center p-3 xl:py-5 text-xs">
                                MONTO <br>
                                <p class="font-semibold text-sm">
                                    <small class="text-[10px] font-medium">{{ $item->moneda->simbolo }}</small>
                                    {{ number_format($item->total, 2, '.', ', ') }}
                                </p>
                            </td>
                            <td class="text-center p-3 xl:py-5">
                                @if ($item->isPagoconfirmado())
                                    <x-span-text text="PAGO CONFIRMADO CON ÉXITO" type="green" />
                                @elseif ($item->isPagado())
                                    <x-span-text text="EN ESPERA DE CONFIRMACIÓN" type="orange" />
                                @else
                                    <x-span-text text="PENDIENTE PAGO" type="red" />
                                @endif
                            </td>
                            @if ($item->transaccion)
                                <td class="text-center p-3 xl:py-5 uppercase">
                                    @if ($item->transaccion->brand == 'visa')
                                        <svg class="w-10 h-6 block mx-auto">
                                            <use href="#visa" />
                                        </svg>
                                    @elseif ($item->transaccion->brand == 'mastercard')
                                        <svg class="w-10 h-6 block mx-auto">
                                            <use href="#mastercard" />
                                        </svg>
                                    @elseif ($item->transaccion->brand == 'paypal')
                                        <svg class="w-10 h-6 block mx-auto">
                                            <use href="#paypal" />
                                        </svg>
                                    @elseif ($item->transaccion->brand == 'unionpay')
                                        <svg class="w-10 h-6 block mx-auto">
                                            <use href="#unionpay" />
                                        </svg>
                                    @elseif ($item->transaccion->brand == 'dinersclub')
                                        <svg class="w-10 h-6 block mx-auto">
                                            <use href="#dinersclub" />
                                        </svg>
                                    @elseif ($item->transaccion->brand == 'amex')
                                        <svg class="w-10 h-6 block mx-auto">
                                            <use href="#amex" />
                                        </svg>
                                    @else
                                        <svg class="w-10 h-6 block mx-auto">
                                            <use href="#default" />
                                        </svg>
                                    @endif

                                    {{ $item->transaccion->brand }}
                                    <br>
                                    {{ $item->transaccion->card }}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <h1 class="text-[10px] p-5 text-colorsubtitleform">NO TIENES ORDENES REGISTRADAS...</h1>
    @endif

    @if ($orders->hasPages())
        <div class="w-full flex justify-center items-center sm:justify-end p-1 sticky -bottom-1 right-0 bg-body">
            {{ $orders->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div wire:key="loadingcompras" wire:loading.flex class="loading-overlay fixed hidden">
        <x-loading-next />
    </div>

    @include('partials.icons-cards')

    <script>
        function select2Estado() {
            this.selectS = $(this.$refs.selects).select2();
            this.selectS.val(this.searchsucursal).trigger("change");
            this.selectS.on("select2:select", (event) => {
                    this.searchsucursal = event.target.value;
                })
                .on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
            this.$watch("searchsucursal", (value) => {
                this.selectS.val(value).trigger("change");
            });
        }

        function select2Pago() {
            this.selectSP = $(this.$refs.selectpago).select2();
            this.selectSP.val(this.pago).trigger("change");
            this.selectSP.on("select2:select", (event) => {
                this.pago = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("pago", (value) => {
                this.selectSP.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectSP.select2().val(this.pago).trigger('change');
            });
        }
    </script>
</div>
