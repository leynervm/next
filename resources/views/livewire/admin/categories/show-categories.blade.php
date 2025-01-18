<div class="relative" x-data="updateimagen">
    <div class="flex flex-col gap-2" id="categories">
        @if (count($categories) > 0)
            @foreach ($categories as $item)
                <x-simple-card class="w-full flex flex-col items-start gap-2 relative p-2" data-id="{{ $item->id }}">
                    <div class="w-full flex gap-3 items-start">
                        <div class="w-full flex-1">
                            <div class="w-full flex gap-2 items-center">
                                @can('admin.almacen.categorias.edit')
                                    <x-icon-sweep class="size-6 xs:size-8" />
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
                        @if ($item->image)
                            <div
                                class="w-12 h-12 p-1 text-colorsubtitleform flex-shrink-0 rounded-xl overflow-hidden shadow shadow-shadowminicard">
                                {{-- {!! $item->icon !!} --}}
                                <img class="w-full h-full object-scale-down"
                                    src="{{ getCategoryURL($item->image->url) }}"
                                    alt="{{ getCategoryURL($item->image->url) }}">
                            </div>
                        @endif
                    </div>

                    <div class="w-full flex gap-1 pt-2 justify-end">
                        @can('admin.almacen.categorias.edit')
                            <x-button-edit wire:click="edit({{ $item->id }})" wire:loading.attr="disabled"
                                wire:key="editcat_{{ $item->id }}" @click="clearimage" />
                        @endcan

                        @can('admin.almacen.categorias.delete')
                            <x-button-delete onclick="confirmDelete({{ $item }})" wire:loading.attr="disabled"
                                wire:key="deletecat_{{ $item->id }}" @click="clearimage" />
                        @endcan
                    </div>
                </x-simple-card>
            @endforeach
        @endif
    </div>

    @if ($categories->hasPages())
        <div class="w-full flex justify-center items-center sm:justify-end p-1 sticky -bottom-1 right-0 bg-body">
            {{ $categories->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div wire:loading.flex class="loading-overlay fixed hidden">
        <x-loading-next />
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar categoría') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="category.name"
                        placeholder="Ingrese nombre categoría..." />
                    <x-jet-input-error for="category.name" />
                </div>
                {{-- <div>
                    <x-label value="Contenido icono SVG :" />
                    <x-text-area class="w-full block" wire:model.defer="category.icon" rows="10">
                    </x-text-area>
                    <x-jet-input-error for="category.icon" />
                </div> --}}

                {{-- @if ($category->icon)
                    <div
                        class="w-48 h-48 p-2 rounded-xl mx-auto text-colorsubtitleform relative mb-2 border border-borderminicard">
                        {!! $category->icon !!}
                    </div>
                @endif --}}

                <x-simple-card class="w-full h-60 max-w-60 mx-auto mb-1 !shadow-none">
                    <template x-if="image">
                        <img class="object-scale-down block w-full h-full" :src="image" />
                    </template>
                    <template x-if="!image">
                        @if ($category->image)
                            <img class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster"
                                src="{{ getCategoryURL($category->image->url) }}" />
                        @else
                            <x-icon-file-upload x-cloak x-show="!image" class="w-full h-full" />
                        @endif
                    </template>
                </x-simple-card>

                <div class="w-full flex gap-1 flex-wrap justify-center">
                    @if (isset($image))
                        <x-button class="inline-flex px-6" wire:loading.attr="disabled" @click="clearimage">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M3 6h18" />
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                <line x1="10" x2="10" y1="11" y2="17" />
                                <line x1="14" x2="14" y1="11" y2="17" />
                            </svg>
                            LIMPIAR
                        </x-button>
                    @else
                        <x-input-file for="updatelogocategory" :titulo="$category->image ? 'CAMBIAR IMAGEN' : 'SELECCIONAR IMAGEN'" wire:loading.remove>
                            <input type="file" class="hidden" id="updatelogocategory" accept="image/*"
                                @change="loadimagecategory" />
                        </x-input-file>
                    @endif

                    @if (!isset($image) && $category->image)
                        <x-button-secondary @click="deleteimagecategory" wire:loading.attr="disabled">
                            ELIMINAR</x-button-secondary>
                    @endif
                </div>
                <x-jet-input-error for="image" class="text-center" />

                <div class="w-full flex gap-2 flex-row pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
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


        function updateimagen() {
            return {
                image: null,
                loadimagecategory() {
                    let file = document.getElementById('updatelogocategory').files[0];
                    var reader = new FileReader();
                    reader.onload = (e) => {
                        this.image = e.target.result;
                        this.$wire.image = reader.result;
                    };
                    reader.readAsDataURL(file);
                    if (file) {
                        let imageExtension = file.name.split('.').pop();
                        this.$wire.extensionimage = imageExtension;
                    }
                },
                clearimage() {
                    this.image = null;
                    this.$wire.extensionimage = null;
                    this.$wire.image = null;
                },
                deleteimagecategory() {
                    this.image = null;
                    this.$wire.deleteimagecategory().then(result => {
                        if (result) {

                        }
                    })
                }
            }
        }

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
