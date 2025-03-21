<div>
    <div class="w-full lg:max-w-sm">
        <x-label value="Buscar :" />
        <div class="relative">
            <x-input class="block w-full disabled:bg-gray-200 pr-6" wire:model.lazy="search" placeholder="Buscar..." />
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                class="block p-1 w-auto h-full text-next-300 absolute right-1 top-0 bottom-0">
                <path
                    d="M11 6C13.7614 6 16 8.23858 16 11M16.6588 16.6549L21 21M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" />
            </svg>
        </div>
        <x-jet-input-error for="searchespecificacion" />
    </div>

    <div class="w-full mt-1 grid grid-cols-1 relative xl:grid-cols-[repeat(auto-fill,minmax(350px,1fr))] gap-5"
        id="caracteristicas">
        @if (count($caracteristicas) > 0)
            @foreach ($caracteristicas as $item)
                <x-simple-card class="w-full flex flex-col gap-2 relative justify-between p-2"
                    data-id="{{ $item->id }}">
                    <div class="w-full">
                        <div class="w-full flex gap-2 items-center">
                            @can('admin.almacen.caracteristicas.edit')
                                <button type="button"
                                    class="text-colortitleform inline-block cursor-grab flex-shrink-0 h-full handle hover:shadow hover:shadow-shadowminicard rounded-md opacity-70 hover:opacity-100 transition ease-in-out duration-150">
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

                        @if (count($item->especificacions) > 0)
                            <div class="w-full mt-2 flex gap-1 flex-wrap items-start xl:max-h-96 xl:overflow-y-auto">
                                @foreach ($item->especificacions as $itemespecif)
                                    <div
                                        class="max-w-full inline-flex gap-1 items-center text-[10px] p-1 rounded-md bg-fondospancardproduct text-textspancardproduct">
                                        <p class="flex-1">{!! nl2br($itemespecif->name) !!}</p>

                                        @canany(['admin.almacen.especificacions.edit',
                                            'admin.almacen.especificacions.delete'])
                                            <div class="flex-shrink-0">
                                                @can('admin.almacen.especificacions.edit')
                                                    <x-button-edit wire:loading.attr="disabled"
                                                        wire:click="editespecificacion({{ $itemespecif->id }})" />
                                                @endcan

                                                @can('admin.almacen.especificacions.delete')
                                                    <x-button-delete wire:loading.attr="disabled"
                                                        wire:key="delesp_{{ $itemespecif->id }}"
                                                        onclick="confirmDeleteEspec({{ $itemespecif }})" />
                                                @endcan
                                            </div>
                                        @endcanany
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="w-full flex justify-between items-end gap-2">
                        @can('admin.almacen.especificacions.create')
                            <x-button wire:loading.attr="disabled" wire:click="addespecificacion({{ $item->id }})">
                                AGREGAR</x-button>
                        @endcan

                        <div class="flex flex-wrap gap-1 items-end">
                            @if ($item->isFilterWeb())
                                <x-span-text text="MOSTRAR EN FILTROS DE PRODUCTOS WEB" class="leading-3" />
                            @endif

                            @can('admin.almacen.caracteristicas.edit')
                                <x-button-edit wire:loading.attr="disabled" wire:key="editc_{{ $item->id }}"
                                    wire:click="edit({{ $item->id }})" />
                            @endcan

                            @can('admin.almacen.caracteristicas.delete')
                                <x-button-delete wire:loading.attr="disabled"
                                    onclick="confirmDeleteCaract({{ $item }})" />
                            @endcan
                        </div>
                    </div>
                </x-simple-card>
            @endforeach
        @endif
    </div>

    @if ($caracteristicas->hasPages())
        <div class="w-full flex justify-center items-center sm:justify-end p-1 sticky -bottom-1 right-0 bg-body">
            {{ $caracteristicas->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div wire:key="loadingespecificacions" wire:loading.flex class="loading-overlay fixed hidden">
        <x-loading-next />
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar característica') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update(true)" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="caracteristica.name"
                        placeholder="Ingrese nombre categoría..." />
                    <x-jet-input-error for="caracteristica.name" />
                </div>

                @if (Module::isEnabled('Marketplace'))
                    <div class="w-full">
                        <x-label-check for="editfilterweb">
                            <x-input wire:model.defer="caracteristica.filterweb" name="filterweb" value="1"
                                type="checkbox" id="editfilterweb" />MOSTRAR EN FILTROS DE PRODUCTOS WEB
                        </x-label-check>
                        <x-jet-input-error for="caracteristica.filterweb" />
                    </div>
                @endif

                <div class="w-full flex items-end pt-4 justify-end">
                    <x-button type="submit" wire:click="update(true)" wire:loading.attr="disabled">
                        {{ __('Save and close') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="openespecificacion" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Agregar especificación') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="saveespecificacion" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Nombre :" />
                    <x-text-area class="block w-full" rows="3" wire:model.defer="name"
                        style="height: auto;"></x-text-area>
                    {{-- <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese especificación..." /> --}}
                    <x-jet-input-error for="name" />
                </div>

                <div class="w-full flex items-end gap-1 pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                    <x-button type="submit" wire:click="saveespecificacion(true)" wire:loading.attr="disabled">
                        {{ __('Save and close') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="openeditespecificacion" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar especificación') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update_especificacion(true)" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Nombre :" />
                    <x-text-area class="block w-full" rows="3" wire:model.defer="name"
                        style="height: auto;"></x-text-area>
                    <x-jet-input-error for="name" />
                    <x-jet-input-error for="especificacion.caracteristica_id" />
                </div>

                <div class="w-full flex items-end gap-1 pt-4 justify-end">
                    <x-button type="submit" wire:click="update_especificacion(true)" wire:loading.attr="disabled">
                        {{ __('Save and close') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            new Sortable(caracteristicas, {
                animation: 150,
                ghostClass: 'bg-fondospancardproduct',
                handle: '.handle',
                store: {
                    set: function(sortable) {
                        const sorts = sortable.toArray();
                        axios.post("{{ route('api.sort.caracteristicas') }}", {
                            sorts: sorts
                        }).catch(function(error) {
                            console.log(error);
                        });
                    }
                },
            })
        })

        function confirmDeleteEspec(especificacion) {
            swal.fire({
                title: 'Eliminar especificación ' + especificacion.name,
                text: "Se eliminará un registro de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deleteEspecificacion(especificacion.id);
                }
            })
        }

        function confirmDeleteCaract(caracteristica) {
            swal.fire({
                title: 'Eliminar característica ' + caracteristica.name,
                text: "Se eliminará un registro de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(caracteristica.id);
                }
            })
        }
    </script>
</div>
