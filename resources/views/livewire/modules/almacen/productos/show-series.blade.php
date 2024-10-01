<div>
    <x-form-card titulo="SERIES" class="relative">
        <div class="w-full h-full rounded flex flex-wrap lg:flex-nowrap gap-3">
            @can('admin.almacen.productos.series.edit')
                <div class="w-full lg:w-80 xl:w-96 lg:flex-shrink-0">
                    <form wire:submit.prevent="save" class="flex flex-col gap-2">
                        <div class="w-full">
                            <x-label value="Almacén :" />
                            <div id="parentalmacenprod" class="relative" x-data="{ almacenserie_id: @entangle('almacen_id').defer }" x-init="selectAlmacenSerie">
                                <x-select class="block w-full" id="almacenprod" x-ref="selectaserie"
                                    data-placeholder="null">
                                    <x-slot name="options">
                                        @if (count($producto->almacens))
                                            @foreach ($producto->almacens as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </x-slot>
                                </x-select>
                                <x-icon-select />
                            </div>
                            <x-jet-input-error for="almacen_id" />
                        </div>
                        <div class="w-full">
                            <x-label value="Serie :" />
                            <x-input class="block w-full" placeholder="Ingresar serie..." wire:model.defer="serie" />
                            <x-jet-input-error for="serie" />
                        </div>
                        <div class="w-full mt-3 flex justify-end">
                            <x-button type="submit" wire:loading.atrr="disabled">
                                {{ __('Save') }}</x-button>
                        </div>
                    </form>
                </div>
            @endcan
            @if (count($producto->series))
                <div class="w-full flex-1 relative bg-body p-3 rounded">
                    <div class="w-full flex flex-wrap items-end gap-1 mb-2">
                        @if (count($producto->almacens) > 1)
                            <div id="parentalsearchalmacen_id" class="relative w-full max-w-xs" x-data="{ searchalmacen_id: @entangle('searchseriealmacen').defer }"
                                x-init="searchAlmacenSerie">
                                <x-select class="block w-full" id="alsearchalmacen_id" x-ref="selectsearchalmacen"
                                    data-placeholder="null">
                                    <x-slot name="options">
                                        @foreach ($producto->almacens as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </x-slot>
                                </x-select>
                                <x-icon-select />
                            </div>
                        @endif
                        <x-label-check for="disponibles">
                            <x-input wire:model.lazy="disponibles" name="disponibles" value="0" type="checkbox"
                                id="disponibles" />
                            MOSTRAR SOLO DISPONIBLES
                        </x-label-check>
                    </div>
                    @if (count($seriesalmacen))
                        @if ($seriesalmacen->hasPages())
                            <div class="w-full pb-2">
                                {{ $seriesalmacen->onEachSide(0)->links('livewire::pagination-default') }}
                            </div>
                        @endif
                        <div class="w-full flex flex-wrap gap-1">
                            @foreach ($seriesalmacen as $item)
                                @php
                                    if ($item->isReservada()) {
                                        $textclass = 'text-blue-400 line-through';
                                    } elseif ($item->isSalida()) {
                                        $textclass = 'text-colorerror line-through';
                                    } else {
                                        $textclass = 'text-textspancardproduct';
                                    }
                                @endphp
                                <span
                                    class="inline-flex gap-1 items-center justify-between p-1 font-medium rounded-md bg-fondospancardproduct text-textspancardproduct">
                                    <small
                                        class="text-[10px] font-medium {{ $textclass }}">{{ $item->serie }}</small>
                                    @can('admin.almacen.productos.series.edit')
                                        <x-button-delete onclick="confirmDeleteSerie({{ $item }})"
                                            wire:loading.attr="disabled" />
                                    @endcan
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div wire:loading.flex
            wire:target="save, disponibles, searchseriealmacen, gotoPage, nextPage, previousPage, delete"
            class="loading-overlay rounded fixed hidden">
            <x-loading-next />
        </div>
    </x-form-card>

    <script>
        function selectAlmacenSerie() {
            this.selectAS = $(this.$refs.selectaserie).select2();
            this.selectAS.val(this.almacenserie_id).trigger("change");
            this.selectAS.on("select2:select", (event) => {
                this.almacenserie_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("almacenserie_id", (value) => {
                this.selectAS.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectAS.select2().val(this.almacenserie_id).trigger('change');
            });
        }

        function searchAlmacenSerie() {
            this.selectSSA = $(this.$refs.selectsearchalmacen).select2();
            this.selectSSA.val(this.searchalmacen_id).trigger("change");
            this.selectSSA.on("select2:select", (event) => {
                this.searchalmacen_id = event.target.value;
                this.$wire.set('searchseriealmacen', event.target.value).then(result => {
                    // console.log('completed succesfull');
                });
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchalmacen_id", (value) => {
                this.selectSSA.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectSSA.select2().val(this.searchalmacen_id).trigger('change');
            });
        }

        window.addEventListener('resetfilter', almacens => {
            @this.resetfilter();
        })

        function confirmDeleteSerie(serie) {
            swal.fire({
                title: 'Eliminar serie ' + serie.serie,
                text: "Se eliminará un registro de la base de datos, incluyendo todos los datos relacionados.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(serie.id);
                }
            })
        }
    </script>
</div>
