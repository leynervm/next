<div>
    @if ($openboxes->hasPages())
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
                    <th scope="col" class="p-2 font-medium">SALDO</th>
                    <th scope="col" class="p-2 font-medium">CERRAR CAJA</th>
                    <th scope="col" class="p-2 font-medium">ESTADO</th>
                    <th scope="col" class="p-2 font-medium">SUCURSAL / USUARIO</th>
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
                                <p class="text-[10px] text-colorsubtitleform">
                                    {{ formatDate($item->monthbox->month, 'MMMM Y') }}</p>
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
                                SIN SALDO
                            </td>
                            <td class="p-2 text-center uppercase">
                                @if ($item->isClosed())
                                    {{ formatDate($item->closedate) }}
                                @else
                                    @if ($item->isExpired() || auth()->user()->isAdmin())
                                        @canany(['admin.cajas.aperturas.close', 'admin.cajas.aperturas.closeothers'])
                                            <x-button class="inline-block" onclick="confirmClose({{ $item }})"
                                                wire:loading.attr="disabled">
                                                CERRAR CAJA
                                            </x-button>
                                        @endcanany
                                    @else
                                        <x-span-text text="EN USO" class="leading-3 !tracking-normal" type="green" />
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
                                <p class="text-[10px] leading-3 text-colorsubtitleform">
                                    {{ $item->user->name }}
                                </p>
                            </td>
                            @can('admin.cajas.aperturas.edit')
                                <td class="p-2 text-center">
                                    @if ($item->isUsing() || auth()->user()->isAdmin())
                                        @if (is_null($item->closedate))
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
                    @if ($openbox->isClosed())
                        <x-disabled-text :text="formatDate($openbox->expiredate)" />
                    @else
                        <x-input class="block w-full" wire:model.defer="openbox.expiredate" type="datetime-local" />
                    @endif
                    <x-jet-input-error for="openbox.expiredate" />
                </div>

                <div class="w-full">
                    <x-label value="Monto apertura :" />
                    {{-- @if ($openbox->isClosed()) --}}
                    <x-disabled-text :text="$openbox->apertura" />
                    {{-- @else
                        <x-input class="block w-full" wire:model.defer="openbox.apertura" type="number" step="0.01"
                            min="0" onkeypress="return validarDecimal(event, 8)" />
                    @endif --}}
                    <x-jet-input-error for="openbox.apertura" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        function confirmClose(openbox) {
            swal.fire({
                title: 'Cerrar apertura ' + openbox.box.name,
                text: "La apertura de caja dejará de estar disponible para realizar cualquier tipo de movimientos de pagos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.close(openbox.id);
                }
            })
        }
    </script>
</div>
