<div>
    <div class="flex flex-wrap items-center gap-2 mt-4 mb-1">
        <div class="w-full xs:max-w-md">
            <x-label value="Buscar :" />
            <div class="relative w-full flex items-center">
                <span class="absolute">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 mx-3 text-next-300">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </span>
                <x-input placeholder="N° Transacción, nombres del usuario" class="block w-full pl-9"
                    wire:model.lazy="search" />
            </div>
        </div>
        <div class="w-full xs:max-w-[170px]">
            <x-label value="Fecha :" />
            <x-input type="date" wire:model.lazy="date" class="w-full" />
        </div>

        <div class="w-full xs:max-w-[170px]">
            <x-label value="Hasta :" />
            <x-input type="date" wire:model.lazy="dateto" class="w-full" />
        </div>
    </div>

    <x-table>
        <x-slot name="header">
            <tr>
                <th scope="col" class="p-2 font-medium">
                    <button class="flex items-center gap-x-3 focus:outline-none">
                        <span>FECHA</span>
                        <svg class="h-3 w-3" viewBox="0 0 10 11" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" stroke="currentColor" stroke-width="0.1">
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
                <th scope="col" class="p-2 font-mediumt">
                    ID TRANSACCIÓN</th>
                <th scope="col" class="p-2 font-medium">
                    USUARIO</th>
                <th scope="col" class="p-2 font-medium">
                    MÉTODO DE PAGO</th>
                <th scope="col" class="p-2 font-medium">
                    MONTO</th>
                <th scope="col" class="p-2 font-medium">
                    ESTADO</th>
            </tr>
        </x-slot>
        @if (count($transaccions))
            <x-slot name="body">
                @foreach ($transaccions as $item)
                    <tr>
                        <td class="p-2 text-[10px]">
                            {{ formatDate($item->date, 'DD MMMM Y') }}
                        </td>
                        <td class="p-2 text-xs text-center">
                            {{ $item->transaction_id }}
                        </td>
                        <td class="p-2 text-center">
                            {{ $item->user->name }} <br>
                            {{ $item->user->email }}
                        </td>

                        <td class="p-2 align-middle text-center">
                            @if ($item->brand == 'visa')
                                <svg class="w-10 h-6 block mx-auto">
                                    <use href="#visa" />
                                </svg>
                            @elseif ($item->brand == 'mastercard')
                                <svg class="w-10 h-6 block mx-auto">
                                    <use href="#mastercard" />
                                </svg>
                            @elseif ($item->brand == 'paypal')
                                <svg class="w-10 h-6 block mx-auto">
                                    <use href="#paypal" />
                                </svg>
                            @elseif ($item->brand == 'unionpay')
                                <svg class="w-10 h-6 block mx-auto">
                                    <use href="#unionpay" />
                                </svg>
                            @elseif ($item->brand == 'dinersclub')
                                <svg class="w-10 h-6 block mx-auto">
                                    <use href="#dinersclub" />
                                </svg>
                            @elseif ($item->brand == 'amex')
                                <svg class="w-10 h-6 block mx-auto">
                                    <use href="#amex" />
                                </svg>
                            @else
                                <svg class="w-10 h-6 block mx-auto">
                                    <use href="#default" />
                                </svg>
                            @endif

                            {{ $item->brand }}
                            <br>
                            {{ $item->card }}
                        </td>
                        <td class="p-2 text-sm font-semibold text-center">
                            <small class="font-medium text-[10px]">{{ $item->order->moneda->simbolo }}</small>
                            {{ decimalOrInteger($item->amount, 2, ', ') }}
                        </td>
                        <td class="p-2 align-middle text-center">
                            <x-span-text :text="$item->action_description" class="text-xs inline-block" type="green" />
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        @endif
    </x-table>

    @if ($transaccions->hasPages())
        <div class="w-full flex justify-center items-center sm:justify-end p-1 sticky -bottom-1 right-0 bg-body">
            {{ $transaccions->onEachSide(0)->links('livewire::pagination-default') }}
        </div>
    @endif

    <div wire:key="loadingtransaccions" wire:loading.flex class="loading-overlay fixed hidden">
        <x-loading-next />
    </div>

    @include('partials.icons-cards')
</div>
