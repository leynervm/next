<div class="">

    @if (count($proveedortypes))
        <div class="pb-2">
            {{ $proveedortypes->links() }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">
        @if (count($proveedortypes))
            @foreach ($proveedortypes as $item)
                @php
                    $default = '';
                    switch ($item->default) {
                        case 1:
                            $default = 'Pago ventas';
                            break;
                        case 2:
                            $default = 'Pago internet';
                            break;
                        case 3:
                            $default = 'Pago cuotas';
                            break;
                        default:
                            $default = null;
                            break;
                    }
                @endphp

                <x-minicard :title="$item->name" :content="$default" :alignFooter="$item->default ? 'justify-between' : 'justify-end'" size="md">
                    <x-slot name="buttons">
                        <div class="inline-flex">
                            @if ($item->default)
                                <span class="bg-green-100 text-green-500 p-1 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </span>
                            @endif
                            @if ($item->web)
                                <span
                                    class="bg-green-100 text-green-500 p-1 rounded-full @if ($item->default) absolute left-6 ring-2 ring-white @endif">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="2" x2="22" y1="12" y2="12" />
                                        <path
                                            d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                    </svg>
                                </span>
                            @endif
                        </div>

                        <div class="">
                            <x-button-edit wire:loading.attr="disabled" wire:target="edit"
                                wire:click="edit({{ $item->id }})"></x-button-edit>
                            <x-button-delete wire:click="$emit('proveedortypes.confirmDelete', {{ $item }})"
                                wire:loading.attr="disabled" wire:target="confirmDelete,delete"></x-button-delete>
                        </div>
                    </x-slot>
                </x-minicard>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar tipo proveedor') }}
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <x-label value="Tipo proveedor :" />
                <x-input class="block w-full" wire:model.defer="proveedortype.name"
                    placeholder="Descripción del concepto pago..." />
                <x-jet-input-error for="proveedortype.name" />

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="update">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('proveedortypes.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar registro con nombre: ' + data.name,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('admin.proveedortypes.show-proveedortypes', 'delete', data
                            .id);
                    }
                })
            })
        })
    </script>
</div>
