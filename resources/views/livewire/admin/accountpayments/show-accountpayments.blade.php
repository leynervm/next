<div class="">

    @if (count($cuentas))
        <div class="pb-2">
            {{ $cuentas->links() }}
        </div>
    @endif

    <div class="block w-full">
        @if (count($cuentas))
            <x-table>
                <thead class="bg-gray-50 text-gray-400 text-xs">
                    <tr>
                        <th scope="col" class="p-2 font-medium text-left">N° CUENTA</th>
                        <th scope="col" class="p-2 font-medium text-center">BANCO</th>
                        <th scope="col" class="p-2 font-medium">FORMAS PAGO</th>
                        <th scope="col" class="p-2 font-medium">ESTADO</th>
                        <th scope="col" class="p-2 font-medium">OPCIONES</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                    @if (count($cuentas))
                        @foreach ($cuentas as $item)
                            <tr>
                                <td class="p-2 text-xs flex flex-wrap items-center gap-1">
                                    @if ($item->default)
                                        <span class="bg-green-100 text-green-500 p-1 rounded-full block">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                        </span>
                                    @endif
                                    <div>
                                        <p class="text-[10px] leading-4">{{ $item->descripcion }}</p>
                                        <p class="text-[9px] font-bold leading-3">{{ $item->account }}</p>
                                    </div>
                                </td>
                                <td class="p-2 text-xs text-center">{{ $item->banco->name }}</td>
                                <td class="p-2 text-center">
                                    @if (count($item->methodpayments))
                                        <div class="flex flex-wrap justify-center items-start gap-1">
                                            @foreach ($item->methodpayments as $method)
                                                <span
                                                    class="inline-block leading-3 bg-fondospancardproduct text-textspancardproduct text-[9px] rounded-lg p-1">{{ $method->name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="p-2 text-xs text-center">
                                    @if ($item->status == 0)
                                        <span
                                            class="bg-green-100 text-green-600 text-[10px] p-1 rounded-lg inline-flex leading-3">Activo</span>
                                    @elseif($item->status == 1)
                                        <span
                                            class="bg-red-100 text-red-600 text-[10px] p-1 rounded-lg inline-flex leading-3">No
                                            disponible</span>
                                    @else
                                        <span
                                            class="bg-orange-100 text-orange-600 text-[10px] p-1 rounded-lg inline-flex leading-3">No
                                            identificado</span>
                                    @endif
                                </td>
                                <td class="p-2 text-xs text-center">
                                    <x-button-edit wire:loading.attr="disabled" wire:target="edit"
                                        wire:click="edit({{ $item->id }})"></x-button-edit>
                                    <x-button-delete wire:loading.attr="disabled" wire:target="confirmDelete"
                                        wire:click="confirmDelete({{ $item->id }})"></x-button-delete>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </x-table>
        @endif
    </div>

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

                <x-label value="Tipo banco :" />
                <div id="parent_editaccountbanco_id">
                    <x-select class="block w-full" wire:model.defer="cuenta.banco_id" id="editbanco_id"
                        data-placeholder="Seleccionar..." data-minimum-results-for-search="Infinity">
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

                <x-label value="N° Cuenta :" class="mt-2" />
                <x-input class="block w-full" wire:model.defer="cuenta.account" placeholder="N° cuenta pago..." />
                <x-jet-input-error for="cuenta.account" />

                <x-label value="Descripción cuenta :" class="mt-2" />
                <x-input class="block w-full" wire:model.defer="cuenta.descripcion"
                    placeholder="Descripción de cuenta pago..." />
                <x-jet-input-error for="cuenta.descripcion" />

                <div class="mt-2">
                    <x-label textSize="[10px]"
                        class="inline-flex items-center tracking-widest font-semibold gap-2 cursor-pointer bg-next-100 rounded-lg p-1"
                        for="edit_default">
                        <x-input wire:model.defer="cuenta.default" name="default" type="checkbox" id="edit_default" />
                        SELECCIONAR COMO PREDETERMINADO
                    </x-label>
                </div>
                <x-jet-input-error for="cuenta.default" />

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

            renderSelect2();

            $("#editbanco_id").on("change", (e) => {
                deshabilitarSelects();
                @this.set('cuenta.banco_id', e.target.value);
            });

            window.addEventListener('render-editaccount-select2', () => {
                renderSelect2();
            });

            function renderSelect2() {
                var formulario = document.getElementById("form_edit_accountpayment");
                var selects = formulario.getElementsByTagName("select");

                for (var i = 0; i < selects.length; i++) {
                    if (selects[i].id !== "") {
                        $("#" + selects[i].id).select2();
                    }
                }
            }

            function deshabilitarSelects() {
                var formulario = document.getElementById("form_edit_accountpayment");
                var selects = formulario.getElementsByTagName("select");

                for (var i = 0; i < selects.length; i++) {
                    selects[i].disabled = true;
                }
            }

            window.addEventListener('accountpayments.confirmDelete', data => {
                swal.fire({
                    title: 'Eliminar registro con N° cuenta: ' + data.detail.account,
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
                            .detail
                            .id);
                    }
                })
            })
        })
    </script>
</div>
