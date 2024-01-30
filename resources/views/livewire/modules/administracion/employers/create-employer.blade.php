<div>
    <x-button-next titulo="Registrar" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo personal') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="relative" x-data="data">
                <div class="w-full grid xs:grid-cols-2 gap-2">
                    <div class="w-full">
                        <x-label value="DNI :" />
                        <div class="w-full inline-flex gap-1">
                            <x-input class="block w-full prevent" wire:model.defer="document"
                                wire:keydown.enter="getClient" placeholder="DNI..." />
                            <x-button-add class="px-2" wire:click="getClient" wire:loading.attr="disabled"
                                wire:target="getClient">
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

                    <div class="w-full xs:col-span-2">
                        <x-label value="Nombres completos :" />
                        <x-input class="block w-full" wire:model.defer="name" placeholder="Nombres del personal..." />
                        <x-jet-input-error for="name" />
                    </div>

                    <div class="w-full">
                        <x-label value="Género :" />
                        <div class="relative" id="parentsexoemp_id" x-data="{ sexo: @entangle('sexo').defer }" x-init="select2Sexo"
                            wire:ignore>
                            <x-select class="block w-full" wire:model.defer="sexo" id="sexoemp_id"
                                data-dropdown-parent="null" x-ref="select">
                                <x-slot name="options">
                                    <option value="M">MASCULINO</option>
                                    <option value="F">FEMENINO</option>
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="sexo" />
                    </div>

                    <div class="w-full">
                        <x-label value="Fecha nacimiento :" />
                        <x-input type="date" class="block w-full" wire:model.defer="nacimiento"
                            placeholder="Correo del cliente..." />
                        <x-jet-input-error for="nacimiento" />
                    </div>

                    <div class="w-full">
                        <x-label value="Teléfono :" />
                        <x-input class="block w-full" wire:model.defer="telefono" placeholder="+51 999 999 999"
                            maxlength="9" />
                        <x-jet-input-error for="telefono" />
                    </div>

                    <div class="w-full">
                        <x-label value="Sueldo :" />
                        <x-input class="block w-full" wire:model.defer="sueldo" type="numeric" placeholder="0.00" />
                        <x-jet-input-error for="sueldo" />
                    </div>

                    <div class="w-full">
                        <x-label value="Hora ingreso :" />
                        <x-input class="block w-full" wire:model.defer="horaingreso" type="time" />
                        <x-jet-input-error for="horaingreso" />
                    </div>

                    <div class="w-full">
                        <x-label value="Hora salida :" />
                        <x-input class="block w-full" wire:model.defer="horasalida" type="time" />
                        <x-jet-input-error for="horasalida" />
                    </div>

                    <div class="w-full xs:grid-cols-2">
                        <x-label value="Área de trabajo :" />
                        <div class="relative" id="parentareawork" x-data="{ areawork_id: @entangle('areawork_id').defer }" x-init="select2Areawork"
                            wire:ignore>
                            <x-select class="block w-full" wire:model.defer="areawork_id" x-ref="selecta"
                                id="areawork" data-dropdown-parent="null">
                                <x-slot name="options">
                                    @if (count($areaworks))
                                        @foreach ($areaworks as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="areawork_id" />
                    </div>

                    <div class="w-full xs:grid-cols-2">
                        <x-label value="Sucursal :" />
                        <div class="relative" x-data="{ sucursal_id: @entangle('sucursal_id').defer }" x-init="select2Sucursal" wire:ignore>
                            <x-select class="block w-full" x-ref="selectsuc" wire:model.defer="sucursal_id"
                                id="ubigeoclient_id" data-minimum-results-for-search="3" data-dropdown-parent="null">
                                <x-slot name="options">
                                    @if (count($sucursals))
                                        @foreach ($sucursals as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="sucursal_id" />
                    </div>
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>

                <div wire:loading.flex class="loading-overlay rounded hidden">
                    <x-loading-next />
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        function select2Sucursal() {
            this.selectSuc = $(this.$refs.selectsuc).select2();
            this.selectSuc.val(this.sucursal_id).trigger("change");
            this.selectSuc.on("select2:select", (event) => {
                this.sucursal_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("sucursal_id", (value) => {
                this.selectSuc.val(value).trigger("change");
            });
        }

        function select2Sexo() {
            this.selectS = $(this.$refs.select).select2();
            this.selectS.val(this.sexo).trigger("change");
            this.selectS.on("select2:select", (event) => {
                this.sexo = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("sexo", (value) => {
                this.selectS.val(value).trigger("change");
            });
        }


        function select2Areawork() {
            this.selectA = $(this.$refs.selecta).select2();
            this.selectA.val(this.areawork_id).trigger("change");
            this.selectA.on("select2:select", (event) => {
                this.areawork_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("areawork_id", (value) => {
                this.selectA.val(value).trigger("change");
            });
        }


        document.addEventListener('alpine:init', () => {
            Alpine.data('data', () => ({
                contact: false,
                document: @entangle('document').defer,

                toggle() {
                    if (this.document.trim().length == 11) {
                        this.contact = true;
                    } else {
                        this.contact = false;
                    }
                }
            }))
        });


        // window.addEventListener('render-client-select2', () => {
        //     renderSelect2();
        // });

        document.addEventListener("livewire:load", () => {

            // renderSelect2();

            // $("#ubigeoclient_id").on("change", (e) => {
            //     deshabilitarSelects();
            //     @this.ubigeo_id = e.target.value;
            // });

            // $("#pricetype_id").on("change", (e) => {
            //     deshabilitarSelects();
            //     @this.pricetype_id = e.target.value;
            // });

            // $("#sexoclient_id").on("change", (e) => {
            //     deshabilitarSelects();
            //     @this.sexo = e.target.value;
            // });



            // function renderSelect2() {
            //     var formulario = document.getElementById("form_create_client");
            //     var selects = formulario.getElementsByTagName("select");

            //     for (var i = 0; i < selects.length; i++) {
            //         if (selects[i].id !== "") {
            //             $("#" + selects[i].id).select2();
            //         }
            //     }
            // }

            // function deshabilitarSelects() {
            //     var formulario = document.getElementById("form_create_client");
            //     var selects = formulario.getElementsByTagName("select");

            //     for (var i = 0; i < selects.length; i++) {
            //         selects[i].disabled = true;
            //     }
            // }

        })
    </script>


</div>
