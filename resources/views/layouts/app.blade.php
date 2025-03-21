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
            if (!isXL) {
                sidebar = false;
            }
        }
    });
    $watch('sidebar', (value) => {
        if (value && !isXL) {
            document.body.style.overflow = 'hidden';
        }
    })
    $watch('isXL', (value) => {
        sidebar = value;
        backdrop = openSidebar;
    })">
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
            @if ($empresa->whatsapp)
                <a href="{{ $empresa->whatsapp }}" target=”_blank” class="btn-float-whatsapp">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 308.00 308.00"
                        stroke="currentColor" transform="matrix(1, 0, 0, 1, 0, 0)" class="block w-6 md:w-9 h-6 md:h-9">
                        <path
                            d="M227.904,176.981c-0.6-0.288-23.054-11.345-27.044-12.781c-1.629-0.585-3.374-1.156-5.23-1.156 c-3.032,0-5.579,1.511-7.563,4.479c-2.243,3.334-9.033,11.271-11.131,13.642c-0.274,0.313-0.648,0.687-0.872,0.687 c-0.201,0-3.676-1.431-4.728-1.888c-24.087-10.463-42.37-35.624-44.877-39.867c-0.358-0.61-0.373-0.887-0.376-0.887 c0.088-0.323,0.898-1.135,1.316-1.554c1.223-1.21,2.548-2.805,3.83-4.348c0.607-0.731,1.215-1.463,1.812-2.153 c1.86-2.164,2.688-3.844,3.648-5.79l0.503-1.011c2.344-4.657,0.342-8.587-0.305-9.856c-0.531-1.062-10.012-23.944-11.02-26.348 c-2.424-5.801-5.627-8.502-10.078-8.502c-0.413,0,0,0-1.732,0.073c-2.109,0.089-13.594,1.601-18.672,4.802 c-5.385,3.395-14.495,14.217-14.495,33.249c0,17.129,10.87,33.302,15.537,39.453c0.116,0.155,0.329,0.47,0.638,0.922 c17.873,26.102,40.154,45.446,62.741,54.469c21.745,8.686,32.042,9.69,37.896,9.69c0.001,0,0.001,0,0.001,0 c2.46,0,4.429-0.193,6.166-0.364l1.102-0.105c7.512-0.666,24.02-9.22,27.775-19.655c2.958-8.219,3.738-17.199,1.77-20.458 C233.168,179.508,230.845,178.393,227.904,176.981z" />
                        <path
                            d="M156.734,0C73.318,0,5.454,67.354,5.454,150.143c0,26.777,7.166,52.988,20.741,75.928L0.212,302.716 c-0.484,1.429-0.124,3.009,0.933,4.085C1.908,307.58,2.943,308,4,308c0.405,0,0.813-0.061,1.211-0.188l79.92-25.396 c21.87,11.685,46.588,17.853,71.604,17.853C240.143,300.27,308,232.923,308,150.143C308,67.354,240.143,0,156.734,0z M156.734,268.994c-23.539,0-46.338-6.797-65.936-19.657c-0.659-0.433-1.424-0.655-2.194-0.655c-0.407,0-0.815,0.062-1.212,0.188 l-40.035,12.726l12.924-38.129c0.418-1.234,0.209-2.595-0.561-3.647c-14.924-20.392-22.813-44.485-22.813-69.677 c0-65.543,53.754-118.867,119.826-118.867c66.064,0,119.812,53.324,119.812,118.867 C276.546,215.678,222.799,268.994,156.734,268.994z" />
                    </svg>
                </a>
            @endif
        @else
            <div class="fixed top-0 left-0 h-screen w-full flex justify-center items-center">
                <x-link-web @click="localStorage.setItem('activeForm', 'login')" :text="__('Log in')"
                    href="{{ route('login') }}" class="btn-next" />
            </div>
        @endif
    @else
        <div class="fixed top-0 left-0 h-screen w-full flex flex-col xs:flex-row gap-5 justify-center items-center">
            @auth
                @if (auth()->user()->isAdmin())
                    <x-link-web :text="__('Dashboard')" href="{{ route('admin') }}" class="btn-next" />
                @endif
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
    <x-loading-web-next x-cloak x-ref="loadingnext" {{-- id="loading-next" --}} />

    @stack('modals')
    @livewireScripts

    <script src="{{ asset('assets/select2/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/sweetAlert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <div x-cloak x-show="backdrop"
        @click="openSidebar= false;subMenu=false;backdrop=false; if (!isXL) {sidebar=false}; document.body.style.overflow = 'auto';"
        class="bg-neutral-900 bg-opacity-70 content-[''] fixed z-[100] w-full h-full top-0 left-0 lg:fixed transition-all ease-in-out duration-200">
    </div>

    @stack('scripts')
</body>


