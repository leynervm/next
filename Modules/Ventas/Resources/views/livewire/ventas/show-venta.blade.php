<div>

    <div class="w-full flex flex-wrap md:flex-nowrap gap-3">
        <div class="w-full md:w-1/2">
            <x-card-next titulo="Cliente" class="border border-next-500 h-full">
                <div class="w-full">
                    <x-label value="Cliente :" textSize="xs" />
                    <p class="text-xs inline-block p-1 rounded bg-fondospancardproduct text-textspancardproduct">
                        <b>[{{ $venta->client->document }}]</b> {{ $venta->client->name }}
                    </p>
                </div>

                <div class="w-full mt-1">
                    <x-label value="Dirección :" textSize="xs" />
                    <p class="text-xs inline-block p-1 rounded bg-fondospancardproduct text-textspancardproduct">
                        {{ $venta->comprobante->direccion }}</p>
                </div>
            </x-card-next>
        </div>
        <div class="w-full md:w-1/2">
            <x-card-next titulo="Comprobante" class="border border-next-500">
                <div class="w-full flex">
                    <div class="w-full">
                        <x-label value="Comprobante :" textSize="xs" />
                        <span class="text-xs p-1 rounded bg-fondospancardproduct text-textspancardproduct">
                            {{ $venta->comprobante->seriecompleta }}</span>
                    </div>
                    <div class="w-full">
                        <x-label value="Tipo pago :" textSize="xs" />
                        <span class="text-xs p-1 rounded bg-fondospancardproduct text-textspancardproduct">
                            {{ $venta->typepayment->name }}</span>
                    </div>
                </div>

                <div class="w-full mt-1 flex">
                    <div class="w-full">
                        <x-label value="Moneda :" textSize="xs" />
                        <span class="text-xs p-1 rounded bg-fondospancardproduct text-textspancardproduct">
                            {{ $venta->moneda->currency }}</span>
                    </div>
                    <div class="w-full">
                        <x-label value="Cotización vinculada :" textSize="xs" />
                        <span class="text-xs p-1 rounded bg-fondospancardproduct text-textspancardproduct">
                            ******</span>
                    </div>
                </div>
            </x-card-next>
        </div>
    </div>

    <x-card-next titulo="Productos" class="mt-3 border border-next-500">
        @if (count($venta->tvitems))
            <div class="flex gap-2 flex-wrap justify-start mt-1">
                @foreach ($venta->tvitems as $item)
                    <div
                        class="w-full bg-fondominicard flex flex-col justify-between sm:w-60 group rounded shadow shadow-shadowminicard p-1 text-xs relative hover:shadow-md hover:shadow-shadowminicard cursor-pointer">
                        <div class="w-full">
                            @if ($item->increment > 0.0)
                                <h1
                                    class="absolute w-8 h-8 top-1 left-1 flex flex-col items-center justify-center p-1 rounded-full bg-green-500 text-white transition-all ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-2 h-2 block" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M6 11l6-6 6 6M6 19l6-6 6 6"></path>
                                    </svg>
                                    <p class="font-semibold text-[8px]">
                                        {{ \App\Helpers\FormatoPersonalizado::getValue($item->increment) }} %</p>
                                </h1>
                            @endif

                            @if (count($item->producto->images))
                                <div class="w-full h-60 sm:h-32 rounded shadow border">
                                    @if ($item->producto->defaultImage)
                                        <img src="{{ asset('storage/productos/' . $item->producto->defaultImage->first()->url) }}"
                                            alt="" class="w-full h-full object-scale-down">
                                    @else
                                        <img src="{{ asset('storage/productos/' . $item->producto->images->first()->url) }}"
                                            alt="" class="w-full h-full object-scale-down">
                                    @endif
                                </div>
                            @endif

                            <h1 class="text-[10px] font-semibold leading-3 text-center mt-1">
                                {{ $item->producto->name }}</h1>


                            <h1 class="mt-1 text-center text-xs font-semibold leading-3 text-green-500">
                                <span class="text-xs">
                                    S/. {{ number_format($item->subtotal, 2, '.', ', ') }}
                                    S/. {{ number_format($item->total, 2, '.', ', ') }}
                                </span>
                            </h1>

                            <div class="w-full flex gap-1 items-start mt-2">
                                <span
                                    class="text-[9px] font-semibold leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                    P. UNIT: {{ $venta->moneda->simbolo }}
                                    {{ number_format($item->price, 2, '.', ', ') }}
                                </span>
                                <span
                                    class="text-[9px] font-semibold leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                    {{ \App\Helpers\FormatoPersonalizado::getValue($item->cantidad) }}
                                    {{ $item->producto->unit->name }}
                                </span>
                                <span
                                    class="text-[9px] font-semibold leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                    {{ $item->almacen->name }}
                                </span>
                            </div>
                        </div>

                        @if (count($item->itemseries))
                            <h1 class="w-full block text-[10px] mt-2">SERIES</h1>
                            <div class="w-full flex flex-wrap gap-1">
                                @foreach ($item->itemseries as $serie)
                                    <span
                                        class="text-[8px] font-semibold rounded py-0.5 px-1 inline-flex gap-1 items-center bg-fondospancardproduct text-textspancardproduct">
                                        {{ $serie->serie->serie }}
                                        {{-- <x-button-delete></x-button-delete> --}}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="w-full flex items-end gap-1 justify-end mt-2">
                            {{-- <x-button-delete wire:click="deleteitemcart({{ $item->id }})"
                                wire:loading.attr="disabled" wire:target="deleteitemcart"></x-button-delete> --}}

                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-card-next>

    @if ($venta->typepayment->paycuotas)
        <x-card-next titulo="Cuotas pago" class="mt-3 border border-next-500">
            @if (count($venta->cuotas))
                <div class="flex gap-2 flex-wrap justify-start mt-1">
                    @foreach ($venta->cuotas as $item)
                        <div
                            class="w-full sm:w-48 border text-left flex flex-col justify-between text-[10px] rounded shadow-md shadow-shadowminicard p-1 hover:shadow-lg">
                            <div class="w-full">
                                <h1 class="text-xs font-semibold text-center">
                                    Cuota{{ substr('000' . $item->cuota, -3) }}</h1>
                                <div class="text-center">
                                    @if ($item->cajamovimiento)
                                        <p
                                            class="text-center inline-block bg-green-100 text-green-500 p-1 rounded-full font-semibold">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path
                                                    d="M20.9953 6.96425C21.387 6.57492 21.3889 5.94176 20.9996 5.55005C20.6102 5.15834 19.9771 5.15642 19.5854 5.54575L8.97661 16.0903L4.41377 11.5573C4.02196 11.1681 3.3888 11.1702 2.99956 11.562C2.61032 11.9538 2.6124 12.5869 3.0042 12.9762L8.27201 18.2095C8.66206 18.597 9.29179 18.5969 9.68175 18.2093L20.9953 6.96425Z"
                                                    fill="black" />
                                            </svg>
                                        </p>
                                    @else
                                        <p
                                            class="text-center inline-block bg-orange-100 text-orange-500 p-1 rounded-lg font-semibold">
                                            Pendiente</p>
                                    @endif
                                </div>

                                <x-label value="Monto :" textSize="xs" class="mt-1" />
                                <p
                                    class="inline-block font-semibold bg-fondospancardproduct text-textspancardproduct rounded-lg p-1">
                                    {{ $venta->moneda->simbolo }}
                                    {{ number_format($item->amount, 2, '.', ', ') }}</p>

                                <x-label value="Fecha pago :" textSize="xs" class="mt-1" />
                                <p
                                    class="inline-block uppercase bg-fondospancardproduct text-textspancardproduct rounded-lg p-1">
                                    {{ \Carbon\Carbon::parse($item->expiredate)->locale('es')->isoformat('DD MMMM Y') }}
                                </p>

                                @if ($item->cajamovimiento)
                                    <h1 class="mt-2 mb-1 underline text-center text-[9px] font-semibold">DETALLES DEL
                                        PAGO</h1>

                                    <div class="w-full flex flex-wrap gap-1 text-[9px]">
                                        <p
                                            class="inline-block font-semibold uppercase bg-fondospancardproduct text-textspancardproduct rounded-lg p-1">
                                            {{ \Carbon\Carbon::parse($item->cajamovimiento->date)->locale('es')->isoformat('DD MMMM Y') }}
                                        </p>
                                        <p
                                            class="inline-block leading-3 font-semibold bg-fondospancardproduct text-textspancardproduct rounded-lg p-1">
                                            {{ $item->cajamovimiento->methodpayment->name }} </p>
                                        <p
                                            class="inline-block leading-3 font-semibold bg-fondospancardproduct text-textspancardproduct rounded-lg p-1">
                                            USUARIO : {{ $item->cajamovimiento->user->name }} </p>

                                        @if ($item->cajamovimiento->detalle)
                                            <p
                                                class="inline-block leading-3 font-semibold bg-fondospancardproduct text-textspancardproduct rounded-lg p-1">
                                                {{ $item->cajamovimiento->detalle }} </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="w-full mt-2">
                                @if ($item->cajamovimiento)
                                    <div class="w-full flex flex-wrap items-center justify-between">
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
                                        <x-button-delete></x-button-delete>
                                    </div>
                                @else
                                    <x-button class="mx-auto mt-2" wire:click="pay({{ $item->id }})"
                                        wire:loading.attr="disable" wire:target="pay">PAGAR</x-button>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
                <div class="mt-2">
                    <x-button wire:click="editcuotas" wire:loading.attr="disable" wire:target="editcuotas">EDITAR
                        CUOTAS</x-button>
                </div>
            @endif
        </x-card-next>
    @endif

    <div class="w-full flex flex-wrap md:flex-nowrap gap-3 mt-3">
        <div class="w-full @if ($venta->cajamovimiento) md:w-1/2 @endif">
            <x-card-next titulo="Resumen venta" class="h-full border border-next-500">
                <p class="text-[10px]">TOTAL EXONERADO : S/. <span
                        class="font-bold text-xs">{{ number_format($venta->exonerado, 2, '.', ', ') }}</span></p>
                <p class="text-[10px]">TOTAL GRAVADO : S/. <span
                        class="font-bold text-xs">{{ number_format($venta->gravado, 2, '.', ', ') }}</span></p>
                <p class="text-[10px]">TOTAL IGV : S/. <span
                        class="font-bold text-xs">{{ number_format($venta->igv, 2, '.', ', ') }}</span></p>
                <p class="text-[10px]">TOTAL DESCUENTOS : S/. <span
                        class="font-bold text-xs">{{ number_format($venta->descuentos, 2, '.', ', ') }}</span></p>
                <p class="text-[10px]">TOTAL PAGAR : S/. <span
                        class="font-bold text-xs">{{ number_format($venta->total, 2, '.', ', ') }}</span></p>
            </x-card-next>
        </div>
        @if ($venta->cajamovimiento)
            <div class="w-full md:w-1/2">
                <x-card-next titulo="Resumen pago" class="border border-next-500">
                    <div class="flex flex-col gap-1">
                        <div class="w-full flex">
                            <div class="w-full">
                                <x-label value="Fecha pago :" textSize="xs" />
                                <span class="text-xs p-1 rounded bg-fondospancardproduct text-textspancardproduct">
                                    {{ \Carbon\Carbon::parse($venta->cajamovimiento->date)->format('d/m/Y h:i A') }}</span>
                            </div>
                            <div class="w-full">
                                <x-label value="Monto :" textSize="xs" />
                                <span class="text-xs p-1 rounded bg-fondospancardproduct text-textspancardproduct">
                                    {{ $venta->cajamovimiento->moneda->simbolo }}
                                    {{ number_format($venta->cajamovimiento->amount, 2, '.', ', ') }}</span>
                            </div>
                        </div>

                        <div class="w-full flex">
                            <div class="w-full">
                                <x-label value="Moneda :" textSize="xs" />
                                <span class="text-xs p-1 rounded bg-fondospancardproduct text-textspancardproduct">
                                    {{ $venta->cajamovimiento->moneda->currency }}</span>
                            </div>
                            <div class="w-full">
                                <x-label value="Concepto pago :" textSize="xs" />
                                <p
                                    class="text-xs inline-block p-1 rounded bg-fondospancardproduct text-textspancardproduct">
                                    {{ $venta->cajamovimiento->concept->name }}</p>
                            </div>
                        </div>

                        <div class="w-full flex">
                            <div class="w-full">
                                <x-label value="Método pago :" textSize="xs" />
                                <span class="text-xs p-1 rounded bg-fondospancardproduct text-textspancardproduct">
                                    {{ $venta->cajamovimiento->methodpayment->name }}</span>
                            </div>
                            <div class="w-full">
                                <x-label value="Movimiento :" textSize="xs" />
                                <span
                                    class="{{ $venta->cajamovimiento->typemovement == '+' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} text-xs p-1 rounded">
                                    {{ $venta->cajamovimiento->typemovement == '+' ? 'INGRESO' : 'EGRESO' }}</span>
                            </div>
                        </div>

                        <div class="w-full flex">
                            <div class="w-full">
                                <x-label value="Caja pago :" textSize="xs" />
                                <p
                                    class="text-xs inline-block p-1 rounded bg-fondospancardproduct text-textspancardproduct">
                                    {{ $venta->cajamovimiento->opencaja->caja->name }}</p>
                            </div>
                            <div class="w-full">
                                <x-label value="Usuario :" textSize="xs" />
                                <span class="text-xs p-1 rounded bg-fondospancardproduct text-textspancardproduct">
                                    {{ $venta->cajamovimiento->user->name }}</span>
                            </div>
                        </div>

                        @if ($venta->cajamovimiento->cuenta)
                            <div class="w-full">
                                <x-label value="Cuenta pago :" textSize="xs" />
                                <span class="text-xs p-1 rounded bg-fondospancardproduct text-textspancardproduct">
                                    {{ $venta->cajamovimiento->cuenta->account }} -
                                    {{ $venta->cajamovimiento->cuenta->descripcion }}
                                    ({{ $venta->cajamovimiento->cuenta->banco->name }})
                                </span>
                            </div>
                        @endif

                        @if ($venta->cajamovimiento->detalle)
                            <div class="w-full">
                                <x-label value="Descripción :" textSize="xs" />
                                <span class="text-xs p-1 rounded bg-fondospancardproduct text-textspancardproduct">
                                    {{ $venta->cajamovimiento->detalle }}</span>
                            </div>
                        @endif
                    </div>
                </x-card-next>
            </div>
        @endif
    </div>

    <div class="w-full flex items-start justify-end gap-3 mt-3">
        <x-button>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M18 22a3 3 0 100-6 3 3 0 000 6zM18 8a3 3 0 100-6 3 3 0 000 6zM6 15a3 3 0 100-6 3 3 0 000 6z" />
                <path d="M15.5 6.5l-7 4M8.5 13.5l7 4" />
            </svg>
        </x-button>
        <x-button>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M17.571 18H20.4a.6.6 0 00.6-.6V11a4 4 0 00-4-4H7a4 4 0 00-4 4v6.4a.6.6 0 00.6.6h2.829M8 7V3.6a.6.6 0 01.6-.6h6.8a.6.6 0 01.6.6V7" />
                <path
                    d="M6.098 20.315L6.428 18l.498-3.485A.6.6 0 017.52 14h8.96a.6.6 0 01.594.515L17.57 18l.331 2.315a.6.6 0 01-.594.685H6.692a.6.6 0 01-.594-.685z" />
                <path d="M17 10.01l.01-.011" />
            </svg>
        </x-button>

        <x-button>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M6 6h12M6 10h12M13 14h5M13 18h5M2 21.4V2.6a.6.6 0 01.6-.6h18.8a.6.6 0 01.6.6v18.8a.6.6 0 01-.6.6H2.6a.6.6 0 01-.6-.6z" />
                <path d="M6 18v-4h3v4H6z" />
            </svg>
        </x-button>
    </div>

    <x-jet-dialog-modal wire:model="open" maxWidth="lg" footerAlign="justify-end">
        <x-slot name="title">
            {{ __('Realizar pago') }}
            <x-button-add wire:click="$toggle('open')" wire:loading.attr="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </x-button-add>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="savepayment">

                <div class="w-full ">
                    <x-label value="N° Cuota :" textSize="xs" />
                    <p
                        class="inline-block text-[10px] font-semibold bg-fondospancardproduct text-textspancardproduct rounded-lg p-1">
                        Cuota{{ substr('000' . $cuota->cuota, -3) }}
                    </p>
                </div>
                <div class="w-full  mt-2">
                    <x-label value="Monto :" textSize="xs" />
                    <p
                        class="inline-block text-[10px] font-semibold bg-fondospancardproduct text-textspancardproduct rounded-lg p-1">
                        {{ $venta->moneda->simbolo }}
                        {{ number_format($cuota->amount, 2, '.', ', ') }}
                    </p>
                </div>
                <div class="w-full mt-2">
                    <x-label value="Método pago :" textSize="xs" />
                    <x-select class="block w-full" id="cuotamethodpayment_id" wire:model.defer="methodpayment_id"
                        data-placeholder="SELECCIONAR..." data-minimum-results-for-search="Infinity">
                        <x-slot name="options">
                            @if (count($methodpayments))
                                @foreach ($methodpayments as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </x-slot>
                    </x-select>
                    <x-jet-input-error for="methodpayment_id" />
                </div>
                <div class="w-full mt-2">
                    <x-label value="Otros (N° operación , Banco, etc) :" textSize="xs" />
                    <x-input class="block w-full" wire:model.defer="detalle" />
                    <x-jet-input-error for="detalle" />
                </div>

                @if ($errors->any())
                    <div class="mt-2">
                        <x-jet-input-error for="caja_id" />
                        <x-jet-input-error for="concept_id" />
                    </div>
                @endif

                <div class="w-full flex flex-row pt-4 gap-2 justify-end text-right">
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
            <form wire:submit.prevent="updatecuotas" class="w-full flex flex-wrap justify-around gap-2">
                @if (count($cuotas))
                    @foreach ($cuotas as $item)
                        <div class="w-48 rounded p-1 border relative shadow-md hover:shadow-lg">
                            @if (!is_null($item['cajamovimiento']))
                                <span class="absolute right-1 top-1 w-5 h-5 block rounded-full p-1 bg-green-100 text-next-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path
                                            d="M20.9953 6.96425C21.387 6.57492 21.3889 5.94176 20.9996 5.55005C20.6102 5.15834 19.9771 5.15642 19.5854 5.54575L8.97661 16.0903L4.41377 11.5573C4.02196 11.1681 3.3888 11.1702 2.99956 11.562C2.61032 11.9538 2.6124 12.5869 3.0042 12.9762L8.27201 18.2095C8.66206 18.597 9.29179 18.5969 9.68175 18.2093L20.9953 6.96425Z"
                                            fill="black" />
                                    </svg>
                                </span>
                            @endif
                            <h1 class="text-xs font-semibold text-center">
                                Cuota{{ substr('000' . $item['cuota'], -3) }}</h1>
                            <x-label value="Fecha pago :" textSize="xs" class="mt-1" />
                            @if (is_null($item['cajamovimiento']))
                                <x-input class="block w-full" type="date"
                                    wire:model="cuotas.{{ $item['cuota'] - 1 }}.date" />
                            @else
                                <x-disabled-text :text="\Carbon\Carbon::parse($item['date'])->format('d/m/Y')" />
                            @endif
                            <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.date" />

                            <x-label value="Monto Cuota :" textSize="xs" class="mt-1" />
                            @if (is_null($item['cajamovimiento']))
                                <x-input class="block w-full" type="number" min="1" step="0.01"
                                    wire:model="cuotas.{{ $item['cuota'] - 1 }}.amount" />
                            @else
                                <x-disabled-text :text="$item['amount']" />
                            @endif
                            <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.amount" />

                            <x-jet-input-error for="cuotas.{{ $item['cuota'] - 1 }}.cuota" />

                        </div>
                    @endforeach
                @endif

                <x-jet-input-error for="cuotas" />

                <div class="w-full mt-3 flex items-center justify-center">
                    <x-button type="submit" wire:loading.attr="disable" wire:target="updatecuotas">CONFIRMAR
                        CUOTAS</x-button>
                </div>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <script>
        document.addEventListener("livewire:load", () => {

            renderSelect2();

            $("#cuotamethodpayment_id").on("change", (e) => {
                deshabilitarSelects();
                @this.methodpayment_id = e.target.value;
            }).on('select2:open', function(e) {
                const evt = "scroll.select2";
                $(e.target).parents().off(evt);
                $(window).off(evt);
            });

            window.addEventListener('render-showventa-select2', () => {
                renderSelect2();
            });

            function renderSelect2() {
                $("#cuotamethodpayment_id").select2();
            }

            function deshabilitarSelects() {
                $("#cuotamethodpayment_id").attr("disabled", true);
            }

        })
    </script>
</div>
