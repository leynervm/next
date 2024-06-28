<div>
    <div class="loading-overlay fixed hidden" wire:loading.flex>
        <x-loading-next />
    </div>

    <x-form-card titulo="CAJAS DE PAGO">
        <div class="w-full flex flex-wrap lg:flex-nowrap gap-3">
            @can('admin.administracion.sucursales.boxes.edit')
                <div class="w-full lg:w-80 xl:w-96 lg:flex-shrink-0 relative" x-data="{ savingserie: false }">
                    <form wire:submit.prevent="save" class="flex flex-col gap-2">
                        <div class="w-full">
                            <x-label value="Nombre caja :" />
                            <x-input class="block w-full" wire:model.defer="name" placeholder="Nombre de caja..." />
                            <x-jet-input-error for="name" />
                        </div>
                        <div class="w-full">
                            <x-label value="Monto predeterminado apertura :" />
                            <x-input class="block w-full" wire:model.defer="apertura" placeholder="0.00" type="number"
                                onkeypress="return validarDecimal(event, 8)" />
                            <x-jet-input-error for="apertura" />
                        </div>

                        <div class="w-full flex pt-4 justify-end">
                            <x-button type="submit" wire:loading.attr="disabled" wire:target="save">
                                {{ __('REGISTRAR') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            @endcan

            @if (count($sucursal->boxes))
                <div class="w-full flex-1 flex gap-3 flex-wrap justify-start">
                    @foreach ($sucursal->boxes as $item)
                        <x-minicard :title="$item->name" :content="'APERTURA S/. ' . $item->apertura" size="lg" class="">
                            @can('admin.administracion.sucursales.boxes.edit')
                                <x-slot name="buttons">
                                    @if ($item->trashed())
                                        <button onclick="confirmRestorecaja({{ $item }})"
                                            wire:loading.attr="disabled" type="button"
                                            wire:key="restorebox_{{ $item->id }}"
                                            class="block p-0.5 rounded-sm disabled:opacity-75">
                                            <svg class="w-5 h-5 scale-125 rounded-sm text-neutral-300" viewBox="0 0 24 24"
                                                fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path fill="currentColor"
                                                    d="M11 12C11 13.6569 9.65685 15 8 15C6.34315 15 5 13.6569 5 12C5 10.3431 6.34315 9 8 9C9.65685 9 11 10.3431 11 12Z" />
                                                <path
                                                    d="M16 6H8C4.68629 6 2 8.68629 2 12C2 15.3137 4.68629 18 8 18H16C19.3137 18 22 15.3137 22 12C22 8.68629 19.3137 6 16 6Z" />
                                            </svg>
                                        </button>
                                    @else
                                        <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})"
                                            wire:key="editbox_{{ $item->id }}" />
                                        <x-button-delete wire:loading.attr="disabled"
                                            onclick="confirmDeleteCaja({{ $item }})"
                                            wire:key="deletebox_{{ $item->id }}" />
                                    @endif
                                </x-slot>
                            @endcan
                        </x-minicard>
                    @endforeach
                </div>
            @endif
        </div>
    </x-form-card>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar caja') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Nombre caja :" />
                    <x-input class="block w-full" wire:model.defer="box.name" placeholder="Nombre de caja..." />
                    <x-jet-input-error for="box.name" />
                </div>
                <div class="w-full">
                    <x-label value="Monto predeterminado apertura :" />
                    <x-input class="block w-full" wire:model.defer="box.apertura" placeholder="0.00" type="number"
                        onkeypress="return validarDecimal(event, 8)" />
                    <x-jet-input-error for="box.apertura" />
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
        function confirmRestorecaja(box) {
            swal.fire({
                title: 'Habilitar registro, ' + box.name,
                text: "Actualizar registro de la base de datos, se habilitará una nueva caja.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.restorecaja(box.id);
                }
            })
        }

        function confirmDeleteCaja(box) {
            swal.fire({
                title: 'Eliminar registro, ' + box.name,
                text: "Se deshabilitará un registro de la base de datos, pero seguirá mostrando sus movimientos registrados.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(box.id);
                }
            })
        }
    </script>
</div>
