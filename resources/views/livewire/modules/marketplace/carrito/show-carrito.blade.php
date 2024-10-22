<div class="py-5">
    @if (Cart::instance('shopping')->count() > 0)
        <div class="w-full md:max-w-3xl mx-auto flex flex-col gap-10">
            <div class="w-full">
                <div class="w-full flex items-end justify-between border-b border-borderminicard">
                    <h1 class="text-lg font-semibold text-primary">CARRITO DE COMPRAS</h1>
                    <x-button-secondary wire:click="delete" wire:loading.attr="disabled"
                        class="hidden sm:inline-flex items-center gap-1 mb-2">
                        ELIMINAR CARRITO</x-button-secondary>
                </div>

                <div class="w-full flex flex-col gap-3">
                    @foreach (Cart::instance('shopping')->content() as $item)
                        <div class="w-full flex gap-2 text-xs sm:py-3">
                            <div class="w-20 h-20 xs:w-24 xs:h-24 rounded overflow-hidden">
                                @if ($item->model->getImageURL())
                                    <img src="{{ $item->model->getImageURL() }}" alt=""
                                        class="w-full h-full object-cover object-center">
                                @else
                                    <x-icon-file-upload class="!w-full !h-full !m-0 !border-0 text-colorsubtitleform"
                                        type="unknown" />
                                @endif
                            </div>
                            <div class="w-full flex flex-col gap-1 flex-1 xs:h-full justify-between p-1 px-2">
                                <p class="leading-3 text-xs text-colorlabel">{{ $item->model->name }}</p>

                                @if (count($item->options->carshoopitems) > 0)
                                    <div class="w-full mb-2 mt-1">
                                        @foreach ($item->options->carshoopitems as $itemcarshop)
                                            <h1 class="text-primary text-[10px] leading-3 text-left">
                                                <span class="w-1.5 h-1.5 bg-primary inline-block rounded-full"></span>
                                                {{ $itemcarshop->name }}
                                            </h1>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="w-full flex flex-wrap items-start justify-between gap-2">
                                    <h1 class="text-sm text-green-500">
                                        <small class="text-[10px] text-colorsubtitleform">IMPORTE :
                                            {{ $item->options->simbolo }}</small>
                                        {{ number_format($item->price * $item->qty, 2, '.', ', ') }}
                                        <small
                                            class="text-[10px] text-colorsubtitleform">{{ $item->options->currency }}</small>
                                    </h1>
                                    <div class="text-[10px] text-colorsubtitleform text-end">
                                        <h1 class=" leading-3">
                                            P. UNIT : {{ $item->options->simbolo }}
                                            {{ number_format($item->price, 2, '.', ', ') }}
                                        </h1>
                                    </div>
                                </div>

                                <div class="w-full mt-auto flex items-end gap-1 justify-between">
                                    <div class="w-full flex-1 flex gap-1">
                                        <button wire:click="decrement('{{ $item->rowId }}')" type="button"
                                            wire:loading.attr="disabled"
                                            @if ($item->qty == 1) disabled @endif
                                            class="font-medium hover:bg-neutral-400 hover:ring-2 hover:ring-neutral-300 text-xl w-9 h-9 bg-neutral-300 text-gray-500 p-2.5 pt-1.5 align-middle inline-flex items-center justify-center rounded-xl disabled:opacity-25 disabled:ring-0 disabled:hover:bg-neutral-300 transition ease-in-out duration-150">-</button>

                                        <x-input value="{{ $item->qty }}"
                                            class="w-14 text-center text-colorlabel input-number-none" type="number"
                                            step="1" min="1" onkeypress="return validarNumero(event, 4)"
                                            x-on:blur="if ($el.value == '' || $el.value == 0 ) {
                                                $el.value = {{ $item->qty }}
                                            }
                                            if ( $el.value != {{ $item->qty }}) {
                                                console.log('update')
                                                $wire.updateitem('{{ $item->rowId }}', $el.value)
                                            }" />
                                        <button wire:click="increment('{{ $item->rowId }}')" type="button"
                                            wire:loading.attr="disabled"
                                            class="font-medium hover:bg-neutral-400 hover:ring-2 hover:ring-neutral-300 text-xl w-9 h-9 bg-neutral-300 text-gray-500 p-2.5 pt-1.5 align-middle inline-flex items-center justify-center rounded-xl disabled:opacity-25 disabled:ring-0 disabled:hover:bg-neutral-300 transition ease-in-out duration-150">+</button>
                                    </div>
                                    <div x-data="{ dropdownOpen: false }" class="relative">
                                        <button @click="dropdownOpen = !dropdownOpen"
                                            class="p-1.5 mx-auto bg-fondominicard text-colorsubtitleform block rounded-lg transition-colors duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path
                                                    d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                            </svg>
                                        </button>
                                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                                            class="absolute min-w-max text-colorsubtitleform text-xs overflow-hidden right-0 mt-1 w-36 bottom-0 bg-fondodropdown rounded-md shadow shadow-shadowminicard z-20">
                                            <button type="button" @click="dropdownOpen = false"
                                                wire:click="move_to_wishlist('{{ $item->rowId }}')"
                                                wire:loading.attr="disabled"
                                                class="w-full p-2.5 leading-3 hover:bg-fondohoverdropdown flex gap-1 items-center justify-between disabled:opacity-25">
                                                Mover a mi lista de deseos
                                            </button>
                                            <button type="button" @click="dropdownOpen = false"
                                                wire:click="deleteitem('{{ $item->rowId }}')"
                                                wire:loading.attr="disabled"
                                                class="w-full p-2.5 leading-3 hover:bg-fondohoverdropdown flex gap-1 items-center justify-between disabled:opacity-25">
                                                Eliminar de mi carrito
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                @if (!is_null($item->options->promocion_id))
                                    @php
                                        $prm = \App\Models\Promocion::find($item->options->promocion_id);
                                        $prm = verifyPromocion($prm);
                                    @endphp
                                    @if (is_null($prm))
                                        <span
                                            class="text-red-600 mr-auto inline-block ring-1 ring-red-600 text-[10px] p-0.5 px-1 rounded-lg mt-1">
                                            PROMOCIÓN AGOTADO</span>
                                    @else
                                        <span
                                            class="text-green-600 inline-block ring-1 ring-green-600 text-[10px] p-0.5 px-1 rounded-lg mt-1 mr-auto">
                                            PROMOCIÓN</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="w-full">
                <h1 class="text-lg font-semibold text-primary border-b border-borderminicard">RESUMEN DEL PEDIDO</h1>

                <div class="mt-2 w-full grid grid-cols-2 gap-3">
                    <p class="text-sm text-colorsubtitleform font-medium">
                        TOTAL</p>
                    <p class="text-3xl font-semibold text-colorlabel text-end">
                        <small class="text-[10px] font-medium">{{ $moneda->simbolo }}</small>
                        {{ formatDecimalOrInteger(Cart::instance('shopping')->subtotal(), 2, ' ,') }}
                    </p>
                </div>
                <div class="w-full p-1 flex justify-end items-end">
                    <a href="{{ route('carshoop.create') }}" class="btn-next">
                        <span class="btn-effect"><span>CONTINUAR COMPRA</span></span>
                    </a>
                </div>
            </div>
        </div>
    @else
        {{-- <div class="w-full max-w-md flex-shrink bg-white shadow-xl rounded-xl"> --}}
        <h1 class="text-xs p-2 font-medium text-colorsubtitleform">NO EXISTEN PRODUCTOS AGREGADOS EN EL CARRITO...</h1>
        {{-- </div> --}}
    @endif

    <script>
        document.addEventListener('livewire:load', data => {
            document.addEventListener('updatecart', data => {
                @this.render();
            })
        })
    </script>
</div>
