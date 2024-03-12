<div class="">

    @if ($almacenareas->hasPages())
        <div class="pb-2">
            {{ $almacenareas->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">
        @if (count($almacenareas) > 0)
            @foreach ($almacenareas as $item)
                <x-minicard :title="$item->name" size="md">
                    <x-slot name="buttons">
                        <div class="ml-auto">
                            @can('admin.almacen.almacenareas.edit')
                                <x-button-edit wire:loading.attr="disabled" wire:target="edit({{ $item->id }})"
                                    wire:click="edit({{ $item->id }})" />
                            @endcan

                            @can('admin.almacen.almacenareas.delete')
                                <x-button-delete wire:loading.attr="disabled" onclick="confirmDelete({{ $item }})" />
                            @endcan
                        </div>
                    </x-slot>
                </x-minicard>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar área almacén') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">

                <x-label value="Nombre :" />
                <x-input class="block w-full" wire:model.defer="almacenarea.name"
                    placeholder="Ingrese descripción área almacén..." />
                <x-jet-input-error for="almacenarea.name" />

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
    <script>
        function confirmDelete(almacenarea) {
            swal.fire({
                title: 'Eliminar area de almacén ' + almacenarea.name,
                text: "Se eliminará un registro de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(almacenarea.id);
                }
            })
        }
    </script>
</div>
