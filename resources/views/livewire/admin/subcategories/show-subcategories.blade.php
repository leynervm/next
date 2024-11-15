<div class="relative">
    <div class="grid grid-cols-[repeat(auto-fill,minmax(110px,1fr))] gap-1 self-start mt-2" id="subcategories">
        @if (count($subcategories))
            @foreach ($subcategories as $item)
                <div class="border border-borderminicard p-1 min-h-28 flex flex-col gap-2 justify-between rounded-lg sm:rounded-2xl"
                    data-id="{{ $item->id }}">
                    <div class="w-full flex-1 h-full py-1 flex flex-col justify-center items-center">
                        <h1 class="text-xs font-medium text-colorsubtitleform text-center leading-none">
                            {{ $item->name }}</h1>
                    </div>

                    <div class="w-full flex gap-2 justify-between items-end">
                        @can('admin.almacen.subcategorias.edit')
                            <button
                                class="text-next-500 block p-1 cursor-grab handle hover:shadow hover:shadow-shadowminicard rounded-md opacity-70 hover:opacity-100 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    stroke="none" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                                    class="w-4 h-4 scale-150">
                                    <path d="M10.4961 16.5H13.4961V19.5H10.4961V16.5Z" />
                                    <path d="M16.5 16.5H19.5V19.5H16.5V16.5Z" />
                                    <path d="M4.5 16.5H7.5V19.5H4.5V16.5Z" />
                                    <path d="M10.4961 10.5H13.4961V13.5H10.4961V10.5Z" />
                                    <path d="M10.5 4.5H13.5V7.5H10.5V4.5Z" />
                                    <path d="M16.5 10.5H19.5V13.5H16.5V10.5Z" />
                                    <path d="M16.5 4.5H19.5V7.5H16.5V4.5Z" />
                                    <path d="M4.5 10.5H7.5V13.5H4.5V10.5Z" />
                                    <path d="M4.5 4.5H7.5V7.5H4.5V4.5Z" />
                                </svg>
                            </button>
                        @endcan
                        @canany(['admin.almacen.subcategorias.edit', 'admin.almacen.subcategorias.delete'])
                            <div class="">
                                @can('admin.almacen.subcategorias.edit')
                                    <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})"
                                        wire:key="editsubcat_{{ $item->id }}" />
                                @endcan
                                @can('admin.almacen.subcategorias.delete')
                                    <x-button-delete wire:loading.attr="disabled" onclick="confirmDelete({{ $item }})"
                                        wire.key="delectesubc_{{ $item->id }}" />
                                @endcan
                            </div>
                        @endcanany
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    @if ($subcategories->hasPages())
        <div class="w-full flex justify-center items-center sm:justify-end p-1 sticky -bottom-1 right-0 bg-body">
            {{ $subcategories->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div wire:loading.flex class="loading-overlay fixed hidden">
        <x-loading-next />
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar subcategoría') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="subcategory.name"
                        placeholder="Ingrese nombre subcategoría..." />
                    <x-jet-input-error for="subcategory.name" />
                </div>

                <x-label value="Seleccionar categorías :" />
                @if (count($categories) > 0)
                    <div class="w-full flex flex-wrap gap-1">
                        @foreach ($categories as $item)
                            <x-input-radio class="py-2" for="category_edit_{{ $item->id }}" :text="$item->name">
                                <input wire:model.defer="selectedCategories"
                                    class="sr-only peer peer-disabled:opacity-25" type="checkbox"
                                    id="category_edit_{{ $item->id }}" name="categories"
                                    value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="selectedCategories" />

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            new Sortable(subcategories, {
                animation: 150,
                ghostClass: 'bg-fondospancardproduct',
                handle: '.handle',
                store: {
                    set: function(sortable) {
                        const sorts = sortable.toArray();
                        axios.post("{{ route('api.sort.subcategories') }}", {
                            sorts: sorts
                        }).catch(function(error) {
                            console.log(error);
                        });
                    }
                },
            })
        })

        function confirmDelete(subcategory) {
            swal.fire({
                title: 'Eliminar subcategoría ' + subcategory.name,
                text: "Se eliminará un registro de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(subcategory.id);
                }
            })
        }
    </script>
</div>
