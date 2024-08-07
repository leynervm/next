<div x-data="showuser">
    <form class="w-full flex flex-col gap-8 max-w-lg mx-auto" wire:submit.prevent="update">
        @if ($user->isAdmin())
            <div
                class="w-full shadow rounded text-center font-semibold text-xs tracking-widest p-3 text-next-500 border border-next-500">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" fill="none" class="w-6 h-6 inline-block">
                    <path
                        d="M17 8L18.8398 9.85008C19.6133 10.6279 20 11.0168 20 11.5C20 11.9832 19.6133 12.3721 18.8398 13.1499L17 15" />
                    <path
                        d="M7 8L5.16019 9.85008C4.38673 10.6279 4 11.0168 4 11.5C4 11.9832 4.38673 12.3721 5.16019 13.1499L7 15" />
                    <path d="M14.5 4L9.5 20" />
                </svg>
                USUARIO DESARROLLADOR
            </div>
        @endif

        <x-form-card titulo="PERFIL USUARIO">
            <div class="w-full flex flex-col gap-2">
                <div class="w-full col-span-2 md:col-span-1">
                    <x-label value="Documento :" />
                    <div class="w-full inline-flex gap-1">
                        <x-disabled-text :text="$user->document" class="w-full" />
                    </div>
                    <x-jet-input-error for="user.document" />
                </div>
                <div class="w-full">
                    <x-label value="Nombres completos :" />
                    <x-input class="block w-full" name="name" wire:model.defer="user.name"
                        placeholder="Nombres del usuario..." />
                    <x-jet-input-error for="user.name" />
                </div>
                <div class="w-full">
                    <x-label value="Correo :" />
                    <x-input class="block w-full" name="email" wire:model.defer="user.email"
                        placeholder="Correo del usuario..." />
                    <x-jet-input-error for="user.email" />
                </div>

                <div class="w-full">
                    <x-label value="Local y/o sucursal :" />
                    <div class="relative" id="parentusersuc" x-init="select2Sucursal">
                        <x-select class="block w-full" x-ref="selectsucursal" id="usersuc" data-placeholder="null">
                            <x-slot name="options">
                                @if (count($sucursales))
                                    @foreach ($sucursales as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <small class="block text-colorerror text-xs text-end">Permitirá el acceso al panel
                        administrativo</small>
                    <x-jet-input-error for="user.sucursal_id" />
                </div>

            </div>

            @if ($user->email_verified_at)
                <div class="mt-2 w-full flex gap-1 items-end">
                    <x-icon-default class="inline-block" />
                    <small class="text-colorlabel text-[9px] uppercase">Verificado el,
                        {{ \Carbon\Carbon::parse($user->email_verified_at)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY [a las] hh:mm A') }}</small>
                </div>
            @endif

            @if (Module::isEnabled('Employer'))
                @if (empty($user->employer))
                    <div class="mt-2">
                        <x-label-check for="addemployer">
                            <x-input x-model="addemployer" name="addemployer" type="checkbox" id="addemployer" />
                            AGREGAR DATOS DEL PERSONAL</x-label-check>
                    </div>

                    <div class="mt-2" x-show="addemployer == false">
                        <x-button-next titulo="SINCRONIZAR PERSONAL" wire:click="searchemployer"
                            classTitulo="text-[10px] font-semibold" wire:loading.attr="disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" wire:loading.class="animate-spin">
                                <path
                                    d="M15.1667 0.999756L15.7646 2.11753C16.1689 2.87322 16.371 3.25107 16.2374 3.41289C16.1037 3.57471 15.6635 3.44402 14.7831 3.18264C13.9029 2.92131 12.9684 2.78071 12 2.78071C6.75329 2.78071 2.5 6.90822 2.5 11.9998C2.5 13.6789 2.96262 15.2533 3.77093 16.6093M8.83333 22.9998L8.23536 21.882C7.83108 21.1263 7.62894 20.7484 7.7626 20.5866C7.89627 20.4248 8.33649 20.5555 9.21689 20.8169C10.0971 21.0782 11.0316 21.2188 12 21.2188C17.2467 21.2188 21.5 17.0913 21.5 11.9998C21.5 10.3206 21.0374 8.74623 20.2291 7.39023" />
                            </svg>
                        </x-button-next>
                    </div>
                @endif
            @endif
        </x-form-card>

        <x-form-card x-show="showroles" titulo="ROLES" class="animate__animated animate__fadeInDown animate__faster">
            <div class="w-full">
                @if (count($roles))
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

        {{-- <x-form-card x-show="showroles" titulo="PERMISOS ADICIONALES"
            class="animate__animated animate__fadeInDown animate__faster">
        </x-form-card> --}}


        @if (Module::isEnabled('Employer'))
            @if ($user->employer)
                <x-form-card titulo="PERFIL TRABAJADOR" class="animate__animated animate__fadeInDown animate__faster">
                    <div class="w-full flex flex-col gap-1 rounded-md cursor-defaul">
                        <div class="w-full">
                            <h1 class="font-semibold text-sm leading-4 text-primary">
                                {{ $user->employer->name }}</h1>

                            <h1 class="text-colorlabel font-medium text-xs">
                                SUCURSAL : {{ $user->employer->sucursal->name }}
                                @if ($user->employer->sucursal->trashed())
                                    <x-span-text text="NO DISPONIBLE" class="leading-3 !tracking-normal inline-block" />
                                @endif
                            </h1>

                            @if ($user->employer->areawork)
                                <h1 class="text-colorlabel font-medium text-xs">
                                    AREA DE TRABAJO : {{ $user->employer->areawork->name }}</h1>
                            @endif

                            @if ($user->employer->turno)
                                <div class="w-full font-semibold text-colorlabel text-2xl flex flex-wrap gap-x-5">
                                    <p class="inline-block leading-normal">
                                        <small class="text-[10px] font-medium">INGRESO :</small>
                                        {{ formatDate($user->employer->turno->horaingreso, 'hh:mm ') }}
                                        <small
                                            class="text-[10px] font-medium">{{ formatDate($user->employer->turno->horaingreso, 'A') }}</small>
                                    </p>
                                    <p class="inline-block leading-normal">
                                        <small class="text-[10px] font-medium">SALIDA :</small>
                                        {{ formatDate($user->employer->turno->horasalida, 'hh:mm ') }}
                                        <small
                                            class="text-[10px] font-medium">{{ formatDate($user->employer->turno->horasalida, 'A') }}</small>
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div class="w-full flex justify-end">
                            <x-button-delete onclick="desvicularEmployer()" wire:loading.attr="disabled" />
                        </div>
                    </div>
                </x-form-card>
            @else
                <x-form-card titulo="PERFIL TRABAJADOR" style="display: none;" x-show="addemployer"
                    class="animate__animated animate__fadeInDown animate__faster">
                    <div class="w-full flex flex-col gap-2">
                        <div class="w-full">
                            <x-label value="Género :" />
                            <div class="relative" id="parentsexoemp_id" x-data="{ sexo: @entangle('sexo').defer }"
                                x-init="select2Sexo" wire:ignore>
                                <x-select class="block w-full" id="sexoemp_id" x-ref="select">
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
                </x-form-card>
            @endif
        @endif


        @if (Module::isEnabled('Marketplace'))
            <x-form-card titulo="DATOS CLIENTE VINCULADO">
                <div class="w-full">
                    @if ($user->client)
                        <x-simple-card class="w-48 flex flex-col gap-1 rounded-xl cursor-default p-3 py-5">
                            <h1 class="font-semibold text-sm leading-4 text-primary text-center">
                                {{ $user->client->name }}</h1>

                            <x-label :value="$user->client->document" class="font-semibold text-center" />

                            @if ($user->client->pricetype)
                                <p class="text-xs text-center text-colorsubtitleform">
                                    {{ $user->client->pricetype->name }}</p>
                            @endif
                        </x-simple-card>
                    @else
                        @if ($client)
                            <x-simple-card class="w-48 mt-3 flex flex-col gap-1 rounded-xl cursor-default p-3 py-5">
                                <x-button-next class="mx-auto" titulo="SINCRONIZAR CLIENTE"
                                    wire:click="sincronizeclient" classTitulo="text-[10px] font-semibold"
                                    wire:loading.attr="disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" wire:loading.class="animate-spin">
                                        <path
                                            d="M15.1667 0.999756L15.7646 2.11753C16.1689 2.87322 16.371 3.25107 16.2374 3.41289C16.1037 3.57471 15.6635 3.44402 14.7831 3.18264C13.9029 2.92131 12.9684 2.78071 12 2.78071C6.75329 2.78071 2.5 6.90822 2.5 11.9998C2.5 13.6789 2.96262 15.2533 3.77093 16.6093M8.83333 22.9998L8.23536 21.882C7.83108 21.1263 7.62894 20.7484 7.7626 20.5866C7.89627 20.4248 8.33649 20.5555 9.21689 20.8169C10.0971 21.0782 11.0316 21.2188 12 21.2188C17.2467 21.2188 21.5 17.0913 21.5 11.9998C21.5 10.3206 21.0374 8.74623 20.2291 7.39023" />
                                    </svg>
                                </x-button-next>

                                <h1 class="font-semibold text-sm leading-4 text-primary text-center">
                                    {{ $client->name }}</h1>

                                <x-label :value="$client->document" class="font-semibold text-center" />

                                <p class="text-xs text-center">{{ $client->email }}</p>
                            </x-simple-card>
                        @endif
                    @endif
                </div>
            </x-form-card>
        @endif

        <div class="w-full flex pt-4 justify-end">
            <x-button type="submit" wire:loading.attr="disabled">
                {{ __('Save') }}</x-button>
        </div>
    </form>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('showuser', () => ({
                sucursal_id: @entangle('user.sucursal_id').defer,
                addemployer: @entangle('addemployer').defer,
                showroles: false,
                init() {
                    if (this.sucursal_id == "" || this.sucursal_id == undefined) {
                        this.showroles = false;
                    } else {
                        this.showroles = true;
                    }
                }
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
                if (this.sucursal_id == "" || this.sucursal_id == undefined) {
                    this.showroles = false;
                } else {
                    this.showroles = true;
                }
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

        function desvicularEmployer() {
            swal.fire({
                title: 'Desvincular personal vinculado al usuario ?',
                text: "El personal dejará de estar disponible para el usuario vinculado, y no podrá realizar algunas funciones en el sistema.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deleteemployer();
                }
            })
        }
    </script>
</div>
