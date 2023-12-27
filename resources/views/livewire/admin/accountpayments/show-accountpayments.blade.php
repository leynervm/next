<div class="">

    @if ($cuentas->hasPages())
        <div class="pb-2">
            {{ $cuentas->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <x-table>
        <x-slot name="header">
            <tr>
                <th scope="col" class="p-2 font-medium text-left">N° CUENTA</th>
                <th scope="col" class="p-2 font-medium text-center">BANCO</th>
                {{-- <th scope="col" class="p-2 font-medium">FORMAS PAGO</th> --}}
                <th scope="col" class="p-2 font-medium">ESTADO</th>
                <th scope="col" class="p-2 font-medium">OPCIONES</th>
            </tr>
        </x-slot>
        <x-slot name="body">
            @if (count($cuentas))
                @foreach ($cuentas as $item)
                    <tr>
                        <td class="p-2 text-xs flex flex-wrap items-center gap-1">
                            @if ($item->default)
                                <span class="bg-green-100 text-green-500 p-1 rounded-full block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </span>
                            @endif
                            <div>
                                <p class="text-xs font-medium leading-3">N° CTA: {{ $item->account }}</p>
                                <p class="text-[10px] leading-4">{{ $item->descripcion }}</p>
                            </div>
                        </td>
                        <td class="p-2 text-xs text-center">{{ $item->banco->name }}</td>
                        <td class="p-2 text-xs text-center">
                            @if ($item->status)
                                <small class="p-1 text-xs leading-3 rounded bg-red-500 text-white inline-block">
                                    No disponible</small>
                            @else
                                <small class="p-1 text-xs leading-3 rounded bg-green-500 text-white inline-block">
                                    Activo</small>
                            @endif
                        </td>
                        <td class="p-2 text-xs text-center">
                            <x-button-edit wire:click="edit({{ $item->id }})" wire:loading.attr="disabled"
                                wire:target="edit" />
                            <x-button-delete wire:click="$emit('accountpayments.confirmDelete',{{ $item }})"
                                wire:loading.attr="disabled" wire:target="confirmDelete" />
                        </td>
                    </tr>
                @endforeach
            @endif
        </x-slot>
    </x-table>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar cuenta pago') }}
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" id="form_edit_accountpayment">

                <div x-data="{ banco_id: @entangle('cuenta.banco_id') }" x-init="select2BancoAlpine" wire:ignore>
                    <x-label value="Tipo banco :" />
                    <x-select class="block w-full" x-ref="select" id="editbanco_id" data-dropdown-parent="null">
                        @if (count($bancos))
                            <x-slot name="options">
                                @foreach ($bancos as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </x-slot>
                        @endif
                    </x-select>
                    <x-jet-input-error for="cuenta.banco_id" />
                </div>

                {{-- <x-label value="Tipo banco :" />
                <div id="parent_editaccountbanco_id">
                    <x-select class="block w-full" wire:model.defer="cuenta.banco_id" id="editbanco_id">
                        @if (count($bancos))
                            <x-slot name="options">
                                @foreach ($bancos as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </x-slot>
                        @endif
                    </x-select>
                    <x-jet-input-error for="cuenta.banco_id" />
                </div> --}}

                <div class="mt-2">
                    <x-label value="N° Cuenta :" class="mt-2" />
                    <x-input class="block w-full" wire:model.defer="cuenta.account" placeholder="N° cuenta pago..." />
                    <x-jet-input-error for="cuenta.account" />
                </div>

                <div class="mt-2">
                    <x-label value="Descripción cuenta :" />
                    <x-input class="block w-full" wire:model.defer="cuenta.descripcion"
                        placeholder="Descripción de cuenta pago..." />
                    <x-jet-input-error for="cuenta.descripcion" />
                </div>

                {{-- <div class="block">
                    <x-label-check for="edit_default">
                        <x-input wire:model.defer="cuenta.default" name="default" type="checkbox" value="1"
                            id="edit_default" />
                        SELECCIONAR COMO PREDETERMINADO
                    </x-label-check>
                    <x-jet-input-error for="cuenta.default" />
                </div> --}}

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="update">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
    <script>
        function select2BancoAlpine() {
            this.select2 = $(this.$refs.select).select2();
            this.select2.val(this.banco_id).trigger("change");
            this.select2.on("select2:select", (event) => {
                this.banco_id = event.target.value;
            })
            this.$watch('banco_id', (value) => {
                this.select2.val(value).trigger("change");
            });
        }

        document.addEventListener('livewire:load', function() {
            Livewire.on('accountpayments.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar registro con N° cuenta: ' + data.account,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('admin.accountpayments.show-accountpayments', 'delete', data
                            .id);
                    }
                })
            })
        })
    </script>
</div>
