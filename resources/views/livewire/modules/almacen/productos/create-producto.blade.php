<div>
    <form wire:submit.prevent="save" class="w-full flex flex-col gap-8 relative">
        <x-form-card titulo="DATOS PRODUCTO" widthBefore="before:w-28" subtitulo="Información del producto registrado.">
            <div class="w-full grid grid-cols-1 xs:grid-cols-2 xl:grid xl:grid-cols-3 gap-2">
                <div class="w-full xs:col-span-2 xl:col-span-3">
                    <x-label value="Descripcion producto :" />
                    <x-input class="block w-full disabled:bg-gray-200" wire:model.defer="name" />
                    <x-jet-input-error for="name" />
                </div>
                <div class="w-full">
                    <x-label value="Marca :" />
                    {{-- soft-scrollbar --}}
                    <x-select class="block w-full" id="marcadproducto_id" wire:model.defer="marca_id"
                        data-dropdown-parent="null">
                        <x-slot name="options">
                            @if (count($marcas))
                                @foreach ($marcas as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="marca_id" />
                </div>
                <div class="w-full">
                    <x-label value="Modelo :" />
                    <x-input class="block w-full" wire:model.defer="modelo" />
                    <x-jet-input-error for="modelo" />
                </div>
                <div class="w-full">
                    <x-label value="Unidad medida :" />
                    <x-select class="block w-full" id="unidadproducto_id" wire:model.defer="unit_id">
                        <x-slot name="options">
                            @if (count($units))
                                @foreach ($units as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="unit_id" />
                </div>
                <div class="w-full">
                    <x-label value="Precio compra :" />
                    <x-input class="block w-full" wire:model.defer="pricebuy" type="number" step="0.01" />
                    <x-jet-input-error for="pricebuy" />
                </div>
                <div class="w-full">
                    <x-label value="IGV compra :" />
                    <x-input class="block w-full" wire:model.defer="igv" type="number" step="0.01" />
                    <x-jet-input-error for="igv" />
                </div>
                <div class="w-full">
                    <x-label value="Categoría :" />
                    <x-select class="block w-full" id="categoryproducto_id" wire:model.target="category_id">
                        <x-slot name="options">
                            @if (count($categories))
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="category_id" />
                </div>
                <div class="w-full">
                    <x-label value="Subcategoría :" />
                    <x-select class="block w-full" id="subcategoryproducto_id" wire:model.defer="subcategory_id">
                        <x-slot name="options">
                            @if (count($subcategories))
                                @foreach ($subcategories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="subcategory_id" />
                </div>
                <div class="w-full">
                    <x-label value="Area :" />
                    <x-select class="block w-full" id="almacenareaproducto_id" wire:model.defer="almacenarea_id">
                        <x-slot name="options">
                            @if (count($almacenareas))
                                @foreach ($almacenareas as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="almacenarea_id" />
                </div>
                <div class="w-full">
                    <x-label value="Estante :" />
                    <x-select class="block w-full" id="estanteproducto_id" wire:model.defer="estante_id">
                        <x-slot name="options">
                            @if (count($estantes))
                                @foreach ($estantes as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="estante_id" />
                </div>
                <div class="w-full xs:col-span-2 xl:col-span-3">
                    <x-label-check for="publicado">
                        <x-input wire:model.defer="publicado" name="publicado" value="1" type="checkbox"
                            id="publicado" />
                        DISPONIBLE TIENDA WEB
                    </x-label-check>
                </div>
            </div>
        </x-form-card>

        <x-form-card titulo="ALMACÉN" widthBefore="before:w-14" subtitulo="Información del producto registrado.">
            <div class="w-full flex flex-wrap gap-1 items-start justify-start">
                @if (count($almacens))
                    @foreach ($almacens as $item)
                        <x-button-checkbox class="tracking-widest" for="almacen_{{ $item->name }}" :text="$item->name">
                            <input wire:model.defer="selectedAlmacens" class="sr-only peer" type="checkbox"
                                id="almacen_{{ $item->name }}" name="almacens" value="{{ $item->id }}" />
                        </x-button-checkbox>
                    @endforeach
                @endif
            </div>
            <x-jet-input-error for="selectedAlmacens" />
        </x-form-card>

        <x-form-card titulo="IMÁGEN REFERENCIAL" widthBefore="before:w-32"
            subtitulo="Información del producto registrado.">
            <div class="w-full xs:max-w-xs">
                <div class="relative" x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
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
                            <svg class="text-neutral-500 w-24 h-24 block" xmlns="http://www.w3.org/2000/svg"
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
                                <x-button class="inline-flex px-6" wire:loading.attr="disabled"
                                    wire:target="clearImage" wire:click="clearImage">
                                    LIMPIAR
                                </x-button>
                            </x-slot>
                        @endif
                    </x-input-file>
                </div>
                <x-jet-input-error wire:loading.remove wire:target="imagen" for="imagen" class="text-center" />
            </div>
        </x-form-card>

        <div class="w-full flex pt-4 gap-2 justify-end">
            <x-button type="submit" wire:loading.attr="disabled">
                {{ __('REGISTRAR') }}
            </x-button>
        </div>

        <div wire:loading.flex wire:target="save, category_id" class="loading-overlay rounded hidden">
            <x-loading-next />
        </div>
    </form>


    <script>
        document.addEventListener("livewire:load", () => {

            // renderSelect2();

            // $("#categoryproducto_id").on("change", (e) => {
            //     deshabilitarSelects();
            //     @this.category_id = e.target.value;
            // });

            // $("#subcategoryproducto_id").on("change", (e) => {
            //     deshabilitarSelects();
            //     @this.subcategory_id = e.target.value;
            // });

            // $("#unidadproducto_id").on("change", (e) => {
            //     deshabilitarSelects();
            //     @this.unit_id = e.target.value;
            // });

            // $("#marcadproducto_id").on("change", (e) => {
            //     deshabilitarSelects();
            //     @this.marca_id = e.target.value;
            // });

            // $("#almacenareaproducto_id").on("change", (e) => {
            //     deshabilitarSelects();
            //     @this.almacenarea_id = e.target.value;
            // });

            // $("#estanteproducto_id").on("change", (e) => {
            //     deshabilitarSelects();
            //     @this.estante_id = e.target.value;
            // });

            // window.addEventListener('render-producto-select2', () => {
            //     renderSelect2();
            // });

            // function renderSelect2() {
            //     var formulario = document.getElementById("form_create_producto");
            //     var selects = formulario.getElementsByTagName("select");

            //     for (var i = 0; i < selects.length; i++) {
            //         if (selects[i].id !== "") {
            //             $("#" + selects[i].id).select2({
            //                 placeholder: "Seleccionar...",
            //             });
            //         }
            //     }
            // }

            // function deshabilitarSelects() {
            //     var formulario = document.getElementById("form_create_producto");
            //     var selects = formulario.getElementsByTagName("select");

            //     for (var i = 0; i < selects.length; i++) {
            //         selects[i].disabled = true;
            //     }
            // }

        })
    </script>

</div>
