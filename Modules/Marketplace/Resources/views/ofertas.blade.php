<x-app-layout>
    <x-slot name="breadcrumb">
        <x-link-breadcrumb text="OFERTAS" active>
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 7l.867 12.143a2 2 0 0 0 2 1.857h10.276a2 2 0 0 0 2 -1.857l.867 -12.143h-16z" />
                    <path d="M8.5 7c0 -1.653 1.5 -4 3.5 -4s3.5 2.347 3.5 4" />
                    <path
                        d="M9.5 17c.413 .462 1 1 2.5 1s2.5 -.897 2.5 -2s-1 -1.5 -2.5 -2s-2 -1.47 -2 -2c0 -1.104 1 -2 2 -2s1.5 0 2.5 1" />
                </svg>
            </x-slot>
        </x-link-breadcrumb>
    </x-slot>

    <div class="w-full">
        <div class="relative w-full m-0 mb-10">
            <h1 class="text-colorsubtitleform flex items-start text-xl leading-normal px-0 py-4 gap-2.5 bg-white">
                Descubre nuestras ofertas que tenemos para tí</h1>
            <div class="w-full m-0 relative">
                <div class="w-full relative overflow-hidden border-t border-borderminicard mb-9">
                    <div
                        class="flex max-h-full bg-fondominicard active w-full float-left -mr-[100%] transition-transform ease-in-out duration-700">
                        @foreach ($ofertas as $item)
                            <x-card-producto-oferta :producto="$item" :empresa="$empresa" :moneda="$moneda" :pricetype="$pricetype" />
                        @endforeach
                        {{-- <div
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
                                            title="product-case-iphone-11-transparente-image" class="align-middle">
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
                                            <div class="w-full text-neutral-500 whitespace-nowrap flex flex-col">
                                                <div class="text-xl">
                                                    S/299.90</div>
                                            </div>
                                            <div class="text-sm text-neutral-500 line-through">
                                                S/499.90</div>
                                            <div class="PopularityCard-module_original-price__qQv-I">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-1 mb-7">
                                    <div class="relative inline-flex justify-center items-center" title="4.6">
                                        <svg class="star-grad"
                                            style="position: absolute; z-index: 0; width: 0px; height: 0px; visibility: hidden;">
                                            <defs>
                                                <linearGradient id="starGrad450314912949914" x1="0%"
                                                    y1="0%" x2="100%" y2="0%">
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
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
