<div class="w-full flex flex-col gap-8" x-data="{ loadingventa: false }">

    <x-form-card titulo="DATOS VENTA" x-data="{ updatingventa: false }">
        <form wire:submit.prevent="update" class="w-full flex flex-col gap-2 bg-body p-3 rounded">
            <div class="w-full flex flex-col xs:grid xs:grid-cols-2 xl:grid-cols-3 gap-2">

                <div class="w-full">
                    <x-label value="Fecha venta :" />
                    <x-disabled-text :text="\Carbon\Carbon::parse($venta->date)->format('d/m/Y')" />
                </div>

                @if (Module::isEnabled('Facturacion'))
                    @if ($venta->comprobante)
                        <div class="w-full">
                            <x-label value="Tipo comprobante :" />
                            <x-disabled-text :text="$venta->comprobante->seriecompleta .
                                ' (' .
                                $venta->seriecomprobante->typecomprobante->descripcion .
                                ')'" />
                        </div>
                    @else
                        <div class="w-full">
                            <x-label value="Codigo venta :" />
                            <x-disabled-text :text="$venta->code .
                                '-' .
                                $venta->id .
                                ' (' .
                                $venta->seriecomprobante->typecomprobante->descripcion .
                                ')'" />
                        </div>
                    @endif
                @else
                    <div class="w-full">
                        <x-label value="Codigo venta :" />
                        <x-disabled-text :text="$venta->code .
                            '-' .
                            $venta->id .
                            ' (' .
                            $venta->seriecomprobante->typecomprobante->descripcion .
                            ')'" />
                    </div>
                @endif

                <div class="w-full">
                    <x-label value="Moneda :" />
                    <x-disabled-text :text="$venta->moneda->currency" />
                </div>

                <div class="w-full">
                    <x-label value="DNI /RUC :" />
                    <x-disabled-text :text="$venta->client->document ?? '-'" />
                </div>

                <div class="w-full xs:col-span-2">
                    <x-label value="Cliente / Razón Social :" />
                    <x-disabled-text :text="$venta->client->name ?? '-'" />
                </div>

                <div class="w-full xs:col-span-2">
                    <x-label value="Dirección :" />
                    <x-disabled-text :text="$venta->direccion ?? '-'" />
                </div>

                @if (Module::isEnabled('Facturacion'))
                    @if ($venta->comprobante)
                        <div class="w-full">
                            <x-label value="Tipo comprobante :" />
                            <x-disabled-text :text="$venta->comprobante->seriecompleta .
                                ' (' .
                                $venta->comprobante->seriecomprobante->typecomprobante->descripcion .
                                ')'" />
                        </div>
                    @endif
                @endif

                <div class="w-full">
                    <x-label value="Tipo pago :" />
                    <x-disabled-text :text="$venta->typepayment->name" />
                </div>

                @if ($venta->cajamovimiento)
                    <div class="w-full">
                        <x-label value="Método pago :" />
                        <x-disabled-text :text="$venta->cajamovimiento->methodpayment->name" />
                    </div>

                    {{-- <div class="w-full">
                        <x-label value="Método pago :" />
                        <div id="parentventamethodpayment_id">
                            <x-select class="block w-full" id="ventamethodpayment_id"
                                wire:model.lazy="methodpaymentventa_id">
                                <x-slot name="options">
                                    @if (count($methodpayments))
                                        @foreach ($methodpayments as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </x-slot>
                            </x-select>
                        </div>
                        <x-jet-input-error for="methodpaymentventa_id" />
                    </div> --}}

                    @if (count($cuentas))
                        <div class="w-full">
                            <x-label value="Cuenta pago :" />
                            <div id="parentcuentaventa_id">
                                <x-select class="block w-full" id="cuentaventa_id" wire:model.defer="cuentaventa_id">
                                    <x-slot name="options">
                                        @foreach ($cuentas as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->account }}
                                                ({{ $item->descripcion }})
                                            </option>
                                        @endforeach
                                    </x-slot>
                                </x-select>
                            </div>
                            <x-jet-input-error for="cuentaventa_id" />
                        </div>
                    @endif

                    <div class="w-full">
                        <x-label value="Caja pago :" />
                        @php
                            $aperturadate = $venta->cajamovimiento->opencaja->startdate;
                            $expiredate = $venta->cajamovimiento->opencaja->expiredate;

                            if ($aperturadate) {
                                $aperturadate = ' (APERT: ' . \Carbon\Carbon::parse($aperturadate)->format('d/m/Y');
                            }

                            if ($expiredate) {
                                $expiredate = ' CIERRE: ' . \Carbon\Carbon::parse($expiredate)->format('d/m/Y');
                            }
                        @endphp

                        <x-disabled-text :text="$venta->cajamovimiento->opencaja->caja->name . $aperturadate . $expiredate . ')'" />
                    </div>

                    <div class="w-full">
                        <x-label value="Detalle pago :" />
                        <x-input class="block w-full" wire:model.defer="detallepago" />
                        <x-jet-input-error for="detallepago" />
                    </div>
                @endif

                @if ($venta->moneda->code == 'USD')
                    <div class="w-full">
                        <x-label value="Tipo Cambio :" />
                        <x-disabled-text :text="$venta->tipocambio ?? '0.000'" />
                    </div>
                @endif

                <div class="w-full">
                    <x-label value="Cotización vinculada :" />
                    <x-disabled-text text="****" />
                </div>

                <div class="w-full">
                    <x-label value="Sucursal venta :" />
                    <x-disabled-text :text="$venta->sucursal->name ?? '-'" />
                </div>

                <div class="w-full">
                    <x-label value="Usuario :" />
                    <x-disabled-text :text="$venta->user->name" />
                </div>
            </div>

            @if ($errors->any())
                <div class="w-full flex flex-col gap-1">
                    @foreach ($errors->keys() as $key)
                        <x-jet-input-error :for="$key" />
                    @endforeach
                </div>
            @endif

            <div class="w-full flex gap-2 pt-4 justify-end">
                <x-button-secondary wire:click="$emit('venta.confirmDelete', {{ $venta }})"
                    wire:loading.attr="disabled">
                    {{ __('ELIMINAR') }}
                </x-button-secondary>

                <x-button type="submit" wire:loading.attr="disabled">
                    {{ __('ACTUALIZAR') }}
                </x-button>
            </div>
        </form>
        <div x-show="updatingventa" wire:loading.flex wire:target="update, delete" class="loading-overlay rounded">
            <x-loading-next />
        </div>
    </x-form-card>

    @if ($venta->typepayment)
        @if ($venta->typepayment->paycuotas)
            <x-form-card titulo="CUOTAS PAGO" widthBefore="before:w-20" subtitulo="Información de cuotas de pago."
                x-data="{ loadingcuotas: false }">

                @if (count($venta->cuotas))
                    <div class="w-full flex flex-col gap-2">
                        <div class="w-full flex gap-2 flex-wrap justify-start">
                            @foreach ($venta->cuotas as $item)
                                <x-card-cuota class="w-full xs:w-48" :titulo="substr('000' . $item->cuota, -3)" :detallepago="$item->cajamovimiento">
                                    <p class="text-colorminicard text-[10px] mt-2">
                                        MONTO :
                                        {{ $venta->moneda->simbolo }}
                                        {{ number_format($item->amount, 3, '.', ', ') }}
                                        {{ $venta->moneda->currency }}
                                    </p>

                                    <p class="text-colorminicard text-[10px] uppercase">
                                        Fecha pago :
                                        {{ \Carbon\Carbon::parse($item->expiredate)->locale('es')->isoformat('DD MMMM Y') }}
                                    </p>

                                    <x-slot name="footer">
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
                                                <x-button-delete
                                                    wire:click="$emit('venta.confirmDeletePay', {{ $item }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="venta.confirmDeletePay"></x-button-delete>
                                            </div>
                                        @else
                                            <div class="w-full flex gap-2 flex-wrap items-end justify-between">
                                                <x-button wire:click="pay({{ $item->id }})"
                                                    wire:key="pay{{ $item->id }}" wire:loading.attr="disabled"
                                                    wire:target="pay">PAGAR</x-button>
                                                <x-button-delete
                                                    wire:click="$emit('venta.confirmDeleteCuota', {{ $item }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="venta.deletecuota"></x-button-delete>
                                            </div>
                                        @endif
                                    </x-slot>

                                </x-card-cuota>
                            @endforeach
                        </div>
                        <div class="w-full">
                            <x-button wire:click="editcuotas" wire:loading.attr="disabled"
                                wire:target="editcuotas">EDITAR CUOTAS</x-button>
                        </div>
                    </div>
                @else
                    <div class="w-full flex flex-wrap xl:flex-nowrap gap-2">
                        <form wire:submit.prevent="calcularcuotas"
                            class="w-full xl:w-1/3 relative flex flex-col gap-2 bg-body p-3 rounded">
                            <div class="w-full">
                                <x-label value="Cuotas :" />
                                <x-input class="block w-full" type="number" min="1" step="1"
                                    max="10" wire:model.defer="countcuotas" />
                            </div>
                            <x-jet-input-error for="countcuotas" />

                            <div class="w-full flex justify-end mt-3">
                                <x-button type="submit" wire:loading.attr="disabled" wire:target="calcularcuotas">
                                    CALCULAR
                                </x-button>
                            </div>
                        </form>

                        <div class="w-full xl:w-2/3">
                            @if (count($cuotas))
                                <div class="w-full flex flex-wrap gap-1">
                                    @foreach ($cuotas as $item)
                                        <x-card-cuota :titulo="substr('000' . $item['cuota'], -3)" class="w-full sm:w-48">

                                            <x-label value="Fecha pago :" textSize="[10px]" />
                                            <x-input class="block w-full" type="date"
                                                wire:model="cuotas.{{ $loop->iteration - 1 }}.date" />

                                            <x-label value="Monto Cuota :" textSize="[10px]" />
                                            <x-input class="block w-full numeric" type="number" min="1"
                                                step="0.0001"
                                                wire:model="cuotas.{{ $loop->iteration - 1 }}.amount" />

                                            <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.cuota" />
                                            <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.date" />
                                            <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.amount" />
                                        </x-card-cuota>
                                    @endforeach
                                </div>
                                <x-jet-input-error for="cuotas" />
                                <x-jet-input-error for="amountcuotas" />

                                <div class="w-full flex pt-4 gap-2 justify-end">
                                    <x-button wire:click="savecuotas" wire:loading.attr="disabled"
                                        wire:target="savecuotas">
                                        {{ __('REGISTRAR') }}
                                    </x-button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div x-show="loadingcuotas" wire:loading wire:loading.flex
                    wire:target="calcularcuotas, deletecuota, deletepaycuota, savepayment, savecuotas, delete"
                    class="loading-overlay rounded">
                    <x-loading-next />
                </div>
            </x-form-card>
        @endif
    @endif

    <x-form-card titulo="RESUMEN DE VENTA">
        <div class="w-full text-colortitleform bg-body p-3 rounded-md">
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

            <p class="text-[10px]">
                @php
                    $amountIncr = number_format((($venta->total - $venta->paymentactual) * $venta->increment) / (100 + $venta->increment), 4, '.', ', ');
                @endphp

                @if ($venta->increment > 0)
                    IMPORTE TOTAL + INCREMENTO({{ $venta->moneda->simbolo }}
                    {{ number_format($amountIncr, 2, '.', ', ') }}) :
                @else
                    IMPORTE TOTAL :
                @endif
                {{ $venta->moneda->simbolo }}
                <span class="font-bold text-xs">{{ number_format($venta->total, 3, '.', ', ') }}</span>
            </p>

        </div>
    </x-form-card>

    <x-form-card titulo="RESUMEN PRODUCTOS">
        <div class="w-full">
            @if (count($venta->tvitems))
                <div class="flex gap-2 flex-wrap justify-start mt-1">
                    @foreach ($venta->tvitems as $item)
                        @php
                            $image = null;
                            if (count($item->producto->images)) {
                                if (count($item->producto->defaultImage)) {
                                    $image = asset('storage/productos/' . $item->producto->defaultImage->first()->url);
                                } else {
                                    $image = asset('storage/productos/' . $item->producto->images->first()->url);
                                }
                            }
                        @endphp
                        <x-card-producto :image="$image" :name="$item->producto->name" :increment="$item->increment ?? null"
                            x-data="{ loadingproducto: false }">
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
                                    number_format($item->price, 3, '.', ', ')" class="leading-3" />

                                @if ($item->igv > 0)
                                    <x-span-text :text="'IGV UNIT : ' .
                                        $venta->moneda->simbolo .
                                        ' ' .
                                        number_format($item->igv, 3, '.', ', ')" class="leading-3" />
                                @endif

                                <x-span-text :text="\App\Helpers\FormatoPersonalizado::getValue($item->cantidad) .
                                    ' ' .
                                    $item->producto->unit->name" class="leading-3" />

                                <x-span-text :text="$item->almacen->name" class="leading-3" />

                                @if (count($item->itemseries) == 1)
                                    <x-span-text :text="$item->itemseries->first()->serie->serie" class="leading-3" />
                                @endif
                            </div>

                            <div class="w-full mt-1" x-data="{ showForm: false }">
                                @if (count($item->itemseries) > 1)
                                    <x-button @click="showForm = !showForm" class="whitespace-nowrap">
                                        {{ __('VER SERIES') }}
                                    </x-button>
                                @endif

                                <div x-show="showForm" @click.away="showForm = false"
                                    x-transition:enter="transition ease-out duration-300 transform"
                                    x-transition:enter-start="opacity-0 translate-y-[-10%]"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-300 transform"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-[-10%]"
                                    class="block w-full rounded mt-1">
                                    <div class="w-full flex flex-wrap gap-1">
                                        @if (count($item->itemseries) > 1)
                                            @foreach ($item->itemseries as $itemserie)
                                                {{-- <x-label-check>
                                                    MI SERIE
                                                    <x-button-delete />
                                                </x-label-check> --}}
                                                <span
                                                    class="inline-flex items-center gap-1 text-[10px] bg-fondospancardproduct text-textspancardproduct p-1 rounded-lg">
                                                    {{ $itemserie->serie->serie }}
                                                    <x-button-delete
                                                        wire:click="$emit('compra.confirmDeleteSerie',{{ $itemserie }})"
                                                        wire:loading.attr="disabled" />
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- <x-slot name="footer">
                                <x-button-delete
                                    wire:click="$emit('compra.confirmDeleteItemCompra',({{ $item->id }}))"
                                    wire:loading.attr="disabled"
                                    wire:target="compra.confirmDeleteItemCompra, deleteitemcompra" />
                            </x-slot> --}}

                            <div x-show="loadingproducto" wire:loading.flex class="loading-overlay rounded">
                                <x-loading-next />
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

    <x-form-card titulo="OPCIONES">
        <div class="w-full flex gap-2 items-start justify-end">
            <x-button>IMPRIMIR A4</x-button>
            <x-button>IMPRIMIR TICKET</x-button>
            @if (Module::isEnabled('Facturacion'))
                <x-button>ENVIAR</x-button>
            @endif
        </div>
    </x-form-card>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Realizar pago cuota') }}
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savepayment" class="flex flex-col gap-2">

                <div class="w-full">
                    <x-label value="N° Cuota / Monto:" />
                    <p class="inline-block text-[10px] font-semibold bg-gray-300 text-gray-700 rounded-lg p-1">
                        Cuota{{ substr('000' . $cuota->cuota, -3) }} /
                        {{ $venta->moneda->simbolo }}
                        {{ number_format($cuota->amount, 3, '.', ', ') }}
                    </p>
                </div>

                <div class="w-full">
                    <x-label value="Método pago :" />
                    <x-select class="block w-full" id="cuotamethodpayment_id" wire:model.defer="methodpayment_id"
                        data-dropdown-parent="">
                        <x-slot name="options">
                            @if (count($methodpayments))
                                @foreach ($methodpayments as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                </div>

                @if (count($cuentas))
                    <div class="w-full">
                        <x-label value="Cuenta pago :" />
                        <div id="parentcuentacuota_id">
                            <x-select class="block w-full" id="cuentacuota_id" wire:model.defer="cuenta_id"
                                data-dropdown-parent="">
                                <x-slot name="options">
                                    @foreach ($cuentas as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->account }}
                                            ({{ $item->descripcion }})
                                        </option>
                                    @endforeach
                                </x-slot>
                            </x-select>
                        </div>
                        <x-jet-input-error for="cuentaventa_id" />
                    </div>
                @endif

                <div class="w-full">
                    <x-label value="Otros (N° operación , Banco, etc) :" textSize="xs" />
                    <x-input class="block w-full" wire:model.defer="detalle" />
                </div>

                @if ($errors->any())
                    <div class="">
                        @foreach ($errors->keys() as $key)
                            <x-jet-input-error :for="$key" />
                        @endforeach
                    </div>
                @endif

                <div class="w-full flex pt-4 gap-2 justify-end">
                    <x-button type="submit" size="xs" class="" wire:loading.attr="disabled"
                        wire:target="savepayment">
                        {{ __('REGISTRAR') }}
                    </x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="opencuotas" maxWidth="2xl" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Editar cuotas pago') }}
            <x-button-add wire:click="$toggle('opencuotas')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <div class="w-full relative" x-data="{ updatingcuotas: false }">
                <x-span-text :text="'MONTO CUOTAS A CALCULAR : ' .
                    $venta->moneda->simbolo .
                    number_format($venta->total - $venta->paymentactual, 2, '.', ', ')" class="mb-2" />
                <form wire:submit.prevent="updatecuotas" class="w-full flex flex-wrap justify-around gap-2">
                    @if (count($cuotas))
                        <div class="w-full flex flex-wrap gap-1">
                            @foreach ($cuotas as $item)
                                <x-card-cuota :titulo="substr('000' . $item['cuota'], -3)" class="w-full sm:w-48">
                                    @if (!is_null($item['cajamovimiento_id']))
                                        <span
                                            class="absolute right-1 top-1 w-5 h-5 block rounded-full p-1 bg-green-100 text-next-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path
                                                    d="M20.9953 6.96425C21.387 6.57492 21.3889 5.94176 20.9996 5.55005C20.6102 5.15834 19.9771 5.15642 19.5854 5.54575L8.97661 16.0903L4.41377 11.5573C4.02196 11.1681 3.3888 11.1702 2.99956 11.562C2.61032 11.9538 2.6124 12.5869 3.0042 12.9762L8.27201 18.2095C8.66206 18.597 9.29179 18.5969 9.68175 18.2093L20.9953 6.96425Z"
                                                    fill="black" />
                                            </svg>
                                        </span>
                                    @endif

                                    <x-label value="Fecha pago :" textSize="[10px]" />
                                    @if (is_null($item['cajamovimiento_id']))
                                        <x-input class="block w-full" type="date"
                                            wire:model.lazy="cuotas.{{ $loop->iteration - 1 }}.date" />
                                    @else
                                        <x-disabled-text :text="\Carbon\Carbon::parse($item['date'])->format('d/m/Y')" />
                                    @endif


                                    <x-label value="Monto Cuota :" textSize="[10px]" />
                                    @if (is_null($item['cajamovimiento_id']))
                                        <x-input class="block w-full" type="number" min="1" step="0.0001"
                                            wire:model.lazy="cuotas.{{ $loop->iteration - 1 }}.amount" />
                                    @else
                                        <x-disabled-text :text="$item['amount']" />
                                    @endif

                                    <x-jet-input-error for="cuotas.{{ $loop->iteration - 1 }}.cuota" />
                                    <x-jet-input-error for="cuotas.{{ $loop->iteration - 1 }}.date" />
                                    <x-jet-input-error for="cuotas.{{ $loop->iteration - 1 }}.amount" />

                                </x-card-cuota>
                            @endforeach
                        </div>
                        <x-jet-input-error for="cuotas" />
                        <x-jet-input-error for="amountcuotas" />

                        <div class="w-full mt-3 gap-2 flex items-center justify-center">
                            <x-button wire:click="addnewcuota" wire:loading.attr="disabled"
                                wire:target="addnewcuota">
                                AGREGAR NUEVA CUOTA
                            </x-button>
                            <x-button type="submit" wire:loading.attr="disable" wire:target="updatecuotas">
                                CONFIRMAR CUOTAS</x-button>
                        </div>
                    @endif
                </form>

                <div x-show="updatingcuotas" wire:loading wire:loading.flex
                    wire:target="editcuotas, updatecuotas, addnewcuota" class="loading-overlay rounded">
                    <x-loading-next />
                </div>
            </div>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        document.addEventListener("livewire:load", () => {

            renderSelect2();

            $("#cuotamethodpayment_id").on("change", (e) => {
                deshabilitarSelects();
                @this.methodpayment_id = e.target.value;
            });

            $("#ventamethodpayment_id").on("change", (e) => {
                deshabilitarSelects();
                @this.methodpaymentventa_id = e.target.value;
            });

            $("#cuentaventa_id").on("change", (e) => {
                deshabilitarSelects();
                @this.cuentaventa_id = e.target.value;
            });

            $("#cuentacuota_id").on("change", (e) => {
                deshabilitarSelects();
                @this.cuenta_id = e.target.value;
            });

            window.addEventListener('render-show-venta', () => {
                renderSelect2();
            });

            Livewire.on('venta.confirmDelete', data => {
                let seriecompleta = data.comprobante !== undefined ? data.comprobante.seriecompleta :
                    undefined;
                let mensaje = seriecompleta ? ', incluyendo el comprobante ' +
                    seriecompleta + ' y todos sus datos contenidos.' : '';

                    console.log(seriecompleta);

                swal.fire({
                    title: 'Desea anular la venta ' + data.code + '-' + data.id + ' ?',
                    text: "Se eliminará un registro de la base de datos" + mensaje,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0FB9B9',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.delete(data.id);
                        // Livewire.emitTo('ventas::ventas.show-venta', 'delete', data.id);
                    }
                })
            });

            Livewire.on('venta.confirmDeletePay', data => {
                const cuotastr = '000' + data.cuota;
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
                        Livewire.emitTo('ventas::ventas.show-venta', 'deletepaycuota', data.id);
                    }
                })
            });

            Livewire.on('venta.confirmDeleteCuota', data => {
                const cuotastr = '000' + data.cuota;
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
                        Livewire.emitTo('ventas::ventas.show-venta', 'deletecuota', data.id);
                    }
                })
            })

            function renderSelect2() {
                $('#ventamethodpayment_id, #cuentaventa_id, #cuotamethodpayment_id, #cuentacuota_id')
                    .select2().on('select2:open', function(e) {
                        const evt = "scroll.select2";
                        $(e.target).parents().off(evt);
                        $(window).off(evt);
                    });

                $('#cuentaventa_id').on("change", (e) => {
                    deshabilitarSelects();
                    @this.cuentaventa_id = e.target.value;
                });

                $('#cuentacuota_id').on("change", (e) => {
                    deshabilitarSelects();
                    @this.cuenta_id = e.target.value;
                });
            }

            function deshabilitarSelects() {
                $("#cuotamethodpayment_id, #ventamethodpayment_id, #cuentaventa_id, #cuentacuota_id").attr(
                    "disabled", true);
            }

        })
    </script>
</div>
