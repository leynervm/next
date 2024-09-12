<div>
    <div wire:loading.flex class="loading-overlay fixed hidden">
        <x-loading-next />
    </div>

    @if ($employers->hasPages())
        <div class="pb-2">
            {{ $employers->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex items-center gap-2 mt-4">
        <div class="w-full max-w-sm">
            <x-label value="Buscar personsal :" />
            <div class="relative flex items-center">
                <span class="absolute">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 mx-3 text-next-300">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </span>
                <x-input placeholder="Buscar documento, nombres del personal..." class="block w-full pl-9"
                    wire:model.lazy="search">
                </x-input>
            </div>
        </div>

        @if (count($sucursalusers) > 1)
            <div class="w-full xs:max-w-sm">
                <x-label value="Filtrar Sucursal :" />
                <div class="relative" id="parentsearchsucursal" x-data="{ searchsucursal: @entangle('searchsucursal') }" x-init="selectSearchsucursal"
                    wire:ignore>
                    <x-select class="block w-full" x-ref="searchsuc" wire:model.defer="searchsucursal"
                        id="searchsucursal" data-minimum-results-for-search="3" data-placeholder="null">
                        <x-slot name="options">
                            @foreach ($sucursalusers as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
                <x-jet-input-error for="searchsucursal" />
            </div>
        @endif
    </div>

    @can('admin.administracion.employers.showdeletes')
        <div class="w-full mt-1">
            <x-label-check for="eliminados">
                <x-input wire:model.lazy="deletes" name="deletes" value="true" type="checkbox" id="eliminados" />
                MOSTRAR PERSONAL ELIMINADOS
            </x-label-check>
        </div>
    @endcan

    <x-table class="mt-1">
        <x-slot name="header">
            <tr>
                <th scope="col" class="p-2 font-medium">
                    <button class="flex items-center gap-x-3 focus:outline-none">
                        <span>NOMBRES</span>

                        <svg class="h-3 w-3" viewBox="0 0 10 11" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" stroke="currentColor" stroke-width="0.1">
                            <path
                                d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z" />
                            <path
                                d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z" />
                            <path
                                d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z"
                                stroke-width="0.3" />
                        </svg>
                    </button>
                </th>
                <th scope="col" class="p-2 font-medium text-left">
                    AREA TRABAJO</th>
                <th scope="col" class="p-2 font-medium">
                    FECHA NACIMIENTO</th>
                <th scope="col" class="p-2 font-medium">
                    SUELDO</th>
                <th scope="col" class="p-2 font-medium">
                    TURNO</th>
                <th scope="col" class="p-2 font-medium">
                    SEXO</th>
                <th scope="col" class="p-2 font-medium text-center">
                    ACCESO</th>
                @can('admin.administracion.employers.payments')
                    <th scope="col" class="p-2 font-medium text-center">
                        TELÉFONO</th>
                @endcan
                <th scope="col" class="p-2 font-medium text-center">
                    PAGOS</th>
                <th scope="col" class="p-2 font-medium text-center">
                    SUCURSAL</th>
                <th scope="col" class="p-2 relative">
                    <span class="sr-only">OPCIONES</span>
                </th>
            </tr>
        </x-slot>
        @if (count($employers) > 0)
            <x-slot name="body">
                @foreach ($employers as $item)
                    <tr>
                        <td class="p-2">
                            <p>{{ $item->name }}</p>
                            <p>{{ $item->document }}</p>
                        </td>
                        <td class="p-2">
                            @if ($item->areawork)
                                <p>{{ $item->areawork->name }}</p>
                            @endif
                        </td>
                        <td class="p-2 text-center">
                            {{ formatDate($item->nacimiento, 'DD MMMM Y') }}
                        </td>
                        <td class="p-2 text-center">
                            {{ number_format($item->sueldo, 2, '.', ', ') }}
                        </td>
                        <td class="p-2 text-center">
                            @if ($item->turno)
                                <p>{{ $item->turno->name }}</p>
                                <p class="text-colorsubtitleform text-[10px]">
                                    {{ formatDate($item->turno->horaingreso, 'hh:ss A') }}
                                    -
                                    {{ formatDate($item->turno->horasalida, 'hh:ss A') }}
                                </p>
                            @endif
                        </td>
                        <td class="p-2 text-center">
                            {{ $item->sexo }}
                        </td>
                        <td class="p-2 text-center">
                            @if ($item->user)
                                <x-span-text text="DASHBOARD" type="next"
                                    class="leading-3 !tracking-normal inline-block" />
                            @endif
                        </td>
                        <td class="p-2 align-middle text-center">
                            @if ($item->telephone)
                                <div
                                    class="inline-flex items-center justify-center gap-1 bg-fondospancardproduct text-textspancardproduct p-1 rounded">
                                    <span class="w-3 h-3 block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                        </svg>
                                    </span>
                                    <span
                                        class="text-[10px] whitespace-nowrap">{{ formatTelefono($item->telephone->phone) }}</span>
                                </div>
                            @endif
                        </td>

                        @can('admin.administracion.employers.payments')
                            <td class="p-2 text-center">
                                @can('sucursal', $item)
                                    @if (!$item->trashed())
                                        <x-link-button href="{{ route('admin.administracion.employers.payments', $item) }}"
                                            class="inline-block">PAGOS</x-link-button>
                                    @endif
                                @endcan
                            </td>
                        @endcan

                        <td class="p-2 text-center">
                            {{ $item->sucursal->name }}
                        </td>
                        <td class="p-2 whitespace-nowrap align-middle text-center">
                            @if ($item->trashed())
                                @can('admin.administracion.employers.restore')
                                    <x-button-toggle :checked="false" onclick="confirmRestore({{ $item }})"
                                        wire:loading.attr="disabled" />
                                @endcan
                            @else
                                @can('admin.administracion.employers.edit')
                                    <x-button-edit wire:click="edit({{ $item->id }})" wire:loading.attr="disabled"
                                        wire:key="editemployer_{{ $item->id }}" />
                                @endcan

                                @can('admin.administracion.employers.delete')
                                    <x-button-toggle onclick="confirmDelete({{ $item }})"
                                        wire:loading.attr="disabled" />
                                @endcan
                            @endif
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        @endif
    </x-table>

    <x-jet-dialog-modal wire:model="open" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar personal') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="relative">
                <div class="w-full grid xs:grid-cols-2 gap-2">

                    <div class="text-colorlabel w-full xs:col-span-2">
                        <h1 class="font-semibold text-sm leading-4">
                            <span class="text-3xl">{{ $employer->name }}</span>
                            {{ $employer->document }}
                        </h1>
                    </div>

                    <div class="w-full">
                        <x-label value="Género :" />
                        <div class="relative" id="parenteditsexoemp_id" x-data="{ sexoedit: @entangle('employer.sexo').defer }"
                            x-init="selectSexo">
                            <x-select class="block w-full" id="editsexoemp_id" data-dropdown-parent="null"
                                x-ref="editselectsexo">
                                <x-slot name="options">
                                    <option value="M">MASCULINO</option>
                                    <option value="F">FEMENINO</option>
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="employer.sexo" />
                    </div>

                    <div class="w-full">
                        <x-label value="Fecha nacimiento :" />
                        <x-input type="date" class="block w-full" wire:model.defer="employer.nacimiento"
                            placeholder="Correo del cliente..." />
                        <x-jet-input-error for="employer.nacimiento" />
                    </div>

                    <div class="w-full">
                        <x-label value="Teléfono :" />
                        <x-input class="block w-full" wire:model.defer="telefono" placeholder="" type="number"
                            maxlength="9" onkeypress="return validarNumero(event, 9)" />
                        <x-jet-input-error for="telefono" />
                    </div>

                    <div class="w-full">
                        <x-label value="Sueldo :" />
                        <x-input class="block w-full" wire:model.defer="employer.sueldo" type="number"
                            placeholder="0.00" onkeypress="return validarDecimal(event, 9)" />
                        <x-jet-input-error for="employer.sueldo" />
                    </div>

                    <div class="w-full xs:col-span-2">
                        <x-label value="Turno laboral :" />
                        <div class="relative" id="parentturnoe_id" x-data="{ turno_id: @entangle('employer.turno_id').defer }" x-init="select2TurnoE">
                            <x-select class="block w-full" x-ref="selectturno" id="turnoe_id"
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
                        <x-jet-input-error for="employer.turno_id" />
                    </div>


                    <div class="w-full xs:col-span-2">
                        <x-label value="Área de trabajo :" />
                        <div class="relative" id="parenteditareawork" x-data="{ areawork_id: @entangle('employer.areawork_id').defer }"
                            x-init="selectAreawork" wire:ignore>
                            <x-select class="block w-full" wire:model.defer="employer.areawork_id"
                                x-ref="editselecta" id="editareawork" data-dropdown-parent="null">
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
                        <x-jet-input-error for="employer.areawork_id" />
                    </div>

                    <div class="w-full xs:col-span-2">
                        <x-label value="Sucursal :" />
                        <div class="relative" x-data="{ sucursal_id: @entangle('employer.sucursal_id').defer }" x-init="selectSucursal" wire:ignore>
                            <x-select class="block w-full" x-ref="editselectsuc"
                                wire:model.defer="employer.sucursal_id" id="editsucursal_id"
                                data-minimum-results-for-search="3" data-dropdown-parent="null">
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
                        <x-jet-input-error for="employer.sucursal_id" />
                    </div>
                </div>

                <x-form-card titulo="PERFIL USUARIO ACCESO"
                    class="mt-5 animate__animated animate__fadeInDown animate__faster">
                    @if ($employer->user)
                        <div class="w-full flex flex-col gap-1 rounded-md cursor-default">
                            <div class="w-full flex flex-col gap-1">
                                <h1 class="text-colorlabel w-full inline-flex gap-1 items-center text-xs">
                                    <span class="w-4 h-4 block">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"
                                            fill="none" class="w-full h-full">
                                            <path d="M14 9H18" />
                                            <path d="M14 12.5H17" />
                                            <rect x="2" y="3" width="20" height="18" rx="5" />
                                            <path d="M5 16C6.20831 13.4189 10.7122 13.2491 12 16" />
                                            <path
                                                d="M10.5 9C10.5 10.1046 9.60457 11 8.5 11C7.39543 11 6.5 10.1046 6.5 9C6.5 7.89543 7.39543 7 8.5 7C9.60457 7 10.5 7.89543 10.5 9Z" />
                                        </svg>
                                    </span>
                                    {{ $employer->user->name }}
                                </h1>

                                <h1 class="text-colorlabel inline-flex gap-1 items-center font-medium text-xs">
                                    <span class="w-4 h-4 block">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"
                                            fill="none" class="w-full h-full">
                                            <path
                                                d="M2 6L8.91302 9.91697C11.4616 11.361 12.5384 11.361 15.087 9.91697L22 6" />
                                            <path
                                                d="M2.01577 13.4756C2.08114 16.5412 2.11383 18.0739 3.24496 19.2094C4.37608 20.3448 5.95033 20.3843 9.09883 20.4634C11.0393 20.5122 12.9607 20.5122 14.9012 20.4634C18.0497 20.3843 19.6239 20.3448 20.7551 19.2094C21.8862 18.0739 21.9189 16.5412 21.9842 13.4756C22.0053 12.4899 22.0053 11.5101 21.9842 10.5244C21.9189 7.45886 21.8862 5.92609 20.7551 4.79066C19.6239 3.65523 18.0497 3.61568 14.9012 3.53657C12.9607 3.48781 11.0393 3.48781 9.09882 3.53656C5.95033 3.61566 4.37608 3.65521 3.24495 4.79065C2.11382 5.92608 2.08114 7.45885 2.01576 10.5244C1.99474 11.5101 1.99475 12.4899 2.01577 13.4756Z" />
                                        </svg>
                                    </span>
                                    {{ $employer->user->email }}
                                </h1>
                            </div>
                            <div class="w-full flex justify-end">
                                <x-button-delete onclick="desvincularuseremployer({{ $employer->id }})"
                                    wire:loading.attr="disabled" wire:key="{{ rand() }}" />
                            </div>
                        </div>
                    @else
                        <x-button-next titulo="SINCRONIZAR USUARIO" wire:click="getClient"
                            classTitulo="text-[10px] font-semibold" wire:loading.attr="disabled">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" wire:loading.class="animate-spin">
                                <path
                                    d="M15.1667 0.999756L15.7646 2.11753C16.1689 2.87322 16.371 3.25107 16.2374 3.41289C16.1037 3.57471 15.6635 3.44402 14.7831 3.18264C13.9029 2.92131 12.9684 2.78071 12 2.78071C6.75329 2.78071 2.5 6.90822 2.5 11.9998C2.5 13.6789 2.96262 15.2533 3.77093 16.6093M8.83333 22.9998L8.23536 21.882C7.83108 21.1263 7.62894 20.7484 7.7626 20.5866C7.89627 20.4248 8.33649 20.5555 9.21689 20.8169C10.0971 21.0782 11.0316 21.2188 12 21.2188C17.2467 21.2188 21.5 17.0913 21.5 11.9998C21.5 10.3206 21.0374 8.74623 20.2291 7.39023" />
                            </svg>
                        </x-button-next>
                    @endif
                </x-form-card>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>

                <div wire:loading.flex class="loading-overlay fixed hidden">
                    <x-loading-next />
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        function desvincularuseremployer(user_id) {
            swal.fire({
                title: 'Desvincular usuario del personal seleccionado ?',
                text: "El usuario dejará de estar disponible para el personal vinculado.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.desvincularuser(user_id);
                }
            })
        }

        function confirmDelete(employer) {
            swal.fire({
                title: 'Realizar baja de personal ' + employer.name,
                text: "Se dará de baja un registro de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(employer.id);
                }
            })
        }

        function confirmRestore(employer) {
            swal.fire({
                title: 'Restaurar personal ' + employer.name,
                text: "Se actualizará un registro de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.restoreemployer(employer.id);
                }
            })
        }

        // document.addEventListener('livewire:load', function() {})

        function selectSearchsucursal() {
            this.selectSearch = $(this.$refs.searchsuc).select2();
            this.selectSearch.val(this.searchsucursal).trigger("change");
            this.selectSearch.on("select2:select", (event) => {
                this.searchsucursal = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("searchsucursal", (value) => {
                this.selectSearch.val(value).trigger("change");
            });
        }

        function selectSucursal() {
            this.selectSuc = $(this.$refs.editselectsuc).select2();
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

        function select2TurnoE() {
            this.selectTur = $(this.$refs.selectturno).select2();
            this.selectTur.val(this.turno_id).trigger("change");
            this.selectTur.on("select2:select", (event) => {
                this.turno_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("turno_id", (value) => {
                this.selectTur.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectTur.select2({
                    templateResult: formatOption
                }).val(this.turno_id).trigger('change');
            });
        }

        function selectSexo() {
            this.selectSexo = $(this.$refs.editselectsexo).select2();
            this.selectSexo.val(this.sexoedit).trigger("change");
            this.selectSexo.on("select2:select", (event) => {
                this.sexoedit = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("sexoedit", (value) => {
                this.selectSexo.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectSexo.select2().val(this.sexoedit).trigger('change');
            });
        }

        function selectAreawork() {
            this.selectA = $(this.$refs.editselecta).select2();
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

        function formatOption(option) {
            var $option = $(
                '<strong>' + option.text + '</strong><p class="select2-subtitle-option">' + option.title + '</p>'
            );
            return $option;
        };
    </script>
</div>
