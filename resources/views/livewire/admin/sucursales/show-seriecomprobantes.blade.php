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
                        <div id="parentseriecomprobante_id" class="relative">
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
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="seriecomprobante_id" />
                    </div>
                    <div class="block">
                        <x-label-check for="defaultserie">
                            <x-input wire:model.defer="default" value="1" type="checkbox" id="defaultserie" />
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
                @if (count($sucursalcomprobantes))
                    <x-table>
                        <x-slot name="header">
                            <tr>
                                <th scope="col" class="p-2 font-medium text-left">
                                    TIPO COMPROBANTE
                                </th>

                                <th scope="col" class="p-2 font-medium text-center">
                                    SERIE</th>

                                <th scope="col" class="p-2 font-medium text-center">
                                    CONTADOR</th>

                                <th scope="col" class="p-2 font-medium text-center">
                                    PREDETERMINADO</th>

                                <th scope="col" class="p-2 font-medium text-end">
                                    OPCIONES
                                </th>
                            </tr>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($sucursalcomprobantes as $item)
                                <tr>
                                    <td class="p-2 text-xs">
                                        <div class="w-full flex gap-1 items-center">
                                            @if ($item->pivot->default)
                                                <x-icon-default class="inline-block" />
                                            @endif
                                            {{ $item->typecomprobante->descripcion }}
                                        </div>
                                    </td>
                                    <td class="p-2 text-center">
                                        {{ $item->serie }}
                                    </td>
                                    <td class="p-2 text-center">
                                        {{ $item->contador }}
                                    </td>
                                    <td class="p-2 text-center">
                                        @if ($item->pivot->default == '0' && !in_array($item->typecomprobante->code, ['09', '07']))
                                            <x-icon-default wire:click="setcomprobantedefault({{ $item->id }})"
                                                class="!text-gray-400 inline-block cursor-pointer hover:!text-next-500" />
                                        @endif
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
