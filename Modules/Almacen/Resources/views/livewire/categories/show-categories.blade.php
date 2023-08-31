<div class="">

    @if (count($categories))
        <div class="pb-2">
            {{ $categories->links() }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">
        @if (count($categories))
            @foreach ($categories as $item)
                <x-card-next :titulo="$item->name" class="w-full sm:w-60">

                    @if (count($item->subcategories))
                        <div class="w-full">
                            @foreach ($item->subcategories as $subcategory)
                                <span
                                    class="text-[10px] p-1 rounded-lg bg-fondospancardproduct text-textspancardproduct">{{ $subcategory->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    <x-slot name="footer">
                        <x-button-edit wire:loading.attr="disabled" wire:target="edit({{ $item->id }})"
                            wire:click="edit({{ $item->id }})"></x-button-edit>
                        <x-button-delete wire:loading.attr="disabled" wire:target="confirmDelete({{ $item->id }})"
                            wire:click="confirmDelete({{ $item->id }})"></x-button-delete>
                    </x-slot>
                </x-card-next>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar categoría') }}
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

                <x-label value="Nombre :" />
                <x-input class="block w-full" wire:model.defer="category.name"
                    placeholder="Ingrese nombre categoría..." />
                <x-jet-input-error for="category.name" />

                <x-label class="mt-6 mb-1 underline" value="Asignar categorías" />
                @if (count($subcategories))
                    <div class="flex flex-wrap gap-1">
                        @foreach ($subcategories as $item)
                            <x-label class="flex items-center gap-1 cursor-pointer bg-next-100 rounded-lg p-1"
                                for="subcategory_edit_{{ $item->id }}">
                                <x-input wire:model.defer="selectedSubcategories" name="subcategories[]" type="checkbox"
                                    id="subcategory_edit_{{ $item->id }}" value="{{ $item->id }}" />
                                {{ $item->name }}
                            </x-label>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="selectedSubcategories" />

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
            window.addEventListener('categories.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar registro con nombre: ' + data.detail.name,
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
                        Livewire.emitTo('almacen::categories.show-categories', 'delete', data
                            .detail.id);
                    }
                })
            })
        })
    </script>
</div>
