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
        <p class="text-center font-medium leading-none text-[10px] text-colorsubtitleform">REPORTE DE CAJA</p>
    </button>

    <x-jet-dialog-modal wire:model="open" maxWidth="md" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Generar reporte de caja') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="exportexcel" class="w-full" x-data="reportcaja">
                <div class="w-full grid grid-cols-1 gap-2">

                    <div class="w-full">
                        <x-label value="Tipo de reporte :" />
                        <div id="parentrpcaja_typereporte" x-init="rpCajaTypereporte" class="relative">
                            <x-select class="block w-full" x-ref="rpcaja_typereporte" id="rpcaja_typereporte"
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
                        <x-label value="Sucursal de caja:" />
                        <div id="parentrpcaja_sucursal_id" x-init="rpCajaSucursal" class="relative">
                            <x-select class="block w-full" x-ref="rpcaja_sucursal_id" id="rpcaja_sucursal_id"
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

                    <div class="w-full" x-cloak x-show="sucursal_id!=='' && typereporte=='{{ getFilter::DEFAULT }}'">
                        <x-label value="Caja mensual de pago :" />
                        <div id="parentrpcaja_monthbox_id" x-init="rpCajaMonthbox" class="relative">
                            <x-select class="block w-full" x-ref="rpcaja_monthbox_id" id="rpcaja_monthbox_id"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpcaja_monthbox_id">
                                <x-slot name="options">
                                    @foreach ($monthboxes as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="monthbox_id" />
                    </div>

                    <div class="w-full" x-cloak x-show="sucursal_id!=='' && typereporte=='{{ getFilter::DEFAULT }}'">
                        <x-label value="Caja diaria de pago :" />
                        <div id="parentrpcaja_openbox_id" x-init="rpCajaOpenbox" class="relative">
                            <x-select class="block w-full" x-ref="rpcaja_openbox_id" id="rpcaja_openbox_id"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpcaja_openbox_id">
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
                    </div>

                    <div class="w-full">
                        <x-label value="Tipo de movimiento :" />
                        <div id="parentrpcaja_typemovement" x-init="rpCajaTypemovement" class="relative">
                            <x-select class="block w-full" x-ref="rpcaja_typemovement" id="rpcaja_typemovement"
                                data-dropdown-parent="null" data-placeholder="null">
                                <x-slot name="options">
                                    @foreach ($typemovements as $item)
                                        <option value="{{ $item->value }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="typemovement" />
                    </div>

                    <div class="w-full">
                        <x-label value="Concepto de pago :" />
                        <div id="parentrpcaja_concept_id" x-init="rpCajaConcept" class="relative">
                            <x-select class="block w-full" x-ref="rpcaja_concept_id" id="rpcaja_concept_id"
                                data-dropdown-parent="null" data-placeholder="null">
                                <x-slot name="options">
                                    @foreach ($concepts as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="concept_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Forma de pago :" />
                        <div id="parentrpcaja_methodpayment_id" x-init="rpCajaMethod" class="relative">
                            <x-select class="block w-full" x-ref="rpcaja_methodpayment_id"
                                id="rpcaja_methodpayment_id" data-dropdown-parent="null" data-placeholder="null">
                                <x-slot name="options">
                                    @foreach ($methodpayments as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="methodpayment_id" />
                    </div>

                    {{-- @if (count($monedas) > 1) --}}
                    <div class="w-full">
                        <x-label value="Moneda :" />
                        <div id="parentrpcaja_moneda_id" x-init="rpCajaMoneda" class="relative">
                            <x-select class="block w-full" x-ref="rpcaja_moneda_id" wire:key="rpcaja_moneda_id"
                                id="rpcaja_moneda_id" data-dropdown-parent="null" data-placeholder="null">
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
                    {{-- @endif --}}

                    <div class="w-full" x-cloak x-show="openbox_id == ''">
                        <x-label value="Usuario de caja :" />
                        <div id="parentrpcaja_user_id" x-init="rpCajaUser" class="relative">
                            <x-select class="block w-full" x-ref="rpcaja_user_id" id="rpcaja_user_id"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpcaja_user_id">
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
                        <div id="parentrpcaja_year" x-init="rpCajaYear" class="relative">
                            <x-select class="block w-full" x-ref="rpcaja_year" id="rpcaja_year"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpcaja_year">
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

                    {{-- <div class="w-full">
                        <x-label value="Funciones del reporte:" />
                        <div id="parentrpcaja_typereporte" x-init="rpCajaTypereporte" class="relative">
                            <x-select class="block w-full" x-ref="rpcaja_typereporte" id="rpcaja_typereporte"
                                data-dropdown-parent="null" data-placeholder="null">
                                <x-slot name="options">
                                    @foreach ($typereportes as $item)
                                        <option value="{{ $item->value }}">{{ $item->label }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="box_id" />
                    </div> --}}

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
            Alpine.data('reportcaja', () => ({
                typereporte: @entangle('typereporte').defer,
                typemovement: @entangle('typemovement'),
                sucursal_id: @entangle('sucursal_id'),
                monthbox_id: @entangle('monthbox_id'),
                openbox_id: @entangle('openbox_id').defer,
                concept_id: @entangle('concept_id').defer,
                methodpayment_id: @entangle('methodpayment_id').defer,
                moneda_id: @entangle('moneda_id').defer,
                user_id: @entangle('user_id').defer,
                year: @entangle('year').defer,
                init() {
                    this.$watch('typereporte', (value) => {
                        this.rpCajaType.val(value).trigger("change");
                        if (value !== @json(getFilter::MESES_SELECCIONADOS->value)) {
                            this.$wire.set('months', []);
                        }
                        if (value !== @json(getFilter::DIAS_SELECCIONADOS->value)) {
                            this.$wire.set('days', []);
                        }
                    });
                    this.$watch('typemovement', (value) => {
                        this.rpCajaTypemovement.val(value).trigger("change");
                    });
                    this.$watch('sucursal_id', (value) => {
                        this.rpCajaSuc.val(value).trigger("change");
                    });
                    this.$watch('concept_id', (value) => {
                        this.rpCajaConcept.val(value).trigger("change");
                    });
                    this.$watch('monthbox_id', (value) => {
                        this.rpCajaMbox.val(value).trigger("change");
                    });
                    this.$watch('openbox_id', (value) => {
                        this.rpCajaObox.val(value).trigger("change");
                        if (value !== '') {
                            this.$wire.set('user_id', null);
                        }
                    });
                    this.$watch('moneda_id', (value) => {
                        this.rpCajaMoneda.val(value).trigger("change");
                    });
                    this.$watch('user_id', (value) => {
                        this.rpCajaUser.val(value).trigger("change");
                    });
                    this.$watch('year', (value) => {
                        this.rpCajaYear.val(value).trigger("change");
                    });

                    // Livewire.hook('element.initialized', () => {
                    //     $(componentloading).fadeIn();
                    // });

                    Livewire.hook('message.processed', () => {
                        this.rpCajaType.select2().val(this.typereporte).trigger('change');
                        this.rpCajaTypemovement.select2().val(this.typemovement).trigger(
                            'change');
                        this.rpCajaSuc.select2().val(this.sucursal_id).trigger('change');
                        this.rpCajaConcept.select2().val(this.concept_id).trigger('change');
                        this.rpCajaMethod.select2().val(this.methodpayment_id).trigger(
                            'change');
                        this.rpCajaMbox.select2().val(this.monthbox_id).trigger('change');
                        this.rpCajaObox.select2({
                            templateResult: (option) => {
                                return $(`<strong>${option.text}</strong>
                                <p class="select2-subtitle-option">${option.title}</p>`);
                            }
                        }).val(this.openbox_id).trigger('change');
                        this.rpCajaMoneda.select2().val(this.moneda_id).trigger('change');
                        this.rpCajaUser.select2().val(this.user_id).trigger('change');
                        this.rpCajaYear.select2().val(this.year).trigger('change');
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

        function rpCajaTypereporte() {
            this.rpCajaType = $(this.$refs.rpcaja_typereporte).select2();
            this.rpCajaType.val(this.typereporte).trigger("change");
            this.rpCajaType.on("select2:select", (event) => {
                this.typereporte = event.target.value;
            })
        }

        function rpCajaTypemovement() {
            this.rpCajaTypemovement = $(this.$refs.rpcaja_typemovement).select2();
            this.rpCajaTypemovement.val(this.typemovement).trigger("change");
            this.rpCajaTypemovement.on("select2:select", (event) => {
                this.typemovement = event.target.value;
            })
        }

        function rpCajaSucursal() {
            this.rpCajaSuc = $(this.$refs.rpcaja_sucursal_id).select2();
            this.rpCajaSuc.val(this.sucursal_id).trigger("change");
            this.rpCajaSuc.on("select2:select", (event) => {
                this.sucursal_id = event.target.value;
            })
        }

        function rpCajaConcept() {
            this.rpCajaConcept = $(this.$refs.rpcaja_concept_id).select2();
            this.rpCajaConcept.val(this.concept_id).trigger("change");
            this.rpCajaConcept.on("select2:select", (event) => {
                this.concept_id = event.target.value;
            })
        }

        function rpCajaMethod() {
            this.rpCajaMethod = $(this.$refs.rpcaja_methodpayment_id).select2();
            this.rpCajaMethod.val(this.methodpayment_id).trigger("change");
            this.rpCajaMethod.on("select2:select", (event) => {
                this.methodpayment_id = event.target.value;
            })
        }

        function rpCajaMonthbox() {
            this.rpCajaMbox = $(this.$refs.rpcaja_monthbox_id).select2();
            this.rpCajaMbox.val(this.monthbox_id).trigger("change");
            this.rpCajaMbox.on("select2:select", (event) => {
                this.monthbox_id = event.target.value;
            })
        }

        function rpCajaOpenbox() {
            this.rpCajaObox = $(this.$refs.rpcaja_openbox_id).select2();
            this.rpCajaObox.val(this.openbox_id).trigger("change");
            this.rpCajaObox.on("select2:select", (event) => {
                this.openbox_id = event.target.value;
            })
        }

        function rpCajaMoneda() {
            this.rpCajaMoneda = $(this.$refs.rpcaja_moneda_id).select2();
            this.rpCajaMoneda.val(this.moneda_id).trigger("change");
            this.rpCajaMoneda.on("select2:select", (event) => {
                this.moneda_id = event.target.value;
            })
        }

        function rpCajaUser() {
            this.rpCajaUser = $(this.$refs.rpcaja_user_id).select2();
            this.rpCajaUser.val(this.user_id).trigger("change");
            this.rpCajaUser.on("select2:select", (event) => {
                this.user_id = event.target.value;
            })
        }

        function rpCajaYear() {
            this.rpCajaYear = $(this.$refs.rpcaja_year).select2();
            this.rpCajaYear.val(this.year).trigger("change");
            this.rpCajaYear.on("select2:select", (event) => {
                this.year = event.target.value;
            })
        }

        // function formatOption(option) {
        //     var $option = $(
        //         '<strong>' + option.text + '</strong><p class="select2-subtitle-option">' + option.title + '</p>'
        //     );
        //     return $option;
        // };
    </script>
</div>
