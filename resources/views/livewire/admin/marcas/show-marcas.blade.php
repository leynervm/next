<div x-data="loadeditimage">
    <div class="grid grid-cols-[repeat(auto-fill,minmax(110px,1fr))] gap-1 self-start mt-2">
        @if (count($marcas) > 0)
            @foreach ($marcas as $item)
                <div
                    class="border border-borderminicard p-1 min-h-28 flex flex-col gap-2 justify-between rounded-lg sm:rounded-2xl">
                    <div class="w-full flex-1 h-full py-1 flex flex-col justify-center items-center">
                        <h1 class="text-xs font-medium text-colorsubtitleform text-center leading-none">
                            {{ $item->name }}</h1>
                        @if ($item->url)
                            <div class="w-full h-auto max-h-16 pt-1">
                                <img class="block w-full max-w-full h-full object-scale-down"
                                    src="{{ getMarcaURL($item->url) }}" alt="Logo marca">
                            </div>
                        @endif
                    </div>
                    <div class="w-full flex justify-end items-end gap-2">
                        @canany(['admin.almacen.marcas.edit', 'admin.almacen.marcas.delete'])
                            @can('admin.almacen.marcas.edit')
                                <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})"
                                    @click="editimage=null" />
                            @endcan
                            @can('admin.almacen.marcas.delete')
                                <x-button-delete wire:loading.attr="disabled"
                                    onclick="confirmDeleteMarca({{ $item }})" />
                            @endcan
                        @endcanany
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    @if ($marcas->hasPages())
        <div class="w-full flex justify-center items-center sm:justify-end p-1 sticky -bottom-1 right-0 bg-body">
            {{ $marcas->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div wire:key="loadingmarcas" wire:loading.flex class="loading-overlay fixed hidden">
        <x-loading-next />
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar marca') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="w-full h-60 relative mb-2 shadow-md shadow-shadowminicard rounded-xl overflow-hidden">
                    <template x-if="editimage">
                        <img id="editimage" class="object-scale-down block w-full h-full" :src="editimage" />
                    </template>
                    <template x-if="!editimage">
                        @if ($marca->image)
                            <img id="editimage" class="object-scale-down block w-full h-full"
                                src="{{ getMarcaURL($marca->image->url) }}" />
                        @else
                            <x-icon-file-upload class="w-full h-full" />
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

                    @if ($marca->image)
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

                    <label for="editFileInput" type="button"
                        class="cursor-pointer text-[10px] inine-flex justify-between items-center focus:outline-none hover:ring-2 hover:ring-ringbutton py-2 px-4 rounded-lg shadow-sm text-left text-colorbutton bg-fondobutton hover:bg-fondohoverbutton hover:text-colorhoverbutton font-semibold tracking-widest">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-flex flex-shrink-0 w-6 h-6 -mt-1 mr-1"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="0" y="0" stroke="none"></rect>
                            <path
                                d="M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" />
                            <circle cx="12" cy="13" r="3" />
                        </svg>
                        SELECCIONAR LOGO
                    </label>
                    <input name="photo" id="editFileInput" accept="image/*" class="hidden disabled:opacity-25"
                        type="file" @change="loadlogo" wire:loading.attr="disabled" wire:model="logo">
                </div>
                <x-jet-input-error for="logo" class="text-center" />

                <x-label class="mt-3" value="Marca :" />
                <x-input class="block w-full" wire:model.defer="marca.name"
                    placeholder="Ingrese nombre de marca..." />
                <x-jet-input-error for="marca.name" />

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
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

        function confirmDeleteMarca(marca) {
            swal.fire({
                title: 'Eliminar marca ' + marca.name,
                text: "Se eliminará un registro de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(marca.id);
                }
            })
        }
    </script>

</div>
