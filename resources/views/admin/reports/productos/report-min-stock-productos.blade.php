@extends('admin.reports.snappyPDF.layout')
@section('titulo', $titulo)

<table class="table table-bordered border-dark table-responsive text-10 font-normal">
    <tbody>
        @foreach ($productos as $item)
            @php
                $image = $item->imagen;
                if (!empty($image)) {
                    $image = $item->imagen->urlmobile;
                }
            @endphp
            <tr class="border border-dark">
                <td class="p-0 m-0 border-end border-dark text-center align-middle leading-none text-10">
                    @if (!empty($image))
                        <div class="picture-producto p-1" style="width:150px; height:150px;">
                            <img src="{{ imageBase64($image, 'app/public/images/productos/', true) }}"
                                alt="{{ $item->imagen->urlmobile }}" class="img-fluid">
                        </div>
                    @else
                        <p class="text-10 text-10" style="color:#000;">IMÁGEN NO DISPONIBLE</p>
                    @endif
                    <br>
                    <p class="p-2 font-normal text-justify">
                        {{ $item->name }}</p> <br>
                </td>
                <td class="p-0 m-0" style="width: 560px;">
                    <table class="p-0 m-0 table font-normal text-10">
                        <tbody>
                            <tr class="border-bottom border-dark">
                                <td colspan="{{ $empresa->usarLista() ? 2 : 1 }}"
                                    class="p-2 leading-none align-middle text-start">
                                    {{ $item->name_category }} <br>
                                </td>
                                <td colspan="{{ $empresa->usarLista() ? 2 : 1 }}"
                                    class="p-2 leading-none align-middle text-start">
                                    {{ $item->name_subcategory }}
                                </td>
                                <td class=" p-2 leading-none text-center" style="width: 112px;">
                                    COMPRA <br>
                                    <b>
                                        S/. {{ number_format($item->pricebuy, 2, '.', ', ') }}
                                    </b>
                                </td>
                            </tr>

                            <tr class="border-dark border-start-0">
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

                            @if (count($item->almacens) > 0)
                                <tr>
                                    <th class="text-center p-2 border border-dark border-start-0 border-end-0"
                                        colspan="5">
                                        ALMACÉNES</th>
                                </tr>

                                @php
                                    $almacens = $item->almacens->chunk(5);
                                @endphp

                                @foreach ($almacens as $chunk)
                                    <tr class="border-0">
                                        @foreach ($chunk as $almacen)
                                            <th class=" align-middle text-center border-end border-dark"
                                                style="width: 112px; word-break: break-all;">
                                                {{ $almacen->name }} <br>
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
                        </tbody>
                    </table>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
