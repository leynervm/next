<div>
    {{-- <div class="sm:flex sm:items-center gap-1">
        <h2 class="text-lg font-medium text-gray-800">Productos</h2>
        <span class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded-full">240
            vendors</span>
    </div> --}}

    @if ($productos->hasPages())
        {{-- <div class="w-full py-2">
            {{ $productos->onEachSide(0)->links('livewire::pagination-default') }}
        </div> --}}
        <div class="pb-2">
            {{ $productos->links() }}
        </div>
    @endif

    <div class="flex items-center gap-2  mt-4 ">
        <div class="relative flex items-center">
            <span class="absolute">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 h-4 mx-3 text-next-300">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </span>
            <x-input placeholder="Buscar" class="block w-full md:w-80 pl-9" wire:model="search">
            </x-input>
        </div>
        @if (count($marcaGroup))
            <x-dropdown titulo="Marca">
                <x-slot name="items">
                    @foreach ($marcaGroup as $item)
                        <li>
                            <div class="flex flex-nowrap items-center hover:bg-next-50 rounded-lg p-1 break-keep">
                                <input id="searchmarca_{{ $item->marca_id }}" type="checkbox"
                                    value="{{ $item->marca_id }}" wire:loading.attr="disabled" wire:model="searchmarca"
                                    name="searchmarca[]"
                                    class="w-4 h-4 text-primary border-next-300 cursor-pointer rounded focus:ring-0 focus:ring-transparent disabled:opacity-25">
                                <label for="searchmarca_{{ $item->marca_id }}"
                                    class="pl-2 text-xs font-medium  cursor-pointer break-keep">{{ $item->marca->name }}</label>
                            </div>
                        </li>
                    @endforeach
                </x-slot>
            </x-dropdown>
        @endif

        @if (count($categoriaGroup))
            <x-dropdown titulo="Categoría">
                <x-slot name="items">
                    @foreach ($categoriaGroup as $item)
                        <li>
                            <div class="flex flex-nowrap items-center hover:bg-next-50 rounded-lg p-1 break-keep">
                                <input id="searchcategory_{{ $item->category_id }}" type="checkbox"
                                    value="{{ $item->category_id }}" wire:loading.attr="disabled"
                                    wire:model="searchcategory" name="searchcategory[]"
                                    class="w-4 h-4 text-primary border-next-300 cursor-pointer rounded focus:ring-0 focus:ring-transparent disabled:opacity-25">
                                <label for="searchcategory_{{ $item->category_id }}"
                                    class="pl-2 text-xs font-medium cursor-pointer break-keep">{{ $item->category->name }}</label>
                            </div>
                        </li>
                    @endforeach
                </x-slot>
            </x-dropdown>
        @endif

        @if (count($subcategoriaGroup))
            <x-dropdown titulo="Subcategoría">
                <x-slot name="items">
                    @foreach ($subcategoriaGroup as $item)
                        <li>
                            <div class="flex flex-nowrap items-center hover:bg-next-50 rounded-lg p-1 break-keep">
                                <input id="searchsubcategory_{{ $item->subcategory_id }}" type="checkbox"
                                    value="{{ $item->subcategory_id }}" wire:loading.attr="disabled"
                                    wire:model="searchsubcategory" name="searchsubcategory[]"
                                    class="w-4 h-4 text-primary border-next-300 cursor-pointer rounded focus:ring-0 focus:ring-transparent disabled:opacity-25">
                                <label for="searchsubcategory_{{ $item->subcategory_id }}"
                                    class="pl-2 text-xs font-medium cursor-pointer break-keep">{{ $item->subcategory->name }}</label>
                            </div>
                        </li>
                    @endforeach
                </x-slot>
            </x-dropdown>
        @endif

        @if (count($almacenGroup))
            <x-dropdown titulo="Almacén">
                <x-slot name="items">
                    @foreach ($almacenGroup as $item)
                        <li>
                            <div class="flex flex-nowrap items-center hover:bg-next-50 rounded-lg p-1 break-keep">
                                <input id="searchalmacen_{{ $item->id }}" type="checkbox"
                                    value="{{ $item->id }}" wire:loading.attr="disabled" wire:model="searchalmacen"
                                    name="almacenSearch[]"
                                    class="w-4 h-4 text-primary border-next-300 cursor-pointer rounded focus:ring-0 focus:ring-transparent disabled:opacity-25">
                                <label for="searchalmacen_{{ $item->id }}"
                                    class="pl-2 text-xs font-medium cursor-pointer break-keep">{{ $item->name }}</label>
                            </div>
                        </li>
                    @endforeach
                </x-slot>
            </x-dropdown>
        @endif
    </div>

    <x-table>
        <x-slot name="header">
            <tr>
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
                    MINICÓDIGO
                </th>

                <th scope="col" class="p-2 font-medium">
                    COD. FABRIC
                </th>

                <th scope="col" class="p-2 font-medium">
                    SKU
                </th>

                <th scope="col" class="p-2 font-medium">
                    PRECIO COMPRA
                </th>

                <th scope="col" class="p-2 font-medium">
                    PRECIO VENTA
                </th>

                <th scope="col" class="p-2 font-medium">
                    CATEGORÍA
                </th>

                <th scope="col" class="p-2 font-medium">
                    ALMACÉN</th>

                <th scope="col" class="p-2 font-medium">
                    PUBLICADO</th>

                <th scope="col" class="p-2 font-medium">
                    ÚLT. INGRESO</th>

                <th scope="col" class="p-2 font-medium">
                    PROVEEDOR</th>

                <th scope="col" class="p-2 font-medium">
                    AREA</th>

                <th scope="col"class="p-2 font-medium">
                    ESTANTE</th>

                <th scope="col" class="p-2 relative">
                    <span class="sr-only">OPCIONES</span>
                </th>
            </tr>
        </x-slot>

        @if (count($productos))
            <x-slot name="body">
                @foreach ($productos as $item)
                    <tr>
                        <td class="p-2 text-xs">
                            <div class="inline-flex gap-2 items-center justify-start">
                                @if (count($item->images))
                                    <button
                                        class="block rounded overflow-hidden w-16 h-16 flex-shrink-0 shadow relative hover:shadow-lg cursor-pointer">

                                        @if (count($item->defaultImage))
                                            <img src="{{ asset('storage/productos/' . $item->defaultImage->first()->url) }}"
                                                alt="" class="w-full h-full object-scale-down">
                                        @else
                                            <img src="{{ asset('storage/productos/' . $item->images->first()->url) }}"
                                                alt="" class="w-full h-full object-scale-down">
                                        @endif

                                        @if (count($item->images) > 1)
                                            <p
                                                class="absolute bottom-0 right-0 flex items-center justify-center w-6 h-6 text-xs text-textspantable bg-fondospantable rounded-full">
                                                +{{ count($item->images) - 1 }}</p>
                                        @endif
                                    </button>
                                @endif
                                <div class="flex-shrink-1">
                                    <a href="{{ route('admin.almacen.productos.show', $item) }}"
                                        class="font-medium break-words underline text-linktable cursor-pointer hover:text-hoverlinktable transition-all ease-in-out duration-150">
                                        {{ $item->name }}</a>

                                    {{-- <a href="{{ route('admin.almacen.productos.show', $item) }}"
                                        class="font-medium break-words underline text-blue-500 cursor-pointer hover:text-indigo-800 transition-all ease-in-out duration-150">
                                        {{ $item->name }}</a> --}}
                                    <p class="text-xs">
                                        {{ $item->modelo }} / {{ $item->marca->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->id }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->codefabricante }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->sku }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->pricebuy }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->pricesale }}
                        </td>
                        <td class="p-2 text-xs">
                            <div>
                                <h4>{{ $item->category->name }}</h4>
                                @if ($item->subcategory)
                                    <p class="text-linktable text-[10px]">{{ $item->subcategory->name }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="p-2">
                            @if (count($item->almacens))
                                @foreach ($item->almacens as $almacen)
                                    <span
                                        class="whitespace-nowrap p-1 pr-0 text-[10px] font-medium text-textspancardproduct bg-fondospancardproduct rounded-lg">
                                        {{ $almacen->name }}
                                        <span
                                            class="bg-fondospantable p-1 text-textspantable rounded-full ml-1">{{ floatval($almacen->pivot->cantidad) }}</span>
                                    </span>
                                    {{-- <p
                                            class="flex items-center justify-center w-6 h-6 -mx-1 text-xs text-blue-600 bg-blue-100 border-2 border-white rounded-full">
                                            +4</p> --}}
                                @endforeach
                            @endif
                        </td>
                        <td class="p-2">
                            @if ($item->publicado)
                                <span
                                    class="inline-block whitespace-nowrap p-1 text-[10px] font-medium text-textspancardproduct bg-fondospancardproduct rounded-full">
                                    DISPONIBLE WEB
                                </span>
                            @endif
                        </td>
                        <td class="p-2 text-xs">
                            {{ $item->lastingreso }}
                        </td>

                        <td class="p-2 text-xs">
                            @if ($item->lastingreso)
                                {{ $item->lastingreso }}
                            @endif
                        </td>
                        <td class="p-2 text-xs">
                            {{ $item->almacenarea->name }}
                        </td>
                        <td class="p-2 text-xs">
                            {{ $item->estante->name }}
                        </td>

                        <td class="p-2 whitespace-nowrap">
                            <div class="flex gap-1 items-center">

                                <x-button-edit wire:click="edit({{ $item->id }})"
                                    wire:loading.attr="disabled"></x-button-edit>

                                {{-- <a href="{{ route('admin.almacen.productos.show', $item) }}"
                                    class="inline-block group relative font-semibold text-sm bg-transparent text-blue-500 p-1 rounded-md hover:bg-blue-500 focus:bg-blue-500 hover:ring-2 hover:ring-blue-300 focus:ring-2 focus:ring-blue-300 hover:text-white focus:text-white disabled:opacity-25 transition ease-in duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg"class="w-4 h-4 mx-auto"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m3 17 2 2 4-4" />
                                        <path d="m3 7 2 2 4-4" />
                                        <path d="M13 6h8" />
                                        <path d="M13 12h8" />
                                        <path d="M13 18h8" />
                                    </svg>
                                </a> --}}

                                <button
                                    class="p-1 text-gray-500 hover:bg-gray-100 transition-colors duration-150 rounded-lg ">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                {{-- <tr>
                <td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="flex items-center">
                        <p
                            class="flex items-center justify-center w-6 h-6 -mx-1 text-xs text-blue-600 bg-blue-100 border-2 border-white rounded-full">
                            +4</p>
                    </div>
                </td>
                <td class="px-4 py-4 text-sm whitespace-nowrap">
                    <div class="w-48 h-1.5 bg-blue-200 overflow-hidden rounded-full">
                        <div class="bg-blue-500 w-2/3 h-1.5"></div>
                    </div>
                </td>
            </tr> --}}
            </x-slot>
        @endif
    </x-table>

    <x-jet-dialog-modal wire:model="open" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar producto') }}
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" id="form_edit_producto">
                <div class="w-full">
                    <x-label value="NOMBRE:" textSize="[10px]" class="font-semibold" />
                    <x-input class="block w-full" wire:model.defer="producto.name" />
                    <x-jet-input-error for="producto.name" />
                </div>

                <div class="flex flex-wrap sm:flex-nowrap gap-2 mt-2">
                    <div class="w-full">
                        <x-label value="PRECIO COMPRA:" textSize="[10px]" class="font-semibold" />
                        <x-input class="block w-full" wire:model.defer="producto.pricebuy" type="number"
                            min="0" step="0.0001" />
                        <x-jet-input-error for="producto.pricebuy" />
                    </div>

                    <div class="w-full">
                        <x-label value="PRECIO VENTA:" textSize="[10px]" class="font-semibold" />
                        <x-input class="block w-full" wire:model.defer="producto.pricesale" type="number"
                            min="0" step="0.0001" />
                        <x-jet-input-error for="producto.pricesale" />
                    </div>
                </div>

                <div class="flex flex-wrap sm:flex-nowrap gap-2 mt-2">
                    <div class="w-full">
                        <x-label value="IGV:" textSize="[10px]" class="font-semibold" />
                        <x-input class="block w-full" wire:model.defer="producto.igv" type="number" min="0"
                            step="0.0001" />
                        <x-jet-input-error for="producto.igv" />
                    </div>
                    <div class="w-full">
                        <x-label value="MARCA:" textSize="[10px]" class="font-semibold" />
                        <x-select class="block w-full" id="editcategoryproducto" wire:model.defer="producto.marca_id"
                            id="marcaproducto_id" data-dropdown-parent="">
                            <x-slot name="options">
                                @if (count($marcas))
                                    @foreach ($marcas as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="producto.marca_id" />
                    </div>
                </div>

                <div class="flex flex-wrap sm:flex-nowrap gap-2 mt-2">
                    <div class="w-full">
                        <x-label value="UNIDAD MEDIDA:" textSize="[10px]" class="font-semibold" />
                        <x-select class="block w-full" id="editunitproducto" wire:model.defer="producto.unit_id"
                            id="unitproducto_id">
                            <x-slot name="options">
                                @if (count($units))
                                    @foreach ($units as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="producto.unit_id" />
                    </div>
                    <div class="w-full">
                        <x-label value="AREA:" textSize="[10px]" class="font-semibold"
                            id="almacenareaproducto_id" />
                        <x-select class="block w-full" id="editalmacenareaproducto"
                            wire:model.defer="producto.almacenarea_id">
                            <x-slot name="options">
                                @if (count($almacenareas))
                                    @foreach ($almacenareas as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="producto.almacenarea_id" />
                    </div>
                </div>

                <div class="flex flex-wrap sm:flex-nowrap gap-2 mt-2">
                    <div class="w-full">
                        <x-label value="ESTANTE:" textSize="[10px]" class="font-semibold" />
                        <x-select class="block w-full" id="editestanteproducto"
                            wire:model.defer="producto.estante_id" id="estanteproducto_id">
                            <x-slot name="options">
                                @if (count($estantes))
                                    @foreach ($estantes as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="producto.estante_id" />
                    </div>
                    <div class="w-full">
                        <x-label value="CATEGORÍA:" textSize="[10px]" class="font-semibold" />
                        <x-select class="block w-full" id="editcategoryprocto"
                            wire:model.defer="producto.category_id" id="categoryproducto_id">
                            <x-slot name="options">
                                @if (count($categories))
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="producto.category_id" />
                    </div>
                </div>

                <div class="flex flex-wrap sm:flex-nowrap gap-2 mt-2">
                    <div class="w-1/2">
                        <x-label value="SUBCATEGORÍA:" textSize="[10px]" class="font-semibold" />
                        <x-select class="block w-full" id="editsubcategoryproducto"
                            wire:model.defer="producto.subcategory_id" id="subcategoryproducto_id">
                            <x-slot name="options">
                                @if (count($subcategories))
                                    @foreach ($subcategories as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="producto.subcategory_id" />
                    </div>
                </div>

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" wire:loading.attr="disabled" wire:target="add_almacen">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener("livewire:load", () => {

            renderSelect2();

            $("#marcaproducto_id").on("change", (e) => {
                deshabilitarSelects();
                @this.set('producto.marca_id', e.target.value);
            });

            $("#unitproducto_id").on("change", (e) => {
                deshabilitarSelects();
                @this.set('producto.unit_id', e.target.value);
            });

            $("#editalmacenareaproducto").on("change", (e) => {
                deshabilitarSelects();
                @this.set('producto.almacenarea_id', e.target.value);
            });

            $("#estanteproducto_id").on("change", (e) => {
                deshabilitarSelects();
                @this.set('producto.estante_id', e.target.value);
            });

            $("#categoryproducto_id").on("change", (e) => {
                deshabilitarSelects();
                @this.set('producto.category_id', e.target.value);
                @this.set('producto.subcategory_id', null);
            });

            $("#subcategoryproducto_id").on("change", (e) => {
                deshabilitarSelects();
                @this.set('producto.subcategory_id', e.target.value);
            });


            window.addEventListener('render-editproducto-select2', () => {
                renderSelect2();
            });

            function renderSelect2() {
                var formulario = document.getElementById("form_edit_producto");
                var selects = formulario.getElementsByTagName("select");

                for (var i = 0; i < selects.length; i++) {
                    if (selects[i].id !== "") {
                        // console.log(selects[i].id);
                        $("#" + selects[i].id).select2({
                            placeholder: "Seleccionar...",
                        });
                    }
                }
            }

            function deshabilitarSelects() {
                var formulario = document.getElementById("form_edit_producto");
                var selects = formulario.getElementsByTagName("select");

                for (var i = 0; i < selects.length; i++) {
                    selects[i].disabled = true;
                }
            }

        })
    </script>

</div>
