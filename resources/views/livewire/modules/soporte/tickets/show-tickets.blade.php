<div class="w-full" id="show-tickets">
    <x-loading-web-next x-cloak wire:loading.flex wire:key="loadingshowtickets" />

    <x-table class="mt-1 overflow-hidden">
        <x-slot name="header">
            <tr>
                <th class="p-2">
                    N° TICKET
                </th>
                <th class="p-2">
                    CLIENTE
                </th>
                <th class="p-2">
                    CONDICIÓN
                </th>
                <th class="p-2">
                    TIPO ATENCIÓN
                </th>
                <th class="p-2">
                    DETALLES
                </th>
                <th class="p-2">
                    ESTADO
                </th>
                <th class="p-2">
                    USUARIO<br> AREA RESPONSABLE
                </th>
                <th class="p-2">
                    SUCURSAL
                </th>
            </tr>
        </x-slot>
        <x-slot name="body">
            @foreach ($tickets as $item)
                <tr>
                    <td class="p-2" style="width: 90px;min-width: 90px;">
                        <span
                            class="text-white text-[9px] p-1 leading-none rounded-md font-medium tracking-widest inline-block"
                            style="background: {{ $item->priority->color }}">
                            {{ $item->priority->name }}
                        </span>


                        <p class="font-semibold text-xs">
                            {{ $item->seriecompleta }}</p>
                        <p class="!leading-3 text-[10px]">
                            {{ formatDate($item->date) }}
                            {{-- {{ formatHuman($item->date) }} --}}
                        </p>
                    </td>
                    <td class="p-2 text-[10px]" style="min-width: 200px;">
                        @if ($item->contact)
                            <b>{{ $item->client->name }}</b> - {{ $item->client->document }}
                            <p class="text-colorsubtitleform">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="inline-block size-4 text-colorsubtitleform">
                                    <path
                                        d="M20 6v12a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2z" />
                                    <path d="M10 16h6" />
                                    <path d="M13 11m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M4 8h3" />
                                    <path d="M4 12h3" />
                                    <path d="M4 16h3" />
                                </svg>
                                {{ $item->contact->name }}
                            </p>
                        @else
                            <b>{{ $item->client->name }}</b> <br>
                            {{ $item->client->document }}
                        @endif

                        @if (count($item->telephones) > 0)
                            <div class="mt-1">
                                @foreach ($item->telephones as $phone)
                                    <span
                                        class="inline-block bg-green-100 p-0.5 text-green-700 tracking-wide font-semibold">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" class="inline-block size-4 text-current">
                                            <path d="M20 4l-2 2" />
                                            <path d="M22 10.5l-2.5 -.5" />
                                            <path d="M13.5 2l.5 2.5" />
                                            <path
                                                d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2c-8.072 -.49 -14.51 -6.928 -15 -15a2 2 0 0 1 2 -2" />
                                        </svg>
                                        {{ formatTelefono($phone->phone) }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td class="p-2 text-[10px]">
                        {{ $item->condition->name }}
                    </td>
                    <td class="p-2 text-[10px]">
                        <b>{{ $item->entorno->name }}</b><br>
                        {{ $item->atencion->name }}
                    </td>
                    <td class="p-2 text-[10px]" style="min-width: 300px;max-width: 450px;">
                        @if ($item->equipo)
                            {{ $item->equipo->typeequipo->name }}
                            {{ $item->equipo->marca->name }} <br>
                            {{ $item->equipo->modelo }}
                            @if (!empty($item->equipo->serie))
                                <span class="inline-block">
                                    <b>SERIE: </b> {{ $item->equipo->serie }}
                                </span>
                            @endif
                        @endif

                        <p class="leading-3 font-semibold">
                            {{ $item->detalle }}</p>

                        @if ($item->direccion)
                            <p class="">
                                {{ $item->direccion->name }}</p>
                            @if (!empty($item->direccion->referencia))
                                <p class="leading-3">
                                    <b>REF:</b> {{ $item->direccion->referencia }}
                                </p>
                            @endif
                            <p class="leading-3">
                                {{ $item->direccion->ubigeo->distrito }},
                                {{ $item->direccion->ubigeo->provincia }},
                                {{ $item->direccion->ubigeo->region }}
                            </p>
                        @endif
                    </td>
                    <td class="p-2 text-[10px] text-center">
                        <span class="text-white text-[10px] p-1 rounded-lg font-semibold inline-block"
                            style="background: {{ $item->estate->color }}">
                            {{ $item->estate->name }}
                        </span>
                    </td>
                    <td class="p-2 text-[10px] text-center" style="min-width: 180px;">
                        @if ($item->userasigned)
                            {{ $item->userasigned->name }}
                        @else
                            <x-button class="mx-auto">
                                ASIGNAR USUARIO
                            </x-button>
                        @endif
                        {{ $item->areawork->name }}
                    </td>
                    <td class="p-2 text-[10px] text-center" style="min-width: 120px;">
                        <p class="leading-3">
                            {{ $item->sucursal->name }}</p>
                        @if ($item->sucursal->trashed())
                            <x-span-text text="NO DISPONIBLE" class="leading-3 !tracking-normal inline-block" />
                        @endif
                        <p class="text-[10px] text-colorsubtitleform">
                            {{ $item->user->name }}</p>
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-table>

    @if ($tickets->hasPages())
        <div class="w-full flex justify-center items-center sm:justify-end p-1 sticky -bottom-1 right-0 bg-body">
            {{ $tickets->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif
</div>
