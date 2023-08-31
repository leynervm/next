<div class="">

    @if (count($entornos))
        <div class="pb-2">
            {{ $entornos->links() }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">

        @if (count($entornos))
            @foreach ($entornos as $item)
                <x-minicard :title="$item->name" :alignFooter="$item->default == 1 ? 'justify-between' : 'justify-end'" size="md">

                    @if ($item->requiredirection == 1)
                        <x-slot name="content">
                            Requiere dirección
                        </x-slot>
                    @endif

                    <x-slot name="buttons">

                        @if ($item->default == 1)
                            <span class="bg-green-100 text-green-500 p-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </span>
                        @endif

                        <div class="ml-auto">
                            <x-button-edit wire:loading.attr="disabled" wire:target="edit({{ $item->id }})"
                                wire:click="edit({{ $item->id }})"></x-button-edit>
                            <x-button-delete wire:loading.attr="disabled"
                                wire:target="confirmDelete({{ $item->id }})"
                                wire:click="confirmDelete({{ $item->id }})"></x-button-delete>
                        </div>
                    </x-slot>
                </x-minicard>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar entorno atención') }}
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

                <x-label value="Entorno Atención :" />
                <x-input class="block w-full" wire:model.defer="entorno.name"
                    placeholder="Ingrese entorno de atención..." />
                <x-jet-input-error for="entorno.name" />

                <x-label class="mt-2 flex items-center gap-1 cursor-pointer" for="requiredirection_edit">
                    <x-input wire:model.defer="entorno.requiredirection" type="checkbox" id="requiredirection_edit"
                        value="1" />
                    Requiere agregar lugar atención.
                </x-label>
                <x-jet-input-error for="entorno.requiredirection" />

                <x-label class="mt-2 flex items-center gap-1 cursor-pointer" for="default_edit">
                    <x-input wire:model.defer="entorno.default" type="checkbox" id="default_edit" value="1" />
                    Definir valor como predeterminado.
                </x-label>
                <x-jet-input-error for="entorno.default" />

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

            window.addEventListener('soporte::entornos.confirmDelete', data => {
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
                        Livewire.emit('deleteEntorno', data.detail.id);
                    }
                })
            })

            window.addEventListener('soporte::entornos.deleted', event => {
                toastMixin.fire({
                    title: 'Eliminado correctamente'
                });
            })
        })
    </script>
</div>
