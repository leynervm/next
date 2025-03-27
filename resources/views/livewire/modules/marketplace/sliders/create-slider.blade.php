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
                    <p class="text-xs text-center text-colorlabel font-medium">
                        VERSIÓN ESCRITORIO</p>
                    <x-simple-card class="w-full h-40 sm:h-56 mx-auto mb-1 overflow-hidden">
                        <template x-if="image">
                            <img :src="image"
                                class="w-full h-full object-scale-down rounded animate__animated animate__fadeIn animate__faster">
                        </template>
                        <template x-if="!image">
                            <x-icon-file-upload type="file" class="w-full h-full" />
                        </template>
                    </x-simple-card>

                    <p class="text-[10px] text-center text-colorsubtitleform">
                        Resolución Mínima : 1920x560px</p>

                    <div class="w-full flex items-center justify-center flex-wrap gap-1">
                        <x-input-file for="{{ $iddesk }}" titulo="SELECCIONAR IMAGEN" class="disabled:opacity-25"
                            wire:loading.attr="disabled">
                            <input type="file" class="hidden" @change="loadimage" id="{{ $iddesk }}" name="photo"
                                accept="image/*" />
                        </x-input-file>

                        @if (isset($image))
                            <x-button class="inline-flex px-6" wire:loading.attr="disabled" @click="reset">
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
                    <x-jet-input-error for="image" class="text-center" />
                </div>

                <div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-5 mt-3">
                    <div class="w-full relative">
                        <p class="text-xs text-center text-colorlabel font-medium">
                            VERSIÓN MOBILE</p>

                        <x-simple-card class="w-full max-w-52 h-60 mx-auto mb-1 overflow-hidden">
                            <template x-if="imagemobile">
                                <img :src="imagemobile"
                                    class="w-full h-full object-scale-down rounded animate__animated animate__fadeIn animate__faster">
                            </template>
                            <template x-if="!imagemobile">
                                <x-icon-file-upload type="file" class="w-full h-full" />
                            </template>
                        </x-simple-card>

                        <p class="text-[10px] text-center text-colorsubtitleform">
                            Resolución Mínima : 720x833px</p>

                        <div class="w-full flex items-center justify-center flex-wrap gap-1">
                            <x-input-file for="{{ $idmobile }}" titulo="SELECCIONAR IMAGEN MOBILE"
                                class="disabled:opacity-25" wire:loading.attr="disabled">
                                <input type="file" class="hidden" @change="loadimagemobile" id="{{ $idmobile }}"
                                    name="photomobile" accept="image/*" />
                            </x-input-file>

                            @if (isset($imagemobile))
                                <x-button class="inline-flex px-6" wire:loading.attr="disabled" @click="resetmobile">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
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
                        <x-jet-input-error for="imagemobile" class="text-center" />
                    </div>

                    <div class="w-full flex flex-col gap-2">
                        <div class="w-full">
                            <x-label value="Link enlace :" />
                            <x-input class="block w-full" wire:model.defer="link"
                                placeholder="Ingrese link para redireccionar al hacer click..." />
                            <x-jet-input-error for="link" />
                        </div>

                        <div class="w-full">
                            <x-label value="Fecha inicio :" />
                            <x-input type="date" class="block w-full" wire:model.defer="start" />
                            <x-jet-input-error for="start" />
                        </div>

                        <div class="w-full">
                            <x-label value="Fecha expiración :" />
                            <x-input type="date" class="block w-full" wire:model.defer="end" />
                            <x-jet-input-error for="end" />
                        </div>
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
                image: @entangle('image').defer,
                imagemobile: @entangle('imagemobile').defer,
                iddesk: @entangle('iddesk').defer,
                idmobile: @entangle('idmobile').defer,

                loadimage() {
                    const input = document.getElementById(this.iddesk);
                    if (!input || !input.files || !input.files[0]) {
                        console.log('No se seleccionó ningún archivo');
                        return;
                    }
                    const file = input.files[0];
                    var reader = new FileReader();
                    reader.onload = (e) => this.image = e.target.result;
                    reader.readAsDataURL(file);
                },
                loadimagemobile() {
                    const input = document.getElementById(this.idmobile);
                    if (!input || !input.files || !input.files[0]) {
                        console.log('No se seleccionó ningún archivo para mobile');
                        return;
                    }
                    const file = input.files[0];
                    var reader = new FileReader();
                    reader.onload = (e) => this.imagemobile = e.target.result;
                    reader.readAsDataURL(file);
                },
                reset() {
                    this.image = null;
                    this.$wire.clearimgdesk();
                },
                resetmobile() {
                    this.imagemobile = null;
                    this.$wire.clearimgmobile();
                }
            }))
        })
    </script>
</div>
