<div>
    <div class="w-full grid grid-cols-1 xs:grid-cols-2 gap-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-5 self-end">
        <div class="w-full">
            <x-label value="Característica :" />
            <div class="relative">
                <x-input class="block w-full disabled:bg-gray-200 pr-6" wire:model.lazy="search" placeholder="Buscar..." />
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="block p-1 w-auto h-full text-next-300 absolute right-1 top-0 bottom-0">
                    <path
                        d="M11 6C13.7614 6 16 8.23858 16 11M16.6588 16.6549L21 21M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" />
                </svg>
            </div>
            <x-jet-input-error for="search" />
        </div>

        <div class="w-full">
            <x-label value="Especificación :" />
            <div class="relative">
                <x-input class="block w-full disabled:bg-gray-200 pr-6" wire:model.lazy="searchespecificacion"
                    placeholder="Buscar..." />
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="block p-1 w-auto h-full text-next-300 absolute right-1 top-0 bottom-0">
                    <path
                        d="M11 6C13.7614 6 16 8.23858 16 11M16.6588 16.6549L21 21M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" />
                </svg>
            </div>
            <x-jet-input-error for="searchespecificacion" />
        </div>

        @if (!empty($search) || !empty($searchespecificacion))
            <div class="inline-flex items-end">
                <button x-on:click="$wire.searchespecificacion = '';$wire.search = ''" id="reset-filters" role="button"
                    aria-label="Resetear filtros"
                    class="bg-fondospancardproduct text-textspancardproduct p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill-rule="evenodd" clip-rule="evenodd"
                        fill="currentColor" class="size-5 block text-colorsubtitleform">
                        <path
                            d="M21.4356 2.5218C21.8377 2.90032 21.8569 3.53319 21.4784 3.93537L14.8289 11.0004L13.4141 9.58556L20.022 2.56464C20.4005 2.16246 21.0334 2.14329 21.4356 2.5218ZM11.1141 9.8569C11.4092 9.67983 11.787 9.72634 12.0303 9.96969L14.5303 12.4697C14.671 12.6103 14.75 12.8011 14.75 13C14.75 15.6795 13.814 17.8177 12.8877 19.2772C12.4241 20.0075 11.9599 20.573 11.6089 20.9585C11.4396 21.1445 11.1279 21.4442 11.0102 21.5573L11.01 21.5575L11.01 21.5575L10.9977 21.5693C10.8136 21.7275 10.5643 21.7869 10.3287 21.7285C8.22723 21.2085 6.31516 20.2792 4.86851 18.5743C4.55974 18.2104 4.27528 17.8148 4.01631 17.3852C4.46585 17.4158 5.01987 17.425 5.62737 17.3722C7.1693 17.2381 9.14788 16.694 10.5761 14.9802C10.8412 14.662 10.7982 14.189 10.48 13.9239C10.1618 13.6587 9.6889 13.7017 9.42373 14.0199C8.35191 15.3061 6.83049 15.7619 5.49742 15.8778C4.83657 15.9353 4.24455 15.9066 3.81796 15.8632C3.76922 15.8583 3.72288 15.8545 3.67748 15.8508H3.67747H3.67746C3.53541 15.8392 3.40251 15.8283 3.23357 15.7848C2.72636 14.4883 2.39274 12.9551 2.25226 11.1471C2.23306 10.9001 2.33712 10.6595 2.5303 10.5043C2.72348 10.3491 2.98083 10.2994 3.21793 10.3714C6.09614 11.2454 8.85755 11.2108 11.1141 9.8569ZM5 6.5C5 5.67157 5.67157 5 6.5 5C7.32843 5 8 5.67157 8 6.5C8 7.32843 7.32843 8 6.5 8C5.67157 8 5 7.32843 5 6.5ZM12 3.95238C12 3.4264 11.5523 3 11 3C10.4477 3 10 3.4264 10 3.95238V4.04762C10 4.5736 10.4477 5 11 5C11.5523 5 12 4.5736 12 4.04762V3.95238Z" />
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <div class="w-full mt-1 grid grid-cols-1 relative xl:grid-cols-[repeat(auto-fill,minmax(350px,1fr))] gap-2"
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
