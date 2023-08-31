<div class="">

    @if (count($priorities))
        <div class="pb-2">
            {{ $priorities->links() }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start">

        @if (count($priorities))
            @foreach ($priorities as $item)
                <x-minicard :title="$item->name" :alignFooter="$item->default == 1 ? 'justify-between' : 'justify-end'" size="md">

                    <x-slot name="spanColor">
                        <span class="block absolute w-4 h-2 rounded left-1/2 -translate-x-1/2 -top-2"
                            style="background: {{ $item->color }}"></span>
                    </x-slot>

                    <x-slot name="buttons">

                        {{-- @if ($item->default == 1)
                            <span class="bg-green-100 text-green-500 p-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </span>
                        @endif --}}

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
            {{ __('Actualizar prioridad atención') }}
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

                <x-label value="Prioridad :" />
                <x-input class="block w-full" wire:model.defer="priority.name" placeholder="Ingrese nombre de prioridad..." />
                <x-jet-input-error for="priority.name" />

                <div class="w-full">
                    <x-label value="Color :" class="mt-2" />
                    <input wire:model.defer="priority.color" type="color" value="#000000" class="h-14 w-14" />
                    <x-jet-input-error for="priority.color" />
                </div>

                {{-- <x-label class="mt-2 inline-flex items-center gap-1 cursor-pointer" for="default">
                    <x-input wire:model.defer="default" type="checkbox" id="default" value="1" />
                    Definir valor como predeterminado
                </x-label>
                <x-jet-input-error for="default" /> --}}

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

            window.addEventListener('soporte::priorities.confirmDelete', data => {
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
                        Livewire.emit('deletePriority', data.detail.id);
                    }
                })
            })

            window.addEventListener('soporte::priorities.deleted', event => {
                toastMixin.fire({
                    title: 'Eliminado correctamente'
                });
            })
        })
    </script>
</div>
