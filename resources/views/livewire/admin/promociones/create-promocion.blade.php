@if (count($productos) > 0)
    <div>
        <x-button-next titulo="Registrar" wire:click="$set('open', true)">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" x2="12" y1="5" y2="19" />
                <line x1="5" x2="19" y1="12" y2="12" />
            </svg>
        </x-button-next>
        <x-jet-dialog-modal wire:model="open" maxWidth="4xl" footerAlign="justify-end">
            <x-slot name="title">
                {{ __('Crear promoción') }}
            </x-slot>

            <x-slot name="content">
                <div class="w-full" x-data="loader">
                    @if (getCombo()->count() > 0)
                        <div class="w-full flex flex-col gap-2">
                            <div class="w-full grid grid-cols-1 gap-2">
                                <div class="w-full">
                                    <div
                                        class="w-full max-w-xs mx-auto h-60 border rounded-xl border-borderminicard overflow-hidden mb-1 duration-300 relative">
                                        @if ($producto && !empty($producto['image']))
                                            <img src="{{ pathURLProductImage($producto['image']) }}" alt=""
                                                class="w-full h-full object-center object-scale-down">
                                        @else
                                            <x-icon-file-upload type="unknown"
                                                class="!w-full !h-full block !border-0" />
                                        @endif

                                        @if ($producto)
                                            <div
                                                class="absolute right-1 bottom-1 flex flex-col gap-1 bg-fondominicard text-colorminicard justify-center items-center w-20 h-20 border border-borderminicard rounded-xl p-1 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="block w-6 h-6 mx-auto"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path
                                                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                                    <polyline points="3.29 7 12 12 20.71 7" />
                                                    <line x1="12" x2="12" y1="22" y2="12" />
                                                </svg>

                                                <h1 class="text-[8px] text-center leading-3 font-semibold">
                                                    {{ $agotarstock ? 'HASTA AGOTAR STOCK' : 'LIMITE STOCK' }}</h1>

                                                <h1 class="text-xl text-center leading-4 font-semibold">
                                                    @if ($agotarstock)
                                                    @else
                                                        {{ floatval(getCombo()->get('limit')) }}
                                                        <small class="text-[10px]">{{ getCombo()->get('unit') }}</small>
                                                    @endif
                                                </h1>
                                            </div>
                                        @endif
                                    </div>

                                    @if ($producto)
                                        <h1 class="text-xs leading-3 text-center text-colortitleform mt-3">
                                            {{ $producto['name'] }}</h1>
                                        <x-jet-input-error for="itempromos" />
                                    @endif
                                </div>

                                <x-title-next titulo="AGREGAR PRODUCTOS SECUNDARIOS" class="mt-5" />

                                @if (count(getCombo()->get('comboitems')) > 0)
                                    <div class="w-full flex flex-wrap gap-2">
                                        @foreach (getCombo()->get('comboitems') as $item)
                                            <div
                                                class="w-full border border-borderminicard rounded-lg md:rounded-xl p-1 max-w-48">
                                                <div class="w-full h-24 rounded-lg">
                                                    @if (!empty($item->image))
                                                        <img src="{{ pathURLProductImage($item->image) }}"
                                                            alt="{{ pathURLProductImage($item->image) }}"
                                                            class="w-full h-full object-scale-down object-center">
                                                    @else
                                                        <x-icon-file-upload type="unknown"
                                                            class="!w-full !h-full block !border-0" />
                                                    @endif
                                                </div>

                                                <h1
                                                    class="text-[10px] text-colorsubtitleform text-center !leading-none mt-2">
                                                    {{ $item->name }}</h1>
                                                <div class="w-full flex items-end justify-between gap-2 mt-1">

                                                    @if ($item->typecombo == \App\Models\Itempromo::SIN_DESCUENTO)
                                                        <x-span-text text="SIN DESCUENTO" type="orange"
                                                            class="leading-3 !tracking-normal" />
                                                    @elseif ($item->typecombo == \App\Models\Itempromo::DESCUENTO)
                                                        <x-span-text :text="decimalOrInteger($item->descuento) . '% DSCT'" type="green"
                                                            class="leading-3 !tracking-normal" />
                                                    @else
                                                        <x-span-text text="GRATIS" type="green"
                                                            class="leading-3 !tracking-normal" />
                                                    @endif

                                                    <x-button-delete wire:click="deleteitem({{ $item->producto_id }})"
                                                        wire:loading.attr="disabled" />
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <x-jet-input-error for="comboitems" />
                                <x-jet-input-error for="pricebuy" />
                            </div>
                        </div>

                        <form wire:submit.prevent="add" class="w-full mt-6 flex flex-col gap-2">
                            <div class="">
                                <x-label value="Producto secundario :" />
                                <div class="relative" id="parentproductosec_id" x-init="selectProductoSec" wire:ignore>
                                    <x-select class="block w-full" x-ref="selectps" id="productosec_id"
                                        data-dropdown-parent="null" data-minimum-results-for-search="3"
                                        data-placeholder="null">
                                        @if (count($productos) > 0)
                                            <x-slot name="options">
                                                @foreach ($productos as $item)
                                                    <option data-marca="{{ $item->name_marca }}"
                                                        data-category="{{ $item->name_category }}"
                                                        data-subcategory="{{ $item->name_subcategory }}"
                                                        data-image="{{ !empty($item->image) ? pathURLProductImage($item->image) : null }}"
                                                        value="{{ $item->id }}">
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </x-slot>
                                        @endif
                                    </x-select>
                                    <x-icon-select />
                                </div>
                                <x-jet-input-error for="productosec_id" />
                                <x-jet-input-error for="limitstocksec" />
                            </div>

                            <div class="w-full">
                                <x-label value="Tipo combo :" />
                                <div class="w-full flex flex-wrap gap-2">
                                    <x-input-radio class="py-2" for="nodiscount" text="NO USAR DESCUENTO">
                                        <input wire:model.defer="typecombo"
                                            class="sr-only peer peer-disabled:opacity-25" type="radio"
                                            id="nodiscount" name="typecombo" value="0" x-model="typecombo" />
                                    </x-input-radio>
                                    <x-input-radio class="py-2" for="discount" text="DESCUENTO">
                                        <input wire:model.defer="typecombo"
                                            class="sr-only peer peer-disabled:opacity-25" type="radio"
                                            id="discount" name="typecombo" value="1" x-model="typecombo" />
                                    </x-input-radio>
                                    <x-input-radio class="py-2" for="free" text="GRATUITO">
                                        <input wire:model.defer="typecombo"
                                            class="sr-only peer peer-disabled:opacity-25" type="radio"
                                            id="free" name="typecombo" value="2" x-model="typecombo" />
                                    </x-input-radio>
                                </div>
                                <x-jet-input-error for="typecombo" />

                                <div class="w-full" x-show="typecombo == '1'">
                                    <x-label value="Descuento (%) :" />
                                    <x-input class="block w-full" wire:model.defer="descuento" type="number"
                                        min="0" step="0.01" />
                                    <x-jet-input-error for="descuento" />
                                </div>
                            </div>

                            <div class="w-full flex justify-between gap-2">
                                <x-button type="submit" wire:loading.attr="disabled">
                                    {{ __('AGREGAR') }}</x-button>
                            </div>

                            @if ($productosec)
                                <div class="">
                                    @if (count($productosec['almacens']) > 0)
                                        <div class="w-full flex flex-wrap gap-2">
                                            <div
                                                class="flex flex-col gap-1 bg-fondominicard text-colorminicard justify-center items-center w-32 h-32 border border-borderminicard rounded-xl shadow shadow-shadowminicard p-1 cursor-pointer hover:shadow-md hover:shadow-shadowminicard @if ($productosec['stock'] < 1) text-red-500 @endif">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="block w-6 h-6 mx-auto"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path
                                                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                                    <polyline points="3.29 7 12 12 20.71 7" />
                                                    <line x1="12" x2="12" y1="22"
                                                        y2="12" />
                                                </svg>

                                                <h1 class="text-[10px] text-center leading-3 font-semibold">
                                                    STOCK TOTAL</h1>

                                                <h1 class="text-xl text-center leading-4 font-semibold">
                                                    {{ floatval($productosec['stock']) }}
                                                    <small class="w-full text-center !text-[10px] font-normal">
                                                        {{ $productosec['unit']['name'] }}</small>
                                                </h1>
                                            </div>

                                            @foreach ($productosec['almacens'] as $item)
                                                <div
                                                    class="flex flex-col gap-1 bg-fondominicard text-colorminicard justify-center items-center w-32 h-32 border border-borderminicard rounded-xl shadow shadow-shadowminicard p-1 cursor-pointer hover:shadow-md hover:shadow-shadowminicard @if ($item['pivot']['cantidad'] < 1) text-red-500 @endif">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="block w-6 h-6 mx-auto" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path
                                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                                        <polyline points="3.29 7 12 12 20.71 7" />
                                                        <line x1="12" x2="12" y1="22"
                                                            y2="12" />
                                                    </svg>

                                                    <h1 class="text-[10px] text-center leading-3 font-semibold">
                                                        {{ $item['name'] }}</h1>

                                                    <h1 class="text-xl text-center leading-4 font-semibold">
                                                        {{ floatval($item['pivot']['cantidad']) }}
                                                        <span class="w-full text-center text-[10px] font-normal">
                                                            {{ $productosec['unit']['name'] }}</span>
                                                    </h1>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="w-full flex justify-end gap-2">
                                <x-button-secondary type="button" wire:loading.attr="disabled" class="inline-block"
                                    wire:click="cancelcombo">{{ __('CANCELAR') }}</x-button-secondary>
                                <x-button type="button" wire:loading.attr="disabled" class="inline-block"
                                    wire:click="save">{{ __('REGISTRAR') }}</x-button>
                            </div>
                        </form>
                    @else
                        <form wire:submit.prevent="confirmar">
                            <div class="w-full grid grid-cols-1 xs:grid-cols-2 xl:grid-cols-3 gap-2">
                                <div class="xs:col-span-2 xl:col-span-3">
                                    <x-label value="Tipo promoción :" />
                                    <div class="w-full flex flex-wrap gap-2">
                                        <x-input-radio class="py-2" for="promodescuento" text="DESCUENTO">
                                            <input wire:model.defer="type"
                                                class="sr-only peer peer-disabled:opacity-25" type="radio"
                                                id="promodescuento" name="type"
                                                value="{{ \App\Models\Promocion::DESCUENTO }}" x-model="type" />
                                        </x-input-radio>
                                        <x-input-radio class="py-2" for="promocombo" text="COMBO DE PRODUCTOS">
                                            <input wire:model.defer="type"
                                                class="sr-only peer peer-disabled:opacity-25" type="radio"
                                                id="promocombo" name="type"
                                                value="{{ \App\Models\Promocion::COMBO }}" x-model="type" />
                                        </x-input-radio>
                                        <x-input-radio class="py-2" for="promoremate" text="LIQUIDACIÓN">
                                            <input wire:model.defer="type"
                                                class="sr-only peer peer-disabled:opacity-25" type="radio"
                                                id="promoremate" name="type"
                                                value="{{ \App\Models\Promocion::REMATE }}" x-model="type" />
                                        </x-input-radio>
                                    </div>
                                    <x-jet-input-error for="type" />

                                    <div class="w-full" x-show="typecombo == '1'">
                                        <x-label value="Descuento (%) :" />
                                        <x-input class="block w-full" wire:model.defer="descuento" type="number"
                                            min="0" step="0.01" />
                                        <x-jet-input-error for="descuento" />
                                    </div>
                                </div>

                                <div class="xs:col-span-2 xl:col-span-3">
                                    <x-label value="Seleccionar producto :" />
                                    <div class="relative" id="parentproducto_id" x-init="select2Producto" wire:ignore>
                                        <x-select class="block w-full" x-ref="select" id="producto_id"
                                            data-minimum-results-for-search="3" data-dropdown-parent="null"
                                            data-placeholder="null">
                                            @if (count($productos) > 0)
                                                <x-slot name="options">
                                                    @foreach ($productos as $item)
                                                        <option data-marca="{{ $item->name_marca }}"
                                                            data-category="{{ $item->name_category }}"
                                                            data-subcategory="{{ $item->name_subcategory }}"
                                                            data-image="{{ !empty($item->image) ? pathURLProductImage($item->image) : null }}"
                                                            value="{{ $item->id }}">
                                                            {{ $item->name }}</option>
                                                    @endforeach
                                                </x-slot>
                                            @endif
                                        </x-select>
                                        <x-icon-select />
                                    </div>
                                    <x-jet-input-error for="producto_id" />
                                </div>

                                <div class="w-full">
                                    <x-label value="Fecha inicio (Opcional) :" />
                                    <x-input class="block w-full" wire:model.defer="startdate" type="date" />
                                    <x-jet-input-error for="startdate" />
                                </div>

                                <div class="w-full">
                                    <x-label value="Fecha finalización (Opcional) :" />
                                    <x-input class="block w-full" wire:model.defer="expiredate" type="date" />
                                    <x-jet-input-error for="expiredate" />
                                </div>

                                <div class="w-full" x-show="type == '0'">
                                    <x-label value="Descuento (%) :" />
                                    <x-input class="block w-full input-number-none" wire:model.defer="descuento"
                                        type="number" min="0" step="0.01"
                                        onkeypress="return validarNumero(event, 5)" />
                                    <x-jet-input-error for="descuento" />
                                </div>

                                <div class="w-full">
                                    <div>
                                        <x-label value="stock Máximo:" />
                                        <x-input x-show="agotarstock == false" class="block w-full"
                                            wire:model.defer="limit" type="number" min="0" step="1"
                                            onkeypress="return validarNumero(event, 9)" />
                                        <x-disabled-text x-show="agotarstock" text="AGOTAR STOCK" />
                                        <x-jet-input-error for="limit" />
                                    </div>
                                    <div class="mt-1">
                                        <x-label-check for="agotarstock">
                                            <x-input wire:model.defer="agotarstock" x-model="agotarstock"
                                                type="checkbox" id="agotarstock" />
                                            HASTA AGOTAR STOCK DISPONIBLE
                                        </x-label-check>
                                        <x-jet-input-error for="agotarstock" />
                                        <x-jet-input-error for="limitstock" />
                                        <x-jet-input-error for="pricebuy" />
                                    </div>
                                </div>

                                <div class="xs:col-span-2 xl:col-span-3">
                                    @if ($producto)
                                        @if (count($producto['almacens']) > 0)
                                            <div class="w-full flex flex-wrap gap-2">
                                                <div
                                                    class="flex flex-col gap-1 bg-fondominicard text-colorminicard justify-center items-center w-32 h-32 border border-borderminicard rounded-xl shadow shadow-shadowminicard p-1 cursor-pointer hover:shadow-md hover:shadow-shadowminicard @if ($producto['stock'] < 1) text-red-500 @endif">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="block w-6 h-6 mx-auto" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path
                                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                                        <polyline points="3.29 7 12 12 20.71 7" />
                                                        <line x1="12" x2="12" y1="22"
                                                            y2="12" />
                                                    </svg>

                                                    <h1 class="text-[10px] text-center leading-3 font-semibold">
                                                        STOCK TOTAL</h1>

                                                    <p class="text-xl text-center leading-4 font-semibold">
                                                        {{ floatval($producto['stock']) }}
                                                        <span class="w-full text-center text-[10px] font-normal">
                                                            {{ $producto['unit']['name'] }}</span>
                                                    </p>
                                                </div>

                                                @foreach ($producto['almacens'] as $item)
                                                    <div
                                                        class="flex flex-col gap-1 bg-fondominicard text-colorminicard justify-center items-center w-32 h-32 border border-borderminicard rounded-xl shadow shadow-shadowminicard p-1 cursor-pointer hover:shadow-md hover:shadow-shadowminicard @if ($item['pivot']['cantidad'] < 1) text-red-500 @endif">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="block w-6 h-6 mx-auto" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path
                                                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                                            <polyline points="3.29 7 12 12 20.71 7" />
                                                            <line x1="12" x2="12" y1="22"
                                                                y2="12" />
                                                        </svg>

                                                        <h1 class="text-[10px] text-center leading-3 font-semibold">
                                                            {{ $item['name'] }}
                                                        </h1>

                                                        <h1 class="text-xl text-center leading-4 font-semibold">
                                                            {{ floatval($item['pivot']['cantidad']) }}
                                                            <span class="w-full text-center text-[10px] font-normal">
                                                                {{ $producto['unit']['name'] }}</span>
                                                        </h1>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <div class="w-full flex justify-end pt-4">
                                <x-button type="submit" wire:click="confirmar" wire:loading.attr="disabled">
                                    {{ __('CONFIRMAR') }}</x-button>
                            </div>
                        </form>
                    @endif
                </div>
            </x-slot>
        </x-jet-dialog-modal>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('loader', () => ({
                    type: @entangle('type').defer,
                    agotarstock: @entangle('agotarstock').defer,
                    producto_id: @entangle('producto_id'),
                    productosec_id: @entangle('productosec_id'),
                    typecombo: @entangle('typecombo').defer,

                    init() {
                        this.$watch('type', value => {
                            if (value == '{{ \App\Models\Promocion::REMATE }}') {
                                this.agotarstock = true;
                            } else {
                                this.agotarstock = false;
                            }
                        });
                    }
                }))
            })

            function select2Producto() {
                this.selectP = $(this.$refs.select).select2({
                    templateResult: function(data) {
                        if (!data.id) {
                            return data.text;
                        }
                        const image = $(data.element).data('image') ?? '';
                        const marca = $(data.element).data('marca') ?? '';
                        const category = $(data.element).data('category') ?? '';
                        const subcategory = $(data.element).data('subcategory') ?? '';

                        let html = `<div class="custom-list-select">
                        <div class="image-custom-select">`;
                        if (image) {
                            html +=
                                `<img src="${image}" class="w-full h-full object-scale-down block" alt="${data.text}">`;
                        } else {
                            html += `<x-icon-image-unknown class="w-full h-full" />`;
                        }
                        html += `</div>
                            <div class="content-custom-select">
                                <p class="title-custom-select">
                                    ${data.text}</p>
                                <p class="marca-custom-select">
                                    ${marca}</p>  
                                <div class="category-custom-select">
                                    <span class="inline-block">${category}</span>
                                    <span class="inline-block">${subcategory}</span>
                                </div>  
                            </div>
                      </div>`;
                        return $(html);
                    }
                });
                this.selectP.val(this.producto_id).trigger("change");
                this.selectP.on("select2:select", (event) => {
                    this.producto_id = event.target.value;
                }).on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
                this.$watch('producto_id', (value) => {
                    this.selectP.val(value).trigger("change");
                });
            }

            function selectProductoSec() {
                this.selectPS = $(this.$refs.selectps).select2({
                    templateResult: function(data) {
                        if (!data.id) {
                            return data.text;
                        }
                        const image = $(data.element).data('image') ?? '';
                        const marca = $(data.element).data('marca') ?? '';
                        const category = $(data.element).data('category') ?? '';
                        const subcategory = $(data.element).data('subcategory') ?? '';

                        let html = `<div class="custom-list-select">
                        <div class="image-custom-select">`;
                        if (image) {
                            html +=
                                `<img src="${image}" class="w-full h-full object-scale-down block" alt="${data.text}">`;
                        } else {
                            html += `<x-icon-image-unknown class="w-full h-full" />`;
                        }
                        html += `</div>
                            <div class="content-custom-select">
                                <p class="title-custom-select">
                                    ${data.text}</p>
                                <p class="marca-custom-select">
                                    ${marca}</p>  
                                <div class="category-custom-select">
                                    <span class="inline-block">${category}</span>
                                    <span class="inline-block">${subcategory}</span>
                                </div>  
                            </div>
                      </div>`;
                        return $(html);
                    }
                });
                this.selectPS.val(this.productosec_id).trigger("change");
                this.selectPS.on("select2:select", (event) => {
                    this.productosec_id = event.target.value;
                }).on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
                this.$watch('productosec_id', (value) => {
                    this.selectPS.val(value).trigger("change");
                });
            }
        </script>
    </div>
@endif
