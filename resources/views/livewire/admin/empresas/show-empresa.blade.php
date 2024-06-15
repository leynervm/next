<div class="w-full flex flex-col gap-8" x-data="showempresa">
    <form wire:submit.prevent="update" class="w-full flex flex-col gap-8">
        <x-form-card titulo="DATOS EMPRESA">
            <div class="bg-body p-3 w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2">
                <div class="w-full">
                    <x-label value="RUC :" />
                    <div class="w-full inline-flex gap-1">
                        @if (auth()->user()->isAdmin())
                            <x-input class="flex-1 block w-full" wire:keydown.enter="searchclient" type="number"
                                wire:model.defer="empresa.document" onkeypress="return validarNumero(event, 11)"
                                onkeydown="disabledEnter(event)" />
                        @else
                            <x-disabled-text :text="$empresa->document" class="flex-1 w-full block" />
                        @endif

                        <x-button-add class="px-2" wire:click="searchclient" wire:loading.attr="disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                        </x-button-add>
                    </div>
                    <x-jet-input-error for="empresa.document" />
                </div>

                <div class="w-full sm:col-span-2">
                    <x-label value="Razón Social :" />
                    <x-input class="block w-full" wire:model.defer="empresa.name" />
                    <x-jet-input-error for="empresa.name" />
                </div>

                <div class="w-full sm:col-span-2 xl:col-span-1">
                    <x-label value="Dirección :" />
                    <x-input class="block w-full" wire:model.defer="empresa.direccion" />
                    <x-jet-input-error for="empresa.direccion" />
                </div>

                <div class="w-full sm:col-span-2">
                    <x-label value="Ubigeo :" />
                    {{-- 20201987297 --}}
                    <div id="parentubigeoempresa_id" class="relative" x-init="SelectUbigeoEmp">
                        <x-select class="block w-full" x-ref="selectubigeo" id="ubigeoempresa_id"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @foreach ($ubigeos as $item)
                                    <option value="{{ $item->id }}">{{ $item->region }} / {{ $item->provincia }} /
                                        {{ $item->distrito }} / {{ $item->ubigeo_reniec }}</option>
                                @endforeach
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="empresa.ubigeo_id" />
                </div>

                <div class="w-full">
                    <x-label value="Estado :" />
                    <x-disabled-text :text="$empresa->estado ?? '-'" />
                    <x-jet-input-error for="empresa.estado" />
                </div>

                <div class="w-full">
                    <x-label value="Condición :" />
                    <x-disabled-text :text="$empresa->condicion ?? '-'" />
                    <x-jet-input-error for="empresa.condicion" />
                </div>

                <div class="w-full">
                    <x-label value="Correo :" />
                    <x-input class="block w-full" wire:model.defer="empresa.email" placeholder="@" />
                    <x-jet-input-error for="empresa.correo" />
                </div>

                <div class="w-full">
                    <x-label value="Web :" />
                    <x-input class="block w-full" wire:model.defer="empresa.web" placeholder="www.misitioweb.com" />
                    <x-jet-input-error for="empresa.web" />
                </div>

                <div class="w-full">
                    <x-label value="Porcentaje IGV :" />
                    <x-input class="block w-full" wire:model.defer="empresa.igv" type="number" placeholder="0.00" />
                    <x-jet-input-error for="empresa.igv" />
                </div>
            </div>
        </x-form-card>

        <div class="grid gap-8 {{ Module::isEnabled('Employer') ? 'lg:grid-cols-2' : '' }}">
            <x-form-card titulo="OTRAS OPCIONES" subtitulo="Seleccionar las opciones según su preferencia de uso.">
                <div class="w-full items-start flex flex-col gap-1">
                    @if (Module::isEnabled('Ventas'))
                        <div class="block">
                            <x-label-check for="uselistprice">
                                <x-input wire:model.defer="empresa.uselistprice" name="uselistprice" value="1"
                                    type="checkbox" id="uselistprice" />
                                USAR LISTA DE PRECIOS
                            </x-label-check>
                            <x-jet-input-error for="empresa.uselistprice" />
                        </div>

                        <div class="block">
                            <x-label-check for="usepricedolar">
                                <x-input x-model="usepricedolar" id="usepricedolar" type="checkbox"
                                    @change="changePricedolar" />USAR PRECIO EN DÓLARES</x-label-check>
                            <x-jet-input-error for="empresa.usepricedolar" />

                            <div x-show="!cambioauto">
                                <div x-show="openpricedolar" style="display: none;">
                                    <x-input class="w-full" type="number" x-model="tipocambio" placeholder="0.00"
                                        min="0" step="0.0001" />
                                </div>
                            </div>

                            <div x-show="cambioauto">
                                <x-disabled-text text="" x-text="tipocambio" />
                            </div>

                            <x-jet-input-error for="empresa.tipocambio" />

                            <div x-show="loadingdolar" style="display: none;">
                                <x-loading-next />
                            </div>
                        </div>

                        <div class="w-full animate__animated animate__fadeInDown" x-show="openpricedolar">
                            <x-label-check for="viewpricedolar">
                                <x-input x-model="viewpricedolar" name="viewpricedolar" type="checkbox"
                                    id="viewpricedolar" />VER PRECIO EN DÓLARES</x-label-check>
                            <x-jet-input-error for="empresa.viewpricedolar" />
                        </div>

                        <div class="w-full animate__animated animate__fadeInDown" x-show="openpricedolar">
                            <x-label-check for="tipocambioauto">
                                <x-input name="tipocambioauto" x-model="cambioauto" type="checkbox"
                                    id="tipocambioauto" @change="changeAutomatico" />
                                ACTUALIZAR TIPO CAMBIO AUTOMÁTICO
                            </x-label-check>
                            <x-jet-input-error for="empresa.tipocambioauto" />
                        </div>
                    @endif

                    <div class="w-full" x-data="{ openvalidatemail: {{ $empresa->validatemail ? 'true' : 'false' }} }">
                        <x-label-check for="validatemail">
                            <x-input wire:model.defer="validatemail" value="1" type="checkbox"
                                x-on:change="openvalidatemail = !openvalidatemail" id="validatemail" />
                            VERIFICAR CORREO CORPORATIVO PARA DASHBOARD
                        </x-label-check>
                        <x-jet-input-error for="validatemail" />

                        <div x-show="openvalidatemail">
                            <x-input class="block w-full" wire:model.defer="dominiocorreo"
                                placeholder="Ingrese dominio web del correo a validar..." />
                            <x-jet-input-error for="dominiocorreo" />
                        </div>
                    </div>
                </div>
            </x-form-card>

            @if (Module::isEnabled('Employer'))
                <x-form-card titulo="CONFIGURAR ADELANTO PERSONAL">
                    <div class="w-full">
                        <x-label value="Monto máximo adelanto :" />
                        <x-input class="block w-full" wire:model.defer="empresa.montoadelanto" placeholder="0.00"
                            type="number" onkeypress="return validarDecimal(event, 11)" />
                        <x-jet-input-error for="empresa.montoadelanto" />
                    </div>
                </x-form-card>
            @endif
        </div>

        <div class="w-full flex justify-end">
            <x-button type="submit">{{ __('ACTUALIZAR') }}</x-button>
        </div>
    </form>

    <div class="grid gap-8 lg:grid-cols-2">
        <x-form-card titulo="LOGOTIPO & ICONO">
            <div class="w-full grid gap-3 xs:grid-cols-2">
                <div class="relative w-full text-center">
                    <div wire:loading.flex wire:target="logo,savelogo,clearLogo"
                        class="loading-overlay rounded hidden">
                        <x-loading-next />
                    </div>

                    @if (isset($logo))
                        <x-simple-card class="w-full h-40 md:max-w-md mx-auto mb-1 border border-borderminicard">
                            <img class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster"
                                src="{{ $logo->temporaryUrl() }}" />
                        </x-simple-card>
                    @else
                        @if ($empresa->image)
                            <x-simple-card class="w-full h-40 md:max-w-md mx-auto mb-1 border border-borderminicard">
                                <img class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster"
                                    src="{{ Storage::url('images/company/' . $empresa->image->url) }}" />
                            </x-simple-card>
                        @else
                            <x-icon-file-upload class="w-36 h-36 text-gray-300" />
                        @endif
                    @endif

                    <div class="w-full flex gap-2 flex-wrap justify-center">
                        @if (isset($logo))
                            <x-button class="inline-flex" wire:loading.attr="disabled" wire:click="savelogo">
                                GUARDAR LOGO
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M17.4776 9.01106C17.485 9.01102 17.4925 9.01101 17.5 9.01101C19.9853 9.01101 22 11.0294 22 13.5193C22 15.8398 20.25 17.7508 18 18M17.4776 9.01106C17.4924 8.84606 17.5 8.67896 17.5 8.51009C17.5 5.46695 15.0376 3 12 3C9.12324 3 6.76233 5.21267 6.52042 8.03192M17.4776 9.01106C17.3753 10.1476 16.9286 11.1846 16.2428 12.0165M6.52042 8.03192C3.98398 8.27373 2 10.4139 2 13.0183C2 15.4417 3.71776 17.4632 6 17.9273M6.52042 8.03192C6.67826 8.01687 6.83823 8.00917 7 8.00917C8.12582 8.00917 9.16474 8.38194 10.0005 9.01101" />
                                    <path
                                        d="M12 21L12 13M12 21C11.2998 21 9.99153 19.0057 9.5 18.5M12 21C12.7002 21 14.0085 19.0057 14.5 18.5" />
                                </svg>
                            </x-button>
                            <x-button class="inline-flex" wire:loading.attr="disabled" wire:click="clearLogo">LIMPIAR
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                    <line x1="10" x2="10" y1="11" y2="17" />
                                    <line x1="14" x2="14" y1="11" y2="17" />
                                </svg>
                            </x-button>
                        @else
                            <x-input-file :for="$idlogo" :titulo="$empresa->image ? 'CAMBIAR LOGO' : 'SELECCIONAR LOGO'" wire:loading.remove wire:target="logo">
                                <input type="file" class="hidden" wire:model.defer="logo"
                                    id="{{ $idlogo }}" accept="image/jpg, image/jpeg, image/png" />
                            </x-input-file>
                        @endif

                        @if (!isset($logo) && $empresa->image)
                            <x-button wire:click="deletelogo({{ $empresa->image->id }})"
                                wire:loading.attr="disabled">ELIMINAR LOGO
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                    <line x1="10" x2="10" y1="11" y2="17" />
                                    <line x1="14" x2="14" y1="11" y2="17" />
                                </svg>
                            </x-button>
                        @endif
                    </div>
                    <x-jet-input-error wire:loading.remove wire:target="logo" for="logo" class="text-center" />
                </div>

                <div class="relative w-full text-center">
                    <div wire:loading.flex wire:target="icono,clearIcono,saveicono"
                        class="loading-overlay rounded hidden">
                        <x-loading-next />
                    </div>

                    @if (isset($icono))
                        <div>
                            <x-simple-card class="w-full h-40 md:max-w-md mx-auto mb-1 border border-borderminicard">
                                <img x-bind:src="URL.createObjectURL(icono)"
                                    class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster">
                            </x-simple-card>
                        </div>
                    @else
                        @if ($empresa->icono)
                            <x-simple-card class="w-full h-40 md:max-w-md mx-auto mb-1 border border-borderminicard">
                                <img class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster"
                                    src="{{ Storage::url('images/company/' . $empresa->icono) }}" />
                            </x-simple-card>
                        @else
                            <x-icon-file-upload class="w-36 h-36 text-gray-300" />
                        @endif
                    @endif

                    <div class="w-full flex gap-1 flex-wrap justify-center">
                        @if (isset($icono))
                            <x-button class="inline-flex" wire:loading.attr="disabled" wire:click="saveicono">
                                GUARDAR ICONO
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M17.4776 9.01106C17.485 9.01102 17.4925 9.01101 17.5 9.01101C19.9853 9.01101 22 11.0294 22 13.5193C22 15.8398 20.25 17.7508 18 18M17.4776 9.01106C17.4924 8.84606 17.5 8.67896 17.5 8.51009C17.5 5.46695 15.0376 3 12 3C9.12324 3 6.76233 5.21267 6.52042 8.03192M17.4776 9.01106C17.3753 10.1476 16.9286 11.1846 16.2428 12.0165M6.52042 8.03192C3.98398 8.27373 2 10.4139 2 13.0183C2 15.4417 3.71776 17.4632 6 17.9273M6.52042 8.03192C6.67826 8.01687 6.83823 8.00917 7 8.00917C8.12582 8.00917 9.16474 8.38194 10.0005 9.01101" />
                                    <path
                                        d="M12 21L12 13M12 21C11.2998 21 9.99153 19.0057 9.5 18.5M12 21C12.7002 21 14.0085 19.0057 14.5 18.5" />
                                </svg>
                            </x-button>
                            <x-button class="inline-flex px-6" wire:loading.attr="disabled"
                                wire:click="clearIcono">LIMPIAR
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                    <line x1="10" x2="10" y1="11" y2="17" />
                                    <line x1="14" x2="14" y1="11" y2="17" />
                                </svg>
                            </x-button>
                        @else
                            <x-input-file for="{{ $idicono }}" :titulo="$empresa->icono ? 'CAMBIAR ÍCONO' : 'SELECCIONAR ÍCONO'" wire:loading.remove
                                wire:target="icono">
                                <input type="file" class="hidden" wire:model.defer="icono"
                                    id="{{ $idicono }}" accept=".ico"
                                    @change="icono = $event.target.files[0]" />
                            </x-input-file>
                        @endif

                        @if (!isset($icono) && $empresa->icono)
                            <x-button wire:click="deleteicono({{ $empresa->id }})"
                                wire:loading.attr="disabled">ELIMINAR ICONO
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                    <line x1="10" x2="10" y1="11" y2="17" />
                                    <line x1="14" x2="14" y1="11" y2="17" />
                                </svg>
                            </x-button>
                        @endif
                    </div>
                    <x-jet-input-error for="icono" class="text-center" />
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="TELÉFONOS" subtitulo="Agregue números de teléfono para contactarse.">
            <div class="w-full flex flex-col gap-3 justify-between h-full">
                @if (count($empresa->telephones))
                    <div class="w-full flex gap-2 flex-wrap h-full">
                        @foreach ($empresa->telephones as $item)
                            <x-minicard title="" :content="formatTelefono($item->phone)">
                                <x-slot name="title">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mx-auto"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                        <path d="M14.05 2a9 9 0 0 1 8 7.94" />
                                        <path d="M14.05 6A5 5 0 0 1 18 10" />
                                    </svg>
                                </x-slot>
                                <x-slot name="buttons">
                                    <x-button-edit wire:click="editphone({{ $item->id }})"
                                        wire:loading.attr="disabled" wire:target="editphone"></x-button-edit>
                                    <x-button-delete wire:click="deletephone({{ $item->id }})"
                                        wire:loading.attr="disabled" wire:target="deletephone"></x-button-delete>
                                </x-slot>
                            </x-minicard>
                        @endforeach
                    </div>
                @endif

                <div class="w-full mt-3 flex justify-end">
                    <x-button wire:click="openmodalphone" wire:loading.attr="disabled">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M16.243 5.243h3m3 0h-3m0 0v-3m0 3v3M18.118 14.702L14 15.5c-2.782-1.396-4.5-3-5.5-5.5l.77-4.13L7.815 2H4.064c-1.128 0-2.016.932-1.847 2.047.42 2.783 1.66 7.83 5.283 11.453 3.805 3.805 9.286 5.456 12.302 6.113 1.165.253 2.198-.655 2.198-1.848v-3.584l-3.882-1.479z" />
                        </svg>
                    </x-button>
                </div>
            </div>
        </x-form-card>
    </div>

    <x-jet-dialog-modal wire:model="openphone" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo teléfono') }}
            <x-button-close-modal wire:click="$toggle('openphone')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savetelefono">
                <div class="w-full">
                    <x-label value="Teléfono :" />
                    <x-input class="block w-full" wire:model.defer="phone" type="number"
                        onkeypress="return validarNumero(event, 9)" />
                    <x-jet-input-error for="phone" />
                    <x-jet-input-error for="empresa.id" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('showempresa', () => ({
                ubigeo_id: @entangle('empresa.ubigeo_id').defer,
                usepricedolar: @entangle('empresa.usepricedolar').defer,
                viewpricedolar: @entangle('empresa.viewpricedolar').defer,
                tipocambio: @entangle('empresa.tipocambio').defer,
                cambioauto: @entangle('empresa.tipocambioauto').defer,

                openpricedolar: @entangle('empresa.usepricedolar').defer,
                loadingdolar: false,

                init() {
                    this.usepricedolar = !!this.usepricedolar;
                    this.viewpricedolar = !!this.viewpricedolar;
                    this.cambioauto = !!this.cambioauto;
                },
                changePricedolar() {
                    if (this.usepricedolar) {
                        this.getTipocambio();
                    } else {
                        this.viewpricedolar = false;
                        this.cambioauto = false;
                        this.openpricedolar = false;
                    }
                },
                changeAutomatico() {
                    if (this.cambioauto) {
                        this.getTipocambio();
                    }
                },
                async getTipocambio() {
                    try {
                        this.loadingdolar = true;
                        const response = await fetch("{{ route('api.tipocambio') }}");
                        const data = await response.json();
                        this.tipocambio = data.precioVenta;
                        this.loadingdolar = false;
                        this.openpricedolar = true;

                    } catch (error) {
                        console.error('Error al obtener el tipo de cambio', error);
                        this.tipocambio = null;
                        this.loadingdolar = false;
                        this.openpricedolar = true;
                    }
                },
            }))
        });

        function SelectUbigeoEmp() {
            this.selectU = $(this.$refs.selectubigeo).select2();
            this.selectU.val(this.ubigeo_id).trigger("change");
            this.selectU.on("select2:select", (event) => {
                this.ubigeo_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("ubigeo_id", (value) => {
                this.selectU.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectU.select2().val(this.ubigeo_id).trigger('change');
            });
        }

        document.addEventListener('livewire:load', function() {
            // Livewire.on('iconload', (inputId) => {
            //     var input = $('#' + inputId);
            //     var file = input[0].files[0];

            //     if (file) {
            //         @this.icono = file;
            //         var reader = new FileReader();
            //         reader.onload = function(e) {
            //             $('#uploadForm + img').remove();
            //             $('#uploadForm').html('<img src="' + e.target.result +
            //                 '" class="w-full h-full object-scale-down"/>');
            //             console.log($('#uploadForm + img'));
            //             // document.getElementById('iconopreview').src = e.target.result;
            //         };
            //         reader.readAsDataURL(file);
            //     }
            // });

            // Livewire.on('searchpricedolar', event => {
            //     let checked = $(event).is(':checked');
            //     if (checked) {
            //         @this.searchpricedolar();
            //     }
            // });

            // function getTipoCambio(inputId) {
            //     axios.get('/admin/tipocambio', {
            //             responseType: 'json'
            //         })
            //         .then(function(response) {
            //             // console.log(response);
            //             if (response.status == 200) {
            //                 @this.empresa.tipocambio = response.data.precioVenta;
            //             }
            //         })
            //         .catch(function(error) {
            //             // handle error
            //             console.log(error);
            //         });
            // }
        })
    </script>
</div>
