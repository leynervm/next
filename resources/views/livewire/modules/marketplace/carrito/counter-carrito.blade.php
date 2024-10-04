<div class="group" x-data="{ opencounter: @entangle('opencounter'), cartOpen: false, isOpen: false }">
    <button @click="cartOpen = !cartOpen" wire:click="open"
        class="hidden xl:flex p-3 px-1 sm:px-3 w-full hover:text-neutral-200 h-full justify-start items-center text-white">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
            stroke-linecap="round" stroke-linejoin="round" class="block w-8 h-8">
            <circle cx="8" cy="21" r="1" />
            <circle cx="19" cy="21" r="1" />
            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
        </svg>
    </button>
    <a href="{{ route('carshoop') }}"
        class="flex xl:hidden p-3 px-1 sm:px-3 w-full hover:text-neutral-200 h-full justify-start items-center text-white">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="block w-8 h-8">
            <circle cx="8" cy="21" r="1" />
            <circle cx="19" cy="21" r="1" />
            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
        </svg>
    </a>
    <span id="countercart"
        class="{{ Cart::instance('shopping')->count() == 0 ? 'hidden' : 'flex' }} absolute w-4 h-4 top-0 -right-1 xl:right-1 tracking-tight h-100 justify-center items-center leading-3 text-[9px] bg-white text-next-500 rounded-full">
        {{ Cart::instance('shopping')->count() }}
    </span>

    @if (Cart::instance('shopping')->count() > 0)
        <div x-show="opencounter" x-cloak style="display: none;" @click.outside="opencounter=false"
            class="hidden xl:block absolute z-[1000] w-96 right-0 translate-y-[100%] bg-fondodropdown rounded-xl bottom-0 shadow-md">
            <h1 class="text-xs p-2 border-b border-b-borderminicard font-semibold text-colorsubtitleform">
                CARRITO DE COMPRAS</h1>
            <div class="w-full xl:max-h-[350px] xl:overflow-y-auto">
                @foreach (Cart::instance('shopping')->content() as $item)
                    @if (!is_null($item->model))
                        <div class="w-full border-b border-b-borderminicard flex gap-2 p-1 text-xs">
                            <div class="w-14 h-14 rounded overflow-hidden">
                                @if ($item->model->getImageURL())
                                    <img src="{{ $item->model->getImageURL() }}" alt=""
                                        class="w-full h-full object-cover" />
                                @else
                                    <x-icon-file-upload class="!w-full !h-full !m-0 !border-0 text-colorsubtitleform"
                                        type="unknown" />
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="leading-3 text-xs text-colorlabel">
                                    {{ $item->model->name }}</p>
                                <div class="w-full flex justify-between items-end gap-2">
                                    <h1 class="text-xs text-colorsubtitleform">
                                        <small class="text-[10px]">P. UNIT : </small>
                                        {{ formatDecimalOrInteger($item->price, 2, ', ') }}
                                    </h1>
                                    <h1 class="text-xs text-colorlabel">
                                        <small class="text-[10px] text-colorsubtitleform">
                                            SUBTOTAL : {{ $item->options->simbolo }}</small>
                                        {{ formatDecimalOrInteger($item->price * $item->qty, 2, ', ') }}
                                    </h1>
                                </div>

                                <div class="flex justify-between items-end gap-1">
                                    <div class="w-full flex-1">
                                        <button wire:click="decrement('{{ $item->rowId }}')" type="button"
                                            wire:loading.attr="disabled"
                                            @if ($item->qty == 1) disabled @endif
                                            class="font-medium hover:bg-neutral-400 hover:ring-2 hover:ring-neutral-300 text-xl w-9 h-9 bg-neutral-300 text-gray-500 p-2.5 pt-1.5 align-middle inline-flex items-center justify-center rounded-xl disabled:opacity-25 disabled:ring-0 disabled:hover:bg-neutral-300 transition ease-in-out duration-150">-</button>
                                        <small
                                            class="font-medium text-xs px-2 text-colorlabel inline-block text-center">
                                            {{ $item->qty }}</small>
                                        <button wire:click="increment('{{ $item->rowId }}')" type="button"
                                            wire:loading.attr="disabled"
                                            class="font-medium hover:bg-neutral-400 hover:ring-2 hover:ring-neutral-300 text-xl w-9 h-9 bg-neutral-300 text-gray-500 p-2.5 pt-1.5 align-middle inline-flex items-center justify-center rounded-xl disabled:opacity-25 disabled:ring-0 disabled:hover:bg-neutral-300 transition ease-in-out duration-150">+</button>
                                    </div>

                                    <x-button-delete wire:click="deleteitem('{{ $item->rowId }}')"
                                        wire.loading.attr="disabled" />
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="p-3 rounded-b-xl bg-next-800 w-full flex justify-between items-center flex-row gap-2">
                <div>
                    <p class="text-[10px] text-white">TOTAL</p>
                    <p class="text-xl text-white font-semibold">
                        <small class="text-[10px] font-medium">{{ $moneda->simbolo }} </small>
                        {{ number_format(Cart::instance('shopping')->subtotal(), 2, '.', ', ') }}
                    </p>
                </div>
                <a href="{{ route('carshoop') }}" class="btn-next btn-white btn-hover-transparent">
                    <span class="btn-effect"><span>REALIZAR PAGO</span></span>
                </a>
            </div>
        </div>
    @endif


    {{-- <div :class="cartOpen ? 'translate-x-0 ease-out' : 'translate-x-full ease-in'"
        class="fixed right-0 top-28 lg:top-20 max-w-sm w-full h-full transition duration-300 transform overflow-y-auto bg-white border-l-2 border-gray-300">
        <div class="flex items-center justify-between p-3">
            <h3 class="text-2xl font-medium text-gray-700">Your cart</h3>
            <button @click="cartOpen = !cartOpen" class="text-gray-600 focus:outline-none">
                <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <hr class="my-3">

        @if (Cart::instance('shopping')->count() > 0)
            <div class="w-full xl:h-[calc(100vh-20rem)] xl:overflow-y-auto">
                @foreach (Cart::instance('shopping')->content() as $item)
                    @if (!is_null($item->model))
                        <div class="w-full border-b border-b-borderminicard flex gap-2 p-1 text-xs">
                            <div class="w-14 h-14 rounded overflow-hidden">
                                @if ($item->model->getImageURL())
                                    <img src="{{ $item->model->getImageURL() }}" alt=""
                                        class="w-full h-full object-cover" />
                                @else
                                    <x-icon-file-upload class="!w-full !h-full !m-0 !border-0 text-colorsubtitleform"
                                        type="unknown" />
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="leading-3 text-xs text-colorlabel">
                                    {{ $item->model->name }}</p>
                                <div class="w-full flex justify-between items-end gap-2">
                                    <h1 class="text-xs text-colorsubtitleform">
                                        <small class="text-[10px]">P. UNIT : </small>
                                        {{ formatDecimalOrInteger($item->price, 2, ', ') }}
                                    </h1>
                                    <h1 class="text-xs text-colorlabel">
                                        <small class="text-[10px] text-colorsubtitleform">
                                            SUBTOTAL : {{ $item->options->simbolo }}</small>
                                        {{ formatDecimalOrInteger($item->price * $item->qty, 2, ', ') }}
                                    </h1>
                                </div>

                                <div class="flex justify-between items-end gap-1">
                                    <div class="w-full flex-1">
                                        <button wire:click="decrement('{{ $item->rowId }}')" type="button"
                                            wire:loading.attr="disabled"
                                            @if ($item->qty == 1) disabled @endif
                                            class="font-medium hover:bg-neutral-400 hover:ring-2 hover:ring-neutral-300 text-xl w-9 h-9 bg-neutral-300 text-gray-500 p-2.5 pt-1.5 align-middle inline-flex items-center justify-center rounded-xl disabled:opacity-25 disabled:ring-0 disabled:hover:bg-neutral-300 transition ease-in-out duration-150">-</button>
                                        <small
                                            class="font-medium text-xs px-2 text-colorlabel inline-block text-center">
                                            {{ $item->qty }}</small>
                                        <button wire:click="increment('{{ $item->rowId }}')" type="button"
                                            wire:loading.attr="disabled"
                                            class="font-medium hover:bg-neutral-400 hover:ring-2 hover:ring-neutral-300 text-xl w-9 h-9 bg-neutral-300 text-gray-500 p-2.5 pt-1.5 align-middle inline-flex items-center justify-center rounded-xl disabled:opacity-25 disabled:ring-0 disabled:hover:bg-neutral-300 transition ease-in-out duration-150">+</button>
                                    </div>

                                    <x-button-delete wire:click="deleteitem('{{ $item->rowId }}')"
                                        wire.loading.attr="disabled" />
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="p-3 mt-auto rounded-b-xl bg-next-800 w-full flex justify-between items-center flex-row gap-2">
                <div>
                    <p class="text-[10px] text-white">TOTAL</p>
                    <p class="text-xl text-white font-semibold">
                        <small class="text-[10px] font-medium">{{ $moneda->simbolo }} </small>
                        {{ number_format(Cart::instance('shopping')->subtotal(), 2, '.', ', ') }}
                    </p>
                </div>
                <a href="{{ route('carshoop') }}" class="btn-next btn-white btn-hover-transparent">
                    <span class="btn-effect"><span>REALIZAR PAGO</span></span>
                </a>
            </div>
        @endif
    </div> --}}



    <script>
        document.addEventListener('updatecart', data => {
            let counter = document.getElementById('countercart');
            if (typeof data.detail === 'object') {
                counter.style.display = 'none';
            } else {
                counter.style.display = 'flex';
                counter.innerHTML = data.detail;
            }
        })
    </script>
</div>
