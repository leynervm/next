<div class="relative">

    @if ($categories->hasPages())
        <div class="w-full pb-2">
            {{ $categories->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex flex-col gap-2" id="categories">
        @if (count($categories))
            @foreach ($categories as $item)
                <x-simple-card class="w-full flex items-start gap-2 relative p-2" data-id="{{ $item->id }}">
                    @can('admin.almacen.categorias.edit')
                        <span
                            class="text-next-500 block cursor-grab h-full handle hover:shadow hover:shadow-shadowminicard rounded-md opacity-70 hover:opacity-100 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="none"
                                stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                                class="w-6 h-6 xs:w-8 xs:h-8">
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
                        </span>
                    @endcan

                    <div class="w-full">
                        <h1 class="text-colortitleform text-xs font-semibold">{{ $item->name }}</h1>
                        <div class="w-full flex flex-col justify-between gap-2 h-full">
                            @if (count($item->subcategories))
                                <div class="w-full flex flex-wrap gap-1">
                                    @foreach ($item->subcategories as $subcategory)
                                        <x-span-text :text="$subcategory->name" class="leading-3" />
                                    @endforeach
                                </div>
                            @endif

                            <div class="w-full flex gap-1 pt-2 justify-end">
                                @can('admin.almacen.categorias.edit')
                                    <x-button-edit wire:click="edit({{ $item->id }})" wire:loading.attr="disabled"
                                        wire:key="editcat_{{ $item->id }}" />
                                @endcan

                                @can('admin.almacen.categorias.delete')
                                    <x-button-delete onclick="confirmDelete({{ $item }})"
                                        wire:loading.attr="disabled" wire:key="deletecat_{{ $item->id }}" />
                                @endcan
                            </div>
                        </div>
                    </div>
                </x-simple-card>
            @endforeach
        @endif
    </div>

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
