<div class="w-full flex flex-col gap-8" x-data="{ loadingventa: false }">

    <x-simple-card class="flex flex-col gap-1 rounded-md cursor-default p-3">
        <div class="w-full sm:flex sm:gap-3">
            <div class="w-full text-colortitleform">
                <h1 class="font-semibold text-sm leading-4">
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

    @if ($venta->typepayment->isContado())
        <x-form-card titulo="RESUMEN PAGO" class="flex flex-col gap-1 rounded-md cursor-default p-3">
            <div class="w-full text-colortitleform">
                <h1 class="font-semibold text-sm leading-4">
                    <span class="text-3xl">{{ $venta->cajamovimiento->monthbox->name }}</span>
                    <span class="font-medium"> {{ $venta->cajamovimiento->openbox->box->name }}</span>
                </h1>

                <h1 class="font-medium text-xs leading-4">
                    FORMA PAGO : {{ $venta->cajamovimiento->methodpayment->name }}
                </h1>
                <h1 class="font-medium text-xs leading-4">
                    {{ $venta->cajamovimiento->detalle }}
                </h1>
            </div>
        </x-form-card>
    @endif

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
                                                <x-mini-button>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path
                                                            d="M17.571 18H20.4a.6.6 0 00.6-.6V11a4 4 0 00-4-4H7a4 4 0 00-4 4v6.4a.6.6 0 00.6.6h2.829M8 7V3.6a.6.6 0 01.6-.6h6.8a.6.6 0 01.6.6V7" />
                                                        <path
                                                            d="M6.098 20.315L6.428 18l.498-3.485A.6.6 0 017.52 14h8.96a.6.6 0 01.594.515L17.57 18l.331 2.315a.6.6 0 01-.594.685H6.692a.6.6 0 01-.594-.685z" />
                                                        <path d="M17 10.01l.01-.011" />
                                                    </svg>
                                                </x-mini-button>
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

            <div wire:loading wire:loading.flex
                wire:target="calcularcuotas, deletecuota, deletepaycuota, savepayment, updatecuotas, delete"
                class="loading-overlay rounded hidden">
                <x-loading-next />
            </div>
        </x-form-card>
    @endif

    <x-form-card titulo="RESUMEN DE VENTA">
        <div class="w-full text-colortitleform">
            <p class="text-[10px]">
                TOTAL EXONERADO : {{ $venta->moneda->simbolo }}
                <span class="font-bold text-xs">{{ number_format($venta->exonerado, 3, '.', ', ') }}</span>
            </p>

            <p class="text-[10px]">TOTAL GRAVADO : {{ $venta->moneda->simbolo }}
                <span class="font-bold text-xs">{{ number_format($venta->gravado, 3, '.', ', ') }}</span>
            </p>

            <p class="text-[10px]">
                TOTAL INAFECTO : {{ $venta->moneda->simbolo }}
                <span class="font-bold text-xs">{{ number_format($venta->inafecto, 3, '.', ', ') }}</span>
            </p>

            <p class="text-[10px]">
                TOTAL IGV : {{ $venta->moneda->simbolo }}
                <span class="font-bold text-xs">{{ number_format($venta->igv, 3, '.', ', ') }}</span>
            </p>

            <p class="text-[10px]">TOTAL GRATUITOS : {{ $venta->moneda->simbolo }}
                <span
                    class="font-bold text-xs">{{ number_format($venta->gratuito + $venta->igvgratuito, 3, '.', ', ') }}</span>
            </p>

            <p class="text-[10px]">TOTAL DESCUENTOS : {{ $venta->moneda->simbolo }}
                <span class="font-bold text-xs">{{ number_format($venta->descuentos, 3, '.', ', ') }}</span>
            </p>

            <p class="text-[10px]">TOTAL VENTA : {{ $venta->moneda->simbolo }}
                <span class="font-bold text-xl">{{ number_format($venta->total, 3, '.', ', ') }}</span>
            </p>

            @if ($venta->typepayment->paycuotas)
                <p class="text-[10px]">SALDO PENDIENTE
                    @php
                        $amountIncr = number_format(
                            (($venta->total - $venta->paymentactual) * $venta->increment) / (100 + $venta->increment),
                            3,
                            '.',
                            '',
                        );
                    @endphp

                    @if ($venta->increment > 0)
                        {{ number_format($venta->total - $venta->paymentactual - $amountIncr, 3, '.', ', ') }}
                        + {{ formatDecimalOrInteger($venta->increment) }}%
                        ({{ number_format($amountIncr, 3, '.', ', ') }})
                    @endif
                    : {{ $venta->moneda->simbolo }}
                    <span
                        class="font-bold text-xl">{{ number_format($venta->total - $venta->paymentactual, 3, '.', ', ') }}</span>
                </p>
            @endif
        </div>
    </x-form-card>

    <x-form-card titulo="RESUMEN PRODUCTOS">
        <div class="w-full" x-data="{ showForm: false }">
            @if (count($venta->tvitems))
                <div class="flex gap-2 flex-wrap justify-start mt-1">
                    @foreach ($venta->tvitems as $item)
                        @php
                            $image = null;
                            if (count($item->producto->images) > 0) {
                                if ($item->producto->images()->default()->exists()) {
                                    $image = asset(
                                        'storage/productos/' . $item->producto->images()->default()->first()->url,
                                    );
                                } else {
                                    $image = asset('storage/productos/' . $item->producto->images->first()->url);
                                }
                            }
                        @endphp
                        <x-card-producto :image="$image" :name="$item->producto->name" :category="$item->gratuito == 1 ? 'GRATUITO' : null" :increment="$item->increment ?? null">
                            <div class="w-full flex flex-wrap gap-1 justify-center mt-1">
                                <x-label-price>
                                    <span>
                                        {{ $venta->moneda->simbolo }}
                                        {{ number_format($item->subtotal + $item->subtotaligv, 3, '.', ', ') }}
                                        {{ $venta->moneda->currency }}
                                    </span>
                                </x-label-price>
                            </div>

                            <div class="w-full flex flex-wrap gap-1 items-start mt-2 text-[10px]">
                                <x-span-text :text="'P.V UNIT : ' .
                                    $venta->moneda->simbolo .
                                    ' ' .
                                    number_format($item->price, 3, '.', ', ')" class="leading-3 !tracking-normal" />

                                @if ($item->igv > 0)
                                    <x-span-text :text="'IGV UNIT : ' .
                                        $venta->moneda->simbolo .
                                        ' ' .
                                        number_format($item->igv, 3, '.', ', ')" class="leading-3 !tracking-normal" />
                                @endif

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

        <div x-show="loadingventa" wire:loading.flex wire:target="loadProductos, delete"
            class="loading-overlay rounded">
            <x-loading-next />
        </div>
    </x-form-card>


    @if (Module::isEnabled('Facturacion'))
        @if ($venta->comprobante)
            @if ($venta->comprobante->guia)
                <x-form-card titulo="GUÍA DE REMISIÓN">
                    <div class="w-full flex flex-col gap-2">
                        <p class="text-colorminicard text-2xl font-semibold">
                            {{ $venta->comprobante->guia->seriecompleta }}
                            <small
                                class="text-sm">{{ $venta->comprobante->guia->seriecomprobante->typecomprobante->descripcion }}</small>
                        </p>

                        <h1 class="text-colorminicard text-xs font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                            </svg>
                            CLIENTE :
                            <span class="font-medium inline-block">
                                {{ $venta->comprobante->guia->client->document }},
                                {{ $venta->comprobante->guia->client->name }}</span>
                        </h1>

                        <div class="flex items-center justify-start gap-1">
                            <button
                                class="p-1.5 bg-red-800 text-white block rounded-lg transition-colors duration-150">
                                <svg class="w-4 h-4 block scale-110 " xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path
                                        d="M7 18V15.5M7 15.5V14C7 13.5286 7 13.2929 7.15377 13.1464C7.30754 13 7.55503 13 8.05 13H8.75C9.47487 13 10.0625 13.5596 10.0625 14.25C10.0625 14.9404 9.47487 15.5 8.75 15.5H7ZM21 13H19.6875C18.8625 13 18.4501 13 18.1938 13.2441C17.9375 13.4882 17.9375 13.881 17.9375 14.6667V15.5M17.9375 18V15.5M17.9375 15.5H20.125M15.75 15.5C15.75 16.8807 14.5747 18 13.125 18C12.7979 18 12.6343 18 12.5125 17.933C12.2208 17.7726 12.25 17.448 12.25 17.1667V13.8333C12.25 13.552 12.2208 13.2274 12.5125 13.067C12.6343 13 12.7979 13 13.125 13C14.5747 13 15.75 14.1193 15.75 15.5Z" />
                                    <path
                                        d="M15 22H10.7273C7.46607 22 5.83546 22 4.70307 21.2022C4.37862 20.9736 4.09058 20.7025 3.8477 20.3971C3 19.3313 3 17.7966 3 14.7273V12.1818C3 9.21865 3 7.73706 3.46894 6.55375C4.22281 4.65142 5.81714 3.15088 7.83836 2.44135C9.09563 2 10.6698 2 13.8182 2C15.6173 2 16.5168 2 17.2352 2.2522C18.3902 2.65765 19.3012 3.5151 19.732 4.60214C20 5.27832 20 6.12494 20 7.81818V10" />
                                    <path
                                        d="M3 12C3 10.1591 4.49238 8.66667 6.33333 8.66667C6.99912 8.66667 7.78404 8.78333 8.43137 8.60988C9.00652 8.45576 9.45576 8.00652 9.60988 7.43136C9.78333 6.78404 9.66667 5.99912 9.66667 5.33333C9.66667 3.49238 11.1591 2 13 2" />
                                </svg>
                            </button>
                            <button
                                class="p-1.5 bg-neutral-900 text-white block rounded-lg transition-colors duration-150">
                                <svg class="w-4 h-4 block scale-110" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path
                                        d="M7.35396 18C5.23084 18 4.16928 18 3.41349 17.5468C2.91953 17.2506 2.52158 16.8271 2.26475 16.3242C1.87179 15.5547 1.97742 14.5373 2.18868 12.5025C2.36503 10.8039 2.45321 9.95455 2.88684 9.33081C3.17153 8.92129 3.55659 8.58564 4.00797 8.35353C4.69548 8 5.58164 8 7.35396 8H16.646C18.4184 8 19.3045 8 19.992 8.35353C20.4434 8.58564 20.8285 8.92129 21.1132 9.33081C21.5468 9.95455 21.635 10.8039 21.8113 12.5025C22.0226 14.5373 22.1282 15.5547 21.7352 16.3242C21.4784 16.8271 21.0805 17.2506 20.5865 17.5468C19.8307 18 18.7692 18 16.646 18" />
                                    <path
                                        d="M17 8V6C17 4.11438 17 3.17157 16.4142 2.58579C15.8284 2 14.8856 2 13 2H11C9.11438 2 8.17157 2 7.58579 2.58579C7 3.17157 7 4.11438 7 6V8" />
                                    <path
                                        d="M13.9887 16L10.0113 16C9.32602 16 8.98337 16 8.69183 16.1089C8.30311 16.254 7.97026 16.536 7.7462 16.9099C7.57815 17.1904 7.49505 17.5511 7.32884 18.2724C7.06913 19.3995 6.93928 19.963 7.02759 20.4149C7.14535 21.0174 7.51237 21.5274 8.02252 21.7974C8.40513 22 8.94052 22 10.0113 22L13.9887 22C15.0595 22 15.5949 22 15.9775 21.7974C16.4876 21.5274 16.8547 21.0174 16.9724 20.4149C17.0607 19.963 16.9309 19.3995 16.6712 18.2724C16.505 17.5511 16.4218 17.1904 16.2538 16.9099C16.0297 16.536 15.6969 16.254 15.3082 16.1089C15.0166 16 14.674 16 13.9887 16Z" />
                                </svg>
                            </button>

                        </div>
                    </div>
                </x-form-card>
            @endif
        @else
            @if ($venta->guia)
                <x-form-card titulo="GUÍA DE REMISIÓN">
                    <div class="w-full flex flex-col gap-2">
                        <p class="text-colorminicard text-2xl font-semibold">
                            {{ $venta->guia->seriecompleta }}
                            <small
                                class="text-sm">{{ $venta->guia->seriecomprobante->typecomprobante->descripcion }}</small>
                        </p>

                        <h1 class="text-colorminicard text-xs font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block text-next-500"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21.8606 5.39176C22.2875 6.49635 21.6888 7.2526 20.5301 7.99754C19.5951 8.5986 18.4039 9.24975 17.1417 10.363C15.9044 11.4543 14.6968 12.7687 13.6237 14.0625C12.5549 15.351 11.6465 16.586 11.0046 17.5005C10.5898 18.0914 10.011 18.9729 10.011 18.9729C9.60281 19.6187 8.86895 20.0096 8.08206 19.9998C7.295 19.99 6.57208 19.5812 6.18156 18.9251C5.18328 17.248 4.41296 16.5857 4.05891 16.3478C3.11158 15.7112 2 15.6171 2 14.1335C2 12.9554 2.99489 12.0003 4.22216 12.0003C5.08862 12.0323 5.89398 12.373 6.60756 12.8526C7.06369 13.1591 7.54689 13.5645 8.04948 14.0981C8.63934 13.2936 9.35016 12.3653 10.147 11.4047C11.3042 10.0097 12.6701 8.51309 14.1349 7.22116C15.5748 5.95115 17.2396 4.76235 19.0042 4.13381C20.1549 3.72397 21.4337 4.28718 21.8606 5.39176Z" />
                            </svg>
                            CLIENTE :
                            <span class="font-medium inline-block">
                                {{ $venta->guia->client->document }},
                                {{ $venta->guia->client->name }}</span>
                        </h1>

                        <div class="flex items-center justify-start gap-1">
                            <button
                                class="p-1.5 bg-red-800 text-white block rounded-lg transition-colors duration-150">
                                <svg class="w-4 h-4 block scale-110 " xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path
                                        d="M7 18V15.5M7 15.5V14C7 13.5286 7 13.2929 7.15377 13.1464C7.30754 13 7.55503 13 8.05 13H8.75C9.47487 13 10.0625 13.5596 10.0625 14.25C10.0625 14.9404 9.47487 15.5 8.75 15.5H7ZM21 13H19.6875C18.8625 13 18.4501 13 18.1938 13.2441C17.9375 13.4882 17.9375 13.881 17.9375 14.6667V15.5M17.9375 18V15.5M17.9375 15.5H20.125M15.75 15.5C15.75 16.8807 14.5747 18 13.125 18C12.7979 18 12.6343 18 12.5125 17.933C12.2208 17.7726 12.25 17.448 12.25 17.1667V13.8333C12.25 13.552 12.2208 13.2274 12.5125 13.067C12.6343 13 12.7979 13 13.125 13C14.5747 13 15.75 14.1193 15.75 15.5Z" />
                                    <path
                                        d="M15 22H10.7273C7.46607 22 5.83546 22 4.70307 21.2022C4.37862 20.9736 4.09058 20.7025 3.8477 20.3971C3 19.3313 3 17.7966 3 14.7273V12.1818C3 9.21865 3 7.73706 3.46894 6.55375C4.22281 4.65142 5.81714 3.15088 7.83836 2.44135C9.09563 2 10.6698 2 13.8182 2C15.6173 2 16.5168 2 17.2352 2.2522C18.3902 2.65765 19.3012 3.5151 19.732 4.60214C20 5.27832 20 6.12494 20 7.81818V10" />
                                    <path
                                        d="M3 12C3 10.1591 4.49238 8.66667 6.33333 8.66667C6.99912 8.66667 7.78404 8.78333 8.43137 8.60988C9.00652 8.45576 9.45576 8.00652 9.60988 7.43136C9.78333 6.78404 9.66667 5.99912 9.66667 5.33333C9.66667 3.49238 11.1591 2 13 2" />
                                </svg>
                            </button>
                            <button
                                class="p-1.5 bg-neutral-900 text-white block rounded-lg transition-colors duration-150">
                                <svg class="w-4 h-4 block scale-110" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path
                                        d="M7.35396 18C5.23084 18 4.16928 18 3.41349 17.5468C2.91953 17.2506 2.52158 16.8271 2.26475 16.3242C1.87179 15.5547 1.97742 14.5373 2.18868 12.5025C2.36503 10.8039 2.45321 9.95455 2.88684 9.33081C3.17153 8.92129 3.55659 8.58564 4.00797 8.35353C4.69548 8 5.58164 8 7.35396 8H16.646C18.4184 8 19.3045 8 19.992 8.35353C20.4434 8.58564 20.8285 8.92129 21.1132 9.33081C21.5468 9.95455 21.635 10.8039 21.8113 12.5025C22.0226 14.5373 22.1282 15.5547 21.7352 16.3242C21.4784 16.8271 21.0805 17.2506 20.5865 17.5468C19.8307 18 18.7692 18 16.646 18" />
                                    <path
                                        d="M17 8V6C17 4.11438 17 3.17157 16.4142 2.58579C15.8284 2 14.8856 2 13 2H11C9.11438 2 8.17157 2 7.58579 2.58579C7 3.17157 7 4.11438 7 6V8" />
                                    <path
                                        d="M13.9887 16L10.0113 16C9.32602 16 8.98337 16 8.69183 16.1089C8.30311 16.254 7.97026 16.536 7.7462 16.9099C7.57815 17.1904 7.49505 17.5511 7.32884 18.2724C7.06913 19.3995 6.93928 19.963 7.02759 20.4149C7.14535 21.0174 7.51237 21.5274 8.02252 21.7974C8.40513 22 8.94052 22 10.0113 22L13.9887 22C15.0595 22 15.5949 22 15.9775 21.7974C16.4876 21.5274 16.8547 21.0174 16.9724 20.4149C17.0607 19.963 16.9309 19.3995 16.6712 18.2724C16.505 17.5511 16.4218 17.1904 16.2538 16.9099C16.0297 16.536 15.6969 16.254 15.3082 16.1089C15.0166 16 14.674 16 13.9887 16Z" />
                                </svg>
                            </button>

                        </div>
                    </div>
                </x-form-card>
            @endif
        @endif
    @endif

    <x-form-card titulo="OPCIONES">
        <div class="w-full flex gap-2 items-start justify-end">
            <x-link-button href="{{ route('admin.ventas.print.a4', $venta) }}">IMPRIMIR A4</x-link-button>
            <x-button>IMPRIMIR TICKET</x-button>
            @if (Module::isEnabled('Facturacion'))
                @can('admin.facturacion.sunat')
                    @if ($venta->seriecomprobante->typecomprobante->sendsunat == 1)
                        <x-button>ENVIAR</x-button>
                    @endif
                @endcan
            @endif
        </div>
    </x-form-card>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Realizar pago cuota') }}
            <x-button-close-modal wire:click="$toggle('open')" wire:loading.attr="disabled" />
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savepayment" class="flex flex-col gap-2">
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

                <div class="w-full">
                    <x-label value="Método pago :" />
                    <div class="relative" x-data="{ methodpayment_id: @entangle('methodpayment_id').defer }" x-init="selectCuotaMethodpayment" wire:ignore>
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
                </div>

                @if ($errors->any())
                    <div class="">
                        @foreach ($errors->keys() as $key)
                            <x-jet-input-error :for="$key" />
                        @endforeach
                    </div>
                @endif

                <div class="w-full flex pt-4 justify-end">
                    <x-button type="submit" wire:loading.attr="disabled">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="opencuotas" maxWidth="2xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Editar cuotas pago') }}
            <x-button-close-modal wire:click="$toggle('opencuotas')" wire:loading.attr="disabled" />
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

                <div wire:loading wire:loading.flex wire:target="editcuotas, updatecuotas, addnewcuota"
                    class="loading-overlay rounded hidden">
                    <x-loading-next />
                </div>
            </div>
        </x-slot>
    </x-jet-dialog-modal>

    <script>
        function selectCuotaMethodpayment() {
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
    </script>
</div>
