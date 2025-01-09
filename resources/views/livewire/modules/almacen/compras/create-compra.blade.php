<div class="w-full flex flex-col gap-3 lg:gap-5" x-data="data">
    <div wire:loading.flex class="fixed loading-overlay hidden">
        <x-loading-next />
    </div>

    <x-form-card titulo="DATOS DE LA COMPRA">
        <form wire:submit.prevent="save" class="w-full flex flex-col gap-2">
            <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                <div class="w-full">
                    <x-label value="Fecha compra :" />
                    <x-input type="date" class="block w-full" wire:model.defer="date" />
                    <x-jet-input-error for="date" />
                </div>

                <div class="w-full">
                    <x-label value="Tipo afectación :" />
                    <div id="parentafectacion_id" class="relative" x-init="afectacionIGV">
                        <x-select class="block w-full" x-ref="afectacion" id="afectacion_id" data-placeholder="null">
                            <x-slot name="options">
                                <option value="E">EXONERAR IGV</option>
                                <option value="S">IGV 18%</option>
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="afectacion" />
                </div>

                <div class="w-full">
                    <x-label value="Moneda :" />
                    <div id="parentmonedacompra_id" class="relative" x-init="selec2Moneda" wire:ignore>
                        <x-select class="block w-full" x-ref="selectmoneda" id="monedacompra_id">
                            <x-slot name="options">
                                @if (count($monedas) > 0)
                                    @foreach ($monedas as $item)
                                        <option value="{{ $item->id }}" data-code="{{ $item->code }}"
                                            data-simbolo="{{ $item->simbolo }}" data-currency="{{ $item->currency }}">
                                            {{ $item->currency }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="moneda_id" />
                </div>

                <div class="w-full" x-show="open" x-transition>
                    <x-label value="Tipo Cambio :" />
                    <x-input class="block w-full" x-model="tipocambio" placeholder="0.00"
                        x-mask:dynamic="$money($input, '.', '', 3)" @input="calcularsoles"
                        onkeypress="return validarDecimal(event, 7)" />
                    <x-jet-input-error for="tipocambio" />
                </div>

                <div class="w-full">
                    <x-label value="Boleta/ Factura compra :" />
                    <x-input class="block w-full" wire:model.defer="referencia"
                        placeholder="Boleta o factura de compra..." />
                    <x-jet-input-error for="referencia" />
                </div>

                <div class="w-full">
                    <x-label value="Guía de compra :" />
                    <x-input class="block w-full" wire:model.defer="guia" placeholder="Guia de compra..." />
                    <x-jet-input-error for="guia" />
                </div>

                <div class="w-full">
                    <x-label value="Tipo pago :" />
                    <div id="parenttypepaymentcompra_id" class="relative" x-init="select2Typepayment">
                        <x-select class="block w-full" x-ref="selecttp" id="typepaymentcompra_id"
                            data-placeholder="null">
                            <x-slot name="options">
                                @if (count($typepayments))
                                    @foreach ($typepayments as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="typepayment_id" />
                </div>

                <div class="w-full xs:col-span-2">
                    <x-label value="Proveedor :" />
                    <div id="parentproveedorcompra_id" class="relative" x-init="selec2Proveedor">
                        <x-select class="block w-full" x-ref="selectproveedor" id="proveedorcompra_id"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @if (count($proveedores))
                                    @foreach ($proveedores as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->document }} - {{ $item->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="proveedor_id" />
                </div>

                <div class="w-full xs:col-span-2">
                    <x-label value="Sucursal :" />
                    <div id="parentsucursalcompra_id" class="relative" x-init="selec2Sucursal" wire:ignore>
                        <x-select class="block w-full" x-ref="selectsucursal" id="sucursalcompra_id"
                            data-minimum-results-for-search="3">
                            <x-slot name="options">
                                @if (count($sucursals) > 0)
                                    @foreach ($sucursals as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="sucursal_id" />
                </div>

                <div class="w-full xs:col-span-2">
                    <x-label value="Observaciones / Detalles :" />
                    <x-text-area class="block w-full" rows="1" wire:model.defer="detalle"></x-text-area>
                    <x-jet-input-error for="detalle" />
                </div>
            </div>

            <div class="w-full flex pt-4 justify-end">
                <x-button type="submit" wire:loading.attr="disabled">
                    {{ __('Save') }}</x-button>
            </div>
        </form>
    </x-form-card>

    <x-form-card titulo="AGREGAR PRODUCTOS">
        <form class="w-full flex flex-col gap-2" @submit.prevent="addproducto">
            <div class="flex w-full flex-col gap-1">
                <x-label value="Seleccionar producto :" />
                <div class="w-full relative" x-init="select2Producto" id="parentproducto_id">
                    <x-select class="block w-full uppercase" x-ref="selectprod" data-minimum-results-for-search="3"
                        id="producto_id">
                        <x-slot name="options">
                            @if (count($productos) > 0)
                                @foreach ($productos as $item)
                                    <option data-marca="{{ $item->name_marca }}"
                                        data-category="{{ $item->name_category }}"
                                        data-subcategory="{{ $item->name_subcategory }}"
                                        data-requireserie="{{ $item->isRequiredserie() }}"
                                        data-image="{{ !empty($item->image) ? pathURLProductImage($item->image) : null }}"
                                        value="{{ $item->id }}">
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-icon-select />
                </div>
                <x-jet-input-error for="producto_id" />
            </div>

            <div class="w-full grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                <div class="w-full">
                    <x-label value="Precio unitario sin IGV :" />
                    <x-input class="block w-full" x-mask:dynamic="$money($input, '.', '', 2)" x-model="priceunitario"
                        @input="calcular" placeholder="0.00" onkeypress="return validarDecimal(event, 12)" />
                    <x-jet-input-error for="priceunitario" />
                </div>
                <div class="w-full" x-cloak x-show="afectacion == 'S'" style="display: none;">
                    <x-label value="IGV :" />
                    <x-input class="block w-full" x-mask:dynamic="$money($input, '.', '', 2)" x-model="igvunitario"
                        @input="calcular" placeholder="0.00" onkeypress="return validarDecimal(event, 12)" />
                    <x-jet-input-error for="igvunitario" />
                </div>

                <div class="w-full">
                    <x-label value="Aplicar descuento :" />
                    <div id="parenttypedscto" class="relative" x-init="select2Typedescto">
                        <x-select class="block w-full" x-ref="selectypedscto" id="typedscto">
                            <x-slot name="options">
                                <option value="0">NO APLICAR DSCTO</option>
                                <option value="1">PRECIO UNITARIO CON DESCUENTO APLICADO</option>
                                <option value="2">PRECIO UNITARIO SIN APLICAR DESCUENTO</option>
                                <option value="3">APLICAR DSCTO AL TOTAL DEL ITEM</option>
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="typedscto" />
                </div>

                <div class="w-full" style="display: none" x-cloak x-show="typedescuento > 0" x-transition>
                    <x-label value="Descuento:" />
                    <x-input class="block w-full" x-model="descuentounitario"
                        x-mask:dynamic="$money($input, '.', '', 2)" @input="calcular" placeholder="0.00"
                        onkeypress="return validarDecimal(event, 12)" />
                    <x-jet-input-error for="typedescuento" />
                </div>

                @if (!$empresa->usarLista())
                    <div class="w-full">
                        <x-label value="Precio venta unitario (SOLES):" />
                        <x-input class="block w-full" x-model="priceventa" placeholder="0.00"
                            x-mask:dynamic="$money($input, '.', '', 2)"
                            onkeypress="return validarDecimal(event, 12)" />
                        <x-jet-input-error for="priceventa" />
                    </div>
                @endif
            </div>

            @if (count($almacens) > 0)
                <div class="w-full flex flex-wrap gap-2" wire:target="producto_id" wire:loading.remove>
                    @foreach ($almacens as $key => $item)
                        <x-simple-card wire:key="{{ $key }}" wire:loading.class="opacity-25"
                            class="w-full xs:w-52 rounded-lg p-2 flex flex-col gap-3 justify-start">
                            <h1 class="text-colortitleform text-[10px] text-center font-semibold">
                                {{ $item['name'] }}</h1>

                            <div class="text-colorsubtitleform text-center">
                                <small class="w-full block text-center text-[8px] leading-3">
                                    STOCK ACTUAL</small>
                                <span class="inline-block text-2xl text-center font-semibold">
                                    {{ $item['stock_actual'] }}</span>
                                <small class="inline-block text-center text-[10px] leading-3">
                                    {{ $item['unit'] }}</small>
                            </div>

                            <div class="w-full">
                                <x-label value="STOCK ENTRANTE :" class="!text-[10px]" />
                                <x-input class="block w-full"
                                    wire:model.debounce.500ms="almacens.{{ $key }}.cantidad"
                                    x-mask:dynamic="$money($input, '.', '', 0)" placeholder="0"
                                    onkeypress="return validarDecimal(event, 9)"
                                    wire:key="cantidad_{{ $item['id'] }}" wire:loading.attr="disabled" />
                                <x-jet-input-error for="almacens.{{ $key }}.cantidad" />
                            </div>

                            <div x-cloak x-show="requireserie" style="display: none;" x-transition>

                                <x-input class="block w-full"
                                    wire:keydown.enter.prevent="addserie('{{ $key }}')"
                                    onkeypress="return validarSerie(event)" placeholder="Ingresar serie..."
                                    wire:model.defer="almacens.{{ $key }}.newserie" />
                                <x-jet-input-error for="almacens.{{ $key }}.newserie" />

                                <div
                                    class="w-full flex gap-1 justify-between items-center font-medium text-colorlabel">
                                    <span class="text-[9px]">ENTER PARA AGREGAR SERIE</span>

                                    <p class="text-end text-[9px] font-semibold">
                                        <span>{{ count($item['series']) + 1 }}</span>
                                        <span> / {{ $almacens[$key]['cantidad'] }}</span>
                                    </p>
                                </div>

                                @if (count($item['series']) > 0)
                                    <ul class="w-full flex flex-wrap gap-1 items-start mt-2">
                                        @foreach ($item['series'] as $k => $ser)
                                            <li>
                                                <div
                                                    class="rounded-lg p-1 bg-fondospancardproduct text-textspancardproduct flex gap-1 items-center">
                                                    <small
                                                        class="text-[10px] leading-3 tracking-wider">{{ $ser }}</small>
                                                    <x-button-delete
                                                        wire:click="removeserie({{ $key }}, {{ $k }})"
                                                        wire:loading.attr="disabled" />
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </x-simple-card>
                    @endforeach
                </div>
            @endif

            <div class="w-full">
                <x-jet-input-error for="sumstock" />
                <x-jet-input-error for="almacens" />
                <x-jet-input-error for="almacens.*.series" />
            </div>

            <div class="flex flex-col flex-col-reverse xs:flex-row items-start gap-2">
                <div class="flex gap-2">
                    <x-button type="button" wire:loading.attr="disabled" wire:loading.remove @click="clearproducto">
                        {{ __('Limpiar') }}</x-button>
                    <x-button type="submit" wire:loading.attr="disabled" wire:loading.remove>
                        {{ __('Agregar') }}</x-button>
                </div>

                <div class="w-full flex-1" x-cloak x-show="priceunitario > 0" style="display: none;">
                    <table class="w-full table text-xs text-colorsubtitleform">
                        <tr>
                            <td class="align-middle text-end">
                                <small>PRECIO UNIT. COMPRA : </small>
                            </td>
                            <td class="text-end w-40">
                                <span x-text="pricebuy" class="text-sm font-medium"></span>
                                <small x-text="namemoneda"></small>
                            </td>
                        </tr>
                        {{-- <tr>
                            <td class="align-middle text-end">
                                <small>SUBTOTAL IGV : </small>
                            </td>
                            <td class="text-end">
                                <span x-text="subtotaligvitem" class="text-sm font-semibold"></span>
                                <small x-text="namemoneda"></small>
                            </td>
                        </tr> --}}
                        <tr>
                            <td class="align-middle text-end">
                                <small>SUBTOTAL : </small>
                            </td>
                            <td class="text-end">
                                <span x-text="subtotalitem" class="text-sm font-medium"></span>
                                <small x-text="namemoneda"></small>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-middle text-end">
                                <small>DESCUENTOS : </small>
                            </td>
                            <td class="text-end">
                                <span x-text="subtotaldsctoitem" class="text-sm font-medium"></span>
                                <small x-text="namemoneda"></small>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-middle text-end">
                                <small>TOTAL : </small>
                            </td>
                            <td class="text-end">
                                <span x-text="totalitem" class="text-sm font-medium"></span>
                                <small x-text="namemoneda"></small>
                            </td>
                        </tr>

                        <template x-if="pricebuysoles>0">
                            <tr class="text-colorlabel">
                                <td class="align-top text-end">
                                    <small>PRECIO UNIT. COMPRA : </small>
                                </td>
                                <td class="text-end">
                                    <span x-text="pricebuysoles" class="text-sm font-semibold"></span>
                                    <small>SOLES <br> (INCL. IGV)</small>
                                </td>
                            </tr>
                        </template>
                    </table>
                </div>
            </div>
        </form>
    </x-form-card>

    @if (count($itemcompras) > 0)
        <div
            class="w-full grid grid-cols-[repeat(auto-fill,minmax(160px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-1 mt-4">
            @foreach ($itemcompras as $key => $item)
                <x-card-producto :image="!empty($item['image']) ? pathURLProductImage($item['image']) : null" :name="$item['name']" id="cardprodbuy_{{ $key }}"
                    x-data="{ showForm: false }">
                    <div
                        class="w-full p-1 rounded-xl shadow-md shadow-shadowminicard border border-borderminicard my-2">
                        @foreach ($item['almacens'] as $key => $value)
                            <div
                                class="text-lg font-semibold mt-1 text-colorlabel text-center leading-4  @if (!$loop->first) pt-2 border-t border-borderminicard @endif">
                                {{ decimalOrInteger($value['cantidad']) }}
                                <small class="text-[10px] font-medium">{{ $item['unit'] }} \
                                    {{ $value['name'] }}</small>
                            </div>
                            @if (count($value['series']) > 0)
                                <div class="w-full flex flex-wrap gap-1 items-start">
                                    @foreach ($value['series'] as $ser)
                                        <x-span-text :text="$ser" />
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <table class="w-full table text-[10px] text-colorsubtitleform">
                        {{-- <tr>
                        <td colspan="3" class="text-center text-colorlabel">
                            TOTAL
                            <span class="font-semibold text-xl">
                                {{ decimalOrInteger($item['totalitem'], 2, ', ') }}</span>
                            <small x-text="namemoneda"></small>
                        </td>
                    </tr> --}}

                        <tr>
                            <td class="align-middle">P. U. C. </td>
                            <td class="text-end">
                                <span
                                    class="text-sm font-medium">{{ decimalOrInteger($item['pricebuy'], 2, ', ') }}</span>
                                <small x-text="namemoneda"></small>
                            </td>
                        </tr>
                        {{-- @if ($item['subtotaldsctoitem'] > 0) --}}
                        <tr>
                            <td class="align-middle">SUBTOTAL</td>
                            <td class="text-end">
                                <span
                                    class="text-sm font-medium">{{ decimalOrInteger($item['subtotalitem'], 2, ', ') }}</span>
                                <small x-text="namemoneda"></small>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-middle">DESCUENTOS</td>
                            <td class="text-end">
                                <span
                                    class="text-sm font-medium">{{ decimalOrInteger($item['subtotaldsctoitem'], 2, ', ') }}</span>
                                <small x-text="namemoneda"></small>
                            </td>
                        </tr>
                        {{-- @endif --}}

                        <tr>
                            <td class="align-middle">TOTAL </td>
                            <td class="text-end">
                                <span
                                    class="text-sm font-medium">{{ decimalOrInteger($item['totalitem'], 2, ', ') }}</span>
                                <small x-text="namemoneda"></small>
                            </td>
                        </tr>

                        <template x-if="codemoneda == 'USD'">
                            <tr class="text-colorlabel">
                                <td class="align-middle">P. U. C.</td>
                                <td class="text-end">
                                    <span
                                        class="text-sm font-semibold">{{ decimalOrInteger($item['pricebuysoles'], 2, ', ') }}</span>
                                    <small>SOLES</small>
                                </td>
                            </tr>
                        </template>


                        @if (!$empresa->usarLista())
                            <tr class="text-colorlabel">
                                <td class="align-middle"> P. U. V. </td>
                                <td class="text-end">
                                    <span
                                        class="text-sm font-semibold">{{ decimalOrInteger($item['priceventa'], 2, ', ') }}</span>
                                    SOLES
                                </td>
                            </tr>
                            {{-- <tr>
                            <td colspan="3" class=" text-center text-colorlabel">
                                VENTA S/.
                                <span class="font-semibold text-xl">
                                    {{ decimalOrInteger($item['priceventa'], 2, ', ') }}</span>
                                SOLES
                            </td>
                        </tr> --}}
                        @endif
                    </table>

                    <x-slot name="footer">
                        <x-button-edit wire:key="edit_{{ $item['producto_id'] }}"
                            wire:click="edit('{{ $item['producto_id'] }}')" wire:loading.attr="disabled" />
                        <x-button-delete wire:key="delete_{{ $item['producto_id'] }}"
                            wire:click="removeitem('{{ $item['producto_id'] }}')" wire:loading.attr="disabled" />
                    </x-slot>
                </x-card-producto>
            @endforeach
        </div>

        <table class="w-full table text-xs text-colorsubtitleform">
            <tr>
                <td class="align-middle text-end">
                    <small>EXONERADO : </small>
                </td>
                <td class="text-end w-40">
                    <span
                        x-text="(exonerado).toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"
                        class="text-sm font-semibold"></span>
                    <small x-text="namemoneda"></small>
                </td>
            </tr>
            <tr>
                <td class="align-middle text-end">
                    <small>GRAVADO : </small>
                </td>
                <td class="text-end">
                    <span
                        x-text="(gravado).toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"
                        class="text-sm font-semibold"></span>
                    <small x-text="namemoneda"></small>
                </td>
            </tr>
            <tr>
                <td class="align-middle text-end">
                    <small>IGV : </small>
                </td>
                <td class="text-end">
                    <span
                        x-text="(igv).toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"
                        class="text-sm font-semibold"></span>
                    <small x-text="namemoneda"></small>
                </td>
            </tr>
            <tr>
                <td class="align-middle text-end">
                    <small>SUBTOTAL : </small>
                </td>
                <td class="text-end">
                    <span
                        x-text="(subtotal).toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"
                        class="text-sm font-semibold"></span>
                    <small x-text="namemoneda"></small>
                </td>
            </tr>
            <tr>
                <td class="align-middle text-end">
                    <small>DESCUENTOS : </small>
                </td>
                <td class="text-end">
                    <span
                        x-text="(descuento).toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"
                        class="text-sm font-semibold"></span>
                    <small x-text="namemoneda"></small>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-sm text-colorlabel lg:text-3xl font-semibold text-end w-24">
                    <span
                        x-text="simbolo + ' '+ (total).toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span>
                </td>
            </tr>
        </table>
    @else
        <p class="font-semibold text-xs text-colorerror">
            Compra no contiene productos agregados</p>
    @endif

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('data', () => ({
                open: false,
                simbolo: '',
                codemoneda: '',
                namemoneda: '',
                afectacion: @entangle('afectacion').defer,
                moneda_id: @entangle('moneda_id').defer,
                proveedor_id: @entangle('proveedor_id').defer,
                sucursal_id: @entangle('sucursal_id').defer,
                typepayment_id: @entangle('typepayment_id').defer,
                exonerado: @entangle('exonerado').defer,
                gravado: @entangle('gravado').defer,
                igv: @entangle('igv').defer,
                descuento: @entangle('descuento').defer,
                otros: @entangle('otros').defer,
                subtotal: @entangle('subtotal').defer,
                total: @entangle('total').defer,

                almacens: @entangle('almacens').defer,
                producto_id: @entangle('producto_id'),
                sumstock: @entangle('sumstock').defer,
                requireserie: @entangle('requireserie').defer,
                typedescuento: @entangle('typedescuento').defer,

                priceunitario: @entangle('priceunitario').defer,
                igvunitario: @entangle('igvunitario').defer,
                subtotaligvitem: @entangle('subtotaligvitem').defer,
                descuentounitario: @entangle('descuentounitario').defer,
                pricebuy: @entangle('pricebuy').defer,
                subtotalitem: @entangle('subtotalitem').defer,
                subtotaldsctoitem: @entangle('subtotaldsctoitem').defer,
                priceventa: @entangle('priceventa').defer,
                pricebuysoles: null,
                tipocambio: @entangle('tipocambio').defer,
                totalitem: @entangle('totalitem').defer,

                init() {
                    this.$watch("almacens", (value) => {
                        const almacens = Object.values(value);
                        if (almacens.length > 0) {
                            const cantidad = almacens.reduce((sum, item) =>
                                parseFloat(sum) + Number(item.cantidad), 0);
                            this.sumstock = cantidad;
                        } else {
                            this.sumstock = 0;
                        }
                        this.calcular()
                    });
                },
                numeric() {
                    this.exonerado = toDecimal(this.exonerado > 0 ? this.exonerado : 0);
                    this.gravado = toDecimal(this.gravado > 0 ? this.gravado : 0);
                    this.igv = toDecimal(this.igv > 0 ? this.igv : 0);
                    this.otros = toDecimal(this.otros > 0 ? this.otros : 0);
                    this.descuento = toDecimal(this.descuento > 0 ? this.descuento : 0);
                },
                sumar() {
                    let total = '0.00';

                    let exonerado = this.exonerado > 0 ? toDecimal(this.exonerado) : 0;
                    let gravado = this.gravado > 0 ? toDecimal(this.gravado) : 0;
                    let igv = this.igv > 0 ? toDecimal(this.igv) : 0;
                    let otros = this.otros > 0 ? toDecimal(this.otros) : 0;
                    let descuento = this.descuento > 0 ? toDecimal(this.descuento) : 0;

                    total = parseFloat(exonerado) + parseFloat(gravado) + parseFloat(igv) + parseFloat(
                        otros);
                    this.subtotal = toDecimal(parseFloat(total) + parseFloat(this.descuento));
                    this.total = toDecimal(total);
                },
                calcular() {
                    if (this.afectacion == 'S') {
                        this.igvunitario = toDecimal(parseFloat(this.priceunitario * 18) / 100, 2)
                    } else {
                        this.igvunitario = 0;
                    }

                    this.pricebuy = toDecimal(parseFloat(this.priceunitario) + parseFloat(this
                        .igvunitario), 2);

                    if (this.typedescuento > 0 && this.sumstock > 0) {
                        if (this.typedescuento == '1') {
                            this.subtotaldsctoitem = toDecimal(parseFloat(this.descuentounitario) *
                                parseFloat(this.sumstock), 2);
                        } else if (this.typedescuento == '2') {
                            this.pricebuy = toDecimal((parseFloat(this.priceunitario) - parseFloat(this
                                .descuentounitario)) + parseFloat(this.igvunitario), 2);
                            this.subtotaldsctoitem = toDecimal(parseFloat(this.descuentounitario) *
                                parseFloat(this.sumstock), 2);
                        } else if (this.typedescuento == '3') {
                            this.subtotaldsctoitem = this.descuentounitario;
                        }
                    }


                    this.totalitem = toDecimal(parseFloat(this.pricebuy) * parseFloat(this.sumstock),
                        2);
                    this.subtotaligvitem = toDecimal(parseFloat(this.igvunitario) * parseFloat(this
                        .sumstock), 2);
                    this.subtotalitem = toDecimal(parseFloat(this.subtotaldsctoitem) + parseFloat(this
                        .totalitem), 2);
                    this.calcularsoles();
                },
                calcularsoles() {
                    // console.log('CODE MONEDA :', this.codemoneda);
                    if (this.codemoneda == 'USD') {
                        this.pricebuysoles = toDecimal(parseFloat(this.pricebuy) * parseFloat(this
                            .tipocambio), 2)
                    } else {
                        this.pricebuysoles = null
                    }
                },
                addproducto() {
                    this.$wire.call('addproducto').then(result => console.log(result));
                },
                clearproducto() {
                    this.search = ''
                    this.product = null
                    this.almacens = []
                    this.sumstock = 0
                    this.producto_id = null

                    this.unit = ''
                    this.sumstock = 0
                    this.requireserie = false
                    this.typedescuento = '0'
                    this.priceunitario = 0
                    this.igvunitario = 0
                    this.subtotaligvitem = 0
                    this.descuentounitario = 0
                    this.pricebuy = 0
                    this.subtotalitem = 0
                    this.subtotaldsctoitem = 0
                    this.priceventa = 0
                    this.pricebuysoles = null
                    this.totalitem = 0
                    this.$wire.$refresh()
                }
            }))
        })

        function select2Producto() {
            this.selectP = $(this.$refs.selectprod).select2({
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
                const selected = event.target.options[event.target.selectedIndex];
                if (selected.dataset) {
                    this.requireserie = selected.dataset.requireserie;
                } else {
                    this.requireserie = false;
                }
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("producto_id", (value) => {
                this.selectP.val(value).trigger("change");
                if (value == null) {
                    this.selectP.empty();
                }
            });
            Livewire.hook('message.processed', () => {
                this.selectP.select2('destroy');
                this.selectP.select2({
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
                }).val(this.producto_id).trigger('change');
            });
        }

        function selec2Proveedor() {
            this.selectProvC = $(this.$refs.selectproveedor).select2();
            this.selectProvC.val(this.proveedor_id).trigger("change");
            this.selectProvC.on("select2:select", (event) => {
                this.proveedor_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("proveedor_id", (value) => {
                this.selectProvC.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectProvC.select2('destroy');
                this.selectProvC.select2().val(this.proveedor_id).trigger('change');
            });
        }

        function selec2Sucursal() {
            this.selectS = $(this.$refs.selectsucursal).select2();
            this.selectS.val(this.sucursal_id).trigger("change");
            this.selectS.on("select2:select", (event) => {
                this.sucursal_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("sucursal_id", (value) => {
                this.selectS.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectS.select2('destroy');
                this.selectS.select2().val(this.sucursal_id).trigger('change');
            });
        }

        function selec2Moneda() {
            this.selectM = $(this.$refs.selectmoneda).select2();
            this.selectM.val(this.moneda_id).trigger("change");
            this.selectM.on("select2:select", (event) => {
                this.moneda_id = event.target.value;
                let codemoneda = event.target.options[event.target.selectedIndex].getAttribute('data-code');
                let datasimbolo = event.target.options[event.target.selectedIndex].getAttribute(
                    'data-simbolo');
                let namemoneda = event.target.options[event.target.selectedIndex].getAttribute(
                    'data-currency');
                this.open = codemoneda == 'USD' ? true : false;
                this.simbolo = datasimbolo;
                this.codemoneda = codemoneda;
                this.namemoneda = namemoneda;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("moneda_id", (value) => {
                this.selectM.val(value).trigger("change");
            });
        }

        function select2Typedescto() {
            this.selectTD = $(this.$refs.selectypedscto).select2();
            this.selectTD.val(this.typedescuento).trigger("change");
            this.selectTD.on("select2:select", (event) => {
                this.typedescuento = event.target.value;
                this.calcular();
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("typedescuento", (value) => {
                this.selectTD.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectTD.select2().val(this.typedescuento).trigger('change');
            });
        }

        function select2Typepayment() {
            this.selectT = $(this.$refs.selecttp).select2();
            this.selectT.val(this.typepayment_id).trigger("change");
            this.selectT.on("select2:select", (event) => {
                this.typepayment_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("typepayment_id", (value) => {
                this.selectT.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectT.select2().val(this.typepayment_id).trigger('change');
            });
        }

        function afectacionIGV() {
            this.selectAF = $(this.$refs.afectacion).select2();
            this.selectAF.val(this.afectacion).trigger("change");
            this.selectAF.on("select2:select", (event) => {
                this.afectacion = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("afectacion", (value) => {
                this.calcular()
                this.selectAF.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectAF.select2().val(this.afectacion).trigger('change');
            });
        }

        function formatOption(option) {
            var $option = $(
                '<strong>' + option.text +
                '</strong><p class="select2-subtitle-option text-[10px] !text-colorsubtitleform">' + option.title +
                '</p>'
            );
            return $option;
        };
    </script>
</div>
