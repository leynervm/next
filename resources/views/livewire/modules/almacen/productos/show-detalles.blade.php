<div>
    <div class="flex flex-col xl:flex-row gap-8 animate__animated animate__fadeIn animate__faster">
        <x-form-card titulo="ESPECIFICACIONES" subtitulo="Características y specificaciones del producto registrado.">
            <div class="w-full flex flex-col gap-3 bg-body p-3 rounded">
                @if (count($producto->especificaciones))
                    <div class="w-full flex flex-wrap gap-1">
                        @foreach ($producto->especificaciones as $item)
                            <span
                                class="text-[10px] inline-flex gap-2 items-center justify-between p-1 font-medium rounded-md bg-fondospancardproduct text-textspancardproduct">
                                {{ $item->caracteristica->name }} : {{ $item->name }}

                                <x-button-delete
                                    wire:click="$emit('producto.confirmDeleteEspecificacion',{{ $item }})"
                                    wire:loading.attr="disabled" />
                            </span>
                        @endforeach
                    </div>
                @endif
                <div class="w-full pt-4 flex justify-end">
                    <x-button wire:click="openmodal" wire:loading.attr="disabled">
                        AÑADIR ESPECIFICACIÓN</x-button>
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="IMÁGENES" subtitulo="Permite tener el mismo producto en múltiples amacénes.">
            <div class="w-full flex flex-col gap-3">
                @if (count($producto->images))
                    <div class="w-full flex flex-wrap gap-1">
                        @foreach ($producto->images as $item)
                            <div
                                class="w-48 group shadow border border-borderminicard shadow-shadowminicard rounded-md relative overflow-hidden hover:shadow-md hover:shadow-shadowminicard">
                                <div class="w-full h-24 block">
                                    <img src="{{ asset('storage/productos/' . $item->url) }}" alt=""
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="w-full flex gap-1 justify-between p-1">
                                    <x-span-text :text="$item->url" class="truncate" />
                                    <x-button-delete
                                        wire:click="$emit('producto.confirmDeleteImage',{{ $item->id }})"
                                        wire:loading.attr="disabled" />
                                </div>
                                @if ($item->default == 1)
                                    <span
                                        class="absolute top-1 left-1 w-6 h-6 rounded-full bg-green-400 p-1 text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                    </span>
                                @else
                                    <button type="button" wire:loading.attr="disabled"
                                        wire:click="defaultimage({{ $item->id }})"
                                        class="absolute top-1 -left-7 w-6 h-6 group-hover:translate-x-8 rounded-full bg-green-500 p-1 text-white focus:ring-2 focus:ring-green-300 transition ease-out duration-150 hover:scale-110 disabled:opacity-25">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="w-full pt-4 flex justify-end">
                    <x-button wire:click="openmodalimage" wire:loading.attr="disabled">AÑADIR IMAGEN</x-button>
                </div>
            </div>
        </x-form-card>
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Agregar especificaciones') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="saveespecificacion">
                <div class="w-full">
                    @if (count($caracteristicas))
                        @foreach ($caracteristicas as $item)
                            <fieldset class="w-full border p-2 rounded border-primary mb-2">
                                <legend class="text-primary text-sm px-1">{{ $item->name }}</legend>
                                <div class="w-full flex gap-2 flex-wrap">
                                    @if (count($item->especificacions))
                                        @foreach ($item->especificacions as $especificacion)
                                            <x-input-radio :for="'especificacion_' . $especificacion->id" :text="$especificacion->name">
                                                <x-input wire:model.defer="selectedEspecificacion.{{ $item->id }}"
                                                    class="sr-only peer" type="radio" :id="'especificacion_' . $especificacion->id"
                                                    :name="'especificaciones_' . $item->id . '[]'" value="{{ $especificacion->id }}" />
                                            </x-input-radio>
                                        @endforeach
                                    @endif
                                </div>
                            </fieldset>
                        @endforeach
                    @endif
                    <x-jet-input-error for="selectedEspecificacion" />
                </div>

                <div class="w-full flex pt-4 gap-2 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR CAMBIOS') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <x-jet-dialog-modal wire:model="openimage" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Agregar nueva imágen') }}
            <x-button-close-modal wire:click="$toggle('openimage')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="saveimage">
                <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                    x-on:livewire-upload-finish="isUploading = false"
                    x-on:livewire-upload-error="$emit('errorImage'), isUploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress" class="w-full relative">

                    @if (isset($imagen))
                        <div
                            class="w-full h-60 shadow-md shadow-shadowminicard border rounded-lg border-borderminicard overflow-hidden mb-1 duration-300">
                            <img class="w-full h-full object-scale-down" src="{{ $imagen->temporaryUrl() }}"
                                alt="">
                        </div>
                    @else
                        <div
                            class="w-full flex items-center justify-center h-60 shadow-md shadow-shadowminicard border rounded-lg border-borderminicard mb-1">
                            <svg class="text-neutral-500 w-24 h-24" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path
                                    d="M13 3.00231C12.5299 3 12.0307 3 11.5 3C7.02166 3 4.78249 3 3.39124 4.39124C2 5.78249 2 8.02166 2 12.5C2 16.9783 2 19.2175 3.39124 20.6088C4.78249 22 7.02166 22 11.5 22C15.9783 22 18.2175 22 19.6088 20.6088C20.9472 19.2703 20.998 17.147 20.9999 13" />
                                <path
                                    d="M2 14.1354C2.61902 14.0455 3.24484 14.0011 3.87171 14.0027C6.52365 13.9466 9.11064 14.7729 11.1711 16.3342C13.082 17.7821 14.4247 19.7749 15 22" />
                                <path
                                    d="M21 16.8962C19.8246 16.3009 18.6088 15.9988 17.3862 16.0001C15.5345 15.9928 13.7015 16.6733 12 18" />
                                <path
                                    d="M17 4.5C17.4915 3.9943 18.7998 2 19.5 2M22 4.5C21.5085 3.9943 20.2002 2 19.5 2M19.5 2V10" />
                            </svg>
                        </div>
                    @endif

                    <div x-show="isUploading" wire:loading.flex class="loading-overlay rounded">
                        <x-loading-next />
                    </div>

                    <x-input-file :for="$identificador" titulo="SELECCIONAR IMAGEN" wire:loading.attr="disabled"
                        wire:target="imagen">
                        <input type="file" class="hidden" wire:model="imagen" id="{{ $identificador }}"
                            accept="image/jpg, image/jpeg, image/png" />

                        @if (isset($imagen))
                            <x-slot name="clear">
                                <x-button class="inline-flex" wire:loading.attr="disabled" type="submit">
                                    REGISTRAR
                                </x-button>
                                <x-button class="inline-flex" wire:loading.attr="disabled" wire:target="clearImage"
                                    wire:click="clearImage">
                                    LIMPIAR
                                </x-button>
                            </x-slot>
                        @endif
                    </x-input-file>
                </div>
                <x-jet-input-error wire:loading.remove wire:target="imagen" for="imagen" class="text-center" />
                <x-jet-input-error for="producto.id" />
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        document.addEventListener("livewire:load", () => {
            Livewire.on("producto.confirmDeleteEspecificacion", data => {
                swal.fire({
                    title: 'Eliminar especificación del producto ' + data.name,
                    text: "Se eliminará un registro de la base de datos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.delete(data.id);
                    }
                })
            });

            Livewire.on("producto.confirmDeleteImage", data => {
                swal.fire({
                    title: 'Eliminar imágen del producto',
                    text: "Se eliminará un registro de la base de datos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.deleteimage(data);
                    }
                })
            });
        })
    </script>
</div>
