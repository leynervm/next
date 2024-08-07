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
            {{ __('Nueva caja mensual') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="relative">
                <div class="w-full grid xs:grid-cols-2 gap-2">
                    <div class="w-full">
                        <x-label value="Descripción caja mensual :" />
                        <x-input class="block w-full" wire:model.defer="name" placeholder="Descripcion de caja..." />
                        <x-jet-input-error for="name" />
                    </div>

                    <div class="w-full">
                        <x-label value="Mes :" />
                        <x-input type="date" class="block w-full" wire:model.lazy="month" type="month"
                            min="{{ \Carbon\Carbon::now('America/Lima')->format('Y-m') }}" />
                        <x-jet-input-error for="month" />
                    </div>

                    <div class="w-full">
                        <x-label value="fecha inicio :" />
                        <x-input class="block w-full" wire:model.defer="startdate" type="datetime-local" />
                        <x-jet-input-error for="startdate" />
                    </div>

                    <div class="w-full">
                        <x-label value="Fecha cierre :" />
                        <x-input class="block w-full" wire:model.defer="expiredate" type="datetime-local" />
                        <x-jet-input-error for="expiredate" />
                    </div>

                    <div class="w-full xs:col-span-2" x-data="checksucursals">
                        <x-title-next titulo="SELECCIONAR SUCURSALES" class="my-3" />
                        @if (count($sucursals) > 0)
                            <div class="w-full flex flex-wrap gap-2">
                                @foreach ($sucursals as $item)
                                    <x-input-radio class="py-2" :for="'sucursal_' . $item->id" :text="$item->name">
                                        <input class="sr-only peer peer-disabled:opacity-25" type="checkbox"
                                            id="sucursal_{{ $item->id }}" name="sucursals[]"
                                            value="{{ $item->id }}" wire:model.defer="sucursalselected"
                                            @change="checked($event.target.checked)" />
                                    </x-input-radio>
                                @endforeach
                            </div>
                            @if (count($sucursals) > 1)
                                <x-label-check for="allsucursals" class="mt-2">
                                    <x-input x-model="allsucursals" name="all" type="checkbox" id="allsucursals"
                                        @change="all" />SELECCIONAR TODO</x-label-check>
                            @endif
                        @endif
                        <x-jet-input-error for="sucursalselected" />
                    </div>
                </div>

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
        document.addEventListener('alpine:init', () => {
            Alpine.data('checksucursals', () => ({
                allsucursals: @entangle('seleccionartodo').defer,

                all() {
                    @this.allsucursals(this.allsucursals);
                },
                checked(target) {
                    console.log(target);
                    if (target == false) {
                        this.allsucursals = false;
                    }
                }
            }))
        })

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
    </script>
</div>
