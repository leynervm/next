<div>
    <div class="flex flex-col gap-8">
        <x-form-card titulo="DATOS PRODUCTO" x-data="showproducto">
            <form class="w-full relative flex flex-col gap-2" x-on:submit.prevent="savedescripcion" {{-- wire:submit.prevent="update" --}}>
                <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                    <div class="w-full xs:col-span-2 sm:col-span-3 lg:col-span-4">
                        <x-label value="Nombre del producto :" />
                        <x-text-area class="block w-full" wire:model.defer="producto.name" rows="1"
                            x-ref="name_producto" style="overflow:hidden;resize:none;"
                            x-on:input="adjustHeight($el)"></x-text-area>
                        <x-jet-input-error for="producto.name" />
                    </div>

                    <div class="w-full">
                        <x-label value="SKU :" />
                        @if ($empresa->autogenerateSku())
                            <x-disabled-text :text="$producto->sku" class="" />
                        @else
                            <x-input class="block w-full" wire:model.defer="producto.sku" />
                        @endif
                        @if (empty($skuold))
                            <small class="text-[11px] text-colorerror leading-none font-semibold">
                                sku pendiente de actualizar</small>
                        @endif
                        <x-jet-input-error for="producto.sku" />
                    </div>

                    <div class="w-full">
                        <x-label value="Marca :" />
                        <div class="relative" id="parentmrcpdto" x-init="selectMarca" wire:ignore>
                            <x-select class="block w-full" id="mrcpdto" x-ref="selectmarca" data-placeholder="null">
                                <x-slot name="options">
                                    @if (count($marcas) > 0)
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
                        <x-label value="N° parte :" />
                        <x-input class="block w-full" wire:model.defer="producto.partnumber"
                            placeholder="N° parte del producto..." />
                        <x-jet-input-error for="producto.partnumber" />
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
                        <x-jet-input-error for="producto.subcategory_id" />
                    </div>

                    {{-- <div class="w-full">
                        <x-label value="IGV :" />
                        <x-input class="block w-full" wire:model.defer="producto.igv" type="number" min="0"
                            step="0.0001" />
                        <x-jet-input-error for="producto.igv" />
                    </div> --}}

                    <div class="w-full">
                        <x-label value="Stock mínimo :" />
                        <x-input class="block w-full input-number-none" wire:model.defer="producto.minstock"
                            type="number" step="1" min="0" />
                        <x-jet-input-error for="producto.minstock" />
                    </div>

                    @if (Module::isEnabled('Marketplace'))
                        <div class="w-full">
                            <x-label value="Límite de stock en tienda web :" />
                            <x-input class="block w-full input-number-none" wire:model.defer="producto.maxstockweb"
                                type="number" step="1" min="0" />
                            <x-jet-input-error for="producto.maxstockweb" />
                        </div>
                    @endif

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

                    <div class="w-full">
                        <x-label value="Precio compra :" />
                        <x-input class="block w-full input-number-none" wire:model.defer="producto.pricebuy"
                            type="number" min="0" step="0.001"
                            onkeypress="return validarDecimal(event, 9)" />
                        <x-jet-input-error for="producto.pricebuy" />
                    </div>

                    @if (!$empresa->usarLista())
                        <div class="w-full">
                            <x-label value="Precio venta :" />
                            <x-input class="block w-full input-number-none" wire:model.defer="producto.pricesale"
                                type="number" min="0" step="0.001"
                                onkeypress="return validarDecimal(event, 9)" />
                            <x-jet-input-error for="producto.pricesale" />
                        </div>
                    @endif
                </div>

                @if ($empresa->usarLista())
                    <div class="w-full py-2 pt-5">
                        <x-label value="PRECIOS DE VENTA" class="!text-colortitleform font-semibold mb-2" />
                        @if (count($pricetypes) > 0)
                            <div
                                class="w-full grid gap-2 xs:grid-cols-2 sm:grid-cols-4 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-7">
                                @foreach ($pricetypes as $item)
                                    @php
                                        $dec = '00000001';
                                        $step = '0.' . substr($dec, -$item->decimals);
                                    @endphp
                                    @if (in_array($item->campo_table, array_keys($producto->getAttributes())))
                                        <div class="w-full" x-data="{ {{ $item->campo_table }}: @entangle('producto.' . $item->campo_table).defer }">
                                            <x-label :value="$item->name . ' (S/)'" class="w-full text-left" />
                                            @can('admin.administracion.pricetypes.productos')
                                                <x-input class="block w-full text-center input-number-none"
                                                    x-model="{{ $item->campo_table }}" wire:key="{{ rand() }}"
                                                    type="number" x-init="{{ $item->campo_table }} = parseFloat({{ $item->campo_table }}).toFixed({{ $item->decimals }})" step="{{ $step }}"
                                                    onkeypress="return validarDecimal(event, 12)" />
                                                <x-jet-input-error for="producto.{{ $item->campo_table }}" />
                                            @endcan
                                            @cannot('admin.administracion.pricetypes.productos')
                                                <x-disabled-text class="w-full text-center block"
                                                    text="S/.  {{ number_format($producto->{$item->campo_table} ?? 0, $item->decimals, '.', ', ') }}" />
                                            @endcannot
                                        </div>

                                        @if ($empresa->verDolar())
                                            @if ($empresa->tipocambio > 0)
                                                {{-- <h1 class="text-center relative pt-1 text-colorlabel text-xs">
                                    S/. {{ decimalOrInteger($producto[$item->campo_table], 2, ', ') }}</h1> --}}
                                            @else
                                                <p class="text-center tracking-widest text-colorerror">
                                                    TIPO CAMBIO NO CONFIGURADO</p>
                                            @endif
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <div class="w-full flex flex-col gap-1 justify-start items-start">
                    @if (Module::isEnabled('Marketplace'))
                        <div>
                            <x-label-check for="publicado_dit">
                                <x-input wire:model.defer="producto.publicado" name="publicado" value="1"
                                    type="checkbox" id="publicado_dit" />
                                DISPONIBLE TIENDA WEB
                            </x-label-check>
                            <x-jet-input-error for="producto.publicado" />
                        </div>
                        <div>
                            <x-label-check for="viewespecificaciones_dit">
                                <x-input wire:model.defer="producto.viewespecificaciones" name="viewespecificaciones"
                                    value="1" type="checkbox" id="viewespecificaciones_dit" />
                                MOSTRAR ESPECIFICACIONES SOBRE IMAGEN EN TIENDA WEB
                            </x-label-check>
                        </div>
                        <div>
                            <x-label-check for="viewdetalle_edit">
                                <x-input wire:model.defer="producto.viewdetalle" name="viewdetalle" value="1"
                                    type="checkbox" id="viewdetalle_edit" />
                                MOSTRAR DETALLES EN TIENDA WEB
                            </x-label-check>
                        </div>
                        <div>
                            <x-label-check for="novedad_edit">
                                <x-input wire:model.defer="producto.novedad" name="novedad" value="1"
                                    type="checkbox" id="novedad_edit" />
                                MARCAR COMO NUEVA ENTRADA
                            </x-label-check>
                        </div>
                    @endif

                    <div>
                        <x-label-check for="requireserie">
                            <x-input wire:model.defer="producto.requireserie" name="requireserie" value="1"
                                type="checkbox" id="requireserie" />REQUIERE AGREGAR SERIES
                        </x-label-check>
                        <x-jet-input-error for="producto.requireserie" />
                    </div>
                </div>

                @can('admin.almacen.productos.edit')
                    <div class="w-full fixed z-[3] bottom-0 p-1 md:p-3 left-0 px-8 bg-body"
                        :class="openSidebar ? 'md:w-[calc(100%-12rem)] md:left-48' : 'md:w-[calc(100%-4rem)] md:left-16'">
                        <div class="w-full xl:max-w-7xl mx-auto flex justify-end px-1 lg:px-5">
                            <x-button type="submit" wire:loading.attr="disabled">
                                {{ __('Save') }}</x-button>
                        </div>
                    </div>
                @endcan
            </form>
        </x-form-card>

        <x-form-card titulo="ALMACÉN" subtitulo="Permite tener el mismo producto en múltiples amacénes.">
            <div class="w-full flex flex-col gap-2 flex-1">
                @if (count($producto->almacens) > 0)
                    <div class="w-full flex flex-wrap gap-2">
                        @foreach ($producto->almacens as $item)
                            <x-minicard :title="null" size="lg" alignFooter="justify-end">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 inline-block mx-auto"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M13 22C12.1818 22 11.4002 21.6588 9.83691 20.9764C8.01233 20.18 6.61554 19.5703 5.64648 19H2M13 22C13.8182 22 14.5998 21.6588 16.1631 20.9764C20.0544 19.2779 22 18.4286 22 17V6.5M13 22L13 11M4 6.5L4 9.5" />
                                    <path
                                        d="M9.32592 9.69138L6.40472 8.27785C4.80157 7.5021 4 7.11423 4 6.5C4 5.88577 4.80157 5.4979 6.40472 4.72215L9.32592 3.30862C11.1288 2.43621 12.0303 2 13 2C13.9697 2 14.8712 2.4362 16.6741 3.30862L19.5953 4.72215C21.1984 5.4979 22 5.88577 22 6.5C22 7.11423 21.1984 7.5021 19.5953 8.27785L16.6741 9.69138C14.8712 10.5638 13.9697 11 13 11C12.0303 11 11.1288 10.5638 9.32592 9.69138Z" />
                                    <path d="M18.1366 4.01563L7.86719 8.98485" />
                                    <path d="M2 13H5" />
                                    <path d="M2 16H5" />
                                </svg>

                                <h1 class="text-[10px] text-center font-semibold leading-3">{{ $item->name }}</h1>
                                <p class="text-xl text-center font-semibold">
                                    {{ floatval($item->pivot->cantidad) }}
                                    <small class="w-full text-center text-[10px] font-normal">
                                        {{ $producto->unit->name }}</small>
                                </p>

                                @can('admin.almacen.productos.almacen')
                                    <x-slot name="buttons">
                                        <x-button-edit wire:click="editalmacen({{ $item->id }})"
                                            wire:loading.attr="disabled" />
                                        <x-button-delete onclick="confirmDeleteAlmacen({{ $item }})"
                                            wire:loading.attr="disabled" />
                                    </x-slot>
                                @endcan
                            </x-minicard>
                        @endforeach
                    </div>
                @endif

                @if (Module::isEnabled('Almacen') || Module::isEnabled('Ventas'))
                    @can('admin.almacen.productos.almacen')
                        <div class="flex justify-end mt-auto">
                            <x-button wire:click="openmodal" wire:loading.attr="disabled">
                                AÑADIR ALMACEN</x-button>
                        </div>
                    @endcan
                @endif
            </div>
        </x-form-card>
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">{{ __('Agregar almacén') }}</x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savealmacen">
                <div class="w-full">
                    <x-label value="Almacén :" />
                    @if ($almacen->id ?? null)
                        <x-disabled-text :text="$almacen->name" />
                    @else
                        <div class="relative" x-data="{ almacen_id: @entangle('almacen_id').defer }" x-init="selectAlmacenP">
                            <x-select class="block w-full relative" x-ref="selectap" id="almacen_id"
                                data-dropdown-parent="null" data-placeholder="null">
                                <x-slot name="options">
                                    @if (count($almacens) > 0)
                                        @foreach ($almacens as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                            <x-icon-select />
                        </div>
                    @endif
                    <x-jet-input-error for="almacen_id" />
                </div>

                <div class="w-full mt-2">
                    <x-label value="Cantidad (Stock) :" />
                    <x-input class="block w-full input-number-none" wire:model.defer="newcantidad" type="number"
                        step="1" min="0" />
                    <x-jet-input-error for="newcantidad" />
                </div>

                <div class="w-full flex pt-4 gap-2 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
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
                comentario: @entangle('producto.comentario').defer,
                init() {
                    const detalle_producto = document.getElementById('ckeditor_descripcion_producto');
                    this.ckeditor_descripcion = detalle_producto.value;

                    this.adjustHeight(this.$refs.name_producto);
                    Livewire.hook('message.processed', () => {
                        this.adjustHeight(this.$refs.name_producto);
                    });
                },
                savedescripcion() {
                    const comment = document.getElementById('comentario');
                    this.comentario = comment.value ?? '';
                    this.$wire.update(this.ckeditor_descripcion).then(route => {
                        if (route) {
                            console.log(route);
                            window.history.replaceState({}, '', route);
                            window.location.reload();
                        }
                    })
                },
                adjustHeight($el) {
                    $el.style.height = 'auto';
                    $el.style.height = $el.scrollHeight + 'px';
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
            this.$watch("subcategory_id", (value) => {
                this.selectS.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectS.select2('destroy');
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

        function selectAlmacenP() {
            this.selectAP = $(this.$refs.selectap).select2();
            this.selectAP.val(this.almacen_id).trigger("change");
            this.selectAP.on("select2:select", (event) => {
                this.almacen_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("almacen_id", (value) => {
                this.selectAP.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectAP.select2().val(this.almacen_id).trigger('change');
            });
        }

        // window.addEventListener('loadsubcategories', subcategories => {
        //     let subcat = document.querySelector('[x-ref="selectsub"]');
        //     $(subcat).val(null).empty().append('<option value="" selected>SELECCIONAR...</option>');
        //     subcategories.detail.forEach(subcateg => {
        //         let option = new Option(subcateg.name, subcateg.id, false, false);
        //         $(subcat).append(option);
        //     });
        //     $(subcat).select2().trigger('change');
        // })

        function confirmDeleteAlmacen(almacen) {
            swal.fire({
                title: 'Desvincular almacén, ' + almacen.name,
                text: "Este producto dejará de estar disponible en el almacén seleccionado.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deletealmacen(almacen.id);
                }
            })
        }

        function confirmDelete(producto) {
            swal.fire({
                title: 'Eliminar producto ' + producto.name + ' ?',
                text: "Se eliminará un registro de la base de datos, incluyendo todos los datos relacionados.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(producto.id);
                }
            })
        }
    </script>
</div>
