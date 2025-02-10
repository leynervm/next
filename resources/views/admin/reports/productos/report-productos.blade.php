@extends('admin.reports.snappyPDF.layout')
@section('titulo', $titulo)

<table class="table table-responsive text-10 font-normal">
    <tbody>
        @foreach ($productos as $item)
            @php
                $image = $item->imagen;
                if (!empty($image)) {
                    $image = $item->imagen->url;
                }
            @endphp
            <tr class="">
                <td class="p-2 border border-dark text-center align-middle leading-none text-10">
                    @if ($detallado)
                        @if (!empty($image))
                            <div class="picture-producto">
                                <img src="{{ imageBase64($image, 'app/public/images/productos/') }}"
                                    alt="{{ $item->imagen->url }}" class="img-fluid">
                            </div>
                        @else
                            <p class="text-10 text-10" style="color:#000;">IMÁGEN NO DISPONIBLE</p>
                        @endif
                    @endif
                    <br>
                    <p class="font-normal text-justify">{{ $item->name }}</p> <br>

                    @if ($detallado && $item->isPublicado())
                        <span class="text-9 font-normal p-2 px-3"
                            style="background: #0fb9b9; white-space: nowrap; color:white;display: inline-block; border-radius: 0.35rem;">
                            TIENDA WEB</span>
                    @endif
                </td>
                <td class="p-0 m-0 border-dark" valign="top" style="width: 560px;">
                    <table class="table border-0 border-white font-normal text-10">
                        <tbody>
                            <tr class="border-dark">
                                <td colspan="{{ $empresa->usarLista() ? 4 : 3 }}" class="p-2 leading-none text-start">
                                    {{ $item->name_category }} <br>
                                    {{ $item->name_subcategory }}
                                </td>
                                <td class=" p-2 leading-none text-center" style="width: 112px;">
                                    COMPRA <br>
                                    <b>
                                        S/. {{ number_format($item->pricebuy, 2, '.', ', ') }}
                                    </b>
                                </td>
                                @if (!$empresa->usarLista())
                                    <td class=" p-2 leading-none text-center" style="width: 112px;">
                                        VENTA<br>
                                        <b>
                                            S/. {{ number_format($item->pricesale, 2, '.', ', ') }}
                                        </b>
                                    </td>
                                @endif
                            </tr>

                            @if ($empresa->usarLista())
                                <tr @if ($detallado) style="border-bottom: 1px solid #222;" @endif>
                                    @foreach ($pricetypes as $pricetype)
                                        <td class="border-b p-2 text-start align-middle leading-none"
                                            style="width: 112px;">
                                            <b>{{ $pricetype->name }}</b>
                                            <br>
                                            S/.{{ number_format($item->getPrecioVenta($pricetype), $pricetype->decimals, '.', ', ') }}
                                        </td>
                                    @endforeach
                                    @for ($i = count($pricetypes); $i < 5; $i++)
                                        <td></td>
                                    @endfor
                                </tr>
                            @endif


                            <tr class="border-dark border-start-0 {{ $detallado ? '' : 'border-top' }}">
                                <th style="background: #f1f1f1; width:112px"
                                    class="p-2 py-3 leading-none text-start border-end border-dark">
                                    MARCA
                                </th>
                                <td colspan="4" class="p-2 py-3 leading-none">
                                    {{ $item->name_marca }}
                                </td>
                            </tr>

                            @if (!empty($item->modelo))
                                <tr class="border-dark border-start-0">
                                    <th style="background: #f1f1f1; width:112px"
                                        class="p-2 py-3 leading-none text-start border-end border-dark">
                                        MODELO
                                    </th>
                                    <td colspan="4" class="p-2 py-3 leading-none">
                                        {{ $item->modelo }}
                                    </td>
                                </tr>
                            @endif

                            @if ($detallado && count($item->especificacions) > 0)
                                @foreach ($item->especificacions as $esp)
                                    <tr class="border-dark">
                                        <th style="background: #f1f1f1; width:112px"
                                            class="p-2 py-3 leading-none text-start border-end border-dark">
                                            {{ $esp->caracteristica->name }}
                                        </th>
                                        <td colspan="4" class="p-2 py-3 leading-none">
                                            {{ $esp->name }}
                                        </td>
                                    </tr>
                                @endforeach
                                @if (!empty($item->comentario))
                                    <tr class="border-dark">
                                        <th style="background: #f1f1f1; width:112px"
                                            class="p-2 py-3 leading-none text-start border-end border-dark">
                                            OTROS
                                        </th>
                                        <td colspan="4" class="p-2 py-3 leading-none">
                                            {{ nl2br($item->comentario) }}
                                        </td>
                                    </tr>
                                @endif
                            @endif


                            @if (count($item->almacens) > 0)
                                <tr>
                                    <th class="text-center p-2 border border-dark border-start-0" colspan="5">
                                        ALMACÉNES</th>
                                </tr>

                                @php
                                    $almacens = $item->almacens->chunk(5);
                                @endphp

                                @foreach ($almacens as $chunk)
                                    <tr class="border-0 border-white">
                                        @foreach ($chunk as $almacen)
                                            <th class="border-0 border-white align-middle text-center"
                                                style="width: 112px; word-break: break-all;">
                                                {{ $almacen->name }}
                                                <br>
                                                {{ decimalOrInteger($almacen->pivot->cantidad) }}
                                                {{ $item->unit->name }}
                                            </th>
                                        @endforeach

                                        @for ($i = count($chunk); $i < 5; $i++)
                                            <td></td>
                                        @endfor
                                    </tr>
                                @endforeach
                            @endif

                            @if ($detallado && count($item->series) > 0)
                                <tr style="border-top: 1px solid #222;">
                                    <th class="text-center p-2" colspan="5">
                                        SERIES</th>
                                </tr>
                                @php
                                    $series = $item->series->chunk(5);
                                @endphp

                                @foreach ($series as $chunk)
                                    <tr class="border-0">
                                        @foreach ($chunk as $serie)
                                            <td class="border border-dark align-middle text-center {{ $loop->first ? 'border-start-0' : '' }}"
                                                style="width: 112px; word-break: break-all;">
                                                {{ $serie->serie }}</td>
                                        @endforeach

                                        @for ($i = count($chunk); $i < 5; $i++)
                                            <td></td>
                                        @endfor
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
