<div x-data="loadimage">
    <x-button-next titulo="Registrar" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nueva categoría') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div>
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese nombre categoría..." />
                    <x-jet-input-error for="name" />
                </div>
                <div>
                    <x-label value="Contenido icono SVG :" />
                    <x-text-area class="w-full block" wire:model.defer="icon" rows="10">
                    </x-text-area>
                    <x-jet-input-error for="icon" />
                </div>

                @if ($icon)
                    <div
                        class="w-48 h-48 p-2 rounded-xl mx-auto text-colorsubtitleform relative mb-2 border border-borderminicard">
                        {!! $icon !!}
                    </div>
                @endif


                <div class="w-full flex items-end gap-1 pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                    <x-button wire:click="save(true)" wire:loading.attr="disabled">
                        {{ __('Save and close') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        function loadimage() {
            return {
                image: null,
                logo: @entangle('logo').defer,
                loadlogo() {
                    let file = document.getElementById('fileInput').files[0];
                    var reader = new FileReader();
                    reader.onload = (e) => this.image = e.target.result;
                    reader.readAsDataURL(file);
                },
                init() {
                    this.$watch('logo', (value) => {
                        if (value == undefined || value == null) {
                            this.image = null;
                        }
                    });
                },
                reset() {
                    this.image = null;
                    @this.clearImage();
                },
            }
        }
    </script>
</div>
