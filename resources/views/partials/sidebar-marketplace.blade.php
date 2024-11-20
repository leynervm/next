<div x-cloak x-show="openSidebar" style="display: none"
    class="fixed left-0 top-[108px] xl:top-24 w-full xs:max-w-[80%] sm:w-64 xl:w-72 sm:max-w-full flex z-[101] transition-transform ease-in-out duration-300"
    x-transition:enter="opacity-0 transition ease-in-out duration-300"
    x-transition:enter-start="opacity-0 -translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="opacity-100 transition ease-in-out duration-300"
    x-transition:leave-start="opacity-100 transition ease-in-out duration-300"
    x-transition:leave-end="opacity-100 -translate-x-full ease-in-out duration-300">
    <div class="sidebar-content-marketplace w-full shadow-md z-[1] flex flex-col justify-between">
        <div class="boxes-sidebar overflow-y-auto !overflow-x-hidden  " {{-- :class="isSM ? 'overflow-y-auto' : ''" --}}>

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
                <a class="itemlink-sidebar-principal" href="{{ route('ofertas') }}"
                    @click="sidebar=false,openSidebar=false,backdrop=false">
                    <div class="title-category-sidebar font-semibold group-hover:text-hovercolorlinknav">
                        <div class="absolute w-12 h-12 p-1 flex-shrink-0 overflow-hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" stroke="none" stroke-width="0" viewBox="0 0 48 49"
                                class="w-full h-full p-1 block animate-bounce">
                                <path fill="#f2a328"
                                    d="m16.31,13.38c-.13-3.69-.27-7.38-.4-11.16-1.42.3-2.78.58-4.24.89,1.53,3.47,3.02,6.87,4.52,10.27.04,0,.08,0,.13,0Z" />
                                <path fill="#f2a328"
                                    d="m32.55,8.91c1.15-2.65,2.3-5.31,3.48-8.02-1.46-.31-2.82-.6-4.21-.89-.07,1.7-.14,3.3-.21,4.9-.06,2.01-.12,4.01-.18,6.02.04.01.08.02.12.04.33-.68.66-1.37,1-2.05Z" />
                                <path fill="#f2a328"
                                    d="m7.41,17.46c-1.64-2.13-3.28-4.27-4.94-6.44-.25.19-.43.33-.6.46-.47.48-.94.97-1.41,1.45-.12.08-.24.17-.45.32,2.48,1.46,4.9,2.88,7.31,4.31.03-.03.06-.07.1-.1Z" />
                                <path fill="#f2a328"
                                    d="m48,13.8c-.78-.81-1.47-1.52-2.24-2.32-1.69,2.2-3.33,4.32-4.96,6.44.04.04.08.07.12.11,2.34-1.4,4.68-2.8,7.07-4.23Z" />
                                <path fill="#de2634"
                                    d="m25.29,22.77c.41-.02.82-.04,1.21-.06.06,0,.12,0,.18,0,2.15-.12,3.61-.2,3.61-.2v.02c.12,0,.23-.01.32-.01.76.03,1.39.11,1.92.16l1.39.38c2.75.57,2.97-1.46,2.8-1.51-.17-.06-.78-7.44-4.89-7.85-4.11-.41-6.06,5.74-6.06,5.74-.78-.44-1.78.28-1.78.28-4.33-6.72-8.19-6.04-9.25-5.22s-2.81,2.83-3.09,5.56c-.28,2.72,2.9,3.24,2.9,3.24,0,0,.04.02.06.03.17,0,.34-.02.52-.03,2.82-.15,6.85-.36,10.15-.53Z" />
                                <polygon fill="#ef941c"
                                    points="30.95 30.68 30.95 30.68 30.94 30.68 30.94 30.68 30.95 30.68" />
                                <path fill="#f19f1e"
                                    d="m13.82,29.59h-6.29c0,.28.02.65.02.91,0,4.94,0,9.87,0,14.81,0,.78.14.91.98.94.32.01.65.04.97.06,1.93.14,3.86.28,5.79.42l.02-17.14c-.5,0-1,0-1.49,0Z" />
                                <path fill="#f1a01e"
                                    d="m30.94,29.62c-.42,0-.84,0-1.26,0-2.5,0-5.04-.02-7.54-.02h-.04s0,.14,0,.14c0,0,0,0,0,0,0,.06,0,.11,0,.17,0,.02,0,.04,0,.05v.36s0,.24,0,.24v.07s0,.04,0,.04v4.14s.03,12.46.03,12.46l7.2.59s1.18.17,1.77.03l-.17-17.2c0-.35,0-.71,0-1.06Z" />
                                <path fill="#e03c50" d="m38.16,23.85s0,.01-.01.02l.19.04c-.06-.02-.12-.04-.17-.05Z" />
                                <path fill="#e02f388"
                                    d="m22.11,29.91v.04h0s0-.02,0-.04c0-.06,0-.11,0-.17,0,0,0,0,0,0v.17s0,0,0,0Z" />
                                <polygon fill="#de2634"
                                    points="22.11 30.67 22.11 30.63 22.11 30.56 22.11 30.32 22.11 29.96 22.11 29.95 22.11 29.91 22.11 29.9 22.11 29.73 22.11 29.6 17.7 29.59 16.24 29.59 16.04 29.59 15.44 29.59 15.43 29.59 15.38 29.59 15.31 29.59 15.31 29.6 15.29 46.74 15.29 46.75 22.14 47.27 22.12 34.81 22.11 30.67" />
                                <path fill="#f3ab2f"
                                    d="m41.44,27.72v-2.48c0-.06,0-.12,0-.19,0-.05,0-.1,0-.14h0s.09-.42-.35-.55c0,0,0,0,0,0,0,0,0,0,0,0h0s-.09-.03-.15-.04c-.87-.14-1.73-.28-2.6-.43h0s0,.18,0,.18l-.02,2.11-.03,3.43h1.89s0,0,0,0c0,0,1.04.07,1.18-.46,0,0,0,0,0,0,.06-.12.08-.27.07-.47,0,0,0-.02,0-.03.03-.46,0-.93,0-.93Z" />
                                <path fill="#e9761c"
                                    d="m38.29,29.61v1.04s0,0,0,0v.12s-.12,15.46-.12,15.46l.19-.07c1.77-.25,1.8-.77,1.8-.77l.03-15.78h-1.89Z" />
                                <polygon fill="#de2634"
                                    points="38.28 30.66 38.28 30.66 38.29 29.61 38.32 26.19 38.33 24.08 38.33 23.9 38.33 23.9 38.15 23.86 33.92 23.06 33.92 23.18 33.92 23.62 33.93 23.88 33.94 25.34 33.98 29.6 33.98 29.62 33.98 29.65 33.99 30.67 33.99 30.69 34.15 47.17 38.16 46.23 38.28 30.77 38.28 30.66" />
                                <path fill="#e9761c"
                                    d="m33.99,30.69v-1.03s0-.03,0-.03v-.02c-1.03,0-3.04.02-3.04.02h0c0,.35,0,.71,0,1.06,0,0,0,0,.01,0h0s0,0,0,0h-.02l.17,17.2,3.05-.71-.16-16.49h0Z" />
                                <path fill="#f3ab2f"
                                    d="m14.19,29.59h1.12s.07,0,.07,0h.66s.2,0,.2,0h1.47s4.4,0,4.4,0h.04c2.5,0,5.04.02,7.54.02.42,0,.84,0,1.26,0h0s2.02-.02,3.04-.02l-.04-4.26v-1.45s-.02-.27-.02-.27v-.44s0-.11,0-.11l-1.39-.38c-.53-.05-1.16-.14-1.92-.16-.09,0-.21,0-.33.01v-.02s-1.45.08-3.6.2c-.06,0-.12,0-.18,0-.39.02-.79.04-1.21.06-3.3.17-7.32.38-10.15.53-.18,0-.35.02-.52.03-1.78.09-2.96.16-2.96.16v.02c-1.89.09-3.25.16-3.58.16-1.42,0-1.41.46-1.41.46v5.47s.86,0,.86,0h6.29s.37,0,.37,0Z" />
                            </svg>
                        </div>

                        <span class="inline-block pl-14 text-primary group-hover:text-hovercolorlinknav">OFERTAS</span>
                        {{-- <span class="sale group-hover:text-textspancardproduct group-hover:bg-fondospancardproduct">
                            SALE</span> --}}
                    </div>
                </a>
            </div>

            <div class="item-sidebar group">
                <a class="itemlink-sidebar-principal" href="{{ route('productos') }}"
                    @click="sidebar=false,openSidebar=false,backdrop=false">
                    <div class="title-category-sidebar group-hover:text-hovercolorlinknav">
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 48 48"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0.3"
                            class="w-9 h-9 p-1">
                            <path
                                d="m4.54,14.08c-1.03-1.25-1.99-2.41-2.94-3.59-.87-1.08-.69-1.86.58-2.45C7.75,5.51,13.31,2.97,18.88.45c1.15-.52,1.66-.39,2.47.58.88,1.05,1.75,2.12,2.71,3.29.94-1.14,1.82-2.22,2.71-3.3.94-1.14,1.46-1.26,2.8-.66,5.45,2.47,10.89,4.95,16.33,7.44,1.51.69,1.67,1.41.62,2.7-.93,1.14-1.87,2.28-2.87,3.51,1.18,1.32,2.33,2.62,3.48,3.91,1.3,1.46,1.16,2.07-.62,2.91-.76.36-1.51.74-2.3,1.03-.67.25-.9.62-.89,1.34.04,3.62,0,10.94.03,14.56.01,1.13-.45,1.8-1.45,2.26-5.49,2.54-10.97,5.1-16.45,7.67-.93.43-1.78.41-2.7-.02-5.44-2.56-10.89-5.11-16.35-7.63-1.06-.49-1.5-1.18-1.48-2.34.05-3.45,0-10.61.03-14.06,0-.71-.22-1.09-.89-1.35-1-.38-1.97-.87-2.94-1.32-1.27-.6-1.46-1.39-.55-2.43,1.28-1.46,2.59-2.9,3.97-4.45Zm35.43-.03c-.26-.16-.39-.26-.54-.33-4.92-2.16-9.83-4.33-14.76-6.46-.34-.15-.86-.1-1.22.06-4.8,2.09-9.59,4.21-14.39,6.33-.22.1-.41.23-.65.37.14.1.19.15.25.17,5,2.14,10,4.29,15.01,6.4.33.14.85.05,1.21-.1,4.15-1.74,8.28-3.52,12.42-5.29.86-.37,1.71-.74,2.66-1.15Zm1.53,9.06c-.58.25-.94.41-1.31.57-3.15,1.42-6.31,2.85-9.46,4.27-1.48.67-1.86.58-2.97-.66-.81-.91-1.63-1.81-2.62-2.92v21.27c.2-.04.34-.04.45-.09,5.12-2.38,10.23-4.76,15.33-7.19.29-.14.53-.66.54-1.01.06-1.64.03-6.99.03-8.63,0-1.8,0-3.6,0-5.61Zm-18.38,22.62v-21.34c-1.11,1.23-2.07,2.29-3.03,3.35-.93,1.02-1.47,1.14-2.75.56-3.15-1.42-6.3-2.85-9.45-4.28-.32-.15-.66-.26-1.08-.42-.02.43-.05.72-.05,1.02,0,2.95.03,9.6-.02,12.54-.01.77.28,1.11.95,1.41,4.42,2.03,8.83,4.11,13.24,6.16.69.32,1.39.63,2.19.99ZM2.25,19.44c5.36,2.42,10.52,4.76,15.7,7.08.2.09.6.1.71-.02,1.29-1.38,2.55-2.8,3.79-4.19-.1-.14-.12-.19-.14-.2-5.24-2.26-10.48-4.52-15.73-6.75-.21-.09-.63,0-.78.16-1.17,1.24-2.29,2.52-3.54,3.91Zm43.47-.31c-1.05-1.17-2-2.17-2.86-3.23-.49-.6-.91-.68-1.62-.37-4.81,2.09-9.63,4.13-14.45,6.19-.33.14-.66.3-1.07.5,1.03,1.16,2.04,2.19,2.93,3.32.6.76,1.12.87,2,.45,3.19-1.51,6.41-2.93,9.62-4.39,1.77-.8,3.54-1.6,5.44-2.46Zm-.73-9.71c-5.44-2.48-10.72-4.88-16.01-7.27-.2-.09-.6-.09-.71.03-.94,1.07-1.83,2.18-2.81,3.36,5.51,2.42,10.85,4.78,16.2,7.11.2.09.63,0,.77-.16.84-.95,1.62-1.94,2.55-3.07Zm-22.29-3.82c-.81-.98-1.58-1.86-2.27-2.8-.44-.6-.83-.67-1.51-.35-4.97,2.3-9.96,4.56-14.95,6.83-.2.09-.38.22-.64.37.75.92,1.48,1.76,2.14,2.64.39.53.73.6,1.34.32,3.31-1.5,6.64-2.93,9.96-4.4,1.93-.85,3.87-1.71,5.93-2.62Z" />
                            <path
                                d="m16.4,35.38c-.27.59-.5,1.09-.77,1.69-2.52-1.18-4.96-2.31-7.51-3.5.25-.56.48-1.06.76-1.69,2.52,1.17,4.98,2.32,7.52,3.5Z" />
                            <path
                                d="m8.1,30c.28-.58.51-1.06.8-1.67,1.45.67,2.86,1.33,4.39,2.04-.26.56-.49,1.05-.78,1.67-1.45-.67-2.86-1.32-4.41-2.04Z" />
                        </svg> --}}
                        {{-- <div class="absolute w-12 h-12 p-1 flex-shrink-0 overflow-hidden">
                            <picture>
                                <source srcset="{{ asset('images/home/recursos/icon-productos.png') }}">
                                <img src="{{ asset('images/home/recursos/icon-productos.png') }}"
                                    alt="{{ asset('images/home/recursos/icon-productos.png') }}"
                                    class="w-full h-full object-scale-down overflow-hidden">
                            </picture>
                        </div> --}}
                       <span class="inline-block {{-- pl-14 --}} tracking-widest text-sm font-medium">TODOS LOS PRODUCTOS</span>
                    </div>
                </a>
            </div>
            @auth
                <div class="item-sidebar group md:hidden">
                    <a class="itemlink-sidebar-principal" href="{{ route('orders') }}"
                        @click="sidebar=false,openSidebar=false,backdrop=false">
                        <div class="title-category-sidebar group-hover:text-hovercolorlinknav">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" class="w-9 h-9 p-1">
                                <path
                                    d="M16 8H17.1597C18.1999 8 19.0664 8.79732 19.1528 9.83391L19.8195 17.8339C19.9167 18.9999 18.9965 20 17.8264 20H6.1736C5.00352 20 4.08334 18.9999 4.18051 17.8339L4.84718 9.83391C4.93356 8.79732 5.80009 8 6.84027 8H8M16 8H8M16 8L16 7C16 5.93913 15.5786 4.92172 14.8284 4.17157C14.0783 3.42143 13.0609 3 12 3C10.9391 3 9.92172 3.42143 9.17157 4.17157C8.42143 4.92172 8 5.93913 8 7L8 8M16 8L16 12M8 8L8 12" />
                            </svg>
                            MIS COMPRAS
                        </div>
                    </a>
                </div>
                <div class="item-sidebar group xs:hidden">
                    <a class="itemlink-sidebar-principal" href="{{ route('wishlist') }}"
                        @click="sidebar=false,openSidebar=false,backdrop=false">
                        <div class="title-category-sidebar group-hover:text-hovercolorlinknav">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="block w-9 h-9 p-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9.73 17.753l-5.23 -5.181a5 5 0 1 1 7.5 -6.566a5 5 0 0 1 8.563 5.041" />
                                <path
                                    d="M17.8 20.817l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l2.428 -.352l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.567 .411l-2.172 -1.138z" />
                            </svg>
                            MIS FAVORITOS
                            <span id="counterwishlist"
                                class="{{ Cart::instance('wishlist')->count() == 0 ? 'hidden' : 'flex' }} w-4 h-4 tracking-tight h-100 justify-center items-center leading-3 text-[9px] bg-fondobadgemarketplace text-colorbadgemarketplace rounded-full">
                                {{ Cart::instance('wishlist')->count() }}
                            </span>
                        </div>
                    </a>
                </div>
            @endauth

            @if (count($categories))
                @foreach ($categories as $item)
                    <div class="item-sidebar" x-data="{ submenu: false }" @click.stop="(submenu=!submenu)">
                        <div class="itemlink-sidebar-principal relative">
                            <div class="title-category-sidebar">
                                <div class="absolute w-12 h-12 p-1 flex-shrink-0 overflow-hidden">
                                    @if ($item->image)
                                        {{-- {!! $item->icon !!} --}}
                                        <picture>
                                            <source srcset="{{ getCategoryURL($item->image->url) }}">
                                            <img src="{{ getCategoryURL($item->image->url) }}"
                                                alt="{{ getCategoryURL($item->image->url) }}"
                                                class="w-full h-full object-scale-down overflow-hidden">
                                        </picture>
                                    @else
                                        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2">
                                            <path d="M4 6h16M4 12h16M4 18h7" />
                                        </svg>
                                    @endif
                                </div>
                                <span class="inline-block pl-14">{{ $item->name }}</span>
                            </div>
                            @if (count($item->subcategories))
                                <div class="link-icon-down">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="block w-full h-full duration-300"
                                        :class="submenu ? 'rotate-90' : ''">
                                        <path d="m9 18 6-6-6-6" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        @if (count($item->subcategories))
                            <div style="display: none" :style="(submenu ? 'display:flex' : 'display:none')"
                                class="menu-subcategories" x-transition>
                                <ul class="w-full flex flex-col">
                                    <li class="w-full block">
                                        <a class="item-subcategory !flex justify-between font-medium gap-1 items-center"
                                            href="{{ route('productos') . '?categorias=' . $item->slug }}"
                                            @click="sidebar=false,openSidebar=false,backdrop=false">
                                            VER TODO
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-4 h-4 p-0.5 block flex-shrink-0" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 12h18m0 0l-8.5-8.5M21 12l-8.5 8.5" />
                                            </svg>
                                        </a>
                                    </li>

                                    @foreach ($item->subcategories as $subcategory)
                                        <li class="w-full block">
                                            <a class="item-subcategory"
                                                href="{{ route('productos') . '?categorias=' . $item->slug . '&subcategorias=' . $subcategory->slug }}"
                                                @click="sidebar=false,openSidebar=false,backdrop=false">
                                                {{ $subcategory->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

        {{-- @if ($empresa->image)
            <a href="/" class="w-full p-1 h-16 mt-auto border-t border-borderminicard">
                <img class="mx-auto w-full max-w-full h-full object-scale-down"
                    src="{{ $empresa->image->getLogoEmpresa() }}" alt="">
            </a>
        @endif --}}
    </div>
</div>
