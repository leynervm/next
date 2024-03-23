<div>
    <x-form-card titulo="SERIES DE COMPROBANTES">
        <div class="w-full flex flex-wrap lg:flex-nowrap gap-3">
            @can('admin.administracion.sucursales.seriecomprobantes.edit')
                <div class="w-full lg:w-80 xl:w-96 lg:flex-shrink-0 relative">
                    <form wire:submit.prevent="saveserie" class="flex flex-col gap-2">
                        <div class="w-full">
                            <x-label value="Tipo comprobante :" />
                            <div id="parenttypecomprobante_id" class="relative" x-data="{ typecomprobante_id: @entangle('typecomprobante_id').defer }"
                                x-init="select2Comprobante">
                                <x-select class="block w-full" id="typecomprobante_id" x-ref="comprobantesuc">
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
                            <x-label value="Serie :" />
                            <x-input class="block w-full" wire:model.defer="serie" maxlength="4" />
                            <x-jet-input-error for="serie" />
                        </div>
                        <div class="w-full mt-2">
                            <x-label value="Contador :" />
                            <x-input class="block w-full" wire:model.defer="contador" type="number" min="0"
                                step="1" />
                            <x-jet-input-error for="contador" />
                        </div>
                        <div class="w-full flex justify-end">
                            <x-button type="submit" wire:loading.attr="disabled">
                                REGISTRAR
                            </x-button>
                        </div>
                    </form>

                    <div class="loading-overlay rounded hidden" wire:loading>
                        <x-loading-next />
                    </div>
                </div>
            @endcan

            <div class="w-full">
                @if (count($seriecomprobantes) > 0)
                    <x-table>
                        <x-slot name="header">
                            <tr>
                                <th scope="col" class="p-2 font-medium text-left">
                                    TIPO COMPROBANTE</th>
                                <th scope="col" class="p-2 font-medium text-center">
                                    SERIE</th>
                                <th scope="col" class="p-2 font-medium text-center">
                                    CONTADOR</th>
                                <th scope="col" class="p-2 font-medium text-center">
                                    PREDETERMINADO</th>
                                @can('admin.administracion.sucursales.seriecomprobantes.edit')
                                    <th scope="col" class="p-2 font-medium text-end">
                                        OPCIONES</th>
                                @endcan
                            </tr>
                        </x-slot>
                        <x-slot name="body">
                            @foreach ($seriecomprobantes as $item)
                                <tr>
                                    <td class="p-2 text-xs">
                                        {{ $item->typecomprobante->descripcion }}
                                    </td>
                                    <td class="p-2 text-center">
                                        {{ $item->serie }}
                                    </td>
                                    <td class="p-2 text-center">
                                        @can('admin.administracion.sucursales.seriecomprobantes.edit')
                                            <x-input value="{{ $item->contador }}" type="number" step="1"
                                                min="0" class="w-auto inline-block text-center max-w-[100px]"
                                                onkeypress="return validarNumero(event)"
                                                wire:keydown.enter="updatecontador({{ $item->id }}, $event.target.value)" />
                                        @endcan

                                        @cannot('admin.administracion.sucursales.seriecomprobantes.edit')
                                            {{ $item->contador }}
                                        @endcannot

                                    </td>


                                    <td class="p-2 text-center">
                                        @if ($item->isDefault())
                                            <x-icon-default class="inline-block" />
                                        @elseif (!$item->trashed() && !$item->isDefault() && !in_array($item->typecomprobante->code, ['09', '07']))
                                            @can('admin.administracion.sucursales.seriecomprobantes.edit')
                                                <x-icon-default wire:click="setcomprobantedefault({{ $item->id }})"
                                                    wire:key="default_{{ $item->id }}"
                                                    class="!text-gray-400 inline-block cursor-pointer hover:!text-next-500" />
                                            @endcan
                                        @endif
                                    </td>
                                    @can('admin.administracion.sucursales.seriecomprobantes.edit')
                                        <td class="p-2">
                                            <div class="flex gap-2 items-center justify-end">
                                                @if ($item->trashed())
                                                    <button onclick="restoreserie({{ $item }})"
                                                        wire:loading.attr="disabled" class="inline-block">
                                                        <svg class="w-4 h-4 scale-125 inline-block" viewBox="0 0 24 24"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path fill="currentColor"
                                                                d="M11 12C11 13.6569 9.65685 15 8 15C6.34315 15 5 13.6569 5 12C5 10.3431 6.34315 9 8 9C9.65685 9 11 10.3431 11 12Z" />
                                                            <path
                                                                d="M16 6H8C4.68629 6 2 8.68629 2 12C2 15.3137 4.68629 18 8 18H16C19.3137 18 22 15.3137 22 12C22 8.68629 19.3137 6 16 6Z" />
                                                        </svg>
                                                    </button>
                                                @else
                                                    <x-button-delete onclick="confirmDelete({{ $item }})"
                                                        wire:loading.attr="disabled"
                                                        wire:key="deleteseriecs_{{ $item->id }}" />
                                                @endif
                                            </div>
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </x-slot>
                    </x-table>
                @endif
            </div>
        </div>
    </x-form-card>

    <script>
        function select2Comprobante() {
            this.selectTP = $(this.$refs.comprobantesuc).select2();
            this.selectTP.val(this.typecomprobante_id).trigger("change");
            this.selectTP.on("select2:select", (event) => {
                this.typecomprobante_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("typecomprobante_id", (value) => {
                this.selectTP.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectTP.select2().val(this.typecomprobante_id).trigger('change');
            });
        }

        function select2Seriecomprobante() {
            this.selectSTP = $(this.$refs.seriecomprobante).select2();
            this.selectSTP.val(this.seriecomprobante_id).trigger("change");
            this.selectSTP.on("select2:select", (event) => {
                this.seriecomprobante_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("seriecomprobante_id", (value) => {
                this.selectSTP.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectSTP.select2().val(this.seriecomprobante_id).trigger('change');
            });
        }

        function confirmDelete(seriecomprobante) {
            swal.fire({
                title: 'Eliminar serie ' + seriecomprobante.serie,
                text: "Se eliminará un registro de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(seriecomprobante.id);
                }
            })
        }

        function restoreserie(seriecomprobante) {
            swal.fire({
                title: 'Desea habilitar serie ' + seriecomprobante.serie + ' ?',
                text: "Se actualizará un registro de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.restoreserie(seriecomprobante.id);
                }
            })
        }
    </script>
</div>
