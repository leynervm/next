<x-app-layout>
    @if (count($sliders) > 0)
        <x-slot name="slider">
            @include('partials.slider')
        </x-slot>
    @endif

    <div class="contenedor content-home w-full py-5">
        {{-- <section class="w-full flex flex-col justify-center items-center mx-auto py-12">
            <h2 class="tracking-wide text-center text-2xl xl:text-5xl mb-3 font-semibold text-next-500">
                NEXT TECHNOLOGIES<br> TU ALIADO TECNOLÓGICO</h2>

            <p
                class="mt-3 mx-auto tracking-wide mb-3 text-justify [text-align-last:center] text-las p-5 max-w-xl text-colorsubtitleform ">
                Somos una empresa creada en 2012, dedicada a la
                venta de productos y servicios. Distribuimos accesorios,
                suministros, piezas, partes y equipos de la industria
                tecnológica, ubicada en la zona nororiente del país.
                Formamos alianzas con empresas importadoras y puntos de
                venta directa para brindar una mejor experiencia a nuestros
                clientes.
            </p>
        </section> --}}

        @if (count($categories))
            <h1 class="text-center font-semibold text-xl pt-6 text-colorsubtitleform">Categorías</h1>
            <section
                class="w-full pt-4 pb-6 md:pb-12 flex flex-wrap gap-2 sm:gap-3 md:gap-5 justify-center items-center self-center">
                @foreach ($categories as $item)
                    <a href="{{ route('productos') . '?categorias=' . $item->slug }}"
                        class="w-full aspect-square max-w-20 sm:max-w-28 md:max-w-32 sm:max-h-28 group max-h-24 md:max-h-32 flex flex-col items-center justify-center self-center rounded-full p-2.5 sm:p-4 md:p-5 ring-2 ring-borderminicard hover:shadow-lg hover:shadow-shadowminicard hover:ring-primary transition ease-in-out duration-300">
                        <div
                            class="w-full h-6 sm:h-10 md:h-12 block text-colorsubtitleform group-hover:text-primary transition ease-in-out duration-300">
                            @if ($item->icon)
                                {!! $item->icon !!}
                            @else
                                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" class="w-full h-full">
                                    <path d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                            @endif
                        </div>
                        <h1
                            class="text-[8px] sm:text-[9px] md:text-[10px] text-colorsubtitleform pt-3 font-semibold leading-none text-center group-hover:text-primary transition ease-in-out duration-300">
                            {{ $item->name }}</h1>
                    </a>
                @endforeach
            </section>
        @endif

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
