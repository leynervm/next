<div class="fixed top-0 left-0 z-[101] w-full flex flex-col xl:flex-row flex-nowrap m-auto h-[70px] bg-next-900">
    <div class="w-full mx-auto flex relative">
        @if ($empresa->image)
            <div class="flex items-center bg-next-800">
                <a href="/" class="hidden xl:block xl:flex-1 w-40 px-5 h-[68%]">
                    <img class="mx-auto h-full w-full object-scale-down" src="{{ $empresa->image->getLogoEmpresa() }}"
                        alt="">
                    {{-- <x-isotipo-next class="text-black h-full mx-auto" /> --}}
                </a>
            </div>
        @endif

        <div class="flex-shrink-0 pl-1 xl:pl-0">
            <button class="hidden h-full xl:flex items-center button-sidebar" type="button">
                <div @mouseover.debounce.50ms="openSidebar=true, backdrop=true"
                    class="menu-hover flex rounded-sm h-8 w-8 z-10 transition ease-linear duration-150">
                    <div class="scale-[.5] xl:ml-2 relative">
                        <span class="icon-button-menu"></span>
                    </div>
                </div>
                <div class="">
                    <span class="mt-1 ml-2 font-semibold text-xl text-white">
                        Menú</span>
                </div>
            </button>
            <button class="flex h-full xl:hidden button-sidebar" type="button">
                <div @click="openSidebar=true, backdrop=true"
                    class="menu-hover bg-next-800 text-center flex rounded-sm h-full w-8 z-10 transition ease-linear duration-150">
                    <div class="scale-[.5] relative w-full">
                        <span class="icon-button-menu"></span>
                    </div>
                </div>
                @if ($empresa->image)
                    <a href="/" class="hidden xs:block xl:hidden w-20 h-full flex-shrink-0">
                        <img class="h-full w-auto m-auto object-center object-scale-down"
                            src="{{ $empresa->image->getLogoEmpresa() }}" alt="">
                        {{-- <x-isotipo-next class="text-white h-full mx-auto" /> --}}
                    </a>
                @endif
            </button>
        </div>

        {{-- <div data-wrapper="MS-MenuSis_Linio" class="h-full flex justify-center items-center p-0"
            data-testid="SiSNavigationMenuId">
            <div class="flex flex-col w-full h-full" data-testid="SiSNavigationMenuDesktop">
                <div class="h-full flex items-center" data-testid="CategoryButton">
                    <div class="CategoryButton-module_top-menu__1HTfC">
                        <div
                            class="flex h-[46px] w-32 py-1 pl-4 pr-3 items-center justify-between rounded-2xl gap-1 border border-white cursor-pointer text-white my-0 ml-14 mr-4 font-semibold">
                            Categorías
                            <div class="flex justify-center items-center">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-chevron-down">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="mr-6 relative flex-1 hidden xl:flex" x-data="productSearch()" @click.away="products= []">
            <form @submit.prevent="handleEnter" autocomplete="off"
                class="w-full bg-none self-center flex cursor-pointer" :class="openSidebar ? '' : 'z-[999]'">
                <div class="w-full flex h-[46px] m-0 bg-white justify-center items-center pl-6 rounded-3xl border-0.5 border-white"
                    :class="products.length ? 'rounded-b-none' : ''">
                    <label for="searchheader-xl" class="absolute w-[1px] h-[1px] p-0 overflow-hidden">
                        Barra de búsqueda</label>
                    <input type="search" name="search" autocomplete="off" x-ref="search" x-model="search"
                        @focus="backdrop=true,openSidebar=false" @input.debounce.300ms="fetchProducts"
                        @keydown.enter.prevent="handleEnter" @keydown.arrow-down.prevent="navigate(1)"
                        @keydown.arrow-up.prevent="navigate(-1)"
                        class="bg-transparent border-0 border-none w-full text-lg h-full leading-5 text-neutral-700 tracking-wide ring-0 focus:border-0 focus:ring-0 outline-none outline-0 focus:outline-none focus:border-none focus:shadow-none shadow-none"
                        placeholder="Buscar en Next Store" id="search">
                </div>
                <button
                    class="bg-next-700 rounded-3xl focus:ring focus:ring-next-500 absolute right-0 box-border border border-white z-10 h-[46px] w-[46px] flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="block w-full h-full p-2 text-white">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </button>
            </form>

            <ul class="w-full block absolute overflow-hidden left-0 top-[58px] p-1 rounded-b-3xl bg-fondominicard z-[999]"
                x-show="products.length">
                <template x-for="(product, index) in products" :key="product.id">
                    <li>
                        <a x-html="highlight(product.name, search)"
                            :class="{ 'bg-fondohoverselect2': index === selectedIndex }"
                            class="block w-full text-colorsubtitleform p-2 py-2.5 text-xs leading-3 hover:bg-fondohoverselect2"
                            :href="route('productos.show', product.slug)" @mouseenter="setSelected(index)"></a>
                    </li>
                </template>
            </ul>
        </div>

        <script>
            function productSearch() {
                return {
                    search: '',
                    products: [],
                    error: '',
                    selectedIndex: -1,
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
                                    this.products = data;
                                    this.selectedIndex = -1;
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
                            if (this.products.length > 1) {
                                window.location.href =
                                    `{{ route('productos') }}?coincidencias=${encodeURIComponent(this.search)}`;
                            } else {
                                if (this.products.length == 1) {
                                    window.location.href = this.route('productos.show', this.products[0].slug);
                                }
                            }
                        }
                    },
                    navigate(direction) {
                        if (this.products.length === 0) return;
                        if (direction === -1 && this.selectedIndex <= 0) {
                            this.selectedIndex = -1;
                            document.getElementById('search').focus();
                            return;
                        }
                        this.selectedIndex = (this.selectedIndex + direction + this.products.length) % this.products.length;
                    },
                    setSelected(index) {
                        this.selectedIndex = index;
                    },
                    handleEnter() {
                        if (this.selectedIndex >= 0 && this.selectedIndex < this.products.length) {
                            window.location.href = this.route('productos.show', this.products[this.selectedIndex].slug);
                        } else {
                            // Si no hay producto seleccionado, realizar la búsqueda general
                            this.redirectEnter();
                        }
                    }
                }
            }
        </script>


        <div class="w-full flex flex-1 sm:w-auto sm:flex-none sm:ml-auto">
            <ul class="w-full sm:w-auto flex justify-end h-full m-0 p-0" x-data="{ login: false }">
                <li
                    class="group relative h-[68%] flex self-center min-w-[50px] w-auto flex-shrink-0 xl:border-r border-white transition ease-out duration-150">
                    @auth
                        <a class="hidden h-full lg:flex items-center p-3 px-1 sm:px-3 font-semibold text-white text-lg leading-4 cursor-pointer group-hover:opacity-80"
                            href="{{ route('orders') }}">
                            Mis<br>compras
                        </a>
                    @else
                        <div class="p-3 px-1 sm:px-3 h-ful flex gap-2 w-full theme-switcher justify-center items-center">
                            <button title="Light" theme="theme-next" type="button"
                                class="block theme-switcher-button rounded-full bg-transparent text-white">
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
                                class="block theme-switcher-button rounded-full bg-transparent text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                    class="block w-8 h-8 p-1">
                                    <path
                                        d="M21.5 14.0784C20.3003 14.7189 18.9301 15.0821 17.4751 15.0821C12.7491 15.0821 8.91792 11.2509 8.91792 6.52485C8.91792 5.06986 9.28105 3.69968 9.92163 2.5C5.66765 3.49698 2.5 7.31513 2.5 11.8731C2.5 17.1899 6.8101 21.5 12.1269 21.5C16.6849 21.5 20.503 18.3324 21.5 14.0784Z" />
                                </svg>
                            </button>
                        </div>
                    @endauth
                </li>
                <li class="group relative h-[68%] flex self-center xl:border-r border-white">
                    <a class="flex w-full h-full justify-center items-center p-3 px-1 sm:px-3 text-white cursor-pointer group-hover:opacity-80 transition ease-out duration-150"
                        href="{{ route('wishlist') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="block w-8 h-8">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M9.73 17.753l-5.23 -5.181a5 5 0 1 1 7.5 -6.566a5 5 0 0 1 8.563 5.041" />
                            <path
                                d="M17.8 20.817l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l2.428 -.352l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.567 .411l-2.172 -1.138z" />
                        </svg>
                        <span id="counterwishlist"
                            class="{{ Cart::instance('wishlist')->count() == 0 ? 'hidden' : 'flex' }} absolute w-4 h-4 top-0 right-1 tracking-tight h-100 justify-center items-center leading-3 text-[9px] bg-white text-next-500 rounded-full">
                            {{ Cart::instance('wishlist')->count() }}
                        </span>
                    </a>
                </li>
                <li class="group relative h-[68%] flex self-center transition ease-out duration-150">
                    <livewire:modules.marketplace.carrito.counter-carrito :empresa="$empresa" :moneda="$moneda"
                        :pricetype="$pricetype" />
                </li>
                <li class="relative h-[68%] flex items-center self-center xl:border-l border-white ">
                    @auth
                        <div @mouseover="login = true" @mouseover.outside="login = false">
                            <button
                                class="h-full flex justify-center items-center p-3 px-1 sm:px-3 cursor-pointer text-white group-hover:opacity-80 transition ease-out duration-150">
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
                                class="absolute w-48 top-12 right-0 bg-fondodropdown rounded-xl p-2"
                                :class="backdrop ? 'z-[1]' : 'z-[1000]'">
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
                                        <li>
                                            <p class="block w-full text-center text-xs font-normal p-2.5 text-colordropdown"
                                                style="text-wrap:wrap;">
                                                {{ Auth::user()->name }}</p>
                                        </li>
                                        @if ($pricetype)
                                            <li>
                                                <p
                                                    class="block w-full text-center text-xs font-normal p-2.5 text-colordropdown">
                                                    {{ $pricetype->name }}</p>
                                            </li>
                                        @endif
                                        @if (auth()->user()->isAdmin() || auth()->user()->sucursal)
                                            <li>
                                                <a class="block w-full text-center text-sm font-medium p-2.5 rounded-lg text-colordropdown hover:bg-fondohoverdropdown hover:text-textohoverdropdown transition ease-in-out duration-150"
                                                    href="{{ route('admin') }}">{{ __('Dashboard') }}</a>
                                            </li>
                                        @endif
                                        <li>
                                            <a class="block w-full text-center text-sm font-medium p-2.5 rounded-lg text-colordropdown hover:bg-fondohoverdropdown hover:text-textohoverdropdown transition ease-in-out duration-150"
                                                href="{{ route('profile') }}">{{ __('Profile') }}</a>
                                        </li>
                                        <li>
                                            <form class="w-full text-center" method="POST"
                                                action="{{ route('logout') }}" x-data>
                                                @csrf
                                                <a href="{{ route('logout') }}" @click.prevent="$root.submit();"
                                                    class="block w-full text-center text-sm font-medium p-2.5 rounded-lg text-colordropdown hover:bg-fondohoverdropdown hover:text-textohoverdropdown transition ease-in-out duration-150">
                                                    {{ __('Log Out') }}</a>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @else
                        <a @click="localStorage.setItem('activeForm', 'login')" href="{{ route('login') }}"
                            class="hover:opacity-801 p-3 px- cursor-pointer whitespace-normal truncate leading-3 font-semibold text-white text-lg sm:text-xl transition ease-out duration-150">
                            {{ __('Log in') }}
                        </a>
                    @endauth
                </li>
            </ul>
        </div>
    </div>

    <div class="xl:hidden w-full bg-next-800 p-3 py-1 pb-2 flex" x-data="productSearch()" @click.away="products= []">
        <div class="w-full self-center flex cursor-pointer relative" :class="openSidebar ? '' : 'z-[999]'">
            <div class="w-full flex h-10 m-0 bg-white justify-center items-center rounded-3xl border-0.5 border-white"
                :class="products.length ? 'rounded-b-none' : ''">
                <label for="searchheader-sm" class="absolute w-[1px] h-[1px] p-0 overflow-hidden">
                    Barra de búsqueda</label>
                <input type="text" name="search" autocomplete="off" x-model="search" @focus="backdrop=true"
                    @input.debounce.300ms="fetchProducts"
                    class="bg-transparent border-0 border-none w-full text-lg h-full leading-5 text-neutral-700 tracking-wide ring-0 focus:border-0 focus:ring-0 outline-none outline-0 focus:outline-none focus:border-none focus:shadow-none shadow-none"
                    value="" placeholder="Buscar en Next Store">
            </div>
            <button
                class="bg-next-700 rounded-3xl focus:ring focus:ring-next-500 absolute right-0 box-border border border-white z-10 h-10 w-10 flex justify-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="block w-full h-full p-2 text-white">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
            </button>

            <ul class="w-full block absolute overflow-hidden left-0 top-10 p-1 rounded-b-3xl bg-fondominicard z-[999]"
                x-show="products.length">
                <template x-for="product in products" :key="product.id">
                    <li>
                        <a x-html="highlight(product.name, search)"
                            class="block w-full text-colorsubtitleform p-2 text-xs leading-3 hover:bg-neutral-100"
                            :href="route('productos.show', product.slug)"></a>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</div>
