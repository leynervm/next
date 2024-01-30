<div>
    <div class="flex flex-col xl:flex-row gap-8 animate__animated animate__fadeIn animate__faster">
        <x-form-card titulo="DATOS PRODUCTO" subtitulo="Información del producto registrado.">
            <form class="w-full bg-body p-3 rounded relative flex flex-col gap-2" wire:submit.prevent="update"
                x-data="showproducto">
                <div class="w-full grid grid-cols-1 sm:grid-cols-2 4xl:grid-cols-3 gap-2">
                    <div class="w-full sm:col-span-2">
                        <x-label value="Descripcion producto :" />
                        <x-input class="block w-full" wire:model.defer="producto.name" />
                        <x-jet-input-error for="producto.name" />
                    </div>

                    <div class="w-full">
                        <x-label value="Marca :" />
                        <div class="relative" id="parentmrcpdto" x-init="selectMarca" wire:ignore>
                            <x-select class="block w-full" id="mrcpdto" x-ref="selectmarca" data-placeholder="null">
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
                        <x-jet-input-error for="producto.marca_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Modelo:" />
                        <x-input class="block w-full" wire:model.defer="producto.modelo" />
                        <x-jet-input-error for="producto.modelo" />
                    </div>

                    <div class="w-full">
                        <x-label value="Codigo fabricante :" />
                        <x-input class="block w-full" wire:model.defer="producto.codefabricante" placeholder="Cádigo del fabricante..." />
                        <x-jet-input-error for="producto.codefabricante" />
                    </div>

                    <div class="w-full">
                        <x-label value="Unidad medida :" />
                        <div class="relative" id="parentundpdto" x-init="selectUnit" wire:ignore>
                            <x-select class="block w-full" id="undpdto" x-ref="selectunit" data-placeholder="null">
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
                        <x-jet-input-error for="producto.unit_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Categoría :" />
                        <div class="relative" id="parentctgpdto" x-data="selectCategory" wire:ignore>
                            <x-select class="block w-full" id="ctgpdto" x-ref="selectcat" data-placeholder="null"
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
                        <x-jet-input-error for="producto.category_id" />
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
                        <x-jet-input-error for="producto.subcategory_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="Precio compra :" />
                        <x-input class="block w-full" wire:model.defer="producto.pricebuy" type="number" min="0"
                            step="0.0001" />
                        <x-jet-input-error for="producto.pricebuy" />
                    </div>

                    @if (mi_empresa()->uselistprice == 0 ?? 0)
                        <div class="w-full">
                            <x-label value="Precio venta :" />
                            <x-input class="block w-full" wire:model.defer="producto.pricesale" type="number"
                                min="0" step="0.0001" />
                            <x-jet-input-error for="producto.pricesale" />
                        </div>
                    @endif

                    {{-- <div class="w-full">
                        <x-label value="IGV :" />
                        <x-input class="block w-full" wire:model.defer="producto.igv" type="number" min="0"
                            step="0.0001" />
                        <x-jet-input-error for="producto.igv" />
                    </div> --}}

                    <div class="w-full">
                        <x-label value="Stock Mínimo :" />
                        <x-input class="block w-full" wire:model.defer="producto.minstock" type="number" step="1"
                            min="0" />
                        <x-jet-input-error for="producto.minstock" />
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
                            <x-jet-input-error for="producto.almacenarea_id" />
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
                            <x-jet-input-error for="producto.estante_id" />
                        </div>
                    @endif
                </div>

                @if (Module::isEnabled('Almacen'))
                    <div class="w-full">
                        <x-label-check for="publicado_dit">
                            <x-input wire:model="producto.publicado" name="publicado" value="1" type="checkbox"
                                id="publicado_dit" />
                            DISPONIBLE TIENDA WEB
                        </x-label-check>
                    </div>
                @endif

                <div class="w-full flex pt-4 gap-2 justify-end">
                    <x-button-secondary wire:click="$emit('producto.confirmDelete', {{ $producto }})"
                        wire:loading.attr="disabled">
                        ELIMINAR
                    </x-button-secondary>
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('ACTUALIZAR') }}
                    </x-button>
                </div>

                <div wire:loading.flex wire:target="update, producto.publicado, producto.category_id, delete"
                    class="loading-overlay rounded hidden">
                    <x-loading-next />
                </div>
            </form>

            {{-- <div class="w-full sm:w-1/2">
                <div class="w-full inline-flex flex-wrap gap-1 justify-between items-start">
                    <x-span-text :text="'MARCA:' . $producto->marca->name" />
                    @if ($producto->marca->logo)
                        <div class="w-24 h-14">
                            <img src="{{ asset('storage/marcas/' . $producto->marca->logo) }}" alt=""
                                class="w-full h-full object-scale-down">
                        </div>
                    @endif
                </div>
            </div> --}}

        </x-form-card>

        <x-form-card titulo="ALMACÉN" subtitulo="Permite tener el mismo producto en múltiples amacénes.">
            <div class="w-full flex flex-col gap-2 h-full">
                <div wire:loading.flex wire:target="savealmacen, deletealmacen"
                    class="loading-overlay rounded hidden">
                    <x-loading-next />
                </div>

                @if (count($producto->almacens))
                    <div class="w-full flex flex-wrap gap-2">
                        @foreach ($producto->almacens as $item)
                            <div
                                class="flex flex-col gap-1 bg-fondominicard text-colorminicard justify-between w-32 h-32 border border-borderminicard rounded-xl shadow shadow-shadowminicard p-1 cursor-pointer hover:shadow-md hover:shadow-shadowminicard">
                                <div class="h-full flex flex-col gap-1 justify-center items-center">
                                    <span class="block w-6 h-6 mx-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                            <polyline points="3.29 7 12 12 20.71 7" />
                                            <line x1="12" x2="12" y1="22" y2="12" />
                                        </svg>
                                    </span>

                                    <h1 class="text-[10px] text-center leading-3 font-semibold">{{ $item->name }}
                                    </h1>

                                    <h1 class="text-xl text-center leading-4 font-semibold">
                                        {{ floatval($item->pivot->cantidad) }}
                                        <span class="w-full text-center text-[10px] font-normal">
                                            {{ $producto->unit->name }}</span>
                                    </h1>
                                </div>

                                <div class="flex justify-end items-end gap-1">
                                    <x-button-edit wire:click="editalmacen({{ $item->id }})"
                                        wire:loading.attr="disabled" />
                                    <x-button-delete
                                        wire:click="$emit('producto.confirmDeleteAlmacen',{{ $item }})"
                                        wire:loading.attr="disabled" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- @if (Module::isEnabled('Almacen')) --}}
                <div class="flex justify-end mt-auto">
                    <x-button wire:click="openmodal" wire:target="openmodal" wire:loading.attr="disabled">
                        AÑADIR ALMACEN
                    </x-button>
                </div>
                {{-- @endif --}}
            </div>
        </x-form-card>
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Agregar almacén') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savealmacen">
                <div class="w-full">
                    <x-label value="Nombre :" />
                    @if ($almacen->id ?? null)
                        <x-disabled-text :text="$almacen->name" />
                    @else
                        <x-select class="block w-full relative" id="almacen_id" wire:model.defer="almacen_id"
                            data-dropdown-parent="null">
                            <x-slot name="options">
                                @if (count($almacens))
                                    @foreach ($almacens as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                    @endif
                    <x-jet-input-error for="almacen_id" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="Cantidad (Stock) :" />
                    <x-input class="block w-full" wire:model.defer="newcantidad" type="number" step="0.01" />
                    <x-jet-input-error for="newcantidad" />
                </div>

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('showproducto', () => ({
                marca_id: @entangle('producto.marca_id').defer,
                unit_id: @entangle('producto.unit_id').defer,
                category_id: @entangle('producto.category_id').defer,
                subcategory_id: @entangle('producto.subcategory_id').defer,
                almacenarea_id: @entangle('producto.almacenarea_id').defer,
                estante_id: @entangle('producto.estante_id').defer,
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

        document.addEventListener("livewire:load", () => {
            Livewire.on("producto.confirmDeleteAlmacen", data => {
                swal.fire({
                    title: 'Eliminar almacen del producto con nombre: ' + data.name,
                    text: "Se eliminará un registro de la base de datos, incluyendo sus registros vinculadas.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // console.log(data.detail);
                        @this.deletealmacen(data.id);
                        // Livewire.emitTo('almacen.productos.show-producto', 'deletealmacen', data.id);
                    }
                })
            });

            Livewire.on("producto.confirmDelete", data => {
                swal.fire({
                    title: 'Eliminar registro',
                    text: "Se eliminará un registro de la base de datos con nombre: " + data.name +
                        ", incluyendo todos los datos relacionados.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.delete();
                        // Livewire.emitTo('almacen::productos.show-producto', 'delete', data.id);
                    }
                })
            });
        })
    </script>
</div>
