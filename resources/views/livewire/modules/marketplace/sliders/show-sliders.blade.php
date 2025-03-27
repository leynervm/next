<div x-data="fileUploadEdit">
    @if ($sliders->hasPages())
        <div class="pb-2">
            {{ $sliders->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="w-full grid grid-cols-1 xs:grid-cols-[repeat(auto-fill,minmax(260px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(320px,1fr))] gap-1 self-start" id="sliders">
        @if (count($sliders))
            @foreach ($sliders as $item)
                <x-simple-card class="flex flex-col justify-between rounded overflow-hidden"
                    data-id="{{ $item->id }}">
                    <div class="w-full">
                        <div class="w-full overflow-hidden">
                            <img src="{{ pathURLSlider($item->url) }}" alt=""
                                class="block w-full h-auto {{ $item->isActivo() ? '' : 'grayscale' }}">
                        </div>

                        <p class="text-xs leading-3 px-1 mt-2 text-colorsubtitleform">
                            <small class="font-semibold">ORDEN: </small>
                            {{ $item->orden }}
                        </p>

                        <p class="text-xs leading-3 px-1 text-colorsubtitleform">
                            <small class="font-semibold">FECHA INICIO: </small>
                            {{ formatDate($item->start, 'DD MMMM Y') }}
                        </p>

                        @if (!empty($item->end))
                            <p class="text-xs leading-3 px-1 text-colorsubtitleform">
                                <small class="font-semibold">FECHA EXPIRACIÓN: </small>
                                {{ formatDate($item->end, 'DD MMMM Y') }}
                            </p>
                        @endif

                        @if (!empty($item->link))
                            <p class="text-xs leading-3 px-1 text-colorlabel">
                                <small class="font-semibold">LINK: </small>
                                {{ $item->link }}
                            </p>
                        @endif
                    </div>

                    @canany(['admin.marketplace.sliders.order', 'admin.marketplace.sliders.pause',
                        'admin.marketplace.sliders.edit', 'admin.marketplace.sliders.delete'])
                        <div class="w-full flex gap-2 items-end justify-between mt-auto p-1">
                            @can('admin.marketplace.sliders.order')
                                <span
                                    class="text-next-500 block cursor-grab h-full handle hover:shadow hover:shadow-shadowminicard rounded-md opacity-70 hover:opacity-100 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        stroke="none" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                                        class="block w-6 h-6 xs:w-8 xs:h-8">
                                        <path d="M10.4961 16.5H13.4961V19.5H10.4961V16.5Z" />
                                        <path d="M16.5 16.5H19.5V19.5H16.5V16.5Z" />
                                        <path d="M4.5 16.5H7.5V19.5H4.5V16.5Z" />
                                        <path d="M10.4961 10.5H13.4961V13.5H10.4961V10.5Z" />
                                        <path d="M10.5 4.5H13.5V7.5H10.5V4.5Z" />
                                        <path d="M16.5 10.5H19.5V13.5H16.5V10.5Z" />
                                        <path d="M16.5 4.5H19.5V7.5H16.5V4.5Z" />
                                        <path d="M4.5 10.5H7.5V13.5H4.5V10.5Z" />
                                        <path d="M4.5 4.5H7.5V7.5H4.5V4.5Z" />
                                    </svg>
                                </span>
                            @endcan

                            <div class="flex-1 flex gap-2 justify-end items-end">
                                @can('admin.marketplace.sliders.pause')
                                    <x-button-toggle onclick="confirmStatusSlider({{ $item }})"
                                        wire:loading.attr="disabled" :checked="$item->isActivo() ? true : false" @click="resetall()" />
                                @endcan

                                @can('admin.marketplace.sliders.edit')
                                    <x-button-edit @click="resetall" wire:loading.attr="disabled"
                                        wire:click="edit({{ $item->id }})" wire:key="editslider_{{ $item->id }}" />
                                @endcan

                                @can('admin.marketplace.sliders.delete')
                                    <x-button-delete wire:loading.attr="disabled"
                                        onclick="confirmDeleteSlider({{ $item }})" />
                                @endcan
                            </div>
                        </div>
                    @endcanany
                </x-simple-card>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar slider') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div class="w-full relative">
                    <p class="text-xs text-center text-colorlabel font-medium">
                        VERSIÓN ESCRITORIO</p>
                    <x-simple-card class="w-full h-40 sm:h-56 mx-auto mb-1 overflow-hidden">
                        @if (isset($image))
                            <template x-if="image">
                                <img :src="image"
                                    class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster">
                            </template>
                        @else
                            @if ($slider->url)
                                <img class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster"
                                    src="{{ pathURLSlider($slider->url) }}" />
                            @else
                                <x-icon-file-upload type="file" class="w-full h-full" />
                            @endif
                        @endif
                    </x-simple-card>

                    <p class="text-[10px] text-center text-colorsubtitleform">
                        Resolución Mínima : 1920px X 560px</p>

                    <div class="w-full flex flex-wrap gap-2 justify-center">
                        @if (isset($image))
                            <x-button class="inline-flex px-6" wire:loading.attr="disabled" @click="reset"
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
                            <x-input-file for="{{ $id_image }}" titulo="SELECCIONAR IMAGEN" wire:loading.remove>
                                <input type="file" class="hidden" id="{{ $id_image }}"
                                    accept="image/jpg, image/jpeg, image/png" @change="selectFile" />
                            </x-input-file>
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
                                @if ($slider->urlmobile)
                                    <img class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster"
                                        src="{{ pathURLSlider($slider->urlmobile) }}" />
                                @else
                                    <x-icon-file-upload type="file" class="w-full h-full" />
                                @endif
                            </template>
                        </x-simple-card>

                        <p class="text-[10px] text-center text-colorsubtitleform">
                            Resolución Mínima : 720x833px</p>

                        <div class="w-full flex items-center justify-center flex-wrap gap-1">
                            <x-input-file for="editfilemobile" titulo="SELECCIONAR IMAGEN MOBILE"
                                class="disabled:opacity-25" wire:loading.attr="disabled">
                                <input type="file" class="hidden" @change="loadeditimagemobile"
                                    id="editfilemobile" name="photomobile" accept="image/*" />
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
                            <x-input class="block w-full" wire:model.defer="slider.link"
                                placeholder="Ingrese link para redireccionar al hacer click..." />
                            <x-jet-input-error for="slider.link" />
                        </div>

                        <div class="w-full">
                            <x-label value="Fecha inicio :" />
                            <x-input type="date" class="block w-full" wire:model.defer="slider.start" />
                            <x-jet-input-error for="slider.start" />
                        </div>

                        <div class="w-full">
                            <x-label value="Fecha expiración :" />
                            <x-input type="date" class="block w-full" wire:model.defer="slider.end" />
                            <x-jet-input-error for="slider.end" />
                        </div>
                    </div>
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('fileUploadEdit', () => ({
                image: null,
                imagemobile: null,
                extensionimagemobile: null,
                isUploading: false,
                progress: 0,
                selectFile(event) {
                    const file = event.target.files[0]
                    const reader = new FileReader()
                    if (event.target.files.length < 1) {
                        return
                    }
                    reader.onload = (e) => {
                        this.image = e.target.result;
                        this.$wire.image = reader.result;
                    };
                    reader.readAsDataURL(file);
                    if (file) {
                        let imageExtension = file.name.split('.').pop();
                        this.$wire.extencionimage = imageExtension;
                    }
                },
                resetall() {
                    this.reset();
                    this.resetmobile();
                },
                reset() {
                    this.image = null;
                    this.$wire.image = null;
                    this.$wire.extencionimage = null;
                },
                loadeditimagemobile() {
                    let file = document.getElementById('editfilemobile').files[0];
                    var reader = new FileReader();
                    reader.onload = (e) => {
                        this.imagemobile = e.target.result;
                        this.$wire.imagemobile = reader.result;
                    };
                    reader.readAsDataURL(file);
                    if (file) {
                        let imageExtension = file.name.split('.').pop();
                        this.$wire.extencionimagemobile = imageExtension;
                    }
                },
                resetmobile() {
                    this.imagemobile = null;
                    this.$wire.imagemobile = null;
                    this.$wire.extencionimagemobile = null;
                }
            }))
        })

        document.addEventListener("DOMContentLoaded", function(event) {
            new Sortable(sliders, {
                animation: 150,
                ghostClass: 'bg-fondospancardproduct',
                handle: '.handle',
                store: {
                    set: function(sortable) {
                        const sorts = sortable.toArray();
                        axios.post("{{ route('api.sort.sliders') }}", {
                            sorts: sorts
                        }).catch(function(error) {
                            console.log(error);
                        });
                    }
                },
            })
        })

        function confirmStatusSlider(slider) {
            const estado = slider.status == '0' ? 'PAUSAR' : 'ACTIVAR';
            const text = slider.status == '0' ? 'dejará de mostrarse' : 'se mostrará';
            swal.fire({
                title: estado + ' PUBLICACIÓN DEL SLIDER EN TIENDA WEB ' + '?',
                text: 'El slider seleccionado ' + text + ' en la tienda web.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.updatestatus(slider.id);
                }
            })
        }

        function confirmDeleteSlider(slider) {
            swal.fire({
                title: 'Eliminar imagen de slider ',
                text: "Se eliminará un registro de la base de datos",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(slider.id);
                }
            })
        }
    </script>
</div>
