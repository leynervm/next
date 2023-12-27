<div>
    <form wire:submit.prevent="save" class="w-full flex flex-col gap-8" x-data="loader">
        <x-form-card titulo="RESUMEN GUÍA REMISIÓN"
            subtitulo="Complete todos los campos para registrar una nueva guía de remisión.">
            <div class="w-full flex flex-col gap-2 bg-body p-3 rounded-md">
                <div class="w-full grid grid-cols-1 xs:grid-cols-2 xl:grid-cols-3 gap-2">
                    <div class="w-full xs:col-span-2 md:col-span-1">
                        <x-label value="Tipo guía :" />
                        <x-select class="block w-full uppercase" id="motivotraslado_id"
                            wire:model.defer="typecomprobante_id">
                            <x-slot name="options">
                                @if (count($typecomprobantes))
                                    @foreach ($typecomprobantes as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="typecomprobante_id" />
                        <x-jet-input-error for="seriecomprobante.id" />
                    </div>

                    <div class="w-full xs:col-span-2 md:col-span-1">
                        <x-label value="Motivo traslado :" />
                        <x-select class="block w-full uppercase" id="motivotraslado_id"
                            wire:model.defer="motivotraslado_id" @change="getCodeMotivo($event.target)">
                            <x-slot name="options">
                                @if (count($motivotraslados))
                                    @foreach ($motivotraslados as $item)
                                        <option value="{{ $item->id }}" data-code="{{ $item->code }}">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <span x-text="codemotivotraslado"></span>
                        <x-jet-input-error for="motivotraslado_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Modalidad transporte :" />
                        <x-select class="block w-full uppercase" id="modalidadtransporte_id"
                            wire:model.defer="modalidadtransporte_id" @change="getCodeModalidad($event.target)">
                            <x-slot name="options">
                                @if (count($modalidadtransportes))
                                    @foreach ($modalidadtransportes as $item)
                                        <option value="{{ $item->id }}" data-code="{{ $item->code }}">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <span x-text="codemodalidad"></span>
                        <x-jet-input-error for="modalidadtransporte_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Fecha traslado :" />
                        <x-input class="block w-full" wire:model.defer="datetraslado" type="date" />
                        <x-jet-input-error for="datetraslado" />
                    </div>

                    <div class="w-full">
                        <x-label value="Peso bruto total (KILOGRAMO) :" />
                        <x-input class="block w-full" wire:model.defer="peso" min="0" step="0.01"
                            type="number" />
                        <x-jet-input-error for="peso" />
                    </div>

                    <div class="w-full">
                        <x-label value="Bultos / Paquetes :" />
                        <x-input class="block w-full" wire:model.defer="packages" min="0" step="1"
                            type="number" />
                        <x-jet-input-error for="packages" />
                    </div>

                    <div class="w-full xs:col-span-2 lg:col-span-1">
                        <x-label value="Descripción :" />
                        <x-input class="block w-full" wire:model.defer="note"
                            placeholder="Descripcion, detalle de la guía (Opcional)..." />
                        <x-jet-input-error for="note" />
                    </div>

                    <div class="w-full">
                        <x-label value="Comprobante referencia emitido :" />
                        <div class="w-full inline-flex">
                            <x-input class="block w-full prevent" wire:model.defer="referencia"
                                wire:keydown.enter="searchreferencia" minlength="0" maxlength="11" />
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

                    <div class="w-full animate__animated animate__fadeInDown" x-show="vehiculosml">
                        <x-label value="Placa vehículo (Opcional) :" />
                        <x-input class="block w-full" wire:model.defer="placavehiculo"
                            placeholder="Placa del vehículo de transporte..." />
                        <x-jet-input-error for="placavehiculo" />
                    </div>
                </div>

                <div>
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

                <div wire:loading.flex wire:target="save" class="loading-overlay rounded hidden">
                    <x-loading-next />
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="DESTINATARIO" x-show="loadingdestinatario"
            class="animate__animated animate__fadeInDown">
            <div class="w-full bg-body p-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-1">
                <div class="w-full">
                    <x-label value="DNI / RUC :" />
                    <div class="w-full inline-flex">
                        <x-input class="block w-full prevent numeric" wire:model.defer="documentdestinatario"
                            wire:keydown.enter="getDestinatario" minlength="0" maxlength="11" />
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
                    <x-input class="block w-full" wire:model.defer="namedestinatario"
                        placeholder="Nombres / razón social del destinatario" />
                    <x-jet-input-error for="namedestinatario" />
                </div>
            </div>
            <div wire:loading.flex wire:target="getDestinatario, save" class="loading-overlay rounded hidden">
                <x-loading-next />
            </div>
        </x-form-card>

        <x-form-card titulo="COMPRADOR" x-show="loadingcomprador" class="animate__animated animate__fadeInDown">
            <div class="w-full bg-body p-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                <div class="w-full">
                    <x-label value="DNI / RUC :" />
                    <div class="w-full inline-flex">
                        <x-input class="block w-full prevent numeric" wire:model.defer="documentcomprador"
                            wire:keydown.enter="getComprador" minlength="0" maxlength="11" />
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
                    <x-input class="block w-full" wire:model.defer="namecomprador"
                        placeholder="Nombres / razón social del comprador" />
                    <x-jet-input-error for="namecomprador" />
                </div>
            </div>
            <div wire:loading.flex wire:target="getComprador,save" class="loading-overlay rounded hidden">
                <x-loading-next />
            </div>
        </x-form-card>

        <x-form-card titulo="TRANSPORTISTA" x-show="loadingpublic" class="animate__animated animate__fadeInDown">
            <div class="w-full bg-body p-3 grid grid-cols-1 xs:grid-cols-2 xl:grid-cols-3 gap-1">
                <div class="w-full xs:col-span-2 lg:col-span-1">
                    <x-label value="RUC :" />
                    <div class="w-full inline-flex">
                        <x-input class="block w-full prevent numeric" wire:model.defer="ructransport"
                            wire:keydown.enter="getTransport" minlength="0" maxlength="11" />
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

        <x-form-card titulo="PROVEEDOR" x-show="loadingproveedor" class="animate__animated animate__fadeInDown">
            <div class="w-full bg-body p-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                <div class="w-full">
                    <x-label value="RUC :" />
                    <div class="w-full inline-flex">
                        <x-input class="block w-full prevent numeric" wire:model.defer="rucproveedor"
                            wire:keydown.enter="getProveedor" minlength="0" maxlength="11" />
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
                    <x-input class="block w-full" wire:model.defer="nameproveedor"
                        placeholder="Razón social del proveedor" />
                    <x-jet-input-error for="nameproveedor" />
                </div>
            </div>
            <div wire:loading.flex wire:target="getProveedor, save" class="loading-overlay rounded hidden">
                <x-loading-next />
            </div>
        </x-form-card>

        <x-form-card titulo="LUGAR DE EMISIÓN">
            <div class="w-full flex flex-col gap-2 bg-body p-3 rounded-md">
                <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-1">
                    <div class="w-full">
                        <x-label value="Departamento :" />
                        <x-select class="block w-full" id="regionorigen_id" wire:model.lazy="regionorigen_id">
                            <x-slot name="options">
                                @if (count($regiones))
                                    @foreach ($regiones as $item)
                                        <option value="{{ $item->departamento_inei }}">
                                            {{ $item->region }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="regionorigen_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Provincia :" />
                        <x-select class="block w-full" id="provinciaorigen_id" wire:model.lazy="provinciaorigen_id">
                            <x-slot name="options">
                                @if (count($provinciasorigen))
                                    @foreach ($provinciasorigen as $item)
                                        <option value="{{ $item->provincia_inei }}">
                                            {{ $item->provincia }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="provinciaorigen_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Distrito :" />
                        <x-select class="block w-full" id="distritoorigen_id" wire:model.defer="distritoorigen_id">
                            <x-slot name="options">
                                @if (count($distritosorigen))
                                    @foreach ($distritosorigen as $item)
                                        <option value="{{ $item->id }}">{{ $item->distrito }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="distritoorigen_id" />
                    </div>

                    <div class="w-full" :class="codemotivotraslado == '04' ? 'lg:col-span-2' : 'lg:col-span-3'">
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

                <div wire:loading.flex wire:target="regionorigen_id, provinciaorigen_id, save"
                    class="loading-overlay rounded hidden">
                    <x-loading-next />
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="LUGAR DE DESTINO">
            <div class="w-full flex flex-col gap-2 bg-body p-3 rounded-md">
                <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-1">
                    <div class="w-full">
                        <x-label value="Departamento :" />
                        <x-select class="block w-full" id="regiondestino_id" wire:model.lazy="regiondestino_id">
                            <x-slot name="options">
                                @if (count($regiones))
                                    @foreach ($regiones as $item)
                                        <option value="{{ $item->departamento_inei }}">
                                            {{ $item->region }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="regiondestino_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Provincia :" />
                        <x-select class="block w-full" id="provinciadestino_id"
                            wire:model.lazy="provinciadestino_id">
                            <x-slot name="options">
                                @if (count($provinciasdestino))
                                    @foreach ($provinciasdestino as $item)
                                        <option value="{{ $item->provincia_inei }}">
                                            {{ $item->provincia }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="provinciadestino_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Distrito :" />
                        <x-select class="block w-full" id="distritodestino_id" wire:model.defer="distritodestino_id">
                            <x-slot name="options">
                                @if (count($distritosdestino))
                                    @foreach ($distritosdestino as $item)
                                        <option value="{{ $item->id }}">{{ $item->distrito }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="distritodestino_id" />
                    </div>

                    <div class="w-full" :class="codemotivotraslado == '04' ? 'lg:col-span-2' : 'lg:col-span-3'">
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
                <div wire:loading.flex wire:target="regiondestino_id,provinciadestino_id, save"
                    class="loading-overlay rounded hidden">
                    <x-loading-next />
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="CONDUCTOR VEHÍCULO" x-show="loadingprivate"
            class="animate__animated animate__fadeInDown">
            <div class="w-full relative rounded flex flex-col gap-2">
                <div class="w-full bg-body p-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                    <div class="w-full">
                        <x-label value="Documento :" />
                        <div class="w-full inline-flex">
                            <x-input class="block w-full prevent numeric" wire:model.defer="documentdriver"
                                wire:keydown.enter="getDriver" minlength="0" maxlength="11" />
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

                <div class="w-full relative rounded">
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
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="VEHÍCULOS TRANSPORTE" x-show="loadingprivate"
            class="animate__animated animate__fadeInDown">
            <div class="w-full relative rounded flex flex-wrap md:flex-nowrap gap-3">

                <div class="w-full md:w-96 md:flex-shrink-0 bg-body p-3 rounded relative" x-data="{ loading: false }">
                    <div class="w-full flex flex-col gap-2">
                        <div class="w-full">
                            <x-label value="Placa vehículo :" />
                            <x-input class="block w-full defer" wire:model.defer="placa"
                                wire:keydown.enter="addplacavehiculo"
                                placeholder="placa del del vehículo transporte..." />
                            <x-jet-input-error for="placa" />
                            <x-jet-input-error for="placavehiculos" />
                        </div>

                        <div class="w-full mt-3 flex justify-end">
                            <x-button type="button" wire:click="addplacavehiculo">{{ __('AGREGAR') }}</x-button>
                        </div>
                    </div>

                    <div wire:loading.flex wire:target="addplacavehiculo" class="loading-overlay rounded hidden">
                        <x-loading-next />
                    </div>
                </div>

                <div class="w-full relative rounded">
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
            <x-button type="submit" wire:loading.attr="disabled">{{ __('REGISTRAR') }}</x-button>
        </div>
    </form>

    <div class="w-full flex flex-col">
        @foreach ($errors->all() as $key)
            <small class="text-red-500">{{ $key }}</small>
        @endforeach
    </div>

    <x-form-card titulo="PRODUCTOS VINCULADOS" class="mt-3">
        <div class="w-full relative rounded flex flex-col gap-3">
            <form wire:submit.prevent="addtoguia" class="w-full flex flex-col gap-2">
                <div class="w-full grid grid-cols-1 sm:grid-cols-4 gap-1">
                    <div class="w-full">
                        <x-label value="Almacén :" />
                        <x-select class="block w-full uppercase" id="almacenguia_id" wire:model.lazy="almacen_id">
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
                        <x-jet-input-error for="almacen_id" />
                    </div>

                    <div class="w-full sm:col-span-2">
                        <x-label value="Descripción del producto :" />
                        <div class="w-full relative" x-data="{ producto_id: @entangle('producto_id') }" x-init="select2ProductoAlpine"
                            id="parentguiaproducto_id" wire:ignore>
                            <x-select class="block w-full uppercase" x-ref="select"
                                data-minimum-results-for-search="3" id="guiaproducto_id">
                                <x-slot name="options">
                                    @if (count($productos))
                                        @foreach ($productos as $item)
                                            <option value="{{ $item->id }}">
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

                    @if (count($series))
                        <div class="w-full">
                            <x-label value="Seleccionar serie :" />
                            <div class="w-full relative" id="parentguiaserie_id" x-data="{ serie_id: @entangle('serie_id').defer }"
                                x-init="select2SerieAlpine">
                                <x-select class="block w-full uppercase" x-ref="select"
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
                    @endif

                    <div class="w-full @if (count($series) > 0) hidden @endif">
                        <x-label value="Cantidad :" />
                        <x-input class="block w-full" wire:model.defer="cantidad" placeholder="0" type="number"
                            min="1" step="1" />
                        <x-jet-input-error for="cantidad" />
                    </div>
                </div>
                {{ count($series) }}


                <div class="w-full flex justify-between items-start">
                    <div class="w-full">
                        <x-label-check for="alterstock">
                            <x-input wire:model.defer="alterstock" name="alterstock" value="true" type="checkbox"
                                id="alterstock" />
                            Alterar stock
                        </x-label-check>
                        <x-label-check for="clearaftersave">
                            <x-input wire:model.defer="clearaftersave" name="clearaftersave" value="true"
                                type="checkbox" id="clearaftersave" />
                            Limpiar formulario despues de agregar
                        </x-label-check>
                    </div>
                    <x-button type="submit" wire:loading.attr="disabled">{{ __('AGREGAR') }}</x-button>
                </div>
            </form>

            <div class="w-full flex flex-wrap gap-2 relative rounded">
                @foreach ($carrito as $item)
                    <x-card-producto :name="$item->producto" :almacen="$item->almacen">
                        <div class="w-full mt-1 flex flex-wrap gap-1 items-start">
                            <x-span-text :text="$item->cantidad . ' ' . $item->unit" class="leading-3" />

                            <x-span-text :text="'ALTER ' . $item->alterstock" class="leading-3" />
                            <x-span-text :text="'SUCURSAL ' . $item->sucursal_id" class="leading-3" />
                            <x-span-text :text="'USER ' . $item->user_id" class="leading-3" />

                            {{-- {{ print_r($item->series) }} --}}
                            {{-- @if (count($item->series) == 1)
                                <x-span-text :text="'SERIE : ' . $item->series[0]->serie" class="leading-3" />
                            @endif --}}

                        </div>
                        @if (count($item->series) > 1)
                            <div x-data="{ showForm: false }" class="mt-1">
                                <x-button @click="showForm = !showForm" class="whitespace-nowrap">
                                    {{ __('VER SERIES') }}
                                </x-button>
                                <div x-show="showForm" x-transition:enter="transition ease-out duration-300 transform"
                                    x-transition:enter-start="opacity-0 translate-y-[-10%]"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-300 transform"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-[-10%]"
                                    class="block w-full rounded mt-1">
                                    <div class="w-full flex flex-wrap gap-1">
                                        @foreach ($item->series as $serie)
                                            <span
                                                class="inline-flex items-center gap-1 text-[10px] bg-fondospancardproduct text-textspancardproduct p-1 rounded-md">
                                                {{ $serie->serie }}
                                                <x-button-delete
                                                    wire:click="deleteserie('{{ $item->id }}', {{ $serie->id }})"
                                                    wire:loading.attr="disabled" />
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <x-slot name="footer">
                            <x-button-delete wire:click="delete('{{ $item->id }}')"
                                wire:loading.attr="disabled" />
                        </x-slot>
                    </x-card-producto>
                @endforeach

                {{ print_r($seriescarrito) }}
                {{-- @if (count($series))
                    @foreach ($series as $item)
                        {{ $item }}
                    @endforeach
                @endif --}}
            </div>
            <div wire:loading.flex class="loading-overlay rounded hidden">
                <x-loading-next />
            </div>
        </div>
    </x-form-card>

    <script>
        function select2ProductoAlpine() {
            this.select2 = $(this.$refs.select).select2();
            this.select2.val(this.producto_id).trigger("change");
            this.select2.on("select2:select", (event) => {
                    this.producto_id = event.target.value;
                })
                .on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
            this.$watch("producto_id", (value) => {
                this.select2.val(value).trigger("change");
            });
        }

        function select2SerieAlpine() {

            this.select2 = $(this.$refs.select).select2();
            this.select2.on("select2:select", (event) => {
                this.serie_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("serie_id", (value) => {
                this.select2.val(value).trigger("change");
            });
        }

        window.addEventListener('renderselect2', () => {
            $("#guiaserie_id").select2();

            // $("#guiaserie_id").select2()
            //     .on("change", (e) => {
            //         @this.serie_id = e.target.value;
            //     }).on('select2:open', function(e) {
            //         const evt = "scroll.select2";
            //         $(e.target).parents().off(evt);
            //         $(window).off(evt);
            //     });
        })

        document.addEventListener('alpine:init', () => {
            Alpine.data('loader', () => ({
                vehiculosml: false,
                loadingprivate: false,
                loadingpublic: false,
                loadingdestinatario: false,
                loadingcomprador: false,
                loadingproveedor: false,
                codemotivotraslado: '',
                codemodalidad: '',

                init() {
                    console.log("loaded Edit");
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
                        case '01':
                            this.loadingdestinatario = true;
                            this.loadingcomprador = false;
                            this.loadingproveedor = false;
                            break;
                        case '02':
                            this.loadingdestinatario = false;
                            this.loadingcomprador = false;
                            this.loadingproveedor = true;
                            break;
                        case '03':
                            this.loadingdestinatario = true;
                            this.loadingcomprador = true;
                            this.loadingproveedor = false;
                            break;
                        case '04':
                            this.loadingpublic = true;
                            this.loadingdestinatario = false;
                            this.loadingcomprador = false;
                            this.loadingproveedor = false;
                            break;
                        case '13':

                            break;
                        default:
                            this.loadingdestinatario = false;
                            this.loadingprivate = false;
                            this.loadingpublic = false;
                            this.loadingdestinatario = false;
                            this.loadingcomprador = false;
                            this.loadingproveedor = false;
                    }
                }
            }))
        })

        document.addEventListener('livewire:load', () => {
            Livewire.on('confirmaritemsguia', data => {
                swal.fire({
                    title: 'Desea agregar items encontrados a la guia"',
                    text: "Se encontraron items del comprobante de referencia, desea añadirlos a la guía ?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.confirmaradditemsguia(data);
                        // Livewire.emitTo('ventas::ventas.show-venta', 'deletecuota', data.id);
                    }
                })

            })
        })
    </script>
</div>
