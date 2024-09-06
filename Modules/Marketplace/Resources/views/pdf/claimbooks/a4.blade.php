<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
    {{-- <meta http-equiv="X-UA-Compatible" content="ie=edge"> --}}
    <title>
        LIBRO DE RECLAMACIÓN - {{ $claimbook->serie }}-{{ $claimbook->correlativo }}
    </title>
</head>


<body>
    <div id="header" class="border">
        <table class="table">
            <thead>
                <tr class="align-baseline">
                    <th style="text-align: left;">
                        @if ($empresa->image)
                            <div class="">
                                <img src="{{ $empresa->image->getLogoEmpresa() }}" alt="" class="image" />
                            </div>
                        @endif
                    </th>
                    <th class="align-baseline" style="padding: 0 2px;">
                        <p class="font-bold text-14 leading-4" style="margin:0;">
                            {{ $empresa->name }}</p>

                        <p class="font-regular text-10 leading-3">
                            {{ $empresa->direccion }}
                        </p>

                        @if ($empresa->ubigeo)
                            <p class="font-regular text-10 leading-3">
                                {{ $empresa->ubigeo->region }}
                                - {{ $empresa->ubigeo->provincia }}
                                - {{ $empresa->ubigeo->distrito }}
                            </p>
                        @endif

                        @if (count($empresa->telephones) > 0)
                            <p class="font-regular" style="font-size: 10px; line-height: .7rem;">
                                TELÉFONO:
                                <span>{{ formatTelefono($empresa->telephones->first()->phone) }}</span>
                            </p>
                        @endif

                    </th>
                    <th class="serie border-2" style="vertical-align:middle; ">
                        <p class="font-bold text-14 leading-7">
                            {{ $empresa->document }}</p>
                        <p class="font-bold text-14 leading-7">
                            LIBRO DE RECLAMACIÓN
                        </p>
                        <p class="font-bold text-14 leading-7">{{ $claimbook->serie }}-{{ $claimbook->correlativo }}
                        </p>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
    @php
        $bottom = !empty($empresa->web) ? '-3.4cm' : '-3cm';
    @endphp
    <div id="footer" class="" style="bottom: {{ $bottom }};">
        <table class="table" style="padding: 0 10px; border-collapse: separate;">
            <thead>
                <tr class="align-middle">
                    <td class="title border" style="">
                        <p class="font-regular text-12 leading-3 mt-2 text-center">
                            © {{ $empresa->name }} 2012 - Todos los derechos reservados.</p>

                    </td>
                </tr>
                @if (!empty($empresa->web))
                    <tr>
                        <td colspan="2" class="text-center text-12 leading-3">
                            {{ $empresa->web }}</td>
                    </tr>
                @endif
            </thead>
        </table>
    </div>

    <div class="body">
        <table class="table mt-3 text-10">
            <tbody>
                <tr>
                    <td class="p-1 font-medium" style="width: 100px">
                        FECHA REGISTRO </td>
                    <td class="p-1 font-bold">
                        : {{ formatDate($claimbook->date, 'DD/MM/Y HH:mm A') }}</td>
                </tr>
                <tr>
                    <td class="p-1 font-medium" style="width: 100px">
                        CANAL DE VENTA </td>
                    <td class="p-1 font-bold">
                        : {{ $claimbook->channelsale }}
                    </td>
                </tr>

                @if ($claimbook->sucursal)
                    <tr>
                        <td class="p-1 font-medium align-baseline" style="width: 100px">
                            PUNTO VENTA </td>
                        <td class="p-1 font-bold">
                            : {{ $claimbook->sucursal->name }}
                            <br>
                            <p class="font-normal text-start" style="padding: 0;">
                                {{ $claimbook->sucursal->direccion }} -
                                {{ $claimbook->sucursal->ubigeo->distrito }} /
                                {{ $claimbook->sucursal->ubigeo->provincia }} /
                                {{ $claimbook->sucursal->ubigeo->region }}
                            </p>
                        </td>
                    </tr>
                @endif

                <tr>
                    <td class="p-1 font-medium" style="width: 100px;">
                        PEDIDO </td>
                    <td class="p-1 font-bold">
                        : {{ $claimbook->pedido }}</td>
                </tr>
            </tbody>
        </table>

        <table class="table mt-3 text-10 border-2">
            <tbody>
                <tr class="">
                    <td colspan="2" class="font-bold text-center p-2">
                        DATOS DE LA PERSONA DEL RECLAMO </td>
                </tr>
                <tr>
                    <td class="p-1 font-bold align-baseline" style="width: 50%">
                        NOMBRE COMPLETO
                        <p class="font-medium" style="padding: 0;">
                            {{ $claimbook->document }} / {{ $claimbook->name }}</p>
                    </td>
                    <td class="p-1 font-bold align-baseline" style="width: 50%">
                        DIRECCIÓN
                        <p class="font-medium" style="padding: 0;">
                            {{ $claimbook->direccion }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="p-1 font-bold align-baseline">
                        TELÉFONO
                        <p style="padding: 0;">{{ formatTelefono($claimbook->telephono) }}</p>
                    </td>
                    <td class="p-1 font-bold align-baseline">
                        CORREO
                        <p style="padding: 0;">{{ $claimbook->email }}</p>
                    </td>
                </tr>
            </tbody>
        </table>

        @if ($claimbook->isMenorEdad())
            <table class="table mt-6 text-10 border-2">
                <tbody>
                    <tr class="">
                        <td colspan="2" class="font-bold text-center p-2">
                            DATOS DEL APODERADO </td>
                    </tr>
                    <tr>
                        <td class="p-1 font-bold align-baseline" style="width: 50%">
                            NOMBRE COMPLETO
                            <p class="font-medium" style="padding: 0;">
                                {{ $claimbook->document }} / {{ $claimbook->name_apoderado }}</p>
                        </td>
                        <td class="p-1 font-bold align-baseline" style="width: 50%">
                            DIRECCIÓN
                            <p class="font-medium" style="padding: 0;">
                                {{ $claimbook->direccion_apoderado }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-1 font-bold align-baseline">
                            TELÉFONO
                            <p style="padding: 0;">{{ formatTelefono($claimbook->telefono_apoderado) }}</p>
                        </td>
                        <td>

                        </td>
                    </tr>
                </tbody>
            </table>
        @endif

        <table class="table mt-6 text-10 border-2">
            <tbody>
                <tr class="">
                    <td colspan="2" class="font-bold text-center p-2" style="width: 100px">
                        DETALLE DEL RECLAMO </td>
                </tr>
                <tr>
                    <td class="p-1 font-bold align-baseline">
                        BIEN CONTRATADO :
                        <p class="font-medium" style="padding: 0;">{{ $claimbook->biencontratado }}</p>
                    </td>
                    <td class="p-1 font-bold">
                        DESCRIPCION PRODUCTO / SERVICIO :
                        <p class="font-medium" style="padding: 0;">{{ $claimbook->descripcion_producto_servicio }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td class="p-1 font-bold align-baseline">
                        TIPO RECLAMO :
                        <p class="font-medium" style="padding: 0;">{{ $claimbook->tipo_reclamo }}</p>
                    </td>
                    <td class="p-1 font-bold">
                        DETALLE :
                        <p class="font-medium" style="padding: 0;">{{ $claimbook->detalle_reclamo }}</p>
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- <span class="font-normal">NORMAL</span>
        <span class="font-regular">REGULAR</span>
        <span class="font-medium">MEDIUM</span>
        <span class="font-bold">BOLD</span> --}}

        {{-- @for ($i = 1; $i < 100; $i++)
            <div style="background:#7e7e7e; margin:2px; font-size: 11px">
                <p class="font-normal">{{ $i }}</p>
            </div>
        @endfor --}}
    </div>
</body>

</html>