<script>
    const componentloading = document.querySelector('[x-ref="loadingnext"]');

    document.addEventListener('readystatechange', () => {
        // console.log(document.readyState)
        if (document.readyState == 'interactive') {
            document.body.style.overflow = 'auto';
            $(componentloading).fadeOut();
        }
    });

    // document.body.style.overflow = 'hidden';
    // document.addEventListener("DOMContentLoaded", () => {
    //     document.body.style.overflow = 'auto';
    //     $('#loading-next').fadeOut();
    // })

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
                // console.log(token);
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
        let timer = event.detail.timer ? event.detail.timer : 1000;
        toastMixin.fire({
            title: event.detail.title,
            icon: event.detail.icon,
            timer: timer,
        });
    })

    window.addEventListener('validation', data => {
        var icon = data.detail.icon ? data.detail.icon : 'info';
        swal.fire({
            title: data.detail.title,
            text: data.detail.text,
            html: data.detail.text,
            icon: icon,
            confirmButtonColor: '#0FB9B9',
            confirmButtonText: 'Aceptar',
        })
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

    function validarPasteNumero(event, maxlenth = 0) {
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


    function addproductocart(producto_id = null, promocion_id = null, cantidad = 1, open_modal = false) {
        const data = {
            promocion_id: promocion_id,
            producto_id: producto_id,
            cantidad: cantidad,
            open_modal: open_modal
        }
        sendRequest(`{{ route('marketplace.addproducto') }}`, data, (response) => {
            //console.log(response);
            if (response.open_modal) {
                window.dispatchEvent(new CustomEvent('getcombos', {
                    detail: {
                        producto_id: response.producto_id,
                    }
                }))
            } else {
                toastMixin.fire({
                    title: response.mensaje,
                    icon: 'success',
                });
                window.dispatchEvent(new CustomEvent('updatecart'));
            }
        }, (errorData) => {
            //console.log(errorData);
            toastMixin.fire({
                title: errorData.error,
                icon: 'error',
                timer: 5000,
            });
        });
    }

    function updatecart(event, rowId, cantidad = 1) {
        event.disabled = true;
        event.textContent = '...';

        const data = {
            rowId: rowId,
            cantidad: cantidad,
            type: event.getAttribute('data-function')
        }

        sendRequest(`{{ route('marketplace.updatecart') }}`, data, () => window.dispatchEvent(new CustomEvent(
                'updatecart')),
            (errorData) => {
                toastMixin.fire({
                    title: errorData.error,
                    icon: 'error',
                    timer: 5000,
                });
            }, () => console.log('Finally ', close)
        );
    }

    function updateqty(event, rowId, cantidad = 1) {
        const data = {
            rowId: rowId,
            cantidad: Math.trunc(event.value ?? 1)
        }

        sendRequest(`{{ route('marketplace.updateqty') }}`, data, () => window.dispatchEvent(new CustomEvent(
                'updatecart')),
            (errorData) => {
                if (errorData.qty) {
                    event.value = errorData.qty;
                }
                toastMixin.fire({
                    title: errorData.error,
                    icon: 'error',
                    timer: 5000,
                });
            });
    }

    function deleteitemcart(event, rowId, cantidad = 1) {
        event.disabled = true;
        event.textContent = 'Eliminando...';

        const data = {
            rowId: rowId
        }

        sendRequest(`{{ route('marketplace.deletecart') }}`, data, (response) => {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {
                    title: response.mensaje,
                    icon: 'success',
                }
            }))
            window.dispatchEvent(new CustomEvent('updatecart'));
        }, (errorData) => {
            toastMixin.fire({
                title: errorData.error,
                icon: 'error',
                timer: 5000,
            });
        });
    }

    function addfavoritos(event, producto_id) {
        sendRequest(`{{ route('marketplace.addfavoritos') }}`, {
            producto_id: producto_id
        }, (response) => {
            window.dispatchEvent(new CustomEvent('updatewishlist', {
                detail: response.counter
            }))

            toastMixin.fire({
                title: response.mensaje,
                icon: 'success',
            });
            
            if (response.favorito) {
                event.classList.add('activo');
            } else {
                event.classList.remove('activo');
            }
            $(componentloading).fadeOut();
        }, (errorData) => {
            toastMixin.fire({
                title: errorData.error,
                icon: 'error',
                timer: 5000,
            });
        });
    }

    function sendRequest(route, data, onSuccess = () => {}, onError = () => {}, onFinally = () => {}) {
        $(componentloading).fadeIn();

        fetch(route, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            }).then(response => {
                // console.log(response);
                if (!response.ok) {
                    throw new Error('Error al procesar solicitud');
                }
                return response.json();
            })
            .then(responseData => {
                if (responseData.success) {
                    onSuccess(responseData);
                } else {
                    $(componentloading).fadeOut();
                    onError(responseData);
                    // console.log(responseData);
                }
            }).catch(error => {
                $(componentloading).fadeOut();
                // console.log(error);
                window.dispatchEvent(new CustomEvent('validation', {
                    detail: {
                        title: error.message,
                        icon: 'error'
                    }
                }));
            })
            .finally(() => onFinally());
    }

    const buttons = document.querySelectorAll('.button-add-to-cart');
    if (buttons.length > 0) {
        buttons.forEach(button => {
            button.addEventListener('click', (e) => {
                const promocion_id = e.currentTarget.getAttribute(
                    'data-promocion-id');
                const producto_id = e.currentTarget.getAttribute(
                    'data-producto-id');
                addproductocart(producto_id, promocion_id);
            })
        });
    }
</script>

</html>
