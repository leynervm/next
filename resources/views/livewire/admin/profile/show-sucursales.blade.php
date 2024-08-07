<x-jet-form-section submit="update">
    <x-slot name="title">
        {{ __('Update branch') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update branch assigned by default.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-label for="sucursal_id" value="Seleccionar :" />
            {{-- <x-disabled-text :text="auth()->user()->sucursal->name" /> --}}
            <div id="parentsucursal_id" class="relative" x-data="{ sucursal_id: @entangle('sucursal_id').defer }" x-init="select2Sucursal">
                <x-select class="block w-full" id="sucursal_id" x-ref="selectsucursal" data-placeholder="null">
                    <x-slot name="options">
                        @if (count($sucursals) > 0)
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
        </script>
    </x-slot>

    <x-slot name="actions">
        <x-button type="submit" wire:loading.attr="disabled">
            {{ __('Save') }}</x-button>
    </x-slot>
</x-jet-form-section>
