<div x-data="loadimage()">
    <x-button-next titulo="Registrar" wire:click="$set('open', true)" @click="image=null">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nueva marca') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save">
                <div class="w-full relative">
                    <div class="w-full text-center">
                        <div
                            class="w-full h-60 relative mb-2 shadow-md shadow-shadowminicard rounded-xl overflow-hidden">
                            <template x-if="image">
                                <img id="image" class="object-scale-down block w-full h-full"
                                    :src="image" />
                            </template>
                            <template x-if="!image">
                                <x-icon-file-upload class="w-full h-full" />
                            </template>
                        </div>

                        <div class="w-full flex flex-wrap items-end justify-center gap-1">
                            <template x-if="image">
                                <x-button class="inline-flex !rounded-lg" wire:loading.attr="disabled" @click="reset">
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
                            <x-input-file for="fileInput" titulo="SELECCIONAR LOGO"
                                wire:loading.class="disabled:opacity-25" class="">
                                <input type="file" id="fileInput" name="photo" @change="loadlogo" accept="image/*"
                                    class="hidden disabled:opacity-25" wire:model="logo" />
                            </x-input-file>
                        </div>
                    </div>
                </div>
                <x-jet-input-error for="logo" class="text-center" />

                <x-label class="mt-3" value="Marca :" />
                <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese nombre de marca..." />
                <x-jet-input-error for="name" />

                <div class="w-full flex pt-4 justify-end gap-2">
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
                loadlogo() {
                    let file = document.getElementById('fileInput').files[0];
                    var reader = new FileReader();
                    reader.onload = (e) => this.image = e.target.result;
                    reader.readAsDataURL(file);
                },
                reset() {
                    this.image = null;
                    @this.clearImage();
                },
            }
        }
    </script>
</div>
