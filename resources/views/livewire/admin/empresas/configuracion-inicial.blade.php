<div class="h-full lg:h-[calc(100vh-145px)] overflow-hidden">

    <style>
        [x-cloak] {
            display: none;
        }

        [type="checkbox"] {
            box-sizing: border-box;
            padding: 0;
        }

        .form-checkbox {
            border-radius: 0.25rem;
        }

        .form-radio {
            border-radius: 50%;
        }

        .form-checkbox:checked {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M5.707 7.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4a1 1 0 0 0-1.414-1.414L7 8.586 5.707 7.293z'/%3e%3c/svg%3e");
            border-color: transparent;
            background-color: currentColor;
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
        }

        .form-radio:checked {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3ccircle cx='8' cy='8' r='3'/%3e%3c/svg%3e");
            border-color: transparent;
            background-color: currentColor;
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>

    <div x-data="app()" x-cloak class="h-full overflow-y-auto flex justify-center items-center">
        <div class="w-full md:max-w-2xl mx-auto px-4 py-10 shadow-md shadow-shadowminicard rounded-xl">

            <div x-cloak x-show="step === 'complete'">
                <div class="w-full bg-white rounded-lg p-10 flex items-center shadow justify-between">
                    <div class="w-full">
                        <svg class="mb-4 h-20 w-20 text-green-500 mx-auto" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>

                        <h2 class="text-2xl mb-4 text-gray-800 text-center font-bold">Registration Success</h2>

                        <div class="text-gray-600 mb-8">
                            Thank you. We have sent you an email to demo@demo.test. Please click the link in the message
                            to activate your account.
                        </div>

                        <button @click="step = 1"
                            class="w-40 block mx-auto focus:outline-none py-2 px-5 rounded-lg shadow-sm text-center text-gray-600 bg-white hover:bg-gray-100 font-medium border">Back
                            to home</button>
                    </div>
                </div>
            </div>

            <div x-show="step != 'complete'" x-cloak>
                <!-- Top Navigation -->
                <div class="w-full border-b py-4">
                    <div class="uppercase tracking-wide text-xs font-bold text-gray-500 mb-1 leading-tight"
                        x-text="`Paso: ${step} de 5`"></div>
                    <div class="w-full flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="w-full md:flex-1">
                            <div x-show="step === 1">
                                <div class="text-lg font-bold text-colorsubtitleform leading-tight">
                                    Perfil de la empresa</div>
                            </div>
                            <div x-show="step === 2">
                                <div class="text-lg font-bold text-colorsubtitleform leading-tight">
                                    Configuración de precios y multimedia</div>
                            </div>
                            <div x-show="step === 3">
                                <div class="text-lg font-bold text-colorsubtitleform leading-tight">
                                    Datos adicionales</div>
                            </div>
                            <div x-show="step === 4">
                                <div class="text-lg font-bold text-colorsubtitleform leading-tight">
                                    Agregar sucursales</div>
                            </div>
                            <div x-show="step === 5">
                                <div class="text-lg font-bold text-colorsubtitleform leading-tight">
                                    Configuración facturación</div>
                            </div>
                        </div>

                        <div class="w-full flex items-center md:w-64 md:flex-shrink-0">
                            <div class="w-full flex-1 bg-white rounded-full mr-2">
                                <div class="w-full flex-1 rounded-full bg-green-500 text-xs leading-none h-2 text-center text-white"
                                    :style="'width: ' + parseInt(step / 5 * 100) + '%'"></div>
                            </div>
                            <div class="text-xs w-10 flex-shrink-0 text-colorsubtitleform text-end"
                                x-text="parseInt(step / 5 * 100) +'%'"></div>
                        </div>
                    </div>
                </div>
                <!-- /Top Navigation -->

                <!-- Step Content -->
                <div class="py-10 w-full">
                    <div x-show="step === 1">
                        <div class="bg-body grid grid-cols-1 gap-2">
                            <div class="w-full">
                                <x-label value="RUC :" />
                                <div class="w-full inline-flex gap-1">
                                    <x-input class="block w-full flex-1" wire:keydown.enter="searchclient"
                                        type="number" wire:model.defer="document"
                                        onkeypress="return validarNumero(event, 11)" onkeydown="disabledEnter(event)" />
                                    <x-button-add class="px-2" wire:click="searchclient" wire:loading.attr="disabled">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <path d="m21 21-4.3-4.3"></path>
                                        </svg>
                                    </x-button-add>
                                </div>
                                <x-jet-input-error for="document" />
                            </div>

                            <div class="w-full">
                                <x-label value="Razón Social :" />
                                <x-input class="block w-full" wire:model.defer="name" placeholder="Razón social..." />
                                <x-jet-input-error for="name" />
                            </div>

                            <div class="w-full">
                                <x-label value="Dirección :" />
                                <x-input class="block w-full" wire:model.defer="direccion" placeholder="Dirección..." />
                                <x-jet-input-error for="direccion" />
                            </div>

                            <div class="w-full">
                                <x-label value="Ubigeo :" />
                                {{-- 20201987297 --}}
                                <div id="parentubigeo_id" class="relative" x-init="selectUbigeo">
                                    <x-select class="block w-full" x-ref="selectubigeo" id="ubigeo_id"
                                        data-minimum-results-for-search="3">
                                        <x-slot name="options">
                                            @foreach ($ubigeos as $item)
                                                <option value="{{ $item->id }}">{{ $item->region }} /
                                                    {{ $item->provincia }} /
                                                    {{ $item->distrito }} / {{ $item->ubigeo_reniec }}</option>
                                            @endforeach
                                        </x-slot>
                                    </x-select>
                                    <x-icon-select />
                                </div>
                                <x-jet-input-error for="ubigeo_id" />
                            </div>

                            <div class="w-full">
                                <x-label value="Estado :" />
                                <x-disabled-text :text="$estado ?? '-'" />
                                <x-jet-input-error for="estado" />
                            </div>

                            <div class="w-full">
                                <x-label value="Condición :" />
                                <x-disabled-text :text="$condicion ?? '-'" />
                                <x-jet-input-error for="condicion" />
                            </div>

                            <div class="w-full">
                                <x-label value="IGV (%):" />
                                <x-input class="block w-full" wire:model.defer="igv" type="number"
                                    placeholder="0.00" />
                                <x-jet-input-error for="igv" />
                            </div>
                        </div>
                    </div>

                    <div x-show="step === 2">
                        @if (Module::isEnabled('Ventas'))
                            <div class="w-full items-start flex flex-col gap-2">
                                <div class="block">
                                    <x-label-check for="uselistprice">
                                        <x-input wire:model.defer="uselistprice" name="uselistprice" value="1"
                                            type="checkbox" id="uselistprice" />
                                        USAR LISTA DE PRECIOS
                                    </x-label-check>
                                    <x-jet-input-error for="uselistprice" />
                                </div>

                                <div class="block">
                                    <x-label-check for="usepricedolar">
                                        <x-input x-model="usepricedolar" id="usepricedolar" type="checkbox"
                                            @change="changePricedolar" />USAR PRECIO EN DÓLARES</x-label-check>
                                    <x-jet-input-error for="usepricedolar" />

                                    <div x-show="!cambioauto">
                                        <div x-show="openpricedolar" style="display: none;">
                                            <x-input class="w-full" type="number" x-model="tipocambio"
                                                placeholder="0.00" min="0" step="0.0001" />
                                        </div>
                                    </div>

                                    <div x-show="cambioauto">
                                        <x-disabled-text text="" x-text="tipocambio" />
                                    </div>

                                    <x-jet-input-error for="tipocambio" />

                                    <div x-show="loadingdolar" style="display: none;">
                                        <x-loading-next />
                                    </div>
                                </div>

                                <div class="w-full animate__animated animate__fadeInDown" x-show="openpricedolar">
                                    <x-label-check for="viewpricedolar">
                                        <x-input x-model="viewpricedolar" name="viewpricedolar" type="checkbox"
                                            id="viewpricedolar" />VER PRECIO EN DÓLARES</x-label-check>
                                    <x-jet-input-error for="viewpricedolar" />
                                </div>

                                <div class="w-full animate__animated animate__fadeInDown" x-show="openpricedolar">
                                    <x-label-check for="tipocambioauto">
                                        <x-input name="tipocambioauto" x-model="cambioauto" type="checkbox"
                                            id="tipocambioauto" @change="changeAutomatico" />
                                        <div class="flex flex-col flex-1">
                                            ACTUALIZAR TIPO CAMBIO AUTOMÁTICO
                                            <small class="font-medium text-xs tracking-normal">
                                                El tipo de cambio se actualizará todos los dias a las 12:00 am
                                                hrs</small>
                                        </div>
                                    </x-label-check>
                                    <x-jet-input-error for="tipocambioauto" />
                                </div>
                            </div>
                        @endif

                        <div class="w-full flex flex-col sm:flex-row gap-3 my-5">
                            <div class="w-full sm:w-1/2 text-center">
                                <div
                                    class="w-full h-60 relative mb-2 shadow-md shadow-shadowminicard rounded-xl overflow-hidden">
                                    <template x-if="image">
                                        <img id="image" class="object-scale-down block w-full h-full"
                                            :src="image" />
                                    </template>
                                    <template x-if="!image">
                                        <x-icon-fileupload class="w-full h-full !my-0" />
                                    </template>
                                </div>

                                <label for="fileInput" type="button"
                                    class="cursor-pointer text-[10px] inine-flex justify-between items-center focus:outline-none hover:ring-2 hover:ring-ringbutton py-2 px-4 rounded-lg shadow-sm text-left text-colorbutton bg-fondobutton hover:bg-fondohoverbutton hover:text-colorhoverbutton font-semibold tracking-widest">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="inline-flex flex-shrink-0 w-6 h-6 -mt-1 mr-1" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                                        <path
                                            d="M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" />
                                        <circle cx="12" cy="13" r="3" />
                                    </svg>
                                    SELECCIONAR LOGO
                                </label>

                                <input name="photo" id="fileInput" accept="image/*" class="hidden" type="file"
                                    @change="let file = document.getElementById('fileInput').files[0]; 
								var reader = new FileReader();
								reader.onload = (e) => image = e.target.result;
								reader.readAsDataURL(file);">
                            </div>
                            <div class="w-full sm:w-1/2 text-center">
                                <div
                                    class="w-full h-60 relative mb-2 shadow-md shadow-shadowminicard rounded-xl overflow-hidden">
                                    <template x-if="icono">
                                        <img id="icono" class="object-scale-down block w-full h-full"
                                            :src="icono" />
                                    </template>
                                    <template x-if="!icono">
                                        <x-icon-fileupload class="w-full h-full !my-0" />
                                    </template>
                                </div>

                                <label for="fileInputIcono" type="button"
                                    class="cursor-pointer text-[10px] inine-flex justify-between items-center focus:outline-none hover:ring-2 hover:ring-ringbutton py-2 px-4 rounded-lg shadow-sm text-left text-colorbutton bg-fondobutton hover:bg-fondohoverbutton hover:text-colorhoverbutton font-semibold tracking-widest">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="inline-flex flex-shrink-0 w-6 h-6 -mt-1 mr-1" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                                        <path
                                            d="M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" />
                                        <circle cx="12" cy="13" r="3" />
                                    </svg>
                                    SELECCIONAR ICONO WEB
                                </label>

                                <input name="icono" id="fileInputIcono" accept=".ico" class="hidden"
                                    type="file"
                                    @change="let isotipo = document.getElementById('fileInputIcono').files[0]; 
								var reader = new FileReader();
								reader.onload = (e) => icono = e.target.result;
								reader.readAsDataURL(isotipo);">
                            </div>
                        </div>
                    </div>

                    <div x-show="step === 3">
                        <div class="bg-body grid grid-cols-1 gap-2">
                            <div class="w-full">
                                <x-label value="Correo :" />
                                <x-input class="block w-full" wire:model.defer="email" placeholder="@" />
                                <x-jet-input-error for="email" />
                            </div>
                            <div class="w-full">
                                <x-label value="Web :" />
                                <x-input class="block w-full" wire:model.defer="web"
                                    placeholder="www.misitioweb.com" />
                                <x-jet-input-error for="web" />
                            </div>
                            <div class="w-full">
                                <x-label value="Teléfono /Celular :" />
                                <x-input class="block w-full" wire:model.defer="telefono" type="number"
                                    onkeypress="return validarNumero(event, 9)" />
                                <x-jet-input-error for="telefono" />
                            </div>

                            <div class="text-end">
                                <x-button wire:click="addphone" wire:loading.attr="disabled">AGREGAR NUEVO
                                    TELEFONO</x-button>
                            </div>

                            <div>
                                @if (count($telephones) > 0)
                                    <div class="w-full flex flex-wrap gap-2">
                                        @foreach ($telephones as $item)
                                            <div
                                                class="inline-flex items-center gap-1 p-1 rounded bg-fondospancardproduct">
                                                <h1 class="text-xs text-textspancardproduct">{{ $item }}</h1>
                                                <x-button-delete />
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>

                    <div x-show="step === 4">
                        
                        SUCURSALES

                    </div>

                    <div x-show="step === 5">

                        FACTURACION

                    </div>
                </div>
                <!-- / Step Content -->
            </div>

            <div class="md:fixed bottom-0 left-0 right-0 py-3 md:py-5 px-5 md:px-0 bg-fondominicard border-t border-borderminicard"
                x-cloak x-show="step != 'complete'">
                <div class="max-w-3xl mx-auto">
                    <div class="flex justify-between">
                        <div class="w-1/2">
                            <x-button class="inline-flex" x-show="step > 1" x-cloak @click="step--">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                    color="currentColor" fill="none" class="w-6 h-6 inline-block">
                                    <path d="M4 12L20 12" />
                                    <path d="M8.99996 17C8.99996 17 4.00001 13.3176 4 12C3.99999 10.6824 9 7 9 7" />
                                </svg>
                                ANTERIOR
                            </x-button>
                        </div>

                        <div class="w-1/2 text-right">
                            <x-button class="inline-flex" x-show="step < 5" @click="step++" {{-- @click="$wire.validatestep(step)" --}}>
                                SIGUIENTE
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                    color="currentColor" fill="none" class="w-6 h-6 inline-block">
                                    <path d="M20 12L4 12" />
                                    <path d="M15 17C15 17 20 13.3176 20 12C20 10.6824 15 7 15 7" />
                                </svg>
                            </x-button>
                            <x-button class="inline-block" @click="step = 'complete'" x-cloak
                                x-show="step === 5">REGISTRAR</x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <script>
        function app() {
            return {
                ubigeo_id: @entangle('ubigeo_id').defer,
                usepricedolar: @entangle('usepricedolar').defer,
                viewpricedolar: @entangle('viewpricedolar').defer,
                tipocambio: @entangle('tipocambio').defer,
                sendmode: @entangle('sendmode').defer,
                cambioauto: @entangle('tipocambioauto').defer,
                loadingdolar: false,
                openpricedolar: false,
                openvalidatemail: false,

                step: @entangle('step').defer,
                passwordStrengthText: '',
                togglePassword: false,

                image: null,
                icono: null,
                password: '',
                gender: 'Male',

                checkPasswordStrength() {
                    var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
                    var mediumRegex = new RegExp(
                        "^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})");

                    let value = this.password;

                    if (strongRegex.test(value)) {
                        this.passwordStrengthText = "Strong password";
                    } else if (mediumRegex.test(value)) {
                        this.passwordStrengthText = "Could be stronger";
                    } else {
                        this.passwordStrengthText = "Too weak";
                    }
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
                        const response = await fetch('/admin/tipocambio');
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
                // , validatestep() {
                // @this.validatestep(this.step);
                // this.step++;
                // }

            }
        }

        function selectUbigeo() {
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
    </script>
</div>
