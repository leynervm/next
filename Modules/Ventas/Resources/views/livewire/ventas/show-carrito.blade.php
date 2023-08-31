<div>
    @if (count($carrito))
        <x-card-next titulo="Carrito compras" class="mt-3 border border-next-500 bg-transparent">
            <div class="flex gap-2 flex-wrap justify-start mt-1">
                @foreach ($carrito as $item)
                    <div
                        class="w-full bg-fondominicard flex flex-col justify-between sm:w-60 group rounded shadow shadow-shadowminicard p-1 text-xs relative hover:shadow-md hover:shadow-shadowminicard cursor-pointer">
                        <div class="w-full">

                            @if ($item->status == 0)
                                <span
                                    class="absolute top-2 left-1 text-[10px] font-semibold leading-3 p-1 rounded-r-lg bg-orange-500 text-white bg-opacity-80 group-hover:bg-opacity-100 transition-all ease-in-out duration-150">
                                    Pendiente Pago</span>
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
                                    S/. {{ number_format($item->total, 2) }}
                                </span>
                            </h1>

                            <div class="w-full flex gap-1 items-start mt-2">
                                <span
                                    class="text-[9px] font-semibold leading-3 p-1 bg-fondospancardproduct text-textspancardproduct rounded uppercase">
                                    P. UNIT:
                                    {{-- {{ $item->moneda->simbolo }} --}}
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



                        @if (count($item->carshoopseries))
                            <h1 class="w-full block text-[10px] mt-2">SERIES</h1>
                            <div class="w-full flex flex-wrap gap-1">
                                @foreach ($item->carshoopseries as $shoopserie)
                                    <span
                                        class="text-[8px] font-semibold rounded py-0.5 px-1 inline-flex gap-1 items-center bg-fondospancardproduct text-textspancardproduct">
                                        {{ $shoopserie->serie->serie }}
                                        <x-button-delete></x-button-delete>
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="w-full flex items-end gap-1 justify-end mt-2">
                            <x-button-delete wire:click="deleteitemcart({{ $item->id }})"
                                wire:loading.attr="disabled" wire:target="deleteitemcart"></x-button-delete>

                        </div>
                    </div>
                @endforeach
            </div>
        </x-card-next>
    @endif
</div>
