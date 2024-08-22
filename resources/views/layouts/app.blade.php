<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    @if ($empresa)
        @if ($empresa->icono)
            <link rel="icon" type="image/x-icon" href="{{ $empresa->getIconoURL() }}">
        @endif
    @endif

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', $empresa->name ?? 'MI SITIO WEB') }}</title>

    <!-- Styles -->
    @livewireStyles

    <link rel="stylesheet" href="{{ asset('assets/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/sweetAlert2/sweetalert2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/animate/animate.min.css') }}" />
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11.1.1/swiper-bundle.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/swiper@11.1.1/swiper-bundle.min.js"></script> --}}

    <!-- Scripts -->
</head>

<body class="bg-body mt-[108px] xl:mt-[70px] animate__animated animate__fadeIn animate__faster"
    :class="openSidebar || sidebar || backdrop ? 'overflow-hidden' : ''" x-data="{ sidebar: false, backdrop: false, openSidebar: false, isXL: window.innerWidth >= 1280, isSM: window.innerWidth >= 640, subcategories: [], category: '' }"
    @resize.window="isXL = window.innerWidth >= 1280, isSM = window.innerWidth >= 640">
    <x-jet-banner />

    @if ($empresa)
        @if (Module::isEnabled('Marketplace'))

            {{-- SIDEBAR --}}
            @include('partials.sidebar-marketplace')
            {{-- HEADER --}}
            @include('partials.header-marketplace')

            @if (isset($slider))
                {{ $slider }}
            @endif

            <div class="{{-- contenedor --}} bg-body min-h-screen">
                @if (session('message'))
                    <x-alert :titulo="session('message')->title" :mensaje="session('message')->text" :type="session('message')->type">
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

                @if (isset($breadcrumb))
                    <div class="w-full overflow-hidden pt-2">
                        <x-breadcrumb-next home="/">
                            {{ $breadcrumb }}
                        </x-breadcrumb-next>
                    </div>
                @endif
                {{ $slot }}

            </div>
            {{-- FOOTER --}}
            @include('partials.footer-marketplace')
        @else
            <div class="fixed top-0 left-0 h-screen w-full flex justify-center items-center">
                <x-link-web @click="localStorage.setItem('activeForm', 'login')" :text="__('Log in')"
                    href="{{ route('login') }}" class="btn-next" />
            </div>
        @endif
    @else
        <div class="fixed top-0 left-0 h-screen w-full flex gap-5 justify-center items-center">
            @auth
                <form class="text-center" method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-link-web :text="__('Log Out')" href="{{ route('logout') }}" @click.prevent="$root.submit();"
                        class="btn-next" />
                </form>
            @else
                <x-link-web @click="localStorage.setItem('activeForm', 'login')" :text="__('Log in')"
                    href="{{ route('login') }}" class="btn-next" />
            @endauth

        </div>
    @endif

    @stack('modals')

    @livewireScripts

    <script src="{{ asset('assets/select2/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/sweetAlert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>


    <div x-cloak x-show="backdrop" @click="openSidebar= false, subMenu= false, backdrop=false, sidebar=false"
        class="bg-neutral-900 bg-opacity-70 content-[''] fixed z-[100] w-full h-full top-0 left-0 lg:fixed transition-all ease-in-out duration-200">
    </div>

    @stack('scripts')
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha_v3.key_web') }}"></script>
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
        toastMixin.fire({
            title: event.detail.title,
            icon: event.detail.icon,
            timer: 1000,
        });
    })

    window.addEventListener('validation', data => {
        // console.log(data.detail);
        var icon = data.detail.icon ? data.detail.icon : 'info';
        console.log(icon);
        swal.fire({
            title: data.detail.title,
            text: data.detail.text,
            html: data.detail.text,
            icon: icon,
            confirmButtonColor: '#0FB9B9',
            confirmButtonText: 'Cerrar',
        })
    })

    document.addEventListener('updatewishlist', data => {
        let counter = document.getElementById('counterwishlist');
        if (typeof data.detail === 'object') {
            counter.style.display = 'none';
        } else {
            counter.style.display = 'flex';
            counter.innerHTML = data.detail;

        }
    })



    // SI FUNCIONA PROVADO EN ONKEYPRERSS DEL INPUT
    //onkeypress="return validarNumeroDecimal(event)"
    function validarDecimal(event, maxlenth = 0) {
        var charCode = (event.which) ? event.which : event.keyCode;
        var charTyped = String.fromCharCode(charCode);
        var regex = /^[0-9.]+$/;

        if (maxlenth > 0) {
            if (event.target.value.length >= maxlenth) {
                return charCode == 13 ? true : false;
            }
        }

        if (regex.test(charTyped)) {
            if (charTyped === '.' && event.target.value.includes('.')) {
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

        if (maxlenth > 0) {
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
                if (button.classList.contains('theme-active')) {
                    button.classList.remove('theme-active');
                }
                if (button.getAttribute('theme') == theme) {
                    button.classList.add('theme-active');
                }
            })
            // let activeButton = document.querySelector(`.theme-switcher-${selectedButton}`);
            // activeButton.classList.add('theme-active');
        }

        function getTheme() {
            const localTheme = localStorage.theme;
            if (localTheme != null || localTheme != undefined) {
                let classes = document.body.className.split(' ');
                let themeClasses = classes.filter(cls => cls.startsWith('theme-'));
                themeClasses.forEach(themeClass => {
                    document.body.classList.remove(themeClass);
                });
                document.body.classList.add(localTheme);
            } else {
                if (window.matchMedia('(prefers-color-scheme:dark)').matches) {
                    document.body.classList.remove("{{ config('app.theme') }}");
                    document.body.classList.add('theme-darknext');
                } else {
                    document.body.classList.remove('theme-darknext');
                    document.body.classList.add("{{ config('app.theme') }}");
                }
            }
            setActive(localTheme);
        }

        function setTheme(event) {
            localStorage.theme = event.getAttribute('theme');
        }
    })
</script>

</html>
