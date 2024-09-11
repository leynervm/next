<nav class="fixed w-16 md:w-auto md:relative h-full overflow-y-auto overflow-x-hidden bg-body/50 md:block md:flex-1 ease-in-out duration-300">
    <div class="pt-16 md:pt-0 flex flex-col h-full py-1 justify-between">
        <ul class="w-full text-center px-1 space-y-1">

            {{-- <li class="">
                <x-nav-link href="#" class="">
                    <x-slot name="titulo">HOME</x-slot>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 10 2 2 4-4" />
                        <rect width="20" height="14" x="2" y="3" rx="2" />
                        <path d="M12 17v4" />
                        <path d="M8 21h8" />
                    </svg>
                </x-nav-link>
            </li> --}}

            <li class="">
                <x-nav-link href="/admin" class="">
                    <x-slot name="titulo">Inicio</x-slot>
                    {{-- <span class="hidden md:block">Inicio</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 p-1.5">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                </x-nav-link>
            </li>

            @if (Module::isEnabled('Marketplace'))
                @canany(['admin.marketplace.orders', 'admin.marketplace.transacciones', 'admin.marketplace.userweb',
                    'admin.marketplace.trackingstates', 'admin.marketplace.shipmenttypes', 'admin.marketplace.sliders'])
                    <li>
                        <x-nav-link href="{{ route('admin.marketplace') }}" :active="request()->routeIs('admin.marketplace*')">
                            <x-slot name="titulo">Tienda Web</x-slot>
                            {{-- <span class="hidden md:block">Sitio Web</span> --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="2" x2="22" y1="12" y2="12" />
                                <path
                                    d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                            </svg>
                        </x-nav-link>
                    </li>
                @endcanany
            @endif

            @if (Module::isEnabled('Ventas'))
                @can('admin.ventas')
                    <li>
                        <x-nav-link href="{{ route('admin.ventas') }}" :active="request()->routeIs('admin.ventas*')">
                            <x-slot name="titulo">Ventas</x-slot>
                            {{-- <span class="hidden md:block">Ventas</span> --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M11.5 21h-2.926a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304h11.339a2 2 0 0 1 1.977 2.304l-.117 .761">
                                </path>
                                <path d="M9 11v-5a3 3 0 0 1 6 0v5" />
                                <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                <path d="M20.2 20.2l1.8 1.8" />
                            </svg>
                        </x-nav-link>
                    </li>
                @endcan
            @endif

            @if (Module::isEnabled('Facturacion'))
                @can('admin.facturacion')
                    <li>
                        <x-nav-link href="{{ route('admin.facturacion') }}" class="" :active="request()->routeIs('admin.facturacion*')">
                            <x-slot name="titulo">Facturación</x-slot>
                            {{-- <span class="hidden md:block">Facturación</span> --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M9.72727 2C6.46607 2 4.83546 2 3.70307 2.79784C3.37862 3.02643 3.09058 3.29752 2.8477 3.60289C2 4.66867 2 6.20336 2 9.27273V11.8182C2 14.7814 2 16.2629 2.46894 17.4462C3.22281 19.3486 4.81714 20.8491 6.83836 21.5586C8.09563 22 9.66981 22 12.8182 22C14.6173 22 15.5168 22 16.2352 21.7478C17.3902 21.3424 18.3012 20.4849 18.732 19.3979C19 18.7217 19 17.8751 19 16.1818V15.5" />
                                <path d="M15 7.5C15 7.5 15.5 7.5 16 8.5C16 8.5 17.5882 6 19 5.5" />
                                <path
                                    d="M22 7C22 9.76142 19.7614 12 17 12C14.2386 12 12 9.76142 12 7C12 4.23858 14.2386 2 17 2C19.7614 2 22 4.23858 22 7Z" />
                                <path
                                    d="M2 12C2 13.8409 3.49238 15.3333 5.33333 15.3333C5.99912 15.3333 6.78404 15.2167 7.43137 15.3901C8.00652 15.5442 8.45576 15.9935 8.60988 16.5686C8.78333 17.216 8.66667 18.0009 8.66667 18.6667C8.66667 20.5076 10.1591 22 12 22" />
                            </svg>
                        </x-nav-link>
                    </li>
                @endcan
            @endif

            @if (Module::isEnabled('Ventas'))
                @can('admin.promociones')
                    <li>
                        <x-nav-link href="{{ route('admin.promociones') }}" class="" :active="request()->routeIs('admin.promociones*')">
                            <x-slot name="titulo">Promociones</x-slot>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 p-1.5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M4 11V15C4 18.2998 4 19.9497 5.02513 20.9749C6.05025 22 7.70017 22 11 22H13C16.2998 22 17.9497 22 18.9749 20.9749C20 19.9497 20 18.2998 20 15V11" />
                                <path
                                    d="M3 9C3 8.25231 3 7.87846 3.20096 7.6C3.33261 7.41758 3.52197 7.26609 3.75 7.16077C4.09808 7 4.56538 7 5.5 7H18.5C19.4346 7 19.9019 7 20.25 7.16077C20.478 7.26609 20.6674 7.41758 20.799 7.6C21 7.87846 21 8.25231 21 9C21 9.74769 21 10.1215 20.799 10.4C20.6674 10.5824 20.478 10.7339 20.25 10.8392C19.9019 11 19.4346 11 18.5 11H5.5C4.56538 11 4.09808 11 3.75 10.8392C3.52197 10.7339 3.33261 10.5824 3.20096 10.4C3 10.1215 3 9.74769 3 9Z" />
                                <path
                                    d="M6 3.78571C6 2.79949 6.79949 2 7.78571 2H8.14286C10.2731 2 12 3.7269 12 5.85714V7H9.21429C7.43908 7 6 5.56091 6 3.78571Z" />
                                <path
                                    d="M18 3.78571C18 2.79949 17.2005 2 16.2143 2H15.8571C13.7269 2 12 3.7269 12 5.85714V7H14.7857C16.5609 7 18 5.56091 18 3.78571Z" />
                                <path d="M12 11L12 22" />
                            </svg>
                        </x-nav-link>
                    </li>
                @endcan
            @endif

            <li>
                <x-nav-link href="#" class="">
                    <x-slot name="titulo">Cotizaciones</x-slot>
                    {{-- <span class="hidden md:block">Cotizaciones</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
                        <path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-.5" />
                        <path d="M16 4h2a2 2 0 0 1 1.73 1" />
                        <path d="M18.42 9.61a2.1 2.1 0 1 1 2.97 2.97L16.95 17 13 18l.99-3.95 4.43-4.44Z" />
                        <path d="M8 18h1" />
                    </svg>
                </x-nav-link>
            </li>

            @canany(['admin.cajas', 'admin.cajas.methodpayments', 'admin.cajas.conceptos', 'admin.cajas.aperturas',
                'admin.cajas.mensuales'])
                <li>
                    <x-nav-link href="{{ route('admin.cajas') }}" class="" :active="request()->routeIs('admin.cajas*')">
                        <x-slot name="titulo">Caja Chica</x-slot>
                        {{-- <span class="hidden md:block">Caja Chica</span> --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="m14,18c4.4183,0 8,-3.5817 8,-8c0,-4.41828 -3.5817,-8 -8,-8c-4.41828,0 -8,3.58172 -8,8c0,4.4183 3.58172,8 8,8z" />
                            <path
                                d="m3.15657,11c-0.73134,1.1176 -1.15657,2.4535 -1.15657,3.8888c0,3.9274 3.18378,7.1112 7.11116,7.1112c1.43534,0 2.77124,-0.4252 3.88884,-1.1566" />
                            <path
                                d="m14,7c-1.1046,0 -2,0.67157 -2,1.5c0,0.82843 0.8954,1.5 2,1.5c1.1046,0 2,0.6716 2,1.5c0,0.8284 -0.8954,1.5 -2,1.5m0,-6c0.8708,0 1.6116,0.4174 1.8862,1m-1.8862,5c-0.8708,0 -1.6116,-0.4174 -1.8862,-1m1.8862,1" />
                        </svg>
                    </x-nav-link>
                </li>
            @endcanany

            @can('admin.clientes')
                <li>
                    <x-nav-link href="{{ route('admin.clientes') }}" :active="request()->routeIs('admin.clientes*')">
                        <x-slot name="titulo">Clientes</x-slot>
                        {{-- <span class="hidden md:block">Clientes</span> --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 19a6 6 0 0 0-12 0" />
                            <circle cx="8" cy="9" r="4" />
                            <path d="M22 19a6 6 0 0 0-6-6 4 4 0 1 0 0-8" />
                        </svg>
                    </x-nav-link>
                </li>
            @endcan

            @can('admin.proveedores')
                @if (Module::isEnabled('Almacen') || Module::isEnabled('Soporte'))
                    <li>
                        <x-nav-link href="{{ route('admin.proveedores') }}" :active="request()->routeIs('admin.proveedores*') || request()->routeIs('admin.proveedores*')">
                            <x-slot name="titulo">Proveedores</x-slot>
                            {{-- <span class="hidden md:block">Proveedores</span> --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M10 17h4V5H2v12h3" />
                                <path d="M20 17h2v-3.34a4 4 0 0 0-1.17-2.83L19 9h-5" />
                                <path d="M14 17h1" />
                                <circle cx="7.5" cy="17.5" r="2.5" />
                                <circle cx="17.5" cy="17.5" r="2.5" />
                            </svg>
                        </x-nav-link>
                    </li>
                @endif
            @endcan

            @if (Module::isEnabled('Almacen'))
                @canany(['admin.almacen', 'admin.almacen.compras'])
                    <li>
                        <x-nav-link href="{{ route('admin.almacen') }}" class="" :active="request()->routeIs('admin.almacen*')">
                            <x-slot name="titulo">Almacén</x-slot>
                            {{-- <span class="hidden md:block">Almacén</span> --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <polygon points="12 2 2 7 12 12 22 7 12 2" />
                                <polyline points="2 17 12 22 22 17" />
                                <polyline points="2 12 12 17 22 12" />
                            </svg>
                        </x-nav-link>
                    </li>
                @endcanany
            @elseif (Module::isEnabled('Ventas'))
                @can('admin.almacen.productos')
                    <li>
                        <x-nav-link href="{{ route('admin.almacen.productos') }}" class="" :active="request()->routeIs('admin.almacen*')">
                            <x-slot name="titulo">Productos</x-slot>
                            {{-- <span class="hidden md:block">Almacén</span> --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M4.5 17V6H19.5V17H4.5Z" />
                                <path d="M4.5 6L6.5 2.00001L17.5 2L19.5 6" />
                                <path d="M10 9H14" />
                                <path
                                    d="M11.9994 19.5V22M11.9994 19.5L6.99939 19.5M11.9994 19.5H16.9994M6.99939 19.5H1.99939V22M6.99939 19.5V22M16.9994 19.5H22L21.9994 22M16.9994 19.5V22" />
                            </svg>
                        </x-nav-link>
                    </li>
                @endcan
            @endif


            @if (Module::isEnabled('Soporte'))
                <li class="">
                    <x-nav-link href="{{ route('admin.soporte') }}" class="" :active="request()->routeIs('admin.soporte*')">
                        <x-slot name="titulo">Soporte Técnico</x-slot>
                        {{-- <span class="hidden md:block">Soporte Técnico</span> --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m9 10 2 2 4-4" />
                            <rect width="20" height="14" x="2" y="3" rx="2" />
                            <path d="M12 17v4" />
                            <path d="M8 21h8" />
                        </svg>
                    </x-nav-link>
                </li>
            @endif
            <li>
                <x-nav-link href="/admin/internet" class="">
                    <x-slot name="titulo">Servicio Internet</x-slot>
                    {{-- <span class="hidden md:block">Servicio Internet</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 10a7.31 7.31 0 0 0 10 10Z" />
                        <path d="m9 15 3-3" />
                        <path d="M17 13a6 6 0 0 0-6-6" />
                        <path d="M21 13A10 10 0 0 0 11 3" />
                    </svg>
                </x-nav-link>
            </li>

            <li>
                <x-nav-link href="#" class="">
                    <x-slot name="titulo">Reportes</x-slot>
                    {{-- <span class="hidden md:block">Reportes</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3v18h18" />
                        <rect width="4" height="7" x="7" y="10" rx="1" />
                        <rect width="4" height="12" x="15" y="5" rx="1" />
                    </svg>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="#" class="">
                    <x-slot name="titulo">Gráficos</x-slot>
                    {{-- <span class="hidden md:block">Gráficos</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21.21 15.89A10 10 0 1 1 8 2.83" />
                        <path d="M22 12A10 10 0 0 0 12 2v10z" />
                    </svg>
                </x-nav-link>
            </li>




            <li>
                <x-nav-link href="{{ route('admin.administracion') }}" class="" :active="request()->routeIs('admin.administracion*')">
                    <x-slot name="titulo">Administración</x-slot>
                    {{-- <span class="hidden md:block">Administración</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M10 13.3333C10 13.0233 10 12.8683 10.0341 12.7412C10.1265 12.3961 10.3961 12.1265 10.7412 12.0341C10.8683 12 11.0233 12 11.3333 12H12.6667C12.9767 12 13.1317 12 13.2588 12.0341C13.6039 12.1265 13.8735 12.3961 13.9659 12.7412C14 12.8683 14 13.0233 14 13.3333V14C14 15.1046 13.1046 16 12 16C10.8954 16 10 15.1046 10 14V13.3333Z" />
                        <path
                            d="M13.9 13.5H15.0826C16.3668 13.5 17.0089 13.5 17.5556 13.3842C19.138 13.049 20.429 12.0207 20.9939 10.6455C21.1891 10.1704 21.2687 9.59552 21.428 8.4457C21.4878 8.01405 21.5177 7.79823 21.489 7.62169C21.4052 7.10754 20.9932 6.68638 20.4381 6.54764C20.2475 6.5 20.0065 6.5 19.5244 6.5H4.47562C3.99351 6.5 3.75245 6.5 3.56187 6.54764C3.00682 6.68638 2.59477 7.10754 2.51104 7.62169C2.48229 7.79823 2.51219 8.01405 2.57198 8.4457C2.73128 9.59552 2.81092 10.1704 3.00609 10.6455C3.571 12.0207 4.86198 13.049 6.44436 13.3842C6.99105 13.5 7.63318 13.5 8.91743 13.5H10.1" />
                        <path
                            d="M3.5 11.5V13.5C3.5 17.2712 3.5 19.1569 4.60649 20.3284C5.71297 21.5 7.49383 21.5 11.0556 21.5H12.9444C16.5062 21.5 18.287 21.5 19.3935 20.3284C20.5 19.1569 20.5 17.2712 20.5 13.5V11.5" />
                        <path
                            d="M15.5 6.5L15.4227 6.14679C15.0377 4.38673 14.8452 3.50671 14.3869 3.00335C13.9286 2.5 13.3199 2.5 12.1023 2.5H11.8977C10.6801 2.5 10.0714 2.5 9.61309 3.00335C9.15478 3.50671 8.96228 4.38673 8.57727 6.14679L8.5 6.5" />
                    </svg>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="#" class="">
                    <x-slot name="titulo">Negocios Web</x-slot>
                    {{-- <span class="hidden md:block">Negocios Web</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M17.4776 8.39801L17.5 8.39795C19.9853 8.39795 22 10.3784 22 12.8214C22 14.3551 21.206 15.7065 20 16.5M17.4776 8.39801C17.4924 8.23611 17.5 8.07215 17.5 7.90646C17.5 4.92055 15.0376 2.5 12 2.5C9.12324 2.5 6.76233 4.67106 6.52042 7.43728M17.4776 8.39801C17.3753 9.51316 16.9286 10.5307 16.2428 11.3469M6.52042 7.43728C3.98398 7.67454 2 9.77448 2 12.3299C2 14.0886 2.93963 15.6315 4.35232 16.5M6.52042 7.43728C6.67826 7.42251 6.83823 7.41496 7 7.41496C8.12582 7.41496 9.16474 7.78072 10.0005 8.39795" />
                        <path
                            d="M8 15.9778C9.14883 15.0431 10.5209 14.5 11.9946 14.5C13.4729 14.5 14.849 15.0466 16 15.9866M14.1743 18.5C13.5182 18.0909 12.7779 17.8607 11.9946 17.8607C11.2152 17.8607 10.4784 18.0886 9.82477 18.4938" />
                        <path d="M12 21.5H12.0064" />
                    </svg>
                </x-nav-link>
            </li>
        </ul>

        <ul class="w-full text-center p-1 space-y-1">
            <li>
                <x-nav-link href="#" class="">
                    <x-slot name="titulo">Personalización</x-slot>
                    {{-- <span class="hidden md:block">Personalización</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="m21.64 3.64-1.28-1.28a1.21 1.21 0 0 0-1.72 0L2.36 18.64a1.21 1.21 0 0 0 0 1.72l1.28 1.28a1.2 1.2 0 0 0 1.72 0L21.64 5.36a1.2 1.2 0 0 0 0-1.72Z" />
                        <path d="m14 7 3 3" />
                        <path d="M5 6v4" />
                        <path d="M19 14v4" />
                        <path d="M10 2v2" />
                        <path d="M7 8H3" />
                        <path d="M21 16h-4" />
                        <path d="M11 3H9" />
                    </svg>
                </x-nav-link>
            </li>
        </ul>
    </div>

    <script>
        const sidebar = document.getElementById('menu');
        const header_sidebar = document.getElementById('sidebar-header');
        const logo_sidebar = document.getElementById('logo-sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const buttonMobileToggle = document.getElementById('sidebar-close-toggle');
        // const navigation = document.getElementById('navigation');
        // const menu_nav = document.getElementById('menu_navigation');
        const isSidebarOpen = localStorage.getItem('isSidebarOpen') === 'true';
        const itemLinks = sidebar.querySelectorAll('li a');
        const spanLinks = sidebar.querySelectorAll('li a span');
        const iconLinks = sidebar.querySelectorAll('li a svg');

        const isSidebarMobileOpen = localStorage.getItem('isSidebarMobileOpen') === 'true';
        console.log(isSidebarOpen);

        if (isSidebarOpen) {
            sidebar.classList.toggle('md:w-16');
            sidebar.classList.toggle('md:w-48');
            header_sidebar.classList.toggle('justify-between');
            header_sidebar.classList.toggle('justify-center');
            logo_sidebar.classList.toggle('md:block');
            itemLinks.forEach(element => {
                element.classList.toggle('md:flex');
                element.classList.toggle('group');
            });
            spanLinks.forEach(element => {
                element.classList.toggle('md:block');
            });
            iconLinks.forEach(element => {
                // element.classList.toggle('mx-auto');
            });
        }


        // if (isSidebarMobileOpen) {
        //     sidebar.classList.toggle('-translate-x-full');
        //     sidebar.classList.toggle('md:flex');
        //     sidebar.classList.toggle('transition-all');
        // }

        // console.log("Contiene clase w-16 : " + sidebar.classList.contains('w-16'));
        // console.log("Contiene clase w-64 : " + sidebar.classList.contains('w-64'));

        sidebarToggle.addEventListener('click', () => {

            // console.log("Contiene clase w-16 Negacion: " + !sidebar.classList.contains('w-16'))
            sidebar.classList.toggle('md:w-16');
            sidebar.classList.toggle('md:w-48');
            header_sidebar.classList.toggle('justify-between');
            header_sidebar.classList.toggle('justify-center');
            logo_sidebar.classList.toggle('md:block');
            logo_sidebar.classList.toggle('transition-all');
            itemLinks.forEach(element => {
                element.classList.toggle('md:flex');
                element.classList.toggle('group');
            });
            spanLinks.forEach(element => {
                element.classList.toggle('md:block');
            });
            iconLinks.forEach(element => {
                // element.classList.toggle('mx-auto');
            });

            localStorage.setItem('isSidebarOpen', !sidebar.classList.contains('md:w-16'));
        });

        buttonMobileToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('md:flex');
            sidebar.classList.toggle('transition-all');

            localStorage.setItem('isSidebarMobileOpen', !sidebar.classList.contains(
                '-translate-x-full'));
        })
    </script>
</nav>


{{-- <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-jet-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-jet-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-jet-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ml-3 relative">
                        <x-jet-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-jet-dropdown-link
                                        href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-jet-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-jet-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-jet-dropdown-link>
                                    @endcan

                                    <div class="border-t border-gray-100"></div>

                                    <!-- Team Switcher -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Switch Teams') }}
                                    </div>

                                    @foreach (Auth::user()->allTeams() as $team)
                                        <x-jet-switchable-team :team="$team" />
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-jet-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ml-3 relative">
                    <x-jet-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button
                                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover"
                                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                        {{ Auth::user()->name }}

                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-jet-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-jet-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-jet-dropdown-link>
                            @endif

                            <div class="border-t border-gray-100"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-jet-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-jet-dropdown-link>
                            </form>
                        </x-slot>
                    </x-jet-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-jet-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-jet-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 mr-3">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                            alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-jet-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-jet-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-jet-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-jet-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-jet-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-jet-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-jet-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                        :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-jet-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-jet-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-jet-responsive-nav-link>
                    @endcan

                    <div class="border-t border-gray-200"></div>

                    <!-- Team Switcher -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Switch Teams') }}
                    </div>

                    @foreach (Auth::user()->allTeams() as $team)
                        <x-jet-switchable-team :team="$team" component="jet-responsive-nav-link" />
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</nav> --}}
