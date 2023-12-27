<div>
    <div class="w-full flex flex-col md:flex-row-reverse flex-wrap items-end justify-between gap-2 py-3">
        <x-minicard :title="'S/.' . number_format($amountdeuda, 2, '.', ', ')" size="md" class="text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14" stroke-width="1" stroke="currentColor"
                class="w-8 h-8 mx-auto">
                <path
                    d="M6.50 5.166s-0.458 -0.511 -1.201 -0.447c-0.743 0.064 -1.1 0.52 -1.1 0.978 0 1.334 2.301 0.409 2.301 1.777 0 0.706 -1.527 1.203 -2.436 0.373" />
                <path d="m8.98 4.20 -1.5 4.5" />
                <path
                    d="M6.5 11.089c2.938 0 4.59 -1.652 4.59 -4.589 0 -2.937 -1.653 -4.589 -4.59 -4.589 -2.937 0 -4.589 1.652 -4.589 4.589 0 2.937 1.653 4.589 4.589 4.589Z" />
            </svg>
            <p class="text-[10px] font-semibold leading-3 text-center uppercase">Saldo total pendiente</p>
        </x-minicard>

        <div class="flex justify-between gap-1 w-full md:w-auto md:justify-start">
            <div class="relative flex items-center w-full md:w-auto">
                <span class="absolute">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 mx-3 text-primary">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </span>
                <x-input placeholder="Buscar" class="block w-full md:w-80 pl-9" wire:model.lazy="search">
                </x-input>
            </div>

            <x-input type="date" class="uppercase" wire:model.lazy="datepay" />
        </div>
    </div>

    @if ($cuotas->hasPages())
        <div class="pt-3 pb-1">
            {{ $cuotas->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div class="w-full relative mt-1" x-data="{ loading: false }" x-init="loadingData">
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
                        PAGAR</th>
                </tr>
            </x-slot>
            @if (count($cuotas))
                <x-slot name="body">
                    @foreach ($cuotas as $item)
                        <tr>
                            <td class="p-2 text-[10px]">
                                {{ $item->comprobante->seriecomprobante->typecomprobante->descripcion }}
                                <p class="font-semibold">
                                    {{ $item->comprobante->seriecompleta }}</p>
                            </td>
                            <td class="p-2 text-xs">
                                <div>
                                    <h4>{{ $item->client->document }}</h4>
                                    <p class="text-[10px]">{{ $item->client->name }}</p>
                                </div>
                            </td>
                            <td class="p-2 text-xs text-center uppercase">
                                @if (count($item->cuotas))
                                    {{-- <p>{{ $item->nextpagos }}</p> --}}
                                    @php
                                        $fechapago = $item->cuotas->first()->expiredate;
                                    @endphp
                                    <p>
                                        {{ Carbon\Carbon::parse($fechapago)->locale('es')->isoformat('DD MMMM Y') }}
                                    </p>

                                    @if (\Carbon\Carbon::parse($fechapago)->isFuture())
                                        <p
                                            class="inline-block bg-blue-100 text-blue-600 text-[10px] text-center rounded p-1 py-0.5">
                                            PROXIMO
                                        </p>
                                    @elseif (\Carbon\Carbon::parse($fechapago)->isToday())
                                        <p
                                            class="inline-block bg-green-100 text-green-600 text-[10px] text-center rounded p-1 py-0.5 animate-bounce">
                                            HOY
                                        </p>
                                    @elseif (\Carbon\Carbon::parse($fechapago)->isPast())
                                        <p
                                            class="inline-block bg-red-100 text-red-600 text-[10px] text-center rounded p-1 py-0.5">
                                            VENCIDO
                                        </p>
                                    @endif
                                @endif
                            </td>
                            <td class="p-2 text-[10px] text-center">
                                @if (count($item->cuotas))
                                    <p
                                        class="inline-block bg-fondospancardproduct text-textspancardproduct text-[10px] text-center rounded-lg font-semibold p-1 py-0.5">
                                        Cuota{{ substr('000' . $item->cuotas->first()->cuota, -3) }}
                                    </p>
                                    <p>{{ $item->moneda->simbolo }}{{ number_format($item->cuotas->first()->amount, 2, '.', ', ') }}
                                    </p>
                                @endif
                            </td>
                            <td class="p-2 text-[10px] text-center">
                                {{ $item->moneda->simbolo }}
                                {{ number_format($item->cuotas->whereNull('cajamovimiento_id')->sum('amount'), 2, '.', ', ') }}
                            </td>
                            <td class="p-2 text-[10px] text-center">
                                {{ $item->moneda->simbolo }}
                                {{ number_format($item->cuotas->sum('amount'), 2, '.', ', ') }}
                            </td>
                            <td class="p-2 text-center text-[10px] align-middle">
                                @if ($item->cajamovimiento)
                                    <div
                                        class="inline-block text-[10px] text-center rounded-full p-1 bg-green-100 text-next-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path
                                                d="M20.9953 6.96425C21.387 6.57492 21.3889 5.94176 20.9996 5.55005C20.6102 5.15834 19.9771 5.15642 19.5854 5.54575L8.97661 16.0903L4.41377 11.5573C4.02196 11.1681 3.3888 11.1702 2.99956 11.562C2.61032 11.9538 2.6124 12.5869 3.0042 12.9762L8.27201 18.2095C8.66206 18.597 9.29179 18.5969 9.68175 18.2093L20.9953 6.96425Z"
                                                fill="black" />
                                        </svg>
                                    </div>
                                @else
                                    <div class="flex items-center justify-center">
                                        <x-link-button href="{{ route('admin.ventas.show', $item->id) }}">
                                            CUOTAS
                                        </x-link-button>
                                    </div>
                                @endif
                            </td>
                            {{-- <td class="p-2 text-[10px] text-center">
                            @if ($item->userpay)
                                {{ $item->userpay->name }}
                            @endif
                        </td> --}}
                        </tr>
                    @endforeach
                </x-slot>
            @endif
        </x-table>
        <div x-show="loading" wire:loading wire:loading.flex class="loading-overlay rounded">
            <x-loading-next />
        </div>
    </div>

    <script>
        function loadingData() {
            Livewire.hook('message.sent', () => {
                loading = true;
            });

            Livewire.hook('message.processed', () => {
                loading = false;
            });
        }
    </script>
</div>
