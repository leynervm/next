<div class="">

    @if (count($caracteristicas))
        <div class="pb-2">
            {{ $caracteristicas->links() }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">
        @if (count($caracteristicas))
            @foreach ($caracteristicas as $item)
                <x-card-next :titulo="$item->name" class="w-full sm:w-96" :alignFooter="$item->view == 1 ? 'justify-between' : 'justify-end'">

                    @if (count($item->especificacions))
                        <div class="w-full flex gap-1 flex-wrap items-start">
                            @foreach ($item->especificacions as $itemespecif)
                                <div
                                    class="inline-flex gap-1 items-center text-[10px] px-1 rounded-lg bg-fondospancardproduct text-textspancardproduct">
                                    <span class="mr-2">{{ $itemespecif->name }}</span>
                                    <x-button-edit wire:loading.attr="disabled" wire:target="editespecificacion"
                                        wire:click="editespecificacion({{ $itemespecif->id }})"></x-button-edit>
                                    <x-button-delete wire:loading.attr="disabled"
                                        wire:target="confirmDeleteEspecificacion"
                                        wire:click="confirmDeleteEspecificacion({{ $itemespecif->id }})"></x-button-delete>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-3">
                        <x-button wire:loading.attr="disabled" wire:target="openmodalespecificacion"
                            wire:click="openmodalespecificacion({{ $item->id }})">
                            AGREGAR
                        </x-button>
                    </div>

                    <x-slot name="footer">
                        @if ($item->view == 1)
                            <span
                                class="text-[10px] p-1 font-semibold rounded-lg bg-fondospancardproduct text-textspancardproduct">Autocompletar
                                equipo</span>
                        @endif
                        <div>
                            <x-button-edit wire:loading.attr="disabled" wire:target="edit"
                                wire:click="edit({{ $item->id }})"></x-button-edit>
                            <x-button-delete wire:loading.attr="disabled" wire:target="confirmDelete"
                                wire:click="confirmDelete({{ $item->id }})"></x-button-delete>
                        </div>
                    </x-slot>
                </x-card-next>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar característica') }}
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
                <x-input class="block w-full" wire:model.defer="caracteristica.name"
                    placeholder="Ingrese nombre categoría..." />
                <x-jet-input-error for="caracteristica.name" />

                <div class="mt-3 mb-1">
                    <x-label textSize="[10px]"
                        class="inline-flex items-center tracking-widest font-semibold gap-2 cursor-pointer bg-next-100 rounded-lg p-1"
                        for="edit_view">
                        <x-input wire:model.defer="caracteristica.view" name="view" type="checkbox" id="edit_view"
                            value="1" />
                        MOSTRAR EN AUTOCOMPLETADO EQUIPOS
                    </x-label>
                    <x-jet-input-error for="caracteristica.view" />
                </div>

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="update">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <x-jet-dialog-modal wire:model="openespecificacion" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Agregar especificación') }}
            <x-button-add wire:click="$toggle('openespecificacion')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="add_especificacion">

                <x-label value="Nombre :" />
                <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese especificación..." />
                <x-jet-input-error for="name" />

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="update">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="openeditespecificacion" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar especificación') }}
            <x-button-add wire:click="$toggle('openeditespecificacion')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update_especificacion">
                <x-label value="Nombre :" />
                <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese especificación..." />
                <x-jet-input-error for="name" />
                <x-jet-input-error for="especificacion.caracteristica_id" />

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="update_especificacion">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('livewire:load', function() {
            window.addEventListener('caracteristica.confirmDelete', data => {
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
                        Livewire.emitTo('almacen::especificaciones.show-especificaciones', 'delete',
                            data
                            .detail.id);
                    }
                })
            })

            window.addEventListener('especificaciones.confirmDelete', data => {
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
                        Livewire.emitTo('almacen::especificaciones.show-especificaciones',
                            'deleteEspecificacion',
                            data
                            .detail.id);
                    }
                })
            })
        })
    </script>
</div>
