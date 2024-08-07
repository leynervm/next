<div>
    @if ($trackingstates->hasPages())
        <div class="pb-2">
            {{ $trackingstates->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">
        @if (count($trackingstates) > 0)
            @foreach ($trackingstates as $item)
                <x-minicard :title="$item->name" :content="$item->isFinalizado() ? null : null" alignFooter="justify-between" size="lg">

                    <div class="w-10 h-10 rounded-full mx-auto" style="background: {{ $item->background }}"></div>

                    <x-slot name="buttons">
                        @if ($item->isDefault())
                            <x-icon-default />
                        @endif
                        @if ($item->isFinalizado())
                            <x-span-text text="FIN PEDIDO" class="!tracking-normal !leading-3" type="next" />
                        @endif

                        <div class="ml-auto">
                            @can('admin.marketplace.trackingstates.edit')
                                <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})" />
                            @endcan

                            @can('admin.marketplace.trackingstates.delete')
                                @if (!$item->isDefault())
                                    <x-button-delete wire:loading.attr="disabled"
                                        onclick="confirmDeleteTrackingstate({{ $item }})" />
                                @endif
                            @endcan
                        </div>
                    </x-slot>
                </x-minicard>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar estado de pedido') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="trackingstate.name"
                        placeholder="Descripción del estado..." />
                    <x-jet-input-error for="trackingstate.name" />
                </div>

                <div class="w-full">
                    <div class="w-14 h-14 relative overflow-hidden rounded-full">
                        <input type="color" wire:model.defer="trackingstate.background"
                            class="w-[140%] h-[140%] border-none border-0 outline-none absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" />
                    </div>
                    <x-label value="SELECCIONAR COLOR" />
                    <x-jet-input-error for="trackingstate.background" />
                </div>

                @if (!$trackingstate->isDefault())
                    <div class="block" x-data="{ finish: @entangle('finish').defer }">
                        <x-label-check for="editfinish">
                            <x-input x-model="finish" type="checkbox" id="editfinish" />
                            FINALIZA SEGUIMIENTO DEL PEDIDO
                        </x-label-check>
                        <x-jet-input-error for="finish" />
                    </div>
                @endif

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        function confirmDeleteTrackingstate(trackingstate) {
            swal.fire({
                title: 'Eliminar estado de seguimiento con descripción ' + trackingstate.name,
                text: "Se eliminará un registro de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(trackingstate.id);
                }
            })
        }
    </script>
</div>
