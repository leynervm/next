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


        <section class="py-12 max-w-full">
            <div class="w-full relative">

                <div class="relative mt-7 xl:mt-10 xl:px-12">
                    <div class="w-full block relative">
                        <div class="w-auto mx-auto grid grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-5 relative">
                            <div class="next-wow fadeInUp">
                                <div class="icono">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" fill="none"
                                        class="block w-16 h-16 mx-auto">
                                        <path
                                            d="M10.014 2C6.23617 2 4.34725 2 3.17362 3.17157C2 4.34315 2 6.22876 2 10C2 13.7712 2 15.6569 3.17362 16.8284C4.34725 18 6.23617 18 10.014 18H14.021C17.7989 18 19.6878 18 20.8614 16.8284C21.671 16.0203 21.9221 14.8723 22 13" />
                                        <path d="M12 18V22" />
                                        <path d="M8 22H16" />
                                        <path d="M11 15H13" />
                                        <path
                                            d="M18 4H16C15.0572 4 14.5858 4 14.2929 4.29289C14 4.58579 14 5.05719 14 6V8C14 8.94281 14 9.41421 14.2929 9.70711C14.5858 10 15.0572 10 16 10H18C18.9428 10 19.4142 10 19.7071 9.70711C20 9.41421 20 8.94281 20 8V6C20 5.05719 20 4.58579 19.7071 4.29289C19.4142 4 18.9428 4 18 4Z" />
                                        <path
                                            d="M15.5 10V12M18.5 10V12M15.5 2V4M18.5 2V4M14 5.5H12M14 8.5H12M22 5.5H20M22 8.5H20" />
                                    </svg>
                                </div>
                                <h1 class="titulo !leading-none">
                                    Mantenimiento Preventivo y Correctivo
                                </h1>
                                <div class="texto !leading-none">
                                    Protege tus equipos electrónicos y así evitar gastos indevidos en el futuro.
                                </div>
                            </div>
                            <div class="next-wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                                <div class="icono">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" color="currentColor" fill="none"
                                        class="block w-16 h-16 mx-auto">
                                        <path
                                            d="M2.25562 15.6322C2.28958 15.309 2.52379 15.0485 2.99222 14.5276L4.02329 13.3749C4.27532 13.0558 4.45417 12.5 4.45417 11.9998C4.45417 11.5 4.27526 10.944 4.02326 10.625L2.99222 9.47231C2.52379 8.95137 2.28957 8.6909 2.25562 8.36768C2.22166 8.04446 2.39662 7.74083 2.74653 7.13358L3.24011 6.27698C3.61341 5.62915 3.80005 5.30523 4.11763 5.17607C4.43521 5.0469 4.79437 5.14883 5.51271 5.35267L6.73294 5.69637C7.19155 5.80212 7.6727 5.74213 8.09145 5.52698L8.42833 5.33261C8.78741 5.10262 9.06361 4.76352 9.21649 4.36493L9.55045 3.36754C9.77002 2.70753 9.87981 2.37752 10.1412 2.18876C10.4026 2 10.7498 2 11.4441 2H12.5589C13.2533 2 13.6005 2 13.8618 2.18876C14.1232 2.37752 14.233 2.70753 14.4526 3.36754L14.7865 4.36493C14.9394 4.76352 15.2156 5.10262 15.5747 5.33261L15.9116 5.52698C16.3303 5.74213 16.8115 5.80212 17.2701 5.69637L18.4903 5.35267C19.2086 5.14883 19.5678 5.0469 19.8854 5.17607C20.203 5.30523 20.3896 5.62915 20.7629 6.27698L21.2565 7.13358C21.6064 7.74083 21.7813 8.04446 21.7474 8.36768C21.7134 8.6909 21.4792 8.95137 21.0108 9.47231L19.9797 10.625C19.7278 10.944 19.5488 11.5 19.5488 11.9998C19.5488 12.5 19.7277 13.0558 19.9797 13.3749L21.0108 14.5276C21.4792 15.0485 21.7134 15.309 21.7474 15.6322C21.7813 15.9555 21.6064 16.2591 21.2565 16.8663L20.7629 17.7229C20.3896 18.3707 20.203 18.6947 19.8854 18.8238C19.5678 18.953 19.2086 18.8511 18.4903 18.6472L17.2701 18.3035C16.8114 18.1977 16.3302 18.2578 15.9114 18.473L15.5746 18.6674C15.2155 18.8974 14.9394 19.2364 14.7866 19.635L14.4526 20.6325C14.233 21.2925 14.1232 21.6225 13.8618 21.8112C13.6005 22 13.2533 22 12.5589 22H11.4441C10.7498 22 10.4026 22 10.1412 21.8112C9.87981 21.6225 9.77002 21.2925 9.55045 20.6325" />
                                        <path
                                            d="M2.73744 18.7798C3.81744 17.6998 7.48945 14.0638 7.84945 13.6438C8.23002 13.1998 7.92145 12.5998 8.10505 10.7398C8.19388 9.8398 8.38748 9.16555 8.94145 8.6638C9.60145 8.0398 10.1414 8.0398 12.0014 7.99781C13.6214 8.0398 13.8134 7.8598 13.9814 8.27981C14.1014 8.57981 13.7414 8.7598 13.3094 9.23981C12.3494 10.1998 11.7854 10.6798 11.7314 10.9798C11.3414 12.2998 12.8774 13.0798 13.7174 12.2398C14.0351 11.9221 15.5054 10.4398 15.6494 10.3198C15.7574 10.2238 16.016 10.2284 16.1414 10.3798C16.2494 10.4859 16.2614 10.4998 16.2494 10.9798C16.2383 11.4241 16.2433 12.062 16.2446 12.7198C16.2464 13.5721 16.2014 14.5198 15.8414 14.9998C15.1214 16.0798 13.9214 16.1398 12.8414 16.1878C11.8214 16.2478 10.9814 16.1398 10.7174 16.3318C10.5014 16.4398 9.36145 17.6398 7.98145 19.0198L5.52145 21.4798C3.48145 23.0998 1.23745 20.5798 2.73744 18.7798Z" />
                                    </svg>
                                </div>
                                <h1 class="titulo">
                                    Atención a domicilio</h1>
                                <div class="texto !leading-none">
                                    Llegamos al lugar donde te encuentres</div>
                            </div>
                            <div class="next-wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                                <div class="icono">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" color="currentColor"
                                        fill="none" class="block w-16 h-16 mx-auto">
                                        <path
                                            d="M14 8C14 9.10457 13.1046 10 12 10C10.8954 10 10 9.10457 10 8C10 6.89543 10.8954 6 12 6C13.1046 6 14 6.89543 14 8Z" />
                                        <path
                                            d="M16.9588 5C17.6186 5.86961 18 6.89801 18 8C18 9.10199 17.6186 10.1304 16.9588 11M7.04117 5C6.38143 5.86961 6 6.89801 6 8C6 9.10199 6.38143 10.1304 7.04117 11" />
                                        <path
                                            d="M20.3159 3C21.3796 4.43008 22 6.14984 22 8C22 9.85016 21.3796 11.5699 20.3159 13M3.68409 3C2.62036 4.43008 2 6.14984 2 8C2 9.85016 2.62036 11.5699 3.68409 13" />
                                        <path d="M11 10L7 21" />
                                        <path d="M17 21L13 10" />
                                        <path d="M8.5 17H15.5" />
                                    </svg>
                                </div>
                                <h1 class="titulo">Garantías</h1>
                                <div class="texto !leading-none">
                                    Contamos con la atención de garántias de marcas reconocidas por el mercado.
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <button type="button"
                        class="absolute w-6 xl:w-10 h-16 xl:h-20 rounded-r m-auto top-0 left-0 bottom-0 z-[5] p-1 text-next-500 text-center flex justify-center items-center transition-opacity ease-in-out duration-150"
                        id="previusslider">
                        <svg viewBox="0 0 21 37" xmlns="http://www.w3.org/2000/svg"
                            class="block w-full h-full p-0.5">
                            <path d="M20.33 1.776L18.517-.04.54 18.397l17.977 18.434 1.815-1.815L4.45 18.67v-.552z"
                                fill="currentColor" fill-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Previous</span>
                    </button>
                    <button type="button"
                        class="absolute w-6 xl:w-10 h-16 xl:h-20 rounded-l m-auto top-0 right-0 bottom-0 z-[5] p-1 text-next-500 text-center flex justify-center items-center transition-opacity ease-in-out duration-150"
                        id="nextslider">
                        <svg viewBox="0 0 21 37" xmlns="http://www.w3.org/2000/svg"
                            class="block w-full h-full p-0.5">
                            <path d="M.539 1.776L2.353-.04l17.978 18.436L2.353 36.831.54 35.016l15.88-16.346v-.552z"
                                fill="currentColor" fill-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Next</span>
                    </button> --}}
                </div>
                {{-- <div class="mt-5">
                    <div class="text-center">
                        <button role="button" class="inline-block">
                            <span
                                class="w-3 h-3 block mx-1 my-2 bg-neutral-200 transition-opacity ease-in-out duration-150 rounded-full"></span>
                        </button>
                        <button role="button" class="inline-block active">
                            <span
                                class="w-3 h-3 block mx-1 my-2 bg-next-500 transition-opacity ease-in-out duration-150 rounded-full"></span>
                        </button>
                    </div>
                </div> --}}
            </div>
            {{-- <div class="w-full text-center p-5">
                <x-link-web href="#" text="SABER MÁS" class="mx-auto" />
            </div> --}}
        </section>


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
