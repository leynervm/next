<div class="w-full grid grid-cols-1 xl:grid-cols-7 gap-3 xl:gap-5">
    <div wire:loading.flex wire:target="updatestock,save,discountserie,discountstock"
        class="fixed loading-overlay hidden z-[99999]">
        <x-loading-next />
    </div>

    <div class="xl:col-span-5 w-full">
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-full text-[10px] md:text-xs">
                <tbody class="divide-y">
                    @foreach ($order->tvitems as $item)
                        @php
                            $image = $item->producto->getImageURL();
                        @endphp

                        <tr class="text-colorlabel">
                            <td class="text-left py-2 align-middle">
                                <div class="flex flex-col xs:flex-row items-start gap-2">
                                    <div
                                        class="flex-shrink-0 w-full h-40 mx-auto xs:w-24 xs:h-24 rounded overflow-hidden">
                                        @if ($image)
                                            <img src="{{ $image }}" alt=""
                                                class="w-full h-full object-scale-down rounded aspect-square overflow-hidden">
                                        @else
                                            <x-icon-file-upload
                                                class="!w-full !h-full !m-0 text-colorsubtitleform !border-0"
                                                type="unknown" />
                                        @endif
                                    </div>
                                    <div
                                        class="w-full flex-1 lg:flex justify-between gap-3 items-center text-colorsubtitleform">
                                        <div class="w-full lg:flex-1">
                                            <a href="{{ route('productos.show', $item->producto) }}"
                                                class="block w-full text-xs text-center xs:text-start">
                                                {{ $item->producto->name }}</a>

                                            @if (!empty($item->promocion_id))
                                                <span
                                                    class="p-1 font-semibold inline-block ring-1 rounded-lg text-[10px] ring-green-600 text-end text-green-600 whitespace-nowrap">
                                                    PROMOCIÓN</span>
                                            @endif

                                            @if (count($item->itemseries) > 0)
                                                <div class="w-full flex flex-wrap gap-2 mb-2">
                                                    @foreach ($item->itemseries as $itemser)
                                                        <div
                                                            class="inline-flex items-center gap-1 text-[10px] text-center bg-fondospancardproduct text-textspancardproduct p-1 rounded-lg">
                                                            <div class="w-full flex-1">
                                                                {{ $itemser->serie->serie }}
                                                                <br>
                                                                <span class="block text-colortitleform">
                                                                    {{ $itemser->serie->almacen->name }}
                                                                </span>
                                                            </div>

                                                            @can('admin.marketplace.orders.deletestock')
                                                                @if ($item->producto->isRequiredserie())
                                                                    <x-button-delete
                                                                        onclick="deleteitemseriestock({{ $itemser->id }})"
                                                                        wire:loading.attr="disabled"
                                                                        class="inline-flex flex-shrink-0" />
                                                                @endif
                                                            @endcan
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if (count($item->kardexes) > 0)
                                                <div class="w-full flex flex-wrap gap-2">
                                                    @foreach ($item->kardexes as $kardex)
                                                        <x-simple-card
                                                            class="max-w-24 min-w-24 p-2 flex flex-col items-center justify-start">
                                                            <div class="text-colorsubtitleform text-center">
                                                                <span
                                                                    class="inline-block text-sm text-center font-semibold">
                                                                    {{ decimalOrInteger($kardex->cantidad) }}</span>
                                                                <small
                                                                    class="inline-block text-center text-[10px] leading-3">
                                                                    {{ $item->producto->unit->name }}</small>
                                                            </div>

                                                            <h1
                                                                class="text-colortitleform text-[10px] text-center font-semibold">
                                                                {{ $kardex->almacen->name }}</h1>

                                                            @can('admin.marketplace.orders.deletestock')
                                                                @if (!$item->producto->isRequiredserie())
                                                                    <x-button-delete
                                                                        onclick="deletestock({{ $kardex->id }})"
                                                                        wire:loading.attr="disabled" class="inline-flex" />
                                                                @endif
                                                            @endcan
                                                        </x-simple-card>
                                                    @endforeach
                                                </div>
                                            @endif


                                            @if ($item->kardexes && $item->kardexes->sum('cantidad') == $item->cantidad)
                                                <div class="mt-1">
                                                    <x-span-text type="green" text="STOCK ACTUALIZADO" />
                                                </div>
                                            @else
                                                @can('admin.marketplace.orders.discountstock')
                                                    <x-button class="mt-2" wire:click="updatestock({{ $item->id }})"
                                                        wire:loading.attr="disabled">DESCONTAR STOCK</x-button>
                                                @endcan
                                            @endif
                                        </div>

                                        <div class="flex items-end lg:items-center lg:w-60 lg:flex-shrink-0 ">
                                            <span
                                                class="text-left p-2 text-xs sm:text-end font-semibold whitespace-nowrap">
                                                x{{ decimalOrInteger($item->cantidad) }}
                                                {{ $item->producto->unit->name }}
                                            </span>

                                            @if ($item->isGratuito())
                                                <div class="flex-1 flex justify-end items-center">
                                                    <span
                                                        class="p-2 font-semibold inline-block ring-1 rounded-lg text-xs ring-green-600 text-end text-green-600 whitespace-nowrap">
                                                        GRATUITO</span>
                                                </div>
                                            @else
                                                <span
                                                    class="p-2 font-semibold text-lg flex-1 text-end text-colorlabel whitespace-nowrap">
                                                    {{ $order->moneda->simbolo }}
                                                    {{ number_format($item->total, 2, '.', ', ') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- TRACKING --}}
    <div class="xl:col-span-2 w-full pb-5 border border-borderminicard rounded-xl p-3">
        <h3 class="text-xl font-semibold text-colorsubtitleform">Tracking</h3>
        @can('admin.marketplace.trackings.create')
            @if ($order->isPagoconfirmado())
                @if (!$order->trackings()->finalizados()->exists())
                    <form wire:submit.prevent="save" class="flex flex-col gap-2 py-5">
                        <div class="w-full">
                            <x-label for="trackingstate_id" value="Seleccionar estado :" />
                            <div class="relative" id="parenttrackingstate_id" x-data="{ trackingstate_id: @entangle('trackingstate_id').defer }"
                                x-init="select2Tracking">
                                <x-select class="block w-full" id="trackingstate_id" data-placeholder="null"
                                    x-ref="selecttraking" x-model="trackingstate_id">
                                    <x-slot name="options">
                                        @foreach ($trackingstates as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </x-slot>
                                </x-select>
                                <x-icon-select />
                            </div>
                            <x-jet-input-error for="trackingstate_id" />
                        </div>

                        <div class="w-full flex justify-end">
                            <x-button type="submit" wire:loading.attr="disabled">
                                {{ __('Save') }}</x-button>
                        </div>
                    </form>
                @endif
            @endif
        @endcan

        @if (count($order->trackings) > 0)
            <ol class="relative ms-3 border-s border-borderminicard">
                @foreach ($order->trackings as $item)
                    <li class="mb-10 ms-6 text-colorlabel">
                        <span
                            class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-next-500 ring-8 ring-body">
                            @if ($item->trackingstate->isFinalizado())
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                    class="h-4 w-4 text-white" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M21 7V12M3 7C3 10.0645 3 16.7742 3 17.1613C3 18.5438 4.94564 19.3657 8.83693 21.0095C10.4002 21.6698 11.1818 22 12 22L12 11.3548" />
                                    <path d="M15 19C15 19 15.875 19 16.75 21C16.75 21 19.5294 16 22 15" />
                                    <path
                                        d="M8.32592 9.69138L5.40472 8.27785C3.80157 7.5021 3 7.11423 3 6.5C3 5.88577 3.80157 5.4979 5.40472 4.72215L8.32592 3.30862C10.1288 2.43621 11.0303 2 12 2C12.9697 2 13.8712 2.4362 15.6741 3.30862L18.5953 4.72215C20.1984 5.4979 21 5.88577 21 6.5C21 7.11423 20.1984 7.5021 18.5953 8.27785L15.6741 9.69138C13.8712 10.5638 12.9697 11 12 11C11.0303 11 10.1288 10.5638 8.32592 9.69138Z" />
                                    <path d="M6 12L8 13" />
                                    <path d="M17 4L7 9" />
                                </svg>
                            @else
                                <svg class="h-4 w-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5" />
                                </svg>
                            @endif
                        </span>
                        <h4 class="mb-0.5 font-semibold text-sm text-primary">
                            {{ formatDate($item->date, 'DD MMM Y, hh:mm A') }}</h4>
                        <p class="text-xs text-colorsubtitleform">{{ $item->trackingstate->name }}</p>
                        @can('admin.marketplace.trackings.delete')
                            @if (!$item->trackingstate->isDefault())
                                <x-button-delete wire:click="delete({{ $item->id }})" wire:loading.attr="disabled" />
                            @endif
                        @endcan
                    </li>
                @endforeach
            </ol>
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="3xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Descontar stock del producto') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="discountstock" x-data="discount">

                @if ($tvitem->producto)
                    <p class="text-xs text-colorlabel pb-6">
                        {{ $tvitem->producto->name }}</p>

                    @if (count($tvitem->itemseries) > 0)
                        <div class="w-full flex flex-wrap gap-2 mb-2">
                            @foreach ($tvitem->itemseries as $item)
                                <div
                                    class="text-xs text-center bg-fondospancardproduct text-textspancardproduct p-1 px-1 rounded-lg">
                                    {{ $item->serie->serie }}
                                    <br>
                                    <span class="block text-colortitleform text-[10px]">
                                        {{ $item->serie->almacen->name }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif

                @if ($tvitem && $tvitem->kardexes->sum('cantidad') < $tvitem->cantidad)
                    @if (count($almacens) > 0)
                        <div class="w-full flex flex-wrap gap-2">
                            @foreach ($almacens as $item)
                                <x-simple-card wire:key="{{ $item['id'] }}"
                                    class="w-full xs:w-52 rounded-lg p-2 flex flex-col gap-3 justify-start">
                                    <div class="text-colorsubtitleform text-center">
                                        <small class="w-full block text-center text-[8px] leading-3">
                                            STOCK ACTUAL</small>
                                        <span class="inline-block text-2xl text-center font-semibold">
                                            {{ decimalOrInteger($item['pivot']['cantidad']) }}</span>
                                        <small class="inline-block text-center text-[10px] leading-3">
                                            {{ $tvitem->producto->unit->name }}</small>
                                    </div>

                                    <h1 class="text-colortitleform text-[10px] text-center font-semibold">
                                        {{ $item['name'] }}</h1>

                                    @if ($tvitem->producto->isRequiredserie())
                                        <div class="w-full">
                                            <x-label value="SELECCIONAR SERIES :" />
                                            <div class="relative" id="parentserie_id_{{ $item['id'] }}">
                                                <x-select class="block w-full relative" x-init="initializeSelect2($el, {{ $item['id'] }})"
                                                    id="serie_id_{{ $item['id'] }}" data-dropdown-parent="null"
                                                    data-minimum-results-for-search="3">
                                                    <x-slot name="options">
                                                        @foreach ($tvitem->producto->series()->disponibles()->where('almacen_id', $item['id'])->get() as $ser)
                                                            <option value="{{ $ser->id }}">
                                                                {{ $ser->serie }}</option>
                                                        @endforeach
                                                    </x-slot>
                                                </x-select>
                                                <x-icon-select />
                                            </div>
                                            <x-jet-input-error for="almacens.{{ $item['id'] }}.serie_id" />
                                        </div>
                                        <div class="w-full">
                                            <x-button type="button"
                                                @click.prevent="discountserie({{ $item['id'] }})">AGREGAR</x-button>
                                        </div>
                                    @else
                                        <div class="w-full">
                                            <x-label value="STOCK DESCONTAR :" />
                                            <x-input class="block w-full"
                                                wire:model.defer="almacens.{{ $item['id'] }}.cantidad"
                                                x-mask:dynamic="$money($input, '.', '', 0)" placeholder="0"
                                                onkeypress="return validarDecimal(event, 9)"
                                                wire:key="cantidad_{{ $item['id'] }}"
                                                wire:loading.class="bg-blue-50" />
                                            <x-jet-input-error for="almacens.{{ $item['id'] }}.cantidad" />
                                        </div>
                                    @endif
                                </x-simple-card>
                            @endforeach
                        </div>
                    @endif
                @endif

                @if ($tvitem->producto && !$tvitem->producto->isRequiredserie())
                    <div class="w-full flex pt-4 gap-2 justify-end">
                        <x-button type="submit" wire:loading.attr="disabled">
                            {{ __('Save') }}</x-button>
                    </div>
                @endif
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('discount', () => ({
                serie_id: @entangle('serie_id').defer,
                almacen_id: @entangle('almacen_id').defer,
                initializeSelect2(element, almacen_id) {
                    $(element).select2().on("select2:select", (event) => {
                        this.serie_id = event.target.value;
                        console.log(this.serie_id);
                    });
                    this.$watch("serie_id", (value) => {
                        $(element).val(value).trigger("change");
                    });
                    Livewire.hook('message.processed', () => {
                        $(element).select2().val(this.serie_id).trigger('change');
                    });
                },
                discountserie(almacen_id) {
                    console.log(almacen_id, this.serie_id);
                    this.$wire.discountserie(almacen_id, this.serie_id).then(function() {
                        console.log('completed');
                    });
                }
            }))
        })

        function select2Tracking() {
            this.selectTRK = $(this.$refs.selecttraking).select2();
            this.selectTRK.val(this.trackingstate_id).trigger("change");
            this.selectTRK.on("select2:select", (event) => {
                this.trackingstate_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("trackingstate_id", (value) => {
                this.selectTRK.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectTRK.select2().val(this.trackingstate_id).trigger('change');
            });
        }

        function deleteitemseriestock(itemserie_id) {
            swal.fire({
                title: 'ANULAR SERIE DEL STOCK EN ALMACÉN SELECCIONADO ?',
                text: "Se eliminará un registro de pago de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deleteitemseriestock(itemserie_id);
                }
            })
        }

        function deletestock(kardex_id) {
            swal.fire({
                title: 'ANULAR DESCUENTO DE STOCK EN ALMACÉN SELECCIONADO ?',
                text: "Se eliminará un registro de pago de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deletestock(kardex_id);
                }
            })
        }

        // function selectAlmacen() {
        //     this.selectAP = $(this.$refs.selectalmacen).select2();
        //     this.selectAP.val(this.almacen_id).trigger("change");
        //     this.selectAP.on("select2:select", (event) => {
        //         this.almacen_id = event.target.value;
        //     }).on('select2:open', function(e) {
        //         const evt = "scroll.select2";
        //         $(e.target).parents().off(evt);
        //         $(window).off(evt);
        //     });
        //     this.$watch("almacen_id", (value) => {
        //         this.selectAP.val(value).trigger("change");
        //     });
        //     Livewire.hook('message.processed', () => {
        //         this.selectAP.select2().val(this.almacen_id).trigger('change');
        //     });
        // }
    </script>
</div>
