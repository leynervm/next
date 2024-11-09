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
                                <div
                                    class="inline-flex gap-2 items-center justify-between p-1 px-2 rounded-lg border border-borderminicard {{ $item->almacen->trashed() || $item->isDisponible() == false ? 'opacity-45' : '' }}">
                                    <span class="inline-block flex-shrink-0 w-6 h-6">
                                        @if ($item->isSalida())
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                class="w-full h-full text-neutral-400" stroke="currentColor"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5">
                                                <circle class="cls-1" cx="12" cy="12" r="10.5" />
                                                <line class="cls-1" x1="19.64" y1="4.36" x2="4.36"
                                                    y2="19.64" />
                                            </svg>
                                        @elseif ($item->isReservada())
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                                stroke-linejoin="round" class="w-full h-full text-neutral-400">
                                                <path
                                                    d="M12 7V12H15M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                class="w-full h-full text-green-500" stroke="currentColor"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5">
                                                <polyline id="primary" points="21 5 12 14 8 10" />
                                                <path id="primary-2" data-name="primary"
                                                    d="M20.94,11A8.26,8.26,0,0,1,21,12a9,9,0,1,1-9-9,8.83,8.83,0,0,1,4,1" />
                                            </svg>
                                        @endif
                                    </span>
                                    <div class="flex-1">
                                        <p class="font-medium text-[10px] text-colorlabel">
                                            {{ $item->serie }}</p>
                                        <p class="text-colorsubtitleform text-[10px]">
                                            {{ $item->almacen->name }}</p>
                                    </div>
                                    @can('admin.almacen.productos.series.edit')
                                        @if ($item->isDisponible())
                                            <x-button-delete class="flex-shrink-0"
                                                onclick="confirmDeleteSerie({{ $item }})"
                                                wire:loading.attr="disabled" />
                                        @endif
                                    @endcan
                                </div>
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
