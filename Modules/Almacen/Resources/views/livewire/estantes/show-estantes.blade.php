<div class="">

    @if ($estantes->hasPages())
        <div class="pb-2">
            {{ $estantes->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">
        @if (count($estantes))
            @foreach ($estantes as $item)
                <x-minicard :title="$item->name" size="md">
                    <x-slot name="buttons">
                        <div class="ml-auto">
                            @can('admin.almacen.estantes.edit')
                                <x-button-edit wire:loading.attr="disabled" wire:target="edit({{ $item->id }})"
                                    wire:click="edit({{ $item->id }})"></x-button-edit>
                            @endcan

                            @can('admin.almacen.estantes.delete')
                                <x-button-delete wire:loading.attr="disabled"
                                    onclick="confirmDelete({{ $item }})"></x-button-delete>
                            @endcan
                        </div>
                    </x-slot>
                </x-minicard>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar estante almacén') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">

                <x-label value="Nombre :" />
                <x-input class="block w-full" wire:model.defer="estante.name"
                    placeholder="Ingrese descripción estante almacén..." />
                <x-jet-input-error for="estante.name" />

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
    <script>
        function confirmDelete(estante) {
            swal.fire({
                title: 'Eliminar estante de almacén ' + estante.name,
                text: "Se eliminará un registro de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(estante.id);
                }
            })
        }
    </script>
</div>
