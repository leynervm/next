<div>
    @if ($clients->hasPages())
        <div class="pb-2">
            {{ $clients->links() }}
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
        @endif --}}

    </div>

    <x-table>
        <thead class="bg-gray-50 text-gray-400 text-xs">
            <tr>
                <th scope="col" class="p-2 font-medium">
                    <button class="flex items-center gap-x-3 focus:outline-none">
                        <span>CLIENTE</span>

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
                    DIRECCIÓN
                </th>

                <th scope="col" class="p-2 font-medium">
                    CORREO
                </th>

                <th scope="col" class="p-2 font-medium">
                    LISTA PRECIO</th>

                <th scope="col" class="p-2 font-medium">
                    TELEFONOS</th>

                <th scope="col" class="p-2 relative">
                    <span class="sr-only">OPCIONES</span>
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
            @if (count($clients))
                @foreach ($clients as $item)
                    <tr>
                        <td class="p-2 text-xs">
                            <a href="{{ route('admin.clientes.show', $item) }}"
                                class="font-medium break-words underline text-blue-500 cursor-pointer hover:text-indigo-800 transition-all ease-in-out duration-150">
                                {{ $item->name }}</a>
                            <p class="text-xs">{{ $item->document }}</p>
                        </td>
                        <td class="p-2 text-xs">
                            @if (count($item->direccions))
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($item->direccions as $dir)
                                        <p
                                            class="inline-block bg-fondospancardproduct text-textspancardproduct p-1 rounded text-[10px]">
                                            {{ $dir->name }} - {{ $dir->ubigeo_id }}</p>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->email }}
                            {{-- @if (count($item->correos))
                                @foreach ($item->correos as $corr)
                                    <p> {{ $corr }}</p>
                                @endforeach
                            @endif --}}

                            {{-- <div>
                                <h4 class="text-gray-700 ">{{ $item->category->name }}</h4>
                                @if ($item->email)
                                    <p class="text-gray-500 text-[10px]">{{ $item->subcategory->name }}</p>
                                @endif
                            </div> --}}
                        </td>
                        {{-- <td class="p-2 text-xs text-center">
                            {{ $item->channelsale->name }}
                        </td> --}}
                        <td class="p-2 text-xs text-center">
                            {{ $item->pricetype->name }}
                        </td>

                        <td class="p-2 text-xs">
                            @if (count($item->telephones))
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($item->telephones as $telef)
                                        <div
                                            class="inline-flex items-center justify-center gap-1 bg-green-100 text-green-500 p-1 rounded">
                                            <span class="w-3 h-3 block">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path
                                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                                </svg>
                                            </span>
                                            <span class="text-[10px]">{{ $telef->phone }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </td>

                        <td class="p-2 whitespace-nowrap">
                            <div class="flex gap-1 items-center">

                                {{-- <x-button-edit wire:click="edit({{ $item->id }})"
                                    wire:loading.attr="disabled"></x-button-edit> --}}

                                <x-button-delete></x-button-delete>

                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
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
        </tbody>
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

        </x-slot>
    </x-jet-dialog-modal>

</div>
