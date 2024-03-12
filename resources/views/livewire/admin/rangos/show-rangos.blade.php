<div class="">

    @if ($rangos->hasPages())
        <div class="pb-2">
            {{ $rangos->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="block w-full">
        @if (count($rangos) > 0)
            <x-table>
                <x-slot name="header">
                    <tr>
                        <th scope="col" class="p-2 font-medium">
                            <button class="flex items-center gap-x-3 focus:outline-none">
                                <span>PRECIO MÍNIMO</span>
                                <svg class="h-3" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z"
                                        fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                    <path
                                        d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z"
                                        fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                    <path
                                        d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z"
                                        fill="currentColor" stroke="currentColor" stroke-width="0.3" />
                                </svg>
                            </button>
                        </th>
                        <th scope="col" class="p-2 font-medium">
                            <button class="flex items-center gap-x-3 focus:outline-none">
                                <span>PRECIO MÁXIMO</span>
                                <svg class="h-3" viewBox="0 0 10 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z"
                                        fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                    <path
                                        d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z"
                                        fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                                    <path
                                        d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z"
                                        fill="currentColor" stroke="currentColor" stroke-width="0.3" />
                                </svg>
                            </button>
                        </th>
                        <th scope="col" class="p-2 font-medium text-center">
                            INCREMENTO P.C
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
                            <td class="p-2 text-xs">
                                {{ $item->desde }}
                            </td>
                            <td class="p-2 text-xs">
                                {{ $item->hasta }}
                            </td>
                            <td class="p-2 text-xs">
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
                                <td class="p-2 text-center">
                                    <p class="font-semibold text-[10px]">{{ $lista->name }}</p>
                                    @can('admin.administracion.rangos.edit')
                                        <x-input class="inline-block text-center" :value="$lista->pivot->ganancia" type="number"
                                            step="0.1" min="0"
                                            wire:keydown.enter="updatepricerango({{ $item->id }},{{ $lista->id }}, $event.target.value)" />
                                    @endcan

                                    @cannot('admin.administracion.rangos.edit')
                                        <x-disabled-text :text="$lista->pivot->ganancia" class="inline-block md:px-5 border-0" />
                                    @endcannot
                                </td>
                            @endforeach

                            @canany(['admin.administracion.rangos.edit', 'admin.administracion.rangos.delete'])
                                <td class="p-2">
                                    <div class="flex gap-2 items-center justify-end">
                                        @can('admin.administracion.rangos.edit')
                                            <x-button-edit wire:click="edit({{ $item->id }})"
                                                wire:loading.attr="disabled" />
                                        @endcan

                                        @can('admin.administracion.rangos.delete')
                                            <x-button-delete wire:loading.attr="disabled"
                                                wire:click="$emit('rangos.confirmDelete',{{ $item }})" />
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
                            min="0" />
                        <x-jet-input-error for="rango.desde" />
                    </div>
                    <div class="w-full sm:w-1/2">
                        <x-label value="Precio hasta :" />
                        <x-input class="block w-full" wire:model.defer="rango.hasta" type="number" step="0.01"
                            min="0" />
                        <x-jet-input-error for="rango.hasta" />
                    </div>
                </div>

                <div class="md:w-1/2 mt-2">
                    <x-label value="Incremento precio :" />
                    <x-input class="block w-full" wire:model.defer="rango.incremento" type="number" step="0.01"
                        min="0" />
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
        document.addEventListener('livewire:load', function() {
            Livewire.on('rangos.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar rango de precio, ' + data.desde + ' - ' + data.hasta,
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
            })
        })
    </script>
</div>
