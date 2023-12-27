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
                        <th scope="col" class="p-2 font-medium">ESTADO</th>
                        <th scope="col" class="p-2 font-medium">SUCURSAL</th>
                        <th scope="col" class="p-2 font-medium">OPCIONES</th>
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @if (count($aperturas))
                        @foreach ($aperturas as $item)
                            <tr>
                                <td class="p-2 text-xs">
                                    {{ $item->caja->name }}
                                </td>
                                <td class="p-2 text-center text-xs uppercase">
                                    {{ \Carbon\Carbon::parse($item->startdate)->locale('es')->isoformat('DD MMMM YYYY hh:mm:ss A') }}
                                </td>
                                <td class="p-2 text-center text-xs uppercase">
                                    @if ($item->expiredate)
                                        {{ \Carbon\Carbon::parse($item->expiredate)->locale('es')->isoformat('DD MMMM YYYY hh:mm:ss A') }}
                                    @else
                                        @if ($item->caja->isUsing())
                                            @if ($item->isUsing())
                                                <x-button class="inline-block"
                                                    wire:click="$emit('aperturas.confirmClose',{{ $item }})"
                                                    wire:loading.attr="disabled">CERRAR CAJA</x-button>
                                            @endif
                                        @endif
                                    @endif
                                </td>
                                <td class="p-2 text-center text-xs">
                                    {{ $item->startmount }}
                                </td>
                                <td class="p-2 text-center text-xs">
                                    {{ $item->totalcash }}
                                </td>
                                <td class="p-2 text-center text-xs">
                                    {{ $item->totalcash }}
                                </td>
                                <td class="p-2 text-center text-xs">
                                    {{ $item->user->name }}
                                </td>
                                <td class="p-2 text-xs text-center">
                                    @if ($item->expiredate)
                                        <small class="p-1 text-xs leading-3 rounded bg-red-500 text-white inline-block">
                                            Cerrado</small>
                                    @else
                                        <small
                                            class="p-1 text-xs leading-3 rounded bg-green-500 text-white inline-block">
                                            Activo</small>
                                    @endif
                                </td>
                                <td class="p-2 text-xs text-center">
                                    {{ $item->caja->sucursal->name }}
                                </td>
                                <td class="p-2 text-center text-xs">
                                    <x-button-edit wire:click="edit({{ $item->id }})"
                                        wire:loading.attr="disabled" />
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
                    <x-label value="Saldo inicial :" />
                    <x-input class="block w-full" wire:model.defer="opencaja.startmount" type="number" step="0.01"
                        min="0" />
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
