<div>
    <div wire:loading.flex class="loading-overlay rounded fixed hidden">
        <x-loading-next />
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
        <div class="shadow-md rounded-xl">
            <table class="w-full min-w-full text-[10px]">
                <tbody class="divide-y text-neutral-700">
                    @foreach ($orders as $item)
                        <tr class="border-b">
                            <td class="flex gap-2 text-left p-3">
                                <span class="flex-shrink block w-6 h-6 text-neutral-500">
                                    @if ($item->isDeposito())
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" fill="none" class="w-full h-full block">
                                            <path
                                                d="M19 9H6.65856C5.65277 9 5.14987 9 5.02472 8.69134C4.89957 8.38268 5.25517 8.01942 5.96637 7.29289L8.21091 5" />
                                            <path
                                                d="M5 15H17.3414C18.3472 15 18.8501 15 18.9753 15.3087C19.1004 15.6173 18.7448 15.9806 18.0336 16.7071L15.7891 19" />
                                        </svg>
                                    @endif
                                </span>
                                <a href="{{ route('orders.payment', $item) }}"
                                    class="inline-block leading-3 text-[10px] uppercase text-linktable">
                                    {{ $item->seriecompleta }}
                                    <br>
                                    {{ formatDate($item->date) }}
                                </a>
                            </td>
                            <td class="text-center p-3 text-xs">
                                {{ number_format($item->total, 2, '.', ', ') }}
                                <p class="font-semibold text-[10px]">{{ $item->moneda->currency }}</p>
                            </td>
                            <td class="text-center p-3">
                                @if ($item->isPagoconfirmado())
                                    <span class="text-green-600 inline-block">
                                        PAGO CONFIRMADO CON ÉXITO
                                    </span>
                                @elseif ($item->isPagado())
                                    <span class="text-green-600 inline-block">PAGADO</span>
                                    <p class="text-orange-600">EN ESPERA DE CONFIRMACIÓN</p>
                                @else
                                    <span class="text-red-600 inline-block font-semibold">PENDIENTE PAGO</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="w-full py-3">
            {{ $orders->onEachSide(0)->links('vendor.pagination.pagination-default') }}
        </div>
    @else
        <h1 class="text-[10px] p-5">NO TIENES ORDENES REGISTRADAS...</h1>
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
