<div>
    <div class="w-full grid {{ Module::isEnabled('Marketplace') ? 'xl:grid-cols-2 gap-8' : '' }}">
        @if (Module::isEnabled('Marketplace'))
            <x-form-card titulo="ESPECIFICACIONES" subtitulo="Características y specificaciones del producto."
                class="flex-1 justify-between">
                <div class="w-full flex flex-col flex-1 justify-between">
                    <div class="w-full">
                        <ul class="w-full flex flex-col max-h-60 overflow-x-auto" id="especificacions">
                            @if (count($producto->especificacions) > 0)
                                <li
                                    class="w-full flex items-center bg-fondoheadertable text-textheadertable rounded-t-md text-[10px] p-2">
                                    <div class="w-full flex flex-1 gap-2">
                                        ESPECIFICACION</div>
                                    <div class="flex-shrink-0">OPCIONES</div>
                                </li>
                            @endif
                            @foreach ($producto->especificacions as $item)
                                <li data-id="{{ $item->id }}"
                                    class="w-full p-1 flex gap-2 rounded text-textbodytable bg-fondobodytable text-[10px] hover:bg-fondohovertable">
                                    <div class="w-full flex-1 flex gap-2 items-center">
                                        <x-icon-sweep class="w-6 h-6 xs:w-8 xs:h-8 block" />
                                        <p class="w-full flex-1 font-medium">
                                            {{ $item->caracteristica->name }} :
                                            <b>{{ $item->name }}</b>
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <x-button-delete onclick="confirmDeleteEspecificacion({{ $item }})"
                                            wire:loading.attr="disabled" />
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        @if (Module::isEnabled('Marketplace'))
                            <div class="w-full {{ count($producto->especificacions) > 0 ? 'mt-1' : '' }}"
                                x-data="{
                                    comment: '',
                                    init() {
                                        this.adjustHeight(this.$refs.comentario);
                                        Livewire.hook('message.processed', () => {
                                            this.adjustHeight(this.$refs.comentario);
                                        });
                                    },
                                    adjustHeight($el) {
                                        $el.style.height = 'auto';
                                        $el.style.height = $el.scrollHeight + 'px';
                                    }
                                }">
                                <x-label value="Otros :" />
                                <x-text-area class="block w-full" rows="1" style="overflow:hidden;resize:none;"
                                    x-ref="comentario" x-on:input="adjustHeight($el)" id="comentario"
                                    wire:model.defer="producto.comentario"></x-text-area>
                                <x-jet-input-error for="producto.comentario" />
                            </div>
                        @endif
                    </div>

                    <div class="w-full pt-4 flex flex-wrap sm:flex-nowrap items-end gap-1 justify-end">
                        @if (count($caracteristicas) > 0)
                            @can('admin.almacen.productos.especificaciones')
                                <x-button wire:click="openmodal" wire:loading.attr="disabled">
                                    AÑADIR ESPECIFICACIÓNES</x-button>
                            @endcan
                        @endif
                        @can('admin.almacen.productos.especificaciones')
                            <x-link-button href="{{ route('admin.almacen.caracteristicas') }}">
                                NUEVAS CARACTERÍSTICAS...</x-link-button>
                        @endcan
                    </div>
                </div>
            </x-form-card>
        @endif

        <x-form-card titulo="IMÁGENES" subtitulo="Agregar múltiples images para una mejor visualización del producto."
            class="flex-1 justify-between">
            <div class="w-full flex h-full flex-1 flex-col justify-between gap-3">
                <div class="w-full grid grid-cols-[repeat(auto-fill,minmax(100px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(140px,1fr))] gap-1 self-start"
                    id="drag_images">
                    @if (count($producto->images) > 0)
                        @foreach ($producto->images as $item)
                            <div class="w-full flex flex-col justify-between group border border-borderminicard rounded-lg relative"
                                data-id="{{ $item->id }}">
                                <div class="w-full max-w-full overflow-hidden">
                                    <img src="{{ pathURLProductImage($item->urlmobile) }}" alt=""
                                        class="block w-full rounded-lg h-auto max-h-20 sm:max-h-28 object-scale-down object-center">
                                </div>
                                <div class="w-full flex gap-1 justify-between p-1">
                                    <x-span-text :text="$item->urlmobile" class="truncate leading-3 flex-1" />
                                    @can('admin.almacen.productos.images')
                                        <x-button-delete onclick="confirmDeleteImage({{ $item->id }})"
                                            wire:loading.attr="disabled" />
                                    @endcan
                                </div>

                                <x-icon-sweep
                                    class="absolute top-1 left-1 bg-fondospancardproduct text-textspancardproduct size-6 xs:size-7" />
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            @can('admin.almacen.productos.images')
                <div class="w-full pt-4 gap-2 flex justify-between items-end">
                    <p class="text-[10px] text-center text-colorsubtitleform">
                        Resolución Mínima : 500px X 500px</p>
                    <x-button wire:click="openmodalimage" wire:loading.attr="disabled">AÑADIR IMAGEN</x-button>
                </div>
            @endcan
        </x-form-card>
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Agregar especificaciones') }}
        </x-slot>

        <x-slot name="content">
            <div class="w-full flex flex-col gap-2 pb-2">
                <div class="w-full">
                    <x-label value="Buscar :" />
                    <div class="relative">
                        <x-input class="block w-full disabled:bg-gray-200" wire:model.lazy="searchcaracteristica"
                            placeholder="Buscar..." />
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="block p-1 w-auto h-full text-next-300 absolute right-1 top-0 bottom-0">
                            <path
                                d="M11 6C13.7614 6 16 8.23858 16 11M16.6588 16.6549L21 21M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" />
                        </svg>
                    </div>

                    <x-jet-input-error for="searchespecificacion" />
                </div>

                @if ($caracteristicas->hasPages())
                    <div class="w-full flex justify-end items-center py-2">
                        {{ $caracteristicas->onEachSide(0)->links('livewire::pagination-default') }}
                    </div>
                @endif
            </div>

            <form wire:submit.prevent="saveespecificacion" class="">
                <div class="w-full overflow-y-auto max-h-[500px]">
                    @if (count($caracteristicas) > 0)
                        @foreach ($caracteristicas as $item)
                            <fieldset class="w-full block border p-2 rounded border-primary mb-2">
                                <legend class="text-colorlabel text-xs px-1">{{ $item->name }}</legend>
                                <div class="w-full flex gap-2 flex-wrap">
                                    @if (count($item->especificacions))
                                        @foreach ($item->especificacions as $especificacion)
                                            <x-input-radio :for="'especificacion_' . $especificacion->id" :text="$especificacion->name" class="text-wrap">
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
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <x-jet-dialog-modal wire:model="openimage" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Agregar nueva imágen') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="saveimage" x-data="loadimage()">
                <div class="h-96 w-auto max-w-full relative mb-2 overflow-hidden">
                    <template x-if="image">
                        <img id="image" class="object-scale-down block w-full h-full" :src="image" />
                    </template>
                    <template x-if="!image">
                        <x-icon-file-upload class="w-full h-full" />
                    </template>
                </div>

                <div class="w-full flex flex-wrap items-end justify-center gap-1">
                    <template x-if="image">
                        <x-button class="inline-flex !rounded-lg" wire:loading.attr="disabled" x-on:click="reset">
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
                    </template>
                    <template x-if="image">
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
                    </template>
                    <x-input-file for="{{ $identificador }}" titulo="SELECCIONAR IMÁGEN" class="">
                        <input type="file" id="{{ $identificador }}" name="photo" @change="loadlogo"
                            accept="image/*" class="hidden disabled:opacity-25" wire:loading.attr="disabled" />
                    </x-input-file>
                </div>
                <x-jet-input-error for="imagen" class="text-center" />
                <x-jet-input-error for="producto.id" />
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    @if (Module::isEnabled('Marketplace'))
        <script>
            document.addEventListener("DOMContentLoaded", function(event) {
                new Sortable(especificacions, {
                    animation: 150,
                    scrollSpeed: 40,
                    ghostClass: 'bg-fondospancardproduct',
                    handle: '.handle',
                    store: {
                        set: function(sortable) {
                            const sorts = Array.from(sortable.el.children)
                                .filter(item => item.dataset.id)
                                .map(item => item.dataset.id);

                            // const sorts = sortable.toArray();
                            const producto_id = '{{ $producto->id }}';
                            axios.post("{{ route('api.sort.especificacions') }}", {
                                sorts: sorts,
                                producto_id: producto_id
                            }).catch(function(error) {
                                console.log(error.response.data.message);
                            });
                        }
                    },
                })
            })
        </script>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            new Sortable(drag_images, {
                animation: 150,
                ghostClass: 'bg-fondospancardproduct',
                handle: '.handle',
                store: {
                    set: function(sortable) {
                        const sorts = Array.from(sortable.el.children)
                            .filter(item => item.dataset.id).map(item => item.dataset.id);
                        axios.post("{{ route('api.sort.producto.images') }}", {
                            sorts: sorts,
                            producto_id: '{{ $producto->id }}'
                        }).catch(function(error) {
                            console.log(error);
                        });
                    }
                },
            })
        })

        function loadimage() {
            return {
                image: @entangle('imagen').defer,
                identificador: @entangle('identificador').defer,
                loadlogo() {
                    const input = document.getElementById(this.identificador);
                    if (!input || !input.files || !input.files[0]) {
                        console.log('No se seleccionó ningún archivo');
                        return;
                    }
                    const file = input.files[0];
                    var reader = new FileReader();
                    reader.onload = (e) => this.image = e.target.result;
                    reader.readAsDataURL(file);
                },
                reset() {
                    // this.image = null;
                    @this.clearImage();
                },
            }
        }

        function confirmDeleteImage(image) {
            swal.fire({
                title: "ELIMINAR IMÁGEN",
                text: null,
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
                title: 'ELIMINAR ESPECIFICACIÓN ' + especificacion.name,
                text: null,
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
