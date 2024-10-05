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
    <meta name="default-theme" content="{{ config('app.theme') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="title" content="{{ config('app.name', $empresa->name ?? 'MI SITIO WEB') }}">
    <title>{{ config('app.name', $empresa->name ?? 'MI SITIO WEB') }}</title>

    <!-- Styles -->
    @livewireStyles

    <link rel="stylesheet" href="{{ asset('assets/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/sweetAlert2/sweetalert2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/animate/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/ubuntu.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
</head>

<style>
    body {
        overflow: hidden;
    }
</style>

<body class="bg-body animate__animated animate__fadeIn animate__faster"
    :class="openSidebar || sidebar || backdrop ? 'overflow-hidden' : ''" x-data="{ sidebar: false, backdrop: false, openSidebar: false, isXL: window.innerWidth >= 1280, isSM: window.innerWidth <= 640, subcategories: [], category: '' }"
    @resize.window="isXL = window.innerWidth >= 1280, isSM = window.innerWidth <= 640" x-init="$watch('openSidebar', (value) => {
        if (value) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'auto';
        }
    });
    $watch('sidebar', (value) => {
        if (value) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'auto';
        }
    })">
    <x-jet-banner />

    <div style="z-index: 999999; background-color: rgba(255,255,255,1);position: fixed;top: 0;left: 0; width: 100%;height: 100vh;display: flex;flex-direction:column;justify-content: center;align-items: center;"
        id="loading-next">
        <x-loading-next />
    </div>

    @if ($empresa)
        @if (Module::isEnabled('Marketplace'))

            {{-- SIDEBAR --}}
            @include('partials.sidebar-marketplace')
            {{-- HEADER --}}
            @include('partials.header-marketplace')

            @if (isset($slider))
                {{ $slider }}
            @endif

            <div class="content block w-full min-h-screen">
                <x-alert />

                @if (isset($breadcrumb))
                    <div class="w-full overflow-hidden p-1.5 lg:p-2">
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

    <x-cookies class="" />

    @stack('modals') @livewireScripts
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

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha_v3.key_web') }}"></script>

<script>
    // document.body.style.overflow = 'hidden';

    window.addEventListener('load', () => {
        document.body.style.overflow = 'auto';
        $('#loading-next').fadeOut();
    })

    let boxCookies = document.getElementById('cookies');
    cookies();

    async function cookies() {
        let cookies_accept = await localStorage.acceptCookies;
        if (cookies_accept) {
            boxCookies.style.display = 'none';
        } else {
            boxCookies.style.display = 'flex';
        }
    }

    function aceptar() {
        boxCookies.style.display = 'none';
        localStorage.acceptCookies = true;
    }


    document.addEventListener('submit', function(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
            grecaptcha.execute("{{ config('services.recaptcha_v3.key_web') }}", {
                action: 'submit'
            }).then(function(token) {
                let form = e.target;
                let action = form.getAttribute('action');
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'g_recaptcha_response';
                input.value = token;
                form.appendChild(input);
                if (action != null || action != undefined) {
                    form.submit();
                }
            });
        });
    })

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
</script>

</html>
