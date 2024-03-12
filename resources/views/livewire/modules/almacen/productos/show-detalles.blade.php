<div>
    <div class="flex flex-col xl:flex-row gap-8 animate__animated animate__fadeIn animate__faster">
        <x-form-card titulo="ESPECIFICACIONES" subtitulo="Características y specificaciones del producto registrado.">
            <div class="w-full flex flex-col gap-3 rounded h-full">
                @if (count($producto->especificaciones))
                    <div class="w-full flex flex-wrap gap-1">
                        @foreach ($producto->especificaciones as $item)
                            <span
                                class="text-[10px] inline-flex gap-2 items-center justify-between p-1 font-medium rounded-md bg-fondospancardproduct text-textspancardproduct">
                                {{ $item->caracteristica->name }} : {{ $item->name }}

                                @can('admin.almacen.productos.especificaciones')
                                    <x-button-delete onclick="confirmDeleteEspecificacion({{ $item }})"
                                        wire:loading.attr="disabled" />
                                @endcan
                            </span>
                        @endforeach
                    </div>
                @endif

                @can('admin.almacen.productos.especificaciones')
                    <div class="w-full pt-4 flex justify-end mt-auto">
                        <x-button wire:click="openmodal" wire:loading.attr="disabled">
                            AÑADIR ESPECIFICACIÓN</x-button>
                    </div>
                @endcan
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
                                    <x-span-text :text="$item->url" class="truncate leading-3" />
                                    @can('admin.almacen.productos.images')
                                        <x-button-delete onclick="confirmDeleteImage({{ $item->id }})"
                                            wire:loading.attr="disabled" />
                                    @endcan
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
                                    @can('admin.almacen.productos.images')
                                        <button type="button" wire:loading.attr="disabled"
                                            wire:click="defaultimage({{ $item->id }})"
                                            class="absolute top-1 -left-7 w-6 h-6 group-hover:translate-x-8 rounded-full bg-green-500 p-1 text-white focus:ring-2 focus:ring-green-300 transition ease-out duration-150 hover:scale-110 disabled:opacity-25">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="20 6 9 17 4 12" />
                                            </svg>
                                        </button>
                                    @endcan
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                @can('admin.almacen.productos.images')
                    <div class="w-full pt-4 flex justify-end">
                        <x-button wire:click="openmodalimage" wire:loading.attr="disabled">AÑADIR IMAGEN</x-button>
                    </div>
                @endcan
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
                                <legend class="text-colorlabel text-xs px-1">{{ $item->name }}</legend>
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
                <div class="w-full relative">
                    @if (isset($imagen))
                        <x-simple-card
                            class="w-full h-60 md:max-w-md mx-auto mb-1 border border-borderminicard animate__animated animate__fadeIn animate__faster">
                            <img src="{{ $imagen->temporaryUrl() }}"
                                class="w-full h-full object-scale-down animate__animated animate__fadeIn animate__faster">
                        </x-simple-card>
                    @else
                        <x-icon-file-upload class="w-full h-60 text-gray-300" />
                    @endif

                    <div wire:loading.flex class="loading-overlay rounded hidden">
                        <x-loading-next />
                    </div>

                    <div class="w-full flex flex-wrap gap-2 justify-center">
                        <x-input-file :for="$identificador" titulo="SELECCIONAR IMAGEN" wire:loading.attr="disabled"
                            wire:target="imagen">
                            <input type="file" class="hidden" wire:model="imagen" id="{{ $identificador }}"
                                accept="image/jpg, image/jpeg, image/png" />
                        </x-input-file>

                        @if (isset($imagen))
                            <x-button class="inline-flex" wire:loading.attr="disabled" type="submit">
                                GUARDAR
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M17.4776 9.01106C17.485 9.01102 17.4925 9.01101 17.5 9.01101C19.9853 9.01101 22 11.0294 22 13.5193C22 15.8398 20.25 17.7508 18 18M17.4776 9.01106C17.4924 8.84606 17.5 8.67896 17.5 8.51009C17.5 5.46695 15.0376 3 12 3C9.12324 3 6.76233 5.21267 6.52042 8.03192M17.4776 9.01106C17.3753 10.1476 16.9286 11.1846 16.2428 12.0165M6.52042 8.03192C3.98398 8.27373 2 10.4139 2 13.0183C2 15.4417 3.71776 17.4632 6 17.9273M6.52042 8.03192C6.67826 8.01687 6.83823 8.00917 7 8.00917C8.12582 8.00917 9.16474 8.38194 10.0005 9.01101" />
                                    <path
                                        d="M12 21L12 13M12 21C11.2998 21 9.99153 19.0057 9.5 18.5M12 21C12.7002 21 14.0085 19.0057 14.5 18.5" />
                                </svg>
                            </x-button>

                            <x-button class="inline-flex" wire:loading.attr="disabled" wire:target="clearImage"
                                wire:click="clearImage">LIMPIAR
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                    <line x1="10" x2="10" y1="11" y2="17" />
                                    <line x1="14" x2="14" y1="11" y2="17" />
                                </svg>
                            </x-button>
                        @endif
                    </div>

                </div>
                <x-jet-input-error wire:loading.remove wire:target="imagen" for="imagen" class="text-center" />
                <x-jet-input-error for="producto.id" />
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        function confirmDeleteImage(image) {
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
                    @this.deleteimage(image);
                }
            })
        }

        function confirmDeleteEspecificacion(especificacion) {
            swal.fire({
                title: 'Eliminar especificación ' + especificacion.name,
                text: "Se eliminará un registro de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(especificacion.id);
                }
            })
        }
    </script>
</div>
