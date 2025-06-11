@extends('admin.reports.snappyPDF.layout')
@section('titulo', $titulo)

<table class="table" style="border: 1.5px solid #222 !important;">
    <tr>
        <td class="p-0 m-0">
            <table class="table border-white table-responsive p-0 m-0 text-12 font-normal">
                <thead>
                    <tr>
                        <th class="p-1 align-top text-start" style="width: 100px;">
                            CLIENTE</th>
                        <td class="p-1 text-start align-top">
                            : {{ $ticket->client->name }} -
                            <b>{{ substr($ticket->client->document, 0, strlen(trim($ticket->client->document)) - 3) }}***</b>
                        </td>

                        <th class="p-1 align-top text-end" style="width: 70px;">
                            TELÉFONOS :</th>
                        <td class="p-1 text-end align-top" style="width: 220px;">
                            {{ implode(
                                ', ',
                                array_map(function ($item) {
                                    return formatTelefono($item['phone']);
                                }, $ticket->telephones->toArray()),
                            ) }}
                        </td>
                    </tr>

                    @if ($ticket->contact)
                        <tr>
                            <th class="p-1 align-top text-start">
                                CONTACTO</th>
                            <td class="p-1 text-start align-top" colspan="3">
                                : {{ $ticket->contact->name }}
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <th class="p-1 text-start">
                            REGISTRO</th>
                        <td class="p-1 text-start">
                            : {{ FormatDate($ticket->date) }}</td>

                        <th class="p-1 align-top text-end">
                            CONDICIÓN :</th>
                        <td class="p-1 text-end align-top">
                            : {{ $ticket->condition->name }}
                        </td>
                    </tr>
                </thead>
            </table>
        </td>
    </tr>
</table>

<table class="table" style="border: 1.5px solid #222 !important;">
    <tr>
        <td class="p-0 m-0">
            <table class="table border-white table-responsive text-12 p-0 m-0 font-normal">
                <tr>
                    <th class="p-1 align-top text-start" style="width: 100px;">
                        DETALLES DE ATENCIÓN</th>
                    <td class="p-1 text-start align-top">
                        : {{ $ticket->detalle }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@if ($ticket->equipo)
    <table class="table p-0 m-0 mt-5 " style="border: 1.5px solid #222 !important;">
        <tr>
            <td class="p-0 m-0">
                <table class="table border-white table-responsive p-0 m-0 text-12 font-normal">
                    <thead>
                        <tr>
                            <th class="p-1 align-top text-start" style="width: 80px;">
                                EQUIPO</th>
                            <td class="p-1 text-start align-top">
                                : {{ $ticket->equipo->typeequipo->name }}</td>

                            <th class="p-1 align-top text-end" style="width: 120px;">
                                MARCA :</th>
                            <td class="p-1 text-start align-top">
                                {{ $ticket->equipo->marca->name }}</td>

                            <th class="p-1 align-top text-end" style="width: 70px;">
                                MODELO :</th>
                            <td class="p-1 text-start align-top" style="width: 120px;">
                                {{ $ticket->equipo->modelo }}</td>

                            @if (!empty($ticket->equipo->serie))
                                <th class="p-1 align-top text-end" style="width: 60px;">
                                    SERIE :</th>
                                <td class="p-1 text-end align-top" style="width: 140px;">
                                    {{ $ticket->equipo->serie }}</td>
                            @endif
                        </tr>
                        <tr>
                            <th class="p-1 align-top text-start" style="width: 100px;">
                                DESCRIPCIÓN DEL EQUIPO</th>
                            <td class="p-1 text-justify align-top"
                                colspan="{{ empty($ticket->equipo->serie) ? 5 : 7 }}">
                                : {{ $ticket->equipo->descripcion }}</td>
                        </tr>
                    </thead>
                </table>
            </td>
        </tr>
    </table>
@endif

@if ($ticket->direccion)
    <table class="table p-0 m-0 mt-5" style="border: 1.5px solid #222 !important;">
        <tr>
            <td class="p-0 m-0">
                <table class="table border-white table-responsive p-0 m-0 text-12 font-normal">
                    <thead>
                        <tr>
                            <th class="p-1 align-top text-start" style="width: 100px;">
                                LUGAR DE ATENCIÓN</th>
                            <td class="p-1 text-start align-top" colspan="3">
                                : {{ $ticket->direccion->name }}
                                @if ($ticket->direccion->referencia)
                                    <p class="p-0 m-0">{{ $ticket->direccion->referencia }}</p>
                                @endif
                                {{ $ticket->direccion->ubigeo->distrito }},
                                {{ $ticket->direccion->ubigeo->provincia }},
                                {{ $ticket->direccion->ubigeo->region }}
                            </td>
                        </tr>
                    </thead>
                </table>
            </td>
        </tr>
    </table>
@endif

<p class="text-center font-light p-0 m-0 mt-1" style="font-size:8px;">
    *** IMPORTANTE: LA EMPRESA SE HACE RESPONSABLE POR LA TENENCIA DE SU EQUIPO SOLO POR 45 DIAS A PARTIR DE LA
    FECHA DE INGRESO ***
</p>
<p class="text-center font-light p-0 m-0 mt-1" style="font-size:8px;">
    *** CONDICIONES DEL SERVICIO BRINDADO, VISITA NUESTRA PAGINA WEB ***
</p>

<table class="table border-white">
    <tr>
        <td></td>
        <td style="border-bottom: 1.5px solid #222 !important;padding: 3.5rem 0rem;"></td>
        <td></td>
        <td style="border-bottom: 1.5px solid #222 !important;padding: 3.5rem 0rem;"></td>
        <td></td>
        <td class="p-0 m-0" rowspan="2" style="width: 120px;">
            <img class="" style="display: block;border:2px solid #222;"
                src="{{ $qr }}">
        </td>
    </tr>
    <tr>
        <td></td>
        <td class="text-center font-semibold" style="width: 200px;">FIRMA TECNICO RESPONSABLE</td>
        <td></td>
        <td class="text-center font-semibold" style="width: 200px;">FIRMA CLIENTE</td>
        <td></td>
    </tr>
</table>

<div class="" style="border-top:1.5px solid #222 !important;"></div>