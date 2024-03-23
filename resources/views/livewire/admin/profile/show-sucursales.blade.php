<x-jet-form-section submit="update">
    <x-slot name="title">
        {{ __('Sucursals asigned') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your sucursals asigneds and almacen default values.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-label for="sucursal_id" value="{{ __('Sucursal asignado') }} :" />
            {{-- <x-disabled-text :text="auth()->user()->sucursal->name" /> --}}
            <div id="parentsucursal_id" class="relative" x-data="{ sucursal_id: @entangle('sucursal_id').defer }" x-init="select2Sucursal">
                <x-select class="block w-full" id="sucursal_id" x-ref="selectsucursal" data-placeholder="null">
                    <x-slot name="options">
                        @if (count($sucursals))
                            @foreach ($sucursals as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        @endif
                    </x-slot>
                </x-select>
                <x-icon-select />
                <x-jet-input-error for="sucursal_id" />
            </div>
        </div>

        {{-- <div class="col-span-6 sm:col-span-4">
            <x-label for="almacen_id" value="{{ __('Almacen predeterminado') }} :" />
            <div id="parentalmacen_id" class="relative" x-data="{ almacen_id: @entangle('almacen_id').defer }" x-init="select2Almacen">
                <x-select class="block w-full" id="almacen_id" x-ref="select" data-placeholder="null">
                    <x-slot name="options">
                        @if (count($almacens))
                            @foreach ($almacens as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        @endif
                    </x-slot>
                </x-select>
                <x-icon-select />
                <x-jet-input-error for="almacen_id" />
            </div>
        </div> --}}

        <script>
            function select2Sucursal() {
                this.selectSC = $(this.$refs.selectsucursal).select2();
                this.selectSC.val(this.sucursal_id).trigger("change");
                this.selectSC.on("select2:select", (event) => {
                    this.sucursal_id = event.target.value;
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

            // function select2Almacen() {
            //     this.selectA = $(this.$refs.select).select2();
            //     this.selectA.val(this.almacen_id).trigger("change");
            //     this.selectA.on("select2:select", (event) => {
            //         this.almacen_id = event.target.value;
            //     }).on('select2:open', function(e) {
            //         const evt = "scroll.select2";
            //         $(e.target).parents().off(evt);
            //         $(window).off(evt);
            //     });
            //     this.$watch("almacen_id", (value) => {
            //         this.selectA.val(value).trigger("change");
            //     });
            //     Livewire.hook('message.processed', () => {
            //         this.selectA.select2().val(this.almacen_id).trigger('change');
            //     });
            // }
        </script>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
