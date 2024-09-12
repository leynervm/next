<div x-data="rangos">
    <div wire:loading.flex class="loading-overlay hidden fixed">
        <x-loading-next />
    </div>

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
                            @if (count($rangos) > 0)
                                <label for="checkall"
                                    class="text-xs flex flex-col justify-center items-center gap-1 leading-3">
                                    <x-input wire:model.lazy="checkall" class="cursor-pointer p-2 !rounded-none"
                                        name="checkall" type="checkbox" id="checkall" wire:loading.attr="disabled" />
                                    TODO
                                </label>
                            @endif
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
                                <x-input type="checkbox" name="selectedrangos" class="p-2 !rounded-none cursor-pointer"
                                    id="{{ $item->id }}" x-model="selectedrangos" value="{{ $item->id }}"
                                    wire:loading.attr="disabled" />
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
                                <td class="p-1 text-center">
                                    <p class="font-semibold text-[10px] text-center">{{ $lista->name }}</p>
                                    @can('admin.administracion.rangos.edit')
                                        <x-input class="inline-block text-center" :value="$lista->pivot->ganancia" type="text"
                                            step="0.01" min="0" id="ganancia_{{ $item->id . $lista->id }}"
                                            x-mask:dynamic="$money($input, '.', '', 2)"
                                            onkeypress="return validarDecimal(event, 5)" wire:loading.attr="disabled"
                                            @keydown.enter.prevent="actualizar($event.target.value, {{ $item->id }}, {{ $lista->id }}, {{ $lista->pivot->ganancia }})"
                                            @blur="$el.value = getBlurValue($el.value)" />
                                    @endcan

                                    @cannot('admin.administracion.rangos.edit')
                                        <x-disabled-text :text="$lista->pivot->ganancia" class="inline-block md:px-5 border-0" />
                                    @endcannot
                                    <p class="text-[10px] text-center text-colorsubtitleform">Enter para acualizar</p>
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
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('rangos', () => ({
                selectedrangos: @entangle('selectedrangos').defer,
                actualizar(value, rango_id, lista_id, oldpercent) {
                    let ganancia = value > 0 ? toDecimal(value, 2) : 0;
                    if (ganancia != toDecimal(oldpercent, 2)) {
                        this.$wire.updatepricerango(rango_id, lista_id, ganancia).then(function() {
                            // console.log('updatepricerango proceess finish successfull');
                        });
                    }
                },
                getBlurValue(value) {
                    if (value === '' || isNaN(value)) {
                        value = '0.00';
                    } else {
                        value = toDecimal(value, 2);
                    }
                    return value;
                }
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
