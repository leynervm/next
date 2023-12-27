<div class="relative" x-data="{ loading: false }" x-init="loadingData">
    <div class="flex flex-col xs:flex-row xs:flex-wrap gap-2">
        <div class="w-full xs:w-auto">
            <x-label value="Fecha :" />
            <x-input type="date" wire:model.lazy="date" class="w-full block xs:w-auto" />
        </div>

        <div class="w-full xs:w-auto">
            <x-label value="Hasta :" />
            <x-input type="date" wire:model.lazy="dateto" class="w-full block xs:w-auto" />
        </div>

        <div class="w-full xs:w-40">
            <x-label value="Movimiento :" />
            <div x-data="{ searchtype: @entangle('searchtype') }" x-init="select2TypeAlpine" id="parentsearchtype" wire:ignore>
                <x-select id="searchtype" x-ref="select" data-placeholder="null">
                    <x-slot name="options">
                        <option value="INGRESO">INGRESO</option>
                        <option value="EGRESO">EGRESO</option>
                    </x-slot>
                </x-select>
            </div>
        </div>

        <div class="w-full xs:w-52">
            <x-label value="Método pago :" />
            <div x-data="{ searchmethodpayment: @entangle('searchmethodpayment') }" x-init="select2MethodpayAlpine" id="parentsearchmethodpayment" wire:ignore>
                <x-select id="searchmethodpayment" x-ref="select" data-placeholder="null">
                    <x-slot name="options">
                        @if (count($methodpayments))
                            @foreach ($methodpayments as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        @endif
                    </x-slot>
                </x-select>
            </div>
        </div>

        <div class="w-full xs:w-52">
            <x-label value="Concepto :" />
            <div x-data="{ searchconcept: @entangle('searchconcept') }" x-init="select2ConceptAlpine" id="parentsearchconcept" wire:ignore>
                <x-select id="searchconcept" x-ref="select" data-placeholder="null">
                    <x-slot name="options">
                        @if (count($concepts))
                            @foreach ($concepts as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        @endif
                    </x-slot>
                </x-select>
            </div>
        </div>

        <div class="w-full xs:w-60">
            <x-label value="Usuario :" />
            <div x-data="{ searchuser: @entangle('searchuser') }" x-init="select2UserAlpine" id="parentsearchuser" wire:ignore>
                <x-select id="searchuser" x-ref="select" data-placeholder="null">
                    <x-slot name="options">
                        @foreach ($users as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </x-slot>
                </x-select>
            </div>
        </div>

        <div class="w-full xs:w-full xs:max-w-xs">
            <x-label value="Sucursal :" />
            <div x-data="{ searchsucursal: @entangle('searchsucursal') }" x-init="select2SucursalAlpine" id="parentsearchsucursal" wire:ignore>
                <x-select id="searchsucursal" x-ref="select" data-placeholder="null">
                    <x-slot name="options">
                        @if (count($sucursals))
                            @foreach ($sucursals as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        @endif
                    </x-slot>
                </x-select>
            </div>
        </div>

        <div class="w-full xs:w-40">
            <x-label value="Caja :" />
            <div x-data="{ searchcaja: @entangle('searchcaja') }" x-init="select2CajaAlpine" id="parentsearchcaja" wire:ignore>
                <x-select id="searchcaja" x-ref="select" data-placeholder="null">
                    <x-slot name="options">
                        @if (count($cajas))
                            @foreach ($cajas as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        @endif
                    </x-slot>
                </x-select>
            </div>
        </div>
    </div>

    @if ($movimientos->hasPages())
        <div class="pt-3 pb-1">
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
                    MONEDA</th>

                <th scope="col" class="p-2 font-medium text-center">
                    REFERENCIA</th>

                <th scope="col" class="p-2 font-medium">
                    DETALLE</th>

                <th scope="col" class="p-2 font-medium">
                    TIPO MOVIMIENTO</th>

                <th scope="col" class="p-2 font-medium">
                    FORMA PAGO</th>

                <th scope="col" class="p-2 font-medium">
                    CONCEPTO</th>

                <th scope="col" class="p-2 font-medium">
                    CAJA</th>

                <th scope="col" class="p-2 font-medium">
                    USUARIO</th>

                <th scope="col" class="p-2 font-medium">
                    SUCURSAL</th>
            </tr>
        </x-slot>
        @if (count($movimientos))
            <x-slot name="body">
                @foreach ($movimientos as $item)
                    <tr>
                        <td class="p-2 text-xs uppercase">
                            {{ \Carbon\Carbon::parse($item->date)->locale('es')->isoformat('DD MMMM YYYY h:m A') }}
                        </td>
                        <td class="p-2 text-[10px]">
                            {{ $item->amount }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->moneda->currency }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->referencia }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            <p>{{ $item->detalle }}</p>
                            @if ($item->cuenta)
                                <p class="text-[10px]">{{ $item->cuenta->account }}</p>
                                <p class="text-[10px]">{{ $item->cuenta->descripcion }}</p>
                            @endif
                        </td>
                        <td class="p-2 text-xs text-center">
                            <small
                                class="p-1 text-xs leading-3 rounded text-white inline-block @if ($item->typemovement == 'INGRESO') bg-green-500 @else bg-red-500 @endif">
                                {{ $item->typemovement }}</small>
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->methodpayment->name }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->concept->name }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->opencaja->caja->name }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->user->name }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->sucursal->name }}
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        @endif
    </x-table>
    <div x-show="loading" wire:loading wire:loading.flex class="loading-overlay rounded">
        <x-loading-next />
    </div>

    <script>
        function loadingData() {
            Livewire.hook('message.sent', () => {
                loading = true;
            });

            Livewire.hook('message.processed', () => {
                loading = false;
            });
        }

        function select2TypeAlpine() {

            this.select2 = $(this.$refs.select).select2();
            this.select2.val(this.searchtype).trigger("change");
            this.select2.on("select2:select", (event) => {
                    this.searchtype = event.target.value;
                    // @this.set('searchtype', event.target.value);
                })
                .on('change', function(event) {

                })
                .on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
            this.$watch('searchtype', (value) => {
                this.select2.val(value).trigger("change");
            });
        }

        function select2MethodpayAlpine() {
            this.select2 = $(this.$refs.select).select2();
            this.select2.val(this.searchmethodpayment).trigger("change");
            this.select2.on("select2:select", (event) => {
                    this.searchmethodpayment = event.target.value;
                })
                .on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
            this.$watch('searchmethodpayment', (value) => {
                this.select2.val(value).trigger("change");
            });
        }


        function select2ConceptAlpine() {
            this.select2 = $(this.$refs.select).select2();
            this.select2.val(this.searchconcept).trigger("change");
            this.select2.on("select2:select", (event) => {
                    this.searchconcept = event.target.value;
                })
                .on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
            this.$watch("searchconcept", (value) => {
                this.select2.val(value).trigger("change");
            });
        }

        function select2SucursalAlpine() {
            this.select2 = $(this.$refs.select).select2();
            this.select2.val(this.searchsucursal).trigger("change");
            this.select2.on("select2:select", (event) => {
                    this.searchsucursal = event.target.value;
                })
                .on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
            this.$watch("searchsucursal", (value) => {
                this.select2.val(value).trigger("change");
            });
        }

        function select2UserAlpine() {
            this.select2 = $(this.$refs.select).select2();
            this.select2.val(this.searchuser).trigger("change");
            this.select2.on("select2:select", (event) => {
                    this.searchuser = event.target.value;
                })
                .on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
            this.$watch("searchuser", (value) => {
                this.select2.val(value).trigger("change");
            });
        }

        function select2CajaAlpine() {
            this.select2 = $(this.$refs.select).select2();
            this.select2.val(this.searchcaja).trigger("change");
            this.select2.on("select2:select", (event) => {
                    this.searchcaja = event.target.value;
                })
                .on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
            this.$watch("searchcaja", (value) => {
                this.select2.val(value).trigger("change");
            });
        }

        // document.addEventListener('DOMContentLoaded', function() {

        //     renderselect2();

        //     $('#searchtype').on("change", function(e) {
        //         disableselect2();
        //         @this.set('searchtype', e.target.value);
        //     });

        //     $('#searchmethodpayment').on("change", function(e) {
        //         disableselect2()
        //         @this.set('searchmethodpayment', e.target.value);
        //     });

        //     $('#searchconcept').on("change", function(e) {
        //         disableselect2()
        //         @this.set('searchconcept', e.target.value);
        //     });

        //     $('#searchuser').on("change", function(e) {
        //         disableselect2()
        //         @this.set('searchuser', e.target.value);
        //     });

        //     $('#searchsucursal').on("change", function(e) {
        //         disableselect2()
        //         @this.set('searchsucursal', e.target.value);
        //     });

        //     $('#searchcaja').on("change", function(e) {
        //         disableselect2()
        //         @this.set('searchcaja', e.target.value);
        //     });

        //     document.addEventListener('render-show-movimientos', () => {
        //         renderselect2();
        //     });

        function disableselect2(estado = true) {
            $('#searchsucursal, #searchtype, #searchmethodpayment, #searchconcept, #searchuser, #searchcaja')
                .attr('disabled', estado);
        }

        //     function renderselect2() {
        //         $('#searchsucursal, #searchtype, #searchmethodpayment, #searchconcept, #searchuser, #searchcaja')
        //             .select2().on('select2:open', function(e) {
        //                 const evt = "scroll.select2";
        //                 $(e.target).parents().off(evt);
        //                 $(window).off(evt);
        //             });
        //     }
        // })
    </script>
</div>
