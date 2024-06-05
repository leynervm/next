<div>
    <div wire:loading.flex wire:target="reload,save" class="fixed loading-overlay rounded hidden overflow-hidden z-[99999]">
        <x-loading-next />
    </div>

    <h1 class="text-md font-semibold text-colorsubtitleform px-5 pt-5">
        RESUMEN</h1>

    <div class="w-full overflow-x-auto md:rounded-lg">
        @if (count($order->tvitems) > 0)
            <table class="w-full min-w-full text-[10px]">
                <thead>
                    <tr class="text-[10px] text-colorsubtitleform">
                        <th></th>
                        <th>PRECIO</th>
                        <th>CANT.</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($order->tvitems as $item)
                        @php
                            $image = $item->producto->getImageURL();
                        @endphp

                        <tr class="text-colorsubtitleform">
                            <td class="flex gap-2 text-left p-2">
                                <div class="flex-shrink w-14 h-14 rounded-xl shadow overflow-hidden">
                                    @if ($image)
                                        <img src="{{ $image }}" alt=""
                                            class="w-full h-full object-cover rounded-xl aspect-square overflow-hidden">
                                    @else
                                        <x-icon-file-upload class="!w-full !h-full !m-0 text-neutral-500 !border-0"
                                            type="unknown" />
                                    @endif
                                </div>
                                <div class="w-full flex-1">
                                    <a href="{{ route('productos.show', $item->producto) }}"
                                        class="w-full  leading-3 text-[10px] text-linktable">
                                        {{ $item->producto->name }}</a>

                                    @if ($order->isPagoconfirmado())
                                        @if ($item->kardex)
                                            <div>
                                                <x-span-text type="green" text="STOCK ACTUALIZADO" />
                                            </div>
                                        @else
                                            <x-button wire:click="descontarstock({{ $item->id }})"
                                                wire:loading.attr="disabled">DESCONTAR STOCK</x-button>
                                        @endif
                                    @else
                                        <p class="text-colorerror text-[10px] leading-3">
                                            CONFIRMAR PAGO PARA DESCONTAR STOCK</p>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center p-2">
                                {{ number_format($item->price, 2, '.', ', ') }}
                                {{ $order->moneda->currency }}
                                </h1>
                            </td>
                            <td class="text-center p-2">
                                {{ formatDecimalOrInteger($item->cantidad) }}
                                {{ $item->producto->unit->name }}
                            </td>
                            <td class="text-center p-2">
                                {{-- {{ $order->moneda->simbolo }} --}}
                                {{ number_format($item->total, 2, '.', ', ') }}
                                {{ $order->moneda->currency }}
                                </h1>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h1 class="text-xs p-3 font-medium text-neutral-500">
                NO EXISTEN PRODUCTOS AGREGADOS EN LA ORDEN...</h1>
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar stock producto') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
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

                {{ print_r($errors->all()) }}

                <div class="w-full flex pt-4 gap-2 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
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
