<div class="w-full overflow-x-auto {{-- rounded-xl border border-borderminicard --}}">
    <div wire:loading.flex wire:target="reload,save" class="fixed loading-overlay hidden z-[99999]">
        <x-loading-next />
    </div>

    <table class="w-full min-w-full text-[10px] md:text-xs">
        <tbody class="divide-y">
            @foreach ($order->tvitems as $item)
                @php
                    $image = $item->producto->getImageURL();
                @endphp

                <tr class="text-colorlabel">
                    <td class="text-left py-2 align-middle">
                        <div class="flex items-center gap-2">
                            <div class="flex-shrink-0 w-16 h-16 xl:w-24 xl:h-24 rounded overflow-hidden">
                                @if ($image)
                                    <img src="{{ $image }}" alt=""
                                        class="w-full h-full object-scale-down rounded aspect-square overflow-hidden">
                                @else
                                    <x-icon-file-upload class="!w-full !h-full !m-0 text-colorsubtitleform !border-0"
                                        type="unknown" />
                                @endif
                            </div>
                            <div
                                class="w-full flex-1 sm:flex justify-between gap-3 items-center text-colorsubtitleform">
                                <div class="w-full sm:flex-1">
                                    <a href="{{ route('productos.show', $item->producto) }}"
                                        class="w-full text-xs">{{ $item->producto->name }}</a>
                                    @if ($item->kardex)
                                        <div>
                                            <x-span-text type="green" text="STOCK ACTUALIZADO" />
                                        </div>
                                    @else
                                        @can('admin.marketplace.orders.confirmstock')
                                            <x-button wire:click="descontarstock({{ $item->id }})"
                                                wire:loading.attr="disabled">DESCONTAR STOCK</x-button>
                                        @endcan
                                    @endif
                                </div>

                                <div class="flex items-end sm:items-center sm:w-60 sm:flex-shrink-0 ">
                                    <span class="text-left p-2 text-xs sm:text-end font-semibold whitespace-nowrap">
                                        x{{ formatDecimalOrInteger($item->cantidad) }}
                                        {{ $item->producto->unit->name }}
                                    </span>
                                    <span
                                        class="p-2 font-semibold text-lg flex-1 text-end text-colorlabel whitespace-nowrap">
                                        {{ $order->moneda->simbolo }}
                                        {{ number_format($item->total, 2, '.', ', ') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar stock producto') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">
                <div class="w-full">
                    <x-label value="Almacén :" />
                    <div class="relative" x-data="{ almacen_id: @entangle('almacen_id').defer }" x-init="selectAlmacen">
                        <x-select class="block w-full relative" x-ref="selectalmacen" id="almacenmark_id"
                            data-dropdown-parent="null" data-placeholder="null">
                            <x-slot name="options">
                                @if (count($almacens) > 0)
                                    @foreach ($almacens as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="almacen_id" />
                </div>

                {{-- {{ print_r($errors->all()) }} --}}

                <div class="w-full flex pt-4 gap-2 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        function selectAlmacen() {
            this.selectAP = $(this.$refs.selectalmacen).select2();
            this.selectAP.val(this.almacen_id).trigger("change");
            this.selectAP.on("select2:select", (event) => {
                this.almacen_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("almacen_id", (value) => {
                this.selectAP.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectAP.select2().val(this.almacen_id).trigger('change');
            });
        }
    </script>
</div>
