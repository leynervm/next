<div class="py-5">
    @if (Cart::instance('wishlist')->count() > 0)
        <div class="w-full md:max-w-3xl mx-auto">
            <div class="w-full flex items-end justify-between border-b border-borderminicard">
                <h1 class="text-lg font-semibold text-primary">
                    MI LISTA DE DESEOS</h1>
                <x-button-secondary wire:click="delete" wire:loading.attr="disabled"
                    class="hidden sm:inline-flex items-center gap-1 mb-2">
                    ELIMINAR TODO</x-button-secondary>
            </div>

            <div class="w-full flex flex-col gap-3">
                @foreach (Cart::instance('wishlist')->content() as $item)
                    <div class="w-full flex flex-col xs:flex-row justify-between gap-2 text-xs sm:py-3">
                        <div class="w-full h-48 xs:w-24 xs:h-24 rounded overflow-hidden">
                            @if ($item->model->getImageURL())
                                <img src="{{ $item->model->getImageURL() }}" alt=""
                                    class="w-full h-full object-scale-down xs:object-cover xs:object-center">
                            @else
                                <x-icon-file-upload class="!w-full !h-full !m-0 !border-0 text-colorsubtitleform"
                                    type="unknown" />
                            @endif
                        </div>
                        <div class="w-full flex flex-col gap-1 flex-1 xs:h-full justify-between p-1 px-2">
                            <div class="w-full">
                                <p class="leading-3 text-xs text-colorlabel text-center xs:text-start">
                                    {{ $item->model->name }}</p>

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

                                <h1
                                    class="text-sm text-colorsubtitleform mt-2 font-semibold leading-3 text-center xs:text-start">
                                    <small class="text-[10px]">{{ $item->options->simbolo }}</small>
                                    {{ number_format($item->price, 2, '.', ', ') }}
                                </h1>
                            </div>

                            <div class="w-full flex-1 flex flex-col xs:flex-row gap-2 justify-between items-end">
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

                                <div class="w-full flex-1 flex gap-1 justify-end">
                                    <x-button-add-car type="button" wire:loading.attr="disabled"
                                        class="!rounded-xl px-3 flex-1 xs:flex-none !flex !justify-center !gap-1 items-center text-xs"
                                        wire:click="move_to_carshoop('{{ $item->rowId }}')"
                                        wire:key="add_{{ $item->rowId }}">AGREGAR AL CARRITO</x-button-add-car>
                                    <x-button-delete wire:click="deleteitem('{{ $item->rowId }}')" class="px-2"
                                        wire:loading.attr="disabled" wire:key="delete_{{ $item->rowId }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <h1 class="text-xs p-2 font-medium text-neutral-500">NO EXISTEN PRODUCTOS AGREGADOS EN LA LISTA DE DESEOS...
        </h1>
    @endif
</div>
