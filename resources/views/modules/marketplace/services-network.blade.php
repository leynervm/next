<x-app-layout>
    <div class="w-full relative overflow-hidden">
        <picture>
            <source srcset="{{ asset('images/home/recursos/home_network.webp') }}" media="(min-width : 768px)">
            <source srcset="{{ asset('images/home/recursos/home_network_mobile.webp') }}">
            <img src="{{ asset('images/home/recursos/home_network_mobile.webp') }}"
                alt="{{ asset('images/home/recursos/home_network_mobile.webp') }}"
                class="block w-full h-auto max-w-full object-cover">
        </picture>
    </div>

    <div class="contenedor w-full py-5">
        <section class="py-3 sm:py-5">
            <p class="text-primary text-center text-sm sm:text-xl md:text-3xl font-medium leading-none">
                Descubre nuestros planes Next</p>
            <h1
                class="text-colorlabel text-center py-3 text-sm sm:text-lg md:text-3xl font-medium leading-none pb-5 sm:pb-8">
                FIBRA ÓPTICA | RADIO ENLACE</h1>

            <div class="relative flex flex-col justify-center overflow-hidden">
                <div x-data="{ activeTab: 1, zonas: @js($zonas), networks: @js($networks) }">

                    <div class="flex justify-center">
                        <div role="tablist"
                            class="w-full lg:max-w-4xl inline-flex flex-wrap justify-center border border-borderminicard rounded-tl-2xl rounded-br-2xl sm:rounded-tl-3xl sm:rounded-br-3xl"
                            @keydown.right.prevent.stop="$focus.wrap().next()"
                            @keydown.left.prevent.stop="$focus.wrap().prev()"
                            @keydown.home.prevent.stop="$focus.first()" @keydown.end.prevent.stop="$focus.last()">

                            <template x-for="(item, index) in zonas">
                                <button x-html="'PLAN INTERNET </br>' + item.name" id="'tab-'+item.id"
                                    class="button-tab-network"
                                    :class="activeTab === item.id ? 'bg-primary text-colorbutton' :
                                        'text-primary hover:text-primary'"
                                    :tabindex="activeTab === item.id ? 0 : -1" :aria-selected="activeTab === item.id"
                                    aria-controls="'tabpanel-'+item.id" @click="activeTab = item.id"
                                    @focus="activeTab = item.id">
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="lg:max-w-4xl mx-auto">
                        <div class="relative flex flex-col" x-data="deslizar">
                            <article id="tabpanel-1" class="w-full rounded-2xl relative" role="tabpanel" tabindex="0"
                                aria-labelledby="tab-1" x-show="activeTab === 1"
                                x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                                x-transition:enter-start="opacity-0 -translate-y-8"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-12">
                                <div class="w-full relative">
                                    <div class="w-full flex overflow-x-hidden" id="content-planes-network">
                                        @foreach ($networks->zona_rural as $key => $value)
                                            <div class="w-full">
                                                <div class="w-full"
                                                    @if ($loop->first) id="content-box-planes-network" @endif>
                                                    <p
                                                        class="w-full block text-lg sm:text-xl lg:text-2xl font-semibold text-colorlabel text-center py-5 sm:py-8 leading-none">
                                                        {{ $value->lugar }}</p>
                                                    <div
                                                        class="w-full flex justify-center items-center mx-auto relative">
                                                        <div
                                                            class="w-full grid grid-cols-[repeat(auto-fill,minmax(170px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(220px,1fr))] gap-1 sm:gap-3 self-start">
                                                            @foreach ($value->planes as $plan)
                                                                <div class="card-network">
                                                                    <div class="header-card-network">
                                                                        <h1 class="title-header-card-network uppercase">
                                                                            {{ $key }}</h1>
                                                                        <p class="subtitle-header-card-network">
                                                                            {{ $plan->type }}</p>
                                                                    </div>
                                                                    <div
                                                                        class="w-full text-colorsubtitleform text-center font-medium text-xs sm:text-sm">
                                                                        <h1 class="leading-none">
                                                                            Download <br>{{ $plan->download }}</h1>
                                                                        <h1 class="leading-none mt-3">
                                                                            Upload <br>{{ $plan->upload }}</h1>
                                                                    </div>
                                                                    <div class="w-full">
                                                                        <div class="footer-card-network">
                                                                            <h1
                                                                                class="title-footer-card-network leading-none">
                                                                                Costo Mensual</h1>
                                                                            <p
                                                                                class="w-full flex justify-center text-2xl sm:text-3xl text-center text-colorlabel sm:pt-2">
                                                                                <small
                                                                                    class="text-[10px] sm:text-sm">S/.</small>
                                                                                {{ number_format($plan->price, 0, '.', ', ') }}
                                                                                <small
                                                                                    class="text-[10px] sm:text-sm">.00</small>
                                                                            </p>
                                                                        </div>
                                                                        @if ($empresa->whatsapp)
                                                                            <div class="w-full pt-2">
                                                                                <x-link-button
                                                                                    href="{{ $empresa->whatsapp }}"
                                                                                    target="_blank"
                                                                                    class="w-full text-center !rounded-2xl">
                                                                                    LO QUIERO</x-link-button>
                                                                            </div>
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="py-3 sm:py-8">
                                                    <p
                                                        class="w-full block text-lg lg:text-2xl lg:rounded-tl-2xl lg:rounded-br-2xl font-semibold text-colorlabel text-center rounded-tl-xl rounded-br-xl leading-none md:py-3 md:bg-fondominicard">
                                                        Conoce sobre nuestros costos de instalación:
                                                    </p>
                                                </div>

                                                <div
                                                    class="w-full rounded-xl sm:rounded-2xl bg-fondominicard sm:p-3 lg:p-5 text-colorlabel text-xs sm:text-sm lg:text-lg">
                                                    <ul class="w-full text-xs sm:text-lg text-colorlabel font-light">
                                                        @foreach ($value->costos->titulos as $item)
                                                            <li>{{ $item }}</li>
                                                        @endforeach
                                                    </ul>
                                                    <ul
                                                        class="w-full text-xs sm:text-sm md:text-lg text-colorlabel font-light divide-y divide-borderminicard">
                                                        @foreach ($value->costos->descripcion as $item)
                                                            <li class="w-full flex gap-2 justify-between py-1">
                                                                {{ $item->descripcion }}
                                                                <span class="inline-block">s/.
                                                                    {{ $item->price }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>

                                                <div class="py-3 sm:py-6">
                                                    <div class="w-full flex justify-center gap-3 sm:gap-5">
                                                        @foreach ($value->soporte as $item)
                                                            <div
                                                                class="flex flex-col justify-center items-center gap-3 sm:gap-5 p-3 sm:p-5 rounded-3xl size-32 sm:size-48 md:size-56 shadow-md shadow-shadowminicard bg-fondominicard">
                                                                @if ($item->icono == 1)
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 55.594 48" stroke-width="0"
                                                                        stroke="none" fill="currentColor"
                                                                        class="block w-auto max-w-full h-10 xs:h-14 sm:h-24 mx-auto text-colorlabel">
                                                                        <path
                                                                            d="M11.886 42.462s-.806-.536-.053-2.577 1.404-4.227 2.265-6.674c.103-.294.083-.439-.095-.711-3.233-4.898-3.806-10.713-1.571-15.958 2.202-5.168 6.91-8.879 12.594-9.926 4.32-.798 8.917.294 12.618 2.994 3.685 2.691 6.121 6.719 6.679 11.052 1.195 9.298-4.839 17.443-14.04 18.946-.586.097-1.167.133-1.726.173q-.366.023-.734.053l-.061.006h-.061c-2.285-.067-4.306-.481-6.183-1.273-.217-.091-.339-.097-.595.044-2.385 1.334-4.511 2.46-6.449 3.528-2.049 1.132-2.587.325-2.587.325ZM27.833 8.511q-1.22-.001-2.418.22c-5.047.931-9.06 4.086-11.005 8.656s-1.44 9.648 1.388 13.932c.548.832.656 1.684.328 2.611a275 275 0 0 0-1.979 5.801c-.022.064.047.123.105.089a393 393 0 0 0 5.621-3.085c.82-.459 1.628-.508 2.476-.151 1.606.679 3.355 1.037 5.343 1.1h.008q.354-.029.711-.05c.534-.036 1.037-.069 1.521-.147 8.161-1.334 13.315-8.295 12.253-16.551-.481-3.75-2.601-7.247-5.812-9.59-2.538-1.851-5.551-2.835-8.542-2.835Z" />
                                                                        <path
                                                                            d="M30.64 44.334c9.131-1.07 16.829-8.517 18.31-17.653 1.857-11.466-5.423-22.14-16.773-24.586C19.699-.596 7.48 8.476 6.437 21.185c-.252 3.077.1 6.06 1.114 8.97.186.53.331 1.048-.155 1.499-.484.451-.993.266-1.509.064C2.402 30.349.061 27.011 0 23.302c-.064-3.824 2.143-7.267 5.643-8.726.526-.22.79-.534 1.031-1.026C11.016 4.737 18.116-.056 27.93 0c9.765.056 16.767 4.918 21.056 13.682.147.303.517.673.828.806 3.53 1.513 5.762 4.787 5.782 8.545.02 3.806-2.182 7.091-5.742 8.645-.336.147-.731.544-.887.876-3.622 7.567-9.564 12.048-17.834 13.487-.284.05-.623.236-.778.464-.848 1.253-2.138 1.779-3.461 1.346-1.787-.586-2.732-2.907-1.243-4.62a2.92 2.92 0 0 1 1.868-.995c1.239-.139 2.329.514 2.966 1.801.044.091.097.178.161.298Z" />
                                                                    </svg>
                                                                @else
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 48 47.917" stroke-width="0"
                                                                        stroke="none" fill="currentColor"
                                                                        class="block w-auto max-w-full h-10 xs:h-14 sm:h-24 mx-auto text-colorlabel">
                                                                        <path
                                                                            d="M47.439 18.873a1.16 1.16 0 0 0-1.4-.902q-.404.098-.809.217c-.811.231-1.03.603-.856 1.433a21 21 0 0 1 .284 6.973c-1.274 10.152-9.927 18.009-20.147 18.167-6.706.103-12.289-2.458-16.576-7.615-6.254-7.518-6.329-18.32-.278-26.058 6.745-8.624 19.12-10.53 28.115-4.309.941.65 1.805 1.408 2.805 2.202a.08.08 0 0 1-.003.125c-.223.161-.369.248-.48.369-.397.439-.239.936.341 1.063q1.845.405 3.7.778c.628.128 1.041-.289.913-.916-.211-1.038-.458-2.074-.636-3.119-.189-1.102-.53-1.655-1.649-.519-.086-.078-.15-.131-.211-.189C36.291 2.604 31.271.338 25.447.039 17.53-.369 10.875 2.436 5.735 8.456c-7.917 9.273-7.601 22.834.72 31.776 5.529 5.943 12.486 8.467 20.515 7.476 8.159-1.008 14.274-5.307 18.21-12.53 2.788-5.121 3.444-10.603 2.263-16.302Z" />
                                                                        <path
                                                                            d="M24.047 5.718C14.038 5.699 5.862 13.811 5.771 23.697 5.679 33.839 13.8 42.154 23.903 42.179c10.186.025 18.329-8.073 18.332-18.21A18.18 18.18 0 0 0 24.047 5.721Zm.056 34.912c-9.201.017-16.688-7.406-16.779-16.477-.095-9.139 7.317-16.902 16.677-16.891 9.098-.017 16.59 7.429 16.704 16.405.117 9.209-7.326 16.944-16.601 16.963" />
                                                                        <path
                                                                            d="M33.292 19.151c-.156-.07-.425.053-.619.136a544 544 0 0 0-6.426 2.794.07.07 0 0 1-.086-.019 2.8 2.8 0 0 0-2.102-.952q-.145.001-.287.014c-.864-2.255-1.736-4.504-2.604-6.756-.299-.772-.592-1.549-.908-2.316-.172-.414-.572-.592-.949-.466-.411.134-.614.522-.497.974.042.164.111.319.172.477 1.111 2.872 2.224 5.746 3.33 8.62a.08.08 0 0 1-.022.086 2.8 2.8 0 0 0-1.025 2.163c0 .444.103.864.289 1.236a.08.08 0 0 1-.025.097c-2.547 1.691-5.065 3.363-7.601 5.049-.036.025-.045.075-.019.108l.245.363a.08.08 0 0 0 .108.022c2.541-1.686 5.065-3.357 7.604-5.043.031-.022.072-.014.097.014a2.8 2.8 0 0 0 2.55.913c.678-.106 1.286-.5 1.708-1.041.569-.731.692-1.42.617-2.046a.08.08 0 0 1 .045-.078c1.063-.472 2.13-.93 3.196-1.395q1.7-.737 3.399-1.477c.388-.17.628-.497.477-.888-.097-.25-.397-.469-.661-.589Zm-9.295-6.39c.422.011.75-.294.781-.764.022-.336.006-.678.006-1.016h-.003c0-.355.025-.714-.006-1.066-.039-.433-.316-.664-.742-.694a.724.724 0 0 0-.775.708 27 27 0 0 0 0 2.08.744.744 0 0 0 .742.747Zm.789 23.22q0-.05-.003-.103c-.05-.45-.338-.733-.753-.742-.422-.008-.753.259-.772.708a26 26 0 0 0 0 2.13c.017.436.308.664.739.689.419.022.739-.262.775-.706.028-.352.006-.711.006-1.066h.008v-.913Zm10.409-12.005c.017.416.336.722.814.733.644.014 1.286.017 1.93 0 .486-.011.784-.305.8-.742.017-.433-.313-.77-.809-.795-.302-.017-.608-.003-.913-.003-.338 0-.678-.017-1.016.003-.497.028-.822.363-.806.803m-23.1-.795a26 26 0 0 0-2.08.006.74.74 0 0 0-.722.77c.006.436.299.733.784.756.322.017.644.003.963.003.338 0 .678.011 1.016-.006.469-.025.784-.344.778-.767a.76.76 0 0 0-.739-.761" />
                                                                    </svg>
                                                                @endif

                                                                <p
                                                                    class="text-primary flex-1 text-center font-medium text-[10px] sm:text-sm lg:text-lg !leading-none">
                                                                    {{ $item->titulo }}: <br>
                                                                    {{ $item->descripcion }}
                                                                </p>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="w-full flex justify-around gap-2 items-center sm:block">
                                        <button type="button" @click="deslizar('-')"
                                            class="relative sm:absolute h-8 sm:h-10 md:h-12 rounded-r top-[20%] left-0 z-[299] bg-body p-1 text-primary sm:text-next-500 text-center sm:opacity-60 flex justify-center items-center transition-opacity ease-in-out duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 349 512"
                                                fill="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                class="w-full h-full block">
                                                <path
                                                    d="M1.843 262.032 170.39 509.5c1.088 1.605 2.564 2.5 4.11 2.5h168.548c2.348 0 4.476-2.081 5.37-5.264.902-3.191.402-6.861-1.26-9.301L182.718 256l164.44-241.434c1.661-2.44 2.161-6.11 1.26-9.3-.894-3.183-3.021-5.265-5.37-5.265H174.5c-1.546 0-3.022.896-4.11 2.5L1.842 249.968c-2.272 3.336-2.272 8.729 0 12.065z">
                                                </path>
                                            </svg>
                                            <span class="sr-only">Previous</span>
                                        </button>

                                        <button type="button" @click="deslizar('+')"
                                            class="relative sm:absolute h-8 sm:h-10 md:h-12 rounded-l top-[20%] right-0 z-[299] bg-body p-1 text-primary sm:text-next-500 text-center sm:opacity-60 flex justify-center items-center transition-opacity ease-in-out duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 349 512"
                                                fill="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                class="w-full h-full block">
                                                <path
                                                    d="m347.01895,249.967l-168.54792,-247.467c-1.08703,-1.604 -2.56296,-2.5 -4.10905,-2.5l-168.5486,0c-2.34774,0 -4.47548,2.082 -5.37044,5.265c-0.90109,3.191 -0.40117,6.861 1.26139,9.301l164.43955,241.434l-164.43955,241.433c-1.66187,2.441 -2.1618,6.11 -1.26139,9.301c0.89496,3.183 3.02202,5.265 5.37044,5.265l168.5486,0c1.54609,0 3.02202,-0.896 4.10905,-2.5l168.5486,-247.467c2.27213,-3.336 2.27213,-8.729 -0.00068,-12.065z">
                                                </path>
                                            </svg>
                                            <span class="sr-only">Next</span>
                                        </button>
                                    </div>
                                </div>
                            </article>

                            <article id="tabpanel-2" class="w-full rounded-2xl" role="tabpanel" tabindex="0"
                                aria-labelledby="tab-2" x-show="activeTab === 2"
                                x-transition:enter="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-700 transform order-first"
                                x-transition:enter-start="opacity-0 -translate-y-8"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-[cubic-bezier(0.68,-0.3,0.32,1)] duration-300 transform absolute"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-12">
                                <div class="w-full relative">
                                    <div class="w-full flex overflow-x-hidden" id="content-planes-network2">
                                        @foreach ($networks->zona_urbana as $key => $value)
                                            <div class="w-full">
                                                <div class="w-full"
                                                    @if ($loop->first) id="content-box-planes-network2" @endif>
                                                    <p
                                                        class="w-full block text-lg sm:text-xl lg:text-2xl font-semibold text-colorlabel text-center py-3 sm:py-8 leading-none">
                                                        {{ $value->lugar }}</p>
                                                    <div
                                                        class="w-full flex justify-center items-center mx-auto relative">
                                                        <div
                                                            class="w-full grid grid-cols-[repeat(auto-fill,minmax(170px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(220px,1fr))] gap-1 sm:gap-3 self-start">
                                                            @foreach ($value->planes as $plan)
                                                                <div class="card-network">
                                                                    <div class="header-card-network">
                                                                        <h1
                                                                            class="title-header-card-network uppercase">
                                                                            {{ $key }}</h1>
                                                                        <p class="subtitle-header-card-network">
                                                                            {{ $plan->type }}</p>
                                                                    </div>
                                                                    <div
                                                                        class="w-full text-colorsubtitleform text-center font-medium text-xs sm:text-sm">
                                                                        <h1 class="leading-none">
                                                                            Download <br>{{ $plan->download }}</h1>
                                                                        <h1 class="leading-none mt-3">
                                                                            Upload <br>{{ $plan->upload }}</h1>
                                                                    </div>
                                                                    <div class="w-full">
                                                                        <div class="footer-card-network">
                                                                            <h1
                                                                                class="title-footer-card-network leading-none">
                                                                                Costo Mensual</h1>
                                                                            <p
                                                                                class="w-full flex justify-center text-2xl sm:text-3xl text-center text-colorlabel sm:pt-2">
                                                                                <small
                                                                                    class="text-[10px] sm:text-sm">S/.</small>
                                                                                {{ number_format($plan->price, 0, '.', ', ') }}
                                                                                <small
                                                                                    class="text-[10px] sm:text-sm">.00</small>
                                                                            </p>
                                                                        </div>
                                                                        @if ($empresa->whatsapp)
                                                                            <div class="w-full pt-2">
                                                                                <x-link-button
                                                                                    href="{{ $empresa->whatsapp }}"
                                                                                    target="_blank"
                                                                                    class="w-full text-center !rounded-2xl">
                                                                                    LO QUIERO</x-link-button>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="py-3 sm:py-8">
                                                    <p
                                                        class="w-full block text-lg lg:text-2xl lg:rounded-tl-2xl lg:rounded-br-2xl font-semibold text-colorlabel text-center rounded-tl-xl rounded-br-xl leading-none md:py-3 md:bg-fondominicard">
                                                        Conoce sobre nuestros costos de instalación:
                                                    </p>
                                                </div>

                                                <div
                                                    class="w-full rounded-xl sm:rounded-2xl bg-fondominicard sm:p-3 lg:p-5 text-colorlabel text-xs sm:text-sm lg:text-lg">
                                                    <ul class="w-full text-xs sm:text-lg text-colorlabel font-light">
                                                        @foreach ($value->costos->titulos as $item)
                                                            <li>{{ $item }}</li>
                                                        @endforeach
                                                    </ul>
                                                    <ul
                                                        class="w-full text-xs sm:text-sm md:text-lg text-colorlabel font-light divide-y divide-borderminicard">
                                                        @foreach ($value->costos->descripcion as $item)
                                                            <li class="w-full flex gap-2 justify-between py-1">
                                                                {{ $item->descripcion }}
                                                                <span class="inline-block">
                                                                    @if (is_numeric($item->price))
                                                                        s/.
                                                                    @endif
                                                                    {{ $item->price }}
                                                                </span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>

                                                <div class="py-3 sm:py-6">
                                                    <div class="w-full flex justify-center gap-3 sm:gap-5">
                                                        @foreach ($value->soporte as $item)
                                                            <div
                                                                class="flex flex-col justify-center items-center gap-3 sm:gap-5 p-3 sm:p-5 rounded-3xl size-32 sm:size-48 md:size-56 shadow-md shadow-shadowminicard bg-fondominicard">
                                                                @if ($item->icono == 1)
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 55.594 48" stroke-width="0"
                                                                        stroke="none" fill="currentColor"
                                                                        class="block w-auto max-w-full h-10 xs:h-14 sm:h-24 mx-auto text-colorlabel">
                                                                        <path
                                                                            d="M11.886 42.462s-.806-.536-.053-2.577 1.404-4.227 2.265-6.674c.103-.294.083-.439-.095-.711-3.233-4.898-3.806-10.713-1.571-15.958 2.202-5.168 6.91-8.879 12.594-9.926 4.32-.798 8.917.294 12.618 2.994 3.685 2.691 6.121 6.719 6.679 11.052 1.195 9.298-4.839 17.443-14.04 18.946-.586.097-1.167.133-1.726.173q-.366.023-.734.053l-.061.006h-.061c-2.285-.067-4.306-.481-6.183-1.273-.217-.091-.339-.097-.595.044-2.385 1.334-4.511 2.46-6.449 3.528-2.049 1.132-2.587.325-2.587.325ZM27.833 8.511q-1.22-.001-2.418.22c-5.047.931-9.06 4.086-11.005 8.656s-1.44 9.648 1.388 13.932c.548.832.656 1.684.328 2.611a275 275 0 0 0-1.979 5.801c-.022.064.047.123.105.089a393 393 0 0 0 5.621-3.085c.82-.459 1.628-.508 2.476-.151 1.606.679 3.355 1.037 5.343 1.1h.008q.354-.029.711-.05c.534-.036 1.037-.069 1.521-.147 8.161-1.334 13.315-8.295 12.253-16.551-.481-3.75-2.601-7.247-5.812-9.59-2.538-1.851-5.551-2.835-8.542-2.835Z" />
                                                                        <path
                                                                            d="M30.64 44.334c9.131-1.07 16.829-8.517 18.31-17.653 1.857-11.466-5.423-22.14-16.773-24.586C19.699-.596 7.48 8.476 6.437 21.185c-.252 3.077.1 6.06 1.114 8.97.186.53.331 1.048-.155 1.499-.484.451-.993.266-1.509.064C2.402 30.349.061 27.011 0 23.302c-.064-3.824 2.143-7.267 5.643-8.726.526-.22.79-.534 1.031-1.026C11.016 4.737 18.116-.056 27.93 0c9.765.056 16.767 4.918 21.056 13.682.147.303.517.673.828.806 3.53 1.513 5.762 4.787 5.782 8.545.02 3.806-2.182 7.091-5.742 8.645-.336.147-.731.544-.887.876-3.622 7.567-9.564 12.048-17.834 13.487-.284.05-.623.236-.778.464-.848 1.253-2.138 1.779-3.461 1.346-1.787-.586-2.732-2.907-1.243-4.62a2.92 2.92 0 0 1 1.868-.995c1.239-.139 2.329.514 2.966 1.801.044.091.097.178.161.298Z" />
                                                                    </svg>
                                                                @else
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 48 47.917" stroke-width="0"
                                                                        stroke="none" fill="currentColor"
                                                                        class="block w-auto max-w-full h-10 xs:h-14 sm:h-24 mx-auto text-colorlabel">
                                                                        <path
                                                                            d="M47.439 18.873a1.16 1.16 0 0 0-1.4-.902q-.404.098-.809.217c-.811.231-1.03.603-.856 1.433a21 21 0 0 1 .284 6.973c-1.274 10.152-9.927 18.009-20.147 18.167-6.706.103-12.289-2.458-16.576-7.615-6.254-7.518-6.329-18.32-.278-26.058 6.745-8.624 19.12-10.53 28.115-4.309.941.65 1.805 1.408 2.805 2.202a.08.08 0 0 1-.003.125c-.223.161-.369.248-.48.369-.397.439-.239.936.341 1.063q1.845.405 3.7.778c.628.128 1.041-.289.913-.916-.211-1.038-.458-2.074-.636-3.119-.189-1.102-.53-1.655-1.649-.519-.086-.078-.15-.131-.211-.189C36.291 2.604 31.271.338 25.447.039 17.53-.369 10.875 2.436 5.735 8.456c-7.917 9.273-7.601 22.834.72 31.776 5.529 5.943 12.486 8.467 20.515 7.476 8.159-1.008 14.274-5.307 18.21-12.53 2.788-5.121 3.444-10.603 2.263-16.302Z" />
                                                                        <path
                                                                            d="M24.047 5.718C14.038 5.699 5.862 13.811 5.771 23.697 5.679 33.839 13.8 42.154 23.903 42.179c10.186.025 18.329-8.073 18.332-18.21A18.18 18.18 0 0 0 24.047 5.721Zm.056 34.912c-9.201.017-16.688-7.406-16.779-16.477-.095-9.139 7.317-16.902 16.677-16.891 9.098-.017 16.59 7.429 16.704 16.405.117 9.209-7.326 16.944-16.601 16.963" />
                                                                        <path
                                                                            d="M33.292 19.151c-.156-.07-.425.053-.619.136a544 544 0 0 0-6.426 2.794.07.07 0 0 1-.086-.019 2.8 2.8 0 0 0-2.102-.952q-.145.001-.287.014c-.864-2.255-1.736-4.504-2.604-6.756-.299-.772-.592-1.549-.908-2.316-.172-.414-.572-.592-.949-.466-.411.134-.614.522-.497.974.042.164.111.319.172.477 1.111 2.872 2.224 5.746 3.33 8.62a.08.08 0 0 1-.022.086 2.8 2.8 0 0 0-1.025 2.163c0 .444.103.864.289 1.236a.08.08 0 0 1-.025.097c-2.547 1.691-5.065 3.363-7.601 5.049-.036.025-.045.075-.019.108l.245.363a.08.08 0 0 0 .108.022c2.541-1.686 5.065-3.357 7.604-5.043.031-.022.072-.014.097.014a2.8 2.8 0 0 0 2.55.913c.678-.106 1.286-.5 1.708-1.041.569-.731.692-1.42.617-2.046a.08.08 0 0 1 .045-.078c1.063-.472 2.13-.93 3.196-1.395q1.7-.737 3.399-1.477c.388-.17.628-.497.477-.888-.097-.25-.397-.469-.661-.589Zm-9.295-6.39c.422.011.75-.294.781-.764.022-.336.006-.678.006-1.016h-.003c0-.355.025-.714-.006-1.066-.039-.433-.316-.664-.742-.694a.724.724 0 0 0-.775.708 27 27 0 0 0 0 2.08.744.744 0 0 0 .742.747Zm.789 23.22q0-.05-.003-.103c-.05-.45-.338-.733-.753-.742-.422-.008-.753.259-.772.708a26 26 0 0 0 0 2.13c.017.436.308.664.739.689.419.022.739-.262.775-.706.028-.352.006-.711.006-1.066h.008v-.913Zm10.409-12.005c.017.416.336.722.814.733.644.014 1.286.017 1.93 0 .486-.011.784-.305.8-.742.017-.433-.313-.77-.809-.795-.302-.017-.608-.003-.913-.003-.338 0-.678-.017-1.016.003-.497.028-.822.363-.806.803m-23.1-.795a26 26 0 0 0-2.08.006.74.74 0 0 0-.722.77c.006.436.299.733.784.756.322.017.644.003.963.003.338 0 .678.011 1.016-.006.469-.025.784-.344.778-.767a.76.76 0 0 0-.739-.761" />
                                                                    </svg>
                                                                @endif

                                                                <p
                                                                    class="text-primary flex-1 text-center font-medium text-[10px] sm:text-sm lg:text-lg !leading-none">
                                                                    {{ $item->titulo }}: <br>
                                                                    {{ $item->descripcion }}
                                                                </p>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="w-full flex justify-around gap-2 items-center sm:block">
                                        <button type="button" @click="deslizarzonaurbana('-')"
                                            class="relative sm:absolute h-8 sm:h-10 md:h-12 rounded-r top-[20%] left-0 z-[299] bg-body p-1.5 text-primary sm:text-next-500 text-center sm:opacity-60 flex justify-center items-center transition-opacity ease-in-out duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 349 512"
                                                fill="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                class="w-full h-full block">
                                                <path
                                                    d="M1.843 262.032 170.39 509.5c1.088 1.605 2.564 2.5 4.11 2.5h168.548c2.348 0 4.476-2.081 5.37-5.264.902-3.191.402-6.861-1.26-9.301L182.718 256l164.44-241.434c1.661-2.44 2.161-6.11 1.26-9.3-.894-3.183-3.021-5.265-5.37-5.265H174.5c-1.546 0-3.022.896-4.11 2.5L1.842 249.968c-2.272 3.336-2.272 8.729 0 12.065z">
                                                </path>
                                            </svg>
                                            <span class="sr-only">Previous</span>
                                        </button>

                                        <button type="button" @click="deslizarzonaurbana('+')"
                                            class="relative sm:absolute h-8 sm:h-10 md:h-12 rounded-r top-[20%] right-0 z-[299] bg-body p-1.5 text-primary sm:text-next-500 text-center sm:opacity-60 flex justify-center items-center transition-opacity ease-in-out duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 349 512"
                                                fill="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                class="w-full h-full block">
                                                <path
                                                    d="m347.01895,249.967l-168.54792,-247.467c-1.08703,-1.604 -2.56296,-2.5 -4.10905,-2.5l-168.5486,0c-2.34774,0 -4.47548,2.082 -5.37044,5.265c-0.90109,3.191 -0.40117,6.861 1.26139,9.301l164.43955,241.434l-164.43955,241.433c-1.66187,2.441 -2.1618,6.11 -1.26139,9.301c0.89496,3.183 3.02202,5.265 5.37044,5.265l168.5486,0c1.54609,0 3.02202,-0.896 4.10905,-2.5l168.5486,-247.467c2.27213,-3.336 2.27213,-8.729 -0.00068,-12.065z">
                                                </path>
                                            </svg>
                                            <span class="sr-only">Next</span>
                                        </button>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>

                </div>
            </div>

        </section>
    </div>

    <div class="w-full relative opacity-80 overflow-hidden">
        <picture>
            <source srcset="{{ asset('images/home/recursos/network.webp') }}" media="(min-width : 768px)">
            <source srcset="{{ asset('images/home/recursos/network_mobile.webp') }}">
            <img src="{{ asset('images/home/recursos/network_mobile.webp') }}"
                alt="{{ asset('images/home/recursos/network_mobile.webp') }}"
                class="block w-full h-auto max-w-full object-cover">
        </picture>
    </div>

    <div class="contenedor w-full py-3 sm:py-5">
        <section class="py-3 sm:py-5">
            <p class="text-primary text-center text-lg lg:text-3xl font-medium leading-none">
                Beneficios de contratar Internet Next</p>

            <div class="lg:max-w-4xl mx-auto">
                <div class="py-3 sm:py-6">
                    <div class="w-full flex flex-wrap justify-center gap-2 sm:gap-3 md:gap-5">
                        <div
                            class="flex flex-col justify-center items-center gap-3 sm:gap-5 p-2.5 sm:p-3 md:p-5 rounded-3xl size-28 sm:size-40 md:size-52 shadow-lg shadow-shadowminicard bg-fondominicard">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 62.306 48" stroke-width="0"
                                stroke="currentColor" fill="currentColor"
                                class="block w-auto max-w-full h-10 xs:h-14 sm:h-24 mx-auto text-colorlabel">
                                <path
                                    d="M31.885 0c10.704.152 20.632 4.012 28.993 11.829 1.73 1.619 1.915 4.006.44 5.566-1.59 1.684-3.941 1.694-5.732.029-8.415-7.827-18.35-10.83-29.689-9.338-7.513.988-13.87 4.365-19.375 9.474-1.511 1.406-3.627 1.489-5.116.209C-.17 16.416-.49 14.203.822 12.556c.775-.974 1.785-1.781 2.751-2.584C11.562 3.328 20.806.13 31.885 0" />
                                <path
                                    d="M31.246 12.791c8.026.065 14.918 2.982 20.853 8.318 1.302 1.172 2.13 2.513 1.691 4.345-.645 2.682-3.905 3.767-6.122 2.037-1.456-1.136-2.809-2.422-4.341-3.442-7.495-4.997-17.784-4.773-25.129.505-1.164.836-2.239 1.803-3.331 2.74-1.633 1.402-3.71 1.434-5.177.043-1.543-1.464-1.835-3.771-.284-5.286 1.832-1.791 3.836-3.497 6.014-4.838 4.817-2.963 10.105-4.521 15.827-4.424Z" />
                                <path
                                    d="M31.261 25.378c4.9.105 8.988 1.99 12.525 5.225.836.765 1.515 1.655 1.647 2.834.205 1.807-.576 3.18-2.25 3.998-1.406.688-3.083.397-4.365-.883-1.272-1.272-2.697-2.286-4.413-2.812-4.193-1.29-7.801-.202-10.927 2.779-1.706 1.629-3.894 1.698-5.426.209-1.604-1.558-1.687-3.789-.065-5.519 2.34-2.491 5.148-4.29 8.505-5.073 1.564-.365 3.176-.511 4.766-.761Zm-.158 22.624c-2.614-.043-4.596-2.127-4.56-4.791.032-2.448 2.225-4.513 4.734-4.467 2.477.047 4.578 2.253 4.546 4.781-.032 2.43-2.235 4.521-4.72 4.481Z" />
                            </svg>

                            <p
                                class="text-primary flex-1 text-center font-medium text-[10px] sm:text-sm lg:text-lg !leading-none">
                                Internet Ilimitado: <br>
                                Acceso sin restricciones
                            </p>
                        </div>
                        <div
                            class="flex flex-col justify-center items-center gap-3 sm:gap-5 p-2.5 sm:p-3 md:p-5 rounded-3xl size-28 sm:size-40 md:size-52 shadow-lg shadow-shadowminicard bg-fondominicard">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48.017 48" stroke="currentColor" fill="currentColor"
                                stroke-width="0.25"
                                class="block w-auto max-w-full h-10 xs:h-14 sm:h-24 mx-auto text-colorlabel">
                                <path
                                    d="M43.103 20.752h1.801c1.821 0 3.109 1.285 3.109 3.106V33.88c0 1.824-1.295 3.116-3.139 3.116H24.78v4.378c.479 0 .952-.007 1.421 0 .456.01.922-.017 1.361.073 1.024.205 1.718.836 2.102 1.811.046.119.063.235.251.235 1.533-.007 3.07-.003 4.63-.003.304-1.226 1.005-2.141 2.161-2.67a3.6 3.6 0 0 1 2.452-.215c1.758.449 2.918 2.055 2.822 3.893-.089 1.698-1.451 3.182-3.126 3.453a3.825 3.825 0 0 1-4.322-2.954h-2.561c-.661 0-1.322.007-1.983 0-.169 0-.258.033-.324.211-.443 1.193-1.47 1.903-2.746 1.903h-5.836c-1.272 0-2.293-.714-2.756-1.903-.033-.086-.152-.198-.231-.202a493 493 0 0 0-4.55-.003c-.026 0-.053.01-.079.017a3.8 3.8 0 0 1-4.375 2.921 3.756 3.756 0 0 1 .03-7.395c1.847-.327 3.846.803 4.345 2.951h.271c1.414 0 2.829-.003 4.243 0 .188 0 .291-.033.363-.235.423-1.15 1.444-1.864 2.677-1.88.724-.01 1.451 0 2.201 0V37H17.5c-.155 0-.321-.007-.469-.046-.344-.093-.555-.433-.519-.796a.74.74 0 0 1 .681-.661c.083-.007.169 0 .251 0h27.384c.862 0 1.464-.436 1.632-1.186.03-.135.036-.278.036-.416V23.871c0-1.001-.621-1.616-1.619-1.613H3.119c-1.014 0-1.626.608-1.626 1.636v9.966c0 1.014.621 1.636 1.636 1.636h10.274c.347 0 .625.099.783.423a.736.736 0 0 1-.496 1.051 1.4 1.4 0 0 1-.33.033c-3.423 0-6.847.003-10.274 0-1.474 0-2.653-.899-3-2.277a3.2 3.2 0 0 1-.089-.744q-.01-5.108 0-10.217a2.976 2.976 0 0 1 2.987-3.004c1.89-.01 3.777 0 5.667 0h.34V14.13c.01-.486.321-.829.757-.829s.75.34.75.829v6.632h25.266c.443 0 .38.04.423-.377.542-5.423.71-10.858.638-16.307-.007-.558-.056-1.124.013-1.672A2.726 2.726 0 0 1 39.809.02c1.53.132 2.63 1.342 2.587 2.862q-.158 5.92.215 11.83c.119 1.913.291 3.82.439 5.73.007.099.03.198.05.321Zm-1.56-.013c0-.099.007-.192 0-.281-.155-2.135-.334-4.266-.466-6.401a125 125 0 0 1-.178-11.129c.023-.813-.506-1.418-1.269-1.431-.783-.013-1.342.568-1.322 1.401.083 3.546.056 7.088-.122 10.63-.096 1.893-.245 3.787-.377 5.68-.036.506-.086 1.011-.132 1.53h3.863ZM24.007 45.601h2.901c.82 0 1.434-.575 1.444-1.345.01-.777-.611-1.371-1.447-1.375q-2.9-.005-5.799 0c-.833 0-1.454.601-1.444 1.375.01.767.625 1.342 1.447 1.342h2.901Zm-11.982-1.348a2.265 2.265 0 0 0-2.247-2.264c-1.233-.007-2.247 1.014-2.247 2.257a2.25 2.25 0 0 0 2.231 2.244 2.254 2.254 0 0 0 2.264-2.24Zm28.455-.003c0-1.242-1.014-2.264-2.247-2.257a2.26 2.26 0 0 0-2.244 2.264 2.254 2.254 0 0 0 2.237 2.24 2.25 2.25 0 0 0 2.257-2.247Z" />
                                <path
                                    d="M42.489 26.829h.909c.588.003.869.284.869.876v5.105c0 .565-.291.853-.862.853h-7.309c-.539 0-.839-.284-.843-.82q-.01-2.609 0-5.218c0-.499.307-.79.81-.796.314-.007.628 0 .965 0v-1.844c0-.634.281-.915.919-.915h3.655c.605 0 .889.284.892.886v1.874Zm-5.723 5.307h5.981v-3.807h-.849c-.641 0-.909-.274-.912-.919v-1.817h-2.455v1.857c0 .591-.278.872-.866.879h-.896v3.807Zm-5.539-5.307h.922c.575.007.853.284.853.862V32.8c0 .588-.284.866-.876.866h-7.257c-.595 0-.869-.274-.872-.869v-5.135c0-.542.281-.823.826-.829.297-.003.591 0 .919 0 .007-.119.02-.215.02-.314 0-.532-.003-1.061 0-1.593.007-.568.301-.856.869-.856h3.714c.591 0 .879.284.882.866zm-5.713 5.31h5.968v-3.81h-.786c-.724 0-.972-.248-.972-.975v-1.761h-2.462v1.745c0 .763-.228.991-1.001.991h-.751v3.81Zm-11.975-3.073c-.026 2.525-1.986 4.583-4.544 4.745-2.416.152-4.603-1.672-4.927-4.121-.182-1.385.192-2.614 1.081-3.688.357-.433.813-.499 1.163-.195.347.304.337.734-.01 1.16-.856 1.061-1.041 2.237-.459 3.47.575 1.219 1.593 1.864 2.938 1.857 1.322-.007 2.323-.644 2.898-1.837.572-1.186.416-2.33-.367-3.387-.056-.076-.122-.142-.178-.215a.75.75 0 0 1 .093-1.031.73.73 0 0 1 1.031.046 4.4 4.4 0 0 1 1.133 2.171c.073.334.096.681.142 1.018ZM1.345 9.411c.04-2.155.813-4.111 2.373-5.75.211-.221.453-.36.767-.297.287.059.489.231.572.512a.74.74 0 0 1-.195.77 6.8 6.8 0 0 0-1.388 2.006c-1.171 2.584-.655 5.574 1.298 7.636.135.142.258.327.304.512.083.324-.099.641-.387.79-.301.155-.634.102-.892-.159a8.3 8.3 0 0 1-2.059-3.4c-.258-.823-.38-1.665-.39-2.624Zm16.816-.093c-.04 2.445-.81 4.392-2.353 6.031-.225.238-.476.393-.816.307-.585-.142-.74-.8-.354-1.282.436-.539.899-1.077 1.223-1.685 1.394-2.607.922-5.836-1.147-7.951-.529-.542-.271-1.239.278-1.365.317-.073.558.05.773.271a8.25 8.25 0 0 1 2.264 4.454c.079.466.102.942.132 1.216ZM9.745 11.79a2.264 2.264 0 0 1-2.267-2.273 2.256 2.256 0 0 1 2.283-2.28 2.27 2.27 0 0 1 2.27 2.27 2.26 2.26 0 0 1-2.287 2.28Zm.01-1.504a.775.775 0 0 0 .777-.78.78.78 0 0 0-.767-.763c-.439-.007-.783.327-.783.763s.34.78.773.78" />
                                <path
                                    d="M6.368 5.512c.449.017.687.155.82.446.142.311.083.601-.155.846-.509.525-.862 1.137-1.014 1.857-.271 1.279.026 2.412.872 3.404.059.069.129.135.188.205a.746.746 0 0 1-.046 1.034.744.744 0 0 1-1.054-.013 4.86 4.86 0 0 1-1.242-1.96c-.671-2.036-.301-3.866 1.13-5.466.155-.175.393-.274.506-.35Zm8.72 4.434c-.01.988-.496 2.231-1.484 3.271-.34.357-.793.393-1.114.099-.334-.307-.324-.76.026-1.137 1.434-1.543 1.434-3.764 0-5.327-.436-.473-.304-1.124.268-1.299.317-.096.578.01.806.241.948.965 1.497 2.307 1.497 4.15M9.514 26.875c0 .734.007 1.47 0 2.204a.724.724 0 0 1-.525.71c-.304.089-.654-.01-.816-.274a1 1 0 0 1-.149-.496q-.014-2.15-.003-4.299c0-.479.311-.793.75-.793s.74.317.747.796c.007.717 0 1.434 0 2.148Z" />
                            </svg>

                            <p
                                class="text-primary flex-1 text-center font-medium text-[10px] sm:text-sm lg:text-lg !leading-none">
                                Equipos a préstamo: <br>
                                Sin costo adicional
                            </p>
                        </div>
                        <div
                            class="flex flex-col justify-center items-center gap-3 sm:gap-5 p-2.5 sm:p-3 md:p-5 rounded-3xl size-28 sm:size-40 md:size-52 shadow-lg shadow-shadowminicard bg-fondominicard">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 76.883 48" stroke-width="0" stroke="currentColor" fill="currentColor"
                                class="block w-auto max-w-full h-10 xs:h-14 sm:h-24 mx-auto text-colorlabel">
                                <path
                                    d="M13.361 26.339V1.606c0-.261-.019-.528.033-.78A1.005 1.005 0 0 1 14.397 0c.49 0 .906.317 1.004.822.061.331.056.677.056 1.013v24.439h45.974V1.723c0-.285-.005-.579.042-.859.098-.546.523-.882 1.046-.864a1 1 0 0 1 .971.859c.047.252.028.523.028.78v24.696h.934c1.751 0 3.501-.009 5.252 0 4.066.023 7.147 3.1 7.161 7.157.009 2.456.009 4.916 0 7.371-.014 3.963-3.081 7.063-7.04 7.124-1.046.014-2.091 0-3.137 0H7.325c-2.502 0-4.514-.966-6.003-2.974a6.6 6.6 0 0 1-1.316-3.954 478 478 0 0 1 0-7.763c.037-3.814 3.151-6.918 6.961-6.961 1.83-.019 3.66 0 5.49 0h.896Zm25.167 2.087q-15.644-.001-31.283.005c-2.946 0-5.117 2.147-5.14 5.084-.019 2.456-.037 4.916.019 7.371.019.71.173 1.475.476 2.115.943 1.993 2.61 2.908 4.794 2.908h61.235c.392 0 .784.014 1.176 0 2.787-.093 4.934-2.194 4.981-4.939.042-2.535.047-5.07-.005-7.605-.037-1.993-1.008-3.478-2.773-4.412-.803-.425-1.681-.532-2.582-.532H38.537Z" />
                                <path
                                    d="M50.577 13.627c-.019.63-.219.948-.612 1.125-.425.191-.826.107-1.153-.219-.182-.182-.317-.415-.472-.626-3.562-4.757-9.696-6.349-15.065-3.898-1.895.864-3.441 2.166-4.673 3.842-.154.21-.289.439-.462.635-.364.411-.91.49-1.349.219a1.055 1.055 0 0 1-.401-1.307c.079-.191.205-.364.327-.532 5.695-8.104 17.698-8.118 23.43-.033.205.289.336.626.425.794Z" />
                                <path
                                    d="M38.519 12.945c2.568.056 4.706 1.078 6.335 3.128a3.6 3.6 0 0 1 .467.719c.205.467.042.98-.345 1.256-.425.299-.966.257-1.354-.135-.145-.145-.257-.331-.383-.495a6.03 6.03 0 0 0-9.561-.065c-.131.163-.233.345-.373.504-.35.392-.938.495-1.349.247-.448-.266-.616-.859-.369-1.377.126-.257.294-.495.472-.714 1.653-2.035 3.8-3.03 6.461-3.067m11.554 27.408c-1.811.009-3.24-1.391-3.254-3.188-.009-1.741 1.405-3.179 3.17-3.212 1.774-.037 3.24 1.415 3.245 3.212a3.167 3.167 0 0 1-3.16 3.193Zm-.065-2.087c.574.019 1.106-.476 1.134-1.06s-.448-1.106-1.041-1.144a1.11 1.11 0 0 0-1.172 1.088 1.123 1.123 0 0 0 1.078 1.111Zm-23.127 2.082c-1.793.009-3.202-1.368-3.221-3.146-.019-1.807 1.424-3.268 3.216-3.259s3.207 1.471 3.193 3.291c-.014 1.713-1.438 3.104-3.188 3.114m.023-4.286a1.124 1.124 0 0 0-1.153 1.032c-.037.574.443 1.125 1.022 1.162a1.11 1.11 0 0 0 1.186-1.069 1.096 1.096 0 0 0-1.05-1.125Zm11.568 4.286c-1.807 0-3.202-1.354-3.23-3.142a3.204 3.204 0 0 1 3.212-3.259c1.755 0 3.184 1.433 3.188 3.202 0 1.788-1.391 3.193-3.17 3.198m1.074-3.221a1.103 1.103 0 0 0-2.204.028c0 .616.546 1.139 1.158 1.111.584-.028 1.06-.551 1.046-1.139" />
                            </svg>

                            <p
                                class="text-primary flex-1 text-center font-medium text-[10px] sm:text-sm lg:text-lg !leading-none">
                                Router (Wifi)</p>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-colorlabel text-center text-sm sm:text-xl font-medium leading-none py-3">
                Importante :</p>

            <p
                class="text-xs sm:text-sm lg:text-lg text-colorlabel text-justify font-light !leading-none text-last-center">
                Los equipos instalados
                son propiedad del proveedor del servicio, NEXT. Si el cliente decide
                cancelar su servicio, el proveedor se encargará de recoger los equipos. Es importante
                señalar que el costo de instalación se destina únicamente a cubrir gastos operativos.
            </p>
            <p class="italic text-xs sm:text-sm lg:text-lg text-colorlabel text-center font-normal">
                Agradecemos su comprensión.</p>

        </section>
    </div>

    <script>
        function deslizar() {
            return {
                content: '#content-planes-network',
                element: '#content-box-planes-network',
                content2: '#content-planes-network2',
                element2: '#content-box-planes-network2',
                deslizar(type = '+') {
                    $(this.content).animate({
                        scrollLeft: type + '=' + Math.round($(this.element).width())
                    }, 'slow');
                },
                deslizarzonaurbana(type = '+') {
                    $(this.content2).animate({
                        scrollLeft: type + '=' + Math.round($(this.element2).width())
                    }, 'slow');
                }
            }
        }
    </script>
</x-app-layout>
