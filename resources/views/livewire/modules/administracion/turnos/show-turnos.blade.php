<div>
    @if ($turnos->hasPages())
        <div class="pb-2">
            {{ $turnos->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <x-table class="mt-1">
        <x-slot name="header">
            <tr>
                <th scope="col" class="p-2 font-medium text-left">
                    DESCRIPCIÓN TURNO</th>
                <th scope="col" class="p-2 font-medium">
                    HORA INGRESO</th>
                <th scope="col" class="p-2 font-medium">
                    HORA SALIDA</th>
                <th scope="col" class="p-2 relative">
                    <span class="sr-only">OPCIONES</span>
                </th>
            </tr>
        </x-slot>
        @if (count($turnos) > 0)
            <x-slot name="body">
                @foreach ($turnos as $item)
                    <tr>
                        <td class="p-2">
                            {{ $item->name }}
                        </td>
                        <td class="p-2 text-center">
                            {{ formatDate($item->horaingreso, 'hh:ss A') }}
                        </td>
                        <td class="p-2 text-center">
                            {{ formatDate($item->horasalida, 'hh:ss A') }}
                        </td>

                        <td class="p-2 whitespace-nowrap align-middle text-center">
                            @can('admin.administracion.turnos.edit')
                                <x-button-edit wire:click="edit({{ $item->id }})" wire:loading.attr="disabled"
                                    wire:key="edit_{{ $item->id }}" />
                            @endcan

                            @can('admin.administracion.turnos.delete')
                                <x-button-delete onclick="confirmDelete({{ $item }})"
                                    wire:loading.attr="disabled" />
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        @endif
        <x-slot name="loading">
            <div wire:loading.flex class="loading-overlay rounded hidden overflow-hidden">
                <x-loading-next />
            </div>
        </x-slot>
    </x-table>

    <x-jet-dialog-modal wire:model="open" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar horario laboral') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="update" class="relative" x-data="showturno">
                <div class="w-full grid xs:grid-cols-2 gap-2">
                    <div class="w-full xs:col-span-2">
                        <x-label value="Descripción del turno :" />
                        <x-input class="block w-full" wire:model.defer="turno.name"
                            placeholder="Nombres del personal..." />
                        <x-jet-input-error for="name" />
                    </div>

                    <div class="w-full">
                        <x-label value="Hora ingreso :" />
                        <div class="relative" x-init="select2HIE" wire:ignore>
                            <x-select class="block w-full" x-ref="selecthie" id="selecthie" data-dropdown-parent="null">
                                <x-slot name="options">
                                    <option value="00:00">12:00 AM</option>
                                    <option value="01:00">01:00 AM</option>
                                    <option value="02:00">02:00 AM</option>
                                    <option value="03:00">03:00 AM</option>
                                    <option value="04:00">04:00 AM</option>
                                    <option value="05:00">05:00 AM</option>
                                    <option value="06:00">06:00 AM</option>
                                    <option value="07:00">07:00 AM</option>
                                    <option value="08:00">08:00 AM</option>
                                    <option value="09:00">09:00 AM</option>
                                    <option value="10:00">10:00 AM</option>
                                    <option value="11:00">11:00 AM</option>
                                    <option value="12:00">12:00 PM</option>
                                    <option value="13:00">01:00 PM</option>
                                    <option value="14:00">02:00 PM</option>
                                    <option value="15:00">03:00 PM</option>
                                    <option value="16:00">04:00 PM</option>
                                    <option value="17:00">05:00 PM</option>
                                    <option value="18:00">06:00 PM</option>
                                    <option value="19:00">07:00 PM</option>
                                    <option value="20:00">08:00 PM</option>
                                    <option value="21:00">09:00 PM</option>
                                    <option value="22:00">10:00 PM</option>
                                    <option value="23:00">11:00 PM</option>
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="turno.horaingreso" />
                    </div>

                    <div class="w-full">
                        <x-label value="Hora salida :" />
                        <div class="relative" x-init="select2HSE" wire:ignore>
                            <x-select class="block w-full" x-ref="selecthse" id="selecthse" data-dropdown-parent="null">
                                <x-slot name="options">
                                    <option value="00:00">12:00 AM</option>
                                    <option value="01:00">01:00 AM</option>
                                    <option value="02:00">02:00 AM</option>
                                    <option value="03:00">03:00 AM</option>
                                    <option value="04:00">04:00 AM</option>
                                    <option value="05:00">05:00 AM</option>
                                    <option value="06:00">06:00 AM</option>
                                    <option value="07:00">07:00 AM</option>
                                    <option value="08:00">08:00 AM</option>
                                    <option value="09:00">09:00 AM</option>
                                    <option value="10:00">10:00 AM</option>
                                    <option value="11:00">11:00 AM</option>
                                    <option value="12:00">12:00 PM</option>
                                    <option value="13:00">01:00 PM</option>
                                    <option value="14:00">02:00 PM</option>
                                    <option value="15:00">03:00 PM</option>
                                    <option value="16:00">04:00 PM</option>
                                    <option value="17:00">05:00 PM</option>
                                    <option value="18:00">06:00 PM</option>
                                    <option value="19:00">07:00 PM</option>
                                    <option value="20:00">08:00 PM</option>
                                    <option value="21:00">09:00 PM</option>
                                    <option value="22:00">10:00 PM</option>
                                    <option value="23:00">11:00 PM</option>
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="turno.horasalida" />
                    </div>

                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>
                <div wire:loading.flex class="loading-overlay rounded hidden" wire:target="update">
                    <x-loading-next />
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        function confirmDelete(turno) {
            swal.fire({
                title: 'Eliminar turno laboral seleccionado, ' + turno.name,
                text: "El horario laboral dejará de estar disponible, tendrá que asignar un nuevo horario para los personales vinculados.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(turno.id);
                }
            })
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('showturno', () => ({
                horaingreso: @entangle('turno.horaingreso').defer,
                horasalida: @entangle('turno.horasalida').defer,
            }))
        })

        function select2HIE() {
            this.selectHIE = $(this.$refs.selecthie).select2();
            this.selectHIE.val(this.horaingreso).trigger("change");
            this.selectHIE.on("select2:select", (event) => {
                this.horaingreso = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("horaingreso", (value) => {
                this.selectHIE.val(value).trigger("change");
            });
        }

        function select2HSE() {
            this.selectHSE = $(this.$refs.selecthse).select2();
            this.selectHSE.val(this.horasalida).trigger("change");
            this.selectHSE.on("select2:select", (event) => {
                this.horasalida = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("horasalida", (value) => {
                this.selectHSE.val(value).trigger("change");
            });
        }
    </script>
</div>
