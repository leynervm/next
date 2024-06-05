<div>
    @if ($sliders->hasPages())
        <div class="pb-2">
            {{ $sliders->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="flex gap-2 flex-wrap justify-start" id="sliders">
        @if (count($sliders))
            @foreach ($sliders as $item)
                <x-simple-card class="flex flex-col justify-between rounded overflow-hidden"
                    data-id="{{ $item->id }}">
                    <div class="w-full">
                        <div class="w-full max-w-md h-36">
                            <img src="{{ $item->getImageURL() }}" alt=""
                                class="w-full h-full {{ $item->isActivo() ? '' : 'grayscale' }}">
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

                    <div class="w-full flex gap-2 items-end justify-between mt-auto p-1">

                        {{-- @can('admin.almacen.categorias.edit') --}}
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
                        {{-- @endcan --}}

                        <div class="flex-1 flex gap-2 justify-end items-end">
                            <x-button-toggle onclick="confirmStatusSlider({{ $item }})"
                                wire:loading.attr="disabled"
                                class="{{ $item->isActivo() ? 'text-next-500' : 'text-neutral-300' }}" />

                            {{-- @can('admin.cajas.conceptos.edit') --}}
                            <x-button-edit wire:loading.attr="disabled" wire:click="edit({{ $item->id }})" />
                            {{-- @endcan --}}

                            {{-- @can('admin.cajas.conceptos.delete') --}}
                            <x-button-delete wire:loading.attr="disabled"
                                onclick="confirmDeleteSlider({{ $item }})" />
                        </div>
                    </div>
                </x-simple-card>
            @endforeach
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Actualizar slider') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form x-data="fileUploadEdit" wire:submit.prevent="save" class="w-full flex flex-col gap-2">
                <div class="w-full relative">
                    <x-simple-card class="w-full h-72 mx-auto mb-1">
                        @if (isset($image))
                            <template x-if="image">
                                <img :src="image"
                                    class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster">
                            </template>
                        @else
                            @if ($slider->url)
                                <img class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster"
                                    src="{{ $slider->getImageURL() }}" />
                            @else
                                <x-icon-file-upload type="file" class="w-full h-full text-gray-300" />
                            @endif
                        @endif
                    </x-simple-card>

                    <div class="w-full flex flex-wrap gap-2 justify-center">
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
                            <x-input-file for="{{ $id_image }}" titulo="SELECCIONAR IMAGEN" wire:loading.remove>
                                <input type="file" class="hidden" id="{{ $id_image }}" wire:model.defer="image"
                                    accept="image/jpg, image/jpeg, image/png" @change="selectFile" />
                            </x-input-file>
                        @endif
                    </div>
                    <x-jet-input-error for="image" class="text-center" />
                </div>


                <div class="w-full">
                    <x-label class="mt-3" value="Link enlace :" />
                    <x-input class="block w-full" wire:model.defer="slider.link"
                        placeholder="Ingrese link para redireccionar al hacer click..." />
                    <x-jet-input-error for="slider.link" />
                </div>

                <div class="w-full grid md:grid-cols-2 gap-2">
                    <div class="w-full">
                        <x-label class="mt-3" value="Fecha inicio :" />
                        <x-input type="date" class="block w-full" wire:model.defer="slider.start" />
                        <x-jet-input-error for="slider.start" />
                    </div>

                    <div class="w-full">
                        <x-label class="mt-3" value="Fecha expiración :" />
                        <x-input type="date" class="block w-full" wire:model.defer="slider.end" />
                        <x-jet-input-error for="slider.end" />
                    </div>
                </div>

                {{ print_r($errors->all()) }}

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('fileUploadEdit', () => ({
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
            const estado = slider.status == '0' ? 'INACTIVO' : 'ACTIVO';
            swal.fire({
                title: 'Desea cambiar el estado de publicación del slider como ' + estado + ' ?',
                text: "Se actualizará un registro de la base de datos",
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
