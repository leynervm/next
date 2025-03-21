<div class="header-marketplace" x-data="searchnext">
    <div class="w-full mx-auto flex items-center relative">
        @if ($empresa->logo)
            <div class="hidden xl:flex w-72 items-center h-full">
                <a href="/" class="w-full p-1 xl:p-2 h-[90%]">
                    <img class="mx-auto h-full w-full object-scale-down"
                        src="{{ getLogoEmpresa($empresa->logo, request()->isSecure() ? true : false) }}" alt="">
                    {{-- <x-isotipo-next class="text-black h-full mx-auto" /> --}}
                </a>
            </div>
        @endif

        <div class="flex-shrink-0 @if (empty($empresa->logo)) pl-4 @endif xl:pr-5">
            <button class="button-sidebar hidden xl:flex items-center" type="button"
                @click="openSidebar=!openSidebar;backdrop=openSidebar;if(!openSidebar) {document.body.style.overflow = 'auto';}"
                :class="openSidebar ? 'open' : ''">
                <div class="scale-[.5] relative h-8 w-8 z-10 transition ease-in-out duration-150">
                    <span class="icon-button-menu"></span>
                </div>
                <span class="pl-2 text-inherit text-start flex-1 font-semibold text-xl">
                    Menú</span>
            </button>
            <button class="button-sidebar xl:hidden px-2" type="button"
                @click="openSidebar=!openSidebar;backdrop=openSidebar;if(!openSidebar) {document.body.style.overflow = 'auto';}">
                <div class="scale-[.5] relative h-8 w-8 z-10 transition ease-in-out duration-150">
                    <span class="icon-button-menu"></span>
                </div>
                @if ($empresa->logo)
                    <div class="w-full flex-1 p-1 xl:hidden h-full">
                        <img class="h-full max-w-24 xs:w-auto xs:max-w-28 sm:max-w-32 m-auto object-center object-scale-down"
                            src="{{ getLogoEmpresa($empresa->logo, request()->isSecure() ? true : false) }}"
                            alt="">
                    </div>
                @endif
            </button>
        </div>

        <div class="mr-6 relative flex-1 hidden xl:flex" @click.away="products= [],selectedIndex=-1">
            <form @submit.prevent="handleEnter" autocomplete="off"
                class="w-full bg-none self-center flex cursor-pointer" :class="openSidebar ? '' : 'z-[299]'">
                <div class="w-full flex h-[46px] m-0 bg-fondosearchmarketplace justify-center items-center pl-6 rounded-3xl border-0.5 border-fondobuttonsearchmarketplace"
                    :class="products.length ? 'rounded-b-none' : ''">
                    <label for="searchheader-xl" class="absolute w-[1px] h-[1px] p-0 overflow-hidden">
                        Barra de búsqueda</label>
                    <input type="search" name="search" autocomplete="off" x-ref="search" x-model="search"
                        @input.debounce.300ms="fetchProducts" @keydown.enter.prevent="handleEnter"
                        @keydown.arrow-down.prevent="navigate(1)" {{-- @blur="handleBlur" --}}
                        @keydown.arrow-up.prevent="navigate(-1)"
                        class="bg-transparent border-0 border-none w-full text-lg h-full leading-5 text-colorsearchmarketplace tracking-wide ring-0 focus:border-0 focus:ring-0 outline-none outline-0 focus:outline-none focus:border-none focus:shadow-none shadow-none"
                        placeholder="Buscar en Next" id="search">
                </div>
                <button type="submit" @click.prevent="handleEnter;"
                    class="bg-fondobuttonsearchmarketplace rounded-3xl focus:ring focus:ring-ringbuttonsearchmarketplace absolute right-0 box-border border-2 border-fondosearchmarketplace z-10 h-[46px] w-[46px] flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="block w-full h-full p-2 text-colorbuttonsearchmarketplace">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </button>
            </form>

            <ul class="w-full block absolute py-2 left-0 top-[100%] rounded-b-3xl bg-fondominicard z-[999]"
                x-show="products.length">
                <div class="rounded p-1 overflow-hidden xl:overflow-y-auto xl:max-h-[360px]">
                    <template x-for="(product, index) in products" :key="product.id">
                        <li class="w-full flex p-1">
                            <template x-if="product.image">
                                <div class="size-16 flex-shrink-0 rounded overflow-hidden">
                                    <img :src="product.image" alt="" class="w-full h-full object-scale-down">
                                </div>
                            </template>
                            <template x-if="product.image==null">
                                <div class="size-16 flex-shrink-0 rounded overflow-hidden">
                                    <x-icon-file-upload class="!w-full !h-full" type="unknown" />
                                </div>
                            </template>
                            <a x-html="highlight(product.name, search)"
                                :class="{ 'bg-fondohoverselect2': index === selectedIndex }"
                                class="block w-full flex-1 rounded text-colorsubtitleform p-2.5 text-xs leading-none hover:bg-fondohoverselect2"
                                :href="route('productos.show', product.slug)" {{--  @mouseenter="setSelected(index)" --}}></a>
                        </li>
                    </template>
                </div>
            </ul>
        </div>

        <div class="w-full flex flex-1 sm:w-auto sm:flex-none sm:ml-auto">
            <ul class="w-full sm:w-auto flex justify-end h-full m-0 p-0" x-data="{ login: false }">
                <li
                    class="group relative h-[68%] self-center hidden xs:flex min-w-[50px] w-auto flex-shrink-0 transition ease-out duration-150">
                    <a class="h-full flex flex-col text-primary items-center px-1 font-semibold text-lg leading-none cursor-pointer group-hover:opacity-80"
                        href="{{ route('ofertas') }}">
                        PROMOCIONES</a>
                </li>

                @auth
                @else
                    <li
                        class="group relative h-[68%] flex self-center min-w-[50px] w-auto flex-shrink-0 transition ease-out duration-150">
                        <div
                            class="p-3 px-1 sm:px-3 h-ful flex gap-1 xs:gap-2 sm:gap-3 w-full theme-switcher justify-center items-center">
                            <button title="Light" theme="theme-next" type="button"
                                class="block theme-switcher-button rounded-full bg-transparent text-inherit">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                    class="block w-8 h-8 p-1">
                                    <path
                                        d="M6.08938 14.9992C5.71097 14.1486 5.5 13.2023 5.5 12.2051C5.5 8.50154 8.41015 5.49921 12 5.49921C15.5899 5.49921 18.5 8.50154 18.5 12.2051C18.5 13.2023 18.289 14.1486 17.9106 14.9992" />
                                    <path d="M12 1.99921V2.99921" />
                                    <path d="M22 11.9992H21" />
                                    <path d="M3 11.9992H2" />
                                    <path d="M19.0704 4.92792L18.3633 5.63503" />
                                    <path d="M5.6368 5.636L4.92969 4.92889" />
                                    <path
                                        d="M14.517 19.3056C15.5274 18.9788 15.9326 18.054 16.0466 17.1238C16.0806 16.8459 15.852 16.6154 15.572 16.6154L8.47685 16.6156C8.18725 16.6156 7.95467 16.8614 7.98925 17.1489C8.1009 18.0773 8.3827 18.7555 9.45345 19.3056M14.517 19.3056C14.517 19.3056 9.62971 19.3056 9.45345 19.3056M14.517 19.3056C14.3955 21.2506 13.8338 22.0209 12.0068 21.9993C10.0526 22.0354 9.60303 21.0833 9.45345 19.3056" />
                                </svg>
                            </button>

                            <button title="Dark" theme="theme-darknext" type="button"
                                class="block theme-switcher-button rounded-full bg-transparent text-inherit">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="block w-8 h-8 p-1">
                                    <path
                                        d="M21.5 14.0784C20.3003 14.7189 18.9301 15.0821 17.4751 15.0821C12.7491 15.0821 8.91792 11.2509 8.91792 6.52485C8.91792 5.06986 9.28105 3.69968 9.92163 2.5C5.66765 3.49698 2.5 7.31513 2.5 11.8731C2.5 17.1899 6.8101 21.5 12.1269 21.5C16.6849 21.5 20.503 18.3324 21.5 14.0784Z" />
                                </svg>
                            </button>
                        </div>
                    </li>
                @endauth

                @auth
                    <li class="group relative h-[68%] self-center hidden xs:block">
                        <a x-data="{ counterwishlist: ' {{ Cart::instance('wishlist')->count() }}' }"
                            class="flex w-full h-full justify-center items-center p-3 px-1 sm:px-3 text-inherit cursor-pointer group-hover:opacity-80 transition ease-out duration-150"
                            href="{{ route('wishlist') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                class="block w-8 h-8">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9.73 17.753l-5.23 -5.181a5 5 0 1 1 7.5 -6.566a5 5 0 0 1 8.563 5.041" />
                                <path
                                    d="M17.8 20.817l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l2.428 -.352l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.567 .411l-2.172 -1.138z" />
                            </svg>
                            <span x-show="counterwishlist > 0" x-text="counterwishlist"
                                @updatewishlist.window ="(data)=> {
                                    counterwishlist = typeof data.detail === 'object' ? 0 : data.detail;
                                }"
                                class="flex absolute w-4 h-4 top-0.5 -right-1 xl:right-1 tracking-tight h-100 justify-center items-center leading-3 text-[9px] bg-fondobadgemarketplace text-colorbadgemarketplace rounded-full">
                                {{ Cart::instance('wishlist')->count() }}
                            </span>
                        </a>
                    </li>
                @endauth
                <li class="group relative h-[68%] flex self-center transition ease-out duration-150">
                    <livewire:modules.marketplace.carrito.counter-carrito />
                </li>
                <li class="relative h-[68%] flex items-center self-center">
                    @auth
                        <div @click.away="login=false">
                            <button @click="login=!login"
                                class="h-full flex justify-center items-center px-1 py-3 sm:px-3 cursor-pointer text-inherit group-hover:opacity-80 transition ease-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    class="block w-8 h-8" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M7.78256 17.1112C6.68218 17.743 3.79706 19.0331 5.55429 20.6474C6.41269 21.436 7.36872 22 8.57068 22H15.4293C16.6313 22 17.5873 21.436 18.4457 20.6474C20.2029 19.0331 17.3178 17.743 16.2174 17.1112C13.6371 15.6296 10.3629 15.6296 7.78256 17.1112Z" />
                                    <path
                                        d="M15.5 10C15.5 11.933 13.933 13.5 12 13.5C10.067 13.5 8.5 11.933 8.5 10C8.5 8.067 10.067 6.5 12 6.5C13.933 6.5 15.5 8.067 15.5 10Z" />
                                    <path
                                        d="M2.854 16C2.30501 14.7664 2 13.401 2 11.9646C2 6.46129 6.47715 2 12 2C17.5228 2 22 6.46129 22 11.9646C22 13.401 21.695 14.7664 21.146 16" />
                                </svg>
                            </button>

                            <div x-show="login" x-cloak x-transition style="display: none"
                                class="absolute shadow shadow-shadowminicard w-48 top-12 right-0 bg-fondodropdown rounded-xl p-2"
                                :class="backdrop ? 'z-[9]' : 'z-[299]'">
                                <div class="w-full">
                                    <div
                                        class="flex gap-2 w-full theme-switcher justify-end items-center bg-fondominicard">
                                        <button title="Light" theme="theme-next"
                                            class="inline-block theme-switcher-button rounded-full bg-transparent text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" class="block w-8 h-8 p-1">
                                                <path
                                                    d="M6.08938 14.9992C5.71097 14.1486 5.5 13.2023 5.5 12.2051C5.5 8.50154 8.41015 5.49921 12 5.49921C15.5899 5.49921 18.5 8.50154 18.5 12.2051C18.5 13.2023 18.289 14.1486 17.9106 14.9992" />
                                                <path d="M12 1.99921V2.99921" />
                                                <path d="M22 11.9992H21" />
                                                <path d="M3 11.9992H2" />
                                                <path d="M19.0704 4.92792L18.3633 5.63503" />
                                                <path d="M5.6368 5.636L4.92969 4.92889" />
                                                <path
                                                    d="M14.517 19.3056C15.5274 18.9788 15.9326 18.054 16.0466 17.1238C16.0806 16.8459 15.852 16.6154 15.572 16.6154L8.47685 16.6156C8.18725 16.6156 7.95467 16.8614 7.98925 17.1489C8.1009 18.0773 8.3827 18.7555 9.45345 19.3056M14.517 19.3056C14.517 19.3056 9.62971 19.3056 9.45345 19.3056M14.517 19.3056C14.3955 21.2506 13.8338 22.0209 12.0068 21.9993C10.0526 22.0354 9.60303 21.0833 9.45345 19.3056" />
                                            </svg>
                                        </button>

                                        <button title="Dark" theme="theme-darknext"
                                            class="inline-block theme-switcher-button rounded-full bg-transparent text-neutral-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" class="block w-8 h-8 p-1">
                                                <path
                                                    d="M21.5 14.0784C20.3003 14.7189 18.9301 15.0821 17.4751 15.0821C12.7491 15.0821 8.91792 11.2509 8.91792 6.52485C8.91792 5.06986 9.28105 3.69968 9.92163 2.5C5.66765 3.49698 2.5 7.31513 2.5 11.8731C2.5 17.1899 6.8101 21.5 12.1269 21.5C16.6849 21.5 20.503 18.3324 21.5 14.0784Z" />
                                            </svg>
                                        </button>
                                    </div>

                                    <ul class="w-full">
                                        @php
                                            $pricetype = getPricetypeAuth();
                                        @endphp
                                        <li
                                            class="w-full flex gap-1 text-[10px] py-1 pt-2 items-center justify-between divide-x border-borderminicard">
                                            <p class="block px-1.5 w-full max-w-[60%] flex-shrink-0 truncate text-inherit"
                                                style="text-wrap:wrap;">
                                                {{ Auth::user()->name }}</p>
                                            @if ($pricetype)
                                                <p class="block px-1.5 w-full text-end !leading-none flex-1 text-inherit">
                                                    {{ $pricetype->name }}</p>
                                            @endif
                                        </li>

                                        @if (auth()->user()->isAdmin() || auth()->user()->sucursal)
                                            <li>
                                                <a class="block w-full text-sm font-medium p-2.5 rounded-lg text-inherit hover:bg-fondohoverdropdown hover:text-textohoverdropdown transition ease-in-out duration-150"
                                                    href="{{ route('admin') }}">{{ __('Dashboard') }}</a>
                                            </li>
                                        @endif

                                        <li>
                                            <a class="block w-full text-sm font-medium p-2.5 rounded-lg text-inherit hover:bg-fondohoverdropdown hover:text-textohoverdropdown transition ease-in-out duration-150"
                                                href="{{ route('orders') }}">
                                                Mis compras
                                            </a>
                                        </li>

                                        {{-- <li>
                                            <a class="block w-full text-center text-sm font-medium p-2.5 rounded-lg text-inherit hover:bg-fondohoverdropdown hover:text-textohoverdropdown transition ease-in-out duration-150"
                                                href="{{ route('profile') }}">{{ __('Profile') }}</a>
                                        </li> --}}
                                        <li class="w-full flex gap-1 items-center">
                                            <a class="block w-full flex-1 text-sm font-medium p-2.5 rounded-lg text-inherit hover:bg-fondohoverdropdown hover:text-textohoverdropdown transition ease-in-out duration-150"
                                                href="{{ route('profile') }}">{{ __('Profile') }}</a>

                                            <form class="flex-shrink-0" method="POST" action="{{ route('logout') }}"
                                                x-data>
                                                @csrf
                                                <a href="{{ route('logout') }}" title="{{ __('Log Out') }}"
                                                    @click.prevent="$root.submit();"
                                                    class="block w-full text-center text-sm font-medium p-2.5 rounded-lg text-colorerror hover:bg-fondohoverdropdown hover:text-textohoverdropdown transition ease-in-out duration-150">
                                                    {{-- {{ __('Log Out') }} --}}
                                                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                                        stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="1.5" fill="none"
                                                        class="w-6 h-6 block">
                                                        <path
                                                            d="M20 12h-9.5m7.5 3l3-3-3-3m-5-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2h5a2 2 0 002-2v-1" />
                                                    </svg>
                                                </a>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @else
                        <a @click="localStorage.setItem('activeForm', 'login')" href="{{ route('login') }}"
                            class="hover:opacity-80 p-1 xs:p-3 cursor-pointer truncate text-wrap max-w-20 sm:max-w-24 text-lg sm:text-xl !leading-4 xl:!leading-none font-semibold text-center transition ease-out duration-150">
                            {{ __('Log in') }}
                        </a>
                    @endauth
                </li>
            </ul>
        </div>
    </div>

    <div class="xl:hidden w-full px-0 xs:px-3 py-1 pb-2 flex z-[8]" @click.away="products= [],selectedIndex=-1">
        <form @submit.prevent="handleEnter" autocomplete="off"
            class="w-full self-center flex cursor-pointer relative" :class="openSidebar ? '' : 'z-[9]'">
            <div class="w-full flex h-10 m-0 bg-fondosearchmarketplace justify-center items-center rounded-3xl border-0.5 border-fondobuttonsearchmarketplace"
                :class="products.length ? 'rounded-b-none' : ''">
                <label for="searchheader-sm" class="absolute w-[1px] h-[1px] p-0 overflow-hidden">
                    Barra de búsqueda</label>
                <input type="text" name="search" autocomplete="off" x-model="search"
                    @input.debounce.300ms="fetchProducts" @keydown.enter.prevent="handleEnter"{{--  @blur="handleBlur" --}}
                    class="bg-transparent border-0 border-none w-full text-lg h-full leading-5 text-colorsearchmarketplace tracking-wide ring-0 focus:border-0 focus:ring-0 outline-none outline-0 focus:outline-none focus:border-none focus:shadow-none shadow-none"
                    placeholder="Buscar en Next">
            </div>
            <button type="submit" @click.prevent="handleEnter"
                class="bg-fondobuttonsearchmarketplace rounded-3xl focus:ring focus:ring-ringbuttonsearchmarketplace absolute right-0 box-border border-2 border-fondosearchmarketplace z-0 h-10 w-10 flex justify-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="block w-full h-full p-2 text-colorbuttonsearchmarketplace">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
            </button>

            <ul class="w-full block absolute left-0 top-[100%] py-2 rounded-b-3xl bg-fondominicard z-[999]"
                x-show="products.length">
                <div class="rounded p-1 overflow-y-auto max-h-[calc(100vh-120px)]">
                    <template x-for="product in products" :key="product.id">
                        <li class="w-full flex p-1">
                            <template x-if="product.image">
                                <div class="size-16 flex-shrink-0 rounded overflow-hidden">
                                    <img :src="product.image" alt=""
                                        class="w-full h-full object-scale-down">
                                </div>
                            </template>
                            <template x-if="product.image==null">
                                <div class="size-16 flex-shrink-0 rounded overflow-hidden">
                                    <x-icon-file-upload class="!w-full !h-full" type="unknown" />
                                </div>
                            </template>
                            <a x-html="highlight(product.name, search)"
                                class="block w-full flex-1 rounded text-colorsubtitleform p-2.5 px-1 text-[10px] leading-none hover:bg-fondohoverselect2"
                                :href="route('productos.show', product.slug)"></a>
                        </li>
                    </template>
                </div>
            </ul>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('searchnext', () => ({
                search: '',
                products: [],
                error: '',
                selectedIndex: -1,
                coincidencias: '',
                init() {
                    const url = new URL(window.location.href);
                    if (url.searchParams.get('coincidencias')) {
                        this.search = url.searchParams.get('coincidencias');
                    }
                    url.searchParams.forEach((value, key) => {
                        if (key == 'coincidencias') {
                            this.coincidencias = value
                        }
                    })
                },
                fetchProducts() {
                    this.openSidebar = false;
                    this.error = '',
                        fetch(`{{ route('api.producto.search') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                search: this.search
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            // console.log(data);
                            if (data.error) {
                                this.error = data.error;
                            } else {
                                // console.log(data.length);
                                this.products = data;
                                // console.log(this.products);
                                this.selectedIndex = -1;
                                if (data.length > 0) {
                                    this.backdrop = true;
                                    this.openSidebar = false;
                                    this.sidebar = this.isXL ? true : false;
                                    document.body.style.overflow = 'hidden';
                                }
                            }
                        })
                        .catch(() => {
                            this.error = 'There was an error processing your request.';
                            // console.log(this.error);
                        });
                },
                highlight(text, search) {
                    if (!search) return text;
                    const regex = new RegExp(`(${search.split(' ').join('|')})`, 'gi');
                    return text.replace(regex, '<span class="highlight">$1</span>');
                },
                route(name, id) {
                    const routes = {
                        'productos.show': '{{ route('productos.show', ':id') }}',
                    };

                    return routes[name].replace(':id', id);
                },
                redirectEnter() {
                    if (this.search.trim().length > 0) {
                        if (this.products.length == 0) {
                            window.location.href =
                                `{{ route('productos') }}?coincidencias=${encodeURIComponent(this.search)}`;
                        }
                        if (this.products.length > 1) {
                            window.location.href =
                                `{{ route('productos') }}?coincidencias=${encodeURIComponent(this.search)}`;
                        } else {
                            if (this.products.length == 1) {
                                window.location.href = this.route('productos.show', this.products[0]
                                    .slug);
                            }
                        }
                    } else {
                        this.handleBlur();
                    }
                },
                navigate(direction) {
                    if (this.products.length === 0) return;
                    if (direction === -1 && this.selectedIndex <= 0) {
                        this.selectedIndex = -1;
                        document.getElementById('search').focus();
                        return;
                    }
                    this.selectedIndex = (this.selectedIndex + direction + this.products.length) %
                        this.products.length;
                },
                setSelected(index) {
                    this.selectedIndex = index;
                },
                handleEnter() {
                    if (this.selectedIndex >= 0 && this.selectedIndex < this.products.length) {
                        window.location.href = this.route('productos.show', this.products[this
                            .selectedIndex].slug);
                    } else {
                        // Si no hay producto seleccionado, realizar la búsqueda general
                        this.redirectEnter();
                    }
                },
                handleBlur() {
                    const newURL = this.updateQueryString('coincidencias', this.search);
                    let coincidencias = '';
                    newURL.searchParams.forEach((value, key) => {
                        if (key == 'coincidencias') {
                            coincidencias = value
                        }
                    })

                    if (coincidencias != this.coincidencias) {
                        window.location.href = `{{ route('productos') }}${newURL.search}`;
                    }
                },
                updateQueryString(param, value) {
                    const url = new URL(window.location.href);
                    // // Reemplaza la URL en el navegador sin recargar la página
                    if (value == '') {
                        url.searchParams.delete(param);
                    } else {
                        url.searchParams.set(param, value);
                    }
                    window.history.replaceState(null, '', url);
                    return url;
                }
            }))
        })
    </script>
</div>
