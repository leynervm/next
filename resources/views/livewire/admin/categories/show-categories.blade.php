<div class="relative" x-data="loadeditimage()">

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
                        @if ($item->image)
                            <div
                                class="w-20 h-20 flex-shrink-0 rounded-full overflow-hidden border border-borderminicard">
                                <img src="{{ $item->image->getCategoryURL() }}" class="w-full h-full object-cover block"
                                    alt="">
                            </div>
                        @endif
                    </div>

                    <div class="w-full flex gap-1 pt-2 justify-end">
                        @can('admin.almacen.categorias.edit')
                            <x-button-edit wire:click="edit({{ $item->id }})" wire:loading.attr="disabled"
                                wire:key="editcat_{{ $item->id }}" @click="editimage=null" />
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

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
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

                <div class="w-full h-60 relative mb-2 shadow-md shadow-shadowminicard rounded-xl overflow-hidden">
                    <template x-if="editimage">
                        <img id="editimage" class="object-scale-down block w-full h-full" :src="editimage" />
                    </template>
                    <template x-if="!editimage">
                        @if ($category->image)
                            <img id="editimage" class="object-scale-down block w-full h-full"
                                src="{{ $category->image->getCategoryURL() }}" />
                        @else
                            <x-icon-file-upload class="w-full h-full !my-0" />
                        @endif
                    </template>
                </div>

                <div class="w-full flex flex-wrap gap-2 justify-center">
                    <template x-if="editimage">
                        <x-button class="inline-flex !rounded-lg" wire:loading.attr="disabled" @click="reset">
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
                    </template>

                    @if ($category->image)
                        <x-button x-cloak x-show="editimage == null" class="inline-flex !rounded-lg"
                            wire:loading.attr="disabled" wire:click="deletelogo" wire:key="buttondeletelogo">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M3 6h18" />
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                <line x1="10" x2="10" y1="11" y2="17" />
                                <line x1="14" x2="14" y1="11" y2="17" />
                            </svg>
                            ELIMINAR LOGO
                        </x-button>
                    @endif

                    <x-input-file for="editFileInput" titulo="SELECCIONAR LOGO"
                        wire:loading.class="disabled:opacity-25" class="!rounded-lg">
                        <input type="file" class="hidden" wire:model="logo" id="editFileInput"
                            accept="image/jpg,image/jpeg,image/png" @change="loadlogo" />
                    </x-input-file>
                </div>
                <x-jet-input-error for="logo" class="text-center" />

                <div class="w-full flex pt-4 justify-end">
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

        function loadeditimage() {
            return {
                editimage: null,
                loadlogo() {
                    let file = document.getElementById('editFileInput').files[0];
                    var reader = new FileReader();
                    reader.onload = (e) => this.editimage = e.target.result;
                    reader.readAsDataURL(file);
                },
                reset() {
                    this.editimage = null;
                    @this.clearImage();
                },
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
