<div>
    <div wire:loading.flex class="fixed loading-overlay rounded hidden">
        <x-loading-next />
    </div>

    <div class="w-full flex flex-col gap-8" x-data="loader">
        <x-form-card titulo="GENERAR NUEVA VENTA" subtitulo="Complete todos los campos para registrar una nueva venta.">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2 bg-body p-3 rounded-md">
                <div class="w-full flex flex-col gap-1">

                    <div class="w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-1">
                        {{-- <div class="w-full">
                            <x-label value="Vincular cotización :" />
                            <div id="parentctzc" class="relative" x-init="selectCotizacion" wire:ignore>
                                <x-select class="block w-full" id="ctzc" x-ref="selectcot"
                                    data-minimum-results-for-search="3">
                                    <x-slot name="options">
                                    </x-slot>
                                </x-select>
                                <x-icon-select />
                            </div>
                            <x-jet-input-error for="cotizacion_id" />
                        </div> --}}
                        @if (count($monedas) > 1)
                            <div class="w-full">
                                <x-label value="Moneda :" />
                                <div id="parentmnd" class="relative" x-init="selectMoneda">
                                    <x-select class="block w-full" x-ref="selectmoneda" id="mnd">
                                        <x-slot name="options">
                                            @if (count($monedas))
                                                @foreach ($monedas as $item)
                                                    <option value="{{ $item->id }}">{{ $item->currency }}</option>
                                                @endforeach
                                            @endif
                                        </x-slot>
                                    </x-select>
                                    <x-icon-select />
                                </div>
                                <x-jet-input-error for="moneda_id" />
                            </div>
                        @endif
                    </div>

                    {{-- BUSCAR GRE Y OBTENER SUS ITEMS --}}
                    @can('admin.ventas.create.guias')
                        @if ($sincronizegre)
                            <div class="w-full">
                                <x-label value="Buscar GRE :" />
                                <div class="w-full inline-flex relative">
                                    <x-disabled-text :text="$searchgre" class="w-full block" />
                                    <x-button-close-modal
                                        class="hover:animate-none !text-red-500 hover:!bg-transparent focus:!bg-transparent hover:!ring-0 focus:!ring-0 absolute right-0 top-1"
                                        wire:click="desvinculargre" wire:loading.attr="disabled" />
                                </div>
                                <x-jet-input-error for="searchgre" />
                            </div>
                        @else
                            <div x-show="!incluyeguia" class="w-full">
                                <x-label value="Buscar GRE :" />
                                <div class="w-full inline-flex">
                                    <x-input class="block w-full" wire:model.defer="searchgre" wire:keydown.enter="getGRE"
                                        minlength="0" maxlength="13" onkeydown="disabledEnter(event)" />
                                    <x-button-add class="px-2" wire:click="getGRE" wire:loading.attr="disabled">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8" />
                                            <path d="m21 21-4.3-4.3" />
                                        </svg>
                                    </x-button-add>
                                </div>
                                <x-jet-input-error for="searchgre" />
                            </div>
                        @endif
                    @endcan

                    <div class="w-full flex flex-wrap lg:flex-nowrap xl:flex-wrap gap-1">
                        <div class="w-full lg:w-1/3 xl:w-full">
                            <x-label value="DNI / RUC :" />
                            @if ($client_id)
                                <div class="w-full inline-flex relative">
                                    <x-disabled-text :text="$document" class="w-full block" />
                                    <x-button-close-modal
                                        class="hover:animate-none !text-red-500 hover:!bg-transparent focus:!bg-transparent hover:!ring-0 focus:!ring-0 absolute right-0 top-1"
                                        wire:click="limpiarcliente" wire:loading.attr="disabled" />
                                </div>
                            @else
                                <div class="w-full inline-flex">
                                    <x-input class="block w-full numeric prevent" wire:model.defer="document"
                                        wire:keydown.enter="getClient" minlength="8" maxlength="11"
                                        onkeypress="return validarNumero(event, 11)" onkeydown="disabledEnter(event)" />
                                    <x-button-add class="px-2" wire:click="getClient" wire:loading.attr="disabled">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8" />
                                            <path d="m21 21-4.3-4.3" />
                                        </svg>
                                    </x-button-add>
                                </div>
                            @endif
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

                        @if (mi_empresa()->uselistprice)
                            @if ($pricetypeasigned)
                                <div class="w-full lg:w-1/3 xl:w-full">
                                    <x-label value="Lista precio asignado :" />
                                    <x-disabled-text :text="$pricetypeasigned ?? ' - '" />
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-1">
                        <div class="w-full">
                            <x-label value="Tipo comprobante :" />
                            <div id="parenttpcmpbt" class="relative" x-init="selectComprobante" wire:ignore>
                                <x-select class="block w-full" x-ref="selectcomprobante" id="tpcmpbt"
                                    @change="getCodeSend($event.target)" data-placeholder="null">
                                    <x-slot name="options">
                                        @if (count($typecomprobantes))
                                            @foreach ($typecomprobantes as $item)
                                                <option value="{{ $item->id }}"
                                                    data-code="{{ $item->typecomprobante->code }}"
                                                    data-sunat="{{ $item->typecomprobante->sendsunat }}">
                                                    [{{ $item->serie }}] - {{ $item->typecomprobante->descripcion }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </x-slot>
                                </x-select>
                                <x-icon-select />
                            </div>
                            <x-jet-input-error for="seriecomprobante_id" />
                        </div>

                        @if (Module::isEnabled('Facturacion'))
                            <div class="w-full">
                                <x-label value="Tipo pago :" />
                                <div id="parenttpymt" class="relative" x-init="selectPayment" wire:ignore>
                                    <x-select class="block w-full" id="tpymt" x-ref="selectpayment"
                                        @change="getTipopago($event.target)" data-placeholder="null">
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
                                    <x-icon-select />
                                </div>
                                <x-jet-input-error for="typepayment_id" />
                            </div>
                        @endif
                    </div>

                    <div class="w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-1">

                        <div class="w-full lg:w-full animate__animated animate__fadeInDown" x-show="paymentcuotas">
                            <x-label value="Pago actual:" />
                            <x-input class="block w-full prevent" type="number" min="0" step="0.100"
                                wire:model.lazy="paymentactual" wire:key="paymentactual"
                                wire:keydown.enter="setpaymentactual($event.target.value)"
                                onkeypress="return validarDecimal(event, 12)" />
                            <x-jet-input-error for="paymentactual" />
                        </div>

                        <div class="w-full lg:w-full animate__animated animate__fadeInDown" x-show="paymentcuotas">
                            <x-label value="Incrementar venta (%):" />
                            <x-input class="block w-full prevent" type="number" min="0" step="0.10"
                                wire:model.lazy="increment" wire:key="increment"
                                wire:keydown.enter="setincrement($event.target.value)"
                                onkeypress="return validarDecimal(event, 5)" />
                            <x-jet-input-error for="increment" />
                        </div>

                        <div class="w-full lg:w-full animate__animated animate__fadeInDown" x-show="paymentcuotas">
                            <x-label value="Cuotas :" />
                            <div class="w-full inline-flex">
                                <x-input class="block w-full" type="number" min="1" step="1"
                                    max="100" wire:model.defer="countcuotas" wire:key="countcuotas"
                                    onkeypress="return validarNumero(event, 3)" />
                            </div>
                            <x-jet-input-error for="countcuotas" />
                        </div>

                        <div class="w-full" x-show="!paymentcuotas">
                            <x-label value="Método pago :" />
                            <div id="parenttmpym" class="relative" x-init="selectMethodpayment" wire:ignore>
                                <x-select class="block w-full" id="tmpym" x-ref="selectmethodpayment"
                                    data-placeholder="null">
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
                                <x-icon-select />
                            </div>
                            <x-jet-input-error for="methodpayment_id" />
                        </div>

                        <div class="w-full" x-show="!paymentcuotas">
                            <x-label value="Detalle pago :" />
                            <x-input class="block w-full" wire:model.defer="detallepago" />
                            <x-jet-input-error for="detallepago" />
                        </div>
                    </div>

                    @if (Module::isEnabled('Facturacion'))
                        <div class="block relative">
                            @can('admin.ventas.create.igv')
                                <x-label-check for="incluyeigv">
                                    <x-input wire:model.lazy="incluyeigv" name="incluyeigv" value="1"
                                        type="checkbox" id="incluyeigv" />INCLUIR IGV</x-label-check>
                                <x-jet-input-error for="incluyeigv" />
                            @endcan

                            @can('admin.ventas.create.guias')
                                @if (count($comprobantesguia) > 0)
                                    <div class="inline-block" x-show="!sincronizegre">
                                        <x-label-check for="incluyeguia" x-show="openguia">
                                            <x-input x-model="incluyeguia" name="incluyeguia" type="checkbox"
                                                id="incluyeguia" />GENERAR GUÍA REMISIÓN
                                        </x-label-check>
                                    </div>
                                @endif

                                <div x-show="incluyeguia" x-transition>

                                    <x-title-next titulo="MENÚ GUIA REMISION" class="my-3" />

                                    <div class="w-full grid grid-cols-1 xs:grid-cols-2 xl:grid-cols-1 gap-1">
                                        <div class="w-full xs:col-span-2 lg:col-span-1">
                                            <x-label value="Guía remisión :" />
                                            <div class="relative" id="parentsrgr" x-init="selectSerieguia" wire:ignore>
                                                <x-select class="block w-full uppercase" x-ref="selectguia"
                                                    id="srgr" data-placeholder="null">
                                                    <x-slot name="options">
                                                        @if (count($comprobantesguia))
                                                            @foreach ($comprobantesguia as $item)
                                                                <option value="{{ $item->id }}"
                                                                    data-code="{{ $item->typecomprobante->code }}">
                                                                    [{{ $item->serie }}] -
                                                                    {{ $item->typecomprobante->descripcion }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </x-slot>
                                                </x-select>
                                                <x-icon-select />
                                            </div>
                                            <x-jet-input-error for="serieguia_id" />
                                        </div>

                                        <div class="w-full xs:col-span-2 lg:col-span-1">
                                            <x-label value="Motivo traslado :" />
                                            <div class="relative" id="parentmtvtr" x-init="selectMotivo" wire:ignore>
                                                <x-select class="block w-full uppercase" x-ref="selectmotivo"
                                                    id="mtvtr" data-placeholder="null"
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
                                                <x-icon-select />
                                            </div>
                                            <span x-text="codemotivotraslado"></span>
                                            <x-jet-input-error for="motivotraslado_id" />
                                        </div>

                                        <div class="w-full">
                                            <x-label value="Modalidad transporte :" />
                                            <div class="relative" id="parentmdtr" x-init="selectModalidad" wire:ignore>
                                                <x-select class="block w-full uppercase" x-ref="selectmodalidad"
                                                    id="mdtr" data-placeholder="null"
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
                                                <x-icon-select />
                                            </div>
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
                                                step="1" type="number"
                                                onkeypress="return validarDecimal(event, 12)" />
                                            <x-jet-input-error for="packages" />
                                        </div>

                                        <div class="w-full">
                                            <x-label value="Peso bruto total (KILOGRAMO) :" />
                                            <x-input class="block w-full" wire:model.defer="peso" min="0"
                                                step="0.01" type="number"
                                                onkeypress="return validarDecimal(event, 12)" />
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
                                                        maxlength="11" onkeypress="return validarNumero(event, 11)" />
                                                    <x-button-add class="px-2" wire:click="getDestinatario"
                                                        wire:target="getDestinatario" wire:loading.attr="disabled">
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
                                                        minlength="0" maxlength="11"
                                                        onkeypress="return validarNumero(event, 11)" />
                                                    <x-button-add class="px-2" wire:click="getTransport"
                                                        wire:loading.attr="disabled">
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
                                                        minlength="0" maxlength="11"
                                                        onkeypress="return validarNumero(event, 8)" />
                                                    <x-button-add class="px-2" wire:click="getDriver"
                                                        wire:loading.attr="disabled">
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
                                                <x-label value="Lugar emisión :" />
                                                <div class="relative" x-init="selectUbigeoEmision" id="parentemision_id">
                                                    <x-select class="block w-full" id="emision_id" x-ref="ubigeoemision"
                                                        data-minimum-results-for-search="3">
                                                        <x-slot name="options">
                                                            @if (count($ubigeos))
                                                                @foreach ($ubigeos as $item)
                                                                    <option value="{{ $item->id }}">
                                                                        {{ $item->region }} / {{ $item->provincia }} /
                                                                        {{ $item->distrito }} / {{ $item->ubigeo_reniec }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </x-slot>
                                                    </x-select>
                                                    <x-icon-select />
                                                </div>
                                                <x-jet-input-error for="ubigeoorigen_id" />
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
                                                <x-label value="Lugar destino :" />
                                                <div class="relative" x-init="selectUbigeoDestino" id="parentdestino_id"
                                                    wire:ignore>
                                                    <x-select class="block w-full" id="destino_id" x-ref="ubigeodestino"
                                                        data-minimum-results-for-search="3">
                                                        <x-slot name="options">
                                                            @if (count($ubigeos))
                                                                @foreach ($ubigeos as $item)
                                                                    <option value="{{ $item->id }}">
                                                                        {{ $item->region }} / {{ $item->provincia }} /
                                                                        {{ $item->distrito }} / {{ $item->ubigeo_reniec }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </x-slot>
                                                    </x-select>
                                                    <x-icon-select />
                                                </div>
                                                <x-jet-input-error for="ubigeodestino_id" />
                                            </div>

                                            <div class="w-full lg:col-span-3 xl:col-span-1">
                                                <x-label value="Direccion destino :" />
                                                <x-input class="block w-full" wire:model.defer="direcciondestino"
                                                    placeholder="Direccion del punto de llegada.." />
                                                <x-jet-input-error for="direcciondestino" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    @endif

                    <div class="w-full flex flex-col gap-1">
                        <x-jet-input-error for="typepayment.id" />
                        <x-jet-input-error for="items" />
                        <x-jet-input-error for="client_id" />
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
                            {{ __('REGISTRAR') }}</x-button>
                    </div>
                </div>
            </form>
        </x-form-card>

        <x-form-card titulo="RESUMEN DE VENTA" class="text-colorlabel">
            <div class="w-full">
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
                    TOTAL VENTA :
                    {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($total, 3, '.', ', ') }}</span>
                </p>

                <p class="text-[10px]">SALDO PENDIENTE
                    @if ($increment)
                        {{ number_format($total - $paymentactual - $amountincrement, 3, '.', ', ') }}
                        + {{ formatDecimalOrInteger($increment) }}%
                        ({{ number_format($amountincrement, 2, '.', ', ') }})
                    @endif
                    : {{ $moneda->simbolo }}
                    <span class="font-bold text-xs">{{ number_format($total - $paymentactual, 3, '.', ', ') }}</span>
                </p>
                {{-- <p>AI--{{ $amountincrement }}</p>
                <p>IINC--{{ $increment }}</p> --}}
            </div>
        </x-form-card>

        @if (count($carshoops) > 0)
            <div class="w-full" x-data="{ showcart: true }">
                <div class="text-end px-3">
                    <button class="text-amber-500 relative inline-block w-6 h-6 cursor-pointer"
                        @click="showcart=!showcart">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            class="w-full h-full block">
                            <path d="M8 16L16.7201 15.2733C19.4486 15.046 20.0611 14.45 20.3635 11.7289L21 6" />
                            <path d="M6 6H22" />
                            <circle cx="6" cy="20" r="2" />
                            <circle cx="17" cy="20" r="2" />
                            <path d="M8 20L15 20" />
                            <path
                                d="M2 2H2.966C3.91068 2 4.73414 2.62459 4.96326 3.51493L7.93852 15.0765C8.08887 15.6608 7.9602 16.2797 7.58824 16.7616L6.63213 18" />
                        </svg>
                        <small
                            class="bg-amber-500 text-white animate-bounce font-semibold absolute -top-3 -right-1 flex items-center justify-center w-4 h-4 p-0.5 leading-3 rounded-full text-[8px]">
                            {{ count($carshoops) }}</small>
                    </button>
                </div>
                <div class="w-full" x-show="showcart" x-transition>
                    <div class="flex gap-2 flex-wrap justify-start">
                        @foreach ($carshoops as $item)
                            <x-simple-card
                                class="w-full flex flex-col border border-borderminicard justify-between lg:max-w-sm xl:w-full group p-1 text-xs relative overflow-hidden">

                                <h1
                                    class="text-colorlabel whitespace-nowrap text-right text-[10px] text-sm font-semibold">
                                    <small class="text-[7px] font-medium">{{ $item->moneda->simbolo }}</small>
                                    {{ number_format($item->total, 2) }}
                                    <small class="text-[7px] font-medium">{{ $item->moneda->currency }}</small>
                                </h1>
                                <h1 class="text-colorlabel w-full text-[10px] leading-3 text-left z-[1]">
                                    {{ $item->producto->name }}</h1>

                                @if (count($item->carshoopitems) > 0)
                                    <div
                                        class="w-auto h-auto bg-red-600 px-7 absolute -left-7 top-1 -rotate-[35deg] leading-3">
                                        <p class=" text-white text-[8px] inline-block font-semibold pb-0.5">
                                            COMBO</p>
                                    </div>

                                    @if (count($item->carshoopitems) > 0)
                                        <div class="w-full mb-2 mt-1">
                                            @foreach ($item->carshoopitems as $itemcarshop)
                                                <h1 class="text-next-500 text-[10px] leading-3 text-left">
                                                    <span
                                                        class="w-1.5 h-1.5 bg-next-500 inline-block rounded-full"></span>
                                                    {{ $itemcarshop->producto->name }}
                                                </h1>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif

                                <div class="w-full flex flex-wrap gap-1 mt-2">

                                    <x-span-text :text="'P.UNIT : ' . number_format($item->price, 2, '.', ', ')" class="leading-3 !tracking-normal" />
                                    @if ($incluyeigv)
                                        <x-span-text :text="'IGV UNIT : ' .
                                            number_format(
                                                $item->price - ($item->price * 100) / (100 + $empresa->igv),
                                                2,
                                                '.',
                                                ', ',
                                            )" class="leading-3 !tracking-normal" />
                                    @endif

                                    <x-span-text :text="formatDecimalOrInteger($item->cantidad) .
                                        ' ' .
                                        $item->producto->unit->name" class="leading-3 !tracking-normal" />
                                    <x-span-text :text="$item->almacen->name" class="leading-3 !tracking-normal" />

                                    @if (count($item->carshoopseries) == 1)
                                        <x-span-text :text="'SERIE : ' . $item->carshoopseries()->first()->serie->serie" class="leading-3 !tracking-normal" />
                                    @endif

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

                                </div>

                                @if (count($item->carshoopseries) > 1)
                                    <div x-data="{ showForm: false }" class="mt-1">
                                        <x-button @click="showForm = !showForm" class="whitespace-nowrap">
                                            {{ __('VER SERIES') }}
                                        </x-button>
                                        <div x-show="showForm" x-transition class="block w-full rounded mt-1">
                                            <div class="w-full flex flex-wrap gap-1">
                                                @foreach ($item->carshoopseries as $itemserie)
                                                    <span
                                                        class="inline-flex items-center gap-1 text-[10px] bg-fondospancardproduct text-textspancardproduct p-1 rounded-lg">
                                                        {{ $itemserie->serie->serie }}
                                                        <x-button-delete
                                                            onclick="confirmDeleteSerie({{ $itemserie }})"
                                                            wire:loading.attr="disabled" />
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif


                                <div class="w-full flex items-end gap-2 justify-between mt-2">
                                    @can('admin.ventas.create.gratuito')
                                        <div>
                                            <x-label-check textSize="[9px]" for="gratuito_{{ $item->id }}">
                                                <x-input wire:change="updategratis({{ $item->id }})" value="1"
                                                    type="checkbox" id="gratuito_{{ $item->id }}"
                                                    :checked="$item->gratuito == '1'" />
                                                GRATUITO</x-label-check>
                                        </div>
                                    @endcan
                                    <x-button-delete onclick="confirmDeleteCarshoop({{ $item }})"
                                        wire:loading.attr="disabled" />
                                </div>
                            </x-simple-card>
                        @endforeach
                    </div>

                    <div class="w-full flex justify-end mt-2">
                        <x-button-secondary onclick="confirmDeleteAllCarshoop()" wire:loading.attr="disabled"
                            class="inline-block">ELIMINAR TODO</x-button-secondary>
                    </div>
                </div>
            </div>
        @endif
    </div>

    

    <script>
        function confirmDeleteSerie(itemserie) {
            swal.fire({
                title: 'Eliminar serie ' + itemserie.serie.serie + ' del carrito de ventas ?',
                text: "Se eliminará un registro del carrito de ventas y se actualizará el stock del producto.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deleteserie(itemserie.id);
                }
            })
        }

        function confirmDeleteCarshoop(Carshoop) {
            swal.fire({
                title: 'ANULAR ITEM DEL CARRITO DE VENTAS ?',
                text: "Se eliminará un registro del carrito de ventas y se actualizará el stock del producto.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(Carshoop.id);
                }
            })
        }

        function confirmDeleteAllCarshoop() {
            swal.fire({
                title: 'Eliminar carrito de ventas ?',
                text: "Se eliminarán todos los productos del carrito de ventas y se actualizará su stock correspondientes.",
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

        function selectUbigeoEmision() {
            this.selectUE = $(this.$refs.ubigeoemision).select2();
            this.selectUE.val(this.ubigeoorigen_id).trigger("change");
            this.selectUE.on("select2:select", (event) => {
                this.ubigeoorigen_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("ubigeoorigen_id", (value) => {
                this.selectUE.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectUE.select2('destroy');
                this.selectUE.select2().val(this.ubigeoorigen_id).trigger('change');
            });
        }

        function selectUbigeoDestino() {
            this.selectUD = $(this.$refs.ubigeodestino).select2();
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
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('loader', () => ({
                incluyeguia: @entangle('incluyeguia').defer,
                vehiculosml: false,
                loadingprivate: false,
                loadingpublic: false,
                loadingdestinatario: false,
                codemotivotraslado: '',
                codemodalidad: '',
                paymentcuotas: false,
                formapago: '',
                code: '',
                sendsunat: '',
                openguia: true,
                sincronizegre: @entangle('sincronizegre').defer,

                cotizacion_id: @entangle('cotizacion_id').defer,
                moneda_id: @entangle('moneda_id'),
                seriecomprobante_id: @entangle('seriecomprobante_id').defer,
                typepayment_id: @entangle('typepayment_id').defer,
                methodpayment_id: @entangle('methodpayment_id').defer,
                serieguia_id: @entangle('serieguia_id').defer,
                motivotraslado_id: @entangle('motivotraslado_id').defer,
                modalidadtransporte_id: @entangle('modalidadtransporte_id').defer,
                ubigeoorigen_id: @entangle('ubigeoorigen_id').defer,
                ubigeodestino_id: @entangle('ubigeodestino_id').defer,

                init() {
                    // const selectpayment = this.$refs.selectpayment;
                    // const selectcomprobante = this.$refs.selectcomprobante;
                    // if (selectpayment) {
                    //     this.getTipopago(selectpayment);
                    // }
                    // if (selectcomprobante) {
                    //     this.getCodeSend(selectcomprobante);
                    // }
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
                    console.log(value);
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
                getCodeSend(target) {
                    this.sendsunat = target.options[target.selectedIndex].getAttribute(
                        'data-sunat');
                    console.log(this.sendsunat);

                    switch (this.sendsunat) {
                        case '0':
                            this.incluyeguia = false;
                            this.openguia = false;
                            break;
                        case '1':
                            this.openguia = true;
                            break;
                        default:
                            this.openguia = false;
                            this.incluyeguia = false;
                            this.sendsunat = '';
                    }

                    // console.log('sendsunat ' + this.sendsunat);
                    // console.log('incluyeguia ' + this.incluyeguia);
                    // console.log('openguia ' + this.openguia);
                },
            }));
        })

        window.addEventListener('show-resumen-venta', () => {
            @this.render();
            @this.setTotal();
        });

        function selectCotizacion() {
            this.selectCO = $(this.$refs.selectcot).select2();
            this.selectCO.val(this.cotizacion_id).trigger("change");
            this.selectCO.on("select2:select", (event) => {
                this.cotizacion_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("cotizacion_id", (value) => {
                this.selectCO.val(value).trigger("change");
            });
        }

        function selectMoneda() {
            this.selectMD = $(this.$refs.selectmoneda).select2();
            this.selectMD.val(this.moneda_id).trigger("change");
            this.selectMD.on("select2:select", (event) => {
                this.moneda_id = event.target.value;
                // @this.setMoneda(event.target.value);
                window.dispatchEvent(new CustomEvent('setMoneda', {
                    detail: {
                        message: event.target.value
                    }
                }));
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("moneda_id", (value) => {
                this.selectMD.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectMD.select2().val(this.moneda_id).trigger('change');
            });
        }

        function selectComprobante() {
            this.selectTC = $(this.$refs.selectcomprobante).select2();
            this.selectTC.val(this.seriecomprobante_id).trigger("change");
            this.selectTC.on("select2:select", (event) => {
                this.seriecomprobante_id = event.target.value;
                this.getCodeSend(event.target);
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("seriecomprobante_id", (value) => {
                this.selectTC.val(value).trigger("change");
            });
        }

        function selectPayment() {
            this.selectTP = $(this.$refs.selectpayment).select2();
            this.selectTP.val(this.typepayment_id).trigger("change");
            this.selectTP.on("select2:select", (event) => {
                // this.typepayment_id = event.target.value;
                this.getTipopago(event.target);
                @this.setTypepayment(event.target.value);
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("typepayment_id", (value) => {
                this.selectTP.val(value).trigger("change");
            });
        }

        function selectSerieguia() {
            this.selectGR = $(this.$refs.selectguia).select2();
            this.selectGR.val(this.serieguia_id).trigger("change");
            this.selectGR.on("select2:select", (event) => {
                this.serieguia_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("serieguia_id", (value) => {
                this.selectGR.val(value).trigger("change");
            });
        }

        function selectMotivo() {
            this.selectM = $(this.$refs.selectmotivo).select2();
            this.selectM.val(this.motivotraslado_id).trigger("change");
            this.selectM.on("select2:select", (event) => {
                this.motivotraslado_id = event.target.value;
                const selectedOption = event.target.selectedOptions[0];
                this.codemotivotraslado = selectedOption.getAttribute('data-code');
                this.selectedMotivotraslado(this.codemotivotraslado);
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("motivotraslado_id", (value) => {
                this.selectM.val(value).trigger("change");
            });
        }

        function selectModalidad() {
            this.selectMT = $(this.$refs.selectmodalidad).select2();
            this.selectMT.val(this.modalidadtransporte_id).trigger("change");
            this.selectMT.on("select2:select", (event) => {
                this.modalidadtransporte_id = event.target.value;
                const selectedOption = event.target.selectedOptions[0];
                this.codemodalidad = selectedOption.getAttribute('data-code');
                this.selectedModalidadtransporte(this.codemodalidad)
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("modalidadtransporte_id", (value) => {
                this.selectMT.val(value).trigger("change");
            });
        }

        function selectMethodpayment() {
            this.selectMP = $(this.$refs.selectmethodpayment).select2();
            this.selectMP.val(this.methodpayment_id).trigger("change");
            this.selectMP.on("select2:select", (event) => {
                this.methodpayment_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("methodpayment_id", (value) => {
                this.selectMP.val(value).trigger("change");
            });
        }
    </script>
</div>
