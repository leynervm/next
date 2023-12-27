<div>
    <x-button-next titulo="Registrar" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nueva caja') }}
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">

                <div class="w-full">
                    <x-label for="sucursal_id" value="{{ __('Sucursal') }} :" />
                    <x-select class="block w-full" id="sucursal_id" wire:model.defer="sucursal_id"
                        data-dropdown-parent="null">
                        <x-slot name="options">
                            @if (count($sucursals))
                                @foreach ($sucursals as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="sucursal_id" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="Nombre caja :" />
                    <x-input class="block w-full" wire:model.defer="name" placeholder="Nombre de caja..." />
                    <x-jet-input-error for="name" />
                </div>

                <div class="w-full flex pt-4 gap-2 justify-end">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="save">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener("DOMContentLoaded", () => {

            renderselect2();

            $('#sucursal_id').on("change", function(e) {
                disableselect2()
                @this.set('sucursal_id', e.target.value);
            });


            document.addEventListener('render-create-caja', () => {
                renderselect2();
            });

            function disableselect2() {
                $('#sucursal_id').attr('disabled', true);
            }

            function renderselect2() {
                $('#sucursal_id').select2().on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
            }
        })
    </script>
</div>
