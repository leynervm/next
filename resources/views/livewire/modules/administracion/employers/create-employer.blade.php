<div x-data="createemployer">
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
        </x-slot>

        <x-slot name="content">
            <div wire:loading.flex class="loading-overlay fixed hidden">
                <x-loading-next />
            </div>

            <form wire:submit.prevent="save" class="relative block w-full">
                <div class="w-full grid xs:grid-cols-2 gap-2">
                    <div class="w-full">
                        <x-label value="DNI :" />
                        @if ($exists)
                            <div class="w-full inline-flex relative">
                                <x-disabled-text :text="$document" class="w-full block" />
                                <x-button-close-modal class="btn-desvincular" wire:click="limpiaremployer"
                                    wire:loading.attr="disabled" />
                            </div>
                        @else
                            <div class="w-full inline-flex gap-1">
                                <x-input class="block w-full flex-1 prevent" wire:model.defer="document"
                                    wire:keydown.enter="getClient" placeholder="DNI..." type="number"
                                    onkeypress="return validarNumero(event, 11)" onkeydown="disabledEnter(event)" />
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
                        @endif
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
                        <x-input class="block w-full" wire:model.defer="telefono" placeholder="" maxlength="9"
                            onkeypress="return validarNumero(event, 9)" />
                        <x-jet-input-error for="telefono" />
                    </div>

                    <div class="w-full">
                        <x-label value="Sueldo :" />
                        <x-input class="block w-full" wire:model.defer="sueldo" type="number" placeholder="0.00"
                            onkeypress="return validarDecimal(event, 9)" />
                        <x-jet-input-error for="sueldo" />
                    </div>

                    <div class="w-full xs:col-span-2">
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

                    <div class="w-full xs:col-span-2">
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

                    <div class="w-full xs:col-span-2">
                        <x-label value="Sucursal :" />
                        @if ($user)
                            @if ($user->sucursal)
                                <x-disabled-text :text="$user->sucursal->name" />
                            @else
                                <div class="relative" x-data="{ sucursal_id: @entangle('sucursal_id').defer }" x-init="select2Sucursal" wire:ignore>
                                    <x-select class="block w-full" x-ref="selectsuc" wire:model.defer="sucursal_id"
                                        id="sucursal_id" data-minimum-results-for-search="3"
                                        data-dropdown-parent="null">
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
                            @endif
                        @else
                            <div class="relative" x-data="{ sucursal_id: @entangle('sucursal_id').defer }" x-init="select2Sucursal" wire:ignore>
                                <x-select class="block w-full" x-ref="selectsuc" wire:model.defer="sucursal_id"
                                    id="sucursal_id" data-minimum-results-for-search="3" data-dropdown-parent="null">
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
                        @endif
                        <x-jet-input-error for="sucursal_id" />
                    </div>
                </div>

                <div class="w-full">
                    <div class="mt-2">
                        <x-label-check for="adduser">
                            <x-input wire:model.defer="adduser" x-model="adduser" name="adduser" type="checkbox"
                                id="adduser" />
                            AGREGAR DATOS DE ACCESO
                        </x-label-check>
                    </div>

                    <x-form-card titulo="PERFIL USUARIO ACCESO" x-show="adduser"
                        class="mt-5 animate__animated animate__fadeInDown animate__faster">
                        @if ($user)
                            <x-simple-card class="w-full flex flex-col gap-1 rounded-md cursor-default p-3">
                                <div class="w-full">
                                    <h1 class="font-semibold text-sm leading-4 text-primary">
                                        {{ $user->name }}</h1>

                                    <h1 class="text-colorlabel font-medium text-xs">
                                        CORREO : {{ $user->email }} </h1>
                                </div>
                                <div class="w-full flex justify-end">
                                    <x-button-delete wire:click="clearuser" wire:loading.attr="disabled"
                                        wire:key="{{ rand() }}" />
                                </div>
                                <x-jet-input-error for="email" />
                            </x-simple-card>
                        @else
                            <div class="w-full grid grid-cols-2 gap-2">
                                <div class="w-full">
                                    <x-label value="Correo :" />
                                    <x-input class="block w-full" name="email" wire:model.defer="email"
                                        placeholder="Correo de acceso..." />
                                    <x-jet-input-error for="email" />
                                </div>
                                <div class="w-full">
                                    <x-label value="Contraseña :" />
                                    <x-input type="password" class="block w-full" name="password"
                                        wire:model.defer="password" placeholder="Ingrese contraseña..." />
                                    <x-jet-input-error for="password" />
                                </div>
                                <div class="w-full">
                                    <x-label value="Confirmar contraseña :" />
                                    <x-input type="password" class="block w-full" name="password_confirmation"
                                        wire:model.defer="password_confirmation"
                                        placeholder="Confirmar contraseña..." />
                                    <x-jet-input-error for="password_confirmation" />
                                </div>
                            </div>
                        @endif
                    </x-form-card>

                    @if (!$user)
                        <x-form-card titulo="ROLES" x-show="adduser"
                            class="mt-5 animate__animated animate__fadeInDown animate__faster">
                            <div class="w-full">
                                @if (count($roles))
                                    <div class="w-full flex flex-wrap gap-2">
                                        @foreach ($roles as $item)
                                            <x-input-radio class="py-2" :for="'role_' . $item->id" :text="$item->name">
                                                <input class="sr-only peer peer-disabled:opacity-25" type="checkbox"
                                                    id="role_{{ $item->id }}" name="roles[]"
                                                    value="{{ $item->id }}" wire:model.defer="selectedRoles" />
                                            </x-input-radio>
                                        @endforeach
                                    </div>
                                @endif
                                <x-jet-input-error for="selectedRoles" />
                            </div>
                        </x-form-card>
                    @endif
                </div>

                <div class="w-full flex flex-wrap gap-1 pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled" class="inline-block">
                        {{ __('Save') }}</x-button>
                    <x-button wire:click="save(true)" wire:loading.attr="disabled" class="inline-block">
                        {{ __('Save and close') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('createemployer', () => ({
                adduser: @entangle('adduser').defer,
            }))
        })

        function formatOption(option) {
            var $option = $(
                '<strong>' + option.text + '</strong><p class="select2-subtitle-option">' + option.title + '</p>'
            );
            return $option;
        };

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
    </script>
</div>
