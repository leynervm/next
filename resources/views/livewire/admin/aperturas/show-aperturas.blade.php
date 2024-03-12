<div>
    @if (count($openboxes))
        <div class="">
            {{ $openboxes->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="block w-full mt-1">
        <x-table>
            <x-slot name="header">
                <tr>
                    <th scope="col" class="p-2 font-medium text-left">CAJA</th>
                    <th scope="col" class="p-2 font-medium text-center">FECHA APERTURA</th>
                    <th scope="col" class="p-2 font-medium text-center">FECHA CIERRE</th>
                    <th scope="col" class="p-2 font-medium">APERTURA</th>
                    <th scope="col" class="p-2 font-medium">EFECTIVO</th>
                    <th scope="col" class="p-2 font-medium">TRANSFERENCIAS</th>
                    <th scope="col" class="p-2 font-medium">USUARIO</th>
                    <th scope="col" class="p-2 font-medium">CIERRE CAJA</th>
                    <th scope="col" class="p-2 font-medium">ESTADO</th>
                    <th scope="col" class="p-2 font-medium">SUCURSAL</th>
                    @can('admin.cajas.aperturas.edit')
                        <th scope="col" class="p-2 font-medium">OPCIONES</th>
                    @endcan
                </tr>
            </x-slot>
            @if (count($openboxes))
                <x-slot name="body">
                    @foreach ($openboxes as $item)
                        <tr>
                            <td class="p-2">
                                {{ $item->box->name }}
                            </td>
                            <td class="p-2 text-center uppercase">
                                {{ formatDate($item->startdate) }}
                            </td>
                            <td class="p-2 text-center uppercase">
                                {{ formatDate($item->expiredate) }}
                            </td>
                            <td class="p-2 text-center">
                                {{ $item->apertura }}
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
                                @if ($item->isClosed())
                                    {{ formatDate($item->closedate) }}
                                @else
                                    @if ($item->isUsing())
                                        @if ($item->isExpired())
                                            @can('admin.cajas.aperturas.close')
                                                <x-button class="inline-block"
                                                    wire:click="$emit('openbox.confirmClose',{{ $item }})"
                                                    wire:loading.attr="disabled">CERRAR CAJA</x-button>
                                            @endcan
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
                                        <x-span-text text="ACTIVO" class="leading-3 !tracking-normal" type="green" />
                                    @endif
                                @endif
                            </td>
                            <td class="p-2 text-center">
                                {{ $item->sucursal->name }}
                            </td>
                            @can('admin.cajas.aperturas.edit')
                                <td class="p-2 text-center">
                                    @if ($item->isUsing())
                                        @if ($item->closedate == null)
                                            <x-button-edit wire:click="edit({{ $item->id }})"
                                                wire:loading.attr="disabled" />
                                        @endif
                                    @endif
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                </x-slot>
            @endif
        </x-table>
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar apertura caja') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="w-full flex flex-col gap-2">

                <div class="w-full">
                    <x-label value="Caja :" />
                    <x-disabled-text :text="$openbox->box->name ?? ' '" />
                    <x-jet-input-error for="caja_id" />
                </div>

                <div class="w-full">
                    <x-label value="Fecha apertura :" />
                    <x-disabled-text :text="formatDate($openbox->startdate)" />
                </div>

                <div class="w-full">
                    <x-label value="Fecha cierre :" />
                    <x-disabled-text :text="formatDate($openbox->expiredate)" />
                </div>

                <div class="w-full">
                    <x-label value="Saldo apertura :" />
                    @if ($openbox->isClosed())
                        <x-disabled-text :text="$openbox->startmount" />
                    @else
                        <x-input class="block w-full" wire:model.defer="openbox.apertura" type="number" step="0.01"
                            min="0" onkeypress="return validarDecimal(event, 8)" />
                    @endif
                    <x-jet-input-error for="openbox.apertura" />
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
            Livewire.on('openbox.confirmClose', data => {
                swal.fire({
                    title: 'Cerrar apertura ' + data.box.name,
                    text: "Se actualizará un registro en la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.close(data.id);
                    }
                })
            })
        })
    </script>
</div>
