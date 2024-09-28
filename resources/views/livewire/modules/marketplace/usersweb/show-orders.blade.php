<div>
    <div wire:loading.flex class="loading-overlay rounded fixed hidden">
        <x-loading-next />
    </div>

    <div class="w-full bg-fondominicard rounded-xl border border-borderminicard">
        <h1 class="text-xl font-semibold text-colorlabel p-3">
            MIS COMPRAS</h1>
    </div>

    <div class="flex flex-wrap gap-2 py-3">
        <div class="w-full xs:max-w-xs">
            <x-label value="Estado pago :" />
            <div class="relative" x-data="{ pago: @entangle('pago') }" x-init="select2Pago" id="parentpago">
                <x-select class="block w-full" x-model="pago" x-ref="selectpago" id="pago" data-placeholder="null">
                    @if (count(getStatusPayWeb()) > 0)
                        <x-slot name="options">
                            @foreach (getStatusPayWeb() as $item)
                                <option value="{{ $item->value }}">{{ str_replace('_', ' ', $item->name) }}</option>
                            @endforeach
                        </x-slot>
                    @endif
                </x-select>
                <x-icon-select />
            </div>
        </div>

        <div class="w-full xs:max-w-[150px]">
            <x-label value="Fecha :" />
            <x-input type="date" wire:model.lazy="date" class="w-full" />
        </div>

        <div class="w-full xs:max-w-[150px]">
            <x-label value="Hasta :" />
            <x-input type="date" wire:model.lazy="dateto" class="w-full" />
        </div>
    </div>

    @if ($orders->hasPages())
        <div class="pt-3 pb-1">
            {{ $orders->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

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
                                    {{ formatDate($item->date, "DD MMM Y hh:mm A") }}
                                </a>
                            </td>
                            <td class="text-center p-3 xl:py-5 text-xs">
                                {{ number_format($item->total, 2, '.', ', ') }}
                                <p class="font-semibold text-[10px]">{{ $item->moneda->currency }}</p>
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
                                    {{ $item->transaccion->brand }}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="w-full py-3">
            {{ $orders->onEachSide(0)->links('vendor.pagination.pagination-default') }}
        </div>
    @else
        <h1 class="text-[10px] p-5 text-colorsubtitleform">NO TIENES ORDENES REGISTRADAS...</h1>
    @endif


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
