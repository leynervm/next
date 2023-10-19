<div>
    {{-- <div class="sm:flex sm:items-center gap-1">
        <h2 class="text-lg font-medium text-gray-800">Productos</h2>
        <span class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded-full">240
            vendors</span>
    </div> --}}

    @if ($ventas->hasPages())
        <div class="pb-2">
            {{ $ventas->links() }}
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
            <x-input placeholder="Buscar" class="block w-full md:w-80 pl-9" wire:model.lazy="search">
            </x-input>
        </div>
        <x-input type="date" name="date" wire:model.lazy="date" id="search" />
    </div>

    <div class="flex items-center gap-2  mt-4 ">
        {{-- <div class="relative flex items-center">
            <span class="absolute">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 h-4 mx-3 text-next-300">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </span>
            <x-input placeholder="Buscar" class="block w-full md:w-80 pl-9" wire:model="search">
            </x-input>
        </div> --}}
        {{-- @if (count($marcaGroup))
            <x-dropdown titulo="Marca">
                <x-slot name="items">
                    @foreach ($marcaGroup as $item)
                        <li>
                            <div class="flex flex-nowrap items-center hover:bg-next-50 rounded-lg p-1 break-keep">
                                <input id="searchmarca_{{ $item->marca_id }}" type="checkbox"
                                    value="{{ $item->marca_id }}" wire:loading.attr="disabled" wire:model="searchmarca"
                                    name="searchmarca[]"
                                    class="w-4 h-4 text-next-600 border-next-300 cursor-pointer rounded focus:ring-0 focus:ring-transparent disabled:opacity-25">
                                <label for="searchmarca_{{ $item->marca_id }}"
                                    class="pl-2 text-xs font-medium text-next-900 cursor-pointer break-keep">{{ $item->marca->name }}</label>
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
                                    class="w-4 h-4 text-next-600 border-next-300 cursor-pointer rounded focus:ring-0 focus:ring-transparent disabled:opacity-25">
                                <label for="searchcategory_{{ $item->category_id }}"
                                    class="pl-2 text-xs font-medium text-next-900 cursor-pointer break-keep">{{ $item->category->name }}</label>
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
                                    class="w-4 h-4 text-next-600 border-next-300 cursor-pointer rounded focus:ring-0 focus:ring-transparent disabled:opacity-25">
                                <label for="searchsubcategory_{{ $item->subcategory_id }}"
                                    class="pl-2 text-xs font-medium text-next-900 cursor-pointer break-keep">{{ $item->subcategory->name }}</label>
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
                                    class="w-4 h-4 text-next-600 border-next-300 cursor-pointer rounded focus:ring-0 focus:ring-transparent disabled:opacity-25">
                                <label for="searchalmacen_{{ $item->id }}"
                                    class="pl-2 text-xs font-medium text-next-900 cursor-pointer break-keep">{{ $item->name }}</label>
                            </div>
                        </li>
                    @endforeach
                </x-slot>
            </x-dropdown>
        @endif --}}
    </div>

    <div class="w-full relative" x-data="{ loading: false }">
        <x-table>
            <x-slot name="header">
                <tr>
                    <th scope="col" class="p-2 font-medium">
                        <button class="flex items-center gap-x-3 focus:outline-none">
                            <span>ID</span>

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
                        <button class="flex items-center gap-x-3 focus:outline-none">
                            <span>SERIE</span>

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
                        FECHA
                    </th>

                    <th scope="col" class="p-2 font-medium">
                        CLIENTE
                    </th>

                    <th scope="col" class="p-2 font-medium">
                        FORMA PAGO</th>

                    <th scope="col" class="p-2 font-medium">
                        EXONERADO</th>

                    <th scope="col" class="p-2 font-medium">
                        GRAVADO</th>

                    <th scope="col" class="p-2 font-medium">
                        IGV</th>

                    <th scope="col" class="p-2 font-medium">
                        TOTAL</th>

                    <th scope="col" class="p-2 font-medium">
                        PAGO</th>

                    <th scope="col"class="p-2 font-medium">
                        USUARIO</th>

                    <th scope="col" class="p-2 relative">
                        <span class="sr-only">OPCIONES</span>
                    </th>
                </tr>
            </x-slot>

            @if (count($ventas))
                <x-slot name="body">
                    @foreach ($ventas as $item)
                        <tr>
                            <td class="p-2 text-xs">
                                <div class="inline-flex gap-2 items-center justify-start">
                                    <div class="flex-shrink-1">
                                        <a href="{{ route('admin.ventas.show', $item) }}"
                                            class="font-medium break-words underline text-linktable cursor-pointer hover:text-hoverlinktable transition-all ease-in-out duration-150">
                                            VT-{{ $item->id }}</a>

                                    </div>
                                </div>
                            </td>
                            <td class="p-2 text-[10px]">
                                {{ $item->comprobante->seriecompleta }}
                            </td>
                            <td class="p-2 text-xs">
                                {{ Carbon\Carbon::parse($item->date)->format('d/m/Y h:i A') }}
                            </td>
                            <td class="p-2 text-xs">
                                <div>
                                    <h4 class="">{{ $item->client->document }}</h4>
                                    <p class=" text-[10px]">{{ $item->client->name }}</p>
                                </div>
                            </td>
                            <td class="p-2 text-[10px]">
                                {{ $item->typepayment->name }}
                            </td>
                            <td class="p-2 text-[10px]">
                                {{ $item->moneda->simbolo }}{{ $item->exonerado }}
                            </td>
                            <td class="p-2 text-[10px]">
                                {{ $item->moneda->simbolo }}{{ $item->gravado }}
                            </td>

                            <td class="p-2 text-[10px]">
                                {{ $item->moneda->simbolo }}{{ $item->igv }}
                            </td>
                            <td class="p-2 text-[10px]">
                                {{ $item->moneda->simbolo }} {{ number_format($item->total, 2, '.', ', ') }}
                            </td>
                            <td class="p-2 text-center text-[10px] align-middle">
                                @if ($item->cajamovimiento)
                                    <div
                                        class="inline-block text-[10px] text-center rounded-full p-1 bg-green-100 text-next-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path
                                                d="M20.9953 6.96425C21.387 6.57492 21.3889 5.94176 20.9996 5.55005C20.6102 5.15834 19.9771 5.15642 19.5854 5.54575L8.97661 16.0903L4.41377 11.5573C4.02196 11.1681 3.3888 11.1702 2.99956 11.562C2.61032 11.9538 2.6124 12.5869 3.0042 12.9762L8.27201 18.2095C8.66206 18.597 9.29179 18.5969 9.68175 18.2093L20.9953 6.96425Z"
                                                fill="black" />
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="p-2 text-[10px]">
                                {{ $item->user->name }}
                            </td>

                            <td class="p-2 whitespace-nowrap">
                                <div class="flex gap-1 items-center">

                                    <x-button-edit wire:click="edit({{ $item->id }})"
                                        wire:loading.attr="disabled"></x-button-edit>

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
                </x-slot>
            @endif
        </x-table>
        <div x-show="loading" wire:loading wire:loading.flex class="loading-overlay rounded">
            <x-loading-next />
        </div>
    </div>

    <script>
        document.addEventListener("livewire:load", () => {

            renderSelect2();

            window.addEventListener('render-editproducto-select2', () => {
                renderSelect2();
            });

            function renderSelect2() {
                // var formulario = document.getElementById("form_edit_producto");
                // var selects = formulario.getElementsByTagName("select");

                // for (var i = 0; i < selects.length; i++) {
                //     if (selects[i].id !== "") {
                //         $("#" + selects[i].id).select2({
                //             placeholder: "Seleccionar...",
                //         });
                //     }
                // }
            }

            function deshabilitarSelects() {
                // var formulario = document.getElementById("form_edit_producto");
                // var selects = formulario.getElementsByTagName("select");

                // for (var i = 0; i < selects.length; i++) {
                //     selects[i].disabled = true;
                // }
            }

        })
    </script>

</div>
