<div>
    <button
        class="border text-colorsubtitleform p-1 sm:p-3 border-borderminicard min-h-24 w-full flex flex-col items-center gap-2 justify-center rounded-lg sm:rounded-2xl hover:shadow hover:shadow-shadowminicard"
        type="button" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="currentColor" stroke="currentColor"
            stroke-width="0.8" stroke-linecap="round" class="block size-9">
            <path
                d="M47.02 31.837h-.49V4.408a.98.98 0 0 0-.98-.98H29.388V.98a.98.98 0 0 0-.98-.98h-8.816a.98.98 0 0 0-.98.98v2.449H2.449a.98.98 0 0 0-.98.98v27.429h-.49a.98.98 0 0 0-.98.98v3.429c0 .541.439.98.98.98h14.923l-4.987 8.312 1.68 1.008 5.592-9.323h4.833V48h1.959V37.224h4.833l5.592 9.32 1.68-1.008-4.986-8.312H47.02a.98.98 0 0 0 .98-.98v-3.429a.98.98 0 0 0-.98-.98M20.571 1.959h6.857v1.469h-6.857zM3.429 5.388h41.143v26.449H3.429zm42.612 29.878H1.959v-1.469h44.082z" />
            <path
                d="M16.653 8.816c-5.401 0-9.796 4.394-9.796 9.796s4.394 9.796 9.796 9.796 9.796-4.394 9.796-9.796-4.394-9.796-9.796-9.796m0 17.633c-4.321 0-7.837-3.516-7.837-7.837 0-3.989 2.997-7.289 6.857-7.773v7.773c0 .541.439.98.98.98h7.773c-.484 3.86-3.784 6.857-7.773 6.857m.98-8.816v-6.794a7.85 7.85 0 0 1 6.794 6.794zm16.163-4.898h6.857v1.959h-6.857zm0 2.939h6.857v1.959h-6.857zm0 2.939h6.857v1.959h-6.857zm0-8.816h4.408v1.959h-4.408zm-1.959 1.959V9.797h-4.408a.98.98 0 0 0-.693.287l-1.469 1.469 1.385 1.385 1.182-1.183zm-1.47 11.264h11.265v1.959H30.367zm-2.939 3.429h14.204v1.959H27.429z" />
        </svg>

        <p class="text-center font-medium leading-none text-[10px] text-colorsubtitleform">REPORTE DE PRODUCTOS</p>
    </button>

    <x-jet-dialog-modal wire:model="open" maxWidth="md" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Generar reporte de productos') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="exportexcel" class="w-full" x-data="reportproducto">
                <div class="w-full grid grid-cols-1 gap-2">

                    <div class="w-full">
                        <x-label value="Tipo de reporte :" />
                        <div id="parentrpprod_typereporte" x-init="selectProdTypereporte" class="relative">
                            <x-select class="block w-full" x-ref="rpprod_typereporte" id="rpprod_typereporte"
                                data-dropdown-parent="null" data-minimum-results-for-search="Infinity">
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
                        <div id="parentrpprod_viewreporte" x-init="selectProdViewreporte" class="relative">
                            <x-select class="block w-full" x-ref="rpprod_viewreporte" id="rpprod_viewreporte"
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

                    <div class="w-full" x-cloak x-show="typereporte=='{{ getFilter::TOP_TEN_PRODUCTOS->value }}'">
                        <x-label value="Sucursal de salida:" />
                        <div id="parentrpprod_sucursal_id" x-init="selectProdSucursal" class="relative">
                            <x-select class="block w-full" x-ref="rpprod_sucursal_id" id="rpprod_sucursal_id"
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

                    <div class="w-full" x-cloak
                        x-show="typereporte=='{{ getFilter::DEFAULT->value }}' && producto_id==''">
                        <x-label value="Filtrar categoría :" />
                        <div id="parentrpprod_category_id" x-init="selectProdCategory" class="relative">
                            <x-select class="block w-full" x-ref="rpprod_category_id" id="rpprod_category_id"
                                data-dropdown-parent="null" data-placeholder="null" {{-- data-minimum-input-length="3" --}}>
                                <x-slot name="options">
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="category_id" />
                    </div>

                    <div class="w-full" x-cloak
                        x-show="typereporte=='{{ getFilter::DEFAULT->value }}' && producto_id==''">
                        <x-label value="Filtrar subcategoría :" />
                        <div id="parentrpprod_subcategory_id" x-init="selectProdSubcategory" class="relative">
                            <x-select class="block w-full" x-ref="rpprod_subcategory_id" id="rpprod_subcategory_id"
                                data-dropdown-parent="null" data-placeholder="null" {{-- data-minimum-input-length="3" --}}>
                                <x-slot name="options">
                                    @foreach ($subcategories as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="subcategory_id" />
                    </div>

                    <div class="w-full" x-cloak x-show="typereporte!=='{{ getFilter::TOP_TEN_PRODUCTOS->value }}'">
                        <x-label value="Filtrar producto :" />
                        <div id="parentrpprod_producto_id" x-init="selectProdProducto" class="relative">
                            <x-select class="block w-full" x-ref="rpprod_producto_id" id="rpprod_producto_id"
                                data-dropdown-parent="null" data-placeholder="null">
                                <x-slot name="options">
                                    @foreach ($productos as $item)
                                        <option
                                            data-image="{{ !empty($item->image) ? pathURLProductImage($item->image) : null }}"
                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="producto_id" />
                    </div>

                    <div class="w-full" x-cloak
                        x-show="'{{ count($almacens) > 1 }}' && typereporte!=='{{ getFilter::KARDEX_PRODUCTOS->value }}'"
                        style="display: none;">
                        <x-label value="Filtrar almacén :" />
                        <div id="parentrpprod_almacen" x-init="selectProdAlmacen" class="relative">
                            <x-select class="block w-full" x-ref="rpprod_almacen_id" id="rpprod_almacen_id"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpprod_almacen_id">
                                <x-slot name="options">
                                    @foreach ($almacens as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="almacen_id" />
                    </div>

                    <div class="w-full" x-cloak
                        x-show="'{{ view()->shared('empresa')->usarLista() }}' && typereporte=='{{ getFilter::DEFAULT->value }}'"
                        style="display: none;">
                        <x-label value="Filtrar lista de precios :" />
                        <div id="parentrpprod_pricetype" x-init="selectProdPricetype" class="relative">
                            <x-select class="block w-full" x-ref="rpprod_pricetype_id" id="rpprod_pricetype_id"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpprod_pricetype_id">
                                <x-slot name="options">
                                    @foreach ($pricetypes as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="pricetype_id" />
                    </div>


                    <div class="w-full" x-cloak
                        x-show="!['{{ getFilter::DEFAULT->value }}', '{{ getFilter::KARDEX_PRODUCTOS->value }}'].includes(typereporte)">
                        <x-label value="Filtrar usuario :" />
                        <div id="parentrpprod_user_id" x-init="selectProdUser" class="relative">
                            <x-select class="block w-full" x-ref="rpprod_user_id" id="rpprod_user_id"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpprod_user_id">
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
                        <div id="parentrpprod_year" x-init="selectProdYear" class="relative">
                            <x-select class="block w-full" x-ref="rpprod_year" id="rpprod_year"
                                data-dropdown-parent="null" data-placeholder="null" wire:key="rpprod_year">
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
            Alpine.data('reportproducto', () => ({
                typereporte: @entangle('typereporte').defer,
                viewreporte: @entangle('viewreporte').defer,
                sucursal_id: @entangle('sucursal_id').defer,
                producto_id: @entangle('producto_id').defer,
                almacen_id: @entangle('almacen_id').defer,
                category_id: @entangle('category_id'),
                pricetype_id: @entangle('pricetype_id').defer,
                subcategory_id: @entangle('subcategory_id').defer,
                serie: @entangle('serie').defer,
                user_id: @entangle('user_id').defer,
                year: @entangle('year').defer,
                init() {
                    this.$watch('typereporte', (value) => {
                        this.rpProdType.val(value).trigger("change");
                    });
                    this.$watch('viewreporte', (value) => {
                        this.rpProdViewreporte.val(value).trigger("change");
                    });
                    this.$watch('producto_id', (value) => {
                        this.rpProdProducto.val(value).trigger("change");
                    });
                    this.$watch('sucursal_id', (value) => {
                        this.rpProdSuc.val(value).trigger("change");
                    });
                    this.$watch('category_id', (value) => {
                        this.rpProdCategory.val(value).trigger("change");
                    });
                    this.$watch('subcategory_id', (value) => {
                        this.rpProdSubcategory.val(value).trigger("change");
                    });
                    this.$watch('almacen_id', (value) => {
                        this.rpProdAlmacen.val(value).trigger("change");
                    });
                    this.$watch('pricetype_id', (value) => {
                        this.rpProdPricetype.val(value).trigger("change");
                    });
                    this.$watch('user_id', (value) => {
                        this.rpProdUser.val(value).trigger("change");
                    });
                    this.$watch('year', (value) => {
                        this.rpProdYear.val(value).trigger("change");
                    });

                    // Livewire.hook('element.initialized', () => {
                    //     $(componentloading).fadeIn();
                    // });

                    Livewire.hook('message.processed', () => {
                        this.rpProdType.select2().val(this.typereporte).trigger('change');
                        this.rpProdViewreporte.select2().val(this.viewreporte).trigger(
                            'change');
                        this.rpProdProducto.select2({
                            templateResult: function(data) {
                                if (!data.id) {
                                    return data.text;
                                }
                                const image = $(data.element).data('image') ?? '';
                                let html = `<div class="custom-list-select">
                                    <div class="image-custom-select">`;
                                if (image) {
                                    html +=
                                        `<img src="${image}" class="w-full h-full object-scale-down block" alt="${data.text}">`;
                                } else {
                                    html +=
                                        `<x-icon-image-unknown class="w-full h-full" />`;
                                }
                                html += `</div>
                                        <div class="content-custom-select">
                                            <p class="title-custom-select">
                                                ${data.text}</p>
                                        </div>
                                    </div>`;
                                return $(html);
                            }
                        }).val(this.producto_id).trigger('change');
                        this.rpProdCategory.select2().val(this.category_id).trigger('change');
                        this.rpProdPricetype.select2().val(this.pricetype_id).trigger('change');
                        this.rpProdSubcategory.select2().val(this.subcategory_id).trigger(
                            'change');
                        this.rpProdSuc.select2().val(this.sucursal_id).trigger('change');
                        this.rpProdAlmacen.select2().val(this.almacen_id).trigger(
                            'change');
                        this.rpProdUser.select2().val(this.user_id).trigger('change');
                        this.rpProdYear.select2().val(this.year).trigger('change');
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

        function selectProdTypereporte() {
            this.rpProdType = $(this.$refs.rpprod_typereporte).select2();
            this.rpProdType.val(this.typereporte).trigger("change");
            this.rpProdType.on("select2:select", (event) => {
                this.typereporte = event.target.value;
            })
        }

        function selectProdViewreporte() {
            this.rpProdViewreporte = $(this.$refs.rpprod_viewreporte).select2();
            this.rpProdViewreporte.val(this.viewreporte).trigger("change");
            this.rpProdViewreporte.on("select2:select", (event) => {
                this.viewreporte = event.target.value;
            })
        }

        function selectProdSucursal() {
            this.rpProdSuc = $(this.$refs.rpprod_sucursal_id).select2();
            this.rpProdSuc.val(this.sucursal_id).trigger("change");
            this.rpProdSuc.on("select2:select", (event) => {
                this.sucursal_id = event.target.value;
            })
        }

        function selectProdClient() {
            this.rpProdClient = $(this.$refs.rpprod_client_id).select2();
            this.rpProdClient.val(this.client_id).trigger("change");
            this.rpProdClient.on("select2:select", (event) => {
                this.client_id = event.target.value;
            })
        }

        function selectProdProducto() {
            this.rpProdProducto = $(this.$refs.rpprod_producto_id).select2({
                templateResult: function(data) {
                    if (!data.id) {
                        return data.text;
                    }
                    const image = $(data.element).data('image') ?? '';
                    let html = `<div class="custom-list-select">
                                    <div class="image-custom-select">`;
                    if (image) {
                        html +=
                            `<img src="${image}" class="w-full h-full object-scale-down block" alt="${data.text}">`;
                    } else {
                        html +=
                            `<x-icon-image-unknown class="w-full h-full" />`;
                    }
                    html += `</div>
                        <div class="content-custom-select">
                            <p class="title-custom-select">
                                ${data.text}</p>
                        </div>
                    </div>`;
                    return $(html);
                }
            });
            this.rpProdProducto.val(this.producto_id).trigger("change");
            this.rpProdProducto.on("select2:select", (event) => {
                this.producto_id = event.target.value;
            })
        }

        function selectProdCategory() {
            this.rpProdCategory = $(this.$refs.rpprod_category_id).select2();
            this.rpProdCategory.val(this.category_id).trigger("change");
            this.rpProdCategory.on("select2:select", (event) => {
                this.category_id = event.target.value;
            })
        }

        function selectProdSubcategory() {
            this.rpProdSubcategory = $(this.$refs.rpprod_subcategory_id).select2();
            this.rpProdSubcategory.val(this.subcategory_id).trigger("change");
            this.rpProdSubcategory.on("select2:select", (event) => {
                this.subcategory_id = event.target.value;
            })
        }

        function selectProdPricetype() {
            this.rpProdPricetype = $(this.$refs.rpprod_pricetype_id).select2();
            this.rpProdPricetype.val(this.pricetype_id).trigger("change");
            this.rpProdPricetype.on("select2:select", (event) => {
                this.pricetype_id = event.target.value;
            })
        }

        function selectProdAlmacen() {
            this.rpProdAlmacen = $(this.$refs.rpprod_almacen_id).select2();
            this.rpProdAlmacen.val(this.almacen_id).trigger("change");
            this.rpProdAlmacen.on("select2:select", (event) => {
                this.almacen_id = event.target.value;
            })
        }

        function selectProdUser() {
            this.rpProdUser = $(this.$refs.rpprod_user_id).select2();
            this.rpProdUser.val(this.user_id).trigger("change");
            this.rpProdUser.on("select2:select", (event) => {
                this.user_id = event.target.value;
            })
        }

        function selectProdYear() {
            this.rpProdYear = $(this.$refs.rpprod_year).select2();
            this.rpProdYear.val(this.year).trigger("change");
            this.rpProdYear.on("select2:select", (event) => {
                this.year = event.target.value;
            })
        }
    </script>
</div>
