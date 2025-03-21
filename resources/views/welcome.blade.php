<x-app-layout>
    @if (count($sliders) > 0)
        <x-slot name="slider">
            @include('partials.slider')
        </x-slot>
    @endif

    <div class="contenedor {{ count($sliders) > 0 ? '-mt-[108px] xl:-mt-24' : '' }} w-full py-5">
        @if (count($categories))
            <h1 class="text-center font-semibold text-lg sm:text-3xl text-colorsubtitleform">
                Nuestros Productos</h1>
            @php
                $chunks = $categories->chunk(5);
            @endphp
            <section
                class="w-full py-6 pb-10 flex flex-col gap-2 sm:gap-3 md:gap-5 justify-center items-center self-center">
                @foreach ($chunks as $chunk)
                    <div class="w-full flex flex-wrap gap-2 sm:gap-3 md:gap-5 justify-center items-center self-center">
                        @foreach ($chunk as $item)
                            <a href="{{ route('productos') . '?categorias=' . $item->slug }}"
                                class="w-full aspect-square max-w-16 xs:max-w-20 sm:max-w-28 md:max-w-32 sm:max-h-28 group max-h-24 md:max-h-32 flex flex-col items-center justify-center self-center rounded-full p-1 xs:p-2.5 sm:p-4 md:p-5 ring-1 ring-borderminicard hover:shadow-lg hover:shadow-shadowminicard hover:ring-primary transition ease-in-out duration-300">
                                <div
                                    class="w-full h-5 xs:h-6 sm:h-10 md:h-12 block text-colorsubtitleform group-hover:text-primary transition ease-in-out duration-300">
                                    @if ($item->image)
                                        <picture>
                                            <source srcset="{{ getCategoryURL($item->image->url) }}">
                                            <img src="{{ getCategoryURL($item->image->url) }}"
                                                alt="{{ getCategoryURL($item->image->url) }}"
                                                class="w-full h-full object-scale-down overflow-hidden">
                                        </picture>
                                    @else
                                        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2" class="w-full h-full">
                                            <path d="M4 6h16M4 12h16M4 18h7" />
                                        </svg>
                                    @endif
                                </div>
                                <h1
                                    class="pt-1 sm:pt-3 text-[7px] xs:text-[8px] sm:text-[9px] md:text-[10px] !leading-none text-colorsubtitleform font-semibold text-center group-hover:text-primary transition ease-in-out duration-300">
                                    {{ $item->name }}</h1>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </section>

        @endif

        <h1 class="text-center font-semibold text-lg sm:text-3xl text-colorsubtitleform">
            Nuestros Servicios</h1>
        <section
            class="w-full pt-6 pb-10 flex flex-wrap gap-2 sm:gap-3 md:gap-5 justify-center items-center self-center">
            <a href="{{ route('tic') }}"
                class="w-full aspect-square max-w-20 sm:max-w-28 md:max-w-32 sm:max-h-28 group max-h-24 md:max-h-32 flex flex-col items-center justify-center self-center rounded-full p-2.5 sm:p-4 md:p-5 ring-1 ring-borderminicard hover:shadow-lg hover:shadow-shadowminicard hover:ring-primary transition ease-in-out duration-300">
                <div
                    class="w-full h-6 sm:h-10 md:h-12 block text-colorsubtitleform group-hover:text-primary transition ease-in-out duration-300">
                    <picture>
                        <source srcset="{{ asset('images/home/recursos/soluciones_integrales.png') }}">
                        <img src="{{ asset('images/home/recursos/soluciones_integrales.png') }}"
                            alt="{{ asset('images/home/recursos/soluciones_integrales.png') }}"
                            class="w-full h-full object-scale-down overflow-hidden">
                    </picture>
                </div>
                <h1
                    class="text-[8px] sm:text-[9px] md:text-[10px] text-colorsubtitleform pt-3 font-semibold leading-none text-center group-hover:text-primary transition ease-in-out duration-300">
                    SOLUCIONES INTEGRALES EN TI</h1>
            </a>
            <a href="{{ route('servicesnetwork') }}"
                class="w-full aspect-square max-w-20 sm:max-w-28 md:max-w-32 sm:max-h-28 group max-h-24 md:max-h-32 flex flex-col items-center justify-center self-center rounded-full p-2.5 sm:p-4 md:p-5 ring-1 ring-borderminicard hover:shadow-lg hover:shadow-shadowminicard hover:ring-primary transition ease-in-out duration-300">
                <div
                    class="w-full h-6 sm:h-10 md:h-12 block text-colorsubtitleform group-hover:text-primary transition ease-in-out duration-300">
                    <picture>
                        <source srcset="{{ asset('images/home/recursos/internet.png') }}">
                        <img src="{{ asset('images/home/recursos/internet.png') }}"
                            alt="{{ asset('images/home/recursos/internet.png') }}"
                            class="w-full h-full object-scale-down overflow-hidden">
                    </picture>
                </div>
                <h1
                    class="text-[8px] sm:text-[9px] md:text-[10px] text-colorsubtitleform pt-3 font-semibold leading-none text-center group-hover:text-primary transition ease-in-out duration-300">
                    SERVICIO DE INTERNET</h1>
            </a>
            <a x-on:click="localStorage.setItem('activeTabCE', 1);" href="{{ route('centroautorizado') }}"
                class="w-full aspect-square max-w-20 sm:max-w-28 md:max-w-32 sm:max-h-28 group max-h-24 md:max-h-32 flex flex-col items-center justify-center self-center rounded-full p-2.5 sm:p-4 md:p-5 ring-1 ring-borderminicard hover:shadow-lg hover:shadow-shadowminicard hover:ring-primary transition ease-in-out duration-300">
                <div
                    class="w-full h-6 sm:h-10 md:h-12 block text-colorsubtitleform group-hover:text-primary transition ease-in-out duration-300">
                    <picture>
                        <source srcset="{{ asset('images/home/recursos/centro_autorizado.png') }}">
                        <img src="{{ asset('images/home/recursos/centro_autorizado.png') }}"
                            alt="{{ asset('images/home/recursos/centro_autorizado.png') }}"
                            class="w-full h-full object-scale-down overflow-hidden">
                    </picture>
                </div>
                <h1
                    class="text-[8px] sm:text-[9px] md:text-[10px] text-colorsubtitleform pt-3 font-semibold leading-none text-center group-hover:text-primary transition ease-in-out duration-300">
                    CENTRO AUTORIZADO</h1>
            </a>
            <a href="https://soporte.next.net.pe/buscar" target="_blank"
                class="w-full aspect-square max-w-20 sm:max-w-28 md:max-w-32 sm:max-h-28 group max-h-24 md:max-h-32 flex flex-col items-center justify-center self-center rounded-full p-2.5 sm:p-4 md:p-5 ring-1 ring-borderminicard hover:shadow-lg hover:shadow-shadowminicard hover:ring-primary transition ease-in-out duration-300">
                <div
                    class="w-full h-6 sm:h-10 md:h-12 block text-colorsubtitleform group-hover:text-primary transition ease-in-out duration-300">
                    <picture>
                        <source srcset="{{ asset('images/home/recursos/orders.png') }}">
                        <img src="{{ asset('images/home/recursos/orders.png') }}"
                            alt="{{ asset('images/home/recursos/orders.png') }}"
                            class="w-full h-full object-scale-down overflow-hidden">
                    </picture>
                </div>
                <h1
                    class="text-[8px] sm:text-[9px] md:text-[10px] text-colorsubtitleform pt-3 font-semibold leading-none text-center group-hover:text-primary transition ease-in-out duration-300">
                    ORDEN DE TRABAJO</h1>
            </a>
        </section>

        <section class="w-full">
            <a href="{{ route('productos') . '?subcategorias=pc-s-escritorio' }}"
                class="w-full block rounded-lg overflow-hidden">
                <img class="block sm:hidden w-full h-full max-h-full object-center object-cover"
                    src="{{ asset('images/home/mobile/section_1.jpg') }}" alt="">

                <img class="hidden sm:block w-full h-full min-h-40 object-cover object-center lg:object-scale-down"
                    src="{{ asset('images/home/desk/section_1.jpg') }}" alt="">
            </a>
        </section>

        <section class="grid grid-cols-1 sm:grid-cols-2 gap-2 lg:gap-3 mt-2 lg:mt-3">
            <a class="w-full rounded-lg overflow-hidden"
                href="{{ route('productos') . '/case-gamemax-contact-coc-turbo-rojo-gamer' }}">
                <img class="block w-full h-min-h-40 h-full max-h-full object-center object-cover"
                    src="{{ asset('images/home/desk/case_gamer_3.jpg') }}" alt="">
            </a>
            <a class="w-full rounded-lg overflow-hidden"
                href="{{ route('productos') . '/audifono-halion-s2-monkey-negro-verde' }}">
                <img class="block w-full h-min-h-40 h-full max-h-full object-scale-down object-center"
                    src="{{ asset('images/home/desk/audifono_gamer_1.jpg') }}" alt="">
            </a>
        </section>

        <section class="grid grid-cols-1 sm:grid-cols-2 gap-2 lg:gap-3 mt-2 lg:mt-3">
            <a class="w-full rounded-lg overflow-hidden"
                href="{{ route('productos') . '/laptop-gamer-lenovo-legion-pro-5-16arx8-amd-ryzen-7-7745hx-3-6ghz-ram-ddr5-32gb-ssd-1tb-t-video-rtx-8gb-16-s-o-windows-11' }}">
                <img class="block w-full h-min-h-40 h-full max-h-full object-scale-down object-center"
                    src="{{ asset('images/home/desk/laptop_5.jpg') }}" alt="">
            </a>
            <a class="w-full rounded-lg overflow-hidden" href="{{ route('productos') . '?subcategorias=camaras' }}">
                <img class="block w-full h-min-h-40 h-full max-h-full object-scale-down object-center"
                    src="{{ asset('images/home/desk/camaras_2.jpg') }}" alt="">
            </a>
        </section>

        <section class="grid grid-cols-1 sm:grid-cols-2 gap-2 lg:gap-3 mt-2 lg:mt-3">
            <a class="w-full rounded-lg overflow-hidden"
                href="{{ route('productos') . '/parlante-maxtron-hertz-mx-334v-bateria-recargable-rgb' }}">
                <img class="block w-full h-min-h-40 h-full max-h-full object-cover sm:object-scale-down object-center"
                    src="{{ asset('images/home/desk/parlante_6.jpg') }}" alt="">
            </a>
            <a class="w-full rounded-lg overflow-hidden"
                href="{{ route('productos') . '/control-de-asistencia-hikvision-hk-ds-k1t320mfwx-b-wi-fi-reconocimiento-facial-huellas-tarjeta' }}">
                <img class="block w-full h-min-h-40 h-full max-h-full object-cover sm:object-scale-down object-center"
                    src="{{ asset('images/home/desk/control_acceso_4.jpg') }}" alt="">
            </a>
        </section>

        <section class="w-full">
            <a href="{{ route('productos') . '?subcategorias=teclados' }}"
                class="w-full block rounded-lg overflow-hidden">
                <img class="block sm:hidden w-full h-full max-h-full object-center object-cover"
                    src="{{ asset('images/ofertas/mobile/ofertas_1.jpg') }}" alt="">

                <img class="hidden sm:block w-full h-full min-h-40 object-cover object-center lg:object-scale-down"
                    src="{{ asset('images/ofertas/desk/ofertas_1.jpg') }}" alt="">
            </a>
        </section>




        {{-- <section class="my-8">
            <h3 class="text-primary text-2xl font-medium">Marcas</h3>
            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mt-6">
                <div class="w-full max-w-sm mx-auto rounded-md shadow-md overflow-hidden">
                    <div class="flex items-end justify-end h-56 w-full bg-cover"
                        style="background-image: url('https://images.unsplash.com/photo-1563170351-be82bc888aa4?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=376&q=80')">
                        <x-button class="p-2 !rounded-full mx-5 -mb-4">
                            <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M20.0001 11.9998L4.00012 11.9998" />
                                <path
                                    d="M15.0003 17C15.0003 17 20.0002 13.3176 20.0002 12C20.0002 10.6824 15.0002 7 15.0002 7" />
                            </svg>
                        </x-button>
                    </div>
                    <div class="px-5 py-3">
                        <h3 class="text-gray-700 uppercase">Chanel</h3>
                    </div>
                </div>
                <div class="w-full max-w-sm mx-auto rounded-md shadow-md overflow-hidden">
                    <div class="flex items-end justify-end h-56 w-full bg-cover"
                        style="background-image: url('https://images.unsplash.com/photo-1544441893-675973e31985?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1500&q=80')">
                        <x-button class="p-2 !rounded-full mx-5 -mb-4">
                            <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M20.0001 11.9998L4.00012 11.9998" />
                                <path
                                    d="M15.0003 17C15.0003 17 20.0002 13.3176 20.0002 12C20.0002 10.6824 15.0002 7 15.0002 7" />
                            </svg>
                        </x-button>
                    </div>
                    <div class="px-5 py-3">
                        <h3 class="text-gray-700 uppercase">Man Mix</h3>
                    </div>
                </div>
                <div class="w-full max-w-sm mx-auto rounded-md shadow-md overflow-hidden">
                    <div class="flex items-end justify-end h-56 w-full bg-cover"
                        style="background-image: url('https://images.unsplash.com/photo-1532667449560-72a95c8d381b?ixlib=rb-1.2.1&auto=format&fit=crop&w=750&q=80')">
                        <x-button class="p-2 !rounded-full mx-5 -mb-4">
                            <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M20.0001 11.9998L4.00012 11.9998" />
                                <path
                                    d="M15.0003 17C15.0003 17 20.0002 13.3176 20.0002 12C20.0002 10.6824 15.0002 7 15.0002 7" />
                            </svg>
                        </x-button>
                    </div>
                    <div class="px-5 py-3">
                        <h3 class="text-gray-700 uppercase">Classic watch</h3>
                    </div>
                </div>
                <div class="w-full max-w-sm mx-auto rounded-md shadow-md overflow-hidden">
                    <div class="flex items-end justify-end h-56 w-full bg-cover"
                        style="background-image: url('https://images.unsplash.com/photo-1590664863685-a99ef05e9f61?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=345&q=80')">
                        <x-button class="p-2 !rounded-full mx-5 -mb-4">
                            <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M20.0001 11.9998L4.00012 11.9998" />
                                <path
                                    d="M15.0003 17C15.0003 17 20.0002 13.3176 20.0002 12C20.0002 10.6824 15.0002 7 15.0002 7" />
                            </svg>
                        </x-button>
                    </div>
                    <div class="px-5 py-3">
                        <h3 class="text-gray-700 uppercase">woman mix</h3>
                    </div>
                </div>
            </div>
        </section> --}}


        {{-- <section class="w-full py-12">
            <div class="w-full grid grid-cols-1 xl:grid-cols-4">
                <section class="pt-24 px-4 pb-4">
                    <div class="max-w-[350px]">
                        <div class="">
                            <div class="bg-white pb-8 rounded-2xl shadow flex content-start relative flex-wrap">
                                <div class="mb-5 w-full">
                                    <div class="w-full -mt-20">
                                        <img src="https://practipago.pe/wp-content/uploads/2020/10/ahorro.svg"
                                            class="rounded-3xl p-4 bg-next-300 object-cover transition-transform ease-out duration-300 hover:-translate-y-2"
                                            alt="">
                                    </div>
                                </div>
                                <div class="text-center w-full">
                                    <div class="px-8">
                                        <h6
                                            class="h-24 text-colorsubtitleform tracking-wide text-xl font-medium leading-5">
                                            1. Ahorro de personal, tiempos y costos</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="pt-24 px-4 pb-4">
                    <div class="max-w-[350px]">
                        <div class="">
                            <div class="bg-white pb-8 rounded-2xl shadow flex content-start relative flex-wrap">
                                <div class="mb-5 w-full">
                                    <div class="w-full -mt-20">
                                        <img src="https://practipago.pe/wp-content/uploads/2020/10/control-de-pago.svg"
                                            class="rounded-3xl p-4 bg-next-300 object-cover transition-transform ease-out duration-300 hover:-translate-y-2"
                                            alt="">
                                    </div>
                                </div>
                                <div class="text-center w-full">
                                    <div class="px-8">
                                        <h6
                                            class="h-24 text-colorsubtitleform tracking-wide text-xl font-medium leading-5">
                                            2. Por Control de pago en línea para empresas</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="pt-24 px-4 pb-4">
                    <div class="max-w-[350px]">
                        <div class="">
                            <div class="bg-white pb-8 rounded-2xl shadow flex content-start relative flex-wrap">
                                <div class="mb-5 w-full">
                                    <div class="w-full -mt-20">
                                        <img src="https://practipago.pe/wp-content/uploads/2020/10/incremento-de-rentabilidad.svg"
                                            class="rounded-3xl p-4 bg-next-300 object-cover transition-transform ease-out duration-300 hover:-translate-y-2"
                                            alt="">
                                    </div>
                                </div>
                                <div class="text-center w-full">
                                    <div class="px-8">
                                        <h6
                                            class="h-24 text-colorsubtitleform tracking-wide text-xl font-medium leading-5">
                                            3. Incremento de rentabilidad</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </section> --}}

    </div>
</x-app-layout>
