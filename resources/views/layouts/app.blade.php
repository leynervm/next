<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">


    @if (mi_empresa())
        @if (mi_empresa()->icono)
            <link rel="icon" type="image/x-icon" href="{{ Storage::url('images/company/' . mi_empresa()->icono) }}">
        @endif
    @endif

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '@NEXT TECHNOLOGIES') }}</title>


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

                        @if (mi_empresa())
                            @if (mi_empresa()->image)
                                <img class="w-full h-full object-scale-down object-center"
                                    src="{{ Storage::url('images/company/' . mi_empresa()->image->url) }}" alt="">
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
            <div class="flex-1 p-1 lg:px-3 overflow-y-auto bg-body h-screen">
                <main class="shadow relative">
                    @if (session('message'))
                        <x-alert :titulo="session('message')->getData()->title" :mensaje="session('message')->getData()->text" :type="session('message')->getData()->type">
                            <x-slot name="icono">
                                <svg class="w-6 h-6 p-0.5 animate-bounce" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M5.32171 9.68293C7.73539 5.41199 8.94222 3.27651 10.5983 2.72681C11.5093 2.4244 12.4907 2.4244 13.4017 2.72681C15.0578 3.27651 16.2646 5.41199 18.6783 9.68293C21.092 13.9539 22.2988 16.0893 21.9368 17.8293C21.7376 18.7866 21.2469 19.6549 20.535 20.3097C19.241 21.5 16.8274 21.5 12 21.5C7.17265 21.5 4.75897 21.5 3.46496 20.3097C2.75308 19.6549 2.26239 18.7866 2.06322 17.8293C1.70119 16.0893 2.90803 13.9539 5.32171 9.68293Z" />
                                    <path
                                        d="M12.2422 17V13C12.2422 12.5286 12.2422 12.2929 12.0957 12.1464C11.9493 12 11.7136 12 11.2422 12" />
                                    <path d="M11.992 9H12.001" />
                                </svg>
                            </x-slot>
                        </x-alert>
                    @endif

                    <div class="relative flex flex-col-reverse gap-2 md:flex-row items-end md:justify-between pb-2">

                        <div class="w-full md:w-auto">
                            <x-breadcrumb-next>
                                @if (isset($breadcrumb))
                                    {{ $breadcrumb }}
                                @endif
                            </x-breadcrumb-next>
                        </div>

                        <x-jet-dropdown align="right" width="48" contentClasses="py-1 bg-fondodropdown">
                            <x-slot name="trigger">
                                <button type="button"
                                    class="bg-fondodropdown shadow shadow-shadowminicard relative inline-flex p-1.5 justify-center items-center text-textspancardproduct cursor-pointer font-semibold rounded-lg hover:shadow-md hover:shadow-shadowminicard focus:shadow-md focus:shadow-shadowminicard transition-all ease-in-out duration-150">
                                    <div class="text-xs px-3">
                                        <p class="text-right">{{ Auth::user()->name }}</p>

                                        @if (Auth::user()->sucursal)
                                            <small
                                                class="font-medium text-[10px]">[{{ Auth::user()->sucursal->name }}]</small>
                                        @else
                                            <small class="font-medium text-[10px] text-next-500">[SUCURSAL NO
                                                ASIGNADA]</small>
                                        @endif

                                    </div>

                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                        <img class="h-8 w-8 rounded-full object-cover"
                                            src="{{ Auth::user()->profile_photo_url }}"
                                            alt="{{ Auth::user()->name }}" />
                                    @else
                                        <svg class="h-8 w-8 p-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @endif

                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-textspancardproduct">
                                    {{ __('Manage Account') }}
                                </div>

                                {{-- <x-jet-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-jet-dropdown-link> --}}

                                <x-nav-link class="px-5 gap-2 shadow-none rounded-none w-full text-textspancardproduct"
                                    href="{{ route('profile.show') }}">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" stroke-linejoin="round" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round">
                                        <path
                                            d="M10.5 22H6.59087C5.04549 22 3.81631 21.248 2.71266 20.1966C0.453365 18.0441 4.1628 16.324 5.57757 15.4816C8.12805 13.9629 11.2057 13.6118 14 14.4281" />
                                        <path
                                            d="M16.5 6.5C16.5 8.98528 14.4853 11 12 11C9.51472 11 7.5 8.98528 7.5 6.5C7.5 4.01472 9.51472 2 12 2C14.4853 2 16.5 4.01472 16.5 6.5Z" />
                                        <path
                                            d="M18.4332 13.8485C18.7685 13.4851 18.9362 13.3035 19.1143 13.1975C19.5442 12.9418 20.0736 12.9339 20.5107 13.1765C20.6918 13.2771 20.8646 13.4537 21.2103 13.8067C21.5559 14.1598 21.7287 14.3364 21.8272 14.5214C22.0647 14.9679 22.0569 15.5087 21.8066 15.9478C21.7029 16.1298 21.5251 16.3011 21.1694 16.6437L16.9378 20.7194C16.2638 21.3686 15.9268 21.6932 15.5056 21.8577C15.0845 22.0222 14.6214 22.0101 13.6954 21.9859L13.5694 21.9826C13.2875 21.9752 13.1466 21.9715 13.0646 21.8785C12.9827 21.7855 12.9939 21.6419 13.0162 21.3548L13.0284 21.1988C13.0914 20.3906 13.1228 19.9865 13.2807 19.6232C13.4385 19.2599 13.7107 18.965 14.2552 18.375L18.4332 13.8485Z" />
                                    </svg>
                                    <span class="font-medium">{{ __('Profile') }}</span>
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
                                    <x-nav-link class="px-5 gap-2 shadow-none rounded-none w-full text-textspancardproduct"
                                        href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="currentColor" stroke="currentColor" stroke-width=".5"
                                            fill-rule="evenodd" clip-rule="evenodd">
                                            <path
                                                d="M7.95964 5.18436C8.25778 5.63184 8.13597 6.23577 7.68756 6.53329C5.58339 7.92941 4.2 10.3139 4.2 13.0203C4.2 17.3192 7.69218 20.8041 12 20.8041C16.3078 20.8041 19.8 17.3192 19.8 13.0203C19.8 10.3139 18.4166 7.92941 16.3124 6.53329C15.864 6.23577 15.7422 5.63184 16.0404 5.18436C16.3385 4.73689 16.9437 4.61533 17.3921 4.91285C20.0168 6.65432 21.75 9.63513 21.75 13.0203C21.75 18.3939 17.3848 22.75 12 22.75C6.61522 22.75 2.25 18.3939 2.25 13.0203C2.25 9.63513 3.98323 6.65432 6.60789 4.91285C7.0563 4.61533 7.6615 4.73689 7.95964 5.18436Z" />
                                            <path
                                                d="M12 1.25C12.5523 1.25 13 1.69772 13 2.25V10.25C13 10.8023 12.5523 11.25 12 11.25C11.4477 11.25 11 10.8023 11 10.25V2.25C11 1.69772 11.4477 1.25 12 1.25Z" />
                                        </svg>
                                        <span class="font-medium">{{ __('Log Out') }}</span>
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

    window.addEventListener('toast', event => {
        // console.log(event);
        toastMixin.fire({
            title: event.detail.title,
            icon: event.detail.icon,
            timer: 2000,
        });
    })

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
            html: data.detail.text,
            icon: 'info',
            confirmButtonColor: '#0FB9B9',
            confirmButtonText: 'Cerrar',
        })
    })

    document.addEventListener("DOMContentLoaded", () => {
        Livewire.hook('element.updated', (el, component) => {
            $('.select2').attr('disabled', false);
        });
    });

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

        // elemento.addEventListener("input", function(event) {
        //     let valor = event.target.value;
        //     valor = valor.replace(/[e\+\-]/gi, '');
        //     // valor = valor.replace(/e|\+|\-/g, '');
        //     event.target.value = valor;
        // });
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
