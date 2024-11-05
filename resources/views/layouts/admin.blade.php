<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    @php
        $empresa = mi_empresa();
    @endphp
    @if ($empresa->icono ?? null)
        <link rel="icon" type="image/x-icon" href="{{ Storage::url('images/company/' . $empresa->icono) }}">
    @endif

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="default-theme" content="{{ config('app.theme') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', $empresa->name ?? 'MI SITIO WEB') }}</title>


    <!-- Fonts -->
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"> --}}
    {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap"> --}}

    <!-- Styles -->
    @livewireStyles

    <link rel="stylesheet" href="{{ asset('assets/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/sweetAlert2/sweetalert2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/animate/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/ubuntu.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />

    <!-- Scripts -->
</head>

<body class="{{ config('app.theme') }}">
    <div class="relative flex h-screen min-h-screen overflow-hidden">
        @auth
            @if ($empresa)
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
                    <div class="md:flex w-full h-16 px-1 items-center justify-center" id="sidebar-header">
                        <div class="h-12 w-32 hidden ease-in-out duration-100" id="logo-sidebar">
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
            @endif

            <div class="flex-1 w-full p-1 lg:px-3 overflow-y-auto bg-body h-screen">
                <main class="w-full relative ">
                    <x-alert />

                    <div class="relative flex flex-col flex-col-reverse md:flex-row gap-2 items-start justify-between pb-2">
                        <div class="w-full flex-1 mt-auto">
                            <x-breadcrumb-next home="{{ route('admin') }}">
                                @if (isset($breadcrumb))
                                    {{ $breadcrumb }}
                                @endif
                            </x-breadcrumb-next>
                        </div>

                        <x-jet-dropdown align="right" width="48" contentClasses="p-2 bg-fondodropdown">
                            <x-slot name="trigger">
                                <button type="button"
                                    class="bg-fondodropdown shadow shadow-shadowminicard relative inline-flex p-1.5 justify-center items-center text-textspancardproduct cursor-pointer font-semibold rounded-lg hover:shadow-md hover:shadow-shadowminicard focus:shadow-md focus:shadow-shadowminicard transition-all ease-in-out duration-150">
                                    <div class="text-xs px-3">
                                        <p class="text-right">{{ Auth::user()->name }}</p>

                                        @if (Auth::user()->sucursal)
                                            <small
                                                class="font-medium text-[10px]">[{{ Auth::user()->sucursal->name }}]</small>
                                        @else
                                            <small class="font-medium text-[10px] text-next-500">
                                                [SUCURSAL NO ASIGNADA]</small>
                                        @endif

                                    </div>

                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                        <img class="h-8 w-8 rounded-full object-cover"
                                            src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
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
                                <div
                                    class="px-4 py-2 flex gap-2 w-full theme-switcher justify-end items-center bg-fondominicard">
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

                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-colorsubtitleform text-center">
                                    {{ __('Manage Account') }}
                                </div>

                                @if (Module::isEnabled('Marketplace'))
                                    <x-nav-link
                                        class="px-5 gap-2 shadow-none !justify-start font-normal w-full !text-colordropdown hover:!bg-fondohoverdropdown"
                                        href="/">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" stroke-linejoin="round"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                            <path
                                                d="M3.06164 15.1933L3.42688 13.1219C3.85856 10.6736 4.0744 9.44952 4.92914 8.72476C5.78389 8 7.01171 8 9.46734 8H14.5327C16.9883 8 18.2161 8 19.0709 8.72476C19.9256 9.44952 20.1414 10.6736 20.5731 13.1219L20.9384 15.1933C21.5357 18.5811 21.8344 20.275 20.9147 21.3875C19.995 22.5 18.2959 22.5 14.8979 22.5H9.1021C5.70406 22.5 4.00504 22.5 3.08533 21.3875C2.16562 20.275 2.4643 18.5811 3.06164 15.1933Z" />
                                            <path
                                                d="M7.5 8L7.66782 5.98618C7.85558 3.73306 9.73907 2 12 2C14.2609 2 16.1444 3.73306 16.3322 5.98618L16.5 8" />
                                            <path
                                                d="M15 11C14.87 12.4131 13.5657 13.5 12 13.5C10.4343 13.5 9.13002 12.4131 9 11" />
                                        </svg>
                                        {{ __('Store') }}
                                    </x-nav-link>
                                @endif

                                <x-nav-link
                                    class="px-5 gap-2 shadow-none !justify-start font-normal w-full !text-colordropdown hover:!bg-fondohoverdropdown"
                                    href="{{ route('admin.profile') }}">
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
                                    {{ __('Profile') }}
                                </x-nav-link>

                                {{-- @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">
                                        {{ __('API Tokens') }}
                                    </x-jet-dropdown-link>
                                @endif --}}

                                {{-- <div class="border-t border-colorlinknav"></div> --}}

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-nav-link
                                        class="px-5 gap-2 shadow-none !justify-start font-normal w-full !text-colordropdown hover:!bg-fondohoverdropdown"
                                        href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="currentColor" stroke="currentColor" stroke-width=".5"
                                            fill-rule="evenodd" clip-rule="evenodd">
                                            <path
                                                d="M7.95964 5.18436C8.25778 5.63184 8.13597 6.23577 7.68756 6.53329C5.58339 7.92941 4.2 10.3139 4.2 13.0203C4.2 17.3192 7.69218 20.8041 12 20.8041C16.3078 20.8041 19.8 17.3192 19.8 13.0203C19.8 10.3139 18.4166 7.92941 16.3124 6.53329C15.864 6.23577 15.7422 5.63184 16.0404 5.18436C16.3385 4.73689 16.9437 4.61533 17.3921 4.91285C20.0168 6.65432 21.75 9.63513 21.75 13.0203C21.75 18.3939 17.3848 22.75 12 22.75C6.61522 22.75 2.25 18.3939 2.25 13.0203C2.25 9.63513 3.98323 6.65432 6.60789 4.91285C7.0563 4.61533 7.6615 4.73689 7.95964 5.18436Z" />
                                            <path
                                                d="M12 1.25C12.5523 1.25 13 1.69772 13 2.25V10.25C13 10.8023 12.5523 11.25 12 11.25C11.4477 11.25 11 10.8023 11 10.25V2.25C11 1.69772 11.4477 1.25 12 1.25Z" />
                                        </svg>
                                        {{ __('Log Out') }}
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
    <script src="{{ asset('assets/sortableJS/Sortable.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- <script src="{{ asset('assets/ckeditor5/ckeditor5_38.1.1_super-build_ckeditor.js') }}"></script> --}}
    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/38.1.1/super-build/ckeditor.js"></script> --}}
    {{-- Translation español super-build CKEditor5 --}}
    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/38.1.1/super-build/translations/es.js"></script> --}}

    @yield('scripts')

</body>
<script>
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
            title: 'REGISTRADO CORRECTAMENTE'
        });
    })

    window.addEventListener('updated', event => {
        toastMixin.fire({
            title: 'ACTUALIZADO CORRECTAMENTE'
        });
    })

    window.addEventListener('deleted', event => {
        toastMixin.fire({
            title: 'ELIMINADO CORRECTAMENTE'
        });
    })

    window.addEventListener('validation', data => {
        // console.log(data.detail);
        var icon = data.detail.icon ? data.detail.icon : 'info';
        swal.fire({
            title: data.detail.title,
            text: data.detail.text,
            html: data.detail.text,
            icon: icon,
            confirmButtonColor: '#0FB9B9',
            confirmButtonText: 'Cerrar',
        })
    })

    window.addEventListener('show-resumen-venta', (event) => {
        toastMixin.fire({
            title: event.detail.mensaje,
            icon: "success",
            timer: 2000,
        });

        if (event.detail.form_id) {
            let form = document.getElementById("cardproduct" + event.detail.form_id);
            if (form) {
                form.reset();
            }
        }
    });


    function toDecimal(valor, decimals = 3) {
        let numero = parseFloat(valor);

        if (isNaN(numero)) {
            return 0;
        } else {
            return parseFloat(numero).toFixed(decimals);
        }
    }

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

    // SI FUNCIONA PROVADO EN ONKEYDOWN DEL INPUT
    function disabledEnter(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
        }
    }

    // SI FUNCIONA PROVADO EN ONKEYPRERSS DEL INPUT
    //onkeypress="return validarNumeroDecimal(event)"
    function validarDecimal(event, maxlenth = 0) {
        var charCode = (event.which) ? event.which : event.keyCode;
        var charTyped = String.fromCharCode(charCode);
        var regex = /^[0-9.]+$/;
        var selectedText = window.getSelection().toString();

        if (maxlenth > 0 && !selectedText.length) {
            if (event.target.value.length >= maxlenth) {
                return charCode == 13 ? true : false;
            }
        }

        if (regex.test(charTyped)) {
            if (charTyped === '.' && event.target.value.includes('.') && !selectedText.length) {
                return false;
            }
            return true;
        }
        //permitir hacer enter en input
        return charCode == 13 ? true : false;
    }

    function validarNumero(event, maxlenth = 0) {
        var charCode = (event.which) ? event.which : event.keyCode;
        var charTyped = String.fromCharCode(charCode);
        var regex = /^[0-9]+$/;
        var selectedText = window.getSelection().toString();

        if (maxlenth > 0 && !selectedText.length) {
            if (event.target.value.length >= maxlenth) {
                return charCode == 13 ? true : false;
            }
        }

        if (regex.test(charTyped)) {
            return true;
        }
        //permitir hacer enter en input
        return charCode == 13 ? true : false;
    }

    function validarPasteNumero(event, propiedad, maxlenth = 0) {
        const clipboardData = event.clipboardData || window.clipboardData;
        const pastedData = clipboardData.getData('Text');
        const onlyNumbers = pastedData.replace(/[^0-9]/g, '');
        event.preventDefault();

        const input = event.target;
        const start = input.selectionStart;
        const end = input.selectionEnd;

        if (start !== end) {
            input.value = input.value.substring(0, start) + onlyNumbers + input.value.substring(end);
        } else {
            input.value += onlyNumbers;
        }

        if (input.value.length > input.maxLength) {
            input.value = input.value.substring(0, input.maxLength);
        }

        const newLength = (start !== end) ? start + onlyNumbers.length : input.value.length;
        input.setSelectionRange(newLength, newLength);
        input.dispatchEvent(new Event("input"));
        return true;
    }

    function validarSerie(event, maxlenth = 0) {
        const regex = /^[a-zA-Z0-9-_]$/;
        const charTyped = String.fromCharCode(event.which || event.keyCode);

        if (regex.test(charTyped)) {
            return true;
        } else {
            event.preventDefault();
            return false;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        getTheme();
        const buttonsTheme = document.querySelectorAll('.theme-switcher-button');
        buttonsTheme.forEach((button) => {
            button.addEventListener('click', () => {
                setTheme(button);
                getTheme();
            });
        })

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            getTheme(e);
        });

        function setActive(theme) {
            const themeSwitcherButtons = document.querySelectorAll('.theme-switcher-button');
            themeSwitcherButtons.forEach((button) => {
                button.classList.remove('theme-active');
                if (button.getAttribute('theme') == theme) {
                    button.classList.add('theme-active');
                }
            })
        }

        function getTheme() {
            let localTheme = localStorage.theme;
            if (localTheme == null || localTheme == undefined) {
                if (window.matchMedia('(prefers-color-scheme:dark)').matches) {
                    localTheme = 'theme-darknext';
                } else {
                    localTheme = "{{ config('app.theme') }}";
                }
                localStorage.theme = localTheme;
            }
            setActive(localTheme);
            let classes = document.body.className.split(' ');
            let themeClasses = classes.filter(cls => cls.startsWith('theme-'));
            let isEqualThem = false;
            themeClasses.forEach(themeClass => {
                if (localTheme == themeClass) {
                    isEqualThem = true;
                } else {
                    document.body.classList.remove(themeClass);
                }
            });

            if (!isEqualThem) {
                document.body.classList.add(localTheme);
            }
        }

        function setTheme(event) {
            localStorage.theme = event.getAttribute('theme');
        }


        const inputsPaste = document.querySelectorAll('.numeric_onpaste_number');
        inputsPaste.forEach(input => {
            input.addEventListener('paste', e => {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                const cleaned = paste.replace(/[^0-9]/g, '');
                let newValue = isNaN(cleaned) || cleaned.length == 0 ? 1 : cleaned;
                input.value = newValue;

                if (input.hasAttribute('x-model')) {
                    const xModelProp = input.getAttribute('x-model');
                    console.log(xModelProp, input.value);
                    this.xModelProp = newValue;
                }
                input.dispatchEvent(new Event("input"));
            });
        })
    })
</script>

</html>
