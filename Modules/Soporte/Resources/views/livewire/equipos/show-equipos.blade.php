<div class="">

    @if (count($equipos))
        <div class="flex flex-wrap gap-2 justify-between">
            <x-input type="search" class="w-full md:w-80" />
            {{ $equipos->links() }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-around md:justify-start mt-2">

        @if (count($equipos))

            @foreach ($equipos as $item)
                <x-minicard :title="$item->name">
                    @if ($item->logo)
                        <x-slot name="imagen">
                            <img class="w-full h-full object-scale-down posi"
                                src="{{ asset('storage/equipos/' . $item->logo) }}" alt="">
                        </x-slot>
                    @endif

                    <x-slot name="buttons">
                        <x-button-edit wire:loading.attr="disabled" wire:target="edit({{ $item->id }})"
                            wire:click="edit({{ $item->id }})"></x-button-edit>
                        <x-button-delete wire:loading.attr="disabled" wire:target="confirmDelete({{ $item->id }})"
                            wire:click="confirmDelete({{ $item->id }})"></x-button-delete>
                    </x-slot>
                </x-minicard>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar tipo equipo') }}
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

                <div x-data="{ isUploading: @entangle('isUploading'), progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                    x-on:livewire-upload-finish="isUploading = false"
                    x-on:livewire-upload-error="$wire.emit('errorImage'), isUploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">

                    <div x-show="isUploading" class="loading-overlay">
                        <div class="spinner"></div>
                    </div>

                    @if (isset($logo))
                        <div class="w-full h-60 md:max-w-md mx-auto mb-1 duration-300">
                            <img class="w-full h-full object-scale-down" src="{{ $logo->temporaryUrl() }}"
                                alt="">
                        </div>
                    @else
                        @if ($equipo)
                            @if ($equipo->logo)
                                <div class="w-full h-60 md:max-w-md mx-auto mb-1 duration-300">
                                    <img class="w-full h-full object-scale-down"
                                        src="{{ asset('storage/equipos/' . $equipo->logo) }}" alt="">
                                </div>
                            @endif
                        @endif
                    @endif

                    <x-input-file for="{{ $identificador }}" titulo="SELECCIONAR IMAGEN" wire:loading.remove
                        wire:target="logo">
                        <input type="file" class="hidden" wire:model="logo" id="{{ $identificador }}"
                            accept="image/jpg, image/jpeg, image/png" />

                        @if (isset($logo))
                            <x-slot name="clear">
                                <x-button class="inline-flex px-6" size="xs" wire:loading.attr="disabled"
                                    wire:target="clearImage" wire:click="clearImage">
                                    Limpiar
                                </x-button>
                            </x-slot>
                        @endif
                    </x-input-file>
                </div>
                <x-jet-input-error wire:loading.remove wire:target="logo" for="logo" class="text-center" />


                <x-label class="mt-3" value="Tipo equipo :" />
                <x-input class="block w-full" wire:model.defer="equipo.name"
                    placeholder="Ingrese nombre del tipo de equipo..." />
                <x-jet-input-error for="equipo.name" />

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="update">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('livewire:load', function() {

            var toastMixin = Swal.mixin({
                toast: true,
                icon: "success",
                title: "Mensaje",
                position: "top-right",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener("mouseenter", Swal.stopTimer);
                    toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
            });

            window.addEventListener('soporte::equipos.confirmDelete', data => {
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
                        Livewire.emit('deleteEquipo', data.detail.id);
                    }
                })
            })

            window.addEventListener('soporte::equipos.deleted', event => {
                toastMixin.fire({
                    title: 'Eliminado correctamente'
                });
            })
        })
    </script>
</div>
