<div x-data="data">
    <div class="w-full flex flex-wrap gap-2 mb-3">
        @if ($empresa->usarlista())
            @if (count($pricetypes) > 0)
                <div class="w-full mb-3 max-w-60">
                    <x-label value="Lista precios :" />
                    <div id="parentventapricetype_id" class="relative" x-init="selectPricetype" wire:ignore>
                        <x-select class="block w-full" id="ventapricetype_id" x-ref="selectp">
                            <x-slot name="options">
                                @foreach ($pricetypes as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }}</option>
                                @endforeach
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="pricetype_id" />
                </div>
            @endif
        @endif

        <div class="w-full max-w-60">
            <x-label value="Filtrar estado :" />
            <div class="relative" id="parentselectestado" x-init="selectEstado">
                <x-select class="block w-full" id="selectestado" x-ref="selectestado">
                    <x-slot name="options">
                        <option value="{{ \App\Models\Promocion::ACTIVO }}">PROMOCIONES ACTIVAS</option>
                        <option value="{{ \App\Models\Promocion::FINALIZADO }}">PROMOCIONES FINALIZADAS</option>
                        <option value="{{ \App\Models\Promocion::DESACTIVADO }}">PROMOCIONES DESACTIVADAS</option>
                    </x-slot>
                </x-select>
                <x-icon-select />
            </div>
            <x-jet-input-error for="estado" />
        </div>
    </div>

    @if (count($promociones) > 0)
        <div class="w-full grid grid-cols-[repeat(auto-fill,minmax(240px,1fr))] gap-1 self-start relative">
            @foreach ($promociones as $item)
                @php
                    $image = !empty($item->producto->image) ? pathURLProductImage($item->producto->image) : null;
                    $empresa = view()->shared('empresa');
                    $tipocambio = $empresa->usarDolar() ? $empresa->tipocambio : null;
                    $descuento = $item->descuento;
                    $combo = getAmountCombo($item, $pricetype);
                    $priceold = $item->producto->getPrecioVentaDefault($pricetype);
                    $pricesale = $priceold;

                    if ($descuento > 0) {
                        $pricesale = getPriceDscto($pricesale, $descuento, $pricetype);
                    }
                    if ($item->isLiquidacion()) {
                        $pricesale = $item->pricebuy;
                    }

                    if (!empty($combo)) {
                        $priceold = $priceold + $combo->total_normal;
                        $pricesale = $pricesale + $combo->total;
                    }
                @endphp

                <x-simple-card
                    class="w-full relative flex flex-col gap-2 justify-between overflow-hidden {{ $item->isFinalizado() ? 'saturate-0' : '' }}">
                    <div class="w-full">
                        <div class="block rounded overflow-hidden w-full h-48 relative cursor-pointer">
                            @if ($image)
                                <img src="{{ $image }}" alt="" class="w-full h-full object-scale-down">
                            @else
                                <x-icon-image-unknown class="w-full h-full" />
                            @endif
                        </div>
                        {{-- <p>{{ getPrecioventa($item->producto, $pricetype) }}</p> --}}

                        <div class="p-1 pt-2">
                            <h1 class="text-colorlabel font-medium text-[10px] text-center leading-tight mb-2">
                                {{ $item->producto->name }}</h1>

                            <p class="text-center text-xs sm:text-sm md:text-lg font-medium text-primary !leading-none">
                                STOCK OFERTADO</p>
                            <p class="text-center text-lg md:text-xl font-semibold text-primary !leading-none">
                                {{ decimalOrInteger($item->limit) }}
                                <small class="text-xs font-medium">
                                    {{ $item->producto->unit->name }}
                                </small>
                            </p>

                            @if (!is_null($item->startdate) || !is_null($item->expiredate))
                                <p class="text-center text-[10px] font-medium text-colorsubtitleform leading-none mt-2">
                                    {{ formatDate($item->startdate, 'dddd\\, DD MMM Y') }}
                                    <small><br>HASTA<br></small>
                                    {{ formatDate($item->expiredate, 'dddd\\, DD MMM Y') }}
                                </p>
                            @endif

                            @if ($item->outs > 0)
                                <p class="font-medium text-center text-xs text-green-600 my-2">
                                    {{ decimalOrInteger($item->outs) }} EXITOSAS</p>
                            @endif
                        </div>

                        {{-- ITEMS SECUNDARIOS --}}
                        @if (count($item->itempromos) > 0)
                            <div class="p-1">
                                <div class="border border-borderminicard p-1 rounded-lg">
                                    @if ($item->isCombo())
                                        <h1 class="text-colorerror font-medium text-xs text-center !leading-none m-1">
                                            {{ $item->titulo }}</h1>
                                    @endif

                                    <div class="w-full my-2 p-1">
                                        @foreach ($combo->products as $itemcombo)
                                            @php
                                                $opacidad = $itemcombo->stock > 0 ? '' : 'opacity-75 saturate-0';
                                            @endphp
                                            <div class="w-full rounded relative">
                                                <h1
                                                    class="text-[10px] text-center leading-tight text-colorsubtitleform">
                                                    {{ $itemcombo->name }}</h1>

                                                <div class="w-full flex gap-2 relative">
                                                    <div
                                                        class="block rounded-lg flex-shrink-0 w-20 h-auto max-h-20 relative">
                                                        @if ($itemcombo->image)
                                                            <img src="{{ $itemcombo->image }}"
                                                                alt="{{ $itemcombo->image }}"
                                                                class="{{ $opacidad }} block w-full h-full object-scale-down overflow-hidden rounded-lg">
                                                        @else
                                                            <x-icon-image-unknown
                                                                class="!w-full !h-full text-colorsubtitleform {{ $opacidad }}" />
                                                        @endif
                                                        @if ($itemcombo->stock <= 0)
                                                            <x-span-text text="AGOTADO" type="red"
                                                                class="absolute top-[50%] left-2 -translate-y-[50%]" />
                                                        @endif
                                                    </div>
                                                    <div class="p-1 w-full flex-1 {{ $opacidad }}">
                                                        @if ($itemcombo->type)
                                                            <x-span-text :text="$itemcombo->type" type="green" />
                                                        @endif
                                                        <h1
                                                            class="text-sm font-semibold text-primary mt-1 leading-none">
                                                            <small class="text-[10px] font-medium">S/.</small>
                                                            {{ decimalOrInteger($itemcombo->price, $pricetype->decimals ?? 2, ', ') }}
                                                        </h1>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($pricesale > 0)
                            <h1 class="text-colorsubtitleform font-semibold text-2xl text-center">
                                <small class="text-[10px]">S/. </small>
                                {{ decimalOrInteger($pricesale, $pricetype->decimals ?? 2, ', ') }}
                            </h1>
                            @if ($item->isDescuento() || $item->isLiquidacion())
                                <small class="block text-[1rem] w-full line-through text-red-600 text-center">
                                    S/.
                                    {{ decimalOrInteger($priceold, $pricetype->decimals ?? 2, ', ') }}
                                </small>
                            @endif
                            {{-- @if ($item->isLiquidacion())
                                <small class="block text-[1rem] w-full line-through text-red-600 text-center">
                                    S/.
                                    {{ decimalOrInteger($item->producto->pricebuy, 2, ', ') }}
                                </small>
                            @endif --}}
                        @endif
                    </div>

                    <div class="w-full p-1 flex gap-2 items-end justify-between">
                        <div class="inline-flex gap-2 items-start ">
                            @if ($item->isFinalizado())
                                <x-span-text text="FINALIZADO" type="red" class="leading-3 !tracking-normal" />
                            @elseif ($item->isDesactivado())
                                <x-span-text text="DESACTIVADO" class="leading-3 !tracking-normal" />
                            @else
                                <x-button-edit wire:click="edit({{ $item->id }})"
                                    wire:key="editpromo_{{ $item->id }}" wire:loading.attr="disabled" />

                                @if ($item->isExpired())
                                    <x-span-text text="EXPIRADO" type="red" class="leading-3 !tracking-normal" />
                                @elseif ($item->startdate > now('America/Lima'))
                                    <x-span-text text="PRÓXIMO" type="amber" class="leading-3 !tracking-normal" />
                                @elseif ($item->limit > 0 && $item->outs == $item->limit)
                                    <x-span-text text="AGOTADO" type="orange" class="leading-3 !tracking-normal" />
                                @else
                                    <x-span-text text="ACTIVO" type="green" class="leading-3 !tracking-normal" />
                                @endif
                            @endif

                            @can('admin.promociones.edit')
                                @if (!$item->isFinalizado())
                                    <x-button-toggle onclick="confirmStatus({{ $item }})"
                                        wire:loading.attr="disabled"
                                        class=" {{ $item->isDesactivado() ? 'text-gray-300' : 'text-next-500' }}" />
                                @endif
                            @endcan
                        </div>

                        @if ($item->isFinalizado())
                            @can('admin.promociones.delete')
                                {{-- <x-button-delete onclick="confirmDelete({{ $item->id }})"
                                    wire:loading.attr="disabled" /> --}}
                            @endcan
                        @else
                            @can('admin.promociones.edit')
                                <x-button onclick="confirmFinalizacion({{ $item->id }})"
                                    wire:loading.attr="disabled">FINALIZAR</x-button>
                            @endcan
                        @endif
                    </div>

                    <div
                        class="w-auto h-auto {{ !empty(verifyPromocion($item)) ? 'bg-red-600' : 'bg-neutral-500' }}  absolute -left-8 top-3 -rotate-[35deg] leading-3">
                        <p class="text-white text-[9px] inline-block font-medium p-1 px-10">
                            @if ($item->isDescuento())
                                - {{ decimalOrInteger($item->descuento) }}% DSCT
                            @elseif ($item->isCombo())
                                COMBO
                            @else
                                LIQUIDACIÓN
                            @endif
                        </p>
                    </div>
                </x-simple-card>
            @endforeach
        </div>
    @endif

    <div wire:key="loadingshowpromociones" wire:loading.flex class="loading-overlay hidden fixed">
        <x-loading-next />
    </div>

    @if ($promociones->hasPages())
        <div class="w-full flex justify-center items-center sm:justify-end p-1 sticky -bottom-1 right-0 bg-body">
            {{ $promociones->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif


    <x-jet-dialog-modal wire:model="open" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Crear promoción') }}
        </x-slot>

        <x-slot name="content">
            @if (!is_null($promocion->id))
                <form wire:submit.prevent="update" class="w-full block">
                    <div class="w-full">
                        <h1 class="text-xs leading-3 text-center text-colortitleform mt-3">
                            {{ $promocion->producto->name }}</h1>

                        <div class="w-full max-w-full mx-auto my-2">
                            @if (!empty($promocion->producto->image))
                                <img src="{{ pathURLProductImage($promocion->producto->image) }}"
                                    alt="{{ pathURLProductImage($promocion->producto->image) }}"
                                    class="block w-full max-w-full h-auto max-h-72 object-scale-down overflow-hidden">
                            @else
                                <x-icon-file-upload type="unknown" class="w-full h-full max-h-72" />
                            @endif
                        </div>
                        @if ($promocion->isCombo())
                            @php
                                $promocombo = getAmountCombo($promocion, $pricetype);
                            @endphp
                            <div class="w-full flex flex-wrap gap-2 mb-2">
                                @foreach ($promocombo->products as $itemcombo)
                                    <div
                                        class="w-48 rounded-lg shadow shadow-shadowminicard flex flex-col gap-1 p-2 relative">
                                        <h1 class="text-[10px] text-center leading-tight text-colorsubtitleform">
                                            {{ $itemcombo->name }}</h1>

                                        <div class="w-full block">
                                            @if ($itemcombo->image)
                                                <img src="{{ $itemcombo->image }}" alt="{{ $itemcombo->image }}"
                                                    class="block w-full h-full max-h-32 object-scale-down overflow-hidden">
                                            @else
                                                <x-icon-image-unknown class="!w-full !h-full text-colorsubtitleform" />
                                            @endif
                                        </div>
                                        @if (!empty($itemcombo->type))
                                            <x-span-text :text="$itemcombo->type" type="green"
                                                class="leading-none absolute bottom-1 left-[50%] -translate-x-[50%]" />
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if ($promocion->type == \App\Enums\PromocionesEnum::COMBO->value)
                        <div class="w-full mt-2">
                            <x-label value="Título del combo :" />
                            <x-input class="block w-full" wire:model.defer="promocion.titulo" type="text"
                                min="0" step="0.01" />
                            <x-jet-input-error for="promocion.titulo" />
                        </div>
                    @endif

                    <div class="mt-2 w-full grid grid-cols-1 xs:grid-cols-2 xl:grid-cols-3 gap-2">
                        <div>
                            <x-label value="Tipo de promoción :" />
                            <x-disabled-text :text="\App\Enums\PromocionesEnum::tryFrom($promocion->type)->label()" />
                        </div>

                        <div class="w-full">
                            <x-label value="Fecha inicio (Opcional) :" />
                            <x-input class="block w-full" wire:model.defer="promocion.startdate" type="date" />
                            <x-jet-input-error for="promocion.startdate" />
                        </div>

                        <div class="w-full">
                            <x-label value="Fecha finalización (Opcional) :" />
                            <x-input class="block w-full" wire:model.defer="promocion.expiredate" type="date" />
                            <x-jet-input-error for="promocion.expiredate" />
                        </div>

                        @if ($promocion->type == \App\Enums\PromocionesEnum::DESCUENTO->value)
                            <x-label value="Descuento (%) :" />
                            <x-input class="block w-full input-number-none" wire:model.defer="promocion.descuento"
                                type="number" min="0" step="0.01"
                                onkeypress="return validarNumero(event, 5)" />
                            <x-jet-input-error for="promocion.descuento" />
                        @endif

                        <div class="w-full">
                            <div>
                                <x-label value="stock Máximo:" />
                                <x-input x-show="agotarstock == false" class="block w-full input-number-none"
                                    wire:model.defer="promocion.limit" type="number" min="0" step="1"
                                    onkeypress="return validarNumero(event, 9)" />
                                <x-disabled-text x-show="agotarstock" text="AGOTAR STOCK" />
                                <x-jet-input-error for="promocion.limit" />
                            </div>
                            <div class="mt-1">
                                <x-label-check for="agotarstock_edit">
                                    <x-input wire:model.defer="agotarstock" x-model="agotarstock" type="checkbox"
                                        id="agotarstock_edit" />
                                    HASTA AGOTAR STOCK DISPONIBLE
                                </x-label-check>
                                <x-jet-input-error for="agotarstock" />
                                <x-jet-input-error for="limitstock" />
                            </div>
                        </div>

                        <div>
                            <x-label value="Cantidad vendida :" />
                            <x-disabled-text :text="$promocion->outs" class="block" />
                        </div>
                    </div>

                    <div class="w-full flex justify-end pt-4">
                        <x-button type="submit" wire:click="update" wire:loading.attr="disabled">
                            {{ __('Save') }}</x-button>
                    </div>
                </form>
            @endif
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('data', () => ({
                pricetype_id: @entangle('pricetype_id'),
                estado: @entangle('estado'),
                agotarstock: @entangle('agotarstock').defer,
                type: @entangle('promocion.type').defer,
                init() {
                    this.$watch('pricetype_id', (value) => {
                        this.selectP.val(value).trigger("change");
                    })
                    this.$watch('estado', (value) => {
                        this.selectE.val(value).trigger("change");
                    })

                    Livewire.hook('message.processed', () => {
                        this.selectP.select2().val(this.pricetype_id).trigger('change');
                        this.selectE.select2().val(this.estado).trigger('change');
                    });
                }
            }))
        })

        function confirmDelete(promocion_id) {
            swal.fire({
                title: `Eliminar promoción del producto seleccionado !`,
                text: `La promoción del producto seleccionados dejará de estar disponible.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(promocion_id);
                }
            })
        }

        function confirmFinalizacion(promocion_id) {
            swal.fire({
                title: `Finalizar promoción del producto seleccionado !`,
                text: `La promoción del producto seleccionados dejará de estar disponible.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.finalizarpromocion(promocion_id);
                }
            })
        }

        function confirmStatus(promocion) {
            let title = promocion.status == '1' ? 'Activar' : 'Desactivar';
            let mensaje = promocion.status == '1' ? ' dejará de ' : ' volverá a ';
            swal.fire({
                title: `${title} promoción del producto seleccionado !`,
                text: `La promoción de los productos ${mensaje} estar disponible.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.disablepromocion(promocion.id);
                }
            })
        }

        function selectPricetype() {
            this.selectP = $(this.$refs.selectp).select2();
            this.selectP.val(this.pricetype_id).trigger("change");
            this.selectP.on("select2:select", (event) => {
                this.pricetype_id = event.target.value;
                // this.$wire.set('pricetype_id', event.target.value);
                // this.$wire.refresh;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
        }

        function selectEstado() {
            this.selectE = $(this.$refs.selectestado).select2();
            this.selectE.val(this.estado).trigger("change");
            this.selectE.on("select2:select", (event) => {
                this.estado = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
        }
    </script>
</div>
