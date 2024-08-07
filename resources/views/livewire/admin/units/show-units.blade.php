<div class="">
    @if ($units->hasPages())
        <div class="pb-2">
            {{ $units->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">
        @if (count($units))
            @foreach ($units as $item)
                <x-minicard :title="$item->name" size="md">

                    <h1 class="text-colorsubtitleform font-semibold text-sm">{{ $item->code }}</h1>

                    @canany(['admin.administracion.units.edit', 'admin.administracion.units.delete'])
                        <x-slot name="buttons">
                            <div class="ml-auto">
                                @can('admin.administracion.units.edit')
                                    <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})" />
                                @endcan
                                @can('admin.administracion.units.delete')
                                    <x-button-delete wire:loading.attr="disabled" onclick="confirmDelete( {{ $item }})" />
                                @endcan
                            </div>
                        </x-slot>
                    @endcanany
                </x-minicard>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar unidad medida') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Unidad medida :" />
                    <x-input class="block w-full" wire:model.defer="unit.name" placeholder="Ingrese nombre unidad..." />
                    <x-jet-input-error for="unit.name" />
                </div>
                <div>
                    <x-label value="Código :" />
                    <x-input class="block w-full" wire:model.defer="unit.code" placeholder="Ingrese nombre unidad..." />
                    <x-jet-input-error for="unit.code" />
                </div>

                {{-- <div class="w-full flex gap-2">
                    <div class="w-1/2 mt-2">
                        <x-label value="Abreviatura :" />
                        <x-input class="block w-full" wire:model.defer="unit.abreviatura"
                            placeholder="Ingrese nombre unidad..." />
                        <x-jet-input-error for="unit.abreviatura" />
                    </div>
                    <div class="w-1/2 mt-2">
                        
                    </div>
                </div> --}}

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        function confirmDelete(unit) {
            swal.fire({
                title: 'Eliminar unidad de medida ' + unit.name,
                text: "Se eliminará un registro de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(unit.id)
                }
            })
        }
    </script>
</div>
