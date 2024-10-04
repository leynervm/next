<div x-cloak x-show="openSidebar" style="display: none"
    class="fixed left-0 bottom-0 w-full xs:max-w-[80%] sm:w-64 xl:w-72 sm:max-w-full flex z-[101] transition-transform ease-in-out duration-300"
    x-transition:enter="opacity-0 transition ease-in-out duration-300"
    x-transition:enter-start="opacity-0 -translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="opacity-100 transition ease-in-out duration-300"
    x-transition:leave-start="opacity-100 transition ease-in-out duration-300"
    x-transition:leave-end="opacity-100 -translate-x-full ease-in-out duration-300">
    <div class="w-full shadow-md bg-fondominicard z-[1] flex flex-col justify-between h-[calc(100vh-108px)] xl:h-[calc(100vh-80px)]">
        <div class="boxes-sidebar" :class="isSM ? 'overflow-y-auto' : ''">

            {{-- <div class="flex justify-end items-center p-2 box-border">
                <button @click="openSidebar= false, backdrop=false"
                    class="cursor-pointer text-next-500 p-0.5 rounded hover:text-colorsubtitleform transition-colors ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill-rule="evenodd" clip-rule="evenodd"
                        fill="none" fill="currentColor" stroke="currentColor" class="block w-4 h-4">
                        <path
                            d="M14.0128 1.33337L8.00025 7.34703L1.98544 1.33337L1.33301 1.98696L7.34667 7.99946L1.33301 14.0143L1.98544 14.6667L8.00025 8.65305L14.0128 14.6667L14.6663 14.0143L8.65268 7.99946L14.6663 1.98696L14.0128 1.33337Z" />
                    </svg>
                </button>
            </div> --}}

            <div class="item-sidebar group">
                <a class="itemlink-sidebar-principal" href="{{ route('ofertas') }}" @click="openSidebar=false">
                    <div
                        class="title-category-sidebar font-semibold group-hover:text-hovercolorlinknav">
                        OFERTAS
                        <span class="sale group-hover:text-textspancardproduct group-hover:bg-fondospancardproduct">
                            SALE</span>
                    </div>
                </a>
            </div>

            <div class="item-sidebar group">
                <a class="itemlink-sidebar-principal" href="{{ route('productos') }}" @click="openSidebar=false">
                    <div class="title-category-sidebar group-hover:text-hovercolorlinknav">
                        TODOS LOS PRODUCTOS</div>
                </a>
            </div>

            @auth
                <div class="item-sidebar group md:hidden">
                    <a @click="openSidebar=false" class="itemlink-sidebar-principal" href="{{ route('orders') }}">
                        <div class="title-category-sidebar group-hover:text-hovercolorlinknav">
                            MIS COMPRAS
                        </div>
                        <div class="link-icon-down">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="block w-full h-full">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        </div>
                    </a>
                </div>
            @endauth

            @if (count($categories))
                @foreach ($categories as $item)
                    <div class="item-sidebar" x-data="{ submenu: false }" @click.stop="isSM && (submenu=!submenu)">
                        <div class="itemlink-sidebar-principal">
                            <div class="title-category-sidebar">
                                @if ($item->icon)
                                    <div class="w-9 h-9 p-1 flex-shrink-0 overflow-hidden">
                                        {!! $item->icon !!}
                                    </div>
                                @endif
                                {{ $item->name }}
                            </div>
                            <div class="link-icon-down">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="block w-full h-full duration-300"
                                    :class="submenu ? 'rotate-90 sm:rotate-0' : ''">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </div>
                        </div>
                        @if (count($item->subcategories))
                            <div style="display: none" :style="isSM && (submenu ? 'display:flex' : 'display:none')"
                                class="submenu-categories">
                                <div
                                    class="w-full hidden sm:flex justify-start gap-3 items-center p-2.5  font-semibold text-sm mb-2">
                                    <a class="truncate text-next-500"
                                        href="{{ route('productos') . '?categorias=' . $item->slug }}">{{ $item->name }}</a>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="currentColor"
                                        stroke-width="2" stroke="currentColor"
                                        class="w-5 h-5 text-next-500 p-0.5 block flex-shrink-0">
                                        <polygon points="28.89 0 18.32 0 37.42 24 18.31 48 28.89 48 48 24 28.89 0" />
                                        <polygon points="10.58 0 0 0 19.11 24 0 48 10.58 48 29.69 24 10.58 0" />
                                    </svg>
                                </div>
                                <ul class="w-full flex flex-col">
                                    @foreach ($item->subcategories as $subcategory)
                                        <li class="w-full xl:w-auto block">
                                            <a class="text-colorsubtitleform whitespace-nowrap rounded-md p-2.5 w-full block hover:bg-fondolinknav hover:text-hoverlinknav text-xs transition ease-in-out duration-150"
                                                href="{{ route('productos') . '?subcategorias=' . $subcategory->slug }}">{{ $subcategory->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

        @if ($empresa->image)
            <a href="/" class="w-full p-1 h-16">
                <img class="mx-auto w-full max-w-full h-full object-scale-down"
                    src="{{ $empresa->image->getLogoEmpresa() }}" alt="">
                {{-- <x-isotipo-next class="max-w-[130px] h-full text-neutral-700" /> --}}
            </a>
        @endif
    </div>
</div>
