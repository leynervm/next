<div>
    <button
        class="border text-colorsubtitleform p-1 sm:p-3 border-borderminicard min-h-24 w-full flex flex-col items-center gap-2 justify-center rounded-lg sm:rounded-2xl hover:shadow hover:shadow-shadowminicard"
        type="button" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 386.802 386.802" fill="currentColor" stroke="currentColor"
            stroke-width="3" stroke-linecap="round" class="block size-9">
            <path
                d="M285.079 162.206a9 9 0 0 1 9-9h17.944c4.971 0 9 4.029 9 9s-4.029 9-9 9h-17.944a9 9 0 0 1-9-9m30.775 61.165a9 9 0 0 0 9 9h24.15l.165.001a9 9 0 0 0 9-9v-92.083c0-4.971-4.029-9-9-9s-9 4.029-9 9v83.082h-15.315a9 9 0 0 0-9 9m-2.577-81.773a8.98 8.98 0 0 0 6.546 2.822 9 9 0 0 0 6.542-15.179l-3.899-4.13c-3.411-3.613-9.106-3.778-12.723-.366-3.614 3.412-3.777 9.108-.365 12.723zM175.125 99.797c20.693 0 37.528 16.835 37.528 37.528s-16.835 37.528-37.528 37.528-37.527-16.835-37.527-37.528 16.835-37.528 37.527-37.528m0 18c-10.768 0-19.527 8.76-19.527 19.528s8.76 19.528 19.527 19.528 19.528-8.76 19.528-19.528c.001-10.767-8.76-19.528-19.528-19.528m151.24 77.374a9 9 0 0 0-13.088-12.359l-3.899 4.129a9 9 0 0 0 .365 12.723 8.97 8.97 0 0 0 6.177 2.457c2.39 0 4.775-.946 6.546-2.821zm-219.043-45.114a67.3 67.3 0 0 1-13.251 1.316c-36.772 0-66.689-29.916-66.689-66.688C27.381 47.915 57.298 18 94.071 18c31.028 0 58.41 21.951 65.107 52.195a9 9 0 1 0 17.574-3.892c-4.103-18.523-14.514-35.349-29.317-47.375C132.412 6.722 113.46 0 94.071 0 47.373 0 9.381 37.99 9.381 84.686s37.991 84.688 84.689 84.688a85.3 85.3 0 0 0 16.806-1.67c4.873-.981 8.027-5.728 7.046-10.6-.982-4.874-5.726-8.024-10.6-7.047M252.437 368.8h-15.315v-83.083a9 9 0 0 0-8.968-9l-51.789-.186v-65.813l63.482 21.192c4.715 1.572 9.813-.973 11.387-5.688a9 9 0 0 0-5.688-11.387l-75.039-25.049a9 9 0 0 0-3.462-.558l-.02.001a8.96 8.96 0 0 0-5.277 1.961 8.9 8.9 0 0 0-2.397 2.932 9 9 0 0 0-.756 2.067 9 9 0 0 0-.23 2.319v86.959a9 9 0 0 0 .762 3.661l.011.024.001.002.01.022.002.003q.004.011.01.021l.003.006.008.019.003.006.009.018.003.008.008.017.005.009.007.015.005.011.007.013.005.012.007.013.006.014.005.011.008.015.004.009.009.017.003.007.01.019.002.005.01.02.003.005.01.021.002.003.011.021.001.003.012.022.001.002.012.024a9 9 0 0 0 1.03 1.588l.001.001.016.02.001.001.015.018.003.003.016.019.001.001.015.018.003.003.015.017.003.004.013.016.005.005.013.015.005.005.012.015.006.006.012.014.006.007.011.013.007.008.011.012.007.008.011.012.008.009.009.01.009.01.008.009.011.012.007.008.011.012.006.007.013.014.005.005.013.015.005.005.014.016.003.003.015.016.004.004.015.016.002.002.017.018.002.002.017.018.019.02a8.98 8.98 0 0 0 6.557 2.869l51.789.186v82.884l-.003.23a8.99 8.99 0 0 0 3.947 7.449 8.96 8.96 0 0 0 5.056 1.553l.191-.002h24.124c4.971 0 9-4.029 9-9s-4.044-8.994-9.015-8.994M368.42 243.424q-.096 0-.191.002H239.764c-4.971 0-9 4.029-9 9s4.029 9 9 9H359.42v107.372h-31.05c-4.971 0-9 4.029-9 9s4.029 9 9 9h40.05a9 9 0 0 0 8.854-10.627 9 9 0 0 0 .146-1.627v-122.12a9 9 0 0 0-9-9m-175.552 65.444h-50.457v-82.307c0-4.971-4.029-9-9-9s-9 4.029-9 9v91.04a8 8 0 0 0-.004.267V377.8c0 4.971 4.029 9 9 9s9-4.029 9-9v-50.932h41.461V377.8c0 4.971 4.029 9 9 9s9-4.029 9-9v-59.932a9 9 0 0 0-9-9m-64.716-190.099a9 9 0 0 1-12.729 0l-7.294-7.294a30 30 0 0 1-5.059 2.103v10.31c0 4.971-4.029 9-9 9s-9-4.029-9-9v-10.309a30 30 0 0 1-5.063-2.104l-7.291 7.294a9 9 0 0 1-12.731-12.726l7.293-7.296a30 30 0 0 1-2.104-5.061H54.87c-4.971 0-9-4.029-9-9s4.029-9 9-9h10.308a30 30 0 0 1 2.104-5.061l-7.293-7.293a9 9 0 0 1 .001-12.728 9 9 0 0 1 12.728 0l7.294 7.295a30 30 0 0 1 5.061-2.103v-10.31c0-4.971 4.029-9 9-9s9 4.029 9 9v10.31c1.76.55 3.451 1.255 5.058 2.102l7.296-7.295a9 9 0 0 1 12.727 12.728l-7.293 7.293a30 30 0 0 1 2.104 5.061h10.307c4.971 0 9 4.029 9 9s-4.029 9-9 9h-10.307a30 30 0 0 1-2.104 5.061l7.294 7.294a9.004 9.004 0 0 1-.003 12.729m-21.824-34.083c0-6.758-5.498-12.256-12.257-12.256-6.761 0-12.261 5.498-12.261 12.256 0 6.759 5.5 12.258 12.261 12.258 6.759 0 12.257-5.499 12.257-12.258" />
        </svg>
        <p class="text-center font-medium leading-none text-[10px] text-colorsubtitleform">REPORTE DE PERSONAL</p>
    </button>

    <x-jet-dialog-modal wire:model="open" maxWidth="md" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Generar reporte de pago a trabajadores') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="exportexcel" class="w-full" x-data="reportemployers">
                <div class="w-full grid grid-cols-1 gap-2">

                    <div class="w-full">
                        <x-label value="Tipo de reporte :" />
                        <div id="parentrpemp_typereporte" x-init="rpEmpTypereporte" class="relative">
                            <x-select class="block w-full" x-ref="rpemp_typereporte" id="rpemp_typereporte"
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
                        <x-label value="Sucursal de trabajadores:" />
                        <div id="parentrpemp_sucursal_id" x-init="rpEmpSucursal" class="relative">
                            <x-select class="block w-full" x-ref="rpemp_sucursal_id" id="rpemp_sucursal_id"
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
                        <div id="parentrpemp_monthbox_id" x-init="rpEmpMonthbox" class="relative">
                            <x-select class="block w-full" x-ref="rpemp_monthbox_id" id="rpemp_monthbox_id"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpemp_monthbox_id">
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

                    {{-- <div class="w-full">
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
                    </div> --}}

                    {{-- <div class="w-full">
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
                    </div> --}}

                    <div class="w-full">
                        <x-label value="Personal :" />
                        <div id="parentrpemp_employer_id" x-init="rpEmpEmployer" class="relative">
                            <x-select class="block w-full" x-ref="rpemp_employer_id" id="rpemp_employer_id"
                                data-dropdown-parent="null" data-placeholder="null">
                                <x-slot name="options">
                                    @foreach ($employers as $item)
                                        <option value="{{ $item->id }}" title="{{ $item->areawork->name ?? '' }}">
                                            {{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="employer_id" />
                    </div>

                    <div class="w-full" x-cloak>
                        <x-label value="Usuario de pago :" />
                        <div id="parentrpemp_user_id" x-init="rpEmpUser" class="relative">
                            <x-select class="block w-full" x-ref="rpemp_user_id" id="rpemp_user_id"
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

                    <div class="w-full" x-cloak x-show="typereporte=='{{ getFilter::ANUAL->value }}'">
                        <x-label value="Año :" />
                        <div id="parentrpemp_year" x-init="rpEmpYear" class="relative">
                            <x-select class="block w-full" x-ref="rpemp_year" id="rpemp_year"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpemp_year">
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
            Alpine.data('reportemployers', () => ({
                typereporte: @entangle('typereporte').defer,
                sucursal_id: @entangle('sucursal_id'),
                monthbox_id: @entangle('monthbox_id'),
                employer_id: @entangle('employer_id').defer,
                user_id: @entangle('user_id').defer,
                year: @entangle('year').defer,
                init() {
                    this.$watch('typereporte', (value) => {
                        this.rpEmpType.val(value).trigger("change");
                        if (value !== @json(getFilter::MESES_SELECCIONADOS->value)) {
                            this.$wire.set('months', []);
                        }
                    });

                    this.$watch('sucursal_id', (value) => {
                        this.rpEmpSuc.val(value).trigger("change");
                    });
                    this.$watch('monthbox_id', (value) => {
                        this.rpEmpMbox.val(value).trigger("change");
                    });
                    this.$watch('employer_id', (value) => {
                        this.rpEmpEmployer.val(value).trigger("change");
                    });
                    this.$watch('user_id', (value) => {
                        this.rpEmpUser.val(value).trigger("change");
                    });
                    this.$watch('year', (value) => {
                        this.rpEmpYear.val(value).trigger("change");
                    });

                    // Livewire.hook('element.initialized', () => {
                    //     $(componentloading).fadeIn();
                    // });

                    Livewire.hook('message.processed', () => {
                        this.rpEmpType.select2().val(this.typereporte).trigger('change');
                        this.rpEmpSuc.select2().val(this.sucursal_id).trigger('change');
                        this.rpEmpMbox.select2().val(this.monthbox_id).trigger('change');
                        this.rpEmpEmployer.select2({
                            templateResult: function(option) {
                                return $(`<strong>${option.text}</strong>
                                <p class="select2-subtitle-option">${option.title}</p>`);
                            }
                        }).val(this.employer_id).trigger('change');
                        this.rpEmpUser.select2().val(this.user_id).trigger('change');
                        this.rpEmpYear.select2().val(this.year).trigger('change');
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

        function rpEmpTypereporte() {
            this.rpEmpType = $(this.$refs.rpemp_typereporte).select2();
            this.rpEmpType.val(this.typereporte).trigger("change");
            this.rpEmpType.on("select2:select", (event) => {
                this.typereporte = event.target.value;
            })
        }

        function rpEmpSucursal() {
            this.rpEmpSuc = $(this.$refs.rpemp_sucursal_id).select2();
            this.rpEmpSuc.val(this.sucursal_id).trigger("change");
            this.rpEmpSuc.on("select2:select", (event) => {
                this.sucursal_id = event.target.value;
            })
        }

        function rpEmpMonthbox() {
            this.rpEmpMbox = $(this.$refs.rpemp_monthbox_id).select2();
            this.rpEmpMbox.val(this.monthbox_id).trigger("change");
            this.rpEmpMbox.on("select2:select", (event) => {
                this.monthbox_id = event.target.value;
            })
        }

        function rpEmpEmployer() {
            this.rpEmpEmployer = $(this.$refs.rpemp_employer_id).select2({
                templateResult: function(option) {
                    return $(`<strong>${option.text}</strong>
                    <p class="select2-subtitle-option">${option.title}</p>`);
                }
            });
            this.rpEmpEmployer.val(this.employer_id).trigger("change");
            this.rpEmpEmployer.on("select2:select", (event) => {
                this.employer_id = event.target.value;
            })
        }

        function rpEmpUser() {
            this.rpEmpUser = $(this.$refs.rpemp_user_id).select2();
            this.rpEmpUser.val(this.user_id).trigger("change");
            this.rpEmpUser.on("select2:select", (event) => {
                this.user_id = event.target.value;
            })
        }

        function rpEmpYear() {
            this.rpEmpYear = $(this.$refs.rpemp_year).select2();
            this.rpEmpYear.val(this.year).trigger("change");
            this.rpEmpYear.on("select2:select", (event) => {
                this.year = event.target.value;
            })
        }
    </script>
</div>
