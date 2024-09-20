<div style="display: none;" class="block relative" x-cloak x-show="incluyeguia" x-transition>
    <x-title-next titulo="MENÚ GUIA REMISION" class="my-3" />

    <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-1 gap-1">
        <div class="w-full">
            <x-label value="Guía remisión :" />
            <div class="relative" id="parentsrgr" x-init="selectSerieguia">
                <x-select class="block w-full uppercase" x-ref="selectguia" id="srgr" data-placeholder="null">
                    <x-slot name="options">
                        @if (count($comprobantesguia))
                            @foreach ($comprobantesguia as $item)
                                <option value="{{ $item->id }}" data-code="{{ $item->typecomprobante->code }}">
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

        <div class="w-full">
            <x-label value="Motivo traslado :" />
            <div class="relative" id="parentmtvtraslado_id" x-init="selectMotivo">
                <x-select class="block w-full uppercase" x-ref="selectmotivo" id="mtvtraslado_id" data-placeholder="null"
                    @change="getCodeMotivo($event.target)">
                    <x-slot name="options">
                        @if (count($motivotraslados) > 0)
                            @foreach ($motivotraslados as $item)
                                <option value="{{ $item->id }}" data-code="{{ $item->code }}">
                                    {{ $item->name }} </option>
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
            <div class="relative" id="parentmdtr" x-init="selectModalidad">
                <x-select class="block w-full uppercase" x-ref="selectmodalidad" id="mdtr" data-placeholder="null"
                    @change="getCodeModalidad($event.target)">
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
            <x-label value="Bultos / Paquetes :" />
            <x-input class="block w-full" wire:model.defer="packages" min="0" step="1" type="number"
                onkeypress="return validarDecimal(event, 12)" />
            <x-jet-input-error for="packages" />
        </div>

        <div class="w-full">
            <x-label value="Peso bruto total (KILOGRAMO) :" />
            <x-input class="block w-full" wire:model.defer="peso" min="0" step="0.01" type="number"
                onkeypress="return validarDecimal(event, 12)" />
            <x-jet-input-error for="peso" />
        </div>

        <div class="w-full lg:col-span-1" :class="vehiculosml ? 'sm:col-span-2 ' : 'xs:col-span-2 sm:col-span-3'">
            <x-label value="Descripción :" />
            <x-input class="block w-full" wire:model.defer="note"
                placeholder="Descripcion, detalle de la guía (Opcional)..." />
            <x-jet-input-error for="note" />
        </div>

        <div class="w-full" x-cloak x-show="vehiculosml" style="display:none" x-transition>
            <x-label value="Placa vehículo (Opcional) :" />
            <x-input class="block w-full" wire:model.defer="placavehiculo"
                placeholder="Placa del vehículo de transporte..." />
            <x-jet-input-error for="placavehiculo" />
        </div>
    </div>

    <div class="w-full">
        <x-label-check for="vehiculosml" class="mt-2 !text-[9px]">
            <x-input wire:model.defer="vehiculosml" name="vehiculosml" type="checkbox" id="vehiculosml"
                @change="toggle" />
            TRASLADO EN VEHÍCULOS DE CATEGORÍA M1 O L
        </x-label-check>
    </div>

    <div class="w-full animate__animated animate__fadeInDown" x-cloak x-show="loadingdestinatario">
        <x-title-next titulo="DATOS DEL DESTINATARIO" class="my-3" />

        <div class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-1 gap-1">
            <div class="w-full">
                <x-label value="DNI / RUC :" />
                <div class="w-full inline-flex gap-1">
                    <x-input class="block w-full flex-1 prevent numeric" wire:model.defer="documentdestinatario"
                        wire:keydown.enter="getDestinatario" minlength="0" maxlength="11"
                        onkeypress="return validarNumero(event, 11)" />
                    <x-button-add class="px-2" wire:click="getDestinatario" wire:target="getDestinatario"
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

            <div class="w-full md:col-span-2 lg:col-span-1">
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
                <div class="w-full inline-flex gap-1">
                    <x-input class="block w-full flex-1 prevent numeric" wire:model.defer="ructransport"
                        wire:keydown.enter="getTransport" minlength="0" maxlength="11"
                        onkeypress="return validarNumero(event, 11)" />
                    <x-button-add class="px-2" wire:click="getTransport" wire:loading.attr="disabled">
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

        <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-1 gap-1">
            <div class="w-full">
                <x-label value="Documento conductor :" />
                <div class="w-full inline-flex gap-1">
                    <x-input class="block w-full flex-1 prevent numeric" wire:model.defer="documentdriver"
                        wire:keydown.enter="getDriver" minlength="0" maxlength="11"
                        onkeypress="return validarNumero(event, 8)" />
                    <x-button-add class="px-2" wire:click="getDriver" wire:loading.attr="disabled">
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
                <x-label value="Nombres conductor :" />
                <x-input class="block w-full" wire:model.defer="namedriver" placeholder="Nombres del conductor" />
                <x-jet-input-error for="namedriver" />
            </div>

            <div class="w-full">
                <x-label value="Apellidos :" />
                <x-input class="block w-full" wire:model.defer="lastname" placeholder="Apellidos del conductor..." />
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
                <x-input class="block w-full" wire:model.defer="placa" placeholder="placa del vehículo..." />
                <x-jet-input-error for="placa" />
            </div>
        </div>
    </div>

    <div class="w-full">
        <x-title-next titulo="LUGAR DE EMISIÓN" class="my-3" />

        <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-1">

            <div class="w-full">
                <x-label value="Lugar emisión :" />
                <div class="relative" id="parentemision_id">
                    <x-select class="block w-full" id="emision_id" x-ref="ubigeoemision"
                        data-minimum-results-for-search="3">
                        <x-slot name="options">
                            {{-- @if (count($ubigeos))
                                @foreach ($ubigeos as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->region }} / {{ $item->provincia }} /
                                        {{ $item->distrito }} / {{ $item->ubigeo_reniec }}
                                    </option>
                                @endforeach
                            @endif --}}
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
        </div>
    </div>

    <div class="w-full">
        <x-title-next titulo="LUGAR DE DESTINO" class="my-3" />

        <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-1">
            <div class="w-full">
                <x-label value="Lugar destino :" />
                <div class="relative" id="parentdestino_id">
                    <x-select class="block w-full" id="destino_id" x-ref="ubigeodestino"
                        data-minimum-results-for-search="3">
                        {{-- <x-slot name="options">
                            @if (count($ubigeos))
                                @foreach ($ubigeos as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->region }} / {{ $item->provincia }} /
                                        {{ $item->distrito }} / {{ $item->ubigeo_reniec }}
                                    </option>
                                @endforeach
                            @endif
                        </x-slot> --}}
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
        </div>
    </div>

    <script>
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

            Livewire.hook('message.processed', () => {
                this.selectGR.select2().val(this.serieguia_id).trigger('change');
            });
        }

        function selectMotivo() {
            this.selectMTR = $(this.$refs.selectmotivo).select2();
            this.selectMTR.val(this.motivotraslado_id).trigger("change");
            this.selectMTR.on("select2:select", (event) => {
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
                this.selectMTR.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectMTR.select2().val(this.motivotraslado_id).trigger('change');
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

            Livewire.hook('message.processed', () => {
                this.selectMT.select2().val(this.modalidadtransporte_id).trigger('change');
            });
        }
    </script>
</div>
