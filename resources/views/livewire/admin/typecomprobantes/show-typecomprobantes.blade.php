<div>
    @if (count($typecomprobantes))
        <div class="pb-2">
            {{ $typecomprobantes->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <x-table class="mt-1">
        <x-slot name="header">
            <tr>
                <th scope="col" class="p-2 font-medium text-left">
                    DESCRIPCIÓN
                </th>

                <th scope="col" class="p-2 font-medium text-left">
                    CÓDIGO DOCUMENTO</th>

                <th scope="col" class="p-2 font-medium text-left">
                    SERIES EMISIÓN</th>

                <th scope="col" class="p-2 font-medium">
                    EMITIBLE SUNAT</th>

                @if (Module::isEnabled('Facturacion') || Module::isEnabled('Ventas'))
                    <th scope="col" class="p-2 font-medium text-center">
                        OPCIONES
                    </th>
                @endif
            </tr>
        </x-slot>
        @if (count($typecomprobantes))
            <x-slot name="body">
                @foreach ($typecomprobantes as $item)
                    <tr>
                        <td class="p-2 text-xs">
                            {{ $item->descripcion }}
                        </td>

                        <td class="p-2 text-xs">
                            {{ $item->code }}
                        </td>

                        <td class="p-2 text-xs">
                            @if (count($item->seriecomprobantes))
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($item->seriecomprobantes as $ser)
                                        <div
                                            class="inline-flex items-center justify-center gap-1 bg-fondospancardproduct text-textspancardproduct p-1 rounded">
                                            <span class="text-[10px]">{{ $ser->serie }}</span>
                                            <x-button-edit wire:click="edit({{ $item->id }}, {{ $ser->id }})"
                                                wire:loading.attr="disabled" />
                                            <x-button-delete
                                                wire:click="$emit('typecomprobantes.confirmDelete',{{ $ser }})"
                                                wire:loading.attr="disabled" />
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </td>

                        <td class="p-2 text-xs text-center">
                            @if ($item->sendsunat)
                                <x-span-text text="EMITIBLE SUNAT" class="leading-3" type="green" />
                            @else
                                <x-span-text text="LOCAL" class="leading-3" />
                            @endif
                        </td>
                        @if (Module::isEnabled('Facturacion') || Module::isEnabled('Ventas'))
                            <td class="p-2 text-xs align-middle text-center">
                                <x-button class="inline-block" wire:click="openmodal({{ $item->id }})"
                                    wire:loading.attr="disabled">AGREGAR
                                    SERIE</x-button>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </x-slot>
        @endif
    </x-table>


    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Agregar nueva serie') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">

                <div class="w-full">
                    <x-label value="Tipo comprobante :" />
                    <x-disabled-text :text="$typecomprobante->descripcion" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="Serie :" />
                    <x-input class="block w-full" wire:model.defer="serie" />
                    <x-jet-input-error for="serie" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="Contador :" />
                    <x-input class="block w-full" wire:model.defer="contador" />
                    <x-jet-input-error for="contador" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __(isset($seriecomprobante->id) ? 'ACTUALIZAR' : 'REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('typecomprobantes.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar serie ' + data.serie,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('admin.typecomprobantes.show-typecomprobantes', 'delete',
                            data
                            .id);
                    }
                })
            })
        })
    </script>
</div>
