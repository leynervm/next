<div class="relative">

    @if ($categories->hasPages())
        <div class="w-full pb-2">
            {{ $categories->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex flex-col gap-2" id="categories">
        @if (count($categories) > 0)
            @foreach ($categories as $item)
                <x-simple-card class="w-full flex flex-col items-start gap-2 relative p-2" data-id="{{ $item->id }}">
                    <div class="w-full flex gap-3 items-start">
                        <div class="w-full flex-1">
                            <div class="w-full flex gap-2 items-center">
                                @can('admin.almacen.categorias.edit')
                                    <button
                                        class="text-next-500 inline-block cursor-grab flex-shrink-0 h-full handle hover:shadow hover:shadow-shadowminicard rounded-md opacity-70 hover:opacity-100 transition ease-in-out duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            stroke="none" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                                            class="w-6 h-6 xs:w-8 xs:h-8 block">
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
                                <h1 class="text-colortitleform text-xs font-semibold">{{ $item->name }}</h1>
                            </div>

                            <div class="w-full mt-2 flex flex-col justify-between gap-2 h-full">
                                @if (count($item->subcategories))
                                    <div class="w-full flex flex-wrap gap-1">
                                        @foreach ($item->subcategories as $subcategory)
                                            <x-span-text :text="$subcategory->name" class="leading-3" />
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if ($item->icon)
                            <div
                                class="w-12 h-12 p-1 text-colorsubtitleform flex-shrink-0 rounded-xl overflow-hidden shadow shadow-shadowminicard">
                                {!! $item->icon !!}
                            </div>
                        @endif
                    </div>

                    <div class="w-full flex gap-1 pt-2 justify-end">
                        @can('admin.almacen.categorias.edit')
                            <x-button-edit wire:click="edit({{ $item->id }})" wire:loading.attr="disabled"
                                wire:key="editcat_{{ $item->id }}" />
                        @endcan

                        @can('admin.almacen.categorias.delete')
                            <x-button-delete onclick="confirmDelete({{ $item }})" wire:loading.attr="disabled"
                                wire:key="deletecat_{{ $item->id }}" />
                        @endcan
                    </div>
                </x-simple-card>
            @endforeach
        @endif
    </div>

    <div wire:loading.flex class="loading-overlay rounded hidden fixed">
        <x-loading-next />
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar categoría') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update(true)" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="category.name"
                        placeholder="Ingrese nombre categoría..." />
                    <x-jet-input-error for="category.name" />
                </div>
                <div>
                    <x-label value="Contenido icono SVG :" />
                    <x-text-area class="w-full block" wire:model.defer="category.icon" rows="10">
                    </x-text-area>
                    <x-jet-input-error for="category.icon" />
                </div>

                @if ($category->icon)
                    <div
                        class="w-48 h-48 p-2 rounded-xl mx-auto text-colorsubtitleform relative mb-2 border border-borderminicard">
                        {!! $category->icon !!}
                    </div>
                @endif

                <div class="w-full flex gap-2 flex-row pt-4 justify-end">
                    <x-button type="button" wire:click="update" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save and close') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            new Sortable(categories, {
                animation: 150,
                ghostClass: 'bg-fondospancardproduct',
                handle: '.handle',
                store: {
                    set: function(sortable) {
                        const sorts = sortable.toArray();
                        axios.post("{{ route('api.sort.categories') }}", {
                            sorts: sorts
                        }).catch(function(error) {
                            console.log(error);
                        });
                    }
                },
            })
        })

        function confirmDelete(category) {
            swal.fire({
                title: 'Eliminar categoría ' + category.name,
                text: "Se eliminará un registro de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(category.id);
                }
            })
        }
    </script>
</div>
