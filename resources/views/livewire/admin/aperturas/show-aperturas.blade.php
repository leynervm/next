<div>

    @if (count($aperturas))
        <div class="pb-2">
            {{ $aperturas->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="block w-full">
        @if (count($aperturas))
            <x-table>
                <x-slot name="header">
                    <tr>
                        <th scope="col" class="p-2 font-medium text-left">CAJA</th>
                        <th scope="col" class="p-2 font-medium text-center">FECHA APERTURA</th>
                        <th scope="col" class="p-2 font-medium text-center">FECHA CIERRE</th>
                        <th scope="col" class="p-2 font-medium">MONTO APERTURA</th>
                        <th scope="col" class="p-2 font-medium">MONTO INGRESOS</th>
                        <th scope="col" class="p-2 font-medium">MONTO EGRESOS</th>
                        <th scope="col" class="p-2 font-medium">USUARIO</th>
                        <th scope="col" class="p-2 font-medium">CIERRE CAJA</th>
                        <th scope="col" class="p-2 font-medium">ESTADO</th>
                        <th scope="col" class="p-2 font-medium">SUCURSAL</th>
                        <th scope="col" class="p-2 font-medium">OPCIONES</th>
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @if (count($aperturas))
                        @foreach ($aperturas as $item)
                            <tr>
                                <td class="p-2">
                                    {{ $item->caja->name }}
                                </td>
                                <td class="p-2 text-center uppercase">
                                    {{ \Carbon\Carbon::parse($item->startdate)->locale('es')->isoformat('DD MMMM YYYY hh:mm A') }}
                                </td>
                                <td class="p-2 text-center uppercase">
                                    {{ \Carbon\Carbon::parse($item->expiredate)->locale('es')->isoformat('DD MMMM YYYY hh:mm A') }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $item->startmount }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $item->totalcash }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $item->totalcash }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $item->user->name }}
                                </td>
                                <td class="p-2 text-center uppercase">
                                    @if ($item->status)
                                        {{ formatDate($item->closedate) }}
                                    @else
                                        @if ($item->caja->isUsingCaja() && $item->isUsingUser())
                                            @if ($item->isExpired())
                                                <x-button class="inline-block"
                                                    wire:click="$emit('aperturas.confirmClose',{{ $item }})"
                                                    wire:loading.attr="disabled">CERRAR CAJA</x-button>
                                            @else
                                                <x-span-text text="EN USO" class="leading-3 !tracking-normal"
                                                    type="green" />
                                            @endif
                                        @endif
                                    @endif
                                </td>
                                <td class="p-2 text-center">
                                    @if ($item->status)
                                        <x-span-text text="CERRADO" class="leading-3 !tracking-normal" type="red" />
                                    @else
                                        @if ($item->isExpired())
                                            <x-span-text text="EXPIRADO" class="leading-3 !tracking-normal"
                                                type="orange" />
                                        @else
                                            <x-span-text text="ACTIVO" class="leading-3 !tracking-normal"
                                                type="green" />
                                        @endif
                                    @endif
                                </td>
                                <td class="p-2 text-center">
                                    {{ $item->caja->sucursal->name }}
                                </td>
                                <td class="p-2 text-center">
                                    @if ($item->closedate == null)
                                        <x-button-edit wire:click="edit({{ $item->id }})"
                                            wire:loading.attr="disabled" />
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </x-slot>
            </x-table>
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Aperturar caja') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update">

                <div class="w-full">
                    <x-label value="Seleccionar caja :" />
                    <x-disabled-text :text="$opencaja->caja->name ?? ' '" />
                    <x-jet-input-error for="caja_id" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="Fecha cierre :" />
                    @if ($opencaja->closedate)
                        <x-disabled-text :text="formatDate($opencaja->expiredate)" />
                    @else
                        <x-input class="block w-full" wire:model.defer="opencaja.expiredate" type="datetime-local" />
                    @endif
                    <x-jet-input-error for="opencaja.expiredate" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="Saldo inicial :" />
                    @if ($opencaja->closedate)
                        <x-disabled-text :text="$opencaja->startmount" />
                    @else
                        <x-input class="block w-full" wire:model.defer="opencaja.startmount" type="number"
                            step="0.01" min="0" />
                    @endif
                    <x-jet-input-error for="opencaja.startmount" />
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
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('aperturas.confirmClose', data => {
                swal.fire({
                    title: 'Cerrar apertura de caja con nombre ' + data.caja.name,
                    text: "Se actualizará un registro en la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('admin.aperturas.show-aperturas', 'close', data.id);
                    }
                })
            })
        })
    </script>
</div>
