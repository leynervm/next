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
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <div class="w-full" x-data="loader">

                @if (getCombo()->count() > 0)
                    <div class="w-full flex flex-col gap-2">
                        <div class="w-full grid grid-cols-1 gap-2">
                            <div class="w-full">
                                <div
                                    class="w-full max-w-xs mx-auto h-60 shadow-md shadow-shadowminicard border rounded-lg border-borderminicard overflow-hidden mb-1 duration-300 relative">
                                    @if ($producto)
                                        @if ($producto->images()->exists())
                                            @if ($producto->images()->default()->exists())
                                                {{-- @if ($producto->defaultImage) --}}
                                                <img src="{{ asset('storage/productos/' . $producto->defaultImage->first()->url) }}"
                                                    alt="" class="w-full h-full object-cover">
                                                {{-- @else
                                                    <img src="{{ asset('storage/productos/' . $producto->images->first()->url) }}"
                                                        alt="" class="w-full h-full object-cover">
                                                @endif --}}
                                            @else
                                                <img src="{{ asset('storage/productos/' . $producto->images->first()->url) }}"
                                                    alt="" class="w-full h-full object-cover">
                                            @endif
                                        @endif
                                    @else
                                        <div
                                            class="w-full flex items-center justify-center h-60 shadow-md shadow-shadowminicard border rounded-lg border-borderminicard mb-1">
                                            <svg class="text-neutral-500 w-24 h-24" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
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

                                    @if ($producto)
                                        <div
                                            class="absolute right-1 bottom-1 flex flex-col gap-1 bg-fondospancardproduct text-colorminicard justify-center items-center w-20 h-20 border rounded-xl shadow shadow-shadowminicard p-1 cursor-pointer hover:shadow-md hover:shadow-shadowminicard">
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

                                            <h1 class="text-[8px] text-center leading-3 font-semibold">
                                                {{ $agotarstock ? 'HASTA AGOTAR STOCK' : 'LIMITE STOCK' }}</h1>

                                            <h1 class="text-xl text-center leading-4 font-semibold">
                                                @if ($agotarstock)
                                                @else
                                                    {{ floatval(getCombo()->get('limit')) }}
                                                    {{ getCombo()->get('unit') }}
                                                @endif
                                            </h1>
                                        </div>
                                    @endif
                                </div>

                                @if ($producto)
                                    <h1 class="text-xs leading-3 text-center text-colortitleform mt-3">
                                        {{ $producto->name }}</h1>
                                    <x-jet-input-error for="itempromos" />
                                @endif
                            </div>

                            {{-- <div class="w-full grid grid-cols-2 gap-2">
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
                            </div> --}}

                            <x-title-next titulo="AGREGAR PRODUCTOS SECUNDARIOS" class="mt-5" />

                            @if (count(getCombo()->get('comboitems')) > 0)
                                <div class="w-full flex flex-wrap gap-2">
                                    @foreach (getCombo()->get('comboitems') as $item)
                                        <div
                                            class="w-full shadow shadow-shadowminicard rounded-md hover:shadow-md hover:shadow-shadowminicard p-1 bg-body max-w-xs">
                                            <h1 class="text-xs text-colortitleform">{{ $item->name }}</h1>
                                            <p class="text-[10px] text-colorsubtitleform">{{ $item->category }}</p>
                                            <div class="w-full flex items-end justify-between gap-2 mt-1">

                                                @if ($item->typecombo == '0')
                                                    <x-span-text text="SIN DESCUENTO" type="orange"
                                                        class="leading-3 !tracking-normal" />
                                                @elseif ($item->typecombo == '1')
                                                    <x-span-text :text="formatDecimalOrInteger($item->descuento) . '% DSCT'" type="green"
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
                        </div>
                    </div>

                    <form wire:submit.prevent="add" class="w-full mt-6 flex flex-col gap-2">
                        <div class="">
                            <x-label value="Producto secundario :" />
                            <div class="relative" id="parentproductosec_id" x-init="selectProductoSec" wire:ignore>
                                <x-select class="block w-full" x-ref="selectps" id="productosec_id"
                                    data-dropdown-parent="null" data-minimum-results-for-search="3"
                                    data-placeholder="null">
                                    @if (count($productos))
                                        <x-slot name="options">
                                            @foreach ($productos as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
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
                                    <input wire:model.defer="typecombo" class="sr-only peer peer-disabled:opacity-25"
                                        type="radio" id="nodiscount" name="typecombo" value="0"
                                        x-model="typecombo" />
                                </x-input-radio>
                                <x-input-radio class="py-2" for="discount" text="DESCUENTO">
                                    <input wire:model.defer="typecombo" class="sr-only peer peer-disabled:opacity-25"
                                        type="radio" id="discount" name="typecombo" value="1"
                                        x-model="typecombo" />
                                </x-input-radio>
                                <x-input-radio class="py-2" for="free" text="GRATUITO">
                                    <input wire:model.defer="typecombo" class="sr-only peer peer-disabled:opacity-25"
                                        type="radio" id="free" name="typecombo" value="2"
                                        x-model="typecombo" />
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
                                @if (count($productosec->almacens) > 0)
                                    <div class="w-full flex flex-wrap gap-2">

                                        @php
                                            $sumstock = $productosec->almacens()->sum('cantidad');
                                        @endphp

                                        <div
                                            class="flex flex-col gap-1 bg-fondominicard text-colorminicard justify-center items-center w-32 h-32 border border-borderminicard rounded-xl shadow shadow-shadowminicard p-1 cursor-pointer hover:shadow-md hover:shadow-shadowminicard @if ($sumstock < 1) text-red-500 @endif">
                                            <span class="block w-6 h-6 mx-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path
                                                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                                    <polyline points="3.29 7 12 12 20.71 7" />
                                                    <line x1="12" x2="12" y1="22"
                                                        y2="12" />
                                                </svg>
                                            </span>

                                            <h1 class="text-[10px] text-center leading-3 font-semibold">STOCK TOTAL
                                            </h1>

                                            <h1 class="text-xl text-center leading-4 font-semibold">
                                                {{ floatval($sumstock) }}
                                                <span class="w-full text-center text-[10px] font-normal">
                                                    {{ $productosec->unit->name }}</span>
                                            </h1>
                                        </div>

                                        @foreach ($productosec->almacens as $item)
                                            <div
                                                class="flex flex-col gap-1 bg-fondominicard text-colorminicard justify-center items-center w-32 h-32 border border-borderminicard rounded-xl shadow shadow-shadowminicard p-1 cursor-pointer hover:shadow-md hover:shadow-shadowminicard @if ($item->pivot->cantidad < 1) text-red-500 @endif">
                                                <span class="block w-6 h-6 mx-auto">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path
                                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                                        <polyline points="3.29 7 12 12 20.71 7" />
                                                        <line x1="12" x2="12" y1="22"
                                                            y2="12" />
                                                    </svg>
                                                </span>

                                                <h1 class="text-[10px] text-center leading-3 font-semibold">
                                                    {{ $item->name }}
                                                </h1>

                                                <h1 class="text-xl text-center leading-4 font-semibold">
                                                    {{ floatval($item->pivot->cantidad) }}
                                                    <span class="w-full text-center text-[10px] font-normal">
                                                        {{ $productosec->unit->name }}</span>
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
                                    <x-input-radio class="py-2" for="promodescuento" text="DESCUENTO EN PRODUCTO">
                                        <input wire:model.defer="type" class="sr-only peer peer-disabled:opacity-25"
                                            type="radio" id="promodescuento" name="type" value="0"
                                            x-model="type" @change="changeType($event.target.value)" />
                                    </x-input-radio>
                                    <x-input-radio class="py-2" for="promocombo" text="COMBO DE PRODUCTOS">
                                        <input wire:model.defer="type" class="sr-only peer peer-disabled:opacity-25"
                                            type="radio" id="promocombo" name="type" value="1"
                                            x-model="type" @change="changeType($event.target.value)" />
                                    </x-input-radio>
                                    <x-input-radio class="py-2" for="promoremate" text="REMATE DE PRODUCTO">
                                        <input wire:model.defer="type" class="sr-only peer peer-disabled:opacity-25"
                                            type="radio" id="promoremate" name="type" value="2"
                                            x-model="type" @change="changeType($event.target.value)" />
                                    </x-input-radio>
                                </div>
                                <x-jet-input-error for="type" />
                                <span x-text="type"></span>

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
                                        @if (count($productos))
                                            <x-slot name="options">
                                                @foreach ($productos as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
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
                                <x-input class="block w-full" wire:model.defer="descuento" type="number"
                                    min="0" step="0.01" />
                                <x-jet-input-error for="descuento" />
                            </div>

                            <div class="w-full">
                                <div>
                                    <x-label value="stock Máximo:" />
                                    <x-input x-show="agotarstock == false" class="block w-full"
                                        wire:model.defer="limit" type="number" min="0" step="1" />
                                    <x-disabled-text x-show="agotarstock" text="AGOTAR STOCK" />
                                    <x-jet-input-error for="limit" />
                                </div>
                                <div class="mt-1">
                                    <x-label-check for="agotarstock">
                                        <x-input wire:model.defer="agotarstock" x-model="agotarstock" type="checkbox"
                                            id="agotarstock" />
                                        HASTA AGOTAR STOCK DISPONIBLE
                                    </x-label-check>
                                    <x-jet-input-error for="agotarstock" />
                                    <x-jet-input-error for="limitstock" />
                                </div>
                            </div>

                            <div class="xs:col-span-2 xl:col-span-3">
                                @if ($producto)
                                    @if (count($producto->almacens) > 0)
                                        <div class="w-full flex flex-wrap gap-2">

                                            @php
                                                $sumstock = $producto->almacens()->sum('cantidad');
                                            @endphp

                                            <div
                                                class="flex flex-col gap-1 bg-fondominicard text-colorminicard justify-center items-center w-32 h-32 border border-borderminicard rounded-xl shadow shadow-shadowminicard p-1 cursor-pointer hover:shadow-md hover:shadow-shadowminicard @if ($sumstock < 1) text-red-500 @endif">
                                                <span class="block w-6 h-6 mx-auto">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path
                                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                                        <polyline points="3.29 7 12 12 20.71 7" />
                                                        <line x1="12" x2="12" y1="22"
                                                            y2="12" />
                                                    </svg>
                                                </span>

                                                <h1 class="text-[10px] text-center leading-3 font-semibold">STOCK TOTAL
                                                </h1>

                                                <h1 class="text-xl text-center leading-4 font-semibold">
                                                    {{ floatval($sumstock) }}
                                                    <span class="w-full text-center text-[10px] font-normal">
                                                        {{ $producto->unit->name }}</span>
                                                </h1>
                                            </div>

                                            @foreach ($producto->almacens as $item)
                                                <div
                                                    class="flex flex-col gap-1 bg-fondominicard text-colorminicard justify-center items-center w-32 h-32 border border-borderminicard rounded-xl shadow shadow-shadowminicard p-1 cursor-pointer hover:shadow-md hover:shadow-shadowminicard @if ($item->pivot->cantidad < 1) text-red-500 @endif">
                                                    <span class="block w-6 h-6 mx-auto">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path
                                                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                                            <polyline points="3.29 7 12 12 20.71 7" />
                                                            <line x1="12" x2="12" y1="22"
                                                                y2="12" />
                                                        </svg>
                                                    </span>

                                                    <h1 class="text-[10px] text-center leading-3 font-semibold">
                                                        {{ $item->name }}
                                                    </h1>

                                                    <h1 class="text-xl text-center leading-4 font-semibold">
                                                        {{ floatval($item->pivot->cantidad) }}
                                                        <span class="w-full text-center text-[10px] font-normal">
                                                            {{ $producto->unit->name }}</span>
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
                                {{ __('CONFIRMAR') }}
                            </x-button>
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

                changeType(value) {
                    this.agotarstock = 0;
                    if (value == '2') {
                        this.agotarstock = true;
                    }
                }
            }))
        })

        function select2Producto() {
            this.selectP = $(this.$refs.select).select2();
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
            this.selectPS = $(this.$refs.selectps).select2();
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
