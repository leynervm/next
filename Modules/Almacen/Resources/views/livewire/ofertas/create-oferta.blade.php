<div>
    <x-button-next titulo="Registrar" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Ofertar producto') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full grid grid-cols-1 xs:grid-cols-2 gap-2">
                <div class="w-full xs:max-w-xs mx-auto xs:col-span-2">
                    @if ($producto)
                        @if (count($producto->images))
                            @if ($producto->defaultImage)
                                <div
                                    class="w-full h-60 shadow-md shadow-shadowminicard border rounded-lg border-borderminicard overflow-hidden mb-1 duration-300 relative">
                                    @if ($producto->defaultImage)
                                        <img src="{{ asset('storage/productos/' . $producto->defaultImage->first()->url) }}"
                                            alt="" class="w-full h-full object-scale-down">
                                    @else
                                        <img src="{{ asset('storage/productos/' . $producto->images->first()->url) }}"
                                            alt="" class="w-full h-full object-scale-down">
                                    @endif

                                </div>
                            @endif
                        @endif
                    @else
                        <div
                            class="w-full flex items-center justify-center h-60 shadow-md shadow-shadowminicard border rounded-lg border-borderminicard mb-1">
                            <svg class="text-neutral-500 w-24 h-24" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path
                                    d="M13 3.00231C12.5299 3 12.0307 3 11.5 3C7.02166 3 4.78249 3 3.39124 4.39124C2 5.78249 2 8.02166 2 12.5C2 16.9783 2 19.2175 3.39124 20.6088C4.78249 22 7.02166 22 11.5 22C15.9783 22 18.2175 22 19.6088 20.6088C20.9472 19.2703 20.998 17.147 20.9999 13" />
                                <path
                                    d="M2 14.1354C2.61902 14.0455 3.24484 14.0011 3.87171 14.0027C6.52365 13.9466 9.11064 14.7729 11.1711 16.3342C13.082 17.7821 14.4247 19.7749 15 22" />
                                <path
                                    d="M21 16.8962C19.8246 16.3009 18.6088 15.9988 17.3862 16.0001C15.5345 15.9928 13.7015 16.6733 12 18" />
                                <path
                                    d="M17 4.5C17.4915 3.9943 18.7998 2 19.5 2M22 4.5C21.5085 3.9943 20.2002 2 19.5 2M19.5 2V10" />
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="xs:col-span-2" x-data="{ producto_id: @entangle('producto_id') }" x-init="select2ProductoAlpine" wire:ignore>
                    <x-label value="Producto :" />
                    <div class="relative">
                        <x-select class="block w-full select2" x-ref="select" id="producto_id" data-dropdown-parent=""
                            data-minimum-results-for-search="3">
                            @if (count($productos))
                                <x-slot name="options">
                                    @foreach ($productos as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            @endif
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="producto_id" />
                </div>

                <div class="">
                    <x-label value="Almacén :" />
                    <x-select wire:model.defer="almacen_id" class="block w-full" id="almacen_id"
                        data-dropdown-parent="">
                        @if ($producto)
                            <x-slot name="options">
                                @foreach ($producto->almacens as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </x-slot>
                        @endif
                    </x-select>
                    <x-jet-input-error for="almacen_id" />
                </div>

                <div class="w-full">
                    <x-label value="Descuento (%) :" />
                    <x-input class="block w-full" wire:model.defer="descuento" type="number" min="0"
                        step="0.1" />
                    <x-jet-input-error for="descuento" />
                </div>

                <div class="w-full">
                    <x-label value="Fecha inicio :" />
                    <x-input class="block w-full" wire:model.defer="datestart" type="date" />
                    <x-jet-input-error for="datestart" />
                </div>

                <div class="w-full">
                    <x-label value="Fecha finalización :" />
                    <x-input class="block w-full" wire:model.defer="dateexpire" type="date" />
                    <x-jet-input-error for="dateexpire" />
                </div>

                <div class="w-full">
                    <x-label value="Máximo stock :" />
                    <x-input class="block w-full" wire:model.defer="limit" type="number" min="0" step="1"
                        :disabled="$max == 1 ? true : false" />
                    <x-jet-input-error for="limit" />
                </div>

                <div class="xs:col-span-2">
                    <x-label-check for="max">
                        <x-input wire:model.lazy="max" name="max" value="1" type="checkbox" id="max" />
                        SELECCIONAR STOCK MÁXIMO DISPONIBLE
                    </x-label-check>
                    <x-jet-input-error for="max" />
                </div>

                <div class="w-full xs:col-span-2 flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        function select2ProductoAlpine() {
            this.select2 = $(this.$refs.select).select2();
            this.select2.val(this.producto_id).trigger("change");
            this.select2.on("select2:select", (event) => {
                this.select2.attr('disabled', true);
                this.producto_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch('producto_id', (value) => {
                this.select2.val(value).trigger("change");
            });
        }
    </script>
</div>
