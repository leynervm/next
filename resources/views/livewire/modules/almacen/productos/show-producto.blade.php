<div>
    <div class="flex flex-col xl:flex-row gap-8 animate__animated animate__fadeIn animate__faster">
        <x-form-card titulo="DATOS PRODUCTO" subtitulo="Información del producto registrado.">
            <form wire:submit.prevent="update"
                class="w-full bg-body p-3 rounded relative grid grid-cols-1 xs:grid-cols-2 2xl:grid-cols-3 gap-2">

                <div class="w-full xs:col-span-2 2xl:col-span-3">
                    <x-label value="NOMBRE:" textSize="[10px]" class="font-semibold" />
                    <x-input class="block w-full" wire:model.defer="producto.name" />
                    <x-jet-input-error for="producto.name" />
                </div>

                <div class="w-full">
                    <x-label value="PRECIO COMPRA:" textSize="[10px]" class="font-semibold" />
                    <x-input class="block w-full" wire:model.defer="producto.pricebuy" type="number" min="0"
                        step="0.0001" />
                    <x-jet-input-error for="producto.pricebuy" />
                </div>

                <div class="w-full">
                    <x-label value="PRECIO VENTA:" textSize="[10px]" class="font-semibold" />
                    <x-input class="block w-full" wire:model.defer="producto.pricesale" type="number" min="0"
                        step="0.0001" />
                    <x-jet-input-error for="producto.pricesale" />
                </div>

                <div class="w-full">
                    <x-label value="IGV:" textSize="[10px]" class="font-semibold" />
                    <x-input class="block w-full" wire:model.defer="producto.igv" type="number" min="0"
                        step="0.0001" />
                    <x-jet-input-error for="producto.igv" />
                </div>

                <div class="w-full">
                    <x-label value="MARCA:" textSize="[10px]" class="font-semibold" />
                    <x-select class="block w-full" id="parentmarcaproducto_id" wire:model.defer="producto.marca_id"
                        id="marcaproducto_id">
                        <x-slot name="options">
                            @if (count($marcas))
                                @foreach ($marcas as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="producto.marca_id" />
                </div>

                <div class="w-full">
                    <x-label value="UNIDAD MEDIDA:" textSize="[10px]" class="font-semibold" />
                    <x-select class="block w-full" id="editunitproducto" wire:model.defer="producto.unit_id"
                        id="unitproducto_id">
                        <x-slot name="options">
                            @if (count($units))
                                @foreach ($units as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="producto.unit_id" />
                </div>

                @if (Module::isEnabled('Almacen'))
                    <div class="w-full">
                        <x-label value="AREA:" textSize="[10px]" class="font-semibold" id="almacenareaproducto_id" />
                        <x-select class="block w-full" id="editalmacenareaproducto"
                            wire:model.defer="producto.almacenarea_id">
                            <x-slot name="options">
                                @if (count($almacenareas))
                                    @foreach ($almacenareas as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="producto.almacenarea_id" />
                    </div>

                    <div class="w-full">
                        <x-label value="ESTANTE:" textSize="[10px]" class="font-semibold" />
                        <x-select class="block w-full" id="editestanteproducto" wire:model.defer="producto.estante_id"
                            id="estanteproducto_id">
                            <x-slot name="options">
                                @if (count($estantes))
                                    @foreach ($estantes as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-jet-input-error for="producto.estante_id" />
                    </div>
                @endif

                <div class="w-full">
                    <x-label value="CATEGORÍA:" textSize="[10px]" class="font-semibold" />
                    {{-- <div x-data="{ category_id: @entangle('producto.category_id') }" x-init="select2CategoryAlpine" id="parentcategoryproducto_id"> --}}
                    <x-select class="block w-full" wire:model.lazy="producto.category_id" id="categoryproducto_id">
                        <x-slot name="options">
                            @if (count($categories))
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    {{-- </div> --}}
                    <x-jet-input-error for="producto.category_id" />
                </div>

                <div class="w-full">
                    <x-label value="SUBCATEGORÍA:" textSize="[10px]" class="font-semibold" />
                    <x-select class="block w-full" wire:model.defer="producto.subcategory_id"
                        id="editsubcategoryproducto">
                        <x-slot name="options">
                            @if (count($subcategories))
                                @foreach ($subcategories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="producto.subcategory_id" />
                </div>

                @if (Module::isEnabled('Almacen'))
                    <div class="w-full xs:col-span-2 2xl:col-span-3">
                        <x-label-check for="publicado_dit">
                            <x-input wire:model="producto.publicado" name="publicado" value="1" type="checkbox"
                                id="publicado_dit" />
                            DISPONIBLE TIENDA WEB
                        </x-label-check>
                    </div>
                @endif

                <div class="w-full xs:col-span-2 2xl:col-span-3 flex pt-4 gap-1 justify-end">
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
        // function select2CategoryAlpine() {
        //     this.select2 = $(this.$refs.select).select2();
        //     this.select2.val(this.category_id).trigger("change");
        //     this.select2.on("change", (event) => {
        //         event.target.setAttribute("disabled", true);
        //         // this.select2.attr('disabled', true);
        //         @this.set('producto.category_id', event.target.value)
        //         this.category_id = event.target.value;
        //     }).on('select2:open', function(e) {
        //         const evt = "scroll.select2";
        //         $(e.target).parents().off(evt);
        //         $(window).off(evt);
        //     });
        //     this.$watch('category_id', (value) => {
        //         this.select2.val(value).trigger("change");
        //         this.select2 = $(this.$refs.select).select2();
        //     });
        // }

        // function select2SubcategoryAlpine() {
        //     this.select2 = $(this.$refs.select).select2();
        //     this.select2.val(this.subcategory_id).trigger("change");
        //     this.select2.on("select2:select", (event) => {
        //         this.select2.attr('disabled', true);
        //         // @this.set('producto.subcategory_id', event.target.value)
        //         this.subcategory_id = event.target.value;
        //     }).on('select2:open', function(e) {
        //         const evt = "scroll.select2";
        //         $(e.target).parents().off(evt);
        //         $(window).off(evt);
        //     });
        //     this.$watch('subcategory_id', (value) => {
        //         this.select2.val(value).trigger("change");
        //     });
        // }


        // $("#newalmacen_id").select2({}).on("change", function(e) {
        //     e.target.setAttribute("disabled", true);
        //     @this.newalmacen_id = e.target.value;
        // }).on('select2:open', function(e) {
        //     const evt = "scroll.select2";
        //     $(e.target).parents().off(evt);
        //     $(window).off(evt);
        // });

        // $("#almacenproducto_id").select2({}).on("change", function(e) {
        //     e.target.setAttribute("disabled", true);
        //     @this.almacen_id = e.target.value;
        // }).on('select2:open', function(e) {
        //     const evt = "scroll.select2";
        //     $(e.target).parents().off(evt);
        //     $(window).off(evt);
        // });

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
