<div class="relative" x-data="{ loading: false }">
    @if ($motivos->hasPages())
        <div class="pt-3 pb-1">
            {{ $motivos->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    @if (count($motivos) > 0)
        <div class="w-full flex flex-wrap gap-2 justify-start items-start">
            @foreach ($motivos as $item)
                <x-minicard :title="$item->name" size="xl">
                    <x-slot name="buttons">
                        @can('admin.facturacion.guias.motivos.edit')
                            <x-button-edit wire:click="edit({{ $item->id }})" wire:loading.attr="disabled" />
                        @endcan
                        @can('admin.facturacion.guias.motivos.delete')
                            <x-button-delete onclick="confirmDelete({{ $item }})" wire:loading.attr="disabled" />
                        @endcan
                    </x-slot>
                </x-minicard>
            @endforeach
        </div>
    @else
        <x-span-text text="NO EXISTEN REGISTROS DE MOTIVOS DE TRASLADOS..." class="mt-3 bg-transparent" />
    @endif

    <div x-show="loading" wire:loading wire:loading.flex class="loading-overlay rounded">
        <x-loading-next />
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar motivo traslado') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="w-full">
                    <x-label value="Descripción del motivo :" />
                    <x-input class="block w-full" wire:model.defer="motivotraslado.name"
                        placeholder="Motivo del traslado..." />
                    <x-jet-input-error for="motivotraslado.name" />
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
        function confirmDelete(motivotraslado) {
            swal.fire({
                title: 'Eliminar motivo de traslado, ' + motivotraslado.name,
                text: "Se eliminará un registro de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(motivotraslado.id);
                }
            })
        }
    </script>
</div>
