<div>
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
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2" x-data="cargarimagen">
                <div>
                    <x-label value="Nombre :" />
                    <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese nombre categoría..." />
                    <x-jet-input-error for="name" />
                </div>
                {{-- <div>
                    <x-label value="Contenido icono SVG :" />
                    <x-text-area class="w-full block" wire:model.defer="icon" rows="10">
                    </x-text-area>
                    <x-jet-input-error for="icon" />
                </div> --}}

                <div class="w-full relative">
                    <div class="w-full text-center">
                        <div
                            class="w-full h-60 max-w-60 mx-auto relative mb-2 shadow-md shadow-shadowminicard rounded-xl overflow-hidden">
                            <template x-if="image">
                                <img id="image" class="object-scale-down block w-full h-full"
                                    :src="image" />
                            </template>
                            <template x-if="!image">
                                <x-icon-file-upload class="w-full h-full !my-0" />
                            </template>
                        </div>

                        <div class="w-full flex flex-wrap items-end justify-center gap-1">
                            <template x-if="image">
                                <x-button class="inline-flex" wire:loading.attr="disabled" @click="reset">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 6h18" />
                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                        <line x1="10" x2="10" y1="11" y2="17" />
                                        <line x1="14" x2="14" y1="11" y2="17" />
                                    </svg>
                                    LIMPIAR</x-button>
                            </template>
                            <x-input-file for="logocategory" titulo="SELECCIONAR IMAGEN"
                                wire:loading.class="disabled:opacity-25" class="">
                                <input type="file" id="logocategory" name="photo" @change="loadimage"
                                    accept="image/*" class="hidden disabled:opacity-25" />
                            </x-input-file>
                        </div>
                        <x-jet-input-error for="image" />
                    </div>
                </div>

                {{-- @if ($icon)
                    <div
                        class="w-48 h-48 p-2 rounded-xl mx-auto text-colorsubtitleform relative mb-2 border border-borderminicard">
                        {!! $icon !!}
                    </div>
                @endif --}}

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
        function cargarimagen() {
            return {
                image: null,
                loadimage() {
                    let file = document.getElementById('logocategory').files[0];
                    var reader = new FileReader();
                    reader.onload = (e) => {
                        this.image = e.target.result;
                        this.$wire.image = reader.result;
                    };
                    reader.readAsDataURL(file);
                    if (file) {
                        let imageExtension = file.name.split('.').pop();
                        this.$wire.extensionimage = imageExtension;
                    }
                },
                reset() {
                    this.image = null;
                    this.$wire.extensionimage = null;
                    this.$wire.image = null;
                },
            }
        }
    </script>
</div>
