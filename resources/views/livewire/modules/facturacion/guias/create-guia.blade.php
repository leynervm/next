<div x-data="createcotizacion">
    <div wire:loading.flex class="loading-overlay hidden fixed" wire:key="loadincreateguia">
        <x-loading-next />
    </div>

    <form wire:submit.prevent="save" class="w-full flex flex-col gap-8">
        <x-form-card titulo="RESUMEN GUÍA REMISIÓN"
            subtitulo="Complete todos los campos para registrar una nueva guía de remisión.">
            <div class="w-full flex flex-col gap-2">
                <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-2">
                    <div class="w-full xs:col-span-2 sm:col-span-1">
                        <x-label value="Tipo guía :" />
                        <div class="relative" id="parenttipoguia_id" x-init="select2Typecomprobante">
                            <x-select class="block w-full uppercase" id="tipoguia_id" x-ref="typecomprobante"
                                {{-- @change="resetMotivotraslado($event.target)" --}}>
                                <x-slot name="options">
                                    @if (count($seriecomprobantes) > 0)
                                        @foreach ($seriecomprobantes as $item)
                                            <option value="{{ $item->id }}"
                                                data-sendsunat="{{ $item->typecomprobante->sendsunat }}">
                                                [{{ $item->serie }}] - {{ $item->typecomprobante->descripcion }}
                                            </option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="seriecomprobante_id" />
                        <x-jet-input-error for="seriecomprobante.id" />
                    </div>

                    <div class="w-full xs:col-span-2 sm:col-span-2 xl:col-span-1">
                        <x-label value="Motivo traslado :" />
                        <div class="relative" id ="parentmotivotraslado_id" x-init="select2Motivo">
                            <x-select class="block w-full uppercase" id="motivotraslado_id" x-ref="motivotraslado">
                                <x-slot name="options">
                                    @if (count($motivotraslados) > 0)
                                        @foreach ($motivotraslados as $item)
                                            <option value="{{ $item->id }}" data-code="{{ $item->code }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="motivotraslado_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Modalidad transporte :" />
                        <div class="relative" id="parentmodalidadtransporte_id" x-init="select2Modalidad">
                            <x-select class="block w-full uppercase" id="modalidadtransporte_id" x-ref="modalidad">
                                <x-slot name="options">
                                    @if (count($modalidadtransportes) > 0)
                                        @foreach ($modalidadtransportes as $item)
                                            <option value="{{ $item->id }}" data-code="{{ $item->code }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="modalidadtransporte_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Fecha traslado :" />
                        <x-input class="block w-full" wire:model.defer="datetraslado" type="date" />
                        <x-jet-input-error for="datetraslado" />
                    </div>

                    <div class="w-full">
                        <x-label value="Peso bruto total (KILOGRAMO) :" />
                        <x-input class="block w-full input-number-none" type="number" wire:model.defer="peso"
                            min="0" step="0.01" onkeypress="return validarNumero(event, 11)" type="number" />
                        <x-jet-input-error for="peso" />
                    </div>

                    <div class="w-full animate__animated animate__fadeInDown" x-show="loadingpackages">
                        <x-label value="Bultos / Paquetes :" />
                        <x-input class="block w-full" wire:model.defer="packages" min="0" step="1"
                            type="number" />
                        <x-jet-input-error for="packages" />
                    </div>

                    <div class="w-full">
                        <x-label value="Descripción :" />
                        <x-input class="block w-full" wire:model.defer="note"
                            placeholder="Detalle de guía (Opcional)..." />
                        <x-jet-input-error for="note" />
                    </div>

                    @if ($sincronizecpe)
                        <div class="w-full">
                            <x-label value="Comprobante referencia emitido :" />
                            <div class="w-full inline-flex relative">
                                <x-disabled-text :text="$referencia" class="w-full block" />
                                <x-button-close-modal class="btn-desvincular" wire:click="desvincularcpe"
                                    wire:loading.attr="disabled" />
                            </div>
                            <x-jet-input-error for="referencia" />
                        </div>
                    @else
                        <div class="w-full" x-show="codemotivotraslado == '01' || codemotivotraslado == '03'">
                            <x-label value="Comprobante referencia emitido :" />
                            <div class="w-full inline-flex gap-1">
                                <x-input class="block w-full flex-1" wire:model.defer="referencia"
                                    wire:keydown.enter.prevent="searchreferencia" minlength="6" maxlength="13" />
                                <x-button-add class="px-2" wire:click="searchreferencia" wire:loading.attr="disable">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8" />
                                        <path d="m21 21-4.3-4.3" />
                                    </svg>
                                </x-button-add>
                            </div>
                            <x-jet-input-error for="referencia" />
                        </div>
                    @endif

                    <div class="w-full animate__animated animate__fadeInDown" x-show="vehiculosml">
                        <x-label value="Placa vehículo (Opcional) :" />
                        <x-input class="block w-full" wire:model.defer="placavehiculo"
                            placeholder="Placa del vehículo de transporte..." />
                        <x-jet-input-error for="placavehiculo" />
                    </div>
                </div>

                <div class="w-full flex flex-wrap gap-1 items-start">
                    <div class="w-full block">
                        <x-label-check for="vehiculosml" class="uppercase">
                            <x-input wire:model.defer="vehiculosml" name="vehiculosml" type="checkbox"
                                id="vehiculosml" @change="toggle" />
                            TRASLADO EN VEHÍCULOS DE CATEGORÍA M1 O L
                        </x-label-check>
                    </div>

                    <div class="w-full block">
                        <x-label-check for="vehiculovacio" class="uppercase">
                            <x-input wire:model.defer="vehiculovacio" name="vehiculovacio" type="checkbox"
                                id="vehiculovacio" />
                            RETORNO VEHÍCULO VACÍO
                        </x-label-check>
                    </div>
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="DESTINATARIO" x-show="loadingdestinatario"
            class="animate__animated animate__fadeInDown">
            <div class="w-full bg-body p-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-1">
                <div class="w-full">
                    <x-label value="DNI / RUC :" />
                    <div class="w-full inline-flex gap-1">
                        <x-input class="block w-full flex-1 input-number-none" type="number"
                            x-model="documentdestinatario" wire:model.defer="documentdestinatario"
                            wire:keydown.enter.prevent="searchclient('documentdestinatario','namedestinatario')"
                            onkeypress="return validarNumero(event, 11)" />
                        <x-button-add class="px-2"
                            wire:click="searchclient('documentdestinatario','namedestinatario')"
                            wire:loading.attr="disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </x-button-add>
                    </div>
                    <x-jet-input-error for="documentdestinatario" />
                </div>

                <div class="w-full lg:col-span-2">
                    <x-label value="Nombres / Razón social :" />
                    <x-input class="block w-full" x-model="namedestinatario" wire:model.defer="namedestinatario"
                        placeholder="Nombres / razón social del destinatario" />
                    <x-jet-input-error for="namedestinatario" />
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="COMPRADOR" x-show="loadingcomprador" class="animate__animated animate__fadeInDown">
            <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                <div class="w-full">
                    <x-label value="DNI / RUC :" />
                    <div class="w-full inline-flex gap-1">
                        <x-input class="block w-full flex-1 input-number-none prevent" type="number"
                            x-model="documentcomprador" wire:model.defer="documentcomprador"
                            wire:keydown.enter.prevent="searchclient('documentcomprador','namecomprador')"
                            maxlength="11" onkeypress="return validarNumero(event, 11)" />
                        <x-button-add class="px-2" wire:click="searchclient('documentcomprador','namecomprador')"
                            wire:loading.attr="disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </x-button-add>
                    </div>
                    <x-jet-input-error for="documentcomprador" />
                </div>

                <div class="w-full lg:col-span-2">
                    <x-label value="Nombres / Razón social :" />
                    <x-input class="block w-full" x-model="namecomprador" wire:model.defer="namecomprador"
                        placeholder="Nombres / razón social del comprador" />
                    <x-jet-input-error for="namecomprador" />
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="PROVEEDOR" x-show="loadingproveedor" class="animate__animated animate__fadeInDown">
            <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                <div class="w-full">
                    <x-label value="RUC :" />
                    <div class="w-full inline-flex gap-1">
                        <x-input class="block w-full flex-1 input-number-none"
                            onkeypress="return validarNumero(event, 11)" x-model="rucproveedor" type="number"
                            wire:model.defer="rucproveedor"
                            wire:keydown.enter.prevent="searchclient('rucproveedor','nameproveedor')" />
                        <x-button-add class="px-2" wire:click="searchclient('rucproveedor','nameproveedor')"
                            wire:loading.attr="disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </x-button-add>
                    </div>
                    <x-jet-input-error for="rucproveedor" />
                </div>

                <div class="w-full lg:col-span-2">
                    <x-label value="Razón social :" />
                    <x-input class="block w-full" x-model="nameproveedor" wire:model.defer="nameproveedor"
                        placeholder="Razón social del proveedor" />
                    <x-jet-input-error for="nameproveedor" />
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="TRANSPORTISTA" x-show="loadingpublic" class="animate__animated animate__fadeInDown">
            <div class="w-full grid grid-cols-1 xs:grid-cols-2 xl:grid-cols-3 gap-2">
                <div class="w-full xs:col-span-2 lg:col-span-1">
                    <x-label value="RUC :" />
                    <div class="w-full inline-flex gap-1">
                        <x-input class="block w-full flex-1 prevent input-number-none"
                            onkeypress="return validarNumero(event, 11)" wire:model.defer="ructransport"
                            type="number" wire:keydown.enter.prevent="searchclient('ructransport','nametransport')"
                            minlength="0" maxlength="11" />
                        <x-button-add class="px-2" wire:click="searchclient('ructransport','nametransport')"
                            wire:loading.attr="disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </x-button-add>
                    </div>
                    <x-jet-input-error for="ructransport" />
                </div>

                <div class="w-full xs:col-span-2 lg:col-span-2">
                    <x-label value="Razón social :" />
                    <x-input class="block w-full" wire:model.defer="nametransport"
                        placeholder="Razón social del transportista" />
                    <x-jet-input-error for="nametransport" />
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="LUGAR DE EMISIÓN">
            <div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div class="w-full">
                    <x-label value="Lugar emisión :" />
                    <div class="relative" id="parentuborig" x-init="SelectUbigeoOrigen" wire:ignore>
                        <x-select class="block w-full" id="uborig" x-ref="selectubigeoorigen"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @if (count($ubigeos))
                                    @foreach ($ubigeos as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->region }} / {{ $item->provincia }} / {{ $item->distrito }}
                                            / {{ $item->ubigeo_reniec }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="ubigeoorigen_id" />
                </div>

                <div class="w-full">
                    <x-label value="Direccion origen :" />
                    <x-input class="block w-full" wire:model.defer="direccionorigen"
                        placeholder="Dirección del punto de partida..." />
                    <x-jet-input-error for="direccionorigen" />
                </div>

                <div class="w-full" x-show="codemotivotraslado == '04'">
                    <x-label value="Codigo anexo :" />
                    <x-input class="block w-full" wire:model.defer="anexoorigen"
                        placeholder="Anexo del punto de partida..." />
                    <x-jet-input-error for="anexoorigen" />
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="LUGAR DE DESTINO">
            <div class="w-full grid grid-cols-1 lg:grid-cols-2 gap-2">
                <div class="w-full">
                    <x-label value="Lugar destino :" />
                    <div class="relative" id="parentubdest" x-data="{ ubigeodestino_id: @entangle('ubigeodestino_id').defer }" x-init="SelectUbigeoDestino"
                        wire:ignore>
                        <x-select class="block w-full" id="ubdest" x-ref="selectubigeodest"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @if (count($ubigeos))
                                    @foreach ($ubigeos as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->region }} / {{ $item->provincia }} / {{ $item->distrito }}
                                            / {{ $item->ubigeo_reniec }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="ubigeodestino_id" />
                </div>

                <div class="w-full">
                    <x-label value="Direccion destino :" />
                    <x-input class="block w-full" wire:model.defer="direcciondestino"
                        placeholder="Direccion del punto de llegada.." />
                    <x-jet-input-error for="direcciondestino" />
                </div>

                <div class="w-full" x-show="codemotivotraslado == '04'">
                    <x-label value="Codigo anexo :" />
                    <x-input class="block w-full" wire:model.defer="anexodestino"
                        placeholder="Anexo del punto de llegada..." />
                    <x-jet-input-error for="anexodestino" />
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="CONDUCTOR VEHÍCULO" x-show="loadingprivate"
            class="animate__animated animate__fadeInDown">
            <div class="w-full relative rounded flex flex-col gap-2">
                <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
                    <div class="w-full">
                        <x-label value="Documento :" />
                        <div class="w-full inline-flex gap-1">
                            <x-input class="block w-full flex-1 input-number-none" type="number"
                                wire:model.defer="documentdriver"
                                wire:keydown.enter.prevent="searchclient('documentdriver','namedriver')"
                                onkeypress="return validarNumero(event, 11)" />
                            <x-button-add class="px-2" wire:click="searchclient('documentdriver','namedriver')"
                                wire:loading.attr="disabled">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.3-4.3" />
                                </svg>
                            </x-button-add>
                        </div>
                        <x-jet-input-error for="documentdriver" />
                    </div>

                    <div class="w-full">
                        <x-label value="Nombres :" />
                        <x-input class="block w-full" wire:model.defer="namedriver"
                            placeholder="Nombres del conductor del vehículo..." />
                        <x-jet-input-error for="namedriver" />
                    </div>

                    <div class="w-full">
                        <x-label value="Apellidos :" />
                        <x-input class="block w-full" wire:model.defer="lastname"
                            placeholder="Nombres del conductor del vehículo..." />
                        <x-jet-input-error for="lastname" />
                    </div>

                    <div class="w-full">
                        <x-label value="Licencia conducir:" />
                        <x-input class="block w-full" wire:model.defer="licencia"
                            placeholder="Licencia del conductor del vehículo..." />
                        <x-jet-input-error for="licencia" />
                    </div>
                </div>

                {{-- <div class="w-full relative rounded"> --}}
                {{-- @if (count($guia->transportdrivers))
                    <div class="w-full">
                        <x-table>
                            <x-slot name="header">
                                <tr>
                                    <th class="p-2 text-left">DOCUMENTO</th>
                                    <th class="p-2 text-left">NOMBRES</th>
                                    <th class="p-2 text-center">LICENCIA</th>
                                    @if ($guia->codesunat != '0')
                                        <th class="p-2">OPCIONES</th>
                                    @endif
                                </tr>
                            </x-slot>
                            <x-slot name="body">
                                @foreach ($guia->transportdrivers as $item)
                                    <tr class="text-[10px]">
                                        <td class="p-2">
                                            {{ $item->document }}
                                            @if ($item->principal)
                                                <x-span-text text="Principal" class="font-semibold leading-3 ml-1" />
                                            @else
                                                <x-span-text text="Secundario" class="font-semibold leading-3 ml-1" />
                                            @endif
                                        </td>
                                        <td class="p-2">{{ $item->name }} {{ $item->lastname }}</td>
                                        <td class="p-2 text-center">{{ $item->licencia }}</td>
                                        @if ($guia->codesunat != '0')
                                            <td class="text-center align-middle">
                                                <x-button-delete
                                                    wire:click="$emit('guia.confirmDeletedriver',{{ $item }})"
                                                    wire:loading.attr="disabled" />
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-table>
                    </div>
                @endif --}}
                {{-- </div> --}}
            </div>
        </x-form-card>

        <x-form-card titulo="VEHÍCULOS TRANSPORTE" x-show="loadingprivate"
            class="animate__animated animate__fadeInDown">
            <div class="w-full relative grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                <div class="w-full flex flex-col gap-2 relative" x-data="{ loading: false }">
                    <div class="w-full">
                        <x-label value="Placa vehículo :" />
                        <x-input class="block w-full defer" wire:model.defer="placa"
                            wire:keydown.enter.prevent="addplacavehiculo" placeholder="placa vehículo..." />
                        <x-jet-input-error for="placa" />
                        <x-jet-input-error for="placavehiculos" />
                    </div>

                    <div class="w-full mt-3 flex justify-end">
                        <x-button type="button" wire:click="addplacavehiculo">{{ __('AGREGAR') }}</x-button>
                    </div>
                </div>

                <div class="w-full sm:col-span-2 lg:col-span-3 relative">
                    @if (count($placavehiculos))
                        <div class="w-full flex flex-wrap items-start gap-2">
                            @foreach ($placavehiculos as $item)
                                <x-minicard :title="'PLACA: ' . $item['placa']" :content="$item['principal'] == 1 ? 'Principal' : 'Secundario'" size="md">
                                    <x-slot name="buttons">
                                        <x-button-delete wire:click="deleteplacavehiculo('{{ $item['placa'] }}')"
                                            wire:loading.attr="disabled" />
                                    </x-slot>
                                </x-minicard>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </x-form-card>

        <div class="w-full flex justify-end">
            <x-button type="submit" wire:loading.attr="disabled">{{ __('Save') }}</x-button>
        </div>
    </form>

    <div class="w-full flex flex-col">
        <x-jet-input-error for="items" />
    </div>

    <x-form-card titulo="RESUMEN PRODUCTOS" class="mt-3">
        <div class="w-full relative rounded flex flex-col gap-3">
            <div class="w-full flex flex-col gap-2">
                <div class="w-full grid sm:grid-cols-3 xl:grid-cols-5 gap-2">
                    <div class="w-full sm:col-span-2">
                        <x-label value="Seleccionar producto :" />
                        <div class="w-full relative" x-init="select2Producto" id="parentguiaproducto_id">
                            <x-select class="block w-full uppercase" x-ref="selectprod"
                                data-minimum-results-for-search="3" id="guiaproducto_id">
                                <x-slot name="options">
                                    @if (count($productos) > 0)
                                        @foreach ($productos as $item)
                                            <option data-marca="{{ $item->name_marca }}"
                                                data-category="{{ $item->name_category }}"
                                                data-subcategory="{{ $item->name_subcategory }}"
                                                data-image="{{ !empty($item->imagen) ? pathURLProductImage($item->imagen->url) : null }}"
                                                value="{{ $item->id }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="producto_id" />
                    </div>

                    {{-- @if (count($series) > 0 && in_array($mode, ['1', '3']))
                        <div class="w-full">
                            <x-label value="Seleccionar serie :" />
                            <div class="w-full relative" id="parentguiaserie_id" x-init="select2Serie">
                                <x-select class="block w-full" x-ref="selectserie"
                                    data-minimum-results-for-search="3" id="guiaserie_id">
                                    <x-slot name="options">
                                        @foreach ($series as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->serie }}
                                            </option>
                                        @endforeach
                                    </x-slot>
                                </x-select>
                                <x-icon-select />
                            </div>
                            <x-jet-input-error for="serie_id" />
                        </div>
                    @else
                        <div class="w-full">
                            <x-label value="Cantidad :" />
                            <x-input class="block w-full input-number-none" wire:key="{{ rand() }}"
                                wire:model.defer="cantidad" placeholder="0" type="number" min="1"
                                step="1" onkeypress="return validarNumero(event, 11)" />
                            <x-jet-input-error for="cantidad" />
                        </div>
                    @endif --}}

                    <div class="w-full">
                        <x-label value="ALterar stock :" />
                        <div class="relative" x-init="select2Stock" id="parentstock" wire:ignore>
                            <x-select class="block w-full uppercase" id="stock" x-ref="selectstock">
                                <x-slot name="options">
                                    <option value="0">NO ALTERAR STOCK</option>
                                    <option value="1">RESERVAR STOCK</option>
                                    {{-- <option value="2">INCREMENTAR STOCK</option> --}}
                                    <option value="3">DISMINUIR STOCK</option>
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="mode" />
                    </div>
                    {{-- <div class="w-full">
                        <x-label value="Almacén :" />
                        <div class="relative" x-init="select2Almacen" id="parentalmacenguia_id">
                            <x-select class="block w-full uppercase" id="almacenguia_id" x-ref="selectalmacen"
                                data-placeholder="null">
                                <x-slot name="options">
                                    @if (count($almacens))
                                        @foreach ($almacens as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="almacen_id" />
                    </div> --}}
                </div>

                @if ($producto)
                    @if (count($producto->almacens) > 0)
                        <div class="w-full flex flex-wrap gap-2">
                            @foreach ($producto->almacens as $item)
                                <x-simple-card wire:key="{{ $item->id }}"
                                    class="w-full xs:w-52 rounded-lg p-2 flex flex-col gap-1 justify-start">
                                    <div class="text-colorsubtitleform text-center">
                                        <small class="w-full block text-center text-[8px] !leading-none">
                                            STOCK ACTUAL</small>
                                        <span class="inline-block text-2xl text-center font-semibold">
                                            {{ decimalOrInteger($item->pivot->cantidad) }}</span>
                                        <small class="inline-block text-center text-[10px] !leading-none">
                                            {{ $producto->unit->name }}</small>
                                    </div>

                                    <h1 class="text-colortitleform text-[10px] text-center font-semibold">
                                        {{ $item->name }}</h1>

                                    @if ($producto->isRequiredserie())
                                        <div class="w-full">
                                            <x-label value="SELECCIONAR SERIES :" />
                                            <div class="relative" id="parentserie_id_{{ $item->id }}">
                                                <x-select class="block w-full relative" x-init="initializeSelect2($el, {{ $item->id }})"
                                                    id="serie_id_{{ $item->id }}" data-dropdown-parent="null"
                                                    data-placeholder="null" x-ref="serie_id_{{ $item->id }}"
                                                    data-minimum-results-for-search="3">
                                                    <x-slot name="options">
                                                        @foreach ($producto->series()->disponibles()->where('almacen_id', $item->id)->get() as $ser)
                                                            <option value="{{ $ser->id }}">
                                                                {{ $ser->serie }}</option>
                                                        @endforeach
                                                    </x-slot>
                                                </x-select>
                                                <x-icon-select />
                                            </div>
                                            <x-jet-input-error for="almacens.{{ $item->id }}.serie_id" />
                                            {{-- <x-jet-input-error for="serie_id" /> --}}
                                        </div>
                                    @else
                                        <div class="w-full">
                                            <x-label value="STOCK DESCONTAR :" />
                                            <x-input class="block w-full"
                                                wire:model.defer="almacens.{{ $item->id }}.cantidad"
                                                x-mask:dynamic="$money($input, '.', '', 0)"
                                                onkeypress="return validarDecimal(event, 9)"
                                                wire:key="cantidad_{{ $item->id }}"
                                                wire:keydown.enter="addtoguia({{ $item->id }})"
                                                wire:loading.class="bg-blue-50" />
                                            <x-jet-input-error for="almacens.{{ $item->id }}.cantidad" />
                                        </div>
                                    @endif
                                    <div class="w-full">
                                        <x-button type="button" wire:click="addtoguia({{ $item->id }})">
                                            AGREGAR</x-button>
                                    </div>
                                </x-simple-card>
                            @endforeach
                        </div>
                    @else
                        <p class="text-colorerror font-semibold text-[11px]">
                            No existen almacénes vinculados</p>
                    @endif
                @endif

                <div class="w-full flex gap-2 items-start">
                    <x-button wire:click="clearproducto" wire:loading.attr="disabled">
                        {{ __('LIMPIAR') }}</x-button>
                </div>
            </div>

            @if (count($tvitems) > 0)
                <div
                    class="w-full grid grid-cols-[repeat(auto-fill,minmax(170px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-1">
                    @foreach ($tvitems as $item)
                        <x-card-producto :name="$item->producto->name" :image="!empty($item->producto->imagen)
                            ? pathURLProductImage($item->producto->imagen->url)
                            : null" :category="$item->producto->marca->name"
                            id="carshoopguia_{{ $item->id }}">

                            <h1 class="text-xl !leading-none font-semibold mt-1 text-center text-colorlabel">
                                {{ decimalOrInteger($item->cantidad) }}
                                <small class="text-[10px] font-medium">
                                    {{ $item->producto->unit->name }}
                                    <small class="text-colorerror">
                                        /
                                        @if ($item->isNoAlterStock())
                                            NO ALTERA STOCK
                                        @elseif ($item->isReservedStock())
                                            STOCK RESERVADO
                                        @elseif ($item->isIncrementStock())
                                            INCREMENTA STOCK
                                        @elseif($item->isDiscountStock())
                                            DISMINUYE STOCK
                                        @endif
                                    </small>
                                </small>
                            </h1>

                            @if (count($item->kardexes) > 0 && count($item->itemseries) == 0)
                                <div
                                    class="w-full grid {{ count($item->kardexes) > 1 ? 'grid-cols-3 xs:grid-cols-2' : 'grid-cols-1' }} gap-1 mt-2 divide-x divide-borderminicard">
                                    @foreach ($item->kardexes as $kardex)
                                        <div class="w-full p-1.5 flex flex-col items-center justify-start">
                                            <h1 class="text-colorsubtitleform text-sm font-semibold !leading-none">
                                                {{ decimalOrInteger($kardex->cantidad) }}
                                                <small class="text-[10px] font-normal">
                                                    {{ $item->producto->unit->name }}</small>
                                            </h1>

                                            <h1 class="text-colortitleform text-[10px] font-semibold">
                                                {{ $kardex->almacen->name }}</h1>

                                            @if (!$item->producto->isRequiredserie())
                                                <x-button-delete
                                                    wire:click="deletekardex({{ $item->id }}, {{ $kardex->id }})"
                                                    wire:loading.attr="disabled" class="inline-flex" />
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="w-full flex flex-col gap-1 justify-center items-center mt-1"
                                x-data="{ showForm: '{{ count($item->itemseries) == 1 }}' }">
                                @if (count($item->itemseries) > 0)
                                    @if (count($item->itemseries) > 1)
                                        <x-button @click="showForm = !showForm" class="whitespace-nowrap">
                                            <span x-text="showForm ? 'OCULTAR SERIES' : 'MOSTRAR SERIES'"></span>
                                        </x-button>
                                    @endif

                                    <div class="w-full" x-show="showForm" x-transition>
                                        <x-table class="w-full block">
                                            <x-slot name="body">
                                                @foreach ($item->itemseries as $itemserie)
                                                    <tr>
                                                        <td class="p-1 align-middle font-medium">
                                                            {{ $itemserie->serie->serie }}
                                                            [{{ $itemserie->serie->almacen->name }}]
                                                        </td>
                                                        <td class="align-middle text-center" width="40px">
                                                            <x-button-delete
                                                                wire:click="deleteitemserie({{ $itemserie->id }})"
                                                                wire:loading.attr="disabled" />
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </x-slot>
                                        </x-table>
                                    </div>
                                @endif
                            </div>

                            <x-slot name="footer">
                                <x-button-delete wire:click="delete({{ $item->id }})"
                                    wire:loading.attr="disabled" />
                            </x-slot>
                        </x-card-producto>
                    @endforeach
                </div>

                <div class="w-full flex justify-end">
                    <x-button-secondary onclick="confirmDeleteAllItems()" wire:loading.attr="disabled"
                        class="inline-block">ELIMINAR TODO</x-button-secondary>
                </div>
            @endif
        </div>
    </x-form-card>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('createcotizacion', () => ({
                vehiculosml: false,
                loadingprivate: false,
                loadingpublic: false,
                loadingdestinatario: false,
                loadingcomprador: false,
                loadingproveedor: false,
                loadingpackages: false,
                local: false,
                codemotivotraslado: '',
                codemodalidad: '',

                seriecomprobante_id: @entangle('seriecomprobante_id').defer,
                motivotraslado_id: @entangle('motivotraslado_id').defer,
                modalidadtransporte_id: @entangle('modalidadtransporte_id').defer,

                documentdestinatario: @entangle('documentdestinatario').defer,
                namedestinatario: @entangle('namedestinatario').defer,
                documentcomprador: @entangle('documentcomprador').defer,
                namecomprador: @entangle('namecomprador').defer,
                rucproveedor: @entangle('rucproveedor').defer,
                nameproveedor: @entangle('nameproveedor').defer,
                ubigeoorigen_id: @entangle('ubigeoorigen_id').defer,

                alterstock: null,
                mode: @entangle('mode').defer,
                // disponibles: @entangle('disponibles'),
                serie_id: @entangle('serie_id').defer,
                almacen_id: @entangle('almacen_id'),
                producto_id: @entangle('producto_id'),
                almacens: @entangle('almacens').defer,

                init() {
                    this.$watch('almacens', (value) => {
                        this.valuesAlmacen();
                    });
                    this.$watch("producto_id", (value) => {
                        this.selectP.val(value).trigger("change");
                    });

                    Livewire.hook('message.processed', () => {
                        this.valuesAlmacen();
                        this.selectP.select2({
                            templateResult: formatOption
                        }).val(this.producto_id).trigger('change');
                    });
                },
                valuesAlmacen() {
                    if (Object.keys(this.almacens).length > 0) {
                        for (let key in this.almacens) {
                            let x_ref = `serie_id_${this.almacens[key].id}`;
                            let value = this.almacens[key].serie_id;
                            const ser = document.getElementById(x_ref);
                            $(ser).select2().val(value).trigger('change');
                        }
                    }

                },
                initializeSelect2(element, almacen_id) {
                    $(element).select2().on('select2:select', (event) => {
                        this.$wire.set(`almacens.${almacen_id}.serie_id`, event.target.value,
                            true);
                    });
                },
                toggle() {
                    this.vehiculosml = !this.vehiculosml;
                    if (this.vehiculosml) {
                        this.loadingpublic = false;
                        this.loadingprivate = false;
                    } else {
                        this.selectedModalidadtransporte(this.codemodalidad);
                    }
                },
                toggleComprador() {
                    this.loadingcomprador = false;
                },
                getCodeMotivo(target) {
                    this.codemotivotraslado = target.options[target.selectedIndex].getAttribute(
                        'data-code');
                    this.selectedMotivotraslado(this.codemotivotraslado);
                    this.documentdestinatario = '';
                    this.namedestinatario = '';
                    this.documentcomprador = '';
                    this.namecomprador = '';
                    this.rucproveedor = '';
                    this.nameproveedor = '';
                },
                getCodeModalidad(target) {
                    this.codemodalidad = target.options[target.selectedIndex].getAttribute(
                        'data-code');
                    if (!this.vehiculosml) {
                        this.selectedModalidadtransporte(this.codemodalidad);
                    }
                },
                selectedModalidadtransporte(value) {
                    switch (value) {
                        case '01':
                            this.loadingpublic = true;
                            this.loadingprivate = false;
                            break;
                        case '02':
                            this.loadingprivate = true;
                            this.loadingpublic = false;
                            break;
                        default:
                            this.loadingprivate = false;
                            this.loadingpublic = false;
                    }
                },
                selectedMotivotraslado(value) {

                    switch (value) {
                        case '01': //VENTA
                            this.loadingdestinatario = true;
                            this.loadingcomprador = false;
                            this.loadingproveedor = false;
                            this.loadingpackages = false;
                            break;
                        case '02': //COMPRA
                            this.loadingdestinatario = false;
                            this.loadingcomprador = false;
                            this.loadingproveedor = true;
                            this.loadingpackages = false;
                            break;
                        case '03': //VENTA TERCEROS
                            this.loadingdestinatario = true;
                            this.loadingcomprador = true;
                            this.loadingproveedor = false;
                            this.loadingpackages = false;
                            break;
                        case '04': //TRASLADO ESTABLECIMIENTOS
                            this.loadingdestinatario = false;
                            this.loadingcomprador = false;
                            this.loadingproveedor = false;
                            this.loadingpackages = false;
                            break;
                        case '05': //CONSIGNACION
                            this.loadingdestinatario = true;
                            this.loadingproveedor = false;
                            this.loadingcomprador = false;
                            this.loadingpackages = false;
                            break;
                        case '06': //DEVOLUCION
                            this.loadingdestinatario = true;
                            this.loadingproveedor = false;
                            this.loadingcomprador = false;
                            this.loadingpackages = false;
                            break;
                        case '13': //OTROS
                            this.loadingdestinatario = true;
                            this.loadingproveedor = true;
                            this.loadingcomprador = true;
                            this.loadingpackages = false;
                            break;
                        case '14': //VENTA SUJETA CONFIRMACION
                            this.loadingdestinatario = true;
                            this.loadingproveedor = false;
                            this.loadingcomprador = false;
                            this.loadingpackages = false;
                            break;
                        default:
                            this.loadingdestinatario = false;
                            // this.loadingprivate = false;
                            // this.loadingpublic = false;
                            this.loadingdestinatario = false;
                            this.loadingcomprador = false;
                            this.loadingproveedor = false;
                            this.loadingpackages = false;
                    }

                    if (this.codemodalidad == '' || this.vehiculosml) {
                        this.loadingprivate = false;
                        this.loadingpublic = false;
                    }

                    if (this.local == '0') {
                        this.loadingdestinatario = true;
                    }
                },
                toggledisponibles() {
                    this.producto_id = null;
                    // @this.loadproductos();
                    // @this.loadseries();
                },
                resetMotivotraslado(target) {
                    this.local = target.options[target.selectedIndex].getAttribute(
                        'data-sendsunat');
                    this.codemotivotraslado = '';
                    this.loadingdestinatario = false;
                    this.loadingcomprador = false;
                    this.loadingproveedor = false;
                    // this.loadingprivate = false;
                    // this.loadingpublic = false;

                    if (this.codemodalidad == '' || this.vehiculosml) {
                        this.loadingprivate = false;
                        this.loadingpublic = false;
                    }

                    if (this.local == '0') {
                        this.loadingdestinatario = true;
                    }
                }
            }))
        })

        function select2Typecomprobante() {
            this.selectTC = $(this.$refs.typecomprobante).select2();
            this.selectTC.val(this.seriecomprobante_id).trigger("change");
            this.selectTC.on("select2:select", (event) => {
                // this.seriecomprobante_id = event.target.value;
                this.$wire.set('seriecomprobante_id', event.target.value, true)
                this.resetMotivotraslado(event.target);
                this.$wire.$refresh()
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("seriecomprobante_id", (value) => {
                this.selectTC.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectTC.select2('destroy');
                this.selectTC.select2().val(this.seriecomprobante_id).trigger('change');
            });
        }

        function select2Motivo() {
            this.selectMOT = $(this.$refs.motivotraslado).select2();
            this.selectMOT.val(this.motivotraslado_id).trigger("change");
            this.selectMOT.on("select2:select", (event) => {
                this.motivotraslado_id = event.target.value;
                this.getCodeMotivo(event.target);
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("motivotraslado_id", (value) => {
                this.selectMOT.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectMOT.select2('destroy');
                this.selectMOT.select2().val(this.motivotraslado_id).trigger('change');
            });
        }

        function select2Modalidad() {
            this.selectMT = $(this.$refs.modalidad).select2();
            this.selectMT.val(this.modalidadtransporte_id).trigger("change");
            this.selectMT.on("select2:select", (event) => {
                this.modalidadtransporte_id = event.target.value;
                this.getCodeModalidad(event.target);
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("modalidadtransporte_id", (value) => {
                this.selectMT.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectMT.select2('destroy');
                this.selectMT.select2().val(this.modalidadtransporte_id).trigger('change');
            });
        }

        function select2Stock() {
            this.selectSTK = $(this.$refs.selectstock).select2();
            this.selectSTK.val(this.mode).trigger("change");
            this.selectSTK.on("select2:select", (event) => {
                this.mode = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("mode", (value) => {
                this.selectSTK.val(value).trigger("change");
            });
        }

        function SelectUbigeoDestino() {
            this.selectUD = $(this.$refs.selectubigeodest).select2();
            this.selectUD.val(this.ubigeodestino_id).trigger("change");
            this.selectUD.on("select2:select", (event) => {
                this.ubigeodestino_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("ubigeodestino_id", (value) => {
                this.selectUD.val(value).trigger("change");
            });
            // Livewire.hook('message.processed', () => {
            //     this.selectUD.select2('destroy');
            //     this.selectUD.select2().val(this.ubigeodestino_id).trigger('change');
            // });
        }

        function SelectUbigeoOrigen() {
            this.selectUO = $(this.$refs.selectubigeoorigen).select2();
            this.selectUO.val(this.ubigeoorigen_id).trigger("change");
            this.selectUO.on("select2:select", (event) => {
                this.ubigeoorigen_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("ubigeoorigen_id", (value) => {
                this.selectUO.val(value).trigger("change");
            });
            // Livewire.hook('message.processed', () => {
            //     this.selectUO.select2('destroy');
            //     this.selectUO.select2().val(this.ubigeoorigen_id).trigger('change');
            // });
        }

        function select2Almacen() {
            this.selectA = $(this.$refs.selectalmacen).select2();
            this.selectA.val(this.almacen_id).trigger("change");
            this.selectA.on("select2:select", (event) => {
                this.almacen_id = event.target.value == "" ? null : event.target.value;
                this.producto_id = null;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("almacen_id", (value) => {
                this.selectA.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectA.select2('destroy');
                this.selectA.select2().val(this.almacen_id).trigger('change');
            });
        }

        function select2Producto() {
            this.selectP = $(this.$refs.selectprod).select2({
                templateResult: formatOption
            });
            this.selectP.val(this.producto_id).trigger("change");
            this.selectP.on("select2:select", (event) => {
                this.producto_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
        }

        function select2Serie() {
            this.selectS = $(this.$refs.selectserie).select2();
            this.selectS.val(this.serie_id).trigger("change");
            this.selectS.on("select2:select", (event) => {
                this.serie_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("serie_id", (value) => {
                this.selectS.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectS.select2('destroy');
                this.selectS.select2().val(this.serie_id).trigger('change');
            });
        }

        function confirmDeleteAllItems() {
            swal.fire({
                title: 'QUITAR TODOS LOS PRODUCTOS AGREGADOS A LA GUÍA ?',
                text: null,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deleteallitems();
                }
            })
        }

        function formatOption(option) {
            if (!option.id) {
                return option.text;
            }
            const image = $(option.element).data('image') ?? '';
            const marca = $(option.element).data('marca') ?? '';
            const category = $(option.element).data('category') ?? '';
            const subcategory = $(option.element).data('subcategory') ?? '';

            let html = `<div class="custom-list-select">
                        <div class="image-custom-select">`;
            if (image) {
                html += `<img src="${image}" class="w-full h-full object-scale-down block" alt="${option.text}">`;
            } else {
                html += `<x-icon-image-unknown class="w-full h-full" />`;
            }
            html += `</div>
                        <div class="content-custom-select">
                            <p class="title-custom-select">${option.text}</p>
                            <p class="marca-custom-select">${marca}</p>  
                            <div class="category-custom-select">
                                <span class="inline-block">${category}</span>
                                <span class="inline-block">${subcategory}</span>
                            </div>  
                        </div>
                    </div>`;
            return $(html);
        }
    </script>
</div>
