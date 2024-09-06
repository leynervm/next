<div>
    <div wire:loading.flex class="loading-overlay fixed hidden">
        <x-loading-next />
    </div>

    @if ($monthboxes->hasPages())
        <div class="pb-2">
            {{ $monthboxes->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 2xl:grid-cols-6 mb-1 gap-1">
        <div class="w-full sm:max-w-xs">
            <x-label value="Filtrar Mes :" />
            <x-input class="block w-full" wire:model.debounce.500ms="searchmonth" type="month" />
        </div>

        {{-- @if (count($sucursals) > 1) --}}
        <div class="w-full">
            <x-label value="Sucursal :" />
            <div class="relative" x-data="{ searchsucursal: @entangle('searchsucursal') }" x-init="select2Sucursal" id="parentsearchsucursal">
                <x-select id="searchsucursal" x-ref="selectsuc" data-placeholder="null">
                    <x-slot name="options">
                        @foreach ($sucursals as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </x-slot>
                </x-select>
                <x-icon-select />
            </div>
        </div>
        {{-- @endif --}}
    </div>

    <x-table class="relative">
        <x-slot name="header">
            <tr>
                <th scope="col" class="p-2 font-medium">
                    <button class="flex items-center gap-x-3 focus:outline-none">
                        <span>DESCRIPCIÓN DE CAJA</span>
                        <svg class="h-3 w-3" viewBox="0 0 10 11" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" stroke="currentColor" stroke-width="0.1">
                            <path
                                d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z" />
                            <path
                                d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z" />
                            <path
                                d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z"
                                stroke-width="0.3" />
                        </svg>
                    </button>
                </th>
                <th scope="col" class="p-2 font-medium">
                    MES</th>
                <th scope="col" class="p-2 font-medium">
                    FECHA INICIO</th>
                <th scope="col" class="p-2 font-medium">
                    FECHA CIERRE</th>
                <th scope="col" class="p-2 font-medium">
                    SALDOS</th>
                <th scope="col" class="p-2 font-medium text-center">
                    ESTADO</th>
                <th scope="col" class="p-2 relative">
                    <span class="sr-only">OPCIONES</span>
                </th>
            </tr>
        </x-slot>
        @if (count($monthboxes) > 0)
            <x-slot name="body">
                @foreach ($monthboxes as $item)
                    @php
                        $mesStr = formatDate($item->month, 'MMMM Y');
                    @endphp
                    <tr>
                        <td class="p-2">
                            <p>{{ $item->name }}</p>
                            <p class="text-colorsubtitleform text-[10px] font-medium">{{ $item->sucursal->name }}</p>
                            @if ($item->sucursal->trashed())
                                <p><x-span-text text="NO DISPONIBLE" class="leading-3 !tracking-normal" /></p>
                            @endif
                        </td>
                        <td class="p-2 text-center">
                            {{ formatDate($item->month, 'MMMM Y') }}
                        </td>
                        <td class="p-2 text-center">
                            {{ formatDate($item->startdate, 'DD MMMM Y') }} <br>
                            {{ formatDate($item->startdate, 'hh:mm A') }}
                        </td>
                        <td class="p-2 text-center">
                            {{ formatDate($item->expiredate, 'DD MMMM Y') }} <br>
                            {{ formatDate($item->expiredate, 'hh:mm A') }}
                        </td>
                        <td class="p-2 text-center">
                            @foreach ($item->cajamovimientos as $saldo)
                                <p class="text-[10px]">
                                    {{ $saldo->moneda->simbolo }}
                                    <span
                                        class="text-xs font-semibold">{{ formatDecimalOrInteger($saldo->diferencia, 2, ', ') }}</span>
                                    {{ $saldo->moneda->currency }}
                                </p>
                            @endforeach
                        </td>
                        <td class="p-2 text-center">
                            @if ($item->isRegister())
                                <x-span-text text="REGISTRADO" class="!tracking-normal leading-3" type="blue" />
                                @if ($monthboxes->where('status', \App\Models\Monthbox::EN_USO)->count() == 0)
                                    @if (\Carbon\Carbon::now()->addMonths(1)->format('Y-m') == $item->month)
                                        @can('admin.cajas.mensuales.close')
                                            <div class="w-full block mt-1">
                                                <x-button
                                                    onclick="confirmActive({{ $item->id }}, '{{ $mesStr }}')"
                                                    wire:key="activemonthbox_{{ $item->id }}"
                                                    wire:loading.attr="disabled" class="inline-block">
                                                    APERTURAR MES</x-button>
                                            </div>
                                        @endcan
                                    @endif
                                @endif
                            @elseif ($item->isActive())
                                @if ($item->isUsing())
                                    <x-span-text text="ACTUAL" class="!tracking-normal leading-3" type="green" />
                                @elseif ($item->isExpired())
                                    <x-span-text text="EXPIRADO" class="!tracking-normal leading-3" type="orange" />
                                    @can('admin.cajas.mensuales.close')
                                        <div class="w-full block mt-1">
                                            <x-button onclick="confirmClose({{ $item->id }}, '{{ $mesStr }}')"
                                                wire:key="closemonthbox_{{ $item->id }}" wire:loading.attr="disabled"
                                                class="inline-block">CERRAR MES</x-button>
                                        </div>
                                    @endcan
                                @endif
                            @elseif ($item->isClose())
                                <x-span-text text="CERRADO" type="red" class="leading-3 !tracking-normal" />
                            @endif
                        </td>
                        <td class="p-2 whitespace-nowrap align-middle text-center">
                            @if ($item->trashed())
                                @can('admin.cajas.mensuales.restore')
                                    <button onclick="confirmRestore({{ $item }})" wire:loading.attr="disabled"
                                        wire:key="restoremonthbox_{{ $item->id }}" type="button"
                                        class="inline-block p-0.5 rounded-sm text-green-500 disabled:opacity-75">
                                        <svg class="w-5 h-5 scale-125 rounded-sm text-neutral-300" viewBox="0 0 24 24"
                                            fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path fill="currentColor"
                                                d="M11 12C11 13.6569 9.65685 15 8 15C6.34315 15 5 13.6569 5 12C5 10.3431 6.34315 9 8 9C9.65685 9 11 10.3431 11 12Z" />
                                            <path
                                                d="M16 6H8C4.68629 6 2 8.68629 2 12C2 15.3137 4.68629 18 8 18H16C19.3137 18 22 15.3137 22 12C22 8.68629 19.3137 6 16 6Z" />
                                        </svg>
                                    </button>
                                @endcan
                                {{-- <button wire:click="usemonthbox({{ $item->id }})" wire:loading.attr="disabled"
                                        type="button"
                                        class="inline-block p-0.5 rounded-sm text-neutral-300 disabled:opacity-75">
                                        <svg class="w-5 h-5 scale-125 rounded-sm " viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path fill="currentColor"
                                                d="M11 12C11 13.6569 9.65685 15 8 15C6.34315 15 5 13.6569 5 12C5 10.3431 6.34315 9 8 9C9.65685 9 11 10.3431 11 12Z" />
                                            <path
                                                d="M16 6H8C4.68629 6 2 8.68629 2 12C2 15.3137 4.68629 18 8 18H16C19.3137 18 22 15.3137 22 12C22 8.68629 19.3137 6 16 6Z" />
                                        </svg>
                                    </button> --}}
                            @else
                                @can('admin.cajas.mensuales.edit')
                                    <x-button-edit wire:click="edit({{ $item->id }})"
                                        wire:key="edit_{{ $item->id }}" wire:loading.attr="disabled" />
                                @endcan

                                @can('admin.cajas.mensuales.delete')
                                    <x-button-delete onclick="confirmDelete({{ $item }})"
                                        wire:key="delete_{{ $item->id }}" wire:loading.attr="disabled" />
                                @endcan
                            @endif
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        @endif
    </x-table>

    <x-jet-dialog-modal wire:model="open" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actulizar caja mensual') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="relative">
                <div class="w-full grid xs:grid-cols-2 gap-2">
                    <div class="w-full">
                        <x-label value="Descripción caja mensual :" />
                        <x-input class="block w-full" wire:model.defer="monthbox.name"
                            placeholder="Descripcion de caja..." />
                        <x-jet-input-error for="monthbox.name" />
                    </div>

                    <div class="w-full">
                        <x-label value="Mes :" />
                        <x-disabled-text :text="formatDate($monthbox->month, 'MMMM Y')" />
                        <x-jet-input-error for="monthbox.month" />
                    </div>

                    <div class="w-full">
                        <x-label value="fecha inicio :" />
                        <x-input class="block w-full" wire:model.defer="monthbox.startdate" type="datetime-local" />
                        <x-jet-input-error for="monthbox.startdate" />
                    </div>

                    <div class="w-full">
                        <x-label value="Fecha cierre :" />
                        <x-input class="block w-full" wire:model.defer="monthbox.expiredate" type="datetime-local" />
                        <x-jet-input-error for="monthbox.expiredate" />
                    </div>

                    <div class="w-full xs:col-span-2">
                        <x-label value="Sucursal :" />
                        @if ($monthbox->sucursal)
                            <x-disabled-text :text="$monthbox->sucursal->name" />
                        @endif

                        <x-jet-input-error for="monthbox.sucursal_id" />
                    </div>
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>

                <div wire:loading.flex class="loading-overlay fixed hidden">
                    <x-loading-next />
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        function confirmDelete(monthbox) {
            swal.fire({
                title: 'Eliminar caja mensual, ' + monthbox.month,
                text: "Caja mensual seleccionada dejará de estar disponible, pero seguirá visualizando sus movimientos registrados.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(monthbox.id);
                }
            })
        }

        function confirmRestore(monthbox) {
            swal.fire({
                title: 'Restaurar caja mensual, ' + monthbox.month,
                text: "Se recuperará un registro de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.restoremonthbox(monthbox.id);
                }
            })
        }

        function confirmActive(monthbox_id, month) {
            swal.fire({
                title: 'ACTIVAR CAJA MENSUAL ' + month + ' ?',
                text: "Caja mensual seleccionada será asignada por defecto para registrar movimientos en caja.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.activemonthbox(monthbox_id);
                }
            })
        }

        function confirmClose(monthbox_id, mes) {
            swal.fire({
                title: 'CERRAR CAJA MENSUAL DE ' + mes,
                text: "Se actualizará el estado de la caja mensual, además dejará de estar disponible para registrar movimientos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.closemonthbox(monthbox_id);
                }
            })
        }

        function select2Sucursal() {
            this.selectS = $(this.$refs.selectsuc).select2();
            this.selectS.val(this.searchsucursal).trigger("change");
            this.selectS.on("select2:select", (event) => {
                this.searchsucursal = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchsucursal", (value) => {
                this.selectS.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectS.select2().val(this.searchsucursal).trigger('change');
            });
        }
    </script>
</div>
