<div x-data="rangos">
    <div wire:loading.flex class="loading-overlay hidden fixed">
        <x-loading-next />
    </div>

    <div class="w-full pb-1 flex flex-row flex-wrap gap-1 items-center justify-between">
        @canany(['admin.administracion.rangos.delete', 'admin.administracion.rangos.sync'])
            @can('admin.administracion.rangos.sync')
                <x-button @click="syncprices" wire:loading.attr="disabled" style="display: none;"
                    x-show="selectedrangos.length > 0">
                    {{ __('SINCRONIZAR PRECIOS DE RANGOS') }} <span x-text="selectedrangos.length"
                        class="bg-white inline-block p-0.5 ml-1 text-[9px] rounded-full !tracking-normal font-semibold text-fondobutton"
                        :class="selectedrangos.length < 10 ? 'px-1.5' : 'px-1'"></span>
                </x-button>
            @endcan
            @can('admin.administracion.rangos.delete')
                <x-button-secondary @click="deleteselecteds" wire:loading.attr="disabled" style="display: none;"
                    x-show="selectedrangos.length > 0">
                    {{ __('ELIMINAR SELECCIONADOS') }} <span x-text="selectedrangos.length"
                        class="bg-white inline-block p-0.5 ml-1 text-[9px] rounded-full !tracking-normal font-semibold text-red-500"
                        :class="selectedrangos.length < 10 ? 'px-1.5' : 'px-1'"></span>
                </x-button-secondary>
            @endcan
        @endcanany
    </div>

    @if (count($rangos) > 0)
        <div class="w-full">
            <x-table>
                <x-slot name="header">
                    <tr>
                        @can('admin.administracion.rangos.delete')
                            <th scope="col" class="p-2 font-medium text-center">
                                @if (count($rangos) > 0)
                                    <label for="checkall"
                                        class="text-xs flex flex-col justify-center items-center gap-1 leading-3">
                                        <x-input @change="toggleAll" x-model="checkall" autocomplete="off"
                                            class="cursor-pointer p-2 !rounded-none" name="checkall" type="checkbox"
                                            id="checkall" x-ref="checkall" wire:loading.attr="disabled" />
                                        TODO</label>
                                @endif
                            </th>
                        @endcan

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
                            % GANANCIA
                        </th>

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
                            @can('admin.administracion.rangos.delete')
                                <td class="p-1 text-xs text-center">
                                    <x-input type="checkbox" name="selectedrangos" class="p-2 !rounded-none cursor-pointer"
                                        id="{{ $item->id }}" @change="toggleRango" value="{{ $item->id }}"
                                        wire:loading.attr="disabled" />
                                </td>
                            @endcan

                            <td class="p-1 text-xs">
                                {{ $item->desde }}
                            </td>
                            <td class="p-1 text-xs">
                                {{ $item->hasta }}
                            </td>
                            <td class="p-1 text-xs">
                                <div class="flex gap-1 text-green-500 items-center justify-center">
                                    <span>{{ decimalOrInteger($item->incremento) }}%</span>
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
                                        <x-input class="inline-block text-center w-full min-w-28 max-w-28 sm:max-w-36"
                                            :value="$lista->pivot->ganancia" type="text" step="0.01" min="0"
                                            id="ganancia_{{ $item->id . $lista->id }}" pricetype_id="{{ $lista->id }}"
                                            rango_id="{{ $item->id }}" name="percent_ganacia"
                                            x-mask:dynamic="$money($input, '.', '', 2)"
                                            onkeypress="return validarDecimal(event, 5)"
                                            wire:key="ganancia_{{ $item->id . $lista->id }}" wire:loading.attr="disabled"
                                            @blur="$el.value = getBlurValue($el.value)" />
                                    @endcan

                                    {{-- @keydown.enter.prevent="actualizar($event.target.value, {{ $item->id }}, {{ $lista->id }}, {{ $lista->pivot->ganancia }})" --}}

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
        </div>
    @endif

    @if ($rangos->hasPages())
        <div class="w-full flex justify-between items-center p-1 sticky -bottom-1 right-0 bg-body">
            @can('admin.administracion.rangos.sync')
                <x-button @click="saveganancias" wire:loading.attr="disabled">ACTUALIZAR LISTA</x-button>
            @endcan

            {{ $rangos->onEachSide(0)->links('livewire::pagination-default') }}
        </div>


        {{-- <div class="w-full flex justify-between items-center p-1 gap-2 sm:pr-6 fixed bottom-0 right-0 bg-body"
            :class="openSidebar ? 'md:pl-52' : 'md:pl-20'">
            @can('admin.administracion.rangos.sync')
                <x-button @click="saveganancias" wire:loading.attr="disabled">ACTUALIZAR LISTA</x-button>
            @endcan
            <div class="w-full flex-1 flex flex-col justify-end items-end">
                {{ $rangos->onEachSide(0)->links('livewire::pagination-default') }}
            </div>
        </div> --}}
    @endif

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar rango precio') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="w-full grid grid-cols-2 gap-2">
                    <div class="w-full">
                        <x-label value="Rango inicio :" />
                        <x-input class="block w-full input-number-none" wire:model.defer="rango.desde" type="number"
                            step="0.01" min="0" onkeypress="return validarDecimal(event, 9)" />
                        <x-jet-input-error for="rango.desde" />
                    </div>
                    <div class="w-full">
                        <x-label value="Rango final :" />
                        <x-input class="block w-full input-number-none" wire:model.defer="rango.hasta" type="number"
                            step="0.01" min="0" onkeypress="return validarDecimal(event, 9)" />
                        <x-jet-input-error for="rango.hasta" />
                    </div>
                    <div class="w-full">
                        <x-label value="Porcentaje ganancia (%) :" />
                        <x-input class="block w-full input-number-none" wire:model.defer="rango.incremento"
                            type="number" step="0.01" min="0"
                            onkeypress="return validarDecimal(event, 5)" />
                        <x-jet-input-error for="rango.incremento" />
                    </div>
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
                checkall: @entangle('checkall').defer,
                selectedrangos: @entangle('selectedrangos').defer,
                init() {

                    Livewire.hook('message.processed', () => {
                        const rangos = document.querySelectorAll(
                            '[type=checkbox][name=selectedrangos]:checked');
                        this.checkall = (this.selectedrangos.length == rangos
                                .length && rangos.length > 0) ? true :
                            false;
                    });
                },
                // actualizar(value, rango_id, lista_id, oldpercent) {
                //     let ganancia = value > 0 ? toDecimal(value, 2) : 0;
                //     if (ganancia != toDecimal(oldpercent, 2)) {
                //         this.$wire.updatepricerango(rango_id, lista_id, ganancia).then(function() {
                //         });
                //     }
                // },
                getBlurValue(value) {
                    if (value === '' || isNaN(value)) {
                        value = '0.00';
                    } else {
                        value = toDecimal(value, 2);
                    }
                    return value;
                },
                toggleAll() {
                    const selectedrangos = [];
                    let checked = this.$event.target.checked;
                    const rangos = document.querySelectorAll(
                        '[type=checkbox][name=selectedrangos]');

                    rangos.forEach(checkbox => {
                        checkbox.checked = checked;
                        if (checkbox.checked) {
                            selectedrangos.push(parseInt(checkbox.value));
                        }
                    });

                    this.selectedrangos = checked ? selectedrangos : [];
                },
                toggleRango() {
                    let value = this.$event.target.value;
                    let index = this.selectedrangos.indexOf(parseInt(value));

                    if (index !== -1) {
                        this.selectedrangos.splice(index, 1);
                    } else {
                        this.selectedrangos.push(parseInt(value));
                    }
                    const rangos = document.querySelectorAll(
                        '[type=checkbox][name=selectedrangos]');
                    this.checkall = (this.selectedrangos.length == rangos.length) ? true :
                        false;
                },
                syncprices() {
                    swal.fire({
                        title: 'SINCRONIZAR LISTA DE PRECIOS DE VENTA DE LOS PRODUCTOS ?',
                        text: null,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#0FB9B9',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.$wire.syncprices(this.selectedrangos).then(result => {
                                if (result) {
                                    const rangos = document.querySelectorAll(
                                        '[type=checkbox][name=selectedrangos]');

                                    rangos.forEach(checkbox => {
                                        checkbox.checked = false;
                                    });
                                    this.checkall = false;
                                }
                            })
                        }
                    })
                },
                deleteselecteds() {
                    swal.fire({
                        title: 'ELIMINAR LISTA DE RANGOS SELECCIONADOS ?',
                        text: null,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#0FB9B9',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.$wire.deleteall(this.selectedrangos).then(result => {
                                console.log(result);
                                if (result) {
                                    const rangos = document.querySelectorAll(
                                        '[type=checkbox][name=selectedrangos]');

                                    rangos.forEach(checkbox => {
                                        checkbox.checked = false;
                                    });
                                    this.checkall = false;
                                }
                            })
                        }
                    })
                },
                saveganancias() {
                    ganancias = []
                    const inputs = document.querySelectorAll(
                        '[type=text][name=percent_ganacia]');
                    inputs.forEach((item, index) => {
                        // ganancias[item.getAttribute('pricetype_rango_id')] = item.value;
                        ganancias.push({
                            rango_id: item.getAttribute('rango_id'),
                            pricetype_id: item.getAttribute('pricetype_id'),
                            ganancia: item.value
                        });
                    })
                    // console.log(ganancias);
                    if (ganancias.length > 0) {
                        swal.fire({
                            title: 'ACTUALIZAR PORCENTAJES DE GANANCIA DE LISTA DE PRECIOS ?',
                            text: null,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#0FB9B9',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Confirmar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.$wire.updateganacias(ganancias).then(result => {})
                            }
                        })
                    }
                }
            }))
        })

        function confirmDeleteRango(rango) {
            swal.fire({
                title: 'Eliminar rango de precio seleccionado, desde: ' + rango.desde + ' hasta: ' + rango
                    .hasta,
                text: "Se eliminará un registro de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(rango.id);
                }
            })
        }
    </script>
</div>
