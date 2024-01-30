<div>
    @if (count($caracteristicas))
        <div class="pb-2">
            {{ $caracteristicas->links() }}
        </div>
    @endif


    @if (count($caracteristicas))
        <div class="flex flex-wrap gap-5">
            @foreach ($caracteristicas as $item)
                <x-form-card :titulo="$item->name" class="w-full xl:max-w-md" :alignFooter="$item->view == 1 ? 'justify-between' : 'justify-end'">
                    <div class="w-full flex flex-col gap-2 justify-between h-full">
                        @if (count($item->especificacions))
                            <div class="w-full flex gap-1 flex-wrap items-start">
                                @foreach ($item->especificacions as $itemespecif)
                                    <div
                                        class="inline-flex gap-1 items-center text-[10px] px-1 rounded-lg bg-fondospancardproduct text-textspancardproduct">
                                        <span class="mr-2">{{ $itemespecif->name }}</span>
                                        <x-button-edit wire:loading.attr="disabled"
                                            wire:click="editespecificacion({{ $itemespecif->id }})" />
                                        <x-button-delete wire:loading.attr="disabled"
                                            wire:click="$emit('especificaciones.confirmDelete',{{ $itemespecif }})" />
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="w-full flex justify-between gap-2">
                            <x-button wire:loading.attr="disabled"
                                wire:click="openmodalespecificacion({{ $item->id }})">
                                AGREGAR
                            </x-button>

                            <div class="flex flex-wrap gap-1 items-end">
                                @if ($item->view == 1)
                                    <x-span-text text="AUTOCOMPLETAR EQUIPO" class="leading-3" />
                                @endif
                                <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})" />
                                <x-button-delete wire:loading.attr="disabled"
                                    wire:click="$emit('caracteristica.confirmDelete',{{ $item }})" />
                            </div>
                        </div>
                    </div>
                </x-form-card>
            @endforeach
        </div>
    @endif


    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar característica') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="caracteristica.name"
                        placeholder="Ingrese nombre categoría..." />
                    <x-jet-input-error for="caracteristica.name" />
                </div>

                <div class="w-full">
                    <x-label-check for="edit_view">
                        <x-input wire:model.defer="caracteristica.view" name="view" value="1" type="checkbox"
                            id="edit_view" />
                        MOSTRAR EN AUTOCOMPLETADO EQUIPOS
                    </x-label-check>
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="openespecificacion" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Agregar especificación') }}
            <x-button-close-modal wire:click="$toggle('openespecificacion')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="add_especificacion" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese especificación..." />
                    <x-jet-input-error for="name" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="openeditespecificacion" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar especificación') }}
            <x-button-close-modal wire:click="$toggle('openeditespecificacion')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update_especificacion" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese especificación..." />
                    <x-jet-input-error for="name" />
                    <x-jet-input-error for="especificacion.caracteristica_id" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('caracteristica.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar registro con nombre: ' + data.name,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.delete(data.id);
                    }
                })
            })

            Livewire.on('especificaciones.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar registro con nombre: ' + data.name,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.deleteEspecificacion(data.id);
                    }
                })
            })
        })
    </script>
</div>
