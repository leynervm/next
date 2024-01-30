<div>
    <form wire:submit.prevent="save" class="w-full flex flex-col gap-8 relative">
        <x-form-card titulo="DATOS PRODUCTO" subtitulo="Información del nuevo producto a registrar.">
            <div class="w-full grid grid-cols-1 xs:grid-cols-2 xl:grid xl:grid-cols-3 gap-2" x-data="createproducto">
                <div class="w-full xs:col-span-2 xl:col-span-3">
                    <x-label value="Descripcion producto :" />
                    <x-input class="block w-full disabled:bg-gray-200" wire:model.defer="name"
                        placeholder="Descripción del producto..." />
                    <x-jet-input-error for="name" />
                </div>
                <div class="w-full">
                    <x-label value="Marca :" />
                    <div class="relative" id="parentmrcpdto" x-init="selectMarca" wire:ignore>
                        <x-select class="block w-full" id="mrcpdto" x-ref="selectmarca"
                            data-minimum-results-for-search="2">
                            <x-slot name="options">
                                @if (count($marcas))
                                    @foreach ($marcas as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="marca_id" />
                </div>
                <div class="w-full">
                    <x-label value="Modelo :" />
                    <x-input class="block w-full" wire:model.defer="modelo" placeholder="Modelo..." />
                    <x-jet-input-error for="modelo" />
                </div>
                <div class="w-full">
                    <x-label value="Codigo fabricante :" />
                    <x-input class="block w-full" wire:model.defer="codefabricante" placeholder="Cádigo del fabricante..." />
                    <x-jet-input-error for="codefabricante" />
                </div>
                <div class="w-full">
                    <x-label value="Unidad medida :" />
                    <div class="relative" id="parentundpdto" x-init="selectUnit" wire:ignore>
                        <x-select class="block w-full" id="undpdto" x-ref="selectunit">
                            <x-slot name="options">
                                @if (count($units))
                                    @foreach ($units as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="unit_id" />
                </div>

                <div class="w-full">
                    <x-label value="Categoría :" />
                    <div class="relative" id="parentctgpdto" x-data="selectCategory" wire:ignore>
                        <x-select class="block w-full" id="ctgpdto" x-ref="selectcat"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @if (count($categories))
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="category_id" />
                </div>

                <div class="w-full">
                    <x-label value="Subcategoría :" />
                    <div class="relative" id="parentsubcpdto" x-init="selectSubcategory" wire:ignore>
                        <x-select class="block w-full" id="subcpdto" x-ref="selectsub" data-placeholder="null">
                            <x-slot name="options">
                                @if (count($subcategories))
                                    @foreach ($subcategories as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="subcategory_id" />
                </div>

                <div class="w-full">
                    <x-label value="Precio compra :" />
                    <x-input class="block w-full" wire:model.defer="pricebuy" type="number" step="0.01" />
                    <x-jet-input-error for="pricebuy" />
                </div>

                @if (mi_empresa()->uselistprice == 0 ?? 0)
                    <div class="w-full">
                        <x-label value="Precio venta :" />
                        <x-input class="block w-full" wire:model.defer="pricesale" type="number" min="0"
                            step="0.0001" />
                        <x-jet-input-error for="pricesale" />
                    </div>
                @endif

                {{-- <div class="w-full">
                    <x-label value="IGV compra :" />
                    <x-input class="block w-full" wire:model.defer="igv" type="number" step="0.01" />
                    <x-jet-input-error for="igv" />
                </div> --}}

                <div class="w-full">
                    <x-label value="Stock Mínimo :" />
                    <x-input class="block w-full" wire:model.defer="minstock" type="number" step="1"
                        min="0" />
                    <x-jet-input-error for="minstock" />
                </div>

                @if (Module::isEnabled('Almacen'))
                    <div class="w-full">
                        <x-label value="Area :" />
                        <div class="relative" id="parentarea" x-init="selectArea" wire:ignore>
                            <x-select class="block w-full" id="area" x-ref="selectarea"
                                data-placeholder="null">
                                <x-slot name="options">
                                    @if (count($almacenareas))
                                        @foreach ($almacenareas as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="almacenarea_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Estante :" />
                        <div class="relative" id="parentestnt" x-init="selectEstante" wire:ignore>
                            <x-select class="block w-full" id="estnt" x-ref="selectestante"
                                data-placeholder="null">
                                <x-slot name="options">
                                    @if (count($estantes))
                                        @foreach ($estantes as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                        <x-jet-input-error for="estante_id" />
                    </div>
                @endif

                @if (Module::isEnabled('Almacen'))
                    <div class="w-full xs:col-span-2 xl:col-span-3">
                        <x-label-check for="publicado">
                            <x-input wire:model.defer="publicado" name="publicado" value="1" type="checkbox"
                                id="publicado" />
                            DISPONIBLE TIENDA WEB
                        </x-label-check>
                    </div>
                @endif
            </div>
        </x-form-card>

        <x-form-card titulo="ALMACÉN">
            <div class="w-full flex flex-wrap gap-2 items-start justify-start">
                @if (count($almacens))
                    @foreach ($almacens as $item)
                        <x-input-radio class="py-2" for="almacen_{{ $item->id }}" :text="$item->name"
                            textSize="xs">
                            <input wire:model.defer="selectedAlmacens" class="sr-only peer peer-disabled:opacity-25"
                                type="checkbox" id="almacen_{{ $item->id }}" name="almacens"
                                value="{{ $item->id }}" />
                        </x-input-radio>
                    @endforeach
                @endif
            </div>
            <x-jet-input-error for="selectedAlmacens" />
        </x-form-card>

        <x-form-card titulo="IMÁGEN REFERENCIAL">
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
        document.addEventListener('alpine:init', () => {
            Alpine.data('createproducto', () => ({
                marca_id: @entangle('marca_id').defer,
                unit_id: @entangle('unit_id').defer,
                category_id: @entangle('category_id').defer,
                subcategory_id: @entangle('subcategory_id').defer,
                almacenarea_id: @entangle('almacenarea_id').defer,
                estante_id: @entangle('estante_id').defer,

                init() {
                    // selectMarca();
                },

            }));
        })

        function selectUnit() {
            this.selectM = $(this.$refs.selectunit).select2();
            this.selectM.val(this.unit_id).trigger("change");
            this.selectM.on("select2:select", (event) => {
                this.unit_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("unit_id", (value) => {
                this.selectM.val(value).trigger("change");
            });
        }

        function selectMarca() {
            this.selectU = $(this.$refs.selectmarca).select2();
            this.selectU.val(this.marca_id).trigger("change");
            this.selectU.on("select2:select", (event) => {
                this.marca_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("marca_id", (value) => {
                this.selectU.val(value).trigger("change");
            });
        }

        function selectCategory() {
            this.selectC = $(this.$refs.selectcat).select2();
            this.selectC.val(this.category_id).trigger("change");
            this.selectC.on("select2:select", (event) => {
                this.category_id = event.target.value;
                @this.setCategory(event.target.value);
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("category_id", (value) => {
                this.selectC.val(value).trigger("change");
            });
        }

        function selectSubcategory() {
            this.selectS = $(this.$refs.selectsub).select2();
            this.selectS.val(this.subcategory_id).trigger("change");
            this.selectS.on("select2:select", (event) => {
                this.subcategory_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("subcategory_id", (value) => {
                this.selectS.val(value).trigger("change");
            });
        }

        function selectArea() {
            this.selectAA = $(this.$refs.selectarea).select2();
            this.selectAA.val(this.almacenarea_id).trigger("change");
            this.selectAA.on("select2:select", (event) => {
                this.almacenarea_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("almacenarea_id", (value) => {
                this.selectAA.val(value).trigger("change");
            });
        }

        function selectEstante() {
            this.selectE = $(this.$refs.selectestante).select2();
            this.selectE.val(this.estante_id).trigger("change");
            this.selectE.on("select2:select", (event) => {
                this.estante_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("estante_id", (value) => {
                this.selectE.val(value).trigger("change");
            });
        }

        window.addEventListener('loadsubcategories', subcategories => {
            let subcat = document.querySelector('[x-ref="selectsub"]');
            $(subcat).val(null).empty().append('<option value="" selected>SELECCIONAR...</option>');
            subcategories.detail.forEach(subcateg => {
                let option = new Option(subcateg.name, subcateg.id, false, false);
                $(subcat).append(option);
            });
            $(subcat).select2().trigger('change');
        })
    </script>

</div>
