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
                            <svg xmlns="http://www.w3.org/2000/svg" stroke-width="0"
                                class="w-full h-full p-1 block animate-bounce" viewBox="0 0 48 49">
                                <path fill="#f2a328"
                                    d="M16.31 13.38c-.13-3.69-.27-7.38-.4-11.16-1.42.3-2.78.58-4.24.89 1.53 3.47 3.02 6.87 4.52 10.27h.13Zm16.24-4.47c1.15-2.65 2.3-5.31 3.48-8.02-1.46-.31-2.82-.6-4.21-.89-.07 1.7-.14 3.3-.21 4.9l-.18 6.02c.04.01.08.02.12.04.33-.68.66-1.37 1-2.05M7.41 17.46c-1.64-2.13-3.28-4.27-4.94-6.44-.25.19-.43.33-.6.46-.47.48-.94.97-1.41 1.45-.12.08-.24.17-.45.32 2.48 1.46 4.9 2.88 7.31 4.31.03-.03.06-.07.1-.1ZM48 13.8c-.78-.81-1.47-1.52-2.24-2.32-1.69 2.2-3.33 4.32-4.96 6.44.04.04.08.07.12.11l7.07-4.23Z" />
                                <path fill="#de2634"
                                    d="m25.29 22.77 1.21-.06h.18l3.61-.2v.02c.12 0 .23-.01.32-.01.76.03 1.39.11 1.92.16l1.39.38c2.75.57 2.97-1.46 2.8-1.51-.17-.06-.78-7.44-4.89-7.85s-6.06 5.74-6.06 5.74c-.78-.44-1.78.28-1.78.28-4.33-6.72-8.19-6.04-9.25-5.22s-2.81 2.83-3.09 5.56c-.28 2.72 2.9 3.24 2.9 3.24l.06.03c.17 0 .34-.02.52-.03 2.82-.15 6.85-.36 10.15-.53Z" />
                                <path fill="#ef941c" d="M30.95 30.68h-.01z" />
                                <path fill="#f19f1e"
                                    d="M13.82 29.59H7.53c0 .28.02.65.02.91v14.81c0 .78.14.91.98.94.32.01.65.04.97.06l5.79.42.02-17.14z" />
                                <path fill="#f1a01e"
                                    d="M30.94 29.62h-1.26c-2.5 0-5.04-.02-7.54-.02h-.04v5.21l.03 12.46 7.2.59s1.18.17 1.77.03l-.17-17.2v-1.06Z" />
                                <path fill="#de2634"
                                    d="M22.11 30.67V29.6l-4.41-.01h-2.39v.01l-.02 17.14v.01l6.85.52-.02-12.46z" />
                                <path fill="#f3ab2f"
                                    d="M41.44 27.72v-2.81s.09-.42-.35-.55-.09-.03-.15-.04c-.87-.14-1.73-.28-2.6-.43s0 .18 0 .18l-.02 2.11-.03 3.43h1.89s1.04.07 1.18-.46c.06-.12.08-.27.07-.47v-.03c.03-.46 0-.93 0-.93Z" />
                                <path fill="#e9761c"
                                    d="M38.29 29.61v1.16l-.12 15.46.19-.07c1.77-.25 1.8-.77 1.8-.77l.03-15.78H38.3Z" />
                                <path fill="#de2634"
                                    d="m38.28 30.66.01-1.05.03-3.42.01-2.11v-.18l-.18-.04-4.23-.8v.56l.01.26.01 1.46.04 4.26v.05l.01 1.02v.02l.16 16.48 4.01-.94.12-15.46z" />
                                <path fill="#e9761c"
                                    d="M33.99 30.69v-1.08c-1.03 0-3.04.02-3.04.02v1.06h.01-.02l.17 17.2 3.05-.71L34 30.69Z" />
                                <path fill="#f3ab2f"
                                    d="M14.19 29.59h7.96c2.5 0 5.04.02 7.54.02h1.26s2.02-.02 3.04-.02l-.04-4.26v-1.45l-.02-.27v-.55l-1.39-.38c-.53-.05-1.16-.14-1.92-.16-.09 0-.21 0-.33.01v-.02l-3.6.2h-.18l-1.21.06c-3.3.17-7.32.38-10.15.53-.18 0-.35.02-.52.03-1.78.09-2.96.16-2.96.16v.02c-1.89.09-3.25.16-3.58.16-1.42 0-1.41.46-1.41.46v5.47h7.52Z" />
                            </svg>
                        </div>

                        <span class="inline-block pl-14 text-primary group-hover:text-hovercolorlinknav">
                            PROMOCIONES</span>
                        {{-- <span class="sale group-hover:text-textspancardproduct group-hover:bg-fondospancardproduct">
                            SALE</span> --}}
                    </div>
                </a>
            </div>

            <div class="item-sidebar group">
                <a class="itemlink-sidebar-principal" href="{{ route('productos') }}"
                    @click="sidebar=false,openSidebar=false,backdrop=false">
                    <div class="title-category-sidebar justify-center group-hover:text-hovercolorlinknav">
                        <span class="inline-block tracking-widest text-sm font-medium relative title-channel-sidebar">
                            NUESTROS PRODUCTOS</span>
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

            <div class="item-sidebar group">
                <div class="itemlink-sidebar-principal hover:!bg-inherit hover:!text-colorsubtitleform !cursor-default">
                    <div class="title-category-sidebar !justify-center">
                        <span class="inline-block relative tracking-widest text-sm font-medium title-channel-sidebar">
                           NUESTROS SERVICIOS</span>
                    </div>
                </div>
            </div>

            <div class="item-sidebar" x-data="{ submenu: false }" @click.stop="(submenu=!submenu)">
                <div class="itemlink-sidebar-principal relative">
                    <div class="title-category-sidebar">
                        <div class="absolute w-12 h-12 p-1 flex-shrink-0 overflow-hidden">
                            <picture>
                                <source srcset="{{ asset('images/home/recursos/soluciones_integrales.webp') }}">
                                <img src="{{ asset('images/home/recursos/soluciones_integrales.webp') }}"
                                    alt="Soluciones Integrales"
                                    class="w-full h-full object-scale-down overflow-hidden">
                            </picture>
                        </div>
                        <span class="inline-block pl-14">SOLUCIONES INTEGRALES TI</span>
                    </div>
                    <div class="link-icon-down">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="block w-full h-full duration-300" :class="submenu ? 'rotate-90' : ''">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </div>
                </div>
                <div style="display: none" :style="(submenu ? 'display:flex' : 'display:none')"
                    class="menu-subcategories" x-transition>
                    <ul class="w-full flex flex-col">
                        <li class="w-full block">
                            <a class="item-subcategory !flex justify-between font-medium gap-1 items-center"
                                href="{{ route('tic') }}"
                                @click="localStorage.setItem('activeTab', 1),sidebar=false,openSidebar=false,backdrop=false">
                                VER TODO
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 p-0.5 block flex-shrink-0"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 12h18m0 0l-8.5-8.5M21 12l-8.5 8.5" />
                                </svg>
                            </a>
                        </li>

                        <li class="w-full block">
                            <a class="item-subcategory" href="{{ route('tic') }}"
                                @click="localStorage.setItem('activeTab', 1),sidebar=false,openSidebar=false,backdrop=false">
                                DESARROLLO DE SOFTWARE</a>
                        </li>
                        <li class="w-full block">
                            <a class="item-subcategory" href="{{ route('tic') }}"
                                @click="localStorage.setItem('activeTab', 2),sidebar=false,openSidebar=false,backdrop=false">
                                SOPORTE TÉCNICO</a>
                        </li>
                        <li class="w-full block">
                            <a class="item-subcategory" href="{{ route('tic') }}"
                                @click="localStorage.setItem('activeTab', 3),sidebar=false,openSidebar=false,backdrop=false">
                                SEGURIDAD ELECTRÓNICA</a>
                        </li>
                        <li class="w-full block">
                            <a class="item-subcategory" href="{{ route('tic') }}"
                                @click="localStorage.setItem('activeTab', 4),sidebar=false,openSidebar=false,backdrop=false">
                                CENTRO DE DATOS</a>
                        </li>
                        <li class="w-full block">
                            <a class="item-subcategory" href="{{ route('tic') }}"
                                @click="localStorage.setItem('activeTab', 5),sidebar=false,openSidebar=false,backdrop=false">
                                REDES Y TELECOMUNICACIONES</a>
                        </li>
                        <li class="w-full block">
                            <a class="item-subcategory" href="{{ route('tic') }}"
                                @click="localStorage.setItem('activeTab', 6),sidebar=false,openSidebar=false,backdrop=false">
                                ELECTRICIDAD Y AIRE ACONDICIONADO</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="item-sidebar group">
                <a class="itemlink-sidebar-principal" href="{{ route('servicesnetwork') }}"
                    @click="localStorage.setItem('activeTabCE', 1),sidebar=false,openSidebar=false,backdrop=false">
                    <div class="title-category-sidebar group-hover:text-hovercolorlinknav">
                        <div class="absolute w-12 h-12 p-1 flex-shrink-0 overflow-hidden">
                            <picture>
                                <source srcset="{{ asset('images/home/recursos/internet.webp') }}">
                                <img src="{{ asset('images/home/recursos/internet.webp') }}"
                                    alt="Servicio de Internet"
                                    class="w-full h-full object-scale-down overflow-hidden">
                            </picture>
                        </div>
                        <span class="inline-block pl-14">SERVICIO DE INTERNET</span>
                    </div>
                </a>
            </div>

            <div class="item-sidebar group">
                <a class="itemlink-sidebar-principal" href="{{ route('centroautorizado') }}"
                    @click="localStorage.setItem('activeTabCE', 1),sidebar=false,openSidebar=false,backdrop=false">
                    <div class="title-category-sidebar group-hover:text-hovercolorlinknav">
                        <div class="absolute w-12 h-12 p-1 flex-shrink-0 overflow-hidden">
                            <picture>
                                <source srcset="{{ asset('images/home/recursos/centro_autorizado.webp') }}">
                                <img src="{{ asset('images/home/recursos/centro_autorizado.webp') }}"
                                    alt="Centro Autorizado" class="w-full h-full object-scale-down overflow-hidden">
                            </picture>
                        </div>
                        <span class="inline-block pl-14">CENTRO AUTORIZADO</span>
                    </div>
                </a>
            </div>


            {{-- <div class="item-sidebar" @click.stop="(submenu=!submenu)">
                <div class="itemlink-sidebar-principal relative">
                    <div class="title-category-sidebar">
                        <div class="absolute w-12 h-12 p-1 flex-shrink-0 overflow-hidden">
                            <picture>
                                <source srcset="{{ asset('images/home/recursos/centro_autorizado.webp') }}">
                                <img src="{{ asset('images/home/recursos/centro_autorizado.webp') }}"
                                    alt="Centro Autorizado" class="w-full h-full object-scale-down overflow-hidden">
                            </picture>
                        </div>
                        <span class="inline-block pl-14">CENTRO AUTORIZADO</span>
                    </div>
                </div>
            </div> --}}

        </div>

        {{-- @if ($empresa->image)
            <a href="/" class="w-full p-1 h-16 mt-auto border-t border-borderminicard">
                <img class="mx-auto w-full max-w-full h-full object-scale-down"
                    src="{{ $empresa->image->getLogoEmpresa() }}" alt="">
            </a>
        @endif --}}
    </div>
</div>
