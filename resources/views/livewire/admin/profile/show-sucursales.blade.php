<x-jet-form-section submit="updateProfileSucursal">
    <x-slot name="title">
        {{ __('Sucursals asigned') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your sucursals asigneds and almacen default values.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-label for="sucursal_id" value="{{ __('Sucursal asignado') }} :" />
            <x-disabled-text :text="auth()->user()->sucursal->name" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="almacen_id" value="{{ __('Almacen predeterminado') }} :" />
            <div id="parentalmacen_id" class="relative" x-data="{ almacen_id: @entangle('almacen_id').defer }" x-init="select2Almacen" wire:ignore>
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
        </div>

        <script>
            function select2Almacen() {
                this.select = $(this.$refs.select).select2();
                this.select.val(this.almacen_id).trigger("change");
                this.select.on("select2:select", (event) => {
                    this.almacen_id = event.target.value;
                }).on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
            }
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
