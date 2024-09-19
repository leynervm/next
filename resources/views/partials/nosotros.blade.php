<x-app-layout>

    <div class="contenedor w-full py-5">
        
        <section x-data="{
            openFaq1: false,
            openFaq2: false,
            openFaq3: false,
            openFaq4: false,
            openFaq5: false,
            openFaq6: false
        }" class="relative z-10 overflow-hidden pt-20 pb-12 lg:pt-24 lg:pb-24">
            <div class="w-full mx-auto mb-16 max-w-lg text-center lg:mb-20">
                <h2 class="text-primary mb-4 text-3xl font-bold sm:text-[40px]/[48px]">
                    Any Questions? Look Here</h2>
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
        </section>


        <section class="py-16">
            <div class="grid gap-2 sm:gap-5 lg:grid-cols-5">
                <div class="lg:col-span-2 p-1 shadow rounded-lg shadow-shadowminicard lg:shadow-none">
                    <h1 class="text-primary mb-3 text-xl font-semibold leading-none lg:text-4xl xl:text-5xl">
                        Nosotros</h1>
                    <p
                        class="text-xs md:text-sm text-colorsubtitleform text-justify lg:shadow lg:p-3 lg:rounded-lg lg:shadow-shadowminicard">
                        Somos una empresa creada en 2012, dedicada a la venta de productos y servicios. Distribuimos
                        equipos, piezas, partes, accesorios, suministros de la industria tecnológica, ubicada en la zona
                        nor
                        oriente de Perú. Seguimos expandiendo nuestro mercado a través del trabajo y compromiso de
                        nuestros
                        colaboradores. Nuestra prioridad es llegar más rapido, con precios competitivos buscando nuevas
                        alternativas que contribuyan al crecimiento y conocimiento de las nuevas tecnologías que ofrece
                        el mercado internacional.
                    </p>
                </div>
                <div class="flex flex-col gap-2 sm:gap-5 lg:col-span-3">
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
                </div>
            </div>
        </section>

    </div>
</x-app-layout>
