<div x-cloak x-show="openSidebar" style="display: none"
    class="fixed top-0 left-0 bottom-0 w-full max-w-[80%] sm:w-64 sm:max-w-full flex z-[299] transition-transform ease-in-out duration-300"
    x-transition:enter="opacity-0 transition ease-in-out duration-300"
    x-transition:enter-start="opacity-0 -translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="opacity-100 transition ease-in-out duration-300"
    x-transition:leave-start="opacity-100 transition ease-in-out duration-300"
    x-transition:leave-end="opacity-100 -translate-x-full ease-in-out duration-300">
    <div class="w-full shadow-md bg-fondominicard z-[1]">
        <div
            class="flex justify-between items-center py-4 pr-4 pl-6 box-border h-14 bg-fondominicard border-t-4 border-colorlinknav shadow">
            <h1
                class="block w-full flex-1 text-xs text-colorsubtitleform leading-5 font-semibold truncate overflow-hidden">
                {{-- Somos --}}
                {{ $empresa->name }}!</h1>
            <button @click="openSidebar= false, backdrop=false"
                class="cursor-pointer text-colorsubtitleform p-0.5 rounded hover:text-neutral-700 transition-colors ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill-rule="evenodd" clip-rule="evenodd"
                    fill="none" fill="currentColor" stroke="currentColor" class="block w-4 h-4">
                    <path
                        d="M14.0128 1.33337L8.00025 7.34703L1.98544 1.33337L1.33301 1.98696L7.34667 7.99946L1.33301 14.0143L1.98544 14.6667L8.00025 8.65305L14.0128 14.6667L14.6663 14.0143L8.65268 7.99946L14.6663 1.98696L14.0128 1.33337Z" />
                </svg>
            </button>
        </div>
        <div
            class="w-full flex flex-col justify-between mb-[60px] h-[calc(100vh-56px)] overflow-y-auto xl:overflow-visible">
            <div class="w-full boxes-sidebar flex-1">
                <div class="item-sidebar group">
                    <a class="itemlink-sidebar-principal" href="{{ route('ofertas') }}" @click="openSidebar=false">
                        <div
                            class="title-category-sidebar font-semibold text-colorlinknav group-hover:text-hovercolorlinknav">
                            OFERTAS
                            <span
                                class="sale group-hover:text-textspancardproduct group-hover:bg-fondospancardproduct">SALE</span>
                        </div>
                    </a>
                </div>
                <div class="item-sidebar group">
                    <a class="itemlink-sidebar-principal" href="{{ route('productos') }}" @click="openSidebar=false">
                        <div
                            class="title-category-sidebar font-semibold text-colorlinknav group-hover:text-hovercolorlinknav">
                            TODOS LOS PRODUCTOS
                        </div>
                    </a>
                </div>

                @auth
                    <div class="item-sidebar group xl:hidden">
                        <a class="itemlink-sidebar-principal" href="{{ route('orders') }}" @click="openSidebar=false">
                            <div
                                class="title-category-sidebar font-medium text-colorlinknav group-hover:text-hovercolorlinknav">
                                MIS COMPRAS
                            </div>
                            <div class="link-icon-down">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="block w-full h-full">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </div>
                        </a>
                    </div>
                @endauth

                @if (count($categories))
                    @foreach ($categories as $item)
                        <div class="item-sidebar relative" x-data="{ submenu: false }"
                            @click.stopPropagation()="!isSM && (submenu=!submenu)">
                            <div class="flex itemlink-sidebar-principal">
                                <div class="title-category-sidebar">
                                    @if ($item->image)
                                        <div class="w-9 h-9 flex-shrink-0 rounded-full overflow-hidden">
                                            <img src="{{ $item->image->getCategoryURL() }}" alt=""
                                                class="w-full h-full object-cover">
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
                                <div style="display: none" :style="!isSM && (submenu ? 'display:flex' : 'display:none')"
                                    class="w-full submenu-categories sm:absolute top-0 left-full sm:rounded-r-xl z-[999] flex-col xl:min-w-[320px] xl:max-w-[460px] xl:p-3 bg-fondominicard box-border">
                                    <div class="w-full">
                                        <div class="w-full hidden xl:flex justify-between items-center max-w-full">
                                            <div
                                                class="w-auto max-w-xs bg-next-500 relative flex items-center p-3 font-semibold text-sm leading-normal rounded">
                                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 512.004 512.004"
                                                    class="absolute text-next-500 block -right-6 flex-shrink-0 h-full w-auto">
                                                    <path
                                                        d="M509.501,249.969L262.035,2.502c-1.596-1.604-3.763-2.5-6.033-2.5H8.535c-3.447,0-6.571,2.082-7.885,5.265 c-1.323,3.191-0.589,6.861,1.852,9.301l241.434,241.434L2.502,497.435c-2.44,2.441-3.174,6.11-1.852,9.301 c1.314,3.183,4.437,5.265,7.885,5.265h247.467c2.27,0,4.437-0.896,6.033-2.5l247.467-247.467 C512.838,258.698,512.838,253.305,509.501,249.969z">
                                                    </path>
                                                </svg>

                                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 512.004 512.004"
                                                    class="absolute text-next-500 block -right-14 flex-shrink-0 h-full w-auto">
                                                    <path
                                                        d="M509.501,249.969L262.035,2.502c-1.596-1.604-3.763-2.5-6.033-2.5H8.535c-3.447,0-6.571,2.082-7.885,5.265 c-1.323,3.191-0.589,6.861,1.852,9.301l241.434,241.434L2.502,497.435c-2.44,2.441-3.174,6.11-1.852,9.301 c1.314,3.183,4.437,5.265,7.885,5.265h247.467c2.27,0,4.437-0.896,6.033-2.5l247.467-247.467 C512.838,258.698,512.838,253.305,509.501,249.969z">
                                                    </path>
                                                </svg>

                                                <a class="text-white min-w-[100px] max-w-[240px] truncate"
                                                    href="{{ route('productos') . '?categorias=' . $item->slug }}">{{ $item->name }}</a>
                                            </div>

                                            {{-- <a href="{{ route('productos') }}" class="custom-btn">
                                                VER TODO<span></span></a> --}}
                                        </div>
                                        <ul class="w-full flex flex-col mt-2">
                                            {{-- <li class="w-full xl:w-auto block">
                                                <a class="text-colorsubtitleform rounded-md p-3 w-full block hover:text-hoverlinknav text-xs transition ease-in-out duration-150"
                                                    href="{{ route('productos') }}">VER TODO</a>
                                            </li> --}}
                                            @foreach ($item->subcategories as $subcategory)
                                                <li class="w-full xl:w-auto block">
                                                    <a class="text-colorsubtitleform rounded-md p-3 w-full block hover:text-hoverlinknav text-xs transition ease-in-out duration-150"
                                                        href="{{ route('productos') . '?subcategorias=' . $subcategory->slug }}">{{ $subcategory->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            {{-- <div class="xl:hidden itemlink-sidebar-principal" @click="submenu= !submenu">
                                <div class="title-category-sidebar flex-1 w-full truncate">{{ $item->name }}
                                </div>
                                <div class="link-icon-down">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="block w-full h-full">
                                        <path d="m9 18 6-6-6-6" />
                                    </svg>
                                </div>
                            </div> --}}

                            {{-- @if (count($item->subcategories))
                                <div x-show="submenu" x-cloak
                                    class="w-full xl:absolute xl:left-64 xl:top-0 xl:z-[9999] xl:mt-[56px] xl:rounded-r-3xl flex flex-col xl:max-w-[460px] overflow-hidden xl:max-h-screen xl:px-3 xl:pt-3 xl:pb-16 bg-fondominicard box-border">
                                    <div class="overflow-y-auto overflow-x-hidden w-full xl:h-full">
                                        <div class="hidden xl:flex justify-between items-center max-w-full">
                                            <div
                                                class="w-auto max-w-xs bg-next-500 relative flex items-center p-3 font-semibold text-sm leading-normal rounded">
                                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 512.004 512.004"
                                                    class="absolute text-next-500 block -right-6 flex-shrink-0 h-full w-auto">
                                                    <path
                                                        d="M509.501,249.969L262.035,2.502c-1.596-1.604-3.763-2.5-6.033-2.5H8.535c-3.447,0-6.571,2.082-7.885,5.265 c-1.323,3.191-0.589,6.861,1.852,9.301l241.434,241.434L2.502,497.435c-2.44,2.441-3.174,6.11-1.852,9.301 c1.314,3.183,4.437,5.265,7.885,5.265h247.467c2.27,0,4.437-0.896,6.033-2.5l247.467-247.467 C512.838,258.698,512.838,253.305,509.501,249.969z">
                                                    </path>
                                                </svg>

                                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 512.004 512.004"
                                                    class="absolute text-next-500 block -right-14 flex-shrink-0 h-full w-auto">
                                                    <path
                                                        d="M509.501,249.969L262.035,2.502c-1.596-1.604-3.763-2.5-6.033-2.5H8.535c-3.447,0-6.571,2.082-7.885,5.265 c-1.323,3.191-0.589,6.861,1.852,9.301l241.434,241.434L2.502,497.435c-2.44,2.441-3.174,6.11-1.852,9.301 c1.314,3.183,4.437,5.265,7.885,5.265h247.467c2.27,0,4.437-0.896,6.033-2.5l247.467-247.467 C512.838,258.698,512.838,253.305,509.501,249.969z">
                                                    </path>
                                                </svg>

                                                <a class="text-white min-w-[100px] max-w-[240px] truncate"
                                                    href="{{ route('productos') . '?categorias=' . $item->slug }}">{{ $item->name }}</a>
                                            </div>

                                            <a href="{{ route('productos') }}" class="custom-btn">
                                                VER TODO<span></span></a>
                                        </div>
                                        <ul
                                            class="w-full flex flex-col xl:flex-row flex-wrap justify-start items-start mt-2">
                                            @foreach ($item->subcategories as $subcategory)
                                                <li class="w-full xl:w-auto block">
                                                    <a class="text-colorsubtitleform rounded-md p-3 w-full block hover:text-hovercolorlinknav text-xs hover:bg-hoverlinknav transition ease-in-out duration-150"
                                                        href="{{ route('productos') . '?subcategorias=' . $subcategory->slug }}">{{ $subcategory->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif --}}
                        </div>
                    @endforeach
                @endif
            </div>

            @if ($empresa->image)
                <a href="/" class="w-full flex py-4 px-8 h-20">
                    <img class="mx-auto max-w-[130px] h-full object-scale-down"
                        src="{{ $empresa->image->getLogoEmpresa() }}" alt="">
                    {{-- <x-isotipo-next class="max-w-[130px] h-full text-neutral-700" /> --}}
                </a>
            @endif
        </div>
    </div>
</div>
