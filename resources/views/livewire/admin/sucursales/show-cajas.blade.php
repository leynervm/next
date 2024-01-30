<div>
    <x-form-card titulo="CAJAS DE PAGO">
        <div class="w-full flex flex-wrap lg:flex-nowrap gap-3">
            <div class="w-full lg:w-80 xl:w-96 lg:flex-shrink-0 relative" x-data="{ savingserie: false }">
                <form wire:submit.prevent="save" class="flex flex-col gap-2">
                    <div class="w-full">
                        <x-label value="Nombre caja :" />
                        <x-input class="block w-full" wire:model.defer="name" placeholder="Nombre de caja..." />
                        <x-jet-input-error for="name" />
                    </div>

                    <div class="w-full flex pt-4 justify-end">
                        <x-button type="submit" wire:loading.attr="disabled" wire:target="save">
                            {{ __('REGISTRAR') }}
                        </x-button>
                    </div>
                </form>

                <div x-show="savingserie" class="loading-overlay rounded" wire:loading wire:loading.flex>
                    <x-loading-next />
                </div>
            </div>
            <div class="w-full">
                @if (count($sucursal->cajas))
                    <div class="w-full flex gap-3 flex-wrap justify-start">
                        @foreach ($sucursal->cajas as $item)
                            <x-minicard :title="null" :content="$item->name" size="md" class="">
                                <x-slot name="buttons">
                                    @if ($item->trashed())
                                        <button wire:click="restorecaja({{ $item->id }})"
                                            wire:loading.attr="disabled" type="button"
                                            class="block p-0.5 rounded-sm text-green-500 disabled:opacity-75">
                                            <svg class="w-5 h-5 scale-125 rounded-sm text-neutral-300"
                                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path fill="currentColor"
                                                    d="M11 12C11 13.6569 9.65685 15 8 15C6.34315 15 5 13.6569 5 12C5 10.3431 6.34315 9 8 9C9.65685 9 11 10.3431 11 12Z" />
                                                <path
                                                    d="M16 6H8C4.68629 6 2 8.68629 2 12C2 15.3137 4.68629 18 8 18H16C19.3137 18 22 15.3137 22 12C22 8.68629 19.3137 6 16 6Z" />
                                            </svg>
                                        </button>
                                    @else
                                        <x-button-edit wire:loading.attr="disabled"
                                            wire:click="edit({{ $item->id }})" />
                                        <x-button-delete wire:loading.attr="disabled"
                                            wire:click="$emit('sucursales.confirmDeleteCaja', {{ $item }})" />
                                    @endif
                                </x-slot>
                            </x-minicard>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </x-form-card>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar caja') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">
                <div class="w-full">
                    <x-label value="Nombre caja :" />
                    <x-input class="block w-full" wire:model.defer="caja.name" placeholder="Nombre de caja..." />
                    <x-jet-input-error for="caja.name" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="update">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        document.addEventListener('livewire:load', function() {

            Livewire.on('confirmRestorecaja', data => {
                console.log(data);
                swal.fire({
                    title: 'Habilitar registro de caja, ' + data.name,
                    text: "Actualizar registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.restorecaja(data.id);
                    }
                })
            })


            Livewire.on('sucursales.confirmDeleteCaja', data => {
                swal.fire({
                    title: 'Eliminar registro con nombre ' + data.name,
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
        })
    </script>
</div>
