<div>
    <button
        class="border text-colorsubtitleform p-1 sm:p-3 border-borderminicard min-h-24 w-full flex flex-col items-center gap-2 justify-center rounded-lg sm:rounded-2xl hover:shadow hover:shadow-shadowminicard"
        type="button" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
            stroke-linecap="round" class="block size-9">
            <path
                d="M15.5 13.5c3.314 0 6-.895 6-2s-2.686-2-6-2-6 .895-6 2 2.686 2 6 2Zm-7-7c3.314 0 6-.895 6-2s-2.686-2-6-2-6 .895-6 2 2.686 2 6 2Z" />
            <path d="M2.5 8.5c0 .923 1.67 1.709 4 2m-4 2c0 .923 1.67 1.709 4 2" />
            <path d="M2.5 4.5v12c0 .87 1.67 1.725 4 2" />
            <path d="M21.5 11.5v8c0 1.105-2.686 2-6 2s-6-.895-6-2v-8" />
            <path d="M21.5 15.5c0 1.105-2.686 2-6 2s-6-.895-6-2" />
        </svg>
        <p class="text-center font-medium leading-none text-[10px] text-colorsubtitleform">REPORTE DE VENTAS</p>
    </button>

    <x-jet-dialog-modal wire:model="open" maxWidth="md" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Generar reporte de ventas') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="exportexcel" class="w-full" x-data="reportventa">
                <div class="w-full grid grid-cols-1 gap-2">

                    <div class="w-full">
                        <x-label value="Tipo de reporte :" />
                        <div id="parentrpvt_typereporte" x-init="selectVTTypereporte" class="relative">
                            <x-select class="block w-full" x-ref="rpvt_typereporte" id="rpvt_typereporte"
                                data-dropdown-parent="null">
                                <x-slot name="options">
                                    @foreach ($typereportes as $item)
                                        <option value="{{ $item->value }}">{{ $item->label }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="typereporte" />
                    </div>

                    <div class="w-full">
                        <x-label value="Vista de reporte :" />
                        <div id="parentrpvt_viewreporte" x-init="selectVTViewreporte" class="relative">
                            <x-select class="block w-full" x-ref="rpvt_viewreporte" id="rpvt_viewreporte"
                                data-dropdown-parent="null">
                                <x-slot name="options">
                                    {{-- @foreach ($viewreportes as $item) --}}
                                    <option value="0">POR DEFECTO</option>
                                    <option value="1">DETALLADO</option>
                                    {{-- @endforeach --}}
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="viewreporte" />
                    </div>

                    <div class="w-full">
                        <x-label value="Sucursal de venta:" />
                        <div id="parentrpvt_sucursal_id" x-init="selectVTSucursal" class="relative">
                            <x-select class="block w-full" x-ref="rpvt_sucursal_id" id="rpvt_sucursal_id"
                                data-dropdown-parent="null" data-placeholder="null">
                                <x-slot name="options">
                                    @foreach ($sucursals as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="sucursal_id" />
                    </div>

                    {{-- <div class="w-full" x-cloak x-show="sucursal_id!=='' && typereporte=='{{ getFilter::DEFAULT }}'">
                        <x-label value="Caja mensual de venta :" />
                        <div id="parentrpvt_monthbox_id" x-init="selectVTMonthbox" class="relative">
                            <x-select class="block w-full" x-ref="rpvt_monthbox_id" id="rpvt_monthbox_id"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpvt_monthbox_id">
                                <x-slot name="options">
                                    @foreach ($monthboxes as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="monthbox_id" />
                    </div> --}}

                    {{-- <div class="w-full" x-cloak x-show="sucursal_id!=='' && typereporte=='{{ getFilter::DEFAULT }}'">
                        <x-label value="Caja diaria de venta :" />
                        <div id="parentrpcaja_openbox_id" x-init="selectVTOpenbox" class="relative">
                            <x-select class="block w-full" x-ref="rpvt_openbox_id" id="rpvt_openbox_id"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpvt_openbox_id">
                                <x-slot name="options">
                                    @foreach ($openboxes as $item)
                                        <option value="{{ $item->id }}" title="{{ $item->user->name }}">
                                            @if (formatDate($item->startdate, 'DD/MM/Y') == formatDate($item->startdate, 'DD/MM/Y'))
                                                {{ formatDate($item->startdate, 'ddd DD MMM Y hh:mm A') }}
                                                Hasta
                                                {{ formatDate($item->expiredate, 'hh:mm A') }}
                                            @else
                                                {{ formatDate($item->startdate, 'ddd DD MMM Y hh:mm A') }}
                                                Hasta
                                                {{ formatDate($item->expiredate, 'ddd DD MMM Y hh:mm A') }}
                                            @endif
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="openbox_id" />
                    </div> --}}

                    <div class="w-full" x-cloak x-show="'{{ count($typepayments) > 1 }}'" style="display: none;">
                        <x-label value="Tipo de pago :" />
                        <div id="parentrpvt_typepayment" x-init="selectVTTypepayment" class="relative">
                            <x-select class="block w-full" x-ref="rpvt_typepayment_id" id="rpvt_typepayment_id"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpvt_typepayment_id">
                                <x-slot name="options">
                                    @foreach ($typepayments as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="typepayment_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Tipo de comprobante :" />
                        <div id="parentrpvt_typecpe" x-init="selectVTTypecpe" class="relative">
                            <x-select class="block w-full" x-ref="rpvt_typecpe" id="rpvt_typecpe"
                                data-dropdown-parent="null" data-placeholder="null">
                                <x-slot name="options">
                                    @foreach ($typecomprobantes as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="typecomprobante_id" />
                    </div>

                    {{-- <div class="w-full">
                        <x-label value="Forma de pago :" />
                        <div id="parentrpvt_methodpayment_id" x-init="selectVTMethod" class="relative">
                            <x-select class="block w-full" x-ref="rpvt_methodpayment_id" id="rpvt_methodpayment_id"
                                data-dropdown-parent="null" data-placeholder="null">
                                <x-slot name="options">
                                    @foreach ($methodpayments as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="methodpayment_id" />
                    </div> --}}

                    <div class="w-full" x-cloak x-show="'{{ count($monedas) > 1 }}'" style="display: none;">
                        <x-label value="Moneda :" />
                        <div id="parentrpvt_moneda_id" x-init="selectVTMoneda" class="relative">
                            <x-select class="block w-full" x-ref="rpvt_moneda_id" wire:key="rpvt_moneda_id"
                                id="rpvt_moneda_id" data-dropdown-parent="null" data-placeholder="null">
                                <x-slot name="options">
                                    @foreach ($monedas as $item)
                                        <option value="{{ $item->id }}">{{ $item->currency }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="moneda_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Usuario de venta :" />
                        <div id="parentrpvt_user_id" x-init="selectVTUser" class="relative">
                            <x-select class="block w-full" x-ref="rpvt_user_id" id="rpvt_user_id"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpvt_user_id">
                                <x-slot name="options">
                                    @foreach ($users as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="user_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Filtrar cliente :" />
                        <div id="parentrpvt_client_id" x-init="selectVTClient" class="relative">
                            <x-select class="block w-full" x-ref="rpvt_client_id" id="rpvt_client_id"
                                data-dropdown-parent="null" data-placeholder="null" {{-- data-minimum-input-length="3" --}}>
                                <x-slot name="options">
                                    @foreach ($clients as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->document }} - {{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="client_id" />
                    </div>

                    <div class="w-full" x-cloak
                        x-show="typereporte=='{{ getFilter::DIARIO->value }}' || typereporte=='{{ getFilter::RANGO_DIAS->value }}' || typereporte=='{{ getFilter::DIAS_SELECCIONADOS->value }}'">
                        <x-label value="Fecha :" />
                        <x-input class="block w-full" wire:model.defer="date" type="date" />
                        <x-jet-input-error for="date" />
                        <x-jet-input-error for="days" />
                    </div>

                    <div class="w-full" x-cloak x-show="typereporte=='{{ getFilter::RANGO_DIAS->value }}'">
                        <x-label value="Límite de Fecha :" />
                        <x-input class="block w-full" wire:model.defer="dateto" type="date"
                            {{-- type="datetime-local" --}} />
                        <x-jet-input-error for="dateto" />
                    </div>

                    <div class="w-full" x-cloak
                        x-show="typereporte=='{{ getFilter::MENSUAL->value }}' || typereporte=='{{ getFilter::RANGO_MESES->value }}' || typereporte=='{{ getFilter::MESES_SELECCIONADOS->value }}'">
                        <x-label value="Mes :" />
                        <x-input class="block w-full" wire:model.defer="month" type="month" />
                        <x-jet-input-error for="month" />
                        <x-jet-input-error for="months" />
                    </div>

                    <div class="w-full" x-cloak x-show="typereporte=='{{ getFilter::RANGO_MESES->value }}'">
                        <x-label value="Límite de Mes :" />
                        <x-input class="block w-full" wire:model.defer="monthto" type="month" />
                        <x-jet-input-error for="monthto" />
                    </div>

                    <div class="w-full" x-cloak x-show="typereporte=='{{ getFilter::SEMANAL->value }}'">
                        <x-label value="Semana :" />
                        <x-input class="block w-full" wire:model.defer="week" type="week" />
                        <x-jet-input-error for="week" />
                    </div>

                    <div class="w-full" x-cloak x-show="typereporte=='{{ getFilter::ANUAL->value }}'">
                        <x-label value="Año :" />
                        <div id="parentrpvt_year" x-init="selectVTYear" class="relative">
                            <x-select class="block w-full" x-ref="rpvt_year" id="rpvt_year"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpvt_year">
                                <x-slot name="options">
                                    @foreach ($years as $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="year" />
                    </div>

                    <div class="w-full" x-show="typereporte=='{{ getFilter::DIAS_SELECCIONADOS->value }}'">
                        <x-button wire:click="addday" wire:loading.attr="disabled" wire:key="addday">
                            AGREGAR DÍA</x-button>
                    </div>

                    <div class="w-full" x-show="typereporte=='{{ getFilter::MESES_SELECCIONADOS->value }}'">
                        <x-button wire:click="addmonth" wire:loading.attr="disabled" wire:key="addmonth">
                            AGREGAR MES</x-button>
                    </div>

                    @if (count($days) > 0)
                        <div
                            class="w-full flex flex-col divide-y divide-borderminicard rounded-xl border border-borderminicard">
                            @foreach ($days as $item)
                                <div class="w-full p-1.5 px-3 flex items-center gap-2">
                                    <div class="flex-1 text-xs text-colorlabel font-medium">
                                        {{ formatDate($item, 'ddd DD MMMM Y') }}
                                    </div>
                                    <div>
                                        <x-button-delete wire:click="deleteindex({{ $loop->index }}, 'days')"
                                            wire:loading.attr="disabled" wire:key="day_{{ $loop->iteration }}" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if (count($months) > 0)
                        <div
                            class="w-full flex flex-col divide-y divide-borderminicard rounded-xl border border-borderminicard">
                            @foreach ($months as $item)
                                <div class="w-full p-1.5 px-3 flex items-center gap-2">
                                    <div class="flex-1 text-xs text-colorlabel font-medium">
                                        {{ formatDate($item, 'MMMM Y') }}
                                    </div>
                                    <div>
                                        <x-button-delete wire:click="deleteindex({{ $loop->index }}, 'months')"
                                            wire:loading.attr="disabled" wire:key="month_{{ $loop->iteration }}" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="w-full pt-4 flex flex-col xs:flex-row xs:justify-between gap-1 xs:gap-2">
                    <x-button-secondary wire:click="resetvalues" class="justify-center" type="button"
                        wire:loading.attr="disabled">
                        {{ __('Reset values') }}</x-button-secondary>

                    <div class="flex flex-col xs:flex-row gap-1 xs:gap-2 xs:flex-1 xs:justify-end">
                        <x-button class=" button-export-pdf" x-on:click="exportPDF" wire:loading.attr="disabled">
                            {{ __('Export PDF') }}</x-button>
                        <x-button class="" type="submit" wire:loading.attr="disabled">
                            {{ __('Export Excel') }}</x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>




    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('reportventa', () => ({
                typereporte: @entangle('typereporte').defer,
                viewreporte: @entangle('viewreporte').defer,
                sucursal_id: @entangle('sucursal_id').defer,
                typecomprobante_id: @entangle('typecomprobante_id').defer,
                client_id: @entangle('client_id').defer,
                typepayment_id: @entangle('typepayment_id').defer,
                // monthbox_id: @entangle('monthbox_id'),
                // openbox_id: @entangle('openbox_id').defer,
                // methodpayment_id: @entangle('methodpayment_id').defer,
                moneda_id: @entangle('moneda_id').defer,
                user_id: @entangle('user_id').defer,
                year: @entangle('year').defer,
                init() {
                    this.$watch('typereporte', (value) => {
                        this.rpVTType.val(value).trigger("change");
                    });
                    this.$watch('viewreporte', (value) => {
                        this.rpVTViewreporte.val(value).trigger("change");
                    });
                    this.$watch('typepayment_id', (value) => {
                        this.rpVTTypepayment.val(value).trigger("change");
                    });
                    this.$watch('typecomprobante_id', (value) => {
                        this.rpVTTypecpe.val(value).trigger("change");
                    });
                    this.$watch('sucursal_id', (value) => {
                        this.rpVTSuc.val(value).trigger("change");
                    });
                    this.$watch('client_id', (value) => {
                        this.rpVTClient.val(value).trigger("change");
                    });
                    this.$watch('moneda_id', (value) => {
                        this.rpVTMoneda.val(value).trigger("change");
                    });
                    this.$watch('user_id', (value) => {
                        this.rpVTUser.val(value).trigger("change");
                    });
                    this.$watch('year', (value) => {
                        this.rpVTYear.val(value).trigger("change");
                    });

                    // Livewire.hook('element.initialized', () => {
                    //     $(componentloading).fadeIn();
                    // });

                    Livewire.hook('message.processed', () => {
                        this.rpVTType.select2().val(this.typereporte).trigger('change');
                        this.rpVTViewreporte.select2().val(this.viewreporte).trigger('change');
                        this.rpVTTypecpe.select2().val(this.typecomprobante_id).trigger(
                            'change');
                        this.rpVTSuc.select2().val(this.sucursal_id).trigger('change');
                        this.rpVTClient.select2().val(this.client_id).trigger('change');
                        this.rpVTTypepayment.select2().val(this.typepayment_id).trigger(
                            'change');

                        this.rpVTMoneda.select2().val(this.moneda_id).trigger('change');
                        this.rpVTUser.select2().val(this.user_id).trigger('change');
                        this.rpVTYear.select2().val(this.year).trigger('change');
                        // $(componentloading).fadeOut();
                    });
                },
                exportPDF() {
                    this.$wire.exportpdf().then(url => {
                        if (url) {
                            window.open(url, '_blank');
                        }
                    })
                }
            }))
        })

        function selectVTTypereporte() {
            this.rpVTType = $(this.$refs.rpvt_typereporte).select2();
            this.rpVTType.val(this.typereporte).trigger("change");
            this.rpVTType.on("select2:select", (event) => {
                this.typereporte = event.target.value;
            })
        }

        function selectVTViewreporte() {
            this.rpVTViewreporte = $(this.$refs.rpvt_viewreporte).select2();
            this.rpVTViewreporte.val(this.viewreporte).trigger("change");
            this.rpVTViewreporte.on("select2:select", (event) => {
                this.viewreporte = event.target.value;
            })
        }

        function selectVTTypecpe() {
            this.rpVTTypecpe = $(this.$refs.rpvt_typecpe).select2();
            this.rpVTTypecpe.val(this.typecomprobante_id).trigger("change");
            this.rpVTTypecpe.on("select2:select", (event) => {
                this.typecomprobante_id = event.target.value;
            })
        }

        function selectVTTypepayment() {
            this.rpVTTypepayment = $(this.$refs.rpvt_typepayment_id).select2();
            this.rpVTTypepayment.val(this.typepayment_id).trigger("change");
            this.rpVTTypepayment.on("select2:select", (event) => {
                this.typepayment_id = event.target.value;
            })
        }

        function selectVTSucursal() {
            this.rpVTSuc = $(this.$refs.rpvt_sucursal_id).select2();
            this.rpVTSuc.val(this.sucursal_id).trigger("change");
            this.rpVTSuc.on("select2:select", (event) => {
                this.sucursal_id = event.target.value;
            })
        }

        function selectVTClient() {
            this.rpVTClient = $(this.$refs.rpvt_client_id).select2();
            this.rpVTClient.val(this.client_id).trigger("change");
            this.rpVTClient.on("select2:select", (event) => {
                this.client_id = event.target.value;
            })
        }

        function selectVTMoneda() {
            this.rpVTMoneda = $(this.$refs.rpvt_moneda_id).select2();
            this.rpVTMoneda.val(this.moneda_id).trigger("change");
            this.rpVTMoneda.on("select2:select", (event) => {
                this.moneda_id = event.target.value;
            })
        }

        function selectVTUser() {
            this.rpVTUser = $(this.$refs.rpvt_user_id).select2();
            this.rpVTUser.val(this.user_id).trigger("change");
            this.rpVTUser.on("select2:select", (event) => {
                this.user_id = event.target.value;
            })
        }

        function selectVTYear() {
            this.rpVTYear = $(this.$refs.rpvt_year).select2();
            this.rpVTYear.val(this.year).trigger("change");
            this.rpVTYear.on("select2:select", (event) => {
                this.year = event.target.value;
            })
        }
    </script>
</div>
