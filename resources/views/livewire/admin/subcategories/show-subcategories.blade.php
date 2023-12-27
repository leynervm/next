<div class="relative">
    @if ($subcategories->hasPages())
        <div class="w-full pb-2">
            {{ $subcategories->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">
        @if (count($subcategories))
            @foreach ($subcategories as $item)
                <x-minicard :title="$item->name" size="md">
                    <x-slot name="buttons">
                        <div class="ml-auto">
                            <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})" />
                            <x-button-delete wire:loading.attr="disabled"
                                wire:click="$emit('subcategories.confirmDelete',{{ $item }})" />
                        </div>
                    </x-slot>
                </x-minicard>
            @endforeach
        @endif
    </div>

    <div wire:loading.flex class="loading-overlay rounded hidden">
        <x-loading-next />
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar subcategoría') }}
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">

                <div>
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="subcategory.name"
                        placeholder="Ingrese nombre subcategoría..." />
                    <x-jet-input-error for="subcategory.name" />
                </div>

                <x-title-next titulo="ASIGNAR CATEGORÍAS" class="mt-3"/>

                @if (count($categories))
                    <div class="w-full flex flex-wrap gap-1">
                        @foreach ($categories as $item)
                            <x-label-check for="category_edit_{{ $item->id }}">
                                <x-input wire:model.defer="selectedCategories" name="categories[]" value="1"
                                    type="checkbox" :value="$item->id" id="category_edit_{{ $item->id }}" />
                                {{ $item->name }}
                            </x-label-check>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="selectedCategories" />

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
                        Livewire.emitTo('almacen::subcategories.show-subcategories', 'delete', data
                            .id);
                    }
                })
            })
        })
    </script>
</div>
