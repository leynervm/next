<x-jet-form-section submit="updateProfileSucursal">
    <x-slot name="title">
        {{ __('Sucursals asigned') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your sucursals asigneds and almacen default values.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-label for="sucursal_id" value="{{ __('Default Sucursal') }} :" />
            <div id="parentsucursal_id" class="relative">
                <x-select class="block w-full" id="sucursal_id" wire:model.defer="sucursal_id">
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

        <div class="col-span-6 sm:col-span-4">
            <x-label for="almacen_id" value="{{ __('Default Almacen') }} :" />
            <div id="parentalmacen_id" class="relative">
                <x-select class="block w-full" id="almacen_id" wire:model.defer="almacen_id">
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
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>

    @section('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {

                renderselect2();

                $('#almacen_id').on("change", function(e) {
                    disableselect2()
                    @this.set('almacen_id', e.target.value);
                });

                $('#sucursal_id').on("change", function(e) {
                    disableselect2()
                    @this.set('sucursal_id', e.target.value);
                });


                document.addEventListener('render-show-sucursals', () => {
                    renderselect2();
                });

                function disableselect2() {
                    $('#sucursal_id, #almacen_id').attr('disabled', true);
                }

                function renderselect2() {
                    $('#sucursal_id, #almacen_id').select2()
                        .on('select2:open', function(e) {
                            const evt = "scroll.select2";
                            $(e.target).parents().off(evt);
                            $(window).off(evt);
                        });
                }
            })
        </script>
    @endsection

</x-jet-form-section>
