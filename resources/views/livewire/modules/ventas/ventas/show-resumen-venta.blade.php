<div>
    <div class="w-full flex flex-col gap-8" x-data="{ loading: false }">
        <x-form-card titulo="GENERAR NUEVA VENTA" subtitulo="Complete todos los campos para registrar una nueva venta.">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2 bg-body p-3 rounded-md">
                <div class="w-full flex flex-col gap-1" x-data="payment">

                    <div class="w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-1">
                        <div class="w-full">
                            <x-label value="Vincular cotización :" />
                            <div id="parentventacotizacion_id">
                                <x-select class="block w-full" id="ventacotizacion_id" wire:model.defer="cotizacion_id"
                                    data-minimum-results-for-search="3">
                                    <x-slot name="options">
                                        {{-- @if (count($categories))
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif --}}
                                    </x-slot>
                                </x-select>
                            </div>
                            <x-jet-input-error for="cotizacion_id" />
                        </div>
                        <div class="w-full">
                            <x-label value="Moneda :" />
                            <div id="parentventamoneda_id">
                                <x-select class="block w-full" id="ventamoneda_id" wire:model.lazy="moneda_id">
                                    <x-slot name="options">
                                        @if (count($monedas))
                                            @foreach ($monedas as $item)
                                                <option value="{{ $item->id }}">{{ $item->currency }}</option>
                                            @endforeach
                                        @endif
                                    </x-slot>
                                </x-select>
                            </div>
                            <x-jet-input-error for="moneda_id" />
                        </div>
                    </div>

                    <div class="w-full flex flex-wrap lg:flex-nowrap xl:flex-wrap gap-1">
                        <div class="w-full lg:w-1/3 xl:w-full">
                            <x-label value="DNI / RUC :" />
                            <div class="w-full inline-flex">
                                <x-input class="block w-full prevent numeric" wire:model.defer="document"
                                    wire:keydown.enter="getClient" minlength="0" maxlength="11" />
                                <x-button-add class="px-2" wire:click="getClient" wire:target="getClient"
                                    wire:loading.attr="disable">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8" />
                                        <path d="m21 21-4.3-4.3" />
                                    </svg>
                                </x-button-add>
                            </div>
                            <x-jet-input-error for="document" />
                        </div>
                        <div class="w-full lg:w-2/3 xl:w-full">
                            <x-label value="Cliente / Razón Social :" />
                            <x-input class="block w-full" wire:model.defer="name"
                                placeholder="Nombres / razón social del cliente" />
                            <x-jet-input-error for="name" />
                        </div>
                    </div>

                    <div class="w-full flex flex-wrap lg:flex-nowrap xl:flex-wrap gap-1">
                        <div class="w-full lg:w-full xl:w-full">
                            <x-label value="Dirección :" />
                            <x-input class="block w-full" wire:model.defer="direccion"
                                placeholder="Dirección del cliente" />
                            <x-jet-input-error for="direccion" />
                        </div>

                        @if ($empresa->uselistprice)
                            @if ($pricetypeasigned)
                                <div class="w-full lg:w-1/3 xl:w-full">
                                    <x-label value="Lista precio asignado :" />
                                    <x-disabled-text :text="$pricetypeasigned ?? ' - '" />
                                </div>
                            @endif
                        @endif
                    </div>

                    @if ($mensaje)
                        {{-- <div class="w-full mt-2">
                            <x-label value="Mensaje :" />
                            <x-input class="block w-full" wire:model.defer="mensaje" disabled readonly />
                            <x-jet-input-error for="mensaje" />
                        </div> --}}
                    @endif

                    <div class="w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-1">
                        <div class="w-full">
                            <x-label value="Tipo comprobante :" />
                            <div id="parentventatypecomprobante_id">
                                <x-select class="block w-full" id="ventatypecomprobante_id"
                                    wire:model.defer="typecomprobante_id">
                                    <x-slot name="options">
                                        @if (count($typecomprobantes))
                                            @foreach ($typecomprobantes as $item)
                                                <option value="{{ $item->id }}">{{ $item->descripcion }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </x-slot>
                                </x-select>
                            </div>
                            <x-jet-input-error for="typecomprobante_id" />
                        </div>

                        @if (Module::isEnabled('Facturacion'))
                            <div class="w-full">
                                <x-label value="Tipo pago :" />
                                <div id="parentventatypepayment_id">
                                    <x-select class="block w-full" id="ventatypepayment_id"
                                        wire:model.lazy="typepayment_id" x-ref="selectpayment"
                                        @change="getTipopago($event.target)">
                                        <x-slot name="options">
                                            @if (count($typepayments))
                                                @foreach ($typepayments as $item)
                                                    <option value="{{ $item->id }}"
                                                        data-payment="{{ $item->paycuotas }}">
                                                        {{ $item->name }} </option>
                                                @endforeach
                                            @endif
                                        </x-slot>
                                    </x-select>
                                </div>
                                <span x-text="formapago"></span>
                                <x-jet-input-error for="typepayment_id" />
                            </div>
                        @endif
                    </div>

                    <div class="w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-1">

                        <div class="w-full lg:w-full animate__animated animate__fadeInDown" x-show="paymentcuotas">
                            <x-label value="Pago actual:" />
                            <x-input class="block w-full prevent" type="number" min="0" step="0.100"
                                wire:model.lazy="paymentactual" wire:key="paymentactual" />
                            <x-jet-input-error for="paymentactual" />
                        </div>

                        <div class="w-full lg:w-full animate__animated animate__fadeInDown" x-show="paymentcuotas">
                            <x-label value="Incrementar venta (%):" />
                            <x-input class="block w-full prevent" type="number" min="0" step="0.10"
                                wire:model.lazy="increment" wire:key="increment" />
                            <x-jet-input-error for="increment" />
                        </div>

                        <div class="w-full lg:w-full animate__animated animate__fadeInDown" x-show="paymentcuotas">
                            <x-label value="Cuotas :" />
                            <div class="w-full inline-flex">
                                <x-input class="block w-full" type="number" min="1" step="1"
                                    max="12" wire:model.defer="countcuotas" wire:key="countcuotas" />
                            </div>
                            <x-jet-input-error for="countcuotas" />
                        </div>

                        <div class="w-full animate__animated animate__fadeInDown" x-show="!paymentcuotas">
                            <x-label value="Método pago :" />
                            <div id="parentventamethodpayment_id">
                                <x-select class="block w-full" id="ventamethodpayment_id"
                                    wire:model.lazy="methodpayment_id">
                                    <x-slot name="options">
                                        @if (count($methodpayments))
                                            @foreach ($methodpayments as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </x-slot>
                                </x-select>
                            </div>
                            <x-jet-input-error for="methodpayment_id" />
                        </div>

                        @if (count($accounts))
                            <div class="w-full animate__animated animate__fadeInDown" x-show="!paymentcuotas">
                                <x-label value="Cuenta pago :" />
                                <div id="parentventacuenta_id">
                                    <x-select class="block w-full" id="ventacuenta_id" wire:model.lazy="cuenta_id">
                                        <x-slot name="options">
                                            @if (count($accounts))
                                                @foreach ($accounts as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->account }}
                                                        ({{ $item->descripcion }})
                                                    </option>
                                                @endforeach
                                            @endif
                                        </x-slot>
                                    </x-select>
                                </div>
                                <x-jet-input-error for="cuenta_id" />
                            </div>
                        @endif

                        <div class="w-full animate__animated animate__fadeInDown" x-show="!paymentcuotas">
                            <x-label value="Detalle pago :" />
                            <x-input class="block w-full" wire:model.defer="detallepago" />
                            <x-jet-input-error for="detallepago" />
                        </div>
                    </div>

                    <div class="block relative" x-data="loader">
                        <x-label-check for="incluyeigv">
                            <x-input wire:model.lazy="incluyeigv" name="incluyeigv" value="1" type="checkbox"
                                id="incluyeigv" />
                            INCLUIR IGV
                        </x-label-check>
                        <x-jet-input-error for="incluyeigv" />

                        @if (Module::isEnabled('Facturacion'))
                            <x-label-check for="incluyeguia">
                                <x-input @change="toggleguia" name="incluyeguia" type="checkbox" id="incluyeguia" />
                                GENERAR GUÍA REMISIÓN
                            </x-label-check>

                            <div x-show="incluyeguia" class="block w-full animate__animated animate__fadeInDown">

                                <x-title-next titulo="MENÚ GUIA REMISION" class="my-3" />

                                <div class="w-full grid grid-cols-1 xs:grid-cols-2 xl:grid-cols-1 gap-1">
                                    <div class="w-full xs:col-span-2 lg:col-span-1">
                                        <x-label value="Motivo traslado :" />
                                        <x-select class="block w-full uppercase" id="motivotraslado_id"
                                            wire:model.defer="motivotraslado_id"
                                            @change="getCodeMotivo($event.target)">
                                            <x-slot name="options">
                                                @if (count($motivotraslados))
                                                    @foreach ($motivotraslados as $item)
                                                        <option value="{{ $item->id }}"
                                                            data-code="{{ $item->code }}">
                                                            {{ $item->name }} </option>
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
                                            wire:model.defer="modalidadtransporte_id"
                                            @change="getCodeModalidad($event.target)">
                                            <x-slot name="options">
                                                @if (count($modalidadtransportes))
                                                    @foreach ($modalidadtransportes as $item)
                                                        <option value="{{ $item->id }}"
                                                            data-code="{{ $item->code }}">{{ $item->name }}
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
                                        <x-input class="block w-full" wire:model.defer="datetraslado"
                                            type="date" />
                                        <x-jet-input-error for="datetraslado" />
                                    </div>

                                    <div class="w-full">
                                        <x-label value="Bultos / Paquetes :" />
                                        <x-input class="block w-full" wire:model.defer="packages" min="0"
                                            step="1" type="number" />
                                        <x-jet-input-error for="packages" />
                                    </div>

                                    <div class="w-full">
                                        <x-label value="Peso bruto total (KILOGRAMO) :" />
                                        <x-input class="block w-full" wire:model.defer="peso" min="0"
                                            step="0.01" type="number" />
                                        <x-jet-input-error for="peso" />
                                    </div>

                                    <div class="w-full xs:col-span-2 lg:col-span-1">
                                        <x-label value="Descripción :" />
                                        <x-input class="block w-full" wire:model.defer="note"
                                            placeholder="Descripcion, detalle de la guía (Opcional)..." />
                                        <x-jet-input-error for="note" />
                                    </div>

                                    <div class="w-full animate__animated animate__fadeInDown" x-show="vehiculosml">
                                        <x-label value="Placa vehículo (Opcional) :" />
                                        <x-input class="block w-full" wire:model.defer="placavehiculo"
                                            placeholder="Placa del vehículo de transporte..." />
                                        <x-jet-input-error for="placavehiculo" />
                                    </div>
                                </div>

                                <div class="w-full">
                                    <x-label-check for="vehiculosml" class="mt-2">
                                        <x-input wire:model.defer="vehiculosml" name="vehiculosml" type="checkbox"
                                            id="vehiculosml" @change="toggle" />
                                        TRASLADO EN VEHÍCULOS DE CATEGORÍA M1 O L
                                    </x-label-check>
                                </div>

                                <div class="w-full animate__animated animate__fadeInDown"
                                    x-show="loadingdestinatario">
                                    <x-title-next titulo="DATOS DEL DESTINATARIO" class="my-3" />

                                    <div class="w-full grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-1 gap-1">
                                        <div class="w-full">
                                            <x-label value="DNI / RUC :" />
                                            <div class="w-full inline-flex">
                                                <x-input class="block w-full prevent numeric"
                                                    wire:model.defer="documentdestinatario"
                                                    wire:keydown.enter="getDestinatario" minlength="0"
                                                    maxlength="11" />
                                                <x-button-add class="px-2" wire:click="getDestinatario"
                                                    wire:target="getDestinatario" wire:loading.attr="disable">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="3" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <circle cx="11" cy="11" r="8" />
                                                        <path d="m21 21-4.3-4.3" />
                                                    </svg>
                                                </x-button-add>
                                            </div>
                                            <x-jet-input-error for="documentdestinatario" />
                                        </div>

                                        <div class="w-full lg:col-span-2 xl:col-span-1">
                                            <x-label value="Nombres :" />
                                            <x-input class="block w-full" wire:model.defer="namedestinatario"
                                                placeholder="Nombres / razón social del destinatario" />
                                            <x-jet-input-error for="namedestinatario" />
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full animate__animated animate__slideInDown" x-show="loadingpublic">
                                    <x-title-next titulo="DATOS DEL TRANSPORTISTA" class="my-3 " />

                                    <div class="w-full grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-1 gap-1">
                                        <div class="w-full">
                                            <x-label value="RUC transportista :" />
                                            <div class="w-full inline-flex">
                                                <x-input class="block w-full prevent numeric"
                                                    wire:model.defer="ructransport" wire:keydown.enter="getTransport"
                                                    minlength="0" maxlength="11" />
                                                <x-button-add class="px-2" wire:click="getTransport"
                                                    wire:loading.attr="disable">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="3" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <circle cx="11" cy="11" r="8" />
                                                        <path d="m21 21-4.3-4.3" />
                                                    </svg>
                                                </x-button-add>
                                            </div>
                                            <x-jet-input-error for="ructransport" />
                                        </div>

                                        <div class="w-full lg:col-span-2 xl:col-span-1">
                                            <x-label value="Razón social transportista :" />
                                            <x-input class="block w-full" wire:model.defer="nametransport"
                                                placeholder="Razón social del transportista" />
                                            <x-jet-input-error for="nametransport" />
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full animate__animated animate__fadeInDown" x-show="loadingprivate">
                                    <x-title-next titulo="DATOS DEL CONDUCTOR / VEHÍCULO" class="my-3" />

                                    <div
                                        class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-1 gap-1">
                                        <div class="w-full">
                                            <x-label value="Documento conductor :" />
                                            <div class="w-full inline-flex">
                                                <x-input class="block w-full prevent numeric"
                                                    wire:model.defer="documentdriver" wire:keydown.enter="getDriver"
                                                    minlength="0" maxlength="11" />
                                                <x-button-add class="px-2" wire:click="getDriver"
                                                    wire:loading.attr="disable">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="3" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <circle cx="11" cy="11" r="8" />
                                                        <path d="m21 21-4.3-4.3" />
                                                    </svg>
                                                </x-button-add>
                                            </div>
                                            <x-jet-input-error for="documentdriver" />
                                        </div>

                                        <div class="w-full">
                                            <x-label value="Nombres conductor :" />
                                            <x-input class="block w-full" wire:model.defer="namedriver"
                                                placeholder="Nombres del conductor" />
                                            <x-jet-input-error for="namedriver" />
                                        </div>

                                        <div class="w-full">
                                            <x-label value="Apellidos :" />
                                            <x-input class="block w-full" wire:model.defer="lastname"
                                                placeholder="Apellidos del conductor..." />
                                            <x-jet-input-error for="lastname" />
                                        </div>

                                        <div class="w-full">
                                            <x-label value="Licencia conducir:" />
                                            <x-input class="block w-full" wire:model.defer="licencia"
                                                placeholder="Licencia del conductor del vehículo..." />
                                            <x-jet-input-error for="licencia" />
                                        </div>

                                        <div class="w-full">
                                            <x-label value="Placa vehículo :" />
                                            <x-input class="block w-full" wire:model.defer="placa"
                                                placeholder="placa del vehículo..." />
                                            <x-jet-input-error for="placa" />
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full">
                                    <x-title-next titulo="LUGAR DE EMISIÓN" class="my-3" />

                                    <div
                                        class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-1 gap-1">
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

                                        <div class="w-full lg:col-span-3 xl:col-span-1">
                                            <x-label value="Direccion origen :" />
                                            <x-input class="block w-full" wire:model.defer="direccionorigen"
                                                placeholder="Dirección del punto de partida..." />
                                            <x-jet-input-error for="direccionorigen" />
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full">
                                    <x-title-next titulo="LUGAR DE DESTINO" class="my-3" />

                                    <div
                                        class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-1 gap-1">
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

                                        <div class="w-full lg:col-span-3 xl:col-span-1">
                                            <x-label value="Direccion destino :" />
                                            <x-input class="block w-full" wire:model.defer="direcciondestino"
                                                placeholder="Direccion del punto de llegada.." />
                                            <x-jet-input-error for="direcciondestino" />
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full flex flex-col gap-1 mt-1">
                                    <x-jet-input-error for="serieguiaremision_id" />
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="w-full flex flex-col gap-1">
                        <x-jet-input-error for="typepayment.id" />
                        <x-jet-input-error for="items" />
                    </div>

                    @if ($errors->any())
                        <div class="w-full flex flex-col gap-1">
                            @foreach ($errors->keys() as $key)
                                <x-jet-input-error :for="$key" />
                            @endforeach
                        </div>
                    @endif

                    <div class="w-full flex mt-2 justify-end">
                        <x-button type="submit" wire:loading.attr="disabled">
                            {{ __('REGISTRAR') }}
                        </x-button>
                    </div>
                </div>
            </form>
            <div x-show="loading" wire:loading.flex
                wire:target="save, typepayment_id, getClient, getTransport, getDestinatario, getDriver"
                class="loading-overlay rounded z-50">
                <x-loading-next />
            </div>
        </x-form-card>

        <x-form-card titulo="RESUMEN DE VENTA">

            <div x-show="loading" wire:loading wire:loading.flex
                wire:target="save, setTotal, increment, paymentactual, incluyeigv, updategratis, deleteitem, deleteserie"
                class="loading-overlay rounded">
                <x-loading-next />
            </div>

            <div class="w-full text-colortitleform bg-body p-3 rounded-md">
                <p class="text-[10px]">
                    TOTAL EXONERADO : {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($exonerado, 3, '.', ', ') }}</span>
                </p>

                <p class="text-[10px]">TOTAL GRAVADO : {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($gravado, 3, '.', ', ') }}</span>
                </p>

                <p class="text-[10px]">
                    TOTAL IGV : {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($igv, 3, '.', ', ') }}</span>
                </p>

                <p class="text-[10px]">TOTAL GRATUITOS : {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($gratuito, 3, '.', ', ') }}</span>
                </p>

                <p class="text-[10px]">TOTAL DESCUENTOS : {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($descuentos, 3, '.', ', ') }}</span>
                </p>

                <p class="text-[10px]">
                    @if ($increment)
                        IMPORTE TOTAL + INCREMENTO({{ $moneda->simbolo }}
                        {{ number_format($amountincrement, 2, '.', ', ') }}) :
                    @else
                        IMPORTE TOTAL :
                    @endif
                    {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($total, 3, '.', ', ') }}</span>
                </p>

                <p class="text-[10px]">SALDO PENDIENTE
                    @if ($increment)
                        ({{ number_format($total - $amountincrement, 3, '.', ', ') }}
                        + {{ formatDecimalOrInteger($increment) }}%)
                    @endif
                    : {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($total - $paymentactual, 3, '.', ', ') }}</span>
                </p>
                {{-- <p>AI--{{ $amountincrement }}</p>
                <p>IINC--{{ $increment }}</p> --}}
            </div>
        </x-form-card>

        {{-- @if (count($carrito))
            <x-form-card titulo="CARRITO">

                <small
                    class="text-white font-semibold absolute top-2 left-[70px] flex items-center justify-center w-5 h-5 p-0.5 leading-3 bg-orange-500 rounded-full text-[10px] animate-bounce">
                    {{ count($carrito) }}</small>

                <div class="flex gap-2 flex-wrap justify-start">
                    @foreach ($carrito as $item)
                        <div
                            class="w-full bg-body border border-borderminicard flex flex-col justify-between lg:w-60 xl:w-full group rounded shadow shadow-shadowminicard p-1 text-xs relative hover:shadow-md hover:shadow-shadowminicard cursor-pointer">
                            <div class="w-full">
                                <div class="w-full inline-flex gap-2 text-colorminicard">
                                    <h1 class="w-full text-[10px] leading-3 text-left mt-1">
                                        {{ $item->producto->name }}</h1>
                                    <h1 class="whitespace-nowrap text-right text-[10px] text-xs leading-3">
                                        {{ $item->moneda->simbolo }}
                                        {{ number_format($item->total, 2) }}
                                        <small class="text-[7px]">{{ $item->moneda->currency }}</small>
                                    </h1>
                                </div>

                                <div class="w-full flex flex-wrap gap-1 mt-2">
                                    <x-span-text :text="'P. UNIT : ' .
                                        $item->moneda->simbolo .
                                        number_format($item->price, 2, '.', ', ')" class="leading-3" />

                                    <x-span-text :text="\App\Helpers\FormatoPersonalizado::getValue($item->cantidad) .
                                        ' ' .
                                        $item->producto->unit->name" class="leading-3" />

                                    <x-span-text :text="$item->almacen->name" class="leading-3" />

                                    @if (count($item->carshoopseries) == 1)
                                        <x-span-text :text="'SERIE : ' . $item->carshoopseries()->first()->serie->serie" class="leading-3" />
                                    @endif
                                </div>

                                @if (count($item->carshoopseries) > 1)
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
                                                @foreach ($item->carshoopseries as $itemserie)
                                                    <span
                                                        class="inline-flex items-center gap-1 text-[10px] bg-fondospancardproduct text-textspancardproduct p-1 rounded-lg">
                                                        {{ $itemserie->serie->serie }}
                                                        <x-button-delete
                                                            wire:click="$emit('ventas.confirmDeleteSerie',{{ $itemserie }})"
                                                            wire:loading.attr="disabled" />
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="w-full flex items-end gap-2 justify-between mt-2">
                                <div>
                                    <x-label-check textSize="[9px]" for="gratuito_{{ $item->id }}">
                                        <x-input wire:change="updategratis({{ $item->id }})" value="1"
                                            type="checkbox" id="gratuito_{{ $item->id }}" :checked="$item->gratuito == 1" />
                                        GRATUITO
                                    </x-label-check>
                                </div>
                                <x-button-delete
                                    wire:click="$emit('ventas.confirmDeleteItemCart', {{ $item->id }})"
                                    wire:loading.attr="disabled" />
                            </div>
                        </div>
                    @endforeach
                </div>

                <div x-show="loading" wire:loading wire:loading.flex
                    wire:target="save, setTotal, deleteitem, deleteserie, updategratis"
                    class="loading-overlay rounded">
                    <x-loading-next />
                </div>
            </x-form-card>
        @endif --}}


        @if (count(getCarrito()) > 0)
            <x-form-card titulo="CARRITO">

                <small
                    class="text-white font-semibold absolute top-2 left-[70px] flex items-center justify-center w-5 h-5 p-0.5 leading-3 bg-orange-500 rounded-full text-[10px] animate-bounce">
                    {{ count(getCarrito()) }}</small>

                <div class="flex gap-2 flex-wrap justify-start">
                    @foreach ($carrito as $item)
                        {{-- {{ print_r($item) }} --}}
                        <div
                            class="w-full bg-body border border-borderminicard flex flex-col justify-between sm:w-64 xl:w-full group rounded shadow shadow-shadowminicard p-1 text-xs relative hover:shadow-md hover:shadow-shadowminicard cursor-pointer">
                            <div class="w-full">
                                <div class="w-full flex flex-col flex-wrap gap-2 text-colorminicard">
                                    <h1 class="w-full text-[10px] leading-3 text-left mt-1">
                                        {{ $item->producto }}</h1>
                                    <h1 class="whitespace-nowrap text-right text-[10px] text-xs leading-3">
                                        {{ $item->simbolo }}
                                        {{ number_format($item->importe, 2, '.', ', ') }}
                                        <small class="text-[7px]">{{ $item->moneda }}</small>
                                    </h1>
                                </div>
                                <div class="w-full flex flex-wrap gap-1 mt-2">
                                    <x-span-text :text="'P. UNIT : ' .
                                        $item->simbolo .
                                        number_format($item->price, 2, '.', ', ')" class="leading-3" />

                                    <x-span-text :text="formatDecimalOrInteger($item->cantidad) . ' ' . $item->unit" class="leading-3" />

                                    <x-span-text :text="$item->almacen" class="leading-3" />

                                    @if (count($item->series) == 1)
                                        <x-span-text :text="'SERIE : ' . $item->series[0]->serie" class="leading-3" />
                                    @endif
                                </div>

                                @if (count($item->series) > 1)
                                    <div x-data="{ showForm: false }" class="mt-1 relative">
                                        <x-button @click="showForm = !showForm" class="whitespace-nowrap">
                                            {{ __('VER SERIES') }}
                                        </x-button>
                                        <div x-show="showForm"
                                            class="block w-full rounded mt-1 relative animate__animated animate__fadeInDown">
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
                            </div>
                            <div class="w-full flex items-end gap-2 justify-between mt-2">
                                <div class="flex just items-end gap-1">
                                    <x-label-check textSize="[9px]" for="gratuito_{{ $item->id }}">
                                        <x-input wire:change="updategratis({{ $item->id }})" value="1"
                                            type="checkbox" id="gratuito_{{ $item->id }}" :checked="$item->gratuito == 1" />
                                        GRATUITO
                                    </x-label-check>
                                    <x-span-text :text="$item->sucursal ?? 'NOMBRE DE SUCURSAL DEL CARRITO ASIGNADO'" class="leading-3 truncate w-28" />
                                </div>
                                <x-button-delete wire:click="delete('{{ $item->id }}')"
                                    wire:loading.attr="disabled" />
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="w-full mt-2 flex justify-end">
                    <x-button-secondary wire:loading.attr="disabled" wire:click="deletecarrito">ELIMINAR
                        TODO</x-button-secondary>
                </div>

                <div x-show="loading" wire:loading wire:loading.flex wire:target="save, render"
                    class="loading-overlay rounded">
                    <x-loading-next />
                </div>
            </x-form-card>
        @endif
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('loader', () => ({
                incluyeguia: @entangle('incluyeguia').defer,
                vehiculosml: false,
                loadingprivate: false,
                loadingpublic: false,
                loadingdestinatario: false,
                codemotivotraslado: '',
                codemodalidad: '',

                init() {
                    console.log("Show resumen venta");
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
                toggleguia() {
                    this.incluyeguia = !this.incluyeguia;
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
                            this.loadingdestinatario = false;
                            break;
                        case '03':
                            this.loadingdestinatario = true;
                            break;
                        default:
                            this.loadingdestinatario = false;
                            this.loadingprivate = false;
                            this.loadingpublic = false;
                    }
                }
            }));

            Alpine.data('payment', () => ({
                paymentcuotas: false,
                formapago: '',

                init() {
                    const selectpayment = this.$refs.selectpayment;
                    if (selectpayment) {
                        this.getTipopago(selectpayment);
                        console.log('Encontrado');
                    }

                },
                getTipopago(target) {
                    let datapayment = target.options[target.selectedIndex].getAttribute(
                        'data-payment');
                    this.formapago = target.options[target.selectedIndex].text;
                    this.selectedFormaPago(datapayment);
                },
                selectedFormaPago(value) {
                    switch (value) {
                        case '0':
                            this.paymentcuotas = false;
                            break;
                        case '1':
                            this.paymentcuotas = true;
                            break;
                        default:
                            this.paymentcuotas = false;
                            this.formapago = '';
                    }
                },
            }))
        })

        // renderselect2();

        // $("#ventamoneda_id").on("change", (e) => {
        //     deshabilitarSelects();
        //     @this.moneda_id = e.target.value;
        // });
        // $("#ventatypepayment_id").on("change", (e) => {
        //     deshabilitarSelects();
        //     @this.typepayment_id = e.target.value;
        // });

        // $("#ventamethodpayment_id").on("change", (e) => {
        //     deshabilitarSelects();
        //     @this.methodpayment_id = e.target.value;
        // });

        // $("#ventacuenta_id").on("change", (e) => {
        //     deshabilitarSelects();
        //     @this.cuenta_id = e.target.value;
        // });

        // $("#ventatypecomprobante_id").on("change", (e) => {
        //     deshabilitarSelects();
        //     @this.typecomprobante_id = e.target.value;
        // });

        // $("#ventacotizacion_id").on("change", (e) => {
        //     deshabilitarSelects();
        //     @this.cotizacion_id = e.target.value;
        // });

        // $("#ventamoneda_id").on("change", (e) => {
        //     deshabilitarSelects();
        //     @this.setMoneda(e.target.value);
        //     @this.moneda_id = e.target.value;
        // });


        // document.addEventListener('render-show-resumen-venta', () => {
        //     renderselect2();
        //     $('#ventamethodpayment_id').on("change", (e) => {
        //         deshabilitarSelects();
        //         @this.methodpayment_id = e.target.value;
        //     });
        // });

        window.addEventListener('show-resumen-venta', () => {
            @this.render();
            @this.setTotal();
        });

        document.addEventListener("livewire:load", () => {
            // Livewire.hook('message.processed', (message, component) => {
            //     message.updateQueue.some(update => {
            //         console.log("Type : " + update.type, " Event : " + update.method);
            //         if (update.method == "addtocar") {
            //             // @this.setTotal();
            //             // console.log(update.method);
            //         }
            //     })
            // });


            Livewire.on('ventas.confirmDeleteItemCart', data => {
                swal.fire({
                    title: 'Desea eliminar el producto del carrito de compras ?',
                    text: "Se eliminará un registro de pago de la base de datos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.deleteitem(data);
                        // Livewire.emitTo('ventas::ventas.show-resumen-venta', 'deleteitem', data);
                    }
                })
            });

            Livewire.on('ventas.confirmDeleteSerie', data => {
                swal.fire({
                    title: 'Desea quitar la serie ' + data.serie.serie +
                        ' del carrito de compras ?',
                    text: "Se eliminará un registro de pago de la base de datos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.deleteserie(data.id);
                        // Livewire.emitTo('ventas::ventas.show-resumen-venta', 'deleteserie', data.id);
                    }
                })
            });

            // function renderselect2() {
            //     $('#ventamoneda_id, #ventatypepayment_id, #ventacotizacion_id, #ventamethodpayment_id, #ventacuenta_id, #ventatypecomprobante_id')
            //         .select2().on('select2:open', function(e) {
            //             const evt = "scroll.select2";
            //             $(e.target).parents().off(evt);
            //             $(window).off(evt);
            //         });

            //     $("#ventacuenta_id").on("change", (e) => {
            //         deshabilitarSelects();
            //         @this.cuenta_id = e.target.value;
            //     });
            // }

            // function deshabilitarSelects() {
            //     $('#ventamoneda_id, #ventatypepayment_id, #ventacotizacion_id, #ventamethodpayment_id, #ventacuenta_id, #ventatypecomprobante_id')
            //         .attr('disabled', true);
            // }
        })
    </script>
</div>
