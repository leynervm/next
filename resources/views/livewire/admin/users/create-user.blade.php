<div x-data="createuser">
    <form class="w-full flex flex-col gap-8 max-w-lg mx-auto" wire:submit.prevent="save">
        <x-form-card titulo="PERFIL USUARIO">
            <div class="w-full flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Documento :" />
                    <div class="w-full inline-flex gap-1">
                        <x-input class="block w-full flex-1 input-number-none" wire:model.defer="document"
                            wire:keydown.enter.prevent="searchclient" onkeypress="return validarNumero(event, 11)"
                            type="number" onkeydown="disabledEnter(event)" />
                        <x-button-add class="px-2 flex-shrink-0" wire:click="searchclient">
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

                <div class="w-full">
                    <x-label value="Nombres completos :" />
                    <x-input class="block w-full" name="name" wire:model.defer="name"
                        placeholder="Nombres del usuario..." />
                    <x-jet-input-error for="name" />
                </div>
                <div class="w-full">
                    <x-label value="Correo :" />
                    <x-input class="block w-full" name="email" wire:model.defer="email"
                        placeholder="Correo del usuario..." />
                    <x-jet-input-error for="email" />
                </div>

                <div class="w-full">
                    <x-label value="Contraseña :" />
                    <x-input type="password" class="block w-full" name="password" wire:model.defer="password"
                        placeholder="Ingrese contraseña..." />
                    <x-jet-input-error for="password" />
                </div>
                <div class="w-full">
                    <x-label value="Confirmar contraseña :" />
                    <x-input type="password" class="block w-full" name="password_confirmation"
                        wire:model.defer="password_confirmation" placeholder="Confirmar contraseña..." />
                    <x-jet-input-error for="password_confirmation" />
                </div>

                <div class="w-full">
                    <x-label value="Local y/o sucursal :" />
                    <div class="relative" id="parentusersuc" x-data="{ sucursal_id: @entangle('sucursal_id').defer }" x-init="select2Sucursal">
                        <x-select class="block w-full" data-placeholder="null" x-ref="selectsucursal" id="usersuc">
                            <x-slot name="options">
                                @if (count($sucursales) > 0)
                                    @foreach ($sucursales as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <small class=" block text-colorerror text-xs text-end">Permitirá el acceso al panel
                        administrativo</small>
                    <x-jet-input-error for="sucursal_id" />
                </div>
            </div>

            @if (Module::isEnabled('Employer'))
                <div class="mt-2">
                    <x-label-check for="addemployer">
                        <x-input wire:model.defer="addemployer" x-model="show" name="addemployer" type="checkbox"
                            id="addemployer" />
                        AGREGAR DATOS DEL TRABAJADOR
                    </x-label-check>
                </div>
            @endif
        </x-form-card>

        <x-form-card titulo="ROLES" style="display: none;" x-show="showroles"
            class="animate__animated animate__fadeInDown animate__faster">
            <div class="w-full">
                @if (count($roles) > 0)
                    <div class="w-full flex flex-wrap gap-2">
                        @foreach ($roles as $item)
                            <x-input-radio class="py-2" :for="'role_' . $item->id" :text="$item->name">
                                <input class="sr-only peer peer-disabled:opacity-25" type="checkbox"
                                    id="role_{{ $item->id }}" name="roles[]" value="{{ $item->id }}"
                                    wire:model.defer="selectedRoles" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="selectedRoles" />
            </div>
        </x-form-card>

        {{-- <x-form-card titulo="PERMISOS ADICIONALES" style="display: none;"
            class="animate__animated animate__fadeInDown animate__faster" x-show="showroles">
        </x-form-card> --}}


        @if (Module::isEnabled('Employer'))
            <x-form-card titulo="PERFIL TRABAJADOR" style="display: none;" x-show="show"
                class="animate__animated animate__fadeInDown animate__faster">
                @if ($employer)
                    <div class="w-full flex flex-col gap-1 rounded-md cursor-default">
                        <div class="w-full flex flex-col gap-1">
                            <h1 class="font-semibold text-sm leading-4 text-primary">
                                {{ $employer->name }}</h1>

                            <h1 class="text-colorlabel font-medium text-xs">
                                SUCURSAL : {{ $employer->sucursal->name }}
                                @if ($employer->sucursal->trashed())
                                    <x-span-text text="NO DISPONIBLE"
                                        class="leading-3 !tracking-normal inline-block" />
                                @endif
                            </h1>

                            @if ($employer->areawork)
                                <h1 class="text-colorlabel font-medium text-xs">
                                    AREA DE TRABAJO : {{ $employer->areawork->name }}</h1>
                            @endif

                            <div class="w-full font-semibold text-colorlabel text-2xl flex flex-wrap gap-x-5">
                                <p class="inline-block leading-normal">
                                    <small class="text-[10px] font-medium">INGRESO :</small>
                                    {{ formatDate($employer->horaingreso, 'HH:mm ') }}
                                    <small
                                        class="text-[10px] font-medium">{{ formatDate($employer->horaingreso, 'A') }}</small>
                                </p>
                                <p class="inline-block leading-normal">
                                    <small class="text-[10px] font-medium">SALIDA :</small>
                                    {{ formatDate($employer->horasalida, 'HH:mm ') }}
                                    <small
                                        class="text-[10px] font-medium">{{ formatDate($employer->horasalida, 'A') }}</small>
                                </p>
                            </div>
                        </div>
                        <div class="w-full flex justify-end">
                            <x-button-delete wire:click="deleteemployer" wire:loading.attr="disabled" />
                        </div>
                    </div>
                @else
                    <div class="w-full flex flex-col gap-2">
                        <div class="w-full">
                            <x-label value="Género :" />
                            <div class="relative" id="parentsexoemp_id" x-data="{ sexo: @entangle('sexo').defer }"
                                x-init="select2Sexo" wire:ignore>
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
                            <x-input class="block w-full" wire:model.defer="telefono" placeholder="" maxlength="9"
                                onkeypress="return validarNumero(event, 9)" />
                            <x-jet-input-error for="telefono" />
                        </div>

                        <div class="w-full">
                            <x-label value="Sueldo :" />
                            <x-input class="block w-full" wire:model.defer="sueldo" type="number"
                                placeholder="0.00" onkeypress="return validarDecimal(event, 9)" />
                            <x-jet-input-error for="sueldo" />
                        </div>

                        {{-- <div class="w-full">
                            <x-label value="Hora ingreso :" />
                            <x-input class="block w-full" wire:model.defer="horaingreso" type="time" />
                            <x-jet-input-error for="horaingreso" />
                        </div>

                        <div class="w-full">
                            <x-label value="Hora salida :" />
                            <x-input class="block w-full" wire:model.defer="horasalida" type="time" />
                            <x-jet-input-error for="horasalida" />
                        </div> --}}

                        <div class="w-full">
                            <x-label value="Turno laboral :" />
                            <div class="relative" id="parentturnoemployer_id" x-data="{ turno_id: @entangle('turno_id').defer }"
                                x-init="select2Turno">
                                <x-select class="block w-full" x-ref="selectturno" id="turnoemployer_id"
                                    data-dropdown-parent="null">
                                    <x-slot name="options">
                                        @if (count($turnos) > 0)
                                            @foreach ($turnos as $item)
                                                <option value="{{ $item->id }}"
                                                    title="{{ formatDate($item->horaingreso, 'hh:ss A') . ' - ' . formatDate($item->horasalida, 'hh:ss A') }}">
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </x-slot>
                                </x-select>
                                <x-icon-select />
                            </div>
                            <x-jet-input-error for="turno_id" />
                        </div>

                        <div class="w-full">
                            <x-label value="Área de trabajo :" />
                            <div class="relative" id="parentareaworkemp" x-data="{ areawork_id: @entangle('areawork_id').defer }"
                                x-init="select2Areawork" wire:ignore>
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
                    </div>
                @endif
            </x-form-card>
        @endif

        {{-- {{ print_r($errors->all()) }} --}}

        <div class="w-full flex gap-2 justify-end items-end">
            {{-- <button class="text-next-500 hover:text-next-700 text-xs underline transition ease-in-out duration-150"
                type="button" wire:click="$toggle('open')">YA ESTOY REGISTRADO</button> --}}
            <x-button type="submit" wire:loading.attr="disabled">
                {{ __('Save') }}</x-button>
        </div>
    </form>



    {{-- <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Asignar sucursal a usuario') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="buscarusuario" class="w-full flex flex-col gap-2">
                <div class="w-full">
                    <x-label value="Buscar correo :" />


                    @if ($userasign)
                        <div class="w-full inline-flex relative">
                            <x-disabled-text :text="$searchemail" class="w-full block" />
                            <x-button-close-modal
                                class="hover:animate-none !text-red-500 hover:!bg-transparent focus:!bg-transparent hover:!ring-0 focus:!ring-0 absolute right-0 top-1"
                                wire:click="limpiaruserasign" wire:loading.attr="disabled" />
                        </div>
                    @else
                        <div class="w-full inline-flex gap-1">
                            <x-input class="block w-full" wire:model.defer="searchemail"
                                wire:keydown.enter="buscarusuario" type="email" />
                            <x-button-add class="px-2" wire:click="buscarusuario" wire:loading.attr="disabled">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.3-4.3" />
                                </svg>
                            </x-button-add>
                        </div>
                    @endif
                    <x-jet-input-error for="searchemail" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('ASIGNAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal> --}}


    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('createuser', () => ({
                show: @entangle('addemployer').defer,
                showroles: false,
            }))
        })

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

        function select2Sucursal() {
            this.selectSC = $(this.$refs.selectsucursal).select2();
            this.selectSC.val(this.sucursal_id).trigger("change");
            this.selectSC.on("select2:select", (event) => {
                this.sucursal_id = event.target.value;
                if (this.sucursal_id == "" || this.sucursal_id == undefined) {
                    this.showroles = false;
                } else {
                    this.showroles = true;
                }
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("sucursal_id", (value) => {
                this.selectSC.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectSC.select2().val(this.sucursal_id).trigger('change');
            });
        }

        function formatOption(option) {
            var $option = $(
                '<strong>' + option.text + '</strong><p class="select2-subtitle-option">' + option.title + '</p>'
            );
            return $option;
        };

        function select2Turno() {
            this.selectTurno = $(this.$refs.selectturno).select2({
                templateResult: formatOption
            });
            this.selectTurno.val(this.turno_id).trigger("change");
            this.selectTurno.on("select2:select", (event) => {
                this.turno_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("turno_id", (value) => {
                this.selectTurno.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectTurno.select2({
                    templateResult: formatOption
                }).val(this.turno_id).trigger('change');
            });
        }
    </script>
</div>
