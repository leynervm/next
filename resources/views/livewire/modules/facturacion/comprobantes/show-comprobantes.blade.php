<div class="relative" x-data="{ loading: false }">

    <div class="flex flex-col xs:flex-row xs:flex-wrap gap-2">
        <div class="w-full sm:max-w-md">
            <x-label value="Cliente :" />
            <x-input wire:model.lazy="search" class="w-full" />
        </div>

        <div class="w-full xs:w-40">
            <x-label value="Serie :" />
            <x-input wire:model.lazy="serie" class="w-full block" />
        </div>
        <div class="w-full xs:w-auto">
            <x-label value="Fecha :" />
            <x-input type="date" wire:model.lazy="date" class="w-full block " />
        </div>

        <div class="w-full xs:w-auto">
            <x-label value="Hasta :" />
            <x-input type="date" wire:model.lazy="dateto" class="w-full block" />
        </div>

        @if (count($typepayments) > 1)
            <div class="w-full xs:w-52">
                <x-label value="Tipo pago :" />
                <div id="parentsearchtypepayment">
                    <x-select id="searchtypepayment" wire:model.lazy="searchtypepayment" class="w-full"
                        data-placeholder="null">
                        <x-slot name="options">
                            @if (count($typepayments))
                                @foreach ($typepayments as $item)
                                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                </div>
            </div>
        @endif

        <div class="w-full xs:w-52">
            <x-label value="Tipo comprobante :" />
            <div id="parentsearchtypecomprobante">
                <x-select class="w-full" wire:model.lazy="searchtypecomprobante" id="searchtypecomprobante"
                    data-placeholder="null">
                    <x-slot name="options">
                        @if (count($typecomprobantes))
                            @foreach ($typecomprobantes as $item)
                                <option value="{{ $item->code }}">{{ $item->descripcion }}</option>
                            @endforeach
                        @endif
                    </x-slot>
                </x-select>
            </div>
        </div>

        <div class="w-full xs:w-60">
            <x-label value="Usuario :" />
            <div id="parentsearchuser">
                <x-select id="searchuser" class="w-full" wire:model.lazy="searchuser" data-placeholder="null">
                    <x-slot name="options">
                        @foreach ($users as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </x-slot>
                </x-select>
            </div>
        </div>

        @if (count($sucursals) > 1)
            <div class="w-full xs:w-full xs:max-w-xs">
                <x-label value="Sucursal :" />
                <div id="parentsearchsucursal">
                    <x-select id="searchsucursal" wire:model.lazy="searchsucursal" class="w-full"
                        data-placeholder="null">
                        <x-slot name="options">
                            @if (count($sucursals))
                                @foreach ($sucursals as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                </div>
            </div>
        @endif
    </div>

    <div x-show="loading" wire:loading wire:loading.flex class="loading-overlay rounded">
        <x-loading-next />
    </div>

    @if ($comprobantes->hasPages())
        <div class="pt-3 pb-1">
            {{ $comprobantes->onEachSide(0)->links('livewire::pagination-default') }}
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

                <th scope="col" class="p-2 font-medium text-left">
                    CLIENTE</th>

                <th scope="col" class="p-2 font-medium">
                    TIPO PAGO</th>

                <th scope="col" class="p-2 font-medium">
                    GRAVADO</th>

                <th scope="col" class="p-2 font-medium">
                    EXONERADO</th>

                <th scope="col" class="p-2 font-medium">
                    IGV</th>

                {{-- <th scope="col" class="p-2 font-medium">
                    DESCUENTOS</th>

                <th scope="col" class="p-2 font-medium">
                    OTROS</th> --}}

                <th scope="col" class="p-2 font-medium">
                    TOTAL</th>

                <th scope="col" class="p-2 font-medium">
                    MONEDA</th>

                <th scope="col" class="p-2 font-medium text-center">
                    REFERENCIA</th>

                <th scope="col" class="p-2 font-medium">
                    USUARIO</th>

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
        @if (count($comprobantes))
            <x-slot name="body">
                @foreach ($comprobantes as $item)
                    <tr>
                        <td class="p-2 text-[10px]">
                            {{ $item->seriecompleta }}
                            <p class="leading-3">{{ $item->seriecomprobante->typecomprobante->descripcion }}</p>
                            {{-- <a href="#"
                                class="text-linktable hover:text-hoverlinktable inline-block leading-3 transition-colors ease-out duration-150">
                                {{ $item->seriecompleta }}
                                <br>
                                {{ $item->seriecomprobante->typecomprobante->descripcion }}
                            </a> --}}
                        </td>
                        <td class="p-2 text-xs uppercase">
                            {{-- <p>
                                {{ \Carbon\Carbon::parse($item->date)->locale('es')->isoformat('DD MMMM YYYY') }}
                            </p> --}}
                            {{ \Carbon\Carbon::parse($item->date)->locale('es')->format('d/m/Y h:i A') }}
                        </td>
                        <td class="p-2 text-xs text-left">
                            <p class="text-[10px]">{{ $item->client->document }}</p>
                            <p class="text-[10px]">{{ $item->client->name }}</p>
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->typepayment->name }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->gravado }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->exonerado }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->igv }}
                        </td>
                        {{-- <td class="p-2 text-xs text-center">
                            {{ $item->descuento }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->otros }} --}}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->total }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->moneda->currency }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->referencia }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->user->name }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->sucursal->name }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{-- {{ $item->deleted_at }} --}}
                            @if ($item->deleted_at)
                                <small class="p-1 text-[10px] leading-3 rounded text-white inline-block bg-red-500">
                                    ANULADO</small>
                            @else
                                @if ($item->seriecomprobante->typecomprobante->sendsunat)
                                    @if ($item->codesunat == '0')
                                        <small
                                            class="p-1 text-[10px] leading-3 rounded text-white inline-block bg-green-500">
                                            ENVIADO</small>
                                    @else
                                        <x-button wire:click="enviarsunat({{ $item->id }})"
                                            wire:loading.attr="disabled" class="inline-block">
                                            ENVIAR
                                        </x-button>
                                    @endif
                                @else
                                    <small
                                        class="p-1 text-[10px] leading-3 rounded text-textspancardproduct inline-block bg-fondospancardproduct">
                                        TICKET</small>
                                @endif
                            @endif
                        </td>
                        <td class="p-2 text-xs text-center">
                            @if ($item->codesunat != '0')
                                <p>{{ $item->codesunat }}</p>
                            @endif
                            <p class="leading-3">{{ $item->descripcion }}</p>
                        </td>
                        <td class="p-2 align-middle">
                            <div class="flex items-center justify-end gap-1">
                                <button
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
                                </button>
                                <button
                                    class="p-1.5 bg-neutral-900 text-white block rounded-lg transition-colors duration-150">
                                    <svg class="w-4 h-4 block scale-110" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        {{-- <path
                                        d="M22 8.87895C21.9331 7.33687 21.7456 6.33298 21.2203 5.53884C20.9181 5.08196 20.5428 4.68459 20.1112 4.36468C18.9447 3.5 17.299 3.5 14.0078 3.5H9.99305C6.70178 3.5 5.05614 3.5 3.88962 4.36468C3.45805 4.68459 3.08267 5.08196 2.78047 5.53884C2.25526 6.33289 2.06776 7.33665 2.00083 8.87843C1.98938 9.14208 2.21648 9.34375 2.46531 9.34375C3.85109 9.34375 4.97449 10.533 4.97449 12C4.97449 13.467 3.85109 14.6562 2.46531 14.6562C2.21648 14.6562 1.98938 14.8579 2.00083 15.1216C2.06776 16.6634 2.25526 17.6671 2.78047 18.4612C3.08267 18.918 3.45805 19.3154 3.88962 19.6353C5.05614 20.5 6.70178 20.5 9.99306 20.5H14.0078C17.299 20.5 18.9447 20.5 20.1112 19.6353C20.5428 19.3154 20.9181 18.918 21.2203 18.4612C21.7456 17.667 21.9331 16.6631 22 15.1211V8.87895Z" />
                                    <path d="M13 12L17 12" />
                                    <path d="M9 16L17 16" /> --}}
                                        {{-- <path stroke-width="0" stroke="none" fill="currentColor"
                                        d="M5.25 17C5.25 17.4142 5.58579 17.75 6 17.75C6.41421 17.75 6.75 17.4142 6.75 17H5.25ZM21.1213 2.87868L21.6517 2.34835V2.34835L21.1213 2.87868ZM16.2022 18.9846L16.9337 18.8191V18.8191L16.2022 18.9846ZM2.17264 18.9846L1.44113 19.1501L2.17264 18.9846ZM2.19447 17.3756L2.77981 17.8445L2.19447 17.3756ZM12 2.75H16V1.25H12V2.75ZM21.25 8V18H22.75V8H21.25ZM6.75 17V8H5.25V17H6.75ZM12 1.25C10.607 1.25 9.48678 1.24841 8.60825 1.36652C7.70814 1.48754 6.95027 1.74643 6.34835 2.34835L7.40901 3.40901C7.68577 3.13225 8.07435 2.9518 8.80812 2.85315C9.56347 2.75159 10.5646 2.75 12 2.75V1.25ZM6.75 8C6.75 6.56458 6.75159 5.56347 6.85315 4.80812C6.9518 4.07434 7.13225 3.68577 7.40901 3.40901L6.34835 2.34835C5.74643 2.95027 5.48754 3.70814 5.36652 4.60825C5.24841 5.48678 5.25 6.60699 5.25 8H6.75ZM16 2.75C17.4354 2.75 18.4365 2.75159 19.1919 2.85315C19.9257 2.9518 20.3142 3.13225 20.591 3.40901L21.6517 2.34835C21.0497 1.74643 20.2919 1.48754 19.3918 1.36652C18.5132 1.24841 17.393 1.25 16 1.25V2.75ZM22.75 8C22.75 6.60699 22.7516 5.48678 22.6335 4.60825C22.5125 3.70814 22.2536 2.95027 21.6517 2.34835L20.591 3.40901C20.8678 3.68577 21.0482 4.07434 21.1469 4.80812C21.2484 5.56347 21.25 6.56458 21.25 8H22.75ZM21.25 18C21.25 19.0355 21.0607 19.867 20.6999 20.417C20.3709 20.9185 19.8613 21.25 18.9937 21.25V22.75C20.3398 22.75 21.3334 22.186 21.9541 21.2398C22.543 20.3422 22.75 19.1736 22.75 18H21.25ZM18.9937 21.25C18.466 21.25 18.0603 21.0267 17.7233 20.618C17.3689 20.1881 17.1009 19.5582 16.9337 18.8191L15.4707 19.1501C15.6671 20.0185 16.0087 20.8963 16.566 21.5722C17.1408 22.2694 17.9489 22.75 18.9937 22.75V21.25ZM4.96411 21.25C4.43641 21.25 4.0307 21.0267 3.69378 20.618C3.33932 20.1881 3.07136 19.5582 2.90415 18.8191L1.44113 19.1501C1.63758 20.0185 1.97915 20.8963 2.53641 21.5722C3.1112 22.2694 3.91939 22.75 4.96411 22.75V21.25ZM4.96411 22.75H18.9937V21.25H4.96411V22.75ZM3.96199 17.75H13.9831V16.25H3.96199V17.75ZM2.90415 18.8191C2.80483 18.3801 2.75633 18.1518 2.7505 17.9862C2.7466 17.8755 2.7631 17.8654 2.77981 17.8445L1.60913 16.9067C1.32496 17.2614 1.23753 17.6446 1.25143 18.039C1.26339 18.3784 1.35442 18.7668 1.44113 19.1501L2.90415 18.8191ZM3.96199 16.25C3.49923 16.25 3.059 16.2476 2.70126 16.3071C2.3029 16.3734 1.91089 16.53 1.60913 16.9067L2.77981 17.8445C2.78888 17.8332 2.79271 17.8307 2.8012 17.8264C2.81587 17.8189 2.85623 17.802 2.94755 17.7868C3.15391 17.7524 3.447 17.75 3.96199 17.75V16.25ZM16.9337 18.8191C16.8204 18.3182 16.7246 17.8912 16.6188 17.5654C16.5168 17.2517 16.3636 16.8863 16.0455 16.6326L15.1101 17.8052C15.0665 17.7704 15.1088 17.7723 15.1921 18.0288C15.2716 18.2734 15.3507 18.6198 15.4707 19.1501L16.9337 18.8191ZM13.9831 17.75C14.4403 17.75 14.7226 17.751 14.9274 17.7731C15.1245 17.7943 15.136 17.8258 15.1101 17.8052L16.0455 16.6326C15.7453 16.3931 15.3995 16.3152 15.0879 16.2817C14.784 16.249 14.4062 16.25 13.9831 16.25V17.75Z" />
                                    <path d="M10.5 7H17.5" />
                                    <path d="M10.5 11H14" />      --}}
                                        <path
                                            d="M7.35396 18C5.23084 18 4.16928 18 3.41349 17.5468C2.91953 17.2506 2.52158 16.8271 2.26475 16.3242C1.87179 15.5547 1.97742 14.5373 2.18868 12.5025C2.36503 10.8039 2.45321 9.95455 2.88684 9.33081C3.17153 8.92129 3.55659 8.58564 4.00797 8.35353C4.69548 8 5.58164 8 7.35396 8H16.646C18.4184 8 19.3045 8 19.992 8.35353C20.4434 8.58564 20.8285 8.92129 21.1132 9.33081C21.5468 9.95455 21.635 10.8039 21.8113 12.5025C22.0226 14.5373 22.1282 15.5547 21.7352 16.3242C21.4784 16.8271 21.0805 17.2506 20.5865 17.5468C19.8307 18 18.7692 18 16.646 18" />
                                        <path
                                            d="M17 8V6C17 4.11438 17 3.17157 16.4142 2.58579C15.8284 2 14.8856 2 13 2H11C9.11438 2 8.17157 2 7.58579 2.58579C7 3.17157 7 4.11438 7 6V8" />
                                        <path
                                            d="M13.9887 16L10.0113 16C9.32602 16 8.98337 16 8.69183 16.1089C8.30311 16.254 7.97026 16.536 7.7462 16.9099C7.57815 17.1904 7.49505 17.5511 7.32884 18.2724C7.06913 19.3995 6.93928 19.963 7.02759 20.4149C7.14535 21.0174 7.51237 21.5274 8.02252 21.7974C8.40513 22 8.94052 22 10.0113 22L13.9887 22C15.0595 22 15.5949 22 15.9775 21.7974C16.4876 21.5274 16.8547 21.0174 16.9724 20.4149C17.0607 19.963 16.9309 19.3995 16.6712 18.2724C16.505 17.5511 16.4218 17.1904 16.2538 16.9099C16.0297 16.536 15.6969 16.254 15.3082 16.1089C15.0166 16 14.674 16 13.9887 16Z" />
                                    </svg>
                                </button>

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
                                            <a href="#"
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
                                            <a href="#"
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
                                            <a href="#"
                                                class="w-full p-2.5 rounded-md text-[10px] text-colordropdown hover:bg-fondohoverdropdown flex gap-1 items-center justify-between">
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
                                            </a>
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

</div>
