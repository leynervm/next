@extends('admin.reports.snappyPDF.layout')
@section('titulo', $titulo)


@if (count($productos) > 0)
    @foreach ($productos as $item)
        @php
            $image = $item->imagen;
        @endphp

        <table class="table table-bordered border-dark text-10 font-normal mb-5">
            <tr class="border-start border-end border-dark">
                <th class="border-end border-dark" style="width: 6cm;">
                    @if (!empty($image))
                        <div class="picture-producto" style="width:150px !important;height:150px !important;;">
                            <img src="{{ imageBase64($image->urlmobile, 'app/public/images/productos/') }}"
                                alt="{{ pathURLProductImage($image->urlmobile) }}" class="img-fluid">
                        </div>
                    @else
                        <p class="text-10 text-10" style="color:#000;">IMÁGEN NO DISPONIBLE</p>
                    @endif
                </th>
                <th colspan="8" class="text-justify">
                    {{ $item->name }}
                </th>
            </tr>

            @if (count($item->promocions) > 0)
                @foreach ($item->promocions as $promocion)
                    @php
                        $combo = getAmountCombo($promocion);
                    @endphp
                    @if ($combo)
                        @foreach ($combo->products as $itemcombo)
                            <tr class="border-start border-end border-dark">
                                @if ($loop->first)
                                    <td rowspan="{{ count($combo->products) }}"
                                        class="border-end border-dark align-middle text-center" style="width:5cm;">
                                        COMBO
                                    </td>
                                    <td rowspan="{{ count($combo->products) }}"
                                        class="border-end border-dark align-middle"
                                        style="text-transform: uppercase; width: 300px;">
                                        {{ $promocion->titulo }}
                                    </td>
                                @endif

                                <td colspan="2" class="align-middle font-normal text-center">
                                    @if (!empty($itemcombo->imagen))
                                        <div class="picture-producto"
                                            style="width:80px !important;height:80px !important;">
                                            <img src="{{ imageBase64($itemcombo->filename, 'app/public/images/productos/') }}"
                                                alt="{{ $itemcombo->imagen }}" class="img-fluid">
                                        </div>
                                    @else
                                        IMÁGEN NO DISPONIBLE
                                    @endif
                                </td>
                                <td colspan="4">
                                    {{ $itemcombo->name }}
                                </td>
                                <td style="width: 80px;"
                                    class="@if ($itemcombo->price <= 0) text-success @endif align-middle text-center">
                                    {{ $itemcombo->type }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="border-start border-end border-dark">
                            <td style="width:5cm;" class="align-middle text-center">
                                @if ($promocion->isDescuento())
                                    DESCUENTO
                                @else
                                    LIQUIDACIÓN
                                @endif
                            </td>
                            <td>
                                @if ($promocion->isDescuento())
                                    {{ $item->descuento }} % DSCT
                                @endif
                            </td>
                            <td colspan="6"></td>
                        </tr>
                    @endif
                @endforeach
            @endif
        </table>
    @endforeach
@endif
