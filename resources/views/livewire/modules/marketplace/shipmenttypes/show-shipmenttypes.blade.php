<div>
    <div wire:loading.flex class="loading-overlay rounded fixed hidden">
        <x-loading-next />
    </div>

    @if ($shipementtypes->hasPages())
        <div class="pt-3 pb-1">
            {{ $shipementtypes->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    @if (count($shipementtypes) > 0)
        <x-table class="w-full mt-1">
            <x-slot name="header">
                <tr>
                    <th scope="col" class="p-2 font-medium">
                        <button class="flex items-center gap-2 focus:outline-none">
                            <span>TIPO ENVÍO</span>
                            <svg class="h-3 w-3" viewBox="0 0 10 11" fill="currentColor" stroke="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z"
                                    stroke-width="0.1" />
                                <path
                                    d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z"
                                    stroke-width="0.1" />
                                <path
                                    d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z"
                                    stroke-width="0.3" />
                            </svg>
                        </button>
                    </th>
                    <th scope="col" class="p-2 font-medium">DESCRIPCIÓN</th>
                    <th scope="col" class="p-2 font-medium">ENTREGA</th>
                    @can('admin.marketplace.shipmenttypes.edit')
                        <th scope="col" class="p-2 font-medium text-center">OPCIONES</th>
                    @endcan
                </tr>
            </x-slot>

            <x-slot name="body">
                @foreach ($shipementtypes as $item)
                    <tr>
                        <td class="p-2">
                            {{ $item->name }}
                        </td>
                        <td class="p-2 text-[10px]">
                            <p class="leading-3"> {{ $item->descripcion }}</p>
                        </td>

                        <td class="p-2 text-center">
                            @if ($item->isEnviodomicilio())
                                <x-span-text text="ENVÍO DOMICILIO" class="text-[10px] leading-3 !tracking-normal" />
                            @else
                                <x-span-text text="RECOJO EN TIENDA" class="text-[10px] leading-3 !tracking-normal" />
                            @endif
                        </td>

                        @can('admin.marketplace.shipmenttypes.edit')
                            <td class="p-2 text-center">
                                <x-button-edit wire:click="edit({{ $item->id }})" wire:loading.attr="disabled" />
                            </td>
                        @endcan
                    </tr>
                @endforeach
            </x-slot>
        </x-table>
    @else
        <x-span-text text="NO EXISTEN REGISTROS DE TIPOS DE ENVÍO..." class="mt-3 bg-transparent" />
    @endif



    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar tipo de envío') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="shipmenttype.name"
                        placeholder="Nombre del tipo de envío..." />
                    <x-jet-input-error for="shipmenttype.name" />
                </div>

                <div class="w-full">
                    <x-label value="Descripción :" />
                    <x-text-area rows="6" class="block w-full"
                        wire:model.defer="shipmenttype.descripcion"></x-text-area>
                    <x-jet-input-error for="shipmenttype.descripcion" />
                </div>

                <div>
                    <x-minicard>
                        @if ($shipmenttype->isEnviodomicilio())
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="w-12 h-12 block text-colorsubtitleform">
                                <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M5 17h-2v-4m-1 -8h11v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" />
                                <path d="M3 9l4 0" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 33" fill-rule="evenodd"
                                clip-rule="evenodd" fill="currentColor" class="w-12 h-12 block text-colorsubtitleform">
                                <path
                                    d="M18.4449 14.2024C19.4296 12.8623 20 11.5761 20 10.5C20 8.29086 18.2091 6.5 16 6.5C13.7909 6.5 12 8.29086 12 10.5C12 11.5761 12.5704 12.8623 13.5551 14.2024C14.3393 15.2698 15.2651 16.2081 16 16.8815C16.7349 16.2081 17.6607 15.2698 18.4449 14.2024ZM16.8669 18.7881C18.5289 17.3455 22 13.9227 22 10.5C22 7.18629 19.3137 4.5 16 4.5C12.6863 4.5 10 7.18629 10 10.5C10 13.9227 13.4712 17.3455 15.1331 18.7881C15.6365 19.2251 16.3635 19.2251 16.8669 18.7881ZM5 11.5H8.27078C8.45724 12.202 8.72804 12.8724 9.04509 13.5H5V26.5H10.5V22C10.5 21.4477 10.9477 21 11.5 21H20.5C21.0523 21 21.5 21.4477 21.5 22V26.5H27V13.5H22.9549C23.272 12.8724 23.5428 12.202 23.7292 11.5H27C28.1046 11.5 29 12.3954 29 13.5V26.5C29.5523 26.5 30 26.9477 30 27.5C30 28.0523 29.5523 28.5 29 28.5H3C2.44772 28.5 2 28.0523 2 27.5C2 26.9477 2.44772 26.5 3 26.5V13.5C3 12.3954 3.89543 11.5 5 11.5ZM19.5 23V26.5H12.5V23H19.5ZM17 10.5C17 11.0523 16.5523 11.5 16 11.5C15.4477 11.5 15 11.0523 15 10.5C15 9.94772 15.4477 9.5 16 9.5C16.5523 9.5 17 9.94772 17 10.5ZM19 10.5C19 12.1569 17.6569 13.5 16 13.5C14.3431 13.5 13 12.1569 13 10.5C13 8.84315 14.3431 7.5 16 7.5C17.6569 7.5 19 8.84315 19 10.5Z">
                                </path>
                            </svg>
                        @endif
                    </x-minicard>
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
</div>
