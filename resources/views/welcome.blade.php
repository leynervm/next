<x-app-layout>
    @if (count($sliders) > 0)
        <x-slot name="slider">
            @include('partials.slider')
        </x-slot>
    @endif

    <div class="contenedor {{ count($sliders) > 0 ? '-mt-[108px] xl:-mt-24' : '' }} w-full py-5">
        @if (count($categories))
            <h1 class="text-center font-semibold text-lg sm:text-xl text-colorsubtitleform">
                Nuestros Productos</h1>

            <div class="w-full overflow-hidden relative" id="swiper-categories">
                <div class="w-full relative py-5 swiper-wrapper">
                    @foreach ($categories as $item)
                        <div class="swiper-slide">
                            <a href="{{ route('productos') . '?categorias=' . $item->slug }}"
                                class="card-category-marketplace"
                                aria-label="{{ route('productos') . '?categorias=' . $item->slug }}">
                                <div
                                    class="block size-10 md:size-12 lg:size-14 xl:size-16 overflow-hidden image-category-marketplace text-colorsubtitleform group-hover:text-primary transition ease-in-out duration-300">
                                    @if ($item->image)
                                        <img src="{{ getCategoryURL($item->image->url) }}" alt="{{ $item->image->url }}"
                                            class="block w-full h-full object-scale-down">
                                    @else
                                        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2" class="w-full h-full">
                                            <path d="M4 6h16M4 12h16M4 18h7" />
                                        </svg>
                                    @endif
                                </div>
                                <p
                                    class="pt-1 text-[9px] font-semibold !leading-none text-colorsubtitleform text-center group-hover:text-primary transition ease-in-out duration-300">
                                    {{ $item->name }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>

                <button id="swiperCatPrev" role="button" aria-label="Boton deslizar izquierda"
                    class="bg-fondominicard z-10 opacity-90 disabled:opacity-25 disabled:shadow-none absolute text-colortitleform top-1/2 left-0 -translate-y-1/2 h-12 w-6 shadow shadow-shadowminicard rounded-sm flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 block">
                        <path d="M15 7L10 12L15 17" />
                    </svg>
                </button>
                <button id="swiperCatNext" role="button" aria-label="Boton deslizar derecha"
                    class="bg-fondominicard z-10 opacity-90 disabled:opacity-25 disabled:shadow-none absolute text-colortitleform top-1/2 right-0 -translate-y-1/2 h-12 w-6 shadow shadow-shadowminicard rounded-sm flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 block">
                        <path d="M10 7L15 12L10 17" />
                    </svg>
                </button>
            </div>
        @endif

        <h1 class="text-center font-semibold text-lg sm:text-xl text-colorsubtitleform">
            Nuestros Servicios</h1>

        <div class="w-full overflow-hidden relative" id="swiper-servicios">
            <div class="w-full relative py-5 swiper-wrapper">
                <div class="swiper-slide">
                    <a href="{{ route('tic') }}" class="card-category-marketplace">
                        <div
                            class="block size-10 md:size-12 lg:size-14 xl:size-16 overflow-hidden image-category-marketplace text-colorsubtitleform group-hover:text-primary transition ease-in-out duration-300">
                            <img src="{{ asset('images/home/recursos/soluciones_integrales.webp') }}"
                                alt="SOLUCIONES INTEGRALES EN TI" class="block w-full h-full object-scale-down"
                                fetchpriority="low">
                        </div>
                        <p
                            class="pt-1 text-[9px] font-semibold !leading-none text-colorsubtitleform text-center group-hover:text-primary transition ease-in-out duration-300">
                            SOLUCIONES INTEGRALES EN TI</p>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="{{ route('servicesnetwork') }}" class="card-category-marketplace">
                        <div
                            class="block size-10 md:size-12 lg:size-14 xl:size-16 overflow-hidden image-category-marketplace text-colorsubtitleform group-hover:text-primary transition ease-in-out duration-300">
                            <img src="{{ asset('images/home/recursos/internet.webp') }}" alt="SERVICIO DE INTERNET"
                                class="block w-full h-full object-scale-down" fetchpriority="low">
                        </div>
                        <p
                            class="pt-1 text-[9px] font-semibold !leading-none text-colorsubtitleform text-center group-hover:text-primary transition ease-in-out duration-300">
                            SERVICIO DE INTERNET</p>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a x-on:click="localStorage.setItem('activeTabCE', 1);" href="{{ route('centroautorizado') }}"
                        class="card-category-marketplace">
                        <div
                            class="block size-10 md:size-12 lg:size-14 xl:size-16 overflow-hidden image-category-marketplace text-colorsubtitleform group-hover:text-primary transition ease-in-out duration-300">
                            <img src="{{ asset('images/home/recursos/centro_autorizado.webp') }}"
                                alt="CENTRO AUTORIZADO" class="block w-full h-full object-scale-down"
                                fetchpriority="low">
                        </div>
                        <p
                            class="pt-1 text-[9px] font-semibold !leading-none text-colorsubtitleform text-center group-hover:text-primary transition ease-in-out duration-300">
                            CENTRO AUTORIZADO</p>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="https://soporte.next.net.pe/buscar" target="_blank" class="card-category-marketplace">
                        <div
                            class="block size-10 md:size-12 lg:size-14 xl:size-16 overflow-hidden image-category-marketplace text-colorsubtitleform group-hover:text-primary transition ease-in-out duration-300">
                            <img src="{{ asset('images/home/recursos/orders.webp') }}" alt=" ORDEN DE TRABAJO"
                                class="block w-full h-full object-scale-down" fetchpriority="low">
                        </div>
                        <p
                            class="pt-1 text-[9px] font-semibold !leading-none text-colorsubtitleform text-center group-hover:text-primary transition ease-in-out duration-300">
                            ORDEN DE TRABAJO</p>
                    </a>
                </div>
            </div>

            <button id="swiperServPrev" role="button" aria-label="Boton deslizar izquierda"
                class="bg-fondominicard z-10 opacity-90 disabled:opacity-25 disabled:shadow-none absolute text-colortitleform top-1/2 left-0 -translate-y-1/2 h-12 w-6 shadow shadow-shadowminicard rounded-sm flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 block">
                    <path d="M15 7L10 12L15 17" />
                </svg>
            </button>
            <button id="swiperServNext" role="button" aria-label="Boton deslizar derecha"
                class="bg-fondominicard z-10 opacity-90 disabled:opacity-25 disabled:shadow-none absolute text-colortitleform top-1/2 right-0 -translate-y-1/2 h-12 w-6 shadow shadow-shadowminicard rounded-sm flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 block">
                    <path d="M10 7L15 12L10 17" />
                </svg>
            </button>
        </div>

        <section class="w-full">
            <a href="{{ route('productos') . '?subcategorias=pc-s-escritorio' }}"
                class="w-full block rounded-lg overflow-hidden"
                aria-label="{{ route('productos') . '?subcategorias=pc-s-escritorio' }}">
                <picture>
                    <source srcset="{{ asset('images/home/desk/section_1.webp') }}" media="(min-width: 640px)"
                        type="image/webp" />
                    <source srcset="{{ asset('images/home/mobile/section_1.webp') }}" media="(max-width: 640px)"
                        type="image/webp" />
                    <img class="block w-full h-auto object-cover sm:object-scale-down object-center"
                        src="{{ asset('images/home/desk/section_1.webp') }}" alt="NEXT TECHNOLOGIES">
                </picture>
            </a>
        </section>

        <section class="grid grid-cols-1 xs:grid-cols-2 gap-2 lg:gap-3 mt-2 lg:mt-3">
            <a class="w-full rounded-lg overflow-hidden"
                href="{{ route('productos') . '/case-gamemax-contact-coc-turbo-rojo-gamer' }}"
                aria-label="{{ route('productos') . '/case-gamemax-contact-coc-turbo-rojo-gamer' }}">
                <picture>
                    <source srcset="{{ asset('images/home/desk/case_gamer_3.webp') }}" media="(min-width: 768px)"
                        type="image/webp" />
                    <source srcset="{{ asset('images/home/mobile/case_gamer_3.webp') }}" media="(max-width: 768px)"
                        type="image/webp" />
                    <img class="block w-full h-auto object-scale-down object-center"
                        src="{{ asset('images/home/desk/case_gamer_3.webp') }}" alt="">
                </picture>
            </a>
            <a class="w-full rounded-lg overflow-hidden"
                href="{{ route('productos') . '/audifono-halion-s2-monkey-negro-verde' }}"
                aria-label="{{ route('productos') . '/audifono-halion-s2-monkey-negro-verde' }}">
                <picture>
                    <source srcset="{{ asset('images/home/desk/audifono_1.webp') }}" media="(min-width: 768px)"
                        type="image/webp" />
                    <source srcset="{{ asset('images/home/mobile/audifono_1.webp') }}" media="(max-width: 768px)"
                        type="image/webp" />
                    <img class="block w-full h-auto object-scale-down object-center"
                        src="{{ asset('images/home/desk/audifono_1.webp') }}" alt="">
                </picture>
            </a>
        </section>

        <section class="grid grid-cols-1 xs:grid-cols-2 gap-2 lg:gap-3 mt-2 lg:mt-3">
            <a class="w-full rounded-lg overflow-hidden"
                href="{{ route('productos') . '/laptop-gamer-lenovo-legion-pro-5-16arx8-amd-ryzen-7-7745hx-3-6ghz-ram-ddr5-32gb-ssd-1tb-t-video-rtx-8gb-16-s-o-windows-11' }}"
                aria-label="{{ route('productos') . '/laptop-gamer-lenovo-legion-pro-5-16arx8-amd-ryzen-7-7745hx-3-6ghz-ram-ddr5-32gb-ssd-1tb-t-video-rtx-8gb-16-s-o-windows-11' }}">
                <picture>
                    <source srcset="{{ asset('images/home/desk/laptop_5.webp') }}" media="(min-width: 768px)"
                        type="image/webp" />
                    <source srcset="{{ asset('images/home/mobile/laptop_5.webp') }}" media="(max-width: 768px)"
                        type="image/webp" />
                    <img class="block w-full h-auto object-scale-down object-center"
                        src="{{ asset('images/home/desk/laptop_5.webp') }}" alt="">
                </picture>
            </a>
            <a class="w-full rounded-lg overflow-hidden" href="{{ route('productos') . '?subcategorias=camaras' }}"
                aria-label="{{ route('productos') . '?subcategorias=camaras' }}">
                <picture>
                    <source srcset="{{ asset('images/home/desk/camaras_2.webp') }}" media="(min-width: 768px)"
                        type="image/webp" />
                    <source srcset="{{ asset('images/home/mobile/camaras_2.webp') }}" media="(max-width: 768px)"
                        type="image/webp" />
                    <img class="block w-full h-auto object-scale-down object-center"
                        src="{{ asset('images/home/desk/camaras_2.webp') }}" alt="">
                </picture>
            </a>
        </section>

        <section class="grid grid-cols-1 xs:grid-cols-2 gap-2 lg:gap-3 mt-2 lg:mt-3">
            <a class="w-full rounded-lg overflow-hidden"
                href="{{ route('productos') . '/parlante-maxtron-hertz-mx-334v-bateria-recargable-rgb' }}"
                aria-label="{{ route('productos') . '/parlante-maxtron-hertz-mx-334v-bateria-recargable-rgb' }}">
                <picture>
                    <source srcset="{{ asset('images/home/desk/parlante_6.webp') }}" media="(min-width: 768px)"
                        type="image/webp" />
                    <source srcset="{{ asset('images/home/mobile/parlante_6.webp') }}" media="(max-width: 768px)"
                        type="image/webp" />
                    <img class="block w-full h-auto object-scale-down object-center"
                        src="{{ asset('images/home/desk/parlante_6.webp') }}" alt="">
                </picture>
            </a>
            <a class="w-full rounded-lg overflow-hidden"
                href="{{ route('productos') . '/control-de-asistencia-hikvision-hk-ds-k1t320mfwx-b-wi-fi-reconocimiento-facial-huellas-tarjeta' }}"
                aria-label="{{ route('productos') . '/control-de-asistencia-hikvision-hk-ds-k1t320mfwx-b-wi-fi-reconocimiento-facial-huellas-tarjeta' }}">
                <picture>
                    <source srcset="{{ asset('images/home/desk/control_acceso_4.webp') }}" media="(min-width: 768px)"
                        type="image/webp" />
                    <source srcset="{{ asset('images/home/mobile/control_acceso_4.webp') }}"
                        media="(max-width: 768px)" type="image/webp" />
                    <img class="block w-full h-auto object-scale-down object-center"
                        src="{{ asset('images/home/desk/control_acceso_4.webp') }}" alt="">
                </picture>
            </a>
        </section>

        <section class="w-full mt-2 lg:mt-3">
            <a href="{{ route('productos') . '?subcategorias=teclados' }}"
                class="w-full block rounded-lg overflow-hidden"
                aria-label="{{ route('productos') . '?subcategorias=teclados' }}">
                <picture>
                    <source srcset="{{ asset('images/ofertas/desk/ofertas_1.webp') }}" media="(min-width: 640px)"
                        type="image/webp" />
                    <source srcset="{{ asset('images/ofertas/mobile/ofertas_1.webp') }}" media="(max-width: 640px)"
                        type="image/webp" />
                    <img class="block w-full h-auto object-cover sm:object-scale-down object-center"
                        src="{{ asset('images/ofertas/desk/ofertas_1.webp') }}" alt="NEXT TECHNOLOGIES">
                </picture>
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

    <script src="{{ asset('assets/swiper/swiper-bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.getElementById('swiper-categories');
            const container = slider.parentElement;
            const swiper = new Swiper(slider, {
                navigation: {
                    prevEl: container.querySelector('#swiperCatPrev'),
                    nextEl: container.querySelector('#swiperCatNext'),
                },
                slidesPerView: 'auto',
                // centeredSlides: true,
                centerInsufficientSlides: true,
                // slidesOffsetBefore: 0,
                // slidesOffsetAfter: 0,
                breakpoints: {
                    0: {
                        slidesPerView: 2,
                        spaceBetween: 16,
                    },
                    280: {
                        slidesPerView: 3,
                        spaceBetween: 8,
                    },
                    360: {
                        slidesPerView: 4,
                        spaceBetween: 6,
                    },
                    480: {
                        slidesPerView: 5,
                        spaceBetween: 10,
                    },
                    640: {
                        slidesPerView: 6,
                        spaceBetween: 10,
                    },
                    768: {
                        slidesPerView: 7,
                        spaceBetween: 10,
                    },
                    1024: {
                        slidesPerView: 8,
                        spaceBetween: 10,
                    },
                    1280: {
                        slidesPerView: 9,
                        spaceBetween: 10,
                    },
                },
                // breakpoints: {
                //     0: {
                //         slidesPerView: 2,
                //         spaceBetween: 4,
                //     },
                //     280: {
                //         slidesPerView: 3,
                //         spaceBetween: 8,
                //     },
                //     380: {
                //         slidesPerView: 4,
                //         spaceBetween: 8,
                //     },
                //     480: {
                //         slidesPerView: 5,
                //         spaceBetween: 16,
                //     },
                //     768: {
                //         slidesPerView: 7,
                //         spaceBetween: 16,
                //     },
                //     1024: {
                //         slidesPerView: 8,
                //         spaceBetween: 16,
                //     },
                //     1280: {
                //         slidesPerView: 9,
                //         spaceBetween: 16,
                //     },
                // },
            })

            const slider_servicios = document.getElementById('swiper-servicios');
            const container_servicios = slider_servicios.parentElement;
            const swiper_servicios = new Swiper(slider_servicios, {
                navigation: {
                    prevEl: container_servicios.querySelector('#swiperServPrev'),
                    nextEl: container_servicios.querySelector('#swiperServNext'),
                },
                slidesPerView: 'auto',
                // centeredSlides: true,
                centerInsufficientSlides: true,
                // slidesOffsetBefore: 0,
                // slidesOffsetAfter: 0,
                breakpoints: {
                    0: {
                        slidesPerView: 2,
                        spaceBetween: 16,
                    },
                    280: {
                        slidesPerView: 3,
                        spaceBetween: 8,
                    },
                    360: {
                        slidesPerView: 4,
                        spaceBetween: 6,
                    },
                    480: {
                        slidesPerView: 5,
                        spaceBetween: 10,
                    },
                    640: {
                        slidesPerView: 6,
                        spaceBetween: 10,
                    },
                    768: {
                        slidesPerView: 7,
                        spaceBetween: 10,
                    },
                    1024: {
                        slidesPerView: 8,
                        spaceBetween: 10,
                    },
                    1280: {
                        slidesPerView: 9,
                        spaceBetween: 10,
                    },
                },
            })
        })
    </script>
</x-app-layout>
