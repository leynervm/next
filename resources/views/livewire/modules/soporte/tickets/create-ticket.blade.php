<div x-data="createticket">
    <x-loading-web-next x-cloak wire:loading.flex wire:key="loadingregisterticket" />

    <form wire:submit.prevent="save" class="w-full flex flex-col gap-2 lg:gap-5">

        <x-form-card titulo="DATOS DEL CLIENTE" class="w-full gap-2">
            <div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-2"
                :class="openSidebar ? 'md:grid-cols-1 lg:grid-cols-3 xl:grid-cols-5' :
                    'md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-5 2xl:grid-cols-6'">
                <div class="w-full">
                    <x-label value="DNI / RUC :" />
                    @if ($client_id)
                        <div class="w-full inline-flex relative">
                            <x-disabled-text :text="$document" class="w-full flex-1 block" />
                            <x-button-close-modal class="btn-desvincular" wire:click="limpiarcliente"
                                wire:loading.attr="disabled" />
                        </div>
                    @else
                        <div class="w-full inline-flex gap-1">
                            <x-input class="block w-full flex-1 input-number-none" x-model="document"
                                wire:model.defer="document" wire:keydown.enter.prevent="searchcliente" minlength="8"
                                maxlength="11" onkeypress="return validarNumero(event, 11)"
                                onpaste="return validarPasteNumero(event, 11)" />
                            <x-button-add class="px-2" wire:click="searchcliente" wire:loading.attr="disabled">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.3-4.3" />
                                </svg>
                            </x-button-add>
                        </div>
                    @endif
                    <x-jet-input-error for="document" />
                </div>

                <div class="w-full md:col-span-2">
                    <x-label value="Cliente / Razón Social :" />
                    <x-input class="block w-full" x-model="name" placeholder="" />
                    <x-jet-input-error for="name" />
                </div>

                @if (!empty($contact['document']))
                    <div class="w-full">
                        <x-label value="DNI Contacto :" />
                        <div class="relative">
                            <x-disabled-text :text="$contact['document']" class="w-full block" />
                            <x-button-close-modal class="btn-desvincular" wire:click="limpiarcontact"
                                wire:loading.attr="disabled" />
                        </div>
                    </div>
                    <div class="w-full md:col-span-2">
                        <x-label value="Nombres Contacto :" />
                        <x-disabled-text :text="$contact['name']" class="w-full block" />
                    </div>
                @endif
            </div>

            <div class="w-full flex flex-col gap-1">
                <div class="inline-flex gap-2 items-end">
                    <x-button type="button" wire:loading.attr="disabled" wire:click="$toggle('openphone')">
                        {{ __('Editar Contacto') }}
                    </x-button>
                </div>

                <x-jet-input-error for="selectedphones" />
                <x-jet-input-error for="telefonos" />
                <x-jet-input-error for="contacts" />

                @if (count($selectedphones) > 0)
                    <div class="w-full flex flex-wrap gap-2 mt-2">
                        @foreach ($selectedphones as $p)
                            <x-minicard :title="null" wire:key="cel_{{ $p }}">
                                <x-icons.phone class="size-6" />

                                <span class="block w-full text-sm text-center font-medium mt-1">
                                    {{ formatTelefono($p) }}
                                </span>
                            </x-minicard>
                        @endforeach
                    </div>
                @endif
            </div>
        </x-form-card>


        <div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-3"
            :class="openSidebar ? 'md:grid-cols-1 lg:grid-cols-3 xl:grid-cols-4' :
                'md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4'">
            <x-form-card titulo="PRIORIDAD ATENCIÓN" class="w-full gap-2">
                @if (count($priorities))
                    <div class="flex flex-wrap gap-1">
                        @foreach ($priorities as $item)
                            <x-input-radio class="py-2" for="priority_{{ $item->id }}" :text="$item->name">
                                <input wire:model.defer="priority_id" class="sr-only peer peer-disabled:opacity-25"
                                    type="radio" id="priority_{{ $item->id }}" name="priorities"
                                    value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="priority_id" />
            </x-form-card>

            <x-form-card titulo="TIPO DE ATENCIÓN" class="w-full gap-2">
                @if (count($areawork->atencions))
                    <div class="flex flex-wrap gap-1">
                        @foreach ($areawork->atencions as $item)
                            <x-input-radio class="py-2" for="atencion_{{ $item->id }}" :text="$item->name">
                                <input wire:model.lazy="atencion_id" class="sr-only peer peer-disabled:opacity-25"
                                    type="radio" id="atencion_{{ $item->id }}" name="atencions"
                                    value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="atencion_id" />
            </x-form-card>

            <x-form-card titulo="ENTORNO ATENCIÓN" class="w-full gap-2">
                @if (count($entornos))
                    <div class="flex flex-wrap gap-1">
                        @foreach ($entornos as $item)
                            <x-input-radio class="py-2" for="entorno_{{ $item->id }}" :text="$item->name">
                                <input wire:key="entorno_{{ $item->id }}" wire:model.lazy="entorno_id"
                                    class="sr-only peer peer-disabled:opacity-25" type="radio"
                                    id="entorno_{{ $item->id }}" name="entornos" value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="entorno_id" />
            </x-form-card>

            <x-form-card titulo="CONDICIONES DE ATENCIÓN" class="w-full gap-2">
                @if (count($conditions))
                    <div class="flex flex-wrap gap-1">
                        @foreach ($conditions as $item)
                            <x-input-radio class="py-2" for="condition_{{ $item->id }}" :text="$item->name">
                                <input wire:model.defer="condition_id" class="sr-only peer peer-disabled:opacity-25"
                                    type="radio" id="condition_{{ $item->id }}" name="conditions"
                                    value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="condition_id" />
            </x-form-card>
        </div>

        @if ($addequipo)
            <x-form-card titulo="AGREGAR EQUIPO" class="w-full gap-2">
                <div class="w-full grid grid-cols-1 xs:grid-cols-2 gap-2"
                    :class="openSidebar ? 'md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5' :
                        'md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5'">
                    <div class="w-full">
                        <x-label value="Tipo :" />
                        <div id="parenttypeequipo_id" class="relative" x-init="select2TypeEquipo">
                            <x-select class="block w-full" id="typeequipo_id" x-ref="selecttypeequipo">
                                <x-slot name="options">
                                    @foreach ($typeequipos as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="typeequipo_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Marca :" />
                        <div id="parentmarca_id" class="relative" x-init="select2Marca">
                            <x-select class="block w-full" id="marca_id" x-ref="selectmarca">
                                <x-slot name="options">
                                    @foreach ($marcas as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="marca_id" />
                    </div>

                    <div>
                        <x-label value="Modelo :" />
                        <x-input class="block w-full" wire:model.defer="modelo" wire:keydown.enter="addequipo" />
                        <x-jet-input-error for="modelo" />
                    </div>

                    <div>
                        <x-label value="Serie :" />
                        <x-input class="block w-full" wire:model.defer="serie" wire:keydown.enter="addequipo" />
                        <x-jet-input-error for="serie" />
                    </div>

                    <div class="w-full">
                        <x-label value="Estado ingreso :" />
                        <div id="parentestadoequipo" class="relative" x-init="select2Estado">
                            <x-select class="block w-full" id="estadoequipo" x-ref="selectestadoequipo">
                                <x-slot name="options">
                                    @foreach ($estadoequipos as $item)
                                        <option value="{{ $item->value }}">{{ $item->label() }}</option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="stateinicial" />
                    </div>

                    <div class="w-full xs:col-span-2"
                        :class="openSidebar ? 'md:col-span-2 lg:col-span-3 2xl:col-span-6' :
                            'md:col-span-2 lg:col-span-3 xl:col-span-5'">
                        <x-label value="Descripción del equipo :" />
                        <x-text-area class="block w-full" wire:model.defer="descripcion"></x-text-area>
                        <x-jet-input-error for="descripcion" />
                    </div>
                </div>
            </x-form-card>
        @endif

        <x-form-card titulo="DESCRIPCIÓN DEL SERVICIO" class="w-full">
            <div class="w-full">
                <x-label value="Servicio / problema a resolver :" />
                <x-text-area class="block w-full" wire:model.defer="servicio"></x-text-area>
                <x-jet-input-error for="servicio" />
            </div>
        </x-form-card>

        @if ($addadress)
            <x-form-card titulo="LUGAR DE ATENCIÓN" class="w-full">
                <div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-2"
                    :class="openSidebar ? 'lg:grid-cols-4 xl:grid-cols-3' : 'lg:grid-cols-4 xl:grid-cols-3'">

                    <div class="w-full xl:col-span-1"
                        :class="openSidebar ? 'md:col-span-2' : 'md:col-span-1 lg:col-span-2'">
                        <x-label value="Lugar :" />
                        <div id="parentubigeo_id" class="relative" x-init="select2TUbigeo" wire:key="ubigeos">
                            <x-select class="block w-full" id="ubigeo_id" x-ref="selectubigeo">
                                <x-slot name="options">
                                    @foreach ($ubigeos as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->region }} / {{ $item->provincia }} / {{ $item->distrito }}
                                            / {{ $item->ubigeo_reniec }}
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="ubigeo_id" />
                    </div>

                    <div class="w-full xl:col-span-1"
                        :class="openSidebar ? 'md:col-span-2' : 'md:col-span-1 lg:col-span-2'">
                        <x-label value="Direccion :" />
                        <x-input class="block w-full" wire:model.defer="direccionasistencia" />
                        <x-jet-input-error for="direccionasistencia" />
                    </div>

                    <div class="w-full xl:col-span-1"
                        :class="openSidebar ? 'md:col-span-2' : 'md:col-span-1 lg:col-span-2'">
                        <x-label value="Referencia :" />
                        <x-input class="block w-full" wire:model.defer="referencia" />
                    </div>

                    <div class="w-full">
                        <x-label value="Fecha asistencia :" />
                        <x-input class="block w-full" type="datetime-local" wire:model.defer="dateasistencia" />
                        <x-jet-input-error for="dateasistencia" />
                    </div>
                </div>
            </x-form-card>
        @endif

        {{-- {{ print_r($errors->all()) }} --}}

        @if (count($equiposticket) > 0)
            <div class="w-full grid grid-cols-[repeat(auto-fill,minmax(220px,1fr))] self-start gap-2">
                @foreach ($equiposticket as $item)
                    <x-simple-card class="flex flex-col gap-2 p-2 justify-between">
                        <div class="w-full flex flex-col gap-2">
                            <div class="w-full flex flex-col gap-1">
                                <div>
                                    <span class="text-white rounded-lg p-1 text-xs"
                                        style="background: {{ $item['color'] }}">
                                        {{ $item['priority'] }}</span>
                                </div>
                                <div class="w-full flex flex-wrap gap-1">
                                    <x-span-text :text="$item['atencion']" />
                                    <x-span-text :text="$item['entorno']" />
                                    <x-span-text :text="$item['condition']" />
                                </div>
                                <p class="text-xs leading-none text-justify text-colorlabel">
                                    {{ $item['servicio'] }}</p>
                            </div>

                            @if (array_key_exists('addequipo', $item) && $item['addequipo'])
                                <div class="w-full flex flex-col gap-1">
                                    <h1 class="font-semibold text-colorlabel text-[10px]">
                                        ESPECIFICACIONES DEL EQUIPO</h1>
                                    <div class="w-full flex flex-wrap gap-1">
                                        <x-span-text :text="$item['typeequipo'] . ' ' . $item['modelo']" />
                                        <x-span-text :text="$item['marca']" />
                                        @if (!empty($item['serie']))
                                            <x-span-text :text="$item['serie']" />
                                        @endif
                                        <x-span-text :text="$item['estado']" />
                                    </div>
                                    <p class="text-xs leading-none text-justify text-colorlabel">
                                        {{ $item['descripcion'] }}</p>
                                </div>
                            @endif

                            @if ($item['addadress'])
                                <div class="w-full flex flex-col gap-1">
                                    <h1 class="font-semibold text-colorlabel text-[10px]">
                                        LUGAR DE ASISTENCIA</h1>
                                    <div class="w-full flex flex-wrap">
                                        <x-span-text :text="formatHuman($item['dateasistencia'])" />
                                    </div>
                                    <p class="text-xs leading-none text-justify text-colorlabel">
                                        {{ $item['direccionasistencia'] }} , {{ $item['referencia'] }}
                                    </p>
                                    <p class="text-xs leading-none text-justify text-colorlabel">
                                        {{ $item['lugar'] }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="w-full flex justify-end items-end">
                            <x-button-delete wire:click="removeequipo('{{ $item['id'] }}')"
                                wire:key="'{{ $item['id'] }}'" wire:loading.attr="disabled" />
                        </div>
                    </x-simple-card>
                @endforeach
            </div>
        @endif

        <div
            class="w-full flex items-center p-1 sticky -bottom-1 right-0 bg-body {{ $addequipo ? 'justify-between' : 'justify-end' }}">
            @if ($addequipo)
                <x-button wire:click="addequipo" type="button" wire:loading.attr="disabled" wire:key="addequipo">
                    {{ __('Add') }} OTRO EQUIPO
                </x-button>
            @endif
            <x-button type="submit" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-button>
        </div>
    </form>

    <x-jet-dialog-modal wire:model="openphone" maxWidth="lg" footerAlign="justify-end" :closeButton=false>
        <x-slot name="title">
            {{ __('Contacto del ticket') }}
        </x-slot>

        <x-slot name="content">
            <x-label value="Agregar nuevo teléfono :" />
            <form wire:submit.prevent="addphone" class="w-full inline-flex gap-1">
                <x-input type="text" class="block w-full flex-1 input-number-none" wire:model.defer="phonenumber"
                    minlength="9" maxlength="9" onkeypress="return validarNumero(event, 9)"
                    onpaste="return validarPasteNumero(event, 9)" />
                <x-button type="submit" wire:loading.attr="disabled">
                    {{ __('Add') }}
                </x-button>
            </form>
            <x-jet-input-error for="phonenumber" />

            @if (count($telefonos) > 0)
                <div class="mt-5">
                    <x-label value="SELECCIONAR TELÉFONO :" class="font-semibold !text-[10px]" />
                    <div class="w-full grid gap-2 grid-cols-[repeat(auto-fill,minmax(100px,1fr))]">
                        @foreach ($telefonos as $item)
                            <div>
                                <input class="sr-only peer peer-disabled:opacity-25" type="checkbox"
                                    id="phone_{{ $item['phone'] }}" name="phones" value="{{ $item['phone'] }}"
                                    wire:model.defer="selectedphones" />
                                <label for="phone_{{ $item['phone'] }}"
                                    class="text-xs h-full relative flex flex-col justify-center items-center input-radio-button">
                                    <x-icons.phone class="size-10" />
                                    {{ formatTelefono($item['phone']) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($client_id && strlen($document) == 11)

                <form wire:submit.prevent="addcontact" class="w-full flex flex-col gap-1 mt-5">
                    <div class="w-full">
                        <x-label value="DNI :" />
                        <div class="w-full inline-flex gap-1">
                            <x-input class="block w-full flex-1 input-number-none" wire:model.defer="documentcontact"
                                wire:model.defer="documentcontact" wire:keydown.enter.prevent="searchcontact"
                                minlength="8" maxlength="8" onkeypress="return validarNumero(event, 8)"
                                onpaste="return validarPasteNumero(event, 8)" />
                            <x-button-add class="px-2" wire:click="searchcontact" wire:loading.attr="disabled">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.3-4.3" />
                                </svg>
                            </x-button-add>
                        </div>
                        <x-jet-input-error for="documentcontact" />
                    </div>
                    <div class="w-full">
                        <x-label value="Nombres :" />
                        <x-input class="block w-full" wire:model.defer="namecontact" />
                        <x-jet-input-error for="namecontact" />
                    </div>

                    <div class="w-full flex justify-end">
                        <x-button type="submit" wire:loading.attr="disabled">
                            {{ __('Add') }}
                        </x-button>
                    </div>
                </form>

                <x-label value="SELECCIONAR CONTACTO :" class="font-semibold !text-[10px] mt-5" />
                <div class="w-full grid gap-2 grid-cols-[repeat(auto-fill,minmax(120px,1fr))]">
                    <div>
                        <input class="sr-only peer peer-disabled:opacity-25" type="radio" id="empty_contact"
                            name="cliecontacts" value="" x-on:change="updatecontact('', '')" />
                        <label for="empty_contact"
                            class="text-[10px] h-full !leading-tight relative flex flex-col justify-center items-center input-radio-button">
                            NINGUNO
                        </label>
                    </div>

                    @foreach ($contacts as $item)
                        <div>
                            <input class="sr-only peer peer-disabled:opacity-25" type="radio"
                                id="contact_{{ $item['document'] }}" name="cliecontacts"
                                value="{{ $item['document'] }}" wire:key="{{ $item['document'] }}"
                                {{ $item['document'] == $contact['document'] ? 'checked' : null }}
                                x-on:change="updatecontact('{{ $item['document'] }}', '{{ $item['name'] }}')" />
                            <label for="contact_{{ $item['document'] }}"
                                class="text-[10px] h-full !leading-tight relative flex flex-col justify-center items-center input-radio-button">
                                <x-icons.agenda class="size-12" />
                                {{ $item['document'] }}
                                <br>
                                {{ $item['name'] }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="w-full flex pt-4 gap-2 justify-end">
                <x-button type="button" wire:loading.attr="disabled" wire:click="confirmcontact">
                    {{ __('Confirm') }}
                </x-button>
            </div>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('createticket', () => ({
                document: @entangle('document').defer,
                name: @entangle('name').defer,
                direccion: @entangle('direccion').defer,
                typeequipo_id: @entangle('typeequipo_id').defer,
                marca_id: @entangle('marca_id').defer,
                stateinicial: @entangle('stateinicial').defer,
                ubigeo_id: @entangle('ubigeo_id').defer,

                init() {
                    // Livewire.hook('message.processed', () => {});
                },
                updatecontact(document = '', name = '') {
                    this.$wire.set('contact', {
                        document,
                        name
                    }, true);
                }
            }));
        })

        // window.addEventListener('print-tickets', event => {
        //     const series = event.detail.series;
        //     series.forEach(serie => {
        //         const url = "{{ route('admin.soporte.tickets.print.registro', ':seriecompleta') }}"
        //             .replace(':seriecompleta', serie);
        //         console.log(url);
        //         window.open(url, '_blank');
        //     });
        // });

        function select2TypeEquipo() {
            this.selectTTE = $(this.$refs.selecttypeequipo).select2();
            this.selectTTE.val(this.typeequipo_id).trigger("change");
            this.selectTTE.on("select2:select", (event) => {
                this.typeequipo_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("typeequipo_id", (value) => {
                this.selectTTE.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectTTE.select2().val(this.typeequipo_id).trigger('change');
            });
        }

        function select2Marca() {
            this.selectTM = $(this.$refs.selectmarca).select2();
            this.selectTM.val(this.marca_id).trigger("change");
            this.selectTM.on("select2:select", (event) => {
                this.marca_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("marca_id", (value) => {
                this.selectTM.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectTM.select2().val(this.marca_id).trigger('change');
            });
        }

        function select2Estado() {
            this.selectTEE = $(this.$refs.selectestadoequipo).select2();
            this.selectTEE.val(this.stateinicial).trigger("change");
            this.selectTEE.on("select2:select", (event) => {
                this.stateinicial = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("stateinicial", (value) => {
                this.selectTEE.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectTEE.select2().val(this.stateinicial).trigger('change');
            });
        }

        function select2TUbigeo() {
            this.selectTU = $(this.$refs.selectubigeo).select2();
            this.selectTU.val(this.ubigeo_id).trigger("change");
            this.selectTU.on("select2:select", (event) => {
                this.ubigeo_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("ubigeo_id", (value) => {
                this.selectTU.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectTU.select2().val(this.ubigeo_id).trigger('change');
            });
        }
    </script>
</div>
