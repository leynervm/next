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
                                {{ __('Save') }}</x-button>
                        </div>
                    </form>
                </div>
            @endcan

            @if (count($sucursal->boxes))
                <div class="w-full flex-1 flex gap-3 flex-wrap justify-start">
                    @foreach ($sucursal->boxes as $item)
                        <x-minicard :title="null" size="lg" class="">
                            <h1 class="text-center text-xs font-semibold">{{ $item->name }}</h1>
                            <p class="text-center text-xl font-semibold leading-4 text-colorsubtitleform">
                                <small class="text-[10px] font-medium">APERTURA <br> S/.</small>
                                {{ $item->apertura }}
                            </p>

                            @can('admin.administracion.sucursales.boxes.edit')
                                <x-slot name="buttons">
                                    @if ($item->trashed())
                                        <x-button-toggle onclick="confirmRestorecaja({{ $item }})"
                                            wire:loading.attr="disabled" wire:key="restorebox_{{ $item->id }}"
                                            :checked="false" />
                                    @else
                                        <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})"
                                            wire:key="editbox_{{ $item->id }}" />
                                        <x-button-toggle onclick="confirmDeleteCaja({{ $item }})"
                                            wire:loading.attr="disabled" wire:key="deletebox_{{ $item->id }}" />
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
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        function confirmRestorecaja(box) {
            swal.fire({
                title: 'ACTIVAR CAJA CON NOMBRE ' + box.name,
                text: null,
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
                title: 'DESACTIVAR CAJA CON NOMBRE ' + box.name,
                text: null,
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
