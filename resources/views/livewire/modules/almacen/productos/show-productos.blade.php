<div x-data="loadproductos">
    <div wire:loading.flex class="loading-overlay rounded hidden fixed">
        <x-loading-next />
    </div>

    @if ($productos->hasPages())
        <div class="w-full pb-2">
            {{ $productos->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex flex-wrap items-center gap-2 mt-4 ">
        <div class="w-full max-w-md">
            <x-label value="Buscar producto :" />
            <div class="relative flex items-center">
                <span class="absolute">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 mx-3 text-next-300">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </span>
                <x-input placeholder="Buscar" class="block w-full pl-9" wire:model.lazy="search">
                </x-input>
            </div>
        </div>

        @if (count($marcas) > 1)
            <div class="w-full max-w-xs">
                <x-label value="Marca :" />
                <div class="relative" id="parentmarca" x-init="selectMarca">
                    <x-select class="block w-full" id="marca" x-ref="selectmarca" data-placeholder="null"
                        data-minimum-results-for-search="2">
                        <x-slot name="options">
                            @foreach ($marcas as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
                <x-jet-input-error for="searchmarca" />
            </div>
        @endif

        @if (count($categorias) > 1)
            <div class="w-full max-w-xs">
                <x-label value="Categoría :" />
                <div class="relative" id="parentcategory" x-init="selectCategory">
                    <x-select class="block w-full" id="category" x-ref="selectcategory" data-placeholder="null"
                        data-minimum-results-for-search="2">
                        <x-slot name="options">
                            @foreach ($categorias as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
                <x-jet-input-error for="searchcategory" />
            </div>
        @endif

        @if (count($subcategories) > 1)
            <div class="w-full max-w-xs">
                <x-label value="Subcategoría :" />
                <div class="relative" id="parentsubcategory" x-init="selectSubcategory">
                    <x-select class="block w-full" id="subcategory" x-ref="selectsubcategory" data-placeholder="null">
                        <x-slot name="options">
                            @foreach ($subcategories as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
                <x-jet-input-error for="searchsubcategory" />
            </div>
        @endif

        @if (count($almacens) > 1)
            <div class="w-full max-w-xs">
                <x-label value="Almacén :" />
                <div class="relative" id="parentalmacen" x-init="selectAlmacen">
                    <x-select class="block w-full" id="almacen" x-ref="selectalmacen" data-placeholder="null">
                        <x-slot name="options">
                            @foreach ($almacens as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
                <x-jet-input-error for="searchalmacen" />
            </div>
        @endif

        <div class="w-full max-w-xs">
            <x-label value="Estado web :" />
            <div class="relative" id="parentweb" x-init="selectWeb">
                <x-select class="block w-full" id="web" x-ref="selectweb" data-placeholder="null">
                    <x-slot name="options">
                        <option value="0">NO DISPONIBLE TIENDA WEB</option>
                        <option value="1">DISPONIBLE TIENDA WEB</option>
                    </x-slot>
                </x-select>
                <x-icon-select />
            </div>
            <x-jet-input-error for="publicado" />
        </div>
    </div>

    {{-- @can('admin.ventas.deletes') --}}
    <div class="w-full mt-1">
        <x-label-check for="ocultos">
            <x-input wire:model.lazy="ocultos" name="ocultos" value="true" type="checkbox" id="ocultos" />
            MOSTRAR PRODUCTOS OCULTOS
        </x-label-check>
    </div>
    {{-- @endcan --}}

    <div class="w-full pt-2" x-cloak x-show="selectedproductos.length>0">
        <x-button-secondary @click="confirmDeleteAllProductos()" wire:loading.attr="disabled">
            {{ __('ELIMINAR SELECCIONADOS') }}
            <span x-text="selectedproductos.length" :class="selectedproductos.length < 10 ? 'px-1' : ''"
                class="bg-white p-0.5 lead text-[9px] rounded-full !tracking-normal font-semibold text-red-500"></span>
        </x-button-secondary>
    </div>

    <x-table class="mt-1">
        <x-slot name="header">
            <tr>
                <th scope="col" class="p-2 font-medium text-center">
                    <label for="checkall" class="text-xs flex flex-col justify-center items-center gap-1 leading-3">
                        TODO
                        <x-input wire:model.lazy="checkall" class="cursor-pointer p-2 !rounded-none" name="productos"
                            type="checkbox" id="checkall" wire:loading.attr="disabled" />
                    </label>
                </th>
                <th scope="col" class="p-2 font-medium">
                    <button class="flex items-center gap-x-3 focus:outline-none">
                        <span>PRODUCTO</span>
                        <svg class="h-3" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z"
                                fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                            <path
                                d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z"
                                fill="currentColor" stroke="currentColor" stroke-width="0.1" />
                            <path
                                d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z"
                                fill="currentColor" stroke="currentColor" stroke-width="0.3" />
                        </svg>
                    </button>
                </th>
                <th scope="col" class="p-2 font-medium">
                    SKU</th>
                <th scope="col" class="p-2 font-medium">
                    COD. FABRICANTE</th>
                <th scope="col" class="p-2 font-medium">
                    CATEGORÍA</th>
                <th scope="col" class="p-2 font-medium">
                    ALMACÉN</th>
                @if (Module::isEnabled('Almacen'))
                    <th scope="col" class="p-2 font-medium">
                        PUBLICADO</th>
                @endif
                <th scope="col" class="p-2 font-medium">
                    PRECIO COMPRA</th>
                @if (mi_empresa())
                    @if (!mi_empresa()->usarLista())
                        <th scope="col" class="p-2 font-medium">
                            PRECIO VENTA</th>
                    @endif
                @endif

                @if (Module::isEnabled('Almacen'))
                    <th scope="col" class="p-2 font-medium">
                        ÚLT. INGRESO</th>
                    <th scope="col" class="p-2 font-medium">
                        AREA</th>
                    <th scope="col"class="p-2 font-medium">
                        ESTANTE</th>
                @endif

                <th scope="col" class="p-2 font-medium">
                </th>
            </tr>
        </x-slot>

        @if (count($productos) > 0)
            <x-slot name="body">
                @foreach ($productos as $item)
                    <tr>
                        <td class="p-2 align-middle text-center">
                            <x-input type="checkbox" value="{{ $item->id }}" x-model="selectedproductos"
                                name="productos" class="p-2 !rounded-0 !rounded-none cursor-pointer"
                                id="{{ $item->id }}" />
                        </td>
                        <td class="p-2">
                            <div class="inline-flex gap-2 items-start justify-start">
                                @php
                                    $image = $item->getImageURL();
                                @endphp

                                <button
                                    class="block rounded overflow-hidden w-16 h-16 flex-shrink-0 shadow relative hover:shadow-lg cursor-pointer">
                                    @if ($image)
                                        <img src="{{ $image }}" alt=""
                                            class="w-full h-full object-cover">
                                    @else
                                        <x-icon-image-unknown class="w-full h-full" />
                                    @endif

                                    @if (count($item->images) > 1)
                                        <p
                                            class="absolute bottom-0 right-0 flex items-center justify-center w-6 h-6 text-textspantable bg-fondospantable rounded-full">
                                            +{{ count($item->images) - 1 }}</p>
                                    @endif
                                </button>

                                <div class="w-full flex-1">
                                    @can('admin.almacen.productos.edit')
                                        <a href="{{ route('admin.almacen.productos.edit', $item) }}"
                                            class="inline-block leading-3 text-[10px] font-medium break-words text-linktable cursor-pointer hover:text-hoverlinktable transition-all ease-in-out duration-150">
                                            {{ $item->name }}</a>
                                    @endcan

                                    @cannot('admin.almacen.productos.edit')
                                        <h1 class="inline-block font-medium break-words text-linktable">
                                            {{ $item->name }}</h1>
                                    @endcannot

                                    <p class="text-[10px] text-colorsubtitleform">
                                        <span class="font-semibold"> MARCA:</span> {{ $item->marca->name }} <span
                                            class="font-semibold">MODELO:</span> {{ $item->modelo }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="p-2 text-center">
                            {{ $item->sku }}
                        </td>
                        <td class="p-2 text-center">
                            {{ $item->codefabricante }}
                        </td>
                        <td class="p-2">
                            <div>
                                <h4>{{ $item->category->name }}</h4>
                                @if ($item->subcategory)
                                    <p class="text-colorsubtitleform text-[10px]">{{ $item->subcategory->name }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="p-2 align-middle">
                            @if (count($item->almacens))
                                <div class="flex flex-wrap items-center justify-center gap-1">
                                    @foreach ($item->almacens as $almacen)
                                        <x-span-text :text="$almacen->name .
                                            ' [' .
                                            formatDecimalOrInteger($almacen->pivot->cantidad) .
                                            ']'"
                                            class="whitespace-nowrap leading-3 !tracking-normal" />
                                    @endforeach
                                </div>
                            @endif
                        </td>

                        @if (Module::isEnabled('Almacen'))
                            <td class="p-2">
                                @if ($item->publicado)
                                    <x-span-text text="DISPONIBLE WEB"
                                        class="whitespace-nowrap leading-3 !tracking-normal" type="green" />
                                @endif
                            </td>
                        @endif

                        <td class="p-2 text-center">
                            {{ number_format($item->pricebuy, 3, '.', ', ') }}
                        </td>

                        @if (mi_empresa())
                            @if (!mi_empresa()->usarLista())
                                <td class="p-2 text-center">
                                    {{ number_format($item->pricesale, 3, '.', ', ') }}
                                </td>
                            @endif
                        @endif

                        @if (Module::isEnabled('Almacen'))
                            <td class="p-2">
                                @if (count($item->compraitems) > 0)
                                    <p>
                                        {{ formatDate($item->compraitems->first()->compra->date, 'DD MMMM Y') }}
                                    </p>
                                    <p>{{ $item->compraitems->first()->compra->proveedor->name }}</p>
                                @endif
                            </td>

                            <td class="p-2">
                                @if ($item->almacenarea)
                                    {{ $item->almacenarea->name }}
                                @endif
                            </td>
                            <td class="p-2">
                                @if ($item->estante)
                                    {{ $item->estante->name }}
                                @endif
                            </td>
                        @endif

                        <td class="p-2 text-center">
                            <x-button-toggle onclick="confirmHiddenProducto({{ $item }})" :checked="$item->isVisible() ? true : false" />
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        @endif

    </x-table>
    <script>
        function confirmHiddenProducto(producto) {
            swal.fire({
                title: 'EL PRODUCTO ' + producto.name + ' DEJARÁ DE ESTAR VISIBLE',
                text: null,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('hiddenproducto', producto.id).then(() => {
                        console.log('function ejecutado correctamente');
                    }).catch(error => {
                        console.error('Error al ejecutar la función:', error);
                    });
                }
            })
        }



        document.addEventListener('alpine:init', () => {
            Alpine.data('loadproductos', () => ({
                searchmarca: @entangle('searchmarca'),
                searchcategory: @entangle('searchcategory'),
                searchsubcategory: @entangle('searchsubcategory'),
                searchalmacen: @entangle('searchalmacen'),
                publicado: @entangle('publicado'),
                selectedproductos: @entangle('selectedproductos').defer,

                init() {
                    // selectMarca();

                },
                confirmDeleteAllProductos() {
                    swal.fire({
                        title: 'ELIMINAR PRODUCTOS SELECCIONADOS',
                        text: "Se eliminará todos los registros seleccionados de la base de datos.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#0FB9B9',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.$wire.call('deleteall').then(() => {
                                console.log('function ejecutado correctamente');
                            }).catch(error => {
                                console.error('Error al ejecutar la función:', error);
                            });
                        }
                    })
                }
            }));
        })

        function selectMarca() {
            this.selectM = $(this.$refs.selectmarca).select2();
            this.selectM.val(this.searchmarca).trigger("change");
            this.selectM.on("select2:select", (event) => {
                this.searchmarca = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchmarca", (value) => {
                this.selectM.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectM.select2().val(this.searchmarca).trigger('change');
            });
        }

        function selectCategory() {
            this.selectC = $(this.$refs.selectcategory).select2();
            this.selectC.val(this.searchcategory).trigger("change");
            this.selectC.on("select2:select", (event) => {
                this.searchcategory = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchcategory", (value) => {
                this.selectC.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectC.select2().val(this.searchcategory).trigger('change');
            });
        }

        function selectSubcategory() {
            this.selectSC = $(this.$refs.selectsubcategory).select2();
            this.selectSC.val(this.searchsubcategory).trigger("change");
            this.selectSC.on("select2:select", (event) => {
                this.searchsubcategory = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchsubcategory", (value) => {
                this.selectSC.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectSC.select2().val(this.searchsubcategory).trigger('change');
            });
        }

        function selectAlmacen() {
            this.selectA = $(this.$refs.selectalmacen).select2();
            this.selectA.val(this.searchalmacen).trigger("change");
            this.selectA.on("select2:select", (event) => {
                this.searchalmacen = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchalmacen", (value) => {
                this.selectA.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectA.select2().val(this.searchalmacen).trigger('change');
            });
        }

        function selectWeb() {
            this.selectW = $(this.$refs.selectweb).select2();
            this.selectW.val(this.publicado).trigger("change");
            this.selectW.on("select2:select", (event) => {
                this.publicado = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("publicado", (value) => {
                this.selectW.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectW.select2().val(this.publicado).trigger('change');
            });
        }
    </script>
</div>
