<div x-data="createturno">
    <x-button-next titulo="Registrar" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo turno laboral') }}
        </x-slot>

        <x-slot name="content">
            <div wire:loading.flex class="loading-overlay fixed hidden">
                <x-loading-next />
            </div>

            <form wire:submit.prevent="save" class="relative block w-full">
                <div class="w-full grid xs:grid-cols-2 gap-2">
                    <div class="w-full xs:col-span-2">
                        <x-label value="Descripción del turno :" />
                        <x-input class="block w-full" wire:model.defer="name" placeholder="Nombres del personal..." />
                        <x-jet-input-error for="name" />
                    </div>

                    <div class="w-full">
                        <x-label value="Hora ingreso :" />
                        <div class="relative" x-init="select2HI" wire:ignore>
                            <x-select class="block w-full" x-ref="selecthi" id="selecthi" data-dropdown-parent="null">
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
                        <x-jet-input-error for="horaingreso" />
                    </div>

                    <div class="w-full">
                        <x-label value="Hora salida :" />
                        <div class="relative" x-init="select2HS" wire:ignore>
                            <x-select class="block w-full" x-ref="selecths" id="selecths" data-dropdown-parent="null">
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
                        <x-jet-input-error for="horasalida" />
                    </div>

                    {{-- <div class="w-full xs:col-span-2">
                        <x-label value="Sucursal :" />
                        <div class="relative" x-init="select2Sucursal" wire:ignore>
                            <x-select class="block w-full" x-ref="selectsuc" id="sucursal_id"
                                data-minimum-results-for-search="3" data-dropdown-parent="null">
                                <x-slot name="options">
                                    @if (count($sucursals) > 0)
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
                    </div> --}}
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
            Alpine.data('createturno', () => ({
                // sucursal_id: @entangle('sucursal_id').defer,
                horaingreso: @entangle('horaingreso').defer,
                horasalida: @entangle('horasalida').defer,
            }))
        })

        // function select2Sucursal() {
        //     this.selectSuc = $(this.$refs.selectsuc).select2();
        //     this.selectSuc.val(this.sucursal_id).trigger("change");
        //     this.selectSuc.on("select2:select", (event) => {
        //         this.sucursal_id = event.target.value;
        //     }).on('select2:open', function(e) {
        //         const evt = "scroll.select2";
        //         $(e.target).parents().off(evt);
        //         $(window).off(evt);
        //     });
        //     this.$watch("sucursal_id", (value) => {
        //         this.selectSuc.val(value).trigger("change");
        //     });
        // }

        function select2HI() {
            this.selectHI = $(this.$refs.selecthi).select2();
            this.selectHI.val(this.horaingreso).trigger("change");
            this.selectHI.on("select2:select", (event) => {
                this.horaingreso = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("horaingreso", (value) => {
                this.selectHI.val(value).trigger("change");
            });
        }

        function select2HS() {
            this.selectHS = $(this.$refs.selecths).select2();
            this.selectHS.val(this.horasalida).trigger("change");
            this.selectHS.on("select2:select", (event) => {
                this.horasalida = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("horasalida", (value) => {
                this.selectHS.val(value).trigger("change");
            });
        }
    </script>
</div>
