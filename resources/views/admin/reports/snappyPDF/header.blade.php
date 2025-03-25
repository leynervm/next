<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/snappyPDF/snappy.css') }}">
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
</head>

<body>
    @php
        $logoimpresion = $empresa->logoimpresion;
        $logofooter = $empresa->logofooter;
        $url_logo = $empresa->logo;
        if (!empty($logoimpresion)) {
            $url_logo = $logoimpresion;
        } else {
            if (!empty($logofooter)) {
                $url_logo = $logofooter;
            }
        }
    @endphp

    <table class="header">
        <thead>
            <tr class="align-middle">
                <th class="text-start" rowspan="2">
                    @if (!empty($url_logo))
                        <div class="image">
                            <img src="{{ imageBase64($url_logo) }}"
                                alt="{{ $empresa->name }}" />
                        </div>
                    @endif
                </th>
                <th class="align-top text-end">
                    <p class="font-bold text-14 leading-4 text-end p-0">
                        {{ $empresa->name }}</p>

                    <p class="font-normal text-10 leading-3 text-end p-0">
                        @if (isset($sucursal))
                            {{ $sucursal->direccion }}
                        @else
                            {{ $empresa->direccion }}
                        @endif
                    </p>

                    <p class="font-normal text-10 leading-3 text-end p-0">
                        @if (isset($sucursal))
                            @if ($sucursal->ubigeo)
                                {{ $sucursal->ubigeo->region }}
                                - {{ $sucursal->ubigeo->provincia }}
                                - {{ $sucursal->ubigeo->distrito }}
                            @endif
                        @else
                            @if ($empresa->ubigeo)
                                {{ $empresa->ubigeo->region }}
                                - {{ $empresa->ubigeo->provincia }}
                                - {{ $empresa->ubigeo->distrito }}
                            @endif
                        @endif
                    </p>
                </th>
            </tr>
            <tr>
                <th class="align-bottom">
                    <p class="font-bold text-14 leading-4 text-end p-0">
                        {{ $titulo }}</p>
                </th>
            </tr>
        </thead>
    </table>
</body>

</html>
