<div class="">

    @if (count($typenotifications))
        <div class="mb-2">
            {{ $typenotifications->links() }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-around md:justify-start">

        @livewire('soporte::tiponotificaciones.create-tipo-notificacion')

        @if (count($typenotifications))

            @foreach ($typenotifications as $item)
                <x-minicard :title="$item->name">
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
            {{ __('Actualizar tipo notificación') }}
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
                <x-label value="Tipo notificación :" />
                <x-input class="block w-full" wire:model.defer="typenotification.name"
                    placeholder="Ingrese nombre del tipo de notificación..." />
                <x-jet-input-error for="typenotification.name" />

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="save">
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

            window.addEventListener(
                'soporte::tiponotificaciones.confirmDelete', data => {
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
                            Livewire.emit('deleteTipoNotificacion', data.detail.id);
                        }
                    })
                })

            window.addEventListener('soporte::tiponotificaciones.deleted', data => {
                toastMixin.fire({
                    title: 'Eliminado correctamente'
                });
            })
        })
    </script>
</div>
