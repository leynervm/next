<div class="py-5">
    @if (Cart::instance('shopping')->count() > 0)
        <div class="w-full grid grid-cols-1 lg:grid-cols-2 items-start gap-5">
            <div class="w-full flex flex-wrap gap-2 ">
                <h1 class="text-xs font-semibold text-colorlabel">CARRITO DE COMPRAS</h1>
                @foreach (Cart::instance('shopping')->content() as $item)
                    <x-simple-card
                        class="w-full flex flex-col xs:flex-row gap-2 text-xs rounded xs:rounded-xl shadow-md">
                        <div class="w-full xs:w-24 h-24 xs:h-full rounded overflow-hidden">
                            @if ($item->model->getImageURL())
                                <img src="{{ $item->model->getImageURL() }}" alt=""
                                    class="w-full h-full object-scale-down aspect-square">
                            @else
                                <x-icon-file-upload class="!w-full !h-full !m-0 text-neutral-500" type="unknown" />
                            @endif
                        </div>
                        <div class="w-full flex flex-col gap-2 flex-1 xs:h-full justify-between p-2">
                            <a class="leading-3 text-xs text-colorlabel">{{ $item->model->name }}</a>
                            <div class="w-full flex flex-wrap items-start justify-between gap-2">
                                <h1 class="text-sm text-green-500">
                                    <small class="text-[10px] text-colorsubtitleform">IMPORTE :
                                        {{ $item->options->simbolo }}</small>
                                    {{ number_format($item->price * $item->qty, 2, '.', ',') }}
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
                                <div class="w-full flex-1">
                                    <button wire:click="decrement('{{ $item->rowId }}')" type="button"
                                        wire:loading.attr="disabled"
                                        class="font-medium hover:bg-neutral-400 hover:ring-2 hover:ring-neutral-300 text-xl w-9 h-9 bg-neutral-300 text-gray-500 p-2.5 pt-1.5 align-middle inline-flex items-center justify-center rounded-xl disabled:opacity-25 transition ease-in-out duration-150">-</button>
                                    <small class="font-medium text-xs px-2 text-colorlabel inline-block text-center">
                                        {{ $item->qty }} {{ $item->model->unit->name }}</small>
                                    <button wire:click="increment('{{ $item->rowId }}')" type="button"
                                        wire:loading.attr="disabled"
                                        class="font-medium hover:bg-neutral-400 hover:ring-2 hover:ring-neutral-300 text-xl w-9 h-9 bg-neutral-300 text-gray-500 p-2.5 pt-1.5 align-middle inline-flex items-center justify-center rounded-xl disabled:opacity-25 transition ease-in-out duration-150">+</button>
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
                                            class="w-full p-2 leading-3 hover:bg-fondohoverdropdown flex gap-1 items-center justify-between disabled:opacity-25">
                                            Mover a mi lista de deseos
                                        </button>
                                        <button type="button" @click="dropdownOpen = false"
                                            wire:click="deleteitem('{{ $item->rowId }}')" wire:loading.attr="disabled"
                                            class="w-full p-2 leading-3 hover:bg-fondohoverdropdown flex gap-1 items-center justify-between disabled:opacity-25">
                                            Eliminar de mi carrito
                                        </button>
                                    </div>
                                </div>

                                {{-- <x-button-delete @click="dropdownOpen = false"
                                    wire:click="deleteitem('{{ $item->rowId }}')" type="button"
                                    wire:loading.attr="disabled" class="w-8 h-8" /> --}}
                            </div>
                        </div>
                    </x-simple-card>
                @endforeach

                <div class="w-full flex justify-end">
                    <x-button-secondary wire:click="delete" wire:loading.attr="disabled"
                        class="items-center gap-1 !rounded-md !p-2">
                        LIMPIAR CARRITO
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M3 6h18" />
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                            <line x1="10" x2="10" y1="11" y2="17" />
                            <line x1="14" x2="14" y1="11" y2="17" />
                        </svg>
                    </x-button-secondary>
                </div>
            </div>

            <x-simple-card class="w-full shadow-md rounded xs:rounded-xl p-3">
                <h1 class="text-xs font-semibold text-colorlabel">RESUMEN DEL CARRITO</h1>

                <div class="w-full flex justify-between gap-2 mt-2">
                    <p class="text-xs font-semibold text-colorlabel">TOTAL : </p>
                    <p class="text-xl font-semibold text-colorlabel">
                        <small>{{ $moneda->simbolo }}</small>
                        {{ Cart::instance('shopping')->subtotal() }}
                        <small class="text-[10px]">{{ $moneda->currency }}</small>
                    </p>
                </div>
                <div class="w-full pt-3 flex items-center justify-center">
                    <x-link-button href="{{ route('carshoop.register') }}" class="!rounded-lg">
                        CONTINUAR COMPRA</x-link-button>
                </div>
            </x-simple-card>
        </div>
    @else
        {{-- <div class="w-full max-w-md flex-shrink bg-white shadow-xl rounded-xl"> --}}
        <h1 class="text-xs p-2 font-medium text-neutral-500">NO EXISTEN PRODUCTOS AGREGADOS EN EL CARRITO...</h1>
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
