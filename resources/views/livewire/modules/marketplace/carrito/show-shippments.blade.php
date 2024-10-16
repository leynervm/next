<div x-data="shipment">

    {{-- <x-loading-web-next class="!hidden" wire:loading.class.remove="!hidden" /> --}}

    <form @submit.prevent="save" class="w-full " id="register_order" autocomplete="off">
        <div class="w-full grid grid-cols-2 gap-3 items-end border-b border-borderminicard mb-5">
            <p class="text-sm font-medium text-colorsubtitleform">TOTAL</p>
            <p class="text-3xl text-right font-semibold text-colorlabel">
                <small class="text-[10px] font-medium">{{ $moneda->simbolo }}</small>
                {{ formatDecimalOrInteger(Cart::instance('shopping')->subtotal(), 2, ', ') }}
            </p>
        </div>

        <div class="w-full flex flex-col ">
            {{-- <h1 class="text-lg font-semibold text-primary">
                TIPO DE ENTREGA</h1> --}}

            @if (count($order) > 0)
                <div class="w-full">
                    <span class="block w-12 h-12 text-colorsubtitleform">
                        @if ($shipmenttype->isEnviodomicilio())
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 33" fill-rule="evenodd"
                                clip-rule="evenodd" fill="currentColor" class="w-full h-full">
                                <path
                                    d="M3 7.5C3 6.39543 3.89543 5.5 5 5.5H17C18.1046 5.5 19 6.39543 19 7.5V10.5H24.4338C25.1363 10.5 25.7873 10.8686 26.1488 11.471L28.715 15.748C28.9015 16.0588 29 16.4145 29 16.777V22.5C29 23.6046 28.1046 24.5 27 24.5H25.874C25.4299 26.2252 23.8638 27.5 22 27.5C20.0283 27.5 18.3898 26.0734 18.0604 24.1961C17.753 24.3887 17.3895 24.5 17 24.5H12.874C12.4299 26.2252 10.8638 27.5 9 27.5C7.12577 27.5 5.55261 26.211 5.1187 24.4711C3.91896 24.2875 3 23.2511 3 22V21.5C3 20.9477 3.44772 20.5 4 20.5C4.55228 20.5 5 20.9477 5 21.5V22C5 22.1459 5.06252 22.2773 5.16224 22.3687C5.65028 20.7105 7.18378 19.5 9 19.5C10.8638 19.5 12.4299 20.7748 12.874 22.5H17V16.5V7.5H5V8.5C5 9.05228 4.55228 9.5 4 9.5C3.44772 9.5 3 9.05228 3 8.5V7.5ZM19 15.5V12.5H24.4338L26.2338 15.5H19ZM19 17.5H27V22.5H25.874C25.4299 20.7748 23.8638 19.5 22 19.5C20.8053 19.5 19.7329 20.0238 19 20.8542V17.5ZM22 21.5C23.1046 21.5 24 22.3954 24 23.5C24 24.6046 23.1046 25.5 22 25.5C20.8954 25.5 20 24.6046 20 23.5C20 22.3954 20.8954 21.5 22 21.5ZM7 23.5C7 24.6046 7.89543 25.5 9 25.5C10.1046 25.5 11 24.6046 11 23.5C11 22.3954 10.1046 21.5 9 21.5C7.89543 21.5 7 22.3954 7 23.5ZM2 10.5C1.44772 10.5 1 10.9477 1 11.5C1 12.0523 1.44772 12.5 2 12.5H7C7.55228 12.5 8 12.0523 8 11.5C8 10.9477 7.55228 10.5 7 10.5H2ZM3 13.5C2.44772 13.5 2 13.9477 2 14.5C2 15.0523 2.44772 15.5 3 15.5H7C7.55228 15.5 8 15.0523 8 14.5C8 13.9477 7.55228 13.5 7 13.5H3ZM3 17.5C3 16.9477 3.44772 16.5 4 16.5H7C7.55229 16.5 8 16.9477 8 17.5C8 18.0523 7.55229 18.5 7 18.5H4C3.44772 18.5 3 18.0523 3 17.5Z" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 33" fill-rule="evenodd"
                                clip-rule="evenodd" fill="currentColor" class="w-full h-full">
                                <path
                                    d="M18.4449 14.2024C19.4296 12.8623 20 11.5761 20 10.5C20 8.29086 18.2091 6.5 16 6.5C13.7909 6.5 12 8.29086 12 10.5C12 11.5761 12.5704 12.8623 13.5551 14.2024C14.3393 15.2698 15.2651 16.2081 16 16.8815C16.7349 16.2081 17.6607 15.2698 18.4449 14.2024ZM16.8669 18.7881C18.5289 17.3455 22 13.9227 22 10.5C22 7.18629 19.3137 4.5 16 4.5C12.6863 4.5 10 7.18629 10 10.5C10 13.9227 13.4712 17.3455 15.1331 18.7881C15.6365 19.2251 16.3635 19.2251 16.8669 18.7881ZM5 11.5H8.27078C8.45724 12.202 8.72804 12.8724 9.04509 13.5H5V26.5H10.5V22C10.5 21.4477 10.9477 21 11.5 21H20.5C21.0523 21 21.5 21.4477 21.5 22V26.5H27V13.5H22.9549C23.272 12.8724 23.5428 12.202 23.7292 11.5H27C28.1046 11.5 29 12.3954 29 13.5V26.5C29.5523 26.5 30 26.9477 30 27.5C30 28.0523 29.5523 28.5 29 28.5H3C2.44772 28.5 2 28.0523 2 27.5C2 26.9477 2.44772 26.5 3 26.5V13.5C3 12.3954 3.89543 11.5 5 11.5ZM19.5 23V26.5H12.5V23H19.5ZM17 10.5C17 11.0523 16.5523 11.5 16 11.5C15.4477 11.5 15 11.0523 15 10.5C15 9.94772 15.4477 9.5 16 9.5C16.5523 9.5 17 9.94772 17 10.5ZM19 10.5C19 12.1569 17.6569 13.5 16 13.5C14.3431 13.5 13 12.1569 13 10.5C13 8.84315 14.3431 7.5 16 7.5C17.6569 7.5 19 8.84315 19 10.5Z" />
                            </svg>
                        @endif
                    </span>
                    <p class="text-sm text-colorlabel font-semibold">
                        {{ $shipmenttype->name }}</p>

                    @if (!empty($order['local_entrega']))
                        <p class="text-sm font-semibold text-primary">
                            {{ $order['local_entrega']['name'] }}</p>
                        <p class="text-[10px] font-medium text-colorlabel">
                            {{ $order['local_entrega']['direccion'] }}</p>
                        <p class="text-[10px] font-medium text-colorsubtitleform">
                            {{ $order['local_entrega']['ubigeo']['distrito'] }},
                            {{ $order['local_entrega']['ubigeo']['provincia'] }},
                            {{ $order['local_entrega']['ubigeo']['region'] }}</p>

                        <p class="text-[10px] leading-3 font-medium text-colorsubtitleform mt-2">
                            FECHA ENTREGA</p>
                        <p class="text-sm font-semibold text-colorlabel leading-3">
                            {{ formatDate($order['daterecojo'], 'DD MMMM Y') }}</p>
                    @else
                        <p class="text-[10px] font-semibold text-colorlabel">
                            {{ $order['direccion_envio']['name'] }}</p>
                        <p class="text-[10px] font-semibold text-colorsubtitleform">
                            REF.: {{ $order['direccion_envio']['referencia'] }}</p>
                        <p class="text-[10px] font-semibold text-colorlabel">
                            {{ $order['direccion_envio']['ubigeo']['distrito'] }},
                            {{ $order['direccion_envio']['ubigeo']['provincia'] }},
                            {{ $order['direccion_envio']['ubigeo']['region'] }}</p>
                    @endif
                </div>
            @else
                <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-2">
                    @if (count($shipmenttypes) > 0)
                        @foreach ($shipmenttypes as $item)
                            <div class="relative w-full">
                                <input x-model="shipmenttype_id" class="sr-only peer peer-disabled:opacity-25"
                                    type="radio" id="shipmenttpe_{{ $item->id }}" name="shipmenttypes"
                                    value="{{ $item->id }}" @change="seleccionarenvio({{ $item }})" />
                                <label for="shipmenttpe_{{ $item->id }}"
                                    class ="text-xs relative flex justify-center items-center border border-ringbutton gap-1 text-center font-medium ring-ringbutton text-colorlabel p-2.5 px-3 rounded-lg cursor-pointer hover:bg-fondohoverbutton hover:ring-fondohoverbutton hover:border-fondobutton hover:text-colorhoverbutton peer-checked:bg-fondohoverbutton peer-checked:ring-2 peer-checked:ring-ringbutton peer-checked:text-colorhoverbutton peer-focus:text-colorhoverbutton checked:bg-fondohoverbutton peer-disabled:opacity-25 transition ease-in-out duration-150">
                                    <span class="block w-6 sm:w-10 h-6 sm:h-10">
                                        @if ($item->isEnviodomicilio())
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 33"
                                                fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"
                                                class="w-full h-full">
                                                <path
                                                    d="M3 7.5C3 6.39543 3.89543 5.5 5 5.5H17C18.1046 5.5 19 6.39543 19 7.5V10.5H24.4338C25.1363 10.5 25.7873 10.8686 26.1488 11.471L28.715 15.748C28.9015 16.0588 29 16.4145 29 16.777V22.5C29 23.6046 28.1046 24.5 27 24.5H25.874C25.4299 26.2252 23.8638 27.5 22 27.5C20.0283 27.5 18.3898 26.0734 18.0604 24.1961C17.753 24.3887 17.3895 24.5 17 24.5H12.874C12.4299 26.2252 10.8638 27.5 9 27.5C7.12577 27.5 5.55261 26.211 5.1187 24.4711C3.91896 24.2875 3 23.2511 3 22V21.5C3 20.9477 3.44772 20.5 4 20.5C4.55228 20.5 5 20.9477 5 21.5V22C5 22.1459 5.06252 22.2773 5.16224 22.3687C5.65028 20.7105 7.18378 19.5 9 19.5C10.8638 19.5 12.4299 20.7748 12.874 22.5H17V16.5V7.5H5V8.5C5 9.05228 4.55228 9.5 4 9.5C3.44772 9.5 3 9.05228 3 8.5V7.5ZM19 15.5V12.5H24.4338L26.2338 15.5H19ZM19 17.5H27V22.5H25.874C25.4299 20.7748 23.8638 19.5 22 19.5C20.8053 19.5 19.7329 20.0238 19 20.8542V17.5ZM22 21.5C23.1046 21.5 24 22.3954 24 23.5C24 24.6046 23.1046 25.5 22 25.5C20.8954 25.5 20 24.6046 20 23.5C20 22.3954 20.8954 21.5 22 21.5ZM7 23.5C7 24.6046 7.89543 25.5 9 25.5C10.1046 25.5 11 24.6046 11 23.5C11 22.3954 10.1046 21.5 9 21.5C7.89543 21.5 7 22.3954 7 23.5ZM2 10.5C1.44772 10.5 1 10.9477 1 11.5C1 12.0523 1.44772 12.5 2 12.5H7C7.55228 12.5 8 12.0523 8 11.5C8 10.9477 7.55228 10.5 7 10.5H2ZM3 13.5C2.44772 13.5 2 13.9477 2 14.5C2 15.0523 2.44772 15.5 3 15.5H7C7.55228 15.5 8 15.0523 8 14.5C8 13.9477 7.55228 13.5 7 13.5H3ZM3 17.5C3 16.9477 3.44772 16.5 4 16.5H7C7.55229 16.5 8 16.9477 8 17.5C8 18.0523 7.55229 18.5 7 18.5H4C3.44772 18.5 3 18.0523 3 17.5Z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 33"
                                                fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"
                                                class="w-full h-full">
                                                <path
                                                    d="M18.4449 14.2024C19.4296 12.8623 20 11.5761 20 10.5C20 8.29086 18.2091 6.5 16 6.5C13.7909 6.5 12 8.29086 12 10.5C12 11.5761 12.5704 12.8623 13.5551 14.2024C14.3393 15.2698 15.2651 16.2081 16 16.8815C16.7349 16.2081 17.6607 15.2698 18.4449 14.2024ZM16.8669 18.7881C18.5289 17.3455 22 13.9227 22 10.5C22 7.18629 19.3137 4.5 16 4.5C12.6863 4.5 10 7.18629 10 10.5C10 13.9227 13.4712 17.3455 15.1331 18.7881C15.6365 19.2251 16.3635 19.2251 16.8669 18.7881ZM5 11.5H8.27078C8.45724 12.202 8.72804 12.8724 9.04509 13.5H5V26.5H10.5V22C10.5 21.4477 10.9477 21 11.5 21H20.5C21.0523 21 21.5 21.4477 21.5 22V26.5H27V13.5H22.9549C23.272 12.8724 23.5428 12.202 23.7292 11.5H27C28.1046 11.5 29 12.3954 29 13.5V26.5C29.5523 26.5 30 26.9477 30 27.5C30 28.0523 29.5523 28.5 29 28.5H3C2.44772 28.5 2 28.0523 2 27.5C2 26.9477 2.44772 26.5 3 26.5V13.5C3 12.3954 3.89543 11.5 5 11.5ZM19.5 23V26.5H12.5V23H19.5ZM17 10.5C17 11.0523 16.5523 11.5 16 11.5C15.4477 11.5 15 11.0523 15 10.5C15 9.94772 15.4477 9.5 16 9.5C16.5523 9.5 17 9.94772 17 10.5ZM19 10.5C19 12.1569 17.6569 13.5 16 13.5C14.3431 13.5 13 12.1569 13 10.5C13 8.84315 14.3431 7.5 16 7.5C17.6569 7.5 19 8.84315 19 10.5Z" />
                                            </svg>
                                        @endif
                                    </span>
                                    <div class="w-full flex-1">
                                        <p class="text-left font-semibold">{{ $item->name }}</p>
                                        @if ($item->descripcion)
                                            <p class="text-[9px] mt-1 leading-3 text-left {{-- [text-align-last:center] --}}">
                                                {{ $item->descripcion }}
                                            </p>
                                        @endif
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    @else
                        <h1 class="text-[10px] p-3 text-colorerror">NO EXISTES TIPOS DE ENVÍO</h1>
                    @endif
                </div>
            @endif
            <x-jet-input-error class="px-3 pb-1" for="shipmenttype_id" />
        </div>

        @if (count($order) == 0)
            <div wire:key="section_direcciones" class="w-full mt-2" style="display:none;" x-show="showadress" x-cloak>
                <h1 class="text-lg font-semibold text-primary">
                    DIRECCIÓN DE ENVÍO</h1>

                @if (count($direccions) > 0)
                    <div class="w-full flex flex-col gap-1 mb-3">
                        @foreach ($direccions as $item)
                            <div
                                class="w-full flex gap-3 justify-between rounded-lg p-3 border {{ $item->isDefault() ? 'shadow-md border-next-500 shadow-next-300' : 'border-borderminicard' }} ">
                                <div class="w-full flex-1 flex gap-1 items-start">
                                    @if ($item->isDefault())
                                        <x-icon-default class="!inline-block" />
                                    @endif
                                    <div class="w-full text-colorlabel">
                                        <p class="text-[10px]">
                                            {{ $item->ubigeo->region }},
                                            {{ $item->ubigeo->provincia }},
                                            {{ $item->ubigeo->distrito }}
                                        </p>
                                        <p class="text-[10px]">{{ $item->name }}</p>
                                        @if ($item->referencia)
                                            <p class="text-[10px]">REFERENCIA : {{ $item->referencia }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex gap-1 items-end justify-end">
                                    @if (!$item->isDefault())
                                        <button type="button" wire:click="savedefaultdireccion({{ $item->id }})"
                                            wire:loading.attr="disabled"
                                            class="inline-block group relative font-semibold text-sm bg-transparent text-yellow-500 p-1 rounded-md hover:bg-yellow-500 focus:bg-yellow-500 hover:ring-2 hover:ring-yellow-300 focus:ring-2 focus:ring-yellow-300 hover:text-white focus:text-white disabled:opacity-25 transition ease-in duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-auto"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path
                                                    d="M13.7276 3.44418L15.4874 6.99288C15.7274 7.48687 16.3673 7.9607 16.9073 8.05143L20.0969 8.58575C22.1367 8.92853 22.6167 10.4206 21.1468 11.8925L18.6671 14.3927C18.2471 14.8161 18.0172 15.6327 18.1471 16.2175L18.8571 19.3125C19.417 21.7623 18.1271 22.71 15.9774 21.4296L12.9877 19.6452C12.4478 19.3226 11.5579 19.3226 11.0079 19.6452L8.01827 21.4296C5.8785 22.71 4.57865 21.7522 5.13859 19.3125L5.84851 16.2175C5.97849 15.6327 5.74852 14.8161 5.32856 14.3927L2.84884 11.8925C1.389 10.4206 1.85895 8.92853 3.89872 8.58575L7.08837 8.05143C7.61831 7.9607 8.25824 7.48687 8.49821 6.99288L10.258 3.44418C11.2179 1.51861 12.7777 1.51861 13.7276 3.44418Z" />
                                            </svg>
                                        </button>
                                    @endif
                                    <x-button-delete wire:loading.attr="disabled"
                                        wire:click="deletedireccion({{ $item->id }})" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <h1 class="text-[10px] p-3 py-2 text-red-600">
                        NO EXISTEN DIRECCIONES REGISTRADAS</h1>
                @endif

                <template x-if="showaddadress == false">
                    <div class="w-full justify-end">
                        <x-button @click="showaddadress=!showaddadress" class="ml-auto"
                            wire:loading.attr="disabled">AGREGAR DIRECCIÓN</x-button>
                    </div>
                </template>
                <x-jet-input-error class="px-3 pb-1" for="direccionenvio_id" />

                <div class="flex flex-col gap-2 p-1 xs:p-3" style="display: none;" x-show="showaddadress" x-cloak>
                    <div class="w-full">
                        <div class="relative" x-data="{ lugar_id: @entangle('lugar_id').defer }" x-init="select2Ubigeo" id="parentlugar_id">
                            <x-select class="block w-full" x-ref="select" id="lugar_id"
                                data-minimum-results-for-search="3" data-placeholder="Seleccionar">
                                <x-slot name="options">
                                    @if (count($ubigeos))
                                        @foreach ($ubigeos as $item)
                                            <option value="{{ $item->id }}">{{ $item->region }} /
                                                {{ $item->provincia }}
                                                / {{ $item->distrito }}</option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="lugar_id" />
                    </div>
                    <div class="w-full">
                        <x-input class="block w-full" wire:model.defer="direccion"
                            placeholder="Dirección de envío" />
                        <x-jet-input-error for="direccion" />
                    </div>
                    <div class="w-full">
                        <x-input class="block w-full" wire:model.defer="referencia" placeholder="Referencia" />
                        <x-jet-input-error for="referencia" />
                    </div>
                    <div class="w-full flex justify-end flex-wrap gap-2">
                        <x-button-secondary class="!rounded-lg" wire:loading.attr="disabled"
                            @click="showaddadress=false">CANCELAR</x-button-secondary>
                        <x-button class="!rounded-lg" wire:loading.attr="disabled"
                            wire:click="savedireccion">REGISTRAR</x-button>
                    </div>
                </div>
            </div>
        @endif

        @if (count($order) == 0)
            <div wire:key="section_tiendas" class="w-full mt-2" style="display:none;" x-show="showlocales">
                <h1 class="text-lg font-semibold text-primary">
                    TIENDA DE ENTREGA</h1>

                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-1 items-end gap-2">
                    <div class="w-full sm:col-span-2 lg:col-span-1">
                        <div class="relative" id="parentlocal_id">
                            <x-select class="block w-full" x-model="local_id" x-init="select2Local"
                                x-ref="selectlocal" id="local_id" data-minimum-results-for-search="3"
                                data-placeholder="SELECCIONAR LOCAL DE ENTREGA...">
                                <x-slot name="options">
                                    @if (count($locals) > 0)
                                        @foreach ($locals as $item)
                                            <option value="{{ $item->id }}"
                                                title="{{ $item->direccion }} - {{ $item->ubigeo->distrito }}, {{ $item->ubigeo->provincia }}, {{ $item->ubigeo->region }} ">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="local_id" />
                    </div>
                    <div class="w-full">
                        <x-label value="Fecha de reclamo :" />
                        <x-input class="block w-full" x-model="daterecojo" type="date" />
                        <x-jet-input-error for="daterecojo" />
                    </div>
                </div>
            </div>
        @endif

        <div class="w-full mt-2">
            <h1 class="text-lg font-semibold text-primary">
                QUIÉN RECIBE EL PEDIDO</h1>
            <div class="flex flex-col gap-1">
                @if (count($order) > 0)
                    <div class="w-full">
                        <p class="text-sm text-colorlabel font-semibold">
                            {{ $order['receiver_info']['name'] }}</p>
                        <p class="text-[10px] text-colorsubtitleform font-medium">
                            {{ $order['receiver_info']['document'] }}
                        </p>
                        <p class="text-[10px] text-colorsubtitleform font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block text-green-600 w-4 h-4 p-0.5"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                            </svg>
                            {{ formatTelefono($order['receiver_info']['telefono']) }}
                        </p>
                    </div>
                @else
                    <div class="w-full flex gap-1">
                        <x-input-radio class="py-2" for="{{ \Modules\Marketplace\Entities\Order::EQUAL_RECEIVER }}"
                            text="YO MISMO">
                            <input x-model="receiver" class="sr-only peer peer-disabled:opacity-25" type="radio"
                                id="{{ \Modules\Marketplace\Entities\Order::EQUAL_RECEIVER }}" name="receiver"
                                value="{{ \Modules\Marketplace\Entities\Order::EQUAL_RECEIVER }}" />
                        </x-input-radio>
                        <x-input-radio class="py-2" for="{{ \Modules\Marketplace\Entities\Order::OTHER_RECEIVER }}"
                            text="OTRA PERSONA">
                            <input x-model="receiver" class="sr-only peer peer-disabled:opacity-25" type="radio"
                                id="{{ \Modules\Marketplace\Entities\Order::OTHER_RECEIVER }}" name="receiver"
                                value="{{ \Modules\Marketplace\Entities\Order::OTHER_RECEIVER }}" />
                        </x-input-radio>
                    </div>
                    <x-jet-input-error for="receiver" />

                    <div class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-1 gap-2">
                        <div class="w-full">
                            <x-label value="Documento :" />
                            <x-input class="block w-full input-number-none" type="number"
                                x-model="receiver_info.document" onkeypress="return validarNumero(event, 11)" />
                            <x-jet-input-error for="receiver_info.document" />
                        </div>
                        <div class="w-full">
                            <x-label value="Nombre completo :" />
                            <x-input class="block w-full" x-model="receiver_info.name"
                                placeholder="Nombres del receptor..." />
                            <x-jet-input-error for="receiver_info.name" />
                        </div>
                        <div class="w-full">
                            <x-label value="Teléfono :" />
                            <x-input class="block w-full input-number-none" x-model="receiver_info.telefono"
                                type="number" onkeypress="return validarNumero(event, 9)" />
                            <x-jet-input-error for="receiver_info.telefono" />
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if (Cart::instance('shopping')->count() > 0)
            <div class="w-full flex flex-col gap-2 mt-2">
                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="w-full mt-2">
                        <x-jet-label for="terms">
                            <div class="flex justify-start items-center">
                                <x-input x-model="terms" x-init="$el.checked = terms" type="checkbox" name="terms"
                                    id="terms" class="!rounded-none" wire:model.defer="terms" />

                                <div class="flex-1 w-full ml-2 text-colorsubtitleform leading-3">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' =>
                                            '<a target="_blank" href="' .
                                            route('terms.show') .
                                            '" class="underline text-sm text-orange-600 hover:text-orange-900">' .
                                            __('Terms of Service') .
                                            '</a>',
                                        'privacy_policy' =>
                                            '<a target="_blank" href="' .
                                            route('policy.show') .
                                            '" class="underline text-sm text-orange-600 hover:text-orange-900">' .
                                            __('Privacy Policy') .
                                            '</a>',
                                    ]) !!}
                                </div>
                            </div>
                        </x-jet-label>
                        <x-jet-input-error for="terms" />
                    </div>
                @endif
                {{-- <x-jet-input-error for="g_recaptcha_response" /> --}}


                @if (session('message'))
                    <div class="w-full" x-data="{ open: true }" x-show="open" x-cloack style="display: none;"
                        x-transition>
                        <div
                            class="flex gap-2 items-center p-3 my-3 text-colorerror border rounded-xl border-colorerror">
                            <p class="w-full flex-1 text-xs font-medium uppercase">
                                {{ session('message')->title }}
                            </p>
                            <button type="button" @click="open = false"
                                class="ms-auto -mx-1.5 -my-1.5 flex-shrink-0 text-colorerror rounded-lg hover:ring-2 focus:ring-2 hover:ring-red-300 focus:ring-red-300 p-1.5 hover:bg-colorerror hover:text-white focus:bg-colorerror focus:text-white inline-flex items-center justify-center h-8 w-8 transition-all ease-in-out duration-150"
                                data-dismiss-target="#alert-border-2" aria-label="Close">
                                <span class="sr-only">Dismiss</span>
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                {{-- <template x-if="processpay == false"> --}}
                <div x-show="processpay == false" wire:key="form_buttons"
                    class="my-2 w-full grid grid-cols-1 xs:grid-cols-2 lg:grid-cols-1 gap-2">
                    @if (count($order) > 0)
                        <x-button x-show="processpay== false" wire:key="buttonpay" type="submit"
                            x-bind:disabled="!terms" wire:loading.attr="disabled"
                            class="block w-full p-3 text-sm">
                            PAGAR</x-button>
                        <button wire:key="buttoncancel" type="button" @click="edit"
                            class="block w-full p-2 lg:p-3 lg:py-4 text-xs font-semibol tracking-widest text-white bg-orange-500 rounded-xl hover:ring-2 hover:ring-orange-300 hover:bg-orange-700 transition-all ease-in-out duration-300">
                            EDITAR</button>
                    @else
                        <x-button x-show="processpay== false" wire:key="buttonpay" type="submit"
                            x-bind:disabled="!terms" wire:loading.attr="disabled"
                            class="block w-full p-3 text-sm">
                            PAGAR</x-button>
                    @endif
                </div>
                {{-- </template> --}}

                {{-- <div class="p-5 opacity-80 w-full flex justify-center items-center" x-show="processpay" x-cloak
                    style="display: none;">
                    <span class="text-[10px] font-medium tracking-widest text-colorsubtitleform">
                        CARGANDO VENTANA CHECKOUT DE PAGO ...</span>
                </div> --}}

                <div {{-- x-show="processpay" x-cloak style="display: none;" --}}
                    class="w-full z-[199] h-screen fixed top-0 left-0 flex justify-center items-center bg-neutral-800 bg-opacity-85">
                    <div
                        class="w-full py-2 sm:py-5 max-w-xs flex flex-col gap-3 bg-fondominicard rounded-xl shadow shadow-shadowminicard">

                        <div class="skeleton-box w-full px-3 sm:px-5 pb-3">
                            <div
                                class="w-full flex flex-col gap-1 justify-center items-center bg-fondospancardproduct bg-opacity-80 h-12 sm:h-16 p-2 sm:p-4 rounded-lg border border-borderminicard">
                                <span class="bg-neutral-300 block h-1.5 w-full max-w-[30%] rounded-lg"></span>
                                <span
                                    class="bg-neutral-400 block h-1 w-full max-w-[35%] bg-opacity-70 rounded-lg"></span>
                                <span
                                    class="bg-neutral-400 block h-1 w-full max-w-[40%] bg-opacity-70 rounded-lg"></span>
                            </div>
                        </div>

                        <div class="skeleton-box w-ful grid grid-cols-1 gap-3 px-3 sm:px-5 py-0 sm:py-3">
                            <div class="w-full flex flex-col gap-2">
                                <span class="bg-fondospancardproduct block h-1 sm:h-1.5 w-full max-w-[40%] rounded-lg"></span>
                                <div
                                    class="w-full bg-fondospancardproduct bg-opacity-80 p-3.5 sm:p-5 rounded-lg border border-borderminicard">
                                    <span class="bg-neutral-300 block h-1 w-full max-w-[50%] rounded-lg"></span>
                                </div>
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <span class="bg-fondospancardproduct block h-1 sm:h-1.5 w-full max-w-[40%] rounded-lg"></span>
                                <div
                                    class="w-full bg-fondospancardproduct bg-opacity-80 p-3.5 sm:p-5 rounded-lg border border-borderminicard">
                                    <span class="bg-neutral-300 block h-1 w-full max-w-[50%] rounded-lg"></span>
                                </div>
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <span class="bg-fondospancardproduct block h-1 sm:h-1.5 w-full max-w-[40%] rounded-lg"></span>
                                <div
                                    class="w-full bg-fondospancardproduct bg-opacity-80 p-3.5 sm:p-5 rounded-lg border border-borderminicard">
                                    <span class="bg-neutral-300 block h-1 w-full max-w-[50%] rounded-lg"></span>
                                </div>
                            </div>
                        </div>

                        <div class="skeleton-box w-ful grid grid-cols-2 gap-3 px-3 sm:px-5 py-3">
                            <div class="w-full flex flex-col gap-2">
                                <span class="bg-fondospancardproduct block h-1 sm:h-1.5 w-full max-w-[40%] rounded-lg"></span>
                                <div
                                    class="w-full bg-fondospancardproduct bg-opacity-80 p-3.5 sm:p-4 rounded-lg border border-borderminicard">
                                    <span class="bg-neutral-300 block h-1 w-full max-w-[50%] rounded-lg"></span>
                                </div>
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <span class="bg-fondospancardproduct block h-1 sm:h-1.5 w-full max-w-[40%] rounded-lg"></span>
                                <div
                                    class="w-full bg-fondospancardproduct bg-opacity-80 p-3.5 sm:p-4 rounded-lg border border-borderminicard">
                                    <span class="bg-neutral-300 block h-1 w-full max-w-[50%] rounded-lg"></span>
                                </div>
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <span class="bg-fondospancardproduct block h-1 sm:h-1.5 w-full max-w-[40%] rounded-lg"></span>
                                <div
                                    class="w-full bg-fondospancardproduct bg-opacity-80 p-3.5 sm:p-4 rounded-lg border border-borderminicard">
                                    <span class="bg-neutral-300 block h-1 w-full max-w-[50%] rounded-lg"></span>
                                </div>
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <span class="bg-fondospancardproduct block h-1 sm:h-1.5 w-full max-w-[40%] rounded-lg"></span>
                                <div
                                    class="w-full bg-fondospancardproduct bg-opacity-80 p-3.5 sm:p-4 rounded-lg border border-borderminicard">
                                    <span class="bg-neutral-300 block h-1 w-full max-w-[50%] rounded-lg"></span>
                                </div>
                            </div>
                        </div>

                        <div class="skeleton-box w-full flex flex-col gap-2 px-5 pb-3">
                            <span class="bg-fondospancardproduct block h-1 sm:h-1.5 w-full max-w-[40%] rounded-lg"></span>
                            <div class="w-full grid grid-cols-3 gap-3">
                                <div class="w-full flex flex-col gap-2">
                                    <div
                                        class="w-full bg-fondospancardproduct bg-opacity-80 p-3.5 sm:p-4 rounded-lg border border-borderminicard">
                                        <span class="bg-neutral-300 block h-0.5 sm:h-1 w-full max-w-[50%] rounded-lg"></span>
                                    </div>
                                </div>
                                <div class="w-full flex flex-col gap-2">
                                    <div
                                        class="w-full bg-fondospancardproduct bg-opacity-80 p-3.5 sm:p-4 rounded-lg border border-borderminicard">
                                        <span class="bg-neutral-300 block h-0.5 sm:h-1 w-full max-w-[50%] rounded-lg"></span>
                                    </div>
                                </div>
                                <div class="w-full flex flex-col gap-2">
                                    <div
                                        class="w-full bg-fondospancardproduct bg-opacity-80 p-3.5 sm:p-4 rounded-lg border border-borderminicard">
                                        <span class="bg-neutral-300 block h-0.5 sm:h-1 w-full max-w-[50%] rounded-lg"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="skeleton-box w-full px-3 sm:px-5 pt-3 sm:pt-5">
                            <div class="w-full bg-next-900 rounded-lg py-5">
                                <div class="w-full mx-auto max-w-[30%] bg-white h-1 sm:h-1.5 rounded-lg"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </form>

    @push('scripts')
        <script type="text/javascript" src="{{ config('services.niubiz.url_js') }}"></script>
    @endpush

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('shipment', () => ({
                error: null,
                loading: false,
                processpay: false,
                showadress: false,
                showlocales: false,
                terms: false,
                showaddadress: @entangle('showaddadress').defer,
                receiver: @entangle('receiver').defer,
                receiver_info: @entangle('receiver_info').defer,
                shipmenttype_id: @entangle('shipmenttype_id').defer,
                local_id: @entangle('local_id').defer,
                daterecojo: @entangle('daterecojo').defer,
                recaptcha: null,

                seleccionarenvio(shipmenttype) {
                    if (shipmenttype.isenvio == '1') {
                        this.showadress = true;
                        this.showlocales = false;
                    } else {
                        this.showlocales = true;
                        this.showadress = false;
                    }
                },
                init() {
                    this.$watch("receiver", (value) => {
                        if (value == '0') {
                            this.receiver_info.document = '{{ auth()->user()->document }}';
                            this.receiver_info.name = '{{ auth()->user()->name }}';
                            this.receiver_info.telefono = '{{ $phoneuser->phone ?? null }}';
                        } else {
                            this.receiver_info.document = '';
                            this.receiver_info.name = '';
                            this.receiver_info.telefono = '';
                        }
                    });

                    this.$watch("shipmenttype_id", (value) => {
                        // if (value) {
                        this.local_id = null;
                        this.daterecojo = null;
                        // }
                    });

                    console.log(this.terms);
                    // window.addEventListener('popstate', () => {
                    //     isChecked = false;
                    // })
                    this.$watch('terms', (value) => {
                        console.log(value);
                    })
                },
                save() {
                    this.loading = true;
                    this.processpay = true;
                    this.$wire.validateorder().then(async (data) => {
                        if (data) {
                            const result = data;
                            result.g_recaptcha_response = await obtenerRecaptchaToken();
                            const config = {
                                ...await getConfigCheckout(result),
                                complete: function(params) {
                                    console.log(params);
                                    alert(JSON.stringify(params));
                                }
                            }
                            VisanetCheckout.configure(config);
                            VisanetCheckout.open();
                            this.processpay = false;
                            this.loading = false;
                        } else {
                            this.loading = false;
                            this.processpay = false;
                        }
                    })
                },
                edit() {
                    this.open = true;
                    this.$wire.order = [];
                    this.$wire.$refresh();
                    this.open = false;
                }
            }))
        })

        async function obtenerRecaptchaToken() {
            return new Promise((resolve, reject) => {
                grecaptcha.ready(async () => {
                    try {
                        const token = await grecaptcha.execute(
                            `{{ config('services.recaptcha_v3.key_web') }}`, {
                                action: 'submit'
                            }
                        );
                        resolve(token);
                    } catch (error) {
                        reject(error);
                    }
                });
            });
        }

        async function getConfigCheckout(datos) {
            try {
                const response = await fetch(`{{ route('orders.niubiz.config') }}`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        datos : datos
                    })
                });

                const data = await response.json();
                if (data.errors) {
                    Object.values(data.errors).forEach(error => {
                        swal.fire({
                            title: error[0].toUpperCase(),
                            text: null,
                            icon: 'info',
                            confirmButtonColor: '#0FB9B9',
                            confirmButtonText: 'Cerrar',
                        })
                    });
                    return null;
                } else if (data.message) {
                    swal.fire({
                        title: data.exception + ', error al leer datos.',
                        text: null,
                        icon: 'info',
                        confirmButtonColor: '#0FB9B9',
                        confirmButtonText: 'Cerrar',
                    })
                } else {
                    // console.log(data);
                    return data;
                }
            } catch (error) {
                console.log(error);
                return null;
            }

            // await fetch(`{{ route('orders.niubiz.config') }}`, {
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/json',
            //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
            //     },
            //     body: JSON.stringify({})
            // }).then(response => response.json()).then(data => {
            //     if (data.error) {
            //         this.error = data.error;
            //     } else {
            //         this.loading = false;
            //         console.log(data);
            //         return data;
            //     }
            // }).catch(() => {
            //     this.error = 'There was an error processing your request.';
            // });
        }

        function select2Ubigeo() {
            this.selectU = $(this.$refs.select).select2();
            this.selectU.val(this.lugar_id).trigger("change");
            this.selectU.on("select2:select", (event) => {
                this.lugar_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("lugar_id", (value) => {
                this.selectU.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectU.select2().val(this.lugar_id).trigger('change');
            });
        }

        function select2Local() {
            this.selectSuc = $(this.$refs.selectlocal).select2({
                templateResult: formatOption
            });
            this.selectSuc.val(this.local_id).trigger("change");
            this.selectSuc.on("select2:select", (event) => {
                this.local_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("local_id", (value) => {
                this.selectSuc.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectSuc.select2({
                    templateResult: formatOption
                }).val(this.local_id).trigger('change');
            });
        }

        function formatOption(option) {
            var $option = $(
                '<strong>' + option.text + '</strong><p class="select2-subtitle-option text-[10px]">' + option
                .title +
                '</p>'
            );
            return $option;
        };
    </script>
</div>
