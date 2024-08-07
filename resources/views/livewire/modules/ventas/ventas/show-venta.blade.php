<div class="w-full flex flex-col gap-8" x-data="{ loadingventa: false }">
    <div wire:loading.flex class="loading-overlay hidden fixed">
        <x-loading-next />
    </div>
    
    <x-simple-card class="flex flex-col gap-1 rounded-md cursor-default p-3">
        <div class="w-full sm:flex sm:gap-3">
            <div class="w-full text-colorlabel">
                <h1 class="font-semibold text-sm leading-4 text-colortitleform">
                    <span class="text-3xl">{{ $venta->seriecompleta }}</span>
                    {{ $venta->seriecomprobante->typecomprobante->name }}
                </h1>

                <h1 class="font-medium text-xs leading-4">
                    {{ $venta->client->name }} - {{ $venta->client->document }}
                    <p>DIRECCIÓN : {{ $venta->direccion }}</p>
                </h1>

                <h1 class="font-medium text-xs">
                    {{ formatDate($venta->date) }}
                </h1>

                <h1 class="font-medium text-xs">
                    TIPO PAGO : {{ $venta->typepayment->name }}
                </h1>

                <h1 class="font-medium text-xs">
                    MONEDA : {{ $venta->moneda->currency }}
                    @if ($venta->moneda->code == 'USD')
                        / {{ number_format($venta->tipocambio, 3, '.', '') }}
                    @endif
                </h1>

                <h1 class="text-colorsubtitleform font-medium text-xs">
                    SUCURSAL: {{ $venta->sucursal->name }}
                    @if ($venta->sucursal->trashed())
                        <x-span-text text="NO DISPONIBLE" class="leading-3 !tracking-normal inline-block" />
                    @endif
                </h1>
            </div>
        </div>

        @can('admin.ventas.delete')
            <div class="w-full flex items-end justify-end">
                <x-button-secondary onclick="confirmDelete({{ $venta }})" wire:loading.attr="disabled">
                    {{ __('ELIMINAR') }}</x-button-secondary>
            </div>
        @endcan
    </x-simple-card>

    <x-form-card titulo="RESUMEN PAGO" class="flex flex-col gap-1 rounded-md cursor-default p-3">
        <div class="w-full text-colortitleform">
            @if (count($venta->cajamovimientos) > 0)
                <div class="w-full flex flex-wrap gap-2">
                    @foreach ($venta->cajamovimientos as $item)
                        <x-card-cuota class="w-full xs:w-48" :titulo="null" :detallepago="$item">
                            <p class="text-colorminicard text-xl font-semibold text-center">
                                <small class="text-[10px] font-medium">{{ $venta->moneda->simbolo }}</small>
                                {{ number_format($item->amount, 2, '.', ', ') }}
                                <small class="text-[10px] font-medium">{{ $venta->moneda->currency }}</small>
                            </p>

                            <x-slot name="footer">
                                <div class="w-full flex gap-2 items-end justify-between">
                                    <x-button-print href="{{ route('admin.payments.print', $item) }}"
                                        target="_blank" />

                                    {{-- @can('admin.almacen.compras.pagos') --}}
                                    @if ($venta->typepayment->isContado())
                                        <x-button-delete onclick="confirmDeletePayment({{ $item->id }})"
                                            wire:loading.attr="disabled" />
                                    @endif

                                    {{-- @endcan --}}
                                </div>
                            </x-slot>
                        </x-card-cuota>
                    @endforeach
                </div>
            @else
                <x-span-text text="NO EXISTEN REGISTROS DE PAGOS..." class="mt-3 bg-transparent" />
            @endif
        </div>
        {{-- @can('admin.almacen.compras.pagos') --}}
        @if ($venta->typepayment->isContado() && $venta->sucursal_id == auth()->user()->sucursal_id)
            @if ($venta->cajamovimientos()->sum('amount') < $venta->total)
                <div class="w-full flex gap-2 pt-4 justify-end">
                    <x-button type="button" wire:loading.attr="disabled" wire:click="openmodal">
                        {{ __('REALIZAR PAGO') }}
                    </x-button>
                </div>
            @endif
        @endif
        {{-- @endcan --}}
    </x-form-card>


    @if ($venta->typepayment->isCredito())
        <x-form-card titulo="CUOTAS PAGO">

            @if (count($venta->cuotas) > 0)
                <div class="w-full flex flex-col gap-2">
                    <div class="w-full flex gap-2 flex-wrap justify-start">
                        @foreach ($venta->cuotas as $item)
                            <x-card-cuota class="w-full xs:w-60" :titulo="null" :detallepago="$item->cajamovimiento">
                                <p class="text-colorminicard text-xl font-semibold text-center">
                                    <small class="text-[10px] font-medium">{{ $venta->moneda->simbolo }}</small>
                                    {{ number_format($item->amount, 3, '.', ', ') }}
                                    <small class="text-[10px] font-medium">{{ $venta->moneda->currency }}</small>
                                </p>

                                <div class="w-full flex flex-wrap gap-2 justify-center">
                                    <x-span-text :text="'Cuota' . substr('000' . $item->cuota, -3)" class="leading-3 !tracking-normal" />
                                    <x-span-text :text="formatDate($item->expiredate, 'DD MMMM Y')" class="leading-3 !tracking-normal" />
                                </div>

                                <x-slot name="footer">
                                    @if (auth()->user()->sucursal_id == $venta->sucursal_id)
                                        @if ($item->cajamovimiento)
                                            <div class="w-full flex gap-2 flex-wrap items-end justify-between">
                                                <a href="{{ route('admin.payments.print', $item->cajamovimiento) }}"
                                                    target="_blank"
                                                    class="p-1.5 bg-neutral-900 text-white block rounded-lg transition-colors duration-150">
                                                    <svg class="w-4 h-4 block scale-110"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path
                                                            d="M7.35396 18C5.23084 18 4.16928 18 3.41349 17.5468C2.91953 17.2506 2.52158 16.8271 2.26475 16.3242C1.87179 15.5547 1.97742 14.5373 2.18868 12.5025C2.36503 10.8039 2.45321 9.95455 2.88684 9.33081C3.17153 8.92129 3.55659 8.58564 4.00797 8.35353C4.69548 8 5.58164 8 7.35396 8H16.646C18.4184 8 19.3045 8 19.992 8.35353C20.4434 8.58564 20.8285 8.92129 21.1132 9.33081C21.5468 9.95455 21.635 10.8039 21.8113 12.5025C22.0226 14.5373 22.1282 15.5547 21.7352 16.3242C21.4784 16.8271 21.0805 17.2506 20.5865 17.5468C19.8307 18 18.7692 18 16.646 18" />
                                                        <path
                                                            d="M17 8V6C17 4.11438 17 3.17157 16.4142 2.58579C15.8284 2 14.8856 2 13 2H11C9.11438 2 8.17157 2 7.58579 2.58579C7 3.17157 7 4.11438 7 6V8" />
                                                        <path
                                                            d="M13.9887 16L10.0113 16C9.32602 16 8.98337 16 8.69183 16.1089C8.30311 16.254 7.97026 16.536 7.7462 16.9099C7.57815 17.1904 7.49505 17.5511 7.32884 18.2724C7.06913 19.3995 6.93928 19.963 7.02759 20.4149C7.14535 21.0174 7.51237 21.5274 8.02252 21.7974C8.40513 22 8.94052 22 10.0113 22L13.9887 22C15.0595 22 15.5949 22 15.9775 21.7974C16.4876 21.5274 16.8547 21.0174 16.9724 20.4149C17.0607 19.963 16.9309 19.3995 16.6712 18.2724C16.505 17.5511 16.4218 17.1904 16.2538 16.9099C16.0297 16.536 15.6969 16.254 15.3082 16.1089C15.0166 16 14.674 16 13.9887 16Z" />
                                                    </svg>
                                                </a>
                                                @can('admin.ventas.payments.edit')
                                                    <x-button-delete wire:key="deletecuota_{{ $item->id }}"
                                                        onclick="confirmDeletePay({{ $item }})"
                                                        wire:loading.attr="disabled" />
                                                @endcan
                                            </div>
                                        @else
                                            <div class="w-full flex gap-2 flex-wrap items-end justify-between">
                                                @can('admin.ventas.payments.edit')
                                                    <x-button wire:click="pay({{ $item->id }})"
                                                        wire:key="pay_{{ $item->id }}"
                                                        wire:loading.attr="disabled">PAGAR</x-button>
                                                @endcan
                                                @can('admin.ventas.create')
                                                    <x-button-delete wire:key="deletecuota_{{ $item->id }}"
                                                        onclick="confirmDeleteCuota({{ $item }})"
                                                        wire:loading.attr="disabled" />
                                                @endcan
                                            </div>
                                        @endif
                                    @endif
                                </x-slot>
                            </x-card-cuota>
                        @endforeach
                    </div>

                    @if ($venta->cuotas()->doesntHave('cajamovimiento')->count())
                        @can('admin.ventas.create')
                            <div class="w-full">
                                <x-button wire:click="editcuotas" wire:loading.attr="disabled">
                                    EDITAR CUOTAS</x-button>
                            </div>
                        @endcan
                    @endif
                </div>
            @else
                @can('admin.ventas.create')
                    <div class="w-full flex flex-wrap xl:flex-nowrap gap-2">
                        <form wire:submit.prevent="calcularcuotas"
                            class="w-full xl:w-1/3 relative flex flex-col gap-2 bg-body p-3 rounded">
                            <div class="w-full">
                                <x-label value="Cuotas :" />
                                <x-input class="block w-full" type="number" min="1" step="1" max="10"
                                    wire:model.defer="countcuotas" />
                            </div>
                            <x-jet-input-error for="countcuotas" />

                            <div class="w-full flex justify-end mt-3">
                                <x-button type="submit" wire:loading.attr="disabled">
                                    CALCULAR</x-button>
                            </div>
                        </form>

                        <div class="w-full xl:w-2/3">
                            @if (count($cuotas) > 0)
                                <form wire:submit.prevent="updatecuotas" class="w-full">
                                    <div class="w-full flex flex-wrap gap-1">
                                        @foreach ($cuotas as $item)
                                            <x-card-cuota :titulo="substr('000' . $item['cuota'], -3)" class="w-full sm:w-48">

                                                <x-label value="Fecha pago :" />
                                                <x-input class="block w-full" type="date"
                                                    wire:model.defer="cuotas.{{ $loop->iteration - 1 }}.date" />

                                                <x-label value="Monto Cuota :" />
                                                <x-input class="block w-full numeric" type="number" min="1"
                                                    step="0.001"
                                                    wire:model.defer="cuotas.{{ $loop->iteration - 1 }}.amount" />

                                                <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.cuota" />
                                                <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.date" />
                                                <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.amount" />
                                            </x-card-cuota>
                                        @endforeach
                                    </div>
                                    <x-jet-input-error for="cuotas" />
                                    <x-jet-input-error for="amountcuotas" />

                                    <div class="w-full flex pt-4 justify-end">
                                        <x-button type="submit" wire:click="updatecuotas" wire:loading.attr="disabled">
                                            {{ __('REGISTRAR') }}</x-button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                @endcan
                @cannot('admin.ventas.create')
                    <p>
                        <x-span-text text="SIN PERMISOS PARA REGISTRAR CUOTAS DE PAGO..."
                            class="leading-3 !tracking-normal inline-block" />
                    </p>
                @endcannot
            @endif
        </x-form-card>
    @endif

    <x-form-card titulo="RESUMEN DE VENTA">
        <div class="w-full text-colorlabel">
            <p class="text-[10px]">EXONERADO : {{ $venta->moneda->simbolo }}
                <span class="font-bold text-xs">{{ number_format($venta->exonerado, 2, '.', ', ') }}</span>
            </p>

            <p class="text-[10px]">GRAVADO : {{ $venta->moneda->simbolo }}
                <span class="font-bold text-xs">{{ number_format($venta->gravado, 2, '.', ', ') }}</span>
            </p>

            <p class="text-[10px]">IGV : {{ $venta->moneda->simbolo }}
                <span class="font-bold text-xs">{{ number_format($venta->igv, 2, '.', ', ') }}</span>
            </p>

            <p class="text-[10px]">GRATUITO : {{ $venta->moneda->simbolo }}
                <span
                    class="font-bold text-xs">{{ number_format($venta->gratuito + $venta->igvgratuito, 2, '.', ', ') }}</span>
            </p>

            <p class="text-[10px]">DESCUENTOS : {{ $venta->moneda->simbolo }}
                <span class="font-bold text-xs">{{ number_format($venta->descuentos, 2, '.', ', ') }}</span>
            </p>

            <p class="text-[10px]">SUBTOTAL : {{ $venta->moneda->simbolo }}
                <span class="font-bold text-xl">{{ number_format($venta->total, 2, '.', ', ') }}</span>
            </p>

            <p class="text-[10px]">TOTAL PAGAR : {{ $venta->moneda->simbolo }}
                <span
                    class="font-bold text-xl">{{ number_format($venta->total - ($venta->gratuito + $venta->igvgratuito), 2, '.', ', ') }}</span>
                @if ($venta->increment > 0)
                    INC. + {{ formatDecimalOrInteger($venta->increment) }}%
                    ({{ number_format($amountincrement, 2, '.', ', ') }})
                @endif
            </p>

            <p class="text-[10px]">PENDIENTE : {{ $venta->moneda->simbolo }}
                <span
                    class="font-bold text-xl text-red-600">{{ number_format($venta->total - ($venta->gratuito + $venta->igvgratuito + $venta->cajamovimientos()->sum('amount')), 2, '.', ', ') }}</span>
            </p>
        </div>
    </x-form-card>

    <x-form-card titulo="RESUMEN PRODUCTOS">
        <div class="w-full" x-data="{ showForm: false }">
            @if (count($venta->tvitems))
                <div class="flex gap-2 flex-wrap justify-start mt-1">
                    @foreach ($venta->tvitems as $item)
                        @php
                            $image = $item->producto->getImageURL();
                        @endphp
                        <x-card-producto :image="$image" :name="$item->producto->name" :category="$item->gratuito == 1 ? 'GRATUITO' : null" :increment="$item->increment ?? null">
                            <h1 class="text-xl text-center font-semibold text-colortitleform">
                                <small class="text-[10px] font-medium">{{ $venta->moneda->simbolo }}</small>
                                {{ formatDecimalOrInteger($item->subtotal + $item->subtotaligv, 2, ', ') }}
                                <small class="text-[10px] font-medium">{{ $venta->moneda->currency }}</small>
                            </h1>

                            <div class="text-sm font-semibold mt-1">
                                <small class="text-[10px] font-medium">P. UNIT : </small>
                                {{ formatDecimalOrInteger($item->price + $item->igv, 2, ', ') }}
                                <small class="text-[10px] font-medium">{{ $venta->moneda->currency }}</small>
                            </div>

                            <div class="w-full flex flex-wrap gap-1 items-start mt-2 text-[10px]">

                                <x-span-text :text="formatDecimalOrInteger($item->cantidad) . ' ' . $item->producto->unit->name" class="leading-3 !tracking-normal" />

                                <x-span-text :text="$item->almacen->name" class="leading-3 !tracking-normal" />

                                @if (count($item->itemseries) == 1)
                                    <x-span-text :text="$item->itemseries->first()->serie->serie" class="leading-3 !tracking-normal" />
                                @endif
                            </div>

                            @if ($item->isPendingSerie())
                                <div class="w-full mt-2" x-data="{ addserie: false }">
                                    <form class="w-full inline-flex"
                                        wire:submit.prevent="saveserie({{ $item->id }})">
                                        <x-input class="block w-full"
                                            wire:model.defer="tvitem.{{ $item->id }}.serie" minlength="1"
                                            maxlength="255" />
                                        <x-button-add class="px-2" wire:loading.attr="disabled" type="submit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="11" cy="11" r="8" />
                                                <path d="m21 21-4.3-4.3" />
                                            </svg>
                                        </x-button-add>
                                    </form>
                                    <x-jet-input-error for="tvitem.{{ $item->id }}.tvitem_id" />
                                    <x-jet-input-error for="tvitem.{{ $item->id }}.serie" />
                                </div>
                            @endif

                            <div class="w-full mt-1">
                                @if (count($item->itemseries) > 1)
                                    <x-button @click="showForm = !showForm" class="whitespace-nowrap">
                                        {{ __('VER SERIES') }}</x-button>
                                @endif

                                <div x-show="showForm" x-transition class="w-full flex flex-wrap gap-1 rounded mt-1">
                                    @if (count($item->itemseries) > 1)
                                        @foreach ($item->itemseries as $itemserie)
                                            <span
                                                class="inline-flex items-center gap-1 text-[10px] bg-fondospancardproduct text-textspancardproduct p-1 rounded-lg">
                                                {{ $itemserie->serie->serie }}
                                                <x-button-delete onclick="confirmDeleteSerie({{ $itemserie }})"
                                                    wire:loading.attr="disabled" />
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </x-card-producto>
                    @endforeach
                </div>
            @endif
        </div>
    </x-form-card>


    @if (Module::isEnabled('Facturacion'))
        @if ($venta->comprobante)
            @if ($venta->comprobante->guia)
                <x-form-card titulo="GUÍA DE REMISIÓN">
                    <div class="w-full flex flex-col gap-2">
                        <p class="text-colorlabel text-2xl font-semibold">
                            {{ $venta->comprobante->guia->seriecompleta }}
                            <small
                                class="text-sm">{{ $venta->comprobante->guia->seriecomprobante->typecomprobante->descripcion }}</small>
                        </p>

                        <h1 class="text-colorlabel text-xs font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                            </svg>
                            CLIENTE :
                            <span class="font-medium inline-block text-colorsubtitleform">
                                {{ $venta->comprobante->guia->client->document }},
                                {{ $venta->comprobante->guia->client->name }}</span>
                        </h1>

                        <div class="">
                            <a href="{{ route('admin.facturacion.guias.print', $venta->comprobante->guia) }}"
                                target="_blank"
                                class="p-1.5 bg-red-800 text-white inline-block rounded-lg transition-colors duration-150">
                                <svg class="w-4 h-4 block scale-110 " xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path
                                        d="M7 18V15.5M7 15.5V14C7 13.5286 7 13.2929 7.15377 13.1464C7.30754 13 7.55503 13 8.05 13H8.75C9.47487 13 10.0625 13.5596 10.0625 14.25C10.0625 14.9404 9.47487 15.5 8.75 15.5H7ZM21 13H19.6875C18.8625 13 18.4501 13 18.1938 13.2441C17.9375 13.4882 17.9375 13.881 17.9375 14.6667V15.5M17.9375 18V15.5M17.9375 15.5H20.125M15.75 15.5C15.75 16.8807 14.5747 18 13.125 18C12.7979 18 12.6343 18 12.5125 17.933C12.2208 17.7726 12.25 17.448 12.25 17.1667V13.8333C12.25 13.552 12.2208 13.2274 12.5125 13.067C12.6343 13 12.7979 13 13.125 13C14.5747 13 15.75 14.1193 15.75 15.5Z" />
                                    <path
                                        d="M15 22H10.7273C7.46607 22 5.83546 22 4.70307 21.2022C4.37862 20.9736 4.09058 20.7025 3.8477 20.3971C3 19.3313 3 17.7966 3 14.7273V12.1818C3 9.21865 3 7.73706 3.46894 6.55375C4.22281 4.65142 5.81714 3.15088 7.83836 2.44135C9.09563 2 10.6698 2 13.8182 2C15.6173 2 16.5168 2 17.2352 2.2522C18.3902 2.65765 19.3012 3.5151 19.732 4.60214C20 5.27832 20 6.12494 20 7.81818V10" />
                                    <path
                                        d="M3 12C3 10.1591 4.49238 8.66667 6.33333 8.66667C6.99912 8.66667 7.78404 8.78333 8.43137 8.60988C9.00652 8.45576 9.45576 8.00652 9.60988 7.43136C9.78333 6.78404 9.66667 5.99912 9.66667 5.33333C9.66667 3.49238 11.1591 2 13 2" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </x-form-card>
            @endif
        @else
            @if ($venta->guia)
                <x-form-card titulo="GUÍA DE REMISIÓN">
                    <div class="w-full flex flex-col gap-2">
                        <p class="text-colorlabel text-2xl font-semibold">
                            {{ $venta->guia->seriecompleta }}
                            <small
                                class="text-sm">{{ $venta->guia->seriecomprobante->typecomprobante->descripcion }}</small>
                        </p>

                        <h1 class="text-colorlabel text-xs font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                            </svg>
                            CLIENTE :
                            <span class="font-medium inline-block text-colorsubtitleform">
                                {{ $venta->guia->client->document }},
                                {{ $venta->guia->client->name }}</span>
                        </h1>

                        <div class="">
                            <a href="{{ route('admin.facturacion.guias.print', $venta->guia) }}" target="_blank"
                                class="p-1.5 bg-red-800 text-white inline-block rounded-lg transition-colors duration-150">
                                <svg class="w-4 h-4 block scale-110 " xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path
                                        d="M7 18V15.5M7 15.5V14C7 13.5286 7 13.2929 7.15377 13.1464C7.30754 13 7.55503 13 8.05 13H8.75C9.47487 13 10.0625 13.5596 10.0625 14.25C10.0625 14.9404 9.47487 15.5 8.75 15.5H7ZM21 13H19.6875C18.8625 13 18.4501 13 18.1938 13.2441C17.9375 13.4882 17.9375 13.881 17.9375 14.6667V15.5M17.9375 18V15.5M17.9375 15.5H20.125M15.75 15.5C15.75 16.8807 14.5747 18 13.125 18C12.7979 18 12.6343 18 12.5125 17.933C12.2208 17.7726 12.25 17.448 12.25 17.1667V13.8333C12.25 13.552 12.2208 13.2274 12.5125 13.067C12.6343 13 12.7979 13 13.125 13C14.5747 13 15.75 14.1193 15.75 15.5Z" />
                                    <path
                                        d="M15 22H10.7273C7.46607 22 5.83546 22 4.70307 21.2022C4.37862 20.9736 4.09058 20.7025 3.8477 20.3971C3 19.3313 3 17.7966 3 14.7273V12.1818C3 9.21865 3 7.73706 3.46894 6.55375C4.22281 4.65142 5.81714 3.15088 7.83836 2.44135C9.09563 2 10.6698 2 13.8182 2C15.6173 2 16.5168 2 17.2352 2.2522C18.3902 2.65765 19.3012 3.5151 19.732 4.60214C20 5.27832 20 6.12494 20 7.81818V10" />
                                    <path
                                        d="M3 12C3 10.1591 4.49238 8.66667 6.33333 8.66667C6.99912 8.66667 7.78404 8.78333 8.43137 8.60988C9.00652 8.45576 9.45576 8.00652 9.60988 7.43136C9.78333 6.78404 9.66667 5.99912 9.66667 5.33333C9.66667 3.49238 11.1591 2 13 2" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </x-form-card>
            @endif
        @endif
    @endif

    <x-form-card titulo="OPCIONES">
        <div class="w-full flex gap-2 items-start justify-end">
            @if (Module::isEnabled('Facturacion'))
                @if ($venta->comprobante)
                    <x-link-button href="{{ route('admin.facturacion.print.a4', $venta->comprobante) }}"
                        target="_blank">IMPRIMIR A4</x-link-button>

                    <x-link-button href="{{ route('admin.facturacion.print.ticket', $venta->comprobante) }}"
                        target="_blank">IMPRIMIR TICKET</x-link-button>

                    @can('admin.facturacion.sunat')
                        @if ($venta->seriecomprobante->typecomprobante->isSunat())
                            @if (!$venta->comprobante->isSendSunat())
                                <x-button wire:click="enviarsunat" wire:loading.attr="disabled" class="inline-block">
                                    ENVIAR SUNAT</x-button>
                            @endif
                        @endif
                    @endcan
                @else
                    {{-- <x-button wire:click="generarcomprobante" wire:loading.attr="disabled"
                        class="inline-block">GENERAR COMPROBANTE</x-button> --}}

                    <x-link-button href="{{ route('admin.ventas.print.ticket', $venta) }}" target="_blank">
                        IMPRIMIR TICKET</x-link-button>
                @endif
            @else
                <x-link-button href="{{ route('admin.ventas.print.ticket', $venta) }}" target="_blank">
                    IMPRIMIR TICKET</x-link-button>
            @endif
        </div>
    </x-form-card>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Realizar pago cuota') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savepayment" class="flex flex-col gap-2" x-data="payventa">
                @if ($monthbox)
                    <p class="text-colorlabel text-md md:text-3xl font-semibold text-end mt-2 mb-5">
                        <small class="text-[10px] font-medium w-full block leading-3">CAJA MENSUAL</small>
                        {{ formatDate($monthbox->month, 'MMMM Y') }}
                        @if ($openbox)
                            <small class="w-full block font-medium text-xs">{{ $openbox->box->name }}</small>
                        @else
                            <small class="text-colorerror w-full block font-medium text-[10px] leading-3">
                                APERTURA DE CAJA DIARIA NO DISPONIBLE...
                            </small>
                        @endif
                    </p>
                @else
                    <p class="text-colorerror text-[10px] text-end">APERTURA DE CAJA MENSUAL NO DISPONIBLE...</p>
                @endif

                <div class="w-full">
                    <x-span-text :text="'Cuota' . substr('000' . $cuota->cuota, -3)" />
                    <p class="text-colorminicard text-3xl font-semibold">
                        <small class="text-[10px] font-medium">{{ $venta->moneda->simbolo }}</small>
                        {{ number_format($cuota->amount, 3, '.', ', ') }}
                        <small class="text-[10px] font-medium">{{ $venta->moneda->currency }}</small>
                    </p>
                </div>

                @if (count($monedas) > 1)
                    <div>
                        <x-label value="Moneda pago :" />
                        <div class="w-full grid grid-cols-1 xs:grid-cols-2 gap-2">
                            @foreach ($monedas as $item)
                                <x-input-radio class="py-2" for="moneda_{{ $item->id }}" :text="$item->currency">
                                    <input data-code="{{ $item->code }}" data-currency="{{ $item->currency }}"
                                        data-simbolo="{{ $item->simbolo }}" x-model="moneda_id"
                                        class="sr-only peer peer-disabled:opacity-25" type="radio"
                                        id="moneda_{{ $item->id }}" name="moneda" value="{{ $item->id }}"
                                        @change="changeMoneda" />
                                </x-input-radio>
                            @endforeach
                        </div>
                        <x-jet-input-error for="moneda_id" />
                    </div>
                @endif

                <div class="w-full" x-show="showtipocambio">
                    <div class="w-full">
                        <x-label value="Tipo cambio :" />
                        <x-input class="block w-full" x-model="tipocambio" type="number" placeholder="0.00"
                            onkeypress="return validarDecimal(event, 7)" step="0.001" min="0.001" />
                        <x-jet-input-error for="tipocambio" />
                    </div>

                    {{-- <span x-text="tipocambio"></span> --}}
                    <div class="w-full text-xs text-end text-colorsubtitleform font-semibold"
                        x-show="totalamount > 0">
                        <small class="inline-block" x-text="simbolo"></small>
                        <template x-if="totalamount > 0">
                            <h1 x-text="totalamount" class="text-2xl inline-block"></h1>
                        </template>
                        <template x-if="totalamount == null">
                            <small class="inline-block text-colorerror">SELECCIONAR TIPO DE MONEDA...</small>
                        </template>
                        <small class="inline-block" x-text="currency"></small>
                    </div>
                </div>

                <div class="w-full">
                    <x-label value="Método pago :" />
                    <div class="relative" x-init="CuotaMethodpayment">
                        <x-select class="block w-full" data-dropdown-parent="null" x-ref="selectcmp"
                            id="parentmpid">
                            <x-slot name="options">
                                @if (count($methodpayments))
                                    @foreach ($methodpayments as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                </div>

                <div class="w-full">
                    <x-label value="Otros (N° operación , descripción, etc) :" />
                    <x-input class="block w-full" wire:model.defer="detalle" />
                    <x-jet-input-error for="detalle" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="opencuotas" maxWidth="2xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Editar cuotas pago') }}
        </x-slot>

        <x-slot name="content">
            <div class="w-full relative">
                <h3 class="font-semibold text-3xl leading-normal text-colorlabel">
                    <small class="text-[10px] font-medium">MONTO : {{ $venta->moneda->simbolo }}</small>
                    {{ number_format($venta->total - $venta->paymentactual, 3, '.', ', ') }}
                    <small class="text-[10px] font-medium">{{ $venta->moneda->currency }}</small>
                </h3>

                <form wire:submit.prevent="updatecuotas" class="w-full flex flex-wrap justify-around gap-2">
                    @if (count($cuotas) > 0)
                        <div class="w-full flex flex-wrap gap-1">
                            @foreach ($cuotas as $item)
                                <x-card-cuota :titulo="substr('000' . $item['cuota'], -3)" class="w-full sm:w-48">
                                    @if (!is_null($item['cajamovimiento_id']))
                                        <p class="text-colorminicard text-xl font-semibold text-center">
                                            <small
                                                class="text-[10px] font-medium">{{ $venta->moneda->simbolo }}</small>
                                            {{ number_format($item['amount'], 3, '.', ', ') }}
                                            <small
                                                class="text-[10px] font-medium">{{ $venta->moneda->currency }}</small>
                                        </p>
                                    @endif

                                    <x-label value="Fecha pago :" class="mt-5" />
                                    @if (is_null($item['cajamovimiento_id']))
                                        <x-input class="block w-full" type="date"
                                            wire:model.lazy="cuotas.{{ $loop->iteration - 1 }}.date" />
                                    @else
                                        <x-disabled-text :text="\Carbon\Carbon::parse($item['date'])->format('d/m/Y')" />
                                    @endif


                                    @if (is_null($item['cajamovimiento_id']))
                                        <x-label value="Monto Cuota :" />
                                        <x-input class="block w-full" type="number" min="1" step="0.001"
                                            wire:model.lazy="cuotas.{{ $loop->iteration - 1 }}.amount" />
                                    @endif

                                    <x-jet-input-error for="cuotas.{{ $loop->iteration - 1 }}.cuota" />
                                    <x-jet-input-error for="cuotas.{{ $loop->iteration - 1 }}.date" />
                                    <x-jet-input-error for="cuotas.{{ $loop->iteration - 1 }}.amount" />

                                </x-card-cuota>
                            @endforeach
                        </div>
                        <x-jet-input-error for="cuotas" />
                        <x-jet-input-error for="amountcuotas" />

                        {{-- {{ $amountcuotas }} --}}
                        @can('admin.ventas.create')
                            <div class="w-full mt-3 gap-2 flex items-center justify-center">
                                <x-button wire:click="addnewcuota" wire:loading.attr="disabled">
                                    AGREGAR NUEVA CUOTA</x-button>
                                <x-button type="submit" wire:loading.attr="disable">
                                    CONFIRMAR CUOTAS</x-button>
                            </div>
                        @endcan

                    @endif
                </form>
            </div>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="openpay" maxWidth="xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Realizar pago venta') }}
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savepay" class="w-full flex flex-col gap-1" x-data="payventa">
                @if ($monthbox)
                    <p class="text-colorlabel text-md md:text-3xl font-semibold text-end mt-2 mb-5">
                        <small class="text-[10px] font-medium w-full block leading-3">CAJA MENSUAL</small>
                        {{ formatDate($monthbox->month, 'MMMM Y') }}
                        @if ($openbox)
                            <small class="w-full block font-medium text-xs">{{ $openbox->box->name }}</small>
                        @else
                            <small class="text-colorerror w-full block font-medium text-[10px] leading-3">
                                APERTURA DE CAJA DIARIA NO DISPONIBLE...
                            </small>
                        @endif
                    </p>
                @else
                    <p class="text-colorerror text-[10px] text-end">APERTURA DE CAJA MENSUAL NO DISPONIBLE...</p>
                @endif

                <div class="w-full">
                    <p class="text-colorlabel text-3xl font-semibold">
                        <small class="text-[10px] font-medium">{{ $venta->moneda->simbolo }}</small>
                        {{ number_format($venta->total, 3, '.', ', ') }}
                        <small class="text-[10px] font-medium">{{ $venta->moneda->currency }}</small>
                    </p>

                    @if ($pendiente < $venta->total)
                        <p class="text-colorerror text-2xl font-semibold">
                            <small class="text-[10px] font-medium">PENDIENTE </small>
                            {{ number_format($pendiente, 3, '.', ', ') }}
                            <small class="text-[10px] font-medium">{{ $venta->moneda->currency }}</small>
                        </p>
                    @endif
                </div>

                @if (count($monedas) > 1)
                    <div>
                        <x-label value="Moneda pago :" />
                        <div class="w-full grid grid-cols-1 xs:grid-cols-2 gap-2">
                            @foreach ($monedas as $item)
                                <x-input-radio class="py-2" for="paymoneda_{{ $item->id }}"
                                    :text="$item->currency">
                                    <input data-code="{{ $item->code }}" data-currency="{{ $item->currency }}"
                                        data-simbolo="{{ $item->simbolo }}" x-model="moneda_id"
                                        class="sr-only peer peer-disabled:opacity-25" type="radio"
                                        id="paymoneda_{{ $item->id }}" name="moneda"
                                        value="{{ $item->id }}" @change="changeMoneda" />
                                </x-input-radio>
                            @endforeach
                        </div>
                        <x-jet-input-error for="moneda_id" />
                    </div>
                @endif

                <div class="w-full">
                    <x-label value="Monto pagar :" />
                    <x-input class="block w-full numeric" x-model="paymentactual" placeholder="0.00" type="number"
                        min="0" step="0.001" onkeypress="return validarDecimal(event, 12)" />
                    <x-jet-input-error for="paymentactual" />
                </div>

                <div class="w-full" x-show="showtipocambio">
                    <div class="w-full">
                        <x-label value="Tipo cambio :" />
                        <x-input class="block w-full" x-model="tipocambio" type="number" placeholder="0.00"
                            onkeypress="return validarDecimal(event, 7)" step="0.001" min="0.001" />
                        <x-jet-input-error for="tipocambio" />
                    </div>

                    {{-- <span x-text="tipocambio"></span> --}}
                    <div class="w-full text-xs text-end text-neutral-500 font-semibold" x-show="totalamount > 0">
                        <small class="inline-block" x-text="simbolo"></small>
                        <template x-if="totalamount > 0">
                            <h1 x-text="totalamount" class="text-2xl inline-block"></h1>
                        </template>
                        <template x-if="totalamount == null">
                            <small class="inline-block text-colorerror">SELECCIONAR TIPO DE MONEDA...</small>
                        </template>
                        <small class="inline-block" x-text="currency"></small>
                    </div>
                </div>

                <div class="w-full">
                    <x-label value="Método pago :" />
                    <div class="relative" x-init="MethodpaymentVenta" id="parentmethodpayv">
                        <x-select class="block w-full" x-ref="selectmpv" id="methodpayv"
                            data-dropdown-parent="null">
                            <x-slot name="options">
                                @if (count($methodpayments))
                                    @foreach ($methodpayments as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="methodpayment_id" />
                </div>

                <div class="w-full">
                    <x-label value="Otros (N° operación , Banco, etc) :" />
                    <x-input class="block w-full" wire:model.defer="detalle" />
                    <x-jet-input-error for="detalle" />
                    <x-jet-input-error for="totalamount" />
                    <x-jet-input-error for="tipocambio" />
                    <x-jet-input-error for="concept.id" />
                    <x-jet-input-error for="openbox.id" />
                    <x-jet-input-error for="monthbox.id" />
                </div>

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('Save') }}</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('payventa', () => ({
                paymentactual: @entangle('paymentactual').defer,
                methodpayment_id: @entangle('methodpayment_id').defer,
                moneda_id: @entangle('moneda_id').defer,
                codemonedaventa: '{{ $venta->moneda->code }}',
                currency: '{{ $venta->moneda->currency }}',
                code: '{{ $venta->moneda->code }}',
                simbolo: '{{ $venta->moneda->simbolo }}',
                totalamount: @entangle('totalamount').defer,
                tipocambio: @entangle('tipocambio').defer,
                showtipocambio: false,

                init() {
                    this.$watch("tipocambio", (value) => {
                        this.tipocambio = value > 0 ? value : 1;

                        if (this.codemonedaventa != this.code) {
                            this.convertToMoneda();
                        }
                    });

                    this.$watch("paymentactual", (value) => {
                        this.paymentactual = value > 0 ? value : 0;

                        if (this.codemonedaventa != this.code) {
                            this.convertToMoneda();
                        }
                    });
                },
                changeMoneda(event) {
                    const rdomoneda = event.target;
                    this.code = rdomoneda.getAttribute('data-code');
                    this.currency = rdomoneda.getAttribute('data-currency');
                    this.simbolo = rdomoneda.getAttribute('data-simbolo');
                    if (this.code != this.codemonedaventa) {
                        this.convertToMoneda();
                        this.showtipocambio = true;

                    } else {
                        this.totalamount = 0;
                        this.showtipocambio = false;
                    }
                },
                convertToMoneda() {
                    // this.tipocambio > 0 ? this.tipocambio : 1;
                    // this.paymentactual > 0 ? this.paymentactual : 0;
                    if (this.code == 'PEN') {
                        this.totalamount = toDecimal(this.tipocambio * this.paymentactual);
                    } else {
                        this.totalamount = toDecimal(this.paymentactual / this.tipocambio);
                    }
                }
            }))
        })


        function MethodpaymentVenta() {
            this.selectMPV = $(this.$refs.selectmpv).select2();
            this.selectMPV.val(this.methodpayment_id).trigger("change");
            this.selectMPV.on("select2:select", (event) => {
                this.methodpayment_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("methodpayment_id", (value) => {
                this.selectMPV.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectMPV.select2().val(this.methodpayment_id).trigger('change');
            });
        }

        function CuotaMethodpayment() {
            this.selectCM = $(this.$refs.selectcmp).select2();
            this.selectCM.val(this.methodpayment_id).trigger("change");
            this.selectCM.on("select2:select", (event) => {
                this.methodpayment_id = event.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });
            this.$watch("methodpayment_id", (value) => {
                this.selectCM.val(value).trigger("change");
            });
            Livewire.hook('message.processed', () => {
                this.selectCM.select2().val(this.methodpayment_id).trigger('change');
            });
        }

        function confirmDelete(venta) {
            swal.fire({
                title: 'Desea anular venta con serie ' + venta.seriecompleta + ' ?',
                text: "Se eliminará un registro de la base de datos, incluyendo sus items del registro.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(venta.id);
                }
            })
        }

        function confirmDeletePay(paycuota) {
            const cuotastr = '000' + paycuota.cuota;
            swal.fire({
                title: 'Desea anular el pago de la Cuota' + cuotastr.substr(-3) + '?',
                text: "Se eliminará un registro de pago de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deletepaycuota(paycuota.id);
                }
            })
        }

        function confirmDeleteCuota(cuota) {
            const cuotastr = '000' + cuota.cuota;
            swal.fire({
                title: 'Desea eliminar la Cuota' + cuotastr.substr(-3) + '?',
                text: "Se eliminará un registro de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deletecuota(cuota.id);
                }
            })
        }

        function confirmDeleteSerie(serie) {
            swal.fire({
                title: 'Eliminar serie de venta ' + serie.serie.serie + ' ?',
                text: "Se eliminará un registro de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deleteserie(serie.id);
                }
            })
        }

        function confirmDeletePayment(payment_id) {
            swal.fire({
                title: 'ELIMINAR PAGO DE VENTA ?',
                text: "Se eliminará un registro de pago de la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0FB9B9',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.deletepay(payment_id);
                }
            })
        }
    </script>
</div>
