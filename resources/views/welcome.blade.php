<x-app-layout>
    {{-- <div class="relative flex items-top justify-center py-4 sm:pt-0">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        <x-link-button class="inline-block uppercase" href="{{ url('/admin') }}">Dashboard</x-link-button>
    
                        <!-- Authentication -->
                        <form method="POST" class="inline-block" action="{{ route('logout') }}" x-data>
                            @csrf
                            <x-button class="inline-block uppercase" type="submit" @click.prevent="$root.submit();">
                                {{ __('Log Out') }}
                            </x-button>
                        </form>
                    @else
                        <x-link-button class="inline-block uppercase" href="{{ route('login') }}">Log in</x-link-button>
    
                        @if (Route::has('register'))
                            <x-link-button class="inline-block uppercase"
                                href="{{ route('register') }}">Register</x-link-button>
                        @endif
                    @endauth
                </div>
            @endif --}}

    @if (count($sliders) > 0)
        <x-slot name="slider">
            <div class="w-full max-w-full p-0 mt-8 xl:mt-0">
                <div class="relative mb-8">
                    <ol class="absolute z-10 flex justify-center items-center -bottom-9 m-0 pl-0 left-0 right-0"
                        id="indice-slider">
                        @foreach ($sliders as $item)
                            <li class="indicador-slider {{ $loop->first ? 'activo' : '' }}"></li>
                        @endforeach
                    </ol>
                    <div class="relative w-full overflow-hidden h-0 pt-[28%] min-h-[446px] md:min-h-[220px]"
                        id="slider">
                        @foreach ($sliders as $item)
                            <div class="carousel-item {{ $loop->first ? 'activo' : '' }}">
                                <div class="h-full flex relative efecto-slider">
                                    <div class="lazyload-wrapper ">
                                        <img src="{{ $item->getImageURL() }}" alt="home"
                                            class="absolute w-full h-full object-cover">
                                    </div>
                                    <div class="carousel-item-link">
                                        @if ($item->link)
                                            <a href="{{ $item->link }}" class="">
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button"
                        class="absolute w-6 h-12 rounded-r m-auto top-0 left-0 bottom-0 z-[5] p-1 shadow bg-white text-neutral-500 text-center opacity-40 flex justify-center items-center transition-opacity ease-in-out duration-150"
                        id="previusslider">
                        <svg viewBox="0 0 21 37" xmlns="http://www.w3.org/2000/svg" class="block w-full h-full p-0.5">
                            <path d="M20.33 1.776L18.517-.04.54 18.397l17.977 18.434 1.815-1.815L4.45 18.67v-.552z"
                                fill="currentColor" fill-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Previous</span>
                    </button>
                    <button type="button"
                        class="absolute w-6 h-12 rounded-l m-auto top-0 right-0 bottom-0 z-[5] p-1 shadow bg-white text-neutral-500 text-center opacity-40 flex justify-center items-center transition-opacity ease-in-out duration-150"
                        id="nextslider">
                        <svg viewBox="0 0 21 37" xmlns="http://www.w3.org/2000/svg" class="block w-full h-full p-0.5">
                            <path d="M.539 1.776L2.353-.04l17.978 18.436L2.353 36.831.54 35.016l15.88-16.346v-.552z"
                                fill="currentColor" fill-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Next</span>
                    </button>
                </div>
            </div>
        </x-slot>
    @endif

    <div class="w-full py-5">
        {{-- <h3 class="tracking-wide font-bold text-next-500 text-xs mb-3">NOSOTROS</h3> --}}

        <section class="w-full flex flex-col justify-center items-center mx-auto py-12">
            <h2 class="tracking-wide mr-auto text-2xl xl:text-5xl mb-3 font-semibold text-next-500">
                NEXT TECHNOLOGIES<br> TU ALIADO TECNOLÓGICO</h2>

            <p
                class="mt-3 ml-auto tracking-wide mb-3 text-justify p-5 max-w-xl shadow-md shadow-shadowminicard text-colorsubtitleform rounded-xl">
                Somos una empresa creada en 2012, dedicada a
                la
                venta de productos y servicios. Distribuimos accesorios,
                suministros, piezas, partes y equipos de la industria
                tecnológica, ubicada en la zona nororiente del país.
                Formamos alianzas con empresas importadoras y puntos de
                venta directa para brindar una mejor experiencia a nuestros
                clientes.
                {{-- <strong></strong> --}}
            </p>
        </section>


        <section class="w-full overflow-hidden flex flex-wrap justify-around gap-5 py-12 relative">
            <div class="flip3D">
                <div class="card front">
                    <p class="fondo">
                        <span class="fondo-img" style="background-image:url(https://next.net.pe/img/quienes_somos.jpg)">
                        </span>
                    </p>
                    <div class="card-depth">
                        <div class="title-card-front"><span>MISIÓN</span></div>
                    </div>
                </div>
                <div class="card back">
                    <p class="fondo">
                        <span class="fondo-img" style="background-image:url(https://next.net.pe/img/quienes_somos.jpg)">
                        </span>
                    </p>
                    <div class="card-depth">
                        <h1 class="title-card-back">MISIÓN</h1>
                        <p class="texto-card-back">
                            Nuestra Misión es consolidar más empresas e influir en su crecimiento,
                            formando alianzas con
                            empresas importadoras y puntos de venta directa para brindar una mejor
                            experiencia a nuestros clientes.
                        </p>
                    </div>
                </div>
            </div>
            <div class="flip3D">
                <div class="card front">
                    <p class="fondo">
                        <span class="fondo-img" style="background-image:url(https://next.net.pe/img/quienes_somos.jpg)">
                        </span>
                    </p>
                    <div class="card-depth">
                        <h1 class="title-card-front">VISIÓN</h1>
                    </div>
                </div>
                <div class="card back">
                    <p class="fondo">
                        <span class="fondo-img" style="background-image:url(https://next.net.pe/img/quienes_somos.jpg)">
                        </span>
                    </p>
                    <div class="card-depth">
                        <h1 class="title-card-back">VISIÓN</h1>
                        <p class="texto-card-back">
                            Nuestra visión es seguir expandiendonos en el mercado,
                            forjando una fuerza e impacto positivo a nuestros clientes,
                            aportando desde la humildad, lo mejor de nuetra empresa, formando alianzas y haciendo crecer
                            la
                            economía de nuestro Perú.
                        </p>
                    </div>
                </div>
            </div>
            <div class="flip3D">
                <div class="card front">
                    <p class="fondo">
                        <span class="fondo-img" style="background-image:url(https://next.net.pe/img/quienes_somos.jpg)">
                        </span>
                    </p>
                    <div class="card-depth">
                        <h1 class="title-card-front">VALORES</h1>
                    </div>
                </div>
                <div class="card back">
                    <p class="fondo">
                        <span class="fondo-img" style="background-image:url(https://next.net.pe/img/quienes_somos.jpg)">
                        </span>
                    </p>
                    <div class="card-depth">
                        <h1 class="title-card-back">VALORES</h1>
                        <p class="texto-card-back">
                            Nuestro valor principal es confianza, compromiso y disciplina.
                            Formamos cada vez más un equipo fuerte en habilidades y actitudes que benefician nuestra
                            empresa, nos apoyamos mutuamente y transmitimos confianza a nuestros clientes.
                        </p>
                    </div>
                </div>
            </div>
        </section>


        <section class="py-12 max-w-full">
            <div class="w-full relative">
                <div class="text-next-500 font-medium text-2xl text-center">Servicios</div>
                <div class="font-normal text-lg text-center text-colorsubtitleform">
                    Mantenimiento Predictivo, Preventivo y Correctivo</div>
                <div class="relative mt-7 xl:mt-10 xl:px-12">
                    <div class="w-full block relative">
                        <div class="w-full grid xs:grid-cols-2 lg:grid-cols-3 gap-3 relative overflow-hidden">
                            <div class="owl-item">
                                <div class="item wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                                    <div class="icono">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="0.8" stroke-linecap="round"
                                            fill="none" class="block w-20 h-20 mx-auto">
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
                                    <div class="titulo">
                                        <strong>Prevención de incendios y reducción de riesgos</strong>
                                    </div>
                                    <div class="texto">
                                        Eliminar el riesgo eléctrico, causa N° 1 de los incendios.
                                    </div>
                                </div>
                            </div>
                            <div class="owl-item">
                                <div class="item wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                                    <div class="icono">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="0.8" stroke-linecap="round"
                                            color="currentColor" fill="none" class="block w-20 h-20 mx-auto">
                                            <path
                                                d="M2.25562 15.6322C2.28958 15.309 2.52379 15.0485 2.99222 14.5276L4.02329 13.3749C4.27532 13.0558 4.45417 12.5 4.45417 11.9998C4.45417 11.5 4.27526 10.944 4.02326 10.625L2.99222 9.47231C2.52379 8.95137 2.28957 8.6909 2.25562 8.36768C2.22166 8.04446 2.39662 7.74083 2.74653 7.13358L3.24011 6.27698C3.61341 5.62915 3.80005 5.30523 4.11763 5.17607C4.43521 5.0469 4.79437 5.14883 5.51271 5.35267L6.73294 5.69637C7.19155 5.80212 7.6727 5.74213 8.09145 5.52698L8.42833 5.33261C8.78741 5.10262 9.06361 4.76352 9.21649 4.36493L9.55045 3.36754C9.77002 2.70753 9.87981 2.37752 10.1412 2.18876C10.4026 2 10.7498 2 11.4441 2H12.5589C13.2533 2 13.6005 2 13.8618 2.18876C14.1232 2.37752 14.233 2.70753 14.4526 3.36754L14.7865 4.36493C14.9394 4.76352 15.2156 5.10262 15.5747 5.33261L15.9116 5.52698C16.3303 5.74213 16.8115 5.80212 17.2701 5.69637L18.4903 5.35267C19.2086 5.14883 19.5678 5.0469 19.8854 5.17607C20.203 5.30523 20.3896 5.62915 20.7629 6.27698L21.2565 7.13358C21.6064 7.74083 21.7813 8.04446 21.7474 8.36768C21.7134 8.6909 21.4792 8.95137 21.0108 9.47231L19.9797 10.625C19.7278 10.944 19.5488 11.5 19.5488 11.9998C19.5488 12.5 19.7277 13.0558 19.9797 13.3749L21.0108 14.5276C21.4792 15.0485 21.7134 15.309 21.7474 15.6322C21.7813 15.9555 21.6064 16.2591 21.2565 16.8663L20.7629 17.7229C20.3896 18.3707 20.203 18.6947 19.8854 18.8238C19.5678 18.953 19.2086 18.8511 18.4903 18.6472L17.2701 18.3035C16.8114 18.1977 16.3302 18.2578 15.9114 18.473L15.5746 18.6674C15.2155 18.8974 14.9394 19.2364 14.7866 19.635L14.4526 20.6325C14.233 21.2925 14.1232 21.6225 13.8618 21.8112C13.6005 22 13.2533 22 12.5589 22H11.4441C10.7498 22 10.4026 22 10.1412 21.8112C9.87981 21.6225 9.77002 21.2925 9.55045 20.6325" />
                                            <path
                                                d="M2.73744 18.7798C3.81744 17.6998 7.48945 14.0638 7.84945 13.6438C8.23002 13.1998 7.92145 12.5998 8.10505 10.7398C8.19388 9.8398 8.38748 9.16555 8.94145 8.6638C9.60145 8.0398 10.1414 8.0398 12.0014 7.99781C13.6214 8.0398 13.8134 7.8598 13.9814 8.27981C14.1014 8.57981 13.7414 8.7598 13.3094 9.23981C12.3494 10.1998 11.7854 10.6798 11.7314 10.9798C11.3414 12.2998 12.8774 13.0798 13.7174 12.2398C14.0351 11.9221 15.5054 10.4398 15.6494 10.3198C15.7574 10.2238 16.016 10.2284 16.1414 10.3798C16.2494 10.4859 16.2614 10.4998 16.2494 10.9798C16.2383 11.4241 16.2433 12.062 16.2446 12.7198C16.2464 13.5721 16.2014 14.5198 15.8414 14.9998C15.1214 16.0798 13.9214 16.1398 12.8414 16.1878C11.8214 16.2478 10.9814 16.1398 10.7174 16.3318C10.5014 16.4398 9.36145 17.6398 7.98145 19.0198L5.52145 21.4798C3.48145 23.0998 1.23745 20.5798 2.73744 18.7798Z" />
                                        </svg>
                                    </div>
                                    <div class="titulo"><strong>Mayor eficiencia energética</strong></div>
                                    <div class="texto">Ahorro de energía, reduciendo los costos de operación.</div>
                                </div>
                            </div>
                            <div class="owl-item">
                                <div class="item wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                                    <div class="icono">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="0.8" stroke-linecap="round"
                                            color="currentColor" fill="none" class="block w-20 h-20 mx-auto">
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
                                    <div class="titulo"><strong>Riesgos mínimos a la vida del personal y su
                                            seguridad</strong></div>
                                    <div class="texto">Proteger debidamente al personal contra los choques eléctricos
                                        que pueden ser mortales.</div>
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


        <section class="w-full py-12">
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
        </section>
    </div>





    <div class="w-full flex flex-wrap gap-3 xl:space-x-8 p-8">
        <x-button-web text="BUTTON PRUEBA" />
        <x-link-web href="#" text="ENLACE PRUEBA" />
    </div>




    <div class="w-full flex flex-col md:flex-row">
        <div class="w-[42%] flex-shrink-0">
            <div class="w-full rounded-3xl overflow-hidden">
                <img src="https://practipago.pe/wp-content/uploads/2020/11/Imagenes-aplicaciones-Practipago-WEB_2.jpg"
                    alt="">
            </div>
        </div>
        <div class="w-full flex-1">
            <div class="pl-12">
                <h3 class="tracking-wide font-bold text-next-500 text-xs mb-3">Aplicaciones</h3>
                <h2 class="tracking-wide text-4xl mb-3">
                    Descubre lo último en la tecnología <br>que tenemos para tí</h2>

                <div class="">
                    <div class="tracking-wide mb-3">
                        Utiliza solo lo que tu empresa necesita y gestiona los perfiles de usuario<br> con
                        funcionalidades específicas </div>
                </div>

                <div class="w-full">
                    <div class="transition duration-150 -mt-5">
                        <div class="">
                            <div class="">
                                <div class="mx-auto w-full relative">
                                    <div
                                        class="absolute bottom-0 left-4 w-10 h-10 leading-10 text-2xl text-next-500 bg-white cursor-pointer z-[90] prev-arrow flex justify-center items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="w-6 h-6">
                                            <path d="m15 18-6-6 6-6" />
                                        </svg>
                                    </div>
                                    <div class="relative block overflow-hidden">
                                        <div class="relative top-0 left-0 mx-auto grid grid-cols-1 lg:grid-cols-2">
                                            <div class="h-full p-4">
                                                <section class="relative py-8">
                                                    <div class="max-w-[1140px] flex mx-auto relative">
                                                        <div
                                                            class="w-full h-[250px] bg-white rounded-2xl shadow relative flex">
                                                            <div
                                                                class="w-full justify-center content-center items-center px-5 py-4 transition-colors ease-out duration-150">
                                                                <div class="mb-4 text-left w-full relative">
                                                                    <div
                                                                        class="text-left transition-colors ease-in-out duration-150">
                                                                        <img src="https://practipago.pe/wp-content/uploads/2020/10/emision.svg"
                                                                            class="block max-w-full h-auto align-middle"
                                                                            alt="">
                                                                    </div>
                                                                </div>
                                                                <div class="w-full mb-4 h-12">
                                                                    <div
                                                                        class="w-full transition-colors ease-in-out duration-150">
                                                                        <h2
                                                                            class="text-neutral-700 text-xl leading-6 font-medium">
                                                                            Practipago Portal</h2>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="w-full flex h-28 text-colorsubtitleform text-left leading-6 text-sm">
                                                                    <div
                                                                        class="w-full transition-colors ease-in-out duration-150">
                                                                        <p class="w-full leading-normal">
                                                                            Plataforma en línea para la gestión
                                                                            de comisionistas, comisiones y pagos.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>
                                            </div>
                                            <div class="h-full p-4">
                                                <section class="relative py-8">
                                                    <div class="max-w-[1140px] flex mx-auto relative">
                                                        <div
                                                            class="w-full h-[250px] bg-transparent relative rounded-2xl flex hover:shadow-xl">
                                                            <div
                                                                class="w-full justify-center content-center items-center px-5 py-4 transition-colors ease-out duration-150">
                                                                <div class="mb-4 text-left w-full relative">
                                                                    <div
                                                                        class="text-left transition-colors ease-in-out duration-150">
                                                                        <img src="https://practipago.pe/wp-content/uploads/2020/10/emision.svg"
                                                                            class="block max-w-full h-auto align-middle"
                                                                            alt="">
                                                                    </div>
                                                                </div>
                                                                <div class="w-full mb-4 h-12">
                                                                    <div
                                                                        class="w-full transition-colors ease-in-out duration-150">
                                                                        <h2
                                                                            class="text-neutral-700 text-xl leading-6 font-medium">
                                                                            Practipago Portal</h2>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="w-full flex h-28 text-colorsubtitleform text-left leading-6 text-sm">
                                                                    <div
                                                                        class="w-full transition-colors ease-in-out duration-150">
                                                                        <p class="w-full leading-normal">
                                                                            Plataforma en línea para la gestión
                                                                            de comisionistas, comisiones y
                                                                            pagos.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="absolute bottom-0 left-16 w-10 h-10 leading-10 text-2xl text-next-500 bg-white cursor-pointer z-[90] next-arrow flex justify-center items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="w-6 h-6">
                                            <path d="m9 18 6-6-6-6" />
                                        </svg>
                                    </div>

                                    <ul class="w-full my-3 relative flex justify-center items-center !p-0 -top-3">
                                        <li
                                            class="w-3 h-3 overflow-hidden rounded-full bg-neutral-300 m-1 cursor-pointer -indent-2.5">
                                            <span role="tab">1</span>
                                        </li>
                                        <li
                                            class="w-3 h-3 overflow-hidden rounded-full bg-neutral-300 m-1 cursor-pointer -indent-2.5 slick-active">
                                            <span role="tab">2</span>
                                        </li>
                                        <li
                                            class="w-3 h-3 overflow-hidden rounded-full bg-neutral-300 m-1 cursor-pointer -indent-2.5">
                                            <span role="tab">3</span>
                                        </li>
                                        <li
                                            class="w-3 h-3 overflow-hidden rounded-full bg-neutral-300 m-1 cursor-pointer -indent-2.5">
                                            <span role="tab">4</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full relative">
        <div class="bg-white w-full max-w-full lg:px-4 px-0">
            <div class="relative">
                <div class="w-full m-0 mb-10">
                    <div class="text-neutral-500 flex items-start text-xl leading-normal px-9 py-4 gap-2.5 bg-white">No
                        te lo pierdas</div>
                    <div class="w-full m-0 relative">
                        <div>
                            <div class="relative">
                                <ol class="absolute right-0 left-0 -bottom-5 z-[15] flex justify-center m-auto ">
                                    <li
                                        class="inline-block w-2 h-2 rounded-full mx-1 cursor-pointer bg-next-500 transition-opacity ease-out duration-300 active">
                                    </li>
                                    <li
                                        class="inline-block w-2 h-2 rounded-full mx-1 cursor-pointer opacity-50 bg-neutral-300 transition-opacity ease-out duration-300">
                                    </li>
                                    <li
                                        class="inline-block w-2 h-2 rounded-full mx-1 cursor-pointer opacity-50 bg-neutral-300 transition-opacity ease-out duration-300">
                                    </li>
                                </ol>
                                <div class="w-full relative overflow-hidden border-t border-neutral-200 mb-9">
                                    <div
                                        class="active block w-full float-left -mr-[100%] transition-transform ease-in-out duration-700">
                                        <div class="flex max-h-full bg-white">
                                            <div
                                                class="w-52 card-not-unknown relative bg-white flex justify-center px-6 pt-3 pb-6 min-h-[24rem]">
                                                <a class="w-full " href="#">
                                                    <div
                                                        class="absolute top-3 right-8 rounded-sm inline-flex items-center justify-center p-1 text-center h-6 w-14 text-white whitespace-nowrap bg-red-600">
                                                        <span class="text-sm">-61%</span>
                                                    </div>
                                                    <div class="w-full p-0">
                                                        <div class="w-32 h-32 mx-auto mt-0 mb-4">
                                                            <img src="https://imagedelivery.net/4fYuQyy-r8_rpBpcY7lH_A/falabellaPE/18973198_1/public"
                                                                alt="product-case-iphone-11-transparente-image"
                                                                title="product-case-iphone-11-transparente-image"
                                                                class="align-middle">
                                                        </div>
                                                        <div class="inline-flex flex-col">
                                                            <div class="">
                                                                <div
                                                                    class="uppercase text-xs text-neutral-500 font font-semibold tracking-[.075rem]">
                                                                    TG EQUIPMENT</div>
                                                                <div class="w-full -tracking-normal overflow-hidden">
                                                                    <h3
                                                                        class="text-neutral-700 text-sm tracking-[-.019rem] overflow-hidden mb-0">
                                                                        Case iPhone 11 Transparente</h3>
                                                                </div>
                                                            </div>
                                                            <div class="mt-1">
                                                                <div
                                                                    class="w-full text-neutral-500 whitespace-nowrap flex flex-col">
                                                                    <div class="text-xl">
                                                                        S/39</div>
                                                                </div>
                                                                <div class="text-sm text-neutral-500 line-through">
                                                                    S/99</div>
                                                                <div
                                                                    class="PopularityCard-module_original-price__qQv-I">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-1 mb-7">
                                                        <div class="relative inline-flex justify-center items-center"
                                                            title="4.6">
                                                            <svg class="star-grad"
                                                                style="position: absolute; z-index: 0; width: 0px; height: 0px; visibility: hidden;">
                                                                <defs>
                                                                    <linearGradient id="starGrad450314912949914"
                                                                        x1="0%" y1="0%" x2="100%"
                                                                        y2="0%">
                                                                        <stop offset="0%" class="stop-color-first"
                                                                            style="stop-color: rgb(247, 181, 0); stop-opacity: 1;">
                                                                        </stop>
                                                                        <stop offset="60%" class="stop-color-first"
                                                                            style="stop-color: rgb(247, 181, 0); stop-opacity: 1;">
                                                                        </stop>
                                                                        <stop offset="60%" class="stop-color-final"
                                                                            style="stop-color: rgb(203, 211, 227); stop-opacity: 1;">
                                                                        </stop>
                                                                        <stop offset="100%" class="stop-color-final"
                                                                            style="stop-color: rgb(203, 211, 227); stop-opacity: 1;">
                                                                        </stop>
                                                                    </linearGradient>
                                                                </defs>
                                                            </svg>
                                                            <div class="relative inline-block align-middle">
                                                                <svg viewBox="0 0 51 48" class="w-3 h-3 mr-1">
                                                                    <path class="star"
                                                                        d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"
                                                                        style="fill: rgb(247, 181, 0); transition: fill 0.2s ease-in-out 0s;">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <div class="relative inline-block align-middle">
                                                                <svg viewBox="0 0 51 48" class="w-3 h-3 mr-1">
                                                                    <path class="star"
                                                                        d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"
                                                                        style="fill: rgb(247, 181, 0); transition: fill 0.2s ease-in-out 0s;">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <div class="relative inline-block align-middle">
                                                                <svg viewBox="0 0 51 48" class="w-3 h-3 mr-1">
                                                                    <path class="star"
                                                                        d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"
                                                                        style="fill: rgb(247, 181, 0); transition: fill 0.2s ease-in-out 0s;">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <div class="relative inline-block align-middle">
                                                                <svg viewBox="0 0 51 48" class="w-3 h-3 mr-1">
                                                                    <path class="star"
                                                                        d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"
                                                                        style="fill: rgb(247, 181, 0); transition: fill 0.2s ease-in-out 0s;">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <div class="relative inline-block align-middle">
                                                                <svg viewBox="0 0 51 48" class="w-3 h-3 mr-1">
                                                                    <path class="star"
                                                                        d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"
                                                                        style="fill: url(&quot;#starGrad450314912949914&quot;); transition: fill 0.2s ease-in-out 0s;">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <span class="text-sm text-neutral-400 pt-0.5">4.6</span>
                                                        </div>
                                                    </div>
                                                    <a href="#"
                                                        class="absolute flex justify-center items-center bottom-5 p-2 px-4 rounded-xl text-xs font-semibold leading-5 bg-neutral-700 text-white">
                                                        Ver producto</a>
                                                </a>
                                            </div>
                                            <div
                                                class="w-52 card-not-unknown relative bg-white flex justify-center px-6 pt-3 pb-6 min-h-[24rem]">
                                                <a class="w-full " href="#">
                                                    <div
                                                        class="absolute top-3 right-8 rounded-sm inline-flex items-center justify-center p-1 text-center h-6 w-14 text-white whitespace-nowrap bg-red-600">
                                                        <span class="text-sm">-40%</span>
                                                    </div>
                                                    <div class="w-full p-0">
                                                        <div class="w-32 h-32 mx-auto mt-0 mb-4">
                                                            <img src="	https://falabella.scene7.com/is/image/FalabellaPE/882727669_1"
                                                                alt="product-case-iphone-11-transparente-image"
                                                                title="product-case-iphone-11-transparente-image"
                                                                class="align-middle">
                                                        </div>
                                                        <div class="inline-flex flex-col">
                                                            <div class="">
                                                                <div
                                                                    class="uppercase text-xs text-neutral-500 font font-semibold tracking-[.075rem]">
                                                                    MICA</div>
                                                                <div class="w-full -tracking-normal overflow-hidden">
                                                                    <h3
                                                                        class="text-neutral-700 text-sm tracking-[-.019rem] overflow-hidden mb-0">
                                                                        Juego de Comedor Platinum 4 Sillas</h3>
                                                                </div>
                                                            </div>
                                                            <div class="mt-1">
                                                                <div
                                                                    class="w-full text-neutral-500 whitespace-nowrap flex flex-col">
                                                                    <div class="text-xl">
                                                                        S/299.90</div>
                                                                </div>
                                                                <div class="text-sm text-neutral-500 line-through">
                                                                    S/499.90</div>
                                                                <div
                                                                    class="PopularityCard-module_original-price__qQv-I">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-1 mb-7">
                                                        <div class="relative inline-flex justify-center items-center"
                                                            title="4.6">
                                                            <svg class="star-grad"
                                                                style="position: absolute; z-index: 0; width: 0px; height: 0px; visibility: hidden;">
                                                                <defs>
                                                                    <linearGradient id="starGrad450314912949914"
                                                                        x1="0%" y1="0%" x2="100%"
                                                                        y2="0%">
                                                                        <stop offset="0%" class="stop-color-first"
                                                                            style="stop-color: rgb(247, 181, 0); stop-opacity: 1;">
                                                                        </stop>
                                                                        <stop offset="60%" class="stop-color-first"
                                                                            style="stop-color: rgb(247, 181, 0); stop-opacity: 1;">
                                                                        </stop>
                                                                        <stop offset="60%" class="stop-color-final"
                                                                            style="stop-color: rgb(203, 211, 227); stop-opacity: 1;">
                                                                        </stop>
                                                                        <stop offset="100%" class="stop-color-final"
                                                                            style="stop-color: rgb(203, 211, 227); stop-opacity: 1;">
                                                                        </stop>
                                                                    </linearGradient>
                                                                </defs>
                                                            </svg>
                                                            <div class="relative inline-block align-middle">
                                                                <svg viewBox="0 0 51 48" class="w-3 h-3 mr-1">
                                                                    <path class="star"
                                                                        d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"
                                                                        style="fill: rgb(247, 181, 0); transition: fill 0.2s ease-in-out 0s;">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <div class="relative inline-block align-middle">
                                                                <svg viewBox="0 0 51 48" class="w-3 h-3 mr-1">
                                                                    <path class="star"
                                                                        d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"
                                                                        style="fill: rgb(247, 181, 0); transition: fill 0.2s ease-in-out 0s;">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <div class="relative inline-block align-middle">
                                                                <svg viewBox="0 0 51 48" class="w-3 h-3 mr-1">
                                                                    <path class="star"
                                                                        d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"
                                                                        style="fill: rgb(247, 181, 0); transition: fill 0.2s ease-in-out 0s;">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <div class="relative inline-block align-middle">
                                                                <svg viewBox="0 0 51 48" class="w-3 h-3 mr-1">
                                                                    <path class="star"
                                                                        d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"
                                                                        style="fill: rgb(247, 181, 0); transition: fill 0.2s ease-in-out 0s;">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <div class="relative inline-block align-middle">
                                                                <svg viewBox="0 0 51 48" class="w-3 h-3 mr-1">
                                                                    <path class="star"
                                                                        d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"
                                                                        style="fill: url(&quot;#starGrad450314912949914&quot;); transition: fill 0.2s ease-in-out 0s;">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <span class="text-sm text-neutral-400 pt-0.5">4.5</span>
                                                        </div>
                                                    </div>
                                                    <a href="#"
                                                        class="absolute flex justify-center items-center bottom-5 p-2 px-4 rounded-xl text-xs font-semibold leading-5 bg-neutral-700 text-white">
                                                        Ver producto</a>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a class="absolute top-0 bottom-0 left-0 shadow w-6 h-12 m-auto bg-white z-1 flex justify-center items-center text-white text-center transition-opacity ease-in-out duration-150"
            role="button" href="#">
            <span class="DesktopCarousel-module_icon__sGMF4">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                    fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M13.5669 3.30806C13.811 3.55214 13.811 3.94786 13.5669 4.19194L7.75888 10L13.5669 15.8081C13.811 16.0521 13.811 16.4479 13.5669 16.6919C13.3229 16.936 12.9271 16.936 12.6831 16.6919L6.43306 10.4419C6.18898 10.1979 6.18898 9.80214 6.43306 9.55806L12.6831 3.30806C12.9271 3.06398 13.3229 3.06398 13.5669 3.30806Z"
                        fill="#636363" />
                </svg>
            </span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="absolute top-0 bottom-0 right-0 shadow w-6 h-12 m-auto bg-white z-1 flex justify-center items-center text-white text-center transition-opacity ease-in-out duration-150"
            role="button" href="#">
            <span class="DesktopCarousel-module_icon__sGMF4">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                    fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M6.43306 3.30806C6.18898 3.55214 6.18898 3.94786 6.43306 4.19194L12.2411 10L6.43306 15.8081C6.18898 16.0521 6.18898 16.4479 6.43306 16.6919C6.67714 16.936 7.07286 16.936 7.31694 16.6919L13.5669 10.4419C13.811 10.1979 13.811 9.80214 13.5669 9.55806L7.31694 3.30806C7.07286 3.06398 6.67714 3.06398 6.43306 3.30806Z"
                        fill="#636363" />
                </svg>
            </span>
            <span class="sr-only">Next</span>
        </a>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slider = document.getElementById('slider');
            const indice_slider = document.getElementById('indice-slider');
            const idicador_items = indice_slider.querySelectorAll('.indicador-slider');
            const items = slider.querySelectorAll('.carousel-item');
            const nextButton = document.getElementById('nextslider');
            const prevButton = document.getElementById('previusslider');
            let currentIndex = 0;
            let autoSlideInterval;

            const showSlide = (index, direction) => {
                if (direction === 'next') {
                    // items[index].classList.add('entrante');
                    // items[currentIndex].classList.remove('saliente');
                } else if (direction === 'prev') {
                    // items[currentIndex].classList.add('entrante');
                    // items[index].classList.add('saliente');
                }

                changeImageSlider(index);
            };

            const nextSlide = () => {
                currentIndex = (currentIndex + 1) % items.length;
                showSlide(currentIndex, 'next');
            };

            const prevSlide = () => {
                currentIndex = (currentIndex - 1 + items.length) % items.length;
                showSlide(currentIndex, 'prev');
            };

            const changeImageSlider = (index) => {
                items.forEach((item, i) => {
                    if (i === index) {
                        item.classList.add('activo');
                    } else {
                        item.classList.remove('activo');
                    }
                });

                idicador_items.forEach((item, i) => {
                    if (i === index) {
                        item.classList.add('activo');
                    } else {
                        item.classList.remove('activo');
                    }
                });

                currentIndex = index;
            }

            const startAutoSlide = () => {
                autoSlideInterval = setInterval(nextSlide, 5000);
            };

            const stopAutoSlide = () => {
                clearInterval(autoSlideInterval);
            };

            idicador_items.forEach((button, e) => {
                button.addEventListener('click', function(e) {
                    changeImageSlider($(this).index());
                })
            })

            nextButton.addEventListener('click', nextSlide);
            prevButton.addEventListener('click', prevSlide);

            slider.addEventListener('mouseover', stopAutoSlide);
            slider.addEventListener('mouseout', startAutoSlide);

            showSlide(currentIndex);
            startAutoSlide();

        });
    </script>
</x-app-layout>
