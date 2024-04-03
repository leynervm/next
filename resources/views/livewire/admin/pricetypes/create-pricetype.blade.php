<div>
    <x-button-next titulo="Registrar Lista Precio" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="2xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nueva lista precio') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Lista precio :" />
                    <x-input class="block w-full" wire:model.defer="name" placeholder="Nombre de lista precio..." />
                    <x-jet-input-error for="name" />
                </div>

                <div class="w-full">
                    <x-label value="Cantidad decimales :" />
                    <x-input class="block w-full" wire:model.defer="decimals" type="number" step="1"
                        min="0" max="4" onkeypress="return validarDecimal(event, 1)" />
                    <x-jet-input-error for="decimals" />
                </div>

                <div class="w-full">
                    <x-label value="Redondeo decimales :" />
                    <div id="parentrounded" class="relative" x-data="{ rounded: @entangle('rounded').defer }" x-init="select2Rounded"
                        wire:ignore>
                        <x-select class="block w-full" id="rounded" x-ref="select">
                            <x-slot name="options">
                                <option value="0">NO USAR REDONDEO</option>
                                <option value="1">REDONDEAR DECIMAL (+0.5)</option>
                                <option value="2">REDONDEAR DECIMAL A ENTERO (+1)</option>
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="rounded" />
                </div>

                <div class="">
                    <x-label-check for="default">
                        <x-input wire:model.defer="default" name="default" value="1" type="checkbox"
                            id="default" />
                        SELECCIONAR COMO PREDETERMINADO
                    </x-label-check>
                    <x-jet-input-error for="default" />
                </div>

                <div class="">
                    <x-label-check for="web">
                        <x-input wire:model.defer="web" name="web" value="1" type="checkbox" id="web" />
                        PREDETERMINADO PARA VENTAS WEB
                    </x-label-check>
                    <x-jet-input-error for="web" />
                </div>

                <div class="">
                    <x-label-check for="defaultlogin">
                        <x-input wire:model.defer="defaultlogin" name="defaultlogin" value="1" type="checkbox"
                            id="defaultlogin" />
                        PREDETERMINADO PARA VENTAS WEB DESPUES LOGIN
                    </x-label-check>
                    <x-jet-input-error for="defaultlogin" />
                </div>

                <div class="block" x-data="loader">
                    <x-label-check for="temporal">
                        <x-input wire:model.defer="temporal" @change="changeTemporal($event.target)" name="temporal"
                            type="checkbox" id="temporal" />
                        USAR DE MANERA TEMPORAL PARA VENTAS POR INTERNET
                    </x-label-check>
                    <x-jet-input-error for="temporal" />

                    <div x-show="show" class="relative mt-2">
                        <div class="w-full flex gap-2 animate__animated animate__fadeInDown">
                            <div class="w-full">
                                <x-label value="Fecha inicio :" />
                                <x-input class="w-full" type="date" wire:model.defer="startdate" />
                                <x-jet-input-error for="startdate" />
                            </div>
                            <div class="w-full">
                                <x-label value="Fecha fin :" />
                                <x-input class="w-full" type="date" wire:model.defer="expiredate" />
                                <x-jet-input-error for="expiredate" />
                            </div>
                        </div>
                    </div>
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
            Alpine.data('loader', () => ({
                show: @entangle('show').defer,

                changeTemporal(target) {
                    this.show = !this.show;
                }
            }))
        });


        function select2Rounded() {
            this.selectR = $(this.$refs.select).select2();
            this.selectR.val(this.rounded).trigger("change");
            this.selectR.on("select2:select", (event) => {
                this.rounded = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("rounded", (value) => {
                this.selectR.val(value).trigger("change");
            });
        }
    </script>
</div>
