<div>
    @if ($areaswork->hasPages())
        <div class="w-full pb-2">
            {{ $areaswork->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-around md:justify-start mt-2">
        @if (count($areaswork))
            @foreach ($areaswork as $item)
                <x-minicard :title="$item->name" size="lg">
                    @canany(['admin.administracion.areaswork.edit', 'admin.administracion.areaswork.delete'])
                        <x-slot name="buttons">
                            @can('admin.administracion.areaswork.edit')
                                <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})" />
                            @endcan
                            @can('admin.administracion.areaswork.delete')
                                <x-button-delete wire:loading.attr="disabled"
                                    onclick="confirmDeleteAreawork({{ $item }})" />
                            @endcan
                        </x-slot>
                    @endcanany
                </x-minicard>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar área de trabajo') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="areawork.name" />
                    <x-jet-input-error for="areawork.name" />
                </div>

                @if (Module::isEnabled('Soporte'))
                    <div class="w-full">
                        <x-label-check for="visible">
                            <x-input wire:model.defer="areawork.visible" name="visible" value="1" type="checkbox"
                                id="visible" />MOSTRAR AREA AL REGISTRAR ORDEN DE TRABAJO
                        </x-label-check>
                        <x-jet-input-error for="areawork.visible" />
                    </div>
                @endif

                <div class="w-full flex gap-1 items-end pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        function confirmDeleteAreawork(areawork) {
            swal.fire({
                title: 'Eliminar area de trabajo ' + areawork.name,
                text: "Se eliminará un registro de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(areawork.id);
                }
            })
        }
    </script>

</div>
