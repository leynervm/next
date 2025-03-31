<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $titulo }}</title>
    <link rel="stylesheet" href="{{ asset('assets/snappyPDF/snappy.css') }}">
    <link href="{{ asset('assets/snappyPDF/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        @font-face {
            font-family: "Ubuntu";
            font-style: normal;
            font-weight: 300;
            font-display: swap;
            src: url("{{ asset('/fonts/Ubuntu-Light.ttf') }}") format("truetype");
        }

        @font-face {
            font-family: "Ubuntu";
            font-style: italic;
            font-weight: 300;
            font-display: swap;
            src: url("{{ asset('/fonts/Ubuntu-LightItalic.ttf') }}") format("truetype");
        }

        @font-face {
            font-family: "Ubuntu";
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url("{{ asset('/fonts/Ubuntu-Regular.ttf') }}") format("truetype");
        }

        @font-face {
            font-family: "Ubuntu";
            font-style: italic;
            font-weight: 400;
            font-display: swap;
            src: url("{{ asset('/fonts/Ubuntu-Italic.ttf') }}") format("truetype");
        }

        @font-face {
            font-family: "Ubuntu";
            font-style: normal;
            font-weight: 500;
            font-display: swap;
            src: url("{{ asset('/fonts/Ubuntu-Medium.ttf') }}") format("truetype");
        }

        @font-face {
            font-family: "Ubuntu";
            font-style: italic;
            font-weight: 500;
            font-display: swap;
            src: url("{{ asset('/fonts/Ubuntu-MediumItalic.ttf') }}") format("truetype");
        }

        @font-face {
            font-family: "Ubuntu";
            font-style: normal;
            font-weight: 700;
            font-display: swap;
            src: url("{{ asset('/fonts/Ubuntu-Bold.ttf') }}") format("truetype");
        }

        @font-face {
            font-family: "Ubuntu";
            font-style: Italic;
            font-weight: 700;
            font-display: swap;
            src: url("{{ asset('/fonts/Ubuntu-BoldItalic.ttf') }}") format("truetype");
        }
    </style>
    <style>
        table {
            border: none !important;
        }

        table th,
        table td {
            border: none !important;
        }
    </style>
</head>

<body class="container-snappy-pdf">

</body>

</html>
