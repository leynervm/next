<div class="w-full grid grid-cols-1 xl:grid-cols-7 gap-3 xl:gap-5" x-data="showorder">
    <x-loading-web-next wire:key="showresumenorder" wire:loading />

    <div class="xl:col-span-5 w-full">
        @if (count($order->tvitems))
            <div
                class="w-full grid grid-cols-[repeat(auto-fill,minmax(170px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-1 mt-1">
                @foreach ($order->tvitems as $item)
                    @php
                        $image = !empty($item->producto->imagen)
                            ? pathURLProductImage($item->producto->imagen->url)
                            : null;
                    @endphp
                    <x-card-producto :image="$image" :name="$item->producto->name" :marca="$item->producto->marca->name" :category="$item->producto->category->name"
                        :increment="$item->increment" :promocion="$item->promocion" class="overflow-hidden">
                        @if ($item->isGratuito())
                            <x-span-text text="GRATUITO" type="green" class="!py-0.5" />
                        @endif

                        <h1 class="text-xl !leading-none font-semibold mt-1 text-center text-colorlabel">
                            {{ decimalOrInteger($item->cantidad, 2, ', ') }}
                            <small class="text-[10px] font-medium">
                                {{ $item->producto->unit->name }}
                                <small class="text-colorerror">
                                    /
                                    @if ($item->isNoAlterStock())
                                        NO ALTERA STOCK
                                    @elseif ($item->isReservedStock())
                                        STOCK RESERVADO
                                    @elseif ($item->isIncrementStock())
                                        INCREMENTA STOCK
                                    @elseif($item->isDiscountStock())
                                        DISMINUYE STOCK
                                    @endif
                                </small>
                            </small>
                        </h1>

                        <h1 class="text-xl text-center font-semibold text-colortitleform">
                            <small class="text-[10px] font-medium">{{ $order->moneda->simbolo }}</small>
                            {{ decimalOrInteger($item->total, 2, ', ') }}
                            <small class="text-[10px] font-medium">{{ $order->moneda->currency }}</small>
                        </h1>

                        @if ($item->cantidad > 1)
                            <div class="text-sm font-semibold text-colorlabel leading-3">
                                <small class="text-[10px] font-medium">P.U.V : </small>
                                {{ decimalOrInteger($item->price + $item->igv, 2, ', ') }}
                                <small class="text-[10px] font-medium">{{ $order->moneda->currency }}</small>
                            </div>
                        @endif

                        @if ($item->producto->isRequiredserie())
                            <div wire:key="contseries_{{ $item->id }}"
                                class="w-full flex flex-col gap-1 justify-center items-center mt-1"
                                x-data="{ showForm: '{{ count($item->itemseries) == 1 }}' }">
                                @if (count($item->itemseries) > 1)
                                    <x-button @click="showForm = !showForm"
                                        class="w-full !flex gap-3 items-center justify-center">
                                        <span x-text="showForm ? 'OCULTAR SERIES' : 'MOSTRAR SERIES'"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                                            stroke-width="1.2" viewBox="0 0 24 24" stroke-linecap="square"
                                            stroke-linejoin="round" class="size-5 block">
                                            <path d="M8 20V4h4v16z" />
                                            <path d="M2 4v16M5 4v16M15 4v16" />
                                            <path d="M18 20V4h4v16z" />
                                        </svg>
                                    </x-button>
                                @endif

                                <div class="w-full" x-show="showForm" x-transition>
                                    @if (count($item->itemseries) > 0)
                                        <x-table class="w-full block">
                                            <x-slot name="body">
                                                @foreach ($item->itemseries as $itemserie)
                                                    <tr>
                                                        <td class="p-1 align-middle font-medium">
                                                            {{ $itemserie->serie->serie }}
                                                            [{{ $itemserie->serie->almacen->name }}]
                                                        </td>
                                                        <td class="align-middle text-center" width="40px">
                                                            @if ($itemserie->serie)
                                                                <x-button-delete wire:loading.attr="disabled"
                                                                    wire:key="deleteitemserie_{{ $itemserie->id }}"
                                                                    x-on:click="confirmDeleteSerie({{ $itemserie->id }}, '{{ $itemserie->serie->serie }}')" />
                                                            @else
                                                                <x-button-delete wire:loading.attr="disabled"
                                                                    wire:key="deleteitemserie_{{ $itemserie->id }}"
                                                                    x-on:click="confirmDeleteSerie({{ $itemserie->id }})" />
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </x-slot>
                                        </x-table>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div
                                class="w-full grid {{ count($item->kardexes) > 1 ? 'grid-cols-3 xs:grid-cols-2' : 'grid-cols-1' }} gap-1 mt-2 divide-x divide-borderminicard">
                                @foreach ($item->kardexes as $kardex)
                                    <div class="w-full p-1.5 flex flex-col items-center justify-start">
                                        <h1 class="text-colorsubtitleform text-sm font-semibold !leading-none">
                                            {{ decimalOrInteger($kardex->cantidad) }}
                                            <small class="text-[10px] font-normal">
                                                {{ $item->producto->unit->name }}</small>
                                        </h1>

                                        <h1 class="text-colortitleform text-[10px] font-semibold">
                                            {{ $kardex->almacen->name }}</h1>

                                        @if (!$item->producto->isRequiredserie())
                                            <x-button-delete
                                                wire:click="deletekardex({{ $item->id }},{{ $kardex->id }})"
                                                wire:loading.attr="disabled" class="inline-flex" />
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <x-slot name="buttoncombos">
                            @include('modules.ventas.forms.modal-carshoopitems', [
                                'moneda' => $order->moneda,
                                'viewloading' => true,
                            ])
                        </x-slot>

                    </x-card-producto>
                @endforeach
            </div>
        @endif
    </div>

    {{-- TRACKING --}}
    <div class="xl:col-span-2 w-full border border-borderminicard rounded-xl p-3">
        <h3 class="text-xl font-semibold text-colorsubtitleform">Tracking</h3>
        @can('admin.marketplace.trackings.create')
            @if ($order->isPagoconfirmado())
                @if (!$order->trackings()->finalizados()->exists())
                    <form wire:submit.prevent="save" class="flex flex-col gap-2 py-5">
                        <div class="w-full">
                            <x-label for="trackingstate_id" value="Seleccionar estado :" />
                            <div class="relative" id="parenttrackingstate_id"" x-init="select2Tracking">
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

                        <div>
                            <x-label-check for="sendemail">
                                <x-input wire:model.defer="sendemail" name="sendemail" type="checkbox"
                                    id="sendemail" />ENVIAR CORREO DE NOTIFICACIÓN
                            </x-label-check>
                            <x-jet-input-error for="sendemail" />
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
                    <li class="mb-3 sm:mb-10 ms-6 text-colorlabel">
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
                        <h4 class="mb-0.5 font-semibold text-[10px] sm:text-sm text-primary">
                            {{ formatDate($item->date, 'DD MMM Y, hh:mm A') }}</h4>
                        <p class="text-[10px] sm:text-xs text-colorsubtitleform">{{ $item->trackingstate->name }}
                        </p>
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

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('showorder', () => ({
                trackingstate_id: @entangle('trackingstate_id').defer,
                // serie_id: @entangle('serie_id').defer,
                // almacen_id: @entangle('almacen_id').defer,
                almacenitem: @entangle('almacenitem').defer,
                almacens: @entangle('almacens').defer,
                init() {
                    this.$watch('almacenitem', (value) => {
                        this.valuesAlmacenItem();
                    });
                    this.$watch('almacens', (value) => {
                        this.valuesAlmacen();
                    });
                    Livewire.hook('message.processed', () => {
                        this.valuesAlmacenItem();
                        this.valuesAlmacen();
                    });
                },
                initializeSelect2(element, almacen_id) {
                    $(element).select2().on('select2:select', (event) => {
                        this.$wire.set(`almacens.${almacen_id}.serie_id`, event.target.value,
                            true);
                    });
                },
                valuesAlmacen() {
                    if (Object.keys(this.almacens).length > 0) {
                        for (let key in this.almacens) {
                            let x_ref =
                                `serie_id_${String(this.almacens[key].tvitem_id) + String(this.almacens[key].id)}`;
                            let value = this.almacens[key].serie_id;
                            const ser = document.getElementById(x_ref);
                            $(ser).select2().val(value).trigger('change');
                        }
                    }
                },
                select2Carshoopitem(element, carshoopitem_id, almacen_id) {
                    $(element).select2().on('select2:select', (event) => {
                        this.$wire.set(
                            `almacenitem.${carshoopitem_id}.almacens.${almacen_id}.serie_id`,
                            event
                            .target.value, true);
                    });
                },
                valuesAlmacenItem() {
                    if (Object.keys(this.almacenitem).length > 0) {
                        for (let key in this.almacenitem) {
                            if (Object.keys(this.almacenitem[key].almacens).length > 0) {
                                for (let almacen in this.almacenitem[key].almacens) {
                                    let x_ref = `serie_id_${String(key) + String(almacen)}`;
                                    let value = this.almacenitem[key].almacens[almacen].serie_id;
                                    const ser = document.getElementById(x_ref);
                                    $(ser).select2().val(value).trigger('change');
                                }
                            }
                        }
                    }
                },
                confirmDeleteSerie(itemserie_id, serie = null) {
                    let mensaje = serie == null ? `SERIE NO DISPONIBLE, ELIMINAR DEL PRODUCTO ?` :
                        `ELIMINAR SERIE ${serie} DEL PRODUCTO ?`;

                    swal.fire({
                        title: mensaje,
                        text: null,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#0FB9B9',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.$wire.deleteitemserie(itemserie_id);
                        }
                    })
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
    </script>
</div>
