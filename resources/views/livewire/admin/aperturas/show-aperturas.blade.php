<div x-data="{
    searchuser: @entangle('searchuser'),
    searchbox: @entangle('searchbox'),
    searchmonthbox: @entangle('searchmonthbox'),
    searchsucursal: @entangle('searchsucursal')
}">
    <div wire:loading.flex class="loading-overlay fixed hidden">
        <x-loading-next />
    </div>

    <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 2xl:grid-cols-7 gap-1">
        <div class="w-full">
            <x-label value="Fecha :" />
            <x-input type="date" wire:model.debounce.500ms="date" class="w-full block" />
        </div>

        <div class="w-full">
            <x-label value="Hasta :" />
            <x-input type="date" wire:model.debounce.500ms="dateto" class="w-full block" />
        </div>

        @if (count($boxes) > 1)
            <div class="w-full ">
                <x-label value="Caja :" />
                <div class="relative" x-init="select2Box" id="parentsearchbox">
                    <x-select id="searchbox" x-ref="selectbox" data-placeholder="null">
                        <x-slot name="options">
                            @foreach ($boxes as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
            </div>
        @endif

        @if (count($monthboxes) > 1)
            <div class="w-full">
                <x-label value="Caja mensual :" />
                <div class="relative" x-init="select2Monthbox" id="parentsearchmonthbox">
                    <x-select id="searchmonthbox" x-ref="selectmonthbox" data-placeholder="null">
                        <x-slot name="options">
                            @foreach ($monthboxes as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
            </div>
        @endif

        @if (count($sucursals) > 1)
            <div class="w-full">
                <x-label value="Sucursal :" />
                <div class="relative" x-init="select2Sucursal" id="parentsearchsucursal">
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
        @endif


        @if (count($users) > 1)
            <div class="w-full">
                <x-label value="Usuario :" />
                <div class="relative" x-init="select2User" id="parentsearchuser">
                    <x-select id="searchuser" x-ref="selectuser" data-placeholder="null">
                        <x-slot name="options">
                            @foreach ($users as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
            </div>
        @endif
    </div>


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
                    <th scope="col" class="p-2 font-medium">SALDOS</th>
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
                                {{ formatDate($item->startdate, 'DD MMMM Y') }} <br>
                                {{ formatDate($item->startdate, 'hh:mm A') }}
                            </td>
                            <td class="p-2 text-center uppercase">
                                {{ formatDate($item->expiredate, 'DD MMMM Y') }} <br>
                                {{ formatDate($item->expiredate, 'hh:mm A') }}
                            </td>
                            <td class="p-2 text-center">
                                {{ $item->cajamovimiento->moneda->simbolo }}
                                {{ formatDecimalOrInteger($item->cajamovimiento->amount, 2, ', ') }}
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
                            <td class="p-2 text-center uppercase">
                                @if ($item->isClosed())
                                    {{ formatDate($item->closedate, 'DD MMMM Y') }} <br>
                                    {{ formatDate($item->closedate, 'hh:mm A') }}
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
        function select2Monthbox() {
            this.selectMB = $(this.$refs.selectmonthbox).select2();
            this.selectMB.val(this.searchmonthbox).trigger("change");
            this.selectMB.on("select2:select", (event) => {
                this.searchmonthbox = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch('searchmonthbox', (value) => {
                this.selectMB.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectMB.select2().val(this.searchmonthbox).trigger('change');
            });
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

        function select2User() {
            this.select2USR = $(this.$refs.selectuser).select2();
            this.select2USR.val(this.searchuser).trigger("change");
            this.select2USR.on("select2:select", (event) => {
                this.searchuser = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchuser", (value) => {
                this.select2USR.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.select2USR.select2().val(this.searchuser).trigger('change');
            });
        }

        function select2Box() {
            this.selectBX = $(this.$refs.selectbox).select2();
            this.selectBX.val(this.searchbox).trigger("change");
            this.selectBX.on("select2:select", (event) => {
                this.searchbox = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchbox", (value) => {
                this.selectBX.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectBX.select2().val(this.searchbox).trigger('change');
            });
        }

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
