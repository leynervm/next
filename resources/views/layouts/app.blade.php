<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    @php
        $empresa = \App\Models\Empresa::first();
    @endphp

    @if ($empresa)
        @if ($empresa->icono)
            <link rel="icon" type="image/x-icon" href="{{ Storage::url('images/company/' . $empresa->icono) }}">
        @endif
    @endif

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- Fonts -->
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"> --}}
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap"> --}}

    <!-- Styles -->
    @livewireStyles

    <link rel="stylesheet" href="{{ asset('assets/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/sweetAlert2/sweetalert2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/animate/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />

    <!-- Scripts -->
</head>

<body class="{{ config('app.theme') }}">
    <div class="relative flex h-screen min-h-screen overflow-hidden">
        @auth
            <div class="w-16 h-16 flex md:hidden fixed z-20 text-center p-1 justify-center items-center">
                <button type="button" id="sidebar-close-toggle"
                    class="inline-block bg-fondolinknav shadow text-iconmenu focus:outline-none rounded-xs p-1.5 rounded-lg hover:shadow focus:shadow hover:bg-hoverlinknav hover:text-hovercolorlinknav focus:bg-hoverlinknav focus:text-hovercolorlinknav transition-colors ease-in-out duration-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 p-1.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-layout-grid">
                        <rect width="7" height="7" x="3" y="3" rx="1"></rect>
                        <rect width="7" height="7" x="14" y="3" rx="1"></rect>
                        <rect width="7" height="7" x="14" y="14" rx="1"></rect>
                        <rect width="7" height="7" x="3" y="14" rx="1"></rect>
                    </svg>
                </button>
            </div>
            {{-- QUIT md:w-64 --}}
            <div class="bg-sidebar fixed z-10 -translate-x-full md:w-16 md:translate-x-0 md:relative w-16 h-screen flex flex-col border-r border-colorlinknav transition-width ease-in duration-100"
                id="menu">
                <div class="md:flex w-full h-16 px-1 items-center justify-center bg-sidebar" id="sidebar-header">

                    {{-- <h2 id="title-sidebar" class="text-lg font-semibold text-white"> --}}
                    {{-- </h2> --}}
                    <div class="h-12 w-32 hidden ease-in-out duration-100" id="logo-sidebar">
                        {{-- <img class="w-full h-full object-scale-down object-center"
                            src="{{ asset('assets/settings/login.png') }}" alt=""> --}}

                        @if ($empresa)
                            @if ($empresa->image)
                                <img class="w-full h-full object-scale-down object-center"
                                    src="{{ Storage::url('images/company/' . $empresa->image->url) }}" alt="">
                            @endif
                        @endif

                    </div>
                    <button type="button" id="sidebar-toggle"
                        class="hidden md:block text-iconmenu shadow focus:outline-none rounded-xs p-1.5 rounded-lg hover:shadow focus:shadow hover:bg-hoverlinknav hover:text-hovercolorlinknav focus:bg-hoverlinknav focus:text-hovercolorlinknav transition-colors ease-in-out duration-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 p-1.5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-layout-grid">
                            <rect width="7" height="7" x="3" y="3" rx="1"></rect>
                            <rect width="7" height="7" x="14" y="3" rx="1"></rect>
                            <rect width="7" height="7" x="14" y="14" rx="1"></rect>
                            <rect width="7" height="7" x="3" y="14" rx="1"></rect>
                        </svg>
                    </button>
                </div>

                @livewire('navigation-menu')

            </div>
            <div class="flex-1 p-3 md:px-5 overflow-y-auto bg-body">
                <main class="shadow">

                    <!-- Settings Dropdown -->
                    <div class="relative flex items-center justify-end">
                        <button type="button"
                            class="relative inline-flex p-1.5 justify-center items-center text-colorlinknav rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8">
                                <path fill-rule="evenodd"
                                    d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <x-jet-dropdown align="right" width="48" contentClasses="py-1 bg-fondodropdown">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <button disabled
                                        class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <img class="h-8 w-8 rounded-full object-cover"
                                            src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                    </button>
                                @else
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="shadow-colorlinknav relative inline-flex md:flex p-1.5 justify-center md:justify-start items-center text-colorlinknav cursor-pointer font-semibold rounded-lg shadow hover:text-hovercolorlinknav hover:bg-hoverlinknav focus:bg-hoverlinknav focus:text-hovercolorlinknav transition-all ease-in-out duration-150">
                                            <span class="text-xs p-1.5">{{ Auth::user()->name }}</span>
                                            <svg class="h-8 w-8 p-1.5" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{-- <svg xmlns="http://www.w3.org/2000/svg" class="bg-colorlinknav text-white ml-2 -mr-0.5 h-4 w-4"
                                                viewBox="0 0 512 512">
                                                <path
                                                    d="M98 190.06l139.78 163.12a24 24 0 0036.44 0L414 190.06c13.34-15.57 2.28-39.62-18.22-39.62h-279.6c-20.5 0-31.56 24.05-18.18 39.62z" />
                                            </svg> --}}
                                        </button>
                                    </span>
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-colorlinknav">
                                    {{ __('Manage Account') }}
                                </div>

                                {{-- <x-jet-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-jet-dropdown-link> --}}

                                <x-nav-link class="px-5 gap-2 shadow-none rounded-none w-full justify-stretch"
                                    href="{{ route('profile.show') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ __('Profile') }}</span>
                                </x-nav-link>

                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">
                                        {{ __('API Tokens') }}
                                    </x-jet-dropdown-link>
                                @endif

                                {{-- <div class="border-t border-colorlinknav"></div> --}}

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-nav-link class="px-5 gap-2 shadow-none rounded-none w-full justify-stretch"
                                        href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                        </svg>
                                        <span>{{ __('Log Out') }}</span>
                                        {{-- <x-jet-dropdown-link href="{{ route('logout') }}"
                                        @click.prevent="$root.submit();">
                                        {{ __('Log Out') }}
                                    </x-jet-dropdown-link> --}}
                                    </x-nav-link>
                                </form>
                            </x-slot>
                        </x-jet-dropdown>
                    </div>

                    {{ $slot }}
                </main>
            </div>
        @endauth
    </div>

    @stack('modals')

    @livewireScripts

    <script src="{{ asset('assets/select2/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/sweetAlert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- <script src="{{ asset('assets/ckeditor5/ckeditor5_38.1.1_super-build_ckeditor.js') }}"></script> --}}
    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/38.1.1/super-build/ckeditor.js"></script> --}}
    {{-- Translation español super-build CKEditor5 --}}
    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/38.1.1/super-build/translations/es.js"></script> --}}


    @yield('scripts')

