<div>
    <x-form-card titulo="SERIES" subtitulo="Administrar series del producto." class="relative">
        <div class="w-full h-full rounded flex flex-wrap lg:flex-nowrap gap-3">
            <div class="w-full lg:w-80 xl:w-96 lg:flex-shrink-0 bg-body p-3 rounded">
                <form wire:submit.prevent="save" class="flex flex-col gap-2" x-data="almacendata">
                    <div class="w-full">
                        <x-label value="Almacén :" />
                        <div id="parentalmacen_id" class="relative" wire:ignore>
                            <x-select class="block w-full" id="almacen_id" x-ref="select" data-placeholder="null">
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
                            REGISTRAR
                        </x-button>
                    </div>
                </form>
            </div>
            @if (count($producto->series))
                <div class="w-full relative bg-body p-3 rounded">
                    <div class="w-full flex items-end gap-1 mb-2">
                        @if (count($producto->almacens) > 1)
                            <div class="relative" x-data="{ open: false }">
                                <button x-on:click="open = !open"
                                    :class="{ 'bg-next-50': open, 'bg-transparent': !open }"
                                    class="border bg-transparent rounde-sm w-full text-xs font-semibold border-next-300 text-next-500 p-2 px-3 focus:ring-0 focus:border-next-400 text-center inline-flex items-center"
                                    type="button">Filtrar almacén
                                    <svg :class="{ 'rotate-180': open }"
                                        class="w-4 h-full ml-2 transform transition duration-150" aria-hidden="true"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7">
                                        </path>
                                    </svg>
                                </button>

                                <div :class="{ 'block': open, 'hidden': !open }" x-show="open"
                                    x-on:click.away="open = !open" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute hidden z-10 w-auto max-w-xs bg-white rounded-lg shadow-md">
                                    <ul class="p-2 space-y-1 text-next-700" aria-labelledby="dropdownCheckboxButton">
                                        @if (count($producto->almacens))
                                            @foreach ($producto->almacens as $item)
                                                <li>
                                                    <div
                                                        class="flex flex-nowrap items-center hover:bg-next-50 rounded-lg p-1 break-keep">
                                                        <input id="searchalmacen_{{ $item->id }}" type="checkbox"
                                                            value="{{ $item->id }}" wire:loading.attr="disabled"
                                                            wire:model="searchseriealmacen" name="searchseriealmacen[]"
                                                            class="w-4 h-4 text-next-600 border-next-300 cursor-pointer rounded focus:ring-0 focus:ring-transparent disabled:opacity-25">
                                                        <label for="searchalmacen_{{ $item->id }}"
                                                            class="pl-2 text-xs font-medium text-next-900 cursor-pointer break-keep">{{ $item->name }}</label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
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
                                    if ($item->status == 1) {
                                        $textclass = 'text-blue-400 line-through';
                                    } elseif ($item->status == 2) {
                                        $textclass = 'text-amber-400 line-through';
                                    } else {
                                        $textclass = 'text-textspancardproduct';
                                    }
                                @endphp
                                <span
                                    class="inline-flex gap-2 items-center justify-between p-1 font-medium rounded-md bg-fondospancardproduct text-textspancardproduct">
                                    <small
                                        class="text-[10px] font-medium {{ $textclass }}">{{ $item->serie }}</small>
                                    <x-button-delete
                                        wire:click="$emit('producto.confirmDeleteSerie',{{ $item }})"
                                        wire:loading.attr="disabled" />
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div wire:loading.flex
            wire:target="save, disponibles, searchseriealmacen, gotoPage, nextPage, previousPage, delete"
            class="loading-overlay rounded hidden">
            <x-loading-next />
        </div>
    </x-form-card>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('almacendata', () => ({
                almacen_id: @entangle('almacen_id').defer,

                init() {
                    this.select2AlmacenAlpine();
                    window.addEventListener('created', () => {
                        this.select2.val(this.almacen_id).trigger("change");
                    });
                },
                select2AlmacenAlpine() {
                    this.select2 = $(this.$refs.select).select2();
                    this.select2.val(this.almacen_id).trigger("change");
                    this.select2.on("select2:select", (event) => {
                        this.almacen_id = event.target.value;
                    }).on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });
                }
            }));
        })

        window.addEventListener('resetfilter', almacens => {
            @this.resetfilter();
            let selectalmacen = document.querySelector('[x-ref="select"]');
            $(selectalmacen).val(null).empty().append('<option value="" selected>SELECCIONAR...</option>');
            almacens.detail.forEach(almacen => {
                let option = new Option(almacen.name, almacen.id, false, false);
                $(selectalmacen).append(option);
            });
            $(selectalmacen).select2().trigger('change');
        })

        document.addEventListener("livewire:load", () => {
            Livewire.on("producto.confirmDeleteSerie", data => {
                swal.fire({
                    title: 'Eliminar serie ' + data.serie,
                    text: "Se eliminará un registro de la base de datos, incluyendo todos los datos relacionados.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.delete(data.id);
                        // Livewire.emitTo('almacen.productos.show-series', 'delete', data.id);
                    }
                })
            });
        })
    </script>
</div>
