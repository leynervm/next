<div>
    <x-form-card titulo="SERIES DE COMPROBANTES">
        <div class="w-full flex flex-wrap lg:flex-nowrap gap-3">
            <div class="w-full lg:w-80 xl:w-96 lg:flex-shrink-0 relative" x-data="{ savingserie: false }">
                <form wire:submit.prevent="saveserie" class="flex flex-col gap-2">
                    <div class="w-full">
                        <x-label value="Tipo comprobante :" />
                        <div id="parenttypecomprobante_id" class="relative">
                            <x-select class="block w-full" id="typecomprobante_id" wire:model.lazy="typecomprobante_id">
                                <x-slot name="options">
                                    @if (count($typecomprobantes))
                                        @foreach ($typecomprobantes as $item)
                                            <option value="{{ $item->id }}">{{ $item->descripcion }}</option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="typecomprobante_id" />
                    </div>
                    <div class="w-full">
                        <x-label value="Serie comprobante :" />
                        <div id="parentseriecomprobante_id">
                            <x-select class="block w-full" id="seriecomprobante_id"
                                wire:model.lazy="seriecomprobante_id">
                                <x-slot name="options">
                                    @if (count($seriecomprobantes))
                                        @foreach ($seriecomprobantes as $item)
                                            <option value="{{ $item->id }}">{{ $item->serie }}</option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                        </div>
                        <x-jet-input-error for="seriecomprobante_id" />
                    </div>
                    <div class="block">
                        <x-label-check for="default">
                            <x-input wire:model.defer="default" value="1" type="checkbox" id="default" />
                            SELECCIONAR COMO PREDETERMINADO
                        </x-label-check>
                        <x-jet-input-error for="default" />
                        <x-jet-input-error for="sucursal.empresa_id" />
                    </div>
                    <div class="w-full flex justify-end">
                        <x-button type="submit" wire:loading.attr="disabled">
                            REGISTRAR
                        </x-button>
                    </div>
                </form>

                <div x-show="savingserie" class="loading-overlay rounded" wire:loading wire:loading.flex>
                    <x-loading-next />
                </div>
            </div>
            <div class="w-full">
                @if (count($sucursal->seriecomprobantes))
                    <x-table>
                        <x-slot name="header">
                            <tr>
                                <th scope="col" class="p-2 font-medium text-left">
                                    TIPO COMPROBANTE
                                </th>

                                <th scope="col" class="p-2 font-medium text-left">
                                    SERIE</th>

                                <th scope="col" class="p-2 font-medium text-end">
                                    OPCIONES
                                </th>
                            </tr>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($sucursal->seriecomprobantes as $item)
                                <tr>
                                    <td class="p-2 text-xs">
                                        <div class="w-full flex gap-1 items-center">
                                            {{ $item->typecomprobante->descripcion }}
                                            @if ($item->pivot->default)
                                                <span class="inline-block text-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-4 h-4 text-next-500 scale-125" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path fill="currentColor"
                                                            d="M18.9905 19H19M18.9905 19C18.3678 19.6175 17.2393 19.4637 16.4479 19.4637C15.4765 19.4637 15.0087 19.6537 14.3154 20.347C13.7251 20.9374 12.9337 22 12 22C11.0663 22 10.2749 20.9374 9.68457 20.347C8.99128 19.6537 8.52349 19.4637 7.55206 19.4637C6.76068 19.4637 5.63218 19.6175 5.00949 19C4.38181 18.3776 4.53628 17.2444 4.53628 16.4479C4.53628 15.4414 4.31616 14.9786 3.59938 14.2618C2.53314 13.1956 2.00002 12.6624 2 12C2.00001 11.3375 2.53312 10.8044 3.59935 9.73817C4.2392 9.09832 4.53628 8.46428 4.53628 7.55206C4.53628 6.76065 4.38249 5.63214 5 5.00944C5.62243 4.38178 6.7556 4.53626 7.55208 4.53626C8.46427 4.53626 9.09832 4.2392 9.73815 3.59937C10.8044 2.53312 11.3375 2 12 2C12.6625 2 13.1956 2.53312 14.2618 3.59937C14.9015 4.23907 15.5355 4.53626 16.4479 4.53626C17.2393 4.53626 18.3679 4.38247 18.9906 5C19.6182 5.62243 19.4637 6.75559 19.4637 7.55206C19.4637 8.55858 19.6839 9.02137 20.4006 9.73817C21.4669 10.8044 22 11.3375 22 12C22 12.6624 21.4669 13.1956 20.4006 14.2618C19.6838 14.9786 19.4637 15.4414 19.4637 16.4479C19.4637 17.2444 19.6182 18.3776 18.9905 19Z">
                                                        </path>
                                                        <path stroke="#fff" fill="#fff"
                                                            d="M9 12.8929C9 12.8929 10.2 13.5447 10.8 14.5C10.8 14.5 12.6 10.75 15 9.5">
                                                        </path>
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>

                                    </td>
                                    <td class="p-2 text-[10px]">
                                        {{ $item->serie }}
                                    </td>
                                    <td class="p-2">
                                        <div class="flex gap-1 items-center justify-end">
                                            <x-button-delete
                                                wire:click="$emit('sucursales.confirmDelete', {{ $item->pivot->sucursal_id }}, {{ $item }})"
                                                wire:loading.attr="disabled" />
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-table>
                @endif
            </div>
        </div>
    </x-form-card>

    <script>
        document.addEventListener('livewire:load', function() {

            renderselect2();

            $('#typecomprobante_id').on("change", function(e) {
                disableselect2()
                @this.set('typecomprobante_id', e.target.value);
            });

            $('#seriecomprobante_id').on("change", function(e) {
                disableselect2()
                @this.set('seriecomprobante_id', e.target.value);
            });

            document.addEventListener('render-show-seriecomprobante', () => {
                renderselect2();
            });

            function disableselect2() {
                $('#typecomprobante_id, #seriecomprobante_id').attr('disabled', true)
            }

            function renderselect2() {
                $('#typecomprobante_id, #seriecomprobante_id').select2()
                    .on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });
            }

            Livewire.on('sucursales.confirmDelete', (sucursal_id, serie) => {
                console.log(sucursal_id, serie);
                swal.fire({
                    title: 'Eliminar la serie asignada ' + serie.serie,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.delete(sucursal_id, serie.id);
                    }
                })
            });

        })
    </script>
</div>
