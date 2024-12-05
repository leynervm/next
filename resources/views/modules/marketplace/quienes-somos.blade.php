<x-app-layout>
    <div class="w-full relative overflow-hidden">
        <picture>
            {{-- <source media="(width >= 900px)" srcset="logo-large.png"> --}}
            <source media="(min-width : 768px)" srcset="{{ asset('images/home/recursos/nosotros.jpg') }}">
            <source srcset="{{ asset('images/home/recursos/nosotros_mobile.jpg') }}">
            <img src="{{ asset('images/home/recursos/nosotros_mobile.jpg') }}"
                alt="{{ asset('images/home/nosotros_mobile/nosotros.jpg') }}"
                class="block w-full h-auto max-w-full object-cover">
        </picture>
    </div>


    <div class="contenedor w-full py-5">

        {{-- <section class="w-full py-3 sm:py-5">
            <h1 class="block text-center text-lg text-colorlabel font-medium">
                NUESTROS PRINCIPALES CLIENTES</h1>

            <div class="w-full">

            </div>
        </section> --}}

        <section class="py-3 sm:py-5">
            <h1
                class="text-colorlabel text-center py-3 sm:py-8 text-xl sm:text-2xl font-semibold leading-none md:text-5xl">
                ACERCA DE <span class="text-next-500">NOSOTROS</span>
            </h1>
            <p
                class="text-xs font-light mx-auto sm:text-lg sm:max-w-3xl text-colorsubtitleform text-justify text-last-center !leading-tight">
                Somos una empresa creada en 2012, dedicada a la venta de productos y servicios. Distribuimos
                equipos, piezas, partes, accesorios, suministros de la industria tecnológica, ubicada en la zona
                nor oriente de Perú. Seguimos expandiendo nuestro mercado a través del trabajo y compromiso de
                nuestros colaboradores. Nuestra prioridad es llegar más rapido, con precios competitivos buscando
                nuevas alternativas que contribuyan al crecimiento y conocimiento de las nuevas tecnologías que
                ofrece el mercado internacional.
            </p>
            {{-- <div class="flex flex-col gap-2 sm:gap-5 lg:col-span-3">
                    <div class="rounded-lg shadow shadow-shadowminicard bg-fondominicard p-1 sm:p-3">
                        <h1 class="text-primary mb-3 text-xl font-medium leading-none lg:text-2xl">
                            Misión </h1>
                        <p class="text-xs text-colorsubtitleform leading-none md:text-sm">
                            Nuestra Misión es consolidar más empresas e influir en su crecimiento, formando alianzas con
                            empresas importadoras y puntos de venta directa para brindar una mejor experiencia a
                            nuestros clientes.
                        </p>
                    </div>
                    <div class="rounded-lg shadow shadow-shadowminicard bg-fondominicard p-1 sm:p-3">
                        <h1 class="text-primary mb-3 text-xl font-medium leading-none lg:text-2xl">
                            Visión </h1>
                        <p class="text-xs text-colorsubtitleform leading-none md:text-sm">
                            Nuestra visión es seguir expandiendonos en el mercado, forjando una fuerza e impacto
                            positivo a nuestros clientes, aportando desde la humildad, lo mejor de nuetra empresa,
                            formando
                            alianzas y haciendo crecer la economía de nuestro Perú.
                        </p>
                    </div>
                    <div class="rounded-lg shadow shadow-shadowminicard bg-fondominicard p-1 sm:p-3">
                        <h1 class="text-primary mb-3 text-xl font-medium leading-none lg:text-2xl">
                            Valores </h1>
                        <p class="text-xs text-colorsubtitleform leading-none md:text-sm">
                            Nuestro valor principal es confianza, compromiso y disciplina. Formamos cada vez más un
                            equipo
                            fuerte en habilidades y actitudes que benefician nuestra empresa, nos apoyamos mutuamente y
                            transmitimos confianza a nuestros clientes.
                        </p>
                    </div>
                </div> --}}

            <section class="w-full overflow-hidden flex flex-wrap justify-around gap-5 py-3 lg:py-10 relative">
                <div class="flip3D">
                    <div class="card front">
                        <p class="fondo">
                            <span class="fondo-img"
                                style="background-image:url({{ asset('images/personal_next.jpg') }})">
                            </span>
                        </p>
                        <div class="card-depth">
                            <div class="title-card-front"><span>MISIÓN</span></div>
                        </div>
                    </div>
                    <div class="card back">
                        <p class="fondo">
                            <span class="fondo-img"
                                style="background-image:url({{ asset('images/personal_next.jpg') }})">
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
                            <span class="fondo-img"
                                style="background-image:url({{ asset('images/personal_next.jpg') }})">
                            </span>
                        </p>
                        <div class="card-depth">
                            <h1 class="title-card-front">VISIÓN</h1>
                        </div>
                    </div>
                    <div class="card back">
                        <p class="fondo">
                            <span class="fondo-img"
                                style="background-image:url({{ asset('images/personal_next.jpg') }})">
                            </span>
                        </p>
                        <div class="card-depth">
                            <h1 class="title-card-back">VISIÓN</h1>
                            <p class="texto-card-back">
                                Nuestra visión es seguir expandiendonos en el mercado,
                                forjando una fuerza e impacto positivo a nuestros clientes,
                                aportando desde la humildad, lo mejor de nuetra empresa, formando alianzas y haciendo
                                crecer
                                la
                                economía de nuestro Perú.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flip3D">
                    <div class="card front">
                        <p class="fondo">
                            <span class="fondo-img"
                                style="background-image:url({{ asset('images/personal_next.jpg') }})">
                            </span>
                        </p>
                        <div class="card-depth">
                            <h1 class="title-card-front">VALORES</h1>
                        </div>
                    </div>
                    <div class="card back">
                        <p class="fondo">
                            <span class="fondo-img"
                                style="background-image:url({{ asset('images/personal_next.jpg') }})">
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
        </section>

        <section class="sm:py-3 md:py-8">
            <div class="text-center">
                <h1 class="text-colorlabel pb-3 sm:pb-5 text-xl sm:text-2xl font-semibold leading-none md:text-5xl">
                    ¿POR QUÉ DESTACAMOS?
                </h1>

                <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 gap-2">
                    <div class="w-full">
                        <h1 class="text-next-500 py-3 sm:py-5 text-lg sm:text-xl font-medium">
                            Tecnología de Vanguardia </h1>
                        <p
                            class="w-full sm:max-w-xs text-justify text-last-center mx-auto text-xs sm:text-sm font-light leading-tight text-colorsubtitleform">
                            Ofrecemos las últimas innovaciones en
                            tecnología para garantizar que nuestros clientes siempre tengan acceso a
                            las soluciones más avanzadas del mercado.</p>
                    </div>
                    <div class="w-full">
                        <h1 class="text-next-500 py-3 sm:py-5 text-lg sm:text-xl font-medium">
                            Soporte Técnico Especializado </h1>
                        <p
                            class="w-full sm:max-w-xs text-justify text-last-center mx-auto text-xs sm:text-sm font-light leading-tight text-colorsubtitleform">
                            Nuestro equipo de expertos está disponible para proporcionar soporte técnico de alta
                            calidad, asegurando
                            que todos tus dispositivos funcionen de manera óptima.</p>
                    </div>
                    <div class="w-full">
                        <h1 class="text-next-500 py-3 sm:py-5 text-lg sm:text-xl font-medium">
                            Distribución Rápida y Eficiente </h1>
                        <p
                            class="w-full sm:max-w-xs text-justify text-last-center mx-auto text-xs sm:text-sm font-light leading-tight text-colorsubtitleform">
                            Contamos con una logística ágil que nos permite entregar
                            nuestros productos de manera rápida y segura, para que
                            puedas disfrutar de tus nuevas adquisiciones sin demoras.</p>
                    </div>
                </div>
            </div>
        </section>


        @if (count($employers) > 0)
            <section class="py-3 sm:py-5">
                <h1
                    class="text-colorlabel text-center py-3 sm:py-8 text-xl sm:text-2xl font-semibold leading-none md:text-5xl">
                    NUESTRO <span class="text-next-500">EQUIPO</span>
                </h1>

                <div
                    class="w-full grid grid-cols-[repeat(auto-fill,minmax(170px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-3 self-start">
                    @foreach ($employers as $item)
                        <div class="w-full border border-borderminicard rounded-2xl p-2 sm:p-3">
                            @if ($item->sexo == 'M')
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"
                                    class="block mx-auto w-full h-auto">
                                    <defs>
                                        <style>
                                            .cls-1 {
                                                fill: #84d0f7;
                                            }

                                            .cls-2 {
                                                fill: #aa392d;
                                            }

                                            .cls-3 {
                                                fill: #7c211a;
                                            }

                                            .cls-4 {
                                                fill: #eab198;
                                            }

                                            .cls-5 {
                                                fill: #2d2d2d;
                                            }

                                            .cls-6 {
                                                opacity: 0.2;
                                            }

                                            .cls-7 {
                                                opacity: 0.3;
                                            }
                                        </style>
                                    </defs>

                                    <g data-name="Male 2" id="Male_2">
                                        <path class="cls-1"
                                            d="M250.57,128.13a122,122,0,0,1-37.91,88.44H44.76a101.85,101.85,0,0,1-9-9l-.83-1c-7-8.35-29.75-38-28.57-78.51C7.94,72,55.14,6,128.44,6A122.13,122.13,0,0,1,250.57,128.13Z"
                                            id="Wallpaper" />
                                        <path class="cls-2"
                                            d="M222,206.68a122.41,122.41,0,0,1-93.54,43.58c-39.71,0-69.44-21.09-83.67-33.69a104.1,104.1,0,0,1-9-9c-.25-.28-.54-.6-.82-1C48.52,192.9,66.85,182.24,88,176.19c5.92,14.71,21.81,25.25,40.45,25.25,17.33,0,32.27-9.09,39-22.16.54-1,1-2,1.43-3.09l1.15.33C190.66,182.64,208.59,193.17,222,206.68Z"
                                            id="Sweater" />
                                        <path class="cls-3"
                                            d="M168.86,176.19c-.43,1.05-.89,2.07-1.43,3.09-6.75,13.07-21.69,22.16-39,22.16-18.64,0-34.53-10.54-40.45-25.25l2.67-.73.22-.07s1.78-.47,3.43-.84a37.95,37.95,0,0,0,68.27,0A28.13,28.13,0,0,1,168.86,176.19Z"
                                            id="Neckband" />
                                        <path class="cls-4"
                                            d="M162.55,174.55a37.94,37.94,0,0,1-68.27,0c1.35-.34,2.71-.63,4.08-.91l1-.2c.17-.05.35-.09.51-.12l.87-.17,1.2-.2c.28-.07.6-.11.91-.18H103c.35-.87.68-1.8,1-2.82.11-.34.22-.71.33-1.07a40.67,40.67,0,0,0,.89-4.07,39.88,39.88,0,0,0,6.91,4.6c.18.09.35.18.53.29l.62.31.76.36c.16.07.31.13.44.2l.38.18.47.2.44.17a.69.69,0,0,0,.2.09,4.75,4.75,0,0,0,.54.2l.66.27h0l.73.27.16,0,.51.18.78.22c.11,0,.22.07.35.09l.71.2a38.17,38.17,0,0,0,4.54.8l.46,0c.11,0,.22,0,.34,0l.62,0,.55,0c.2,0,.43,0,.65,0h.55a30.33,30.33,0,0,0,9.74-1.63.07.07,0,0,0,.06,0,1.7,1.7,0,0,0,.36-.11c.8-.27,1.51-.56,2.13-.8l.85-.36.22-.08a38.68,38.68,0,0,0,9.89-6.14c.31,1.38.69,2.85,1.16,4.38.42,1.38.86,2.67,1.31,3.89.37.07.73.11,1.11.2l1.09.2,1.09.2c.26,0,.53.11.8.15a4.54,4.54,0,0,0,.53.12C159.86,173.92,161.21,174.21,162.55,174.55Z"
                                            id="Neck" />
                                        <path class="cls-5"
                                            d="M79.85,94.5a67.36,67.36,0,0,0-1,15.29c0,1,.11,2.06.22,3.09,0,.37.06.77.11,1.15-2.11-1.11-3-6-4.65-15.31-1-5.87-1.55-8.8-1.4-12a37,37,0,0,1,4.09-14.23,17.8,17.8,0,0,0,6.25,9.34A69,69,0,0,0,79.85,94.5Z"
                                            id="Hairs" />
                                        <path class="cls-5"
                                            d="M182.24,98.72c-.8,4.53-1.43,8-2.09,10.51-.71,2.71-1.49,4.29-2.63,4.82.18-1.6.32-3.17.38-4.77a65,65,0,0,0-3.51-24.65s0-.07,0-.09a83.27,83.27,0,0,0,7.75-5.86,36.07,36.07,0,0,1,1.52,8v0C183.81,89.94,183.28,92.88,182.24,98.72Z"
                                            data-name="Hairs" id="Hairs-2" />
                                        <path class="cls-6"
                                            d="M170,176.52c-.82,1-1.69,1.87-2.58,2.76a57.05,57.05,0,0,1-6.28,5.47,44.46,44.46,0,0,1-9.52,5.4A35.78,35.78,0,0,1,145,192.1a30.8,30.8,0,0,1-6.07.54,32.7,32.7,0,0,1-11.62-2.43,4.63,4.63,0,0,1-.64-.29,41.9,41.9,0,0,1-8.34-4.73,59.9,59.9,0,0,1-12.69-12.87c-.57-.77-1.13-1.55-1.68-2.37.11-.34.22-.71.33-1.07a40.67,40.67,0,0,0,.89-4.07c.58.47,1.18.94,1.8,1.38a45.8,45.8,0,0,0,5.11,3.22c.18.09.35.18.53.29l.62.31.76.36c.16.07.31.13.44.2l.38.18.47.2.44.17a.69.69,0,0,0,.2.09l.54.2.66.27h0l.73.27.16,0,.51.18.78.22c.11,0,.22.07.35.09l.71.2a31.48,31.48,0,0,0,4.54.8l.46,0c.11,0,.22,0,.34,0l.62,0,.55,0c.2,0,.43,0,.65,0h.55a31.93,31.93,0,0,0,9.74-1.63.07.07,0,0,0,.06,0,1.7,1.7,0,0,0,.36-.11c.67-.22,1.31-.47,2-.73l.2-.07c.26-.11.53-.22.82-.36l.22-.08a46.4,46.4,0,0,0,8.27-4.89c.55-.4,1.09-.83,1.62-1.25.31,1.38.69,2.85,1.16,4.38.42,1.38.86,2.67,1.31,3.89.37.07.73.11,1.11.2l1.09.2,1.09.2c.26,0,.53.11.8.15a4.54,4.54,0,0,0,.53.12c1.38.28,2.73.57,4.07.91,2.13.49,4.24,1,6.31,1.64Z"
                                            id="NeckShadow" />
                                        <path class="cls-4"
                                            d="M186.6,120.16c-.16,5.2-3.48,10.47-7.19,12.41a7.11,7.11,0,0,1-1.58.6l-.12,0a5.07,5.07,0,0,1-2.05.06,5,5,0,0,1-2-.88l-.28-.21a7.39,7.39,0,0,1-1.13-1.11c-2.68-3.23-3.77-9.29-2-14.13a14.78,14.78,0,0,1,3.23-5.06,9.08,9.08,0,0,1,4.44-2.6,5.91,5.91,0,0,1,2.37,0C184.7,110.15,186.74,115.81,186.6,120.16Z"
                                            id="RightEar" />
                                        <path class="cls-7"
                                            d="M186.6,120.16c-.16,5.2-3.48,10.47-7.2,12.41a7,7,0,0,1-1.57.6l-.12,0a5.07,5.07,0,0,1-2.05.06,5,5,0,0,1-2-.88l-.28-.21a7,7,0,0,1-1.13-1.11c-2.68-3.23-3.77-9.29-2-14.13a14.78,14.78,0,0,1,3.23-5.06,9.08,9.08,0,0,1,4.44-2.6,5.91,5.91,0,0,1,2.37,0C184.7,110.15,186.74,115.81,186.6,120.16Z"
                                            id="RightEarShadow" />
                                        <path class="cls-4"
                                            d="M84.57,131.06a7.28,7.28,0,0,1-1.41,1.32,5.13,5.13,0,0,1-2,.88,5.07,5.07,0,0,1-2.05-.06l-.12,0a7.11,7.11,0,0,1-1.58-.6c-3.71-1.94-7-7.21-7.2-12.41-.13-4.35,1.91-10,6.33-10.9,2.51-.51,4.91.71,6.8,2.61a14.66,14.66,0,0,1,3.24,5.06C88.34,121.77,87.24,127.83,84.57,131.06Z"
                                            id="LeftEar" />
                                        <path class="cls-4"
                                            d="M177.9,109.28c-.06,1.6-.2,3.17-.38,4.77a80.14,80.14,0,0,1-4.17,18.12c-4.67,13.11-12.52,24.73-22,32.33-.53.42-1.07.85-1.62,1.25a46.4,46.4,0,0,1-8.27,4.89l-.22.08c-.29.14-.56.25-.82.36l-.2.07c-.65.26-1.29.51-2,.73a1.7,1.7,0,0,1-.36.11.07.07,0,0,1-.06,0,31.93,31.93,0,0,1-9.74,1.63h-.55c-.22,0-.45,0-.65,0l-.55,0-.62,0c-.12,0-.23,0-.34,0l-.46,0a31.48,31.48,0,0,1-4.54-.8l-.71-.2c-.13,0-.24-.07-.35-.09l-.78-.22-.51-.18-.16,0-.73-.27h0l-.66-.27-.54-.2a.69.69,0,0,1-.2-.09l-.44-.17-.47-.2-.38-.18c-.13-.07-.28-.13-.44-.2l-.76-.36-.62-.31c-.18-.11-.35-.2-.53-.29a45.8,45.8,0,0,1-5.11-3.22c-.62-.44-1.22-.91-1.8-1.38a57.24,57.24,0,0,1-8.6-8.53,78.57,78.57,0,0,1-13.67-25.09A81.2,81.2,0,0,1,79.21,114c0-.38-.09-.78-.11-1.15-.11-1-.18-2-.22-3.09a67.36,67.36,0,0,1,1-15.29A69,69,0,0,1,83.5,81.79c.66-1.76,1.42-3.51,2.24-5.25a65.61,65.61,0,0,1,17.09-22.49c.31-.26.62-.51.94-.75a39.76,39.76,0,0,1,24.49-8.89C138,44.39,146.7,48.12,154,54a58.4,58.4,0,0,1,10.24,10.91,74.31,74.31,0,0,1,8.64,15.51c.54,1.38,1.07,2.71,1.54,4.09,0,0,0,.07,0,.09a65,65,0,0,1,3.51,24.65Z"
                                            id="Head" />
                                        <path class="cls-6"
                                            d="M183.64,86.68v0a26.09,26.09,0,0,1-3.43,3c-.09.09-.55.4-1.15.82-1,.65-2,1.29-3,1.91a74.33,74.33,0,0,1-13.42,6.43,76.87,76.87,0,0,1-62.09-4.47c-2.25-1.22-4.43-2.56-6.52-4-.71-.49-1.4-1-2.09-1.49a64.17,64.17,0,0,1-5.28-4.29c-.8-.71-1.6-1.44-2.36-2.2a40.61,40.61,0,0,0,8.78,5c6.46,2.85,14.18,5,21.67,7.07a73.55,73.55,0,0,0,19.93,3,65.77,65.77,0,0,0,20.07-3,56.6,56.6,0,0,0,8.58-3.57c1.31-.65,2.62-1.36,4-2.09,2.63-1.43,4.91-2.8,7.05-4.25a82.89,82.89,0,0,0,7.67-5.8A32.88,32.88,0,0,1,183.64,86.68Z"
                                            id="HairsShadow" />
                                        <path class="cls-5"
                                            d="M199.37,57c-.07,5.6-5.33,10.71-6.87,12.15-3.93,3.74-7.18,6.8-10.38,9.52l-.08.06a82.89,82.89,0,0,1-7.67,5.8c-2.14,1.45-4.42,2.82-7.05,4.25-1.35.73-2.66,1.44-4,2.09a56.6,56.6,0,0,1-8.58,3.57,65.77,65.77,0,0,1-20.07,3,73.55,73.55,0,0,1-19.93-3c-7.49-2.09-15.21-4.22-21.67-7.07a40.61,40.61,0,0,1-8.78-5l0,0c-.27-.22-.54-.42-.8-.64a17.8,17.8,0,0,1-6.25-9.34v0C74.88,63,81.79,53.56,82.83,52.16a30.41,30.41,0,0,1,12.49-9.42c9.36-4,13.69-.87,34,0,18.07.78,27.12,1.18,35.65-2.16,3.44-1.35,9.89-4.33,11-2.73.91,1.29-1.74,5.11-2.36,6-2.33,3.36-4.29,4.27-4,5.07.51,1.29,6.16.66,10.87-.71,5-1.47,6.89-3.2,7.78-2.36,1.2,1.13-.6,6-3.63,9.22-2.17,2.34-4.2,3-4,3.8.29,1.07,4.17,1.45,7.77.54,5.69-1.43,8.58-5.58,10.18-4.54C199.08,55.1,199.39,55.67,199.37,57Z"
                                            data-name="Hairs" id="Hairs-3" />
                                    </g>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"
                                    class="block mx-auto w-full h-auto">
                                    <defs>
                                        <style>
                                            .cls-1 {
                                                fill: #84d0f7;
                                            }

                                            .cls-2 {
                                                fill: #2d2d2d;
                                            }

                                            .cls-3 {
                                                fill: #050505;
                                            }

                                            .cls-3,
                                            .cls-7 {
                                                opacity: 0.2;
                                            }

                                            .cls-4 {
                                                fill: #aa392d;
                                            }

                                            .cls-5 {
                                                fill: #7c211a;
                                            }

                                            .cls-6 {
                                                fill: #eab198;
                                            }

                                            .cls-8 {
                                                opacity: 0.3;
                                            }
                                        </style>
                                    </defs>

                                    <g data-name="Female 1" id="Female_1">
                                        <path class="cls-1"
                                            d="M250,127.7a121.69,121.69,0,0,1-37.81,88.21H44.71a103.52,103.52,0,0,1-9-8.93l-.82-1c-7-8.33-29.68-37.88-28.5-78.31C8,71.68,55.07,5.87,128.18,5.87A121.82,121.82,0,0,1,250,127.7Z"
                                            id="Wallpaper" />
                                        <circle class="cls-2" cx="127.97" cy="40.84" id="Chignon"
                                            r="22.31" />
                                        <circle class="cls-3" cx="127.97" cy="40.84" id="ChignonShadow"
                                            r="22.31" />
                                        <path class="cls-4"
                                            d="M221.46,206a118.76,118.76,0,0,1-9.27,9.89,121.78,121.78,0,0,1-84,33.6c-25.94,0-47.6-9-63.46-18.77a142.71,142.71,0,0,1-20-14.83,99.86,99.86,0,0,1-9-8.94c-.24-.27-.53-.59-.83-1a111.85,111.85,0,0,1,25.28-19,131.27,131.27,0,0,1,27.67-11.41q20.1,17.76,40.15,35.5,20.3-17.76,40.55-35.5c.28.09.57.16.85.24a.89.89,0,0,0,.29.09,134.45,134.45,0,0,1,26.49,11A113.7,113.7,0,0,1,221.46,206Z"
                                            id="Sweater" />
                                        <path class="cls-5"
                                            d="M168.51,175.64Q148.25,193.4,128,211.14q-20.05-17.76-40.15-35.5A26.74,26.74,0,0,1,94.1,174q16.92,14.5,33.84,29,17.13-14.53,34.27-29A28.65,28.65,0,0,1,168.51,175.64Z"
                                            id="Neckband" />
                                        <path class="cls-6"
                                            d="M162.21,174q-17.13,14.5-34.27,29Q111,188.5,94.1,174c1.45-.35,3-.68,4.5-1s2.81-.55,4.17-.77a38,38,0,0,0,1.66-5.17c.22-1,.4-1.89.55-2.77a41.51,41.51,0,0,0,4.34,3.1,46.93,46.93,0,0,0,4.48,2.45,36.69,36.69,0,0,0,13.53,3.25,37.22,37.22,0,0,0,15-3.53,55.31,55.31,0,0,0,4.93-2.81c1.54-1,2.81-2,3.77-2.76.29,1.29.64,2.65,1.08,4.08s.9,2.87,1.4,4.16c1.19.2,2.39.42,3.64.68Q159.78,173.4,162.21,174Z"
                                            id="Neck" />
                                        <path class="cls-7"
                                            d="M169.65,176c-.84.94-1.69,1.87-2.57,2.74a55,55,0,0,1-6.27,5.46,42.66,42.66,0,0,1-9.49,5.37,34.67,34.67,0,0,1-6.61,2,29.15,29.15,0,0,1-6.05.52,31.93,31.93,0,0,1-11.6-2.41l-.66-.26a42,42,0,0,1-8.28-4.74,60.1,60.1,0,0,1-12.68-12.83c-.57-.76-1.12-1.55-1.66-2.37.1-.35.21-.7.3-1.07a35.34,35.34,0,0,0,.9-4.06c.59.49,1.19.95,1.8,1.39a42.56,42.56,0,0,0,5.11,3.2c.17.08.35.19.52.28l.64.33.75.35.43.2.38.17.46.2.44.18a.83.83,0,0,0,.22.08,3.85,3.85,0,0,0,.52.22l.68.25a0,0,0,0,1,0,0l.73.24.15.06.53.16c.24.09.5.17.76.24a2.1,2.1,0,0,1,.36.09l.7.19a28.59,28.59,0,0,0,4.54.79,2.36,2.36,0,0,0,.48.05,1.62,1.62,0,0,0,.31,0c.22,0,.41,0,.63,0s.35,0,.55.05h1.18a31.37,31.37,0,0,0,9.72-1.6.16.16,0,0,0,.06,0,2.8,2.8,0,0,0,.38-.13c.65-.19,1.29-.46,1.95-.7l.17-.09c.29-.11.55-.24.84-.35l.22-.09a43.86,43.86,0,0,0,8.24-4.86l1.62-1.25c.31,1.36.68,2.83,1.14,4.36.42,1.38.88,2.68,1.34,3.88l1.1.2,1.09.2,1.08.19c.26.07.53.11.81.18a5,5,0,0,1,.53.11c1.36.28,2.72.57,4.05.9,2.13.5,4.21,1.05,6.3,1.64Z"
                                            id="NeckShadow" />
                                        <path class="cls-6"
                                            d="M186.2,119.75c-.16,5.19-3.47,10.44-7.18,12.37a7.27,7.27,0,0,1-1.57.61l-.12,0a4.9,4.9,0,0,1-2.05.06,5.18,5.18,0,0,1-2-.87l-.28-.21a7.82,7.82,0,0,1-1.12-1.11c-2.67-3.22-3.76-9.27-2-14.09a14.64,14.64,0,0,1,3.23-5,8.93,8.93,0,0,1,4.42-2.59,5.91,5.91,0,0,1,2.37,0C184.3,109.76,186.33,115.41,186.2,119.75Z"
                                            id="RightEar" />
                                        <path class="cls-8"
                                            d="M186.2,119.75c-.17,5.19-3.48,10.44-7.19,12.39a8.19,8.19,0,0,1-1.56.59l-.13,0a5.39,5.39,0,0,1-2,.07,5.26,5.26,0,0,1-2-.88l-.28-.22a7.26,7.26,0,0,1-1.12-1.1c-2.68-3.22-3.77-9.27-2-14.1a14.52,14.52,0,0,1,3.22-5,10,10,0,0,1,3.14-2.19,6.43,6.43,0,0,1,1.29-.4,5.9,5.9,0,0,1,2.37,0,2.25,2.25,0,0,1,.35.09C184.4,110.06,186.33,115.54,186.2,119.75Z"
                                            id="RightEarShadow" />
                                        <path class="cls-6"
                                            d="M84.42,130.62a7.53,7.53,0,0,1-1.4,1.32,5.35,5.35,0,0,1-2,.88,5.08,5.08,0,0,1-2-.07l-.12,0a7.27,7.27,0,0,1-1.57-.61c-3.71-1.93-7-7.18-7.18-12.37-.13-4.34,1.9-10,6.31-10.88,2.51-.5,4.9.71,6.79,2.61a14.64,14.64,0,0,1,3.23,5C88.19,121.35,87.09,127.4,84.42,130.62Z"
                                            id="LeftHear" />
                                        <path class="cls-6"
                                            d="M177.52,108.89c0,.4,0,.81-.07,1.23a78.87,78.87,0,0,1-4.49,21.6c-4.65,13.09-12.48,24.69-21.89,32.26l-1.62,1.25a43.86,43.86,0,0,1-8.24,4.86l-.22.09c-.29.11-.55.24-.84.35l-.17.09c-.66.24-1.3.51-1.95.7a2.8,2.8,0,0,1-.38.13.16.16,0,0,1-.06,0,31.37,31.37,0,0,1-9.72,1.6h-1.18c-.2,0-.37,0-.55-.05s-.41,0-.63,0a1.62,1.62,0,0,1-.31,0,2.36,2.36,0,0,1-.48-.05,28.59,28.59,0,0,1-4.54-.79l-.7-.19a2.1,2.1,0,0,0-.36-.09c-.26-.07-.52-.15-.76-.24l-.53-.16-.15-.06-.73-.24a0,0,0,0,0,0,0l-.68-.25a3.85,3.85,0,0,1-.52-.22.83.83,0,0,1-.22-.08l-.44-.18-.46-.2-.38-.17-.43-.2-.75-.35-.64-.33c-.17-.09-.35-.2-.52-.28a42.56,42.56,0,0,1-5.11-3.2c-.61-.44-1.21-.9-1.8-1.39a45.76,45.76,0,0,1-4.43-4,52.45,52.45,0,0,1-4.14-4.51v0a78.39,78.39,0,0,1-13.64-25.05A80.66,80.66,0,0,1,79,112.49c-.09-1-.15-2.06-.19-3.09a66.94,66.94,0,0,1,.32-10.88c.16-1.47.38-2.91.64-4.36A53.68,53.68,0,0,1,81,88.83,72.7,72.7,0,0,1,85.6,76.24C89.83,67.36,96,59,103.58,53.06c7-5.41,15.17-8.83,24.43-8.85,14.88-.07,27.21,8.68,35.83,20.45a71.61,71.61,0,0,1,8.61,15.49c.57,1.38,1.1,2.76,1.58,4.16a69.49,69.49,0,0,1,2.57,9.85c.24,1.4.46,2.83.61,4.25A70.47,70.47,0,0,1,177.52,108.89Z"
                                            id="Head" />
                                        <path class="cls-7"
                                            d="M162,112.1c-4.95-.14-12.8-4.32-17.17-7.28C132,96.13,122.06,79.6,114.89,79.27a5.89,5.89,0,0,0-2.26.33c-3.24,1.2-4.34,5.44-5.5,9.54-1.43,5.06-2.37,8.33-3,10.43-.07-5.76-.18-12,3.31-21,1.05-2.65,2.35-5.37,5-6.34,1.27-.48,5.43-1.49,16,8.29a62.63,62.63,0,0,1,7.63,8.25c8.61,11.25,8.7,12.12,13.07,16C155.42,110.38,159.23,110.1,162,112.1Z"
                                            id="HairsShadow" />
                                        <path class="cls-2"
                                            d="M194.12,170.77c-.46,1.23-9.21.9-16.82-3.88-8.86-5.57-15.48-16.77-15.85-28.46-.38-12.3,6.4-17.21,2.58-23.92a7.66,7.66,0,0,0-2-2.41h0c-2.74-2-6.55-1.72-12.8-7.28-4.37-3.89-4.46-4.76-13.07-16a62.63,62.63,0,0,0-7.63-8.25c-10.6-9.78-14.76-8.77-16-8.29-2.63,1-3.93,3.69-5,6.34-3.49,8.93-3.38,15.2-3.31,21,0,4.92.06,9.46-2.2,14.94-4.8,11.62-13.37,11.95-13.85,22.25-.29,5.79,2.36,7.06,2.21,14.61,0,2.54-.24,12-6.16,18.33-9.89,10.57-29.78,6.25-30,4s18.37-2,22-11.64c2.94-7.85-8-13.77-6.47-27.17.79-6.84,3.91-7.39,6.47-15.83,3.77-12.5-1.27-17.2-.41-28.75,1.49-20.18,19-37.37,34.69-44a49.45,49.45,0,0,1,15.19-3.88c20.88-1.78,36.58,11,42.34,16.49,4.21,4,14.76,14.08,16.49,28.77a63,63,0,0,1-1,19.73c-2.79,15.18-7.7,16.87-8.73,29.1-.41,4.87-1.2,14.34,4.21,22.3C184.78,167.44,194.6,169.44,194.12,170.77Z"
                                            id="Hairs" />
                                    </g>
                                </svg>
                            @endif

                            <div class="w-full">
                                @php
                                    $arrayName = explode(' ', $item->name);
                                @endphp
                                <h1 class="text-sm sm:text-xl text-colorlabel font-medium !leading-none text-center">
                                    {{ $arrayName[0] ?? '' }} {{ $arrayName[1] ?? '' }} </h1>
                                <p
                                    class="text-[10px] sm:text-xs pt-2 text-colorsubtitleform font-light !leading-none text-center">
                                    {{ $item->areawork->name }}</p>
                            </div>
                            {{-- {{ $item }} --}}
                        </div>
                    @endforeach
                </div>
            </section>
        @endif



        {{-- <section x-data="{
            openFaq1: false,
            openFaq2: false,
            openFaq3: false,
            openFaq4: false,
            openFaq5: false,
            openFaq6: false
        }" class="relative z-10 overflow-hidden pt-20 pb-12 lg:pt-24 lg:pb-24">
            <div class="w-full mx-auto mb-16 max-w-lg text-center lg:mb-20">
                <h2 class="text-primary mb-4 text-2xl md:text-3xl italic font-bold sm:text-[40px]/[48px]">
                   PREGUNTAS FRECUENTES</h2>
                <p class="text-base text-colorsubtitleform">
                    There are many variations of passages of Lorem Ipsum available
                    but the majority have suffered alteration in some form.
                </p>
            </div>
            <div class="w-full grid grid-cols-1 lg:grid-cols-2 gap-2 lg:gap-5 items-start">
                <div class="w-full p-1 sm:p-3 bg-fondominicard rounded-lg shadow-shadowminicard">
                    <button class="flex w-full text-left" @click="openFaq1 = !openFaq1">
                        <div
                            class="bg-fondominicard text-primary mr-5 flex h-10 w-full max-w-[40px] items-center justify-center rounded-lg">
                            <svg :class="openFaq1 && 'rotate-180'" width="22" height="22" viewBox="0 0 22 22"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11 15.675C10.7937 15.675 10.6219 15.6062 10.45 15.4687L2.54374 7.69998C2.23436 7.3906 2.23436 6.90935 2.54374 6.59998C2.85311 6.2906 3.33436 6.2906 3.64374 6.59998L11 13.7844L18.3562 6.53123C18.6656 6.22185 19.1469 6.22185 19.4562 6.53123C19.7656 6.8406 19.7656 7.32185 19.4562 7.63123L11.55 15.4C11.3781 15.5719 11.2062 15.675 11 15.675Z"
                                    fill="currentColor" />
                            </svg>
                        </div>
                        <div class="w-full flex-1">
                            <h4 class="mt-1 text-sm sm:text-lg font-semibold text-primary">
                                How long we deliver your first blog post?
                            </h4>
                        </div>
                    </button>
                    <div x-show="openFaq1" class="pl-[62px]">
                        <p class="py-2 leading-none text-xs sm:text-sm text-colorsubtitleform">
                            It takes 2-3 weeks to get your first blog post ready. That
                            includes the in-depth research & creation of your monthly
                            content marketing strategy that we do before writing your
                            first blog post, Ipsum available .
                        </p>
                    </div>
                </div>
                <div class="w-full p-1 sm:p-3 bg-fondominicard rounded-lg shadow-shadowminicard">
                    <button class="flex w-full text-left" @click="openFaq2 = !openFaq2">
                        <div
                            class="bg-fondominicard text-primary mr-5 flex h-10 w-full max-w-[40px] items-center justify-center rounded-lg">
                            <svg :class="openFaq2 && 'rotate-180'" width="22" height="22" viewBox="0 0 22 22"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11 15.675C10.7937 15.675 10.6219 15.6062 10.45 15.4687L2.54374 7.69998C2.23436 7.3906 2.23436 6.90935 2.54374 6.59998C2.85311 6.2906 3.33436 6.2906 3.64374 6.59998L11 13.7844L18.3562 6.53123C18.6656 6.22185 19.1469 6.22185 19.4562 6.53123C19.7656 6.8406 19.7656 7.32185 19.4562 7.63123L11.55 15.4C11.3781 15.5719 11.2062 15.675 11 15.675Z"
                                    fill="currentColor" />
                            </svg>
                        </div>
                        <div class="w-full flex-1">
                            <h4 class="mt-1 text-sm sm:text-lg font-semibold text-primary">
                                How long we deliver your first blog post?
                            </h4>
                        </div>
                    </button>
                    <div x-show="openFaq2" class="pl-[62px]">
                        <p class="py-2 leading-none text-xs sm:text-sm text-colorsubtitleform">
                            It takes 2-3 weeks to get your first blog post ready. That
                            includes the in-depth research & creation of your monthly
                            content marketing strategy that we do before writing your
                            first blog post, Ipsum available .
                        </p>
                    </div>
                </div>
                <div class="w-full p-1 sm:p-3 bg-fondominicard rounded-lg shadow-shadowminicard">
                    <button class="flex w-full text-left" @click="openFaq3 = !openFaq3">
                        <div
                            class="bg-fondominicard text-primary mr-5 flex h-10 w-full max-w-[40px] items-center justify-center rounded-lg">
                            <svg :class="openFaq3 && 'rotate-180'" width="22" height="22" viewBox="0 0 22 22"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11 15.675C10.7937 15.675 10.6219 15.6062 10.45 15.4687L2.54374 7.69998C2.23436 7.3906 2.23436 6.90935 2.54374 6.59998C2.85311 6.2906 3.33436 6.2906 3.64374 6.59998L11 13.7844L18.3562 6.53123C18.6656 6.22185 19.1469 6.22185 19.4562 6.53123C19.7656 6.8406 19.7656 7.32185 19.4562 7.63123L11.55 15.4C11.3781 15.5719 11.2062 15.675 11 15.675Z"
                                    fill="currentColor" />
                            </svg>
                        </div>
                        <div class="w-full flex-1">
                            <h4 class="mt-1 text-sm sm:text-lg font-semibold text-primary">
                                How long we deliver your first blog post?
                            </h4>
                        </div>
                    </button>
                    <div x-show="openFaq3" class="pl-[62px]">
                        <p class="py-2 leading-none text-xs sm:text-sm text-colorsubtitleform">
                            It takes 2-3 weeks to get your first blog post ready. That
                            includes the in-depth research & creation of your monthly
                            content marketing strategy that we do before writing your
                            first blog post, Ipsum available .
                        </p>
                    </div>
                </div>
                <div class="w-full p-1 sm:p-3 bg-fondominicard rounded-lg shadow-shadowminicard">
                    <button class="flex w-full text-left" @click="openFaq4 = !openFaq4">
                        <div
                            class="bg-fondominicard text-primary mr-5 flex h-10 w-full max-w-[40px] items-center justify-center rounded-lg">
                            <svg :class="openFaq4 && 'rotate-180'" width="22" height="22" viewBox="0 0 22 22"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11 15.675C10.7937 15.675 10.6219 15.6062 10.45 15.4687L2.54374 7.69998C2.23436 7.3906 2.23436 6.90935 2.54374 6.59998C2.85311 6.2906 3.33436 6.2906 3.64374 6.59998L11 13.7844L18.3562 6.53123C18.6656 6.22185 19.1469 6.22185 19.4562 6.53123C19.7656 6.8406 19.7656 7.32185 19.4562 7.63123L11.55 15.4C11.3781 15.5719 11.2062 15.675 11 15.675Z"
                                    fill="currentColor" />
                            </svg>
                        </div>
                        <div class="w-full flex-1">
                            <h4 class="mt-1 text-sm sm:text-lg font-semibold text-primary">
                                How long we deliver your first blog post?
                            </h4>
                        </div>
                    </button>
                    <div x-show="openFaq4" class="pl-[62px]">
                        <p class="py-2 leading-none text-xs sm:text-sm text-colorsubtitleform">
                            It takes 2-3 weeks to get your first blog post ready. That
                            includes the in-depth research & creation of your monthly
                            content marketing strategy that we do before writing your
                            first blog post, Ipsum available .
                        </p>
                    </div>
                </div>
                <div class="w-full p-1 sm:p-3 bg-fondominicard rounded-lg shadow-shadowminicard">
                    <button class="flex w-full text-left" @click="openFaq5 = !openFaq5">
                        <div
                            class="bg-fondominicard text-primary mr-5 flex h-10 w-full max-w-[40px] items-center justify-center rounded-lg">
                            <svg :class="openFaq5 && 'rotate-180'" width="22" height="22" viewBox="0 0 22 22"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11 15.675C10.7937 15.675 10.6219 15.6062 10.45 15.4687L2.54374 7.69998C2.23436 7.3906 2.23436 6.90935 2.54374 6.59998C2.85311 6.2906 3.33436 6.2906 3.64374 6.59998L11 13.7844L18.3562 6.53123C18.6656 6.22185 19.1469 6.22185 19.4562 6.53123C19.7656 6.8406 19.7656 7.32185 19.4562 7.63123L11.55 15.4C11.3781 15.5719 11.2062 15.675 11 15.675Z"
                                    fill="currentColor" />
                            </svg>
                        </div>
                        <div class="w-full flex-1">
                            <h4 class="mt-1 text-sm sm:text-lg font-semibold text-primary">
                                How long we deliver your first blog post?
                            </h4>
                        </div>
                    </button>
                    <div x-show="openFaq5" class="pl-[62px]">
                        <p class="py-2 leading-none text-xs sm:text-sm text-colorsubtitleform">
                            It takes 2-3 weeks to get your first blog post ready. That
                            includes the in-depth research & creation of your monthly
                            content marketing strategy that we do before writing your
                            first blog post, Ipsum available .
                        </p>
                    </div>
                </div>
                <div class="w-full p-1 sm:p-3 bg-fondominicard rounded-lg shadow-shadowminicard">
                    <button class="flex w-full text-left" @click="openFaq6 = !openFaq6">
                        <div
                            class="bg-fondominicard text-primary mr-5 flex h-10 w-full max-w-[40px] items-center justify-center rounded-lg">
                            <svg :class="openFaq6 && 'rotate-180'" width="22" height="22" viewBox="0 0 22 22"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11 15.675C10.7937 15.675 10.6219 15.6062 10.45 15.4687L2.54374 7.69998C2.23436 7.3906 2.23436 6.90935 2.54374 6.59998C2.85311 6.2906 3.33436 6.2906 3.64374 6.59998L11 13.7844L18.3562 6.53123C18.6656 6.22185 19.1469 6.22185 19.4562 6.53123C19.7656 6.8406 19.7656 7.32185 19.4562 7.63123L11.55 15.4C11.3781 15.5719 11.2062 15.675 11 15.675Z"
                                    fill="currentColor" />
                            </svg>
                        </div>
                        <div class="w-full flex-1">
                            <h4 class="mt-1 text-sm sm:text-lg font-semibold text-primary">
                                How long we deliver your first blog post?
                            </h4>
                        </div>
                    </button>
                    <div x-show="openFaq6" class="pl-[62px]">
                        <p class="py-2 leading-none text-xs sm:text-sm text-colorsubtitleform">
                            It takes 2-3 weeks to get your first blog post ready. That
                            includes the in-depth research & creation of your monthly
                            content marketing strategy that we do before writing your
                            first blog post, Ipsum available .
                        </p>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 z-[-1]">
                <svg width="1440" height="886" viewBox="0 0 1440 886" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.5"
                        d="M193.307 -273.321L1480.87 1014.24L1121.85 1373.26C1121.85 1373.26 731.745 983.231 478.513 729.927C225.976 477.317 -165.714 85.6993 -165.714 85.6993L193.307 -273.321Z"
                        fill="url(#paint0_linear)" />
                    <defs>
                        <linearGradient id="paint0_linear" x1="1308.65" y1="1142.58" x2="602.827"
                            y2="-418.681" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#3056D3" stop-opacity="0.36" />
                            <stop offset="1" stop-color="#F5F2FD" stop-opacity="0" />
                            <stop offset="1" stop-color="#F5F2FD" stop-opacity="0.096144" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>
        </section> --}}

    </div>
</x-app-layout>
