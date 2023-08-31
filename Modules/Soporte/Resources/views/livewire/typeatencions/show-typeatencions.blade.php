<div class="">

    @if (count($atenciontypes))
        <div class="pb-2">
            {{ $atenciontypes->links() }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">

        @if (count($atenciontypes))
            @foreach ($atenciontypes as $item)
                <x-minicard :title="$item->name" size="lg" textContentSize="[10px]">
                    <x-slot name="content">
                        {{ $item->atencion->name }}
                    </x-slot>
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
            {{ __('Actualizar tipo atención') }}
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
                <x-label value="Tipo atención :" />
                <x-input class="block w-full" wire:model.defer="atenciontype.name"
                    placeholder="Ingrese nombre del tipo de atención..." />
                <x-jet-input-error for="atenciontype.name" />

                <x-label class="mt-2" value="Atención :" />
                <x-select class="block w-full" wire:model.defer="atenciontype.atencion_id">
                    <x-slot name="options">
                        @if (count($atenciones))
                            @foreach ($atenciones as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        @endif
                    </x-slot>
                </x-select>
                <x-jet-input-error for="atenciontype.atencion_id" />

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

            window.addEventListener('soporte::typeatencions.confirmDelete', data => {
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
                        Livewire.emit('deleteAtenciontype', data.detail.id);
                    }
                })
            })

            window.addEventListener('soporte::typeatencions.deleted', event => {
                toastMixin.fire({
                    title: 'Eliminado correctamente'
                });
            })
        })
    </script>
</div>
