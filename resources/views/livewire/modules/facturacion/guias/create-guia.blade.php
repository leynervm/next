<div x-data="loader">
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
                    <div class="inline-block">
                        <x-label-check for="vehiculosml" class="uppercase">
                            <x-input wire:model.defer="vehiculosml" name="vehiculosml" type="checkbox"
                                id="vehiculosml" @change="toggle" />
                            TRASLADO EN VEHÍCULOS DE CATEGORÍA M1 O L
                        </x-label-check>
                    </div>

                    <div class="inline-block">
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
                            wire:keydown.enter.prevent="getDestinatario" minlength="0" maxlength="11"
                            onkeypress="return validarNumero(event, 11)" />
                        <x-button-add class="px-2" wire:click="getDestinatario" wire:target="getDestinatario"
                            wire:loading.attr="disable">
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
                            wire:keydown.enter="getComprador" minlength="0" maxlength="11"
                            onkeypress="return validarNumero(event, 11)" />
                        <x-button-add class="px-2" wire:click="getComprador" wire:loading.attr="disable">
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
                        <x-input class="block w-full flex-1 prevent input-number-none"
                            onkeypress="return validarNumero(event, 11)" x-model="rucproveedor" type="number"
                            wire:model.defer="rucproveedor" wire:keydown.enter="getProveedor" minlength="0"
                            maxlength="11" />
                        <x-button-add class="px-2" wire:click="getProveedor" wire:target="getDestinatario"
                            wire:loading.attr="disable">
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
                            type="number" wire:keydown.enter="getTransport" minlength="0" maxlength="11" />
                        <x-button-add class="px-2" wire:click="getTransport" wire:loading.attr="disable">
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
                        <div class="w-full inline-flex gap-1" x-data="{ documentdriver: '' }">
                            <x-input class="block w-full flex-1" wire:model.defer="documentdriver"
                                wire:keydown.enter.prevent="getDriver" minlength="0" maxlength="11"
                                @input="event.target.value = event.target.value.replace(/[^0-9]/g, '')" />
                            <x-button-add class="px-2" wire:click="getDriver" wire:loading.attr="disable">
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
        {{-- @foreach ($errors->all() as $key)
            <small class="text-red-500">{{ $key }}</small>
        @endforeach --}}
    </div>

    <x-form-card titulo="RESUMEN PRODUCTOS" class="mt-3">
        <div class="w-full relative rounded flex flex-col gap-3">
            <form wire:submit.prevent="addtoguia" class="w-full flex flex-col gap-2">
                <div class="w-full grid lg:grid-cols-3 gap-2">
                    <div class="w-full">
                        <x-label value="ALterar stock :" />
                        <div class="relative" x-init="select2Stock" id="parentstock" wire:ignore>
                            <x-select class="block w-full uppercase" id="stock" x-ref="selectstock">
                                <x-slot name="options">
                                    <option value="0">NO ALTERAR STOCK</option>
                                    <option value="1">RESERVAR STOCK</option>
                                    <option value="2">INCREMENTAR STOCK</option>
                                    <option value="3">DISMINUIR STOCK</option>
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="mode" />
                    </div>
                    <div class="w-full">
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
                    </div>
                </div>

                <div class="w-full grid sm:grid-cols-3 gap-2">
                    <div class="w-full sm:col-span-2">
                        <x-label value="Descripción del producto :" />
                        <div class="w-full relative" x-init="select2Producto" id="parentguiaproducto_id">
                            <x-select class="block w-full uppercase" x-ref="selectprod"
                                data-minimum-results-for-search="3" id="guiaproducto_id">
                                <x-slot name="options">
                                    @if (count($productos) > 0)
                                        @foreach ($productos as $item)
                                            <option data-marca="{{ $item->name_marca }}"
                                                data-category="{{ $item->name_category }}"
                                                data-subcategory="{{ $item->name_subcategory }}"
                                                data-image="{{ !empty($item->image) ? pathURLProductImage($item->image) : null }}"
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

                    @if (count($series) > 0 && in_array($mode, ['1', '3']))
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
                    @endif
                </div>

                <div class="w-full flex justify-between items-start">
                    <div class="w-full flex-1">
                        <x-label-check for="disponibles">
                            <x-input wire:model.lazy="disponibles" @change="toggledisponibles" name="disponibles"
                                type="checkbox" id="disponibles" />
                            MOSTRAR SOLAMENTE PRODUCTOS DISPONIBLES</x-label-check>

                        <x-label-check for="clearaftersave">
                            <x-input wire:model.defer="clearaftersave" name="clearaftersave" value="true"
                                type="checkbox" id="clearaftersave" />
                            LIMPIAR FORMULARIO DESPUES AGREGAR</x-label-check>
                    </div>
                    <x-button type="submit" wire:loading.attr="disabled">{{ __('AGREGAR') }}</x-button>
                </div>
            </form>

            @if (count($carshoops) > 0)
                <div
                    class="w-full grid grid-cols-[repeat(auto-fill,minmax(170px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-1">
                    @foreach ($carshoops as $item)
                        <x-card-producto :name="$item->producto->name" :image="!empty($item->producto->image) ? pathURLProductImage($item->producto->image) : null" :almacen="$item->almacen->name" :category="$item->producto->category->name"
                            id="carshoopguia_{{ $item->id }}" {{-- x-data="lazyload" --}}>
                            <div class="w-full mt-1 flex flex-wrap gap-1 items-start">
                                <x-span-text :text="$item->cantidad . ' ' . $item->producto->unit->name" class="leading-3 !tracking-normal" />

                                @if ($item->isNoAlterStock())
                                    <x-span-text text="NO ALTERA STOCK" class="leading-3 !tracking-normal" />
                                @elseif ($item->isReservedStock())
                                    <x-span-text text="STOCK RESERVADO" class="leading-3 !tracking-normal"
                                        type="orange" />
                                @elseif ($item->isIncrementStock())
                                    <x-span-text text="INCREMENTA STOCK" class="leading-3 !tracking-normal"
                                        type="green" />
                                @elseif($item->isDiscountStock())
                                    <x-span-text text="DISMINUYE STOCK" class="leading-3 !tracking-normal"
                                        type="red" />
                                @endif

                                @if (count($item->carshoopseries) == 1)
                                    <x-span-text :text="'SERIE : ' . $item->carshoopseries()->first()->serie->serie" class="leading-3 !tracking-normal" />
                                @endif
                            </div>

                            @if (count($item->carshoopseries) > 1)
                                <div x-data="{ showForm: false }" class="mt-1">
                                    <x-button @click="showForm = !showForm" class="whitespace-nowrap">
                                        {{ __('VER SERIES') }}</x-button>
                                    <div x-show="showForm" x-transition class="block w-full rounded mt-1">
                                        <div class="w-full flex flex-wrap gap-1">
                                            @foreach ($item->carshoopseries as $itemserie)
                                                <span
                                                    class="inline-flex items-center gap-1 text-[10px] bg-fondospancardproduct text-textspancardproduct p-1 rounded-md">
                                                    {{ $itemserie->serie->serie }}
                                                    <x-button-delete wire:click="deleteserie({{ $itemserie->id }})"
                                                        wire:loading.attr="disabled" />
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <x-slot name="footer">
                                <x-button-delete wire:click="delete({{ $item->id }})"
                                    wire:loading.attr="disabled" />
                            </x-slot>
                        </x-card-producto>
                    @endforeach
                </div>

                <div class="w-full flex justify-end">
                    <x-button-secondary onclick="confirmDeleteAllCarshoop()" wire:loading.attr="disabled"
                        class="inline-block">ELIMINAR TODO</x-button-secondary>
                </div>
            @endif
        </div>
    </x-form-card>

    <script>
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
                templateResult: function(data) {
                    if (!data.id) {
                        return data.text;
                    }
                    const image = $(data.element).data('image') ?? '';
                    const marca = $(data.element).data('marca') ?? '';
                    const category = $(data.element).data('category') ?? '';
                    const subcategory = $(data.element).data('subcategory') ?? '';

                    let html = `<div class="custom-list-select">
                        <div class="image-custom-select">`;
                    if (image) {
                        html +=
                            `<img src="${image}" class="w-full h-full object-scale-down block" alt="${data.text}">`;
                    } else {
                        html += `<x-icon-image-unknown class="w-full h-full" />`;
                    }
                    html += `</div>
                            <div class="content-custom-select">
                                <p class="title-custom-select">
                                    ${data.text}</p>
                                <p class="marca-custom-select">
                                    ${marca}</p>  
                                <div class="category-custom-select">
                                    <span class="inline-block">${category}</span>
                                    <span class="inline-block">${subcategory}</span>
                                </div>  
                            </div>
                      </div>`;
                    return $(html);
                }
            });
            this.selectP.val(this.producto_id).trigger("change");
            this.selectP.on("select2:select", (event) => {
                this.producto_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("producto_id", (value) => {
                this.selectP.val(value).trigger("change");
                if (value == null) {
                    this.selectP.empty();
                }
            });
            Livewire.hook('message.processed', () => {
                this.selectP.select2('destroy');
                this.selectP.select2({
                    templateResult: function(data) {
                        if (!data.id) {
                            return data.text;
                        }
                        const image = $(data.element).data('image') ?? '';
                        const marca = $(data.element).data('marca') ?? '';
                        const category = $(data.element).data('category') ?? '';
                        const subcategory = $(data.element).data('subcategory') ?? '';

                        let html = `<div class="custom-list-select">
                        <div class="image-custom-select">`;
                        if (image) {
                            html +=
                                `<img src="${image}" class="w-full h-full object-scale-down block" alt="${data.text}">`;
                        } else {
                            html += `<x-icon-image-unknown class="w-full h-full" />`;
                        }
                        html += `</div>
                            <div class="content-custom-select">
                                <p class="title-custom-select">
                                    ${data.text}</p>
                                <p class="marca-custom-select">
                                    ${marca}</p>  
                                <div class="category-custom-select">
                                    <span class="inline-block">${category}</span>
                                    <span class="inline-block">${subcategory}</span>
                                </div>  
                            </div>
                      </div>`;
                        return $(html);
                    }
                }).val(this.producto_id).trigger('change');
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

        function confirmDeleteAllCarshoop() {
            swal.fire({
                title: 'Eliminar carrito de guías de remisión ?',
                text: "Se eliminarán todos los productos del carrito de guías de remisión y se actualizará su stock correspondientes.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deleteallcarshoop();
                }
            })
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('loader', () => ({
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
                mode: @entangle('mode'),

                // disponibles: @entangle('disponibles'),
                serie_id: @entangle('serie_id').defer,
                almacen_id: @entangle('almacen_id'),
                producto_id: @entangle('producto_id'),

                init() {
                    // console.log("loaded Alpine on create-guia");
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
    </script>
</div>
