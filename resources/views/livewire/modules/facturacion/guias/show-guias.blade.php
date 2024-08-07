<div class="relative">
    <div wire:loading.flex class="loading-overlay rounded fixed hidden">
        <x-loading-next />
    </div>

    <div class="flex flex-col xs:flex-row xs:flex-wrap gap-2">
        <div class="w-full sm:max-w-md">
            <x-label value="Destinatario :" />
            <x-input wire:model.lazy="search" class="w-full" placeholder="Buscar destinatario..." />
        </div>

        <div class="w-full xs:w-40">
            <x-label value="Serie :" />
            <x-input wire:model.lazy="serie" class="w-full block" placeholder="Buscar serie..." />
        </div>
        <div class="w-full xs:w-auto">
            <x-label value="Fecha :" />
            <x-input type="date" wire:model.lazy="date" class="w-full block " />
        </div>

        <div class="w-full xs:w-auto">
            <x-label value="Hasta :" />
            <x-input type="date" wire:model.lazy="dateto" class="w-full block" />
        </div>
    </div>

    @if ($guias->hasPages())
        <div class="pt-3 pb-1">
            {{ $guias->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <x-table class="mt-1">
        <x-slot name="header">
            <tr>
                <th scope="col" class="p-2 font-medium text-left">
                    SERIE</th>
                <th scope="col" class="p-2 font-medium">
                    <button class="flex items-center gap-x-3 focus:outline-none">
                        <span>FECHA</span>
                        <svg class="h-3 w-3" viewBox="0 0 10 11" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" stroke="currentColor" stroke-width="0.1">
                            <path
                                d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z" />
                            <path
                                d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z" />
                            <path
                                d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z"
                                stroke-width="0.3" />
                        </svg>
                    </button>
                </th>
                <th scope="col" class="p-2 font-medium">
                    MOTIVO TRASLADO</th>
                <th scope="col" class="p-2 font-medium">
                    MODALIDAD TRANSPORTE</th>
                <th scope="col" class="p-2 font-medium text-left">
                    DESTINATARIO</th>
                <th scope="col" class="p-2 font-medium text-left">
                    DATOS TRANSPORTE</th>
                <th scope="col" class="p-2 font-medium">
                    PESO</th>
                <th scope="col" class="p-2 font-medium">
                    FECHA TRASLADO</th>
                {{-- <th scope="col" class="p-2 font-medium">
                    LUGAR EMISIÓN</th> --}}
                <th scope="col" class="p-2 font-medium">
                    LUGAR DE ENVÍO</th>
                <th scope="col" class="p-2 font-medium">
                    INFORMACIÓN ADICIONAL</th>
                <th scope="col" class="p-2 font-medium text-center">
                    REFERENCIA</th>
                <th scope="col" class="p-2 font-medium">
                    SUCURSAL</th>
                <th scope="col" class="p-2 font-medium">
                    SUNAT</th>
                <th scope="col" class="p-2 font-medium">
                    DESCRIPCIÓN SUNAT</th>
                <th scope="col" class="p-2 font-medium text-center">
                    OPCIONES</th>
            </tr>
        </x-slot>
        @if (count($guias))
            <x-slot name="body">
                @foreach ($guias as $item)
                    <tr>
                        <td class="p-2 text-[10px]">
                            @can('admin.facturacion.guias.create')
                                <a href="{{ route('admin.facturacion.guias.edit', $item) }}"
                                    class="text-linktable leading-3 hover:text-hoverlinktable inline-block transition-colors ease-out duration-150">
                                    {{ $item->seriecompleta }}
                                    <br>
                                    {{ $item->seriecomprobante->typecomprobante->descripcion }}
                                </a>
                            @endcan

                            @cannot('admin.facturacion.guias.create')
                                <p class="text-linktable inline-block leading-3">
                                    {{ $item->seriecompleta }}
                                    <br>
                                    {{ $item->seriecomprobante->typecomprobante->descripcion }}
                                </p>
                            @endcannot
                        </td>
                        <td class="p-2 uppercase">
                            {{ formatdate($item->date) }}
                        </td>
                        <td class="p-2 text-[10px] text-center leading-3">
                            {{ $item->motivotraslado->name }}

                            @if ($item->motivotraslado->code == '02')
                                <p class="leading-3 mt-1"><b>PROVEEDOR : </b>
                                    {{ $item->rucproveedor }}
                                    <br>
                                    {{ $item->nameproveedor }}
                                </p>
                            @elseif($item->motivotraslado->code == '03')
                                <p class="leading-3 mt-1"><b>COMPRADOR : </b>
                                    {{ $item->client->document }}
                                    <br>
                                    {{ $item->client->name }}
                                </p>
                            @endif
                        </td>
                        <td class="p-2 text-[10px] text-center uppercase leading-3">
                            {{ $item->modalidadtransporte->name }}
                        </td>
                        <td class="p-2 text-[10px] text-left">
                            <p>{{ $item->documentdestinatario }}</p>
                            <p class="leading-3">{{ $item->namedestinatario }}</p>
                        </td>
                        <td class="p-2 text-[10px]">
                            <p>{{ $item->ructransport }}</p>
                            <p class="leading-3">{{ $item->nametransport }}</p>
                        </td>
                        <td class="p-2 text-center whitespace-nowrap">
                            <p>{{ formatDecimalOrInteger($item->peso) }} {{ $item->unit }}</p>

                            @if ($item->packages)
                                <p>{{ $item->packages }} {{ $item->packages > 1 ? 'BULTOS' : 'BULTO' }}</p>
                            @endif
                        </td>
                        <td class="p-2 text-center">
                            {{ formatdate($item->datetraslado, 'DD MMMM Y') }}
                        </td>
                        {{-- <td class="p-2 text-[10px]">
                            <p>{{ $item->direccionorigen }}</p>
                            <p class="whitespace-nowrap">{{ $item->ubigeoorigen->region }},
                                {{ $item->ubigeoorigen->provincia }},
                                {{ $item->ubigeoorigen->distrito }}</p>
                        </td> --}}
                        <td class="p-2 text-[10px]">
                            <p>{{ $item->direcciondestino }}</p>
                            <p class="whitespace-nowrap">{{ $item->ubigeodestino->region }},
                                {{ $item->ubigeodestino->provincia }},
                                {{ $item->ubigeodestino->distrito }}</p>
                        </td>
                        <td class="p-2">
                            <p class="leading-3">
                                @if ($item->indicadortransbordo == '1')
                                    {{ getIndicadorTransbProg()->name }}
                                @endif
                                @if ($item->indicadorvehiculosml == '1')
                                    {{ getIndicadorVehiculoML()->name }}
                                @endif
                                @if ($item->indicadorvehretorenvacios == '1')
                                    {{ getIndicadorRetornoVehEnvaVacio()->name }}
                                @endif
                                @if ($item->indicadorvehretorvacio == '1')
                                    {{ getIndicadorRetornoVehVacio()->name }}
                                @endif
                                @if ($item->indicadordamds == '1')
                                    {{ getIndicadorTotalDAMDS()->name }}
                                @endif
                                @if ($item->indicadorconductor == '1')
                                    {{ getIndicadorRegistrarVehCondTransport()->name }}
                                @endif
                            </p>
                        </td>
                        <td class="p-2 text-center">
                            @if ($item->guiable)
                                {{ $item->guiable->seriecompleta }}
                            @endif
                        </td>
                        <td class="p-2 text-center min-w-[120px]">
                            <p class="leading-3">{{ $item->sucursal->name }}</p>
                            @if ($item->sucursal->trashed())
                                <p><x-span-text text="NO DISPONIBLE" class="leading-3 !tracking-normal" /></p>
                            @endif
                            <p class="text-[10px] text-colorsubtitleform leading-3">
                                {{ $item->user->name }}</p>
                        </td>
                        <td class="p-2 text-center">
                            @if ($item->seriecomprobante->typecomprobante->sendsunat)
                                @if ($item->isSendSunat())
                                    <x-span-text text="ENVIADO" class="leading-3 !tracking-normal" type="green" />
                                @else
                                    @can('admin.facturacion.guias.sunat')
                                        <x-button wire:click="enviarsunat({{ $item->id }})"
                                            wire:loading.attr="disabled" class="inline-block">
                                            ENVIAR</x-button>
                                    @endcan
                                @endif
                            @else
                                <x-span-text text="LOCAL" class="leading-3 !tracking-normal" />
                            @endif
                        </td>
                        <td class="p-2 text-center w-48">
                            <p class="text-center text-wrap leading-3">
                                {{ $item->descripcion }}</p>

                            @if (!$item->isSendSunat())
                                <p>{{ $item->codesunat }}</p>
                            @endif
                        </td>
                        <td class="p-2 align-middle">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.facturacion.guias.print', $item) }}" target="_blank"
                                    class="p-1.5 bg-red-800 text-white block rounded-lg transition-colors duration-150">
                                    <svg class="w-4 h-4 block scale-110 " xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path
                                            d="M7 18V15.5M7 15.5V14C7 13.5286 7 13.2929 7.15377 13.1464C7.30754 13 7.55503 13 8.05 13H8.75C9.47487 13 10.0625 13.5596 10.0625 14.25C10.0625 14.9404 9.47487 15.5 8.75 15.5H7ZM21 13H19.6875C18.8625 13 18.4501 13 18.1938 13.2441C17.9375 13.4882 17.9375 13.881 17.9375 14.6667V15.5M17.9375 18V15.5M17.9375 15.5H20.125M15.75 15.5C15.75 16.8807 14.5747 18 13.125 18C12.7979 18 12.6343 18 12.5125 17.933C12.2208 17.7726 12.25 17.448 12.25 17.1667V13.8333C12.25 13.552 12.2208 13.2274 12.5125 13.067C12.6343 13 12.7979 13 13.125 13C14.5747 13 15.75 14.1193 15.75 15.5Z" />
                                        <path
                                            d="M15 22H10.7273C7.46607 22 5.83546 22 4.70307 21.2022C4.37862 20.9736 4.09058 20.7025 3.8477 20.3971C3 19.3313 3 17.7966 3 14.7273V12.1818C3 9.21865 3 7.73706 3.46894 6.55375C4.22281 4.65142 5.81714 3.15088 7.83836 2.44135C9.09563 2 10.6698 2 13.8182 2C15.6173 2 16.5168 2 17.2352 2.2522C18.3902 2.65765 19.3012 3.5151 19.732 4.60214C20 5.27832 20 6.12494 20 7.81818V10" />
                                        <path
                                            d="M3 12C3 10.1591 4.49238 8.66667 6.33333 8.66667C6.99912 8.66667 7.78404 8.78333 8.43137 8.60988C9.00652 8.45576 9.45576 8.00652 9.60988 7.43136C9.78333 6.78404 9.66667 5.99912 9.66667 5.33333C9.66667 3.49238 11.1591 2 13 2" />
                                    </svg>
                                </a>

                                @if ($item->seriecomprobante->typecomprobante->sendsunat && $item->codesunat == '0')
                                    <div x-data="{ dropdownOpen: false }" class="">
                                        <button @click="dropdownOpen = !dropdownOpen"
                                            class="p-1.5 mx-auto bg-fondospancardproduct text-textspancardproduct block rounded-lg transition-colors duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="w-4 h-4">
                                                <path
                                                    d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                            </svg>
                                        </button>
                                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                                            class="absolute right-0 mt-1 w-36 bg-fondodropdown rounded-md shadow shadow-fondominicard border border-borderminicard z-20">
                                            <a target="_blank"
                                                href="{{ route('facturacion.guia.download.xml', ['guia' => $item, 'type' => 'xml']) }}"
                                                class="w-full p-2.5 rounded-md text-[10px] text-colordropdown hover:bg-fondohoverdropdown flex gap-1 items-center justify-between">
                                                DESCARGAR XML
                                                <svg class="w-4 h-4 block scale-110"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" fill="none">
                                                    <path
                                                        d="M7 13L8.64706 15.5M8.64706 15.5L10.2941 18M8.64706 15.5L10.2941 13M8.64706 15.5L7 18M21 18H20.1765C19.4 18 19.0118 18 18.7706 17.7559C18.5294 17.5118 18.5294 17.119 18.5294 16.3333V13M12.3529 17.9999L12.6946 13.8346C12.7236 13.4813 12.7381 13.3046 12.845 13.2716C12.9518 13.2386 13.0613 13.3771 13.2801 13.6539L14.1529 14.7579C14.2716 14.9081 14.331 14.9831 14.4102 14.9831C14.4893 14.9831 14.5487 14.9081 14.6674 14.7579L15.5407 13.6533C15.7594 13.3767 15.8687 13.2384 15.9755 13.2713C16.0824 13.3042 16.097 13.4807 16.1262 13.8338L16.4706 17.9999" />
                                                    <path
                                                        d="M15 22H10.7273C7.46607 22 5.83546 22 4.70307 21.2022C4.37862 20.9736 4.09058 20.7025 3.8477 20.3971C3 19.3313 3 17.7966 3 14.7273V12.1818C3 9.21865 3 7.73706 3.46894 6.55375C4.22281 4.65142 5.81714 3.15088 7.83836 2.44135C9.09563 2 10.6698 2 13.8182 2C15.6173 2 16.5168 2 17.2352 2.2522C18.3902 2.65765 19.3012 3.5151 19.732 4.60214C20 5.27832 20 6.12494 20 7.81818V10" />
                                                    <path
                                                        d="M3 12C3 10.1591 4.49238 8.66667 6.33333 8.66667C6.99912 8.66667 7.78404 8.78333 8.43137 8.60988C9.00652 8.45576 9.45576 8.00652 9.60988 7.43136C9.78333 6.78404 9.66667 5.99912 9.66667 5.33333C9.66667 3.49238 11.1591 2 13 2" />
                                                </svg>
                                            </a>
                                            <a target="_blank"
                                                href="{{ route('facturacion.guia.download.xml', ['guia' => $item, 'type' => 'cdr']) }}"
                                                class="w-full p-2.5 rounded-md text-[10px] text-colordropdown hover:bg-fondohoverdropdown flex gap-1 items-center justify-between">
                                                DESCARGAR CDR
                                                <svg class="w-4 h-4 block scale-110"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" fill="none">
                                                    <path
                                                        d="M12.5 2H12.7727C16.0339 2 17.6645 2 18.7969 2.79784C19.1214 3.02643 19.4094 3.29752 19.6523 3.60289C20.5 4.66867 20.5 6.20336 20.5 9.27273V11.8182C20.5 14.7814 20.5 16.2629 20.0311 17.4462C19.2772 19.3486 17.6829 20.8491 15.6616 21.5586C14.4044 22 12.8302 22 9.68182 22C7.88275 22 6.98322 22 6.26478 21.7478C5.10979 21.3424 4.19875 20.4849 3.76796 19.3979C3.5 18.7217 3.5 17.8751 3.5 16.1818V12" />
                                                    <path
                                                        d="M20.5 12C20.5 13.8409 19.0076 15.3333 17.1667 15.3333C16.5009 15.3333 15.716 15.2167 15.0686 15.3901C14.4935 15.5442 14.0442 15.9935 13.8901 16.5686C13.7167 17.216 13.8333 18.0009 13.8333 18.6667C13.8333 20.5076 12.3409 22 10.5 22" />
                                                    <path
                                                        d="M4.5 7.5C4.99153 8.0057 6.29977 10 7 10M9.5 7.5C9.00847 8.0057 7.70023 10 7 10M7 10L7 2" />
                                                </svg>
                                            </a>
                                            <button wire:click="enviarxml({{ $item->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-full p-2.5 rounded-md text-[10px] text-colordropdown hover:bg-fondohoverdropdown flex gap-1 items-center justify-between disabled:opacity-25">
                                                ENVIAR CORREO
                                                <svg class="w-4 h-4 block scale-110"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" fill="none">
                                                    <path
                                                        d="M22 12.5001C22 12.0087 21.9947 11.0172 21.9842 10.5244C21.9189 7.45886 21.8862 5.92609 20.7551 4.79066C19.6239 3.65523 18.0497 3.61568 14.9012 3.53657C12.9607 3.48781 11.0393 3.48781 9.09882 3.53656C5.95033 3.61566 4.37608 3.65521 3.24495 4.79065C2.11382 5.92608 2.08114 7.45885 2.01576 10.5244C1.99474 11.5101 1.99475 12.4899 2.01577 13.4756C2.08114 16.5412 2.11383 18.0739 3.24496 19.2094C4.37608 20.3448 5.95033 20.3843 9.09883 20.4634C9.90159 20.4836 10.7011 20.4954 11.5 20.4989" />
                                                    <path
                                                        d="M2 6L8.91302 9.92462C11.4387 11.3585 12.5613 11.3585 15.087 9.92462L22 6" />
                                                    <path
                                                        d="M22 17.5L14 17.5M22 17.5C22 16.7998 20.0057 15.4915 19.5 15M22 17.5C22 18.2002 20.0057 19.5085 19.5 20" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        @endif
    </x-table>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('select2Sucursal', () => ({
                searchsucursal: @entangle('searchsucursal'),
                init() {
                    this.select2 = $(this.$refs.select).select2();
                    this.select2.on("select2:select", (event) => {
                        this.searchsucursal = event.target.value;
                    }).on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });
                    this.$watch("searchsucursal", (value) => {
                        this.select2.val(value).trigger("change");
                    });
                },
            }))
        })
    </script>
</div>