</body>

<script>
    // document.addEventListener('Livewire:load', function() {

    var toastMixin = Swal.mixin({
        toast: true,
        icon: "success",
        title: "Mensaje",
        position: "top-right",
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
    });

    window.addEventListener('created', event => {
        toastMixin.fire({
            title: 'Registrado correctamente'
        });
    })

    window.addEventListener('updated', event => {
        toastMixin.fire({
            title: 'Actualizado correctamente'
        });
    })

    window.addEventListener('deleted', event => {
        toastMixin.fire({
            title: 'Eliminado correctamente'
        });
    })

    window.addEventListener('validation', data => {
        console.log(data.detail);
        swal.fire({
            title: data.detail.title,
            text: data.detail.text,
            icon: 'info',
            confirmButtonColor: '#0FB9B9',
            confirmButtonText: 'Cerrar',
        })
    })

    // window.addEventListener('validation-deleted', data => {
    //     swal.fire({
    //         title: data.detail,
    //         text: "",
    //         icon: 'question',
    //         showCancelButton: false,
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Confirmar',
    //         confirmButtonColor: '#0FB9B9',
    //         confirmButtonText: 'Cerrar',
    //     })
    // });

    // window.addEventListener('validation', event => {
    //     toastMixin.fire({
    //         title: event.detail,
    //         icon: "warning",
    //         timer: 3000,
    //     });
    // })


    const sidebar = document.getElementById('menu');
    const header_sidebar = document.getElementById('sidebar-header');
    const logo_sidebar = document.getElementById('logo-sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const buttonMobileToggle = document.getElementById('sidebar-close-toggle');
    // const navigation = document.getElementById('navigation');
    // const menu_nav = document.getElementById('menu_navigation');
    const isSidebarOpen = localStorage.getItem('isSidebarOpen') === 'true';
    const itemLinks = sidebar.querySelectorAll('li a');
    const spanLinks = sidebar.querySelectorAll('li a span');
    const iconLinks = sidebar.querySelectorAll('li a svg');

    const isSidebarMobileOpen = localStorage.getItem('isSidebarMobileOpen') === 'true';

    console.log(isSidebarOpen);

    if (isSidebarOpen) {
        sidebar.classList.toggle('md:w-16');
        sidebar.classList.toggle('md:w-48');
        header_sidebar.classList.toggle('justify-between');
        header_sidebar.classList.toggle('justify-center');
        logo_sidebar.classList.toggle('md:block');
        itemLinks.forEach(element => {
            element.classList.toggle('md:flex');
            element.classList.toggle('group');
        });
        spanLinks.forEach(element => {
            element.classList.toggle('md:block');
        });
        iconLinks.forEach(element => {
            // element.classList.toggle('mx-auto');
        });
    }


    // if (isSidebarMobileOpen) {
    //     sidebar.classList.toggle('-translate-x-full');
    //     sidebar.classList.toggle('md:flex');
    //     sidebar.classList.toggle('transition-all');
    // }

    // console.log("Contiene clase w-16 : " + sidebar.classList.contains('w-16'));
    // console.log("Contiene clase w-64 : " + sidebar.classList.contains('w-64'));

    sidebarToggle.addEventListener('click', () => {

        // console.log("Contiene clase w-16 Negacion: " + !sidebar.classList.contains('w-16'))
        sidebar.classList.toggle('md:w-16');
        sidebar.classList.toggle('md:w-48');
        header_sidebar.classList.toggle('justify-between');
        header_sidebar.classList.toggle('justify-center');
        logo_sidebar.classList.toggle('md:block');
        logo_sidebar.classList.toggle('transition-all');
        itemLinks.forEach(element => {
            element.classList.toggle('md:flex');
            element.classList.toggle('group');
        });
        spanLinks.forEach(element => {
            element.classList.toggle('md:block');
        });
        iconLinks.forEach(element => {
            // element.classList.toggle('mx-auto');
        });

        localStorage.setItem('isSidebarOpen', !sidebar.classList.contains('md:w-16'));
    });

    buttonMobileToggle.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        sidebar.classList.toggle('md:flex');
        sidebar.classList.toggle('transition-all');

        localStorage.setItem('isSidebarMobileOpen', !sidebar.classList.contains(
            '-translate-x-full'));

    })


    const elementos = document.querySelectorAll(".prevent");

    elementos.forEach(elemento => {
        elemento.addEventListener("keydown", (e) => {
            if (e.key == "Enter") {
                // console.log(e.key);
                e.preventDefault();
                return false;
            }
        });
    });

    const numericInputs = document.querySelectorAll(".numeric");

    numericInputs.forEach(elemento => {
        elemento.addEventListener("keydown", function(event) {
            if (event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') {
                event.preventDefault(); // Evitar que la tecla 'e' tenga efecto
            }
        });

        elemento.addEventListener("input", function(event) {
            let valor = event.target.value;
            valor = valor.replace(/[e\+\-]/gi, '');
            // valor = valor.replace(/e|\+|\-/g, '');
            event.target.value = valor;
        });
    })

    function valid(e) {
        let code = (e.which) ? e.which : e.keyCode;
        if (code == 8 || code == 13) {
            return true;
        } else if (code >= 48 && code <= 57) {
            return true;
        } else {
            return false;
        }
    }

    function numeric(event) {
        if (event.key === 'e' || event.key === 'E' || event.key === '+' || event.key === '-') {
            event.preventDefault(); // Evitar que la tecla 'e' tenga efecto
        }
    }

    function notsimbols(event) {
        let valor = event.target.value;
        valor = valor.replace(/[e\+\-]/gi, '');
        // valor = valor.replace(/e|\+|\-/g, '');
        event.target.value = valor;

        // if (event.key === '+' || event.key === '-') {
        //     event.preventDefault(); // Evitar que la tecla 'e' tenga efecto
        // }
    }

    // $('.numeric').on('keydown', function(event) {
    //     if (event.key === 'e' || event.key === 'E') {
    //         event.preventDefault(); // Evitar que la tecla 'e' tenga efecto
    //     }
    // })

    // })
</script>

</html>
