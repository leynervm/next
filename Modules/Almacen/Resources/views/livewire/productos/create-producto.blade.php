<div>
    <form wire:submit.prevent="save" id="form_create_producto" class="mt-3 max-w-7xl md:p-3 mx-auto shadow-xl rounded">

        <x-card-next titulo="Datos producto" class="mt-3 border border-next-500">
            <div class="flex flex-wrap sm:grid sm:grid-cols-3 gap-3">
                <div class="w-full sm:col-span-2 gap-3">
                    <x-label value="Descripcion producto :" />
                    <x-input class="block w-full disabled:bg-gray-200" wire:model.defer="name" />
                    <x-jet-input-error for="name" />
                </div>
                <div class="w-full">
                    <x-label value="Marca :" />
                    {{-- soft-scrollbar --}}
                    <x-select class="block w-full" id="marcadproducto_id" wire:model.defer="marca_id">
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
            </div>
        </x-card-next>


        <div class="w-full lg:flex lg:flex-nowrap gap-3 ">
            <x-card-next titulo="Almacén" class="lg:w-1/2 mt-3 border border-next-500">
                <div class="w-full mb-2">
                    @if (count($almacens))
                        @foreach ($almacens as $item)
                            <div class="inline-flex">
                                <x-button-checkbox class="tracking-widest" for="almacen_{{ $item->name }}"
                                    :text="$item->name">
                                    <input wire:model.defer="selectedAlmacens" class="sr-only peer" type="checkbox"
                                        id="almacen_{{ $item->name }}" name="almacens" value="{{ $item->id }}" />
                                </x-button-checkbox>
                            </div>
                        @endforeach
                    @endif
                    <x-jet-input-error for="selectedAlmacens" />
                </div>
                <div class="flex flex-wrap sm:grid md:grid-cols-2 gap-3">
                    <div class="w-full gap-3">
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
                    <div class="w-full gap-3">
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
                </div>
            </x-card-next>
            <x-card-next titulo="Imagen referencial" class="lg:w-1/2 mt-3 border border-next-500">
                <div class="relative" x-data="{ isUploading: @entangle('isUploading'), progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                    x-on:livewire-upload-finish="isUploading = false"
                    x-on:livewire-upload-error="$wire.emit('errorImage'), isUploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">

                    <div x-show="isUploading" class="loading-overlay rounded">
                        <div class="spinner"></div>
                    </div>

                    @if (isset($imagen))
                        <div class="w-full h-60 md:max-w-md mx-auto mb-1 duration-300">
                            <img class="w-full h-full object-scale-down" src="{{ $imagen->temporaryUrl() }}"
                                alt="">
                        </div>
                    @endif

                    <x-input-file :for="$identificador" titulo="SELECCIONAR IMAGEN" wire:loading.remove
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
            </x-card-next>
        </div>

        <div class="mt-3 mb-1">
            <x-label
                class="inline-flex items-center tracking-widest text-xs font-semibold gap-2 cursor-pointer bg-next-100 rounded-lg p-1"
                for="publicado">
                <x-input wire:model.defer="publicado" name="publicado" type="checkbox" id="publicado"
                    value="1" />
                DISPONIBLE TIENDA WEB
            </x-label>
        </div>
        <x-jet-input-error for="tribute.id" />

        <div class="w-full py-2 text-center">
            <p wire:loading class="text-xs tracking-widest shadow-lg text-next-500 rounded-lg bg-white p-1 px-2">
                Cargando...</p>
        </div>

        <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
            <x-button type="submit" size="xs" class="" wire:loading.attr="disabled" wire:target="save">
                {{ __('REGISTRAR') }}
            </x-button>
        </div>
    </form>

    <script>
        document.addEventListener("livewire:load", () => {

            renderSelect2();

            $("#categoryproducto_id").on("change", (e) => {
                deshabilitarSelects();
                @this.category_id = e.target.value;
            });

            $("#subcategoryproducto_id").on("change", (e) => {
                deshabilitarSelects();
                @this.subcategory_id = e.target.value;
            });

            $("#unidadproducto_id").on("change", (e) => {
                deshabilitarSelects();
                @this.unit_id = e.target.value;
            });

            $("#marcadproducto_id").on("change", (e) => {
                // e.target.setAttribute("disabled", true);
                deshabilitarSelects();
                @this.marca_id = e.target.value;
            });

            $("#almacenareaproducto_id").on("change", (e) => {
                deshabilitarSelects();
                @this.almacenarea_id = e.target.value;
            });

            $("#estanteproducto_id").on("change", (e) => {
                deshabilitarSelects();
                @this.estante_id = e.target.value;
            });

            window.addEventListener('render-producto-select2', () => {
                renderSelect2();
            });

            function renderSelect2() {
                var formulario = document.getElementById("form_create_producto");
                var selects = formulario.getElementsByTagName("select");

                for (var i = 0; i < selects.length; i++) {
                    if (selects[i].id !== "") {
                        $("#" + selects[i].id).select2({
                            placeholder: "Seleccionar...",
                        });
                    }
                }
            }

            function deshabilitarSelects() {
                var formulario = document.getElementById("form_create_producto");
                var selects = formulario.getElementsByTagName("select");

                for (var i = 0; i < selects.length; i++) {
                    selects[i].disabled = true;
                }
            }

        })
    </script>

</div>
