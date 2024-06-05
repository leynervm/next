<div>
    <div wire:loading.flex class="loading-overlay rounded h-[calc(100vh-10px)] hidden">
        <x-loading-next />
    </div>

    @if (count($sumatorias) > 0)
        <div class="w-full flex flex-wrap gap-5">
            @foreach ($sumatorias as $item)
                <x-minicard :title="null" size="lg" class="cursor-pointer">
                    <div class="text-xs font-medium text-center">
                        <small>PENDIENTE COBRAR</small>
                        <h3 class="font-semibold text-lg">
                            {{ number_format($item->total, 2, '.', ', ') }}</h3>
                        <small>{{ $item->moneda->currency }}</small>
                    </div>
                </x-minicard>
            @endforeach
        </div>
    @endif


    <div class="flex flex-wrap gap-1 w-full mt-3">
        <div class="w-full sm:max-w-sm">
            <x-label value="Buscar cliente :" />
            <div class="relative flex items-center w-full">
                <span class="absolute">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 mx-3 text-primary">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </span>
                <x-input placeholder="Buscar documento, nombres del cliente..." class="block w-full pl-9"
                    wire:model.lazy="search">
                </x-input>
            </div>
        </div>

        <div class="w-full sm:max-w-xs">
            <x-label value="Comprobante :" />
            <x-input placeholder="Buscar comprobante..." class="block w-full" wire:model.lazy="comprobante">
            </x-input>
        </div>

        <div class="w-full max-w-xs">
            <x-label value="Fecha pago :" />
            <x-input type="date" wire:model.lazy="datepay" class="w-full block" />
        </div>
    </div>

    @if ($cuotas->hasPages())
        <div class="pt-3 pb-1">
            {{ $cuotas->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="w-full relative mt-1">
        <x-table class="w-full relative mt-1">
            <x-slot name="header">
                <tr>
                    <th scope="col" class="p-2 font-medium">
                        <button class="flex items-center gap-x-3 focus:outline-none">
                            <span>COMPROBAMNTE</span>

                            {{-- <svg class="h-3" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg"> --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 10 11"
                                stroke-width="0.1" stroke="currentColor" class="w-3 h-3">
                                <path
                                    d="M2.13347 0.0999756H2.98516L5.01902 4.79058H3.86226L3.45549 3.79907H1.63772L1.24366 4.79058H0.0996094L2.13347 0.0999756ZM2.54025 1.46012L1.96822 2.92196H3.11227L2.54025 1.46012Z" />
                                <path
                                    d="M0.722656 9.60832L3.09974 6.78633H0.811638V5.87109H4.35819V6.78633L2.01925 9.60832H4.43446V10.5617H0.722656V9.60832Z" />
                                <path
                                    d="M8.45558 7.25664V7.40664H8.60558H9.66065C9.72481 7.40664 9.74667 7.42274 9.75141 7.42691C9.75148 7.42808 9.75146 7.42993 9.75116 7.43262C9.75001 7.44265 9.74458 7.46304 9.72525 7.49314C9.72522 7.4932 9.72518 7.49326 9.72514 7.49332L7.86959 10.3529L7.86924 10.3534C7.83227 10.4109 7.79863 10.418 7.78568 10.418C7.77272 10.418 7.73908 10.4109 7.70211 10.3534L7.70177 10.3529L5.84621 7.49332C5.84617 7.49325 5.84612 7.49318 5.84608 7.49311C5.82677 7.46302 5.82135 7.44264 5.8202 7.43262C5.81989 7.42993 5.81987 7.42808 5.81994 7.42691C5.82469 7.42274 5.84655 7.40664 5.91071 7.40664H6.96578H7.11578V7.25664V0.633865C7.11578 0.42434 7.29014 0.249976 7.49967 0.249976H8.07169C8.28121 0.249976 8.45558 0.42434 8.45558 0.633865V7.25664Z"
                                    stroke-width="0.3" />
                            </svg>
                        </button>
                    </th>

                    <th scope="col" class="p-2 font-medium">
                        CLIENTE
                    </th>
                    <th scope="col" class="p-2 font-medium">
                        PRÓXIMA FECHA PAGO
                    </th>
                    <th scope="col" class="p-2 font-medium">
                        MONTO</th>
                    <th scope="col" class="p-2 font-medium">
                        SALDO PENDIENTE</th>
                    <th scope="col" class="p-2 font-medium">
                        MONTO TOTAL</th>
                    <th scope="col" class="p-2 font-medium">
                        SUCURSAL</th>
                </tr>
            </x-slot>
            @if (count($cuotas))
                <x-slot name="body">
                    @foreach ($cuotas as $item)
                        <tr>
                            <td class="p-2 text-[10px]">
                                @can('admin.ventas.edit')
                                    <a class="inline-block text-linktable hover:text-hoverlinktable transition-all ease-in-out duration-150"
                                        href="{{ route('admin.ventas.edit', $item->id) }}">
                                        {{ $item->seriecompleta }}
                                        <br>
                                        {{ $item->comprobante->seriecomprobante->typecomprobante->descripcion }}
                                    </a>
                                @endcan

                                @cannot('admin.ventas.edit')
                                    <a class="inline-block text-linktable">
                                        {{ $item->seriecompleta }}
                                        <br>
                                        {{ $item->comprobante->seriecomprobante->typecomprobante->descripcion }}
                                    </a>
                                @endcannot
                            </td>
                            <td class="p-2">
                                <p>{{ $item->client->document }}</p>
                                <p class="text-[10px]">{{ $item->client->name }}</p>
                            </td>
                            <td class="p-2 text-center uppercase">
                                @if (count($item->cuotas))
                                    {{-- <p>{{ $item->nextpagos }}</p> --}}
                                    @php
                                        $fechapago = $item->cuotas->first()->expiredate;
                                    @endphp
                                    <p>
                                        {{ formatDate($fechapago, 'DD MMMM Y') }}
                                    </p>

                                    @if (\Carbon\Carbon::parse($fechapago)->isFuture())
                                        <x-span-text text="PRÓXIMO" class="leading-3" type="blue" />
                                    @elseif (\Carbon\Carbon::parse($fechapago)->isToday())
                                        <x-span-text text="HOY" class="leading-3" type="green" />
                                    @elseif (\Carbon\Carbon::parse($fechapago)->isPast())
                                        <x-span-text text="VENCIDO" class="leading-3" type="red" />
                                    @endif
                                @endif
                            </td>
                            <td class="p-2 text-center">
                                @if (count($item->cuotas))
                                    <x-span-text :text="'CUOTA' . substr('000' . $item->cuotas->first()->cuota, -3)" class="leading-3" />

                                    <p>{{ $item->moneda->simbolo }}
                                        {{ number_format($item->cuotas->first()->amount, 3, '.', ', ') }}
                                    </p>
                                @endif
                            </td>
                            <td class="p-2 text-center">
                                {{ $item->moneda->simbolo }}
                                {{ number_format($item->cuotas->whereNull('cajamovimiento_id')->sum('amount'), 3, '.', ', ') }}
                            </td>
                            <td class="p-2 text-center">
                                {{ $item->moneda->simbolo }}
                                {{ number_format($item->total - $item->paymentactual, 3, '.', ', ') }}
                            </td>

                            <td class="p-2 text-[10px] text-center">
                               {{$item->sucursal->name}}
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            @endif
        </x-table>
    </div>
</div>
