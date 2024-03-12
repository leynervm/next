<div>
    @if ($productos->hasPages())
        <div class="w-full pb-2">
            {{ $productos->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex flex-wrap items-center gap-2 mt-4 ">
        <div class="relative flex items-center">
            <span class="absolute">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 h-4 mx-3 text-next-300">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </span>
            <x-input placeholder="Buscar" class="block w-full md:w-80 pl-9" wire:model.lazy="search">
            </x-input>
        </div>
        @if (count($marcaGroup) > 0)
            <x-dropdown titulo="Marca">
                <x-slot name="items">
                    @foreach ($marcaGroup as $item)
                        <li>
                            <x-link-dropdown :for="'searchmarca_' . $item->marca_id">
                                <input id="searchmarca_{{ $item->marca_id }}" type="checkbox"
                                    value="{{ $item->marca->name }}" name="searchmarca[]" wire:loading.attr="disabled"
                                    wire:model.lazy="searchmarca"
                                    class="w-4 h-4 mr-1 text-primary border-next-300 cursor-pointer rounded focus:ring-0 focus:ring-transparent disabled:opacity-25">
                                {{ $item->marca->name }}
                            </x-link-dropdown>
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
                            <x-link-dropdown :for="'searchcategory_' . $item->category_id">
                                <input id="searchcategory_{{ $item->category_id }}" type="checkbox"
                                    value="{{ $item->category->name }}" name="searchcategory[]"
                                    wire:loading.attr="disabled" wire:model.lazy="searchcategory"
                                    class="w-4 h-4 mr-1 text-primary border-next-300 cursor-pointer rounded focus:ring-0 focus:ring-transparent disabled:opacity-25">
                                {{ $item->category->name }}
                            </x-link-dropdown>
                        </li>
                    @endforeach
                </x-slot>
            </x-dropdown>
        @endif

        @if (count($subcategoriaGroup))
            <x-dropdown titulo="Subcategoría">
                <x-slot name="pages">
                    <div class="w-full pb-2">
                        {{ $subcategoriaGroup->onEachSide(0)->links('livewire::pagination-default') }}
                    </div>
                </x-slot>
                <x-slot name="items">
                    @foreach ($subcategoriaGroup as $item)
                        <li>
                            <x-link-dropdown :for="'searchsubcategory_' . $item->subcategory_id">
                                <input id="searchsubcategory_{{ $item->subcategory_id }}" type="checkbox"
                                    value="{{ $item->subcategory->name }}" name="searchsubcategory[]"
                                    wire:loading.attr="disabled" wire:model.lazy="searchsubcategory"
                                    class="w-4 h-4 mr-1 text-primary border-next-300 cursor-pointer rounded focus:ring-0 focus:ring-transparent disabled:opacity-25">
                                {{ $item->subcategory->name }}
                            </x-link-dropdown>
                        </li>
                    @endforeach
                </x-slot>
                <x-slot name="loading">
                    <div wire:loading.flex wire:target="searchsubcategory" class="loading-overlay rounded hidden">
                        <x-loading-next />
                    </div>
                </x-slot>
            </x-dropdown>
        @endif

        @if (count($almacenGroup) > 1)
            <x-dropdown titulo="Almacén">
                <x-slot name="items">
                    @foreach ($almacenGroup as $item)
                        <li>
                            <x-link-dropdown :for="'searchalmacen_' . $item->id">
                                <input id="searchalmacen_{{ $item->id }}" type="checkbox"
                                    value="{{ $item->name }}" wire:loading.attr="disabled"
                                    wire:model.lazy="searchalmacen" name="almacenSearch[]"
                                    class="w-4 h-4 mr-1 text-primary border-next-300 cursor-pointer rounded focus:ring-0 focus:ring-transparent disabled:opacity-25">
                                {{ $item->name }}
                            </x-link-dropdown>
                        </li>
                    @endforeach
                </x-slot>
            </x-dropdown>
        @endif

        <x-select class="" wire:model.lazy="publicado" id="searchpublicado">
            <x-slot name="options">
                <option value="0">NO DISPONIBLE TIENDA WEB</option>
                <option value="1">DISPONIBLE TIENDA WEB</option>
            </x-slot>
        </x-select>
    </div>

    <x-table class="mt-1">
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
                    COD. PRODUCTO
                </th>

                <th scope="col" class="p-2 font-medium">
                    COD. FABRICANTE
                </th>

                <th scope="col" class="p-2 font-medium">
                    CATEGORÍA
                </th>

                <th scope="col" class="p-2 font-medium">
                    ALMACÉN</th>

                @if (Module::isEnabled('Almacen'))
                    <th scope="col" class="p-2 font-medium">
                        PUBLICADO</th>
                @endif

                <th scope="col" class="p-2 font-medium">
                    PRECIO COMPRA
                </th>

                @if (mi_empresa()->uselistprice == '0')
                    <th scope="col" class="p-2 font-medium">
                        PRECIO VENTA
                    </th>
                @endif

                @if (Module::isEnabled('Almacen'))
                    <th scope="col" class="p-2 font-medium">
                        ÚLT. INGRESO</th>

                    <th scope="col" class="p-2 font-medium">
                        PROVEEDOR</th>

                    <th scope="col" class="p-2 font-medium">
                        AREA</th>

                    <th scope="col"class="p-2 font-medium">
                        ESTANTE</th>
                @endif
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
                                                alt="" class="w-full h-full object-cover">
                                        @else
                                            <img src="{{ asset('storage/productos/' . $item->images->first()->url) }}"
                                                alt="" class="w-full h-full object-cover">
                                        @endif

                                        @if (count($item->images) > 1)
                                            <p
                                                class="absolute bottom-0 right-0 flex items-center justify-center w-6 h-6 text-xs text-textspantable bg-fondospantable rounded-full">
                                                +{{ count($item->images) - 1 }}</p>
                                        @endif
                                    </button>
                                @endif
                                <div class="flex-shrink-1">
                                    @can('admin.almacen.productos.edit')
                                        <a href="{{ route('admin.almacen.productos.edit', $item) }}"
                                            class="inline-block font-medium break-words text-linktable cursor-pointer hover:text-hoverlinktable transition-all ease-in-out duration-150">
                                            {{ $item->name }}</a>
                                    @endcan

                                    @cannot('admin.almacen.productos.edit')
                                        <h1 class="inline-block font-medium break-words text-linktable">
                                            {{ $item->name }}</h1>
                                    @endcannot

                                    <p class="text-xs">
                                        {{ $item->marca->name }} / MODELO : {{ $item->modelo }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->code }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->codefabricante }}
                        </td>
                        <td class="p-2 text-xs">
                            <div>
                                <h4>{{ $item->category->name }}</h4>
                                @if ($item->subcategory)
                                    <p class="text-linktable text-[10px]">{{ $item->subcategory->name }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="p-2 align-middle">
                            @if (count($item->almacens))
                                <div class="flex flex-wrap items-center justify-center gap-1">
                                    @foreach ($item->almacens as $almacen)
                                        <x-span-text :text="$almacen->name"
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

                        <td class="p-2 text-xs text-center">
                            {{ number_format($item->pricebuy, 3, '.', ', ') }}
                        </td>

                        @if (mi_empresa()->uselistprice == '0')
                            <td class="p-2 text-xs text-center">
                                {{ number_format($item->pricesale, 3, '.', ', ') }}
                            </td>
                        @endif

                        @if (Module::isEnabled('Almacen'))
                            <td class="p-2 text-xs">
                                @if (count($item->compraitems) > 0)
                                    {{ \Carbon\Carbon::parse($item->compraitems->first()->compra->date)->format('d/m/Y') }}
                                @endif
                            </td>

                            <td class="p-2 text-xs">
                                @if (count($item->compraitems) > 0)
                                    {{ $item->compraitems->first()->compra->proveedor->name }}
                                @endif
                            </td>

                            <td class="p-2 text-xs">
                                @if ($item->almacenarea)
                                    {{ $item->almacenarea->name }}
                                @endif
                            </td>
                            <td class="p-2 text-xs">
                                @if ($item->estante)
                                    {{ $item->estante->name }}
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            </x-slot>
        @endif

        <x-slot name="loading">
            <div wire:loading.flex
                wire:target="search, searchalmacen, searchmarca, searchcategory, gotoPage, nextPage, previousPage, publicado"
                class="loading-overlay rounded hidden">
                <x-loading-next />
            </div>
        </x-slot>
    </x-table>
</div>
