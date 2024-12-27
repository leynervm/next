<div class="w-full flex flex-col gap-8" x-data="{
    istransferencia: false,
    detalle: @entangle('detalle').defer,
    detallepaycuota: @entangle('detalle').defer,
    showtipocambio: false,
    reset() {
        this.istransferencia = false;
        this.detalle = '';
        this.detallepaycuota = '';
        this.showtipocambio = false;
    }
}">
    <div wire:loading.flex class="loading-overlay hidden fixed">
        <x-loading-next />
    </div>

    <x-simple-card class="flex flex-col gap-1 rounded-md cursor-default p-3">
        <div class="w-full grid grid-cols-1 sm:grid-cols-2">
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
                    @if ($venta->moneda->isDolar())
                        / {{ number_format($venta->tipocambio, 3, '.', '') }}
                    @endif
                </h1>

                <h1 class="text-colorsubtitleform font-medium text-xs">
                    SUCURSAL: {{ $venta->sucursal->name }}
                    @if ($venta->sucursal->trashed())
                        <x-span-text text="NO DISPONIBLE" class="leading-3 !tracking-normal inline-block" />
                    @endif
                </h1>

                <h1 class="font-medium text-xs">
                    OBSERVACIONES : {{ $venta->observaciones }}
                </h1>
            </div>
            <div class="w-full text-colorlabel mt-3 sm:mt-0">
                <table class="w-full table text-[10px]">
                    <tr>
                        <td class="sm:text-end w-32 sm:w-auto">EXONERADO :</td>
                        <td class="sm:text-end sm:w-40">
                            <span
                                class="font-semibold text-sm">{{ number_format($venta->exonerado, 2, '.', ', ') }}</span>
                            <small>{{ $venta->moneda->currency }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td class="sm:text-end">GRAVADO :</td>
                        <td class="sm:text-end">
                            <span class="font-semibold text-sm">
                                {{ number_format($venta->gravado, 2, '.', ', ') }}</span>
                            <small>{{ $venta->moneda->currency }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td class="sm:text-end">IGV :</td>
                        <td class="sm:text-end">
                            <span class="font-semibold text-sm"> {{ number_format($venta->igv, 2, '.', ', ') }}</span>
                            <small>{{ $venta->moneda->currency }}</small>
                        </td>
                    </tr>

                    @if ($venta->gratuito > 0)
                        <tr>
                            <td class="sm:text-end">GRATUITO :</td>
                            <td class="sm:text-end">
                                <span class="font-semibold text-sm">
                                    {{ number_format($venta->gratuito + $venta->igvgratuito, 2, '.', ', ') }}</span>
                                <small>{{ $venta->moneda->currency }}</small>
                            </td>
                        </tr>
                    @endif

                    {{-- @if ($venta->descuentos > 0)
                        <tr>
                            <td class="sm:text-end">DESCUENTOS :</td>
                            <td class="sm:text-end">
                                <span class="font-semibold text-sm">
                                    {{ number_format($venta->descuentos, 2, '.', ', ') }}</span>
                                <small>{{ $venta->moneda->currency }}</small>
                            </td>
                        </tr>
                    @endif --}}

                    @if ($venta->gratuito + $venta->descuentos > 0)
                        <tr>
                            <td class="sm:text-end">SUBTOTAL :</td>
                            <td class="sm:text-end">
                                <span class="font-semibold text-sm">
                                    {{ number_format($venta->total, 2, '.', ', ') }}</span>
                                <small>{{ $venta->moneda->currency }}</small>
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td class="sm:text-end">TOTAL PAGAR :</td>
                        <td class="sm:text-end">
                            <span class="font-semibold text-xl">
                                {{ number_format($venta->total, 2, '.', ', ') }}</span>
                            <small>{{ $venta->moneda->currency }}</small>

                            @if ($venta->increment > 0)
                                <br>
                                INC. + {{ decimalOrInteger($venta->increment) }}%
                                ({{ number_format($amountincrement, 2, '.', ', ') }})
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="sm:text-end">PENDIENTE :</td>
                        <td class="sm:text-end">
                            <span class="font-semibold text-xl text-red-600">
                                {{ number_format($venta->total - $venta->cajamovimientos()->sum('amount'), 2, '.', ', ') }}</span>
                            <small>{{ $venta->moneda->currency }}</small>
                        </td>
                    </tr>
                </table>
            </div>
        </div>


        <div class="w-full flex flex-col xs:flex-row gap-1 items-end justify-between mt-4">
            <div class="flex flex-wrap gap-1">
                @if (Module::isEnabled('Facturacion'))
                    @if ($venta->comprobante)
                        <x-link-button href="{{ route('admin.facturacion.print.a4', $venta->comprobante) }}"
                            target="_blank">IMPRIMIR A4</x-link-button>

                        <x-link-button href="{{ route('admin.facturacion.print.ticket', $venta->comprobante) }}"
                            target="_blank">IMPRIMIR TICKET</x-link-button>

                        {{-- @can('admin.facturacion.sunat')
                                @if ($venta->seriecomprobante->typecomprobante->isSunat())
                                    @if (!$venta->comprobante->isSendSunat())
                                        <x-button wire:click="enviarsunat" wire:loading.attr="disabled" class="inline-block">
                                            ENVIAR SUNAT</x-button>
                                    @endif
                                @endif
                            @endcan --}}
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
        </div>

    </x-simple-card>

    <x-form-card titulo="RESUMEN PAGO" class="flex flex-col gap-1 rounded-md cursor-default p-3">
        <div class="w-full text-colortitleform">
            @if (count($venta->cajamovimientos) > 0)
                <div
                    class="w-full grid grid-cols-[repeat(auto-fill,minmax(12rem,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(15rem,1fr))] gap-2">
                    @foreach ($venta->cajamovimientos as $item)
                        <x-card-payment-box :cajamovimiento="$item" :moneda="$venta->moneda">
                            <x-slot name="footer">
                                <x-button-print class="mr-auto" target="_blank"
                                    href="{{ route('admin.payments.print', $item) }}" />

                                @can('admin.ventas.payments.edit')
                                    <x-button-delete onclick="confirmDeletePayment({{ $item->id }})"
                                        wire:loading.attr="disabled" />
                                @endcan
                            </x-slot>
                        </x-card-payment-box>
                    @endforeach
                </div>
            @else
                {{-- <x-span-text text="NO EXISTEN REGISTROS DE PAGOS..." class="mt-3 bg-transparent" /> --}}
            @endif
        </div>

        @if ($venta->typepayment->isContado() && $venta->sucursal_id == auth()->user()->sucursal_id)
            @if ($venta->cajamovimientos()->sum('amount') < $venta->total)
                <div class="w-full flex gap-2 pt-4 justify-end">
                    <x-button type="button" wire:loading.attr="disabled" wire:click="openmodal" @click="reset">
                        {{ __('REALIZAR PAGO') }}</x-button>
                </div>
            @endif
        @endif
    </x-form-card>

    @if ($venta->typepayment->isCredito())
        <x-form-card titulo="CUOTAS PAGO">
            @if (count($venta->cuotas) > 0)
                <div class="w-full flex flex-col gap-2">
                    <div
                        class="w-full grid grid-cols-[repeat(auto-fill,minmax(12rem,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(15rem,1fr))] gap-2">
                        @foreach ($venta->cuotas as $item)
                            <x-simple-card class="w-full p-1 flex flex-col justify-between gap-1">
                                <div class="w-full">
                                    <p class="text-colorminicard text-xl font-semibold text-center">
                                        <small class="text-[10px] font-medium">{{ $venta->moneda->simbolo }}</small>
                                        {{ decimalOrInteger($item->amount, 2, ', ') }}
                                        <small class="text-[10px] font-medium">{{ $venta->moneda->currency }}</small>
                                    </p>

                                    <div class="w-full flex flex-col justify-center items-center">
                                        <x-span-text :text="'Cuota' . substr('000' . $item->cuota, -3)" class="leading-3 !tracking-normal" />
                                        <p class="text-colorsubtitleform text-[10px]">
                                            VENC. {{ formatDate($item->expiredate, 'DD MMMM Y') }}</p>
                                    </div>

                                    @if (count($item->cajamovimientos) > 0)
                                        <div class="w-full flex flex-col gap-1">
                                            @foreach ($item->cajamovimientos as $payment)
                                                <x-card-payment-box :cajamovimiento="$payment" :moneda="$venta->moneda">
                                                    @if (auth()->user()->sucursal_id == $venta->sucursal_id)
                                                        <x-slot name="footer">
                                                            <x-button-print class="mr-auto"
                                                                href="{{ route('admin.payments.print', $payment) }}" />

                                                            @can('admin.ventas.payments.edit')
                                                                <x-button-delete
                                                                    wire:key="deletepaycuota_{{ $payment->id }}"
                                                                    onclick="confirmDeletePaycuota({{ $item }},{{ $payment->id }})"
                                                                    wire:loading.attr="disabled" />
                                                            @endcan
                                                        </x-slot>
                                                    @endif
                                                </x-card-payment-box>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                @if (auth()->user()->sucursal_id == $venta->sucursal_id)
                                    <div class="w-full flex gap-2 flex-wrap items-end justify-between">
                                        @if ($item->cajamovimientos->sum('amount') < $item->amount)
                                            @can('admin.ventas.payments.edit')
                                                <x-button wire:click="pay({{ $item->id }})"
                                                    wire:key="pay_{{ $item->id }}" wire:loading.attr="disabled"
                                                    @click="reset">PAGAR</x-button>
                                            @endcan
                                        @endif

                                        @if (count($item->cajamovimientos) == 0)
                                            @can('admin.ventas.create')
                                                <x-button-delete wire:key="deletecuota_{{ $item->id }}"
                                                    onclick="confirmDeleteCuota({{ $item }})"
                                                    wire:loading.attr="disabled" />
                                            @endcan
                                        @endif
                                    </div>
                                @endif
                            </x-simple-card>
                        @endforeach
                    </div>

                    @can('admin.ventas.create')
                        @if ($venta->sucursal_id == auth()->user()->sucursal_id)
                            @if ($venta->cuotas()->sum('amount') < $venta->total)
                                <div class="w-full flex justify-end items-end">
                                    <x-button wire:click="editcuotas" wire:loading.attr="disabled">
                                        EDITAR CUOTAS</x-button>
                                </div>
                            @endif
                        @endif
                    @endcan
                </div>
            @else
                @can('admin.ventas.create')
                    <div class="w-full grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                        <form class="w-full relative flex flex-col" wire:submit.prevent="calcularcuotas">
                            <div class="w-full">
                                <x-label value="Cuotas :" />
                                <x-input class="block w-full" type="number" min="1" step="1" max="10"
                                    wire:model.defer="countcuotas" />
                                <x-jet-input-error for="countcuotas" />
                            </div>

                            <div class="w-full flex justify-end mt-3">
                                <x-button type="submit" wire:loading.attr="disabled">
                                    CALCULAR</x-button>
                            </div>
                        </form>

                        <div class="w-full sm:col-span-2 lg:col-span-3 xl:col-span-4">
                            @if (count($cuotas) > 0)
                                <form wire:submit.prevent="updatecuotas" class="w-full">
                                    <div
                                        class="w-full grid grid-cols-[repeat(auto-fill,minmax(10rem,1fr))] xl:grid-cols-[repeat(auto-fill,minmax(12rem,1fr))] gap-2">
                                        @foreach ($cuotas as $item)
                                            <x-simple-card class="w-full flex flex-col justify-center items-center p-1">
                                                <x-span-text :text="'Cuota' . substr('000' . $item['cuota'], -3)" />

                                                <div class="block w-full">
                                                    <x-label value="Fecha pago :" />
                                                    <x-input class="block w-full" type="date"
                                                        wire:model.defer="cuotas.{{ $loop->iteration - 1 }}.date" />
                                                </div>
                                                <div class="block w-full">
                                                    <x-label value="Monto Cuota :" />
                                                    <x-input class="block w-full" type="number" min="0.001"
                                                        step="0.001"
                                                        wire:model.defer="cuotas.{{ $loop->iteration - 1 }}.amount" />
                                                </div>
                                                <div>
                                                    <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.cuota" />
                                                    <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.date" />
                                                    <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.amount" />
                                                </div>
                                            </x-simple-card>
                                        @endforeach
                                    </div>
                                    <x-jet-input-error for="cuotas" />
                                    <x-jet-input-error for="amountcuotas" />

                                    <div class="w-full flex pt-4 justify-end">
                                        <x-button type="submit" wire:click="updatecuotas" wire:loading.attr="disabled">
                                            {{ __('Save') }}</x-button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                @endcan
                @cannot('admin.ventas.create')
                    <p>
                        <x-span-text text="SIN PERMISOS PARA REGISTRAR CUOTAS DE PAGO..." />
                    </p>
                @endcannot
            @endif
        </x-form-card>
    @endif

    <x-form-card titulo="RESUMEN PRODUCTOS">
        <div class="w-full">
            @if (count($venta->tvitems))
                <div
                    class="w-full grid grid-cols-[repeat(auto-fill,minmax(170px,1fr))] sm:grid-cols-[repeat(auto-fill,minmax(200px,1fr))] gap-1 mt-1">
                    @foreach ($venta->tvitems as $item)
                        @php
                            $image = !empty($item->producto->image)
                                ? pathURLProductImage($item->producto->image)
                                : null;
                        @endphp
                        <x-card-producto :image="$image" :name="$item->producto->name" :marca="$item->producto->marca->name" :category="$item->producto->category->name"
                            :increment="$item->increment" :promocion="$item->promocion" class="overflow-hidden">

                            @if ($item->isGratuito())
                                <x-span-text text="GRATUITO" type="green" class="!py-0.5" />
                            @endif

                            <h1 class="text-xl text-center font-semibold text-colortitleform">
                                <small class="text-[10px] font-medium">{{ $venta->moneda->simbolo }}</small>
                                {{ decimalOrInteger($item->subtotal + $item->subtotaligv, 2, ', ') }}
                                <small class="text-[10px] font-medium">{{ $venta->moneda->currency }}</small>
                            </h1>

                            <div class="text-xl font-semibold mt-1 text-colorlabel">
                                {{ decimalOrInteger($item->cantidad, 2, ', ') }}
                                <small class="text-[10px] font-medium">
                                    {{ $item->producto->unit->name }} /
                                    {{ $item->almacen->name }}
                                </small>
                            </div>

                            <div class="text-sm font-semibold text-colorlabel leading-3">
                                <small class="text-[10px] font-medium">P.U.V : </small>
                                {{ decimalOrInteger($item->price + $item->igv, 2, ', ') }}
                                <small class="text-[10px] font-medium">{{ $venta->moneda->currency }}</small>
                            </div>

                            @if (count($item->itemseries) == 1)
                                <div class="text-sm font-semibold text-colorlabel leading-3">
                                    <small class="text-[10px] font-medium">
                                        SN: {{ $item->itemseries->first()->serie->serie }}
                                    </small>
                                </div>
                            @endif


                            @if ($item->producto->isRequiredserie() && count($item->itemseries) < $item->cantidad)
                                <div class="w-full mt-2" x-data="{ addserie: false }">
                                    <form class="w-full inline-flex gap-0.5"
                                        wire:submit.prevent="saveserie({{ $item->id }})">
                                        <x-input class="block w-full flex-1" minlength="1" maxlength="255"
                                            wire:model.defer="tvitem.{{ $item->id }}.serie" />
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

                            <div class="w-full mt-1" x-data="{ showForm: false }">
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

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">{{ __('Realizar pago cuota') }}</x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savepayment" class="flex flex-col gap-2" x-data="payventa">
                @if ($monthbox)
                    <x-card-box :openbox="$openbox" :monthbox="$monthbox" />
                @else
                    <p class="text-colorerror text-[10px] text-end">APERTURA DE CAJA MENSUAL NO DISPONIBLE...</p>
                @endif

                <div class="w-full">
                    <x-span-text :text="'Cuota' . substr('000' . $cuota->cuota, -3)" />
                    <p class="text-colorminicard text-3xl font-semibold">
                        <small class="text-[10px] font-medium">{{ $venta->moneda->simbolo }}</small>
                        {{ number_format($cuota->amount, 2, '.', ', ') }}
                        <small class="text-[10px] font-medium">{{ $venta->moneda->currency }}</small>
                    </p>

                    @if ($amountpendiente > 0)
                        <p class="text-colorerror text-sm font-semibold">
                            <small class="text-[10px] font-medium">SALDO </small>
                            {{ number_format($amountpendiente, 2, '.', ', ') }}
                            <small class="text-[10px] font-medium">{{ $venta->moneda->currency }}</small>
                        </p>
                    @endif
                </div>

                @if (count($monedas) > 1)
                    <div>
                        <x-label value="Moneda pago :" />
                        <div class="w-full flex flex-wrap gap-2 justify-start items-center">
                            @foreach ($monedas as $item)
                                <div class="inline-flex">
                                    <input class="sr-only peer" data-code="{{ $item->code }}"
                                        data-currency="{{ $item->currency }}" data-simbolo="{{ $item->simbolo }}"
                                        x-model="moneda_id" type="radio" name="monedas"
                                        id="moneda_{{ $item->id }}" value="{{ $item->id }}"
                                        @change="changeMoneda" />
                                    <x-label-check-moneda for="moneda_{{ $item->id }}" title="MONEDA"
                                        :simbolo="$item->currency" />
                                </div>
                            @endforeach
                        </div>
                        <x-jet-input-error for="moneda_id" />
                    </div>
                @endif

                <div class="w-full">
                    <x-label value="Monto :" />
                    <x-input class="block w-full input-number-none" x-model="paymentactual" type="number"
                        onkeypress="return validarDecimal(event, 7)" step="0.001" @input="calcular"
                        min="0" />
                    <x-jet-input-error for="paymentactual" />
                    <x-jet-input-error for="totalamount" />
                </div>

                <div class="w-full" x-show="showtipocambio">
                    <div class="w-full">
                        <x-label value="Tipo cambio :" />
                        <x-input class="block w-full input-number-none" x-model="tipocambio" type="number"
                            placeholder="0.00" onkeypress="return validarDecimal(event, 7)" step="0.001"
                            @input="calcular" />
                        <x-jet-input-error for="tipocambio" />
                    </div>

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
                                @if (count($methodpayments) > 0)
                                    @foreach ($methodpayments as $item)
                                        <option value="{{ $item->id }}"
                                            data-transferencia="{{ $item->isTransferencia() }}">
                                            {{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                </div>

                <div class="w-full" x-show="istransferencia" x-cloak style="display: none;" x-transition>
                    <x-label value="Otros (N° operación , descripción, etc) :" />
                    <x-input class="block w-full" x-model="detallepaycuota" />
                    <x-jet-input-error for="detalle" />
                </div>

                <div>
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

    <x-jet-dialog-modal wire:model="opencuotas" maxWidth="2xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Editar cuotas pago') }}
        </x-slot>

        <x-slot name="content">
            <div class="w-full relative">
                <h3 class="font-semibold text-3xl leading-normal text-colorlabel">
                    <small class="text-[10px] font-medium">MONTO : {{ $venta->moneda->simbolo }}</small>
                    {{ (float) $venta->total - ($venta->gratuito + $venta->igvgratuito) }}
                    <small class="text-[10px] font-medium">{{ $venta->moneda->currency }}</small>
                </h3>

                <form wire:submit.prevent="updatecuotas" class="w-full flex flex-wrap justify-around gap-2">
                    @if (count($cuotas) > 0)
                        <div class="w-full grid grid-cols-[repeat(auto-fill,minmax(12rem,1fr))] gap-2">
                            @foreach ($cuotas as $item)
                                <x-simple-card class="p-2 w-full flex flex-col justify-center items-center gap-2">

                                    <x-span-text :text="'Cuota' . substr('000' . $item['cuota'], -3)" />

                                    @if (count($item['cajamovimientos']) > 0)
                                        <p class="text-colorminicard text-xl font-semibold text-center">
                                            <small
                                                class="text-[10px] font-medium">{{ $venta->moneda->simbolo }}</small>
                                            {{ (float) $item['amount'] }}
                                            <small
                                                class="text-[10px] font-medium">{{ $venta->moneda->currency }}</small>
                                        </p>
                                    @endif

                                    <div class="w-full">
                                        <x-label value="Fecha pago :" class="mt-5" />
                                        @if (count($item['cajamovimientos']) == 0)
                                            <x-input class="block w-full" type="date"
                                                wire:model.lazy="cuotas.{{ $loop->iteration - 1 }}.date" />
                                        @else
                                            <x-disabled-text :text="formatDate($item['date'], 'DD/MM/Y')" />
                                        @endif
                                    </div>

                                    @if (count($item['cajamovimientos']) == 0)
                                        <div class="w-full">
                                            <x-label value="Monto Cuota :" />
                                            <x-input class="block w-full" type="number" min="1"
                                                step="0.001"
                                                wire:model.lazy="cuotas.{{ $loop->iteration - 1 }}.amount" />
                                        </div>
                                    @endif

                                    <div class="w-full">
                                        <x-jet-input-error for="cuotas.{{ $loop->iteration - 1 }}.cuota" />
                                        <x-jet-input-error for="cuotas.{{ $loop->iteration - 1 }}.date" />
                                        <x-jet-input-error for="cuotas.{{ $loop->iteration - 1 }}.amount" />
                                    </div>
                                </x-simple-card>
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

    <x-jet-dialog-modal wire:model="openpay" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">{{ __('Realizar pago venta') }}</x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savepay" class="w-full flex flex-col gap-1" x-data="payventa">
                @if ($monthbox)
                    <x-card-box :openbox="$openbox" :monthbox="$monthbox" />
                @else
                    <p class="text-colorerror text-[10px] text-end">APERTURA DE CAJA MENSUAL NO DISPONIBLE...</p>
                @endif

                <div class="w-full">
                    <p class="text-colortitleform text-3xl font-semibold">
                        <small class="text-[10px] font-medium">{{ $venta->moneda->simbolo }}</small>
                        {{ number_format($venta->total, 2, '.', ', ') }}
                        <small class="text-[10px] font-medium">{{ $venta->moneda->currency }}</small>
                    </p>

                    @if ($pendiente > 0)
                        <p class="text-colorerror text-sm font-semibold">
                            <small class="text-[10px] font-medium">SALDO </small>
                            {{ number_format($pendiente, 2, '.', ', ') }}
                            <small class="text-[10px] font-medium">{{ $venta->moneda->currency }}</small>
                        </p>
                    @endif
                </div>

                @if (count($monedas) > 1)
                    <div>
                        <x-label value="Moneda pago :" />
                        <div class="w-full flex flex-wrap gap-2 justify-start items-center">
                            @foreach ($monedas as $item)
                                <div class="inline-flex">
                                    <input class="sr-only peer" data-code="{{ $item->code }}"
                                        data-currency="{{ $item->currency }}" data-simbolo="{{ $item->simbolo }}"
                                        x-model="moneda_id" type="radio" name="moneda"
                                        id="paymoneda_{{ $item->id }}" value="{{ $item->id }}"
                                        @change="changeMoneda" />
                                    <x-label-check-moneda for="paymoneda_{{ $item->id }}" title="MONEDA"
                                        :simbolo="$item->currency" />
                                </div>
                            @endforeach
                        </div>
                        <x-jet-input-error for="moneda_id" />
                    </div>
                @endif

                <div class="w-full">
                    <x-label value="Monto pagar :" />
                    <x-input class="block w-full input-number-none" x-model="paymentactual" type="number"
                        step="0.001" min="0" onkeypress="return validarDecimal(event, 12)"
                        @input="calcular" />
                    <x-jet-input-error for="paymentactual" />
                    <x-jet-input-error for="totalamount" />
                </div>

                <div class="w-full" x-show="showtipocambio">
                    <div class="w-full">
                        <x-label value="Tipo cambio :" />
                        <x-input class="block w-full input-number-none" x-model="tipocambio" type="number"
                            onkeypress="return validarDecimal(event, 7)" step="0.001" @input="calcular" />
                        <x-jet-input-error for="tipocambio" />
                    </div>

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
                    <div class="relative" x-init="MethodpaymentVenta" id="parentmethodpayv">
                        <x-select class="block w-full" x-ref="selectmpv" id="methodpayv"
                            data-dropdown-parent="null">
                            <x-slot name="options">
                                @if (count($methodpayments) > 0)
                                    @foreach ($methodpayments as $item)
                                        <option value="{{ $item->id }}"
                                            data-transferencia="{{ $item->isTransferencia() }}">
                                            {{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </x-slot>
                        </x-select>
                        <x-icon-select />
                    </div>
                    <x-jet-input-error for="methodpayment_id" />
                </div>

                <div class="w-full" x-show="istransferencia" x-cloak style="display: none;" x-transition>
                    <x-label value="Otros (N° operación , Banco, etc) :" />
                    <x-input class="block w-full" x-model="detalle" />
                    <x-jet-input-error for="detalle" />
                </div>

                <div>
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
                monedaventa_id: '{{ $venta->moneda_id }}',
                currency: '{{ $venta->moneda->currency }}',
                code: '{{ $venta->moneda->code }}',
                simbolo: '{{ $venta->moneda->simbolo }}',
                totalamount: @entangle('totalamount').defer,
                tipocambio: @entangle('tipocambio').defer,
                tipocambiodefault: '{{ (float) mi_empresa()->tipocambio }}',

                init() {

                },
                changeMoneda(event) {
                    const rdomoneda = event.target;
                    this.code = rdomoneda.getAttribute('data-code');
                    this.currency = rdomoneda.getAttribute('data-currency');
                    this.simbolo = rdomoneda.getAttribute('data-simbolo');
                    this.showtipocambio = (this.moneda_id != this.monedaventa_id) ? true : false;
                    this.tipocambio = this.tipocambiodefault > 0 ? this.tipocambiodefault : null;
                    this.calcular();
                },
                calcular() {
                    if (this.showtipocambio) {
                        if (this.code == 'PEN') {
                            if (toDecimal(this.paymentactual) > 0 && toDecimal(this.tipocambio) > 0) {
                                this.totalamount = toDecimal(this.paymentactual * this.tipocambio, 2);
                            } else {
                                this.totalamount = '0.00'
                            }
                        } else if (this.code == 'USD') {
                            if (toDecimal(this.paymentactual) > 0 && toDecimal(this.tipocambio) > 0) {
                                this.totalamount = toDecimal(this.paymentactual / this.tipocambio, 2);
                            } else {
                                this.totalamount = '0.00'
                            }
                        } else {
                            this.totalamount = null
                        }
                    }
                }
            }))
        })


        function MethodpaymentVenta() {
            this.selectMPV = $(this.$refs.selectmpv).select2();
            this.selectMPV.val(this.methodpayment_id).trigger("change");
            this.selectMPV.on("select2:select", (event) => {
                this.methodpayment_id = event.target.value;
                var selectedOption = event.params.data.element;
                this.istransferencia = Boolean($(selectedOption).data('transferencia'));
                if (!this.istransferencia) {
                    this.detalle = '';
                }
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
                var selectedOption = event.params.data.element;
                this.istransferencia = Boolean($(selectedOption).data('transferencia'));
                if (!this.istransferencia) {
                    this.detalle = '';
                }
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

        function confirmDeletePaycuota(cuota, cajamovimiento_id) {
            const cuotastr = '000' + cuota.cuota;
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
                    @this.deletepay(cajamovimiento_id);
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
