<div class="relative">
    @if ($categories->hasPages())
        <div class="w-full pb-2">
            {{ $categories->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif


    @if (count($categories))
        <div class="flex gap-2 flex-wrap justify-start">
            @foreach ($categories as $item)
                <x-form-card :titulo="$item->name" class="w-full relative xs:w-60">
                    <div class="w-full flex flex-col justify-between gap-2 h-full">
                        @if (count($item->subcategories))
                            <div class="w-full flex flex-wrap gap-1">
                                @foreach ($item->subcategories as $subcategory)
                                    <x-span-text :text="$subcategory->name" class="leading-3" />
                                @endforeach
                            </div>
                        @endif

                        <div class="w-full flex gap-1 pt-2 justify-end">
                            <x-button-edit wire:click="edit({{ $item->id }})" wire:loading.attr="disabled" />
                            <x-button-delete wire:click="$emit('subcategories.confirmDelete', {{ $item }})"
                                wire:loading.attr="disabled" />
                        </div>
                    </div>
                </x-form-card>
            @endforeach
        </div>
    @endif


    <div wire:loading.flex class="loading-overlay rounded hidden">
        <x-loading-next />
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar categoría') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="category.name"
                        placeholder="Ingrese nombre categoría..." />
                    <x-jet-input-error for="category.name" />
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
            Livewire.on('subcategories.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar registro con nombre, ' + data.name,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // console.log(data.detail.id);
                        Livewire.emitTo('almacen::categories.show-categories', 'delete', data.id);
                    }
                })
            })
        })
    </script>
</div>
