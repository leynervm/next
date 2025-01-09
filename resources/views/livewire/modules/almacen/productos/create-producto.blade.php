<div x-data="createproducto" x-on:confirmsave.window ="confirmsave">
    <div wire:loading.flex class="loading-overlay hidden fixed">
        <x-loading-next />
    </div>

    <form wire:submit.prevent="save" class="w-full flex flex-col gap-8">
        <x-form-card titulo="DATOS PRODUCTO" subtitulo="Información del nuevo producto a registrar.">
            <div class="w-full grid grid-cols-1 xs:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                <div class="w-full xs:col-span-2 md:col-span-3 lg:col-span-4">
                    <x-label value="Nombre del producto :" />
                    <x-text-area rows="1" class="block w-full disabled:bg-gray-200" wire:model.defer="name"
                        x-ref="name_producto" style="overflow:hidden;resize:none;"
                        x-on:input="adjustHeight($el)"></x-text-area>
                    <x-jet-input-error for="name" />
                </div>

                <div class="w-full">
                    <x-label value="SKU :" />
                    @if ($empresa->autogenerateSku())
                        <x-disabled-text :text="$sku" class="" />
                    @else
                        <x-input class="block w-full" wire:model.defer="sku" />
                    @endif
                    <x-jet-input-error for="sku" />
                </div>

                <div class="w-full">
                    <x-label value="Marca :" />
                    <div class="relative" id="parentmrcpdto" x-init="selectMarca">
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
                    <x-label value="N° parte :" />
                    <x-input class="block w-full" wire:model.defer="partnumber"
                        placeholder="N° parte del producto..." />
                    <x-jet-input-error for="partnumber" />
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
                    <div class="relative" id="parentctgpdto" x-data="selectCategory">
                        <x-select class="block w-full" id="ctgpdto" x-ref="selectcat"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @if (count($categories) > 0)
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
                    <div class="relative" id="parentsubcpdto" x-init="selectSubcategory">
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
                    <x-label value="Stock Mínimo :" />
                    <x-input class="block w-full input-number-none" wire:model.defer="minstock" type="number"
                        step="1" min="0" onkeypress="return validarDecimal(event, 9)" />
                    <x-jet-input-error for="minstock" />
                </div>

                @if (Module::isEnabled('Almacen'))
                    <div class="w-full">
                        <x-label value="Area :" />
                        <div class="relative" id="parentarea" x-init="selectArea" wire:ignore>
                            <x-select class="block w-full" id="area" x-ref="selectarea" data-placeholder="null">
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

                <div class="w-full">
                    <x-label value="Precio compra :" />
                    <x-input class="block w-full input-number-none" wire:model.defer="pricebuy" type="number"
                        step="0.001" onkeypress="return validarDecimal(event, 9)" />
                    <x-jet-input-error for="pricebuy" />
                </div>

                @if (!$empresa->usarLista())
                    <div class="w-full">
                        <x-label value="Precio venta :" />
                        <x-input class="block w-full input-number-none" wire:model.defer="pricesale" type="number"
                            min="0" onkeypress="return validarDecimal(event, 9)" step="0.001" />
                        <x-jet-input-error for="pricesale" />
                    </div>
                @endif

                {{-- @if (Module::isEnabled('Marketplace'))
                    <div
                        class="w-full xs:col-span-2 {{ $empresa->usarLista() ? 'lg:col-span-3' : 'md:col-span-3 lg:col-span-2' }}  xl:col-span-5">
                        <x-label value="Comentario :" />
                        <x-text-area class="block w-full" rows="1" wire:model.defer="comentario"
                            x-ref="comentario" x-on:input="adjustHeight($el)"
                            style="overflow:hidden;resize:none;"></x-text-area>
                        <x-jet-input-error for="comentario" />
                    </div>
                @endif --}}
            </div>

            <div class="w-full flex flex-col gap-1 justify-start items-start mt-2">
                @if (Module::isEnabled('Marketplace'))
                    <div>
                        <x-label-check for="publicado">
                            <x-input wire:model.defer="publicado" name="publicado" value="1" type="checkbox"
                                id="publicado" />DISPONIBLE TIENDA WEB
                        </x-label-check>
                        <x-jet-input-error for="publicado" />
                    </div>
                    <div>
                        <x-label-check for="viewespecificaciones">
                            <x-input wire:model.defer="viewespecificaciones" name="viewespecificaciones"
                                value="1" type="checkbox" id="viewespecificaciones" />
                            MOSTRAR ESPECIFICACIONES EN TIENDA WEB
                        </x-label-check>
                    </div>
                    <div>
                        <x-label-check for="viewdetalle">
                            <x-input wire:model.defer="viewdetalle" x-model="viewdetalle" name="viewdetalle"
                                value="1" type="checkbox" id="viewdetalle" />
                            MOSTRAR DETALLES EN TIENDA WEB
                        </x-label-check>
                    </div>
                    <div>
                        <x-label-check for="novedad">
                            <x-input wire:model.defer="novedad" x-model="novedad" name="novedad" value="1"
                                type="checkbox" id="novedad" />
                            MARCAR COMO NUEVA ENTRADA
                        </x-label-check>
                    </div>
                @endif
                <div>
                    <x-label-check for="requireserie">
                        <x-input wire:model.defer="requireserie" name="requireserie" value="1" type="checkbox"
                            id="requireserie" />REQUIERE AGREGAR SERIES
                    </x-label-check>
                    <x-jet-input-error for="requireserie" />
                </div>
            </div>

            <x-simple-card class="mt-2 !border-0 hover:!shadow-none">
                <x-label value="SELECCIONAR ALMACÉNES" class="!text-colortitleform font-semibold mt-6 mb-2" />

                @if (count($almacens) > 0)
                    <div class="w-full flex flex-wrap gap-2 items-start justify-start">
                        @foreach ($almacens as $item)
                            <x-input-radio class="py-2" for="almacen_{{ $item->id }}" :text="$item->name"
                                textSize="xs">
                                <input wire:model.defer="selectedAlmacens"
                                    class="sr-only peer peer-disabled:opacity-25" type="checkbox"
                                    id="almacen_{{ $item->id }}" name="almacens" value="{{ $item->id }}" />
                            </x-input-radio>
                        @endforeach
                    </div>
                @endif
                <x-jet-input-error for="selectedAlmacens" />
            </x-simple-card>
        </x-form-card>

        <x-form-card titulo="IMÁGEN REFERENCIAL">
            <div class="w-full xs:max-w-sm">
                <div class="relative" class="w-full relative">
                    <x-simple-card class="w-full h-60 mx-auto border border-borderminicard">
                        @if (isset($imagen))
                            <img src="{{ $imagen->temporaryUrl() }}" class="w-full h-full object-scale-down ">
                        @else
                            <x-icon-file-upload class="w-full h-full" />
                        @endif
                    </x-simple-card>
                    <p class="text-[10px] text-center text-colorsubtitleform">
                        Resolución Mínima : 500px X 500px</p>

                    <div class="w-full flex flex-wrap gap-1 justify-center">
                        <x-input-file :for="$identificador" titulo="SELECCIONAR IMAGEN" wire:loading.attr="disabled"
                            wire:target="imagen">
                            <input type="file" class="hidden" wire:model="imagen" id="{{ $identificador }}"
                                accept="image/jpg,image/jpeg,image/png,image/webp" />
                        </x-input-file>
                        @if (isset($imagen))
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
                <x-jet-input-error for="imagen" class="text-center" />
            </div>
        </x-form-card>

        @if (Module::isEnabled('Marketplace'))
            <div class="w-full block" x-cloack x-show="viewdetalle" wire:ignore style="display: none;">
                <x-ckeditor-5 id="myckeditor" wire:model.defer="descripcionproducto" />
            </div>
        @endif

        <div class="w-full flex justify-center items-center sm:justify-end p-1 sticky -bottom-1 right-0 bg-body">
            <x-button type="submit" wire:loading.attr="disabled">{{ __('Save') }}</x-button>
        </div>
    </form>
    {{-- <script src="{{ asset('assets/vendor/ckeditor5/build/ckeditor.js') }}"></script> --}}
    <script src="{{ asset('assets/ckeditor5/ckeditor5_38.1.1_super-build_ckeditor.js') }}"></script>
    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script> --}}

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('createproducto', () => ({
                marca_id: @entangle('marca_id').defer,
                unit_id: @entangle('unit_id').defer,
                category_id: @entangle('category_id'),
                subcategory_id: @entangle('subcategory_id').defer,
                almacenarea_id: @entangle('almacenarea_id').defer,
                estante_id: @entangle('estante_id').defer,
                viewdetalle: @entangle('viewdetalle').defer,
                novedad: @entangle('novedad').defer,
                // descripcionproducto: @entangle('descripcionproducto').defer,
                init() {
                    this.adjustHeight(this.$refs.name_producto);
                    Livewire.hook('message.processed', () => {
                        this.adjustHeight(this.$refs.name_producto);
                    });
                },
                adjustHeight($el) {
                    $el.style.height = 'auto';
                    $el.style.height = $el.scrollHeight + 'px';
                },
                confirmsave() {
                    swal.fire({
                        title: "YA EXISTE UN PRODUCTO CON EL MISMO MODELO Y MARCA",
                        text: null,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#0FB9B9',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Continuar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.$wire.save(true).then(() => {}).catch(error => {
                                console.error('Error al ejecutar la función:', error);
                            });
                        }
                    })
                }
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
            Livewire.hook('message.processed', () => {
                this.selectU.select2().val(this.marca_id).trigger('change');
            });
        }

        function selectCategory() {
            this.selectC = $(this.$refs.selectcat).select2();
            this.selectC.val(this.category_id).trigger("change");
            this.selectC.on("select2:select", (event) => {
                this.category_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("category_id", (value) => {
                this.selectC.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectC.select2().val(this.category_id).trigger('change');
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

            Livewire.hook('message.processed', () => {
                this.selectS.select2().val(this.subcategory_id).trigger('change');
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
    </script>
</div>
