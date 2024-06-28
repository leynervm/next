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

        <div class="flex justify-center items-center">
            <button class="hidden xl:flex button-sidebar" type="button">
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
            <button class="flex xl:hidden button-sidebar" type="button">
                <div @click="openSidebar=true, backdrop=true"
                    class="menu-hover flex rounded-sm h-8 w-8 z-10 transition ease-linear duration-150">
                    <div class="scale-[.5] xl:ml-2 relative">
                        <span class="icon-button-menu"></span>
                    </div>
                </div>
                @if ($empresa->image)
                    <a href="/" class="hidden xs:block xl:hidden w-20 h-[68%] flex-shrink-0">
                        <img class="h-full object-scale-down" src="{{ $empresa->image->getLogoEmpresa() }}"
                            alt="">
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
            <div class="w-full bg-none self-center flex cursor-pointer" :class="openSidebar ? '' : 'z-[999]'">
                <div class="w-full flex h-[46px] m-0 bg-white justify-center items-center pl-6 rounded-3xl border-0.5 border-white"
                    :class="products.length ? 'rounded-b-none' : ''">
                    <label for="searchheader-xl" class="absolute w-[1px] h-[1px] p-0 overflow-hidden">
                        Barra de búsqueda</label>
                    <input type="text" autocomplete="off" x-model="search" @focus="backdrop=true,openSidebar=false"
                        @input.debounce.300ms="fetchProducts" @keydown.enter="redirectEnter"
                        class="bg-transparent border-0 border-none w-full text-lg h-full leading-5 text-neutral-700 tracking-wide ring-0 focus:border-0 focus:ring-0 outline-none outline-0 focus:outline-none focus:border-none focus:shadow-none shadow-none"
                        value="" placeholder="Buscar en NEXT">
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
            </div>

            <ul class="w-full block absolute overflow-hidden left-0 top-[58px] p-1 rounded-b-3xl bg-fondominicard z-[999]"
                x-show="products.length">
                <template x-for="product in products" :key="product.id">
                    <li>
                        <a x-html="highlight(product.name, search)"
                            class="block w-full text-colorsubtitleform p-2 text-xs leading-3 hover:bg-fondohoverselect2"
                            :href="route('productos.show', product.slug)"></a>
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
                            // window.location.href = `{{ route('productos') }}?coincidencias=${encodeURIComponent(this.search)}`;
                        }
                    }
                }
            }
        </script>


        <div class="ml-auto w-auto flex">
            <ul class="flex justify-end h-full m-0 p-0" x-data="{ login: false }">
                <li @mouseover="login = true" @mouseover.outside="login = false"
                    class="group relative h-[68%] flex flex-col justify-center items-center cursor-pointer xl:whitespace-nowrap xl:min-w-[50px] w-auto flex-shrink-0 p-0 xl:border-r border-white self-center transition ease-out duration-150">
                    <div class="h-full">
                        <div class="h-full relative flex justify-center items-center cursor-pointer">
                            <div>
                                <div class="flex h-full py-4">
                                    <div class="pr-4 leading-4">
                                        <div class="flex">
                                            @auth
                                                <p
                                                    class="group-hover:opacity-80 min-w-[50px] max-w-[120px] whitespace-normal xl:truncate leading-4 font-semibold text-white text-xs xl:text-sm mt-1 transition ease-out duration-150">
                                                    {{ Auth::user()->name }}
                                                </p>
                                                <svg class="flex-shrink-0 text-white block mt-1 ml-1 w-5 h-5"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-chevron-down">
                                                    <path d="m6 9 6 6 6-6" />
                                                </svg>
                                            @else
                                                <a @click="localStorage.setItem('activeForm', 'login')"
                                                    href="{{ route('login') }}"
                                                    class="group-hover:opacity-80 max-w-[120px] whitespace-normal xl:truncate leading-4 font-semibold text-white text-xs xl:text-xl mt-1 transition ease-out duration-150">
                                                    {{ __('Log in') }}
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                                @auth
                                    <div class="absolute w-full h-full top-0 right-0 xl:left-0"
                                        :class="backdrop ? 'z-[1]' : 'z-[1000]'">
                                        <div x-show="login" x-cloak x-transition style="display: none"
                                            class="absolute right-0 xl:-right-[56px] top-[35px] xl:top-[52px] shadow-md p-2 bg-fondominicard rounded-xl w-full min-w-[200px] xs:min-w-[226px] outline-none z-20">
                                            {{-- <div class="block text-xs text-colorsubtitleform border-b border-neutral-200">
                                            {{ __('Manage Account') }}
                                        </div> --}}
                                            <div class="w-full p-3">

                                                {{-- <button type="button"
                                                    class="w-10 h-10 ml-auto bg-transparent text-[10px] relative block text-white cursor-pointer font-semibold rounded-full transition-all ease-in-out duration-150">
                                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                                        <img class="h-full w-full rounded-full object-cover"
                                                            src="{{ Auth::user()->profile_photo_url }}"
                                                            alt="{{ Auth::user()->name }}" />
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2" fill="none"
                                                            class="w-full h-full block">
                                                            <circle cx="12" cy="12" r="10" />
                                                            <path
                                                                d="M7.5 17C9.8317 14.5578 14.1432 14.4428 16.5 17M14.4951 9.5C14.4951 10.8807 13.3742 12 11.9915 12C10.6089 12 9.48797 10.8807 9.48797 9.5C9.48797 8.11929 10.6089 7 11.9915 7C13.3742 7 14.4951 8.11929 14.4951 9.5Z" />
                                                        </svg>
                                                    @endif
                                                </button> --}}

                                                @if ($pricetype)
                                                    <p class="block text-center text-xs text-colorsubtitleform">
                                                        {{ $pricetype->name }}
                                                    </p>
                                                @endif

                                                <p class="w-full text-xs text-center text-colorlabel"
                                                    style="text-wrap:wrap;">
                                                    {{ Auth::user()->name }}</p>

                                                @if (auth()->user()->sucursal)
                                                    <x-link-web href="{{ route('admin') }}" class="mx-auto"
                                                        :text="__('Dashboard')" />
                                                @endif


                                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                                    <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">
                                                        {{ __('API Tokens') }}
                                                    </x-jet-dropdown-link>
                                                @endif

                                                <div class="w-full mt-5 text-center">
                                                    <a href="{{ route('profile') }}"
                                                        class="custom-btn uppercase mx-auto block w-full align-middle text-center">{{ __('Profile') }}<span></span>
                                                    </a>
                                                </div>


                                                <!-- Authentication -->
                                                <form class="w-full text-center" method="POST"
                                                    action="{{ route('logout') }}" x-data>
                                                    @csrf

                                                    <a href="{{ route('logout') }}" @click.prevent="$root.submit();"
                                                        class="custom-btn uppercase mx-auto block w-full align-middle text-center">{{ __('Log Out') }}<span></span>
                                                    </a>
                                                    {{-- <x-link-web href="{{ route('logout') }}" class="mx-auto"
                                                        @click.prevent="$root.submit();" :text="__('Log Out')" /> --}}
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </li>
                <li
                    class="group relative h-[68%] hidden xl:flex flex-col justify-center items-center cursor-pointer px-0 min-w-[50px] w-auto flex-shrink-0 p-0 xl:border-r border-white self-center transition ease-out duration-150">
                    <a class="p-4 relative flex flex-col justify-center items-start h-full w-full text-center group-hover:opacity-80"
                        href="{{ route('orders') }}">
                        <span class="font-semibold text-white text-lg leading-4 ">Mis</span>
                        <span class="font-semibold text-white text-lg leading-4 mt-0.5">compras</span>
                    </a>
                </li>
                <li
                    class="group relative h-[68%] flex flex-col justify-center items-center cursor-pointer px-0 whitespace-nowrap min-w-[50px] max-w-[120px] xs:max-w-full w-auto flex-shrink-0 p-0 xl:border-r border-white self-center transition ease-out duration-150">
                    <div>
                        <a class="flex w-full h-full justify-center items-center text-white group-hover:opacity-80"
                            href="{{ route('wishlist') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="block w-8 h-8">
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
                    </div>
                </li>
                <li
                    class="group relative h-[68%] flex flex-col justify-center items-center cursor-pointer px-0 xl:min-w-[83px] w-auto flex-shrink-0 p-0 self-center transition ease-out duration-150">
                    <div class="relative flex flex-col justify-center items-start w-full h-full pr-3 xl:pr-0 xl:pl-4">
                        <livewire:modules.marketplace.carrito.counter-carrito :empresa="$empresa" :moneda="$moneda"
                            :pricetype="$pricetype" />
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="xl:hidden w-full bg-next-800 p-3 py-1 pb-2 flex" x-data="productSearch()" @click.away="products= []">
        <div class="w-full self-center flex cursor-pointer relative" :class="openSidebar ? '' : 'z-[999]'">
            <div class="w-full flex h-10 m-0 bg-white justify-center items-center pl-6 rounded-3xl border-0.5 border-white"
                :class="products.length ? 'rounded-b-none' : ''">
                <label for="searchheader-sm" class="absolute w-[1px] h-[1px] p-0 overflow-hidden">
                    Barra de búsqueda</label>
                <input type="text" autocomplete="off" x-model="search" @focus="backdrop=true"
                    @input.debounce.300ms="fetchProducts"
                    class="bg-transparent border-0 border-none w-full text-lg h-full leading-5 text-neutral-700 tracking-wide ring-0 focus:border-0 focus:ring-0 outline-none outline-0 focus:outline-none focus:border-none focus:shadow-none shadow-none"
                    value="" placeholder="Buscar en NEXT">
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
