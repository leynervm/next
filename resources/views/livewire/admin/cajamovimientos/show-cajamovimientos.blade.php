<div>
    <div wire:loading.flex class="loading-overlay fixed hidden">
        <x-loading-next />
    </div>

    <div class="flex flex-col xs:flex-row xs:flex-wrap gap-2">
        <div class="w-full xs:w-60">
            <x-label value="Fecha :" />
            <x-input type="date" wire:model.lazy="date" class="w-full block" />
        </div>

        <div class="w-full xs:w-60">
            <x-label value="Hasta :" />
            <x-input type="date" wire:model.lazy="dateto" class="w-full block" />
        </div>

        <div class="w-full sm:max-w-xs">
            <x-label value="Movimiento :" />
            <div x-data="{ searchtype: @entangle('searchtype') }" x-init="select2Type" class="relative" id="parentsearchtype" wire:ignore>
                <x-select id="searchtype" x-ref="select" data-placeholder="null">
                    <x-slot name="options">
                        <option value="INGRESO">INGRESO</option>
                        <option value="EGRESO">EGRESO</option>
                    </x-slot>
                </x-select>
                <x-icon-select />
            </div>
        </div>

        @if (count($methodpayments) > 1)
            <div class="w-full sm:max-w-xs">
                <x-label value="Método pago :" />
                <div class="relative" x-data="{ searchmethodpayment: @entangle('searchmethodpayment') }" x-init="select2Methodpay" id="parentsearchmethodpayment"
                    wire:ignore>
                    <x-select id="searchmethodpayment" x-ref="select" data-placeholder="null">
                        <x-slot name="options">
                            @foreach ($methodpayments as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
            </div>
        @endif

        @if (count($concepts) > 1)
            <div class="w-full sm:max-w-xs">
                <x-label value="Concepto :" />
                <div class="relative" x-data="{ searchconcept: @entangle('searchconcept') }" x-init="select2Concept" id="parentsearchconcept"
                    wire:ignore>
                    <x-select id="searchconcept" x-ref="select" data-placeholder="null">
                        <x-slot name="options">
                            @foreach ($concepts as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
            </div>
        @endif

        @if (count($users) > 1)
            <div class="w-full sm:max-w-xs">
                <x-label value="Usuario :" />
                <div class="relative" x-data="{ searchuser: @entangle('searchuser') }" x-init="select2User" id="parentsearchuser" wire:ignore>
                    <x-select id="searchuser" x-ref="select" data-placeholder="null">
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

        @if (count($boxes) > 1)
            <div class="w-full sm:max-w-xs">
                <x-label value="Caja :" />
                <div class="relative" x-data="{ searchcaja: @entangle('searchcaja') }" x-init="select2Caja" id="parentsearchcaja" wire:ignore>
                    <x-select id="searchcaja" x-ref="selectcaja" data-placeholder="null">
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
            <div class="w-full sm:max-w-xs">
                <x-label value="Caja mensual :" />
                <div class="relative" x-data="{ searchmonthbox: @entangle('searchmonthbox') }" x-init="select2Monthbox" id="parentsearchmonthbox"
                    wire:ignore>
                    <x-select id="searchmonthbox" x-ref="selectmb" data-placeholder="null"
                        data-minimum-results-for-search="3">
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
    </div>

    @if ($movimientos->hasPages())
        <div class="pt-3 pb-1 flex flex-col justify-end items-end">
            {{ $movimientos->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <x-table class="mt-1">
        <x-slot name="header">
            <tr>
                <th scope="col" class="p-2 font-medium">
                    <button class="flex items-center gap-x-3 focus:outline-none">
                        <span>FECHA</span>
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
                <th scope="col" class="p-2 font-medium text-left">
                    MONTO</th>
                <th scope="col" class="p-2 font-medium">
                    MOVIMIENTO</th>
                <th scope="col" class="p-2 font-medium">
                    CONCEPTO</th>
                <th scope="col" class="p-2 font-medium text-center">
                    REFERENCIA</th>
                <th scope="col" class="p-2 font-medium">
                    CAJA</th>
                <th scope="col" class="p-2 font-medium">
                    SUCURSAL / USUARIO</th>
                <th scope="col" class="p-2 font-medium">
                    OPCIONES</th>
            </tr>
        </x-slot>
        @if (count($movimientos) > 0)
            <x-slot name="body">
                @foreach ($movimientos as $item)
                    <tr>
                        <td class="p-2 uppercase">
                            <p>{{ formatDate($item->date, 'DD MMMM Y') }}</p>
                            <p>{{ formatDate($item->date, 'hh:mm A') }}</p>
                        </td>
                        <td class="p-2">
                            {{ $item->moneda->simbolo }}
                            {{ number_format($item->totalamount, 2, '.', ', ') }}
                            <small class="text-[8px]">{{ $item->moneda->currency }}</small>
                            @if ($item->openbox->isActivo() && $item->openbox->isUsing())
                                <p class="text-next-500 text-[10px]">CAJA ACTUAL</p>
                            @endif

                            @if ($item->tipocambio > 0)
                                <p class="text-xs text-colorsubtitleform leading-3">
                                    {{ number_format($item->amount, 2, '.', ', ') }}
                                    <small class="text-[8px]">
                                        @if ($item->moneda->isDolar())
                                            SOLES
                                        @else
                                            DÓLARES
                                        @endif
                                    </small>
                                </p>
                                <p class="text-xs text-colorsubtitleform leading-3">
                                    <small class="text-[8px]">TIPO CAMBIO :</small>
                                    {{ $item->tipocambio }}
                                </p>
                            @endif
                        </td>
                        <td class="p-2 text-center">
                            <x-span-text :text="$item->typemovement->value" class="!leading-3 !tracking-normal"
                                type="{{ $item->isIngreso() ? 'green' : 'red' }}" />
                            <p class="text-[10px]">{{ $item->methodpayment->name }}</p>
                        </td>
                        <td class="p-2 text-center">
                            {{ $item->concept->name }}
                            @if (!empty($item->detalle))
                                <p class="text-[10px] leading-3 text-colorsubtitleform">{{ $item->detalle }}</p>
                            @endif
                        </td>
                        <td class="p-2 text-center">
                            {{ $item->referencia }}
                        </td>
                        <td class="p-2 text-center">
                            <p>{{ $item->openbox->box->name }}</p>
                            <p class="text-colorsubtitleform text-[10px]">
                                {{ formatDate($item->monthbox->month, 'MMMM Y') }}</p>
                        </td>
                        <td class="p-2 text-center">
                            {{ $item->sucursal->name }}
                            <p class="text-[10px] leading-3 text-colorsubtitleform">
                                {{ $item->user->name }}</p>
                        </td>
                        <td class="p-2 text-center flex gap-1 items-center justify-end">
                            <x-button-print href="{{ route('admin.payments.print', $item) }}" target="_blank" />

                            @if ($item->concept->isDeletemanual())
                                @if (auth()->user()->isAdmin() || $item->user_id == auth()->user()->id)
                                    <x-button-delete onclick="confirmDelete({{ $item->id }})"
                                        wire:loading.attr="disabled" wire:key="deletecjmv_{{ $item->id }}" />
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        @endif
    </x-table>
    <script>
        function select2Monthbox() {
            this.selectMB = $(this.$refs.selectmb).select2();
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
        }

        function select2Type() {
            this.selectT = $(this.$refs.select).select2();
            this.selectT.val(this.searchtype).trigger("change");
            this.selectT.on("select2:select", (event) => {
                this.searchtype = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch('searchtype', (value) => {
                this.selectT.val(value).trigger("change");
            });
        }

        function select2Methodpay() {
            this.selectM = $(this.$refs.select).select2();
            this.selectM.val(this.searchmethodpayment).trigger("change");
            this.selectM.on("select2:select", (event) => {
                this.searchmethodpayment = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch('searchmethodpayment', (value) => {
                this.selectM.val(value).trigger("change");
            });
        }

        function select2Concept() {
            this.selectCo = $(this.$refs.select).select2();
            this.selectCo.val(this.searchconcept).trigger("change");
            this.selectCo.on("select2:select", (event) => {
                this.searchconcept = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchconcept", (value) => {
                this.selectCo.val(value).trigger("change");
            });
        }

        function select2SucursalAlpine() {
            this.selectS = $(this.$refs.selectsucursal).select2();
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
        }

        function select2User() {
            this.select2 = $(this.$refs.select).select2();
            this.select2.val(this.searchuser).trigger("change");
            this.select2.on("select2:select", (event) => {
                this.searchuser = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchuser", (value) => {
                this.select2.val(value).trigger("change");
            });
        }

        function select2Caja() {
            this.selectC = $(this.$refs.selectcaja).select2();
            this.selectC.val(this.searchcaja).trigger("change");
            this.selectC.on("select2:select", (event) => {
                this.searchcaja = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchcaja", (value) => {
                this.selectC.val(value).trigger("change");
            });
        }

        function confirmDelete(cajamovimiento_id) {
            swal.fire({
                title: 'Eliminar registro de movimientos en caja ?',
                text: "Se eliminará un registro de pagos de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(cajamovimiento_id);
                }
            })
        }
    </script>
</div>
