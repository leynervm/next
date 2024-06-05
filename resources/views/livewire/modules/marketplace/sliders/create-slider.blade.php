<div>
    <x-button-next titulo="Registrar" wire:click="$set('open', true)">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" x2="12" y1="5" y2="19" />
            <line x1="5" x2="19" y1="12" y2="12" />
        </svg>
    </x-button-next>
    <x-jet-dialog-modal wire:model="open" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Nuevo slider') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form x-data="fileUpload" wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div class="w-full relative">
                    <x-simple-card class="w-full h-72 mx-auto mb-1">
                        <template x-if="image">
                            <img :src="image"
                                class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster">
                        </template>
                        <template x-if="!image">
                            <x-icon-file-upload type="file" class="w-full h-full text-gray-300" />
                        </template>
                    </x-simple-card>

                    <div class="w-full flex flex-wrap gap-2 justify-center">
                        @if (isset($image))
                            <x-button class="inline-flex px-6" wire:loading.attr="disabled"
                                wire:click="clearImage" @click="reset">LIMPIAR
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
                                <input type="file" class="hidden" id="{{ $identificador }}" wire:model.defer="image"
                                    accept="image/jpg, image/jpeg, image/png" @change="selectFile" />
                            </x-input-file>
                        @endif
                    </div>
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

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>
    <script>
        // function imageViewer() {
        //     return {
        //         image: @entangle('image').defer,
        //         isUploading: false,
        //         progress: 0,

        //         fileChosen(event) {
        //             this.fileToDataUrl(event, src => this.image = src)
        //         },

        //         fileToDataUrl(event, callback) {
        //             if (!event.target.files.length) return

        //             let file = event.target.files[0],
        //                 reader = new FileReader()

        //             reader.readAsDataURL(file)
        //             reader.onload = e => callback(e.target.result)
        //         },
        //     }
        // }
        document.addEventListener('alpine:init', () => {
            Alpine.data('fileUpload', () => ({
                image: null,
                isUploading: false,
                progress: 0,
                selectFile(event) {
                    const file = event.target.files[0]
                    const reader = new FileReader()
                    if (event.target.files.length < 1) {
                        return
                    }
                    reader.readAsDataURL(file)
                    reader.onload = () => (this.image = reader.result)
                },
                reset() {
                    this.image = null;
                }
            }))
        })
    </script>
</div>
