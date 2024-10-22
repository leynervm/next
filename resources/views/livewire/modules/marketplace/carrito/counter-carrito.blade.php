<div class="group" @click.away="cartOpen = false" x-data="{ cartOpen: false }" x-init="$watch('cartOpen', (value) => {
    if (value) {
        if (!backdrop) {
            document.body.style.overflow = 'hidden';
        }
    } else {
        if (!backdrop) {
            document.body.style.overflow = 'auto';
        }
    }
})"
    @updatecart.window ="(data)=> {
    $wire.render().then((result) => {})
    }">
    <button @click="cartOpen = !cartOpen" wire:loading.attr="disabled"
        class="flex p-3 px-1 sm:px-3 w-full h-full justify-start items-center hover:opacity-80 disabled:opacity-75 disabled:bg-opacity-75 transition ease-in-out duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="block w-8 h-8">
            <circle cx="8" cy="21" r="1" />
            <circle cx="19" cy="21" r="1" />
            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
        </svg>
    </button>
    {{-- <a href="{{ route('carshoop') }}"
        class="flex xl:hidden p-3 px-1 sm:px-3 w-full h-full justify-start items-center hover:opacity-80 transition ease-in-out duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="block w-8 h-8">
            <circle cx="8" cy="21" r="1" />
            <circle cx="19" cy="21" r="1" />
            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
        </svg>
    </a> --}}

    @if (Cart::instance('shopping')->count() > 0)
        <span id="countercart"
            class="flex absolute w-4 h-4 top-0.5 -right-1 xl:right-1 tracking-tight h-100 justify-center items-center leading-3 text-[9px] bg-fondobadgemarketplace text-colorbadgemarketplace rounded-full">
            {{ Cart::instance('shopping')->count() }}
        </span>

        <div style="transform:translateX(100%)" x-cloak
            x-bind:style="cartOpen ? 'transform:translateX(0)' : 'transform:translateX(100%)'"
            class="fixed z-[699] right-0 top-0 max-w-sm w-full h-full flex flex-col justify-center transform overflow-y-auto transition duration-300"
            :class="cartOpen ? 'ease-out' : 'ease-in'">
            <div
                class="w-full bg-fondominicard sm:rounded-tl-xl md:shadow-inner md:shadow-shadowminicard h-full flex-1 overflow-y-auto items-center justify-between p-3">
                <div class="w-full flex justify-between items-center gap-2 pb-3 border-b border-borderminicard">
                    <h3 class="text-2xl font-medium text-colorsubtitleform">Mi carrito</h3>
                    <button @click="cartOpen = !cartOpen" class="text-colorsubtitleform focus:outline-none">
                        <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="">
                    @foreach (Cart::instance('shopping')->content() as $item)
                        @if (!is_null($item->model))
                            <div class="w-full border-b border-b-borderminicard flex gap-2 p-1 text-xs">
                                <div class="w-14 h-14 rounded overflow-hidden">
                                    @if ($item->model->getImageURL())
                                        <img src="{{ $item->model->getImageURL() }}" alt=""
                                            class="w-full h-full object-cover" />
                                    @else
                                        <x-icon-file-upload
                                            class="!w-full !h-full !m-0 !border-0 text-colorsubtitleform"
                                            type="unknown" />
                                    @endif
                                </div>
                                <div class="flex-1">
                                    @if (!is_null($item->options->promocion_id))
                                        @php
                                            $prm = \App\Models\Promocion::find($item->options->promocion_id);
                                            $prm = verifyPromocion($prm);
                                        @endphp
                                        @if (is_null($prm))
                                            <span
                                                class="text-red-600 mb-1 mr-auto inline-block ring-1 ring-red-600 text-[10px] p-0.5 px-1 rounded-lg mt-1">
                                                PROMOCIÓN AGOTADO</span>
                                        @else
                                            <span
                                                class="text-green-600 mb-1 inline-block ring-1 ring-green-600 text-[10px] p-0.5 px-1 rounded-lg mt-1 mr-auto">
                                                PROMOCIÓN</span>
                                        @endif
                                    @endif

                                    <p class="leading-3 text-xs text-colorlabel">
                                        {{ $item->model->name }}</p>
                                    @if (count($item->options->carshoopitems) > 0)
                                        <div class="w-full mb-2 mt-1">
                                            @foreach ($item->options->carshoopitems as $itemcarshop)
                                                <h1 class="text-primary text-[10px] leading-3 text-left">
                                                    <span
                                                        class="w-1.5 h-1.5 bg-primary inline-block rounded-full"></span>
                                                    {{ $itemcarshop->name }}
                                                </h1>
                                            @endforeach
                                        </div>
                                    @endif

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
            </div>
            <div
                class="w-full flex-shrink-0 p-3 mt-auto sm:rounded-bl-xl bg-next-800 flex justify-between items-center flex-row gap-2">
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

    {{-- <script>
        document.addEventListener('updatecart', data => {
            console.log(data);
            let counter = document.getElementById('countercart');
            if (typeof data.detail === 'object') {
                counter.style.display = 'none';
            } else {
                counter.style.display = 'flex';
                counter.innerHTML = data.detail;
            }
        })
    </script> --}}
</div>
