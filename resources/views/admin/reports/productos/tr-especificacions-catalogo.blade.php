<td class="align-top text-center p-3" style="padding-left: 2rem !important;">
    <table class="table border-white table-responsive p-0 align-top">
        <thead>
            <tr>
                <th colspan="3" class="px-0 text-start leading-none"
                    style="text-align-last: center;font-size: 16px;color:#444444;">
                    {{-- {!! nl2br($item->name) !!} --}}
                    {{ $item->name }}
                </th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($item->marca) || !empty($item->modelo))
                <tr class="">
                    <td class="text-center align-top especificacion" width="50px" colspan="2">
                        <h1 class="text-13 font-bold" style="color:#494949;">
                            MARCA</h1>

                        <p class="text-10 m-0" style="color:#5f5f5f;padding-bottom: 2px;">
                            {{ $item->name_marca }}</p>
                    </td>

                    @if (!empty($item->modelo))
                        <td class="text-center align-top especificacion" width="50px">
                            <h1 class="text-13 font-bold" style="color:#494949;">
                                MODELO</h1>

                            <p class="text-10 m-0" style="color:#5f5f5f;padding-bottom: 2px;">
                                {{ $item->modelo }}</p>
                        </td>
                    @endif
                </tr>
            @endif


            @if (count($especificacions) > 0)
                @foreach ($especificacions as $chunk)
                    @php
                        $clase = $loop->last ? '' : 'especificacion';
                        $clase = !empty($item->comentario) ? 'especificacion' : $clase;
                    @endphp
                    <tr class="">
                        @foreach ($chunk as $esp)
                            <td class="text-center align-top {{ $clase }}" width="70px">
                                <h1 class="text-13 font-bold" style="color:#494949;">
                                    {{ $esp->caracteristica->name }}</h1>

                                <p class="text-10 m-0" style="color:#5f5f5f;padding-bottom: 2px;">
                                    {{ $esp->name }}</p>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            @endif

            @if (!empty($item->comentario))
                <tr class="">
                    <td class="text-center align-top" colspan="3" width="50px">
                        <h1 class="text-13 font-bold" style="color:#494949;">
                            OTROS</h1>

                        <p class="text-10 m-0" style="color:#5f5f5f;padding-bottom: 2px;">
                            {!! nl2br($item->comentario) !!}</p>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</td>
