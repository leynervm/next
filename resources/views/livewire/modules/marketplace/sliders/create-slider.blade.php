<div x-data="fileUpload">
    <x-button-next titulo="Registrar" @click="image=null" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo slider') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div class="w-full relative">
                    <x-simple-card class="w-full h-72 mx-auto mb-1">
                        <template x-if="image">
                            <img :src="image"
                                class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster">
                        </template>
                        <template x-if="!image">
                            <x-icon-file-upload type="file" class="w-full h-full text-gray-300 !border-0" />
                        </template>
                    </x-simple-card>

                    <div class="w-full flex items-center justify-center flex-wrap gap-2">
                        <label for="fileInput" type="button" wire:loading.attr="disabled"
                            class="cursor-pointer text-[10px] inine-flex justify-between items-center focus:outline-none hover:ring-2 hover:ring-ringbutton py-2 px-4 rounded-lg shadow-sm text-left text-colorbutton bg-fondobutton hover:bg-fondohoverbutton hover:text-colorhoverbutton font-semibold tracking-widest disabled:opacity-25">
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline-flex flex-shrink-0 w-6 h-6 -mt-1 mr-1"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="0" y="0" stroke="none"></rect>
                                <path
                                    d="M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" />
                                <circle cx="12" cy="13" r="3" />
                            </svg>
                            SELECCIONAR IMÁGEN
                        </label>
                        <input name="photo" id="fileInput" accept="image/*" class="hidden disabled:opacity-25"
                            type="file" @change="loadlogo" wire:loading.attr="disabled" wire:model="image">

                        @if (isset($image))
                            <x-button class="inline-flex px-6 !rounded-lg" wire:loading.attr="disabled" @click="reset">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                    <line x1="10" x2="10" y1="11" y2="17" />
                                    <line x1="14" x2="14" y1="11" y2="17" />
                                </svg>
                                LIMPIAR
                            </x-button>
                        @endif
                    </div>
                    {{-- <div class="w-full flex flex-wrap gap-2 justify-center">
                        @if (isset($image))
                            <x-button class="inline-flex px-6" wire:loading.attr="disabled" wire:click="clearImage"
                                @click="reset">LIMPIAR
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                    <line x1="10" x2="10" y1="11" y2="17" />
                                    <line x1="14" x2="14" y1="11" y2="17" />
                                </svg>
                            </x-button>
                        @else
                            <x-input-file for="{{ $identificador }}" titulo="SELECCIONAR IMAGEN" wire:loading.remove>
                                <input type="file" class="hidden" id="{{ $identificador }}"
                                    accept="image/jpg, image/jpeg, image/png" @change="selectFile" />
                            </x-input-file>
                        @endif
                    </div> --}}
                    <x-jet-input-error for="image" class="text-center" />
                </div>


                <div class="w-full">
                    <x-label class="mt-3" value="Link enlace :" />
                    <x-input class="block w-full" wire:model.defer="link"
                        placeholder="Ingrese link para redireccionar al hacer click..." />
                    <x-jet-input-error for="link" />
                </div>

                <div class="w-full grid md:grid-cols-2 gap-2">
                    <div class="w-full">
                        <x-label class="mt-3" value="Fecha inicio :" />
                        <x-input type="date" class="block w-full" wire:model.defer="start" />
                        <x-jet-input-error for="start" />
                    </div>

                    <div class="w-full">
                        <x-label class="mt-3" value="Fecha expiración :" />
                        <x-input type="date" class="block w-full" wire:model.defer="end" />
                        <x-jet-input-error for="end" />
                    </div>
                </div>

                <div class="w-full flex items-center gap-1 pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>

                    <x-button wire:click="save(true)" wire:loading.attr="disabled">
                        {{ __('Save and close') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('fileUpload', () => ({
                image: null,
                imageWidth: null,
                imageHeight: null,
                init() {
                    window.addEventListener('created', event => {
                        console.log(event);
                        this.image = null;
                    })
                },
                loadlogo() {
                    let file = document.getElementById('fileInput').files[0];
                    var reader = new FileReader();
                    reader.onload = (e) => this.image = e.target.result;
                    reader.readAsDataURL(file);

                    if (file) {
                        let imageName = file.name;
                        let imageExtension = file.name.split('.').pop();
                        this.getBase64(file, (result) => {
                            // @this.set('image', result);
                            // @this.set('extencionimage', imageExtension);
                        });
                    }
                },
                getBase64(file, callback) {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = () => callback(reader.result);
                    reader.onerror = error => console.error('Error: ', error);
                },
                reset() {
                    this.image = null;
                    @this.clearImage();
                },
            }))
        })
    </script>
</div>
