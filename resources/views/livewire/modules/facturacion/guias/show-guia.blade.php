<div>
    <div class="w-full flex flex-col gap-8" x-data="loader" x-init="init">
        <form wire:submit.prevent="update" class="w-full flex flex-col gap-8">
            <x-form-card titulo="RESUMEN GUÍA REMISIÓN"
                subtitulo="Complete todos los campos para registrar una nueva venta.">
                <div class="w-full flex flex-col gap-2 bg-body p-3 rounded-md">
                    <div class="w-full grid grid-cols-1 xs:grid-cols-2 xl:grid-cols-3 gap-1">
                        <div class="w-full">
                            <x-label value="Tipo guía :" />
                            <x-disabled-text :text="$guia->seriecomprobante->typecomprobante->name" />
                        </div>

                        <div class="w-full xs:col-span-2 md:col-span-1">
                            <x-label value="Motivo traslado :" />
                            <x-select class="block w-full uppercase" id="motivotraslado_id"
                                wire:model.defer="guia.motivotraslado_id" x-ref="selectmotivo"
                                @change="getCodeMotivo($event.target)">
                                <x-slot name="options">
                                    @if (count($motivotraslados))
                                        @foreach ($motivotraslados as $item)
                                            <option value="{{ $item->id }}" data-code="{{ $item->code }}">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <span x-text="codemotivotraslado"></span>
                            <x-jet-input-error for="guia.motivotraslado_id" />
                        </div>

                        <div class="w-full">
                            <x-label value="Modalidad transporte :" />
                            <x-select class="block w-full uppercase" id="modalidadtransporte_id"
                                wire:model.defer="guia.modalidadtransporte_id" @change="getCodeModalidad($event.target)"
                                x-ref="selectmodalidad">
                                <x-slot name="options">
                                    @if (count($modalidadtransportes))
                                        @foreach ($modalidadtransportes as $item)
                                            <option value="{{ $item->id }}" data-code="{{ $item->code }}">
                                                {{ $item->name }} </option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <span x-text="codemodalidad"></span>
                            <x-jet-input-error for="guia.modalidadtransporte_id" />
                        </div>

                        <div class="w-full">
                            <x-label value="Fecha traslado :" />
                            <x-input class="block w-full" wire:model.defer="guia.datetraslado" type="date" />
                            <x-jet-input-error for="guia.datetraslado" />
                        </div>

                        <div class="w-full">
                            <x-label value="Peso bruto total (KILOGRAMO) :" />
                            <x-input class="block w-full" wire:model.defer="guia.peso" min="0" step="0.01"
                                type="number" />
                            <x-jet-input-error for="guia.peso" />
                        </div>

                        <div class="w-full">
                            <x-label value="Bultos / Paquetes :" />
                            <x-input class="block w-full" wire:model.defer="guia.packages" min="0" step="1"
                                type="number" />
                            <x-jet-input-error for="guia.packages" />
                        </div>

                        <div class="w-full xs:col-span-2 lg:col-span-1">
                            <x-label value="Descripción :" />
                            <x-input class="block w-full" wire:model.defer="guia.note"
                                placeholder="Descripcion, detalle de la guía (Opcional)..." />
                            <x-jet-input-error for="guia.note" />
                        </div>

                        @if ($guia->comprobante)
                            <div class="w-full">
                                <x-label value="Comprobante referencia emitido :" />
                                <x-disabled-text :text="$guia->comprobante->seriecompleta" />
                            </div>
                        @endif

                        <div class="w-full animate__animated animate__fadeInDown" x-show="vehiculosml">
                            <x-label value="Placa vehículo (Opcional) :" />
                            <x-input class="block w-full" wire:model.defer="guia.placavehiculo"
                                placeholder="Placa del vehículo de transporte..." />
                            <x-jet-input-error for="guia.placavehiculo" />
                        </div>
                    </div>

                    <div>
                        <div class="inline-block">
                            <x-label-check for="vehiculosml" class="uppercase">
                                <x-input wire:model.defer="vehiculosml" name="vehiculosml" type="checkbox"
                                    id="vehiculosml" @change="toggle" x-ref="checkvehiculosml" />
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

                    <div wire:loading.flex wire:target="update, getTransport, getDestinatario"
                        class="loading-overlay rounded hidden">
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
                            <x-input class="block w-full prevent numeric" wire:model.defer="guia.documentdestinatario"
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
                        <x-jet-input-error for="guia.documentdestinatario" />
                    </div>

                    <div class="w-full lg:col-span-2">
                        <x-label value="Nombres / Razón social :" />
                        <x-input class="block w-full" wire:model.defer="guia.namedestinatario"
                            placeholder="Nombres / razón social del destinatario" />
                        <x-jet-input-error for="guia.namedestinatario" />
                    </div>
                </div>
                <div wire:loading.flex wire:target="getDestinatario, update" class="loading-overlay rounded hidden">
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
                <div wire:loading.flex wire:target="getComprador, update" class="loading-overlay rounded hidden">
                    <x-loading-next />
                </div>
            </x-form-card>

            <x-form-card titulo="TRANSPORTISTA" x-show="loadingpublic" class="animate__animated animate__fadeInDown">
                <div class="w-full bg-body p-3 grid grid-cols-1 xs:grid-cols-2 xl:grid-cols-3 gap-1">
                    <div class="w-full xs:col-span-2 lg:col-span-1">
                        <x-label value="RUC :" />
                        <div class="w-full inline-flex">
                            <x-input class="block w-full prevent numeric" wire:model.defer="guia.ructransport"
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
                        <x-jet-input-error for="guia.ructransport" />
                    </div>

                    <div class="w-full xs:col-span-2 lg:col-span-2">
                        <x-label value="Razón social :" />
                        <x-input class="block w-full" wire:model.defer="guia.nametransport"
                            placeholder="Razón social del transportista" />
                        <x-jet-input-error for="guia.nametransport" />
                    </div>
                </div>
            </x-form-card>

            <x-form-card titulo="PROVEEDOR" x-show="loadingproveedor" class="animate__animated animate__fadeInDown">
                <div class="w-full bg-body p-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                    <div class="w-full">
                        <x-label value="RUC :" />
                        <div class="w-full inline-flex">
                            <x-input class="block w-full prevent numeric" wire:model.defer="guia.rucproveedor"
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
                        <x-jet-input-error for="guia.rucproveedor" />
                    </div>

                    <div class="w-full lg:col-span-2">
                        <x-label value="Razón social :" />
                        <x-input class="block w-full" wire:model.defer="guia.nameproveedor"
                            placeholder="Razón social del proveedor" />
                        <x-jet-input-error for="guia.nameproveedor" />
                    </div>
                </div>
                <div wire:loading.flex wire:target="getProveedor, update" class="loading-overlay rounded hidden">
                    <x-loading-next />
                </div>
            </x-form-card>

            @if ($guia->codesunat != '0')
                <x-form-card titulo="LUGAR DE EMISIÓN"
                    subtitulo="Complete todos los campos para registrar una nueva venta.">
                    <div class="w-full flex flex-col gap-2 bg-body p-3 rounded-md">
                        <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-1">
                            <div class="w-full">
                                <x-label value="Departamento :" />
                                <x-select class="block w-full" id="regionorigen_id"
                                    wire:model.lazy="regionorigen_id">
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
                                <x-select class="block w-full" id="provinciaorigen_id"
                                    wire:model.lazy="provinciaorigen_id">
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
                                <x-select class="block w-full" id="distritoorigen_id"
                                    wire:model.defer="distritoorigen_id">
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

                            <div class="w-full"
                                :class="codemotivotraslado == '04' ? 'lg:col-span-2' : 'lg:col-span-3'">
                                <x-label value="Direccion origen :" />
                                <x-input class="block w-full" wire:model.defer="guia.direccionorigen"
                                    placeholder="Dirección del punto de partida..." />
                                <x-jet-input-error for="guia.direccionorigen" />
                            </div>

                            <div class="w-full" x-show="codemotivotraslado == '04'">
                                <x-label value="Codigo anexo :" />
                                <x-input class="block w-full" wire:model.defer="guia.anexoorigen"
                                    placeholder="Anexo del punto de partida..." />
                                <x-jet-input-error for="guia.anexoorigen" />
                            </div>
                        </div>

                        <div wire:loading.flex wire:target="update" class="loading-overlay rounded hidden">
                            <x-loading-next />
                        </div>
                    </div>
                </x-form-card>

                <x-form-card titulo="LUGAR DE DESTINO"
                    subtitulo="Complete todos los campos para registrar una nueva venta.">
                    <div class="w-full flex flex-col gap-2 bg-body p-3 rounded-md">
                        <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-1">
                            <div class="w-full">
                                <x-label value="Departamento :" />
                                <x-select class="block w-full" id="regiondestino_id"
                                    wire:model.lazy="regiondestino_id">
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
                                <x-select class="block w-full" id="distritodestino_id"
                                    wire:model.defer="distritodestino_id">
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
                                <x-input class="block w-full" wire:model.defer="guia.direcciondestino"
                                    placeholder="Direccion del punto de llegada.." />
                                <x-jet-input-error for="guia.direcciondestino" />
                            </div>

                            <div class="w-full" x-show="codemotivotraslado == '04'">
                                <x-label value="Codigo anexo :" />
                                <x-input class="block w-full" wire:model.defer="guia.anexodestino"
                                    placeholder="Anexo del punto de llegada..." />
                                <x-jet-input-error for="guia.anexodestino" />
                            </div>
                        </div>
                        <div wire:loading.flex wire:target="update" class="loading-overlay rounded hidden">
                            <x-loading-next />
                        </div>
                    </div>
                </x-form-card>
            @endif

            @if ($guia->codesunat != '0')
                <div class="w-full flex justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">{{ __('ACTUALIZAR') }}</x-button>
                </div>
            @endif

            <div>
                <x-jet-input-error for="guia.documentdestinatario" />
                <x-jet-input-error for="guia.namedestinatario" />
                {{ print_r($errors->all()) }}
            </div>
        </form>

        <x-form-card titulo="CONDUCTORES VEHÍCULO" x-show="loadingprivate"
            class="animate__animated animate__fadeInDown">
            <div class="w-full relative rounded flex flex-wrap lg:flex-nowrap gap-3">
                @if ($guia->codesunat != '0')
                    <div class="w-full lg:w-96 lg:flex-shrink-0 bg-body p-3 rounded relative" x-data="{ loading: false }">
                        <form wire:submit.prevent="savedriver" class="w-full flex flex-col gap-2">
                            <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-1">
                                <div class="w-full">
                                    <x-label value="Documento :" />
                                    <div class="w-full inline-flex">
                                        <x-input class="block w-full prevent numeric"
                                            wire:model.defer="documentdriver" wire:keydown.enter="getDriver"
                                            minlength="0" maxlength="11" />
                                        <x-button-add class="px-2" wire:click="getDriver"
                                            wire:loading.attr="disable">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
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
                            <div class="w-full flex justify-end">
                                <x-button type="submit">{{ __('REGISTRAR') }}</x-button>
                            </div>
                        </form>

                        <div x-show="loading" wire:loading.flex wire:target="savedriver, getDriver"
                            class="loading-overlay rounded">
                            <x-loading-next />
                        </div>
                    </div>
                @endif
                <div class="w-full relative rounded">
                    @if (count($guia->transportdrivers))
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
                                                    <x-span-text text="Principal"
                                                        class="font-semibold leading-3 ml-1" />
                                                @else
                                                    <x-span-text text="Secundario"
                                                        class="font-semibold leading-3 ml-1" />
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
                    @endif
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="VEHÍCULOS TRANSPORTE" x-show="loadingprivate"
            class="animate__animated animate__fadeInDown">
            <div class="w-full relative rounded flex flex-wrap md:flex-nowrap gap-3">
                @if ($guia->codesunat != '0')
                    <div class="w-full md:w-96 md:flex-shrink-0 bg-body p-3 rounded relative" x-data="{ loading: false }">
                        <form wire:submit.prevent="savevehiculo" class="w-full flex flex-col gap-2">
                            <div class="w-full">
                                <x-label value="Placa vehículo :" />
                                <x-input class="block w-full" wire:model.defer="placa"
                                    placeholder="placa del del vehículo transporte..." />
                                <x-jet-input-error for="placa" />
                            </div>

                            <div class="w-full mt-3 flex justify-end">
                                <x-button type="submit">{{ __('AGREGAR') }}</x-button>
                            </div>
                        </form>

                        <div x-show="loading" wire:loading.flex wire:target="savevehiculo"
                            class="loading-overlay rounded">
                            <x-loading-next />
                        </div>
                    </div>
                @endif
                <div class="w-full relative rounded">
                    @if (count($guia->transportvehiculos))
                        <div class="w-full flex flex-wrap items-start gap-2">
                            @foreach ($guia->transportvehiculos as $item)
                                <x-minicard :title="'PLACA: ' . $item->placa" :content="$item->principal == 1 ? 'Principal' : 'Secundario'" size="md">
                                    @if ($guia->codesunat != '0')
                                        <x-slot name="buttons">
                                            <x-button-delete
                                                wire:click="$emit('guia.confirmDeletevehiculo',{{ $item }})"
                                                wire:loading.attr="disabled" />
                                        </x-slot>
                                    @endif
                                </x-minicard>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="RESUMEN PRODUCTOS">
            <div class="w-full">
                @if (count($guia->tvitems))
                    <div class="flex gap-2 flex-wrap justify-start">
                        @foreach ($guia->tvitems as $item)
                            <x-card-producto :name="$item->producto->name">
                                <x-span-text :text="'CANT: ' .
                                    formatDecimalOrInteger($item->cantidad) .
                                    ' ' .
                                    $item->producto->unit->name" class="leading-3 mt-1" />
                                @if (count($item->itemseries) == 1)
                                    <x-span-text :text="$item->itemseries()->first()->serie->serie" class="leading-3" />
                                @endif

                                @if (count($item->itemseries) > 1)
                                    <div x-data="{ showForm: false }" class="mt-1">
                                        <x-button @click="showForm = !showForm" class="whitespace-nowrap">
                                            {{ __('VER SERIES') }}
                                        </x-button>
                                        <div x-show="showForm"
                                            x-transition:enter="transition ease-out duration-300 transform"
                                            x-transition:enter-start="opacity-0 translate-y-[-10%]"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-300 transform"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 translate-y-[-10%]"
                                            class="block w-full rounded mt-1">
                                            <div class="w-full flex flex-wrap gap-1">
                                                @foreach ($item->itemseries as $serie)
                                                    <x-span-text :text="$serie->serie->serie" class="leading-3" />
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </x-card-producto>
                        @endforeach
                    </div>
                @endif
            </div>
        </x-form-card>
    </div>

    <script>
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
                    const selectmotivo = this.$refs.selectmotivo;
                    const selectmodalidad = this.$refs.selectmodalidad;
                    this.vehiculosml = this.$refs.checkvehiculosml.checked;

                    this.getCodeMotivo(selectmotivo);
                    this.getCodeModalidad(selectmodalidad);
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

        document.addEventListener("livewire:load", () => {
            Livewire.on('guia.confirmDeletevehiculo', data => {
                swal.fire({
                    title: 'Eliminar registro de vehículo de transporte con placa, ' + data.placa,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.deletevehiculo(data.id);
                    }
                })
            });

            Livewire.on('guia.confirmDeletedriver', data => {
                swal.fire({
                    title: 'Eliminar registro del conductor con documento, ' + data.document,
                    text: "Se eliminará un registro de la base de datos",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.deletedriver(data.id);
                    }
                })
            });
        })
    </script>
</div>
