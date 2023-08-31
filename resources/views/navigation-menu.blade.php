<nav class="h-full overflow-y-auto overflow-x-hidden bg-body/50 md:block md:flex-1 ease-in-out duration-300">
    <div class="flex flex-col h-full py-1 justify-between">
        <ul class="w-full text-center px-1 space-y-1">

            {{-- <li class="">
                <x-nav-link href="#" class="">
                    <x-slot name="titulo">HOME</x-slot>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 10 2 2 4-4" />
                        <rect width="20" height="14" x="2" y="3" rx="2" />
                        <path d="M12 17v4" />
                        <path d="M8 21h8" />
                    </svg>
                </x-nav-link>
            </li> --}}

            <li class="">
                <x-nav-link href="/admin" class="">
                    <x-slot name="titulo">Inicio</x-slot>
                    {{-- <span class="hidden md:block">Inicio</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 p-1.5">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                </x-nav-link>
            </li>
            <li class="">
                <x-nav-link href="{{ route('admin.soporte') }}" class="" :active="request()->routeIs('admin.soporte*')">
                    <x-slot name="titulo">Soporte Técnico</x-slot>
                    {{-- <span class="hidden md:block">Soporte Técnico</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 10 2 2 4-4" />
                        <rect width="20" height="14" x="2" y="3" rx="2" />
                        <path d="M12 17v4" />
                        <path d="M8 21h8" />
                    </svg>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="/admin/internet" class="">
                    <x-slot name="titulo">Servicio Internet</x-slot>
                    {{-- <span class="hidden md:block">Servicio Internet</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 10a7.31 7.31 0 0 0 10 10Z" />
                        <path d="m9 15 3-3" />
                        <path d="M17 13a6 6 0 0 0-6-6" />
                        <path d="M21 13A10 10 0 0 0 11 3" />
                    </svg>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="{{ route('admin.ventas') }}" :active="request()->routeIs('admin.ventas*')">
                    <x-slot name="titulo">Ventas</x-slot>
                    {{-- <span class="hidden md:block">Ventas</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" width="24" height="24"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M11.5 21h-2.926a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304h11.339a2 2 0 0 1 1.977 2.304l-.117 .761">
                        </path>
                        <path d="M9 11v-5a3 3 0 0 1 6 0v5" />
                        <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                        <path d="M20.2 20.2l1.8 1.8" />
                    </svg>
                    {{-- <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <line x1="3" x2="21" y1="6" y2="6" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg> --}}
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="#" class="">
                    <x-slot name="titulo">Facturación</x-slot>
                    {{-- <span class="hidden md:block">Facturación</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 2v5h5" />
                        <path d="M21 6v6.5c0 .8-.7 1.5-1.5 1.5h-7c-.8 0-1.5-.7-1.5-1.5v-9c0-.8.7-1.5 1.5-1.5H17l4 4z" />
                        <path d="M7 8v8.8c0 .3.2.6.4.8.2.2.5.4.8.4H15" />
                        <path d="M3 12v8.8c0 .3.2.6.4.8.2.2.5.4.8.4H11" />
                    </svg>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="{{ route('admin.cajas') }}" class="" :active="request()->routeIs('admin.cajas*')">
                    <x-slot name="titulo">Caja Chica</x-slot>
                    {{-- <span class="hidden md:block">Caja Chica</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="8" cy="8" r="6" />
                        <path d="M18.09 10.37A6 6 0 1 1 10.34 18" />
                        <path d="M7 6h1v4" />
                        <path d="m16.71 13.88.7.71-2.82 2.82" />
                    </svg>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="{{ route('admin.almacen') }}" class="" :active="request()->routeIs('admin.almacen*')">
                    <x-slot name="titulo">Almacén</x-slot>
                    {{-- <span class="hidden md:block">Almacén</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 2 7 12 12 22 7 12 2" />
                        <polyline points="2 17 12 22 22 17" />
                        <polyline points="2 12 12 17 22 12" />
                    </svg>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="#" class="">
                    <x-slot name="titulo">Reportes</x-slot>
                    {{-- <span class="hidden md:block">Reportes</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3v18h18" />
                        <rect width="4" height="7" x="7" y="10" rx="1" />
                        <rect width="4" height="12" x="15" y="5" rx="1" />
                    </svg>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="#" class="">
                    <x-slot name="titulo">Gráficos</x-slot>
                    {{-- <span class="hidden md:block">Gráficos</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21.21 15.89A10 10 0 1 1 8 2.83" />
                        <path d="M22 12A10 10 0 0 0 12 2v10z" />
                    </svg>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="{{ route('admin.clientes') }}" :active="request()->routeIs('admin.clientes*') ||
                    request()->routeIs('admin.pricetypes*') ||
                    request()->routeIs('admin.channelsales*')">
                    <x-slot name="titulo">Clientes</x-slot>
                    {{-- <span class="hidden md:block">Clientes</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 19a6 6 0 0 0-12 0" />
                        <circle cx="8" cy="9" r="4" />
                        <path d="M22 19a6 6 0 0 0-6-6 4 4 0 1 0 0-8" />
                    </svg>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="#" class="">
                    <x-slot name="titulo">Proveedores</x-slot>
                    {{-- <span class="hidden md:block">Proveedores</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 17h4V5H2v12h3" />
                        <path d="M20 17h2v-3.34a4 4 0 0 0-1.17-2.83L19 9h-5" />
                        <path d="M14 17h1" />
                        <circle cx="7.5" cy="17.5" r="2.5" />
                        <circle cx="17.5" cy="17.5" r="2.5" />
                    </svg>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="#" class="">
                    <x-slot name="titulo">Sitio Web</x-slot>
                    {{-- <span class="hidden md:block">Sitio Web</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="2" x2="22" y1="12" y2="12" />
                        <path
                            d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                    </svg>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="#" class="">
                    <x-slot name="titulo">Cotizaciones</x-slot>
                    {{-- <span class="hidden md:block">Cotizaciones</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="8" height="4" x="8" y="2" rx="1"
                            ry="1" />
                        <path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-.5" />
                        <path d="M16 4h2a2 2 0 0 1 1.73 1" />
                        <path d="M18.42 9.61a2.1 2.1 0 1 1 2.97 2.97L16.95 17 13 18l.99-3.95 4.43-4.44Z" />
                        <path d="M8 18h1" />
                    </svg>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="{{ route('admin.administracion') }}" class="">
                    <x-slot name="titulo">Administración</x-slot>
                    {{-- <span class="hidden md:block">Administración</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="M8 10v8h8" />
                        <path d="m8 18 4-4" />
                    </svg>
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="#" class="">
                    <x-slot name="titulo">Negocios Web</x-slot>
                    {{-- <span class="hidden md:block">Negocios Web</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" x2="21" y1="22" y2="22" />
                        <line x1="6" x2="6" y1="18" y2="11" />
                        <line x1="10" x2="10" y1="18" y2="11" />
                        <line x1="14" x2="14" y1="18" y2="11" />
                        <line x1="18" x2="18" y1="18" y2="11" />
                        <polygon points="12 2 20 7 4 7" />
                    </svg>
                </x-nav-link>
            </li>
        </ul>

        <ul class="w-full text-center p-1 space-y-1">
            <li>
                <x-nav-link href="#" class="">
                    <x-slot name="titulo">Personalización</x-slot>
                    {{-- <span class="hidden md:block">Personalización</span> --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="m21.64 3.64-1.28-1.28a1.21 1.21 0 0 0-1.72 0L2.36 18.64a1.21 1.21 0 0 0 0 1.72l1.28 1.28a1.2 1.2 0 0 0 1.72 0L21.64 5.36a1.2 1.2 0 0 0 0-1.72Z" />
                        <path d="m14 7 3 3" />
                        <path d="M5 6v4" />
                        <path d="M19 14v4" />
                        <path d="M10 2v2" />
                        <path d="M7 8H3" />
                        <path d="M21 16h-4" />
                        <path d="M11 3H9" />
                    </svg>
                </x-nav-link>
            </li>
        </ul>
    </div>
</nav>


{{-- <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-jet-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-jet-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-jet-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ml-3 relative">
                        <x-jet-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-jet-dropdown-link
                                        href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-jet-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-jet-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-jet-dropdown-link>
                                    @endcan

                                    <div class="border-t border-gray-100"></div>

                                    <!-- Team Switcher -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Switch Teams') }}
                                    </div>

                                    @foreach (Auth::user()->allTeams() as $team)
                                        <x-jet-switchable-team :team="$team" />
                                    @endforeach
                                </div>
                            </x-slot>
                        </x-jet-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ml-3 relative">
                    <x-jet-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button
                                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover"
                                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                        {{ Auth::user()->name }}

                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-jet-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-jet-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-jet-dropdown-link>
                            @endif

                            <div class="border-t border-gray-100"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-jet-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-jet-dropdown-link>
                            </form>
                        </x-slot>
                    </x-jet-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-jet-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-jet-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 mr-3">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                            alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-jet-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-jet-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-jet-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-jet-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-jet-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-jet-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-jet-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                        :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-jet-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-jet-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-jet-responsive-nav-link>
                    @endcan

                    <div class="border-t border-gray-200"></div>

                    <!-- Team Switcher -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Switch Teams') }}
                    </div>

                    @foreach (Auth::user()->allTeams() as $team)
                        <x-jet-switchable-team :team="$team" component="jet-responsive-nav-link" />
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</nav> --}}
