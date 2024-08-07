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
                                <x-icon-fileupload class="w-full h-full !my-0" />
                            </template>
                        </div>

                        <template x-if="image">
                            <x-button class="inline-flex !rounded-lg" wire:loading.attr="disabled" @click="reset">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                    <line x1="10" x2="10" y1="11" y2="17" />
                                    <line x1="14" x2="14" y1="11" y2="17" />
                                </svg>
                                LIMPIAR</x-button>
                        </template>

                        <label for="fileInput" type="button"
                            class="cursor-pointer text-[10px] inine-flex justify-between items-center focus:outline-none hover:ring-2 hover:ring-ringbutton py-2 px-4 rounded-lg shadow-sm text-left text-colorbutton bg-fondobutton hover:bg-fondohoverbutton hover:text-colorhoverbutton font-semibold tracking-widest">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-flex flex-shrink-0 w-6 h-6 -mt-1 mr-1"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="0" y="0" stroke="none"></rect>
                                <path
                                    d="M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" />
                                <circle cx="12" cy="13" r="3" />
                            </svg>
                            SELECCIONAR LOGO
                        </label>
                        <input name="photo" id="fileInput" accept="image/*" class="hidden disabled:opacity-25"
                            type="file" @change="loadlogo" wire:loading.attr="disabled" wire:model="logo">

                    </div>
                </div>
                <x-jet-input-error for="logo" class="text-center" />

                <x-label class="mt-3" value="Marca :" />
                <x-input class="block w-full" wire:model.defer="name" placeholder="Ingrese nombre de marca..." />
                <x-jet-input-error for="name" />

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
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
