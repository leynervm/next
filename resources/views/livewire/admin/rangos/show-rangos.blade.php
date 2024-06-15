<div x-data="rangos">
    <div wire:loading.flex class="loading-overlay rounded hidden fixed">
        <x-loading-next />
    </div>

    @can('admin.administracion.rangos.import')
        <div class="w-full flex flex-col gap-2 items-start">
            <form wire:submit.prevent="import">
                <label class="">
                    <x-icon-file-upload type="excel"
                        class="w-24 h-24 p-3 {{ $file ? 'border-fondobutton text-colorsubtitleform shadow-fondobutton animate-pulse' : '' }}  text-colorlabel cursor-pointer hover:border-fondobutton hover:text-fondobutton hover:shadow-fondobutton transition ease-in-out duration-150">
                    </x-icon-file-upload>
                    <input type="file" class="hidden" wire:model="file" id="{{ $identificador }}" accept=".xlsx, .csv" />

                    <p class="text-[9px] mt-2 text-center leading-3 font-semibold tracking-widest">
                        @if ($file)
                            ARCHIVO LISTO
                        @else
                            IMPORTAR LISTA RANGOS
                        @endif
                    </p>
                </label>

                @if ($file)
                    <div class="w-full flex gap-2 mt-1">
                        <x-button wire:loading.attr="disabled" type="submit">
                            IMPORTAR
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v4" />
                                <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                <path d="M2 15h10" />
                                <path d="m9 18 3-3-3-3" />
                            </svg>
                        </x-button>
                        <x-button type="reset" class="inline-flex" wire:loading.attr="disabled" wire:click="resetFile">
                            LIMPIAR
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M3 6h18" />
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                <line x1="10" x2="10" y1="11" y2="17" />
                                <line x1="14" x2="14" y1="11" y2="17" />
                            </svg>
                        </x-button>
                    </div>
                @endif
            </form>
            <x-jet-input-error for="file" />
        </div>
    @endcan

    <div class="block w-full mt-1">
        @if ($rangos->hasPages())
            <div class="w-full py-2">
                {{ $rangos->onEachSide(0)->links('livewire::pagination-default') }}
            </div>
        @endif

        @if (count($rangos) > 0)
            <div class="w-full mb-1" style="display: none;" x-show="selectedrangos.length > 0">
                <x-button-secondary onclick="confirmDeleteAll()" wire:loading.attr="disabled">
                    {{ __('ELIMINAR SELECCIONADOS') }} <span :class="selectedrangos.length < 10 ? 'px-1' : ''"
                        class="bg-white p-0.5 text-[9px] rounded-full !tracking-normal font-semibold text-red-500"
                        x-text="selectedrangos.length"></span>
                </x-button-secondary>
            </div>
            <x-table>
                <x-slot name="header">
                    <tr>
                        <th scope="col" class="p-2 font-medium text-center">
                            <label for="checkall"
                                class="text-xs flex flex-col justify-center items-center gap-1 leading-3">
                                <x-input wire:model.lazy="checkall" class="cursor-pointer p-2" name="checkall"
                                    type="checkbox" id="checkall" wire:loading.attr="disabled" />
                                TODO
                            </label>
                        </th>
                        <th scope="col" class="p-2 font-medium">
                            <button class="flex items-center gap-x-3 focus:outline-none">
                                <span class="leading-3">PRECIO <br> MÍNIMO</span>
                            </button>
                        </th>
                        <th scope="col" class="p-2 font-medium">
                            <button class="flex items-center gap-x-3 focus:outline-none">
                                <span class="leading-3">PRECIO <br> MÁXIMO</span>
                            </button>
                        </th>
                        <th scope="col" class="p-2 font-medium text-center">
                            INCREMENTO P.C
                        </th>
                        {{-- <th class="" colspan="{{ count($pricetypes) }}">
                        </th> --}}

                        @if (count($pricetypes) > 0)
                            @foreach ($pricetypes as $item)
                                <th scope="col" class="p-2 font-medium text-center">
                                    <div class="flex justify-center items-center gap-1">
                                        <span>{{ $item->name }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="w-4 h-4 block" stroke-width="0.5" stroke="currentColor"
                                            fill-rule="evenodd" clip-rule="evenodd">
                                            <path
                                                d="M19 11.1554L17.5858 12.5L12 7.1892L6.4142 12.5L5 11.1554L12.0001 4.5L19 11.1554Z" />
                                            <path
                                                d="M19 18.1554L17.5858 19.5L12 14.1892L6.4142 19.5L5 18.1554L12.0001 11.5L19 18.1554Z" />
                                        </svg>
                                    </div>
                                </th>
                            @endforeach
                        @endif

                        @canany(['admin.administracion.rangos.edit', 'admin.administracion.rangos.delete'])
                            <th scope="col" class="p-2 text-end">OPCIONES</th>
                        @endcanany
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @foreach ($rangos as $item)
                        <tr>
                            <td class="p-1 text-xs text-center">
                                <x-input type="checkbox" name="selectedrangos" class="p-2 cursor-pointer"
                                    x-model="selectedrangos" value="{{ $item->id }}" wire:loading.attr="disabled" />
                            </td>
                            <td class="p-1 text-xs">
                                {{ $item->desde }}
                            </td>
                            <td class="p-1 text-xs">
                                {{ $item->hasta }}
                            </td>
                            <td class="p-1 text-xs">
                                <div class="flex gap-1 text-green-500 items-center justify-center">
                                    <span>{{ formatDecimalOrInteger($item->incremento) }}%</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-4 h-4 block" stroke-width="0.5" stroke="currentColor"
                                        fill-rule="evenodd" clip-rule="evenodd">
                                        <path
                                            d="M19 11.1554L17.5858 12.5L12 7.1892L6.4142 12.5L5 11.1554L12.0001 4.5L19 11.1554Z" />
                                        <path
                                            d="M19 18.1554L17.5858 19.5L12 14.1892L6.4142 19.5L5 18.1554L12.0001 11.5L19 18.1554Z" />
                                    </svg>
                                </div>
                            </td>


                            @foreach ($item->pricetypes as $lista)
                                <td class="p-1 text-center" x-data="{ percent: '{{ $lista->pivot->ganancia }}' }">
                                    <p class="font-semibold text-[10px] text-center">{{ $lista->name }}</p>
                                    @can('admin.administracion.rangos.edit')
                                        <x-input class="inline-block text-center" :value="$lista->pivot->ganancia" type="number"
                                            step="0.01" min="0" x-model.number="percent"
                                            x-mask:dynamic="$money($input, '.', ' ')"
                                            onkeypress="return validarDecimal(event, 9)" :key="$item->id . $lista->id"
                                            id="{{ $item->id . $lista->id }}" wire:loading.attr="disabled"
                                            @keydown.enter="$wire.updatepricerango('{{ $item->id }}', '{{ $lista->id }}', percent)"
                                            x-bind:class="{ 'border-red-500': percent == '' }" />
                                    @endcan

                                    @cannot('admin.administracion.rangos.edit')
                                        <x-disabled-text :text="$lista->pivot->ganancia" class="inline-block md:px-5 border-0" />
                                    @endcannot
                                </td>
                            @endforeach

                            @canany(['admin.administracion.rangos.edit', 'admin.administracion.rangos.delete'])
                                <td class="p-1">
                                    <div class="flex gap-2 items-center justify-end">
                                        @can('admin.administracion.rangos.edit')
                                            <x-button-edit wire:click="edit({{ $item->id }})"
                                                wire:loading.attr="disabled" />
                                        @endcan

                                        @can('admin.administracion.rangos.delete')
                                            <x-button-delete wire:loading.attr="disabled"
                                                onclick="confirmDeleteRango({{ $item }})"
                                                wire:key="deleterango_{{ $item->id }}" />
                                        @endcan
                                    </div>
                                </td>
                            @endcanany
                        </tr>
                    @endforeach
                </x-slot>
            </x-table>
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar rango precio') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="w-full flex flex-wrap sm:flex-nowrap gap-2">
                    <div class="w-full sm:w-1/2">
                        <x-label value="Precio desde :" />
                        <x-input class="block w-full" wire:model.defer="rango.desde" type="number" step="0.01"
                            min="0" onkeypress="return validarDecimal(event, 9)" />
                        <x-jet-input-error for="rango.desde" />
                    </div>
                    <div class="w-full sm:w-1/2">
                        <x-label value="Precio hasta :" />
                        <x-input class="block w-full" wire:model.defer="rango.hasta" type="number" step="0.01"
                            min="0" onkeypress="return validarDecimal(event, 9)" />
                        <x-jet-input-error for="rango.hasta" />
                    </div>
                </div>

                <div class="md:w-1/2 mt-2">
                    <x-label value="Incremento precio :" />
                    <x-input class="block w-full" wire:model.defer="rango.incremento" type="number" step="0.01"
                        min="0" onkeypress="return validarDecimal(event, 9)" />
                    <x-jet-input-error for="rango.incremento" />
                </div>


                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('rangos', () => ({
                selectedrangos: @entangle('selectedrangos').defer,
            }))
        })

        function confirmDeleteAll() {
            swal.fire({
                title: 'Eliminar rangos de precios seleccionados ? ',
                text: "Se eliminarán múltiples registros de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deleteall();
                }
            })
        }

        function confirmDeleteRango(rango) {
            swal.fire({
                title: 'Eliminar rango de precio seleccionado, desde: ' + rango.desde + ' hasta: ' + rango.hasta,
                text: "Se eliminará un registro de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(data.id);
                }
            })
        }
    </script>
</div>
