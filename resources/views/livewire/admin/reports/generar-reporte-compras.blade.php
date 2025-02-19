<div>
    <button
        class="border text-colorsubtitleform p-1 sm:p-3 border-borderminicard min-h-24 w-full flex flex-col items-center gap-2 justify-center rounded-lg sm:rounded-2xl hover:shadow hover:shadow-shadowminicard"
        type="button" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 486.4 486.4" fill="currentColor" stroke="currentColor"
            stroke-width="0.5" stroke-linecap="round" class="block size-9">
            <path
                d="M404.587 35.439h-89.6V9.5c0-5.246-4.254-9.5-9.5-9.5H180.913c-5.246 0-9.5 4.254-9.5 9.5v25.939h-89.6c-5.246 0-9.5 4.254-9.5 9.5V476.9c0 5.246 4.254 9.5 9.5 9.5h322.774c5.246 0 9.5-4.254 9.5-9.5V44.939a9.5 9.5 0 0 0-9.5-9.5M190.413 19h105.574v54.749H190.413zm204.674 448.4H91.313V54.439h80.1V83.25c0 5.246 4.254 9.5 9.5 9.5h124.574c5.246 0 9.5-4.254 9.5-9.5V54.439h80.1z" />
            <path
                d="M131.618 141.596h146.541c5.246 0 9.5-4.254 9.5-9.5s-4.254-9.5-9.5-9.5H131.618c-5.246 0-9.5 4.254-9.5 9.5s4.254 9.5 9.5 9.5m226.038 29.847H131.618c-5.246 0-9.5 4.254-9.5 9.5s4.254 9.5 9.5 9.5h226.038c5.246 0 9.5-4.254 9.5-9.5s-4.254-9.5-9.5-9.5m0 48.848H131.618c-5.246 0-9.5 4.254-9.5 9.5s4.254 9.5 9.5 9.5h226.038c5.246 0 9.5-4.254 9.5-9.5s-4.254-9.5-9.5-9.5m0 45.973H131.618c-5.246 0-9.5 4.254-9.5 9.5s4.254 9.5 9.5 9.5h226.038c5.246 0 9.5-4.254 9.5-9.5s-4.254-9.5-9.5-9.5m-31.607-124.668h31.607c5.246 0 9.5-4.254 9.5-9.5s-4.254-9.5-9.5-9.5h-31.607c-5.246 0-9.5 4.254-9.5 9.5s4.254 9.5 9.5 9.5M261.631 373.82a9.5 9.5 0 0 0-2.818-6.752l-36.227-35.857a9.5 9.5 0 0 0-13.069-.281c-12.546 11.393-19.742 27.592-19.742 44.444 0 16.116 6.335 31.261 17.838 42.647 1.851 1.832 4.267 2.748 6.683 2.748s4.832-.915 6.683-2.748l37.835-37.449a9.5 9.5 0 0 0 2.817-6.752m-46.578 23.333c-4.089-6.473-6.277-13.961-6.277-21.779 0-8.494 2.685-16.752 7.546-23.631l22.306 22.077z" />
            <path
                d="M341.655 332.729c-11.477-11.36-26.728-17.616-42.946-17.616-15.234 0-29.811 5.618-41.044 15.819a9.5 9.5 0 0 0-.296 13.785l29.405 29.105-31.013 30.697a9.5 9.5 0 0 0 0 13.504c11.477 11.36 26.73 17.616 42.949 17.616 33.517 0 60.786-27.034 60.786-60.263-.002-16.115-6.337-31.259-17.84-42.645m-62.924 6.399a42.1 42.1 0 0 1 19.976-5.016c7.885 0 15.437 2.142 21.973 6.147l-20.403 20.195zm19.976 77.509c-7.887 0-15.438-2.142-21.973-6.147l37.611-37.228 19.87-19.667c4.09 6.474 6.278 13.961 6.278 21.779 0 22.753-18.745 41.263-41.786 41.263M247.95 34.2h-32.3c-5.246 0-9.5 4.254-9.5 9.5s4.254 9.5 9.5 9.5h32.3c5.246 0 9.5-4.254 9.5-9.5s-4.254-9.5-9.5-9.5" />
        </svg>

        <p class="text-center font-medium leading-none text-[10px] text-colorsubtitleform">
            REPORTE DE COMPRAS</p>
    </button>

    <x-jet-dialog-modal wire:model="open" maxWidth="md" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Generar reporte de compras') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="exportexcel" class="w-full" x-data="reportventa">
                <div class="w-full grid grid-cols-1 gap-2">

                    <div class="w-full">
                        <x-label value="Tipo de reporte :" />
                        <div id="parentrpcmp_typereporte" x-init="selectCMPTypereporte" class="relative">
                            <x-select class="block w-full" x-ref="rpcmp_typereporte" id="rpcmp_typereporte"
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
                        <div id="parentrpcmp_viewreporte" x-init="selectCMPViewreporte" class="relative">
                            <x-select class="block w-full" x-ref="rpcmp_viewreporte" id="rpcmp_viewreporte"
                                data-dropdown-parent="null">
                                <x-slot name="options">
                                    <option value="0">POR DEFECTO</option>
                                    <option value="1">DETALLADO</option>
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="viewreporte" />
                    </div>

                    <div class="w-full">
                        <x-label value="Sucursal :" />
                        <div id="parentrpcmp_sucursal_id" x-init="selectCMPSucursal" class="relative">
                            <x-select class="block w-full" x-ref="rpcmp_sucursal_id" id="rpcmp_sucursal_id"
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

                    <div class="w-full" x-cloak x-show="'{{ count($typepayments) > 1 }}'" style="display: none;">
                        <x-label value="Tipo de pago :" />
                        <div id="parentrpcmp_typepayment" x-init="selectCMPTypepayment" class="relative">
                            <x-select class="block w-full" x-ref="rpcmp_typepayment_id" id="rpcmp_typepayment_id"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpcmp_typepayment_id">
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

                    <div class="w-full" x-cloak x-show="'{{ count($proveedors) > 1 }}'" style="display: none;">
                        <x-label value="Proveedor :" />
                        <div id="parentrpcmp_proveedor_id" x-init="selectCMPProveedor" class="relative">
                            <x-select class="block w-full" x-ref="rpcmp_proveedor_id" wire:key="rpcmp_proveedor_id"
                                id="rpcmp_proveedor_id" data-dropdown-parent="null" data-placeholder="null">
                                <x-slot name="options">
                                    @foreach ($proveedors as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="proveedor_id" />
                    </div>

                    <div class="w-full" x-cloak x-show="'{{ count($monedas) > 1 }}'" style="display: none;">
                        <x-label value="Moneda :" />
                        <div id="parentrpcmp_moneda_id" x-init="selectCMPMoneda" class="relative">
                            <x-select class="block w-full" x-ref="rpcmp_moneda_id" wire:key="rpcmp_moneda_id"
                                id="rpcmp_moneda_id" data-dropdown-parent="null" data-placeholder="null">
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
                        <x-label value="Usuario de compra :" />
                        <div id="parentrpcmp_user_id" x-init="selectCMPUser" class="relative">
                            <x-select class="block w-full" x-ref="rpcmp_user_id" id="rpcmp_user_id"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpcmp_user_id">
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

                    <div class="w-full" x-cloak x-show="typereporte=='{{ getFilter::DIARIO->value }}'">
                        <x-label value="Fecha :" />
                        <x-input class="block w-full" wire:model.defer="date" type="date" />
                        <x-jet-input-error for="date" />
                    </div>

                    <div class="w-full" x-cloak x-show="typereporte=='{{ getFilter::ANUAL->value }}'">
                        <x-label value="Año :" />
                        <div id="parentrpcmp_year" x-init="selectCMPYear" class="relative">
                            <x-select class="block w-full" x-ref="rpcmp_year" id="rpcmp_year"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpcmp_year">
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

                    <div class="w-full" x-cloak x-show="typereporte=='{{ getFilter::MENSUAL->value }}'">
                        <x-label value="Mes :" />
                        <div id="parentrpcmp_month" x-init="selectCMPMonth" class="relative">
                            <x-select class="block w-full" x-ref="rpcmp_month" id="rpcmp_month"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpcmp_month">
                                <x-slot name="options">
                                    @foreach ($months as $item)
                                        <option value="{{ $item }}">
                                            {{ formatDate($item, 'MMMM Y') }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="month" />
                    </div>

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
                typepayment_id: @entangle('typepayment_id').defer,
                proveedor_id: @entangle('proveedor_id').defer,
                moneda_id: @entangle('moneda_id').defer,
                user_id: @entangle('user_id').defer,
                year: @entangle('year').defer,
                month: @entangle('month').defer,
                date: @entangle('date').defer,
                init() {
                    this.$watch('typereporte', (value) => {
                        this.rpCMPType.val(value).trigger("change");
                    });
                    this.$watch('viewreporte', (value) => {
                        this.rpCMPview.val(value).trigger("change");
                    });
                    this.$watch('typepayment_id', (value) => {
                        this.rpCMPTypepayment.val(value).trigger("change");
                    });
                    this.$watch('proveedor_id', (value) => {
                        this.rpCMPprov.val(value).trigger("change");
                    });
                    this.$watch('sucursal_id', (value) => {
                        this.rpCMPSuc.val(value).trigger("change");
                    });
                    this.$watch('moneda_id', (value) => {
                        this.rpCMPMoneda.val(value).trigger("change");
                    });
                    this.$watch('user_id', (value) => {
                        this.rpCMPUser.val(value).trigger("change");
                    });
                    this.$watch('year', (value) => {
                        this.rpCMPYear.val(value).trigger("change");
                    });
                    this.$watch('month', (value) => {
                        this.rpCMPMonth.val(value).trigger("change");
                    });

                    // Livewire.hook('element.initialized', () => {
                    //     $(componentloading).fadeIn();
                    // });

                    Livewire.hook('message.processed', () => {
                        this.rpCMPType.select2().val(this.typereporte).trigger('change');
                        this.rpCMPview.select2().val(this.viewreporte).trigger('change');
                        this.rpCMPSuc.select2().val(this.sucursal_id).trigger('change');
                        this.rpCMPTypepayment.select2().val(this.typepayment_id).trigger(
                            'change');
                        this.rpCMPprov.select2().val(this.proveedor_id).trigger('change');
                        this.rpCMPMoneda.select2().val(this.moneda_id).trigger('change');
                        this.rpCMPUser.select2().val(this.user_id).trigger('change');
                        this.rpCMPYear.select2().val(this.year).trigger('change');
                        this.rpCMPMonth.select2().val(this.month).trigger('change');
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

        function selectCMPTypereporte() {
            this.rpCMPType = $(this.$refs.rpcmp_typereporte).select2();
            this.rpCMPType.val(this.typereporte).trigger("change");
            this.rpCMPType.on("select2:select", (event) => {
                this.typereporte = event.target.value;
            })
        }

        function selectCMPViewreporte() {
            this.rpCMPview = $(this.$refs.rpcmp_viewreporte).select2();
            this.rpCMPview.val(this.viewreporte).trigger("change");
            this.rpCMPview.on("select2:select", (event) => {
                this.viewreporte = event.target.value;
            })
        }

        function selectCMPProveedor() {
            this.rpCMPprov = $(this.$refs.rpcmp_proveedor_id).select2();
            this.rpCMPprov.val(this.proveedor_id).trigger("change");
            this.rpCMPprov.on("select2:select", (event) => {
                this.proveedor_id = event.target.value;
            })
        }

        function selectCMPTypepayment() {
            this.rpCMPTypepayment = $(this.$refs.rpcmp_typepayment_id).select2();
            this.rpCMPTypepayment.val(this.typepayment_id).trigger("change");
            this.rpCMPTypepayment.on("select2:select", (event) => {
                this.typepayment_id = event.target.value;
            })
        }

        function selectCMPSucursal() {
            this.rpCMPSuc = $(this.$refs.rpcmp_sucursal_id).select2();
            this.rpCMPSuc.val(this.sucursal_id).trigger("change");
            this.rpCMPSuc.on("select2:select", (event) => {
                this.sucursal_id = event.target.value;
            })
        }

        function selectCMPMoneda() {
            this.rpCMPMoneda = $(this.$refs.rpcmp_moneda_id).select2();
            this.rpCMPMoneda.val(this.moneda_id).trigger("change");
            this.rpCMPMoneda.on("select2:select", (event) => {
                this.moneda_id = event.target.value;
            })
        }

        function selectCMPUser() {
            this.rpCMPUser = $(this.$refs.rpcmp_user_id).select2();
            this.rpCMPUser.val(this.user_id).trigger("change");
            this.rpCMPUser.on("select2:select", (event) => {
                this.user_id = event.target.value;
            })
        }

        function selectCMPYear() {
            this.rpCMPYear = $(this.$refs.rpcmp_year).select2();
            this.rpCMPYear.val(this.year).trigger("change");
            this.rpCMPYear.on("select2:select", (event) => {
                this.year = event.target.value;
            })
        }

        function selectCMPMonth() {
            this.rpCMPMonth = $(this.$refs.rpcmp_month).select2();
            this.rpCMPMonth.val(this.month).trigger("change");
            this.rpCMPMonth.on("select2:select", (event) => {
                this.month = event.target.value;
            })
        }
    </script>
</div>
