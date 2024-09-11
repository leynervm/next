<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    @php
        $empresa = mi_empresa();
    @endphp
    @if ($empresa)
        @if ($empresa->icono ?? null)
            <link rel="icon" type="image/x-icon" href="{{ Storage::url('images/company/' . $empresa->icono) }}">
        @endif
        <title>{{ config('app.name', $empresa->name ?? 'MI SITIO WEB') }}</title>
    @endif

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('css/ubuntu.css') }}" />

    <!-- Scripts -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="{{ config('app.theme') }}">
    <div class="font-sans text-primary">
        {{ $slot }}
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha_v3.key_web') }}"></script>
<script>
    document.addEventListener('submit', function(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
            grecaptcha.execute("{{ config('services.recaptcha_v3.key_web') }}", {
                action: 'submit'
            }).then(function(token) {
                let form = e.target;
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'g_recaptcha_response';
                input.value = token;
                form.appendChild(input);
                form.submit();
            });
        });
    })

    //onkeypress="return validarDecimal(event)"
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
                // var selection = window.getSelection().toString();
                // if (selection.length >= maxlenth) {
                //     input.value = "";
                // }
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
