<div x-data="data">
    <div wire:loading.flex class="loading-overlay hidden fixed">
        <x-loading-next />
    </div>

    @if ($promociones->hasPages())
        <div class="pt-3 pb-1">
            {{ $promociones->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="w-full flex flex-wrap gap-2 mb-3">
        @if (mi_empresa()->usarlista())
            @if (count($pricetypes) > 0)
                <div class="w-full mb-3 max-w-sm">
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

        <div class="w-full max-w-xs">
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
                    $empresa = mi_empresa();
                    $tipocambio = $empresa->usarDolar() ? $empresa->tipocambio : null;
                    $descuento = $item->descuento;
                    $combo = $item->producto->getAmountCombo($item, $pricetype);
                    // $pricesale = $item->producto->obtenerPrecioVenta($pricetype);
                    $pricesale = $item->producto->obtenerPrecioByPricebuy($item->pricebuy, $item, $pricetype, false);
                    $pricesale = !empty($combo) ? $pricesale + $combo->total : $pricesale;
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

                        <div class="p-1 pt-3">
                            <h1 class="text-colorlabel font-medium text-xs text-center leading-3 mb-2">
                                {{ $item->producto->name }}</h1>

                            {{-- <x-label-price>
                                S/.
                                @if (count($item->itempromos) > 0)
                                    @if ($item->isDisponible() && $item->isAvailable())
                                        {{ formatDecimalOrInteger($pricesale - $combo->total, $pricetype->decimals ?? 2, ', ') }}
                                    @else
                                        {{ formatDecimalOrInteger($pricesale, $pricetype->decimals ?? 2, ', ') }}
                                    @endif
                                @else
                                    {{ formatDecimalOrInteger($pricesale, $pricetype->decimals ?? 2, ', ') }}
                                @endif
                            </x-label-price> --}}

                            <div class="w-full">
                                <x-span-text :text="formatDecimalOrInteger($item->outs) . ' SALIDAS'" class="leading-3 !tracking-normal" />

                                <x-span-text :text="$item->limit > 0
                                    ? 'STOCK MAXIMO : ' .
                                        formatDecimalOrInteger($item->limit) .
                                        ' ' .
                                        $item->producto->unit->name
                                    : 'HASTA AGOTAR STOCK'" class="leading-3 !tracking-normal" />

                                <x-span-text :text="$item->startdate
                                    ? 'FECHA INICIO : ' . formatDate($item->startdate, 'DD MMMM Y')
                                    : 'SIN FECHA INICIO'" class="leading-3 !tracking-normal" />

                                <x-span-text :text="$item->expiredate
                                    ? 'FECHA EXPIRACIÓN : ' . formatDate($item->expiredate, 'DD MMMM Y')
                                    : 'SIN FECHA LÍMITE'" class="leading-3 !tracking-normal" />
                            </div>
                        </div>


                        {{-- ITEMS SECUNDARIOS --}}
                        @if (count($item->itempromos) > 0)
                            <div class="w-full my-2 p-1">
                                @foreach ($combo->products as $itemcombo)
                                    <div class="w-full flex gap-2 bg-body rounded relative">
                                        <div
                                            class="block rounded overflow-hidden flex-shrink-0 w-16 h-16 relative hover:shadow-lg cursor-pointer">
                                            @if ($itemcombo->image)
                                                <img src="{{ $itemcombo->image }}" alt=""
                                                    class="w-full h-full object-scale-down">
                                            @else
                                                <x-icon-image-unknown class="!w-full !h-full text-colorsubtitleform" />
                                            @endif
                                        </div>
                                        <div class="p-1 w-full flex-1">
                                            <h1 class="text-[10px] leading-3 text-left">
                                                {{ $itemcombo->name }}</h1>
                                            <h1 class="text-xs font-semibold text-next-500 mt-1 leading-3">
                                                S/.
                                                {{ formatDecimalOrInteger($itemcombo->price, $pricetype->decimals ?? 2, ', ') }}
                                            </h1>
                                            @if ($itemcombo->type)
                                                <x-span-text :text="$itemcombo->type" type="green" class="leading-3" />
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if ($pricesale > 0)
                            <h1 class="text-colorsubtitleform font-semibold text-2xl text-center">
                                <small class="text-[10px]">S/. </small>
                                @if ($item->isDisponible() && $item->isAvailable())
                                    {{ formatDecimalOrInteger($pricesale, $pricetype->decimals ?? 2, ', ') }}
                                @else
                                    {{ formatDecimalOrInteger(count($item->itempromos) > 0 ? $combo->total : $pricesale, $pricetype->decimals ?? 2, ', ') }}
                                @endif
                            </h1>
                            @if ($descuento > 0)
                                <small class="block text-[1rem] w-full line-through text-red-600 text-center">
                                    S/.
                                    {{ getPriceAntes($pricesale, $descuento, $pricetype ?? null, ', ') }}
                                </small>
                            @endif
                            @if ($item->isRemate())
                                <small class="block text-[1rem] w-full line-through text-red-600 text-center">
                                    S/.
                                    {{ formatDecimalOrInteger($item->pricebuy, 2, ', ') }}
                                </small>
                            @endif
                        @endif
                    </div>

                    <div class="w-full p-1 flex gap-2 items-end justify-between">
                        <div class="inline-flex gap-2 items-start ">
                            @if ($item->isFinalizado())
                                <x-span-text text="FINALIZADO" type="red" class="leading-3 !tracking-normal" />
                            @elseif ($item->isDesactivado())
                                <x-span-text text="DESACTIVADO" class="leading-3 !tracking-normal" />
                            @else
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

                    {{-- <div class="w-auto h-auto bg-red-600 absolute -left-9 top-3 -rotate-[35deg] leading-3">
                        <p class=" text-white text-[8px] inline-block font-semibold p-1 px-10">
                            PROMOCIÓN</p>
                    </div> --}}
                    {{-- <div class="w-auto h-auto bg-red-600 absolute -left-9 top-3 -rotate-[35deg] leading-3">
                        <p class="text-white text-[8px] block w-full leading-3 font-semibold p-1  px-10">
                            LIQUIDACIÓN</p>
                    </div> --}}
                    <div
                        class="w-auto h-auto {{ !empty(verifyPromocion($item)) ? 'bg-red-600' : 'bg-neutral-500' }}  absolute -left-8 top-3 -rotate-[35deg] leading-3">
                        <p class="text-white text-[9px] inline-block font-medium p-1 px-10">
                            @if ($item->isDescuento())
                                - {{ formatDecimalOrInteger($item->descuento) }}% DSCT
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

    <script>
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

        document.addEventListener('alpine:init', () => {
            Alpine.data('data', () => ({
                pricetype_id: @entangle('pricetype_id'),
                estado: @entangle('estado'),
            }))
        })


        function selectPricetype() {
            this.selectP = $(this.$refs.selectp).select2();
            this.selectP.val(this.pricetype_id).trigger("change");
            this.selectP.on("select2:select", (event) => {
                this.pricetype_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch('pricetype_id', (value) => {
                this.selectP.val(value).trigger("change");
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
            this.$watch('estado', (value) => {
                this.selectE.val(value).trigger("change");
            });

            Livewire.hook('message.processed', () => {
                this.selectE.select2().val(this.estado).trigger('change');
            });
        }
    </script>
</div>
