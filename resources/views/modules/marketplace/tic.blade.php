<x-app-layout>
    <div class="w-full">
        <picture>
            <source srcset="{{ $data->image }}">
            <img src="{{ $data->image }}" alt="{{ $data->image }}"
                class="w-full h-full object-center sm:object-right-bottom max-h-[28rem] min-h-24 sm:min-h-36 object-cover md:object-cover">
        </picture>
    </div>
    <div class="contenedor">
        <div class="relative flex flex-col gap-2 lg:gap-0 lg:flex-row overflow-hidden py-5" x-data="{
            activeTab: parseInt(localStorage.getItem('activeTab')) || 1,
            setActiveTab(index) {
                this.activeTab = index;
                localStorage.setItem('activeTab', index.toString());
            }
        }">
            <div class="w-full lg:w-40 lg:flex-shrink-0">
                <div role="tablist" class="mx-auto flex flex-wrap lg:flex-col gap-2 justify-center"
                    @keydown.right.prevent.stop="$focus.wrap().next()" @keydown.left.prevent.stop="$focus.wrap().prev()"
                    @keydown.home.prevent.stop="$focus.first()" @keydown.end.prevent.stop="$focus.last()">
                    <button id="tab-1" class="button-tic"
                        :class="activeTab === 1 ? 'bg-next-500 border-next-500 text-white' : 'text-colorsubtitleform'"
                        :tabindex="activeTab === 1 ? 0 : -1" :aria-selected="activeTab === 1" aria-controls="tabpanel-1"
                        @click="setActiveTab(1)" @focus="setActiveTab(1)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z">
                            </path>
                        </svg>
                        <span class="block leading-none">DESARROLLO DE SOFTWARE</span>
                    </button>

                    <button id="tab-2" class="button-tic"
                        :class="activeTab === 2 ? 'bg-next-500 border-next-500 text-white' : 'text-colorsubtitleform'"
                        :tabindex="activeTab === 2 ? 0 : -1" :aria-selected="activeTab === 2" aria-controls="tabpanel-2"
                        @click="setActiveTab(2)" @focus="setActiveTab(2)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z">
                            </path>
                        </svg>
                        <span class="block leading-none">SOPORTE TÉCNICO</span>
                    </button>

                    <button id="tab-3" class="button-tic"
                        :class="activeTab === 3 ? 'bg-next-500 border-next-500 text-white' : 'text-colorsubtitleform'"
                        :tabindex="activeTab === 3 ? 0 : -1" :aria-selected="activeTab === 3" aria-controls="tabpanel-3"
                        @click="setActiveTab(3)" @focus="setActiveTab(3)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z">
                            </path>
                        </svg>
                        <span class="block leading-none">SEGURIDAD ELECTRÓNICA</span>
                    </button>

                    <button id="tab-4" class="button-tic"
                        :class="activeTab === 4 ? 'bg-next-500 border-next-500 text-white' : 'text-colorsubtitleform'"
                        :tabindex="activeTab === 4 ? 0 : -1" :aria-selected="activeTab === 4"
                        aria-controls="tabpanel-4" @click="setActiveTab(4)" @focus="setActiveTab(4)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z">
                            </path>
                        </svg>
                        <span class="block leading-none">CENTRO DE DATOS</span>
                    </button>

                    <button id="tab-5" class="button-tic"
                        :class="activeTab === 5 ? 'bg-next-500 border-next-500 text-white' : 'text-colorsubtitleform'"
                        :tabindex="activeTab === 5 ? 0 : -1" :aria-selected="activeTab === 5"
                        aria-controls="tabpanel-5" @click="setActiveTab(5)" @focus="setActiveTab(5)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z">
                            </path>
                        </svg>
                        <span class="block leading-none max-w-full truncate">REDES Y <br> TELECOMUNICACIONES</span>
                    </button>

                    <button id="tab-6" class="button-tic"
                        :class="activeTab === 6 ? 'bg-next-500 border-next-500 text-white' : 'text-colorsubtitleform'"
                        :tabindex="activeTab === 6 ? 0 : -1" :aria-selected="activeTab === 6"
                        aria-controls="tabpanel-6" @click="setActiveTab(6)" @focus="setActiveTab(6)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z">
                            </path>
                        </svg>
                        <span class="block leading-none">ELECTRICIDAD Y AIRE ACONDICIONADO</span>
                    </button>
                </div>
            </div>
            <div class="w-full relative flex-1 max-w-full rounded-lg">
                <article id="tabpanel-1" class="w-full" role="tabpanel" tabindex="0" aria-labelledby="tab-1"
                    x-show="activeTab === 1"
                    x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                    x-transition:enter-start="opacity-0 -translate-y-8 lg:translate-y-0 lg:-translate-x-8"
                    x-transition:enter-end="opacity-100 translate-y-0 lg:translate-x-0"
                    x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                    x-transition:leave-start="opacity-100 translate-y-0 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-y-12 lg:translate-y-0 lg:translate-x-12">

                    <div class="w-full bg-gray-700 py-5 pb-8 rounded-lg md:rounded-3xl">
                        <div class="w-full max-w-[95%] md:max-w-[85%] mx-auto">
                            <h1 class="md:p-3 text-white text-center text-sm md:text-xl font-semibold leading-none">
                                ACELERA EL DESARROLLO DE PRODUCTOS DIGITALES CON NEXT</h1>
                            <p class="py-3 sm:mt-5 text-white text-justify text-xs sm:text-sm md:text-lg font-normal">
                                En Next technologies, ofrecemos soluciones de desarrollo de software personalizadas para
                                satisfacer las necesidades específicas de tu negocio. Nuestro enfoque se basa en la
                                colaboración y la comprensión profunda de tus objetivos, garantizando que cada proyecto
                                se entregue a tiempo y dentro del presupuesto.
                            </p>

                            <div class="w-full relative mt-5 rounded-lg md:rounded-3xl overflow-hidden">
                                <picture>
                                    <source srcset="{{ $data->desarrollo->image }}">
                                    <img src="{{ $data->desarrollo->image }}" alt="{{ $data->desarrollo->image }}"
                                        class="w-full h-full max-h-[28rem] object-scale-down sm:object-cover rounded-lg overflow-hidden">
                                </picture>
                                {{-- <div
                                    class="absolute w-full max-w-full left-0 bottom-0 sm:h-[165%] sm:top-[50%] sm:-translate-y-[50%] sm:-left-[53%] sm:rounded-r-[50%] flex justify-center items-center p-2 sm:p-3 bg-next-500 bg-opacity-70 sm:bg-opacity-100 rounded-t-xl">
                                    <h1
                                        class="text-xs xs:text-lg sm:text-2xl lg:text-3xl font-medium sm:pl-[55%] text-white w-full !leading-none">
                                        Transformamos Ideas en Soluciones: La revolución del Desarrollo de Software
                                    </h1>
                                </div> --}}
                            </div>
                        </div>
                    </div>

                    <div class="w-full bg-body py-5">
                        <div class="w-full max-w-[95%] md:max-w-[85%] mx-auto">
                            <h1 class="md:p-3 text-primary text-center text-sm md:text-xl font-semibold">
                                ¿QUÉ OFRECEMOS?</h1>

                            <ul
                                class="text-xs sm:text-sm w-full space-y-3 md:space-y-5 block text-colorsubtitleform py-3 md:mt-5">
                                <li>
                                    <b>Desarrollo de Aplicaciones Personalizadas:</b> Creamos aplicaciones adaptadas a
                                    tus requerimientos, ya sea para dispositivos móviles, web o escritorio. Nos
                                    aseguramos de que cada solución sea intuitiva y fácil de usar.
                                </li>
                                <li>
                                    <b>Integración de Sistemas:</b> Ayudamos a integrar diferentes plataformas y
                                    sistemas para optimizar tus procesos y mejorar la eficiencia operativa de tu
                                    empresa.
                                </li>
                                <li>
                                    <b>Mantenimiento y Soporte:</b> Ofrecemos servicios de mantenimiento continuo para
                                    garantizar que tu software funcione sin problemas, así como actualizaciones
                                    periódicas para adaptarse a las nuevas necesidades del mercado.
                                </li>
                                <li>
                                    <b>Consultoría en Desarrollo de Software:</b> Nuestros expertos te asesorarán en la
                                    selección de tecnologías y metodologías adecuadas para maximizar el rendimiento y la
                                    efectividad de tus proyectos.
                                </li>
                            </ul>

                            <h1 class="md:p-3 text-primary text-center text-sm md:text-xl font-semibold md:mt-5">
                                ¿POR QUÉ ELEGIRNOS?</h1>

                            <ul
                                class="text-xs sm:text-sm w-full block space-y-3 md:space-y-5 text-colorsubtitleform py-3 md:mt-5">
                                <li>
                                    <b>Experiencia:</b> Contamos con un equipo de desarrolladores con amplia experiencia
                                    en diversas tecnologías y sectores.
                                </li>
                                <li>
                                    <b>Calidad:</b> Priorizamos la calidad en cada fase del desarrollo, desde el diseño
                                    hasta la implementación y el mantenimiento.
                                </li>
                                <li>
                                    <b>Adaptabilidad:</b> Nos ajustamos a las necesidades de tu negocio, garantizando
                                    soluciones escalables que crecen contigo.
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="w-full bg-gray-700 py-3 md:py-5 rounded-2xl xs:rounded-3xl">
                        <div
                            class="w-full max-w-[95%] md:max-w-[85%] mx-auto grid grid-cols-1 xs:grid-cols-2 gap-3 md:gap-5">
                            @foreach ($data->desarrollo->content as $item)
                                <div
                                    class="w-full flex flex-col gap-1 md:gap-5 bg-fondominicard rounded-2xl md:rounded-3xl p-2 sm:p-3 md:p-5 py-3 md:py-8">
                                    <h1
                                        class="text-lg xs:text-xl md:text-3xl text-center font-semibold text-colorlabel !leading-none">
                                        {{ $item->title }}</h1>
                                    <picture>
                                        <source srcset="{{ $item->url }}">
                                        <img src="{{ $item->url }}"
                                            alt="{{ asset('images/home/recursos/recurso_1.png') }}"
                                            class="w-full h-full min-h-28 object-cover rounded-lg">
                                    </picture>
                                    <p
                                        class="text-xs sm:text-sm text-justify sm:text-center text-colorsubtitleform font-normal">
                                        {{ $item->description }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </article>

                <article id="tabpanel-2" class="w-full" role="tabpanel" tabindex="0" aria-labelledby="tab-2"
                    x-show="activeTab === 2"
                    x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                    x-transition:enter-start="opacity-0 -translate-y-8 lg:translate-y-0 lg:-translate-x-8"
                    x-transition:enter-end="opacity-100 translate-y-0 lg:translate-x-0"
                    x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                    x-transition:leave-start="opacity-100 translate-y-0 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-y-12 lg:translate-y-0 lg:translate-x-12">
                    {{-- <h1 class="heading">Card Flip</h1> --}}
                    <div class="w-full grid grid-cols-2 md:grid-cols-3 gap-1 md:gap-5">
                        @foreach ($data->soporte->content as $item)
                            <div class="card-solutions security" :key="$i">
                                <picture>
                                    <source srcset="{{ $item->url }}">
                                    <img src="{{ $item->url }}" alt="{{ $item->url }}" class="">
                                </picture>
                                <div class="front-card-solutions">
                                    <div class="absolute bottom-[8%] xl:bottom-[12%] left-0 w-full">
                                        <h1
                                            class="text-lg xs:text-lg sm:text-2xl !leading-none text-center text-colorlabel font-normal">
                                            {{ $item->title }}</h1>
                                    </div>
                                </div>
                                <div class="back-card-solutions">
                                    <p class="block w-full text-sm xs:text-lg md:text-xl text-center">
                                        {{ $item->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="w-full mt-3 md:mt-5">
                        <picture>
                            <source srcset="{{ $data->soporte->image }}">
                            <img src="{{ $data->soporte->image }}" alt="{{ $data->soporte->image }}"
                                class="w-full h-full object-scale-down md:object-cover rounded-lg">
                        </picture>
                    </div>

                    <div class="w-full max-w-[95%] md:max-w-[85%] mx-auto md:mt-5">
                        <h1 class="p-3 text-colorlabel text-center text-sm :md:text-xl font-semibold leading-none">
                            ¿COMO SABER SI TU EQUIPO TIENE GARANTÍA?</h1>
                        <ul
                            class="w-full py-3 md:mt-8 list-disc space-y-5 text-colorlabel text-xs md:text-sm text-justify">
                            <li>
                                <b>Revisa la garantía:</b> Lee los términos y condiciones de la garantía para entender
                                qué cubre y qué no. Verifica la duración y si hay algún requisito específico.
                            </li>
                            <li>
                                <b>Contacta al fabricante o vendedor:</b> Busca la información de contacto en la
                                documentación de la garantía. Llama o envía un correo electrónico para informar sobre el
                                problema.
                            </li>
                            <li>
                                <b>Prepara la documentación:</b> Ten a mano la factura de compra, el comprobante de
                                garantía y cualquier otra información relevante que pueda ayudar a resolver el problema.
                            </li>
                            <li>
                                <b>Describe el problema:</b> Explica claramente el problema que estás experimentando.
                                Asegúrate de mencionar cualquier error o comportamiento inusual del equipo.
                            </li>
                            <li>
                                <b>Sigue las instrucciones:</b> El fabricante o vendedor te dará instrucciones sobre
                                cómo proceder. Esto puede incluir enviar el equipo para reparación o llevarlo a un
                                centro de servicio.
                            </li>
                            <li>
                                <b>Guarda copias:</b> Mantén copias de toda la correspondencia y documentación
                                relacionada con la reclamación de la garantía.
                            </li>
                            <li>
                                <b>Sé paciente:</b> El proceso de reparación o reemplazo puede tomar tiempo, así que ten
                                paciencia mientras se resuelve el problema.
                            </li>
                        </ul>
                    </div>
                </article>

                <article id="tabpanel-3" class="w-full" role="tabpanel" tabindex="0" aria-labelledby="tab-3"
                    x-show="activeTab === 3"
                    x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                    x-transition:enter-start="opacity-0 -translate-y-8 lg:translate-y-0 lg:-translate-x-8"
                    x-transition:enter-end="opacity-100 translate-y-0 lg:translate-x-0"
                    x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                    x-transition:leave-start="opacity-100 translate-y-0 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-y-12 lg:translate-y-0 lg:translate-x-12">
                    <div class="w-full grid grid-cols-2 xs:grid-cols-2 md:grid-cols-3 gap-1 md:gap-5">
                        @foreach ($data->seguridad->content as $item)
                            <div class="card-solutions security" :key="$i">
                                <picture>
                                    <source srcset="{{ $item->url }}">
                                    <img src="{{ $item->url }}" alt="{{ $item->url }}" class="">
                                </picture>
                                <div class="front-card-solutions">
                                    <div class="absolute bottom-[8%] xl:bottom-[12%] left-0 w-full">
                                        <h1
                                            class="text-lg sm:text-xl md:text-2xl !leading-none text-center text-colorlabel font-normal">
                                            {{ $item->title }}</h1>
                                    </div>
                                </div>
                                <div class="back-card-solutions">
                                    <p class="block w-full text-sm xs:text-lg md:text-xl text-center">
                                        {{ $item->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="w-full mt-3 md:mt-5">
                        <picture>
                            <source srcset="{{ $data->seguridad->image }}">
                            <img src="{{ $data->seguridad->image }}" alt="{{ $data->seguridad->image }}"
                                class="w-full h-full min-h-28 object-center object-cover rounded-lg">
                        </picture>
                    </div>
                </article>

                <article id="tabpanel-4" class="w-full" role="tabpanel" tabindex="0" aria-labelledby="tab-4"
                    x-show="activeTab === 4"
                    x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                    x-transition:enter-start="opacity-0 -translate-y-8 lg:translate-y-0 lg:-translate-x-8"
                    x-transition:enter-end="opacity-100 translate-y-0 lg:translate-x-0"
                    x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                    x-transition:leave-start="opacity-100 translate-y-0 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-y-12 lg:translate-y-0 lg:translate-x-12">

                    <div class="w-full bg-gray-700 rounded-lg md:rounded-3xl py-5">
                        <div class="w-full max-w-[95%] md:max-w-[85%] mx-auto">
                            <h1 class="md:p-3 text-white text-center text-sm md:text-xl font-semibold leading-none">
                                OPTIMIZA TU INFRAESTRUCTURA IT CON NUESTRO
                                SERVICIO DE CENTRO DE DATOS
                            </h1>
                            <p class="py-3 sm:mt-5 text-white text-justify text-xs sm:text-sm md:text-lg font-normal">
                                En Next, ofrecemos un servicio de centro de datos diseñado para optimizar la
                                infraestructura
                                IT de tu negocio. Nuestra solución combina tecnología de punta con seguridad avanzada,
                                garantizando un rendimiento eficiente y confiable. Con capacidad de escalabilidad y
                                soporte
                                técnico 24/7,
                                ayudamos a las empresas a reducir costos y a centrarse en su crecimiento. Confía en Next
                                para impulsar tu transformación
                                digital y conectar tu futuro. ¡Descubre cómo podemos llevar tu negocio al siguiente
                                nivel!
                            </p>

                            <div class="w-full relative mt-5 overflow-hidden">
                                <picture>
                                    <source srcset="{{ $data->datacenter->image }}">
                                    <img src="{{ $data->datacenter->image }}" alt="{{ $data->datacenter->image }}"
                                        class="w-full h-full object-cover rounded-lg">
                                </picture>
                            </div>
                        </div>
                    </div>

                    <div class="w-full bg-body py-5">
                        <div class="w-full max-w-[95%] md:max-w-[85%] mx-auto">
                            <h1 class="md:p-3 text-primary text-center text-sm md:text-xl font-semibold">
                                ¿QUÉ OFRECEMOS?</h1>

                            <ol
                                class="w-full list-datacenter text-xs sm:text-sm text-justify list-decimal list-inside space-y-3 md:space-y-5 block text-colorlabel py-3 md:mt-5">
                                <li>
                                    <b class="text-xs md:text-sm">Infraestructura Física</b>
                                    <ul class="md:space-y-5">
                                        <li>Edificio y Diseño: Un centro de datos está construido con consideraciones
                                            específicas para seguridad, eficiencia energética y redundancia. Suelen
                                            tener múltiples niveles de seguridad física.
                                        </li>
                                        <li>
                                            Equipamiento: Incluye servidores, sistemas de almacenamiento, dispositivos
                                            de red y unidades de refrigeración, todos configurados para funcionar de
                                            manera óptima.
                                        </li>
                                    </ul>
                                </li>
                                <li class="md:space-y-3">
                                    <b class="text-xs md:text-sm"> Conectividad y Redes</b>
                                    <ul class="space-y-5">
                                        <li>
                                            Conexiones de Alta Velocidad: Acceso a internet robusto y redundante, con
                                            múltiples proveedores para asegurar la conectividad continua.
                                        </li>
                                        <li>
                                            Redes Internas: Sistemas de red bien diseñados para permitir la comunicación
                                            rápida y segura entre servidores y otros dispositivos.
                                        </li>
                                    </ul>
                                </li>
                                <li class="md:space-y-3">
                                    <b class="text-xs md:text-sm">Seguridad</b>
                                    <ul class="space-y-5">
                                        <li>
                                            Seguridad Física: Acceso controlado mediante tarjetas de acceso, cámaras de
                                            vigilancia y vigilancia las 24 horas.
                                        </li>
                                        <li>
                                            Seguridad Informática: Implementación de firewalls, sistemas de detección de
                                            intrusos y protocolos de cifrado
                                            para proteger los datos contra accesos no autorizados.
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <b>Monitoreo y Gestión</b>
                                    <ul class="md:space-y-5">
                                        <li>
                                            Sistemas de Monitoreo: Herramientas que permiten supervisar en tiempo real
                                            el estado de los equipos, el uso de energía y las condiciones ambientales.
                                        </li>
                                        <li>
                                            Gestión Proactiva: Administradores que gestionan la infraestructura,
                                            realizando mantenimiento preventivo y solucionando problemas antes de que
                                            afecten el servicio.
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <b>Recuperación ante Desastres</b>
                                    <ul class="md:space-y-5">
                                        <li>
                                            Planes de Respaldo: Estrategias para realizar copias de seguridad de datos y
                                            aplicaciones, asegurando su recuperación en caso de fallos.
                                        </li>
                                        <li>
                                            Centros de Recuperación: Instalaciones secundarias que pueden ser activadas
                                            rápidamente para restaurar servicios en caso de un desastre en el centro
                                            principal.
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <b>Tipos de Servicios Ofrecidos</b>
                                    <ul class="md:space-y-5">
                                        <li>
                                            Colocación: Espacio físico donde los clientes pueden colocar sus propios
                                            servidores, mientras el centro se encarga de la infraestructura básica.
                                        </li>
                                        <li>
                                            Alojamiento (Hosting): El proveedor gestiona los servidores y ofrece espacio
                                            para aplicaciones y datos. Servicios en la Nube: Provisión de recursos de
                                            computación y almacenamiento
                                            a través de internet, permitiendo a los clientes escalar según sus
                                            necesidades.
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <b>Escalabilidad y Flexibilidad</b>
                                    <ul>
                                        <li>
                                            Los servicios de centros de datos permiten a las empresas escalar
                                            rápidamente sus recursos, adaptándose a cambios en la demanda sin la
                                            necesidad de realizar grandes inversiones iniciales en hardware.
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <b>Cumplimiento Normativo</b>
                                    <ul>
                                        <li>
                                            Muchos centros de datos cumplen con regulaciones específicas del sector
                                            (como HIPAA, GDPR, etc.), lo que garantiza que la gestión de datos sea
                                            conforme a la ley.
                                        </li>
                                    </ul>
                                </li>
                            </ol>
                        </div>
                    </div>
                </article>

                <article id="tabpanel-5" class="w-full" role="tabpanel" tabindex="0" aria-labelledby="tab-5"
                    x-show="activeTab === 5"
                    x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                    x-transition:enter-start="opacity-0 -translate-y-8 lg:translate-y-0 lg:-translate-x-8"
                    x-transition:enter-end="opacity-100 translate-y-0 lg:translate-x-0"
                    x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                    x-transition:leave-start="opacity-100 translate-y-0 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-y-12 lg:translate-y-0 lg:translate-x-12">
                    <div class="w-full grid grid-cols-2 xs:grid-cols-2 md:grid-cols-3 gap-1 md:gap-5">
                        @foreach ($data->redes->content as $item)
                            <div class="card-solutions security" :key="$i">
                                <picture>
                                    <source srcset="{{ $item->url }}">
                                    <img src="{{ $item->url }}" alt="{{ $item->url }}" class="">
                                </picture>
                                <div class="front-card-solutions">
                                    <div class="absolute bottom-[8%] xl:bottom-[12%] left-0 w-full">
                                        <h1
                                            class="text-lg sm:text-xl md:text-2xl !leading-none text-center text-colorlabel font-normal">
                                            {{ $item->title }}</h1>
                                    </div>
                                </div>
                                <div class="back-card-solutions">
                                    <p class="block w-full text-sm xs:text-lg md:text-xl text-center">
                                        {{ $item->description }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="w-full mt-3 md:mt-5">
                        <picture>
                            <source srcset="{{ $data->redes->image }}">
                            <img src="{{ $data->redes->image }}" alt="{{ $data->redes->image }}"
                                class="w-full h-full object-cover rounded-lg md:rounded-3xl">
                        </picture>
                    </div>
                </article>

                <article id="tabpanel-6" class="w-full" role="tabpanel" tabindex="0" aria-labelledby="tab-6"
                    x-show="activeTab === 6"
                    x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                    x-transition:enter-start="opacity-0 -translate-y-8 lg:translate-y-0 lg:-translate-x-8"
                    x-transition:enter-end="opacity-100 translate-y-0 lg:translate-x-0"
                    x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                    x-transition:leave-start="opacity-100 translate-y-0 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-y-12 lg:translate-y-0 lg:translate-x-12">

                    <div class="w-full bg-gray-700 py-5 pb-8 rounded-lg md:rounded-3xl">
                        <div class="w-full max-w-[95%] md:max-w-[85%] mx-auto">
                            <h1 class="md:p-3 text-white text-center text-sm md:text-xl font-semibold leading-none">
                                SOLUCIONES INTEGRALES EN ELECTRICIDAD Y AIRE ACONDICIONADO PARA TU COMODIDAD
                            </h1>
                            <p class="py-3 sm:mt-5 text-white text-justify text-xs sm:text-sm md:text-lg font-normal">
                                Nuestras soluciones integrales en electricidad y aire acondicionado están diseñadas para
                                ofrecerte el máximo confort en tu hogar o negocio.
                            </p>

                            <div class="w-full relative mt-3 overflow-hidden">
                                <picture>
                                    <source srcset="{{ $data->electricidad->image }}">
                                    <img src="{{ $data->electricidad->image }}"
                                        alt="{{ $data->electricidad->image }}"
                                        class="w-full h-full max-h-[28rem] object-scale-down sm:object-cover rounded-lg overflow-hidden">
                                </picture>
                            </div>
                        </div>
                    </div>

                    <div class="w-full bg-body py-5">
                        <div class="w-full max-w-[95%] md:max-w-[85%] mx-auto">
                            <h1 class="md:p-3 text-primary text-center text-sm md:text-xl font-semibold">
                                ¿QUÉ OFRECEMOS?</h1>

                            <ul
                                class="w-full text-xs md:text-sm space-y-3 md:space-y-5 block text-colorsubtitleform py-3 text-justify">
                                <li>
                                    Nuestro servicio de electricidad y aire acondicionado ofrece soluciones
                                    completas
                                    para garantizar un
                                    ambiente seguro y confortable en tu hogar o empresa. Nos especializamos en la
                                    instalación, mantenimiento y
                                    reparación de sistemas eléctricos, asegurando que todos los componentes
                                    funcionen de
                                    manera eficiente y
                                    cumpliendo con las normativas de seguridad.

                                </li>
                                <li>
                                    Además, proporcionamos servicios de instalación y mantenimiento de sistemas de
                                    aire
                                    acondicionado,
                                    optimizando el rendimiento y la calidad del aire en tus espacios. Utilizamos
                                    tecnologías modernas para
                                    maximizar la eficiencia energética, lo que no solo mejora tu confort, sino que
                                    también contribuye al ahorro en
                                    tus facturas de energía. Nuestro equipo de expertos está comprometido en brindar
                                    un
                                    servicio rápido y
                                    confiable, adaptándose a tus necesidades específicas y garantizando la
                                    satisfacción
                                    del cliente.
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="w-full bg-gray-700 py-3 md:py-5 rounded-2xl xs:rounded-3xl">
                        <div
                            class="w-full max-w-[95%] md:max-w-[85%] mx-auto grid grid-cols-1 xs:grid-cols-2 gap-3 md:gap-5">
                            @foreach ($data->electricidad->content as $item)
                                <div
                                    class="w-full flex flex-col gap-1 md:gap-5 bg-fondominicard rounded-2xl md:rounded-3xl p-2 sm:p-3 md:p-5 py-3 md:py-8">
                                    <h1
                                        class="text-lg xs:text-xl md:text-3xl text-center font-semibold text-colorlabel !leading-none">
                                        {{ $item->title }}</h1>
                                    <picture>
                                        <source srcset="{{ $item->url }}">
                                        <img src="{{ $item->url }}" alt="{{ $item->url }}"
                                            class="w-full h-full min-h-28 object-cover rounded-lg">
                                    </picture>
                                    <p
                                        class="text-xs sm:text-sm text-justify sm:text-center text-colorsubtitleform font-normal">
                                        {{ $item->description }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</x-app-layout>
